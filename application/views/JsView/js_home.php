<script type="text/javascript">

$(document).ready( function() {
    var d = new Date();    
    FechaActual = (d.getFullYear())+`-`+(d.getMonth()+1)+`-`+(d.getDate());
    FechaInicio = (d.getFullYear())+`-`+(d.getMonth()+1)+`-01`;    

	generateTableVisitas(FechaInicio, FechaActual);
    generateTableMedicos('');

    $('#dom-id').dateRangePicker({
        language: 'es',
        singleMonth: true,
        showShortcuts: false,
        startOfWeek: 'monday',
        separator : ' hasta ',
            setValue: function(s,s1,s2)
        {
            generateTableVisitas(s1, s2);
        }
    });
});

$("#selectRuta").on('change', function(event) {
    var ruta = $('#selectRuta option:selected').val();    
    generateTableMedicos(ruta);
});


$("#filterDtMedicos").on("keyup",function() {
    var table = $("#tblMedicos").DataTable();
    table.search(this.value).draw();
});

$( "#cDtMedicos").change(function() {
    var table = $('#tblMedicos').DataTable();
    table.page.len(this.value).draw();
});

$("#filterDtCliente").on("keyup",function() {
    var table = $("#tblVisitas").DataTable();
    table.search(this.value).draw();
});

$( "#cDtVisitas").change(function() {
    var table = $('#tblVisitas').DataTable();
    table.page.len(this.value).draw();
});

function initMap(latitud,longitud) {
	$("#bodyModal")
    .empty()
    .html(`<div id="map-template" style="height: 450px"></div>`);

	const map = L.map('map-template').setView([latitud,longitud], 50);
	const tileUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
	L.tileLayer(
    	tileUrl,
    	{
        	attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        	detectRetina: true
    	}
    ).addTo(map);

	var redMarker = {icon: L.ExtraMarkers.icon({
    	icon: 'fa-street-view',
    	markerColor: 'red',
    	shape: '',
    	prefix: 'fas'
	})};

	L.marker([latitud,longitud], redMarker).bindPopup('Punto de visita').addTo(map);
	$('#mdDetails').on('shown.bs.modal', function() {
	  map.invalidateSize();
	});
    $("#titleModal")
    .empty()
    .text("Mostrando ubicacion");

	$('#mdDetails').modal('show')
}

function generateTableVisitas(s1, s2) {
    $('#tblVisitas').DataTable({
        "ajax": {
            "url": "obtenerDataVisita",
            "type": 'POST',
            "data": {F1:s1,F2:s2},
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
            "emptyTable": `No se encontraron visitas  <i class="fas fa-binoculars text-danger"></i>`,
            "search":     "BUSCAR"
        },
        'columns': [
            { "title": "N°",            "data": "idReporte" },
            { "title": "Fecha", 		"data": "fecha" },
            { "title": "Cliente", 	    "data": "cliente" },
            { "title": "Visitador", 	"data": "nombre" },
            { "title": "", 	            "data": "direccion" },
            { "title": "",              "data": "details" }
        ],
        "columnDefs": [
          {"className": "dt-center", "targets": [ 0, 1, 3, 4, 5]},
        ],
        "fnInitComplete": function () {
            $("#tblVisitas_length").hide();
            $("#tblVisitas_filter").hide();
        }
    });
}

function generateTableMedicos(ruta) {
    $('#tblMedicos').DataTable({
        "ajax": {
            "url": "obtenerDataMedico",
            "type": 'POST',
            "data": {R:ruta},
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
            "emptyTable": `No se encontraron registros  <i class="fas fa-binoculars text-danger"></i>`,
            "search":     "BUSCAR"
        },
        'columns': [
            { "title": "Nombre",    	"data": "nombre" },
            { "title": "Dirección", 	"data": "direccion" },
            { "title": "Clínica",       "data": "nom_clinica" },
            { "title": "Ruta",          "data": "ruta" },
        ],
        "columnDefs": [
          {"className": "dt-center", "targets": [ 3 ]},
          /*{"className": "dt-left", "targets": [ 1 , 3 ] },
          { "width": "20%", "targets": [ 1, 2, 4 ] }*/
        ],
        "fnInitComplete": function () {     
            $("#tblMedicos_length").hide();
            $("#tblMedicos_filter").hide();
        }
    });
}

function details(idReporte, idMedico) {
    $("#bodyModal")
    .empty()
    .html(`
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
                    { "title": "Articulo",      "data": "articulo" },
                    { "title": "Descripcion",   "data": "descripcion" },
                    { "title": "Cant.",         "data": "cantidad" },
                    { "title": "U/M",           "data": "unidad" },
                    { "title": "Ruta",          "data": "ruta" }
                ],
                "columnDefs": [
                    {"className": "dt-left",    "targets": [ 1 ]},
                    {"className": "dt-center",  "targets": [ 0, 2, 3, 4 ]},
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

function detailsMed(idMedico) {
    body1 = body2 = ``;
    k=0;

    $.ajax({
        url: `obtenerDetalleMedico`,
        data: {
            idMedico : idMedico
        },
        type: 'post',
        async: false,
        success: function (response) {

            Dta = JSON.parse(response);
            $.each( Dta['infoVs'], function(i, item) {
                body2 += 
                `<dl class="timeline-series"><dt class="timeline-event" id="event0`+k+`"><a>`+item['mes']+`</a></dt>
                    <dd class="timeline-event-content" id="event0`+k+`EX">`;

                if ( (item['Fechas']).length>0 ) {
                    $.each(item['Fechas'], function(k, item1) {
                        F1 = $.format.date(item1['fecha'], "dd/MM/yyyy")
                        body2 +=`<p><strong>Fecha:</strong> `+F1+`<br><strong>Ruta:</strong> `+item1['ruta']+`<br><strong>N°: </strong>`+item1['idRpt']+`</p>`;
                    });
                }else {
                    body2 +=`<p>Sin visitas para este mes</p>`;
                }

                body2 += 
                    `</dd>
                </dl>`;
                k++;
            });

            $.each( Dta['infoDr'], function(j, item2) {
                body1 += `
                  <div class="form-group row">
                    <div class="col-sm-6">
                        <label for="nombre" class="col-form-label"><strong>Nombre completo:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="nombre" value="`+( (item2['NombreCliente']=='')?'-':item2['NombreCliente'] )+`">
                    </div>
                    <div class="col-sm-3">
                        <label for="cedula" class="col-form-label"><strong>Cedula:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="cedula" value="`+( (item2['Cedula']=='')?'-':item2['Cedula'] )+`">
                    </div>
                    <div class="col-sm-3">
                        <label for="telefono" class="col-form-label"><strong>Telefono:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="telefono" value="`+( (item2['Telefono']=='')?'-':item2['Telefono'] )+`">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-9">
                        <label for="direccion" class="col-form-label"><strong>Direccion:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="direccion" value="`+( (item2['Direccion']=='')?'-':item2['Direccion'] )+`">
                    </div>
                    <div class="col-sm-3">
                        <label for="cumpleF" class="col-form-label"><strong>Cumpleaños:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="cumpleF" value="`+( (item2['Cumple']=='00/00/0000')?'-':item2['Cumple'] )+`">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="nClinica" class="col-form-label"><strong>Nombre clinica:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="nClinica" value="`+( (item2['NombreClinica']=='')?'-':item2['NombreClinica'] )+`">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="especialidad" class="col-form-label"><strong>Especialidad:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="especialidad" value="`+( (item2['Especialidad']=='')?'-':item2['Especialidad'] )+`">
                    </div>
                    <div class="col-sm-4">
                        <label for="email" class="col-form-label"><strong>Correo electronico:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="email" value="`+ ( (item2['Email']=='')?'-':item2['Email'] )+`">
                    </div>
                    <div class="col-sm-4">
                        <label for="departamento" class="col-form-label"><strong>Departamento:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="departamento" value="`+( (item2['Departamento']=='')?'-':item2['Departamento'] )+`">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="municipio" class="col-form-label"><strong>Municipio:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="municipio" value="`+( (item2['Municipio']=='')?'-':item2['Municipio'] )+`">
                    </div>
                    <div class="col-sm-4">
                        <label for="numPaciente" class="col-form-label"><strong>Numero paciente:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="numPaciente" value="`+( (item2['NumeroPacientes']=='')?'-':item2['NumeroPacientes'] )+`">
                    </div>
                    <div class="col-sm-4">
                        <label for="proConsulta" class="col-form-label"><strong>Prom. consulta:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="proConsulta" value="`+( (item2['PromConsulta']=='')?'-':item2['PromConsulta'] )+`">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="nContacto" class="col-form-label"><strong>Nombre contacto:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="nContacto" value="`+( (item2['NombreContacto']=='')?'-':item2['NombreContacto'] )+`">
                    </div>
                    <div class="col-sm-4">
                        <label for="cContacto" class="col-form-label"><strong>Ced. contacto:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="cContacto" value="`+( (item2['CedulaContacto']=='')?'-':item2['CedulaContacto'] )+`">
                    </div>
                    <div class="col-sm-4">
                        <label for="telContacto" class="col-form-label"><strong>Telefono contacto:</strong></label>
                        <input type="text" readonly class="form-control-plaintext" id="telContacto" value="`+( (item2['TelefonoContacto']=='')?'-':item2['TelefonoContacto'] )+`">
                    </div>
                  </div>`;
            })

            $("#bodyModal")
            .empty()
            .html(
            `<div class="row pl-4 pr-4">
                <div class="col-sm-9">
                    <h5 class="card-title text-danger font-weight-bold">Información personal</h5>
                    `+body1+`
                </div>
                <div class="col-sm-3">
                    <h5 class="card-title text-primary font-weight-bold">Visitas realizadas</h5>
                    <div id="timeline" class="timeline-container">
                        <div class="timeline-wrapper">
                        `+body2+`
                        </div>
                        <br class="clear">
                    </div>
                </div>
            </div>`);

            $.timeliner({});
            $("#titleModal")
            .empty()
            .text("Detalle de Médico");
            $('#mdDetails').modal('show');
        }
    });
}

</script>