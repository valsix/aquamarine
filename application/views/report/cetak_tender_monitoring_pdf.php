<base href="<?= base_url(); ?>" />

<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$this->load->model("Tender");
$tender = new Tender();
    
$reqJudul = $this->input->get('reqJudul');
if($reqJudul == ""){
    $reqJudul = 'TENDER MONITORING';
}

$aColumns = array("URUT","PROJECT_NO", "COMPANY_NAME", "PROJECT_NAME", 
                "ANNOUNCEMENT", "ISSUED_DATE", "REGISTER_DATE", 
                "PQ_DATE", "PREBID_DATE",  "SUBMISSION_DATE", "OPENING1_DATE", "OPENING2_DATE", "LOA");


$aColumnsAlias =  array('NO', "TENDER_NO","CLIENT_NAME", "TENDER_NAME", "STATUS", "DATE ISSUED ANNOUNCEMENT", "REGISTER DATE & COLLECT DOCUMENT", 
                "PQ -  COMPANY", "PRE BID DATE", "DOK. TEKNIS & KOMERSIAL SUBMIT DATE", "OPENING BID DATE", "OPENING 2ND ENVELOPE", 
                "KONTRAK / LOA");
$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, $reqJudul, 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 8);

// exit;
$panjang_tabel = $pdf->w * (3/100);
$arrPanjang = array();
$arrHeader = array();
for ($i = 0; $i < count($aColumns); $i++) {
    if ($i == 0) {
        $panjang_tabel = $pdf->w * (3/100);
    } else if ($i <= 3) {
        $panjang_tabel = $pdf->w * (12/100);
    } else if ($i > 3) {
        $panjang_tabel = $pdf->w * (6/100);
    }
    array_push($arrPanjang, $panjang_tabel);
    array_push($arrHeader, str_replace('_', ' ', $aColumnsAlias[$i]));
}
$pdf->SetWidths($arrPanjang);

$pdf->RowCenter($arrHeader);
    // $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
// $pdf->Ln();
$reqAnnouncement            =  $this->input->get('reqAnnouncement');
$reqCariProjectNo           =  $this->input->get('reqCariProjectNo');
$reqCariProjectName         =  $this->input->get('reqCariProjectName');
$reqCariIssuedDateFrom      =  $this->input->get('reqCariIssuedDateFrom');
$reqCariIssuedDateTo        =  $this->input->get('reqCariIssuedDateTo');
$reqCariRegisterDateFrom    =  $this->input->get('reqCariRegisterDateFrom');
$reqCariRegisterDateTo      =  $this->input->get('reqCariRegisterDateTo');
$reqCariPQDateFrom          =  $this->input->get('reqCariPQDateFrom');
$reqCariPQDateTo            =  $this->input->get('reqCariPQDateTo');
$reqCariPrebidDateFrom      =  $this->input->get('reqCariPrebidDateFrom');
$reqCariPrebidDateTo        =  $this->input->get('reqCariProjectName');
$reqCariSubmissionDateFrom  =  $this->input->get('reqCariSubmissionDateFrom');
$reqCariSubmissionDateTo    =  $this->input->get('reqCariSubmissionDateTo');
$reqCariOpening1DateFrom    =  $this->input->get('reqCariOpening1DateFrom');
$reqCariOpening1DateTo      =  $this->input->get('reqCariOpening1DateTo');
$reqCariOpening2DateFrom    =  $this->input->get('reqCariOpening2DateFrom');
$reqCariOpening2DateTo      =  $this->input->get('reqCariOpening2DateTo');

$statement_privacy = "";
if (!empty($reqCariProjectNo)) {
    $statement_privacy .= " AND UPPER(PROJECT_NO) LIKE '%" . strtoupper($reqCariProjectNo) . "%' ";
}

if (!empty($reqCariProjectName)) {
    $statement_privacy .= " AND UPPER(PROJECT_NAME) LIKE '%" . strtoupper($reqCariProjectName) . "%' ";
}

if (!empty($reqCariIssuedDateFrom) && !empty($reqCariIssuedDateTo)) {
    $statement_privacy .= " AND A.ISSUED_DATE BETWEEN TO_DATE('" . $reqCariIssuedDateFrom . "', 'DD-MM-YYYY') AND TO_DATE('" . $reqCariIssuedDateTo . "', 'DD-MM-YYYY')";
}

if (!empty($reqCariRegisterDateFrom) && !empty($reqCariRegisterDateTo)) {
    $statement_privacy .= " AND A.REGISTER_DATE BETWEEN TO_DATE('" . $reqCariRegisterDateFrom . "', 'DD-MM-YYYY') AND TO_DATE('" . $reqCariRegisterDateTo . "', 'DD-MM-YYYY')";
}

if (!empty($reqCariPQDateFrom) && !empty($reqCariPQDateTo)) {
    $statement_privacy .= " AND A.PQ_DATE BETWEEN TO_DATE('" . $reqCariPQDateFrom . "', 'DD-MM-YYYY') AND TO_DATE('" . $reqCariPQDateTo . "', 'DD-MM-YYYY')";
}

if (!empty($reqCariPrebidDateFrom) && !empty($reqCariPrebidDateTo)) {
    $statement_privacy .= " AND A.PREBID_DATE BETWEEN TO_DATE('" . $reqCariPrebidDateFrom . "', 'DD-MM-YYYY') AND TO_DATE('" . $reqCariPrebidDateTo . "', 'DD-MM-YYYY')";
}

if (!empty($reqCariSubmissionDateFrom) && !empty($reqCariSubmissionDateTo)) {
    $statement_privacy .= " AND A.SUBMISSION_DATE BETWEEN TO_DATE('" . $reqCariSubmissionDateFrom . "', 'DD-MM-YYYY') AND TO_DATE('" . $reqCariSubmissionDateTo . "', 'DD-MM-YYYY')";
}

if (!empty($reqCariOpening1DateFrom) && !empty($reqCariOpening1DateTo)) {
    $statement_privacy .= " AND A.OPENING1_DATE BETWEEN TO_DATE('" . $reqCariOpening1DateFrom . "', 'DD-MM-YYYY') AND TO_DATE('" . $reqCariOpening1DateTo . "', 'DD-MM-YYYY')";
}

if (!empty($reqCariOpening2DateFrom) && !empty($reqCariOpening2DateTo)) {
    $statement_privacy .= " AND A.OPENING2_DATE BETWEEN TO_DATE('" . $reqCariOpening2DateFrom . "', 'DD-MM-YYYY') AND TO_DATE('" . $reqCariOpening2DateTo . "', 'DD-MM-YYYY')";
}

if (!empty($reqAnnouncement)) {
    $statement_privacy .= " AND UPPER(ANNOUNCEMENT) = '" . strtoupper($reqAnnouncement) . "' ";
}
$reqOrder =$_SESSION[$this->input->get("pg")."reqCariOrder"];

$tender->selectByParams(array(), -1, -1, $statement_privacy,$reqOrder);
// var_dump($tender);

$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths($arrPanjang);
$no = 0;
while ($tender->nextRow()) {
    $row = array();
    // $row[] = $no;
    for ($i=0; $i < count($aColumnsAlias); $i++) { 

                    $path='';
                    $path = $tender->getField('PREBID_PATH');
                    if(!empty($path)){
                    $path = explode(";", $path);
                    $paths='';
                    $id = $tender->getField("TENDER_ID");
                    if($path[0] == ""){

                        $paths = "-";
                    }
                    else{
                        $paths = $path[0];
                    }
                    }
              
                    $row[] = $tender->getField($aColumns[$i]);
                
    }
    $pdf->Row($row);
     $pdf->MultiCell($panjang +6, 5, 'REMARK : ' . "\t" . $tender->getField('REMARK'), 1, 'J', 0, 20);
    // $pdf->MultiCell($panjang +6, 5, 'PRE BID SOW DOCUMENT : ' . "\t" . $paths, 1, 'J', 0, 20);
    $no++;
}
ob_end_clean();
$pdf->Output();

?>