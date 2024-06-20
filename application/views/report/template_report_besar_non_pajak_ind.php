<base href="<?= base_url(); ?>" />
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get('reqId');
$reqMode = $this->input->get('reqMode');

$this->load->model("Invoice");
$this->load->model("InvoiceDetail");

$invoice = new Invoice();
$statement = " AND A.INVOICE_ID = " . $reqId;
$invoice->selectByParamsMonitoring(array(), -1, -1, $statement);
$invoice->firstRow();

$reqInvoiceId       = $invoice->getField("INVOICE_ID");
$reqInvoiceNumber   = $invoice->getField("INVOICE_NUMBER");
$reqCompanyId       = $invoice->getField("COMPANY_ID");
$reqInvoiceDate     = $invoice->getField("INVOICE_DATE");
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
$reqInvoiceDateNew =  date('F jS, Y', strtotime($reqInvoiceDate));
$reqDP              = $invoice->getField("DP");

 $reqPPh             = $invoice->getField("PPH");	
 $reqPphPercent     = $invoice->getField("PPHPERCENT");    


?>
<h1 style="text-align: center;font-size: 16px;font-family:Arial"> <u>FAKTUR / <b>INVOICE</b> </u> </h1>
<div style="padding-left:40.6px;padding-right:  40.6px">
<table style="width: 100%;border-collapse: 1px solid black;padding: 10px;font-size: 12px;font-family: Arial" border="1" >
	<tr>
		<td style="padding: 10px;width: 50%">
			<p>INVOICE NO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>:</span><b> <?=$reqInvoiceNumber?></b> </p>
			<p>TAX INVOICE &nbsp;&nbsp;<span>:</span> <b><?=$reqInvoiceTax?>  </b> </p>
			<p>PO DATE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:<b> <?= $reqInvoiceDateNew ?> </b> </p>
			<p>PO NUMBER &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:<b> <?=$reqInvoicePo?> </b> </p>
		</td>
		
		
		<td style="padding: 10px;width: 50%">
			<p>To &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?=$reqContactName?> </p>
			<p><b><?=$reqCompanyName?></b><br><?=$reqAddress?></p>
			<p>Telp &nbsp;&nbsp;&nbsp; : <?=$reqTelephone?> </p>
			<p>Fax &nbsp;&nbsp;&nbsp;&nbsp; : <?=$reqFaximile?> </p>
			<p>Email &nbsp; : <u><?=$reqEmail?></u> </p>
		</td>
	</tr>
	

</table>




<br>




<center>
	<div class="row">
		<div class="col">
			<table border="1" style="border-collapse: 1px solid black;text-align: center;width: 100%; font-size: 11px;font-family:Arial">
				<thead>
					<tr >
						<th style="width: 380px;background-color: #808080" > <span style="color: white">DESKRIPSI </span> / DESCRIPTION</th>
						<th colspan="2" style="background-color: #808080" rowspan="2"><span style="color: white">JUMLAH </span> / AMOUNT</th>

					</tr>
					<tr>
						<td rowspan="2"  style="border-bottom: none;border: none;border-left: 1px solid black"> </td>
						
					</tr>
				</thead>

				<tbody>
					<tr>

						<td> <em>Dollar <em> </td>
						<td> <em> IDR  </em></td>
					</tr>
					<?
					$invoice_detail = new InvoiceDetail();
					$invoice_detail->selectByParamsMonitoring(array("A.INVOICE_ID" => $reqId));
					$total_idr = 0;
					$total_usd = 0;
					$nooo=0;
					while ($invoice_detail->nextRow()) {
						if($nooo==0){

						}
						$amount_idr =	'';
						$amount_usd =	'';
						$currenct  =	$invoice_detail->getField('CURRENCY');
						if ($currenct == 1) {
							$amount_idr = currencyToPage2($invoice_detail->getField('AMOUNT'));
							$total_idr  = $total_idr + $invoice_detail->getField('AMOUNT');
						} else {
							$amount_usd = currencyToPage2($invoice_detail->getField('AMOUNT'));
							$total_usd  = $total_usd + $invoice_detail->getField('AMOUNT');
						}
					?>
						<tr>
							<td align="left" style="padding: 10px;border: none;border-left: 1px solid black;border-right: 1px solid black"><b style="font-size: 15px;"><?= $invoice_detail->getField('SERVICE_TYPE') ?></b><br>
								<ul style="padding-left: 40px">

									<!-- <li><?= $invoice_detail->getField('SERVICE_DATE') ?> </li>
									<li><?= $invoice_detail->getField('LOCATION') ?> </li>
									<li><?= $invoice_detail->getField('VESSEL') ?> </li> -->
									<li>Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= $invoice_detail->getField('SERVICE_DATE') ?> </li>
									<li>Location &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= $invoice_detail->getField('LOCATION') ?> </li>
									<li >Vessel &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= $invoice_detail->getField('VESSEL') ?> </li>
									<li >No Report &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= $reqNoReport ?> </li>
								</ul>
							</td>
							<td style="border: none;border-left: 1px solid black;border-right: 1px solid black;text-align: right;"><b> <?= $amount_usd ?></b> </td>
							<td style="border: none;border-left: 1px solid black;border-right: 1px solid black;text-align: right"><b> <?= $amount_idr ?></b></td>

						</tr>
					<?
					$nooo++;
					}
					?>
				</tbody>
				<tfoot>
					<?
					$total_ppn_idr = ($total_idr * $reqPpnPercent) / 100;
					$total_ppn_usd = ($total_usd * $reqPpnPercent) / 100;

					$total_pph_idr = ($total_idr * $reqPphPercent) / 100;
					$total_pph_usd = ($total_usd * $reqPphPercent) / 100;

					if($reqPPh==0 || $reqPPh==''){
						$total_pph_idr ='null';
						$total_pph_usd ='null';
						$reqPphPercent=0;
					}					

					// var_dump ($total_ppn_usd);exit;

					if ($total_ppn_usd == 0 )
					{
						$total_ppn_usd = 'null';
						$total_pph_usd = 'null';
						// $total_ppn_idr = 'null';
					}
					elseif ($total_ppn_idr == 0 ) {
						$total_ppn_idr = 'null';
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
						<td align="right"><strong> TOTAL </strong> </td>
						<td style="text-align: right"><strong><?= currencyToPage2($total_usd) ?></strong></td>
						<td style="text-align: right"><strong><?= currencyToPage2($total_idr) ?></strong></td>
					</tr>
					<tr>
						<td align="right"><strong> ADVANCE PAYMENT </strong></td>
						<td><strong></strong></td>
						<td style="text-align: right"><strong> - <?= currencyToPage2($reqDP) ?> </strong></td>
					</tr>
					<tr>
						<td align="right"><strong> PPN (<?= $reqPpnPercent ?>) % </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_ppn_usd) ?> </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_ppn_idr) ?> </strong></td>
					</tr>
					<tr>
						<td align="right"><strong> PPH-23 (<?= $reqPphPercent ?>) % </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_pph_usd) ?> </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_pph_idr) ?> </strong></td>
					</tr>
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
						<td><strong></strong></td>
						<td style="text-align: right"><strong> -<?= currencyToPage2($reqDP) ?> </strong></td>
					</tr>
					<tr>
						<td align="right"><strong> PPN (<?= $reqPpnPercent ?>) % </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_ppn_usd) ?> </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_ppn_idr) ?> </strong></td>
					</tr>
					<?
					}
					$total_idr_payable= $total_idr - $reqDP + $total_ppn_idr-$total_pph_idr;
					if ($total_usd == 0 )
					{
						$total_usd = 'null';	
					}
					elseif ($total_idr == 0 )
					{
						$total_idr = 'null';
					}		
					?>

					<tr>
						<td align="right"><strong> TOTAL PAYABLE </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_usd) ?> </strong></td>
						<td style="text-align: right"><strong> <?= currencyToPage2($total_idr_payable) ?> </strong></td>
					</tr>

					<tr>
					<td colspan="3" align="center"> <b> The Sum of </b>  : ## <?=terbilang($total_idr_payable)?> ## </td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</center>

<?
$tgl_skrng = date('d F');
$tgl_skrng_new =  date('F jS, Y', strtotime($tgl_skrng));

$year = date('Y');
?>

<table style="width: 100%;font-size: 11px;font-family:Arial;margin-left:-3px;">
	<tr>
		<td style="width: 50%;text-align: justify;font-size: 11px;" valign="top"><b> Syarat & Ketentuan Umum </b>/ <em> General Terms & Conditions :</em> <br><br>
			Tagihan atas Invoice ini telah kami anggap selesai bila kami telah menerima pembayaran sesuai dengan faktur ini / <em> This invoice is considered paid when we receive the payment as per stipulated within this Invoice </em><br><br>
			Pembayaran mengunakan cek. Bilyet Giro atau lainnya kami anggap telah selesai dibayarkan bila telah dapat kami uangkan / <em>Any other payment by cheque, bilyet giro or others shall be deemed as paid when we had collected same effective into our account  </em>
		</td>
		<td style="width: 50%;padding: 20px;padding-left: 70px;font-size: 11px" valign="top">Sidoarjo,  <?= $tgl_skrng_new ?> </strong><!-- <sup>th</sup> <strong> <?= $year ?> </strong> --><br><br><br><br><br><br>
			<b> <u>ISNAINI .R</u> <br> ACCOUNT MANAGER </b> </td>
		</tr>

	</table>

</div>
<br>
<br>
	<div>
		<table style="width: 100%;text-align: left;font-family:Arial;font-size: 11px;margin-left:40px;color: #979797">
		<tr>
			<td colspan="3">
				Bank Negara Indonesia ( Persero )
			</td>
			<td colspan="3">
				Bank Negara Indonesia ( Persero )
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
			<td> 6100099517 (IDR) </td>
			<td> Account Number</td>
			<td> :</td>
			<td> 6100112181 (USD)</td>
		</tr>
		<tr>
			<td> </td>
			<td> </td>
			<td> </td>
			<td> Swift Code</td>
			<td> :</td>
			<td> CENAIDJA</td>
		</tr>
	</table>
</div>