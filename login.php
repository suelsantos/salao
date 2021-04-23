<?php
require('./class/ConfigClass.php');

if(!empty($_SESSION['id_usuario'])){
    header("Location: index.php");
}

if($_SERVER['REQUEST_URI'] == '/adm'){
    header("Location: /adm/");
}

$login = new Login();

$altera_senha = false;

if(!empty($_REQUEST['entrar'])){
    $email = $_REQUEST['email'];
    $senha = sha1($_REQUEST['senha']);
    
    $login->getAcesso($email, $senha);
    $usuario = $login->getDados();
    
    if($login->getErro() == ''){
        $login->getAcessoDias($usuario['dias_acesso'],$usuario['horario_inicio'], $usuario['horario_fim']);
        
        if($login->getErro() == ''){
            if($usuario['altera_senha'] == 0){
                $login->gravaSessao($usuario);
                $global->gravaLog($usuario['id_usuario'], 1, 1);
                header("Location: index.php");
                exit;
            }else{
                $altera_senha = true;
                $login->gravaSessao($usuario);
                $global->gravaLog($usuario['id_usuario'], 1, 1);
            }
        }
    }
}

if(!empty($_REQUEST['alterar_senha'])){
    $senha_nova = sha1($_REQUEST['senha_nova']);
    $id_usuario = $_SESSION['id_usuario'];    
    
    $login->atualizaSenha($senha_nova, $id_usuario);    
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt-br" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="pt-br">
<!--<![endif]-->

    <head>
        <meta charset="utf-8" />
        <title><?php echo IGREJA; ?></title>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />      
        
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <link href="assets/css/animate.min.css" rel="stylesheet" />
        <link href="assets/css/style.min.css" rel="stylesheet" />
        <link href="assets/css/style-responsive.min.css" rel="stylesheet" />        
        <link href="assets/css/theme/default.css" rel="stylesheet" id="theme" />        
        <link href="assets/css/validationEngine.jquery.css" rel="stylesheet" />                
        
        <script src="assets/plugins/pace/pace.min.js"></script>        
    </head>
    <body class="pace-top bg-mari" onload="goFocus('email')">
        <div id="page-loader" class="fade in">
            <span class="spinner"></span>
        </div>
        
        <div id="page-container" class="fade">            
            <div class="login login-with-news-feed">                
                <div class="news-feed">
                    <div class="news-image">
                        <img src="assets/img/login-bg/bg-11.jpg" data-id="login-cover-image" alt="" />
                    </div>                
                </div>
                <div class="right-content">                    
                    <div class="login-header">
                        <div class="brand">
                            <img src="assets/img/logo.png" class="logo-login" id="logo-login">
                            <h4 class="text-white">Sistema para Salão de Beleza</h4>
                        </div>                        
                    </div>
                    
                    <?php if(!$altera_senha){ ?>
                    <div class="login-content">
                        <form action="#" method="post" class="margin-bottom-0" id="form_login" name="form_login" data-parsley-validate="true">
                            <div class="form-group m-b-15"  for="email">
                                <input type="text" class="form-control input-lg" placeholder="Login" name="email" id="email" />                                
                            </div>
                            <div class="form-group m-b-15">
                                <input type="password" class="form-control input-lg" placeholder="Senha" data-parsley-required="true" name="senha" id="senha" />
                            </div>
                            
                            <?php if($login->getErro() != ''){ ?>
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <?php echo $login->erro; ?>
                            </div>
                            <?php } ?>
                            
                            <?php if($login->getSucess() != ''){ ?>
                            <div class="alert alert-dismissible alert-success">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <?php echo $login->sucess; ?>
                            </div>
                            <?php } ?>
                            
                            <!--
                            <div class="checkbox m-b-30">
                                <label>
                                    <input type="checkbox" /> Lembrar login
                                </label>
                            </div>
                            -->                            
                            <div class="login-buttons">
                                <input type="submit" class="btn btn-purple btn-block btn-lg" name="entrar" id="entrar" value="Acessar">
                            </div>
                            <hr />
                            <p class="text-center text-white">
                                &copy; <a href="http://www.alisites.com.br" class="text-mari" target="_blank">Ali Sites</a> <?php echo date('Y'); ?>
                            </p>
                        </form>
                    </div>
                    <?php }else{ ?>
                    <div class="login-content">
                        <form action="#" method="post" class="margin-bottom-0" id="form_altera_senha" name="form_altera-senha" data-parsley-validate="true">
                            <div class="form-group m-b-15">
                                <input type="password" class="form-control input-lg" placeholder="Nova Senha" data-parsley-required="true" minlength="6" data-parsley-minlength="6" name="senha_nova" id="senha_nova" />                                
                            </div>
                            <div class="form-group m-b-15">
                                <input type="password" class="form-control input-lg" placeholder="Confirmar Senha" data-parsley-required="true" data-parsley-equaltopass="#senha_nova" minlength="6" data-parsley-minlength="6" name="senha_nova_conf" id="senha_nova_conf" />
                            </div>
                            
                            <?php if($login->getErro() != ''){ ?>
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <?php echo $login->erro; ?>
                            </div>
                            <?php } ?>
                            
                            <!--
                            <div class="checkbox m-b-30">
                                <label>
                                    <input type="checkbox" /> Lembrar login
                                </label>
                            </div>
                            -->                            
                            <div class="login-buttons">
                                <input type="submit" class="btn btn-success btn-block btn-lg" name="alterar_senha" id="alterar_senha" value="Alterar">
                            </div>                        
                            <hr />
                            <p class="text-center text-inverse">
                                &copy; <a href="http://semearsistemas.com.br" target="_blank">Semear Sistemas</a> 2015
                            </p>
                        </form>
                    </div>
                    <?php } ?>                   
                </div>
            </div>
        </div>
        
        <script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
        <script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
        <script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
        <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        
        <!--
        [if lt IE 9]>
                <script src="assets/crossbrowserjs/html5shiv.js"></script>
                <script src="assets/crossbrowserjs/respond.min.js"></script>
                <script src="assets/crossbrowserjs/excanvas.min.js"></script>
        <![endif]
        -->
        
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        <script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
        <script src="assets/js/jquery.validationEngine.js"></script>
        <script src="assets/js/jquery.validationEngine-pt_BR.js"></script>
        
        <!--page level-->
        <script src="assets/plugins/parsley/dist/parsley.js"></script>
        <script src="assets/js/apps.min.js"></script>
        
        <!--resources-->
        <script src="resources/js/global.js"></script>
        <script src="resources/js/login.js"></script>                
    </body>
</html>