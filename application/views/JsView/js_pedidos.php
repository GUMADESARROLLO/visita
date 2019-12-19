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
            "paginate": {
                "first":      "Primera",
                "last":       "Última ",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "_MENU_",
            "emptyTable": `Realice su busqueda por el filtro de fechas <i class="fas fa-binoculars text-danger"></i>`,
            "search":     "BUSCAR"
        },
        'columns': [
            { "title": "N°",            "data": "idPedido" },
            { "title": "Vendedor", 		"data": "nom_ruta" },
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
	alert("fecha 1: "+F1+" fecha 2:"+F2)
})

function detailsPedido(idPedido, nom_ruta, nom_cliente) {
    $("#bodyModal")
    .empty()
    .html(`
    <div class="row pr-3 pl-3">
        <div class="col-sm-12">      
            <div class="row m-0">
                <div class="col-sm-12">
                    <p class="font-weight-bold m-0 text-center" id="txtPedido"></p>
                    <p class="font-weight-bold m-0 text-center" id="txtCliente"></p>
                    <p class="font-weight-bold mb-3 text-center" id="txtRuta"></p>
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
                 <th>0.00</th>
                 <th>0.00</th>
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
                    { "title": "Cant.",         "data": "CANTIDAD" },
                    { "title": "Precio",        "data": "TOTAL" },
                    { "title": "Bonificado",    "data": "BONIFICADO" }
                ],
                "columnDefs": [
                    {"className": "dt-left",    "targets": [  ]},
                    {"className": "dt-center",  "targets": [  ]},
                    { "width": "25%", "targets": [  ] },
                    { "width": "15%", "targets": [  ] }
                ],
                initComplete: function () {
                    var api = this.api();
                    for (var i=0; i<=4; i++) {
                        var tt  = 0;
                        api.columns([i]).every( function () {
                            this.data().each( function ( d, j ) {
                                tt += parseFloat(numeral(d).format('00.00'));
                            } );
                        } );
                        if ( i==2 || i==3 ) {
                            $retVal = numeral(tt).format("00");
                            $( api.column( i ).footer() ).html($retVal);
                        }
                    }
                    $("#tblTemporal_length, #tblTemporal_filter").hide();
                    $('#tblTemporal_paginate').appendTo('#id_pg');
                }
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
            .text(`VENDEDOR: `+nom_ruta);
    
            $("#mdDetails").modal('show');
        } 
    })


}
</script>