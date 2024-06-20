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

<style type="text/css">
    table#table2 td {
        border: none;
    }
    table#table3 td {
        border: none;
    }
    td:hover {
        border: 1px solid red;
    }
    .space-border {
        border-top: none;
        border-left: none;
        border-bottom: none;
        border-right:  1px inset black;
    }
</style>
<body>
<img src="images/header-excel.png">
<br>
<br>
<br>
<br>
<br>
<br>
<table>
    <tr>
        <td width="10px">=TRIM(A1)</td>
        <td>
            <table style="width: 1290px;font-size: 12px;font-family: Arial;" >
                <tr>
                    <td colspan="11" align="right" width="200"><?=$sekarang?></td>
                </tr>
            </table>
            <table  style="width: 1290px;font-size: 12px;font-family: Arial;border:1px solid black;border: 1px inset black;
                border-collapse: collapse;
                border-spacing: 0; margin-left: 100px" border="1" id="table1"  > 
                <tr>
                    <td colspan="11" style="text-align: center;"><b>Project Cost</b></td>
                </tr>  
            </table>
            <table  style="width: 1290px;font-size: 12px;font-family: Arial;border:1px solid black;border: 1px inset black;
                border-collapse: collapse;
                border-spacing: 0; margin-left: 100px" border="1" id="table2"  > 
                <tr>
                    <td width="400px" colspan="2">Project No.</td>
                    <td width="10">:</td>
                    <td width="400px" colspan="2"><?=$reqNoProject?></td>
                    <td>&nbsp;</td>
                    <td width="400px" colspan="2">Owner / Client</td>
                    <td width="10px">:</td>
                    <td width="400px" colspan="2"><?=$reqCompanyName?></td>
                </tr>
                <tr>
                    <td colspan="2">Vessel's Name</td>
                    <td>:</td>
                    <td colspan="2"><?=$reqVesselName?></td>
                    <td>&nbsp;</td>
                    <td colspan="2">Contact Person</td>
                    <td>:</td>
                    <td colspan="2"><?=$reqContactPerson?></td>
                </tr>
                <tr>
                    <td colspan="2">Vessel's Type</td>
                    <td>:</td>
                    <td colspan="2"><?=$reqTypeOfVessel?></td>
                    <td>&nbsp;</td>
                    <td colspan="2">Advance Survey</td>
                    <td>:</td>
                    <td colspan="2"><?=$reqKasbon?></td>
                </tr>
                <tr>
                    <td colspan="2">Date Start</td>
                    <td>:</td>
                    <td colspan="2"><?=getFormattedDateMin($reqDateService1)?>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="2">Real Price</td>
                    <td>:</td>
                    <td colspan="2"><?=$reqRealPrice?></td>
                </tr>
                <tr>
                    <td colspan="2">Date Finish</td>
                    <td>:</td>
                    <td colspan="2"><?=getFormattedDateMin($reqDateService2)?>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="2">Surveyor</td>
                    <td>:</td>
                    <td colspan="2"><?=$reqSurveyor?></td>
                </tr>
                <tr>
                    <td colspan="2">Location</td>
                    <td>:</td>
                    <td colspan="2"><?=$reqNoProject?></td>
                    <td>&nbsp;</td>
                    <td colspan="2">Operator</td>
                    <td>:</td>
                    <td colspan="2"><?=$reqOperator?></td>
                </tr>
            </table>
            <br>
            <table  style="width: 1290px;font-size: 12px;font-family: Arial;border:1px solid black;border: 1px inset black;
                border-collapse: collapse;
                border-spacing: 0; margin-left: 100px" border="1" id="table"  > 
                <tr>
                    <th colspan="3">Date</th>
                    <th colspan="4">Description</th>
                    <th colspan="4">Currency</th>
                </tr>
            </table>
            <table  style="width: 1290px;font-size: 12px;font-family: Arial;border:1px solid black;border: 1px inset black;
                border-collapse: collapse;
                border-spacing: 0; margin-left: 100px" border="1" id="table3"  > 
                <?php
                while ($cost_project_detil->nextRow()) {
                    // 'cost_project_detil_id' => string '86' (length=2)
                    //   'cost_project_id' => string '17' (length=2)
                    //   'cost_date' => string '21-11-2020' (length=10)
                    //   'description' => string 'snack@10.000x6org' (length=17)
                    //   'cost' => string '60000' (length=5)
                    //   'status' => string '1' (length=1)
                    //   'cost_dates' => string '21-11-2020' (length=10)
                    //   'currency' => string 'IDR' (length=3)
                    $currency = "USD";
                    if($cost_project_detil->getField("currency") == "IDR"){
                        $currency = "Rp.";
                    }

                ?>
                <tr>
                    <td colspan="3"><?=getFormattedDateMin($cost_project_detil->getField("cost_dates"))?>&nbsp;</td>
                    <td colspan="4"><?=$cost_project_detil->getField("description")?></td>
                    <td><?=$currency?></td>
                    <td colspan="3" style="text-align: right;"><?=currencyToPage2($cost_project_detil->getField("cost"))?></td>
                </tr>

                <?php
                }
                ?>
            </table>
        </td>    
    </tr>
    
    
</table>
<br>
<br>
<br>
<br>
<img src="images/footer-excel.png">

</body>
</html>
<?
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Content-Type: application/vnd.ms-excel;");
?>