<?php
require('../class/ConfigClass.php');

$unidade = new Unidade();

/*
 * USADO O NOME DO SUBMENU
 * PARA DEFINIR O NAME/ID DO INPUT HIDDEN
 * QUE SERÁ PREENCHIDO
 * EX: usuario
 */

$area = "unidade";

/*
 * CASO O USUARIO NÃO TENHA PERMISSÃO 
 * PARA NENHUMA AÇÃO
 */
if($global->verificaPermissoesAcoes('18,19,20')){
    $target = 4;
}else{
    $target = "null";
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
	<link href="../assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="../assets/css/animate.min.css" rel="stylesheet" />
	<link href="../assets/css/style.min.css" rel="stylesheet" />
	<link href="../assets/css/style-responsive.min.css" rel="stylesheet" />
        <link href="../assets/css/bootstrap-dialog.min.css" rel="stylesheet" />
	<link href="../assets/css/theme/default.css" rel="stylesheet" id="theme" />	
	
	<link href="../assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" />
	<link href="../assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
	<link href="../assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
        <link href="../assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />	                
	
        <link href="../assets/plugins/DataTables/css/data-table.css" rel="stylesheet" />
    </head>
    <body>
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>	
	        
        <form action="" method="post" name="form1" class="form-horizontal" id="form1" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $area; ?>" id="<?php echo $area; ?>" value="" />
            
            <div id="page-container" class="fade page-sidebar-fixed page-header-fixed page-with-light-sidebar">
                
                <!--TOPO-->
                <?php include('../template/header.php'); ?>

                <!--BARRA LATERAL-->
                <?php include('../template/sidebar.php'); ?>

                <div id="content" class="content">                                        		                			                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-heading-btn">
                                        <a href="javascript:;" data-click="panel-expand"><i class="fa fa-lg fa-expand icon-blue"></i></a>
                                        <a href="javascript:;" data-click="panel-reload"><i class="fa fa-lg fa-repeat icon-blue"></i></a>
                                    </div>
                                    <h4 class="panel-title">Controle de Unidades</h4>
                                </div>
                                <div class="clear-both"></div>
                                <div class="panel-body">
                                    <?php if($global->verificaPermissoesAcoes(17)){ ?>
                                    <div class="row text-right m-r-0 m-b-10">
                                        <a href="javascript:;" class="btn btn-info btn_unidade" data-action="cadastrar"><i class="fa fa-plus"></i> Nova Unidade</a>
                                    </div>
                                    <?php } ?>  
                                    
                                    <div class="table-responsive">
                                        <?php
                                        $unidade_sql = $unidade->getListaUnidade();
                                        $unidade_tot = mysql_num_rows($unidade_sql);
                                        
                                        if($unidade_tot > 0){
                                        ?>
                                        <table id="data-tableA" class="table table-striped table-bordered table-hover table-condensed display nowrap" width="100%">
                                            <thead>
                                                <tr>
                                                    <th class="t-w-5 desktop tablet-l tablet-p mobile-l">Cod</th>
                                                    <th>Nome</th>
                                                    <th>Telefone</th>
                                                    <th>Responsável</th>
                                                    
                                                    <?php if($global->verificaPermissoesAcoes('18,19,20')){ ?>
                                                    <th class="t-w-15">Ações</th>
                                                    <?php } ?>                                                    
                                                </tr>
                                            </thead>
                                        </table>
                                        <?php }else{ ?>
                                        <div class="note note-warning">
                                            <h5>Nenhum registro encontrado</h5>
                                        </div>
                                        <?php } ?>
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
	
        <script src="../assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="../assets/plugins/jquery-cookie/jquery.cookie.js"></script>
        
	<script src="../assets/js/dashboard.min.js"></script>
        <script src="../assets/js/bootstrap-dialog.min.js"></script>
	<script src="../assets/js/apps.min.js"></script>
        
        <!--DATA TABLE-->
        <script src="../assets/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="../assets/plugins/DataTables/js/dataTables.tableTools.js"></script>
        <script src="../assets/plugins/DataTables/js/dataTables.responsive.js"></script>
	<script src="../assets/js/table-manage-tabletools.demo.min.js"></script>
        <script src="../assets/js/table-manage-responsive.demo.min.js"></script>
        
        <script src="../resources/js/global.js?t=<?php AlteracaoFile('global.js'); ?>"></script>
        <script src="../resources/js/unidade.js?t=<?php AlteracaoFile('unidade.js'); ?>"></script>
        
        <script>
            $(function(){
                var config_dTable = <?php echo json_encode($config_dataTable); ?>;
                
                //FUNÇÃO QUE CHAMA DATATABLE E JÁ ENVIA AS CONFIGURAÇÕES DA MESMA
                getDataTable("dtable_unidade", config_dTable, <?php echo $target; ?>);
            });
        </script>
    </body>
</html>