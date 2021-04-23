<?php

class Globals {                
    
    /**
     * MÉTODO PARA EXIBIR MSG DO BOOTSTRAP
     * @param $tipo
     * @param $mensagem
     * @param $id
     * @return string
     */
    public static function getResposta($tipo, $mensagem, $id){
        $div = "";
        
        if($tipo != "" && $mensagem != ""){
            $div .= "<div class='alert alert-dismissable alert-{$tipo}' id='{$id}'>";
            $div .= "<button type='button' class='close' data-dismiss='alert'>×</button>";
            $div .= "<p>{$mensagem}</p>";
            $div .= "</div>";
        }                
        
        session_destroy();
        
        return $div;
    }
    
    /**
     * 
     * @param $usuario
     * @param $local
     * @param $acao
     * @param $acao_complementar
     */
    public static function gravaLog($usuario, $id_local, $id_acao = null, $acao_complementar = null) {
        
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = date('Y-m-d H:i:s');
        
        sqlInsert("log", "id_usuario, id_unidade, id_local, data_log, ip, id_acao, acao_complementar, status", 
            "{$usuario}, {$_SESSION['id_unidade']}, {$id_local}, '{$data}', '{$ip}', '{$id_acao}', '{$acao_complementar}', 1") or die("Erro gravaLog");
    }        
    
    /* 
    * 1 BASICO
    * Principal e Site
    * 
    * 2 ADM
    * Principal, Administração, Site e Sistema
    * 
    * 3 SECRETARIA
    * Principal e Administração
    */
    public static function gravaPermissaoBotao($perfil, $id_usuario) {                                
        if($perfil == 1){
            $botoes_menu = '1, 3';
        }elseif($perfil == 2){
            $botoes_menu = '1, 2, 3, 4';
        }elseif($perfil == 3){
            $botoes_menu = '1, 2';
        }
        
        $sql = montaQuery("botoes AS A", "A.botoes_id", "A.botoes_menu IN({$botoes_menu})", null, null, null, true);
        
        while($res = mysql_fetch_assoc($sql)){
            sqlInsert("botoes_assoc", "botoes_id, id_usuario", "{$res['botoes_id']}, {$id_usuario}") or die("Erro gravaPermissao");
        }
    }
       
    public static function deletaPermissaoBotao($id_usuario) {
        $sql = "DELETE FROM botoes_assoc WHERE id_usuario = {$id_usuario}";
        $qry = mysql_query($sql) or die("ERRO deletaPermissaoBotao");
        
        return $qry;
    }
    
    //colocar pra pegar pela unidade que será marcada na tela de cadastro
    public static function gravaPermissaoUnidade($id_usuario) {
        sqlInsert("unidade_assoc", "id_unidade, id_usuario", "1, {$id_usuario}") or die("Erro gravaPermissaoUnidade");
    }
    
    public function getUnidade($id_unidade = null) {
        $sql = montaQuery("unidade", "*", "id_unidade = {$id_unidade}", null, null, null, false);
        $row = mysql_fetch_assoc($sql);
        
        return $row;
    }
    
    /*
     * TRAZER ID DAS EXTENSÕES NO BD
     */
    public static function getExtensaoFile($nome_extensao) {
        $sql = montaQuery("file_extensao", "*", "nome = '{$nome_extensao}'", null, null, null, false);
        $row = mysql_fetch_assoc($sql);
        
        return $row;
    }
    
    //TRAZER ESTADOS DO BRASIL
    public static function getUF($sigla = null, $value = 'uf_id') {
        if(!is_null($sigla)){
            $and = "uf_sigla = '{$sigla}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("uf", "*", $and, null, null, null, false);
                
        $uf = array("" => "Selecione um Estado");
        
        while ($rst = mysql_fetch_assoc($sql)) {
            $uf[$rst[$value]] = $rst['uf_sigla'];
        }
        
        return $uf;                
    }
    
    //TRAZER PROFISSÕES
    public static function getProfissao($id = null, $list = null) {
        if(!is_null($id)){
            $and = "id_profissao = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("profissao", "*", $and, "nome", null, null, false);   
        
        if(!is_null($list)){
            return $sql;
        }else{
            $profissao = array("" => "Selecione uma Profissão");        

            while ($rst = mysql_fetch_assoc($sql)) {
                $profissao[$rst['id_profissao']] = $rst['nome'];
            }

            if(!is_null($id)){
                return $profissao[$id];
            }else{
                return $profissao;
            }
        }
    }
    
    //TRAZER CARGOS
    public static function getCargo($id = null, $list = null) {
        if(!is_null($id)){
            $and = "id_cargo = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("cargo", "*", $and, "nome", null, null, false); 
        
        if(!is_null($list)){
            return $sql;
        }else{
            $cargo = array("" => "Selecione um Cargo");
            
            while ($rst = mysql_fetch_assoc($sql)) {
                $cargo[$rst['id_cargo']] = $rst['nome'];
            }
            
            if(!is_null($id)){
                return $cargo[$id];
            }else{
                return $cargo;
            }
        }
    }
    
    //TRAZER FUNÇÕES
    public static function getFuncao($id = null, $list = null) {
        if(!is_null($id)){
            $and = "id_funcao = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("funcao", "*", $and, "nome", null, null, false);   
        
        if(!is_null($list)){
            return $sql;
        }else{
            $funcao = array("" => "Selecione uma Função");        

            while ($rst = mysql_fetch_assoc($sql)) {
                $funcao[$rst['id_funcao']] = $rst['nome'];
            }

            if(!is_null($id)){
                return $funcao[$id];
            }else{
                return $funcao;
            }
        }
    }
    
    //TRAZER SERVIÇOS
    public static function getServicos($id = null, $list = null) {
        if(!is_null($id)){
            $and = "id_servico = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("servico", "*", $and, "nome", null, null, false);   
        
        if(!is_null($list)){
            return $sql;
        }else{
            $servico = array("" => "Selecione um Serviço");        

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
    
    //TRAZER TIPO DE ENTRADA/SAIDA
    public static function getCategoriaPgt($id = null, $tipo = null, $select = null) {
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
        
        $select_default = (!is_null($select)) ? $select : "Selecione uma Categoria";
        
        $cat = array("" => $select_default);
        
        while ($rst = mysql_fetch_assoc($sql)) {
            $cat[$rst['id_tipo']] = $rst['nome'];
        }
        
        if(!is_null($id)){
            return $cat[$id];
        }else{
            return $cat;
        }
    }
    
    //TRAZER CONTA
    public static function getContaBancaria($id = null, $select = null) {
        if(!is_null($id)){
            $and = "id_conta = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("finan_conta", "*", $and, "nome", null, null, false);
        
        $select_default = (!is_null($select)) ? $select : "Selecione uma Conta";
        
        $conta = array("" => $select_default);
        
        while ($rst = mysql_fetch_assoc($sql)) {
            $conta[$rst['id_conta']] = $rst['nome'];
        }
        
        if(!is_null($id)){
            return $conta[$id];
        }else{
            return $conta;
        }
    }
        
    public static function getServico($id = null, $select = null) {
        if(!is_null($id)){
            $and = "id_servico = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("servico", "*", $and, "nome", null, null, false);
        
        $select_default = (!is_null($select)) ? $select : "Selecione um Serviço";
        
        $servico = array("" => $select_default);
        
        while ($rst = mysql_fetch_assoc($sql)) {
            $servico[$rst['id_servico']] = $rst['nome'];
        }
        
        if(!is_null($id)){
            return $servico[$id];
        }else{
            return $servico;
        }
    }
    
    public static function getProfissional($id = null, $select = null) {
        if(!is_null($id)){
            $and = "id_profissional = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("profissional", "*", $and, "nome", null, null, false);
        
        $select_default = (!is_null($select)) ? $select : "Selecione um Profissional";
        
        $profissional = array("" => $select_default);
        
        while ($rst = mysql_fetch_assoc($sql)) {
            $profissional[$rst['id_profissional']] = $rst['nome'];
        }
        
        if(!is_null($id)){
            return $profissional[$id];
        }else{
            return $profissional;
        }
    }
    
    public static function getCliente($id = null, $select = null) {
        if(!is_null($id)){
            $and = "id_cliente = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("cliente", "*", $and, "nome", null, null, false);
        
        $select_default = (!is_null($select)) ? $select : "Selecione um Cliente";
        
        $cliente = array("" => $select_default);
        
        while ($rst = mysql_fetch_assoc($sql)) {
            $cliente[$rst['id_cliente']] = $rst['nome'];
        }
        
        if(!is_null($id)){
            return $cliente[$id];
        }else{
            return $cliente;
        }
    }
    
    /*
     * UPLOAD DE FOTOS
     * $string: type data:image
     * $tipo: type image
     * $chave: id cadastro
     * $dir: diretorio(ex: ../)
     */
    public static function fotoUpload($string, $tipo, $chave, $dir = "", $pasta) {
        $img = str_replace('data:image/'.$tipo.';base64,', '', $string);
        $img = str_replace(' ', '+', $img);
        
        $filename = "{$dir}uploads/".COD_IGREJA."/{$pasta}/{$chave}.{$tipo}";
        
        if(file_exists($filename)){
            unlink($filename);
            $upload = file_put_contents($filename, base64_decode($img));
        }else{
            $upload = file_put_contents($filename, base64_decode($img));
        }
    }
    
    /**
     * CADASTRAR ARQUIVO
     * @param type $extensao extensao do arquivo(ex: jpg) sem o ponto
     * @param integer $tipo tipo de arquivo(ex: 8(Aluno))
     * @param integer $id_cad
     */
    public static function cadFile($extensao, $tipo, $id_cad){
        $row_ext = Globals::getExtensaoFile($extensao);
        $cod_ext = $row_ext['id_extensao'];
        
        $user = $_SESSION['id_usuario'];
        $data = date('Y-m-d H:i:s');
        
        $insere = sqlInsert("file", "id_extensao, id_tipo, data_cad, user_cad", "{$cod_ext}, {$tipo}, '{$data}', {$user}");
        
        if($insere){
            $id_file = mysql_insert_id();
            
            $insere_file_assoc = sqlInsert("file_assoc", "id_file, id_key", "{$id_file}, {$id_cad}");
            
            if(!$insere_file_assoc){
                echo "ERRO AO INSERIR ARQUIVO | GlobalsClass::cadFile insere_file_assoc";
            }
        }else{
            echo "ERRO AO INSERIR ARQUIVO | GlobalsClass::cadFile";
        }
    }
    
    public static function getFileGlobal($tipo, $id_cad) {
        $qry = montaQuery("file AS A
                LEFT JOIN file_extensao AS B ON(A.id_extensao = B.id_extensao)
                LEFT JOIN file_assoc AS C ON(A.id_file = C.id_file)", 
                "A.id_file, B.nome AS extensao", 
                "A.id_tipo = {$tipo} AND C.id_key = {$id_cad} AND A.status = 1", null, null, null);
        return $qry;
    }
    
    public static function getAniversariantes(){
        $qry = montaQuery("cliente AS A", 
                "A.id_cliente, A.nome, A.apelido, A.whatsapp, DATE_FORMAT(A.data_nascimento, '%d/%m/%Y') AS data_nascimento", 
                "A.id_unidade = {$_SESSION['id_unidade']} AND YEARWEEK(A.data_nascimento, 1) = YEARWEEK(CURDATE(), 1) AND A.status = 1", 
                "A.data_nascimento", null, null, false);        
        return $qry;
    }
    
    public static function verificaPermissoesBotoes($botoes_id){
        $result = montaQuery("botoes_assoc", 
                "*", "id_usuario = '{$_SESSION['id_usuario']}' AND botoes_id  = '{$botoes_id}'", null, null, null, false);
        
        $verifica_acoes = mysql_num_rows($result);
        
        if($verifica_acoes != 0) { 
            return true;            
        } else { 
            return false;            
        }
    }
    
    public static function verificaPermissoesAcoes($acoes_id){
        $result = montaQuery("acoes_assoc", 
                "*", "id_usuario = '{$_SESSION['id_usuario']}' AND acoes_id IN({$acoes_id})", null, null, null, false);
        
        $verifica_acoes = mysql_num_rows($result);
        
        if($verifica_acoes != 0) { 
            return true;            
        } else { 
            return false;            
        }
    }
    
    public function getSelectUnidade($id_unidade = null, $array = false, $id_usuario = null) {
        if(!is_null($id_unidade)){
            $and_unidade = "AND A.id_unidade = '{$id_unidade}'";
        }
        
        if(!is_null($id_usuario)){
            $and_usuario = "AND B.id_usuario = '{$id_usuario}'";
        }
        
        $sql = montaQuery("unidade AS A
            LEFT JOIN unidade_assoc AS B ON(A.id_unidade = B.id_unidade)", 
            "A.id_unidade, A.unidade", 
            "A.status = 1 {$and_unidade} {$and_usuario}", null, null, null, false);
        
        if(is_null($array)){
            $row = mysql_fetch_assoc($sql);

            return $row;
        }else{
            $unidade = array("-1" => "- Todas -");
            
            while ($rst = mysql_fetch_assoc($sql)) {
                $unidade[$rst['id_unidade']] = $rst['id_unidade'] . " - " . $rst['unidade'];
            }
            
            return $unidade;
        }
    }
    
    public function getListaContato() {        
        $sql = "SELECT A.id_cliente AS id, A.nome, A.email, A.celular, A.telefone, A.whatsapp, 'Cliente' AS tipo
            FROM cliente AS A
            WHERE A.id_unidade = {$_SESSION['id_unidade']} AND A.status = 1

            UNION

            SELECT B.id_profissional AS id, B.nome, B.email, B.celular, B.telefone, B.whatsapp, 'Profissional' AS tipo
            FROM profissional AS B
            WHERE B.id_unidade = {$_SESSION['id_unidade']} AND B.status = 1

            ORDER BY nome";
        $qry = mysql_query($sql) or die(mysql_error());
        
        return $qry;
    }
    
}

?>