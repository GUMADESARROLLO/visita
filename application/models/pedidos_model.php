<?php 
class pedidos_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        include(APPPATH.'libraries\PHPExcel\Classes\PHPExcel.php');
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

    //GENERA EXCEL DE PEDIDOS...
    public function descargarExcelPedidos($F1, $F2, $Rta) {
        $data=array(); $c=0;
        $Rta = ($Rta=='ND')?'':$Rta;


        $DATA_PED = $this->db->query("SELECT * FROM pedido P1 WHERE P1.VENDEDOR LIKE '%".$Rta."%' AND P1.FECHA_CREADA BETWEEN '".$F1."' AND '".$F2."'");
        
        if ($DATA_PED->num_rows()>0) {
            foreach ($DATA_PED->result_array() as $key) {
                $data[$c]['IDPEDIDO']           = $key['IDPEDIDO'];
                $data[$c]['VENDEDOR']           = $this->returnNombreUsuario($key['VENDEDOR']);
                $data[$c]['RESPONSABLE']        = $key['RESPONSABLE'];
                $data[$c]['CLIENTE']            = $key['CLIENTE'];
                $data[$c]['NOMBRE']             = $key['NOMBRE'];
                $data[$c]['MONTO']              = $key['MONTO'];
                $data[$c]['FECHA_CREADA']       = date('d/m/Y', strtotime($key['FECHA_CREADA']));
                $data[$c]['COMENTARIO']         = $key['COMENTARIO'];
                $data[$c]['COMENTARIO_CONFIR']  = $key['COMENTARIO_CONFIR'];
                $c++;
            }

            $objPHPExcel = new PHPExcel();
            $tituloRpt = "REPORTE DE PEDIDOS";
            $subTituloRpt = "Generado desde ".(date("d/m/Y", strtotime($F1))).' hasta '.(date("d/m/Y", strtotime($F2)));
            $titulosColumnas = array('ID PEDIDO','VENDEDOR','RESPONSABLE','CLIENTE','NOMBRE','MONTO','FECHA','COMENTARIO','COMENTARIO CONFIRMACION');
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->mergeCells('A1:I1');
            $objPHPExcel->setActiveSheetIndex(0)
                        ->mergeCells('A2:I2');
                            
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1',    $tituloRpt)
                        ->setCellValue('A2',    $subTituloRpt)
                        ->setCellValue('A4',    $titulosColumnas[0])
                        ->setCellValue('B4',    $titulosColumnas[1])
                        ->setCellValue('C4',    $titulosColumnas[2])
                        ->setCellValue('D4',    $titulosColumnas[3])
                        ->setCellValue('E4',    $titulosColumnas[4])
                        ->setCellValue('F4',    $titulosColumnas[5])
                        ->setCellValue('G4',    $titulosColumnas[6])
                        ->setCellValue('H4',    $titulosColumnas[7])
                        ->setCellValue('I4',    $titulosColumnas[8]);
            $i=5;
            foreach ($data as $key) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i,  $key['IDPEDIDO'])
                        ->setCellValue('B'.$i,  $key['VENDEDOR'])
                        ->setCellValue('C'.$i,  $key['RESPONSABLE'])
                        ->setCellValue('D'.$i,  $key['CLIENTE'])
                        ->setCellValue('E'.$i,  $key['NOMBRE'])
                        ->setCellValue('F'.$i,  $key['MONTO'])
                        ->setCellValue('G'.$i,  $key['FECHA_CREADA'])
                        ->setCellValue('H'.$i,  $key['COMENTARIO'])
                        ->setCellValue('I'.$i,  ($key['COMENTARIO_CONFIR']=='')?'-':$key['COMENTARIO_CONFIR']);
                $i++;
            }
            
            $estiloTituloReporte = array(
                'font' => array(
                    'name'      => 'Calibri',
                    'bold'      => true,
                    'italic'    => false,
                    'strike'    => false,
                    'size' =>18,
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

            $estiloTituloColumnas = array(
                'font' => array(
                    'name'      => 'Calibri',
                    'bold'      => true,
                    'italic'    => false,
                    'strike'    => false,
                    'size'      =>10,
                    'color'     => array(
                        'rgb' => 'FFFFFF'
                    )
                ),
                'borders' => array(
                    'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                        'argb' => '126c95',
                    )
                ),
                'alignment' =>  array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'wrap'          => TRUE
                ));
                
            $estiloInformacion = new PHPExcel_Style();
            $estiloInformacion->applyFromArray(
                array(
                    'font' => array(
                        'name'      => 'Calibri',
                        'size'      => 10
                    ),
                    'borders' => array(
                        'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
                    ),
                ));

            $center = new PHPExcel_Style();
            $center->applyFromArray(
                array(
                    'alignment' =>  array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'wrap'          => TRUE
                    ),
                    'borders' => array(
                        'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ),
                    ))
                );

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($estiloTituloReporte);
            $objPHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($estilosubTituloReporte);
            $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->applyFromArray($estiloTituloColumnas);
            $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A5:I".($i-1));

            $objPHPExcel->getActiveSheet()->setSharedStyle($center, "A5:D".($i-1));
            $objPHPExcel->getActiveSheet()->setSharedStyle($center, "G5:G".($i-1));


            $objPHPExcel->getActiveSheet()->setTitle('REPORTE PEDIDOS');
            
            $objPHPExcel->setActiveSheetIndex(0);
            
            //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
            //$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

            /*$objPHPExcel->getActiveSheet()->getStyle('F4:F'.($i-1))->getNumberFormat()->setFormatCode('#,##0.00');
            $objPHPExcel->getActiveSheet()->getStyle('G4:G'.($i-1))->getNumberFormat()->setFormatCode('#,##0.00');*/

            $objPHPExcel->getActiveSheet(0)
            ->getStyle('F4:F'.($i-1))
            ->getNumberFormat()->setFormatCode("#,##0.00");



            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="REPORTE PEDIDO '.date('d/m/Y').'.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        }
    }
}