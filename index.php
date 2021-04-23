<?php
require('./class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../adm");
}

//$cidadao = new Cidadao();
$entrada = new Entrada();
$saida = new Saida();

// LISTA PARA AGENDA
$sql = $global->getListaContato();

// TOTAIS DE CIDADAOS
//$lista_status = $cidadao->listaStatusCidadao();

//foreach ($lista_status as $val) {
//    $qtd_cidadaos_status[$val['nome']] = $cidadao->contaCidadaoPorStatus($val['id_situacao'], $_SESSION['id_unidade']);
//    
//    if($qtd_cidadaos_status[$val['nome']] > 0){
//        $data[] = "['{$val['nome']}', {$qtd_cidadaos_status[$val['nome']]}]";
//    }
//}

// LISTA DE ANIVERSARIANTES
$sql_niver = $global->getAniversariantes();
$tot_niver = mysql_num_rows($sql_niver);

$permissao_adm = true;

//PERMISSÃO REF A ADM
if($global->verificaPermissoesBotoes(1)){
    $permissao_adm = false;
    $col_adm = 6;
}else{
    $col_adm = 12;
}

//LISTA ENTRADAS DO DIA
$entrada_dia_sql = $entrada->getListaEntrada("hoje", "todas");
$entrada_dia_tot = mysql_num_rows($entrada_dia_sql);

//LISTA SAIDAS DO DIA
$saida_dia_sql = $saida->getListaSaida("hoje", "todas");
$saida_dia_tot = mysql_num_rows($saida_dia_sql);

//LISTA PROXIMAS SAIDAS (10 DIAS)
$saida_prox_sql = $saida->getListaSaida(null, "a_vencer", date('Y-m-d'), date('Y-m-d', strtotime('+10 day', strtotime(date('Y-m-d')))), "A.data_vencimento");
$saida_prox_tot = mysql_num_rows($saida_prox_sql);
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
        
        <link rel="shortcut icon" href="<?php echo FAVICON; ?>">
	
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="assets/css/animate.min.css" rel="stylesheet" />
	<link href="assets/css/style.min.css" rel="stylesheet" />
	<link href="assets/css/style-responsive.min.css" rel="stylesheet" />
	<link href="assets/css/theme/default.css" rel="stylesheet" id="theme" />	
	
	<link href="assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
        <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />	
        
        <!--DIALOG-->
        <link href="assets/css/bootstrap-dialog.min.css" rel="stylesheet" />
        
        <!--AUTOCOMPLETE-->
        <link rel="stylesheet" href="assets/plugins/autocomplete/chosen.jquery.css" />
	
	<script src="assets/plugins/pace/pace.min.js"></script>	
    </head>
    <body>
        <form action="" method="post" name="form1" class="form-horizontal" id="form1" enctype="multipart/form-data">
            <div id="page-loader" class="fade in"><span class="spinner"></span></div>
            
            <div id="page-container" class="fade page-sidebar-fixed page-header-fixed page-with-light-sidebar">
                
                <!--TOPO-->
                <?php include('template/header.php'); ?>
                
                <!--BARRA LATERAL-->
                <?php include('template/sidebar.php'); ?>
                
                <div id="content" class="content">                             
                    <div class="row">
                        <?php if($global->verificaPermissoesBotoes(11)){ ?>
                        <div class="col-md-6">
                            <div class="panel panel-default">                                
                                <div class="panel-heading">
                                    <div class="panel-heading-btn">                                    
                                        <a href="financeiro/entrada_list.php" class="toolt" data-original-title="Lista"><i class="fa fa-list fa-lg"></i></a>
                                    </div>
                                    <h2 class="panel-title">
                                        Entradas do dia <br />
                                        <small>Lista de entradas de hoje</small>
                                    </h2>
                                </div>
                                <div class="panel-body">                                    
                                    <?php if($entrada_dia_tot > 0){ ?>
                                    <div data-scrollbar="true" class="scroll_">
                                        <table id="data-table" class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Valor</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($entrada_dia_res = mysql_fetch_assoc($entrada_dia_sql)){ ?>
                                                <tr>
                                                    <td>
                                                        <?php 
                                                        if($entrada_dia_res['nome'] == "" && $entrada_dia_res['id_cidadao'] != 0){
                                                            echo $entrada_dia_res['nome_cidadao'];
                                                        }else{
                                                            echo $entrada_dia_res['nome']; 
                                                        }
                                                        ?>
                                                        <br />
                                                        <span class="f-s-10"><?php echo $entrada_dia_res['nome_tipo']; ?></span>
                                                    </td>
                                                    <td>
                                                        <?php echo formataMoeda($entrada_dia_res['valor']); ?><br />
                                                        <span class="f-s-10"><?php echo getTipoPgt($entrada_dia_res['tipo_pgt']); ?></span>
                                                    </td>
                                                    <td><?php echo getStatus($entrada_dia_res['status'], 1); ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php }else{ ?>
                                    <div class="alert alert-warning">Nenhuma Entrada Hoje</div>
                                    <?php } ?>                                    
                                </div>
                                
                                <?php if($global->verificaPermissoesAcoes(5)){ ?>
                                <div class="panel-footer text-right">
                                    <a href="javascript:;" class="btn btn-info cad_entrada"><i class="fa fa-plus"></i> Nova Entrada</a>
                                </div>
                                <?php } ?>  
                            </div>
                        </div>
                        <?php } ?>
                        
                        <?php if($global->verificaPermissoesBotoes(10)){ ?>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-heading-btn">
                                        <a href="financeiro/saida_list.php" class="toolt" data-original-title="Lista"><i class="fa fa-list fa-lg"></i></a>
                                    </div>
                                    <h2 class="panel-title">
                                        Saídas do dia <br />
                                        <small>Lista de saidas de hoje</small>
                                    </h2>
                                </div>
                                <div class="panel-body">
                                    <?php if($saida_dia_tot > 0){ ?>
                                    <div data-scrollbar="true" class="scroll_">
                                        <table id="data-table" class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Valor</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($saida_dia_res = mysql_fetch_assoc($saida_dia_sql)){ ?>
                                                <tr>
                                                    <td>
                                                        <?php 
                                                        if($saida_dia_res['nome'] == "" && $saida_dia_res['id_cidadao'] != 0){
                                                            echo $saida_dia_res['nome_cidadao'];
                                                        }else{
                                                            echo $saida_dia_res['nome']; 
                                                        }
                                                        ?>
                                                        <br />
                                                        <span class="f-s-10"><?php echo $saida_dia_res['nome_tipo']; ?></span>
                                                    </td>
                                                    <td>
                                                        <?php echo formataMoeda($saida_dia_res['valor']); ?><br />
                                                        <span class="f-s-10"><?php echo getTipoPgt($saida_dia_res['tipo_pgt']); ?></span>
                                                    </td>
                                                    <td><?php echo getStatus($saida_dia_res['status'], 2); ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php }else{ ?>
                                    <div class="alert alert-warning">Nenhuma Saída Hoje</div>                        
                                    <?php } ?>                                    
                                </div>
                                
                                <?php if($global->verificaPermissoesAcoes(1)){ ?>
                                <div class="panel-footer text-right">
                                    <a href="javascript:;" class="btn btn-danger cad_saida"><i class="fa fa-plus"></i> Nova Saida</a>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    
                    <?php if($global->verificaPermissoesBotoes(10)){ ?>
                    <div class="row">                           
                        <div class="col-md-12">
                            <div class="panel panel-default">                                
                                <div class="panel-heading">                                   
                                    <h2 class="panel-title">
                                        Contas à Vencer <br />
                                        <small>Lista de contas dos próximos 10 dias</small>
                                    </h2>
                                </div>
                                <div class="panel-body">
                                    <?php if($saida_prox_tot > 0){ ?>
                                    <div data-scrollbar="true" class="scroll_">
                                        <table id="data-table" class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Data</th>
                                                    <th>Nome</th>
                                                    <th>Valor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($saida_prox_res = mysql_fetch_assoc($saida_prox_sql)){ ?>
                                                <tr>
                                                    <td><?php echo converteData($saida_prox_res['data_vencimento'], "d/m/Y"); ?></td>
                                                    <td>
                                                        <?php
                                                        if($saida_prox_res['nome'] == "" && $saida_prox_res['id_cidadao'] != 0){
                                                            echo $saida_prox_res['nome_cidadao'];
                                                        }else{
                                                            echo $saida_prox_res['nome']; 
                                                        }
                                                        ?>
                                                        <br />
                                                        <span class="f-s-10"><?php echo $saida_prox_res['nome_tipo']; ?></span>
                                                    </td>
                                                    <td>
                                                        <?php echo formataMoeda($saida_prox_res['valor']); ?><br />
                                                        <span class="f-s-10"><?php echo getTipoPgt($saida_prox_res['tipo_pgt']); ?></span>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php }else{ ?>
                                    <div class="alert alert-warning">Nenhuma Conta à Vencer</div>
                                    <?php } ?>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <?php } ?>
                    
                    <div class="row">   
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">                                    
                                    <h2 class="panel-title">
                                        Aniversariantes da semana <span class="badge badge-danger badge-square"><?php echo $tot_niver; ?></span> <br />
                                        <small>Parabenize seus clientes e funcionários</small>
                                    </h2>
                                </div>
                                <div class="panel-body">
                                    <div data-scrollbar="true" class="scroll_">
                                        <ul class="media-list media-list-with-divider">
                                            <li class="media media-sm">
                                                <div class="row">
                                                    <?php while($row_niver = mysql_fetch_assoc($sql_niver)){ 
                                                        $img = "uploads/".COD_IGREJA."/clientes/".$row_niver['id_cliente'].".jpeg";
                                                        
                                                        if(!file_exists($img)){
                                                            $img = "uploads/".COD_IGREJA."/clientes/0.jpg";
                                                        }
                                                    ?>
                                                    <div class="col-md-6 col-sm-6 col-xs-12 m-b-15">
                                                        <a class="media-left" href="javascript:;">
                                                            <img src="<?php echo $img; ?>" alt="" class="media-object rounded-corner">
                                                        </a>
                                                        <div class="media-body">
                                                            <h5 class="media-heading"><?php echo $row_niver['nome']; ?></h5>
                                                            <p class="m-b-3"><?php echo $row_niver['data_nascimento']; ?></p>
                                                            
                                                            <?php if(!empty($row_niver['whatsapp'])){ ?>                                                            
                                                            <a href="https://web.whatsapp.com/send?phone=55<?= $row_niver['whatsapp'] ?>&text=Ol%C3%A1%20<?=$row_niver['apelido']?>%2C%20o%20Espa%C3%A7o%20Mari%20de%20Sales%20deseja%20muitas%20felicidades%20neste%20dia." target="_blank" class="btn btn-success btn-icon btn-circle btn-sm" title="Whatsapp"><i class='fa fa-whatsapp'></i></a>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <?php }
                                                    if($tot_niver == 0){                                                    
                                                    ?>     
                                                    <div class="alert alert-warning">Nenhum Aniversariante</div>
                                                    <?php } ?>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <!--<div class="col-md-<?php // echo $col_adm; ?>">-->
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">                                    
                                    <h2 class="panel-title">
                                        Agenda de Contatos <br />
                                        <small>Consulta todos os contatos</small>
                                    </h2>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <select data-placeholder="Digite o Nome" class="chosen-select form-control input-lg" id="agenda_cont">
                                                <option value=""><?php echo ($mobile) ? "Selecione um Nome" : ""; ?></option>
                                                <?php while($res = mysql_fetch_assoc($sql)){ ?>
                                                <option data-tipo="<?= $res['tipo'] ?>" value="<?php echo $res['id']; ?>">
                                                    <?php echo $res['nome']." ({$res['tipo']})"; ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                
                    <div class="row"> 
                        
                    </div>
                </div>

                <!-- begin scroll to top btn -->
                <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
                <!-- end scroll to top btn -->
            </div>
            <!-- end page container -->
        </form>
	
	<script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	
        <!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
		<script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
        
        <!--HIGHCHARTS-->
        <script src="assets/plugins/highcharts/js/highcharts.js"></script>
        <script src="assets/plugins/highcharts/js/highcharts-3d.js"></script>
        <script src="assets/plugins/highcharts/js/modules/exporting.js"></script>
        
        <!--AUTOCOMPLETE-->
        <script src="assets/plugins/autocomplete/chosen.jquery.js"></script>
        
        <!--SCROLL-->
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        
        <!--DIALOG-->
        <script src="assets/js/bootstrap-dialog.min.js"></script>
        
	<script src="assets/js/dashboard.min.js"></script>
	<script src="assets/js/apps.min.js"></script>
        
        <script src="resources/js/global.js?t=<?php AlteracaoFile('global.js'); ?>"></script>
        <script src="resources/js/index.js?t=<?php AlteracaoFile('index.js'); ?>"></script>
        <script>
            $(function () {                                                   
                //CHART DE CIDADAOS
                $('#highjs_cidadao').highcharts({
                    chart: {
                        type: 'pie',
                        renderTo: 'container'
                    },
                    exporting: {
                        enabled: false
                    },
                    title: {
                        text: null
                    },
                    plotOptions: {
                        series: {
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b> ({point.y:,.0f})',
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                                softConnector: true
                            },
                            neckWidth: '30%',
                            neckHeight: '25%'               
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.percentage:.0f}%</b><br/>'
                    },
                    series: [{            
                        name: 'Percentual',
                        data: [<?php echo join($data, ',') ?>]                        
                    }]
                });
            });
        </script>
    </body>
</html>