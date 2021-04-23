<?php
require('class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<!--[if IE 8]> 
<html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="pt-br">
<!--<![endif]-->
    <head><meta charset="euc-jp">
	
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
        
        <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
        
        <!--AUTOCOMPLETE-->
        <link rel="stylesheet" href="assets/plugins/autocomplete/chosen.jquery.css" />
        
        <!--FULL CALENDAR-->
        <link rel="stylesheet" href="assets/plugins/fullcalendar/fullcalendar.css" />
        
        <!--MODAL-->
        <link href="assets/css/bootstrap-dialog.min.css" rel="stylesheet" />
        
        <!--DATE PICKER-->
        <link href="assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
        
        <!--MULTIPLE SELECT-->
        <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
	
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
                    <div class="panel panel-inverse">
                        <div class="panel-body">
                            <div id='calendar'></div>
                        </div>
                    </div>
                </div>
                                
                <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>                
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
        
        <!--FULL CALENDAR-->
        <script src="assets/plugins/fullcalendar/lib/moment.min.js"></script>
        <script src="assets/plugins/fullcalendar/fullcalendar.min.js"></script>
        <script src="assets/plugins/fullcalendar/lang/pt-br.js"></script>
        <script src="assets/plugins/fullcalendar/scheduler.min.js"></script>        
        <!--<script src="assets/js/calendar.demo.min.js"></script>-->
        
        <!--MODAL-->
        <script src="assets/js/bootstrap-dialog.min.js"></script>
        
        <!--DATE PICKER-->
        <script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        
        <!--MASCARA-->
        <script src="assets/plugins/masked-input/maskedinput.js"></script>
        
        <!--VALIDADOR FORM-->
        <script src="assets/plugins/parsley/dist/parsley.js"></script>
        
        <!--MULTIPLE SELECT-->
        <script src="assets/plugins/select2/dist/js/select2.min.js"></script>
        
	<script src="assets/js/dashboard.min.js"></script>
	<script src="assets/js/apps.min.js"></script>
        
        <script src="resources/js/global.js"></script>
        <script>
            $(function() {
                
		$('#calendar').fullCalendar({
                    lang: 'pt-br',                    
                    defaultView: 'agendaDay',
                    allDaySlot: false,
                    axisFormat: 'HH:mm',
                    schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                    minTime: '07:00:00',
                    maxTime: '21:00:00',
                    timeFormat: 'HH:mm',
                    slotEventOverlap: false,
                    //slotLabelInterval: '00:30:00',
                    //editable: true,
                    eventLimit: true,
                    contentHeight: 'auto',
                    destroy: true,
                    //defaultDate: '2016-06-20',
                    header: {
                        left: "today prev,next",
                        center: "title",
                        right: "month,agendaWeek,agendaDay"
                    },
                    views: {
                        week: {
                            selectable: false,
                            selectHelper: false
                        },
                        day: {
                            selectable: true,
                            selectHelper: true
                        }
                    },                    
                    columnFormat: {
                        month: 'ddd',
                        week: 'ddd D',
                        day: 'dddd d/M'
                    },
                    resources: {
                        url: 'methods.php',
                        type: 'POST',
                        data:{
                            method:"get_profissionais_agenda"
                        },
                        error: function() {
                            alert('Agendamento não encontrado');
                        }
                    },
                    events: {
                        url: 'methods.php',
                        type: 'POST',
                        data:{
                            method:"get_agendamentos"
                        },
                        error: function() {
                            alert('Agendamento não encontrado');
                        }
                    },
                    
                    //CADASTRAR
                    select: function(start, end, jsEvent, view, resource) {
                        var ini = start.format();
                        var fim = end.format();
                        var id_profissional = resource.id;
                        
                        $.post("agenda/agenda_form.php", {
                            ini: ini, 
                            fim: fim, 
                            id_profissional: id_profissional, 
                            method: "cad_agenda"
                        }, function(data){
                            bootDialog(data,'Cadastro de Agenda', true);
                        });
                        
                        /*
                        console.log(
                            'select',
                            start.format(),
                            end.format(),
                            resource ? resource.id : '(no resource)'
                        );
                        */
                    },
                    
                    //EDITAR
                    eventClick: function(event) {                        
                        var ini = event.start._i;
                        var fim = event.end._i;
                        var id_agenda = event.id;
                        var id_profissional = event.resourceId;
                        
                        $.post("agenda/agenda_form.php", {
                            ini: ini, 
                            fim: fim, 
                            id_agenda: id_agenda, 
                            id_profissional: id_profissional, 
                            method: "edit_agenda"
                        }, function(data){
                            bootDialog(data,'Edição de Agenda', true);
                        });
                    }
                    
                    //CADASTRAR
//                    dayClick: function(date, jsEvent, view, resource) {
                        
                        //console.log(date, resource);
                        
//                        var start = date.format();
//                        var id_profissional = resource.id;
//                        
//                        $.post("agenda/form_agenda.php", {
//                            start: start, 
//                            id_profissional: id_profissional, 
//                            method: "cad_agenda"
//                        }, function(data){
//                            bootDialog(data,'Cadastro de Agenda', true);
//                        });
//                        console.log(
//                            'dayClick',
//                            date.format(),
//                            resource ? resource.id : '(no resource)'
//                        );
//                    },                                        
                    
                    /*
                    resources: [
                        { id: 'a', title: 'Meire' },
                        { id: 'b', title: 'Paula' }
                    ],
                    events: [
                        { id: '2', resourceId: 'a', start: '2016-05-07T12:00:00', end: '2016-05-07T13:00:00', title: 'ALZIRA' },
                        { id: '2', resourceId: 'a', start: '2016-05-07T16:00:00', end: '2016-05-07T17:00:00', title: 'ALZIRA' },
                        { id: '3', resourceId: 'b', start: '2016-05-07T10:00:00', end: '2016-05-07T11:00:00', title: 'MARGARIDA' }
                    ],
                    select: function(start, end, jsEvent, view, resource) {
                        console.log(
                            'select',
                            start.format(),
                            end.format(),
                            resource ? resource.id : '(no resource)'
                        );
                    }
                    */
		});
	
            });
        </script>
    </body>
</html>