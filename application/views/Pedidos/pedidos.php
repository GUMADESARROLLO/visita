<div class="container-fluid p-4">
  <div class="row mt-0">
    <div class="col-sm-8 mt-0">
      <button id="dom-id" class="btn btn-light text-primary fa-1x m-0"><i class="fas fa-calendar-day"></i> Filtro por Fechas</button>
	<a id="exp-to-excel" href="#!" class="btn btn-light text-success"><i class="fas fa-file-excel"></i> Excel</a>
    </div>
    <div class="col-sm-4">
      <select class="custom-select custom-select-sm float-right" style="width: 80%;" id="selectRuta">
        <option selected value="ND">Todas</option>
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
	          <h4 class="m-0 font-weight-bold mb-3">Pedidos</h4>
	          <div class="row">
	            <div class="col-sm-11">
	              <div class="input-group">
	                <div class="input-group-prepend">
	                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-search text-primary"></i></span>
	                </div>
	                <input type="text" id="filterDtPedidos" class="form-control" placeholder="Buscar en pedidos">
	              </div>
	            </div>
	            <div class="col-sm-1">
	              <div class="input-group">
	                <select class="custom-select" id="cDtPedidos">
	                    <option value="5">5</option>
	                    <option value="10">10</option>
	                    <option value="-1">Todas las filas...</option>
	                </select>
	              </div>
	            </div>
	          </div>
	          <table class="table table-bordered table-sm mt-3" id="tblPedidos" width="100%"></table>
	        </div>
	      </div>
		</div>
	</div>
</div>
<!-- Modal:Detalle -->
<div class="modal fade" id="mdDetails"  tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document" >
    <div class="modal-content">
        <div class="modal-header d-block">
            <div class="d-flex">
                <h3 class="modal-title" id="titleModal">Modal title</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="row">
                <div class="col-sm">
                    <p class="font-weight-bold m-0 " id="txtPedido"></p>
                </div>
                <div class="col-sm">
                    <p class="font-italic m-0 text-right" id="txtRuta"></p>
                </div>
            </div>
        </div>
      <div class="modal-body" id="bodyModal">

      </div>
    </div>
  </div>
</div>