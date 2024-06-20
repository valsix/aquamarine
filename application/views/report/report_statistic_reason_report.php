<base href="<?= base_url(); ?>" />
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get('reqId');
$reqMode = $this->input->get('reqMode');

$this->load->model('Offer');
$this->load->model('MasterReason'); 
$this->load->model('StatisticOfferTahun'); 

 $this->load->model("DokumenReport");
        $reqTahun = $this->input->get("reqTahun");
        $reqId =$this->input->get("reqId");
        $arrValue =array("Preparation before working","Conduct of the team during","Knowledge of working","Concerns for safety","Performance of equipement","Performance of personnel","Overall Performance of team","etc");
        $arrLabel = array('SURYEVOR_SATISFACTION_SHEET','CLIENT_SATISFACTION_SHEET');
        $reqIds = explode('-', $reqId);
        $report = new DokumenReport();
        $statement = "  AND TO_CHAR(A.START_DATE,'YYYY')='".$reqId."'";

        $report->selectByParams(array(),-1,-1,$statement);
        // echo $report->query;exit;
        $arrLabel = array('SURYEVOR_SATISFACTION_SHEET','CLIENT_SATISFACTION_SHEET');
        $arrDataValue = array();
          $arrDataValue2 = array();
         $color = array();
        $data[] = array('Task', '');
        while ( $report->nextRow()) {
                $reqSurveyors = $report->getField("SURYEVOR");
                $reqSurveyorsx = json_decode($reqSurveyors,true);

                $reqClients = $report->getField("CLIENT");
                $reqClientsx = json_decode($reqClients,true); 


                for($i=0;$i<count($arrValue);$i++){
                    for($j=0;$j<5;$j++){
                         if(!empty($reqSurveyorsx['reqSurveyor'.$i.$j])){
                            $arrDataValue[$arrValue[$i]] += (5-$j);
                            $arrDataValue[$j][$arrValue[$i]] += 1;
                         }
                         if(!empty($reqClientsx['reqClient'.$i.$j])){
                             $arrDataValue2[$arrValue[$i]] += (5-$j);
                             $arrDataValue2[$j][$arrValue[$i]] += 1;
                         }
                    }
                }
            }

           

            //  echo json_encode(array(
            //     'data' => $data,
            //     'color' => $color,
            // ));

 for($kk=0;$kk<count($arrLabel);$kk++){
?>

<div style="background: #4259c1;border-radius: 2px;padding-bottom: 9px;
margin: 20px 0 20px;">
<h3 style="font-size: 14px;
text-transform: uppercase;
margin-bottom: 0px;
padding-top: 8px;color: #FFFFFF;padding-left: 20px">Statistic Detail <?=str_replace('_', ' ', $arrLabel[$kk])?> </h3>
</div>
<table  style="width: 100%;border-collapse: 1px solid black" border="1">
    <tr>
        <th align="left" style="padding-left: 20px"> Description </th>
        <!-- <th align="center"> Value </th> -->
        <th valign="center" style="text-align: center;">Excellent ( 81-100 ) <br> (Point 5)</th>
                                <th valign="center" style="text-align: center;">Good ( 61- 80 ) <br> (Point 4) </th>
                                <th valign="center" style="text-align: center;">Adequate ( 41- 60 ) <br> (Point 3) </th>
                                <th valign="center" style="text-align: center;">Poor ( 21- 41 ) <br> (Point 2)</th>
                                <th valign="center" style="text-align: center;">Very Poor ( 0- 20 ) <br> (Point 1)</th>
    </tr>
    <?  
    
    $total=0;
      $arrTotal= array();
  for($i=0;$i<count($arrValue);$i++){
     if($kk==0){    
        $stotal = ifZero($arrDataValue[$arrValue[$i]]);
        $total  = $total+$stotal;
    }else{
       $stotal = ifZero($arrDataValue2[$arrValue[$i]]);
       $total  = $total+$stotal;
   }
        ?>
        <tr>
            <td align="left" style="padding-left: 20px"><?=$arrValue[$i]?></td>
            <!-- <td align="center"><?= $stotal ?></td> -->
            <?

            for($mm=0;$mm<5;$mm++){
                $valss =0;
                if($kk==0){    
                 $valss = $arrDataValue[$mm][$arrValue[$i]];
             }else{
                 $valss = $arrDataValue2[$mm][$arrValue[$i]];
             }
              $arrTotal[$mm] +=$valss;
             ?>
             <td valign="center" align="center"><?=$valss?> </td>
             <?
         }
         ?>
        </tr>

        <?
    }
    ?>
    <tr>
            <td align="left" style="padding-left: 20px"> <b>Grand Total</b></td>
            <!-- <td align="center" colspan="6"><?= $total ?></td> -->
             <?
                                    for($mm=0;$mm<5;$mm++){
                                   ?> 
                                  <td valign="center" align="center"><?=$arrTotal[$mm]?> </td>
                                  <?
                                  }
                                  ?>
        </tr>

</table>
  <br>
  <br>
  <table style="width: 100%;border-collapse: 1px solid black" border="1">
    <tr>
      <th>Excellent ( 81-100 ) </th>
        <th>Good ( 61- 80 ) </th>
          <th>Adequate ( 41- 60 ) </th>
            <th>Poor ( 21- 41 ) </th>
              <th>Very Poor ( 0- 20 ) </th>
          </tr>
          <tr>
           <?
           for($mm=0;$mm<5;$mm++){
             ?> 
             <td valign="center" align="center">
                <?
                if(file_exists("uploads/piechar/".$reqId."/report_".$arrLabel[$kk]."_".$mm.".png")){
                ?>
             <img src="uploads/piechar/<?=$reqId?>/<?='report_'.$arrLabel[$kk]?>_<?=$mm?>.png" style="height: 170px;width: 170px"> 
             <?
            }
             ?>
             </td>
             <?
         }
         ?>
          </tr>
  </table>  

<div style="text-align: center;">
    <?
    $lokasi = explode('-', $reqId);
    ?>
 <!-- <img src="uploads/piechar/<?=$reqId?>/<?='report_'.$arrLabel[$kk]?>.png" style="height: 270px;width: 270px">  -->
</div>
<br>
<?
    }
?>