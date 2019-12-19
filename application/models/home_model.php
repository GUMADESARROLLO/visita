<?php 
class home_model extends CI_Model {
    public function __construct() {
        parent::__construct();

    }

    public function returnDataVisita($F1, $F2) {
        $Dta=array();
        $i=0;

        $query = $this
                ->db
                ->select('vm.id_reporte')
                ->select('vm.IdMedico')
                ->select('vm.Ruta')
                ->select('vm.Comentario')
                ->select('vm.Fecha')
                ->select('vm.Latitud')
                ->select('vm.Longitud')
                ->select('md.NombreCliente')
                ->select('us.Nombre')
                ->from('visita_medico vm')
                ->where("STR_TO_DATE(Fecha, '%Y-%m-%d') BETWEEN '".date('Y-m-d', strtotime($F1))."' and '".date('Y-m-d', strtotime($F2))."' ")
                ->join('medicos md', 'vm.IdMedico = md.id_medico', 'left')
                ->join('usuario us', 'vm.Ruta = us.Usuario')
                ->get();

        if ($query->num_rows()>0) {
            foreach ($query->result_array() as $key) {
                $Dta[$i]['idReporte']    = $key['id_reporte'];
                $Dta[$i]['idMedico']    = $key['IdMedico'];
                $Dta[$i]['cliente']     = $key['NombreCliente'];
                $Dta[$i]['ruta']        = $key['Ruta'];
                $Dta[$i]['nombre']      = $key['Nombre'];
                $Dta[$i]['coment']      = ($key['Comentario']=='')?"<p class='text-danger'>Sin comentario</p>":$key['Comentario'];
                $Dta[$i]['fecha']       = date('d/m/Y', strtotime($key['Fecha']));
                $Dta[$i]['direccion']   = '<a href="#!" onclick="initMap( '."'".$key['Latitud']."'".','."'".$key['Longitud']."'".' )"><i class="fas fa-map-marker-alt text-danger"></i></a>';
                $Dta[$i]['details']     = '<a href="#!" onclick="details( '."'".$key['id_reporte']."'".', '."'".$key['IdMedico']."'".' )"><i class="fas fa-info-circle"></i></a>';

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

    public function returnDataMedico($ruta) {
        $Dta=array();
        $i=0;

        $query = $this
                ->db
                ->like('Ruta', $ruta)
                ->get('medicos');

        if ($query->num_rows()>0) {
            foreach ($query->result_array() as $key) {
                $Dta[$i]['nombre']          = '<a href="#!" onclick="detailsMed( '."'".$key['id_medico']."'".')">'.$key['NombreCliente'].'</a>';
                $Dta[$i]['ruta']            = $key['Ruta'];
                $Dta[$i]['direccion']       = $key['Direccion'];
                $Dta[$i]['nom_clinica']     = $key['NombreClinica'];
                $i++;
            }

            echo json_encode($Dta);
        }else {
            echo json_encode(false);
        }
    }

    public function returnDetalleVisita($idReporte, $idMedico) {
        $Dta=array();
        $i=0;
        $query = $this
                ->db
                ->query('SELECT
                        T1.id_reporte AS id_reporte,
                        T1.id_Medico AS id_Medico,
                        T2.NombreCliente AS NombreCliente,
                        T1.Articulo AS Articulo,
                        T1.Descripcion AS Descripcion,
                        T1.Cantidad AS Cantidad,
                        T1.Unidad AS Unidad,
                        T1.Ruta AS Ruta,
                        (SELECT T3.Comentario FROM visita_medico T3 WHERE T3.IdMedico='."'".$idMedico."'".' AND T3.id_reporte='."'".$idReporte."'".') AS Comentario,
                        (SELECT T4.Objetivo FROM visita_medico T4 WHERE T4.IdMedico='."'".$idMedico."'".' AND T4.id_reporte='."'".$idReporte."'".') AS Objetivo
                        FROM
                        visita_medico_detalle T1
                        JOIN medicos T2 ON T1.id_Medico = T2.id_medico
                        WHERE T1.id_Medico='."'".$idMedico."'".'
                        AND T1.id_reporte='."'".$idReporte."'".' ');

        if ($query->num_rows()>0) {
            foreach ($query->result_array() as $key) {
                $Dta[$i]['nombre']        = $key['NombreCliente'];
                $Dta[$i]['articulo']      = $key['Articulo'];
                $Dta[$i]['descripcion']   = $key['Descripcion'];
                $Dta[$i]['cantidad']      = $key['Cantidad'];
                $Dta[$i]['unidad']        = $key['Unidad'];
                $Dta[$i]['ruta']          = $key['Ruta'];
                $Dta[$i]['obj']           = $key['Objetivo'];
                $Dta[$i]['comt']          = $key['Comentario'];
                $i++;
            }

            echo json_encode($Dta);
        }else {
            echo json_encode(false);
        }
    }

    public function returnDetalleMedico($idMedico) {
        
        $Dta    = array();
        $JSON   = array();
        $array_merge = array();
        $j=0;
        $anio = date('Y');
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        $queryD = $this
                ->db
                ->where('id_medico', $idMedico)
                ->get('medicos');

        $query = $this
                ->db
                ->query('SELECT
                        MONTH(vm.Fecha) AS numMes,
                        vm.Fecha AS fecha,
                        vm.Ruta AS ruta,
                        vm.id_reporte AS idRpt
                        FROM
                            visita_medico vm
                        WHERE
                            vm.IdMedico = '."'".$idMedico."'".' AND YEAR(vm.Fecha)= '.$anio.' ');

        $Dta = $query->result_array();

 
        for ($i=1; $i <=12 ; $i++) {
            
            $monthName = $meses[$i-1];
            $pos = array_search($i, array_column($Dta, 'numMes'));
            $JSON[$j] = array(
                'mes' => $monthName,
                'Fechas' => array_values(array_filter($Dta, function($item) use($i) { return $item['numMes'] == $i; } ))
            );
            $j++;
        }

        $array_merge = array(
            "infoDr" => $queryD->result_array(),
            "infoVs" => $JSON
        );

        echo json_encode($array_merge);
    }
}

