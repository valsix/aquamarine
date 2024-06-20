<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$reqCariCompanyName = $this->input->post("reqCariCompanyName");
$reqCariContactPerson = $this->input->post("reqCariContactPerson");
$reqCariVasselName = $this->input->post("reqCariVasselName");
$reqCariEmailPerson = $this->input->post("reqCariEmailPerson");

if (!empty($reqCariCompanyName)) {
    $statement_privacy = " AND A.NAME = '" . $reqCariCompanyName . "' ";
}
if (!empty($reqCariContactPerson)) {
    $statement_privacy .= " AND A.CP1_NAME = '" . $reqCariContactPerson . "' ";
}
if (!empty($reqCariVasselName)) {
    $statement_privacy .= " AND EXISTS(SELECT 1 FROM vessel v WHERE v.COMPANY_ID = c.COMPANY_ID AND v.NAME LIKE '%" . $reqCariVasselName . "%') ";
}
if ($reqCariEmailPerson == 'not') {
    $statement_privacy .= " AND A.EMAIL IS NOT NULL  AND A.EMAIL <> '' AND A.EMAIL <> '-' ";
}


$this->load->model("Customer");
$customer_list = new Customer();

$aColumns = array(
    "NO",
    "NAME", "ADDRESS", "PHONE", "EMAIL", "CP1_NAME",
    "CP1_TELP", "CP2_NAME", "CP2_TELP"
);
$aColumnsAlias = array(
    "NO",
     "NAME", "ADDRESS", "PHONE",  "EMAIL", "CONTACT_NAME",
    "PHONE_NO", "CP2_NAME", "CP2_TELP"
);
$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'CUSTOMER LIST REPORT', 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < 7; $i++) {
    if ($i != 0) {
        $panjang_tabel = 43;
    }
    $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumnsAlias[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
// print_r($arrPanjang);exit;
$customer_list->selectByParamsCetakPdf(array(), -1, -1, $statement . $statement_privacy);
// echo $customer_list->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 43, 43, 43, 43, 43, 43, 43));
$no = 1;
while ($customer_list->nextRow()) {
    // $date1 =  $customer_list->getField('DATE1');
    // $date2 =  $customer_list->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $customer_list->getField($aColumns[1]),
    //         '' . $customer_list->getField($aColumns[2]),
    //         '' . $customer_list->getField($aColumns[3]),
    //         '' . $customer_list->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $customer_list->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $customer_list->getField($aColumns[1]),
        '' . $customer_list->getField($aColumns[2]),
        '' . $customer_list->getField($aColumns[3]),
        '' . $customer_list->getField($aColumns[4]),
        '' . $customer_list->getField($aColumns[5]),
        '' . $customer_list->getField($aColumns[6])

    ));

   // $pdf->MultiCell($panjang - 2.2, 5, 'CONTACT NAME : ' . $customer_list->getField('CP1_NAME'), 1);
 //   $pdf->MultiCell($panjang - 2.2, 5, 'PHONE NO : ' . $customer_list->getField('CP1_TELP'), 1);
   // $pdf->MultiCell($panjang - 2.2, 5, 'CP2 NAME : ' . $customer_list->getField('CP2_NAME'), 1);
    //$pdf->MultiCell($panjang - 2.2, 5, 'CP2 TELP : ' . $customer_list->getField('CP2_TELP'), 1);


    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $customer_list->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $customer_list->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($customer_list->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($customer_list->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($customer_list->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $customer_list->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>