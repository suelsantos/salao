$(function () {
    
    //CHART DE CIDADAOS
    $('#highjs_cidadao').highcharts({
        chart: {
            type: 'pie'
        },
        exporting: {            
            enabled: false
        },
        title: {
            text: null
        },
        plotOptions: {            
            series: {
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b> ({point.y:,.0f})',
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                    softConnector: true
                },
                neckWidth: '30%',
                neckHeight: '25%'               
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.percentage:.1f}%</b><br/>'
        },
        series: [{
            name: 'Percentual',
            data: [
                ['Membros', 8],
                ['Novos Convertidos', 3],
                ['Participantes', 1],
            ]
        }]
    });
    
    //AUTOCOMPLETE    
    $('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({ allow_single_deselect: true });  
    
    //CADASTRO DE ENTRADA
    $("body").on("click", '.cad_entrada', function() {    
        $("#form1").attr('action','financeiro/entrada_form.php');
        $("#form1").submit();
    });
    
    //CADASTRO DE SAIDA
    $("body").on("click", '.cad_saida', function() {    
        $("#form1").attr('action','financeiro/saida_form.php');
        $("#form1").submit();
    });
    
    //AGENDA DE CONTATOS
    $("body").on("change", '#agenda_cont', function() {    
        var key = $(this).val();
        var tipo = $(this).find(':selected').data('tipo');
        
        $.post("administracao/contatos.php", {id: key, tipo: tipo}, function(data){
            bootDialog(data,'Consulta de Contatos', true);
        });
    });
    
});