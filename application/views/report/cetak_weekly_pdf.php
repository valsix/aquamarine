<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');



$this->load->model("WeeklyProses");
$this->load->model("WeeklyProsesDetail");
$this->load->model("WeeklyProgresInline");
$this->load->model("WeeklyProgresRincian");

$aColumns = array(
    "NO",
    "DEPARTEMENT", "MASALAH", "SOLUSI", "PROGRESS", "DUE DATE", "PIC_PERSON", "TOTAL_AMOUNT"
);
$reqId = $this->input->get("reqId");

if(!empty($reqId )){
    $statement = " AND CAST(A.WEEKLY_PROSES_ID AS VARCHAR) ='".$reqId."'";
}else{
 $statement = $_SESSION[$this->input->get("pg")."statement"];
 $order  =$_SESSION[$this->input->get("pg")."order"];   
}


$weekly_progres_inline = new WeeklyProgresInline();

$weekly_proses = new WeeklyProses();
// $total_row_rincian_inline     = $weekly_progres_inline->getCountByParamsMonitoring(array("A.WEEKLY_PROSES_ID"=>$reqId));

// 
$weekly_proses->selectByParamsMonitoring(array(),-1,-1,$statement,$order );
$arrData = array();
$no=0;
while ($weekly_proses->nextRow()) {
    $arrData[$no]["NAMA_DEPARTEMEN"]  =$weekly_proses->getField("NAMA_DEPARTEMEN");
    $arrData[$no]["NUMBER"]           =$weekly_proses->getField("WEEKLY_PROSES_ID");
    $arrData[$no]["MASALAH"]          =$weekly_proses->getField("MASALAH");

    $weekly_proses_detail = new WeeklyProsesDetail();
    $weekly_proses_detail->selectByParamsMonitoring(array("A.WEEKLY_PROSES_ID"=>$weekly_proses->getField("WEEKLY_PROSES_ID")),-1,-1,""," ORDER BY A.URUT ASC");
    while($weekly_proses_detail->nextRow()){
      $arrData[$no]["MASTER_SOLUSI_ID"]  =$weekly_proses_detail->getField("URUT").'. '.$weekly_proses_detail->getField("MASTER_SOLUSI_ID");
     
              $weekly_progres_inline = new WeeklyProgresInline();
              $weekly_progres_inline->selectByParamsMonitoring(array("A.WEEKLY_PROSES_ID"=>$weekly_proses_detail->getField("WEEKLY_PROSES_ID"),"WEEKLY_PROSES_DETAIL_ID"=>$weekly_proses_detail->getField("WEEKLY_PROSES_DETAIL_ID")),-1,-1,""," ORDER BY A.URUT ASC");
              $j=0;
             while ( $weekly_progres_inline->nextRow()) {
                        $STATUS = $weekly_progres_inline->getField("STATUS");
                         $arrData[$no]["DUE_DATE"]=$weekly_progres_inline->getField("DUE_DATE");
                         $arrData[$no]["STATUS"]= $STATUS;
                         $arrData[$no]["PROSES"]=$weekly_progres_inline->getField("PROSES");
                         $arrData[$no]["INLINEID"]=$weekly_progres_inline->getField("WEEKLY_PROGRES_INLINE_ID");
                         $arrData[$no]["DUE_PIC"]=$weekly_progres_inline->getField("DUE_PIC");
                         $arrData[$no]["PIC_PERSON"]=$weekly_progres_inline->getField("PIC_PERSON");

                         if($STATUS=='Complated'){
                           $classs =" class='grayClass'";
                         }else if($STATUS=='Progress'){
                            $classs =" class='yellowClass'";
                         }  
                         else if($STATUS=='Not Respon'){
                           $classs =" class='redClass'";
                         }
                         $arrData[$no]["CLASS"]=$classs;
                         $no++;
                         $j++;
              
             }
             if($j==0){
              $no++;
             }
     
    }
}

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'PROGRESS WEEKLY MEETING ', 0, 0, 'C');
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

// echo $invoice->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 43, 43, 43, 43, 43, 43, 43));
$no = 1;
for($i=0;$i<count($arrData);$i++){
    $nomer ='';
     if(!empty($arrData[$i]['NUMBER'])){
        $nomer =$no;
        $no++;

    }
    $pdf->Row(array(
        // kolom tabel
        $nomer,
        '' . $arrData[$i]['NAMA_DEPARTEMEN'],
        '' . $arrData[$i]['MASALAH'],
        '' . $arrData[$i]['MASTER_SOLUSI_ID'],
        '' . $arrData[$i]['PROSES'],
        '' . $arrData[$i]['DUE_DATE'],
        '' . $arrData[$i]['PIC_PERSON']
    ));

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $invoice->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $invoice->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($invoice->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($invoice->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($invoice->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $invoice->getField('INVOICE_DATE'), 1);

    
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>