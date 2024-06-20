<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get("reqId");
$this->load->model("SoTeamNew");
$this->load->model("ServiceOrderNew");
$this->load->model("DokumenSertifikat");

$service_order = new ServiceOrderNew();
$statement = " AND A.SERVICE_ORDER_NEW_ID = " . $reqId;
$service_order->selectByParamsMonitoring(array(), -1, -1, $statement);
$service_order->firstRow();


$service_order->firstRow();
$reqSoId            = $service_order->getField("SO_ID");
$reqProjectName     = $service_order->getField("NO_PO");
$reqNoOrder         = $service_order->getField("NAMA_CODE_PROJECT");
$reqCompanyName     = $service_order->getField("COMPANY_NAME");
$reqVesselName      = $service_order->getField("VESSEL_NAME");
$reqVesselType      = $service_order->getField("VESSEL_TYPE");
$reqVesselClass     = $service_order->getField("VESSEL_CLASS");
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
$reqTTDNama         = $service_order->getField("TTD_NAMA");
$reqTTDJabatan      = $service_order->getField("TTD_JABATAN");
$reqTTDLink         = $service_order->getField("TTD_LINK");
$reqDateOWR         = $service_order->getField('DATE_OWR');

$reqObligationNew = str_replace('style="font-family: arial; font-size: 10pt;"', '', $reqObligation);
// var_dump ($reqObligation);exit;

$dom = new DOMDocument;
$dom->loadHTML($reqObligation);
foreach($dom->getElementsByTagName('li') as $node)
{
    $arrayObligation[] = $node->nodeValue;
}

?>
<!-- <style>
    td, th {
        font-size: 10px;
    }
</style> -->

<h1 style="font-size: 16px;text-align: center;font-family: Arial;"><u> <b> PROPOSED PERSONNEL LIST </b> </u><br>
    <!-- <small style="font-size: 13px;font-family: Arial;font-weight: normal;   "><em> <?=$reqNoOrder?></em></small> -->
</h1>



 <table style="font-size: 11px;font-family: Arial;border-collapse: 1px solid black;">
            <tr>
                <td style="width: 10%;"> &nbsp;</td>
                <td >No. of Tender/Work  </td>
                <td >&nbsp;:</td>
                <td style="padding-left: 5px;"><?=$reqProjectName?></td>
                 <td > &nbsp;</td>
            </tr>
              <tr>
                <td style="width: 10%;"> &nbsp;</td>
                <td >Title of Tender/Work </td>
                <td >&nbsp;:</td>
                <td style="padding-left: 5px;"><?=$reqNoOrder ?></td>
                 <td > &nbsp;</td>
            </tr>
             <tr>
                <td style="width: 10%;"> &nbsp;</td>
                <td >Company </td>
                <td >&nbsp;:</td>
                <td style="padding-left: 5px;"><?=$reqCompanyName ?></td>
                 <td > &nbsp;</td>
            </tr>
            <tr>
                <td style="width: 10%;"> &nbsp;</td>
                <td >Contractor </td>
                <td >&nbsp;:</td>
                <td style="padding-left: 5px;">PT Aquamarine Divindo Inspection (AMDI)</td>
                 <td > &nbsp;</td>
            </tr>
        </table>
        <br>

        <table border="1" style="width: 100%; font-size: 20px; border-collapse: 1px solid black;font-family: Arial">
           
                <tr>
                    <th style="width: 10%;background-color: #D9D9D9">No.</th>
                     <th style="width: 30%;background-color: #D9D9D9">Position / Qualification</th>
                    <th style="width: 40%;background-color: #D9D9D9">Personnel Name</th>
                    <th style="width: 40%;background-color: #D9D9D9">Certificate</th>
                    <th style="width: 40%;background-color: #D9D9D9">Remark</th>
                   
                </tr>
           
                <?
                 $so_team = new SoTeamNew();
                $so_team->selectByParamsMonitoring(array("A.SO_ID"=>$reqId));
                $arrTeam = $so_team->rowResult;
                $arrDokId = array_column( $arrTeam, 'document_id');
                $stringDokId = implode_to_string($arrDokId);

                $no=1;
                 
                while ( $so_team->nextRow()) {
                    $reqDokId = $so_team->getField("DOCUMENT_ID");
                   
                ?>
                <tr>
                    <td style="text-align: center;"><?=$no?></td>
                    <td style="padding-left: 5px;"><?=$so_team->getField("POSITION")?></td>
                    <td style="padding-left: 5px;"><?=$so_team->getField("NAMA_PERSONIL")?> </td>
                    <td style="padding-left: 5px;"><?=$so_team->getField("NAMA_SERTIFIKAT")?> </td>
                    <td style="padding-left: 5px;"><?=$so_team->getField("REMARK")?> </td>
                </tr>
                <?
                $no++;
                }
                ?>
               
          
        </table>
    

<br>


<div class="row">
    <div class="col">
        <table style="font-size: 10px; width: 100%;font-family: Arial">
            <tr>
                <td style="text-align: justify;"><b>Notice:</b><br> PT Aquamarine Divindo Inspection (“AMDI”) proposes required Personnel as per listed abovementioned, trust to be suitable for this tender/work purpose. AMDI
informs that the proposed personnel may not be available upon award, therefore AMDI will do its best endeavor to replace the personnel with other suitable for
this tender/work.</td>
            </tr>
        </table>
    </div>
</div>

<br>
