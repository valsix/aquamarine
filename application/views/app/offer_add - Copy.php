<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Offer");
$offer = new Offer();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $offer->selectByParams(array("OFFER_ID" => $reqId));
    $offer->firstRow();

    $reqNoOrder = $offer->getField("NO_ORDER");
    $reqEmail = $offer->getField("EMAIL");
    $reqDestination = $offer->getField("DESTINATION");
    $reqCompanyName = $offer->getField("COMPANY_NAME");
    $reqVesselName = $offer->getField("VESSEL_NAME");
    $reqTypeOfVessel = $offer->getField("TYPE_OF_VESSEL");
    $reqFaximile = $offer->getField("FAXIMILE");
    $reqTypeOfService = $offer->getField("TYPE_OF_SERVICE");
    $reqTotalPrice = $offer->getField("TOTAL_PRICE");
    $reqScopeOfWork = $offer->getField("SCOPE_OF_WORK");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/offer">Offer</a> &rsaquo; Form Offer</div>

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
                        <label for="reqEmail" class="control-label col-md-2"> Email</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="email" id="reqEmail" class="easyui-validatebox textbox form-control" required="true" name="reqEmail" value="<?= $reqEmail ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDestination" class="control-label col-md-2">Destination</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDestination" class="easyui-validatebox textbox form-control" required="true" name="reqDestination" value="<?= $reqDestination ?>" style=" width:100%" />
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
                        <label for="reqTypeOfVessel" class="control-label col-md-2">Type Of Vessel</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqTypeOfVessel" class="easyui-validatebox textbox form-control" required="true" name="reqTypeOfVessel" value="<?= $reqTypeOfVessel ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqFaximile" class="control-label col-md-2">Faximile</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="number" id="reqFaximile" class="easyui-validatebox textbox form-control" required="true" name="reqFaximile" value="<?= $reqFaximile ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqTypeOfService" class="control-label col-md-2">Type Of Service</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqTypeOfService" id="reqTypeOfService" required style="width:100%;"><?= $reqTypeOfService; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqTotalPrice" class="control-label col-md-2">Total Price</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqTotalPrice" class="easyui-validatebox textbox form-control" required="true" name="reqTotalPrice" value="<?= $reqTotalPrice ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqScopeOfWork" class="control-label col-md-2">Scope Of Work</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqScopeOfWork" id="reqScopeOfWork" required style="width:100%;"><?= $reqScopeOfWork; ?></textarea>
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
                url: 'web/offer_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    $.messager.alertLink('Info', data, 'info', "app/index/offer");
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
</div>
</div>