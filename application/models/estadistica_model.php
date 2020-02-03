<?php 
class estadistica_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function dataVentas($F1, $F2, $Ruta) {
		$T1_ = array();
		$i=0;
			
		$data_vent = $this->sqlsrv->fetchArray("EXEC GP_VM_ESTADISTICAS_VENTAS '".$F1."','".$F2."','".$Ruta."' ",SQLSRV_FETCH_ASSOC);
		
		if (count($data_vent)>0) {
			$rutas 	= array_values(array_unique(array_column($data_vent, 'RUTA')));

			foreach ($rutas as $key => $value) {
				$Rta = $value;
				$T1_[$i]['Ruta'] = $Rta;
				$T1_[$i]['Dias'] = array_column(array_filter($data_vent, function($item) use($Rta) { return $item['RUTA'] == $Rta; } ), 'Dia');
				$T1_[$i]['Mont'] = array_column(array_filter($data_vent, function($item) use($Rta) { return $item['RUTA'] == $Rta; } ), 'ventaTotal');
				$i++;
			}
		}
		echo json_encode($T1_);
    }

    public function returnRutas() {
        $tmp = array(); 
        $i=0;
        
        $DTA=$this
            ->db
            ->like('Usuario', 'F')
            ->get("usuario");

        if ($DTA->num_rows()>0) {
            foreach ( $DTA->result_array() as $key ) {
                $tmp[$i]['value'] = $key['Usuario'];
                $tmp[$i]['desc']  = $key['Usuario'].' - '.$key['Nombre'];
                $i++;
            }
            return $tmp;
        }else {
            return false;
        }  
    }
}