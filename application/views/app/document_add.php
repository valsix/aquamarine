<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("DokumenMarketing");
$dokumen_marketing = new DokumenMarketing();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $statement = " AND A.DOCUMENT_ID = " . $reqId;
    $dokumen_marketing->selectByParamsMonitoring(array(), -1, -1, $statement);

    $dokumen_marketing->firstRow();
    $reqDocumentId              = $dokumen_marketing->getField("DOCUMENT_ID");
    $reqCompanyName             = $dokumen_marketing->getField("COMPANY_NAME");
    $reqVesselName              = $dokumen_marketing->getField("VESSEL_NAME");
    $reqDescription             = $dokumen_marketing->getField("DESCRIPTION");
    $reqPath                    = $dokumen_marketing->getField("PATH");
    $reqLastRevisi              = $dokumen_marketing->getField("LAST_REVISI");
    $reqTypeOfService           = $dokumen_marketing->getField("TYPE_OF_SERVICE");
    $reqLocation                = $dokumen_marketing->getField("LOCATION");
    $reqDateOperation           = $dokumen_marketing->getField("DATE_OPERATION");
    $reqClassRules              = $dokumen_marketing->getField("CLASS_RULES");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/document"> Experience List</a> &rsaquo; Form Experience List
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry Document of Marketing
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqCompanyName" class="control-label col-md-2">Company Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCompanyName" class="easyui-validatebox textbox form-control" name="reqCompanyName" value="<?= $reqCompanyName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Vessel Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqVesselName" class="easyui-validatebox textbox form-control" name="reqVesselName" value="<?= $reqVesselName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqTypeOfService" class="control-label col-md-2">Type of Service</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqTypeOfService" data-options="width:'350',editable:false, valueField:'id',textField:'text',url:'combo_json/comboTypeOfService'" value="<?= $reqTypeOfService ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqLocation" class="control-label col-md-2">Location</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLocation" class="easyui-validatebox textbox form-control" name="reqLocation" value="<?= $reqLocation ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqPath" class="control-label col-md-2">Date</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqDateOperation" id="reqDateOperation" value="<?= $reqDateOperation ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Class</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqClassRules" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel'" value="<?= $reqClassRules ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Description</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqDescription" cols="4" rows="3" style="width:100%;"><?= $reqDescription; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <table style="width: 100%" class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="80%"> File Name <a onclick="tambahPenyebab()" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>
                                <th width="10%"> Type </th>
                                <th width="10%"> Action </th>
                            </tr>
                        </thead>
                        <tbody id="tambahAttacment">
                            <?
                            $files_data = explode(',',  $reqPath);
                            for ($i = 0; $i < count($files_data); $i++) {
                                if (!empty($files_data[$i])) {
                                    $texts = explode('-', $files_data[$i]);
                                    $ext = substr($files_data[$i], -3);
                            ?>
                                    <tr>

                                        <td>
                                            <input type="file" name="document[]" class="form-control" style="width: 90%">
                                            <input type="hidden" name="reqLinkFileTemp[]" value="<?= $files_data[$i] ?>">
                                            <a onclick="openAdd('uploads/doc_marketing/<?= $reqId ?>/<?= $files_data[$i] ?>');" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file"> <?= $texts[2] ?> </span>
                                        </td>
                                        <td><?=strtoupper($ext)?></td>
                                        <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                    </tr>
                            <?
                                }
                            }
                            ?>

                        </tbody>
                    </table>
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
                url: 'web/dokumen_marketing_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/document_add?reqId=" + datas[0]);
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>

    <script type="text/javascript">
        function tambahPenyebab() {
            $.get("app/loadUrl/app/tempalate_row_attacment?", function(data) {
                $("#tambahAttacment").append(data);
            });
        }
    </script>
</div>
</div>