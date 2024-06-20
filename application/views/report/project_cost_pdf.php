<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$reqCariNoOrder             = $this->input->get('reqCariNoOrder');
$reqCariCompanyName          = $this->input->get('reqCariCompanyName');
$reqCariPeriodeYearFrom      = $this->input->get('reqCariPeriodeYearFrom');
$reqCariPeriodeYearTo        = $this->input->get('reqCariPeriodeYearTo');
$reqCariVasselName           = $this->input->get('reqCariVasselName');
$reqCariGlobal               = $this->input->get('reqCariGlobal');

if (!empty($reqCariCompanyName)) {
  $statement .= " AND UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%' ";
}
if (!empty($reqCariVasselName)) {
  $statement .= " AND UPPER(A.VESSEL_NAME) LIKE '%" . strtoupper($reqCariVasselName) . "%' ";
}
if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
  $statement .= " AND DATE_SERVICE1 BETWEEN  TO_CHAR(" . $reqCariPeriodeYearFrom . ", 'yyyy-MM-dd')  AND TO_CHAR(" . $reqCariPeriodeYearTo . ", 'yyyy-MM-dd') ";
}

if (!empty($reqCariNoOrder)) {
  $statement .= " AND UPPER(A.NO_PROJECT) LIKE '%" . strtoupper($reqCariNoOrder) . "%' ";
}




$this->load->model("Project_cost");
   $this->load->model("CostProjectDetil");
$projectCost = new Project_cost();

$aColumns = array(
  "NO",
  "NO_PROJECT", "VESSEL_NAME", "CLASS_OF_VESSEL","TYPE_OF_VESSEL", "TYPE_OF_SERVICE", "DATE_OF_SERVICE", "DESTINATION", "COMPANY_NAME", "CONTACT_PERSON", "KASBON",
  "OFFER_PRICE", "REAL_PRICE", "SURVEYOR"
);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'COST PROJECT REPORT', 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < 7; $i++) {
  if ($i != 0) {
    $panjang_tabel = 43;
  }
  $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
  array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
// print_r($arrPanjang);exit;
$projectCost->selectByParams(array(), -1, -1, $statement);
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 43, 43, 43, 43, 43, 43, 43));
$no = 1;
while ($projectCost->nextRow()) {
  $date1 =  $projectCost->getField('DATE1');
  $date2 =  $projectCost->getField('DATE2');
  $ids = $projectCost->getField('COST_PROJECT_ID');
  $cost_project_detil = new CostProjectDetil();
  $cost_project_detil->selectByParamsMonitoring(array("A.COST_PROJECT_ID"=>$ids));
  $total = 0;
  while ( $cost_project_detil->nextRow()) {
    $total += ifZero2($cost_project_detil->getField("COST"));
  }
  

    $pdf->Row(array(
      $no,
      '' . $projectCost->getField($aColumns[1]),
      '' . $projectCost->getField($aColumns[2]),
      '' . $projectCost->getField($aColumns[3]),
      '' . $projectCost->getField($aColumns[4]),
      '' . $projectCost->getField($aColumns[5]),
      '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    

    ));




  $pdf->MultiCell($panjang - 2.2, 5, 'DESTINATION  : ' . "\t" . $projectCost->getField($aColumns[6]), 1, 'J', 0, 10);  
  $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $projectCost->getField($aColumns[7]), 1, 'J', 0, 10);
  $pdf->MultiCell($panjang - 2.2, 5, 'CONTACT PERSON : ' . $projectCost->getField('CONTACT_PERSON'), 1);
  $pdf->MultiCell($panjang - 2.2, 5, 'OFFER PRICE : ' . currencyToPage2($projectCost->getField('KASBON')), 1);
  $pdf->MultiCell($panjang - 2.2, 5, 'ADVANCE_SURVEY : ' . currencyToPage2($projectCost->getField('OFFER_PRICE')), 1);
  $pdf->MultiCell($panjang - 2.2, 5, 'REAL PRICE: ' . currencyToPage2($projectCost->getField('REAL_PRICE')), 1);
  $pdf->MultiCell($panjang - 2.2, 5, 'SURVEYOR : ' . $projectCost->getField('SURVEYOR'), 1);
  $pdf->MultiCell($panjang - 2.2, 5, 'OVER HEAD : ' . currencyToPage2($total), 1);
  $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>