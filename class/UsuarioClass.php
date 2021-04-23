<?php

class Usuario extends Globals {    
    
    public $erro;
    public $sucess;
    public $user;
    public $user_logado;
    
    public function getListaUsuario($usuario_logado = null) {        
        $and_dev = (!$usuario_logado['dev']) ? 'AND A.dev != 1' : '';
        
//        $qry = montaQuery("usuario AS A", 
//                "A.*, IF(A.nivel_user = 2, 'Administrador', 'Básico') AS perfil", 
//                "A.status = 1 {$and_dev}", null, null, null, false);
        $sql = "SELECT A.*, B.unidade AS nome_unidade, IF(A.nivel_user = 2, 'Administrador', 'Básico') AS perfil, (SELECT DATE_FORMAT(MAX(data_log), '%d/%m/%Y às %H:%i') AS ultimo_acesso FROM log WHERE id_usuario = A.id_usuario) AS ultimo_acesso
            FROM usuario AS A    
            LEFT JOIN unidade AS B ON(A.id_unidade = B.id_unidade)
            WHERE A.status = 1 {$and_dev}";
        $qry = mysql_query($sql) or die("ERRO get employees");
        return $qry;
    }
    
    public static function getUsuario($key, $value, $retorno = null) {
        $qry = montaQuery("usuario AS A
                LEFT JOIN file_extensao AS B ON(A.id_extensao = B.id_extensao)",
                "A.*, B.nome AS nome_extensao",
                "A.{$key} = '{$value}' AND A.status = 1", null, null, null, false);
        
        if(!is_null($retorno)){
            $res = mysql_fetch_assoc($qry);
            
            return $res[$retorno];
        }else{
            return $qry;
        }
    }
    
    public function getLog($id_usuario) {
        $qry = montaQuery("log AS A
                LEFT JOIN log_local AS B ON(A.id_local = B.id_log_local)
                LEFT JOIN log_acao AS C ON(A.id_acao = C.id_log_acao)
                LEFT JOIN unidade AS D ON(A.id_unidade = D.id_unidade)", 
                "A.id_log, DATE_FORMAT(A.data_log, '%d/%m/%Y %H:%i:%s') AS data_log, A.ip, D.unidade, B.local, IF(A.id_acao = 0, A.acao_complementar, C.acao) AS acao", 
                "A.id_usuario = {$id_usuario} AND A.status = 1", null, null, null, false);        
        return $qry;
    }
    
    public function cadUsuario() {
        $nome = acentoMaiusculo($_REQUEST['nome_usuario']);
        $login = $_REQUEST['login_usuario'];
        $perfil = $_REQUEST['perfil'];
        
        $senha = sha1('123456');
        $id_unidade = $_SESSION['id_unidade'];
        $id_logado = $_SESSION['id_usuario'];
        $data_atual = date('Y-m-d H:i:s');
        
        //VERIFICA SE JA EXISTE LOGIN
        $qry_verifica = $this->getUsuario("login", $login);
        
        if(mysql_num_rows($qry_verifica) > 0){
            $this->erro = "Login <strong>{$login}</strong> já existe!";
        }else{
            $insere_usuario = sqlInsert("usuario", "nome, login, senha, nivel_user, id_unidade, altera_senha, user_cad, data_cad", 
                "'{$nome}', '{$login}', '{$senha}', {$perfil}, {$id_unidade}, 1, {$id_logado}, '{$data_atual}'") or die("ERRO insere_usuario");
            
            $id_usuario = mysql_insert_id();
            
            Globals::gravaLog($_SESSION['id_usuario'], 4, null, "Cadastrou o usuário {$nome}");
            
            //GRAVA PERMISSÕES PELO PERFIL
            Globals::gravaPermissaoBotao($perfil, $id_usuario);
            Globals::gravaPermissaoUnidade($id_usuario);
        }
        
        if($insere_usuario){
            $this->sucess = "Usuário Cadastrado com sucesso!<br> Senha Padrão: <strong>123456</strong>";
        }else{
            $this->erro = "Erro ao cadastrar usuário, tente mais tarde";
        }
    }
    
    public function editUsuario($id_usuario) {
        $nome = acentoMaiusculo($_REQUEST['nome_usuario']);
        $login = $_REQUEST['login_usuario'];
        $perfil = $_REQUEST['perfil'];
        $senha = ($_REQUEST['senha_usuario'] == '') ? '' : sha1($_REQUEST['senha_usuario']);
        $user_logado = $_REQUEST['user_logado'];
        
        //CONSULTA PERFIL, PRA ALTERAR PERMISSOES
        $qry_perfil = $this->getUsuario("id_usuario", $id_usuario);
        $res_perfil = mysql_fetch_assoc($qry_perfil);
        
        if(!$user_logado){            
            //GRAVA PERMISSÕES PELO PERFIL
            if($res_perfil['nivel_user'] != $perfil){
                Globals::deletaPermissaoBotao($id_usuario);
                Globals::gravaPermissaoBotao($perfil, $id_usuario);
            }
            
            $txt_log = "Alterou o usuário {$nome} | ID: {$id_usuario}";
            $nivel_user = ",nivel_user = {$perfil}";
        }else{
            $txt_log = "Editou o perfil";            
        }
        
        //ALTERAÇÃO DE SENHA
        if($senha != ''){
            $alt_senha = ",senha = '{$senha}'";            
        }else{
            $alt_senha = "";            
        }

        //NÂO PERMITE ATUALIZAR O CAMPO LOGIN ATRAVÉS DA EDIÇÂO DE PERFIL
        if(!$user_logado){
            $update_login = "login = '{$login}',";
        } else {
            $update_login = "";
        }

        $altera_usuario = sqlUpdate("usuario", "nome = '{$nome}', {$update_login} user_alter = '{$_SESSION['id_usuario']}', data_alter = NOW() {$nivel_user} {$alt_senha}", "id_usuario = {$id_usuario}");
        
        Globals::gravaLog($_SESSION['id_usuario'], 5, null, $txt_log);
        
        if($altera_usuario){
            $this->sucess = "Dados atualizados com sucesso!";
            $this->user = $id_usuario;
            $this->user_logado = $user_logado;
        }else{
            $this->erro = "Erro ao atualizar dados, tente mais tarde";
        }
    }
    
    public function alteraIdFoto($id_usuario, $extensao_foto) {
        $qry = montaQuery("file_extensao AS A", 
            "*", 
            "A.nome = '{$extensao_foto}'", null, null, null, false);
        $id_extensao = mysql_result($qry, 0);
        
        $sql = sqlUpdate("usuario", "id_extensao = {$id_extensao}", "id_usuario = {$id_usuario}", false);
        
        return $sql;
    }
    
    public function desativaUsuario($id_usuario) {                
        $nome = Usuario::getUsuario("id_usuario", $id_usuario, "nome");
        
        Globals::gravaLog($_SESSION['id_usuario'], 6, null, "Excluiu o usuário {$nome} | ID: {$id_usuario}");
        
        $sql = sqlUpdate("usuario", "status = 0", "id_usuario = {$id_usuario}", false);
        
        return $sql;
    }
    
    public function resetaSenha($id_usuario) {
        $senha = sha1("123456");
        $nome = Usuario::getUsuario("id_usuario", $id_usuario, "nome");
        
        Globals::gravaLog($_SESSION['id_usuario'], 7, null, "Resetou a senha do usuário {$nome} | ID: {$id_usuario}");
        
        $sql = sqlUpdate("usuario", "senha = '{$senha}', altera_senha = 1", "id_usuario = {$id_usuario}", false);
        
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