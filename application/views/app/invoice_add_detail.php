<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$reqId = $this->input->get("reqId");




$reqChild = $this->input->get("reqChild");
$reqCompanyId = $this->input->get("reqCompanyId");


//---------------------------- JSON FORMAT ADD DiLETAKKAN DI SINI 
$reqServiceType = $this->input->get("reqServiceType");
$reqServiceDate = $this->input->get("reqServiceDate");
$reqLocation = $this->input->get("reqLocation");
$reqVessel = $this->input->get("reqVessel");
$reqAmount = $this->input->get("reqAmount");
$reqCurrency = $this->input->get("reqCurrency");
$reqMode = $this->input->get("reqMode");


$this->load->model("InvoiceDetail");
$invoice_detail= new InvoiceDetail();

$invoice_detail->setField("INVOICE_DETAIL_ID", $reqChild);
$invoice_detail->setField("INVOICE_ID", $reqId);
$invoice_detail->setField("SERVICE_TYPE", $reqServiceType);
$invoice_detail->setField("SERVICE_DATE", dateToDBCheck($reqServiceDate));
$invoice_detail->setField("LOCATION", $reqLocation);
$invoice_detail->setField("VESSEL", $reqVessel);
$invoice_detail->setField("AMOUNT", $reqAmount);
$invoice_detail->setField("CURRENCY", $reqCurrency);

if (!empty($reqMode)) {
    if (empty($reqChild)) {
        $invoice_detail->insert();
    } else {
        $invoice_detail->update();
    }
}

//-------------------------------------------------------



$invoice_detail= new InvoiceDetail();

$reqId = $this->input->get("reqId");
$reqChild = $this->input->get("reqChild");
$statement = '';
if (!empty($reqChild)) {
    $statement  = " AND A.INVOICE_DETAIL_ID ='" . $reqChild . "'";
}

if ($reqChild == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    // $statement= " AND A.INVOICE_DETAIL_ID = ".$reqChild;
    $invoice_detail->selectByParamsMonitoring(array(), -1,-1, $statement);

    $invoice_detail->firstRow();
    $reqInvoiceDetailId= $invoice_detail->getField("INVOICE_DETAIL_ID");
    $reqInvoiceId= $invoice_detail->getField("INVOICE_ID");
    $reqServiceType= $invoice_detail->getField("SERVICE_TYPE");
    $reqServiceDate= $invoice_detail->getField("SERVICE_DATE");
    $reqLocation= $invoice_detail->getField("LOCATION");
    $reqVessel= $invoice_detail->getField("VESSEL");
    $reqAmount= $invoice_detail->getField("AMOUNT");
    $reqCurrency= $invoice_detail->getField("CURRENCY");
}


// $invoice_detail = new Project_cost_detil();
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
    <label for="reqServiceType" class="control-label col-md-2">Type Of Service</label>
    <div class="col-md-4">
        <div class="form-group">
            <div class="col-md-11">
                 <input class="easyui-combobox form-control" style="width:100%" id="reqServiceType" name="reqServiceType" data-options="width:'372',editable:false, valueField:'id',textField:'text',url:'combo_json/comboTypeOfService'" value="<?= $reqServiceType ?>" />


            </div>
        </div>
    </div>
    <label for="reqVessel" class="control-label col-md-2">Vessel</label>
    <div class="col-md-4">
        <div class="form-group">
            <div class="col-md-11">
               

                   <input class="easyui-combobox form-control" style="width:100%" id="reqVessel" name="reqVessel" data-options="width:'372',editable:false, valueField:'id',textField:'text',url:'combo_json/comboVessel?reqId=<?=$reqCompanyId?>'" value="<?= $reqVessel ?>" />
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="reqServiceDate" class="control-label col-md-2">Date</label>
    <div class="col-md-4">
        <div class="form-group">
            <div class="col-md-11">
                  <input type="text" id="reqServiceDate" class="easyui-datebox textbox form-control"  name="reqServiceDate" value="<?=$reqServiceDate?>" style=" width:200px" />
              
            </div>
        </div>
    </div>
    <label for="reqAmount" class="control-label col-md-2">Amount</label>
    <div class="col-md-4">
        <div class="form-group">
            <div class="col-md-11">
                <input type="number" id="reqAmount" class="easyui-validatebox textbox form-control"  name="reqAmount" value="<?= $reqAmount ?>" style=" width:100%" />
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="reqDescription" class="control-label col-md-2">Location</label>
    <div class="col-md-4">
        <div class="form-group">
            <div class="col-md-11">
               <input type="text" id="reqLocation" class="easyui-validatebox textbox form-control"  name="reqLocation" value="<?= $reqLocation ?>" style=" width:100%" />
            </div>
        </div>
    </div>
    <label for="reqStatus" class="control-label col-md-2">Currency</label>
    <div class="col-md-4">
        <div class="form-group">
            <div class="col-md-11">
                <input class="easyui-combobox form-control" style="width:100%" id="reqCurrency" name="reqCurrency" data-options="width:'372',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar2'" value="<?= $reqCurrency ?>" />
            </div>
        </div>
    </div>
</div>
<div class="form-group">
     <label for="reqStatus" class=" col-md-2"></label>
    <div class="col-md-4">
        <div class="form-group">
            <div class="col-md-11">
             <input type="hidden" name="reqId" value="<?= $reqId ?>" />
             <input type="hidden" id="reqChild" name="reqChild" value="<?= $reqChild ?>" class="easyui-validatebox textbox form-control" />
             <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

             <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearFormDetil()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="tambahPenyebab_add()"><i class="fa fa-fw fa-send"></i> Submit</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="delete_datas(<?= $reqChild ?>)"><i class="fa fa-fw fa-trash"></i> Delete</a>

            </div>
            </div>
        </div>
    </div>
   
    <label for="reqStatus" class="control-label col-md-2"><input type="checkbox" <?=$checks?>  name="chek" id="chek" onchange="getPPn()" onkeyup="getPPn()"  /> Currency</label>
    <div class="col-md-4">
        <div class="form-group">
            <div class="col-md-11">
               <input type="number" id="reqPpn" class=" form-control"  name="reqPpn" value="10" style=" width:20%" /> %
            </div>
        </div>
    </div>
    </div>




<div style="background-color: white; height: 250px; width: 100%;  overflow-y: scroll;">
    <table style="width: 100%" id="tablei" class="table table-striped table-hover dt-responsive" style="background: white">
        <thead>
            <tr>
                <th width="5%">Aksi </th>
                <th width="5%">No </th>
                <th width="20%">Service Type </th>
                <th width="15%">Date </th>
                <th width="15%">Location </th>
                <th width="20%">Vessel</th>
                <th width="10%">Amount ( USD )</th>
                 <th width="10%">Amount ( IDR )</th>

            </tr>
        </thead>
        <tbody>
            <?
            $invoice_detail= new InvoiceDetail();
            $invoice_detail->selectByParamsMonitoring(array("A.INVOICE_ID" => $reqId));
            $total = 0;
            $nomer=1;
            $total_idr=0;
            $total_usd=0;
            while ($invoice_detail->nextRow()) {

                $reqIds             = $invoice_detail->getField("INVOICE_DETAIL_ID");
                $reqInvoiceId       = $invoice_detail->getField("INVOICE_ID");
                $reqServiceType     = $invoice_detail->getField("SERVICE_TYPE");
                $reqServiceDate     = $invoice_detail->getField("SERVICE_DATE");
                $reqLocation        = $invoice_detail->getField("LOCATION");
                $reqVessel          = $invoice_detail->getField("VESSEL");
                $reqAmount          = $invoice_detail->getField("AMOUNT");
                $reqCurrency        = $invoice_detail->getField("CURRENCY");
                $reqAmount2='0';
                if($reqCurrency=='1'){
                     $reqAmount2 =$reqAmount;
                     $reqAmount='0';
                      $total_idr = $total_idr+$reqAmount2;
                }else{
                         $total_usd = $total_usd+$invoice_detail->getField("AMOUNT");
                }
               
            ?>
                <tr>
                    <td> <a onclick="tambahPenyebab_edit(<?= $reqIds ?>)" id="btnPenyebab" class="btn btn-info"><i class="fa fa-pencil"></i></a></td>
                     <td><?= $nomer ?> </td>
                      <td><?= $reqServiceType ?> </td>
                    <td><?= $reqServiceDate ?> </td>
                    <td> <?= $reqLocation ?> </td>
                    <td> <?= $reqVessel ?> </td>
                    <td> <?= currencyToPage($reqAmount) ?> </td>
                    <td> <?= currencyToPage($reqAmount2) ?> </td>
                </tr>
            <?
            $nomer++;
            }
            ?>

        </tbody>
    </table>
   
</div>
<script type="text/javascript">
    function round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}
</script>
<script>
    // A $( document ).ready() block.
    $(document).ready(function() {
        setTimeout(function(){
      
        $('#RP').val('<?=$total_idr?>');
          $('#RP_USD').val(<?=$total_usd?>);
      

        getPPn();
     }, 1000);
      
    });
</script>


