<div class="container-fluid p-4">
  <div class="row mt-0">
    <div class="col-sm-8 mt-0">
        <h4 class="m-0 font-weight-bold mb-3">Estadisticas</h4>
    </div>
    <div class="col-sm-4"></div>
  </div>
	<div class="row">
		<div class="col-sm-12">
	      <div class="card">
	        <div class="card-body">
	          <div class="row">
	            <div class="col-sm-3">
                <div class="form-group">
                  <label for="vTotal" class="p-0 m-0 title-01">Total de venta</label>
                  <h4 class="font-weight-bold mb-3" id="vTotal"></h4>
                </div>
              </div>
              <div class="col-sm-4 border-left">
                <div class="form-group">
                  <label for="cmbRutas" class="p-0 m-0 title-01">Visitador</label>
                  <select class="form-control form-control-sm" id="cmbRutas">
                    <?php
                    if ($rutas) {
                      foreach ($rutas as $key) {
                        if ($key === reset($rutas)) {
                          echo '<option value="'.$key['value'].'" selected>'.$key['desc'].'</option>';
                        }else {
                          echo '<option value="'.$key['value'].'">'.$key['desc'].'</option>';
                        }                      
                      }               
                    }
                    ?>
                    
                  </select>
                </div>
              </div>
              <div class="col-sm-2 border-left">
                <div class="form-group">
                  <label for="F1" class="p-0 m-0 title-01">Desde</label>
                  <input type="text" class="form-control form-control-sm" name="F1" id="F1" />
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group">
                  <label for="F2" class="p-0 m-0 title-01">Hasta</label>
                  <input type="text" class="form-control form-control-sm" name="F2" id="F2" />
                </div>                
              </div>
              <div class="col-sm-1">
                <a href="#!" class="btn btn-primary btn-sm mt-4" id="appFiltro">Mostrar</a>
              </div>
	          </div>
            <div class="row mt-2">
              <div class="col-sm-9">
                <figure class="highcharts-figure">
                    <div id="container"></div>
                </figure>
              </div>
              <div class="col-sm-3 border-left">
                <p class="font-weight-bold">Dia mas alto</p>
                <h4 class="font-weight-bold mb-3" id="vntMax"></h4>
                <hr>
                <p class="font-weight-bold mt-3">Dia mas bajo</p>
                <h4 class="font-weight-bold mb-3" id="vntMin"></h4>
              </div>
            </div>
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
