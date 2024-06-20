<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf2.php');

$reqCariFind = $this->input->post('reqCariFind');
$reqCariFindMenthod = $this->input->post('reqCariFindMenthod');

if (!empty($reqCariFind)) {
    $statement = " AND A.COMPANY_NAME LIKE '%" . $reqCariCompanyName . "%' ";
}
if (!empty($reqCariFindMenthod)) {
    $statement .= " AND A.VESSEL_NAME LIKE '%" . $reqCariVasselName . "%' ";
}
// if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
//     $statement .= " AND INVOICE_DATE BETWEEN  TO_CHAR(" . $reqCariPeriodeYearFrom . ", 'yyyy-MM-dd')  AND TO_CHAR(" . $reqCariPeriodeYearTo . ", 'yyyy-MM-dd') ";
// }

// if (!empty($reqCariNoOrder)) {
//     $statement .= " AND A.INVOICE_NUMBER LIKE '%" . $reqCariNoOrder . "%' ";
// }

$this->load->model("CashReport");
$this->load->model("CashReportDetil");
$this->load->model("KategoriCash");
$kategori_cash = new KategoriCash();
$cash_report_detil = new CashReportDetil();
$reqId = $this->input->get("reqId");





$cash_report = new CashReport();
$cash_report->selectByParamsMonitoring(array("A.CASH_REPORT_ID"=>$reqId));
$cash_report->firstRow();
$tanggal = $cash_report->getField("TANGGAL");
$tanggal = explode('-', $tanggal);

$statement = ' AND EXISTS( SELECT 1 FROM KATEGORI_CASH WHERE KATEGORI_CASH_ID = A.KATEGORI_ID)';

$kategori_cash->selectByParamsMonitoring(array());

$arrData = array();
$no_body=0;
$no_footer=0;
$NOMER=0;
while ($kategori_cash->nextRow()) {
    $cash_report_detil = new CashReportDetil();
    $cash_report_detil->selectByParamsMonitoring(array("A.CASH_REPORT_ID"=>$reqId,"A.KATEGORI_ID"=>$kategori_cash->getField("KATEGORI_CASH_ID")));
    $no=0;
    $index_saldo=0;
    $arData = array();
    while ( $cash_report_detil->nextRow()) {

        if($kategori_cash->getField("NAMA") == "SALDO")
        {

            $cash_report_detil_count_saldo = new CashReportDetil();
            $jumlah_row_saldo = $cash_report_detil_count_saldo->getCountByParamsMonitoring(array("A.CASH_REPORT_ID"=>$reqId,"A.KATEGORI_ID"=>$kategori_cash->getField("KATEGORI_CASH_ID")));

            $tanggal_detil = $cash_report_detil->getField("TANGGAL");
            $keterangan = $cash_report_detil->getField("KETERANGAN");
            $kredit = $cash_report_detil->getField("KREDIT");
            $debet = $cash_report_detil->getField("DEBET");
            $kredit_usd = $cash_report_detil->getField("KREDIT_USD");
            $debet_usd = $cash_report_detil->getField("DEBET_USD");
            $arData[$no]["TANGGAL"]=$tanggal_detil;
            $arData[$no]["KETERANGAN"]=$keterangan;
            if($kredit != "0"){
                $arData[$no]["KREDIT"]=$kredit;    
            }
            if($debet != "0"){
                $arData[$no]["DEBET"]=$debet;    
            }
            if($kredit_usd != "0"){
                $arData[$no]["KREDIT_USD"]=$kredit_usd;    
            }
            if($debet_usd != "0"){
                $arData[$no]["DEBET_USD"]=$debet_usd;    
            }

            $index_saldo++;
            if($jumlah_row_saldo == $index_saldo)
            {
                if(empty($arData[$no]["KREDIT"])){
                    $arData[$no]["KREDIT"] = "0";
                }
                if(empty($arData[$no]["DEBET"])){
                    $arData[$no]["DEBET"] = "0";
                }
                if(empty($arData[$no]["KREDIT_USD"])){
                    $arData[$no]["KREDIT_USD"] = "0";
                }
                if(empty($arData[$no]["DEBET_USD"])){
                    $arData[$no]["DEBET_USD"] = "0";
                }
                $no++;     
            }
        }
        else
        {
            $arData[$no]["TANGGAL"]=$cash_report_detil->getField("TANGGAL");
            $arData[$no]["KETERANGAN"]=$cash_report_detil->getField("KETERANGAN");
            $arData[$no]["KREDIT"]=$cash_report_detil->getField("KREDIT");
            $arData[$no]["DEBET"]=$cash_report_detil->getField("DEBET");
            $arData[$no]["KREDIT_USD"]=$cash_report_detil->getField("KREDIT_USD");
            $arData[$no]["DEBET_USD"]=$cash_report_detil->getField("DEBET_USD");
            $no++; 
        }
    }

    if($no!=0){
        $arrData[$NOMER]['ID']=$kategori_cash->getField("KATEGORI_CASH_ID");
        $arrData[$NOMER]['NAMA']=$kategori_cash->getField("NAMA");
        $arrData[$NOMER]['FLAG']=$kategori_cash->getField("FLAG");
        $arrData[$NOMER]['RESULTS']=$arData;
        $NOMER++;
    }
  
}

// var_dump($arrData[0]); exit();


$pdf = new PDF2();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
$pdf->AddFont('Calibri','','Calibri.php');
$pdf->AddFont('Calibri Bold','B','Calibri Bold.php');


// ECHO $pdf->w;exit;
$pdf->SetFont('Calibri Bold', 'B', 13);
$pdf->Cell($panjang, 10, 'CASH FLOW PERUSAHAAN', 0, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Calibri Bold', 'B', 13);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < 7; $i++) {
    if ($i != 0) {
        $panjang_tabel = 30;
    }else{
        $panjang_tabel=10;
    }
    // $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
$pdf->Cell((($pdf->w * 70) / 100)  , 5, '', '', 0, 'C');
$pdf->Cell(22, 5, 'Perbulan :', '', 0, 'C');
$pdf->Cell(22, 5, getNameMonth((int)$tanggal[1]), '', 0, 'C');
$pdf->Cell(22, 5, 'Th :'.$tanggal[2], '', 0, 'C');

$pdf->Ln();
$pdf->Cell((($pdf->w * 3) / 100), 14, 'No', 1, 0, 'C');
$pdf->Cell((($pdf->w * 9) / 100), 14, 'Tanggal', 1, 0, 'C');
$pdf->Cell((($pdf->w * 28) / 100), 14, 'Keterangan', 1, 0, 'C');
$pdf->Cell((($pdf->w * 26) / 100), 7, 'Kredit', 1, 0, 'C');
$pdf->Cell((($pdf->w * 26) / 100), 7, 'Debet', 1, 0, 'C');
$pdf->ln();

$pdf->Cell((($pdf->w * 40) / 100), 7, '', '', 0, 'C');
$pdf->Cell((($pdf->w * 13) / 100), 7, 'Rupiah', 1, 0, 'C');
$pdf->Cell((($pdf->w * 13) / 100), 7, 'USD', 1, 0, 'C');
$pdf->Cell((($pdf->w * 13) / 100), 7, 'Rupiah', 1, 0, 'C');
$pdf->Cell((($pdf->w * 13) / 100), 7, 'USD', 1, 0, 'C');

$pdf->ln();

$pdf->SetFont('Calibri', '', 11);

$TOTAL_IDR_KREDIT=0;
$TOTAL_USD_KREDIT=0;
$TOTAL_IDR_DEBET=0;
$TOTAL_USD_DEBET=0;
$nomer_footer =1;

for($i=0;$i<count($arrData);$i++){

    if($arrData[$i]['NAMA'] == "SALDO"){
        $nomer=1+$i;
        $pdf->SetWidths(array(($pdf->w * 3) / 100, ($pdf->w * 9) / 100, ($pdf->w * 28) / 100, ($pdf->w * 13) / 100, ($pdf->w * 13) / 100, ($pdf->w * 13) / 100, ($pdf->w * 13) / 100));
     
        $results =$arrData[$i]['RESULTS'];
        
        for($j=0;$j<count($results);$j++){
            $nomers = 1+$j;
            $TOTAL_IDR_KREDIT +=$results[$j]['KREDIT'];
            $TOTAL_IDR_DEBET +=$results[$j]['DEBET'];
            $TOTAL_USD_KREDIT +=$results[$j]['KREDIT_USD'];
            $TOTAL_USD_DEBET +=$results[$j]['DEBET_USD'];

            $pdf->RowLeft(array(
             // kolom tabel
                $nomer,
                $results[$j]['TANGGAL']  ,
                $arrData[$i]['NAMA'] ,
                currencyToPage($results[$j]['DEBET']).'&&R' ,
                currencyToPage($results[$j]['DEBET_USD']).'&&R' ,
                currencyToPage($results[$j]['KREDIT']).'&&R' ,
                currencyToPage($results[$j]['KREDIT_USD']).'&&R' 

            ), 7);

        }
    } else if($arrData[$i]['FLAG']=='BODY' && $arrData[$i]['NAMA'] != "SALDO"){
        $nomer=1+$i;
        $pdf->SetWidths(array(($pdf->w * 3) / 100, ($pdf->w * 9) / 100, ($pdf->w * 28) / 100, ($pdf->w * 13) / 100, ($pdf->w * 13) / 100, ($pdf->w * 13) / 100, ($pdf->w * 13) / 100));
        $pdf->Row(array(
             // kolom tabel
             $nomer,
             ''  ,
             '' . $arrData[$i]['NAMA'],
             '' ,
             '' ,
             '' ,
             '' ,
              '' 

        ), 7);
    
        $results =$arrData[$i]['RESULTS'];
      
        for($j=0;$j<count($results);$j++){
            $nomers = 1+$j;
            $TOTAL_IDR_KREDIT +=$results[$j]['KREDIT'];
            $TOTAL_IDR_DEBET +=$results[$j]['DEBET'];
            $TOTAL_USD_KREDIT +=$results[$j]['KREDIT_USD'];
            $TOTAL_USD_DEBET +=$results[$j]['DEBET_USD'];

            $pdf->RowLeft(array(
             // kolom tabel
            '',
             $results[$j]['TANGGAL']  ,
             
             $nomers.'. '.$results[$j]['KETERANGAN'] ,
             currencyToPage($results[$j]['DEBET']).'&&R' ,
             currencyToPage($results[$j]['DEBET_USD']).'&&R' ,
              currencyToPage($results[$j]['KREDIT']).'&&R' ,
             currencyToPage($results[$j]['KREDIT_USD']).'&&R' 

            ), 7);

        }

    }
} 
$pdf->Row(array('',''  ,'' , '' ,'' ,'' , '' , '' ));
$pdf->SetFont('Calibri Bold', 'B', 13);
$pdf->RowRight(array('',''  ,'TOTAL &&L' , currencyToPage($TOTAL_IDR_DEBET) ,currencyToPage($TOTAL_USD_DEBET) ,currencyToPage($TOTAL_IDR_KREDIT) , currencyToPage($TOTAL_USD_KREDIT)  ));
$pdf->Row(array('',''  ,'' , '' ,'' ,'' , '' , '' ));


$pdf->SetFont('Calibri', '', 13);
$TOTAL_IDR_KREDIT2 = 0;
$TOTAL_USD_KREDIT2 = 0;
 for($i=0;$i<count($arrData);$i++){
    if($arrData[$i]['FLAG']=='FOOTER'){
    $nomer=1+$i;
    $pdf->SetWidths(array(($pdf->w * 3) / 100, ($pdf->w * 9) / 100, ($pdf->w * 28) / 100, ($pdf->w * 13) / 100, ($pdf->w * 13) / 100, ($pdf->w * 13) / 100, ($pdf->w * 13) / 100));
    $pdf->Row(array(
         // kolom tabel
         $nomer,
         ''  ,
         '' . $arrData[$i]['NAMA'],
         '' ,
         '' ,
         '' ,
         '' ,
          '' 

     ), 7);
    
      $results =$arrData[$i]['RESULTS'];
      for($j=0;$j<count($results);$j++){
        $nomers = 1+$j;
        $TOTAL_IDR_KREDIT2 +=$results[$j]['KREDIT'];
        // $TOTAL_IDR_DEBET +=$results[$j]['DEBET'];
        $TOTAL_USD_KREDIT2 +=$results[$j]['KREDIT_USD'];
        // $TOTAL_USD_DEBET2 +=$results[$j]['DEBET_USD'];

        $pdf->RowLeft(array(
         // kolom tabel
       '',
         $results[$j]['TANGGAL']  ,
         
         $nomers.'. '.$results[$j]['KETERANGAN'] ,
         // currencyToPage($results[$j]['DEBET']) ,
         // currencyToPage($results[$j]['DEBET_USD']) ,
         $results[$j][''] ,
         $results[$j][''] ,

          currencyToPage($results[$j]['KREDIT']).'&&R' ,
         currencyToPage($results[$j]['KREDIT_USD']).'&&R' 

     ));

      }

    }
} 
$pdf->SetFont('Calibri Bold', 'B', 13);
 // $pdf->Row(array('',''  ,'TOTAL' , currencyToPage($TOTAL_IDR_DEBET) ,currencyToPage($TOTAL_USD_DEBET2) ,currencyToPage($TOTAL_IDR_KREDIT2) , currencyToPage($TOTAL_USD_KREDIT)  ));
 $pdf->RowRight(array('',''  ,'TOTAL &&L' , '','' ,currencyToPage($TOTAL_IDR_KREDIT2) , currencyToPage($TOTAL_USD_KREDIT2)  ));
 $pdf->RowRight(array('',''  ,'' , '','' ,'' , ''));
  $pdf->RowRight(array('',''  ,'LABA RUGI &&L' , '' ,'' ,currencyToPage($TOTAL_IDR_DEBET-$TOTAL_IDR_KREDIT2-$TOTAL_IDR_KREDIT) , currencyToPage($TOTAL_USD_DEBET-$TOTAL_USD_KREDIT2-$TOTAL_USD_KREDIT)  ));


ob_end_clean();
$pdf->Output();

?>