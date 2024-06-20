<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$reqCariNoOrder         = $this->input->get('reqCariNoOrder');
$reqCariPeriodeYearFrom = $this->input->get('reqCariPeriodeYearFrom');
$reqCariPeriodeYearTo   = $this->input->get('reqCariPeriodeYearTo');
$reqCariCompanyName     = $this->input->get('reqCariCompanyName');
$reqCariVasselName      = $this->input->get('reqCariVasselName');

if (!empty($reqCariNoOrder)) {
    $statement .= "AND I.INVOICE_NUMBER like '%" . $reqCariNoOrder . "%'";
}
if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
    $statement .= " AND I.INVOICE_DATE BETWEEN  TO_CHAR(" . $reqCariPeriodeYearFrom . ", 'yyyy-MM-dd')  AND TO_CHAR(" . $reqCariPeriodeYearTo . ", 'yyyy-MM-dd') ";
}

if (!empty($reqCariCompanyName)) {
    $statement .= "AND I.COMPANY_NAME like '%" . $reqCariCompanyName . "%'";
}
if (!empty($reqCariVasselName)) {
    $statement .= "AND (SELECT VESSEL FROM INVOICE_DETAIL X WHERE X.INVOICE_ID = I.INVOICE_ID LIMIT 1) like '%" . $reqCariVasselName . "%'";
}


$this->load->model("Invoice");
$invoice = new Invoice();

$aColumns = array(
    "NO",
    "INVOICE_NUMBER", "COMPANY_NAME", "VESSEL_NAME", "INVOICE_DATE", "PPN", "STATUS", "TOTAL_AMOUNT"
);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'INVOICE REPORT', 0, 0, 'C');
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
$invoice->selectByParamsCetakPdf(array(), -1, -1, $statement);
// echo $invoice->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 43, 43, 43, 43, 43, 43, 43));
$no = 1;
while ($invoice->nextRow()) {
    // $date1 =  $invoice->getField('DATE1');
    // $date2 =  $invoice->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $invoice->getField($aColumns[1]),
    //         '' . $invoice->getField($aColumns[2]),
    //         '' . $invoice->getField($aColumns[3]),
    //         '' . $invoice->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $invoice->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $invoice->getField($aColumns[1]),
        '' . $invoice->getField($aColumns[2]),
        '' . $invoice->getField($aColumns[3]),
        '' . $invoice->getField($aColumns[4]),
        '' . $invoice->getField($aColumns[5]),
        '' . $invoice->getField($aColumns[6])
    ));

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $invoice->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $invoice->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($invoice->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($invoice->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($invoice->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $invoice->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>