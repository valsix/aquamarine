<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

 $statement_privacy = "";
        $reqCariCompanyName  =  $this->input->get('reqCariCompanyName');
        $reqCariDescription  =  $this->input->get('reqCariDescription');
        $reqPeriod           =  $this->input->get('reqPeriod');
        if (!empty($reqCariCompanyName)) {
            $statement_privacy .= " AND UPPER(A.NAME) LIKE '%" . $reqCariCompanyName . "%'";
        }
        if (!empty($reqCariDescription)) {
            $statement_privacy .= " AND UPPER(A.DESCRIPTION) LIKE '%" . $reqCariDescription . "%'";
        }
        if (!empty($reqPeriod)) {
            $statement_privacy .= " AND A.PERIOD = '" . $reqPeriod . "'";
        }



$this->load->model("Bussines_plan");
$bussines_plan = new Bussines_plan();

$aColumns = array(
    "NO",
    "PERIOD", "NAME", "DESCRIPTION", "PATH", "LAST_REVISI"
);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'BUSSINES PLAN REPORT', 0, 0, 'C');
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
$bussines_plan->selectByParamsCetakPdf(array(), -1, -1, $statement . $statement_privacy);
// echo $bussines_plan->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 53, 53, 53, 53, 53, 53, 53));
$no = 1;
while ($bussines_plan->nextRow()) {
    // $date1 =  $bussines_plan->getField('DATE1');
    // $date2 =  $bussines_plan->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $bussines_plan->getField($aColumns[1]),
    //         '' . $bussines_plan->getField($aColumns[2]),
    //         '' . $bussines_plan->getField($aColumns[3]),
    //         '' . $bussines_plan->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $bussines_plan->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $bussines_plan->getField($aColumns[1]),
        '' . $bussines_plan->getField($aColumns[2]),
        '' . $bussines_plan->getField($aColumns[3]),
        '' . $bussines_plan->getField($aColumns[4]),
        '' . $bussines_plan->getField($aColumns[5])

    ));

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $bussines_plan->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $bussines_plan->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($bussines_plan->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($bussines_plan->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($bussines_plan->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $bussines_plan->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>