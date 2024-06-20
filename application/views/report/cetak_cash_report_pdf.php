<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$reqCariPeriodeYearFrom = $this->input->post('reqCariPeriodeYearFrom');
$reqCariPeriodeYearTo = $this->input->post('reqCariPeriodeYearTo');


// if (!empty($reqCariCompanyName)) {
//     $statement .= " AND A.COMPANY_NAME LIKE '%" . $reqCariCompanyName . "%' ";
// }
// if (!empty($reqCariVasselName)) {
//     $statement .= " AND A.VESSEL_NAME LIKE '%" . $reqCariVasselName . "%' ";
// }
if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
    $statement = " AND TANGGAL BETWEEN  TO_DATE(" . $reqCariPeriodeYearFrom . ", 'yyyy-MM-dd')  AND TO_DATE(" . $reqCariPeriodeYearTo . ", 'yyyy-MM-dd') ";
}

// if (!empty($reqCariNoOrder)) {
//     $statement .= " AND A.INVOICE_NUMBER LIKE '%" . $reqCariNoOrder . "%' ";
// }


$this->load->model("Cash_report");
$cash_flow_report = new Cash_report();

$aColumns = array(
    "NO",
    "TANGGAL", "DESKRIPSI"

);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'CASH FLOW REPORT', 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < 3; $i++) {
    if ($i != 0) {
        $panjang_tabel = 130;
    }
    $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
// print_r($arrPanjang);exit;
$cash_flow_report->selectByParamsCetakPdf(array(), -1, -1, $statement);
// echo $cash_flow_report->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 130, 130, 130, 130, 130, 130, 130));
$no = 1;
while ($cash_flow_report->nextRow()) {
    // $date1 =  $cash_flow_report->getField('DATE1');
    // $date2 =  $cash_flow_report->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $cash_flow_report->getField($aColumns[1]),
    //         '' . $cash_flow_report->getField($aColumns[2]),
    //         '' . $cash_flow_report->getField($aColumns[3]),
    //         '' . $cash_flow_report->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $cash_flow_report->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $cash_flow_report->getField($aColumns[1]),
        '' . $cash_flow_report->getField($aColumns[2])

    ));

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $cash_flow_report->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $cash_flow_report->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($cash_flow_report->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($cash_flow_report->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($cash_flow_report->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $cash_flow_report->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>