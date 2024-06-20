<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Offer");
$this->load->model("Company");
$this->load->model("Vessel");

$this->load->model("OfferRevisi");


$reqId = $this->input->get('reqId');
$reqRevId = $this->input->get("reqRevId");
$reqNom = $this->input->get("reqNom");

if(empty($reqRevId)){
	$offer = new Offer();
	$offer->selectByParamsMonitoring(array("OFFER_ID" => $reqId));
}else{
	$offer = new OfferRevisi();
	$offer->selectByParamsMonitoring(array("OFFER_REVISI_ID" => $reqRevId));
}

// $offer = new Offer();

// $offer->selectByParamsMonitoring(array("OFFER_ID" => $reqId));
    // echo  $offer->query;exit;
    $offer->firstRow();
    $reqOfferId          = $offer->getField("OFFER_ID");
    $reqDocumentId       = $offer->getField("DOCUMENT_ID");
    $reqDocumentPerson   = $offer->getField("DOCUMENT_PERSON");
    $reqDestination      = $offer->getField("DESTINATION");
    $reqDateOfService    = $offer->getField("DATE_OF_SERVICE");
    $reqTypeOfService    = $offer->getField("TYPE_OF_SERVICE");
    $reqScopeOfWork      = $offer->getField("SCOPE_OF_WORK");
    $reqTermAndCondition = $offer->getField("TERM_AND_CONDITION");
    $reqPaymentMethod    = $offer->getField("PAYMENT_METHOD");
    $reqTotalPrice       = $offer->getField("TOTAL_PRICE");
    $reqPriceUnit 	     = $offer->getField("PRICE_UNIT");
    $reqTotalPriceWord   = $offer->getField("TOTAL_PRICE_WORD");
    $reqStatus           = $offer->getField("STATUS");
    $reqReason           = $offer->getField("REASON");
    $reqNoOrder          = $offer->getField("NO_ORDER");
    $reqDateOfOrder      = $offer->getField("DATE_OF_ORDER");
    $reqCompanyName      = $offer->getField("COMPANY_NAME");
    $reqAddress          = $offer->getField("ADDRESS");
    $reqFaximile         = $offer->getField("FAXIMILE");
    $reqEmail            = $offer->getField("EMAIL");
    $reqTelephone        = $offer->getField("TELEPHONE");
    $reqHp               = $offer->getField("HP");
    $reqVesselName       = $offer->getField("VESSEL_NAME");
    $reqTypeOfVessel     = $offer->getField("TYPE_OF_VESSEL");
    $reqClassOfVessel    = $offer->getField("CLASS_OF_VESSEL");
    $reqMaker            = $offer->getField("MAKER");
    // $reqMaker            = $offer->getField("PENANGGUNG_JAWAB");
    $reqMaker            = $offer->getField("PENANGGUNG_JAWAB");
    $reqTTDLink          = $offer->getField("TTD_LINK");
    $reqClassAddend      = $offer->getField("CLASS_ADDEND");
    $reqClassAddend2      = $offer->getField("CLASS_ADDEND2");
    $reqStandByRate      = $offer->getField("STAND_BY_RATE");
    $reqLandTransport    = $offer->getField("LAND_TRANSPORT");
    $reqSoDays      	 = $offer->getField("SO_DAYS");
    $reqLumpsumDays      = $offer->getField("LUMPSUM_DAYS");

    $reqIssueDate       = $offer->getField("ISSUE_DATE");
    $reqIssueDateFormat = $offer->getField("ISSUE_DATE_FORMAT");
    $reqPreparedBy      = $offer->getField("PREPARED_BY");
    $reqReviewedBy      = $offer->getField("REVIEWED_BY");
    $reqApprovedBy      = $offer->getField("APPROVED_BY");
    $reqIssuePurpose    = $offer->getField("ISSUE_PURPOSE");
    $reqSubject         = $offer->getField("SUBJECT");
    $reqGeneralService  = $offer->getField("GENERAL_SERVICE");
    $reqGeneralServiceName = $offer->getField("GENERAL_SERVICE_NAME");
    
    $reqGeneralServiceDetail= $offer->getField("GENERAL_SERVICE_DETAIL");
    $reqProposalValidaty= $offer->getField("PROPOSAL_VALIDATY");
    $reqTechicalScope   = $offer->getField("TECHICAL_SCOPE");
    $reqTechicalSupport = $offer->getField("TECHICAL_SUPPORT");
    $reqCommercialSupport= $offer->getField("COMMERCIAL_SUPPORT");
    $reqDimentionL  = $offer->getField("VESSEL_DIMENSION_L");
    $reqDimentionB 	= $offer->getField("VESSEL_DIMENSION_B");
    $reqDimentionD	= $offer->getField("VESSEL_DIMENSION_D");
    $reqMinimumCharger     = $offer->getField("MINIMUM_CHARGER");
    $reqWorkTime           = $offer->getField("WORK_TIME");

    $reqRevHistory= $offer->getField("REV_HISTORY");

    $reqDimentionVessel = "L = ".$reqDimentionL." m, B = ".$reqDimentionB." m, D = ".$reqDimentionD." m";

    $reqTechicalScope 	= json_decode($reqTechicalScope,true);
    $reqTechicalSupport = json_decode($reqTechicalSupport,true);
    $reqCommercialSupport = json_decode($reqCommercialSupport,true);
    $reqRevHistory        = json_decode($reqRevHistory,true);

    $reqDateOfServiceMonth = getMonth($reqDateOfService);    
    $reqDateOfServiceYear = getDay($reqDateOfService);

    $reqDateOfService = getNameMonthEn((int)$reqDateOfServiceMonth);
?>
<h1 style="font-family: Arial;font-size: 18px;text-align: center;"> TECHNICAL AND COMMERCIAL PROPOSAL </h1>	

<div style="padding-left:50px;padding-right:  50px">


<table style="width: 100%;border-collapse: 1px solid black;font-size: 14px;font-family: Arial">
	<tr>
		<td style="width: 15%"> Doc. No.</td>
		<td style="width: 2%">: </td>
		<td> <?=$reqNoOrder?></td>
	</tr>
	<tr>
		<td> Date</td>
		<td>: </td>
		<td><?=$reqIssueDateFormat?></td>
	</tr>
<!-- 	<tr>
		<td> Prepered For.</td>
		<td>: </td>
		<td> <?=$reqDocumentPerson?></td>
	</tr>

	<tr>
		<td>Client Name</td>
		<td>: </td>
		<td> <?=$reqCompanyName?></td>
	</tr>

	 -->
	<tr>
		<td> Subject</td>
		<td>: </td>
		<td> <?=$reqSubject?></td>
	</tr>
	
</table>

<?
// print_r($reqRevHistory);
?>

<?php /* 
<table style="width: 100%;font-size: 12px;font-family: Arial;border-collapse: 1px solid balck" border="1">
	<tr>
		<td style="background-color: #D9D9D9" align="center"> <b>Rev</b> </td>
		<td style="background-color: #D9D9D9" align="center"> <b>Isssue Date</b></td>
		<td style="background-color: #D9D9D9" align="center" > <b>Prepared By</b></td>
		<td style="background-color: #D9D9D9" align="center"> <b>Reviewed By</b> </td>
		<td style="background-color: #D9D9D9" align="center"> <b>Approved By</b></td>
		<td style="background-color: #D9D9D9" align="center"> <b>Issue Purpose </b></td>
	</tr>
	<?
	for($i=0;$i<count($reqRevHistory);$i++){
	?>
	<tr>
		<td align="center"> <?=$i?> </td>
		<td style="padding-left: 10px"> <?=$reqRevHistory[$i]['ISSUE_DATE']?></td>
		<td style="padding-left: 10px"> <?=$reqRevHistory[$i]['PREPARED_BY']?></td>
		<td style="padding-left: 10px"> <?=$reqRevHistory[$i]['REVIEWED_BY']?> </td>
		<td style="padding-left: 10px"	> <?= $reqRevHistory[$i]['APPROVED_BY']?></td>
		<td style="padding-left: 10px"> <?=$reqRevHistory[$i]['ISSUE_PURPOSE']?></td>
	</tr>
	<?
	}
	?>
</table>
*/ ?>

<div style="font-size:  14px;font-family: Arial;text-align: justify-all;">
<p> Dear <?=$reqDocumentPerson?> </p>
<p style="text-align: justify;">
 With refer to <?=$reqCompanyName?> by Email dd: <?=getFormattedDateViewNew($reqDateOfOrder)?> pertaining to plan for <?=$reqGeneralServiceName?> due perusal of into received, we, PT Aquamarine Divindo Inspection ("CONTRACTOR"), herewith submit out Technical & Commercial Proposal <?=$reqCompanyName?> ("Client"), as follows: 
</p> 
</div>
<table style="font-size:  14px;font-family: Arial;text-align: justify-all;width: 100%">
	<tr>
		<td style="width: 28%;"> Client Name </td>
		<td style="width: 2%"> : </td>
		<td><?=$reqCompanyName?> </td>
	</tr>
	<tr>
		<td style="width: 28%;"> Contact Person </td>
		<td style="width: 2%"> : </td>
		<td><?=$reqDocumentPerson?> </td>
	</tr>
	<tr>
		<td> General Services </td>
		<td> : </td>
		<td><?=$reqGeneralServiceName?> </td>
	</tr>
	<tr>
		<td style="vertical-align:top"> Detail of Vessel </td>
		<td style="vertical-align:top"> : </td>
		<td> 
			<table>
				<tr>
					<td width="120px">- Vessel's Name</td>
					<td width="10px"> : </td>
					<td><?=$reqVesselName?> </td>
				</tr>
				<tr>
					<td>- Vessel's Type</td>
					<td> : </td>
					<td><?=$reqTypeOfVessel?> </td>
				</tr>
				<tr>
					<td>- Vessel's Class</td>
					<td> : </td>
					<td><?=$reqClassOfVessel?> </td>
				</tr>
				<tr>
					<td>- Dimension</td>
					<td> : </td>
					<td><?=$reqDimentionVessel?> </td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="vertical-align:top"> Location </td>
		<td style="vertical-align:top"> : </td>
		<td> <?=$reqDestination?> </td>
	</tr>
	<tr>
		<td style="vertical-align:top"> Date of Services </td>
		<td style="vertical-align:top"> : </td>
		<td> Circa, <?=$reqDateOfService?> <?=$reqDateOfServiceYear?> (Will be mutually agreed) </td>
	</tr>
	<tr>
		<td style="vertical-align:top"> Price of UWS </td>
		<td style="vertical-align:top"> : </td>
		<?
		 $reqTotalPrice = explode(" ", $reqTotalPrice);
		?>
		<td><?=$reqTotalPrice[0]?> <?=currencyToPage2($reqTotalPrice[1])?>/<?=$reqPriceUnit?> <br>
		( ## Say <?=$reqTotalPriceWord?> ##)</td>
	</tr>
	<tr>
		<td style="vertical-align:top"> Price Breakdown </td>
		<td style="vertical-align:top"> : </td>
		<td> <?=$reqMinimumCharger?>
		 </td>
	</tr>
	<tr>
		<td style="vertical-align:top"> Work Time </td>
		<td style="vertical-align:top"> : </td>
		<td> <?=$reqWorkTime?>
		 </td>
	</tr>
	<tr>
		<td style="vertical-align:top"> Payment Menthod </td>
		<td style="vertical-align:top"> : </td>
		<td> <?=$reqPaymentMethod?>
		 </td>
	</tr>
	<tr>
		<td style="vertical-align:top"> Proposal Validity </td>
		<td style="vertical-align:top"> : </td>
		<td><?=$reqProposalValidaty?> Days as of date of submission
		 </td>
	</tr>
</table>


<br>
<b style="font-size: 14px;font-family: Arial"> Term and Condition </b>
<div style="font-size: 14px;font-family: Arial">
<b> A.  Technical Scope </b>
	<table style="width: 100%;border-collapse: 1px solid black;font-family: Arial;font-size: 14px; " border="1"  >
		<?
		
		$this->load->model("TechicalScope");
		$techical_scope = new TechicalScope();
		$techical_scope->selectByParamsMonitoring(array("A.PARENT_ID"=>0),-1,-1,'',' ORDER BY A.URUTAN ASC');
		// echo $techical_scope->query;
		?>
		<thead>
		<tr>
			<th style="width: 5%; vertical-align: top" align="center"> No</th>
			<th style="width: 50%"> Description</th>
			<th style="width: 10%"> Include</th>
			<th style="width: 10%"> Exclude</th>
			<th style="width: 25%"> Remark</th>
		</tr>
		</thead>
		<tbody>
			<?
			$no=1;
			while ( $techical_scope->nextRow()) {
				$text='';
				if($techical_scope->getField('ID')==21){
					$text = $reqClassAddend;
				}
				if($techical_scope->getField('ID')==22){
					$text = $reqClassAddend2;
				}
				
				$style='style="padding-left: 10px"';
				# code...
				$remark = $reqTechicalScope[$techical_scope->getField('ID')]['REMARK'];
				$inc_check = $reqTechicalScope[$techical_scope->getField('ID')]['INC'];
				$enc_check = $reqTechicalScope[$techical_scope->getField('ID')]['ENC'];
				 if(!empty($inc_check)){$inc_check='<img src="images/centang.png" style="width: 10px;height: 10px">';}
             	 if(!empty($enc_check)){$enc_check='<img src="images/centang.png" style="width: 10px;height: 10px">';}

             	 // $techical_scope2 = new TechicalScope();
             	 // $total=	 $techical_scope2->getCountByParamsMonitoring(array("A.ID"=>$techical_scope->getField('PARENT_ID')));
             	 // $text_no=$no;
             	 // if($total!=0){
             	 // 	$style='style="padding-left: 25px"';
             	 // 	 $text_no = '';
             	 
             	 // }else{
             	 // 		$no++;
             	 // }


             	 $style='';

             	 $nama = $techical_scope->getField('NAMA');
             	 // if(strpos($nama, "Specify: Single Class:")){
             	 // 	$nama = $nama.$reqClassAddend;
             	 // }
             	 // if(strpos($nama, "Specify: Single Class:")){
             	 // 	$nama = $nama.$reqClassAddend;
             	 // }
			
			?>
			
			<tr>
				<td style="vertical-align: top;" align="center"><?=$no?> </td>
				<td align="left" <?=$style?> ><?=$nama?> <?=$text?> </td>
				<td align="center"><?=$inc_check?></td>
				<td align="center"><?=$enc_check?> </td>
				<td align="center"><?=$remark?> </td>

			</tr>
			<?
			 $techical_scope2 = new TechicalScope();
             	 $techical_scope2->selectByParamsMonitoring(array("A.PARENT_ID"=>$techical_scope->getField("ID")));
             	 while ($techical_scope2->nextRow()) {
             	 	 	 $nama = $techical_scope2->getField('NAMA');
             	 	 	 if($techical_scope2->getField('ID')==22){
             	 	 	 	$text = $reqClassAddend2;
             	 	 	 }
             	 	$style='style="padding-left: 25px"';
             	 	$remark = $reqTechicalScope[$techical_scope2->getField('ID')]['REMARK'];
             	 	$inc_check = $reqTechicalScope[$techical_scope2->getField('ID')]['INC'];
             	 	$enc_check = $reqTechicalScope[$techical_scope2->getField('ID')]['ENC'];
             	 	if(!empty($inc_check)){$inc_check='<img src="images/centang.png" style="width: 10px;height: 10px">';}
             	 	if(!empty($enc_check)){$enc_check='<img src="images/centang.png" style="width: 10px;height: 10px">';}

             	 	?>

             	 	<tr>
             	 		<td style="vertical-align: top;" align="center"> </td>
             	 		<td align="left" <?=$style?> ><?=$nama?> <?=$text?> </td>
             	 		<td align="center"><?=$inc_check?></td>
             	 		<td align="center"><?=$enc_check?> </td>
             	 		<td align="center"><?=$remark?> </td>

             	 	</tr>
             	 	<?
             	 	# code...
             	 }

				$no++;
			}
			?>
		</tbody>


	</table>
	<br>
	<b> B.  Technical Support </b>
	<table style="width: 100%;border-collapse: 1px solid black;font-family: Arial;font-size: 14px;" border='1'>
		<?
		$this->load->model("TechicalSupport");
		$techical_scope = new TechicalSupport();
		$techical_scope->selectByParamsMonitoring(array());
		?>
		<thead>
		<tr>
			<th style="width: 5%; vertical-align: top" align="center"> No</th>
			<th style="width: 50%"> Description</th>
			<th style="width: 10%"> Include</th>
			<th style="width: 10%"> Exclude</th>
			<th style="width: 25%"> Remark</th>
		</tr>
		</thead>
		<tbody>
			<?
			$no=1;
			while ( $techical_scope->nextRow()) {
				$style='style="padding-left: 10px"';
				# code...
				$remark = $reqTechicalSupport[$techical_scope->getField('ID')]['REMARK'];
				$inc_check = $reqTechicalSupport[$techical_scope->getField('ID')]['INC'];
				$enc_check = $reqTechicalSupport[$techical_scope->getField('ID')]['ENC'];
				 if(!empty($inc_check)){$inc_check='<img src="images/centang.png" style="width: 10px;height: 10px">';}
             	 if(!empty($enc_check)){$enc_check='<img src="images/centang.png" style="width: 10px;height: 10px">';}

             	 $techical_scope2 = new TechicalSupport();
             	 $total=	 $techical_scope2->getCountByParamsMonitoring(array("A.ID"=>$techical_scope->getField('PARENT_ID')));
             	 $text_no=$no;
             	 if($total!=0){
             	 	$style='style="padding-left: 25px"';
             	 	 $text_no = '';
             	 
             	 }else{
             	 		$no++;
             	 }

             	 $nama = $techical_scope->getField('NAMA');
             	 
             	 if(strpos($nama, "Airplane Ticket") !== false){
             	 	$nama = str_replace("{}", $reqLandTransport, $nama);
             	 }
			
			?>
			<tr>
				<td style="vertical-align: top;" align="center"><?=$text_no?> </td>
				<td align="left" <?=$style?> ><?=$nama?> </td>
				<td align="center"><?=$inc_check?></td>
				<td align="center"><?=$enc_check?> </td>
				<td align="center"><?=$remark?> </td>

			</tr>
			<?
			
			}
			?>
		</tbody>


	</table>
	<br>
	
	<b> C.  Commercial Support </b>
	<table style="width: 100%;border-collapse: 1px solid black;font-family: Arial;font-size: 14px;" border='1'>
		<?
		$this->load->model("CommercialSupport");
		$techical_scope = new CommercialSupport();
		$techical_scope->selectByParamsMonitoring(array());
		
		?>
		<thead>
		<tr>
			<th style="width: 5%; vertical-align: top" align="center"> No</th>
			<th style="width: 50%"> Description</th>
			<th style="width: 10%"> Include</th>
			<th style="width: 10%"> Exclude</th>
			<th style="width: 25%"> Remark</th>
		</tr>
		</thead>
		<tbody>
			<?
			$no=1;
			while ( $techical_scope->nextRow()) {
				$style='style="padding-left: 10px"';
				# code...
				$remark = $reqCommercialSupport[$techical_scope->getField('ID')]['REMARK'];
				$inc_check = $reqCommercialSupport[$techical_scope->getField('ID')]['INC'];
				$enc_check = $reqCommercialSupport[$techical_scope->getField('ID')]['ENC'];
				 if(!empty($inc_check)){$inc_check='<img src="images/centang.png" style="width: 10px;height: 10px">';}
             	 if(!empty($enc_check)){$enc_check='<img src="images/centang.png" style="width: 10px;height: 10px">';}

             	 $techical_scope2 = new CommercialSupport();
             	 $total=	 $techical_scope2->getCountByParamsMonitoring(array("A.ID"=>$techical_scope->getField('PARENT_ID')));
             	 $text_no=$no;
             	 if($total!=0){
             	 	 $text_no = '';
             	 
             	 }else{
             	 		$no++;
             	 }

             	 $nama = $techical_scope->getField('NAMA');
             	 
             	 if(strpos($nama, "Standby Rate :") !== false){
             	 	$nama = str_replace("{}", $reqStandByRate, $nama);
             	 }
             	 if(strpos($nama, "Service Order (SO)") !== false){
             	 	$nama = str_replace("{}", $reqSoDays, $nama);
             	 }
             	 if(strpos($nama, "Applicable when:") !== false){
             	 	$nama = str_replace("{}", $reqLumpsumDays, $nama);
             	 }
			
			?>
			<tr>
				<td style="vertical-align: top;" align="center"><?=$text_no?> </td>
				<td align="left" <?=$style?> ><?=$nama?> </td>
				<td align="center"><?=$inc_check?></td>
				<td align="center"><?=$enc_check?> </td>
				<td align="center"><?=$remark?> </td>

			</tr>
			<?
			
			}
			?>
		</tbody>


	</table>
	</div>


<p style="font-size: 14px; font-family: Arial;text-align: justify;">
Hence, Shall there be any questions pertaining to above,feel free to contact us at any of your convenience time. Shall you find it suit to your immediate SO Accordingly.
<br>
<br>
Best Regards,<br>
<!-- <img src="uploads/offering/<?= $reqId ?>/offering<?= $reqMaker ?>.png"> -->
<img style="position: absolute; width: 150px" src="<?=$reqTTDLink?>">
<style="position: absolute; left: 0px; margin-top: -10px"><br>
<style="font-size: 14px; font-family: Arial; position: absolute; z-index: 2; text-align: justify;">
<u><?= ucfirst( strtolower($reqMaker)) ?></u><br>
</em>Marketing Department</em>



<?

?>
<div style="font-size: 14px;font-family: Arial">
	<table border="1" style="width: 100%; font-size: 14px; border-collapse: 1px solid black;font-family: Arial">
	    <thead>
	        <tr>
	            <th style="width: 5%;background-color: #D9D9D9">Rev</th>
	            <th style="width: 15%;background-color: #D9D9D9">Issue Date</th>
	            <th style="width: 15%;background-color: #D9D9D9">Prepared by</th>
	            <th style="width: 10%;background-color: #D9D9D9">Reviewed by</th>
	            <th style="width: 20%;background-color: #D9D9D9">Approved by</th>
	            <th style="width: 20%;background-color: #D9D9D9">Issue Purpose</th>
	        </tr>
	    </thead>
	    <?php
	    $no = 0;
	    // exit;
	    ?>
	    <tbody>
	    	<?php
	    	$offer_revisi = new Offer();
	    	if(empty($reqRevId)){
	    		// $offer_revisi->selectByParamsRevisi(array("OFFER_ID" => $reqId), -1, -1, "", " ORDER BY REV_DATE ASC ");
	    	}else{
	    		// $offer_revisi->selectByParamsRevisi(array("OFFER_REVISI_ID" => -1), -1, -1, "", " ORDER BY REV_DATE ASC ");
	    		// $no = $reqNom;
	    	}
	    	$offer_revisi->selectByParamsRevisi(array("OFFER_ID" => $reqId), -1, -1, "", " ORDER BY REV_DATE ASC ");
	    	// echo $offer_revisi->query;exit;
	    	if(empty($reqRevId)){
	    		$reqNom='999';
	    	}
	    	while ($offer_revisi->nextRow()) {
	    		if($no <= $reqNom ){
	    	?>
	        <tr>
	            <td style="text-align: center;"><?=$no?></td>
	            <td style="text-align: center;"><?=$offer_revisi->getField("ISSUE_DATE")?></td>
	            <td style="text-align: center;"><?=$offer_revisi->getField("PREPARED_BY")?></td>
	            <td style="text-align: center;"><?=$offer_revisi->getField("REVIEWED_BY")?></td>
	            <td style="text-align: center;"><?=$offer_revisi->getField("APPROVED_BY")?></td>
	            <td style="text-align: center;"><?=$offer_revisi->getField("ISSUE_PURPOSE")?></td>
	        </tr>
	        <?php
	        	}
	        $no++;
	    	}
	    	if(empty($reqRevId)){
	    		$offer = new Offer();
				$offer->selectByParamsMonitoring(array("OFFER_ID" => $reqId));
				$offer->firstRow();
				$reqIssueDate       = $offer->getField("ISSUE_DATE");
				$reqPreparedBy      = $offer->getField("PREPARED_BY");
				$reqReviewedBy      = $offer->getField("REVIEWED_BY");
				$reqApprovedBy      = $offer->getField("APPROVED_BY");
				$reqIssuePurpose    = $offer->getField("ISSUE_PURPOSE");
				
	    	?>
	    	 <tr>
	            <td style="text-align: center;"><?=$no?></td>
	            <td style="text-align: center;"><?=$reqIssueDate?></td>
	            <td style="text-align: center;"><?=$reqPreparedBy?></td>
	            <td style="text-align: center;"><?=$reqReviewedBy?></td>
	            <td style="text-align: center;"><?=$reqApprovedBy?></td>
	            <td style="text-align: center;"><?=$reqIssuePurpose?></td>
	        </tr>

		    <?
		    	
		    }		
	    	?>
	    	
	    </tbody>
	    <?php
	    $no++;
	    ?>
	</table>
</div>
</div>

</div>
<?

?>