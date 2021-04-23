<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$financeiro = new Financeiro();

$id_conta = $_REQUEST['id'];

$qry = $financeiro->getContaF("id_conta", $id_conta);
$row = mysql_fetch_assoc($qry);

$qry_conta_dizimo = $financeiro->verificaContaPadraoDizimo();
$res_conta_dizimo = mysql_fetch_assoc($qry_conta_dizimo);
$tot_conta_dizimo = mysql_num_rows($qry_conta_dizimo);

/*
 * DESABILITA O CHECKBOX DE PADRÃO DE DÍZIMO
 * QUANDO JÁ EXISTIR UMA CONTA DEFINIDA COMO PADRÃO
 */
if($tot_conta_dizimo > 0){
    if($row['padrao_dizimo'] == 0){
        $disabled = "disabled";
    }
}

if(!empty($id_conta)){
    $title_page = "Alteração";
    $botao = "Atualizar";
    $fa_botao = "refresh";
}else{
    $title_page = "Cadastro";
    $botao = "Salvar";
    $fa_botao = "save";
}

//exit();
?>

<form action="" method="post" name="form_conta" class="form-horizontal" id="form_conta" data-parsley-validate enctype="multipart/form-data">
    <input type="hidden" name="conta" value="<?php echo $id_conta; ?>" />        
    
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
                                <label class="col-md-3 control-label">Nome</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control input-lg" name="nome" id="nome" data-parsley-required value="<?php echo $row['nome']; ?>" />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-3 control-label">Padrão</label>
                                <div class="col-md-9">                                    
                                    <input type="checkbox" name="padrao_dizimo" id="padrao_dizimo" <?php echo $disabled; ?> class="_switchSN" <?php echo checked("1", $row['padrao_dizimo']); ?> />
                                    
                                    <?php if(($tot_conta_dizimo > 0) && ($row['padrao_dizimo'] == 0)){ ?>
                                    <a class="btn btn-warning btn-icon btn-circle btn-xs pop_hover" tabindex="0" data-toggle="popover" title="Padrão desabilitado?" data-trigger="focus" data-content="Já existe uma conta definada como padrão. Somente uma pode ser padrão"><i class="fa fa-question"></i></a>
                                    <?php } ?>
                                </div>
                            </div>                            
                            
                            <button type="button" class="btn btn-info pull-right" id="acao_conta" data-action="<?php echo $fa_botao; ?>" data-key="<?php echo $row['id_conta']; ?>" name="<?php echo $fa_botao; ?>"><span class="fa fa-<?php echo $fa_botao; ?>"></span>&nbsp;&nbsp;<?php echo $botao; ?></button>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function(){        
        //CADASTRO/EDIÇÃO DE CATEGORIA
        $("#acao_conta").on("click", function(e){
            e.preventDefault();
            
            var action = $(this).data("action");
            var key = $(this).data("key");
            var form = $("#form_conta");
            var nome = $("#nome").val();
            
            var padrao_dizimo = 0;
            
            if($("#padrao_dizimo").is(":checked")){
                padrao_dizimo = 1;
            }
            
            form.parsley().validate();
            
            if(action === "save"){
                if (form.parsley().isValid()){
                    $.ajax({
                        type: "post",
                        url: "../methods.php",
                        dataType: "json",
                        data: {
                            nome: nome,
                            padrao_dizimo: padrao_dizimo,
                            method: "cadastra_conta"
                        },
                        success: function(data) {
                            if(data.status == "1"){
                                $("#msg_").removeClass("hide");
                                $("#msg_").fadeIn("slow").delay(2500).fadeOut("slow");
                                $("#nome").val("");                                
                                $("#msg_").removeClass("alert-danger").removeClass("alert-error");
                                $("#msg_").addClass("alert-success");                                
                                $("#msg_ span").html('Conta <b>'+data.nome+'</b> cadastrada com sucesso.'); 
                                
                                $(".close").click(function(){
                                   window.location.reload();
                                });
                            }
                        },
                        beforeSend: function(){
                            $('#page-loader').removeClass("hide");
                        },
                        complete: function(){
                            $('#page-loader').addClass("hide");
                        }
                    });
                }
            
            }else if(action === "refresh"){
                if (form.parsley().isValid()){
                    $.ajax({
                        type: "post",
                        url: "../methods.php",
                        dataType: "json",
                        data: {
                            id: key,
                            nome: nome,
                            padrao_dizimo: padrao_dizimo,
                            method: "edita_conta"
                        },
                        success: function(data) {
                            if(data.status == "1"){
                                $("#msg_").removeClass("hide");
                                $("#campos_form").hide();
                                $("#msg_ .close").hide();
                                $("#msg_").fadeIn("slow");
                                $("#nome").val("");                                
                                $("#msg_").removeClass("alert-danger").removeClass("alert-error");
                                $("#msg_").addClass("alert-success");
                                $("#msg_ span").html('Conta <b>'+data.nome+'</b> editada com sucesso.');
                                $("#td_nome_conta_"+data.id).html(data.nome);
                                
                            }else{
                                $("#msg_").removeClass("hide");
                                $("#msg_").fadeIn("slow");
                                $("#msg_").removeClass("alert-success").removeClass("alert-error");
                                $("#msg_").addClass("alert-danger");
                                $("#msg_ span").html('ERRO ao editar, tente mais tarde');
                            }
                        },
                        beforeSend: function(){
                            $('#page-loader').removeClass("hide");
                        },
                        complete: function(){
                            $('#page-loader').addClass("hide");
                        }
                    });
                }
            }
        });
        
        $("._switchSN").bootstrapSwitch({
            onText: 'SIM',
            offText: 'NÃO'
        });
        
        $('.pop_hover').popover();
    });
</script>