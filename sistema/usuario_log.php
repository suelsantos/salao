<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$user = new Usuario();

$id_user = $_REQUEST['usuario'];
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
            <div id="page-container" class="fade page-sidebar-fixed page-header-fixed page-with-light-sidebar">

                <!--TOPO-->
                <?php include('../template/header.php'); ?>

                <!--BARRA LATERAL-->
                <?php include('../template/sidebar.php'); ?>

                <div id="content" class="content">
                    <ol class="breadcrumb pull-right m-b-15">
                        <li><a href="usuario_list.php">Usuários</a></li>                    
                        <li class="active">Histórico do Usuário</li>
                    </ol>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-heading-btn">
                                        <a href="javascript:;" data-click="panel-expand"><i class="fa fa-lg fa-expand icon-blue"></i></a>                                        
                                    </div>
                                    <h4 class="panel-title">Histórico do Usuário</h4>
                                </div>
                                <div class="clear-both"></div>
                                <div class="panel-body">                                
                                    <div class="table-responsive">
                                        <?php
                                        $log_sql = $user->getLog($id_user);
                                        ?>                                    
                                        <table id="data-table" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>                                                
                                                    <th class="t-w-5">Cod</th>
                                                    <th>Data e Hora</th>
                                                    <th>Unidade</th>
                                                    <th>Local</th>
                                                    <th>Ação</th>
                                                    <th>IP</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($log_res = mysql_fetch_assoc($log_sql)){ ?>
                                                <tr>                                                
                                                    <td><?php echo $log_res['id_log']; ?></td>
                                                    <td><?php echo $log_res['data_log']; ?></td>                                                
                                                    <td><?php echo $log_res['unidade']; ?></td>                                                
                                                    <td><?php echo $log_res['local']; ?></td>                                                
                                                    <td><?php echo $log_res['acao']; ?></td>                                                
                                                    <td><?php echo $log_res['ip']; ?></td>                                                
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- begin scroll to top btn -->
                <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
                <!-- end scroll to top btn -->
            </div>
            <!-- end page container -->
        </form>
	
	<script src="../assets/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="../assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="../assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	
        <!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
		<script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	
        <script src="../assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="../assets/plugins/jquery-cookie/jquery.cookie.js"></script>                                
        
	<script src="../assets/js/dashboard.min.js"></script>
        <script src="../assets/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="../assets/js/apps.min.js"></script>
        
        <script src="../resources/js/global.js"></script>
        <script src="../resources/js/usuario.js"></script>                
        <script>
            $(function(){
                $('#data-table').DataTable({
                    "pagingType": "full_numbers",
                    "order": [[ 0, "desc" ]]
                });
            });
        </script>
    </body>
</html>