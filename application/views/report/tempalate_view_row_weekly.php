<?
$reqId = $this->input->get('reqId');
$this->load->model("WeeklyProses");
$this->load->model("WeeklyProsesDetail");
$this->load->model("WeeklyProgresInline");
$this->load->model("WeeklyProgresRincian");

$weekly_progres_inline = new WeeklyProgresInline();
$weekly_proses_detail = new WeeklyProsesDetail();
$weekly_proses = new WeeklyProses();
$total_row_rincian_inline     = $weekly_progres_inline->getCountByParamsMonitoring(array("A.WEEKLY_PROSES_ID"=>$reqId));

$weekly_proses_detail->selectByParamsMonitoring(array("A.WEEKLY_PROSES_ID"=>$reqId),-1,-1,""," ORDER BY A.URUT ASC");
$weekly_proses->selectByParamsMonitoring(array("A.WEEKLY_PROSES_ID"=>$reqId));
$arrData = array();
$no=0;
while ($weekly_proses->nextRow()) {
    $arrData[$no]["NAMA_DEPARTEMEN"]  =$weekly_proses->getField("NAMA_DEPARTEMEN");
    $arrData[$no]["MASALAH"]          =$weekly_proses->getField("MASALAH");
    
    while($weekly_proses_detail->nextRow()){
      $arrData[$no]["MASTER_SOLUSI_ID"]  =$weekly_proses_detail->getField("URUT").'. '.$weekly_proses_detail->getField("MASTER_SOLUSI_ID");
     
              $weekly_progres_inline = new WeeklyProgresInline();
              $weekly_progres_inline->selectByParamsMonitoring(array("A.WEEKLY_PROSES_ID"=>$reqId,"WEEKLY_PROSES_DETAIL_ID"=>$weekly_proses_detail->getField("WEEKLY_PROSES_DETAIL_ID")),-1,-1,""," ORDER BY A.URUT ASC");
              $j=0;
             while ( $weekly_progres_inline->nextRow()) {
                        $STATUS = $weekly_progres_inline->getField("STATUS");
                         $arrData[$no]["DUE_DATE"]=$weekly_progres_inline->getField("DUE_DATE");
                         $arrData[$no]["STATUS"]= $STATUS;
                         $arrData[$no]["PROSES"]=$weekly_progres_inline->getField("URUT").'. '.$weekly_progres_inline->getField("PROSES");
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

?>

<table style="table-layout: fixed; width: 100%" class="table table-striped tablei" border="1">
  <thead>
    <tr>
      <th style="width: 10% !important"> DEPARTEMENT </th>
      <th  style="width: 20% !important"> MASALAH </th>
      <th style="width: 23% !important"> SOLUSI </th>
      <th style="width: 20% !important"> PROGRESS </th>
      <th style="width: 7% !important"> DUE DATE </th>
      <th style="width: 10% !important"> PIC PERSON </th>
       <th style="width: 5% !important"> DUE PIC </th>
    </tr>
    <tbody>
      <?
      for($i=0;$i<$no;$i++){
        $reqPicPath  =$arrData[$i]['DUE_PIC'];
        $reqPicPaths ="uploads/weekly_meeting/".$arrData[$i]['INLINEID']."/".$reqPicPath;
                                                                 

      ?>
      <tr>
        <td ><?=$arrData[$i]['NAMA_DEPARTEMEN']?> </td>
        <td ><div class='text-wrap width-200'> <?=$arrData[$i]['MASALAH']?></div></td>
        <td > <div class='text-wrap width-200'><?=$arrData[$i]['MASTER_SOLUSI_ID']?> </div></td>
        <td <?=$arrData[$i]['CLASS']?>><div class='text-wrap width-200'> <?=$arrData[$i]['PROSES']?></div> </td>
        <td <?=$arrData[$i]['CLASS']?>><?=$arrData[$i]['DUE_DATE']?> </td>
          <td <?=$arrData[$i]['CLASS']?>><div class='text-wrap width-200'><?=$arrData[$i]['PIC_PERSON']?> </div></td>
        <td <?=$arrData[$i]['CLASS']?>>
          <?
          if(!empty( $reqPicPath)){
          ?>
          <a onclick="openAdd('<?=$reqPicPaths?>')" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a>
        <?
          }  
        ?>
          </td>
      </tr>
      <?
      }
      ?>
      
    </tbody>
  </thead>
</table>