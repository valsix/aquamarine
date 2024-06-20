<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');


$reqCariName = $this->input->get('reqCariName');

if (!empty($reqCariName)) {
    $statement_privacy .= " AND UPPER(A.DESCRIPTION) LIKE '%" . strtoupper($reqCariName) . "%'";
}


$this->load->model("Statistic");
$statistic_analyst = new Statistic();

$aColumns = array(
    "NO",
    "DESCRIPTION",
    "TAHUN"
);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'STATISTIC ANALYST REPORT', 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < 2; $i++) {
    if ($i != 0) {
        $panjang_tabel = 265;
    }
    $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
// print_r($arrPanjang);exit;
$statistic_analyst->selectByParamsMonitoringAll(array(), -1, -1,  $statement_privacy,'');
// echo $statistic_analyst->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 265, 265, 265, 265, 265, 265, 265));
$no = 1;
while ($statistic_analyst->nextRow()) {
    // $date1 =  $statistic_analyst->getField('DATE1');
    // $date2 =  $statistic_analyst->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $statistic_analyst->getField($aColumns[1]),
    //         '' . $statistic_analyst->getField($aColumns[2]),
    //         '' . $statistic_analyst->getField($aColumns[3]),
    //         '' . $statistic_analyst->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $statistic_analyst->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $statistic_analyst->getField($aColumns[1])

    ));

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $statistic_analyst->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $statistic_analyst->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($statistic_analyst->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($statistic_analyst->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($statistic_analyst->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $statistic_analyst->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>