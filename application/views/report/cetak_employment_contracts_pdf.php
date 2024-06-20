<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');


$reqCariCompanyName = $this->input->get('reqCariCompanyName');
$reqCariDescription = $this->input->get('reqCariDescription');
$reqType = $this->input->get('reqType');
$reqSearch = $this->input->get('reqSearch');

if (!empty($reqCariCompanyName)) {
    $statement_privacy .= " AND  UPPER(A.NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%'";
}
if (!empty($reqCariDescription)) {
    $statement_privacy .= " AND  UPPER(A.DESCRIPTION) LIKE '%" . strtoupper($reqCariDescription) . "%'";
}
if (!empty($reqType) && $reqType != "ALL") {
    $statement_privacy .= " AND A.TYPE ='" . $reqType . "'";
}

// $statement = " AND (UPPER(NAME) LIKE '%" . strtoupper($reqSearch) . "%')";

$this->load->model("Employment_contracts");
$employment_contracts = new Employment_contracts();

$aColumns = array(
    "NO",
    "TYPE", 'TAHUN', "NAME", "DESCRIPTION"

);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'EMPLOYMENT CONTRACTS REPORT', 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < 5; $i++) {
    if ($i == 0) {
        $panjang_tabel = 10;
    }
    else if ($i == 1) {
        $panjang_tabel = 40;
    }
    else if ($i == 2) {
        $panjang_tabel = 36;
    }
    else {
        $panjang_tabel = 95;
    }
    $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
// print_r($arrPanjang);exit;
$employment_contracts->selectByParamsCetakPdf(array(), -1, -1, $statement . $statement_privacy, " ORDER BY DOCUMENT_ID desc");
// echo $employment_contracts->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 40, 36, 95, 95));
$no = 1;
while ($employment_contracts->nextRow()) {
    // $date1 =  $employment_contracts->getField('DATE1');
    // $date2 =  $employment_contracts->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $employment_contracts->getField($aColumns[1]),
    //         '' . $employment_contracts->getField($aColumns[2]),
    //         '' . $employment_contracts->getField($aColumns[3]),
    //         '' . $employment_contracts->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $employment_contracts->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $employment_contracts->getField($aColumns[1]),
        '' . $employment_contracts->getField($aColumns[2]),
        '' . $employment_contracts->getField($aColumns[3]),
        '' . $employment_contracts->getField($aColumns[4])
    ));


    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $employment_contracts->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $employment_contracts->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($employment_contracts->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($employment_contracts->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($employment_contracts->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $employment_contracts->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>