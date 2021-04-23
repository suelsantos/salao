<?php

class Financeiro extends Globals {    
    
    public $erro;
    public $sucess;    
    public $cod;
    
    //TRAZER TIPO DE ENTRADA/SAIDA
    public static function getCategoria($id = null, $tipo = null, $list = null) {
        if(!is_null($id)){
            $and = "AND id_tipo = '{$id}'";
        }else{
            $and = null;
        }
        
        if(!is_null($tipo)){
            $tipo_ = $tipo;
        }else{
            $tipo_ = "1,2";
        }
        
        $sql = montaQuery("finan_tipo", "*", "tipo IN({$tipo_}) {$and}", "nome", null, null, false);
        
        if(!is_null($list)){
            return $sql;
            
        }else{        
            $cat = array("" => "Selecione uma Categoria");
            
            while ($rst = mysql_fetch_assoc($sql)) {
                $cat[$rst['id_tipo']] = $rst['nome'];
            }
            
            if(!is_null($id)){
                return $cat[$id];
            }else{
                return $cat;
            }
        }
    }
    
    //TRAZER CONTA BANCÁRIA
    public static function getConta($id = null, $list = null) {
        if(!is_null($id)){
            $and = "AND id_conta = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("finan_conta", "*", $and, "nome", null, null, false);
        
        if(!is_null($list)){
            return $sql;
            
        }else{
            $cat = array("" => "Selecione uma Conta");
            
            while ($rst = mysql_fetch_assoc($sql)) {
                $cat[$rst['id_conta']] = $rst['nome'];
            }
            
            if(!is_null($id)){
                return $cat[$id];
            }else{
                return $cat;
            }
        }
    }
    
    public static function getCategoriaF($key, $value, $retorno = null) {
        $qry = montaQuery("finan_tipo AS A",
                "A.*",
                "A.{$key} = '{$value}'", null, null, null, false);
        
        if(!is_null($retorno)){
            $res = mysql_fetch_assoc($qry);
            
            return $res[$retorno];
        }else{
            return $qry;
        }
    }
    
    public static function getContaF($key, $value, $retorno = null) {
        $qry = montaQuery("finan_conta AS A",
                "A.*",
                "A.{$key} = '{$value}'", null, null, null, false);
        
        if(!is_null($retorno)){
            $res = mysql_fetch_assoc($qry);
            
            return $res[$retorno];
        }else{
            return $qry;
        }
    }        
    
    // verifica se existe categoria cadastrada
    public function verificaCategoriaExist($nome, $tipo = null) {
        if(!is_null($tipo)){
            $and_tipo = "AND tipo = {$tipo}";
        }
        
        $qry = montaQuery("finan_tipo", "*", "nome = '{$nome}' {$and_tipo}", null, null, null);
        return $qry;
    }
    
    // verifica se existe entrada/saida vinculada a categoria
    public function verificaVinculoCategoria($id, $tipo) {
        if($tipo == 1){
            $rel = "entrada";            
        }elseif($tipo == 2){
            $rel = "saida";
        }
        
        $qry = montaQuery("finan_{$rel}", "*", "id_tipo = '{$id}'", null, null, null);
        $tot = mysql_num_rows($qry);
        
        return $tot;
    }
    
    // verifica se existe entrada/saida vinculada a conta
    public function verificaVinculoConta($id) {
        $qry_ent = montaQuery("finan_entrada", "*", "id_conta = '{$id}'", null, null, null);
        $tot_ent = mysql_num_rows($qry_ent);
        
        $qry_sai = montaQuery("finan_saida", "*", "id_conta = '{$id}'", null, null, null);
        $tot_sai = mysql_num_rows($qry_sai);
        
        $total = $tot_ent + $tot_sai;
        
        return $total;
    }
    
    // verifica se existe conta padrão de dízimos
    public function verificaContaPadraoDizimo() {
        $qry = montaQuery("finan_conta", "*", "padrao_dizimo = 1", null, null, null, false);
        return $qry;
    }
    
    public function cadCategoria($campos_cad) {
        foreach($campos_cad as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        //print_array($_campos); exit();
        
        //DIVISÃO DE CAMPOS E VALORES
        foreach ($_campos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = $valor;
        }
        
        $insere = sqlInsert("finan_tipo", $campos, $valores);
        
        if($insere){
            $id = mysql_insert_id();
            Globals::gravaLog($_SESSION['id_usuario'], 27, null, "Cadastrou a categoria {$_campos['nome']} | ID: {$id}");
        }
        
        return $insere;
    }
    
    public function cadConta($campos_cad) {
        foreach($campos_cad as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        //print_array($_campos); exit();
        
        //DIVISÃO DE CAMPOS E VALORES
        foreach ($_campos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = $valor;
        }
        
        $insere = sqlInsert("finan_conta", $campos, $valores);
        
        if($insere){
            $id = mysql_insert_id();
            Globals::gravaLog($_SESSION['id_usuario'], 30, null, "Cadastrou a conta bancária {$_campos['nome']} | {$id}");
        }
        
        return $insere;
    }
    
    public function editCategoria($id, $campos_edit) {
        foreach($campos_edit as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $altera = sqlUpdate("finan_tipo", $_campos, "id_tipo = {$id}");
        
        if($altera){
            Globals::gravaLog($_SESSION['id_usuario'], 28, null, "Alterou a categoria {$_campos['nome']} | ID: {$id}");
        }
        
        return $altera;
    }
    
    public function editConta($id, $campos_edit) {
        foreach($campos_edit as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $altera = sqlUpdate("finan_conta", $_campos, "id_conta = {$id}");
        
        if($altera){
            Globals::gravaLog($_SESSION['id_usuario'], 31, null, "Alterou a conta bancária {$_campos['nome']} | ID: {$id}");
        }
        
        return $altera;
    }
    
    public function excluiCategoria($id) {
        $nome = Financeiro::getCategoriaF("id_tipo", $id, "nome");
        Globals::gravaLog($_SESSION['id_usuario'], 29, null, "Excluiu a categoria {$nome} | ID: {$id}");
        
        $sql = sqlDelete("finan_tipo", "id_tipo = {$id}", false);
        
        return $sql;
    }
    
    public function excluiConta($id) {
        $nome = Financeiro::getContaF("id_tipo", $id, "nome");
        Globals::gravaLog($_SESSION['id_usuario'], 32, null, "Excluiu a conta bancária {$nome} | ID: {$id}");
        
        $sql = sqlDelete("finan_conta", "id_conta = {$id}", false);
        
        return $sql;
    }
    
    public function getExtrato($search = null) {
        $sql_saida = '';
        $sql_entrada = '';
        
        $data_ini = converteData($search['data_ini']);
        $data_fim = converteData($search['data_fim']);
        
        $union = ($search['tipo'] == '-1') ? 'UNION' : '';
        
        if(($search['tipo'] == 2) || ($search['tipo'] == '-1')){
            $and_saida = "";
            
            if($search['id_unidade'] != '-1'){
                $and_saida .= "AND A.id_unidade = {$search['id_unidade']} ";
            }
            
            if($search['id_conta'] != ''){
                $and_saida .= "AND A.id_conta = {$search['id_conta']} ";
            }
            
            if($search['tipo_pgt'] != '-1'){
                $and_saida .= "AND A.tipo_pgt = {$search['tipo_pgt']} ";
            }
            
            $sql_saida = "
                SELECT 	
                    DATE_FORMAT(A.data_vencimento, '%d/%m/%Y') AS 'data',
                    'saida' AS tipo,
                    A.nome,
                    C.nome AS categoria,
                    A.valor,
                    E.descricao AS tipo_pgto,
                    G.nome AS conta
                FROM finan_saida AS A
                    LEFT JOIN finan_tipo AS C ON(A.id_tipo = C.id_tipo)
                    LEFT JOIN finan_tipo_pgto AS E ON(A.tipo_pgt = E.id_tipo_pgto)
                    LEFT JOIN finan_conta AS G ON(A.id_conta = G.id_conta)
                WHERE                    
                    A.status = 1
                    AND A.data_vencimento BETWEEN '{$data_ini}' AND '{$data_fim}'
                    {$and_saida}
            ";
        }
        
        if(($search['tipo'] == 1) || ($search['tipo'] == '-1')){
            $and_entrada = "";
            
            if($search['id_unidade'] != '-1'){
                $and_entrada .= "AND B.id_unidade = {$search['id_unidade']} ";
            }
            
            if($search['id_conta'] != ''){
                $and_entrada .= "AND B.id_conta = {$search['id_conta']} ";
            }
            
            if($search['id_servico'] != ''){
                $and_entrada .= "AND B.id_servico = {$search['id_servico']} ";
            }
            
            if($search['id_profissional'] != ''){
                $and_entrada .= "AND B.id_profissional = {$search['id_profissional']} ";
            }
            
            if($search['id_cliente'] != ''){
                $and_entrada .= "AND B.id_cliente = {$search['id_cliente']} ";
            }
            
            if($search['tipo_pgt'] != '-1'){
                $and_entrada .= "AND B.tipo_pgt = {$search['tipo_pgt']} ";
            }
            
            $sql_entrada = "
                SELECT 	
                    DATE_FORMAT(B.data_credito, '%d/%m/%Y') AS 'data',
                    'entrada' AS tipo,
                    B.nome,
                    D.nome AS categoria,
                    B.valor,
                    F.descricao AS tipo_pgto,
                    H.nome AS conta
                FROM finan_entrada AS B
                    LEFT JOIN finan_tipo AS D ON(B.id_tipo = D.id_tipo)
                    LEFT JOIN finan_tipo_pgto AS F ON(B.tipo_pgt = F.id_tipo_pgto)
                    LEFT JOIN finan_conta AS H ON(B.id_conta = H.id_conta)
                WHERE
                    B.status = 1
                    AND B.data_credito BETWEEN '{$data_ini}' AND '{$data_fim}'
                    {$and_entrada}                                                                                
            ";
        }
        
        $sql = "
            {$sql_entrada}
            {$union}
            {$sql_saida}
            ORDER BY data
        ";
        
        $qry = mysql_query($sql);
                
        $result = array();
        
        while ($rst = mysql_fetch_assoc($qry)) {
            $result[] = $rst;
        }
        
        return $result;
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