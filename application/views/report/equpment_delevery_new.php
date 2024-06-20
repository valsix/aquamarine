<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Service_order");
$service_order = new Service_order();

$reqId = $this->input->get("reqId");


$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $service_order->selectByParamsMonitoring(array("A.SO_ID" => $reqId));
    $service_order->firstRow();
   
    $reqSoId            = $service_order->getField("SO_ID");
    $reqProjectName     = $service_order->getField("PROJECT_NAME");
    $reqNoOrder         = $service_order->getField("NO_ORDER");
    $reqCompanyName     = $service_order->getField("COMPANY_NAME");
    $reqVesselName      = $service_order->getField("VESSEL_NAME");
    // echo $reqCompanyName;exit;
    $reqVesselType      = $service_order->getField("VESSEL_TYPE");
    $reqSurveyor        = $service_order->getField("SURVEYOR");
    $reqDestination     = $service_order->getField("DESTINATION");
    $reqService         = $service_order->getField("SERVICE");
    $reqDateOfStart     = $service_order->getField("DATE_OF_START");
    $reqDateOfFinish    = $service_order->getField("DATE_OF_FINISH");
    $reqTransport       = $service_order->getField("TRANSPORT");
    $reqEquipment       = $service_order->getField("EQUIPMENT");
    $reqObligation      = $service_order->getField("OBLIGATION");
    $reqDateOfService   = $service_order->getField("DATE_OF_SERVICE");
    $reqPicEquip        = $service_order->getField("PIC_EQUIP");
    $reqContactPerson   = $service_order->getField("CONTACT_PERSON");
    $reqNoDelivery   = $service_order->getField("NO_DELIVERY");

    
}

?>
<h1 style="font-family: Arial;font-size: 15px;text-align: center;font-weight: bold"><u> EQUIPMENT DELIVERY SLIP </u> </h1>	
<br>
<div style="font-family: Arial;font-size: 13px;text-align: center;margin-top: -30px;padding: 0;line-height: 0px;"><em><?=$reqNoDelivery?></em></div>	
<br>
<br>
<div style="margin-top: -25px;">
<table style="width: 100%;font-size: 13px;font-family: calibri;"  >
	<tr>
		<td style="width: 15%; vertical-align: top">Project of Name </td>
		<td style="width: 2%; vertical-align: top">: </td>
		<td style="width: 35%"> <?=$reqProjectName?></td>
		<td style="width: 5%">&nbsp;</td>
		<td style="width: 15%; vertical-align: top">Vessel of Name </td>
		<td style="width:2%; vertical-align: top">: </td>
		<td style="width:25%; vertical-align: top"> <?=$reqVesselName?></td>	
	</tr>
	<tr >
		<td style="vertical-align: top">Client of Name </td>
		<td style="vertical-align: top">: </td>
		<td style="vertical-align: top"> <?=$reqCompanyName?></td>
		<td > &nbsp;</td>
		<td style="vertical-align: top">Type of Vessel </td>
		<td style="vertical-align: top">: </td>
		<td style="vertical-align: top"><?=$reqVesselType?> </td>
		<!-- <td>Vessel Name </td>
		<td>: </td>
		<td> <?=$reqVesselName?></td> -->
	</tr>
	<tr>
		<td style="vertical-align: top">Location </td>
		<td style="vertical-align: top">: </td>
		<td style="vertical-align: top" colspan="5"> <?=$reqDestination?></td>
		
	</tr>
	
</table>

<br>

<?	
$this->load->model('EquipCategory');
$equipcategory = new EquipCategory();
$this->load->model('SoEquip');


$equipcategory->selectByParamsMonitoring(array());
$arrData = array();
$so_equip = new SoEquip(); 
// $statement = " AND (B.EC_ID IS NULL OR B.EC_ID ='' )";
$statement = " AND (B.EC_ID IS NULL )";

$so_equip->selectByParamsMonitoringEquips(array("A.SO_ID"=>$reqId),-1,-1,$statement);
$aData = array();
$nn=0;
while ( $so_equip->nextRow()) {
	$aData[$nomer]["EQUIP_NAME"]=$so_equip->getField("EQUIP_NAME");
	$aData[$nomer]["QTY"]=$so_equip->getField("QTY");
	$aData[$nomer]["EQUIP_ITEM"]=$so_equip->getField("EQUIP_ITEM");
	$aData[$nomer]["OUT_CONDITION"]=$so_equip->getField("OUT_CONDITION");
	$aData[$nomer]["IN_CONDITION"]=$so_equip->getField("IN_CONDITION");
	$aData[$nomer]["REMARK"]=$so_equip->getField("REMARK");
$nn++;
}
		$arrData[0]["NAMA"] = "NO KATEGORI";
		$arrData[0]["RESULTS"] =$aData;

$no=1;
while ($equipcategory->nextRow()) {
		$so_equip = new SoEquip(); 
		$so_equip->selectByParamsMonitoringEquips(array("A.SO_ID"=>$reqId,"B.EC_ID"=>$equipcategory->getField("EC_ID")));
		$nomer=0;
		$aData = array();
		while ($so_equip->nextRow()) {
			$aData[$nomer]["EQUIP_NAME"]=$so_equip->getField("EQUIP_NAME");
			$aData[$nomer]["QTY"]=$so_equip->getField("QTY");
			$aData[$nomer]["EQUIP_ITEM"]=$so_equip->getField("EQUIP_ITEM");
			$aData[$nomer]["OUT_CONDITION"]=$so_equip->getField("OUT_CONDITION");
			$aData[$nomer]["IN_CONDITION"]=$so_equip->getField("IN_CONDITION");
			$aData[$nomer]["REMARK"]=$so_equip->getField("REMARK");
			$nomer++;
		}
		$arrData[$no]["NAMA"] = $equipcategory->getField("EC_NAME");
		$arrData[$no]["RESULTS"] =$aData;
		if($nomer!=0){
			$no++;
		}

}

?>

<table style="width: 100%;font-size: 13px;font-family: calibri;border-collapse: 1px solid black; margin-top: -10px" border="1">
	<tr>
		<th style="width: 5%">No</th>
		<th style="width: 30%">Description of Goods</th>
		<th style="width: 10%">Quantity</th>
		<th style="width: 10%">Item</th>
		<th style="width: 10%">Out (*) Condition</th>
		<th style="width: 10%">In (*) Condition</th>
		<th style="width: 25%">Remark</th>
	</tr>
	<?
	$nomer=1;
	$nomers=0;
	for($i=0;$i<count($arrData);$i++){
		$results = $arrData[$i]['RESULTS'];
		if(count($results)!=0 && !empty($results[0]['EQUIP_NAME']) ){
			$nomers= $nomers+1;
	?>
	<tr>
		<td align="center" style="padding: 5px"><b><?=romanic_number($nomers)?></b> </td>
		<td style="padding: 5px"><b><?=$arrData[$i]['NAMA']?></b> </td>
		<td style="padding: 5px"> </td>
		<td style="padding: 5px"> </td>
		<td style="padding: 5px"> </td>
		<td style="padding: 5px"> </td>
		<td style="padding: 5px"> </td>
	</tr>
	<?
		for($j=0;$j<count($results);$j++){
	?>
	<tr>
		<td align="center" style="padding: 5px"><?=($nomer)?> </td>
		<td style="padding: 5px"><?=$results[$j]['EQUIP_NAME']?> </td>
		<td align="center" style="padding: 5px"><?=$results[$j]['QTY']?> </td>
		<td align="center" style="padding: 5px"><?=$results[$j]['EQUIP_ITEM']?> </td>
		<td align="center" style="padding: 5px"><?=$results[$j]['OUT_CONDITION']?></td>
		<td align="center" style="padding: 5px"><?=$results[$j]['IN_CONDITION']?></td>
		<td style="padding: 5px"><?=$results[$j]['REMARK']?> </td>
	</tr>
	<?		
		$nomer++;
		}
		}	
	}
	?>
</table>
<span style="font-size: 9px;font-family: calibri;"><em>(*) Notes : G = Goood , B= Broken, M = Miss </em> </span>
<br>
<br>
<table style="width: 100%;border-collapse: 1px solid black;font-size: 13px;font-family:calibri;text-align: center;" border="1">
	<tr>
		<td colspan="3" align="center"><b> Out Checked By </b></td>
		<td colspan="3" align="center"><b> In Checked By </b> </td>

	</tr>
	<tr>
		<td><b> Inventory </b></td>
		<td><b> Diving Supt</b></td>
		<td><b> HSE Spv </b></td>
		<td><b> Inventory </b></td>
		<td><b> Diving Supt </b></td>
		<td><b> HSE Spv </b></td>
	</tr>
	<tr >
		<td height="50">&nbsp; </td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
	</tr>
	<tr>
		<td align="left">Name : </td>
		<td align="left">Name :  </td>
		<td align="left">Name : </td>
		<td align="left">Name : </td>
		<td align="left">Name : </td>
		<td align="left">Name : </td>
	</tr>
	<tr>
		<td align="left">Date </td>
		<td align="left">Date  </td>
		<td align="left">Date </td>
		<td align="left">Date </td>
		<td align="left">Date </td>
		<td align="left">Date </td>
	</tr>
	<tr >
		<td >&nbsp; </td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
		<td>&nbsp; </td>
	</tr>
	<tr >
		<td>Received By </td>
		<td>Note </td>
		<td colspan="2">Remark on item conditions </td>
		
		<td>Returned By </td>
		<td>Note </td>
	</tr>
	<tr >
		<td height="50">&nbsp; </td>
		<td rowspan="3">&nbsp; </td>
		<td colspan="2" rowspan="3">&nbsp; </td>
		
		<td>&nbsp; </td>
		<td  rowspan="3">&nbsp; </td>
	</tr>
	<tr>
		<td align="left">Name </td>
		<td align="left">Name </td>
	</tr>
	<tr>
		<td align="left">Date </td>
		<td align="left">Date </td>
	</tr>
</table>

</div>