<base href="<?= base_url(); ?>" />
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get('reqId');
$reqMode = $this->input->get('reqMode');

$this->load->model("Invoice");
$this->load->model("InvoiceDetail");
$this->load->model("Offer");

$invoice = new Invoice();
$statement = " AND A.INVOICE_ID = " . $reqId;
$invoice->selectByParamsMonitoring(array(), -1, -1, $statement);
$invoice->firstRow();

$reqInvoiceId       = $invoice->getField("INVOICE_ID");
$reqInvoiceNumber   = $invoice->getField("INVOICE_NUMBER");
$reqCompanyId       = $invoice->getField("COMPANY_ID");
$reqInvoiceDate     = $invoice->getField("INVOICE_DATE");
$reqPoDate     		= $invoice->getField("PO_DATE");
$reqPpn             = $invoice->getField("PPN");
$reqCompanyName     = $invoice->getField("COMPANY_NAME");
$reqContactName     = $invoice->getField("CONTACT_NAME");
$reqAddress         = $invoice->getField("ADDRESS");
$reqTelephone       = $invoice->getField("TELEPHONE");
$reqFaximile        = $invoice->getField("FAXIMILE");
$reqEmail           = $invoice->getField("EMAIL");
$reqPpnPercent      = $invoice->getField("PPN_PERCENT");
$reqStatus          = $invoice->getField("STATUS");
$reqInvoicePo       = $invoice->getField("INVOICE_PO");
$reqInvoiceTax      = $invoice->getField("INVOICE_TAX");
$reqTerms           = $invoice->getField("TERMS");
$reqNoKontrak       = $invoice->getField("NO_KONTRAK");
$reqNoReport        = $invoice->getField("NO_REPORT");
$reqPoDateNew 		= date('F jS, Y', strtotime($reqPoDate));
$reqDP              = $invoice->getField("DP");
$reqOfferID         = $invoice->getField("OFFER_ID");
$reqSubject         = $invoice->getField("SUBJECT");
$reqTotalWord       = $invoice->getField("TOTAL_WORD");
$reqManualPpn       = $invoice->getField("MANUAL_PPN");
$reqNominalManual   = $invoice->getField("NOMINAL_MANUAL");

 $reqPPh             = $invoice->getField("PPH");	
 $reqPphPercent     = $invoice->getField("PPHPERCENT");

 $reqJenisPPh   = $invoice->getField("JENIS_PPH");    
 $reqJenisPPh= $reqJenisPPh?$reqJenisPPh:'23';
 $reqRemarkInvoiceDetail =  $invoice->getField("REMARK");    
 $reqRoCheck =  $invoice->getField("RO_CHECK");    
 $reqRoDate =  $invoice->getField("RO_DATE");    
 $reqRoNomer =  $invoice->getField("RO_NOMER");    
 $reqRoDate 		= date('F jS, Y', strtotime($reqRoDate));
?>
<h1 style="text-align: center;font-size: 16px;font-family:Arial"> <u>FAKTUR / <b>INVOICE</b> </u> </h1>
<div style="padding-left:40.6px;padding-right:  40.6px">
<table style="width: 100%;border-collapse: 1px solid black;padding: 10px;font-size: 14px;font-family:Arial" border="1" >
	<tr>
		<td style="padding: 14px;width: 50%; vertical-align: top">
			<table style="font-size: 14px;font-family:Arial">
				<tr>
					<td width="36%" valign="top">INVOICE NO.</td>
					<td width="4%" valign="top">:</td>
					<td width="60%" valign="top"><b> <?=$reqInvoiceNumber?></b></td>
				</tr>
				<tr>
					<td valign="top">TAX INVOICE</td>
					<td valign="top">:</td>
					<td valign="top"><b> <?=$reqInvoiceTax?></b></td>
				</tr>
				<tr>
					<td valign="top">PO DATE</td>
					<td valign="top">:</td>
					<td valign="top"><b> <?=$reqPoDateNew?></b></td>
				</tr>
				<tr>
					<td valign="top">PO/CONTRACT NO</td>
					<td valign="top">:</td>
					<td valign="top"><b> <?=$reqInvoicePo?></b></td>
				</tr>
				<tr>
					<td valign="top">VENDOR ID</td>
					<td valign="top">:</td>
					<td valign="top"><b> <?=$reqRoNomer?></b></td>
				</tr>
				<?if( $reqRoCheck==1){?>
				<tr>
					<td valign="top">RO DATE</td>
					<td valign="top">:</td>
					<td valign="top"><b> <?=$reqRoDate?></b></td>
				</tr>
				<tr>
					<td valign="top">RO NO.</td>
					<td valign="top">:</td>
					<td valign="top"><b> <?=$reqRoNomer?></b></td>
				</tr>
				<?}?>
			</table>
		</td>
		
		
		<td style="padding: 10px;width: 50%; border: 1px solid black">
			<table style="font-size: 14px;font-family:Arial">
				<tr>
					<td width="15%" valign="top">To</td>
					<td width="4%" valign="top">:</td>
					<td width="81%" valign="top"><?=$reqContactName?></td>
				</tr>
				<tr>
					<td colspan="3" valign="top">
						<b><?=$reqCompanyName?></b><br><?=$reqAddress?>
					</td>
				</tr>
				<tr>
					<td valign="top">Telp.</td>
					<td valign="top">:</td>
					<td valign="top"><?=$reqTelephone?></td>
				</tr>
				<tr>
					<td valign="top">Fax.</td>
					<td valign="top">:</td>
					<td valign="top"><?=$reqFaximile?></td>
				</tr>
				<tr>
					<td valign="top">Email</td>
					<td valign="top">:</td>
					<td valign="top"><u><?=$reqEmail?></u></td>
				</tr>
			</table>
		</td>
	</tr>
	

</table>




<br>

<?
$this->load->model("MasterCurrency");
$master_currency = new MasterCurrency();

$invoice_detail = new InvoiceDetail();
$invoice_detail->selectByParamsMonitoring(array("A.INVOICE_ID" => $reqId, "TYPE_PROJECT" => "Project Besar"));
$invoice_detail->firstRow();
$reqGlobalCurrency = $invoice_detail->getField("CURRENCY");
$statement = " AND CAST(A.MASTER_CURRENCY_ID AS VARCHAR) ='".$reqGlobalCurrency."'";
$master_currency->selectByParamsMonitoring(array(),-1,-1,$statement);
$master_currency->firstRow();
$reqCurFormat = $master_currency->getField('FORMAT');
$reqGlobalCurrencyNama = $master_currency->getField('NAMA');

if($reqGlobalCurrency=='1' || empty($reqGlobalCurrency)){
  $reqGlobalCurrencyNama = 'USD';
}
?>


<center>
	<div class="row">
		<div class="col">
			<table border="1" style="border-collapse: 1px solid black;text-align: center;width: 100%; font-size: 14px;font-family:Arial">
				<thead>
					<tr >
						<th style="width: 60%;background-color: #808080" >DESKRIPSI  /  <span style="color: white">DESCRIPTION</span></th>
						<th colspan="2" style="width: 40%;background-color: #808080">JUMLAH  / <span style="color: white">AMOUNT </span></th>

					</tr>
					<tr>
						<td rowspan="2"  style="border-bottom: none;border: none;border-left: 1px solid black;padding: 10px" align="left"><b style="font-size: 14px;">
						 <!-- <?=strtoupper($reqSubject)?> -->
						  <b> </td>
							<td> <em><?=$reqGlobalCurrencyNama?> <em> </td>
						<td> <em> IDR  </em></td>
							
						
					</tr>
				</thead>

				<tbody>
					<tr>

						<td style="border-bottom: none;border: none;border-left: 1px solid black;padding: 10px;border-right: 1px solid black" ></td>
						<td style="border-bottom: none;border: none;border-left: 1px solid black;padding: 10px;border-right: 1px solid black" ></td>
					</tr>
					<?
					$invoice_detail = new InvoiceDetail();
					$invoice_detail->selectByParamsMonitoring(array("A.INVOICE_ID" => $reqId, "TYPE_PROJECT" => "Project Besar"));
					$total_idr = 0;
					$total_usd = 0;
					$nooo=0;
					$globalCurrency = '';
					while ($invoice_detail->nextRow()) {
						if($nooo==0){

						}
						$amount_idr =	'';
						$amount_usd =	'';
						$currenct  =	$invoice_detail->getField('CURRENCY');
						$reqRemark  =	$invoice_detail->getField('REMARK');
						$reqRemark = $reqRemark ?'<br>NOTE<br>'.$reqRemark :'';

						if ($currenct == 1) {
							// $jml_amount_idr = $invoice_detail->getField('AMOUNT') * $invoice_detail->getField('QUANTITYITEM');
							$jml_amount_idr= $invoice_detail->getField('AMOUNT_NILAI');
							$amount_idr = currencyToPage2($jml_amount_idr);
							$total_idr  = $total_idr + $jml_amount_idr;
						} else {
							// $jml_amount_usd = $invoice_detail->getField('AMOUNT') * $invoice_detail->getField('QUANTITYITEM');
							$jml_amount_usd = $invoice_detail->getField('AMOUNT_NILAI') ;
							$amount_usd = currencyToPage2($jml_amount_usd);
							$total_usd  = $total_usd + $jml_amount_usd;
						}
					?>
						<tr>
							<td align="left" style="padding: 10px;border: none;border-left: 1px solid black;border-right: 1px solid black" valign="top">
								
								<?=$invoice_detail->getField('DESCRIPTION') ?>
								<!-- <br>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?=($currenct == 1 ? 'IDR' : 'USD')?> <?= currencyToPage2($invoice_detail->getField('AMOUNT')) ?> X <?=$invoice_detail->getField('QUANTITYITEM')?> -->
								
							</td>
							<td style="border: none;border-left: 1px solid black;border-right: 1px solid black;text-align: right;padding-top: 10px;" valign="top"> <?= $amount_usd ?> </td>
							<td style="border: none;border-left: 1px solid black;border-right: 1px solid black;text-align: right;padding-top: 10px;" valign="top"> <?= $amount_idr ?></td>

						</tr>
					<?
					$nooo++;
					}
					?>
					<tr>
							<td align="left" style="padding: 10px;border: none;border-left: 1px solid black;border-right: 1px solid black" valign="bottom">
								<?=$reqRemarkInvoiceDetail?>
							
								
							</td>
							<td style="border: none;border-left: 1px solid black;border-right: 1px solid black;text-align: right;padding-top: 10px;" valign="top">  </td>
							<td style="border: none;border-left: 1px solid black;border-right: 1px solid black;text-align: right;padding-top: 10px;" valign="top"> </td>

						</tr>
				</tbody>
				<tfoot>
					<?
					if($reqPpn ==0){$reqPpnPercent=0;}
					if($reqPPh ==0){$reqPphPercent=0;}
					$total_ppn_idr = ($total_idr * $reqPpnPercent) / 100;
					$total_ppn_usd = ($total_usd * $reqPpnPercent) / 100;

					$total_pph_idr = ($total_idr * $reqPphPercent) / 100;
					$total_pph_usd = ($total_usd * $reqPphPercent) / 100;
					// var_dump ($total_ppn_usd);exit;

					if ($total_ppn_usd == 0 )
					{
						$total_ppn_usd = 'null';
						//$total_pph_usd = 'null';
						// $total_ppn_idr = 'null';
					}
					elseif ($total_ppn_idr == 0 ) {
						$total_ppn_idr = 'null';
					}

					if($total_pph_idr==0){
						$total_pph_idr='null';
					}else if($total_pph_usd==0){
						$total_pph_usd='null';
					}

					if($reqManualPpn=='Ya'){
 							if($total_ppn_usd !='null'){
								$total_ppn_usd = $reqNominalManual;
							}
							if($total_ppn_idr !='null'){
								$total_ppn_idr = $reqNominalManual;
							}	
					}
					if($reqPPh==0 || $reqPPh==''){
						$total_pph_idr ='null';
						$total_pph_usd ='null';
						$reqPphPercent=0;
					}			



					$total_sum_idr='null';
					$total_sum_usd='null';
					if($reqMode=='ppn'){
						$total_sum_idr = $total_idr - $total_ppn_idr;
						$total_sum_usd = $total_usd - $total_ppn_usd;
						// var_dump($total_sum_usd);exit;
						if ($total_sum_usd == 0 || $total_usd == 0 )
						{
						  $total_sum_usd = 'null';
						  $total_usd = 'null';	
						}
						elseif ($total_sum_idr == 0 || $total_idr == 0 )
						{
						   $total_sum_idr = 'null';
						   $total_idr = 'null';
						}
					?>
					<tr>
						<?

						?>

						<td align="right"><strong> TOTAL </strong> </td>
						<td style="text-align: right"><strong><?= currencyToPage2($total_usd) ?></strong></td>
						<td style="text-align: right"><strong><?= currencyToPage2($total_idr) ?></strong></td>
					</tr>
					<tr>
						<td align="right"><strong> ADVANCE PAYMENT </strong></td>
						<?php
						if($total_usd == 'null')
						{
						?>
						<td><strong></strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($reqDP) ?> </strong></td>
						<?php
						}
						else
						{
						?>
						<td style="text-align: right"><strong> -<?= currencyToPage2($reqDP) ?> </strong></td>
						<td><strong></strong></td>
						<?php
						}
						?>
					</tr>
					<?if($reqPpn==1){?>
					
					<tr>
						<td align="right"><strong> PPN (<?= $reqPpnPercent ?>) % </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_ppn_usd) ?> </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_ppn_idr) ?> </strong></td>
					</tr>
					<?}?>
					<?if($reqPPh==1){?>
					<tr>
						<td align="right"><strong> PPH-<?=$reqJenisPPh?> (<?= $reqPphPercent ?>) % </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_pph_usd) ?> </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_pph_idr) ?> </strong></td>
					</tr>
					<?}?>
					<?
					}
					else
					{
						$reqPpnPercent = 0;
						$total_ppn_idr = '0';
						$total_ppn_usd = 'null';
						$total_sum_idr = $total_idr ;
						$total_sum_usd = $total_usd ;
						if ($total_sum_usd == 0 || $total_usd == 0 )
						{
						  $total_sum_usd = 'null';
						  $total_usd = 'null';	
						}
						elseif ($total_sum_idr == 0 || $total_idr == 0 )
						{
						   $total_sum_idr = 'null';
						   $total_idr = 'null';
						}
					?>
					<tr>
						<td align="right"><strong> TOTAL </strong> </td>
						<td style="text-align: right"><strong><?= currencyToPage2($total_usd) ?></strong></td>
						<td style="text-align: right"><strong><?= currencyToPage2($total_idr) ?></strong></td>
					</tr>
					<tr>
						<td align="right"><strong> ADVANCE PAYMENT </strong></td>
						<?php
						if($total_usd == 'null')
						{
						?>
						<td><strong></strong></td>
						<td style="text-align: right"><strong> -<?= currencyToPage2($reqDP) ?> </strong></td>
						<?php
						}
						else
						{
						?>
						<td style="text-align: right"><strong> -<?= currencyToPage2($reqDP) ?> </strong></td>
						<td><strong></strong></td>
						<?php
						}
						?>
					</tr>
					<tr>
						<td align="right"><strong> PPN (<?= $reqPpnPercent ?>) % </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_ppn_usd) ?> </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_ppn_idr) ?> </strong></td>
					</tr>
					<?
					}
					if ($total_usd == 0 )
					{
						$total_usd = 'null';	
					}
					elseif ($total_idr == 0 )
					{
						$total_idr = 'null';
					}	

					
					if($total_idr == 'null'){
						$total_idr_payable = 'null';
					} else {
						$total_idr_payable= $total_idr - $reqDP + $total_ppn_idr -$total_pph_idr ;	
					}
					if($total_usd == 'null'){
						$total_usd_payable = 'null';
					} else {
						$total_usd_payable= $total_usd - $reqDP + $total_ppn_usd -$total_pph_usd;	
					}	
					?>


					<tr>
						<td align="right"><strong> TOTAL PAYABLE </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_usd_payable) ?> </strong></td>
						<td style="text-align: right"><strong>

						 <?= currencyToPage2($total_idr_payable) ?> </strong></td>
					</tr>
					<?php
					if($total_idr_payable != 'null')
					{
						// $rupiah =' Rupiah';
						$total_kata = $total_idr_payable;
					}
					else
					{
						// $rupiah =' Dollar';
						$total_kata = $total_usd_payable;	
					}
					$angka =round($total_kata,2);
					$arrData = explode('.',$angka);
					$reqNominal= $arrData[0];
					$reqCent= $arrData[1];

					$reqTextNominal=kekata_eng($reqNominal).' '.$reqCurFormat;
					if(!empty($reqCent)){
						if(strlen($reqCent)==1){
							$reqCent .='0';
						}
						$reqTextNominal .=' and '.kekata_eng($reqCent).' Cent';
					}else{
						$reqTextNominal .=' Only ';
					}
					?>
					<tr>
					<td colspan="3" align="center"> <b> The Sum of :</b> ## <?=$reqTextNominal?> ##  </td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</center>
<br>
<!-- <div style="height: 80px"></div> -->
<div style="position: absolute; bottom: 160px; width: 100%;">
	<table style="width: 100%;text-align: left;font-family:Arial;font-size: 11px;margin-left:0px;margin-right:0px;color: black">
		<tr>
			<!-- <td colspan="3">
				Bank Negara Indonesia ( Persero )
			</td>
			<td colspan="3">
				Bank Negara Indonesia ( Persero )
			</td> -->
			<td colspan="3">
				Bank Negara Indonesia (Persero)
			</td>
			<td colspan="3">
				Bank Negara Indonesia (Persero)
			</td>

		</tr>
		<tr>
			<td colspan="3">
				Branch 255 Graha Pangeran Surabaya
			</td>
			<td colspan="3">
				Branch 255 Graha Pangeran Surabaya
			</td>
		</tr>
		<tr>
			<td> Name</td>
			<td> :</td>
			<td> PT. AQUAMARINE DIVINDO INSPECTION</td>
			<td> Name</td>
			<td> :</td>
			<td> PT. AQUAMARINE DIVINDO INSPECTION</td>
		</tr>
		<tr>
			<td> Account Number</td>
			<td> :</td>
			<td> 777 5555 778 (IDR) </td>
			<td> Account Number</td>
			<td> :</td>
			<td> 777 5555 881 (USD)</td>
		</tr>
		
	</table>
</div>
<br>
<?
$tgl_skrng_new =  date('F jS, Y', strtotime($reqInvoiceDate));

$year = date('Y');
				?>
<table style="width: 100%;font-size: 12px;font-family:Arial;margin-left:-3px;">
	<tr>
		<td style="width: 55%;text-align: justify;font-size: 11.5px" valign="top"><b> Syarat & Ketentuan Umum </b>/ <em> General Terms & Conditions :</em> <br><br>
			Tagihan atas Invoice ini telah kami anggap selesai bila kami telah menerima pembayaran sesuai dengan faktur ini. / <em> This invoice is considered paid when we receive the payment as per stipulated within this Invoice. </em><br><br>
			Pembayaran mengunakan cek. Bilyet giro atau lainnya kami anggap telah selesai dibayarkan bila telah dapat kami uangkan / <em>Any other payment by cheque, bilyet giro or others shall be deemed as paid when we had collected same effective into our account.  </em>
			<br>
			<br>
			<br>
		</td>
		<td style="width: 45%;padding: 20px;padding-left: 70px;font-size: 12px" valign="top">Sidoarjo,  <?= $tgl_skrng_new ?> </strong><!-- <sup>th</sup> <strong> <?= $year ?> </strong> -->
		<br><br><br><br><br><br>
		<br>
		<br>
		<b> <u>ISNAINI R.</u></b><br> Accounting </td>
	</tr>
	
</table>
<!-- <br>
 -->
</div>


</div>