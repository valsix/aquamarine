<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

       
      
        

        $this->load->model("ServiceOrder");
         $service_order = new ServiceOrder();

        $aColumns = array("NO", "NO_ORDER", "COMPANY_NAME", "VESSEL_NAME", "VESSEL_TYPE", "SURVEYOR","SERVICE","DESTINATION");

        $reqCariGlobal                = $this->input->get('reqCariGlobal');
        $reqCariProject               = $this->input->get('reqCariProject');
        $reqCariVasselName            = $this->input->get('reqCariVasselName');
        $reqCariPeriodeYear           = $this->input->get('reqCariPeriodeYear');
        $reqCariCompanyName           = $this->input->get('reqCariCompanyName');
        $reqCariPeriodeYearFrom         = $this->input->get('reqCariPeriodeYearFrom');
        $reqCariPeriodeYearTo           = $this->input->get('reqCariPeriodeYearTo');

        if (!empty($reqCariCompanyName)) {
          $statement .= " AND UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%'";
        }
        if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo) ) {
          $statement .= "  AND A.DATE_OF_SERVICE BETWEEN  TO_DATE(" . $reqCariPeriodeYearFrom . ", 'yyyy-mm-dd')  AND  TO_DATE(" . $reqCariPeriodeYearTo . ", 'yyyy-mm-dd')  ";
          
        }
        if (!empty($reqCariPeriodeYear)&& $reqCariPeriodeYear !='ALL') {
          $statement .= " AND TO_CHAR(A.DATE_OF_SERVICE,'YYYY') = '".$reqCariPeriodeYear . "'";
        }
        if (!empty($reqCariVasselName)) {
          $statement .= " AND UPPER(A.VESSEL_NAME) LIKE '%" . strtoupper($reqCariVasselName) . "%' ";
        }
        if (!empty($reqCariProject)) {
          $statement .= " AND UPPER(A.SERVICE) LIKE '%" . strtoupper($reqCariProject) . "%' ";
        }
        if (!empty($reqCariGlobal)) {
          $statement .= " AND UPPER(A.NO_ORDER) LIKE '%" . strtoupper($reqCariGlobal) . "%' ";
        }
        


$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L','A4');
$panjang = (($pdf->w*91)/100);
 // ECHO $pdf->w;exit;
$pdf->SetFont('Arial','B',16);
$pdf->Cell($panjang ,10,'REPORT EQUIPMENT DELIVERY  LIST',0,0,'C');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);

// exit;
$panjang_tabel =10;
$arrPanjang=array();
for($i=0;$i<7;$i++){
    if($i!=0){
        $panjang_tabel =43;
    }
    $pdf->Cell($panjang_tabel,10,str_replace('_', ' ', $aColumns[$i]),1,0,'C');
    array_push($arrPanjang, $panjang_tabel);
  }
  $pdf->Ln();
  // print_r($arrPanjang);exit;
  $service_order->selectByParamsEquiment(array(),-1,-1,$statement);
  $pdf->SetFont('Arial','',8);
  $pdf->SetWidths($arrPanjang);
  $no=1;
  while ($service_order->nextRow()) {
    
    
   $pdf->Row(array($no,
    ''.$service_order->getField($aColumns[1]),
    ''.$service_order->getField($aColumns[2]),
    ''.$service_order->getField($aColumns[3]),
    ''.$service_order->getField($aColumns[4]),
    ''.$service_order->getField($aColumns[5]),
    ''.$service_order->getField($aColumns[6]),
     
   ));





    $pdf->MultiCell($panjang-2.2 ,5,$aColumns[7].' : '."\t".$service_order->getField($aColumns[7]) ,1,'J',0,10);
    
    
   $no++;
  }


ob_end_clean();
$pdf->Output();

?>