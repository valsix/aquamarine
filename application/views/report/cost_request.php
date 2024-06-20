<?
$this->load->model("CostRequestDetail");
$reqId = $this->input->get("reqId");
$tgl_skrng =date('d F Y');

$this->load->model("CostRequest");
$cost_request = new CostRequest();

$cost_request->selectByParamsMonitoring(array("A.COST_REQUEST_ID " => $reqId));
$cost_request->firstRow();
$reqId          = $cost_request->getField("COST_REQUEST_ID");
$reqKode        = $cost_request->getField("KODE");
$reqTanggal     = $cost_request->getField("TANGGAL");
$reqTotal       = $cost_request->getField("TOTAL");
$reqKeterangan  = $cost_request->getField("KETERANGAN");
$reqPengambilan = $cost_request->getField("PENGAMBILAN");
$cost_request_detail = new CostRequestDetail();
$total = $cost_request_detail->getCountByParamsSum(array("A.COST_REQUEST_ID " => $reqId));


?>

<table  style="width: 100%;font-size: 12px;font-weight: bold;font-family: Arial;border-collapse: 1px solid black" border="1">
	<tr>
		<td align="center" style="width: 250px;background-color: #002060;color: white"> COST REQUEST </td>
		<td style="width: 100px"> </td>
		<td  align="center" style="width: 300px;background-color: #002060;color: white"> TOTAL ( Rp ) </td>
		<td style="width: 150px;background-color: #002060;color: white"> DATE </td>
		<td style="background-color: #002060;color: white"> PREPARED BY </td>
	</tr>
	<tr>
		<td rowspan="3" valign="top" align="center"><br><?=$reqKode ?> </td>
		<td style="width: 100px;background-color: #002060;color: white" align="center"> Total Request </td>
		<td  valign="top" align="right"> <br><?=currencyToPage2($total)?></td>
		<td><?= $tgl_skrng?> </td>
		<td style="background-color: #002060;color: white">Isnaini </td>
	</tr>
	<tr>
		<td style="width: 100px;"align="center" >Diambil Dari BCA </td>
		<td align="right"><?=currencyToPage2($reqPengambilan)?> </td>
		<td style="background-color: #002060;color: white">Date </td>
		<td style="background-color: #002060;color: white">ACKNOWLEDGED BY </td>
	</tr>
	<tr>
		<td style="width: 100px;background-color: #002060;color: white" align="center"> Total </td>
		<td align="right"> <?=currencyToPage2($total-$reqPengambilan)?></td>
		<td><?= $tgl_skrng?></td>
		<td> ( ) </td>
	</tr>
</table>

<br>
<br>
<table style="width: 100%;border-collapse: 1px solid black;font-size: 12px;font-family: Arial;text-align: center;" border="1" >
	<tr>
	<td style="background-color: #002060;color: white;width:5% "><b>NO</b></td> 
	<td style="background-color: #002060;color: white;width:20%"><b>DESCRIPTION OF COST</b></td> 
	<td style="background-color: #002060;color: white;width:10%"><b>COST CODE</b></td> 
	<td style="background-color: #002060;color: white;width:10%"><b>COST CATEGORI</b></td> 
	<td style="background-color: #002060;color: white;width:10%"><b>TANGGAL</b></td> 
	<td style="background-color: #002060;color: white;width:10%"><b>EVIDENCE</b></td> 
	<td style="background-color: #002060;color: white;width:10%"><b>AMOUNT ( RP )</b></td> 
	<td style="background-color: #002060;color: white;width:10%"><b>PROJECT</b></td> 
	<td style="background-color: #002060;color: white;width:10%"><b>PAID TO</b></td> 
</tr>
<?
  
        $cost_request_detail = new CostRequestDetail();
        $cost_request_detail->selectByParamsMonitoring(array("A.COST_REQUEST_ID"=>$reqId));
        $no=1;
	  while ( $cost_request_detail->nextRow()) {
        	?>
        	<tr>
        	<td><?=$no?> </td>
        	<td style="text-align: justify;"><?=$cost_request_detail->getField("KETERANGAN")?> </td>
        	<td style="text-align: justify;"><?=$cost_request_detail->getField("COST_CODE")?> </td>
        	<td style="text-align: justify;"><?=$cost_request_detail->getField("COST_CODE_CATEGORI")?> </td>
        	<td style="text-align: justify;"><?=$cost_request_detail->getField("TANGGAL")?> </td>
        	<td style="text-align: justify;"><?=$cost_request_detail->getField("EVIDANCE")?> </td>
        	<td style="text-align: right;"><?=currencyToPage2($cost_request_detail->getField("AMOUNT"))?> </td>
        	<td style="text-align: justify;"><?=$cost_request_detail->getField("PROJECT")?> </td>
        	<td style="text-align: justify;"><?=$cost_request_detail->getField("PAID_TO")?> </td>
        </tr>
        	<?

        $no++;	
        }
?>


</table>