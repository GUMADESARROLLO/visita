<div class="container-fluid p-4">
  <div class="row mt-0">
    <div class="col-sm-8 mt-0">
        <h4 class="m-0 font-weight-bold mb-3">Clientes</h4>
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
	          <div class="row">
	            <div class="col-sm-11">
	              <div class="input-group">
	                <div class="input-group-prepend">
	                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-search text-primary"></i></span>
	                </div>
	                <input type="text" id="filterDtPedidos" class="form-control" placeholder="Buscador...">
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
	          <table class="table table-bordered table-sm mt-3" id="tblClientes" width="100%"></table>
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
                    <p class="font-weight-bold m-0 " id="txtCliente"></p>
                </div>
                <div class="col-sm">
                    <p class="font-italic m-0 text-right" id="txtRangeDate">00/00/0000 al 00/00/0000</p>
                </div>
            </div>
        </div>


      <div class="modal-body" id="bodyModal">

      </div>
    </div>
  </div>
</div>
