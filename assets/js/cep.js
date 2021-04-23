function getEndereco() {    
    var cep = $("#cep").val().replace(/\D/g, '');
        
    if($.trim(cep) != ""){
        $('#page-loader').removeClass("hide");  
        
        var validacep = /^[0-9]{8}$/;
        
        if(validacep.test(cep)) {        
            $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                if (!("erro" in dados)) {
                    $('#logradouro_').attr("disabled","disabled");
                    $('#bairro_').attr("disabled","disabled");
                    $('#cidade_').attr("disabled","disabled");
                    $('#estado').attr("disabled","disabled");
                    
                    $("#logradouro").val(dados.logradouro);
                    $("#logradouro_").val(dados.logradouro);
                    $("#bairro").val(dados.bairro);
                    $("#bairro_").val(dados.bairro);
                    $("#cidade").val(dados.localidade);
                    $("#cidade_").val(dados.localidade);                
                    $("#estado option[value='"+dados.uf+"']").attr("selected", true);
                    $("#uf").val(dados.uf);
                    
                    $("#num").focus();                    
                    $('#page-loader').addClass("hide");                    
                    
                } else {                    
                    bootAlert('Endereço não encontrado, preencha manualmente', 'Busca por CEP', null, 'danger');
                    //$("#enderecoCompleto").show("slow");
                    $('#page-loader').addClass("hide");
                    $("#select_uf").removeClass("hide");
                    $('#logradouro_').removeAttr("disabled");
                    $('#bairro_').removeAttr("disabled");
                    $('#cidade_').removeAttr("disabled");
                    $('#estado').removeAttr("disabled");      
                    
                    $("#logradouro").val("");
                    $("#logradouro_").val("");
                    $("#bairro").val("");
                    $("#bairro_").val("");
                    $("#cidade").val("");
                    $("#cidade_").val("");                    
                    $("#estado option[value='']").attr("selected", true);
                    $("#uf").val("");
                    
                    return false;
                }
            });
            
        } else {
            bootAlert('Formato de CEP inválido','Busca por CEP', null, 'danger');
        }
        
    } else {        
        bootAlert('Preencha o campo CEP','Busca por CEP', null, 'danger');
    }
    
    return false;	
}