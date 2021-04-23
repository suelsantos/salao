<?php

class Login {
    
    public $erro;
    public $sucess;
    
    /**
     * MÉTODO PARA PEGAR O ACESSO
     * @param $id
     */
    public function getAcesso($login, $senha){
        $qry = montaQuery("usuario", "*", "login = '{$login}' AND senha = '{$senha}' AND status = '1'", null, "1", null);
        if(mysql_num_rows($qry) == 0){
            $this->erro = 'Email e(ou) senha incorreto(s)';
        }else{
            $this->dados = mysql_fetch_assoc($qry);
        }
    }
    
    /**
     * MÉTODO PARA VERIFICAR SE O USUARIO PODE ACESSAR NESSE DIA E HORA
     * @param $acesso_dias
     * @param $horario_inicio
     * @param $horario_fim
     */
    public function getAcessoDias($acesso_dias, $horario_inicio, $horario_fim) {
        if($acesso_dias != 7) {
            $horario_inicio = str_replace(':','',$horario_inicio);
            $horario_fim = str_replace(':','',$horario_fim);
            $horaAtual = date('His');
            $dias_semana = array('1', '2', '3', '4', '5');
            
            if (!in_array(date('w'), $dias_semana) || ($horario_inicio >= $horaAtual || $horario_fim <= $horaAtual)) {                
                $this->erro = 'Fora do seu horário de acesso.';
            }
        }
    }
    
    /**
     * MÉTODO PARA GRAVAR A SESSAO
     * @param $usuario
     */
    public function gravaSessao($usuario) {
        $_SESSION['id_unidade'] = $usuario['id_unidade'];
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nome_usuario'] = $usuario['nome'];
    }
    
    /**
     * MÉTODO PARA ATUALIZAR A SENHA
     * @param $senha
     * @param $id_usuario
     */
    public function atualizaSenha($senha, $id_usuario) {        
        if(sqlUpdate("usuario", "senha = '{$senha}', altera_senha = 0", "id_usuario = '{$id_usuario}'") or die("Erro metodo atualizaSenha")){
            $this->sucess = "Senha alterada, faça Login.";
        }
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
    
    public function getDados() {
        return $this->dados;
    }
    
}

?>