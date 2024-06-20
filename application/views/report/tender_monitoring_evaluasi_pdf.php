<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");


include_once("libraries/MPDF60/mpdf.php");
$this->load->model("MasterTenderPeriode");
$this->load->model("MasterTenerMenus");
$reqId = $this->input->get("reqId");
$reqEvalusiId = $this->input->get("reqEvalusiId");
$reqColomn =$this->input->get("reqColomn");
$reqPeriode='2021';
$rules = new MasterTenderPeriode();
$rules->selectByParamsMonitoring(array("CAST(A.TAHUN AS VARCHAR)" => $reqId));
$rules->firstRow();
$last = $rules->getField("LAST_UPDATE");
$tahun = $rules->getField("TAHUN");


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

$reqColomns = explode(',', $reqColomn);
$index=0;
$arrId = array();
for($i=0;$i<count($defauls_colomn);$i++){
    if(in_array($defauls_colomn[$i], $reqColomns)){
     array_push($arrId, $index);
    }
  $index++; 
}
$reqColomnsId = implode(',', $arrId);
/* END VALIDASI */
/*$mpdf = new mPDF('c','LEGAL',0,'',2,2,2,2,2,2,'L');*/
//$mpdf = new mPDF('c','LEGAL',0,'',15,15,16,16,9,9, 'L');
$mpdf = new mPDF('c', 'LEGAL');
$mpdf->AddPage(
    'L', // L - landscape, P - portrait
    '',
    '',
    '',
    '',
    10, // margin_left
    10, // margin right
    28, // margin top
    10, // margin bottom
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
    <img src="images/logo_baru.png" style="height: 70px;width: 60%">
    </div>
    </td>
    <td style="width:10%">
     &nbsp;
    </td>
    <td style="width:10%;border:1px solid black" align="center" >
    UPDATE
    </td>
    <td style="width:10%;border:1px solid black" align="center">
    '.$last.'
    </td>
    <td style="width:10%;border:1px solid black;background:#BDD7EE;font-weight:bold;font-size:20px" align="center">
    '.$tahun.'
    </td>
    </tr>
    </table>


    </div>', '', TRUE);



$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
//$stylesheet = file_get_contents('css/invoice-kwitansi.css');
//$mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this is css/style only and no body/html/text

// LOAD a stylesheet
$stylesheet = file_get_contents('css/gaya_laporan.css');
$mpdf->WriteHTML($stylesheet, 1);   // The parameter 1 tells that this is css/style only and no body/html/text

//$html = file_get_contents("http://rais.teluklamong.co.id/report/index/berita_acara/?reqId=".$reqId);
// $reqIds = "LAST_UPDATE,INDEX,NAMA_PSC,TITLE,TENDER_NO";
$reqColomn='';
// echo $reqColomnsId;exit;
$html = file_get_contents($this->config->item('base_report') . "report/index/tender_monitoring_evaluasi/?reqId=". $reqId."&reqColomn=".$reqColomnsId."&reqEvalusiId=".$reqEvalusiId);
 // echo $this->config->item('base_report') . "report/index/tender_monitoring_evaluasi/?reqId=". $reqId."&reqColomn=".$reqColomnsId."&reqEvalusiId=".$reqEvalusiId;exit;

$mpdf->WriteHTML($html, 2);

$mpdf->Output('monitoring_evaluasi.pdf', 'I');
exit;
//==============================================================
//==============================================================
//==============================================================
