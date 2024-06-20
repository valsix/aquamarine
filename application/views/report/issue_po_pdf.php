<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$this->load->model("IssuePo");
$offer = new IssuePo();

$aColumns = array("NO","NOMER_PO","PO_DATE","DOC_LAMPIRAN","FINANCE","COMPANY_NAME","CONTACT","ADDRESS","EMAIL","TELP","FAX","HP","BUYER_ID","OTHER","PPN","PPN_PERCENT");

$reqPoNumber            = $this->input->get("reqPoNumber");
        $reqCariDateofPoFrom    = $this->input->get("reqCariDateofPoFrom");
        $reqCariDateofPoTo      = $this->input->get("reqCariDateofPoTo");
        $reqCariCompanyName     = $this->input->get("reqCariCompanyName");
        $reqAddress             = $this->input->get("reqAddress");
        $reqEmail               = $this->input->get("reqEmail");
        $reqTelp                = $this->input->get("reqTelp");
        $reqFinance             = $this->input->get("reqFinance");
       


        if (!empty($reqPoNumber)) {
            $statement .= " AND UPPER(A.NOMER_PO) LIKE '%" . strtoupper($reqPoNumber) . "' ";
        }
        // echo $reqCariDateofServiceFrom;exit;
        if (!empty($reqCariDateofPoFrom) && !empty($reqCariDateofPoTo)) {

            $statement .= " AND A.PO_DATE BETWEEN TO_DATE('" . $reqCariDateofPoFrom . "', 'yyyy-MM-dd')  AND  TO_DATE('" . $reqCariDateofPoTo . "', 'yyyy-MM-dd') ";
        }

        if (!empty($reqCariCompanyName)) {
            $statement .= " AND UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%' ";
        }

        if (!empty($reqAddress)) {
            $statement .= " AND UPPER(A.ADDRESS) LIKE '%" . strtoupper($reqAddress) . "%' ";
        }
        if (!empty($reqEmail)) {
            $statement .= " AND UPPER(A.EMAIL) LIKE '%" . strtoupper($reqEmail) . "%' ";
        }
        if (!empty($reqTelp)) {
            $statement .= " AND UPPER(A.TELP) LIKE '%" . strtoupper($reqTelp) . "%' ";
        }
        if (!empty($reqFinance)) {
            $statement .= " AND UPPER(A.CONTACT) LIKE '%" . strtoupper($reqFinance) . "%' ";
        }



$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'ISSUE PO LIST REPORT', 0, 0, 'C');
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
$offer->selectByParamsMonitoring(array(), -1, -1, $statement);
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths($arrPanjang);
$no = 1;
while ($offer->nextRow()) {


    $pdf->Row(array(
        $no,
        '' . $offer->getField($aColumns[1]),
        '' . $offer->getField($aColumns[2]),
        '' . $offer->getField($aColumns[3]),
        '' . $offer->getField($aColumns[4]),
        '' . $offer->getField($aColumns[5]),
        '' . $offer->getField($aColumns[6]),

    ));



    // $text = explode(' ', $offer->getField($aColumns[9]));
    //  $text =  $text[0].' '.currencyToPage2($text[1]);


    $pdf->MultiCell($panjang - 2.2, 5, $aColumns[7] . ' : ' . "\t" . $offer->getField($aColumns[7]), 1, 'J', 0, 10);
    $pdf->MultiCell($panjang - 2.2, 5, $aColumns[8] . ' : ' . $offer->getField($aColumns[8]), 1);
    $pdf->MultiCell($panjang - 2.2, 5, $aColumns[9] . ' : ' . $offer->getField($aColumns[9]), 1);
    $pdf->MultiCell($panjang - 2.2, 5, $aColumns[10] . ' : ' . $offer->getField($aColumns[10]), 1);
    $pdf->MultiCell($panjang - 2.2, 5, $aColumns[11] . ' : ' . $offer->getField($aColumns[11]), 1);
    $pdf->MultiCell($panjang - 2.2, 5, $aColumns[12] . ' : ' . $offer->getField($aColumns[12]), 1);
    $pdf->MultiCell($panjang - 2.2, 5, $aColumns[13] . ' : ' . $offer->getField($aColumns[13]), 1);
    $pdf->MultiCell($panjang - 2.2, 5, $aColumns[14] . ' : ' . $offer->getField($aColumns[14]), 1);
    $pdf->MultiCell($panjang - 2.2, 5, $aColumns[15] . ' : ' . $offer->getField($aColumns[15]), 1);

    $no++;
}


ob_end_clean();
$pdf->Output();

?>