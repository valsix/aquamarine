
<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");


include_once("libraries/MPDF60/mpdf.php");

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

$addStr ="&reqCariCompanyName=".$this->input->get("reqCariCompanyName");
$addStr .="&reqTypeOfService=".$this->input->get("reqTypeOfService");
$addStr .="&reqCariTypeofQualification=".$this->input->get("reqCariTypeofQualification");



/* END VALIDASI */
/*$mpdf = new mPDF('c','LEGAL',0,'',2,2,2,2,2,2,'L');*/
//$mpdf = new mPDF('c','LEGAL',0,'',15,15,16,16,9,9, 'L');
$mpdf = new mPDF('c', 'A4');
$mpdf->AddPage(
	'L', // L - landscape, P - portrait
	'',
	'',
	'',
	'',
	10, // margin_left
	10, // margin right
	36, // margin top
	48, // margin bottom
	9, // margin header
	9
);
//$mpdf=new mPDF('c','A4'); 
//$mpdf=new mPDF('utf-8', array(297,420));

$mpdf->mirroMargins = true;

$mpdf->SetDisplayMode('fullpage');
$mpdf->SetHTMLHeader('
	<div>
	<table style="width: 100%">
	<tr>
	<td >
	<div style="text-align: center;">
	<img src="images/logo_baru.png" style="height: 70px;width: 100%">
	</div>
	</td>

	</tr>
	</table>


	</div>', '', TRUE);

$mpdf->SetHTMLFooter('
	<p style="text-align:right"><em>Page {PAGENO} of {nb} </em></p>
<div>
	<table style="width: 100%">
	<tr>
	<td >
	<div style="text-align: center;">
	<img src="images/footer-min.png" style="height: 120px;width: 100%">
	</div>
	</td>

	</tr>
	</table>


	</div>');

$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0;    // 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
//$stylesheet = file_get_contents('css/invoice-kwitansi.css');
//$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

// LOAD a stylesheet
$stylesheet = file_get_contents('css/gaya_laporan.css');
$mpdf->WriteHTML($stylesheet, 1);    // The parameter 1 tells that this is css/style only and no body/html/text

//$html = file_get_contents("http://rais.teluklamong.co.id/report/index/berita_acara/?reqId=".$reqId);

$html = file_get_contents($this->config->item('base_report') . "report/index/list_personil/?reqId=" . $reqId.$addStr);


$mpdf->WriteHTML($html, 2);

$mpdf->Output('ba_opening.pdf', 'I');
exit;
//==============================================================
//==============================================================
//==============================================================
