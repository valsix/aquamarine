<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');


$reqCariNoOrder              = $this->input->get('reqCariNoOrder');
$reqCariPeriodeYearFrom      = $this->input->get('reqCariPeriodeYearFrom');
$reqCariPeriodeYearTo        = $this->input->get('reqCariPeriodeYearTo');



if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
    $statement .= " AND A.TANGGAL BETWEEN  TO_CHAR(" . $reqCariPeriodeYearFrom . ", 'yyyy-MM-dd')  AND TO_CHAR(" . $reqCariPeriodeYearTo . ", 'yyyy-MM-dd') ";
}

if (!empty($reqCariNoOrder)) {
    $statement .= " AND A.KODE LIKE '%" . $reqCariNoOrder . "%' ";
}


$this->load->model("CostRequest");
$cost_request = new CostRequest();

$aColumns = array(
    "NO",
    "KODE", "TANGGAL", "TOTAL", "KETERANGAN"

);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'COST REQUEST REPORT', 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < 5; $i++) {
    if ($i != 0) {
        $panjang_tabel = 66;
    }
    $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
// print_r($arrPanjang);exit;
$cost_request->selectByParamsMonitoringCetakPdf(array(), -1, -1, $statement . $statement_privacy);
// echo $cost_request->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 66, 66, 66, 66, 66, 66, 66));
$no = 1;
while ($cost_request->nextRow()) {
    // $date1 =  $cost_request->getField('DATE1');
    // $date2 =  $cost_request->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $cost_request->getField($aColumns[1]),
    //         '' . $cost_request->getField($aColumns[2]),
    //         '' . $cost_request->getField($aColumns[3]),
    //         '' . $cost_request->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $cost_request->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $cost_request->getField($aColumns[1]),
        '' . $cost_request->getField($aColumns[2]),
        '' . $cost_request->getField($aColumns[3]),
        '' . $cost_request->getField($aColumns[4])
    ));

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $cost_request->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $cost_request->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($cost_request->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($cost_request->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($cost_request->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $cost_request->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>