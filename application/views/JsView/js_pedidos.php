<script type="text/javascript">

$(document).ready( function() {
	generateTablePedidos(false, false);

    $('#dom-id').dateRangePicker({
        language: 'es',
        singleMonth: true,
        showShortcuts: false,
        startOfWeek: 'monday',
        separator : ' hasta ',
		setValue: function(s,s1,s2)
        {
        	F1 = s1;
        	F2 = s2;
            generateTablePedidos(s1, s2);
        }
    });
});

$("#filterDtPedidos").on("keyup",function() {
    var table = $("#tblPedidos").DataTable();
    table.search(this.value).draw();
});

$( "#cDtPedidos").change(function() {
    var table = $('#tblPedidos').DataTable();
    table.page.len(this.value).draw();
});

$("#selectRuta").on('change', function(event) {
    var table = $("#tblPedidos").DataTable();
    table.search(this.value).draw();
});

function generateTablePedidos(F1, F2) {
    $('#tblPedidos').DataTable({
        "ajax": {
            "url": "obtenerDataPedidos",
            "type": 'POST',
            "data": {F1:F1,F2:F2},
            'dataSrc': '',
        },
        "destroy": true,
        "order": [[ 0, "asc"]],
        "ordering": true,
        "info": true,
        "bPaginate": true,
        "bfilter": true,
        "searching": true,
        "pagingType": "full_numbers",
        "aaSorting": [
            [0, "asc"]
        ],
        "lengthMenu": [
            [5, 10, -1],
            [5, 10, "Todo"]
        ],
        "language": {
            "info": `<p class="font-weight-bold">Se encontraron _TOTAL_ registros</p>`,
            "zeroRecords": `<i class="fas fa-search"></i> Sin resultados`,
            "info": "Registro _START_ a _END_ de _TOTAL_ entradas",
            "infoEmpty": "",
            "paginate": {
                "first":      "Primera",
                "last":       "Última ",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "_MENU_",
            "emptyTable": `Realice su busqueda por el Filtro por Fechas <i class="fas fa-binoculars text-danger"></i>`,
            "search":     "BUSCAR"
        },
        'columns': [
            { "title": "N°",            "data": "idPedido" },
            { "title": "Visitador", 	"data": "nom_ruta" },
            { "title": "Responsable", 	"data": "responsable" },
            { "title": "Cliente", 	    "data": "cliente" },
            { "title": "Nombre", 	    "data": "nombre" },
            { "title": "Fecha",         "data": "fecha" },
            { "title": "Monto",         "data": "monto" },
            { "title": "",              "data": "details" }
        ],
        "columnDefs": [
          {"className": "dt-center", "targets": [ 0, 3, 5, 7]},
          {"className": "dt-right", "targets": [6]},
        ],
        "fnInitComplete": function () {
            $("#tblPedidos_length").hide();
            $("#tblPedidos_filter").hide();
        }
    });
}

var F1;
var F2;
$("#exp-to-excel").click( function() {
    ruta = $("#selectRuta option:selected").val();

    if (F1===``||F1===undefined && F2===``||F2===undefined) {
        mensaje("Seleccione un rango de fechas para generar el reporte", "error")
    }else {
        //alert("../index.php/pedidos_controller/descargarExcelPedidos/"+F1+"/"+F2+"/"+ruta)
       location.href = "../index.php/visitas_controller/descargarExcelVisitas/"+F1+"/"+F2+"/"+ruta;
    }
})

function detailsPedido(idPedido, nom_ruta, nom_cliente) {
    $("#bodyModal")
    .empty()
    .html(`
    <div class="row pr-3 pl-3">
        <div class="col-sm-12">      
            <div class="row m-0">
                <div class="col-sm-12">
                    <p class="font-weight-bold m-0" id="txtPedido"></p>
                    <p class="font-weight-bold m-0" id="txtCliente"></p>
                    <p class="font-weight-bold mb-3" id="txtRuta"></p>
                </div>
            </div>
            <div class="row">

                <div class="col-sm-11">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-search text-primary"></i></span>
                        </div>
                        <input type="text" id="filterTmp1" class="form-control" placeholder="Buscar en el detalle">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="input-group">
                        <select class="custom-select" id="cantRowsDtTemp">
                            <option value="5" selected>5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="Todo">Todo</option>
                        </select>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-sm mt-3" id="tblTemporal" width="100%">
                <tfoot>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tfoot>
            </table>
        </div>
    </div>`);
    $.ajax({
        url: `obtenerDetallePedido`,
        data: {
            idPedido : idPedido
        },
        type: 'post',
        async: false,
        success: function (response) {
           $('#tblTemporal').DataTable({
                "data":JSON.parse(response),
                "destroy": true,
                "info":    false,
                "lengthMenu": [
                    [5, 10, -1],
                    [5, 10, "Todo"]
                ],
                "language": {
                    "zeroRecords": `<i class="fas fa-search"></i> Sin resultados`,
                    "paginate": {
                        "first":      "Primera",
                        "last":       "Última ",
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    },
                    "lengthMenu": "MOSTRAR _MENU_",
                    "emptyTable": `<i class="fas fa-search"></i> No se encontro detalle de la visita`,
                    "search":     "BUSCAR"
                },
                'columns': [
                    { "title": "Articulo",      "data": "ARTICULO" },
                    { "title": "Descripcion",   "data": "DESCRIPCION" },
                    { "title": "Cant.",         "data": "CANTIDAD", render: $.fn.dataTable.render.number( ',', '.', 0 ) },
                    { "title": "Precio",        "data": "TOTAL" },
                    { "title": "Bonificado",    "data": "BONIFICADO" }
                ],
                "columnDefs": [
                    {"className": "dt-right",   "targets": [ 3 ]},
                    {"className": "dt-center",  "targets": [ 0, 2, 3, 4 ]},
                    /*{ "width": "50%",           "targets": [ 0,2 ] }*/
                ],
                initComplete: function () {
                    var api = this.api();
                    for (var i=0; i<=4; i++) {
                        var tt  = 0;
                        api.columns([i]).every( function () {
                            this.data().each( function ( d, j ) {
                                tt += parseFloat(numeral(d).format('00.00,00'));
                            } );
                        } );
                        $retVal = ( i == 2 ) ? numeral(tt).format("0,0") : (numeral(tt).format("0,0.00"));

                        if ( i==3 ) {
                            $( api.column( i ).footer() ).html($retVal);
                        }
                    }
                    $("#tblTemporal_length, #tblTemporal_filter").hide();
                }
            });

            $("#filterTmp1").on("keyup",function() {
                var table = $("#tblTemporal").DataTable();
                table.search(this.value).draw();
            });

            $( "#cantRowsDtTemp").change(function() {
                var table = $('#tblTemporal').DataTable();
                table.page.len(this.value).draw();
            });

            $("#titleModal")
            .empty()
            .text(`Detalle de pedido`);

            $("#txtPedido")
            .empty()
            .text(`PEDIDO: `+idPedido);

            $("#txtCliente")
            .empty()
            .text(`CLIENTE: `+nom_cliente);

            $("#txtRuta")
            .empty()
            .text(`VISITADOR: `+nom_ruta);
    
            $("#mdDetails").modal('show');
        } 
    })
}
</script>