<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');


$reqCariCompanyName = $this->input->get('reqCariCompanyName');
        $reqCariDescription = $this->input->get('reqCariDescription');
        $reqCariCategori = $this->input->get('reqCariCategori');


        if (!empty($reqCariCompanyName)) {
            $statement_privacy = " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%'";
        }
        if (!empty($reqCariDescription)) {
            $statement_privacy .= " AND UPPER(A.DESCRIPTION) LIKE '%" . strtoupper($reqCariCompanyName) . "%'";
        }
        if (!empty($reqCariCategori)) {
            $statement_privacy .= " AND A.CATEGORY_ID = '" . $reqCariCategori . "'";
        }


// if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
//     $statement .= " AND INVOICE_DATE BETWEEN  TO_CHAR(" . $reqCariPeriodeYearFrom . ", 'yyyy-MM-dd')  AND TO_CHAR(" . $reqCariPeriodeYearTo . ", 'yyyy-MM-dd') ";
// }


// if (!empty($reqCariNoOrder)) {
//     $statement .= " AND A.INVOICE_NUMBER LIKE '%" . $reqCariNoOrder . "%' ";
// }


$this->load->model("DokumenOther");
$other = new DokumenOther();

$aColumns = array(
    "NO",
    "NAME", "DESCRIPTION", "PATH", "LAST_REVISI", "NAMA_CATEGORI"

);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'OTHERS REPORT', 0, 0, 'C');
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
$other->selectByParamsCetakPdf(array(), -1, -1, $statement.' '.$statement_privacy);
// echo $other->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 53, 53, 53, 53, 53, 53, 53));
$no = 1;
while ($other->nextRow()) {
    // $date1 =  $other->getField('DATE1');
    // $date2 =  $other->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $other->getField($aColumns[1]),
    //         '' . $other->getField($aColumns[2]),
    //         '' . $other->getField($aColumns[3]),
    //         '' . $other->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $other->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $other->getField($aColumns[1]),
        '' . $other->getField($aColumns[2]),
        '' . $other->getField($aColumns[3]),
        '' . $other->getField($aColumns[4]),
        '' . $other->getField($aColumns[5])

    ));


    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $other->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $other->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($other->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($other->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($other->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $other->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>