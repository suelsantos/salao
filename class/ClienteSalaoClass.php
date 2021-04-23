<?php

class ClienteSalao extends Globals {    
    
    public $erro;
    public $sucess;    
    public $cod;
    
    public function getListaCliente() {
        $qry = montaQuery("cliente AS A", 
                "A.*, DATE_FORMAT(A.data_nascimento, '%d/%m/%Y') AS data_nascimento", 
                "A.id_unidade = {$_SESSION['id_unidade']} AND A.status = 1", null, null, null, false);
        return $qry;
    }
    
    public static function getCliente($key, $value, $retorno = null) {
        $qry = montaQuery("cliente AS A",
                "A.*",
                "A.{$key} = '{$value}' AND A.status = 1", null, null, null, false);
        
        if(!is_null($retorno)){
            $res = mysql_fetch_assoc($qry);
            
            return $res[$retorno];
        }else{
            return $qry;
        }
    }    
    
    public function cadCliente() {
        foreach($_REQUEST['cadastro'] as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $_campos['nome'] = acentoMaiusculo($_campos['nome']);
        $_campos['apelido'] = acentoMaiusculo($_campos['apelido']);
        $_campos['data_nascimento'] = (!empty($_campos['data_nascimento'])) ? converteData($_campos['data_nascimento']) : "";
        $_campos['celular'] = removeMascara($_campos['celular']);
        $_campos['whatsapp'] = removeMascara($_campos['whatsapp']);
        $_campos['telefone'] = removeMascara($_campos['telefone']);
        $_campos['cep'] = removeMascara($_campos['cep']);
        $_campos['logradouro'] = acentoMaiusculo($_campos['logradouro']);
        $_campos['bairro'] = acentoMaiusculo($_campos['bairro']);
        $_campos['cidade'] = acentoMaiusculo($_campos['cidade']);
        $_campos['uf'] = getDB("uf", "uf_sigla = '{$_campos['uf']}'", "uf_id");
        $_campos['complemento'] = acentoMaiusculo($_campos['complemento']);
        $_campos['obs'] = acentoMaiusculo($_campos['obs']);
        
        $_campos['id_unidade'] = $_SESSION['id_unidade'];
        $_campos['user_cad'] = $_SESSION['id_usuario'];
        $_campos['data_cad'] = date('Y-m-d H:i:s');
        
        //print_array($_campos);
        
        //DIVISÃO DE CAMPOS E VALORES
        foreach ($_campos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = $valor;
        }
        
        $insere = sqlInsert("cliente", $campos, $valores);
        
        if($insere){
            $id = mysql_insert_id();
            Globals::gravaLog($_campos['user_cad'], 46, null, "Cadastrou o cliente {$_campos['nome']} ID: {$insere}");
            
            //UPLOAD IMAGEM
            $foto_data =  $_REQUEST['crop_string'];
            $foto_type = $_REQUEST['crop_type'];
            
            if($foto_data != ''){
                Globals::cadFile($foto_type, 8, $id);
                Globals::fotoUpload($foto_data, $foto_type, $id, "../", "clientes");
            }
        }
        
        if($insere){
            $this->sucess = "Cadastrado com sucesso!";
        }else{
            $this->erro = "Erro ao cadastrar, tente mais tarde";
        }
    }
    
    public function editCliente($id) {
        foreach($_REQUEST['cadastro'] as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $_campos['nome'] = acentoMaiusculo($_campos['nome']);
        $_campos['apelido'] = acentoMaiusculo($_campos['apelido']);
        $_campos['data_nascimento'] = (!empty($_campos['data_nascimento'])) ? converteData($_campos['data_nascimento']) : "";      
        $_campos['celular'] = removeMascara($_campos['celular']);
        $_campos['whatsapp'] = removeMascara($_campos['whatsapp']);
        $_campos['telefone'] = removeMascara($_campos['telefone']);
        $_campos['cep'] = removeMascara($_campos['cep']);
        $_campos['logradouro'] = acentoMaiusculo($_campos['logradouro']);
        $_campos['bairro'] = acentoMaiusculo($_campos['bairro']);
        $_campos['cidade'] = acentoMaiusculo($_campos['cidade']);
        $_campos['uf'] = getDB("uf", "uf_sigla = '{$_campos['uf']}'", "uf_id");
        $_campos['complemento'] = acentoMaiusculo($_campos['complemento']);
        $_campos['obs'] = acentoMaiusculo($_campos['obs']);
        
        $_campos['id_unidade'] = $_SESSION['id_unidade'];
        $_campos['user_alter'] = $_SESSION['id_usuario'];
        $_campos['data_alter'] = date('Y-m-d H:i:s');                
        
        //print_array($_campos);                      
        
        $altera = sqlUpdate("cliente", $_campos, "id_cliente = {$id}");
        
        if($altera){
            Globals::gravaLog($_SESSION['id_usuario'], 47, null, "Alterou o cliente {$_campos['nome']} | ID: {$id}");
            
            //UPLOAD IMAGEM
            $foto_data =  $_REQUEST['crop_string'];
            $foto_type = $_REQUEST['crop_type'];
            
            if($foto_data != ''){
                $verifica_file = Globals::getFileGlobal(8, $id);
                $total_file = mysql_num_rows($verifica_file);
                
                if($total_file == 0){
                    Globals::cadFile($foto_type, 8, $id);
                }
                
                Globals::fotoUpload($foto_data, $foto_type, $id, "../", "clientes");
            }
        }
        
        if($altera){
            $this->sucess = "Dados atualizados com sucesso!";
            $this->cod = $id;
            header("refresh: 3; cliente_list.php");
        }else{
            $this->erro = "Erro ao atualizar dados, tente mais tarde";
            $this->cod = $id;
            header("refresh: 3; cliente_list.php");
        }
    }
    
    public function excluiCliente($id) {
        $nome = ClienteSalao::getCliente("id_cliente", $id, "nome");
        Globals::gravaLog($_SESSION['id_usuario'], 45, null, "Excluiu o cliente {$nome} | ID: {$id}");
        
        $sql = sqlUpdate("cliente", "status = 0", "id_cliente = {$id}", false);                
        
        return $sql;
    }        
    
    public function getErro() {
        return $this->erro;
    }
    
    public function setErro($erro) {
        $this->erro = $erro;
    }
    
    public function getSucess() {
        return $this->sucess;
    }
    
    public function setSucess($sucess) {
        $this->sucess = $sucess;
    }
    
}

?>