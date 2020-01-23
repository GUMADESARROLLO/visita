<?php 
class clientes_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function returnDataPedido() {
        $Dta=array();
        $i=0;
        $query = $this->sqlsrv->fetchArray("SELECT * FROM GP_GMV_Clientes",SQLSRV_FETCH_ASSOC);

        if (count($query)>0) {
            foreach ($query as $key) {
                $Dta[$i]['idPedido']        = $key['CLIENTE'];
                $Dta[$i]['nom_ruta']        = $key['NOMBRE'];
                $Dta[$i]['responsable']     = $key['DIRECCION'];
                $Dta[$i]['cliente']         = $key['VENDEDOR'];
                $Dta[$i]['nombre']          = number_format($key['CREDITO'], 2);
                $Dta[$i]['fecha']           = number_format($key['SALDO'], 2);
                $Dta[$i]['monto']           = number_format($key['DISPONIBLE'], 2);
                $Dta[$i]['details']         = '<a href="#!" onclick="detailsPedido('."'".$key['CLIENTE']."'".', '."'".$key['NOMBRE']."'".', '."'".$key['DIRECCION']."'".','."'".number_format($key['CREDITO'], 2)."'".','."'".number_format($key['SALDO'], 2)."'".','."'".number_format($key['DISPONIBLE'], 2)."'".')"><i class="fas fa-history"></i></a>';
                $i++;
            }

            echo json_encode($Dta);
        }else {
            echo json_encode(false);
        }
    }
    public function returnHistoricoCliente($idClientes) {
        $Dta=array();
        $i=0;
        $query = $this->sqlsrv->fetchArray("SELECT * FROM GP_GMV_hstCompra_3M WHERE CLIENTE ='$idClientes'  ",SQLSRV_FETCH_ASSOC);

        if (count($query)>0) {
            foreach ($query as $key) {
                $Dta[$i]['ARTICULO']        = $key['ARTICULO'];
                $Dta[$i]['DESCRIPCION']     = $key['DESCRIPCION'];
                $Dta[$i]['CANTIDAD']        = $key['CANTIDAD'];
                $Dta[$i]['TOTAL']           = $key['VentasTotal'];
                $Dta[$i]['Dia']             = $key['Dia'];
                $Dta[$i]['VENDEDOR']        = $key['nombreRuta'];
                $Dta[$i]['DateRange']       = $key['D1']. " al " . $key['D2'];
                $i++;
            }

            echo json_encode($Dta);
        }else {
            echo json_encode(false);
        }
    }

}