<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$reqKategori = $this->input->get('reqKategori');
$reqCariCompanyName = $this->input->get('reqCariCompanyName');
$reqCariDescription = $this->input->get('reqCariDescription');

if (!empty($reqKategori)) {
    $statement_privacy = " AND A.CATEGORY='" . strtoupper($reqKategori) . "'";
}

if (!empty($reqCariCompanyName)) {
    $statement_privacy .= " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%'";
}

if (!empty($reqCariDescription)) {
    $statement_privacy .= " AND UPPER(A.DESCRIPTION) LIKE'%" . strtoupper($reqCariDescription) . "%'";
}

$this->load->model("Dokumen");
$qhse = new Dokumen();

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
$pdf->Cell($panjang, 10, 'QHSE REPORT', 0, 0, 'C');
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
$qhse->selectByParamsDokumentCetakPdf(array(), -1, -1, $statement . $statement_privacy);
// echo $qhse->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 43, 43, 43, 43, 43, 43, 43));
$no = 1;
while ($qhse->nextRow()) {
    // $date1 =  $qhse->getField('DATE1');
    // $date2 =  $qhse->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $qhse->getField($aColumns[1]),
    //         '' . $qhse->getField($aColumns[2]),
    //         '' . $qhse->getField($aColumns[3]),
    //         '' . $qhse->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $qhse->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $qhse->getField($aColumns[1]),
        '' . $qhse->getField($aColumns[2]),
        '' . $qhse->getField($aColumns[3]),
        '' . $qhse->getField($aColumns[4]),
        '' . $qhse->getField($aColumns[5]),
        '' . $qhse->getField($aColumns[6])
    ));

    $pdf->MultiCell($panjang - 2.2, 5, 'EXP : ' . $qhse->getField('EXP'), 1);

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $qhse->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $qhse->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($qhse->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($qhse->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($qhse->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $qhse->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>