<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$financeiro = new Financeiro();

$id_categoria = $_REQUEST['id'];

//AÇÔES
if(isset($_REQUEST['save'])){
    $financeiro->cadCategoria();
}elseif(isset($_REQUEST['refresh'])){
    $financeiro->editCategoria($id_categoria);
    $id_categoria = $financeiro->cod;
}

$qry = $financeiro->getCategoriaF("id_tipo", $id_categoria);
$row = mysql_fetch_assoc($qry);

if(!empty($id_categoria)){
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

<form action="" method="post" name="form_categoria" class="form-horizontal" id="form_categoria" data-parsley-validate enctype="multipart/form-data">
    <input type="hidden" name="categoria" value="<?php echo $id_categoria; ?>" />        
    
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
                            <div class="form-group">
                                <label class="col-md-1 control-label">Tipo</label>
                                <div class="col-md-11">
                                    <select name="tipo" id="tipo" class="form-control input-lg" data-parsley-required>
                                        <option value="">Selecione um Tipo</option>
                                        <option value="1" <?php echo selected(1, $row['tipo']); ?>>Entrada</option>
                                        <option value="2" <?php echo selected(2, $row['tipo']); ?>>Saída</option>
                                    </select>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-info pull-right" id="acao_categoria" data-action="<?php echo $fa_botao; ?>" data-key="<?php echo $row['id_tipo']; ?>" name="<?php echo $fa_botao; ?>"><span class="fa fa-<?php echo $fa_botao; ?>"></span>&nbsp;&nbsp;<?php echo $botao; ?></button>
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
        $("#acao_categoria").on("click", function(e){
            e.preventDefault();
            
            var action = $(this).data("action");
            var key = $(this).data("key");
            var form = $("#form_categoria");
            var nome = $("#nome").val();
            var tipo = $("#tipo").val();
            
            form.parsley().validate();
            
            if(action === "save"){
                if (form.parsley().isValid()){
                    $.ajax({
                        type: "post",
                        url: "../methods.php",
                        dataType: "json",
                        data: {
                            nome: nome,
                            tipo: tipo,
                            method: "cadastra_categoria"
                        },
                        success: function(data) {
                            if(data.status == "1"){
                                $("#msg_").removeClass("hide");
                                $("#msg_").fadeIn("slow").delay(2500).fadeOut("slow");
                                $("#nome").val("");
                                $("#tipo").val("");
                                $("#msg_").removeClass("alert-danger").removeClass("alert-error");
                                $("#msg_").addClass("alert-success");                                
                                $("#msg_ span").html('Categoria <b>'+data.nome+'</b> cadastrada com sucesso.');  
                                
                                $(".close").click(function(){
                                   window.location.reload();
                                });
                            
                            }else if(data.status == "2"){
                                $("#msg_").removeClass("hide");
                                $("#msg_").fadeIn("slow");                                
                                $("#msg_").removeClass("alert-success").removeClass("alert-error");
                                $("#msg_").addClass("alert-danger");
                                $("#msg_ span").html('Categoria <b>'+data.nome+'</b> já existe.');
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
                            tipo: tipo,
                            method: "edita_categoria"
                        },
                        success: function(data) {
                            if(data.status == "1"){
                                $("#msg_").removeClass("hide");
                                $("#campos_form").hide();
                                $("#msg_ .close").hide();
                                $("#msg_").fadeIn("slow");
                                $("#nome").val("");
                                $("#tipo").val("");
                                $("#msg_").removeClass("alert-danger").removeClass("alert-error");
                                $("#msg_").addClass("alert-success");
                                $("#msg_ span").html('Categoria <b>'+data.nome+'</b> editada com sucesso.');      
                                $("#td_nome_"+data.id).html(data.nome);
                                $("#td_tipo_"+data.id).html(data.nome_tipo);
                                
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