<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$reqKategori = $this->input->get('reqKategori');

$reqCariCompanyName = $this->input->get('reqCariCompanyName');
$reqCariDescription = $this->input->get('reqCariDescription');
$reqCariExpiredDateFrom = $this->input->get('reqCariExpiredDateFrom');
$reqCariExpiredDateTo = $this->input->get('reqCariExpiredDateTo');

if (!empty($reqKategori)) {
    $statement_privacy = " AND A.CATEGORY='" . $reqKategori . "'";
}

if (!empty($reqCariCompanyName)) {
    $statement_privacy .= " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%'";
}
if (!empty($reqCariDescription)) {
    $statement_privacy .= " AND UPPER(A.DESCRIPTION) LIKE'%" . $reqCariDescription . "%'";
}
if (!empty($reqCariExpiredDateFrom) &&  !empty($reqCariExpiredDateTo)) {
    $statement_privacy .= " AND A.EXPIRED_DATE BETWEEN  TO_DATE('" . $reqCariExpiredDateFrom . "','dd-mm-yyyy')  AND TO_DATE('" . $reqCariExpiredDateFrom . "','dd-mm-yyyy') ";
}


$this->load->model("Dokumen");
$legality_letters = new Dokumen();

$aColumns = array(
    "NO",
    "CATEGORY", "NAME", "DESCRIPTION", "PATH", "LAST_REVISI", "EXPIRED_DATE", "EXP"
);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'LEGALITY LETTERS REPORT', 0, 0, 'C');
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
$legality_letters->selectByParamsDokumentCetakPdf(array(), -1, -1, $statement . $statement_privacy);
// echo $legality_letters->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 43, 43, 43, 43, 43, 43, 43));
$no = 1;
while ($legality_letters->nextRow()) {
    // $date1 =  $legality_letters->getField('DATE1');
    // $date2 =  $legality_letters->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $legality_letters->getField($aColumns[1]),
    //         '' . $legality_letters->getField($aColumns[2]),
    //         '' . $legality_letters->getField($aColumns[3]),
    //         '' . $legality_letters->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $legality_letters->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $legality_letters->getField($aColumns[1]),
        '' . $legality_letters->getField($aColumns[2]),
        '' . $legality_letters->getField($aColumns[3]),
        '' . $legality_letters->getField($aColumns[4]),
        '' . $legality_letters->getField($aColumns[5]),
        '' . $legality_letters->getField($aColumns[6])
    ));

    $pdf->MultiCell($panjang - 2.2, 5, 'EXP : ' . $legality_letters->getField('EXP'), 1);


    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $legality_letters->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $legality_letters->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($legality_letters->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($legality_letters->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($legality_letters->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $legality_letters->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>