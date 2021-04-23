<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$saida = new Saida();

/*
 * USADO O NOME DO SUBMENU
 * PARA DEFINIR O NAME/ID DO INPUT HIDDEN
 * QUE SERÁ PREENCHIDO
 * EX: usuario
 */

$area = "saida";

$filtro = null;
$filtro2 = null;
$periodo = "Mês Atual";
$status = "Todas";

if(!empty($_REQUEST['filtro'])){
    $filtro = $_REQUEST['filtro'];
    
    if($filtro == "mes_anterior"){
        $periodo = "Mês Anterior";
    }elseif($filtro == "mes_atual"){
        $periodo = "Mês Atual";
    }elseif($filtro == "mes_seguinte"){
        $periodo = "Próximo Mês";
    }elseif($filtro == "hoje"){
        $periodo = "Hoje";
    }elseif($filtro == "todos"){
        $periodo = "Todos";
    }
    
}

if(!empty($_REQUEST['filtro2'])){
    $filtro2 = $_REQUEST['filtro2'];
    
    if($filtro2 == "todas"){
        $status = "Todas";
    }elseif($filtro2 == "vencidas"){
        $status = "Vencidas";
    }elseif($filtro2 == "a_vencer"){
        $status = "A Vencer";
    }elseif($filtro2 == "recebidas"){
        $status = "Pagas";
    }
    
}

/*
 * CASO O USUARIO NÃO TENHA PERMISSÃO 
 * PARA NENHUMA AÇÃO
 */
if($global->verificaPermissoesAcoes('2,3,4')){
    $target = 6;
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
        
	<script src="../assets/plugins/pace/pace.min.js"></script>	
    </head>
    <body>
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>	
	
        <form action="" method="post" name="form1" class="form-horizontal" id="form1" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $area; ?>" id="<?php echo $area; ?>" value="" />
            <input type="hidden" name="_tipo" id="_tipo" value="" />
            <input type="hidden" name="filtro" id="filtro" value="<?php echo $filtro; ?>" />
            <input type="hidden" name="filtro2" id="filtro2" value="<?php echo $filtro2; ?>" />
            
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
                                    <h4 class="panel-title">Controle de Saidas</h4>
                                </div>
                                <div class="clear-both"></div>
                                <div class="panel-body">
                                    <div class="row m-l-0">
                                        <ul class="nav navbar-nav navbar-default border_filt m-r-15 m-b-15">
                                            <li class="dropdown navbar-user">
                                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">                                                   
                                                    <span><b>Período:</b> <?php echo $periodo; ?></span>
                                                    <b class="caret"></b>
                                                </a>
                                                <ul class="dropdown-menu animated fadeInLeft">
                                                    <li class="arrow"></li>
                                                    <li><a href="javascript:;" class="filt_periodo" data-value="hoje">Hoje</a></li>
                                                    <li><a href="javascript:;" class="filt_periodo" data-value="mes_atual">Mês Atual</a></li>
                                                    <li><a href="javascript:;" class="filt_periodo" data-value="mes_anterior">Mês Anterior</a></li>
                                                    <li><a href="javascript:;" class="filt_periodo" data-value="mes_seguinte">Próximo Mês</a></li>
                                                    <li><a href="javascript:;" class="filt_periodo" data-value="todos">Todos</a></li>
						</ul>
                                            </li>
                                        </ul>
                                        <ul class="nav navbar-nav navbar-default border_filt m-r-15 m-b-15">
                                            <li class="dropdown navbar-user">
                                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">                                                   
                                                    <span><b>Status:</b> <?php echo $status; ?></span>
                                                    <b class="caret"></b>
                                                </a>
                                                <ul class="dropdown-menu animated fadeInLeft">
                                                    <li class="arrow"></li>
                                                    <li><a href="javascript:;" class="filt_status" data-value="todas">Todas</a></li>
                                                    <li><a href="javascript:;" class="filt_status" data-value="vencidas">Vencidas</a></li>
                                                    <li><a href="javascript:;" class="filt_status" data-value="a_vencer">A vencer</a></li>
                                                    <li><a href="javascript:;" class="filt_status" data-value="recebidas">Pagas</a></li>                                                   
						</ul>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <?php if($global->verificaPermissoesAcoes(1)){ ?>
                                    <div class="row text-right m-r-0 m-b-10">
                                        <a href="javascript:;" class="btn btn-info btn_user" data-action="cadastrar"><i class="fa fa-plus"></i> Nova Saida</a>                                        
                                    </div>
                                    <?php } ?>  
                                    
                                    <?php
                                    $saida_sql = $saida->getListaSaida($filtro, $filtro2);
                                    $saida_tot = mysql_num_rows($saida_sql);
                                    
                                    if($saida_tot > 0){
                                    ?>
                                    <div class="table-responsive">                                        
                                        <table id="data-tableA" class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>                                                
                                                    <th class="t-w-5">Cod</th>
                                                    <th>Nome</th>
                                                    <th>Data Vencimento</th>
                                                    <th>Conta</th>
                                                    <th>Valor</th>
                                                    <th>Status</th>
                                                    
                                                    <?php if($global->verificaPermissoesAcoes('2,3,4')){ ?>
                                                    <th class="t-w-15 hidden-print">Ações</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php                                  
                                                $data_hj = date("Y-m-d");
                                                
                                                $qtd_pago = 0;
                                                $qtd_vencer = 0;
                                                $qtd_vencido = 0;
                                                $qtd_total = 0;
                                                
                                                while($saida_res = mysql_fetch_assoc($saida_sql)){
                                                    //PAGO
                                                    if($saida_res['status'] == 1){
                                                        $total_pago += $saida_res['valor'];
                                                        $qtd_pago ++;
                                                        
                                                        $tooltip = "Estornar";  
                                                        $action = "estornar";
                                                        $btn = "info";
                                                        $fa = "thumbs-up";
                                                    
                                                    //A PAGAR
                                                    }elseif($saida_res['status'] == 2){                                                        
                                                        $tooltip = "Pagar";
                                                        $action = "pagar";
                                                        $btn = "default";
                                                        $fa = "thumbs-down";
                                                        
                                                        if($saida_res['data_vencimento'] >= $data_hj){
                                                            $total_vencer += $saida_res['valor'];
                                                            $qtd_vencer ++;
                                                        }elseif($saida_res['data_vencimento'] < $data_hj){
                                                            $total_vencido += $saida_res['valor'];
                                                            $qtd_vencido ++;
                                                        }
                                                    }
                                                    
                                                    $total += $saida_res['valor'];
                                                    $qtd_total ++;
                                                ?>
                                                <tr id="tr_<?php echo $saida_res['id_saida']; ?>">
                                                    <td><?php echo $saida_res['id_saida']; ?></td>
                                                    <td>
                                                        <?php echo $saida_res['nome']; ?>
                                                        <br />
                                                        <span class="f-s-10"><?php echo $saida_res['nome_tipo']; ?></span>
                                                    </td>
                                                    <td><?php echo converteData($saida_res['data_vencimento'], "d/m/Y"); ?></td>
                                                    <td><?php echo $saida_res['nome_conta']; ?></td>
                                                    <td>
                                                        <?php echo formataMoeda($saida_res['valor']); ?><br />
                                                        <span class="f-s-10"><?php echo getTipoPgt($saida_res['tipo_pgt']); ?></span>
                                                    </td>
                                                    <td class="text-center" id="status_<?php echo $saida_res['id_saida']; ?>"><?php echo getStatus($saida_res['status'], 2); ?></td>
                                                    
                                                    <?php if($global->verificaPermissoesAcoes('2,3,4')){ ?>
                                                    <td class="text-center hidden-print">
                                                        <!--<a href="javascript:;" class="btn btn-info btn-icon btn-square btn-sm btn_user toolt" id="toolt_view" data-action="visualizar" data-original-title="Visualizar" data-key="<?php echo $saida_res['id_saida']; ?>"><i class="fa fa-search"></i></a>-->
                                                        <?php if($global->verificaPermissoesAcoes(4)){ ?>
                                                        <a href="javascript:;" class="btn btn-<?php echo $btn; ?> btn-icon btn-sm btn_user toolt pg_est_<?php echo $saida_res['id_saida']; ?>" id="toolt_view" data-action="<?php echo $action; ?>" data-original-title="<?php echo $tooltip; ?>" data-key="<?php echo $saida_res['id_saida']; ?>"><i class="fa fa-<?php echo $fa; ?>"></i></a>
                                                        <?php } ?>
                                                        
                                                        <?php if($global->verificaPermissoesAcoes(2)){ ?>
                                                        <a href="javascript:;" class="btn btn-warning btn-icon btn-square btn-sm btn_user toolt" data-action="editar" data-original-title="Editar" data-key="<?php echo $saida_res['id_saida']; ?>"><i class="fa fa-pencil"></i></a>                                                        
                                                        <?php } ?>
                                                        
                                                        <?php if($global->verificaPermissoesAcoes(3)){ ?>
                                                        <a href="javascript:;" class="btn btn-danger btn-icon btn-square btn-sm btn_user toolt" data-action="excluir" data-original-title="Excluir" data-key="<?php echo $saida_res['id_saida']; ?>"><i class="fa fa-minus"></i></a>                                                        
                                                        <?php } ?>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="row m-t-15">                                        
                                        <div class="col-md-3 col-sm-6">
                                            <div class="widget widget-stats bg-silver-darker">
                                                <div class="stats-icon"><?php echo $qtd_pago; ?></div>
                                                <div class="stats-info">
                                                    <h4>PAGO</h4>
                                                    <p><?php echo formataMoeda($total_pago); ?></p>	
                                                </div>
                                            </div>
                                        </div>                                        
                                        <div class="col-md-3 col-sm-6">
                                            <div class="widget widget-stats bg-silver-darker">
                                                <div class="stats-icon"><?php echo $qtd_vencido; ?></div>
                                                <div class="stats-info">
                                                    <h4>A PAGAR (VENCIDAS)</h4>
                                                    <p><?php echo formataMoeda($total_vencido); ?></p>	
                                                </div>                                                           
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="widget widget-stats bg-silver-darker">
                                                <div class="stats-icon"><?php echo $qtd_vencer; ?></div>
                                                <div class="stats-info">
                                                    <h4>A PAGAR (A VENCER)</h4>
                                                    <p><?php echo formataMoeda($total_vencer); ?></p>	
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="widget widget-stats bg-silver-darker">
                                                <div class="stats-icon"><?php echo $qtd_total; ?></div>
                                                <div class="stats-info">
                                                    <h4>VALOR TOTAL</h4>
                                                    <p><?php echo formataMoeda($total); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
        
        <!--DATA TABLE-->
        <script src="../assets/plugins/DataTables/js/jquery.dataTables.js"></script>        
	<script src="../assets/plugins/DataTables/js/dataTables.tableTools.js"></script>
        <script src="../assets/plugins/DataTables/js/dataTables.responsive.js"></script>
	<script src="../assets/js/table-manage-tabletools.demo.min.js"></script>
        <script src="../assets/js/table-manage-responsive.demo.min.js"></script>
        
	<script src="../assets/js/apps.min.js"></script>
        
        <script src="../resources/js/global.js?t=<?php AlteracaoFile('global.js'); ?>"></script>
        <script src="../resources/js/saida.js?t=<?php AlteracaoFile('saida.js'); ?>"></script>
        <script>
            $(function(){
                var config_dTable = <?php echo json_encode($config_dataTable); ?>;
                config_dTable['width'] = "11%";
                
                //FUNÇÃO QUE CHAMA DATATABLE E JÁ ENVIA AS CONFIGURAÇÕES DA MESMA
                getDataTable(null, config_dTable, <?php echo $target; ?>);
            });
        </script>
    </body>
</html>