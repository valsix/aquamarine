<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$reqKategori = $this->input->get('reqKategori');
$reqCariName = $this->input->get('reqCariName');
$reqCariDescription = $this->input->get('reqCariDescription');
$reqJenis = $this->input->get('reqJenis');

if (!empty($reqKategori)) {
    $statement_privacy = " AND A.TYPE='" . $reqKategori . "'";
}

if (!empty($reqCariName)) {
    $statement_privacy .= " AND UPPER(A.NAME)  LIKE '%" . strtoupper($reqCariName) . "%'";
}
if (!empty($reqCariDescription)) {
    $statement_privacy .= " AND UPPER(A.DESCRIPTION)  LIKE '%" . strtoupper($reqCariDescription) . "%'";
}
if (!empty($reqJenis)) {
    $statement_privacy .= " AND A.FORMAT_ID='" . $reqJenis . "'";
}


$this->load->model("DokumenQm");
$qms = new DokumenQm();

$aColumns = array(
    "NO",
    "TYPE", "FORMAT", "NAME", "DESCRIPTION", "PATH"

);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'QMS REPORT', 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < 6; $i++) {
    if ($i != 0) {
        $panjang_tabel = 53;
    }
    $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
// print_r($arrPanjang);exit;
$qms->selectByParamsCetakPdf(array(), -1, -1, $statement . $statement_privacy);
// echo $qms->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 53, 53, 53, 53, 53, 53, 53));
$no = 1;
while ($qms->nextRow()) {
    // $date1 =  $qms->getField('DATE1');
    // $date2 =  $qms->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $qms->getField($aColumns[1]),
    //         '' . $qms->getField($aColumns[2]),
    //         '' . $qms->getField($aColumns[3]),
    //         '' . $qms->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $qms->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $qms->getField($aColumns[1]),
        '' . $qms->getField($aColumns[2]),
        '' . $qms->getField($aColumns[3]),
        '' . $qms->getField($aColumns[4]),
        '' . $qms->getField($aColumns[5])
    ));

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $qms->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $qms->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($qms->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($qms->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($qms->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $qms->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>