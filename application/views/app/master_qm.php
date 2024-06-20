<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("DokumenQm");
$dokumen_qm = new DokumenQm();

$reqId = $this->input->get("reqId");
$reqTipe = 'QMS';

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";

    $dokumen_qm->selectByParams(array("A.DOCUMENT_ID" => $reqId));
    // echo  $dokumen_qm->query;exit;
    $dokumen_qm->firstRow();

    $reqDocumentId      = $dokumen_qm->getField("DOCUMENT_ID");
    $reqType            = $dokumen_qm->getField("TYPE");
    $reqFormatId        = $dokumen_qm->getField("FORMAT_ID");
    $reqName            = $dokumen_qm->getField("NAME");
    $reqDescription     = $dokumen_qm->getField("DESCRIPTION");
    $reqPath            = $dokumen_qm->getField("PATH");
    $reqLastRevisi      = $dokumen_qm->getField("LAST_REVISI");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/qms"> Qms</a> &rsaquo; Form Qms</div>

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
                        <label for="reqFormatId" class="control-label col-md-2">Format</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqFormatId" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'combo_json/comboQms'" value="<?= $reqFormatId ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqFormat" class="easyui-validatebox textbox form-control"  name="reqName" value="<?= $reqName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Description</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" id="reqDescription" class="easyui-validatebox textbox form-control"  name="reqDescription" value="" style=" width:100%" ><?=$reqDescription?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                      <br>
                    <table style="width: 100%" class="table table-bordered" >
                        <thead>
                            <tr>
                                <th width="50%"> Nama</th>

                                <th width="50%"> Description </th>
                            </tr>
                        </thead>
                        <tbody id="tambahAttacment">
                            <?
                            $files_data =explode(';',  $reqPath);
                            for($i=0;$i<count($files_data);$i++){
                                if(!empty($files_data[$i])){
                                    $texts = explode('-', $files_data[$i]);
                                    $ext = substr($files_data[$i], -3);
                            ?>
                            <tr>

                                <td>
                                     <input type="file" onchange="getFileName(this, '<?=($i+1)?>')" name="document[]" multiple class="form-control" style="width: 90%" >
                                     <input type="hidden" name="reqLinkFileTemp[]" value="<?=$files_data[$i]?>">

                                    <?if ($ext !=='pdf')
                                    {
                                    ?>
                                      <a href="uploads/<?=$reqTipe?>/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                    <?
                                    }
                                    else
                                    {
                                    ?>
                                      <a onclick="openAdd(`uploads/<?=$reqTipe?>/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                    <?
                                    }
                                    ?>
                                 </td>
                                
                                <td><a onclick="$(this).parent().parent().remove();" ><i class="fa fa-trash fa-lg"></i></a>  </td>
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
            $('#ff').form('submit', {
                url: 'web/qms_json/add_new',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/qms_add?reqId="+datas[0]);
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
    </script>
</div>
</div>