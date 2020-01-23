<script type="text/javascript">

$(document).ready( function() {
    generateTablePedidos();
});

$("#filterDtPedidos").on("keyup",function() {
    var table = $("#tblClientes").DataTable();
    table.search(this.value).draw();
});

$( "#cDtPedidos").change(function() {
    var table = $('#tblClientes').DataTable();
    table.page.len(this.value).draw();
});

$("#selectRuta").on('change', function(event) {
    var table = $("#tblClientes").DataTable();
    table.search(this.value).draw();
});

function generateTablePedidos() {
    $('#tblClientes').DataTable({
        "ajax": {
            "url": "obtenerDataClientes",
            "type": 'POST',
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
            { "title": "Nombre", 	    "data": "nom_ruta" },
            { "title": "Dirección", 	"data": "responsable" },
            { "title": "Vendedor", 	    "data": "cliente" },
            { "title": "Credito", 	    "data": "nombre" },
            { "title": "Saldo",         "data": "fecha" },
            { "title": "Disponible",    "data": "monto" },
            { "title": "",              "data": "details" }
        ],
        "columnDefs": [
          {"className": "dt-center", "targets": [ 0, 3, 5, 7]},
          {"className": "dt-right", "targets": [6]},
            {
                "targets": [ 3 ],
                "visible": false

            }
        ],

        "fnInitComplete": function () {
            $("#tblClientes_length").hide();
            $("#tblClientes_filter").hide();
        }
    });
}


function detailsPedido(idPedido, nom_ruta, nom_cliente,mCredito,mSaldo,mDispo) {
    $("#bodyModal")
    .empty()
    .html(`

<div class="row pr-3 pl-3">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Credito</h5>
                    <p id="objtCredi" class="card-text"></p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Saldo Actual.</h5>
                    <p id="objtSaldo" class="card-text"></p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Disponible</h5>
                    <p id="objtDispo" class="card-text"></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row p-3"></div>
    <div class="row pr-3 pl-3">
        <div class="col-sm-12">      
            <div class="row m-0">
                <div class="col-sm-12">
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
                            <option value="10" selected>10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="Todo">Todo</option>
                        </select>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-sm mt-3" id="tblTemporal" width="100%">
                <tfoot>
            <tr>
                <th colspan="6" style="text-align:right">Total:</th>

            </tr>
        </tfoot>
            </table>
        </div>
    </div>`);
    $.ajax({
        url: `returnHistoricoCliente`,
        data: {
            idCliente : idPedido
        },
        type: 'post',
        async: false,
        success: function (response) {
            var DataRage ="";
           $('#tblTemporal').DataTable({
                "data":JSON.parse(response),
                "destroy": true,
                "info":    false,
                "lengthMenu": [
                    [10, -1],
                    [10, "Todo"]
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
                    { "title": "UND",         "data": "CANTIDAD", render: $.fn.dataTable.render.number( ',', '.', 0 ) },
                    { "title": "Precio",        "data": "TOTAL",render: $.fn.dataTable.render.number( ',', '.', 4 ) },
                    { "title": "Fecha",         "data": "Dia" },
                    { "title": "Vendedor",          "data": "VENDEDOR" },
                    { "title": "DateRange",     "data": "DateRange" }
                ],
                "columnDefs": [
                    {"className": "dt-right",   "targets": [ 3 ]},
                    {"className": "dt-center",  "targets": [ 0, 2, 3,5, 4 ]},
                    {
                        "targets": [ 6 ],
                        "visible": false

                    }
                ],

               "order": [[ 4, "asc" ]],
               "footerCallback": function ( ) {
                   var api = this.api();

                   // Remove the formatting to get integer data for summation
                   var intVal = function ( i ) {
                       return typeof i === 'string' ?
                           i.replace(/[\$,]/g, '')*1 :
                           typeof i === 'number' ?
                               i : 0;
                   };

                   // Total over all pages
                   total = api
                       .column( 3 )
                       .data()
                       .reduce( function (a, b) {
                           return intVal(a) + intVal(b);
                       }, 0 );

                   // Update footer
                   $( api.column( 3 ).footer() ).html(
                       'C$ '+ numeral(total).format('0,0.00') +' total'
                   );
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
            .text('[' + idPedido +'] - ' + nom_ruta);


            $("#txtCliente")
            .empty()
            .text(nom_cliente);



            $('#objtCredi').empty().text('C$ ' + mCredito);
            $('#objtSaldo').empty().html('C$ ' + mSaldo);
            $('#objtDispo').empty().text('C$ ' + mDispo);

            $('#txtRangeDate').empty().text(JSON.parse(response)[0]['DateRange']);

    
            $("#mdDetails").modal('show');
        } 
    })
}
</script>