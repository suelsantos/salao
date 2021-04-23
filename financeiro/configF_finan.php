<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$financeiro = new Financeiro();

/*
 * USADO O NOME DO SUBMENU
 * PARA DEFINIR O NAME/ID DO INPUT HIDDEN
 * QUE SERÁ PREENCHIDO
 * EX: usuario
 */

$area = "financeiro";

/*
 * CASO O USUARIO NÃO TENHA PERMISSÃO 
 * PARA NENHUMA AÇÃO
 */
if($global->verificaPermissoesAcoes(10)){
    $target = 2;
}else{
    $target = "null";
}

if($global->verificaPermissoesAcoes(12)){
    $target2 = 1;
}else{
    $target2 = "null";
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
        
        <!--SWITCH-->
        <link rel="stylesheet" href="../assets/plugins/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.css" />
        
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
                    
                    <h2 class="page-header">Financeiro <small>Configurações</small></h2>
                    
                    <div class="row">
                        <div class="col-md-12">                            
                            <div class="panel panel-default panel-with-tabs" data-sortable-id="ui-unlimited-tabs-2">
                                <div class="panel-heading p-0">                                            
                                    <div class="tab-overflow overflow-right">
                                        <ul class="nav nav-tabs">
                                            <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-inverse"><i class="fa fa-arrow-left"></i></a></li>
                                            <li class="active"><a href="#nav-tab2-1" data-toggle="tab">Categorias</a></li>
                                            <li class=""><a href="#nav-tab2-2" data-toggle="tab">Contas</a></li>                                                                                                                            
                                            <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-inverse"><i class="fa fa-arrow-right"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="nav-tab2-1">
                                        <h4 class="m-t-10">Categorias</h4>
                                        
                                        <?php if($global->verificaPermissoesAcoes(9)){ ?>
                                        <div class="row text-right m-r-0 m-b-10">
                                            <a href="javascript:;" class="btn btn-info btn_categoria" data-action="cadastrar"><i class="fa fa-plus"></i> Nova Categoria</a>
                                        </div>
                                        <?php } ?>
                                        
                                        <div class="table-responsive">
                                            <?php
                                            $categoria_sql = $financeiro->getCategoria(null, null, 1);
                                            $categoria_tot = mysql_num_rows($categoria_sql);
                                            
                                            if($categoria_tot > 0){
                                            ?>
                                            <table id="data-table" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>                                                                                                        
                                                        <th>Descrição</th>
                                                        <th>Tipo</th>
                                                        
                                                        <?php if($global->verificaPermissoesAcoes(10)){ ?>
                                                        <th class="t-w-15">Ações</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while($categoria_res = mysql_fetch_assoc($categoria_sql)){ ?>
                                                    <tr id="tr_categoria_<?php echo $categoria_res['id_tipo']; ?>">                                                        
                                                        <td id="td_nome_<?php echo $categoria_res['id_tipo']; ?>"><?php echo $categoria_res['nome']; ?></td>
                                                        <td id="td_tipo_<?php echo $categoria_res['id_tipo']; ?>"><?php echo ($categoria_res['tipo'] == 1) ? "Entrada" : "Saída"; ?></td>
                                                        
                                                        <?php if($global->verificaPermissoesAcoes(10)){ ?>
                                                        <td class="text-center col-md-1">
                                                            <a href="javascript:;" class="btn btn-warning btn-icon btn-square btn-sm btn_categoria toolt" data-action="editar" data-original-title="Editar" data-key="<?php echo $categoria_res['id_tipo']; ?>"><i class="fa fa-pencil"></i></a>                                                                                                                    
                                                            <!--<a href="javascript:;" class="btn btn-danger btn-icon btn-square btn-sm btn_categoria toolt" data-action="excluir" data-original-title="Excluir" data-key="<?php echo $categoria_res['id_tipo']; ?>" data-tipo="<?php echo $categoria_res['tipo']; ?>"><i class="fa fa-minus"></i></a>-->
                                                        </td>
                                                        <?php } ?>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <?php }else{ ?>
                                            <div class="note note-warning">
                                                <h5>Nenhum registro encontrado</h5>                                            
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-tab2-2">
                                        <h4 class="m-t-10">Contas</h4>
                                        
                                        <?php if($global->verificaPermissoesAcoes(11)){ ?>
                                        <div class="row text-right m-r-0 m-b-10">
                                            <a href="javascript:;" class="btn btn-info btn_conta" data-action="cadastrar"><i class="fa fa-plus"></i> Nova Conta</a>
                                        </div>
                                        <?php } ?>
                                        
                                        <div class="table-responsive">
                                            <?php
                                            $conta_sql = $financeiro->getConta(null, 1);
                                            $conta_tot = mysql_num_rows($conta_sql);
                                            
                                            if($conta_tot > 0){
                                            ?>
                                            <table id="data-table2" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>                                                                                                        
                                                        <th>Descrição</th>
                                                        
                                                        <?php if($global->verificaPermissoesAcoes(12)){ ?>
                                                        <th>Ações</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while($conta_res = mysql_fetch_assoc($conta_sql)){ ?>
                                                    <tr id="tr_conta_<?php echo $conta_res['id_conta']; ?>">                                                        
                                                        <td id="td_nome_conta_<?php echo $conta_res['id_conta']; ?>">
                                                            <?php echo $conta_res['nome']; ?>
                                                            
                                                            <?php if($conta_res['padrao_dizimo'] == 1){ ?>
                                                            &nbsp;<span class="label label-info">Padrão</span>
                                                            <?php } ?>
                                                        </td>
                                                        
                                                        <?php if($global->verificaPermissoesAcoes(12)){ ?>
                                                        <td class="text-center col-md-1">
                                                            <a href="javascript:;" class="btn btn-warning btn-icon btn-square btn-sm btn_conta toolt" data-action="editar" data-original-title="Editar" data-key="<?php echo $conta_res['id_conta']; ?>"><i class="fa fa-pencil"></i></a>                                                                                                                    
                                                            <!--<a href="javascript:;" class="btn btn-danger btn-icon btn-square btn-sm btn_conta toolt" data-action="excluir" data-original-title="Excluir" data-key="<?php echo $conta_res['id_conta']; ?>"><i class="fa fa-minus"></i></a>-->
                                                        </td>
                                                        <?php } ?>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
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
        <script src="../assets/plugins/parsley/dist/parsley.js"></script>
        
        <!--DATA TABLE-->
        <script src="../assets/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="../assets/plugins/DataTables/js/dataTables.tableTools.js"></script>
        <script src="../assets/plugins/DataTables/js/dataTables.responsive.js"></script>
	<script src="../assets/js/table-manage-tabletools.demo.min.js"></script>
        <script src="../assets/js/table-manage-responsive.demo.min.js"></script>
        
        <!--SWITCH-->
        <script src="../assets/plugins/bootstrap-switch-master/dist/js/bootstrap-switch.js"></script>
        
        <script src="../resources/js/global.js?t=<?php AlteracaoFile('global.js'); ?>"></script>
        <script src="../resources/js/financeiro.js?t=<?php AlteracaoFile('financeiro.js'); ?>"></script>
        <script>
            $(function(){
                var config_dTable = <?php echo json_encode($config_dataTable); ?>;
                
                //FUNÇÃO QUE CHAMA DATATABLE E JÁ ENVIA AS CONFIGURAÇÕES DA MESMA
                getDataTable(null, config_dTable, <?php echo $target; ?>, "#data-table");
                getDataTable(null, config_dTable, <?php echo $target2; ?>, "#data-table2");
            });
        </script>
    </body>
</html>