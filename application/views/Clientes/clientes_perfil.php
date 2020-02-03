
<div class="container emp-profile">
    <form method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="profile-img">
                    <img src="<?php echo base_url('assets/images/404.png'); ?>" alt="">
                    <div class="file btn btn-lg btn-primary">
                        <br>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="profile-head">
                    <h5>
                        <?php print_r($AllData['Mora'][0]['Nombre']); ?>
                    </h5>
                    <h6>
                        <?php print_r($AllData['Mora'][0]['Direccion']); ?>
                    </h6>
                    <p class="proile-rating"></p>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Mora</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Facturas Activas</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="container mt-10 mb-5">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Ultimos Pagos</h4>
                            <ul class="timeline">
                                <?php
                                if(isset($AllData['UltimosPagos'])){
                                    foreach ($AllData['UltimosPagos'] as $LastPagos){
                                        echo '<li>
                                                 <a href="#">'.$LastPagos['FECHA'].'</a>
                                                 <p>Se realizo un pago al Documento '.$LastPagos['DEBITO'].' por un monto de C$ '.$LastPagos['MONTO_DEBITO'].'</p>
                                              </li>';

                                    }
                                }
                                ?>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="tab-content profile-tab" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <label>No Vencidos</label>
                            </div>
                            <div class="col-md-6">
                                <p>C$ <?php echo number_format($AllData['Mora'][0]['NoVencidos'],2); ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>30 Dias</label>
                            </div>
                            <div class="col-md-6">
                                <p>C$ <?php echo number_format($AllData['Mora'][0]['Dias30'],2); ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>60 Dias</label>
                            </div>
                            <div class="col-md-6">
                                <p>C$ <?php echo number_format($AllData['Mora'][0]['Dias60'],2); ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>90 Dias</label>
                            </div>
                            <div class="col-md-6">
                                <p>C$ <?php echo number_format($AllData['Mora'][0]['Dias90'],2); ?></p>                          </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>120 Dias</label>
                            </div>
                            <div class="col-md-6">
                                <p>C$ <?php echo number_format($AllData['Mora'][0]['Dias120'],2); ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Mas 120 Dias</label>
                            </div>
                            <div class="col-md-6">
                                <p>C$ <?php echo number_format($AllData['Mora'][0]['Mas120'],2); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <table class="table table-bordered table-sm mt-3" id="tblClientes" width="100%">
                            <thead>
                            <tr>
                                <th>Factura</th>
                                <th>Fecha</th>
                                <th>Saldo</th>

                            </tr>
                            </thead>
                            <tbody>

                            <?php
                                foreach ($AllData['FacturasActivas'] as $Fact){
                                    echo '<tr>
                                            <td>'.$Fact['DOCUMENTO'].'</td>
                                            <td>'.$Fact['FECHA'].'</td>
                                            <td>'.number_format($Fact['SALDO'],2).'</td>
                                        </tr>';

                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>