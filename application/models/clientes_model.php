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
    public function InformacionCliente($idClientes) {
        $Dta=array();

        $i=0;
        $query_FacturasActivas = $this->sqlsrv->fetchArray("SELECT t0.DOCUMENTO,CONVERT(VARCHAR,t0.FECHA,105) as FECHA,t0.SALDO FROM Softland.guma.documentos_cc t0 WHERE t0.cliente ='$idClientes'  and t0.SALDO > 0",SQLSRV_FETCH_ASSOC);
        if (count($query_FacturasActivas)>0) {
            foreach ($query_FacturasActivas as $key) {
                $Dta['FacturasActivas'][$i]['DOCUMENTO']   = $key['DOCUMENTO'];
                $Dta['FacturasActivas'][$i]['FECHA']       = $key['FECHA'];
                $Dta['FacturasActivas'][$i]['SALDO']       = number_format($key['SALDO'],2,".","");
                $i++;
            }
        }else{
            $Dta['FacturasActivas'][$i]['DOCUMENTO']   = "";
            $Dta['FacturasActivas'][$i]['FECHA']       = "";
            $Dta['FacturasActivas'][$i]['SALDO']       = number_format(0,2,".","");
        }

        $i=0;
        $query_mora = $this->sqlsrv->fetchArray("SELECT TOP 1 * FROM GP_GMV_ClientesPerMora t0 WHERE t0.cliente ='$idClientes'",SQLSRV_FETCH_ASSOC);
        if (count($query_mora)>0) {
            foreach ($query_mora as $key) {
                $Dta['Mora'][$i]['Nombre']          = $key['NOMBRE'];
                $Dta['Mora'][$i]['Direccion']       = $key['DIRECCION'];
                $Dta['Mora'][$i]['NoVencidos']      = number_format($key['NoVencidos'],2,".","");
                $Dta['Mora'][$i]['Dias30']          = number_format($key['Dias30'],2,".","");
                $Dta['Mora'][$i]['Dias60']          = number_format($key['Dias60'],2,".","");
                $Dta['Mora'][$i]['Dias90']          = number_format($key['Dias90'],2,".","");
                $Dta['Mora'][$i]['Dias120']         = number_format($key['Dias120'],2,".","");
                $Dta['Mora'][$i]['Mas120']          = number_format($key['Mas120'],2,".","");
                $i++;
            }
        }else{
            $Dta['Mora'][$i]['Nombre']          = "N/D";
            $Dta['Mora'][$i]['Direccion']       = "N/D";
            $Dta['Mora'][$i]['NoVencidos']      = number_format(0,2,".","");
            $Dta['Mora'][$i]['Dias30']          = number_format(0,2,".","");
            $Dta['Mora'][$i]['Dias60']          = number_format(0,2,".","");
            $Dta['Mora'][$i]['Dias90']          = number_format(0,2,".","");
            $Dta['Mora'][$i]['Dias120']         = number_format(0,2,".","");
            $Dta['Mora'][$i]['Mas120']          = number_format(0,2,".","");
        }

        $i=0;
        $query_mora = $this->sqlsrv->fetchArray("SELECT TOP 10 t0.TIPO_CREDITO,CONVERT(VARCHAR,t0.FECHA,105) as FECHA ,t0.DEBITO,t0.MONTO_DEBITO FROM Softland.guma.AUXILIAR_CC t0 WHERE t0.cli_doc_debito ='$idClientes' AND t0.TIPO_CREDITO= 'REC' ORDER BY t0.FECHA DESC",SQLSRV_FETCH_ASSOC);
        if (count($query_mora)>0) {
            foreach ($query_mora as $key) {
                $Dta['UltimosPagos'][$i]['TIPO_CREDITO']    = $key['TIPO_CREDITO'];
                $Dta['UltimosPagos'][$i]['FECHA']           = $key['FECHA'];
                $Dta['UltimosPagos'][$i]['DEBITO']          = $key['DEBITO'];
                $Dta['UltimosPagos'][$i]['MONTO_DEBITO']    = number_format($key['MONTO_DEBITO'],2,".","");
                $i++;
            }
        }else{
            $Dta['UltimosPagos'][$i]['TIPO_CREDITO']    = "N/D";
            $Dta['UltimosPagos'][$i]['FECHA']           = "N/D";
            $Dta['UltimosPagos'][$i]['DEBITO']          = "N/D";
            $Dta['UltimosPagos'][$i]['MONTO_DEBITO']    = number_format(0,2,".","");
        }


        return $Dta;
    }

}

