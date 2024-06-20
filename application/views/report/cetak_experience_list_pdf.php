<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$reqContactNo               = $this->input->get('reqContactNo');
$reqProjectLocation         = $this->input->get('reqProjectLocation');
$reqClientName              = $this->input->get('reqClientName');
$reqProjectName             = $this->input->get('reqProjectName');
$reqYear                    = $this->input->get('reqYear');

if (!empty($reqYear)) {
  $statement .= " AND TO_CHAR(A.FROM_DATE,'YYYY') ='" . strtoupper($reqYear) . "'";
}

if (!empty($reqContactNo)) {
  $statement .= " AND UPPER(A.CONTACT_NO) LIKE '%" . strtoupper($reqCariCompanyName) . "%'";
}

if (!empty($reqProjectLocation)) {
  $statement .= " AND UPPER(A.PROJECT_LOCATION) LIKE '%" . strtoupper($reqCariDescription) . "%'";
}

if (!empty($reqClientName)) {
  $statement .= " AND UPPER(B.NAME) LIKE '%" . strtoupper($reqCariDescription) . "%'";
}
if (!empty($reqProjectName)) {
  $statement .= " AND UPPER(A.PROJECT_NAME) LIKE '%" . strtoupper($reqCariDescription) . "%'";
}



$this->load->model("ExperienceList");
$this->load->model("Company");
$experience_list = new ExperienceList();
$min_tahun = $experience_list->getCountByParamsMinYear(array());
$max_tahun = $experience_list->getCountByParamsMaxYear(array());

if(!empty($reqYear)){
    $judul ='PROJECT EXPERINCE LIST'.$reqYear;
}else{
$judul ='PROJECT EXPERINCE LIST '.$min_tahun.' - '.$max_tahun;
}
$experience_list = new ExperienceList();


$aColumns = array(
    "NO.","PROJECT_NAME","PROJECT_LOCATION","CLIENT / ALAMAT","CONTACT_NO","PROJECT DURATION"
);


$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($panjang, 10, $judul, 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < count($aColumns); $i++) {
    if ($i != 0) {
        $panjang_tabel = 43;
    } if($i==3){
         $panjang_tabel = 86;
    }

    $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
$statement= $_SESSION[$this->input->get("pg")."reqStatement"] ;
$TOTAL = $experience_list->getCountByParamsMonitoring(array(),$statement);
$experience_list->selectByParamsMonitoring(array(), -1, -1, $statement," ORDER BY A.URUT DESC");


$pdf->SetFont('Arial', '', 10);
$pdf->SetWidths(array(10, 43, 43,  86, 43, 43));
$no = 0;

while ($experience_list->nextRow()) {
  $nomer =(int) ($TOTAL-$no);
   $docId = $experience_list->getField("COSTUMER_ID");
    $str_name_project ='';
  
      $company = new Company();
      $company->selectByParamsMonitoring(array("A.COMPANY_ID"=>$docId));
      $company->firstRow();
      $reqName = $company->getField('NAME');
      $reqAddress = $company->getField('ADDRESS');
      $str_name_project=''.$reqName."\n".$reqAddress;
     
      $str_long='';

      $tgl_awal= getFormattedDateView($experience_list->getField('FROM_DATE'));
      $tgl_akhir= getFormattedDateView($experience_list->getField('TO_DATE'));
      $str_long  = $tgl_awal .' - '.$tgl_akhir."\n".' ( '.$experience_list->getField('DURATION').' ) Days';
   

    $pdf->RowBold(array(
        // kolom tabel
        $nomer,
        '' . $experience_list->getField($aColumns[1]),
        '' . $experience_list->getField($aColumns[2]),        
        '' . $str_name_project,
        '' . $experience_list->getField($aColumns[4]),
        '' . $str_long,

    ),5,[1,3]);

    
    $no++;
}



ob_end_clean();
$pdf->Output();

?>