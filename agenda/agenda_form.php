<?php
require('../class/ConfigClass.php');

$ini = $_REQUEST['ini'];
$fim = $_REQUEST['fim'];
$id_profissional = $_REQUEST['id_profissional'];
$id_agenda = $_REQUEST['id_agenda'];

//EXPLODE A DATETIME PARA PEGAR A DATA E A HORA
$exp_ini = explode("T", $ini);
$exp_fim = explode("T", $fim);

$data = $exp_ini[0];
$hora_ini = date("H:i", strtotime($exp_ini[1]));
$hora_fim = date("H:i", strtotime($exp_fim[1]));

$agenda = new Agenda();

$profissional = $agenda->getProfissional();
$cliente = $agenda->getCliente();
$servico = $agenda->getServico();

if(!empty($id_agenda)){
    $title_page = "Alteração";
    $botao = "Atualizar";
    $fa_botao = "refresh";
    
    $dados_agenda = mysql_fetch_assoc($agenda->getAgenda(null, $id_agenda));
    
    $sql_servico = $agenda->getServicoAssoc($dados_agenda['id']);
    
    while($res_servico = mysql_fetch_assoc($sql_servico)){        
        $dados_agenda['servicos'][$res_servico['id_servico']] = inicialPalavraMaiuscula($res_servico['nome']);
    }
    
}else{
    $title_page = "Cadastro";
    $botao = "Salvar";
    $fa_botao = "save";
}
?>

<form action="" method="post" name="form_agenda" class="form-horizontal" id="form_agenda" data-parsley-validate enctype="multipart/form-data">
    <input type="hidden" name="agenda" value="<?php echo $id_agenda; ?>" />        
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-dismissible m-b-15 hide" id="msg_">
                                <button type="button" class="close" data-dismiss="alert">×</button> 
                                <span></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" id="campos_form">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Profissional</label>
                                <div class="col-md-10">
                                    <?php echo montaSelect($profissional, $id_profissional, "class='chosen-select form-control input-lg' data-parsley-required id='id_profissional' name='cadastro[id_profissional]'"); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Cliente</label>
                                <div class="col-md-10">
                                    <?php echo montaSelect($cliente, $dados_agenda['id_cliente'], "class='chosen-select form-control input-lg' data-parsley-required id='id_cliente' name='cadastro[id_cliente]'"); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Serviço</label>
                                <div class="col-md-10">
                                    <select class="multiple-select2 form-control input-lg" id="id_servico" multiple="multiple" data-parsley-required name="cadastro[id_servico]">
                                        <?php
                                        foreach($servico as $id_servico => $nome_servico){
                                            foreach ($dados_agenda['servicos'] as $serv_id_bd => $serv_bd){
                                                if($id_servico === $serv_id_bd){
                                                    $selected = 'selected';
                                                }                                                                                             
                                            }
                                            
                                            echo "<option {$selected} value='{$id_servico}'>".acentoMaiusculo($nome_servico)."</option>";
                                            
                                            unset($selected);
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Data</label>
                                <div class="col-md-10">
                                    <div class="input-group date" id="datepicker-disabled-past" data-date-format="dd-mm-yyyy" data-date-start-date="Date.default">
                                        <input type="text" class="form-control input-lg data_agenda" name="cadastro[data]" data-parsley-required data-parsley-type="databr" id="data_" value="<?php echo ($data != 0) ? converteData($data, "d/m/Y") : ''; ?>">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Inicio</label>
                                <div class="col-md-4">
                                    <div class="input-group date" id="datepicker-disabled-past" data-date-format="dd-mm-yyyy" data-date-start-date="Date.default">
                                        <input type="time" step="1800" class="form-control input-lg hora_ini" name="cadastro[hora_ini]" data-parsley-required id="hora_ini" value="<?php echo ($hora_ini != 0) ? $hora_ini : ''; ?>">
                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                    </div>
                                </div>
                                <label class="col-md-2 control-label">Fim</label>
                                <div class="col-md-4">
                                    <div class="input-group date" id="datepicker-disabled-past" data-date-format="dd-mm-yyyy" data-date-start-date="Date.default">
                                        <input type="time" step="1800" class="form-control input-lg hora_fim" name="cadastro[hora_fim]" data-parsley-required id="hora_fim" value="<?php echo ($hora_fim != 0) ? $hora_fim : ''; ?>">
                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" id="id_unidade" value="<?php echo $_SESSION['id_unidade'] ?>" />
                            
                            <button type="button" class="btn btn-info pull-right m-t-15 acao_agenda" data-action="<?php echo $fa_botao; ?>" data-key="<?php echo $id_agenda; ?>" name="<?php echo $fa_botao; ?>"><span class="fa fa-<?php echo $fa_botao; ?>"></span>&nbsp;&nbsp;<?php echo $botao; ?></button>
                            
                            <?php if(!empty($id_agenda)){ ?>
                            <button type="button" class="btn btn-danger pull-left m-t-15 acao_agenda" id="del_agenda" data-original-title="Excluir" data-action="delete" data-key="<?php echo $id_agenda; ?>" name="exclui_agenda"><span class="fa fa-trash"></span></button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function(){
        
        $(".data_agenda").mask("99/99/9999");
        
        $('.chosen-select').chosen();
        
        $(".data_agenda").datepicker({
            todayHighlight:!0,
            autoclose:!0,
            format: 'dd/mm/yyyy'
        });
        
        $("#del_agenda").tooltip();
        
        $(".multiple-select2").select2({
            placeholder:"Selecione"
        });
        
        //CADASTRO/EDIÇÃO DE AGENDA
        $(".acao_agenda").on("click", function(e){
            e.preventDefault();
            
            var action = $(this).data("action");
            var key = $(this).data("key");
            var form = $("#form_agenda");
            
            var profissional = $("#id_profissional").val();
            var cliente = $("#id_cliente").val();
            var servico = $("#id_servico").val();
            var data = $("#data_").val();
            var hora_ini = $("#hora_ini").val();
            var hora_fim = $("#hora_fim").val();
            var unidade = $("#id_unidade").val();                            
            
            if(action === "save"){
                form.parsley().validate();
                
                if (form.parsley().isValid()){
                    $.ajax({
                        type: "post",
                        url: "methods.php",
                        dataType: "json",
                        data: {
                            profissional: profissional,
                            cliente: cliente,
                            servico: servico,
                            data: data,
                            hora_ini: hora_ini,
                            hora_fim: hora_fim,
                            unidade: unidade,
                            method: "cadastra_agenda"
                        },
                        success: function(data) {
                            if(data.status == "1"){
                                window.location.reload();                            
                            }else{
                                alert('ERRO ao cadastrar, tente mais tarde');                                
                            }
                        }
                    });
                }
            
            }else if(action === "refresh"){
                form.parsley().validate();
                
                if (form.parsley().isValid()){
                    $.ajax({
                        type: "post",
                        url: "methods.php",
                        dataType: "json",
                        data: {
                            id: key,
                            profissional: profissional,
                            cliente: cliente,
                            servico: servico,
                            data: data,
                            hora_ini: hora_ini,
                            hora_fim: hora_fim,
                            unidade: unidade,
                            method: "edita_agenda"
                        },
                        success: function(data) {
                            if(data.status == "1"){
                                window.location.reload();                            
                            }else{                                
                                bootAlert('ERRO ao editar, tente mais tarde', 'Edição de agenda', null, 'danger');
                            }
                        }
                    });
                }
                
            }else if(action === "delete"){
                BootstrapDialog.confirm('Deseja realmente excluir?', 'Confirmação de Exclusão', function(result) {
                    if (result) {                    
                        $.ajax({
                            type: "post",
                            url: "methods.php",
                            dataType: "json",
                            data: {
                                id: key,
                                method: "exclui_agenda"
                            },
                            success: function(data) {
                                if(data.status == "1"){
                                    window.location.reload();
                                }else{
                                    alert('ERRO ao excluir');
                                }
                            }
                        });
                    }
                },
                'danger');
            }
        });
    });
</script>