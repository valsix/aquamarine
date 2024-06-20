<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$this->load->model("Offer");
$offer = new Offer();

$aColumns = array(
    "NO", "NO_ORDER", "EMAIL", "DESTINATION", "COMPANY_NAME", "VESSEL_NAME", "TYPE_OF_VESSEL", "FAXIMILE", "TYPE_OF_SERVICE", "TOTAL_PRICE", "SCOPE_OF_WORK"
);

$reqCariNoOrder = $this->input->get("reqCariNoOrder");
$reqCariDateofServiceFrom = $this->input->get("reqCariDateofServiceFrom");
$reqCariDateofServiceTo = $this->input->get("reqCariDateofServiceTo");
$reqCariCompanyName = $this->input->get("reqCariCompanyName");
$reqCariPeriodeYear = $this->input->get("reqCariPeriodeYear");
$reqCariVasselName = $this->input->get("reqCariVasselName");
$reqCariProject = $this->input->get("reqCariProject");
$reqCariGlobalSearch = $this->input->get("reqCariGlobalSearch");
$reqCariStatus = $this->input->get("reqCariStatus");

 $reqCariNoOrder = $this->input->get("reqCariNoOrder");
        $reqCariDateofServiceFrom = $this->input->get("reqCariDateofServiceFrom");
        $reqCariDateofServiceTo = $this->input->get("reqCariDateofServiceTo");
        $reqCariCompanyName = $this->input->get("reqCariCompanyName");
        $reqCariPeriodeYear = $this->input->get("reqCariPeriodeYear");
        $reqCariVasselName = $this->input->get("reqCariVasselName");
        $reqCariProject = $this->input->get("reqCariProject");
        $reqCariGlobalSearch = $this->input->get("reqCariGlobalSearch");
        $reqCariStatus = $this->input->get("reqCariStatus");

        if (!empty($reqCariNoOrder)) {
            $statement .= " AND UPPER(A.NO_ORDER) LIKE '%" . strtoupper($reqCariNoOrder) . "' ";
        }
        // echo $reqCariDateofServiceFrom;exit;
        if (!empty($reqCariDateofServiceFrom) && !empty($reqCariDateofServiceTo)) {

            $statement .= " AND A.DATE_OF_SERVICE BETWEEN to_date('" . $reqCariDateofServiceTo . "', 'yyyy-MM-dd')  AND  to_date('" . $reqCariDateofServiceFrom . "', 'yyyy-MM-dd') ";
        }

        if (!empty($reqCariCompanyName)) {
            $statement .= " AND UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%' ";
        }

        if (!empty($reqCariPeriodeYear) && $reqCariPeriodeYear !='' && $reqCariPeriodeYear !='ALL') {
            $statement .= " AND   TO_CHAR(A.DATE_OF_ORDER, 'yyyy') ='".$reqCariPeriodeYear."'";
        }

        if (!empty($reqCariVasselName)) {
            $statement .= " AND UPPER(A.VESSEL_NAME) LIKE '%" . strtoupper($reqCariVasselName) . "%' ";
        }
        if (!empty($reqDestination)) {
            $statement_privacy .= "AND UPPER(A.DESTINATION) LIKE '%" . strtoupper($reqDestination) . "%'  ";
        }

       if (!empty($reqCariProject)) {
            $statement_privacy .= "AND UPPER(A.GENERAL_SERVICE) = '" . strtoupper($reqCariProject) . "'  ";
        }

        if (!empty($reqCariGlobalSearch)) {
            $statement .= " AND (  UPPER(A.VESSEL_NAME) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' 
                                    OR UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' 
                                    OR UPPER(A.SCOPE_OF_WORK) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' 
                                    
                                    OR UPPER(A.NO_ORDER) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' ";
            $statement .= " OR A.TOTAL_PRICE LIKE '%" . $reqCariGlobalSearch . "%' OR UPPER(A.CONTACT_PERSON) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' OR UPPER(A.DESTINATION) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%'  ";
        }

        if (!empty($reqCariStatus) && $reqCariStatus !='ALL') {
            if($reqCariStatus=='3'){
                    $statement_privacy .= "  AND A.STATUS  IS NULL";
                }else{
                    $statement_privacy .= "  AND A.STATUS  =".$reqCariStatus ;
                }
            // $statement .= "  AND UPPER(A.STATUS)  LIKE '%" . strtoupper($reqCariStatus) . "%'";
        }

        $statement_privacy .=$_SESSION[$this->input->get("pg")."reqSearch"];

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'OFFER LIST REPORT', 0, 0, 'C');
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
$offer->selectByParams(array(), -1, -1, $statement);
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



    $text = explode(' ', $offer->getField($aColumns[9]));
     $text =  $text[0].' '.currencyToPage2($text[1]);


    $pdf->MultiCell($panjang - 2.2, 5, $aColumns[7] . ' : ' . "\t" . $offer->getField($aColumns[7]), 1, 'J', 0, 10);
    $pdf->MultiCell($panjang - 2.2, 5, $aColumns[8] . ' : ' . $offer->getField($aColumns[8]), 1);
    $pdf->MultiCell($panjang - 2.2, 5, $aColumns[9] . ' : ' . $text, 1);
    $pdf->MultiCell($panjang - 2.2, 5, $aColumns[10] . ' : ' . $offer->getField($aColumns[10]), 1);

    $no++;
}


ob_end_clean();
$pdf->Output();

?>