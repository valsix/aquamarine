<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("SoEquip");
$so_equip = new SoEquip();

$reqId = $this->input->get("reqId");
$reqSoId = $this->input->get("reqSoId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $statement = " AND A.SO_EQUIP_ID = " . $reqId;
    $so_equip->selectByParamsMonitoringEquips(array(), -1, -1, $statement);
    $so_equip->firstRow();

    $reqEquipId = $so_equip->getField("EQUIP_ID");
    $reqEquipName = $so_equip->getField("EQUIP_NAME");
    $reqQty = $so_equip->getField("QTY");
    $reqOutCondition = $so_equip->getField("OUT_CONDITION");
    $reqInCondition = $so_equip->getField("IN_CONDITION");
    $reqItem = $so_equip->getField("ITEM");
    $reqRemark = $so_equip->getField("REMARK");
}


?>
<base href="<?= base_url(); ?>" />
<link href="libraries/bootstrap-3.3.7/docs/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="libraries/bootstrap-3.3.7/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="libraries/bootstrap-3.3.7/docs/examples/sticky-footer-navbar/sticky-footer-navbar.css" rel="stylesheet">

<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="libraries/bootstrap-3.3.7/docs/assets/js/ie-emulation-modes-warning.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<link rel="stylesheet" href="css/halaman.css" type="text/css">
<link rel="stylesheet" href="css/gaya-egateway.css" type="text/css">
<link rel="stylesheet" href="css/gaya-affix.css" type="text/css">
<link rel="stylesheet" href="css/gaya-pagination.css" type="text/css">
<link rel="stylesheet" href="css/gaya-datatable-egateway.css" type="text/css">
<link rel="stylesheet" href="libraries/font-awesome-4.7.0/css/font-awesome.css">

<script type='text/javascript' src="libraries/bootstrap/js/jquery-1.12.4.min.js"></script>


<!-- DATATABLE -->
<link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/extensions/Responsive/css/dataTables.responsive.css">
<link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/examples/resources/syntax/shCore.css">
<link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/examples/resources/demo.css">

<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/fnReloadAjax.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/examples/resources/demo.js"></script>

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

<script src="libraries/tinyMCE/tinymce.min.js"></script>

<script type="text/javascript" src="libraries/functions/string.func.js?n=1"></script>

<script type="text/javascript">
    tinymce.init({
        selector: ".tinyMCES",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        menubar: true,

    });
</script>


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
        border-bottom: 1px solid black;
    }
</style>
<body>
<div class="col-md-12">

    <!-- <div class="judul-halaman">Offer Project </div> -->

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Equipment Project </h3>
                    </div>
                    <div class="form-group">
                        <label for="reqEquipName" class="control-label col-md-2">Equipment</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <span class="input-group-addon" onclick="openEquipmentList()"><i class="fa fa-address-book-o fa-lg"></i> </span>
                                       <input type="text" id="reqEquipmentName" name="reqEquipmentName" class="easyui-validatebox textbox form-control" value="<?= $reqEquipmentName ?>" style=" width:50%" required  readOnly  /> 
                                       <input type="hidden" id="reqEquipId" name="reqEquipId" />

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqQty" class="control-label col-md-2">Quantity</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input name="reqQty" id="reqQty" onkeyup="numberWithCommas('reqQty');" onchange="numberWithCommas('reqQty');" value="<?= currencyToPage2($reqQty) ?>" type="text" class="easyui-validatebox textbox form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqItem" class="control-label col-md-2">Item</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqItem" name="reqItem" class="easyui-validatebox textbox form-control" value="<?= $reqItem ?>" style=" width:50%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqOutCondition" class="control-label col-md-2">Out Condition</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqOutCondition" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'combo_json/status'" value="<?= $reqOutCondition ?>" required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqInCondition" class="control-label col-md-2">In Condition</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqInCondition" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'combo_json/status'" value="<?= $reqInCondition ?>" required />

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqRemark" class="control-label col-md-2">Remarks</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqRemark" name="reqRemark" class="easyui-validatebox textbox form-control" value="<?= $reqRemark ?>" style=" width:50%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>


                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqOfferId" value="<?= $reqOfferId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                    <div style="text-align:center;padding:5px">
                        <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                    </div>
                </form>

                </div>
            </div>
        </div>
    </div>



</body>
<script type="text/javascript">
    function submitForm() {
        $('#ff').form('submit', {
            url: 'web/so_equip_json/add',
            onSubmit: function() {
                return $(this).form('enableValidation').form('validate');
            },
            success: function(data) {

                $.messager.alert('Info', data, 'info');
                parent.reload();
                top.closePopup();
            }
        });
    }

    function clearForm() {
        $('#ff').form('clear');
    }

    function tambahPenyebab(id) {
        window.location.href = "app/loadUrl/app/tempalate_master_services?reqId=" + id;

    }
    function hitungTotal() {
        var reqPrice = $('#reqPrice').val();
        var reqQuantity = $("#reqQuantity").val();
        var reqDuration = $("#reqDuration").val();
        reqPrice = reqPrice.replaceAll('.', '');
        reqQuantity = reqQuantity.replaceAll('.', '');
        reqDuration = reqDuration.replaceAll('.', '');

        reqPrice = (reqPrice == '' ? 0 : parseFloat(reqPrice));
        reqQuantity = (reqQuantity == '' ? 0 : parseFloat(reqQuantity));
        reqDuration = (reqDuration == '' ? 0 : parseFloat(reqDuration));
        

        var reqTotal = reqPrice * reqQuantity * reqDuration;

        $("#reqTotal").val(reqTotal);
        numberWithCommas('reqTotal')


    }
</script>

<script>
    window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
</script>
<script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>