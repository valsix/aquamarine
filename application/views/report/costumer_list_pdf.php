<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');



$reqId = $this->input->get('reqId');
$reqId = explode(',',  $reqId);


$this->load->model("Customer");
$customer = new Customer();

$aColumns = array(
  "NO", "NAME",  "ADDRESS", "PHONE", "FAX", "EMAIL", "CP1_NAME",
  "CP1_TELP", "CP2_NAME", "CP2_TELP"
);

for ($i = 0; $i < count($reqId); $i++) {
  if (!empty($reqId[$i])) {
    if ($i == 0) {
      $statement .= " AND A.COMPANY_ID = '" . $reqId[$i] . "' ";
    } else {
      $statement .= " OR A.COMPANY_ID = '" . $reqId[$i] . "' ";
    }
  }
}



$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'REPORT COSTUMER  LIST', 0, 0, 'C');
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
$customer->selectByParams(array(), -1, -1, $statement);
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths($arrPanjang);
$no = 1;
while ($customer->nextRow()) {


  $pdf->Row(array(
    $no,
    '' . $customer->getField($aColumns[1]),
    '' . $customer->getField($aColumns[2]),
    '' . $customer->getField($aColumns[3]),
    '' . $customer->getField($aColumns[4]),
    '' . $customer->getField($aColumns[5]),
    '' . $customer->getField($aColumns[6]),

  ));





  $pdf->MultiCell($panjang - 2.2, 5, $aColumns[7] . ' : ' . "\t" . $customer->getField($aColumns[7]), 1, 'J', 0, 10);
  $pdf->MultiCell($panjang - 2.2, 5, $aColumns[8] . ' : ' . $customer->getField($aColumns[8]), 1);
  $pdf->MultiCell($panjang - 2.2, 5, $aColumns[9] . ' : ' . $customer->getField($aColumns[9]), 1);

  $no++;
}


ob_end_clean();
$pdf->Output();

?>