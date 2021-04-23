<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$uf = $global->getUF(null, 'uf_sigla');

$unidade = new Unidade();

$id_unidade = $_REQUEST['unidade'];

//AÇÔES
if(isset($_REQUEST['save'])){
    $unidade->cadUnidade();
}elseif(isset($_REQUEST['refresh'])){
    $unidade->editUnidade($id_unidade);
    $id_unidade = $unidade->cod;
}

$qry = $unidade->getUnidadeI("id_unidade", $id_unidade);
$row = mysql_fetch_assoc($qry);

if(!empty($id_unidade)){
    $title_page = "Alteração";
    $botao = "Atualizar";
    $fa_botao = "refresh";
}else{
    $title_page = "Cadastro";
    $botao = "Salvar";
    $fa_botao = "save";
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
        
        <!--DATE PICKER-->
        <link href="../assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
	<link href="../assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
        
        <!--IMG CROP-->
        <link rel="stylesheet" type="text/css" href="../assets/plugins/imagecrop/example.css">
        <link rel="stylesheet" type="text/css" href="../assets/plugins/imagecrop/crop.css">
        
        <!--AUTOCOMPLETE-->
        <link rel="stylesheet" href="../assets/plugins/autocomplete/chosen.jquery.css" />
        
        <!--TAG-IT-->
        <link rel="stylesheet" href="../assets/plugins/jquery-tagit/css/tagit-dark-grey.css" />
        
        <!--SWITCH-->
        <link rel="stylesheet" href="../assets/plugins/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.css" />
	
        <link href="../assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />	                
	
	<script src="../assets/plugins/pace/pace.min.js"></script>        
    </head>
    <body>
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
        
        <form action="" method="post" name="form1" class="form-horizontal" id="form1" data-parsley-validate="true" enctype="multipart/form-data">            
            <input type="hidden" name="unidade" value="<?php echo $id_unidade; ?>" />
            
            <div id="page-container" class="fade page-sidebar-fixed page-header-fixed page-with-light-sidebar">
                
                <!--TOPO-->
                <?php include('../template/header.php'); ?>
                
                <!--BARRA LATERAL-->
                <?php include('../template/sidebar.php'); ?>
                
                <div id="content" class="content">                    
                    <ol class="breadcrumb pull-right m-b-15">
                        <li><a href="unidade_list.php">Unidades</a></li>
                        <li class="active"><?php echo $title_page; ?> de Unidade</li>
                    </ol>
                    
                    <h2 class="page-header"><?php echo $title_page; ?> <small>de Unidade</small></h2>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if($unidade->getErro() != ''){ ?>
                                            <div class="alert alert-dismissible alert-danger m-t-25 m-r-10 m-b-15 m-l-10">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <?php echo $unidade->erro; ?>
                                            </div>
                                            <?php } ?>

                                            <?php if($unidade->getSucess() != ''){ ?>
                                            <div class="alert alert-dismissible alert-success m-t-25 m-r-10 m-b-10 m-l-10">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <?php echo $unidade->sucess; ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    
                                    <?php if(!isset($unidade->cod)) { ?>
                                    <div class="row m-r-10 m-l-10">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-md-1 control-label">Nome</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control input-lg text-uppercase" name="cadastro[unidade]" placeholder="Nome da Unidade" id="unidade" data-parsley-required="true" value="<?php echo $row['unidade']; ?>" />
                                                </div>
                                                <label class="col-md-1 control-label">Telefone da Unidade</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control input-lg mask_tel" name="cadastro[telefone]" id="telefone" value="<?php echo $row['telefone']; ?>" />
                                                </div>
                                            </div>                                        
                                            <div class="form-group">
                                                <label class="col-md-1 control-label">Responsável</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control input-lg text-uppercase" name="cadastro[nome_responsavel]" placeholder="Nome do Responsável" id="nome_responsavel" value="<?php echo $row['nome_responsavel']; ?>" />
                                                </div>
                                                <label class="col-md-1 control-label">Telefone do responsável</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control input-lg mask_tel" name="cadastro[telefone_responsavel]" id="telefone_responsavel" value="<?php echo $row['telefone_responsavel']; ?>" />
                                                </div>
                                            </div>          
                                            <div class="form-group">
                                                <label class="col-md-1 control-label">CEP</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control input-lg mask_cep" name="cadastro[cep]" id="cep" value="<?php echo $row['cep']; ?>" />
                                                </div>                                        
                                                <label class="col-md-1 control-label">Endereço</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control input-lg text-uppercase" disabled="disabled" id="logradouro_" value="<?php echo $row['logradouro']; ?>" />
                                                    <input type="hidden" name="cadastro[logradouro]" id="logradouro" value="<?php echo $row['logradouro']; ?>" />
                                                </div>   
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-1 control-label">Bairro</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control input-lg text-uppercase" disabled="disabled" id="bairro_" value="<?php echo $row['bairro']; ?>" />
                                                    <input type="hidden" name="cadastro[bairro]" id="bairro" value="<?php echo $row['bairro']; ?>" />
                                                </div>                                       
                                                <label class="col-md-1 control-label">Cidade</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control input-lg text-uppercase" disabled="disabled" id="cidade_" value="<?php echo $row['cidade']; ?>" />
                                                    <input type="hidden" name="cadastro[cidade]" id="cidade" value="<?php echo $row['cidade']; ?>" />
                                                </div>     
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-1 control-label">Estado</label>
                                                <div class="col-md-5">
                                                    <?php echo montaSelect($uf, getDB("uf", "uf_id = {$row['uf']}", "uf_sigla"), "class='form-control input-lg' disabled='disabled' id='estado'"); ?>     
                                                    <input type="hidden" name="cadastro[uf]" id="uf" value="<?php echo getDB("uf", "uf_id = {$row['uf']}", "uf_sigla"); ?>" />
                                                </div>                                        
                                                <label class="col-md-1 control-label">Nº</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control input-lg sonumeros" name="cadastro[numero]" id="num" value="<?php echo ($row['numero'] != 0) ? $row['numero'] : ""; ?>" />
                                                </div>  
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-1 control-label">Complemento</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control input-lg text-uppercase" name="cadastro[complemento]" id="complemento" value="<?php echo $row['complemento']; ?>" />
                                                </div>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-info pull-right" id="acao_user" name="<?php echo $fa_botao; ?>"><span class="fa fa-<?php echo $fa_botao; ?>"></span>&nbsp;&nbsp;<?php echo $botao; ?></button>
                                        </div>
                                    </div>                                    
                                    <?php } ?>
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
        
        <!--DATE PICKER-->
        <script src="../assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        
        <!--MASCARA-->
        <script src="../assets/plugins/masked-input/maskedinput.js"></script>
        
        <!--IMG CROP-->
        <script src="../assets/plugins/imagecrop/crop.js"></script>
        
        <!--ENDERECO PELO CEP-->
        <script src="../assets/js/cep.js"></script>
        
        <!--AUTOCOMPLETE-->
        <script src="../assets/plugins/autocomplete/chosen.jquery.js"></script>
        
        <!--TAGIT-->
        <script src="../assets/plugins/jquery-tagit/js/tagit.js"></script>
        
        <!--SWITCH-->
        <script src="../assets/plugins/bootstrap-switch-master/dist/js/bootstrap-switch.js"></script>
	
        <script src="../assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="../assets/plugins/jquery-cookie/jquery.cookie.js"></script>
        <script src="../assets/plugins/parsley/dist/parsley.js"></script>
	<script src="../assets/js/dashboard.min.js"></script>
	<script src="../assets/js/apps.min.js"></script>
        
        <script src="../resources/js/global.js?t=<?php AlteracaoFile('global.js'); ?>"></script>
        <script src="../resources/js/unidade.js?t=<?php AlteracaoFile('unidade.js'); ?>"></script>
    </body>
</html>