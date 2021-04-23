$(function () {
    $(".btn_user").on("click", function() {
        var action = $(this).data("action");
        var key = $(this).data("key");
        
        if(action === "log") {
            $("#usuario").val(key);
            $("#form1").attr('action','usuario_log.php');
            $("#form1").submit();      
            
        }else if(action === "visualizar"){
            $.post("usuario_view.php", {id_user: key}, function(data){
                bootDialog(data,'Detalhes do Usuário');
            });
            
        }else if(action === "cadastrar"){
            $("#form1").attr('action','usuario_form.php');
            $("#form1").submit();
            
        }else if(action === "editar"){
            $("#usuario").val(key);
            $("#form1").attr('action','usuario_form.php');
            $("#form1").submit();        
        
        }else if(action === "desativar"){
            var key = $(this).data("key");
            
            BootstrapDialog.confirm('Deseja realmente desativar esse usuario?', 'Confirmação de Exclusão', function(result) {
                if (result) {                    
                    $.ajax({
                        type: "post",
                        url: "../methods.php",
                        dataType: "json",
                        data: {
                            id: key,
                            method: "desativa_usuario"
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
        
        }else if(action === "resetar_senha"){
            var key = $(this).data("key");
            
            BootstrapDialog.confirm('Deseja realmente resetar a senha?', 'Confirmação de Reset', function(result) {
                if (result) {
                    $.ajax({
                        type: "post",
                        url: "../methods.php",
                        dataType: "json",
                        data: {
                            id: key,
                            method: "resetar_senha"
                        },
                        success: function(data) {
                            if(data.status == "1"){
                                bootAlert('Senha resetada com sucesso! Senha: <b>123456</b>','Reset de senha');
                            }
                        }
                    });
                }
            },
            'danger');
        }
    });
    
    //MODAL FOTO DO PERFIL
    $(".altera_foto").on("click", function() {
        var key = $(this).data("key");
        
        $.post("usuario_foto.php", {id_user: key, method: "altera_foto"}, function(data){
            bootDialog(data,'Foto do Perfil', true);
        });
    });
    
    $(".altera_senha").on("click", function() {    
        $("#campos_alt_senha").toggle();
        $("#campos_alt_senha").removeClass('hide');
        $("#senha_usuario").val('');
        $("#confsenha_usuario").val('');
    });
});