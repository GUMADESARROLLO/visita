<script type="text/javascript">
$(document).ready( function() {
	$('input[name="F1"], input[name="F2"]').daterangepicker({
        "locale": {
            "format": "DD-MM-YYYY",
            "separator": " - ",
            "applyLabel": "Apply",
            "cancelLabel": "Cancel",
            "fromLabel": "From",
            "toLabel": "To",
            "customRangeLabel": "Custom",
            "daysOfWeek": [
                "D",
                "L",
                "M",
                "M",
                "J",
                "V",
                "S"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 0
        },
        singleDatePicker: true,
        showDropdowns: true
	});

    var d = new Date();
    F1 = d.getFullYear()+'-'+(d.getMonth()+1)+'-01';
    F2 = d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
    Rta = $("#cmbRutas option:selected").val();
    
    initGraf(F1, F2, Rta)

	ventas = {
        chart: {
           type: 'spline',
            renderTo: 'container'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            title: {
                text: ''
            }
        },
        yAxis: {
            title: {
                text: ''
            }                
        },
        tooltip: {
            crosshairs: true,
            shared: true
        },
        legend: {
            align: 'center',
            verticalAlign: 'top',
            borderWidth: 0
        },
        plotOptions: {

        },
        series: [],
        responsive: {
            rules: [{
                condition: {
                maxWidth: 500
                },
                chartOptions: {
                    legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                    }
                }
            }]
        }
    };
});

var ventas = {};
function initGraf(F1, F2, Rta) {

    ventas.series = [];
        
    $.getJSON("dataVentas/"+F1+'/'+F2+'/'+Rta, function(json) {
        var newseries;
        var vtas;
        var vts_total = 0;
        var vts_max = 0;
        var vts_min = 0;

        $.each(json, function (i, item) {
            newseries = {};
            vtas = [];

            newseries.showInLegend = false;
            newseries.data = item['Mont'];
            newseries.name = item['Ruta'];
            
            ventas.series.push(newseries);
            ventas.xAxis.categories = item['Dias'];
            vts_total = item['Mont'].reduce(function(a, b){ return a + b; });
            vts_max = Math.max.apply(null, item['Mont']);
            vts_min = Math.min.apply(null, item['Mont']);
        })

        var chart = new Highcharts.Chart(ventas);
        $("#vTotal")
        .text('C$ '+ numeral(vts_total).format('0,0.00'));

        $("#vntMax")
        .css('color', 'green')
        .text('C$ '+ numeral(vts_max).format('0,0.00'))

        $("#vntMin")
        .css('color', 'red')
        .text('C$ '+ numeral(vts_min).format('0,0.00'))
    });
}

$("#appFiltro").click( function() {
    f1 = $("#F1").val();
    f2 = $("#F2").val();
    Rta = $("#cmbRutas option:selected").val();
    initGraf(f1, f2, Rta);
})

</script>