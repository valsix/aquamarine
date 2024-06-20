<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("DokumenReport");
$dokumen_report = new DokumenReport();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $dokumen_report->selectByParams(array("DOCUMENT_ID" => $reqId));
    $dokumen_report->firstRow();

    $reqDocumentId                = $dokumen_report->getField("DOCUMENT_ID");
    $reqReportId                  = $dokumen_report->getField("REPORT_ID");
    $reqName                      = $dokumen_report->getField("NAME");
    $reqDescription               = $dokumen_report->getField("DESCRIPTION");
    $reqPath                      = $dokumen_report->getField("PATH");
    $reqStartDate                 = $dokumen_report->getField("START_DATE");
    $reqFinishDate                = $dokumen_report->getField("FINISH_DATE");
    $reqDeliveryDate              = $dokumen_report->getField("DELIVERY_DATE");
    $reqInvoiceDate               = $dokumen_report->getField("INVOICE_DATE");
    $reqReason                    = $dokumen_report->getField("REASON");
}
// echo "oke";
// exit;
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/report"> Report Survey</a> &rsaquo; Form Report Survey
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry Document of Report Survey
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>
                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Type of Report</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqReportId" data-options="width:'290',editable:false, valueField:'id',textField:'text',url:'combo_json/comboReport'" value="<?= $reqReportId ?>" />

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Company Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqName" class="easyui-textbox textbox form-control" name="reqName" value="<?= $reqName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Vessel Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqName" class="easyui-textbox textbox form-control" name="reqDescription" value="<?= $reqDescription ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Start Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqName" class="easyui-datebox textbox form-control" name="reqStartDate" value="<?= $reqStartDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                        <label for="reqName" class="control-label col-md-2">Delevery Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDeliveryDate" class="easyui-datebox textbox form-control" name="reqDeliveryDate" value="<?= $reqDeliveryDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Finish Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqFinishDate" class="easyui-datebox textbox form-control" name="reqFinishDate" value="<?= $reqFinishDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                        <label for="reqName" class="control-label col-md-2">Invoice Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInvoiceDate" class="easyui-datebox textbox form-control" name="reqInvoiceDate" value="<?= $reqInvoiceDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Reason</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" id="reqName" class="easyui-textbox textbox form-control" name="reqReason" style=" width:100%"><?= $reqReason ?></textarea>
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
                            ?>
                                    <tr>

                                        <td>
                                            <input type="file" name="document[]" class="form-control" style="width: 90%">
                                            <input type="hidden" name="reqLinkFileTemp[]" value="<?= $files_data[$i] ?>">
                                            <a onclick="openAdd('uploads/report/<?= $reqId ?>/<?= $files_data[$i] ?>');" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file"> <?= $texts[2] ?> </span>
                                        </td>

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
                    <input type="hidden" name="reqTipe" value="Report" />

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
                url: 'web/dokument_report_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');
                    //alert(data);
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/report_add?reqId=" + datas[0]);
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