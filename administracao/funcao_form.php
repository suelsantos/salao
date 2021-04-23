<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$administracao = new Administracao();

$id_funcao = $_REQUEST['id'];

$qry = $administracao->getFuncaoF("id_funcao", $id_funcao);
$row = mysql_fetch_assoc($qry);

if(!empty($id_funcao)){
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

<form action="" method="post" name="form_funcao" class="form-horizontal" id="form_funcao" data-parsley-validate enctype="multipart/form-data">
    <input type="hidden" name="funcao" value="<?php echo $id_funcao; ?>" />        
    
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
                                <label class="col-md-1 control-label">Nome</label>
                                <div class="col-md-11">
                                    <input type="text" class="form-control input-lg" name="nome" id="nome" data-parsley-required value="<?php echo $row['nome']; ?>" />
                                </div>
                            </div>                            
                            
                            <button type="button" class="btn btn-info pull-right" id="acao_funcao" data-action="<?php echo $fa_botao; ?>" data-key="<?php echo $row['id_funcao']; ?>" name="<?php echo $fa_botao; ?>"><span class="fa fa-<?php echo $fa_botao; ?>"></span>&nbsp;&nbsp;<?php echo $botao; ?></button>
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
        $("#acao_funcao").on("click", function(e){
            e.preventDefault();
            
            var action = $(this).data("action");
            var key = $(this).data("key");
            var form = $("#form_funcao");
            var nome = $("#nome").val();            
            
            form.parsley().validate();
            
            if(action === "save"){
                if (form.parsley().isValid()){
                    $.ajax({
                        type: "post",
                        url: "../methods.php",
                        dataType: "json",
                        data: {
                            nome: nome,
                            method: "cadastra_funcao"
                        },
                        success: function(data) {
                            if(data.status == "1"){
                                $("#msg_").removeClass("hide");
                                $("#msg_").fadeIn("slow").delay(2500).fadeOut("slow");
                                $("#nome").val("");                                
                                $("#msg_").removeClass("alert-danger").removeClass("alert-error");
                                $("#msg_").addClass("alert-success");                                
                                $("#msg_ span").html('Função <b>'+data.nome+'</b> cadastrada com sucesso.');   
                                
                                $(".close").click(function(){
                                   window.location.reload();
                                });
                                
                            }else if(data.status == "2"){
                                $("#msg_").removeClass("hide");
                                $("#msg_").fadeIn("slow");                                
                                $("#msg_").removeClass("alert-success").removeClass("alert-error");
                                $("#msg_").addClass("alert-danger");
                                $("#msg_ span").html('Função <b>'+data.nome+'</b> já existe.');
                                
                            }else{
                                $("#msg_").removeClass("hide");
                                $("#msg_").fadeIn("slow");
                                $("#msg_").removeClass("alert-success").removeClass("alert-error");
                                $("#msg_").addClass("alert-danger");
                                $("#msg_ span").html('ERRO ao cadastrar, tente mais tarde');
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
                            method: "edita_funcao"
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
                                $("#msg_ span").html('Função <b>'+data.nome+'</b> atualizada com sucesso.');  
                                //$("#td_nome_funcao_"+data.id).html(data.nome);
                                $("#tr_funcao_"+data.id+" td.sorting_1").html(data.nome);
                                
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
    });
</script>