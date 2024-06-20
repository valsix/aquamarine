<?
include_once("functions/default.func.php");
include_once("functions/string.func.php");
$this->load->model("TenderEvaluation");
$this->load->model("MasterTenerMenus");
$this->load->model("TenderEvaluationDetail");
$this->load->model("MasterTenderPeriode");


$reqId        = $this->input->get('reqId');
$reqIds       = explode(',', $reqId);
$reqCari      = $this->input->get('reqCari');
$reqTahun     = $this->input->get('reqTahun');

$REQIDS2 =array();
for($i=0;$i<count($reqIds);$i++){
  if($reqIds[$i]!='ALL'){
    array_push($REQIDS2,$reqIds[$i] );
  }
}

$master_tender_periode = new MasterTenderPeriode();
$master_tender_periode->selectByParamsMonitoring(array("A.TAHUN"=>$reqTahun));
$master_tender_periode->firstRow();
$reqPeriodeId =$master_tender_periode->getField('MASTER_TENDER_PERIODE_ID');
if(empty($reqPeriodeId)){
$master_tender_periode = new MasterTenderPeriode();
$master_tender_periode->setField("TAHUN",$reqTahun);
$master_tender_periode->insert();
$reqPeriodeId = $master_tender_periode->id;
} 

$statement    = " AND UPPER(A.INDEX) LIKE '%".strtoupper($reqCari)."%'";
$_SESSION['pencarianEvaluation']=$statement;
$aColumn = $REQIDS2;
// array_push($aColumn, 'AKSI');
$aColumns = array("CHECK");
$aColumns= array_merge($aColumns,$aColumn);
$tender_evaluation = new TenderEvaluation();
$tender_evaluation->selectByParamsMonitoring(array("A.MASTER_TENDER_PERIODE_ID"=>$reqPeriodeId),-1,-1,$statement);
// echo $tender_evaluation->query;exit;
$master_tener_menus = new MasterTenerMenus();
$master_tener_menus->selectByParamsMonitoring(array());
$attData = array();
 $attDataId = array();
 while ( $master_tener_menus->nextRow()) {
    array_push($attData , strtoupper($master_tener_menus->getField('NAMA')));
    $attDataId[strtoupper($master_tener_menus->getField('NAMA'))]= $master_tener_menus->getField("MASTER_TENDER_MENUS_ID");
}
// $aColumns.remove('ALL');
// $key = array_search('ALL',$aColumns);
// if (false !== $key) {
//     unset($aColumns[$key]);
// }
// print_r($aColumns);
$arrWitdh = array();
for($i=0;$i<count($aColumns);$i++){
  if($aColumns[$i]=='LAST_UPDATE' || $aColumns[$i]=='CLOSING'|| $aColumns[$i]=='OPENING' ){
     $arrWitdh[$aColumns[$i]]=' style="width:120px;vertical-align: middle;"';
  }else if($aColumns[$i]=='TITLE' || $aColumns[$i]=='NOTES' ){
     $arrWitdh[$aColumns[$i]]=' style="width:270px;vertical-align: middle;"';
  }else if($aColumns[$i]=='NAMA_PSC' || $aColumns[$i]=='TENDER_NO' || $aColumns[$i]=='OWNER' || $aColumns[$i]=='BID_VALUE'
  || $aColumns[$i]=='TKDN'|| $aColumns[$i]=='BID_BOUDS' || $aColumns[$i]=='BID_VALIDATY' ){
     $arrWitdh[$aColumns[$i]]=' style="width:150px;vertical-align: middle;"';
  }else{
    $arrWitdh[$aColumns[$i]]=' style="width:80px;vertical-align: middle;"';
  }
}
// $arrColomnAlias = array("Last updated by","Index","PSC Name","Tender Title","Tender No","Closing Date/1st Opening","2nd Opening Date","Failed / Decline","Owners Estimate","Bid Value","% TKDN","Bid Bond Value","Bid Validity (days)","Notes");

$arrColomnAlias = array();
$arrColomnAlias['LAST_UPDATE']= "Last updated by";$arrColomnAlias['INDEX']= "Index";
$arrColomnAlias['NAMA_PSC']= "PSC Name";$arrColomnAlias['TITLE']= "Tender Title";
$arrColomnAlias['TENDER_NO']= "Tender No";$arrColomnAlias['CLOSING']= "Closing Date/1st Opening";
$arrColomnAlias['OPENING']= "2nd Opening Date";$arrColomnAlias['STATUS']= "Failed / Decline";
$arrColomnAlias['OWNER']= "Owners Estimate";$arrColomnAlias['BID_VALUE']= "Bid Value";
$arrColomnAlias['TKDN']= "% TKDN";$arrColomnAlias['BID_BOUDS']= "Bid Bond Value";
$arrColomnAlias['BID_VALIDATY']= "Bid Validity (days)";$arrColomnAlias['NOTES']= "Notes";
?>
<style type="text/css">
  .table tr th:nth-child(2) { 
    width: 80%;  /*Custom your width*/
 }
  td{
    word-wrap: break-word;
  }

</style>


 <div class="table-responsive">
            <table id="example" class="table table-striped table-hover dt-responsive"  cellspacing="0" style="table-layout: fixed; width: 100%">
                <thead>
                    <tr>
                        
                        <?php
                        for ($i = 0; $i < count($aColumns); $i++) {
                          $colomnNama = $arrColomnAlias[$aColumns[$i]];
                          if(empty($colomnNama)){
                            $colomnNama = strtolower($aColumns[$i]);
                          }
                            $colomnNama= join(' ', array_map('ucfirst', explode(' ', $colomnNama)));
                        // var_dump($aColumns[$i]);
                            ?>
                            <th <?=$arrWitdh[$aColumns[$i]]?> valign="top"><?= str_replace('_', ' ', $colomnNama)  ?></th>  
                            <?php

                        };

                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $index=0;
                    while ($tender_evaluation->nextRow()) {
                       $reIdv = $tender_evaluation->getField('TENDER_EVALUATION_ID');
                        $status = $tender_evaluation->getField('STATUS');
                        $stityke_tr='';
                        if($status =='Failed'){
                           $stityke_tr = 'style="background:red"';
                        }else if($status =='Decline'){
                           $stityke_tr = 'style="background:#BDBDBD"';
                        }
                    ?>
                    <tr id="<?=$reIdv ?>" <?=$stityke_tr?>> 
                        <?
                           for ($i = 0; $i < count($aColumns); $i++) {

                            $text = $tender_evaluation->getField($aColumns[$i]);
                            $style='';
                          
                              if ($aColumns[$i] == "AKSI") {
                                $btn_edit = '<button type="button"  class="btn btn-info " onclick=editing(' . $reIdv . ')  ><i class="fa fa-pencil-square-o fa-lg"> </i> </button>';
                                $btn_delete = '<button type="button"  class="btn btn-danger hapusi"  onclick="deleting(' . $reIdv . ')"><i class="fa fa-trash-o fa-lg"> </i> </button>';

                                $text = $btn_edit . $btn_delete;
                             } else if(in_array($aColumns[$i], $attData)){
                   $master_tener_menus = new MasterTenerMenus();
                   $master_tener_menus->selectByParamsMonitoring(array("UPPER(A.NAMA)"=>$aColumns[$i]));
                   // echo $master_tener_menus->query.'<br>';
                   $master_tener_menus->firstRow();
                  $color2 = $master_tener_menus->getField("COLOR2");
                      $tender_evaluation_detail = new TenderEvaluationDetail();
                      $tender_evaluation_detail->selectByParamsMonitoring(array("A.TENDER_EVALUTATION_ID"=>$reIdv,"A.MASTER_TENDER_MENUS_ID"=>$attDataId[$aColumns[$i]]));
                      $tender_evaluation_detail->firstRow();
                    
                     
                      $NILAI= $tender_evaluation_detail->getField('NILAI');
                      if($NILAI == '100'){
                         $color = $tender_evaluation_detail->getField("COLOR");
                      }else if(empty($NILAI)){
                        $color = $color2;
                        $NILAI='&nbsp;';
                      } else{
                        $color = $color2;
                        $NILAI=$NILAI;
                      }
                      $style = ' style="background:'.$color.';width=100%;text-align:center;font-weight:bold"';

                      $text = $NILAI;
                } else if($aColumns[$i]=="CHECK"){
                    $text = '<input type="checkbox" value="'.$reIdv.'" name="reqIds[]" >';
                }else if ($aColumns[$i] == "STATUS"){
                     $status = $tender_evaluation->getField('STATUS');
                    if(!empty($status)){
                    $style = 'style="background:#404040;width=100%;text-align:center;font-weight:bold"';
                    $text ='<p style="font-size:15px;color:white">'.$status.'</p>';
                    }
                }else if ($aColumns[$i] == "OWNER"){
                    $text = $tender_evaluation->getField("CUR_OWNER")." " .currencyToPage2($tender_evaluation->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "BID_VALUE"){
                    $text = $tender_evaluation->getField("CUR_BID")." " .currencyToPage2($tender_evaluation->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "NAMA_PSC"){
                     $style = 'style="background:#FFFF00;width=100%;text-align:center;font-weight:bold"';
                }
                        ?>
                        <td  <?=$style?> ><?=$text?> </td>

                        <?
                        }
                        ?>
                    </tr>
                    <?
                    $index++;
                    }
                    if($index == 0){
                    ?>
                      <tr>
                        <td colspan="<?=count($aColumns)?>" align="center"> No Display Records  </td>
                      </tr>
                    <?
                    }

                    ?>
                </tbody>
            </table>
        </div>

    </div>