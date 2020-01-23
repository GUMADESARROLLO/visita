<?php 
class visitas_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
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
                ->select('vm.Objetivo')
                ->select('md.NombreCliente')
                ->select('us.Nombre')
                ->from('visita_medico vm')
                ->where("STR_TO_DATE(Fecha, '%Y-%m-%d') BETWEEN '".date('Y-m-d', strtotime($F1))."' and '".date('Y-m-d', strtotime($F2))."' ")
                ->join('medicos md', 'vm.IdMedico = md.id_medico', 'left')
                ->join('usuario us', 'vm.Ruta = us.Usuario')
                ->get();

        if ($query->num_rows()>0) {
            foreach ($query->result_array() as $key) {
                $Dta[$i]['idReporte']   = $key['id_reporte'];
                $Dta[$i]['idMedico']    = $key['IdMedico'];
                $Dta[$i]['cliente']     = $key['NombreCliente'];
                $Dta[$i]['ruta']        = $key['Ruta'];
                $Dta[$i]['nombre']      = $key['Nombre'];
                $Dta[$i]['coment']      = ($key['Comentario']=='')?"<p class='text-danger'>Sin comentario</p>":$key['Comentario'];
                $Dta[$i]['Objeti']      = ($key['Objetivo']=='')?"<p class='text-danger'>Sin objetivo</p>":$key['Objetivo'];
                $Dta[$i]['fecha']       = date('d/m/Y', strtotime($key['Fecha']));
                $Dta[$i]['more']        = '<a href="#!" onclick="detailsVisit('."'".$key['id_reporte']."'".', '."'".$key['IdMedico']."'".', '."'".$key['Nombre']."'".', '."'".$key['NombreCliente']."'".')"><i class="fas fa-info-circle"></i></a>';
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

    //GENERA EXCEL DE VISITAS...
    public function descargarExcelVisitas($F1, $F2, $Rta) {
        $data=array(); $c=0;
        $Rta = ($Rta=='ND')?'':$Rta;

            $negrita = array(
                'font' => array(
                    'name'      => 'Calibri',
                    'bold'      => true
                )
            );

            $borders = array(
                'borders' => array(
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
                )
            );

            $titleTable = array(
                'font' => array(
                    'name'      => 'Calibri',
                    'bold'      => true,
                    'color' => array('rgb' => 'FFFF')
                ),
                'alignment' =>  array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'rotation'   => 0,
                        'wrap'       => TRUE,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                        'argb' => '126c95',
                    )

                ),
            );

            $bodyTable = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
                )
            );

        $DATA_PED = $this->db->query("SELECT * FROM visita_medico WHERE Ruta LIKE '%".$Rta."%' AND STR_TO_DATE(Fecha, '%Y-%m-%d') BETWEEN '".date('Y-m-d', strtotime($F1))."' AND '".date('Y-m-d', strtotime($F2))."'");
        
        if ($DATA_PED->num_rows()>0) {
            foreach ($DATA_PED->result_array() as $key) {
                $data[$c]['IdReporte']          = $key['id_reporte'];
                $data[$c]['IdMedico']           = $key['IdMedico'];
                $data[$c]['Cliente']            = $this->returnNombreMedico($key['IdMedico']);
                $data[$c]['Coordenadas']        = $key['Latitud'].", ".$key['Longitud'];
                $data[$c]['Ruta']               = $this->returnNombreUsuario($key['Ruta']);
                $data[$c]['coment']             = $key['Comentario'];
                $data[$c]['objeti']             = $key['Objetivo'];
                $data[$c]['fecha']              = $key['Fecha'];
                $c++;
            }

            $objPHPExcel = new PHPExcel();
            $tituloRpt = "Reporte de visitas GUMAPHARMA";
            $subTituloRpt = "Generado desde ".(date("d/m/Y", strtotime($F1))).' hasta '.(date("d/m/Y", strtotime($F2)));
            $titulosColumnas = array('N°','Cliente','Coordenadas','Visitador','Objetivo','Comentario','Fecha');
            $titulosTabla = array('Articulo','Descripción','Cantidad','U/M');
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->mergeCells('A1:D1');
            $objPHPExcel->setActiveSheetIndex(0)
                        ->mergeCells('A2:D2');
                            
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1',    $tituloRpt)
                        ->setCellValue('A2',    $subTituloRpt);

            $i=4;
            foreach ($data as $key) {
                $x=$i;
                $DtaVenta = $this->db->query(
                'SELECT
                    T1.id_reporte AS id_reporte,
                    T1.id_Medico AS id_Medico,
                    T1.Articulo AS Articulo,
                    T1.Descripcion AS Descripcion,
                    T1.Cantidad AS Cantidad,
                    T1.Unidad AS Unidad,
                    T1.Ruta AS Ruta
                FROM
                visita_medico_detalle T1
                WHERE T1.id_Medico='."'".$key['IdMedico']."'".'
                AND T1.id_reporte='."'".$key['IdReporte']."'".' ');

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  $titulosColumnas[0])
                    ->setCellValue('B'.$i,  $key['IdReporte'])
                    ->mergeCells('B'.$i.':D'.$i)
                    ->getColumnDimension('B')->setWidth(30);
                $i++;

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  $titulosColumnas[1])
                    ->mergeCells('B'.$i.':D'.$i)
                    ->setCellValue('B'.$i,  $key['Cliente']);
                $i++;

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  $titulosColumnas[2])
                    ->mergeCells('B'.$i.':D'.$i)
                    ->setCellValue('B'.$i,  $key['Coordenadas']);
                $i++;

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  $titulosColumnas[3])
                    ->mergeCells('B'.$i.':D'.$i)
                    ->setCellValue('B'.$i,  $key['Ruta']);
                $i++;

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  $titulosColumnas[6])
                    ->mergeCells('B'.$i.':D'.$i)
                    ->setCellValue('B'.$i,  date("d/m/Y g:i a", strtotime($key['fecha'])) );
                $i++;

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  $titulosColumnas[4])
                    ->setCellValue('B'.$i,  ($key['objeti']=='')?'N/D':$key['objeti'])
                    ->mergeCells('B'.$i.':D'.$i);

                $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP )
                    ->setWrapText(true)
                    ->getActiveSheet()
                    ->getRowDimension($i)->setRowHeight(45);
                $i++;

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  $titulosColumnas[5])
                    ->setCellValue('B'.$i,  ($key['coment']=='')?'N/D':$key['coment'])
                    ->mergeCells('B'.$i.':D'.$i);

                $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP )
                    ->setWrapText(true)
                    ->getActiveSheet()
                    ->getRowDimension($i)->setRowHeight(45);
                $i++;

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  $titulosTabla[0])                    
                    ->getColumnDimension('A')->setWidth(30);

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B'.$i,  $titulosTabla[1])
                    ->getColumnDimension('B')->setWidth(45);

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C'.$i,  $titulosTabla[2])
                    ->getRowDimension('C'.$i)->setRowHeight(100);
            

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D'.$i,  $titulosTabla[3])
                    ->getColumnDimension('D')->setWidth(20);
                    

                $objPHPExcel->setActiveSheetIndex(0)
                    ->getStyle('A'.$i.':D'.$i)->applyFromArray($titleTable);

                $objPHPExcel->setActiveSheetIndex(0)
                    ->getStyle('A'.$x.':A'.($i-1))->applyFromArray($borders);

                $objPHPExcel->setActiveSheetIndex(0)
                    ->getStyle('B'.$x.':D'.($i-1))->applyFromArray($borders);

                $y=$i;
                $i++;
                if ($DtaVenta->num_rows()>0) {                    
                    foreach ($DtaVenta->result_array() as $key2) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$i,  $key2['Articulo'])
                            ->setCellValue('B'.$i,  $key2['Descripcion'])
                            ->setCellValue('C'.$i,  $key2['Cantidad'])
                            ->setCellValue('D'.$i,  $key2['Unidad']);

                        $i++;
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->getStyle('A'.$y.':D'.($i-1))->applyFromArray($bodyTable);
                }else {
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,  '-')
                    ->setCellValue('B'.$i,  '-')
                    ->setCellValue('C'.$i,  '-')
                    ->setCellValue('D'.$i,  '-');
                    
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->getStyle('A'.$y.':D'.($i))->applyFromArray($bodyTable);


                    $i++;
                }
                $i++;
            }
            
            $estiloTituloReporte = array(
                'font' => array(
                    'name'      => 'Calibri',
                    'bold'      => true,
                    'italic'    => false,
                    'strike'    => false,
                    'size' =>15,
                        'color'     => array(
                            'rgb' => '151515'
                        )
                ),
                'alignment' =>  array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'rotation'   => 0,
                        'wrap'       => TRUE,
                )
            );

            $estilosubTituloReporte = array(
                'font' => array(
                    'name'      => 'Calibri',
                    'bold'      => false,
                    'italic'    => false,
                    'strike'    => false,
                    'size' =>12,
                        'color'     => array(
                            'rgb' => '151515'
                        )
                ),
                'alignment' =>  array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'rotation'   => 0,
                        'wrap'       => TRUE,
                )
            );

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($estiloTituloReporte);
            $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($estilosubTituloReporte);
            $objPHPExcel->getActiveSheet()->getStyle('A4:A'.($i+1))
            ->applyFromArray($negrita);
            
            $objPHPExcel->getActiveSheet()->getStyle('A4:A'.($i+1))->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP )
            ->setWrapText(true);
            $objPHPExcel->getActiveSheet()->setTitle('REPORTE VISITAS');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="REPORTE VISITAS '.date('d/m/Y').'.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        }
    }

    public function returnNombreMedico($idMedico) {
        $nameDr = $this
                ->db
                ->query(' SELECT NombreCliente AS NDR FROM medicos WHERE id_medico='."'".$idMedico."'".' ');

        if ($nameDr->num_rows()>0) {
            return $nameDr->result_array()[0]['NDR'];
        }

        return 'N/D';
    }

    public function returnDetalleProductos($idReporte, $idMedico) {
        $Dta=array();
        $i=0;
        $query = $this
                ->db
                ->query("SELECT
                            T1.id_reporte AS id_reporte,
                            T1.id_Medico AS id_Medico,
                            T1.Articulo AS Articulo,
                            T1.Descripcion AS Descripcion,
                            T1.Cantidad AS Cantidad,
                            T1.Unidad AS Unidad,
                            T1.Ruta AS Ruta
                        FROM
                        visita_medico_detalle T1
                        WHERE T1.id_Medico='F08-LYHKA0R'
                        AND T1.id_reporte='VM00001' ");

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

    public function obtenerCiudad($lat,$lon) {
        $url = 'http://nominatim.openstreetmap.org/reverse?format=json&lat='.$lat.'&lon='.$lon;
        $archivo_web = file_get_contents($url);
        $archivo = json_decode(utf8_decode($archivo_web));
        return $archivo->address->city;
    }

    public function returnNombreUsuario($ruta) {
        $DTA=$this
            ->db
            ->where("Usuario", $ruta)
            ->get("usuario");

        if ($DTA->num_rows()>0) {
            return $DTA->result_array()[0]['Nombre'];
        }

        return false;
    }
}