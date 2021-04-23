$(function () {    
    //CARGOS    
    $("body").on("click", '.btn_cargo', function() {
        var action = $(this).data("action");
        var key = $(this).data("key");                
        
        if(action === "cadastrar"){
            $.post("cargo_form.php", {id: key}, function(data){
                bootDialog(data,'Cadastro de Cargo', true);
            });
        
        }else if(action === "editar"){
            $.post("cargo_form.php", {id: key}, function(data){
                bootDialog(data,'Edição de Cargo', true);
            });
        
        }
    });     
    
    //PROFISSOES
    $("body").on("click", '.btn_profissao', function() {    
        var action = $(this).data("action");
        var key = $(this).data("key");                
        
        if(action === "cadastrar"){
            $.post("profissao_form.php", {id: key}, function(data){
                bootDialog(data,'Cadastro de Profissão', true);
            });
        
        }else if(action === "editar"){
            $.post("profissao_form.php", {id: key}, function(data){
                bootDialog(data,'Edição de Profissão', true);
            });
        
        }
    });            
    
    //FUNCOES
    $("body").on("click", '.btn_servico', function() {    
        var action = $(this).data("action");
        var key = $(this).data("key");                
        
        if(action === "cadastrar"){
            $.post("servico_form.php", {id: key}, function(data){
                bootDialog(data,'Cadastro de Serviço', true);
            });
        
        }else if(action === "editar"){
            $.post("servico_form.php", {id: key}, function(data){
                bootDialog(data,'Edição de Serviço', true);
            });
        
        }
    });            
    
    //FORMAS DE ENTRADA
    $("body").on("click", '.btn_forma_entrada', function() {
        var action = $(this).data("action");
        var key = $(this).data("key");
        
        if(action === "cadastrar"){
            $.post("forma_entrada_form.php", {id: key}, function(data){
                bootDialog(data,'Cadastro de Forma de Entrada', true);
            });
        
        }else if(action === "editar"){
            $.post("forma_entrada_form.php", {id: key}, function(data){
                bootDialog(data,'Edição de Forma de Entrada', true);
            });
        
        }
    });            
    
    //SITUACOES
    $("body").on("click", '.btn_situacao', function() {    
        var action = $(this).data("action");
        var key = $(this).data("key");                
        
        if(action === "cadastrar"){
            $.post("situacao_form.php", {id: key}, function(data){
                bootDialog(data,'Cadastro de Situação', true);
            });
        
        }else if(action === "editar"){
            $.post("situacao_form.php", {id: key}, function(data){
                bootDialog(data,'Edição de Situação', true);
            });
        
        }
    });
    
    //FORMAÇÕES TEOLÓGICAS
    $("body").on("click", '.btn_formacao', function() {    
        var action = $(this).data("action");
        var key = $(this).data("key");                
        
        if(action === "cadastrar"){
            $.post("formacao_form.php", {id: key}, function(data){
                bootDialog(data,'Cadastro de Formação', true);
            });
        
        }else if(action === "editar"){
            $.post("formacao_form.php", {id: key}, function(data){
                bootDialog(data,'Edição de Formação', true);
            });
        
        }
    });            
});