$(function () {
    $("body").on("click", '.btn_user', function() {    
        var action = $(this).data("action");
        var key = $(this).data("key");                
        var tipo = $(this).data("tipo");                
        
        if(action === "visualizar"){            
            $.post("entrada_view.php", {id: key}, function(data){
                bootDialog(data,'Detalhes da Entrada', true);
            });
        
        }else if(action === "cadastrar"){                        
            $("#_tipo").val(tipo);
            $("#form1").attr('action','entrada_form.php');
            $("#form1").submit();
        
        }else if(action === "editar"){
            $("#entrada").val(key);
            $("#form1").attr('action','entrada_form.php');
            $("#form1").submit();
        
        }else if(action === "excluir"){
            var key = $(this).data("key");
            
            BootstrapDialog.confirm('Deseja realmente excluir essa entrada?', 'Confirmação de Exclusão', function(result) {
                if (result) {
                    $.ajax({
                        type: "post",
                        url: "../methods.php",
                        dataType: "json",
                        data: {
                            id: key,
                            method: "exclui_entrada"
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
        
        }else if(action === "estornar"){                                               
            BootstrapDialog.confirm('Deseja realmente ESTORNAR essa entrada?', 'Confirmação de Estorno', function(result) {
                if (result) {
                    $.ajax({
                        type: "post",
                        url: "../methods.php",
                        dataType: "json",
                        data: {
                            id: key,
                            method: "estorna_entrada"
                        },
                        success: function(data) {
                            if(data.status == "1"){
                                $(".pg_est_"+key).removeClass("btn-info").addClass("btn-default");
                                $(".pg_est_"+key).data("action", "receber").data("original-title", "Receber").attr("data-action", "receber").attr("data-original-title", "Receber");
                                $(".pg_est_"+key+" .fa").removeClass("fa-thumbs-up").addClass("fa-thumbs-down");
                                $("#status_"+key+" .label").removeClass("label-success").addClass("label-warning").html("A RECEBER");
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
        
        }else if(action === "receber"){
            BootstrapDialog.confirm('Deseja realmente RECEBER essa entrada?', 'Confirmação de Recebimento', function(result) {
                if (result) {
                    $.ajax({
                        type: "post",
                        url: "../methods.php",
                        dataType: "json",
                        data: {
                            id: key,
                            method: "recebe_entrada"
                        },
                        success: function(data) {
                            if(data.status == "1"){
                                $(".pg_est_"+key).removeClass("btn-default").addClass("btn-info");
                                $(".pg_est_"+key).data("action", "estornar").data("original-title", "Estornar").attr("data-action", "estornar").attr("data-original-title", "Estornar");
                                $(".pg_est_"+key+" .fa").removeClass("fa-thumbs-down").addClass("fa-thumbs-up");
                                $("#status_"+key+" .label").removeClass("label-warning").addClass("label-success").html("RECEBIDA");
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
    
    //FILTRO NA LISTAGEM DE ENTRADAS
    $(".filt_periodo").click(function(){
        var value = $(this).data("value");
       
        $("#filtro").val(value);        
        $("#form1").submit();
    });
    
    $(".filt_status").click(function(){
        var value = $(this).data("value");
       
        $("#filtro2").val(value);        
        $("#form1").submit();
    });
});