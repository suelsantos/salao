<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$user = new Usuario();

$id_user = $_REQUEST['usuario'];
$logado = false;

//AÇÔES
if(isset($_REQUEST['save_user'])){
    $user->cadUsuario();
}elseif(isset($_REQUEST['refresh_user'])){
    $user->editUsuario($_REQUEST['user']);
    $id_user = $user->user;
    
    if($user->user_logado){
        $logado = true;
    }
}

//EDIÇÃO DO PROPRIO PERFIL
if(!empty($_REQUEST['id_usuario_logado'])){
    $id_user = $_REQUEST['id_usuario_logado'];
    $logado = true;
}

$qry_user = $user->getUsuario("id_usuario", $id_user);
$row_user = mysql_fetch_assoc($qry_user);

if(!empty($id_user)){
    $title_page = "Alteração";
    $botao = "Atualizar";
    $fa_botao = "refresh";
}else{
    $title_page = "Cadastro";
    $botao = "Salvar";
    $fa_botao = "save";
}

//PESQUISA SE USUARIO TEM FOTO
$usuario_edit = $user->getUsuario("id_usuario", $id_user);
$usuario_edit_fetch = mysql_fetch_assoc($usuario_edit);

if($usuario_edit_fetch['nome_extensao'] == ""){
    $foto_perfil = "0.jpg";
}else{
    $foto_perfil = "{$usuario_edit_fetch['id_usuario']}.{$usuario_edit_fetch['nome_extensao']}";
}
?>
<!DOCTYPE html>
<!--[if IE 8]> 
<html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="pt-br">
<!--<![endif]-->
    <head>
	<meta charset="utf-8" />
	<title><?php echo IGREJA; ?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
        
        <link rel="shortcut icon" href="../<?php echo FAVICON; ?>">
	
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="../assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="../assets/css/bootstrap-dialog.min.css" rel="stylesheet" />
	<link href="../assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="../assets/css/animate.min.css" rel="stylesheet" />
	<link href="../assets/css/style.min.css" rel="stylesheet" />
	<link href="../assets/css/style-responsive.min.css" rel="stylesheet" />
	<link href="../assets/css/theme/default.css" rel="stylesheet" id="theme" />	
        
        <!--IMG CROP-->
        <link rel="stylesheet" type="text/css" href="../assets/plugins/imagecrop/example.css">
        <link rel="stylesheet" type="text/css" href="../assets/plugins/imagecrop/crop.css">
	
        <link href="../assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />	                
	
	<script src="../assets/plugins/pace/pace.min.js"></script>        
    </head>
    <body>
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
        
        <form action="" method="post" name="form1" class="form-horizontal" id="form1" data-parsley-validate="true" enctype="multipart/form-data">            
            <input type="hidden" name="user" value="<?php echo $id_user; ?>" />
            
            <?php if($logado){ ?>
            <input type="hidden" name="user_logado" value="1" />
            <?php } ?>
            
            <div id="page-container" class="fade page-sidebar-fixed page-header-fixed page-with-light-sidebar">
                
                <!--TOPO-->
                <?php include('../template/header.php'); ?>
                
                <!--BARRA LATERAL-->
                <?php include('../template/sidebar.php'); ?>
                
                <div id="content" class="content">
                    <?php if(!$logado){ ?>
                    <ol class="breadcrumb pull-right m-b-15">
                        <li><a href="usuario_list.php">Usuários</a></li>
                        <li class="active"><?php echo $title_page; ?> de Usuário</li>
                    </ol>
                    
                    <h2 class="page-header"><?php echo $title_page; ?> <small>de Usuário</small></h2>
                    <?php }else{ ?>
                    <h2 class="page-header">Editar <small>meu perfil</small></h2>
                    <?php } ?>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row m-t-15 m-r-10 m-l-10">
                                        <div class="col-md-2 col-sm-12 over-h">
                                            <div class="profile-left">
                                                <div class="profile-image">
                                                    <img src="<?php echo "../uploads/".COD_IGREJA."/usuarios/{$foto_perfil}?t=".filemtime("../uploads/".COD_IGREJA."/usuarios/{$foto_perfil}"); ?>" id="img_perf">
                                                    <i class="fa fa-user hide"></i>
                                                </div>
                                                <?php if(!empty($id_user)){ ?>
                                                <div class="m-b-10">
                                                    <a href="javascript:;" class="btn btn-warning btn-block btn-sm altera_foto" data-key="<?php echo $id_user; ?>"><span class="fa fa-image"></span>&nbsp;&nbsp;Alterar Imagem</a>
                                                    <?php if($logado){ ?>
                                                    <a href="javascript:;" class="btn btn-danger btn-block btn-sm altera_senha" data-key="<?php echo $id_user; ?>"><span class="fa fa-key"></span>&nbsp;&nbsp;Alterar Senha</a>
                                                    <?php } ?>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-md-10 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-md-1 control-label">Nome</label>
                                                <div class="col-md-11">
                                                    <input type="text" class="form-control input-lg" name="nome_usuario" id="nome_usuario" placeholder="Nome e Sobrenome" data-parsley-required="true" value="<?php echo $row_user['nome']; ?>" />
                                                </div>
                                            </div>

                                            <?php if(!$logado){ ?>
                                            <div class="form-group">
                                                <label class="col-md-1 control-label">Login</label>
                                                <div class="col-md-11">
                                                    <input type="text" class="form-control input-lg" name="login_usuario" id="login_usuario" placeholder="Login" value="<?php echo $row_user['login']; ?>" />
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <?php if(!$logado){ ?>
                                            <div class="form-group">
                                                <label class="col-md-1 control-label">Perfil</label>
                                                <div class="col-md-11">
                                                    <select name="perfil" id="perfil" class="form-control input-lg" data-parsley-required="true">
                                                        <option value="">Selecione um Perfil</option>
                                                        <option value="1" <?php echo selected("1", $row_user['nivel_user']); ?>>Básico</option>
                                                        <option value="2" <?php echo selected("2", $row_user['nivel_user']); ?>>Administrador</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php }else{ ?>
                                            <div id="campos_alt_senha" class="hide">
                                                <div class="form-group">
                                                    <label class="col-md-1 control-label"></label>
                                                    <div class="col-md-11">
                                                        <input type="password" class="form-control input-lg" name="senha_usuario" id="senha_usuario" minlength="6" data-parsley-minlength="6" placeholder="Nova Senha" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-1 control-label"></label>
                                                    <div class="col-md-11">
                                                        <input type="password" class="form-control input-lg" name="confsenha_usuario" id="confsenha_usuario" minlength="6" data-parsley-minlength="6" data-parsley-equaltopass="#senha_usuario" placeholder="Confirmar Senha" />
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <button type="submit" class="btn btn-info pull-right" id="acao_user" name="<?php echo $fa_botao; ?>_user"><span class="fa fa-<?php echo $fa_botao; ?>"></span>&nbsp;&nbsp;<?php echo $botao; ?></button>                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php if($user->getErro() != ''){ ?>
                                                <div class="alert alert-dismissible alert-danger m-t-25 m-r-10 m-b-15 m-l-10">
                                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                                    <?php echo $user->erro; ?>                                                    
                                                </div>
                                                <?php } ?>

                                                <?php if($user->getSucess() != ''){ ?>
                                                <div class="alert alert-dismissible alert-success m-t-25 m-r-10 m-b-10 m-l-10">
                                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                                    <?php echo $user->sucess; ?>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>                                                                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
            </div>
        </form>
        
        <script src="../assets/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="../assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="../assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="../assets/js/bootstrap-dialog.min.js"></script>
	
        <!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
		<script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->                
        
        <!--IMG CROP-->
        <script src="../assets/plugins/imagecrop/crop.js"></script>
	
        <script src="../assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="../assets/plugins/jquery-cookie/jquery.cookie.js"></script>                       
        <script src="../assets/plugins/parsley/dist/parsley.js"></script>
	<script src="../assets/js/dashboard.min.js"></script>
	<script src="../assets/js/apps.min.js"></script>                
        
        <script src="../resources/js/global.js"></script>
        <script src="../resources/js/usuario.js"></script>                
    </body>
</html>