    <base href="<?= base_url(); ?>" />

    <?
$fileName = 'print_cash_report.xls';
$aColumns = array( "TANGGAL", "KETERANGAN",  "DEBET ( Rp. )", "KREDIT ( Rp. )", "BALENCE ( Rp. )", "STATUS", "ID_DETAIL");
$aColumns_alias = array("TANGGAL","KETERANGAN","DEBET","KREDIT","SALDO","CASH_REPORT_ID","CASH_REPORT_DETIL_ID");



    

//  $company->selectByParamsMonitoring(array(), -1, -1, $statement);
$this->load->model("CashReportDetil");
$this->load->model("KategoriCash");
$kategori_cash = new KategoriCash();
$cash_report_detil = new CashReportDetil();
$reqId = $this->input->get("reqId");
$statement = ' AND EXISTS( SELECT 1 FROM KATEGORI_CASH WHERE KATEGORI_CASH_ID = A.KATEGORI_ID)';
// $cash_report_detil->selectByParamsMonitoring(array("A.CASH_REPORT_ID"=>$reqId),-1,-1,$statement);
// $arrData = array();
// while ($cash_report_detil->nextRow()) {
//     $arData = array();
//    $arrData[$cash_report_detil->getField('KATEGORI_CASH_ID')][]
// }


// ECHO $cash_report_detil->query;exit;
$kategori_cash->selectByParamsMonitoring(array());
$arrData = array();
$no_body=0;
$no_footer=0;
$NOMER=0;
while ($kategori_cash->nextRow()) {

  
   $cash_report_detil = new CashReportDetil();
   $cash_report_detil->selectByParamsMonitoring(array("A.CASH_REPORT_ID"=>$reqId,"A.KATEGORI_ID"=>$kategori_cash->getField("KATEGORI_CASH_ID")));
   $no=0;
    $arData = array();
   while ( $cash_report_detil->nextRow()) {
       
        $arData[$no]["TANGGAL"]=$cash_report_detil->getField("TANGGAL");
        $arData[$no]["KETERANGAN"]=$cash_report_detil->getField("KETERANGAN");
         $arData[$no]["KREDIT"]=$cash_report_detil->getField("KREDIT");
         $arData[$no]["DEBET"]=$cash_report_detil->getField("DEBET");
         $arData[$no]["KREDIT_USD"]=$cash_report_detil->getField("KREDIT_USD");
         $arData[$no]["DEBET_USD"]=$cash_report_detil->getField("DEBET_USD");
        $no++;
   }

  if($no!=0){
        $arrData[$NOMER]['ID']=$kategori_cash->getField("KATEGORI_CASH_ID");
        $arrData[$NOMER]['NAMA']=$kategori_cash->getField("NAMA");
         $arrData[$NOMER]['FLAG']=$kategori_cash->getField("FLAG");
        $arrData[$NOMER]['RESULTS']=$arData;
    $NOMER++;
  }
  
}
// print_r($arrData);



?>


<body>
<h1 style="font-family: Arial;font-size: 18px;text-align: center;"> TECHNICAL AND COMMERCIAL PROPOSAL </h1> 
<br>

<div style="padding-left:75.6px;padding-right:  75.6px">



<table  style="width: 100%;font-size: 12px;font-family: Arial;
    border-collapse: 1px solid black" border="1" ;
       > 
    <tr>
        <th rowspan="2" width="20"><b> No </b> </th>
        <th rowspan="2" width="120"> <b>Tanggal </b>  </th>
        <th rowspan="2" width="350"> <b>Keterangan </b>  </th>
        <th  colspan="2" width="400"> <b>Debet </b> </th>
        <th  colspan="2" width="400"> <b>Kredit </b> </th>
    </tr>
    <tr>
        <th width="200"> <b>Rupiah </b></th>
        <th width="200"> <b>USD </b></th>
         <th width="200"> <b>Rupiah </b></th>
        <th width="200"> <b>USD</b> </th>
    </tr>
    <?
    $TOTAL_IDR_KREDIT=0;
    $TOTAL_USD_KREDIT=0;
    $TOTAL_IDR_DEBET=0;
    $TOTAL_USD_DEBET=0;
    $nomer_footer =1;
    for($i=0;$i<count($arrData);$i++){
    if($arrData[$i]['FLAG']=='BODY'){
    $nomer=1+$i;
    ?>
<tr>

    <td><?=$nomer?></td>
    <td></td>
    <td><?=$arrData[$i]['NAMA']?></td>

    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
<?
    $results =$arrData[$i]['RESULTS'];
    
    for($j=0;$j<count($results);$j++){
        $nomers = 1+$j;
        $TOTAL_IDR_KREDIT +=$results[$j]['KREDIT'];
        $TOTAL_IDR_DEBET +=$results[$j]['DEBET'];
        $TOTAL_USD_KREDIT +=$results[$j]['KREDIT_USD'];
        $TOTAL_USD_DEBET +=$results[$j]['DEBET_USD'];
?>
    <tr>

    <td></td>
    <td><?=$results[$j]['TANGGAL']?></td>
    <td><?=$nomers?>. <?=$results[$j]['KETERANGAN']?></td>

     <td align="center"><?=currencyToPage($results[$j]['DEBET'])?></td>
    <td align="center"><?=currencyToPage($results[$j]['DEBET_USD'])?></td>
    <td align="center"><?=currencyToPage($results[$j]['KREDIT'])?></td>
    <td align="center"><?=currencyToPage($results[$j]['KREDIT_USD'])?></td>
    
    </tr>


<?
    


    }
    }

    ?>
 

    <?
    
    }
?>
<tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
</tr>

<tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
</tr>
<tr>
    <td> </td>
    <td> </td>
    <td><b> TOTAL </b> </td>
    <td align="center"><b><?=currencyToPage($TOTAL_IDR_DEBET)?></b> </td>
    <td align="center"><b><?=currencyToPage($TOTAL_USD_DEBET)?></b> </td>
    <td align="center"><b><?=currencyToPage($TOTAL_IDR_KREDIT)?></b> </td>
     <td align="center"><b><?=currencyToPage($TOTAL_USD_KREDIT)?></b> </td>
</tr>
<?
for($i=0;$i<count($arrData);$i++){
    if($arrData[$i]['FLAG']=='FOOTER'){
       
?>
<tr>

    <td><?=$nomer_footer?></td>
    <td></td>
    <td><?=$arrData[$i]['NAMA']?></td>

    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
<?
 $nomer_footer++;


?>
<?
    $results =$arrData[$i]['RESULTS'];
    
    for($j=0;$j<count($results);$j++){
        $nomers = 1+$j;
         $TOTAL_IDR_KREDIT +=$results[$j]['KREDIT'];
         $TOTAL_IDR_DEBET +=$results[$j]['DEBET'];
          $TOTAL_USD_KREDIT +=$results[$j]['KREDIT_USD'];
            $TOTAL_USD_DEBET +=$results[$j]['DEBET_USD'];
?>
    <tr>

    <td></td>
    <td><?=$results[$j]['TANGGAL']?></td>
    <td><?=$nomers?>. <?=$results[$j]['KETERANGAN']?></td>

    <td align="center"><?=currencyToPage($results[$j]['DEBET'])?></td>
    <td align="center"><?=currencyToPage($results[$j]['DEBET_USD'])?></td>
    <td align="center"><?=currencyToPage($results[$j]['KREDIT'])?></td>
    <td align="center"><?=currencyToPage($results[$j]['KREDIT_USD'])?></td>
    </tr>


<?
    


    }
}
}
    ?>
    <tr>
    <td> </td>
    <td> </td>
    <td><b> TOTAL </b> </td>
    <td align="center"><b><?=currencyToPage($TOTAL_IDR_DEBET)?></b> </td>
    <td align="center"><b><?=currencyToPage($TOTAL_USD_DEBET)?></b> </td>
    <td align="center"><b><?=currencyToPage($TOTAL_IDR_KREDIT)?></b> </td>
    <td align="center"><b><?=currencyToPage($TOTAL_USD_KREDIT)?></b> </td>
</tr>
<tr>
    <td> </td>
    <td> </td>
    <td><b> LABA RUGI </b> </td>
    <td align="center"><b><?=currencyToPage($TOTAL_IDR_DEBET)?></b> </td>
     <td align="center"><b><?=currencyToPage($TOTAL_USD_DEBET)?></b> </td>
    <td align="center"><b><?=currencyToPage(($TOTAL_IDR_DEBET-$TOTAL_IDR_KREDIT))?></b> </td>
     <td align="center"><b><?=currencyToPage(($TOTAL_USD_DEBET-$TOTAL_USD_KREDIT))?></b> </td>
</tr>
</table>
</div>

</body>
</html>
