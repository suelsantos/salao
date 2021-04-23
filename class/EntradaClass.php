<?php

class Entrada extends Globals {    
    
    public $erro;
    public $sucess;    
    public $cod;
    public $tipo;
    
    public function getListaEntrada($filtro = null, $filtro2 = null) {
        $and = "AND MONTH(A.data_credito) = MONTH(NOW()) AND YEAR(A.data_credito) = YEAR(NOW())";
        $and_status = "AND A.status IN(1,2)";
        
        if(!is_null($filtro)){
            if($filtro == "mes_anterior"){
                $and = "AND DATE_FORMAT(A.data_credito, '%Y-%m') = '".date('Y-m', strtotime('-1 months', strtotime(date('Y-m-d'))))."'";
            }elseif($filtro == "mes_atual"){
                $and = "AND MONTH(A.data_credito) = MONTH(NOW()) AND YEAR(A.data_credito) = YEAR(NOW())";
            }elseif($filtro == "mes_seguinte"){
                $and = "AND DATE_FORMAT(A.data_credito, '%Y-%m') = '".date('Y-m', strtotime('+1 months', strtotime(date('Y-m-d'))))."'";
            }elseif($filtro == "hoje"){
                $and = "AND A.data_credito = '".date('Y-m-d')."'";
            }elseif($filtro == "todos"){
                $and = "";
            }        
        }
        
        if(!is_null($filtro2)){
            if($filtro2 == "todas"){
                $and_status = "AND A.status IN(1,2)";
            }elseif($filtro2 == "vencidas"){
                $and_status = "AND A.status IN(2) AND A.data_credito < CURRENT_DATE()";
            }elseif($filtro2 == "a_vencer"){
                $and_status = "AND A.status IN(2) AND A.data_credito >= CURRENT_DATE()";
            }elseif($filtro2 == "recebidas"){
                $and_status = "AND A.status IN(1)";
            }        
        }
        
        $qry = montaQuery("finan_entrada AS A
                LEFT JOIN finan_conta AS B ON(A.id_conta = B.id_conta)
                LEFT JOIN finan_tipo AS C ON(A.id_tipo = C.id_tipo)",
                "A.*, B.nome AS nome_conta, C.nome AS nome_tipo", 
                "A.id_unidade = {$_SESSION['id_unidade']} {$and_status} {$and}", null, null, null, false);
        return $qry;
    }
    
    public static function getEntrada($key, $value, $retorno = null) {
        $qry = montaQuery("finan_entrada AS A",
                "A.*",
                "A.{$key} = '{$value}'", null, null, null, false);
        
        if(!is_null($retorno)){
            $res = mysql_fetch_assoc($qry);
            
            return $res[$retorno];
        }else{
            return $qry;
        }
    }
        
    public static function getSituacao($id = null) {
        if(!is_null($id)){
            $and = "id_situacao = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("cidadao_situacao", "*", $and, null, null, null, false);                
        
        while ($rst = mysql_fetch_assoc($sql)) {
            $situacao[$rst['id_situacao']] = $rst['nome'];
        }
        
        return $situacao;                
    }
    
    public function cadEntrada() {
        foreach($_REQUEST['cadastro'] as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $_campos['nome'] = acentoMaiusculo($_campos['nome']);   
        $_campos['valor'] = valorBrtoUs($_campos['valor']);
        $_campos['data_credito'] = ($_campos['data_credito'] != "") ? converteData($_campos['data_credito']) : "";        
        $_campos['status'] = ($_campos['status'] == 1) ? 1 : 2;
        $_campos['observacoes'] = acentoMaiusculo($_campos['observacoes']);        
        
        $_campos['id_unidade'] = $_SESSION['id_unidade'];
        $_campos['user_cad'] = $_SESSION['id_usuario'];
        $_campos['data_cad'] = date('Y-m-d H:i:s');                  
        
//        print_array($_campos); exit();
        
        //DIVISÃO DE CAMPOS E VALORES
        foreach ($_campos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = $valor;
        }
        
        $insere = sqlInsert("finan_entrada", $campos, $valores);
        
        if($insere){
            $id = mysql_insert_id();
            
//            $dados_cidadao = montaQuery("cidadao", "nome", "id_cidadao = {$_campos['id_cidadao']}", null, null, "array");
//            $nome_cidadao = $dados_cidadao[1]['nome'];
//            
//            if($_campos['id_tipo'] == 1){
//                $nome_entrada = "DÍZIMO ({$nome_cidadao})";
//            }else{
                $nome_entrada = $_campos['nome'];
//            }
            
            Globals::gravaLog($_campos['user_cad'], 17, null, "Cadastrou a entrada {$nome_entrada} | ID {$id}");
        }
        
        if($insere){
            $this->sucess = "Cadastrado com sucesso!";
            $this->tipo = $_REQUEST['_tipo'];
        }else{
            $this->erro = "Erro ao cadastrar, tente mais tarde";
        }
    }
    
    public function editEntrada($id) {
        foreach($_REQUEST['cadastro'] as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $_campos['nome'] = acentoMaiusculo($_campos['nome']);   
        $_campos['valor'] = valorBrtoUs($_campos['valor']);
        $_campos['data_credito'] = ($_campos['data_credito'] != "") ? converteData($_campos['data_credito']) : "";        
        $_campos['status'] = ($_campos['status'] == 1) ? 1 : 2;
        $_campos['observacoes'] = acentoMaiusculo($_campos['observacoes']);
        
        $altera = sqlUpdate("finan_entrada", $_campos, "id_entrada = {$id}");
        
        if($altera){
//            $dados_cidadao = montaQuery("cidadao", "nome", "id_cidadao = {$_campos['id_cidadao']}", null, null, "array");
//            $nome_cidadao = $dados_cidadao[1]['nome'];
//            
//            if($_campos['id_tipo'] == 1){
//                $nome_entrada = "DÍZIMO ({$nome_cidadao})";
//            }else{
                $nome_entrada = $_campos['nome'];
//            }
            
            Globals::gravaLog($_SESSION['id_usuario'], 18, null, "Alterou a entrada {$nome_entrada} | ID: {$id}");                        
        }
        
        if($altera){
            $this->sucess = "Dados atualizados com sucesso!";
            $this->cod = $id;
            header("refresh: 3; entrada_list.php");
        }else{
            $this->erro = "Erro ao atualizar dados, tente mais tarde";
            $this->cod = $id;
            header("refresh: 3; entrada_list.php");
        }
    }
    
    public function excluiEntrada($id) {
        $qry_entrada = Entrada::getEntrada("id_entrada", $id);
        $res_entrada = mysql_fetch_assoc($qry_entrada) or die(mysql_error());
        
//        $nome_cidadao = getDB("cidadao", "id_cidadao = {$res_entrada['id_cidadao']}", "nome");
        
//        if($res_entrada['id_tipo'] == 1){
//            $nome_entrada = "DÍZIMO ({$nome_cidadao})";
//        }else{
            $nome_entrada = $res_entrada['nome'];
//        }
        
        Globals::gravaLog($_SESSION['id_usuario'], 19, null, "Excluiu a entrada {$nome_entrada} | ID: {$id}");
        
        $sql = sqlUpdate("finan_entrada", "status = 0", "id_entrada = {$id}", false);
        
        return $sql;
    }
    
    public function alteraStatusEntrada($id, $tipo = null) {
        if($tipo == "estorno"){
            $acao = "Estornou";
            $status = 2;
            $local = 20;
            
        }elseif($tipo === "recebe"){
            $acao = "Recebeu";
            $status = 1;
            $local = 21;
        }
        
        $qry_entrada = Entrada::getEntrada("id_entrada", $id);
        $res_entrada = mysql_fetch_assoc($qry_entrada) or die(mysql_error());
        
//        $nome_cidadao = getDB("cidadao", "id_cidadao = {$res_entrada['id_cidadao']}", "nome");
        
//        if($res_entrada['id_tipo'] == 1){
//            $nome_entrada = "DÍZIMO ({$nome_cidadao})";
//        }else{
            $nome_entrada = $res_entrada['nome'];
//        }
        
        Globals::gravaLog($_SESSION['id_usuario'], $local, null, "{$acao} a entrada {$nome_entrada} | ID: {$id}");
        
        $sql = sqlUpdate("finan_entrada", "status = {$status}", "id_entrada = {$id}", false);
        
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