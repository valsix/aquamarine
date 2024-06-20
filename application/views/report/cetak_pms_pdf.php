<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$reqCariCompetentPerson   = $this->input->get('reqCariCompetentPerson');
        $reqCariTimeofTest        = $this->input->get('reqCariTimeofTest');
        $reqCariDateofArrivedFrom = $this->input->get('reqCariDateofArrivedFrom');
        $reqCariDateofArrivedTo   = $this->input->get('reqCariDateofArrivedTo');
        $reqCariName              = $this->input->get('reqCariName');


        if (!empty($reqCariName)) {
            $statement_privacy = " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqCariName) . "%'";
        }
        if (!empty($reqCariDateofArrivedTo) && !empty($reqCariDateofArrivedFrom)) {
            $statement_privacy .= " AND A.DATE_ARRIVE BETWEEN TO_DATE('" . $reqCariDateofArrivedFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariDateofArrivedTo . "','dd-mm-yyyy')";
        }

        if (!empty($reqCariTimeofTest)) {
            $statement_privacy .= " AND A.TIME_TEST ='" . $reqCariTimeofTest     . "'";
        }
        if (!empty($reqCariCompetentPerson)) {
            $statement_privacy .= " AND UPPER(A.COMPETENT) LIKE '%" . strtoupper($reqCariCompetentPerson) . "%'";
        }

$this->load->model("PmsEquipment");
$pms = new PmsEquipment();

$aColumns = array(
    "NO", "EQUIPMENT", "ITEM NAME", "COMPENENT", "D", "W", "M", "6 M", "Y", "2.5 Y", "5 Y", "DATE_TEST", "NEXT_TEST"

);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'PMS EQUIPMENT REPORT', 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
$arrWidth = array(10, 30, 30, 30, 15, 15, 15, 15, 15, 15, 15, 30, 30);
for ($i = 0; $i < count($aColumns); $i++) {
    $pdf->Cell($arrWidth[$i], 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
// print_r($arrPanjang);exit;
$pms->selectByParamsMonitoringCetakPdf(array(), -1, -1, $statement . $statement_privacy);
// echo $pms->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths($arrWidth);
$no = 1;
while ($pms->nextRow()) {
    // $date1 =  $pms->getField('DATE1');
    // $date2 =  $pms->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $pms->getField($aColumns[1]),
    //         '' . $pms->getField($aColumns[2]),
    //         '' . $pms->getField($aColumns[3]),
    //         '' . $pms->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $pms->getField($aColumns[6]),

    //     ));
    $check_val = $pms->getField("TIME_TEST") ;
   
    $check_daily ='';
    if(1==$check_val){ $check_daily ='checked'; }
    $check_WEEKLY ='';
    if(2==$check_val){ $check_WEEKLY ='checked'; }
     $check_MOTHLY ='';
    if(3==$check_val){ $check_MOTHLY ='checked'; }
    $check_SIX_MOTHLY ='';
    if(4==$check_val){ $check_SIX_MOTHLY ='checked'; }
     $check_SIX_YEARLY ='';
    if(5==$check_val){ $check_YEARLY ='checked'; }
     $check_SIX_2_5_YEARLY ='';
    if(6==$check_val){ $check_SIX_2_5_YEARLY ='checked'; }
     $check_5_YEARLY ='';
    if(7==$check_val){ $check_5_YEARLY ='checked'; }
  
    $image1 = "images/check-mark.png";
    // $pdf->SetFont('ZapfDingbats','', 10);
    $pdf->RowWithCheck(array(
        // kolom tabel
        $no,
        '' . $pms->getField("EQUIP_NAME"),
        '' . $pms->getField("NAME"),
        '' . $pms->getField("COMPENENT_PERSON"),
        $check_daily,
       $check_WEEKLY,
        $check_MOTHLY,
        $check_SIX_MOTHLY,
        $check_YEARLY,
        $check_SIX_2_5_YEARLY,
        $check_5_YEARLY,
        '' . $pms->getField("DATE_TEST"),
        '' . $pms->getField("DATE_NEXT_TEST"),
    ));

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $pms->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, '2,5 YEARLY  : ' . $check_SIX_2_5_YEARLY, 1);
    // $pdf->MultiCell($panjang - 2.2, 5, '5 YEARLY  : ' . $check_5_YEARLY, 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TIME TEST : ' . $pms->getField('TIME_TEST'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPETENT : ' . $pms->getField('COMPETENT'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PIC PATH  : ' . $pms->getField('LINK_FILE'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'DATE ARRIVE : ' . $pms->getField('EQUIP_DATEIN'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>