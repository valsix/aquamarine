<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

        $statement_privacy = " ";
        $reqCariNameofCertificate = $this->input->get('reqCariNameofCertificate');
        $reqCariTypeofCertificate = $this->input->get('reqCariTypeofCertificate');
        $reqCariIssueDateFrom     = $this->input->get('reqCariIssueDateFrom');
        $reqCariIssueDateTo       = $this->input->get('reqCariIssueDateTo');
        $reqCariExpiredDateFrom   = $this->input->get('reqCariExpiredDateFrom');
        $reqCariExpiredDateTo     = $this->input->get('reqCariExpiredDateTo');
        $reqCariGlobalSearch      = $this->input->get('reqCariGlobalSearch');


        if (!empty($reqCariNameofCertificate)) {
            $statement_privacy .= " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqCariNameofCertificate) . "%'";
        }
        if (!empty($reqCariTypeofCertificate)) {
            $statement_privacy .= " AND UPPER(A.CERTIFICATE_ID) LIKE '%" . strtoupper($reqCariTypeofCertificate) . "%'";
        }

        if (!empty($reqCariIssueDateFrom) && !empty($reqCariIssueDateTo)) {
            $statement_privacy .= " AND A.ISSUED_DATE BETWEEN TO_DATE('" . $reqCariIssueDateFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariIssueDateTo . "','dd-mm-yyyy')";
        }
        if (!empty($reqCariExpiredDateFrom) && !empty($reqCariExpiredDateTo)) {
            $statement_privacy .= " AND A.EXPIRED_DATE BETWEEN TO_DATE('" . $reqCariIssueDateFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariIssueDateTo . "','dd-mm-yyyy')";
        }
        if (!empty($reqCariGlobalSearch)) {
            $statement_privacy .= " AND UPPER(A.SURVEYOR) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%'";
        }


$this->load->model("DokumenCertificate");
$certificate = new DokumenCertificate();

$aColumns = array(
    "NO",
    "NAME", "ISSUED_DATE", "EXPIRED_DATE", "SURVEYOR"

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
for ($i = 0; $i < 5; $i++) {
    if ($i != 0) {
        $panjang_tabel = 66;
    }
    $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
// print_r($arrPanjang);exit;
$certificate->selectByParamsCetakPdf(array(), -1, -1, $statement . $statement_privacy);
// echo $certificate->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 66, 66, 66, 66, 66, 66, 66));
$no = 1;
while ($certificate->nextRow()) {
    // $date1 =  $certificate->getField('DATE1');
    // $date2 =  $certificate->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $certificate->getField($aColumns[1]),
    //         '' . $certificate->getField($aColumns[2]),
    //         '' . $certificate->getField($aColumns[3]),
    //         '' . $certificate->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $certificate->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $certificate->getField($aColumns[1]),
        '' . $certificate->getField($aColumns[2]),
        '' . $certificate->getField($aColumns[3]),
        '' . $certificate->getField($aColumns[4])
    ));

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $certificate->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $certificate->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($certificate->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($certificate->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($certificate->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $certificate->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>