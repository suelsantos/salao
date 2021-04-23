<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$uf = $global->getUF(null, 'uf_sigla');

$cliente = new ClienteSalao();

$id_cliente = $_REQUEST['cliente'];

//AÇÔES
if(isset($_REQUEST['save'])){
    $cliente->cadCliente();
}elseif(isset($_REQUEST['refresh'])){
    $cliente->editCliente($id_cliente);
    $id_cliente = $cliente->cod;
}

$qry = $cliente->getCliente("id_cliente", $id_cliente);
$row = mysql_fetch_assoc($qry);

if(!empty($id_cliente)){
    $title_page = "Alteração";
    $botao = "Atualizar";
    $fa_botao = "refresh";
}else{
    $title_page = "Cadastro";
    $botao = "Salvar";
    $fa_botao = "save";
}

//PESQUISA SE TEM FOTO
$pesquisa_foto = $global->getFileGlobal(8, $id_cliente);
$result_foto = mysql_fetch_assoc($pesquisa_foto);
$total_foto = mysql_num_rows($pesquisa_foto);

if($total_foto == 0){
    $foto = "0.jpg";
}else{
    $foto = "{$id_cliente}.{$result_foto['extensao']}";
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
	
        <link href="../assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />	                
	
	<script src="../assets/plugins/pace/pace.min.js"></script>        
    </head>
    <body onload="setInterval('window.clipboardData',20)">
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
        
        <form action="" method="post" name="form1" class="form-horizontal" id="form1" data-parsley-validate="true" enctype="multipart/form-data">            
            <input type="hidden" name="cliente" value="<?php echo $id_cliente; ?>" />
            
            <div id="page-container" class="fade page-sidebar-fixed page-header-fixed page-with-light-sidebar">
                
                <!--TOPO-->
                <?php include('../template/header.php'); ?>
                
                <!--BARRA LATERAL-->
                <?php include('../template/sidebar.php'); ?>
                
                <div id="content" class="content">                    
                    <ol class="breadcrumb pull-right m-b-15">
                        <li><a href="cliente_list.php">Clientes</a></li>
                        <li class="active"><?php echo $title_page; ?> de Cliente</li>
                    </ol>
                    
                    <h2 class="page-header"><?php echo $title_page; ?> <small>de Cliente</small></h2>                    
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if($cliente->getErro() != ''){ ?>
                                            <div class="alert alert-dismissible alert-danger m-t-15 m-r-10 m-b-15 m-l-10">                                                    
                                                <?php echo $cliente->erro; ?>
                                            </div>
                                            <?php } ?>

                                            <?php if($cliente->getSucess() != ''){ ?>
                                            <div class="alert alert-dismissible alert-success m-t-15 m-r-10 m-b-10 m-l-10">                                                    
                                                <?php echo $cliente->sucess; ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    
                                    <?php if(!isset($cliente->cod)) { ?>
                                    <div class="row m-r-10 m-l-10">                                                                                                                        
                                        <div class="col-md-2 col-sm-12 over-h">
                                            <div class="profile-left">
                                                <div class="profile-image">
                                                    <img src="<?php echo "../uploads/".COD_IGREJA."/clientes/{$foto}?t=".filemtime("../uploads/".COD_IGREJA."/clientes/{$foto}"); ?>" id="img_perf">
                                                    <i class="fa fa-user hide"></i>
                                                </div>
                                                <div class="m-b-10">
                                                    <a href="javascript:;" class="btn btn-warning btn-block btn-sm altera_foto" data-key="<?php echo $id_cliente; ?>"><span class="fa fa-image"></span>&nbsp;&nbsp;Alterar Foto</a>                                                    
                                                    <input type="hidden" name="crop_string" id="crop_string" value="" />
                                                    <input type="hidden" name="crop_type" id="crop_type" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-10 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Nome</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control input-lg text-uppercase" name="cadastro[nome]" id="nome" data-parsley-required="true" value="<?php echo $row['nome']; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Apelido</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control input-lg text-uppercase" name="cadastro[apelido]" id="nome" value="<?php echo $row['apelido']; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Data de Nascimento</label>
                                                <div class="col-md-10">
                                                    <div class="input-group date" id="datepicker-disabled-past" data-date-format="dd-mm-yyyy" data-date-start-date="Date.default">
                                                        <input type="text" class="form-control input-lg date_picker mask_data" name="cadastro[data_nascimento]" id="data_nascimento" value="<?php echo ($row['data_nascimento'] == '0000-00-00' || $row['data_nascimento'] == "") ? '' : converteData($row['data_nascimento'], "d/m/Y"); ?>">
                                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Sexo</label>
                                                <div class="col-md-10">
                                                    <select name="cadastro[sexo]" id="sexo" class="form-control input-lg">
                                                        <option value="">Selecione um Sexo</option>
                                                        <option value="1" <?php echo selected("1", $row['sexo']); ?>>Masculino</option>
                                                        <option value="2" <?php echo selected("2", $row['sexo']); ?>>Feminino</option>                                                        
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Celular</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control input-lg mask_tel" name="cadastro[celular]" id="celular" value="<?php echo $row['celular']; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Whatsapp</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control input-lg mask_tel" name="cadastro[whatsapp]" id="whatsapp" value="<?php echo $row['whatsapp']; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Telefone</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control input-lg mask_tel" name="cadastro[telefone]" id="telefone" value="<?php echo $row['telefone']; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Email</label>
                                                <div class="col-md-10">
                                                    <input type="email" class="form-control input-lg" name="cadastro[email]" id="email" value="<?php echo $row['email']; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">CEP</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control input-lg mask_cep" name="cadastro[cep]" id="cep" value="<?php echo $row['cep']; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Endereço</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control input-lg text-uppercase" disabled="disabled" id="logradouro_" value="<?php echo $row['logradouro']; ?>" />
                                                    <input type="hidden" name="cadastro[logradouro]" id="logradouro" value="<?php echo $row['logradouro']; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Bairro</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control input-lg text-uppercase" disabled="disabled" id="bairro_" value="<?php echo $row['bairro']; ?>" />
                                                    <input type="hidden" name="cadastro[bairro]" id="bairro" value="<?php echo $row['bairro']; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Cidade</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control input-lg text-uppercase" disabled="disabled" id="cidade_" value="<?php echo $row['cidade']; ?>" />
                                                    <input type="hidden" name="cadastro[cidade]" id="cidade" value="<?php echo $row['cidade']; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Estado</label>
                                                <div class="col-md-10">
                                                    <?php echo montaSelect($uf, getDB("uf", "uf_id = {$row['uf']}", "uf_sigla"), "class='form-control input-lg' disabled='disabled' id='estado'"); ?>     
                                                    <input type="hidden" name="cadastro[uf]" id="uf" value="<?php echo getDB("uf", "uf_id = {$row['uf']}", "uf_sigla"); ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Nº</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control input-lg sonumeros" name="cadastro[numero]" id="num" value="<?php echo $row['numero']; ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Complemento</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control input-lg text-uppercase" name="cadastro[complemento]" id="complemento" value="<?php echo $row['complemento']; ?>" />
                                                </div>
                                            </div>                                            
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Observações</label>
                                                <div class="col-md-10">
                                                    <textarea class="form-control text-uppercase" name="cadastro[obs]" id="obs" rows="5"><?php echo $row['obs']; ?></textarea>
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

                <a href="javascript:;" class="btn btn-icon btn-circle btn-info btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
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
	
        <script src="../assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="../assets/plugins/jquery-cookie/jquery.cookie.js"></script>                       
        <script src="../assets/plugins/parsley/dist/parsley.js"></script>
	<script src="../assets/js/dashboard.min.js"></script>
	<script src="../assets/js/apps.min.js"></script>                
        
        <script src="../resources/js/global.js"></script>
        <script src="../resources/js/cliente.js"></script>               
    </body>
</html>