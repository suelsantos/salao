<?php

class Agenda extends Globals {
    
    public $erro;
    public $sucess;
    
    public static function getAgenda($retorno = null, $id = null) {
        
        if(!is_null($id)){
            $and_id = "AND A.id = {$id}";
        }
        
        $qry = montaQuery("agenda AS A
                LEFT JOIN profissional AS B ON(A.id_profissional = B.id_profissional)
                LEFT JOIN cliente AS C ON(A.id_cliente = C.id_cliente)
                LEFT JOIN servico AS D ON(A.id_servico = D.id_servico)",
                "A.id, A.id_profissional, A.id_cliente, A.id_servico, DATE_FORMAT(A.start, '%Y-%m-%dT%H:%i:%s') AS start, DATE_FORMAT(A.end, '%Y-%m-%dT%H:%i:%s') AS end, DATE_FORMAT(A.`start`, '%H:%i') AS hora_ini, DATE_FORMAT(A.`end`, '%H:%i') AS hora_fim, B.apelido AS nome_profissional, C.nome AS nome_cliente, D.nome AS nome_servico",
                "A.status = 1 {$and_id} AND A.id_unidade = {$_SESSION['id_unidade']}", "B.apelido, A.`start`", null, null, false);
        
        if(!is_null($retorno)){
            $res = mysql_fetch_assoc($qry);
            
            return $res[$retorno];
        }else{
            return $qry;
        }
    }
    
    public static function getProfissional($id = null, $list = null) {
        if(!is_null($id)){
            $and = "AND A.id_profissional = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("profissional AS A", "A.id_profissional, A.apelido, A.nome", "A.status = 1 {$and} AND A.id_unidade = {$_SESSION['id_unidade']}", "A.apelido", null, null, false); 
        
        if(!is_null($list)){
            return $sql;
        }else{
            $profissional = array("" => "Selecione um Profissional");
            
            while ($rst = mysql_fetch_assoc($sql)) {
                $profissional[$rst['id_profissional']] = $rst['apelido'];
            }
            
            if(!is_null($id)){
                return $profissional[$id];
            }else{
                return $profissional;
            }
        }
    }
    
    public static function getCliente($id = null, $list = null) {
        if(!is_null($id)){
            $and = "AND A.id_cliente = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("cliente AS A", "A.id_cliente, A.nome", "A.status = 1 {$and} AND A.id_unidade = {$_SESSION['id_unidade']}", "A.nome", null, null, false); 
        
        if(!is_null($list)){
            return $sql;
        }else{
            $cliente = array("" => "Selecione um Cliente");
            
            while ($rst = mysql_fetch_assoc($sql)) {
                $cliente[$rst['id_cliente']] = $rst['nome'];
            }
            
            if(!is_null($id)){
                return $cliente[$id];
            }else{
                return $cliente;
            }
        }
    }
    
    public static function getServico($id = null, $list = null) {
        if(!is_null($id)){
            $and = "AND A.id_servico = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("servico AS A", "A.id_servico, A.nome", "A.status = 1 {$and}", "A.nome", null, null, false); 
        
        if(!is_null($list)){
            return $sql;
        }else{
            //$servico = array("" => "Selecione um Serviço");
            
            while ($rst = mysql_fetch_assoc($sql)) {
                $servico[$rst['id_servico']] = $rst['nome'];
            }
            
            if(!is_null($id)){
                return $servico[$id];
            }else{
                return $servico;
            }
        }
    }
    
    public static function getServicoAssoc($id_agenda = null, $list = null) { 
        if(!is_null($id_agenda)){
            $and = "A.id_agenda = {$id_agenda}";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("agenda_servico_assoc AS A
            LEFT JOIN servico AS B ON(A.id_servico = B.id_servico)", 
            "B.nome, B.id_servico", $and, "B.nome", null, null, false);                 
        return $sql;        
    }
    
    public function cadAgenda($campos_cad) {
        foreach($campos_cad as $indice => $valor){
            $_campos[$indice] = $valor;
        }                
        
        //DIVISÃO DE CAMPOS E VALORES
        foreach ($_campos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = $valor;
        }
        
        $insere = sqlInsert("agenda", $campos, $valores);
        
        if($insere){
            Globals::gravaLog($_SESSION['id_usuario'], 42, null, "Cadastrou a agenda ID: {$insere}");
        }
        
        return $insere;
    }
    
    public function editAgenda($id, $campos_edit) {
        foreach($campos_edit as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $altera = sqlUpdate("agenda", $_campos, "id = {$id}");
        
        if($altera){
            Globals::gravaLog($_SESSION['id_usuario'], 44, null, "Alterou a agenda ID: {$id}");
        }
        
        return $altera;
    }
    
    public function excluiAgenda($id_agenda) {
        Globals::gravaLog($_SESSION['id_usuario'], 43, null, "Excluiu a agenda ID: {$id_agenda}");
        
        $sql = sqlUpdate("agenda", "status = 0", "id = {$id_agenda}", false);
        
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