<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$reqKategori = $this->input->get('reqKategori');
$reqCariCompanyName = $this->input->get('reqCariCompanyName');
$reqCariDescription = $this->input->get('reqCariDescription');
$reqCariExpiredDateFrom = $this->input->post('reqCariExpiredDateFrom');
$reqCariExpiredDateTo = $this->input->post('reqCariExpiredDateTo');

if (!empty($reqKategori)) {
            $statement_privacy = " AND A.CATEGORY='" . $reqKategori . "'";
        }
if (!empty($reqCariCompanyName)) {
    $statement_privacy .= " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%'";
}
if (!empty($reqCariDescription)) {
    $statement_privacy .= " AND UPPER(A.DESCRIPTION) LIKE'%" . $reqCariDescription . "%'";
}


$this->load->model("Dokumen");
$hse = new Dokumen();

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
$pdf->Cell($panjang, 10, 'HSE REPORT', 0, 0, 'C');
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
$hse->selectByParamsDokumentCetakPdf(array(), -1, -1, $statement . $statement_privacy);
// echo $hse->query;exit;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 43, 43, 43, 43, 43, 43, 43));
$no = 1;
while ($hse->nextRow()) {
    // $date1 =  $hse->getField('DATE1');
    // $date2 =  $hse->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $hse->getField($aColumns[1]),
    //         '' . $hse->getField($aColumns[2]),
    //         '' . $hse->getField($aColumns[3]),
    //         '' . $hse->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $hse->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $hse->getField($aColumns[1]),
        '' . $hse->getField($aColumns[2]),
        '' . $hse->getField($aColumns[3]),
        '' . $hse->getField($aColumns[4]),
        '' . $hse->getField($aColumns[5]),
        '' . $hse->getField($aColumns[6])
    ));

    $pdf->MultiCell($panjang - 2.2, 5, 'EXP : ' . $hse->getField('EXP'), 1);


    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $hse->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $hse->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($hse->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($hse->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($hse->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $hse->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>