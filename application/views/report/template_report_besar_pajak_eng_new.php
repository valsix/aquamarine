<base href="<?= base_url(); ?>" />
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get('reqId');
$reqMode = $this->input->get('reqMode');
	$this->load->model("MasterCurrency");
$this->load->model("InvoiceNew");
$this->load->model("InvoiceDetail");
$this->load->model("Offer");

$invoice = new InvoiceNew();
$statement = " AND A.INVOICE_NEW_ID = " . $reqId;
$invoice->selectByParamsMonitoring(array(), -1, -1, $statement);
$arrData = $invoice->rowResult;
$arrData = $arrData[0];
$invoice->firstRow();

$reqInvoiceId       = $invoice->getField("INVOICE_NEW_ID");
$reqInvoiceNumber   = $invoice->getField("NOMER");
$reqCompanyId       = $invoice->getField("COMPANY_ID");
$reqInvoiceDate     = $invoice->getField("INVOICE_DATE");
$reqPoDate     		= $invoice->getField("PO_DATE");
$reqPpn             = $invoice->getField("PPN");
$reqCompanyName     = $invoice->getField("COMPANY_NAME");
$reqContactName     = $invoice->getField("CONTACT_PERSON");
$reqAddress         = $invoice->getField("ADDRESS");
$reqTelephone       = $invoice->getField("PHONE");
$reqFaximile        = $invoice->getField("FAX");
$reqEmail           = $invoice->getField("EMAIL");
$reqPpnPercent      = $invoice->getField("PPN_PERCENT");
$reqStatus          = $invoice->getField("STATUS");
$reqInvoicePo       = $invoice->getField("PO_NOMER");
$reqInvoiceTax      = $invoice->getField("TAX_INVOICE_NOMINAL");
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
 $reqRoNomer =  $invoice->getField("RO_NUMBER");    
 $reqRoDate 		= date('F jS, Y', strtotime($reqRoDate));


    $reqDescriptionProject =  $invoice->getField('DESKRIPSI');
       $totalAmount = ($arrData['amount']*$arrData['quantity']);
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
					<td valign="top">PO NUMBER</td>
					<td valign="top">:</td>
					<td valign="top"><b> <?=$reqInvoicePo?></b></td>
				</tr>
				<?if( $reqRoCheck==1){?>
				<tr>
					<td valign="top">RO DATE</td>
					<td valign="top">:</td>
					<td valign="top"><b> <?=$reqRoDate?></b></td>
				</tr>
				<tr>
					<td valign="top">RO NUMBER</td>
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
$currencyId = $arrData['currency'];
$master_currency = new MasterCurrency();
$statement = " AND CAST(A.MASTER_CURRENCY_ID AS VARCHAR) ='".$currencyId."'";
$master_currency->selectByParamsMonitoring(array(),-1,-1,$statement);

$master_currency->firstRow();
$reqGlobalCurrencyNama = $master_currency->getField('NAMA');
$reqCurFormat = $master_currency->getField('FORMAT');

if($currencyId=='1' || empty($currencyId)){
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
						<td   style="border-bottom: none;border: none;border-left: 1px solid black;padding: 10px" align="left"><b style="font-size: 14px;">
						 <!-- <?=strtoupper($reqSubject)?> -->
						  </b> </td>
							<td> <em><?=$reqGlobalCurrencyNama?> </em> </td>
						<td> <em> IDR  </em></td>
							
						
					</tr>
				</thead>
				<?
				$currency = $arrData['currency'];
				
				
				if($currency == '1') {$currency ='IDR'; }else{$currency = 'USD';}
				$val[$currency]['total']= currencyToPage2($totalAmount);
			    $val[$currency]['adv_payment']='-'. currencyToPage2($arrData['adv_payment']);
			    $nilai_ppn =0;
				$PPN = $arrData['ppn'];
				$PPN_PERCENT = $arrData['ppn_percen'];

				if($PPN==1){
					$nilai_ppn = ( $totalAmount * $PPN_PERCENT ) /100;
				}else { $PPN_PERCENT=0;}	
				 $val[$currency]['nilai_ppn']= currencyToPage2($nilai_ppn);
				 $PPH = $arrData['pph'];
				 $PPH_CURRENCY = $arrData['pph_currency'];
				 $nilai_pph=0;
				 if($PPH==1){
					$nilai_pph = ( $totalAmount * $PPH_CURRENCY ) /100;
				}else { $PPN_PERCENT=0;}	
				$val[$currency]['nilai_pph']= currencyToPage2($nilai_pph);

				$PPH_JENIS = $arrData['pph_jenis'];

				$total_payable = ( $totalAmount  + $nilai_ppn + $nilai_pph) - $arrData['adv_payment'];
				$val[$currency]['total_payable']= currencyToPage2($total_payable);

				$angka =round($total_payable,2);
				$arrData2 = explode('.',$angka);
				$reqNominal= $arrData2[0];
				$reqCent= $arrData2[1];

			
				

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
				<tbody>
					<tr>
						<td align="left" valign="top" style="padding: 10px;border-top: none;"><?=$reqDescriptionProject?>  <br> <br> <?=$arrData['note'];?>  </td>
						<td align="right"> <?=$val['USD']['total']?> </td>
						<td align="right">  <?=$val['IDR']['total']?> </td>
					</tr>
					<tr>
						<td align="right"> TOTAL  </td>
						<td align="right"> <?=$val['USD']['total']?> </td>
						<td align="right">  <?=$val['IDR']['total']?> </td>
					</tr>
					<tr>
						<td align="right"> ADVANCE PAYMENT  </td>
						<td align="right"> <?=$val['USD']['adv_payment']?> </td>
						<td align="right">  <?=$val['IDR']['adv_payment']?> </td>
					</tr>
					<tr>
						<td align="right"> PPN ( <?=$PPN_PERCENT?> ) %  </td>
						<td align="right"> <?=$val['USD']['nilai_ppn']?> </td>
						<td align="right">  <?=$val['IDR']['nilai_ppn']?> </td>
					</tr>
					<tr>
						<td align="right"> PPH <?=$PPH_JENIS?> (<?=$PPH_CURRENCY?>) %  </td>
						<td align="right"> <?=$val['USD']['nilai_pph']?> </td>
						<td align="right">  <?=$val['IDR']['nilai_pph']?> </td>
					</tr>
					<tr>
						<td align="right"> TOTAL PAYABLE  </td>
						<td align="right"> <?=$val['USD']['total_payable']?> </td>
						<td align="right">  <?=$val['IDR']['total_payable']?> </td>
					</tr>
					<tr>
						<td colspan="3" align="center"> <b> The Sum of :</b> ## <?=$reqTextNominal?> ##  </td>
					
					</tr>
				</tbody>
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