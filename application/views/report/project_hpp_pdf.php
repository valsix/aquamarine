<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$reqCariNoOrder             = $this->input->get('reqCariNoOrder');
 $reqCariPeriodeYearFrom      = $this->input->get('reqCariPeriodeYearFrom');
$reqCariPeriodeYearTo        = $this->input->get('reqCariPeriodeYearTo');



if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
  $statement .= " AND A.DATE_PROJECT BETWEEN  TO_CHAR(" . $reqCariPeriodeYearFrom . ", 'yyyy-MM-dd')  AND TO_CHAR(" . $reqCariPeriodeYearTo . ", 'yyyy-MM-dd') ";
}

if (!empty($reqCariNoOrder)) {
  $statement .= " AND UPPER(A.NAMA) LIKE '%" . strtoupper($reqCariNoOrder) . "%' ";
}




$this->load->model("ProjectHpp");
$projectCost = new ProjectHpp();

$aColumns = array(
  "NO",
"REF_NO","OWNER","VESSEL_NAME","LOA","LOCATION","ESTIMASI_PEKERJAAN","COST_FROM_AMDI","PROFIT"
);

$aColumnsAlias = array(
  "NO",
"REF_NO","OWNER","VESSEL_NAME","LOA","LOCATION","DATE","COST_FROM_AMDI","PROFIT"
);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'PROJECT HPP REPORT', 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < 7; $i++) {
  if ($i != 0) {
    $panjang_tabel = 43;
  }
  $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumnsAlias[$i]), 1, 0, 'C');
  array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
// print_r($arrPanjang);exit;
$projectCost->selectByParamsMonitoring(array(), -1, -1, $statement);
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 43, 43, 43, 43, 43, 43, 43));
$no = 1;
while ($projectCost->nextRow()) {
  $date1 =  $projectCost->getField('DATE1');
  $date2 =  $projectCost->getField('DATE2');
  $date_val ='';
    // $MONTH = getFormattedDateEng($projectCost->getField($aColumns[6]));
    //                  $MONTH = explode(' ',  $MONTH);
    $pdf->Row(array(
      $no,
      '' . $projectCost->getField($aColumns[1]),
      '' . $projectCost->getField($aColumns[2]),
      '' . $projectCost->getField('NAMA'),
      '' . $projectCost->getField($aColumns[4]),
      '' . $projectCost->getField($aColumns[5]),
      '' . $projectCost->getField($aColumns[6]),

    ));





  // $pdf->MultiCell($panjang - 2.2, 5, 'DATE PROJECT : ' . "\t" . getFormattedDateEng($projectCost->getField($aColumns[7])), 1, 'J', 0, 10);
  $pdf->MultiCell($panjang - 2.2, 5, 'COST FROM AMDI : ' . currencyToPage2($projectCost->getField($aColumns[7])), 1);
  $pdf->MultiCell($panjang - 2.2, 5, 'PROFIT : ' . currencyToPage2($projectCost->getField($aColumns[8])), 1);
  // $pdf->MultiCell($panjang - 2.2, 5, 'KASBON : ' . currencyToPage2($projectCost->getField('OFFER_PRICE')), 1);
  // $pdf->MultiCell($panjang - 2.2, 5, 'REAL PRICE: ' . currencyToPage2($projectCost->getField('REAL_PRICE')), 1);
  // $pdf->MultiCell($panjang - 2.2, 5, 'SURVEYOR : ' . $projectCost->getField('SURVEYOR'), 1);
  $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>