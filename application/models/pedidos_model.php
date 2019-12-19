<?php 
class pedidos_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function returnDataPedido($F1, $F2) {
        $Dta=array();
        $i=0;
        $query = $this
                ->db
                ->query("SELECT
                        t1.IDPEDIDO AS IDPEDIDO,
                        t1.VENDEDOR AS VENDEDOR,
                        t1.RESPONSABLE AS RESPONSABLE,
                        t1.CLIENTE AS CLIENTE,
                        t1.NOMBRE AS NOMBRE,
                        t1.FECHA_CREADA AS FECHA_CREADA,
                        t1.MONTO AS MONTO,
                        t2.Nombre AS NOMBRE_RUTA
                        FROM
                        pedido t1
                        JOIN usuario t2 ON t1.VENDEDOR = t2.Usuario WHERE
                        STR_TO_DATE(t1.FECHA_CREADA, '%Y-%m-%d') BETWEEN '".date('Y-m-d', strtotime($F1))."' and '".date('Y-m-d', strtotime($F2))."' ");

        if ($query->num_rows()>0) {
            foreach ($query->result_array() as $key) {
                $Dta[$i]['idPedido']        = $key['IDPEDIDO'];
                $Dta[$i]['nom_ruta']        = $key['NOMBRE_RUTA'];
                $Dta[$i]['responsable']     = $key['RESPONSABLE'];
                $Dta[$i]['cliente']         = $key['CLIENTE'];
                $Dta[$i]['nombre']          = $key['NOMBRE'];
                $Dta[$i]['fecha']           = date('d/m/Y', strtotime($key['FECHA_CREADA']));
                $Dta[$i]['monto']           = number_format($key['MONTO'], 2);
                $Dta[$i]['details']         = '<a href="#!" onclick="detailsPedido('."'".$key['IDPEDIDO']."'".', '."'".$key['NOMBRE_RUTA']."'".', '."'".$key['NOMBRE']."'".')"><i class="fas fa-info-circle"></i></a>';
                $i++;
            }

            echo json_encode($Dta);
        }else {
            echo json_encode(false);
        }
    }

    public function returnRutas() {
        $tmp = array();
        $i=0;
        
        $DTA=$this
            ->db
            ->distinct()
            ->select("Ruta")
            ->get("medicos");

        if ($DTA->num_rows()>0) {
            foreach ( $DTA->result_array() as $key ) {
                $tmp[$i]['value'] = $key['Ruta'];
                $tmp[$i]['desc']  = $key['Ruta'];
                $i++;
            }
            return $tmp;
        }else {
            return false;
        }  
    }

    public function returnDetallePedido($idPedido) {
        $DTA=$this
            ->db
            ->where("IDPEDIDO", $idPedido)
            ->get("pedido_detalle");

        if ($DTA->num_rows()>0) {
            echo json_encode( $DTA->result_array() );
        }else {
            echo json_encode(false);
        }
    }
}