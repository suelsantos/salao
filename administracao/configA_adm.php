<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$administracao = new Administracao();

/*
 * USADO O NOME DO SUBMENU
 * PARA DEFINIR O NAME/ID DO INPUT HIDDEN
 * QUE SERÁ PREENCHIDO
 * EX: usuario
 */

$area = "administracao";

/*
 * CASO O USUARIO NÃO TENHA PERMISSÃO 
 * PARA NENHUMA AÇÃO
 */
$target1 = ($global->verificaPermissoesAcoes(22)) ? 1 : "null";
$target2 = ($global->verificaPermissoesAcoes(24)) ? 1 : "null";
$target3 = ($global->verificaPermissoesAcoes(26)) ? 1 : "null";
$target4 = ($global->verificaPermissoesAcoes(28)) ? 1 : "null";
$target5 = ($global->verificaPermissoesAcoes(30)) ? 1 : "null";
$target6 = ($global->verificaPermissoesAcoes(32)) ? 1 : "null";
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
        
	<script src="../assets/plugins/pace/pace.min.js"></script>	
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
                    
                    <h2 class="page-header">Administração <small>Configurações</small></h2>
                    
                    <div class="row">
                        <div class="col-md-12">                            
                            <div class="panel panel-default panel-with-tabs" data-sortable-id="ui-unlimited-tabs-2">
                                <div class="panel-heading p-0">                                            
                                    <div class="tab-overflow overflow-right">
                                        <ul class="nav nav-tabs">
                                            <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-inverse"><i class="fa fa-arrow-left"></i></a></li>
                                            <li class="active"><a href="#nav-tab2-1" data-toggle="tab">Cargos</a></li>                                            
                                            <!--<li class=""><a href="#nav-tab2-2" data-toggle="tab">Horários</a></li>-->                                                                                       
                                            <li class=""><a href="#nav-tab2-3" data-toggle="tab">Serviços</a></li>                                                                                       
                                            <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-inverse"><i class="fa fa-arrow-right"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="nav-tab2-1">
                                        <h4 class="m-t-10">Cargos</h4>
                                        
                                        <?php if($global->verificaPermissoesAcoes(21)){ ?>
                                        <div class="row text-right m-r-0 m-b-10">
                                            <a href="javascript:;" class="btn btn-info btn_cargo" data-action="cadastrar"><i class="fa fa-plus"></i> Novo Cargo</a>
                                        </div>
                                        <?php } ?>
                                        
                                        <div class="table-responsive">
                                            <?php
                                            $cargo_sql = $global->getCargo(null, 1);
                                            $cargo_tot = mysql_num_rows($cargo_sql);
                                            
                                            if($cargo_tot > 0){
                                            ?>
                                            <table id="data-table1" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Descrição</th>
                                                        
                                                        <?php if($global->verificaPermissoesAcoes(22)){ ?>
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
                                    <div class="tab-pane fade" id="nav-tab2-2">
                                        <h4 class="m-t-10">Horários</h4>

                                        <?php if($global->verificaPermissoesAcoes(23)){ ?>
                                        <div class="row text-right m-r-0 m-b-10">
                                            <a href="javascript:;" class="btn btn-info btn_profissao disabled" data-action="cadastrar"><i class="fa fa-plus"></i> Nova Horário</a>
                                        </div>
                                        <?php } ?>
                                        
                                        <div class="table-responsive">
                                            <?php
                                            $profissao_sql = $global->getProfissao(null, 1);
                                            $profissao_tot = mysql_num_rows($profissao_sql);
                                            
                                            $profissao_tot = 0;
                                            
                                            if($profissao_tot > 0){
                                            ?>
                                            <table id="data-table2" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Descrição</th>       
                                                        
                                                        <?php if($global->verificaPermissoesAcoes(24)){ ?>
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
                                    <div class="tab-pane fade" id="nav-tab2-3">
                                        <h4 class="m-t-10">Serviços</h4>
                                        
                                        <?php if($global->verificaPermissoesAcoes(25)){ ?>
                                        <div class="row text-right m-r-0 m-b-10">
                                            <a href="javascript:;" class="btn btn-info btn_servico" data-action="cadastrar"><i class="fa fa-plus"></i> Novo Serviço</a>
                                        </div>
                                        <?php } ?>
                                        
                                        <div class="table-responsive">
                                            <?php
                                            $servico_sql = $global->getServicos(null, 1);
                                            $servico_tot = mysql_num_rows($servico_sql);
                                            
                                            if($servico_tot > 0){
                                            ?>
                                            <table id="data-table3" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Descrição</th>
                                                        
                                                        <?php if($global->verificaPermissoesAcoes(26)){ ?>
                                                        <th class="t-w-15">Ações</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <?php } else { ?>                                        
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
        <script src="../assets/plugins/parsley/dist/parsley.js"></script>
        
	<script src="../assets/js/dashboard.min.js"></script>
        <script src="../assets/js/bootstrap-dialog.min.js"></script>
        <script src="../assets/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="../assets/js/apps.min.js"></script>
        
        <script src="../resources/js/global.js?t=<?php AlteracaoFile('global.js'); ?>"></script>        
        <script src="../resources/js/administracao.js?t=<?php AlteracaoFile('administracao.js'); ?>"></script>
        <script>
            $(function(){
                var config_dTable = <?php echo json_encode($config_dataTable); ?>;
                
                //FUNÇÃO QUE CHAMA DATATABLE E JÁ ENVIA AS CONFIGURAÇÕES DA MESMA
                getDataTable("dtable_cargo", config_dTable, <?php echo $target1; ?>, "#data-table1");
                getDataTable("dtable_servico", config_dTable, <?php echo $target3; ?>, "#data-table3");
                // getDataTable("dtable_servico", config_dTable, <?php echo $target2; ?>, "#data-table2");
            });
        </script>
    </body>
</html>