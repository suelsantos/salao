<?php
require('../class/ConfigClass.php');

$financeiro = new Financeiro();

/*
 * USADO O NOME DO SUBMENU
 * PARA DEFINIR O NAME/ID DO INPUT HIDDEN
 * QUE SERÁ PREENCHIDO
 * EX: usuario
 */

$area = "extrato";

$list_unidades = $global->getSelectUnidade(null, true, $_SESSION['id_usuario']);
$conta = $global->getContaBancaria(null, '- Todas -');
$servico = $global->getServico(null, '- Todos -');
$profissional = $global->getProfissional(null, '- Todos -');
$cliente = $global->getCliente(null, '- Todos -');

$filtro = false;
$trava_data = false;

if(!empty($_REQUEST['filtrar'])){
    if(($_REQUEST['relatorio']['data_ini'] == "") || ($_REQUEST['relatorio']['data_fim']  == "")){
        $trava_data = true;
    }
    
    if(!$trava_data){
        $filtro = true;
        $result = $financeiro->getExtrato($_REQUEST['relatorio']);
    
        $total = count($result);
    }
    
    $slc_unidade = $_REQUEST['relatorio']['id_unidade'];
    $slc_tipo = $_REQUEST['relatorio']['tipo'];
    $slc_conta = $_REQUEST['relatorio']['id_conta'];
    $slc_servico = $_REQUEST['relatorio']['id_servico'];
    $slc_profissional = $_REQUEST['relatorio']['id_profissional'];
    $slc_cliente = $_REQUEST['relatorio']['id_cliente'];
    $slc_tipo_pgto = $_REQUEST['relatorio']['tipo_pgt'];
    $slc_data_ini = $_REQUEST['relatorio']['data_ini'];
    $slc_data_fim = $_REQUEST['relatorio']['data_fim'];
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
        <link href="../assets/plugins/DataTables/buttons/css/buttons.dataTables.min.css" rel="stylesheet" />
        
        <!--AUTOCOMPLETE-->
        <link rel="stylesheet" href="../assets/plugins/autocomplete/chosen.jquery.css" />
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
                                    </div>
                                    <h4 class="panel-title">Extrato Financeiro</h4>
                                </div>
                                <div class="clear-both"></div>
                                <div class="panel-body">                                    
                                    <div class="row text-right m-r-0 m-b-10">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-md-1 control-label pull-left">Unidade</label>
                                                <div class="col-md-3">
                                                    <?php echo montaSelect($list_unidades, $slc_unidade, "class='form-control input-lg' id='unidade' name='relatorio[id_unidade]'"); ?>
                                                </div>
                                                <label class="col-md-1 control-label pull-left">Tipo</label>
                                                <div class="col-md-3">
                                                    <select name="relatorio[tipo]" id="tipo" class="form-control input-lg">
                                                        <option value="-1">- Todos -</option>
                                                        <option value="1" <?php echo selected("1", $slc_tipo); ?>>Entrada</option>
                                                        <option value="2" <?php echo selected("2", $slc_tipo); ?>>Saída</option>
                                                    </select>
                                                </div>
                                                <label class="col-md-1 control-label pull-left">Conta</label>
                                                <div class="col-md-3">
                                                    <?php echo montaSelect($conta, $slc_conta, "class='form-control input-lg' id='id_conta' name='relatorio[id_conta]'"); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">                                                
                                                <label class="col-md-1 control-label pull-left">Tipo de Pagamento</label>
                                                <div class="col-md-3">
                                                    <select name="relatorio[tipo_pgt]" id="tipo_pgt" class="form-control input-lg">
                                                        <option value="-1">- Todos -</option>
                                                        <option value="1" <?php echo selected("1", $slc_tipo_pgto); ?>>Dinheiro</option>
                                                        <option value="2" <?php echo selected("2", $slc_tipo_pgto); ?>>Cheque</option>
                                                        <option value="3" <?php echo selected("3", $slc_tipo_pgto); ?>>Transferência Bancária</option>
                                                        <option value="4" <?php echo selected("4", $slc_tipo_pgto); ?>>Cartão de Crédito</option>
                                                        <option value="5" <?php echo selected("5", $slc_tipo_pgto); ?>>Cartão de débito</option>
                                                    </select>
                                                </div>
                                                <label class="col-md-1 control-label pull-left">Serviço</label>
                                                <div class="col-md-3 text-left">
                                                    <?php echo montaSelect($servico, $slc_servico, "class='chosen-select form-control input-lg' id='id_servico' name='relatorio[id_servico]'"); ?>
                                                </div>
                                                <label class="col-md-1 control-label pull-left">Profissional</label>
                                                <div class="col-md-3 text-left">
                                                    <?php echo montaSelect($profissional, $slc_profissional, "class='chosen-select form-control input-lg' id='id_profissional' name='relatorio[id_profissional]'"); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">  
                                                <label class="col-md-1 control-label pull-left">Cliente</label>
                                                <div class="col-md-3 text-left">
                                                    <?php echo montaSelect($cliente, $slc_cliente, "class='chosen-select form-control input-lg' id='id_cliente' name='relatorio[id_cliente]'"); ?>
                                                </div>
                                                <label class="col-md-1 control-label pull-left">Período</label>
                                                <div class="col-md-3">
                                                    <div class="input-group date" id="datepicker-disabled-past" data-date-format="dd-mm-yyyy" data-date-start-date="Date.default">
                                                        <input class="form-control input-lg date_picker mask_data" name="relatorio[data_ini]" type="text" id="data_ini" maxlength="11" value="<?= $slc_data_ini ?>">
                                                        <div class="input-group-addon">até</div>
                                                        <input class="form-control input-lg date_picker mask_data" name="relatorio[data_fim]" type="text" id="data_fim" maxlength="11" value="<?= $slc_data_fim ?>">
                                                    </div>
                                                </div>
                                            </div>
                                                                                                                                    
                                            <input type="submit" class="btn btn-info pull-right" id="filtrar" name="filtrar" value="Filtrar">
                                        </div>
                                    </div> 
                                    
                                    <?php if($trava_data){ ?>
                                    <div class="note note-danger">
                                        Campo <b>Período</b> é obrigatório
                                    </div>
                                    <?php } ?>
                                    
                                    <hr class="hr" />
                                    
                                    <?php if(($filtro) && (!$trava_data)){ ?>
                                    <div class="table-responsive">
                                        <?php if($total > 0){ ?> 
                                        <table id="data-tableA" class="table table-striped table-hover table-condensed display nowrap" width="100%">
                                            <thead>
                                                <tr>
                                                    <th class="t-w-5 desktop tablet-l tablet-p mobile-l">Data</th>
                                                    <th>Conta</th>
                                                    <th>Descrição</th>
                                                    <th>Categoria</th>                                                    
                                                    <th>Tipo de Pgto</th>
                                                    <th>Valor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total_receita = 0;
                                                $total_despesa = 0;
                                                
                                                foreach ($result as $key => $res){
                                                    if($res['tipo'] == 'entrada'){
                                                        $signal = '&nbsp;&nbsp;';
                                                        $icon = 'fa-caret-up text-info';
                                                        $total_receita += $res['valor'];
                                                                
                                                    } else {
                                                        $signal = '- ';
                                                        $icon = 'fa-caret-down text-danger';
                                                        $total_despesa += $res['valor'];
                                                    }
                                                ?>
                                                    <tr>
                                                        <td><?php echo $res['data']; ?></td>
                                                        <td><?php echo $res['conta']; ?></td>
                                                        <td><?php echo $res['nome']; ?></td>
                                                        <td><?php echo quebraTexto($res['categoria'], 35); ?></td>                                                                                                                                                                
                                                        <td><?php echo $res['tipo_pgto']; ?></td>
                                                        <td>
                                                            <span class="m-r-10 fa <?= $icon ?>"></span>
                                                            <?php echo $signal . formataMoeda($res['valor']); ?>
                                                        </td>
                                                    </tr>
                                                <?php                                                 
                                                } 
                                                
                                                $total_saldo = $total_receita - $total_despesa;                                                                                                
                                                $cor_saldo = ($total_saldo < 0) ? 'danger' : 'success';
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td class="text-right p-15">
                                                        <h5 class="l-h-2">Total de Receitas:</h5>
                                                    </td>
                                                    <td class="text-left p-15">
                                                        <p class="f-s-20 l-h-2 label label-success"><?= formataMoeda($total_receita) ?></p>
                                                    </td>                                                    
                                                    <td class="text-right p-15">
                                                        <h5 class="l-h-2">Total de Despesas:</h5>
                                                    </td>
                                                    <td class="text-left p-15">
                                                        <p class="f-s-20 l-h-2 label label-danger"><?= formataMoeda($total_despesa) ?></p>
                                                    </td>                                                    
                                                    <td class="text-right p-15">                                                        
                                                        <h5 class="l-h-2">Saldo no Período:</h5>
                                                    </td>
                                                    <td class="text-left p-15">
                                                        <p class="f-s-20 l-h-2 label label-<?= $cor_saldo ?>"><?= formataMoeda($total_saldo) ?></p>
                                                    </td>                                                    
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <?php }else{ ?>                                        
                                        <div class="note note-warning">
                                            <h5>Nenhum registro encontrado</h5>                                            
                                        </div>
                                        <?php } ?>
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
	<script src="../assets/js/apps.min.js"></script>
        
        <!--DATA TABLE-->
        <script src="../assets/plugins/DataTables/buttons/js/jquery.dataTables.min.js"></script>
	<script src="../assets/plugins/DataTables/js/dataTables.tableTools.js"></script>
        <script src="../assets/plugins/DataTables/js/dataTables.responsive.js"></script>        
	<script src="../assets/js/table-manage-tabletools.demo.min.js"></script>
        <script src="../assets/js/table-manage-responsive.demo.min.js"></script>
        <script src="../assets/plugins/DataTables/buttons/js/dataTables.buttons.min.js"></script>
        <script src="../assets/plugins/DataTables/buttons/js/buttons.flash.min.js"></script>
        <script src="../assets/plugins/DataTables/buttons/js/jszip.min.js"></script>
        <script src="../assets/plugins/DataTables/buttons/js/pdfmake.min.js"></script>
        <script src="../assets/plugins/DataTables/buttons/js/vfs_fonts.js"></script>
        <script src="../assets/plugins/DataTables/buttons/js/buttons.html5.min.js"></script>
        <script src="../assets/plugins/DataTables/buttons/js/buttons.print.min.js"></script>
        
        <!--AUTOCOMPLETE-->
        <script src="../assets/plugins/autocomplete/chosen.jquery.js"></script>
        
        <!--DATE PICKER-->
        <script src="../assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        
        <!--MASCARA-->
        <script src="../assets/plugins/masked-input/maskedinput.js"></script>
                
        <script src="../resources/js/global.js?t=<?php AlteracaoFile('global.js'); ?>"></script>
        
        <script>
            $(function(){
                var config_dTable = <?php echo json_encode($config_dataTable); ?>;
                
                //FUNÇÃO QUE CHAMA DATATABLE E JÁ ENVIA AS CONFIGURAÇÕES DA MESMA
                getDataTable(null, config_dTable, null, "#data-tableA", null, true, "Extrato Financeiro");
            });
        </script>
    </body>
</html>