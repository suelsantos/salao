<?php

class Saida extends Globals {    
    
    public $erro;
    public $sucess;    
    public $cod;
    public $tipo;
    
    public function getListaSaida($filtro = null, $filtro2 = null, $data_ini = null, $data_fim = null, $order = null) {
        $and = "AND MONTH(A.data_vencimento) = MONTH(NOW()) AND YEAR(A.data_vencimento) = YEAR(NOW())";
        $and_status = "AND A.status IN(1,2)";
        
        if(!is_null($filtro)){
            if($filtro == "mes_anterior"){
                $and = "AND DATE_FORMAT(A.data_vencimento, '%Y-%m') = '".date('Y-m', strtotime('-1 months', strtotime(date('Y-m-d'))))."'";
            }elseif($filtro == "mes_atual"){
                $and = "AND MONTH(A.data_vencimento) = MONTH(NOW()) AND YEAR(A.data_vencimento) = YEAR(NOW())";
            }elseif($filtro == "mes_seguinte"){
                $and = "AND DATE_FORMAT(A.data_vencimento, '%Y-%m') = '".date('Y-m', strtotime('+1 months', strtotime(date('Y-m-d'))))."'";
            }elseif($filtro == "hoje"){
                $and = "AND A.data_vencimento = '".date('Y-m-d')."'";
            }elseif($filtro == "todos"){
                $and = "";
            }
        }
        
        if(!is_null($filtro2)){
            if($filtro2 == "todas"){
                $and_status = "AND A.status IN(1,2)";
            }elseif($filtro2 == "vencidas"){
                $and_status = "AND A.status IN(2) AND A.data_vencimento < CURRENT_DATE()";
            }elseif($filtro2 == "a_vencer"){
                $and_status = "AND A.status IN(2) AND A.data_vencimento >= CURRENT_DATE()";
            }elseif($filtro2 == "recebidas"){
                $and_status = "AND A.status IN(1)";
            }        
        }
        
        if((!is_null($data_ini)) && (!is_null($data_fim))){
            $and_periodo = "AND A.data_vencimento BETWEEN '{$data_ini}' AND '{$data_fim}'";
            $and = "";
        }
        
        $qry = montaQuery("finan_saida AS A
                LEFT JOIN finan_conta AS B ON(A.id_conta = B.id_conta)
                LEFT JOIN finan_tipo AS C ON(A.id_tipo = C.id_tipo)",
                "A.*, B.nome AS nome_conta, C.nome AS nome_tipo", 
                "A.id_unidade = {$_SESSION['id_unidade']} {$and_status} {$and} {$and_periodo}", $order, null, null, false);
        return $qry;
    }
    
    public static function getSaida($key, $value, $retorno = null) {
        $qry = montaQuery("finan_saida AS A",
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
    
    public function cadSaida() {
        foreach($_REQUEST['cadastro'] as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $_campos['nome'] = acentoMaiusculo($_campos['nome']);   
        $_campos['valor'] = valorBrtoUs($_campos['valor']);
        $_campos['data_vencimento'] = ($_campos['data_vencimento'] != "") ? converteData($_campos['data_vencimento']) : "";        
        $_campos['status'] = ($_campos['status'] == 1) ? 1 : 2;
        $_campos['observacoes'] = acentoMaiusculo($_campos['observacoes']);        
        
        $_campos['id_unidade'] = $_SESSION['id_unidade'];
        $_campos['user_cad'] = $_SESSION['id_usuario'];
        $_campos['data_cad'] = date('Y-m-d H:i:s');                  
        
        //print_array($_campos); exit();
        
        //DIVISÃO DE CAMPOS E VALORES
        foreach ($_campos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = $valor;
        }
        
        $insere = sqlInsert("finan_saida", $campos, $valores);
        
        if($insere){
            $id = mysql_insert_id();
            Globals::gravaLog($_campos['user_cad'], 22, null, "Cadastrou a saida {$_campos['nome']} | ID {$id}");
        }
        
        if($insere){
            $this->sucess = "Cadastrado com sucesso!";
            $this->tipo = $_REQUEST['_tipo'];
        }else{
            $this->erro = "Erro ao cadastrar, tente mais tarde";
        }
    }
    
    public function editSaida($id) {
        foreach($_REQUEST['cadastro'] as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $_campos['nome'] = acentoMaiusculo($_campos['nome']);   
        $_campos['valor'] = valorBrtoUs($_campos['valor']);
        $_campos['data_vencimento'] = ($_campos['data_vencimento'] != "") ? converteData($_campos['data_vencimento']) : "";        
        $_campos['status'] = ($_campos['status'] == 1) ? 1 : 2;
        $_campos['observacoes'] = acentoMaiusculo($_campos['observacoes']);
        
        $altera = sqlUpdate("finan_saida", $_campos, "id_saida = {$id}");
        
        if($altera){
            Globals::gravaLog($_SESSION['id_usuario'], 23, null, "Alterou a saida {$_campos['nome']} | ID: {$id}");                        
        }
        
        if($altera){
            $this->sucess = "Dados atualizados com sucesso!";
            $this->cod = $id;
            header("refresh: 3; saida_list.php");
        }else{
            $this->erro = "Erro ao atualizar dados, tente mais tarde";
            $this->cod = $id;
            header("refresh: 3; saida_list.php");
        }
    }
    
    public function excluiSaida($id) {
        $nome = Saida::getSaida("id_saida", $id, "nome");
        Globals::gravaLog($_SESSION['id_usuario'], 24, null, "Excluiu a saida {$nome} | ID: {$id}");
        
        $sql = sqlUpdate("finan_saida", "status = 0", "id_saida = {$id}", false);
        
        return $sql;
    }
    
    public function alteraStatusSaida($id, $tipo = null) {
        if($tipo == "estorno"){
            $acao = "Estornou";
            $status = 2;
            $local = 25;
            
        }elseif($tipo === "pago"){
            $acao = "Pagou";
            $status = 1;
            $local = 26;
        }
        
        $nome = Saida::getSaida("id_saida", $id, "nome");                
        
        Globals::gravaLog($_SESSION['id_usuario'], $local, null, "{$acao} a saida {$nome} | ID: {$id}");
        
        $sql = sqlUpdate("finan_saida", "status = {$status}", "id_saida = {$id}", false);
        
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