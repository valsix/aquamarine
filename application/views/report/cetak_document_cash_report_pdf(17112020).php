<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$reqCariFind = $this->input->post('reqCariFind');
$reqCariFindMenthod = $this->input->post('reqCariFindMenthod');

if (!empty($reqCariFind)) {
    $statement = " AND A.COMPANY_NAME LIKE '%" . $reqCariCompanyName . "%' ";
}
if (!empty($reqCariFindMenthod)) {
    $statement .= " AND A.VESSEL_NAME LIKE '%" . $reqCariVasselName . "%' ";
}
// if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
//     $statement .= " AND INVOICE_DATE BETWEEN  TO_CHAR(" . $reqCariPeriodeYearFrom . ", 'yyyy-MM-dd')  AND TO_CHAR(" . $reqCariPeriodeYearTo . ", 'yyyy-MM-dd') ";
// }

// if (!empty($reqCariNoOrder)) {
//     $statement .= " AND A.INVOICE_NUMBER LIKE '%" . $reqCariNoOrder . "%' ";
// }

$this->load->model("CashReport");
$this->load->model("CashReportDetil");
$this->load->model("KategoriCash");
$kategori_cash = new KategoriCash();
$cash_report_detil = new CashReportDetil();
$reqId = $this->input->get("reqId");





$cash_report = new CashReport();
$cash_report->selectByParamsMonitoring(array("A.CASH_REPORT_ID"=>$reqId));
$cash_report->firstRow();
$tanggal = $cash_report->getField("TANGGAL");
$tanggal = explode('-', $tanggal);

$statement = ' AND EXISTS( SELECT 1 FROM KATEGORI_CASH WHERE KATEGORI_CASH_ID = A.KATEGORI_ID)';

$kategori_cash->selectByParamsMonitoring(array());
$arrData = array();
$no_body=0;
$no_footer=0;
$NOMER=0;
while ($kategori_cash->nextRow()) {

  
   $cash_report_detil = new CashReportDetil();
   $cash_report_detil->selectByParamsMonitoring(array("A.CASH_REPORT_ID"=>$reqId,"A.KATEGORI_ID"=>$kategori_cash->getField("KATEGORI_CASH_ID")));
   $no=0;
    $arData = array();
   while ( $cash_report_detil->nextRow()) {
       
        $arData[$no]["TANGGAL"]=$cash_report_detil->getField("TANGGAL");
        $arData[$no]["KETERANGAN"]=$cash_report_detil->getField("KETERANGAN");
         $arData[$no]["KREDIT"]=$cash_report_detil->getField("KREDIT");
         $arData[$no]["DEBET"]=$cash_report_detil->getField("DEBET");
         $arData[$no]["KREDIT_USD"]=$cash_report_detil->getField("KREDIT_USD");
         $arData[$no]["DEBET_USD"]=$cash_report_detil->getField("DEBET_USD");
        $no++;
   }

  if($no!=0){
        $arrData[$NOMER]['ID']=$kategori_cash->getField("KATEGORI_CASH_ID");
        $arrData[$NOMER]['NAMA']=$kategori_cash->getField("NAMA");
         $arrData[$NOMER]['FLAG']=$kategori_cash->getField("FLAG");
        $arrData[$NOMER]['RESULTS']=$arData;
    $NOMER++;
  }
  
}



$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('P', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($panjang, 10, 'CASH FLOW PERUSAHAAN', 0, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 9);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < 7; $i++) {
    if ($i != 0) {
        $panjang_tabel = 30;
    }else{
         $panjang_tabel=10;
    }
    // $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
$pdf->Cell(115  , 5, '', '', 0, 'C');
$pdf->Cell(22, 5, 'Perbulan :', '', 0, 'C');
$pdf->Cell(22, 5, getNameMonth((int)$tanggal[1]), '', 0, 'C');
$pdf->Cell(22, 5, 'Th :'.$tanggal[2], '', 0, 'C');

$pdf->Ln();
$pdf->Cell(10, 10, 'No', 1, 0, 'C');
$pdf->Cell(22, 10, 'Tanggal', 1, 0, 'C');
$pdf->Cell(60, 10, 'Keterangan', 1, 0, 'C');
$pdf->Cell(48, 5, 'Debet', 1, 0, 'C');
$pdf->Cell(48, 5, 'Kredit', 1, 0, 'C');
$pdf->ln();

$pdf->Cell(92, 5, '', '', 0, 'C');
$pdf->Cell(24, 5, 'Rupiah', 1, 0, 'C');
$pdf->Cell(24, 5, 'USD', 1, 0, 'C');
$pdf->Cell(24, 5, 'Rupiah', 1, 0, 'C');
$pdf->Cell(24, 5, 'USD', 1, 0, 'C');

$pdf->ln();

$pdf->SetFont('Arial', '', 7);

    $TOTAL_IDR_KREDIT=0;
    $TOTAL_USD_KREDIT=0;
    $TOTAL_IDR_DEBET=0;
    $TOTAL_USD_DEBET=0;
    $nomer_footer =1;
    for($i=0;$i<count($arrData);$i++){
    if($arrData[$i]['FLAG']=='BODY'){
    $nomer=1+$i;
    $pdf->SetWidths(array(10, 22, 60, 24, 24, 24, 24));
    $pdf->Row(array(
         // kolom tabel
         $nomer,
         ''  ,
         '' . $arrData[$i]['NAMA'],
         '' ,
         '' ,
         '' ,
         '' ,
          '' 

     ));
    
      $results =$arrData[$i]['RESULTS'];
      for($j=0;$j<count($results);$j++){
        $nomers = 1+$j;
        $TOTAL_IDR_KREDIT +=$results[$j]['KREDIT'];
        $TOTAL_IDR_DEBET +=$results[$j]['DEBET'];
        $TOTAL_USD_KREDIT +=$results[$j]['KREDIT_USD'];
        $TOTAL_USD_DEBET +=$results[$j]['DEBET_USD'];

        $pdf->Row(array(
         // kolom tabel
       '',
         $results[$j]['TANGGAL']  ,
         
         $nomers.'. '.$results[$j]['KETERANGAN'] ,
         currencyToPage($results[$j]['DEBET']) ,
         currencyToPage($results[$j]['DEBET_USD']) ,
          currencyToPage($results[$j]['KREDIT']) ,
         currencyToPage($results[$j]['KREDIT_USD']) 

     ));

      }

    }
} 
 $pdf->Row(array('',''  ,'' , '' ,'' ,'' , '' , '' ));
 $pdf->Row(array('',''  ,'' , '' ,'' ,'' , '' , '' ));
$pdf->SetFont('Arial', 'B', 8);
 $pdf->Row(array('',''  ,'TOTAL' , currencyToPage($TOTAL_IDR_DEBET) ,currencyToPage($TOTAL_USD_DEBET) ,currencyToPage($TOTAL_IDR_KREDIT) , currencyToPage($TOTAL_USD_KREDIT)  ));

$pdf->SetFont('Arial', '', 7);
 for($i=0;$i<count($arrData);$i++){
    if($arrData[$i]['FLAG']=='FOOTER'){
    $nomer=1+$i;
    $pdf->SetWidths(array(10, 22, 60, 24, 24, 24, 24));
    $pdf->Row(array(
         // kolom tabel
         $nomer,
         ''  ,
         '' . $arrData[$i]['NAMA'],
         '' ,
         '' ,
         '' ,
         '' ,
          '' 

     ));
    
      $results =$arrData[$i]['RESULTS'];
      for($j=0;$j<count($results);$j++){
        $nomers = 1+$j;
        $TOTAL_IDR_KREDIT +=$results[$j]['KREDIT'];
        $TOTAL_IDR_DEBET +=$results[$j]['DEBET'];
        $TOTAL_USD_KREDIT +=$results[$j]['KREDIT_USD'];
        $TOTAL_USD_DEBET +=$results[$j]['DEBET_USD'];

        $pdf->Row(array(
         // kolom tabel
       '',
         $results[$j]['TANGGAL']  ,
         
         $nomers.'. '.$results[$j]['KETERANGAN'] ,
         currencyToPage($results[$j]['DEBET']) ,
         currencyToPage($results[$j]['DEBET_USD']) ,
          currencyToPage($results[$j]['KREDIT']) ,
         currencyToPage($results[$j]['KREDIT_USD']) 

     ));

      }

    }
} 
$pdf->SetFont('Arial', 'B', 8);
 $pdf->Row(array('',''  ,'TOTAL' , currencyToPage($TOTAL_IDR_DEBET) ,currencyToPage($TOTAL_USD_DEBET) ,currencyToPage($TOTAL_IDR_KREDIT) , currencyToPage($TOTAL_USD_KREDIT)  ));
  $pdf->Row(array('',''  ,'LABA RUGI' , currencyToPage($TOTAL_IDR_DEBET) ,currencyToPage($TOTAL_USD_DEBET) ,currencyToPage($TOTAL_IDR_DEBET-$TOTAL_IDR_KREDIT) , currencyToPage($TOTAL_USD_DEBET-$TOTAL_USD_KREDIT)  ));


ob_end_clean();
$pdf->Output();

?>