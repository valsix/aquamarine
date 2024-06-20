<?
include_once("functions/default.func.php");
include_once("functions/string.func.php");


$this->load->model("TenderEvaluation");
$this->load->model("MasterTenerMenus");
$this->load->model("TenderEvaluationDetail");
$this->load->model("MasterTenderPeriode");

$reqTahun       = $this->input->get('reqId');
$reqColomn      = $this->input->get('reqColomn');
$reqEvalusiId      = $this->input->get('reqEvalusiId');
$reqIds       = explode(',', $reqId);
$reqCari      = $this->input->get('reqCari');
// $reqTahun     = $this->input->get('reqTahun');




// echo 'asd'.$reqTahun;exit;
$master_tender_periode = new MasterTenderPeriode();
$master_tender_periode->selectByParamsMonitoring(array("A.TAHUN"=>$reqTahun));
// echo $master_tender_periode->query;exit;
$master_tender_periode->firstRow();
$reqPeriodeId =$master_tender_periode->getField('MASTER_TENDER_PERIODE_ID');
if(empty($reqPeriodeId)){
$master_tender_periode = new MasterTenderPeriode();
$master_tender_periode->setField("TAHUN",$reqTahun);
$master_tender_periode->insert();
$reqPeriodeId = $master_tender_periode->id;
}


$master_tener_menus = new MasterTenerMenus();
$master_tener_menus->selectByParamsMonitoring(array());
$attData = array();
$attDataId = array();
while ( $master_tener_menus->nextRow()) {
    array_push($attData , strtoupper($master_tener_menus->getField('NAMA')));
    $attDataId[strtoupper($master_tener_menus->getField('NAMA'))]= $master_tener_menus->getField("MASTER_TENDER_MENUS_ID");
}
$defauls_colomn = array("LAST_UPDATE","INDEX","NAMA_PSC","TITLE","TENDER_NO","CLOSING","OPENING");
$defauls_colomn =array_merge($defauls_colomn,$attData);
$arDataOther = array("STATUS","OWNER","BID_VALUE","TKDN","BID_BOUDS","BID_VALIDATY","NOTES");
$defauls_colomn = array_merge($defauls_colomn,$arDataOther);

$reqColomnss = explode(',', $reqColomn);
// print_r($reqColomn);exit;
$aColumns = array();
for($i=0;$i<count($reqColomnss);$i++){
	array_push($aColumns, $defauls_colomn[$reqColomnss[$i]]);
}

// $reqColomn = explode(',', $reqColomn );
$REQIDS2 =array();
for($i=0;$i<count($aColumns);$i++){
  if($aColumns[$i]!='ALL'){
    array_push($REQIDS2,$aColumns[$i] );
  }
}

$reqColomVariable = implode_to_string( $REQIDS2);
$statement = " AND UPPER(A.NAMA) IN (".$reqColomVariable.")";
$master_tener_menus = new MasterTenerMenus();
$master_tener_menus->selectByParamsMonitoring(array(),-1,-1,$statement);
$attData = array();
 $attDataId = array();
 while ( $master_tener_menus->nextRow()) {
    array_push($attData , strtoupper($master_tener_menus->getField('NAMA')));
    $attDataId[strtoupper($master_tener_menus->getField('NAMA'))]= $master_tener_menus->getField("MASTER_TENDER_MENUS_ID");
}


$statement='';
if(!empty($reqEvalusiId)){
$reqEvalusiIds =explode(',', $reqEvalusiId);
$reqVid = implode_to_string( $reqEvalusiIds);
$statement = " AND CAST(A.TENDER_EVALUATION_ID AS VARCHAR) IN (".$reqVid.")";
}

$tender_evaluation = new TenderEvaluation();
$tender_evaluation->selectByParamsMonitoring(array("A.MASTER_TENDER_PERIODE_ID"=>$reqPeriodeId),-1,-1,$statement);
// echo $tender_evaluation->query;exit;


// $reqValue ="LAST_UPDATE,INDEX,NAMA_PSC,TITLE,TENDER_NO,CLOSING,OPENING,PQ REGIST,COLLECT ITB,PRE BID,DOC PREPARE,SUBMIT 1ST ENV/OPEN TECH,SUBMIT 2ND ENV/OPEN COMM,NEGO,NOA,LOA,STATUS,OWNER,BID_VALUE,TKDN,BID_BOUDS,BID_VALIDATY,NOTES";


$arrColomnAlias = array();
$arrColomnAlias['LAST_UPDATE']= "Last updated by";$arrColomnAlias['INDEX']= "Index";
$arrColomnAlias['NAMA_PSC']= "PSC Name";$arrColomnAlias['TITLE']= "Tender Title";
$arrColomnAlias['TENDER_NO']= "Tender No";$arrColomnAlias['CLOSING']= "Closing Date/1st Opening";
$arrColomnAlias['OPENING']= "2nd Opening Date";$arrColomnAlias['STATUS']= "Failed / Decline";
$arrColomnAlias['OWNER']= "Owners Estimate";$arrColomnAlias['BID_VALUE']= "Bid Value";
$arrColomnAlias['TKDN']= "% TKDN";$arrColomnAlias['BID_BOUDS']= "Bid Bond Value";
$arrColomnAlias['BID_VALIDATY']= "Bid Validity (days)";$arrColomnAlias['NOTES']= "Notes";

// $aColumns = explode(',', $reqValue);

$arrWitdh = array();
for($i=0;$i<count($aColumns);$i++){
  if($aColumns[$i]=='LAST_UPDATE' || $aColumns[$i]=='CLOSING'|| $aColumns[$i]=='OPENING' ){
     $arrWitdh[$aColumns[$i]]='width:120px';
  }else if($aColumns[$i]=='TITLE' || $aColumns[$i]=='NOTES' ){
     $arrWitdh[$aColumns[$i]]='width:270px';
  }else if($aColumns[$i]=='NAMA_PSC' || $aColumns[$i]=='TENDER_NO' || $aColumns[$i]=='OWNER' || $aColumns[$i]=='BID_VALUE'
  || $aColumns[$i]=='TKDN'|| $aColumns[$i]=='BID_BOUDS' || $aColumns[$i]=='BID_VALIDATY' ){
     $arrWitdh[$aColumns[$i]]=' width:150px';
  }else{
    $arrWitdh[$aColumns[$i]]=' width:80px';
  }
}
?>

<table style="width: 100%;border-collapse: 1px solid black;">
<tr>
	<td colspan="<?=count($aColumns)?>" style="border:1px solid black;background: #D9D9D9;font-size: 20px;font-weight: bold" align="center">TENDER EVALUATION SYSTEM </td>


</tr>
<tr>
	<?
	for ($i = 0; $i < count($aColumns); $i++) {
		$colomnNama = $arrColomnAlias[$aColumns[$i]];
		if(empty($colomnNama)){
			$colomnNama = strtolower($aColumns[$i]);
		}
		
		$title = str_replace('_', ' ', strtolower($colomnNama));
		$join= join(' ', array_map('ucfirst', explode(' ', $title)));
	?>
	<td style="border:1px solid black;background: #808080;font-weight: bold;<?=$arrWitdh[$aColumns[$i]]?>" align="center">
		<?= $join ?>
	</td>
	<?
	}
	?>
	</tr>
	
			<?
			$index=0;
			while ($tender_evaluation->nextRow()) {
				?>
				<tr>
					<?
					for($i=0;$i<count($aColumns);$i++){
						$text = '';
						$style='';
						 $reIdv = $tender_evaluation->getField('TENDER_EVALUATION_ID');
						 if(in_array($aColumns[$i], $attData)){
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
						 		// $NILAI='&nbsp;';
						 	}
						 	$style = ' background:'.$color.';width=100%;text-align:center;font-weight:bold';

						 	$text = $NILAI;
						 }else if ($aColumns[$i] == "STATUS"){
                     $status = $tender_evaluation->getField('STATUS');
                    if(!empty($status)){
                    $style = 'background:#404040;width=100%;text-align:center;font-weight:bold';
                    $text ='<p style="font-size:15px;color:white">'.$status.'</p>';
                    }
                }else if ($aColumns[$i] == "OWNER"){
                    $text = $tender_evaluation->getField("CUR_OWNER")." " .currencyToPage2($tender_evaluation->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "BID_VALUE"){
                    $text = $tender_evaluation->getField("CUR_BID")." " .currencyToPage2($tender_evaluation->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "NAMA_PSC"){
                	$text = $tender_evaluation->getField($aColumns[$i]);
                     $style = 'background:#FFFF00;width=100%;text-align:center;font-weight:bold';
                }else{
						 	$text = $tender_evaluation->getField($aColumns[$i]);
						 }


					?>
					<td style="border:1px solid black;padding-left: 10px;<?=$style?>">
					<?=$text?>	
					</td>
					<?
					}
					?>
				</tr>
				<?
				$index++;


			}
			if($index==0){
			?>
			<tr>
				<td colspan="<?=count($aColumns)?>" align="center" style="border:1px solid black"> No Display Results </td>
			</tr>
			<?
			}

			?>
		
 </table>