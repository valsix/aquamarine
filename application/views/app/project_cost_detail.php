<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$reqId = $this->input->get("reqId");
$reqChild = $this->input->get("reqChild");


//---------------------------- JSON FORMAT ADD DiLETAKKAN DI SINI 
$reqMode = $this->input->get("reqMode");
$reqCostDate = $this->input->get("reqCostDate");
$reqCost = $this->input->get("reqCost");
$reqDescription = $this->input->get("reqDescription");
$reqStatus = $this->input->get("reqStatus");


$this->load->model("Project_cost_detil");
$projectCostDetil = new Project_cost_detil();

$projectCostDetil->setField("COST_PROJECT_DETIL_ID", $reqChild);
$projectCostDetil->setField("COST_PROJECT_ID", $reqId);

$projectCostDetil->setField("COST_DATE", dateToDBCheck($reqCostDate));
$projectCostDetil->setField("COST", $reqCost);
$projectCostDetil->setField("DESCRIPTION", $reqDescription);
$projectCostDetil->setField("STATUS", $reqStatus);
if (!empty($reqMode)) {
    if (empty($reqChild)) {
        $projectCostDetil->insert();
    } else {
        $projectCostDetil->update();
    }
}

//-------------------------------------------------------


$this->load->model("Project_cost_detil");
$projectCostDetil = new Project_cost_detil();

$reqId = $this->input->get("reqId");
$reqChild = $this->input->get("reqChild");
$statement = '';
if (!empty($reqChild)) {
    $statement  = " AND A.COST_PROJECT_DETIL_ID ='" . $reqChild . "'";
}

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $projectCostDetil->selectByParams(array("A.COST_PROJECT_ID" => $reqId), -1, -1, $statement);
    $projectCostDetil->firstRow();

    $reqCostDate = $projectCostDetil->getField("DATES");
    $reqCost = $projectCostDetil->getField("COST");
    $reqDescription = $projectCostDetil->getField("DESCRIPTION");
    $ReqStatus = $projectCostDetil->getField("STATUS");
}


// $projectCostDetil = new Project_cost_detil();
// setsdas


?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<!-- EASYUI 1.4.5 -->
<link rel="stylesheet" type="text/css" href="libraries/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="libraries/easyui/themes/icon.css">
<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="libraries/easyui/globalfunction.js"></script>
<script type="text/javascript" src="libraries/easyui/kalender-easyui.js"></script>

<?php
// Header Nama TABEL TH
?>

<style type="text/css">
    #tablei {
        background-color: white;
    }

    #tablei tr td {
        color: black;
        font-weight: bold;
        padding: 5px;
    }
</style>

<div class="form-group">
    <label for="reqCostDate" class="control-label col-md-2">Cost Date</label>
    <div class="col-md-4">
        <div class="form-group">
            <div class="col-md-11">
                <input type="text" id="reqCostDate" class="easyui-datebox textbox form-control" required="true" name="reqCostDate" value="<?= $reqCostDate ?>" style=" width:100%" />
            </div>
        </div>
    </div>
    <label for="reqCost" class="control-label col-md-2">Cost (Rp.)</label>
    <div class="col-md-4">
        <div class="form-group">
            <div class="col-md-11">
                <input type="number" id="reqCost" class="easyui-validatebox textbox form-control" required="true" name="reqCost" value="<?= $reqCost ?>" style=" width:100%" />
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="reqDescription" class="control-label col-md-2">Description</label>
    <div class="col-md-4">
        <div class="form-group">
            <div class="col-md-11">
                <textarea class="form-control" name="reqDescription" id="reqDescription" style="width: 100%;"><?= $reqDescription; ?></textarea>
            </div>
        </div>
    </div>
    <label for="reqStatus" class="control-label col-md-2">Status</label>
    <div class="col-md-4">
        <div class="form-group">
            <div class="col-md-11">
                <input class="easyui-combobox form-control" style="width:100%" id="reqStatus" name="reqStatus" data-options="width:'372',editable:false, valueField:'id',textField:'text',url:'combo_json/comboStatus'" value="<?= $ReqStatus ?>" />
            </div>
        </div>
    </div>
</div>


<input type="hidden" name="reqId" value="<?= $reqId ?>" />
<input type="hidden" id="reqChild" name="reqChild" value="<?= $reqChild ?>" class="easyui-validatebox textbox form-control" />
<input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

<div style="text-align:center;padding:5px">
    <a href="javascript:void(0)" class="btn btn-warning" onclick="clearFormDetil()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
    <a href="javascript:void(0)" class="btn btn-primary" onclick="tambahPenyebab_add()"><i class="fa fa-fw fa-send"></i> Submit</a>
    <a href="javascript:void(0)" class="btn btn-primary" onclick="delete_datas(<?= $reqChild ?>)"><i class="fa fa-fw fa-trash"></i> Delete</a>

</div>

<div style="background-color: white; height: 250px; width: 100%;  overflow-y: scroll;">
    <table style="width: 100%" id="tablei" class="table table-striped table-hover dt-responsive" style="background: white">
        <thead>
            <tr>
                <th width="10%">Aksi </th>
                <th width="20%">Cost Date </th>
                <th width="30%">Description </th>
                <th width="20%">Cost (Rp) </th>
                <th width="20%">Status</th>

            </tr>
        </thead>
        <tbody>
            <?
            $projectCostDetil = new Project_cost_detil();
            $projectCostDetil->selectByParams(array("A.COST_PROJECT_ID" => $reqId));
            $total = 0;
            while ($projectCostDetil->nextRow()) {

                $reqIds = $projectCostDetil->getField("COST_PROJECT_DETIL_ID");
                $reqCostDate = $projectCostDetil->getField("COST_DATE");
                $reqCost = $projectCostDetil->getField("COST");
                $reqDescription = $projectCostDetil->getField("DESCRIPTION");
                $ReqStatus = $projectCostDetil->getField("STATUS");
                $total = $total + $reqCost;
                $text_status = "";
                if ($ReqStatus == 1) {
                    $text_status = "Lunas";
                } else {
                    $text_status = "Belum Lunas";
                }
            ?>
                <tr>
                    <td> <a onclick="tambahPenyebab_edit(<?= $reqIds ?>)" id="btnPenyebab" class="btn btn-info"><i class="fa fa-pencil"></i></a></td>
                    <td><?= $reqCostDate ?> </td>
                    <td> <?= $reqDescription ?> </td>
                    <td> <?= currencyToPage($reqCost) ?> </td>
                    <td> <?= $text_status ?> </td>
                </tr>
            <?
            }
            ?>

        </tbody>
    </table>
</div>

<script>
    // A $( document ).ready() block.
    $(document).ready(function() {
        setTimeout(function(){
    $("#reqOp").val('<?=$total?>');
var reqPc = $("#reqPc").val();
var saldo =reqPc - <?=$total?>;
$("#reqSaldo").val(saldo);


var profit = (saldo/reqPc)*100;
var formats_persen = round(profit,2);
$("#reqProfit").html("( Profit: "+formats_persen+" % )" );

     }, 1000);
      
    });
</script>