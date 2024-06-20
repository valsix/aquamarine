<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$this->load->model("Users_management");
$users_management = new Users_management();

$aColumns = array(
    "NO",
    "USERNAME", "FULLNAME",  "LEVEL", "MENUMARKETING", "MENUFINANCE", "MENUPRODUCTION", "MENUDOCUMENT", "MENUSEARCH", "MENUOTHERS"
);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'USER MANAGEMENT REPORT', 0, 0, 'C');
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
$users_management->selectByParamsCetakPdf(array(), -1, -1, $statement . $statement_privacy);
// echo $users_management->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 43, 43, 43, 43, 43, 43, 43));
$no = 1;
while ($users_management->nextRow()) {
    // $date1 =  $users_management->getField('DATE1');
    // $date2 =  $users_management->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $users_management->getField($aColumns[1]),
    //         '' . $users_management->getField($aColumns[2]),
    //         '' . $users_management->getField($aColumns[3]),
    //         '' . $users_management->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $users_management->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $users_management->getField($aColumns[1]),
        '' . $users_management->getField($aColumns[2]),
        '' . $users_management->getField($aColumns[3]),
        '' . $users_management->getField($aColumns[4]),
        '' . $users_management->getField($aColumns[5]),
        '' . $users_management->getField($aColumns[6])

    ));

    $pdf->MultiCell($panjang - 2.2, 5, 'MENUDOCUMENT : ' . $users_management->getField('MENUDOCUMENT'), 1);
    $pdf->MultiCell($panjang - 2.2, 5, 'MENUSEARCH : ' . $users_management->getField('MENUSEARCH'), 1);
    $pdf->MultiCell($panjang - 2.2, 5, 'MENUOTHERS : ' . $users_management->getField('MENUOTHERS'), 1);


    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $users_management->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $users_management->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($users_management->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($users_management->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($users_management->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $users_management->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>