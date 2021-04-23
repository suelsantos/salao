$(document).ready(function() {
    App.init();        
});

$(function () {      
    //ADICIONANDO CLASSE QUE OCULTA/MOSTRA SIDEBAR MENU
    $("#page-container").addClass($(".active_sidebar").html());
    
    //TROCA DE UNIDADE NO HEADER
    $('.bt-troca-unidade').click(function () {
        var unidade_de = $(this).data('regiao');
        var unidade_para = $(this).data('key');
        var url = $(this).data('url');
        
        if(unidade_de != unidade_para){
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    unidade_de: unidade_de,
                    unidade_para: unidade_para,
                    method: "troca_unidade"
                },
                success: function(data) {
                    if(data.status == "1"){
                        location.href = '';
                    }
                }
            });
        }
    });
    
    $("body").on("click", '.sidebar-minify-btn', function() {
        var url = $(this).data('url');
        
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {
                method: "oculta_menu"
            }
        });
    });
    
    $('.edit_perfil_logado').click(function () {
//        var url = $(this).data('url');
//        var key = $(this).data('key');                
//                
//        $("#id_usuario_logado").val(key);
//        $("#form1").attr('action',url);
//        $("#form1").submit();
    });
    
    //TOOLTIPS
    $(".toolt").tooltip();
    
    //CALENDARIO
    $(".date_picker").datepicker({
        todayHighlight:!0,
        autoclose:!0,
        format: 'dd/mm/yyyy'
    });        
    
    //MASCARAS
    if($(".mask_data").length){$(".mask_data").mask("99/99/9999");}
    if($(".mask_data2").length){$(".mask_data2").mask("99/9999");}
    if($(".mask_tel").length){$(".mask_tel").mask("(99) 9999-9999?9");}
    if($(".mask_cep").length){$(".mask_cep").mask("99999-999");}
    if($(".mask_cpf").length){$(".mask_cpf").mask("999.999.999-99");}
    if($(".mask_cartao").length){$(".mask_cartao").mask("9999 9999 9999 9999");}
    if($(".mask_money").length){$(".mask_money").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:','});}    
    
    //AUTOCOMPLETE
    if($(".chosen-select").length){$('.chosen-select').chosen();}
    if($(".chosen-select-deselect").length){$('.chosen-select-deselect').chosen({ allow_single_deselect: true });}   
    
    //TAGIT
    if($("._tagit").length){
        $('._tagit').tagit({
            triggerKeys:['enter', 'comma', 'tab'],
            select: true
        });
    }
    
    //SWITCH SIM/NÃO
    if($("._switchYN").length){
        $("._switchYN").bootstrapSwitch({
            onText: 'SIM',
            offText: 'NÃO'
        });
    }
    
    //BUSCA CEP
    $("#cep").change(function(){
      getEndereco();
    });      
});

//BOOTSTRAPDIALOG
if (typeof BootstrapDialog === 'function') {
    /**
     * Alert window
     * 
     * @param {type} message
     * @param {type} title
     * @param {type} callback
     * @param {type} type
     * @returns {undefined}
     */
    BootstrapDialog.alert = function (message, title, callback, type) {
        if (typeof title === 'undefined' || title === '' || title === null) {
            title = 'Alerta';
        }
        if (typeof type === 'undefined' || type === '' || type === null) {
            type = 'primary';
        }
        new BootstrapDialog({
            nl2br: false,
            type: 'type-' + type,
            title: title,
            message: message,
            data: {
                'callback': callback
            },
            closable: false,
            buttons: [{
                    label: 'OK',
                    action: function (dialog) {
                        typeof dialog.getData('callback') === 'function' && dialog.getData('callback')(true);
                        dialog.close();
                    }
                }]
        }).open();
    };
    
    /**
     * Confirm window
     * 
     * @param {type} message
     * @param {type} tyle
     * @param {type} callback
     * @param {type} type
     * @returns {undefined}
     */
    BootstrapDialog.confirm = function (message, title, callback, type) {
        if (typeof title === 'undefined' || title === '' || title === null) {
            title = 'Confirmação';
        }
        if (typeof type === 'undefined' || type === '' || type === null) {
            type = 'primary';
        }
        new BootstrapDialog({
            nl2br: false,
            title: title,
            message: message,
            closable: false,
            type: 'type-' + type,
            data: {
                'callback': callback
            },
            buttons: [{
                    label: 'Cancelar',
                    action: function (dialog) {
                        typeof dialog.getData('callback') === 'function' && dialog.getData('callback')(false);
                        dialog.close();
                    }
                }, {
                    label: 'OK',
                    cssClass: 'btn-' + type,
                    action: function (dialog) {
                        typeof dialog.getData('callback') === 'function' && dialog.getData('callback')(true);
                        dialog.close();
                    }
                }]
        }).open();
    };

    //ALIAS PARA ALERT
    var bootAlert = function (message, title, callback, type) {
        BootstrapDialog.alert(message, title, callback, type);
    };

    //ALIAS PARA CONFIRM
    var bootConfirm = function (message, title, callback, type) {
        BootstrapDialog.confirm(message, title, callback, type);
    };

    //ALIAS PARA DIALOG
    var bootDialog = function (message, title, close, buttons, type) {
        if (typeof title === 'undefined' || title === '' || title === null) {
            title = 'Mensagem';
        }
        if (typeof type === 'undefined' || type === '' || type === null) {
            type = 'primary';
        }
        
        if (typeof close === 'undefined' || close === '' || close === null) {
            close = false;
        }
        
        BootstrapDialog.show({
            nl2br: false,
            title: title,
            message: message,
            type: 'type-' + type,
            closable: close,
            buttons: buttons
        });
    };
    
    $('.sonumeros').keypress(function (event) {
        var tecla = (window.event) ? event.keyCode : event.which;
        if ((tecla > 47 && tecla < 58))
            return true;
        else {
            if (tecla != 8)
                return false;
            else
                return true;
        }
    });
    
    // funcao para converter data do formato br para o americano
    var converteData = function (data, separador_atual) {
        if (separador_atual == '') {
            separador_atual = '/';
        }
        var dataArr = data.split(' ')[0].split(separador_atual);
        var separador = ((separador_atual === '/')?'-':'/');
        return dataArr[2] + separador + dataArr[1] + separador + dataArr[0];
    };
    
    function cria_carregando_modal(){
        $("body").append(
            $("<div>",{id:"carregando", class:"modal fade"})
                .append($('<div>',{class:"modal-dialog text-center no-margin-t", style:"width: 100%; height:100%; margin-top: 0!important; padding-top: 25%;"})
                    .append($('<img>',{src:location.protocol+"//"+location.host+"/intranet/imagens/loading2.gif", style:"height: 100px;"}))
                )
        );
        $('#carregando').modal('show');
    }
    
    function remove_carregando_modal(){
        $('#carregando').modal('hide');
//        $('#carregando').remove();            
//        $('.modal-backdrop').remove();
    }
}

//CARREGAR PAGINA COM FOCO NO INPUT
function goFocus(elementID){
    document.getElementById(elementID).focus();
}

/*
 * FUNÇÃO PARA CHAMAR DATATABLE, JÁ COM AS CONFIGURAÇÕES
 * method: DEFINE O METODO PARA FAZER BUSCA DOS DADOS POR JSON
 * 
 */
function getDataTable(method = null, item, target = null, id_table = "#data-tableA", dados = null, relatorio = false, title_relatorio = null){
    var width = "";
    
    if (typeof item.width === 'undefined' || item.width === '' || item.width === null) {
        var width = "5%";
    }else{
        var width = item.width;
    }
    
    //SEM REQUISIÇÃO VIA AJAX
    if (typeof method === 'undefined' || method === '' || method === null) {
        if(!relatorio){
            $(id_table).DataTable({
                "pagingType": item.pagingType,
                "responsive": true,
                "language": {
                    "paginate": {
                        "previous": item.previous,
                        "next": item.next,
                        "first": item.first,
                        "last": item.last
                    },
                    "lengthMenu": item.lengthMenu,
                    "search": item.search,
                    "processing": "<i class='fa fa-spinner fa-pulse fa-3x fa-fw'></i><span class='sr-only'>Loading...</span>"
                },
                "columnDefs": [{
                    "width": width,
                    "targets": target,
                    "className": "text-center",
                }]
            });
        }else{
            $(id_table).DataTable({                
                "paging": false,
                "searching": false,
                "pagingType": item.pagingType,
                "responsive": true,
                "language": {
                    "paginate": {
                        "previous": item.previous,
                        "next": item.next,
                        "first": item.first,
                        "last": item.last
                    },
                    "lengthMenu": item.lengthMenu,
                    "search": item.search,
                    "processing": "<i class='fa fa-spinner fa-pulse fa-3x fa-fw'></i><span class='sr-only'>Loading...</span>"
                },
                "columnDefs": [{
                    "width": width,
                    "targets": target,
                    "className": "text-center",
                }],
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'print',
                        footer: true,
                        text: item.buttonTxtPrint,
                        title: title_relatorio,
                        className: 'btn btn-info',
                        exportOptions : {
                            columns: ':visible'
                        },
                    },
                    {
                        extend: 'pdf',
                        footer: true,
                        text: item.buttonTxtPdf,                        
                        title: title_relatorio,
                        className: 'btn btn-danger'
                    },
                    {
                        extend: 'excel',
                        footer: true,
                        text: item.buttonTxtExcel,                        
                        title: title_relatorio,
                        className: 'btn btn-success'
                    }
                ]
            });
        }
    
    }else{    
        $(id_table).DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
                url : "../methods.php",
                type: "post",
                dataType: "json",
                data: {
                    method: method,
                    dados: dados
                },
                error: function(){
                    $(".employee-grid-error").html("");
                    $(id_table).append('<tbody class="employee-grid-error"><tr><th colspan="6">Nenhum registro encontrado</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
                }
            },
            "pagingType": item.pagingType,
            "responsive": true,
            "language": {
                "paginate": {
                    "previous": item.previous,
                    "next": item.next,
                    "first": item.first,
                    "last": item.last                           
                },
                "lengthMenu": item.lengthMenu,
                "search": item.search,
                "processing": "<i class='fa fa-spinner fa-pulse fa-3x fa-fw'></i><span class='sr-only'>Loading...</span>"
            },
            "columnDefs": [{
                "width": width,
                "targets": target,
                "className": "text-center"
            }],
            "fnDrawCallback": function(){
                $(".toolt").tooltip();                
            }
        });
    }
    
    $('.dataTables_filter input').attr("placeholder", item.searchPlaceholder);
}

/**
 * DATATABLE
 * Ordenar considerando acentos
 * Basta acrescentar o código abaixo, dentro do .Datatable para funcionar
 * "columnDefs": [{ type: 'portugues', targets: "_all" }],
 */
jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "portugues-pre": function ( data ) {
        var a = 'a';
        var e = 'e';
        var i = 'i';
        var o = 'o';
        var u = 'u';
        var c = 'c';
        var special_letters = {
            "Á": a, "á": a, "Ã": a, "ã": a, "À": a, "à": a,
            "É": e, "é": e, "Ê": e, "ê": e,
            "Í": i, "í": i, "Î": i, "î": i,
            "Ó": o, "ó": o, "Õ": o, "õ": o, "Ô": o, "ô": o,
            "Ú": u, "ú": u, "Ü": u, "ü": u,
            "ç": c, "Ç": c
        };
        for (var val in special_letters)
            data = data.split(val).join(special_letters[val]).toLowerCase();
        return data;
    },
    "portugues-asc": function ( a, b ) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },
    "portugues-desc": function ( a, b ) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
});