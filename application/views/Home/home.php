<div class="container-fluid p-4">
  <div class="row mt-0">
    <div class="col-sm-10 mt-0">
      <button id="dom-id" class="btn btn-light text-danger fa-1x m-0"><i class="fas fa-calendar-day"></i> Filtrar por fecha</button>
    </div>
    <div class="col-sm-2">
      <select class="custom-select custom-select-sm float-right" style="width: 60%;" id="selectRuta">
        <option selected value="">Todas</option>
        <?php 
        if ($rutas) {
          foreach ($rutas as $key) {
            echo '<option value="'.$key['value'].'">'.$key['desc'].'</option>';
          }
        }
        ?>
      </select>
      <p class="mt-1 float-left">Rutas</p>
    </div>
  </div>
	<div class="row">
		<div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <h4 class="m-0 font-weight-bold mb-3">Visitas</h4>
          <div class="row">
            <div class="col-sm-11">              
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-search text-primary"></i></span>
                </div>
                <input type="text" id="filterDtCliente" class="form-control" placeholder="Buscar en clientes">
              </div>
            </div>
            <div class="col-sm-1">
              <div class="input-group">
                <select class="custom-select" id="cDtVisitas">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="-1">Todas las filas...</option>
                </select>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-sm mt-3" id="tblVisitas" width="100%"></table>
        </div>
      </div>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <h4 class="m-0 font-weight-bold mb-3">Médicos</h4>
          <div class="row">
            <div class="col-sm-11">              
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-search text-primary"></i></span>
                </div>
                <input type="text" id="filterDtMedicos" class="form-control" placeholder="Buscar en médicos">
              </div>
            </div>
            <div class="col-sm-1">
              <div class="input-group">
                <select class="custom-select" id="cDtMedicos">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="-1">Todas las filas...</option>
                </select>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-sm mt-3" id="tblMedicos" width="100%"></table>
        </div>
      </div>
		</div>
	</div>
</div>
<!-- Modal:Detalle -->
<div class="modal fade" id="mdDetails"  tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titleModal"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="bodyModal"></div>
    </div>
  </div>
</div>