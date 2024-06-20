<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

        $statement_privacy = " ";
        $reqCariDepartment          = $this->input->get('reqCariDepartment');
        $reqCariSendDateFrom        = $this->input->get('reqCariSendDateFrom');
        $reqCariSendDateTo          = $this->input->get('reqCariSendDateTo');
        $reqCariReceivedDateFrom    = $this->input->get('reqCariReceivedDateFrom');
        $reqCariReceivedDateTo      = $this->input->get('reqCariReceivedDateTo');


        if (!empty($reqCariDepartment)) {
            $statement_privacy .= " AND UPPER(A.DEPARTMENT) LIKE '%" . strtoupper($reqCariNameofCertificate) . "%'";
        }
        if (!empty($reqCariSendDateFrom) && !empty($reqCariSendDateTo)) {
            $statement_privacy .= " AND A.SEND_DATE BETWEEN TO_DATE('" . $reqCariSendDateFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariSendDateTo . "','dd-mm-yyyy')";
        }
        if (!empty($reqCariReceivedDateFrom) && !empty($reqCariReceivedDateTo)) {
            $statement_privacy .= " AND A.RECEIVE_DATE BETWEEN TO_DATE('" . $reqCariReceivedDateFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariReceivedDateTo . "','dd-mm-yyyy')";
        }


$this->load->model("ReportDepartment");
$report_department = new ReportDepartment();

$aColumns = array(
    "NO", "DEPARTMENT", "PROJECT", "CLIENT", "SEND_DATE", "RECEIVE_DATE", "DESCRIPTION"

);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'CERTIFICATE REPORT', 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < 7; $i++) {
    if ($i != 0) {
        $panjang_tabel = 44;
    }
    $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
// print_r($arrPanjang);exit;
$report_department->selectByParamsCetakPdf(array(), -1, -1, $statement . $statement_privacy);
// echo $report_department->query;
// exit;
$pdf->SetFont('Arial', '', 10);
$pdf->SetWidths(array(10, 44, 44, 44, 44, 44, 44));
$no = 1;
while ($report_department->nextRow()) {
    // $date1 =  $report_department->getField('DATE1');
    // $date2 =  $report_department->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $report_department->getField($aColumns[1]),
    //         '' . $report_department->getField($aColumns[2]),
    //         '' . $report_department->getField($aColumns[3]),
    //         '' . $report_department->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $report_department->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $report_department->getField($aColumns[1]),
        '' . $report_department->getField($aColumns[2]),
        '' . $report_department->getField($aColumns[3]),
        '' . $report_department->getField($aColumns[4]),
        '' . $report_department->getField($aColumns[5]),
        '' . $report_department->getField($aColumns[6])
    ));

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $report_department->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $report_department->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($report_department->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($report_department->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($report_department->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $report_department->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>