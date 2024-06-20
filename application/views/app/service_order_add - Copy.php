<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Service_order");
$serviceOrder = new Service_order();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $serviceOrder->selectByParams(array("SO_ID" => $reqId));
    $serviceOrder->firstRow();

    $reqNoOrder = $serviceOrder->getField("NO_ORDER");
    $reqProjectName = $serviceOrder->getField("PROJECT_NAME");
    $reqCompanyName = $serviceOrder->getField("COMPANY_NAME");
    $reqVesselName = $serviceOrder->getField("VESSEL_NAME");
    $reqVesseltype = $serviceOrder->getField("VESSEL_TYPE");
    $reqSurveyor = $serviceOrder->getField("SURVEYOR");
    $reqDestination = $serviceOrder->getField("DESTINATION");
    $reqService = $serviceOrder->getField("SERVICE");
    $reqDateOfStart = $serviceOrder->getField("DATE_OF_START");
    $reqDateOfFinish = $serviceOrder->getField("DATE_OF_FINISH");
    $reqEquipment = $serviceOrder->getField("EQUIPMENT");
    $reqDateOfService = $serviceOrder->getField("DATE_OF_SERVICE");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/service_order">Service Order</a> &rsaquo; Form Service Order</div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Data</h3>
                    </div>

                    <div class="form-group">
                        <label for="reqNoOrder" class="control-label col-md-2">No Order</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNoOrder" class="easyui-validatebox textbox form-control" required="true" name="reqNoOrder" value="<?= $reqNoOrder ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqProjectName" class="control-label col-md-2">Project Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqProjectName" class="easyui-validatebox textbox form-control" required="true" name="reqProjectName" value="<?= $reqProjectName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCompanyName" class="control-label col-md-2">Company Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCompanyName" class="easyui-validatebox textbox form-control" required="true" name="reqCompanyName" value="<?= $reqCompanyName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqVesselName" class="control-label col-md-2">Vessel Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqVesselName" class="easyui-validatebox textbox form-control" required="true" name="reqVesselName" value="<?= $reqVesselName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqVesseltype" class="control-label col-md-2">Vessel Type</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqVesseltype" class="easyui-validatebox textbox form-control" required="true" name="reqVesseltype" value="<?= $reqVesseltype ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqSurveyor" class="control-label col-md-2">Surveyor</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqSurveyor" class="easyui-validatebox textbox form-control" required="true" name="reqSurveyor" value="<?= $reqSurveyor ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqService" class="control-label col-md-2">Service</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqService" class="easyui-validatebox textbox form-control" required="true" name="reqService" value="<?= $reqService ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDateOfStart" class="control-label col-md-2">Date Of Start</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="date" id="reqDateOfStart" class="easyui-validatebox textbox form-control" required="true" name="reqDateOfStart" value="<?= $reqDateOfStart ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDateOfFinish" class="control-label col-md-2">Date Of Finish</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="date" id="reqDateOfFinish" class="easyui-validatebox textbox form-control" required="true" name="reqDateOfFinish" value="<?= $reqDateOfFinish ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqEquipment" class="control-label col-md-2">Equipment</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqEquipment" class="easyui-validatebox textbox form-control" required="true" name="reqEquipment" value="<?= $reqEquipment ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDateOfService" class="control-label col-md-2">Date Of Service</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="date" id="reqDateOfService" class="easyui-validatebox textbox form-control" required="true" name="reqDateOfService" value="<?= $reqDateOfService ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>

        </div>

    </div>

    <script>
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/service_order_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    $.messager.alertLink('Info', data, 'info', "app/index/service_order");
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
</div>
</div>