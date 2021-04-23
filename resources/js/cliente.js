$(function () {
    $(".btn_user").on("click", function() {
        var action = $(this).data("action");
        var key = $(this).data("key");
        
        if(action === "visualizar"){
            $.post("cliente_view.php", {id: key}, function(data){
                bootDialog(data,'Detalhes do Cliente', true);
            });
        
        }else if(action === "cadastrar"){
            $("#form1").attr('action','cliente_form.php');
            $("#form1").submit();
            
        }else if(action === "editar"){
            $("#cliente").val(key);
            $("#form1").attr('action','cliente_form.php');
            $("#form1").submit();        
        
        }else if(action === "excluir"){
            var key = $(this).data("key");                        
            
            BootstrapDialog.confirm('Deseja realmente excluir esse cliente?', 'Confirmação de Exclusão', function(result) {
                if (result) {                    
                    $.ajax({
                        type: "post",
                        url: "../methods.php",
                        dataType: "json",
                        data: {
                            id: key,
                            method: "exclui_cliente"
                        },
                        success: function(data) {                            
                            if(data.status == "1"){
                                $("#tr_"+key).fadeOut();
                            }
                        }
                    });
                }
            },
            'danger');
        
        }
    });
    
    $(".altera_foto").on("click", function() {
        var key = $(this).data("key");
        
        $.post("cliente_foto.php", {cod: key, method: "altera_foto"}, function(data){
            bootDialog(data,'Foto do Cliente', true);
        });
    });
});