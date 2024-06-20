<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get("reqId");
$this->load->model("SoTeam");
$this->load->model("Service_order");
$service_order = new Service_order();
$statement = " AND A.SO_ID = " . $reqId;
$service_order->selectByParamsMonitoring(array(), -1, -1, $statement);
$service_order->firstRow();


$service_order->firstRow();
$reqSoId            = $service_order->getField("SO_ID");
$reqProjectName     = $service_order->getField("PROJECT_NAME");
$reqNoOrder         = $service_order->getField("NO_ORDER");
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

<h1 style="font-size: 16px;text-align: center;font-family: Arial;"><u> <b> OPERATIONAL WORK REQUEST (OWR) </b> </u><br>
    <small style="font-size: 13px;font-family: Arial;font-weight: normal;   "><em> <?=$reqNoOrder?></em></small>
</h1>

<div style="padding-left:50px;padding-right: 50px">

<div style="font-size: 13px;font-family: Arial; margin-bottom: 4px; "> Instructed to : </div>

<div class="row">
    <div class="col">
        <table border="1" style="width: 100%; font-size: 13px; border-collapse: 1px solid black;font-family: Arial">
            <thead>
                <tr>
                    <th style="width: 10%;background-color: #D9D9D9">No.</th>
                    <th style="width: 40%;background-color: #D9D9D9">Name</th>
                    <th style="width: 30%;background-color: #D9D9D9">Position</th>
                </tr>
            </thead>

            <tbody>
                <?
                $so_team = new SoTeam();
                $so_team->selectByParamsMonitoringTeam(array("A.SO_ID"=>$reqId));
                $no=1;
                while ( $so_team->nextRow()) {
                
                ?>
                <tr>
                    <td style="text-align: center;"><?=$no?></td>
                    <td style="padding-left: 5px;"><?=$so_team->getField("NAME")?></td>
                    <td style="padding-left: 5px;"><?=$so_team->getField("JENIS")?> </td>
                </tr>
                <?
                $no++;
                }
                ?>
               
            </tbody>
        </table>
    </div>
</div>

<br>

<div class="row">
    <div class="col">
        <table style="font-size: 13px;font-family: Arial">
            <tr>
                <td style="width: 25%;">Name of Client</td>
                <td style="width: 3%;">:</td>
                <td style="width: 70%;padding-left: 5px;"><?= $reqCompanyName ?></td>
            </tr>
            <tr>
                <td>Name of Vessel</td>
                <td>:</td>
                <td style="padding-left: 5px;"><?= $reqVesselName ?> </td>
            </tr>
            <tr>
                <td>Type of Vessel</td>
                <td>:</td>
                <td style="padding-left: 5px;"><?=$reqVesselType ?></td>
            </tr>
            <tr>
                <td>Location of Work</td>
                <td>:</td>
                <td style="padding-left: 5px;"><?= $reqDestination; ?></td>
            </tr>
            <tr>
                <td>Class Society</td>
                <td>:</td>
                <td style="padding-left: 5px;"><?= $reqVesselClass ?></td>
            </tr>
            <tr>
                <td>Scope of Work</td>
                <td>:</td>
                <td style="padding-left: 5px;"><?= $reqProjectName; ?></td>
            </tr>
            <tr>
                <td>Date of Departure</td>
                <td>:</td>
                <td style="padding-left: 5px;"><?= getFormattedDateEng($reqDateOfStart)?></td>
            </tr>
            <tr>
                <td>Date of Work</td>
                <td>:</td>
                <td style="padding-left: 5px;"><?=getFormattedDateEng($reqDateOfService)?></td>
            </tr>
            <tr>
                <?
                $return_date = new DateTime($reqDateOfFinish);
                $return_date->format('d-m-Y');
                $return_date->modify('+1 day'); 
                $return_date = $return_date->format('d-m-Y');

                ?>
                <td>Date of Return</td>
                <td>:</td>
                <td style="padding-left: 5px;"><?=getFormattedDateEng($reqDateOfFinish)?></td>
            </tr>
            <tr>
                <td>Work Equipment</td>
                <td>:</td>
                <td style="padding-left: 5px;">Work equipment list is attached</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Duty</td>
                <td style="vertical-align: top;">:</td>
                <td style="padding-left: 5px; text-align: justify;">
                    <table style="font-size: 13px;font-family: Arial">
                    <?php
                    for ($i=0; $i < count($arrayObligation); $i++) 
                    { 
                    ?>
                    <tr>
                        <td width="5%" style="vertical-align: top"><?=$i+1?>.</td>
                        <td width="95%"><?=$arrayObligation[$i]?></td>
                    </tr>
                    <?php    
                    }
                    ?>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>

<br>

<div class="row">
    <div class="col">
        <table style="font-size: 13px; width: 100%;font-family: Arial">
            <tr>
                <td style="text-align: justify;">Thus, this Operational Work Request (OWR) constitutes a formal reference of the services to be done as per Contractor operational standard and to be conducted at the utmost safety condition at always.</td>
            </tr>
        </table>
    </div>
</div>

<br>
<?
$date = date('d-m-Y');
?>

<p style="font-size: 13px;font-family: Arial; position: absolute; z-index: 2; margin-bottom: -11px;"> Sidoarjo, <?=getFormattedDateEng($reqDateOWR)?> </p>
<img style="position: absolute; z-index: 1; width: 200px" src="<?=$reqTTDLink?>"/>
<p style="font-size: 13px;font-family: Arial; position: absolute; z-index: 2; margin-top: -20px"><b><u> <?=$reqTTDNama?></u></b> <br>
<em> <?=$reqTTDJabatan?></em> </p>
</div>