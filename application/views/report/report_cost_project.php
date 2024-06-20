<base href="<?= base_url(); ?>" />
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$fileName = 'print_cash_report.xls';
$aColumns = array( "TANGGAL", "KETERANGAN",  "DEBET ( Rp. )", "KREDIT ( Rp. )", "BALENCE ( Rp. )", "STATUS", "ID_DETAIL");
$aColumns_alias = array("TANGGAL","KETERANGAN","DEBET","KREDIT","SALDO","CASH_REPORT_ID","CASH_REPORT_DETIL_ID");

$reqId = $this->input->get("reqId");

$this->load->model("Project_cost");
$projectCost = new Project_cost();
$projectCost->selectByParamsNew(array("A.COST_PROJECT_ID" => $reqId));
$projectCost->firstRow();

$reqNoProject       = $projectCost->getField("NO_PROJECT");
$reqVesselName      = $projectCost->getField("VESSEL_NAME");
$reqTypeOfVessel    = $projectCost->getField("TYPE_OF_VESSEL");
$reqTypeOfService   = $projectCost->getField("TYPE_OF_SERVICE");
$reqDateService1    = $projectCost->getField("DATE_SERVICE1");
$reqDateService2    = $projectCost->getField("DATE_SERVICE2");
$reqDestination     = $projectCost->getField("DESTINATION");
$reqCompanyName     = $projectCost->getField("COMPANY_NAME");
$reqContactPerson   = $projectCost->getField("CONTACT_PERSON");
$reqKasbon          = $projectCost->getField("KASBON");
$reqOfferPrice      = $projectCost->getField("OFFER_PRICE");
$reqRealPrice       = $projectCost->getField("REAL_PRICE");
$reqSurveyor        = $projectCost->getField("SURVEYOR");
$reqOperator        = $projectCost->getField("OPERATOR");
$reqKasbonCur       = $projectCost->getField("KASBON_CUR");
$reqOfferCur        = $projectCost->getField("OFFER_CUR");
$reqRealCur         = $projectCost->getField("REAL_CUR");
$reqAddService      = $projectCost->getField("ADD_SERVICE");
$reqServiceOrderId  = $projectCost->getField("SERVICE_ORDER_ID");

$sekarang = $this->db->query("SELECT TO_CHAR(CURRENT_DATE, 'Day, D Month, YYYY') SEKARANG ")->row()->sekarang;


$this->load->model("CostProjectDetil");
$cost_project_detil = new CostProjectDetil();
$cost_project_detil->selectByParamsMonitoring(array("COST_PROJECT_ID" => $reqId));
?>
<br>

<div style="text-align: right;"><?=$sekarang?></div>
<table style="width: 100%;font-size: 12px;font-family: Arial;
    border-collapse: collapse;
    border-spacing: 0;font-weight: bold;" border="1">
    <tr>
        <td colspan="7" align="center">PROJECT COST </td>
    </tr>
        <tr>
            <td align="left" style="width: 20%;padding: 5px">No Project </td>
            <td style="width: 1%;padding: 5px" align="center">:</td>
            <td style="width: 27%;padding: 5px;border-right:  none;"><?=$reqNoProject?> </td>
            <td style="width: 4%;padding: 5px;border:none;border-bottom: 1px solid black" > &nbsp;&nbsp;&nbsp; </td>
            <td style="width: 20%;padding: 5px"> Owner / Client</td>
            <td style="width: 1%;padding: 5px" align="center"> :</td>
            <td style="width: 27%;padding: 5px"><?=$reqCompanyName?> </td>

        </tr>
        <tr>
            <td style="padding: 5px"> Vessel's Name</td>
            <td style="padding: 5px" align="center">: </td>
            <td style="border-right:  none;padding: 5px"><?=$reqVesselName?> </td>
            <td style="border:none;border-bottom: 1px solid black;padding: 5px"> &nbsp;&nbsp;&nbsp;</td>
            <td style="padding: 5px">Contact Person </td>
            <td style="padding: 5px" align="center">: </td>
            <td style="padding: 5px"><?=$reqContactPerson?> </td>

        </tr>
        <tr>
            <td style="padding: 5px"> Vessel's Name</td>
            <td style="padding: 5px" align="center">: </td>
            <td style="border-right:  none;padding: 5px"><?=$reqVesselName?> </td>
            <td style="border:none;border-bottom: 1px solid black;padding: 5px"> &nbsp;&nbsp;&nbsp;</td>
            <td style="padding: 5px">Contact Person </td>
            <td style="padding: 5px" align="center">: </td>
            <td style="padding: 5px"><?=$reqContactPerson?> </td>

        </tr>
        <tr>
            <td style="padding: 5px"> Vessel's Type</td>
            <td style="padding: 5px" align="center">: </td>
            <td style="border-right:  none;padding: 5px"><?=$reqTypeOfVessel?> </td>
            <td style="border:none;border-bottom: 1px solid black;padding: 5px"> &nbsp;&nbsp;&nbsp;</td>
            <td style="padding: 5px">Advance Survey </td>
            <td style="padding: 5px" align="center">: </td>
            <td style="padding: 5px"><?=currencyToPage2($reqKasbon)?> </td>

        </tr>
        <tr>
            <td style="padding: 5px"> Date Start</td>
            <td style="padding: 5px" align="center">: </td>
            <td style="border-right:  none;padding: 5px"><?=getFormattedDateMin($reqDateService1)?> </td>
            <td style="border:none;border-bottom: 1px solid black;padding: 5px"> &nbsp;&nbsp;&nbsp;</td>
            <td style="padding: 5px">Real Price </td>
            <td style="padding: 5px" align="center">: </td>
            <td style="padding: 5px"><?=currencyToPage2($reqRealPrice)?> </td>

        </tr>
        <tr>
            <td style="padding: 5px"> Date Finish</td>
            <td style="padding: 5px" align="center">: </td>
            <td style="border-right:  none;padding: 5px"><?=getFormattedDateMin($reqDateService2)?> </td>
            <td style="border:none;border-bottom: 1px solid black;padding: 5px"> &nbsp;&nbsp;&nbsp;</td>
            <td style="padding: 5px">Surveyor </td>
            <td style="padding: 5px" align="center">: </td>
            <td style="padding: 5px"><?=$reqSurveyor?> </td>

        </tr>
        <tr>
            <td style="padding: 5px"> Location</td>
            <td style="padding: 5px" align="center">: </td>
            <td style="border-right:  none;padding: 5px"><?=$reqNoProject?> </td>
            <td style="border:none;border-bottom: 1px solid black;padding: 5px"> &nbsp;&nbsp;&nbsp;</td>
            <td style="padding: 5px">Operator </td>
            <td style="padding: 5px" align="center">: </td>
            <td style="padding: 5px"><?=$reqOperator?> </td>

        </tr>

    </table>
    <br>
    <br>
    <table style="width: 100%;font-size: 12px;font-family: Arial;
    border-collapse: collapse;
    border-spacing: 0;" border="1">
    <tr>
        <td align="center" style="padding: 5px;width: 20%" ><b> Date </b></td>
        <td align="center" style="padding: 5px;width: 50%"><b> Description </b></td>
        <td align="center" style="padding: 5px;width: 5%"><b>Currency</b> </td>
        <td align="center" style="padding: 5px;width: 25%"><b>Total</b> </td>
    </tr>
    <?
       while ($cost_project_detil->nextRow()) {
         $currency = "USD";
         if($cost_project_detil->getField("currency") == "IDR"){
            $currency = "Rp.";
        }
    ?>
    <tr>
            <td align="center" style="padding: 5px"> <?=getFormattedDateMin($cost_project_detil->getField("cost_dates"))?></td>
            <td style="padding: 5px"> <?=$cost_project_detil->getField("description")?></td>
            <td align="center" style="padding: 5px"><?=$currency?> </td>
            <td align="right" style="padding: 5px"><?=currencyToPage2($cost_project_detil->getField("cost"))?> </td>
    </tr>
    <?
    }       
    ?>
</table>
