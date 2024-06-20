<?
include_once("functions/string.func.php");
include_once("libraries/excel/PHPExcel.php");
include_once("libraries/PHPExcel/IOFactory.php");

header('Cache-Control:max-age=0');
header('Cache-Control:max-age=1');
ini_set('memory_limit', '-1');

ini_set('upload_max_filesize', '200M');
ini_set('post_max_size', '200M');
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('max_execution_time', -1);


$this->load->model("TenderEvaluation");
$this->load->model('MasterTenerMenus');
$this->load->model("TenderEvaluationDetail");
$this->load->model("MasterTenderPeriode");

$reqId = $this->input->get('reqId');
$reqColomn = $this->input->get('reqColomn');
$reqEvalusiId  = $this->input->get('reqEvalusiId');

$master_tender_periode = new MasterTenderPeriode();
$master_tender_periode->selectByParamsMonitoring(array("A.TAHUN"=>$reqId));
$master_tender_periode->firstRow();
$reqPeriodeId =$master_tender_periode->getField('MASTER_TENDER_PERIODE_ID');
if(empty($reqPeriodeId)){
$master_tender_periode = new MasterTenderPeriode();
$master_tender_periode->setField("TAHUN",$reqId);
$master_tender_periode->insert();
$reqPeriodeId = $master_tender_periode->id;
} 
$reqId = $reqPeriodeId;


$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objPHPExcel = new PHPExcel();
$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load("temp_excel/tender_evaluasi_new.xls");
$objPHPExcel->getProperties()->setCreator("PT Aquamarine Divindo Inspection ")
  ->setLastModifiedBy($this->NAMA)
  ->setTitle("PRINT PRIVIEW")
  ->setSubject("PT Aquamarine Divindo Inspection")
  ->setDescription("PT Aquamarine Divindo Inspection EXPORT EXCEL.")
  ->setKeywords("Office Management | PT Aquamarine Divindo Inspection")
  ->setCategory("Office Management | PT Aquamarine Divindo Inspection");

  $objDrawingLogo = new PHPExcel_Worksheet_Drawing();  
try{
$objDrawingLogo = new PHPExcel_Worksheet_Drawing();
$objDrawingLogo->setName('test_img');
$objDrawingLogo->setDescription('test_img');
$objDrawingLogo->setPath('images/logo_baru.png');
$objDrawingLogo->setCoordinates('B2');  
//setOffsetX works properly
$objDrawingLogo->setOffsetX(10); 
$objDrawingLogo->setOffsetY(10);                
//set width, height
$objDrawingLogo->setWidth(1200); 
$objDrawingLogo->setHeight(100); 
$objDrawingLogo->setWidthAndHeight(2600,150);
$objDrawingLogo->setResizeProportional(true);
$objDrawingLogo->setWorksheet($objPHPExcel->getActiveSheet());


}catch (Exception $e) {
// $panjang_baris =$panjang_baris-4;
}



$styleArrayBorderNone = array(
  
  'borders' => array(
   'outline' => array(
      'style' => PHPExcel_Style_Border::BORDER_NONE
   ),
)
);

$styleArrayss = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  ),
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'font'  => array(
    'bold'  => true
	)
);
 $styleStatus = array(
             'font'  => array(
              'bold'  => true,
              'color' => array('rgb' => 'FFFFFF'),
              'size'  => 25,
              'name'  => 'Verdana'
            ));      

$reqColomn = explode(',', $reqColomn );
$REQIDS2 =array();
for($i=0;$i<count($reqColomn);$i++){
  if($reqColomn[$i]!='ALL'){
    array_push($REQIDS2,$reqColomn[$i] );
  }
}

$reqColomVariable = implode_to_string( $REQIDS2);

$statement = " AND UPPER(A.NAMA) IN (".$reqColomVariable.")";
// print_r($reqColomVariable);

$master_tener_menus = new MasterTenerMenus();
$totalBaseRow = $master_tener_menus->getCountByParamsMonitoring(array(),$statement);
$master_tener_menus = new MasterTenerMenus();
$master_tener_menus->selectByParamsMonitoring(array(),-1,-1,''," ORDER BY A.URUTAN ASC");
 $attData = array();
        $attDataId = array();
while($master_tener_menus->nextRow()){
	array_push($attData , strtoupper($master_tener_menus->getField('NAMA')));
	$attDataId[strtoupper($master_tener_menus->getField('NAMA'))]= $master_tener_menus->getField("MASTER_TENDER_MENUS_ID");
}


// $aColumns = array(
// 	"LAST_UPDATE","INDEX","NAMA_PSC","TITLE","TENDER_NO","Closing Date/1st Opening","2nd Opening Date"
// );
// $aColumns =array_merge($aColumns,$attData);
// $arDataOther = array("Failed/
// Decline","Owners Estimate","Bid Value","% TKDN","Bid Bond Value","Bid Validity (days)","NOTES");
// $aColumns = array_merge($aColumns,$arDataOther);

$aColumns = $reqColomn;
// print_r($aColumns);exit;


$arrColomnAlias = array();
$arrColomnAlias['LAST_UPDATE']= "Last updated by";$arrColomnAlias['INDEX']= "Index";
$arrColomnAlias['NAMA_PSC']= "PSC Name";$arrColomnAlias['TITLE']= "Tender Title";
$arrColomnAlias['TENDER_NO']= "Tender No";$arrColomnAlias['CLOSING']= "Closing Date/1st Opening";
$arrColomnAlias['OPENING']= "2nd Opening Date";$arrColomnAlias['STATUS']= "Failed / Decline";
$arrColomnAlias['OWNER']= "Owners Estimate";$arrColomnAlias['BID_VALUE']= "Bid Value";
$arrColomnAlias['TKDN']= "% TKDN";$arrColomnAlias['BID_BOUDS']= "Bid Bond Value";
$arrColomnAlias['BID_VALIDATY']= "Bid Validity (days)";$arrColomnAlias['NOTES']= "Notes";



// print_r($aColumns );exit;
$rowSecHeader =2;
$rowHeader =3;
$rowing =4;
$colms =2;
for($i=0;$i<count($aColumns);$i++){
 $colomnNama = $arrColomnAlias[$aColumns[$i]];
 if(empty($colomnNama)){
  $colomnNama = strtolower($aColumns[$i]);
}

	$title = str_replace('_', ' ', strtolower($colomnNama));
	$join= join(' ', array_map('ucfirst', explode(' ', $title)));
	$objPHPExcel->getActiveSheet()->setCellValue(getColoms($colms) . $rowing, $join);
	 $objPHPExcel->getActiveSheet()->getStyle(getColoms($colms) . $rowing)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('808080');
	$colms++;
}

// $row_header_second = count($aColumns);
$objPHPExcel->getActiveSheet()->getStyle('B' . ($rowHeader))->applyFromArray($styleArrayBorderNone);
 $objPHPExcel->getActiveSheet()->getStyle('B' . ($rowHeader))->applyFromArray($styleArrayss);


if(count($aColumns > 13)){
$rules = new MasterTenderPeriode();
$rules->selectByParamsMonitoring(array("CAST(A.MASTER_TENDER_PERIODE_ID AS VARCHAR)" => $reqId));
$rules->firstRow();
$last = $rules->getField("LAST_UPDATE");
$tahun = $rules->getField("TAHUN");
$row_header_second = count($aColumns);
$objPHPExcel->getActiveSheet()->setCellValue(getColoms($colms-3) . $rowSecHeader, 'UPDATE');
 $objPHPExcel->getActiveSheet()->getStyle(getColoms($colms-3) . $rowSecHeader)->applyFromArray($styleArrayss);
$objPHPExcel->getActiveSheet()->setCellValue(getColoms($colms-2) . $rowSecHeader, $last);
 $objPHPExcel->getActiveSheet()->getStyle(getColoms($colms-2) . $rowSecHeader)->applyFromArray($styleArrayss);
 $objPHPExcel->getActiveSheet()->setCellValue(getColoms($colms-1) . $rowSecHeader, $tahun);
 $objPHPExcel->getActiveSheet()->getStyle(getColoms($colms-1) . $rowSecHeader)->applyFromArray($styleArrayss);
 $objPHPExcel->getActiveSheet()->getStyle(getColoms($colms-1) . $rowSecHeader)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('BDD7EE');
	$objPHPExcel->getActiveSheet()->getStyle(getColoms($colms-1) . $rowSecHeader)->getFont()->setBold(true)
                                ->setName('Verdana')
                                ->setSize(40)
                                ->getColor()->setRGB('404040');
}


$baseRow = 6;
$r = 0;
$row = 0;
$colomStart=8;
$colomIndexPlus = $colomStart+$totalBaseRow;

$objWorksheet = $objPHPExcel->getActiveSheet();


$objWorksheet->mergeCells('B'.$rowHeader.':'.getColoms($colms-1).$rowHeader)->setCellValue('B'.$rowHeader, 'TENDER EVALUATION SYSTEM' );

$tender_evaluation = new TenderEvaluation();
$statement='';
if(!empty($reqEvalusiId)){
$reqEvalusiIds =explode(',', $reqEvalusiId);
$reqVid = implode_to_string( $reqEvalusiIds);
$statement = " AND CAST(A.TENDER_EVALUATION_ID AS VARCHAR) IN (".$reqVid.")";
}

$tender_evaluation->selectByParamsMonitoring(array("A.MASTER_TENDER_PERIODE_ID"=>$reqId),-1,-1,$statement);
$row = $row+$baseRow;
$objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);
while ( $tender_evaluation->nextRow()) {
$reqIds = $tender_evaluation->getField('TENDER_EVALUATION_ID');
$wizard = new PHPExcel_Helper_HTML;
for($kk=0;$kk<count($aColumns);$kk++){
  $indexs = 2+$kk;
  
  
        if(in_array($aColumns[$kk], $attData)){
            $tender_evaluation_detail = new TenderEvaluationDetail();
            $tender_evaluation_detail->selectByParamsMonitoring(array("A.TENDER_EVALUTATION_ID"=>$reqIds,"CAST(A.MASTER_TENDER_MENUS_ID AS VARCHAR)"=>$attDataId[$aColumns[$kk]]));
           $tender_evaluation_detail->firstRow();
           $master_tener_menus = new MasterTenerMenus();
           $master_tener_menus->selectByParamsMonitoring(array("UPPER(A.NAMA)"=>$aColumns[$kk]));
           $master_tener_menus->firstRow();
           $color2 = $master_tener_menus->getField("COLOR2");

           $NILAI= $tender_evaluation_detail->getField('NILAI');
           if($NILAI == '100'){
            $color = $tender_evaluation_detail->getField("COLOR");
          }else if(empty($NILAI)){
            $color = $color2;
            $NILAI='';
          } else{
            $color = $color2;
            // $NILAI='';
          }
          $color = str_replace("#", '', $color);
          $objPHPExcel->getActiveSheet()->setCellValue(getColoms($indexs). $row, $NILAI);
          $objPHPExcel->getActiveSheet()->getStyle(getColoms($indexs). $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color);
        }else if($aColumns[$kk]=='STATUS'){
           
  
          if(!empty($tender_evaluation->getField($aColumns[$kk]))){
             $objPHPExcel->getActiveSheet()->setCellValue(getColoms($indexs) . $row,$tender_evaluation->getField('STATUS') );
            $objPHPExcel->getActiveSheet()->getStyle(getColoms($indexs) . $row)->getFont()->setBold(true)
                                ->setName('Verdana')
                                ->setSize(10)
                                ->getColor()->setRGB('FFFFFF');
            // $objPHPExcel->getActiveSheet()->getStyle(getColoms($indexs) . $row)->applyFromArray($styleStatus);
               // $objPHPExcel->getActiveSheet()->getStyle(getColoms($indexs) . $row)->applyFromArray($styleStatus);
                $objPHPExcel->getActiveSheet()->getStyle(getColoms($indexs) . $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('404040');
              }
        }else if($aColumns[$kk]=='NAMA_PSC'){
          $objPHPExcel->getActiveSheet()->setCellValue(getColoms($indexs) . $row,$tender_evaluation->getField('NAMA_PSC') );
          $objPHPExcel->getActiveSheet()->getStyle(getColoms($indexs) . $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
        }else if($aColumns[$kk]=='OWNER'){
          $objPHPExcel->getActiveSheet()->setCellValue(getColoms($indexs) . $row,
            $tender_evaluation->getField('CUR_OWNER')." ".
            currencyToPage2($tender_evaluation->getField('OWNER')) );
          
        }else if($aColumns[$kk]=='BID_VALUE'){
          $objPHPExcel->getActiveSheet()->setCellValue(getColoms($indexs) . $row,
            $tender_evaluation->getField('CUR_BID')." ".
            currencyToPage2($tender_evaluation->getField('BID_VALUE')) );
          
        }else{
          $objPHPExcel->getActiveSheet()->setCellValue(getColoms($indexs) . $row, $wizard->toRichTextObject($tender_evaluation->getField($aColumns[$kk])));
        }



}
 // ->setCellValue('C' . $row, $tender_evaluation->getField("INDEX"))
 // ->setCellValue('D' . $row, $tender_evaluation->getField("NAMA_PSC"))
 // ->setCellValue('E' . $row, $tender_evaluation->getField("TITLE"))
 // ->setCellValue('F' . $row, $tender_evaluation->getField("TENDER_NO"))
 // ->setCellValue('G' . $row, $tender_evaluation->getField("CLOSING"))
 // ->setCellValue('H' . $row, $tender_evaluation->getField("OPENING"));
 // $indexs=1;
 // for($k=0;$k<count($attData);$k++){
 // 	$colomStartD = $indexs+$colomStart;
 // 	$tender_evaluation_detail = new TenderEvaluationDetail();
 // 	$tender_evaluation_detail->selectByParamsMonitoring(array("A.TENDER_EVALUTATION_ID"=>$reqIds,"A.MASTER_TENDER_MENUS_ID"=>$attDataId[$attData[$k]]));
 // 	$tender_evaluation_detail->firstRow();
	

	// $master_tener_menus = new MasterTenerMenus();
	// $master_tener_menus->selectByParamsMonitoring(array("UPPER(A.NAMA)"=>$attData[$k]));
	// $master_tener_menus->firstRow();
 //    $color2 = $master_tener_menus->getField("COLOR2");

 //    $NILAI= $tender_evaluation_detail->getField('NILAI');
 //    if($NILAI == '100'){
 //    	$color = $tender_evaluation_detail->getField("COLOR");
 //    }else if(empty($NILAI)){
 //    	$color = $color2;
 //    	$NILAI='';
 //    } else{
 //    	$color = $color2;
 //    	$NILAI='';
 //    }
 //    $color = str_replace("#", '', $color);
 //    $objPHPExcel->getActiveSheet()->setCellValue(getColoms($colomStartD). $row, $NILAI);
 //    $objPHPExcel->getActiveSheet()->getStyle(getColoms($colomStartD). $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color);


 // 	$indexs++;
 // }
 // $master_tener_menus = new MasterTenerMenus();

// $colomn_plus = $colomIndexPlus+1;
//  $objPHPExcel->getActiveSheet()->setCellValue(getColoms($colomn_plus) . $row, $tender_evaluation->getField("STATUS"));
//  if(!empty($tender_evaluation->getField("STATUS"))){

//  	$objPHPExcel->getActiveSheet()->getStyle(getColoms($colomn_plus). $row)->getFont()->setBold(true)
//                                 ->setName('Verdana')
//                                 ->setSize(10)
//                                 ->getColor()->setRGB('FFFFFF');
//  $objPHPExcel->getActiveSheet()->getStyle(getColoms($colomn_plus) . $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('404040');
//   }

//  $colomn_plus++;
//  $objPHPExcel->getActiveSheet()->setCellValue(getColoms($colomn_plus) . $row, $tender_evaluation->getField("CUR_OWNER").' '.currencyToPage2($tender_evaluation->getField("OWNER")));
//  $colomn_plus++;
//   $objPHPExcel->getActiveSheet()->setCellValue(getColoms($colomn_plus) . $row, $tender_evaluation->getField("CUR_BID").' '.currencyToPage2($tender_evaluation->getField("BID_VALUE")));
//   $colomn_plus++;
//   $objPHPExcel->getActiveSheet()->setCellValue(getColoms($colomn_plus) . $row, $tender_evaluation->getField("TKDN"));
//   $colomn_plus++;
//   $objPHPExcel->getActiveSheet()->setCellValue(getColoms($colomn_plus) . $row, $tender_evaluation->getField("BID_BOUDS"));
//   $colomn_plus++;
//   $objPHPExcel->getActiveSheet()->setCellValue(getColoms($colomn_plus) . $row, $tender_evaluation->getField("BID_VALIDATY"));
//   $colomn_plus++;
//   $objPHPExcel->getActiveSheet()->setCellValue(getColoms($colomn_plus) . $row,  $wizard->toRichTextObject($tender_evaluation->getField("NOTES")));
$row++;
}
$objPHPExcel->getActiveSheet()->removeRow($baseRow - 1, 1);


$objPHPExcel->setActiveSheetIndex(0);
$date =date('YmdHis');
$file_dir = 'uploads/excell/';
makedirs($file_dir);
$filename = 'tender_evaluasi.xls';
$name = $file_dir . 'tender_evaluasi.xls';


$objPHPExcel->setActiveSheetIndex(0);
// header("Content-Type: $mtype; charset=" . $objPHPExcel->sEncoding);
// header("Content-Type:application/octet-stream");
// header("Content-Disposition: inline; filename=" . $filename );

// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// $objWriter->setPreCalculateFormulas(false);

//$name = '/path/to/folder/xyz.xlsx';
// $objWriter->writeAttribute('val', "low");. 
$objWriter->setIncludeCharts(TRUE);
$objWriter->save($name);
// $objWriter->save('php://output');
// $objWriter->save(str_replace('.php', 'hasil_akhir.xlsx', $file_dir));
ob_end_clean();
redirect('uploads/excell/tender_evaluasi.xls', '_blank');
?>
