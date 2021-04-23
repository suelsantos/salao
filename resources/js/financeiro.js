$(function () {
    
    //CATEGORIAS
    $("body").on("click", '.btn_categoria', function() {    
        var action = $(this).data("action");
        var key = $(this).data("key");                
        var tipo = $(this).data("tipo");                
        
        if(action === "cadastrar"){
            $.post("categoria_form.php", {id: key}, function(data){
                bootDialog(data,'Cadastro de Categoria', true);
            });
        
        }else if(action === "editar"){
            $.post("categoria_form.php", {id: key}, function(data){
                bootDialog(data,'Edição de Categoria', true);
            });
        
        }else if(action === "excluir"){
            var key = $(this).data("key");
            
            BootstrapDialog.confirm('Deseja realmente excluir essa categoria?', 'Confirmação de Exclusão', function(result) {
                if (result) {
                    $.ajax({
                        type: "post",
                        url: "../methods.php",
                        dataType: "json",
                        data: {
                            id: key,
                            tipo: tipo,
                            method: "exclui_categoria"
                        },
                        success: function(data) {
                            if(data.status == "1"){
                                $("#tr_categoria_"+key).fadeOut();
                                
                            }else if(data.status == "2"){
                                
                                var nome_tipo = "";
                                
                                if(data.tipo == "1"){
                                    nome_tipo = "entrada(s)";
                                }else if(data.tipo == "2"){
                                    nome_tipo = "saida(s)";
                                }
                                
                                bootAlert('Categoria não pode ser excluída, pois existe(m) <b>'+data.qtd+'</b> '+nome_tipo+' vinculada(s)', 'Exclusão de Categoria', null, 'danger');
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
    
    //CONTAS BANCARIAS
    $("body").on("click", '.btn_conta', function() {    
        var action = $(this).data("action");
        var key = $(this).data("key");                
        
        if(action === "cadastrar"){
            $.post("conta_form.php", {id: key}, function(data){
                bootDialog(data,'Cadastro de Conta Bancária', true);
            });
        
        }else if(action === "editar"){
            $.post("conta_form.php", {id: key}, function(data){
                bootDialog(data,'Edição de Conta Bancária', true);
            });
        
        }else if(action === "excluir"){
            var key = $(this).data("key");
            
            BootstrapDialog.confirm('Deseja realmente excluir essa conta?', 'Confirmação de Exclusão', function(result) {
                if (result) {
                    $.ajax({
                        type: "post",
                        url: "../methods.php",
                        dataType: "json",
                        data: {
                            id: key,
                            method: "exclui_conta"
                        },
                        success: function(data) {
                            if(data.status == "1"){
                                $("#tr_conta_"+key).fadeOut();
                            }else if(data.status == "2"){
                                bootAlert('Conta não pode ser excluída, pois existe(m) <b>'+data.qtd+'</b> entrada(s)/saída(s) vinculada(s)', 'Exclusão de Conta', null, 'danger');
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