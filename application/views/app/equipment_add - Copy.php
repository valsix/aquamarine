<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Equipment");
$equipment = new Equipment();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $equipment->selectByParams(array("EQUIP_ID" => $reqId));
    $equipment->firstRow();

    $reqEquipName = $equipment->getField("EQUIP_NAME");
    $reqEquipQty = $equipment->getField("EQUIP_QTY");
    $reqEquipItem = $equipment->getField("EQUIP_ITEM");
    $reqEquipSpec = $equipment->getField("EQUIP_SPEC");
    $reqEquipDatein = $equipment->getField("EQUIP_DATEIN");
    $reqEquipLastCal = $equipment->getField("EQUIP_LASTCAL");
    $reqEquipNextCal = $equipment->getField("EQUIP_NEXTCAL");
    $reqEquipCondition = $equipment->getField("EQUIP_CONDITION");
    $reqEquipStorage = $equipment->getField("EQUIP_STORAGE");
    $reqEquipRemarks = $equipment->getField("EQUIP_REMARKS");
    $reqEquipPrice = $equipment->getField("EQUIP_PRICE");
    $reqPicPath = $equipment->getField("PIC_PATH");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/equipment"> Equipment</a> &rsaquo; Form Equipment</div>

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
                        <label for="reqEquipName" class="control-label col-md-2">Equip Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqEquipName" class="easyui-validatebox textbox form-control" required="true" name="reqEquipName" value="<?= $reqEquipName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqEquipQty" class="control-label col-md-2">Equip Qty</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="number" id="reqEquipQty" class="easyui-validatebox textbox form-control" required="true" name="reqEquipQty" value="<?= $reqEquipQty ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqEquipItem" class="control-label col-md-2">Equip Item</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqEquipItem" class="easyui-validatebox textbox form-control" required="true" name="reqEquipItem" value="<?= $reqEquipItem ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqEquipSpec" class="control-label col-md-2">Equip Spec</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqEquipSpec" class="easyui-validatebox textbox form-control" required="true" name="reqEquipSpec" value="<?= $reqEquipSpec ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqEquipDatein" class="control-label col-md-2">Equip Date In</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="date" id="reqEquipDatein" class="easyui-validatebox textbox form-control" required="true" name="reqEquipDatein" value="<?= $reqEquipDatein ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqEquipLastCal" class="control-label col-md-2">Equip Last Call</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="date" id="reqEquipLastCal" class="easyui-validatebox textbox form-control" required="true" name="reqEquipLastCal" value="<?= $reqEquipLastCal ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqEquipNextCal" class="control-label col-md-2">Equip Next Call</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="date" id="reqEquipNextCal" class="easyui-validatebox textbox form-control" required="true" name="reqEquipNextCal" value="<?= $reqEquipNextCal ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqEquipCondition" class="control-label col-md-2">Equip Condition</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqEquipCondition" class="easyui-validatebox textbox form-control" required="true" name="reqEquipCondition" value="<?= $reqEquipCondition ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqEquipStorage" class="control-label col-md-2">Equip Storage</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqEquipStorage" class="easyui-validatebox textbox form-control" required="true" name="reqEquipStorage" value="<?= $reqEquipStorage ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqEquipRemarks" class="control-label col-md-2">Equip Remarks</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqEquipRemarks" class="easyui-validatebox textbox form-control" required="true" name="reqEquipRemarks" value="<?= $reqEquipRemarks ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqEquipPrice" class="control-label col-md-2">Equip Price</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqEquipPrice" class="easyui-validatebox textbox form-control" required="true" name="reqEquipPrice" value="<?= $reqEquipPrice ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqPicPath" class="control-label col-md-2">Pic Pach</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqPicPath" id="reqPicPath" style="width:100%" required><?= $reqPicPath; ?></textarea>
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
                url: 'web/equipment_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    $.messager.alertLink('Info', data, 'info', "app/index/equipment");
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
</div>
</div>