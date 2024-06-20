<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$reqCariFind = $this->input->post('reqCariFind');
$reqCariFindMenthod = $this->input->post('reqCariFindMenthod');

if (!empty($reqCariFind)) {
    $statement = " AND A.COMPANY_NAME LIKE '%" . $reqCariCompanyName . "%' ";
}
if (!empty($reqCariFindMenthod)) {
    $statement .= " AND A.VESSEL_NAME LIKE '%" . $reqCariVasselName . "%' ";
}
// if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
//     $statement .= " AND INVOICE_DATE BETWEEN  TO_CHAR(" . $reqCariPeriodeYearFrom . ", 'yyyy-MM-dd')  AND TO_CHAR(" . $reqCariPeriodeYearTo . ", 'yyyy-MM-dd') ";
// }

// if (!empty($reqCariNoOrder)) {
//     $statement .= " AND A.INVOICE_NUMBER LIKE '%" . $reqCariNoOrder . "%' ";
// }


$this->load->model("DokumenMarketing");
$document_marketing = new DokumenMarketing();

$aColumns = array(
    "NO",
    "COMPANY_NAME", "VESSEL_NAME", "DESCRIPTION", "LAST_REVISI", "TYPE_OF_SERVICE", "LOCATION", "DATE_OPERATION", "CLASS_RULES"
);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'DOCUMENT REPORT', 0, 0, 'C');
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
$document_marketing->selectByParamsCetakPdf(array(), -1, -1, $statement);
// echo $document_marketing->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 43, 43, 43, 43, 43, 43, 43));
$no = 1;
while ($document_marketing->nextRow()) {
    // $date1 =  $document_marketing->getField('DATE1');
    // $date2 =  $document_marketing->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $document_marketing->getField($aColumns[1]),
    //         '' . $document_marketing->getField($aColumns[2]),
    //         '' . $document_marketing->getField($aColumns[3]),
    //         '' . $document_marketing->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $document_marketing->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $document_marketing->getField($aColumns[1]),
        '' . $document_marketing->getField($aColumns[2]),
        '' . $document_marketing->getField($aColumns[3]),
        '' . $document_marketing->getField($aColumns[4]),
        '' . $document_marketing->getField($aColumns[5]),
        '' . $document_marketing->getField($aColumns[6]),

    ));

    $pdf->MultiCell($panjang - 2.2, 5, 'CLASS RULES : ' . $document_marketing->getField('CLASS_RULES'), 1);

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $document_marketing->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $document_marketing->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($document_marketing->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($document_marketing->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($document_marketing->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $document_marketing->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>