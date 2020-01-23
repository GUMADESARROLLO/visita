<script type="text/javascript">

$(document).ready( function() {
	generateTableVisitas(false,false);

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
            generateTableVisitas(s1, s2);
        }
    });
});

function generateTableVisitas(F1, F2) {
    $('#tblVisitas').DataTable({
        "ajax": {
            "url": "obtenerDataVisitas",
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
            { "title": "N°",            "data": "idReporte" },
            { "title": "Ruta",          "data": "ruta" },
            { "title": "Cliente", 		"data": "cliente" },
            { "title": "Visitador", 	"data": "nombre" },
            { "title": "Comentario", 	"data": "coment" },
            { "title": "Objetivo", 	    "data": "Objeti" },
            { "title": "Fecha",         "data": "fecha" },
            { "title": "",              "data": "more" },
        ],
        "columnDefs": [
            { "visible": false, "targets": 1 },
            { "width": "5%",   "targets": [ 0, 6, 7 ] },
            { "width": "15%",   "targets": [ 2, 3 ] },
            { "width": "20%",   "targets": [ 4,5 ] },
            {"className": "dt-center", "targets": [ 0, 6, 7 ]}
        ],
        "fnInitComplete": function () {
            $("#tblVisitas_length").hide();
            $("#tblVisitas_filter").hide();
        }
    });
}

$("#filterDtVisitas").on("keyup",function() {
    var table = $("#tblVisitas").DataTable();
    table.search(this.value).draw();
});

$( "#cDtVisitas").change(function() {
    var table = $('#tblVisitas').DataTable();
    table.page.len(this.value).draw();
});

$("#selectRuta").on('change', function(event) {
    var table = $("#tblVisitas").DataTable();
    table.search(this.value).draw();
});

var F1;
var F2;
$("#exp-to-excel").click( function() {
    ruta = $("#selectRuta option:selected").val();

    if (F1===``||F1===undefined && F2===``||F2===undefined) {
        mensaje("Seleccione un rango de fechas para generar el reporte", "error")
    }else {
       location.href = "../index.php/visitas_controller/descargarExcelVisitas/"+F1+"/"+F2+"/"+ruta;
    }
})

function detailsVisit(idReporte, idMedico, vendedor, cliente) {
    $("#bodyModal")
    .empty()
    .html(`
    <p class="pr-3 pl-3"><strong>Cliente:</strong> `+cliente+`</p>
    <p class="pr-3 pl-3"><strong>Visitador:</strong> `+vendedor+`</p>
    <div class="row pr-3 pl-3">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Objetivo</h5>
                    <p id="textObj" class="card-text"></p>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Comentario</h5>
                    <p id="textComent" class="card-text"></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row p-3">
        <div class="col-sm-12">
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
            <table class="table table-bordered table-sm mt-3" id="tblTemporal" width="100%"></table>
        </div>
    </div>`);

    $.ajax({
        url: `obtenerDetalleVisita`,
        data: {
            idReporte : idReporte,
            idMedico : idMedico
        },
        type: 'post',
        async: true,
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
                    { "title": "Articulo",      "data": "articulo" },
                    { "title": "Descripcion",   "data": "descripcion" },
                    { "title": "Cant.",         "data": "cantidad" },
                    { "title": "U/M",           "data": "unidad" }
                ],
                "columnDefs": [
                    {"className": "dt-left",    "targets": [ 1 ]},
                    {"className": "dt-center",  "targets": [ 0, 2, 3]},
                    { "width": "25%", "targets": [ 1 ] },
                    { "width": "15%", "targets": [ 0 ] }
                ],
                "fnInitComplete": function () {
                    $("#tblTemporal_length").hide();
                    $("#tblTemporal_filter").hide();
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


            Obj = JSON.parse(response);
            $("#textObj")
            .empty()
            .text(Obj[0]['obj']);

            $("#textComent")
            .empty()
            .text(Obj[0]['comt'])
        }
    });
    $("#titleModal")
    .empty()
    .text("Detalle de visita ["+idReporte+"]");
    $('#mdDetails').modal('show')
}



</script>