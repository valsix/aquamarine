<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Offer");
$this->load->model("Company");
$this->load->model("Vessel");
$this->load->model("CategoryProject");

$reqId = $this->input->get('reqId');
$offer = new Offer();

$offer->selectByParamsMonitoring(array("OFFER_ID" => $reqId));
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
    // $reqMaker            = $offer->getField("PENANGGUNG_JAWAB");
    $reqMaker            = $offer->getField("PENANGGUNG_JAWAB");
    $reqTTDLink            = $offer->getField("TTD_LINK");
    $reqClassAddend            = $offer->getField("CLASS_ADDEND");

    $reqIssueDate       = $offer->getField("ISSUE_DATE");
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

    $reqRevHistory= $offer->getField("REV_HISTORY");

    $reqTechicalScope 	= json_decode($reqTechicalScope,true);
    $reqTechicalSupport = json_decode($reqTechicalSupport,true);
    $reqCommercialSupport = json_decode($reqCommercialSupport,true);
    $reqRevHistory        = json_decode($reqRevHistory,true);

    $reqDateOfServiceMonth = getMonth($reqDateOfService);    
    $reqDateOfServiceYear = getDay($reqDateOfService);

    $reqDateOfService = getNameMonth((int)$reqDateOfServiceMonth);
?>
<div style="padding-left:30px;padding-right: 30px;font-size:  14px;font-family: Arial;">
<table style="width: 100%;font-size:  14px;font-family: Arial;">
	<tr>
		<td width="70%">Project No: <?=$reqNoOrder?></td>
		<td width="30%" style="text-align: right;">Sidoarjo, 23 October 2020</td>
	</tr>
</table>

<br>
<p>
	To: 
	<br>
	<?=$reqCompanyName?>
	<br>
	<?=str_replace("\n", "<br>", $reqAddress)?>
	<br>
	Attn. : <?=$reqDocumentPerson?>
</p>

<div style="text-align: justify-all;">
Dear Sir, 
<p style="text-align: justify;">
 With refer to <?=$reqCompanyName?> by Email dd: <?=getFormattedDateViewNew($reqDateOfOrder)?>  pertaining to plan for  <?=$reqGeneralServiceName?> due perusal of into received, we, PT Aquamarine Divindo Inspection ("CONTRACTOR"), herewith submit out Technical & Commercial Proposal  <?=$reqCompanyName?> (" Client"), as follows :  
</p>
</div>
<table style="text-align: justify-all;width: 100%;font-size:  14px;font-family: Arial;">
	<tr>
		<td width="18%"> General Services </td>
		<td> : </td>
		<td><?=$reqGeneralServiceName ?: $reqSubject?> </td>
	</tr>
	<tr>
		<td style="vertical-align:top"> Detail of Services </td>
		<td style="vertical-align:top"> : </td>
		<td><?=str_replace("\n", "<br>", $reqGeneralServiceDetail)?> </td> 
	</tr>
	<tr>
		<td> Location </td>
		<td> : </td>
		<td> <?=$reqDestination?> </td>
	</tr>
	<tr>
		<td> Date of Services </td>
		<td> : </td>
		<td> Circa, <?=($reqDateOfService)?> <?=$reqDateOfServiceYear?>(Will be mutually agreed) </td>
	</tr>
</table>

<br>

<table style="width: 100%;border-collapse: 1px solid black;font-size:  14px;font-family: Arial;" border="1"  >
	
	<thead>
	<tr>
		<th style="width: 5%; " align="center"> No</th>
		<th style="width: 40%"> Description</th>
		<th style="width: 8%"> QTY</th>
		<th style="width: 8%"> Dur</th>
		<th style="width: 8%"> UOM</th>
		<th style="width: 20%"> Price</th>
		<th style="width: 20%"> Total</th>
	</tr>
	</thead>
	<tbody>
		<?php
		$category_project = new CategoryProject();
		$category_project->selectByParamsMonitoring();
		$no = 1;
		$grand_total = 0;
		while ($category_project->nextRow())
		{ 
		$this->load->model("OfferProject");
		$offer_project = new OfferProject();
		$offer_project->selectByParams(array("OFFER_ID" => $reqId, "CATEGORY" => $category_project->getField("NAME")));
		if ($offer_project->rowCount > 0) 
		{
		?>
		<tr>
			<th colspan="7" style="text-align: left; padding-left: 5px; background-color: #8DCE63">
				<?=$category_project->getField("NAME")?>
			</th>
		</tr>
		<?php	
		$sub_total=0;
		while ($offer_project->nextRow()) 
		{
		?>
		<tr>
			<td style="text-align: center;"><?=$no?></td>
			<td style="text-align: left; padding-left: 5px"><?=$offer_project->getField("DESCRIPTION")?></td>
			<td style="text-align: center;"><?=$offer_project->getField("QUANTITY")?></td>
			<td style="text-align: center;"><?=$offer_project->getField("DURATION")?></td>
			<td style="text-align: center;"><?=$offer_project->getField("UOM")?></td>
			<td style="text-align: right; padding-right: 5px"><?=currencyToPage2($offer_project->getField("PRICE"))?></td>
			<td style="text-align: right; padding-right: 5px"><?=currencyToPage2($offer_project->getField("TOTAL"))?></td>
		</tr>

		<?php
		$no++;
		$sub_total+=$offer_project->getField("TOTAL");
		$grand_total+=$offer_project->getField("TOTAL");
		}
		?>
		<tr>
			<td colspan="6" style="text-align: center;">Sub Total</td>
			<td style="text-align: right; padding-right: 5px; background-color: #FFFD56"><?=currencyToPage2($sub_total)?></td>
		</tr>

		<?php
		}
		}
		?>
		<tr>
			<td colspan="6" style="text-align: center;">TOTAL</td>
			<td style="text-align: right; padding-right: 5px; background-color: #00AC5B"><?=currencyToPage2($grand_total)?></td>
		</tr>
	</tbody>


</table>

<br>
<b>Terms and conditions:</b>
<br>
<?=$reqTermAndCondition?>
<!-- <table style="width: 100%">
	<tr>
		<td style="vertical-align: top; width: 4%">1)</td>
		<td>Above quotation is made based on various mail trailing’s received pertaining to subject requirement.</td>
	</tr>
	<tr>
		<td style="vertical-align: top">2)</td>
		<td>The working hours : 1 (one) shift of 06:00 - 18:00 (twelve hours daily)</td>
	</tr>
	<tr>
		<td style="vertical-align: top">3)</td>
		<td>If there any delay caused by any party except PT Aquamarine Divindo Inspection after manpower and equipment on board, standby rate is applicable as per table rate.</td>
	</tr>
	<tr>
		<td style="vertical-align: top">4)</td>
		<td>If there any additional days of work (more than 7 days), standby rate is applicable as per table rate.</td>
	</tr>
	<tr>
		<td style="vertical-align: top">5)</td>
		<td>If there any additional work hours (more than 12 hours in a day), overtime rate is applicable as per table rate.</td>
	</tr>
	<tr>
		<td style="vertical-align: top">6)</td>
		<td>If there any additional cleaning work and additional scope, standby rate is applicable as per table rate.</td>
	</tr>
	<tr>
		<td style="vertical-align: top">7)</td>
		<td>Personnel meals, Lodging and Laundry during the work will be provided by PT Aquamarine Divindo Inspection</td>
	</tr>
	<tr>
		<td style="vertical-align: top">8)</td>
		<td>Vessel, Agency and Stevedoring will be arrange by PT Aquamarine Divindo Inspection.</td>
	</tr>
	<tr>
		<td style="vertical-align: top">9)</td>
		<td>If there any FEP or HSE Passport requirement will be arranged by Client</td>
	</tr>
	<tr>
		<td style="vertical-align: top">10)</td>
		<td>FEP EMCL should be ready before work execution</td>
	</tr>
	<tr>
		<td style="vertical-align: top">11)</td>
		<td>Working Permit from Dirjen Hubla Jakarta, DIVNAV Jakarta & Local Harbour Master KUPP Brondong will be arranged by  Contractor (should be received before personnel quarantine)</td>
	</tr>
	<tr>
		<td style="vertical-align: top">12)</td>
		<td>PO should be released 14 days before work execution. </td>
	</tr>
	<tr>
		<td style="vertical-align: top">13)</td>
		<td>If there any material supply by Contractor, Handling fee 15% will be add to final invoice</td>
	</tr>
	<tr>
		<td style="vertical-align: top">14)</td>
		<td>Payment terms:<br>•&nbsp;&nbsp;&nbsp;30 Days After work finished</td>
	</tr>
	<tr>
		<td style="vertical-align: top">15)</td>
		<td>Validity of the quotation is 30 day.</td>
	</tr>
	<tr>
		<td style="vertical-align: top">16)</td>
		<td>Others not stipulated above are to be mutually agreed upon by EMCL and PT. Aquamarine Divindo Inspection.</td>
	</tr>
</table> -->


<p style="text-align: justify;">
Shall there be further clarifications needed, do feel free to contact us.
</p>

<br>

Faithfully yours,
<br>
<b>PT AQUAMARINE DIVINDO INSPECTION</b>
<br>
<img style="position: absolute; width: 150px" src="<?=$reqTTDLink?>">
<p style="position: absolute; left: 0px; margin-top: -15px">
<u><?= ucfirst( strtolower($reqMaker)) ?></u><br>
<em>Marketing Department</em>
</p>
<?php 
/*
<div style="position: absolute; top: -10px; width: 120px; height: 120px">
<img style="width: 100%; height: 100%" src="<?=$reqTTDLink?>">	
</div>
<br>
<u><?= ucfirst( strtolower($reqMaker)) ?></u><br>
<b style="position: absolute; top: -10px"><em>Marketing Department</em></b>
*/
?>
</div>

</div>