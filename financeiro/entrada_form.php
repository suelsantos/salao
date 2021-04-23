<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$categoria = $global->getCategoriaPgt(null, 1);
$conta = $global->getContaBancaria();
$servico = $global->getServico();
$profissional = $global->getProfissional();
$cliente = $global->getCliente();

$entrada = new Entrada();

$id_entrada = $_REQUEST['entrada'];
$tipo_entrada = $_REQUEST['_tipo'];

//AÇÔES
if(isset($_REQUEST['save'])){
    $entrada->cadEntrada();
    $tipo_entrada = $entrada->tipo;
}elseif(isset($_REQUEST['refresh'])){
    $entrada->editEntrada($id_entrada);
    $id_entrada = $entrada->cod;
}

$qry = $entrada->getEntrada("id_entrada", $id_entrada);
$row = mysql_fetch_assoc($qry);

if(!empty($id_entrada)){
    $title_page = "Alteração";
    $botao = "Atualizar";
    $fa_botao = "refresh";
}else{
    $title_page = "Cadastro";
    $botao = "Salvar";
    $fa_botao = "save";
}

$nome_tipo = "Entrada";

if(!empty($tipo_entrada)){
    if($tipo_entrada == "dizimo"){
        $row['id_tipo'] = 1;
        $row['status'] = 1;
        $row['tipo_pgt'] = 1;
        $nome_tipo = "Dízimo";
        
        //consulta conta padrão para dízimos
        $qry_dizimo = montaQuery("finan_conta", "*", "padrao_dizimo = 1", null, null, null, false);
        $res_dizimo = mysql_fetch_assoc($qry_dizimo);
        
        $row['id_conta'] = $res_dizimo['id_conta'];
    }else{
        $row['status'] = 1;
    }
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
            <input type="hidden" name="entrada" value="<?php echo $id_entrada; ?>" />
            <input type="hidden" name="_tipo" id="_tipo" value="<?php echo $tipo_entrada; ?>" />
            
            <div id="page-container" class="fade page-sidebar-fixed page-header-fixed page-with-light-sidebar">                                
                
                <!--TOPO-->
                <?php include('../template/header.php'); ?>
                
                <!--BARRA LATERAL-->
                <?php include('../template/sidebar.php'); ?>
                
                <div id="content" class="content">                    
                    <ol class="breadcrumb pull-right m-b-15">
                        <li><a href="entrada_list.php">Entradas</a></li>
                        <li class="active"><?php echo $title_page; ?> de <?php echo $nome_tipo; ?></li>
                    </ol>
                    
                    <h2 class="page-header"><?php echo $title_page; ?> <small>de <?php echo $nome_tipo; ?></small></h2>                                        
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if($entrada->getErro() != ''){ ?>
                                            <div class="alert alert-dismissible alert-danger m-t-25 m-r-10 m-b-15 m-l-10">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <?php echo $entrada->erro; ?>
                                            </div>
                                            <?php } ?>

                                            <?php if($entrada->getSucess() != ''){ ?>
                                            <div class="alert alert-dismissible alert-success m-t-25 m-r-10 m-b-10 m-l-10">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <?php echo $entrada->sucess; ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>                                                                        
                                    
                                    <?php if(!isset($entrada->cod)) { ?>
                                    <div class="row m-r-10 m-l-10">                                                                                                                                                                                                        
                                        <div class="col-md-12 col-sm-12">
                                            <?php if($tipo_entrada != "dizimo"){ ?>
                                            <div class="form-group">
                                                <label class="col-md-1 control-label">Descrição</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control input-lg text-uppercase" name="cadastro[nome]" id="nome" <?php echo ($row['id_tipo'] == 1) ? '' : 'data-parsley-required="true"' ?> value="<?php echo $row['nome']; ?>" />
                                                </div>   
                                                <label class="col-md-1 control-label">Valor</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control input-lg mask_money" name="cadastro[valor]" id="valor" data-parsley-required="true" value="<?php echo ($row['valor'] != 0) ? formataMoeda($row['valor'], 1) : ""; ?>" />
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="form-group">                                                
                                                <label class="col-md-1 control-label">Data Crédito</label>
                                                <div class="col-md-5">
                                                    <div class="input-group date" id="datepicker-disabled-past" data-date-format="dd-mm-yyyy" data-date-start-date="Date.default">
                                                        <input type="text" class="form-control input-lg date_picker mask_data" name="cadastro[data_credito]" id="data_credito" data-parsley-required="true" data-parsley-type="databr" value="<?php echo ($row['data_credito'] != 0) ? converteData($row['data_credito'], "d/m/Y") : date("d/m/Y"); ?>">
                                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>     
                                                    </div>
                                                </div>
                                                <label class="col-md-1 control-label">Categoria</label>
                                                <div class="col-md-5">                                                    
                                                    <?php echo montaSelect($categoria, $row['id_tipo'], "class='chosen-select form-control input-lg' id='id_tipo' name='cadastro[id_tipo]' data-parsley-required"); ?>                                                    
                                                </div>
                                            </div>
                                            <div class="form-group">                                                
                                                <label class="col-md-1 control-label">Conta</label>
                                                <div class="col-md-5">                                                    
                                                    <?php echo montaSelect($conta, $row['id_conta'], "class='chosen-select form-control input-lg' id='id_conta' name='cadastro[id_conta]' data-parsley-required"); ?>
                                                </div>
                                                <label class="col-md-1 control-label">Serviço</label>
                                                <div class="col-md-5">                                                    
                                                    <?php echo montaSelect($servico, $row['id_servico'], "class='chosen-select form-control input-lg' id='id_servico' name='cadastro[id_servico]'"); ?>                                                    
                                                </div>
                                            </div>          
                                            <div class="form-group">                                                
                                                <label class="col-md-1 control-label">Profissional</label>
                                                <div class="col-md-5">                                                    
                                                    <?php echo montaSelect($profissional, $row['id_profissional'], "class='chosen-select form-control input-lg' id='id_profissional' name='cadastro[id_profissional]'"); ?>
                                                </div>
                                                <label class="col-md-1 control-label">Cliente</label>
                                                <div class="col-md-5">                                                    
                                                    <?php echo montaSelect($cliente, $row['id_cliente'], "class='chosen-select form-control input-lg' id='id_cliente' name='cadastro[id_cliente]'"); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-1 control-label">Foi Pago?</label>
                                                <div class="col-md-5">
                                                    <input type="checkbox" name="cadastro[status]" class="_switchYN" value="1" <?php echo checked("1", $row['status']); ?> />
                                                </div>
                                                <label class="col-md-1 control-label">Tipo de Pagamento</label>
                                                <div class="col-md-5">
                                                    <select name="cadastro[tipo_pgt]" id="tipo_pgt" class="form-control input-lg">
                                                        <option value="">Selecione um Tipo</option>
                                                        <option value="1" <?php echo selected("1", $row['tipo_pgt']); ?>>Dinheiro</option>
                                                        <option value="2" <?php echo selected("2", $row['tipo_pgt']); ?>>Cheque</option>
                                                        <option value="3" <?php echo selected("3", $row['tipo_pgt']); ?>>Transferência Bancária</option>
                                                        <option value="4" <?php echo selected("4", $row['tipo_pgt']); ?>>Cartão de Crédito</option>
                                                        <option value="5" <?php echo selected("5", $row['tipo_pgt']); ?>>Cartão de débito</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-1 control-label">Observações</label>
                                                <div class="col-md-11">
                                                    <textarea class="form-control text-uppercase" name="cadastro[observacoes]" id="observacoes" rows="5"><?php echo $row['observacoes']; ?></textarea>
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
        <script src="../assets/js/jquery.maskMoney.js"></script>
        
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
        <script src="../resources/js/entrada.js?t=<?php AlteracaoFile('entrada.js'); ?>"></script>
    </body>
</html>