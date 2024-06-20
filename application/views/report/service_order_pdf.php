<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');





$this->load->model("Service_order");
$serviceOrder = new Service_order();

$aColumns = array(
       "NO", "NO_ORDER", "PROJECT_NAME", "COMPANY_NAME", "VESSEL_NAME", "VESSEL_TYPE", "SURVEYOR", "DESTINATION", "SERVICE", "DATE_OF_START", "DATE_OF_FINISH",
       "EQUIPMENT", "DATE_OF_SERVICE"
);

$reqCariNoOrder          = $this->input->get('reqCariNoOrder');
$reqCariPeriodeYearFrom  = $this->input->get('reqCariPeriodeYearFrom');
$reqCariPeriodeYearTo    = $this->input->get('reqCariPeriodeYearTo');
$reqCariCompanyName      = $this->input->get('reqCariCompanyName');
$reqCariPeriodeYear      = $this->input->get('reqCariPeriodeYear');
$reqCariVasselName       = $this->input->get('reqCariVasselName');
$reqCariProject          = $this->input->get('reqCariProject');
$reqCariGlobal           = $this->input->get('reqCariGlobal');


if (!empty($reqCariNoOrder)) {
       $statement_privacy .= " AND  A.NO_ORDER LIKE '%" . $reqCariNoOrder . "%' ";
}
if (!empty($reqCariPeriodeYearFrom) || !empty($reqCariPeriodeYearTo)) {
       $statement_privacy .= " AND A.DATE_OF_SERVICE BETWEEN TO_CHAR('" . $reqCariPeriodeYearFrom . "','dd-mm-yyyy') AND  TO_CHAR('" . $reqCariPeriodeYearTo . "','dd-mm-yyyy')";
}

if (!empty($reqCariCompanyName)) {
       $statement_privacy .= " AND  A.COMPANY_NAME LIKE '%" . $reqCariCompanyName . "%' ";
}
if (!empty($reqCariPeriodeYear)) {
       $mtgl_awal = '01-01-' . $reqCariPeriodeYear;
       $mtgl_akhir = '31-12-' . $reqCariPeriodeYear;
       if ($reqCariPeriodeYear != 'All Year') {
              $statement_privacy .= " AND A.DATE_OF_SERVICE BETWEEN TO_CHAR('" . $mtgl_awal . "','dd-mm-yyyy') AND  TO_CHAR('" . $mtgl_akhir . "','dd-mm-yyyy')";
       }
}
if (!empty($reqCariVasselName)) {
       $statement_privacy .= " AND A.VESSEL_NAME LIKE '%" . $reqCariVasselName . "%' ";
}
if(!empty($reqCariGlobal)){
  $statement_privacy .= " AND (  UPPER(A.SERVICE) LIKE   '%".strtoupper($reqCariGlobal)."%' ";
  $statement_privacy .= " OR UPPER(A.COMPANY_NAME) LIKE '%".strtoupper($reqCariGlobal)."%' ";
  $statement_privacy .= " OR UPPER(A.VESSEL_NAME) LIKE '%".strtoupper($reqCariGlobal)."%' ";
  $statement_privacy .= " OR UPPER(A.NO_ORDER) LIKE '%".strtoupper($reqCariGlobal)."%' ) ";
} 

 if(!empty($sOrder)){
             $sOrder = ' ORDER BY A.SO_ID DESC';
}   

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'SERVICE ORDER LIST REPORT', 0, 0, 'C');
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
$serviceOrder->selectByParams(array(), -1, -1, $statement,$sOrder);
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths($arrPanjang);
$no = 1;
while ($serviceOrder->nextRow()) {


       $pdf->Row(array(
              $no,
              '' . $serviceOrder->getField($aColumns[1]),
              '' . $serviceOrder->getField($aColumns[2]),
              '' . $serviceOrder->getField($aColumns[3]),
              '' . $serviceOrder->getField($aColumns[4]),
              '' . $serviceOrder->getField($aColumns[5]),
              '' . $serviceOrder->getField($aColumns[6]),

       ));





       $pdf->MultiCell($panjang - 2.2, 5, $aColumns[7] . ' : ' . "\t" . $serviceOrder->getField($aColumns[7]), 1, 'J', 0, 10);
       $pdf->MultiCell($panjang - 2.2, 5, $aColumns[8] . ' : ' . $serviceOrder->getField($aColumns[8]), 1);
       $pdf->MultiCell($panjang - 2.2, 5, $aColumns[9] . ' : ' . $serviceOrder->getField($aColumns[9]), 1);
       $pdf->MultiCell($panjang - 2.2, 5, $aColumns[10] . ' : ' . $serviceOrder->getField($aColumns[10]), 1);
       $pdf->MultiCell($panjang - 2.2, 5, $aColumns[10] . ' : ' . $serviceOrder->getField($aColumns[11]), 1);
       $pdf->MultiCell($panjang - 2.2, 5, $aColumns[10] . ' : ' . $serviceOrder->getField($aColumns[12]), 1);

       $no++;
}


ob_end_clean();
$pdf->Output();

?>