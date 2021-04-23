<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$cliente = new ClienteSalao();

/*
 * USADO O NOME DO SUBMENU
 * PARA DEFINIR O NAME/ID DO INPUT HIDDEN
 * QUE SERÁ PREENCHIDO
 * EX: usuario
 */
$area = "cliente";
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-heading-btn">
                                        <a href="javascript:;" data-click="panel-expand"><i class="fa fa-lg fa-expand icon-blue"></i></a>
                                    </div>
                                    <h4 class="panel-title">Controle de Clientes</h4>
                                </div>
                                <div class="clear-both"></div>
                                <div class="panel-body">
                                    <div class="row text-right m-r-0 m-b-10">
                                        <a href="javascript:;" class="btn btn-info btn_user" data-action="cadastrar"><i class="fa fa-plus"></i> Novo Cliente</a>
                                    </div>
                                    <div class="table-responsive">
                                        <?php
                                        $sql = $cliente->getListaCliente();                                                                                
                                        $cliente_tot = mysql_num_rows($sql);
                                        
                                        if($cliente_tot > 0){
                                        ?>
                                        <table id="data-table" class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="t-w-5">Cod</th>
                                                    <th>Nome</th>
                                                    <th>Whatsapp</th>
                                                    <th>Celular</th>                                                    
                                                    <th>Email</th>
                                                    <th class="t-w-15">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                while($res = mysql_fetch_assoc($sql)){
                                                ?>
                                                <tr id="tr_<?php echo $res['id_cliente']; ?>">
                                                    <td><?php echo $res['id_cliente']; ?></td>
                                                    <td><?php echo $res['nome']; ?></td>                                                                                                     
                                                    <td><?php echo ($res['whatsapp'] != 0) ? mascara_stringTel($res['whatsapp']) : ""; ?></td>
                                                    <td><?php echo ($res['celular'] != 0) ? mascara_stringTel($res['celular']) : ""; ?></td>                                                                                                                                                                                                                                                              
                                                    <td><?php echo $res['email']; ?></td>                                                                                                     
                                                    <td class="text-center">                                                                                                                
                                                        <a href="javascript:;" class="btn btn-primary btn-icon btn-square btn-sm btn_user toolt" id="toolt_view" data-action="visualizar" data-original-title="Visualizar" data-key="<?php echo $res['id_cliente']; ?>"><i class="fa fa-search"></i></a>
                                                        <a href="javascript:;" class="btn btn-warning btn-icon btn-square btn-sm btn_user toolt" data-action="editar" data-original-title="Editar" data-key="<?php echo $res['id_cliente']; ?>"><i class="fa fa-pencil"></i></a>                                                        
                                                        <a href="javascript:;" class="btn btn-danger btn-icon btn-square btn-sm btn_user toolt" data-action="excluir" data-original-title="Excluir" data-key="<?php echo $res['id_cliente']; ?>"><i class="fa fa-trash"></i></a>
                                                    </td>
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
        <script src="../assets/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="../assets/js/apps.min.js"></script>

        <script src="../resources/js/global.js"></script>
        <script src="../resources/js/cliente.js"></script>
        <script>
            $('#data-table').DataTable({
                "columnDefs": [{ type: 'portugues', targets: "_all" }],
                "order": [[1, "asc"]],
                "pagingType": "full_numbers",
            });

            // $(function(){
            //     $("#data-table").DataTable({
            //         "pagingType": "full_numbers",
            //         "order": [[1, "asc"]]
            //     });
            // });
        </script>
    </body>
</html>