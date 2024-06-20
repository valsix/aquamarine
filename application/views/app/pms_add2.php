<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Pms");
$pms = new Pms();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $pms->selectByParams(array("PMS_ID" => $reqId));
    $pms->firstRow();

    $reqName = $pms->getField("NAME");
    $reqTimeTest = $pms->getField("TIME_TEST");
    $reqCompetent = $pms->getField("COMPETENT");
    $reqPicPath = $pms->getField("PIC_PATH");
    $reqDateArrive = $pms->getField("DATE_ARRIVE");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/pms"> PMS</a> &rsaquo; Form PMS</div>

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
                        <label for="reqName" class="control-label col-md-2">Name Equipment</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqName" class="easyui-validatebox textbox form-control" required="true" name="reqName" value="<?= $reqName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqTimeTest" class="control-label col-md-2">Time Test</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="number" id="reqTimeTest" class="easyui-validatebox textbox form-control" required="true" name="reqTimeTest" value="<?= $reqTimeTest ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCompetent" class="control-label col-md-2">Competent</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCompetent" class="easyui-validatebox textbox form-control" required="true" name="reqCompetent" value="<?= $reqCompetent ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqPicPath" class="control-label col-md-2">Pic Path</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqPicPath" id="reqPicPath" style="width: 100%;"><?= $reqPicPath; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDateArrive" class="control-label col-md-2">Date Arrive</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="date" id="reqDateArrive" class="easyui-validatebox textbox form-control" required="true" name="reqDateArrive" value="<?= $reqDateArrive ?>" style=" width:100%" />
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
                url: 'web/pms_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    $.messager.alertLink('Info', data, 'info', "app/index/pms");
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
</div>
</div>