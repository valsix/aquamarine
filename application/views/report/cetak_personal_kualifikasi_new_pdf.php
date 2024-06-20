<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

        $reqCariCompanyName = $this->input->get('reqCariCompanyName');
        $reqCariTypeofQualification = $this->input->get('reqCariTypeofQualification');
        $reqTypeOfService = $this->input->get('reqTypeOfService');


        if (!empty($reqCariCompanyName)) {
            $statement_privacy .= " AND A.NAME LIKE '%" . $reqCariCompanyName . "%'";
        }
        if (!empty($reqCariTypeofQualification)) {
            $statement_privacy .= " AND A.JENIS='" . $reqCariTypeofQualification . "'";
        }
        if (!empty($reqTypeOfService)) {
            $reqTypeOfService =str_replace('-', ',', $reqTypeOfService);
            $statement_privacy .= "   AND A.DOCUMENT_ID IN (SELECT C.DOCUMENT_ID FROM DETIL_PERSONAL_CERTIFICATE C WHERE C.CERTIFICATE_ID IN (" . $reqTypeOfService . "))";
        }




$this->load->model("JenisKualifikasi");
$personal_kualifikasi = new JenisKualifikasi();

$aColumns = array(
    "NO",
    "NAME", "ADDRESS", "BIRTH_DATE", "PHONE", "QUALIFICATION", "CERTIFICATE"
);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;

$pdf->Ln();


$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'PERSONAL KUALIFIKASI REPORT', 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 7);

$pdf->MultiCell(8, 10, 'No', 1, 0, 'C');
$pdf->MultiCell(18, 10, 'ID Number', 1, 0, 'C');
$pdf->Cell(33, 10, 'NAME', 1, 0, 'C');
$pdf->Cell(18, 10, 'ID CARD', 1, 0, 'C');
$pdf->Cell(18, 10, 'POSITION', 1, 0, 'C');
$pdf->Cell(18, 10, 'LOCATION', 1, 0, 'C');
$pdf->Cell(18, 10, "AGE \n (TH)", 1, 0, 'C');
$pdf->Cell(18, 10, 'CONTACT', 1, 0, 'C');
$pdf->Cell(18, 10, 'IMCA', 1, 0, 'C');
$pdf->Cell(18, 10, 'ADCI', 1, 0, 'C');
$pdf->Cell(18, 10, "BOSIET / \n BST / \n BSS", 1, 0, 'C');
$pdf->Cell(18, 10, 'ID Number', 1, 0, 'C');
$pdf->Cell(18, 10, 'ID Number', 1, 0, 'C');
$pdf->Cell(18, 10, 'ID Number', 1, 0, 'C');
$pdf->Cell(18, 10, 'ID Number', 1, 0, 'C');


$pdf->ln();

// $pdf->Cell(92, 5, '', '', 0, 'C');
// $pdf->Cell(24, 5, 'Rupiah', 1, 0, 'C');
// $pdf->Cell(24, 5, 'USD', 1, 0, 'C');
// $pdf->Cell(24, 5, 'Rupiah', 1, 0, 'C');
// $pdf->Cell(24, 5, 'USD', 1, 0, 'C');
// $pdf->ln();

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
$personal_kualifikasi->selectByParamsMonitoringPersonalKualifikasiCetakPdf(array(), -1, -1, $statement.' '.$statement_privacy,' ORDER BY A.DOCUMENT_ID asc');
// echo $personal_kualifikasi->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 53, 53, 53, 53, 53, 53, 53));
$no = 1;
while ($personal_kualifikasi->nextRow()) {
    // $date1 =  $personal_kualifikasi->getField('DATE1');
    // $date2 =  $personal_kualifikasi->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $personal_kualifikasi->getField($aColumns[1]),
    //         '' . $personal_kualifikasi->getField($aColumns[2]),
    //         '' . $personal_kualifikasi->getField($aColumns[3]),
    //         '' . $personal_kualifikasi->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $personal_kualifikasi->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $personal_kualifikasi->getField($aColumns[1]),
        '' . $personal_kualifikasi->getField($aColumns[2]),
        '' . $personal_kualifikasi->getField($aColumns[3]),
        '' . $personal_kualifikasi->getField($aColumns[4]),
        '' . $personal_kualifikasi->getField($aColumns[5])
    ));

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $personal_kualifikasi->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $personal_kualifikasi->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($personal_kualifikasi->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($personal_kualifikasi->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($personal_kualifikasi->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $personal_kualifikasi->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>