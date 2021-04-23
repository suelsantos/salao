$(function () {                
    $("body").on("click", '.btn_unidade', function() {
        var action = $(this).data("action");
        var key = $(this).data("key");
        
        if(action === "visualizar"){
            $.post("unidade_view.php", {id: key}, function(data){
                bootDialog(data,'Detalhes da Unidade', true);
            });
        
        }else if(action === "cadastrar"){
            $("#form1").attr('action','unidade_form.php');
            $("#form1").submit();
            
        }else if(action === "editar"){
            $("#unidade").val(key);
            $("#form1").attr('action','unidade_form.php');
            $("#form1").submit();
        
        }else if(action === "excluir"){
            var key = $(this).data("key");
            
            BootstrapDialog.confirm('Deseja realmente excluir essa unidade?', 'Confirmação de Exclusão', function(result) {
                if (result) {
                    $.ajax({
                        type: "post",
                        url: "../methods.php",
                        dataType: "json",
                        data: {
                            id: key,
                            method: "exclui_unidade"
                        },
                        success: function(data) {
                            if(data.status == "1"){
                                $("#tr_"+key).fadeOut();
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
            },
            'danger');
        
        }
    });
});