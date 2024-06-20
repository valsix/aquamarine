<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("ReportDepartment");
$report_department = new ReportDepartment();

$this->load->model("ReportDepartmentTemplate");
$template = new ReportDepartmentTemplate();

$reqId = $this->input->get("reqId");
$reqTipe = 'Certificate';
if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $report_department->selectByParamsMonitoring(array("A.REPORT_DEPARTMENT_ID" => $reqId));
    $report_department->firstRow();
    $reqDepartment          = $report_department->getField("DEPARTMENT");
    $reqProject             = $report_department->getField("PROJECT");
    $reqClient              = $report_department->getField("CLIENT");
    $reqSendDate            = $report_department->getField("SEND_DATE");
    $reqReceivedDate        = $report_department->getField("RECEIVE_DATE");
    $reqDescription         = $report_department->getField("DESCRIPTION");
    $reqPath                = $report_department->getField("PATH");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />
<style type="text/css">
    table#template td {
        padding-top: 10px;
        padding-left: 30px;
    }
</style>

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/report_department"> Report Department </a> &rsaquo; Form Report Department
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_template_report()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Template</span> </a>
    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Template Report Department
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>
                        </h3>
                        <br>
                    </div>

                    <table id="template">
                        <?php
                        $template->selectByParamsMonitoring(array());
                        while ($template->nextRow()) 
                        {
                            $reqNama    = $template->getField("NAMA");
                            $reqLink    = $template->getField("LINK");
                        ?>
                        <tr>
                            <td><?=$reqNama?></td>
                            <td><a href="<?=$reqLink?>" target="_blank" class=""> Download  </a></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry Report Department
                        </h3>
                    </div> 

                    <div class="form-group">
                        <label for="reqDepartment" class="control-label col-md-2">Department</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqDepartment" data-options="width:'230',editable:false, valueField:'id',textField:'text',url:'combo_json/comboDepartment'" value="<?= $reqDepartment ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqProject" class="control-label col-md-2">Project</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqProject" class="easyui-validatebox textbox form-control" name="reqProject" value="<?= $reqProject ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqClient" class="control-label col-md-2">Client</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqClient" class="easyui-validatebox textbox form-control" name="reqClient" value="<?= $reqClient ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqSendDate" class="control-label col-md-2">Send Date </label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqSendDate" class="easyui-datebox textbox form-control" name="reqSendDate" value="<?= $reqSendDate ?>" style=" width:190px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqReceivedDate" class="control-label col-md-2">Received Date </label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqReceivedDate" class="easyui-datebox textbox form-control" name="reqReceivedDate" value="<?= $reqReceivedDate ?>" style=" width:190px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Description </label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" rows="3" cols="2" id="reqDescription" class=" textbox form-control" name="reqDescription" style=" width:100%"><?= $reqDescription ?></textarea>
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
                            $files_data = explode(';',  $reqPath);
                            for ($i = 0; $i < count($files_data); $i++) {
                                if (!empty($files_data[$i])) {
                                    $texts = explode('-', $files_data[$i]);
                                    $ext = substr($files_data[$i], -3);
									
									$text ='';
									for($kk=2;$kk<count($texts);$kk++){
										$text .=$texts[$kk];
									}
                            ?>
                                    <tr>

                                        <td>
                                            <input type="file" onchange="getFileName(this, '<?=($i+1)?>')" name="document[]" multiple class="form-control" style="width: 90%">
                                            <input type="hidden" name="reqLinkFileTemp[]" value="<?= $files_data[$i] ?>">
                                            <?if ($ext !=='pdf')
                                            {
                                            ?>
                                              <a href="uploads/report_department/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $text ?> </span>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                              <a onclick="openAdd(`uploads/report_department/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $text ?> </span>
                                            <?
                                            }
                                            ?>
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
                    <input type="hidden" name="reqTipe" value="<?= $reqTipe ?>" />
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
              var win = $.messager.progress({
            title: 'Office Management  | PT Aquamarine Divindo',
            msg: 'proses data...'
        });
            $('#ff').form('submit', {
                url: 'web/report_department_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                     if(datas[0]=='xxx'){
                             show_toast('error', 'Have troube in ',  datas[1]);
                            $.messager.alert('Info', datas[1], 'info'); 
                    }else{
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/report_department_add?reqId=" + datas[0]);
                    }
                     $.messager.progress('close'); 
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
    <script type="text/javascript">
        function tambahPenyebab(filename='') {
            var id = $('#tambahAttacment tr').length+1;
            $.get("app/loadUrl/app/tempalate_row_attacment?id="+id+"&filename="+filename, function(data) {
                $("#tambahAttacment").append(data);
            });
        }

        function getFileName(input, id) {
            for (var i = 0; i < input.files.length; i++) {
                if(i == 0)
                {
                    $("#namaFile"+id).html(input.files[0].name);
                    var ext = input.files[0].name.split('.').pop();
                    ext = ext.toUpperCase();
                    if(ext.length > 3) ext = '';
                    if(ext == 'PNG' || ext == 'JPG' || ext == 'JPEG' || ext == 'BMP') ext = 'IMAGE'
                    $("#namaFile"+id).parent().next().html(ext);
                }
                else
                    tambahPenyebab(encodeURIComponent(input.files[i].name))
            }
            
        }

        function master_template_report() {
            openAdd("app/loadUrl/app/tempalate_report_department");
        }
    </script>
</div>
</div>