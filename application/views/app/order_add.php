<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("DokumenOther");
$dokumen_other = new DokumenOther();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
    $reqTanggal = date("d-m-Y");
    $reqJam = date("H:i");
} else {
    $reqMode = "ubah";
    $dokumen_other->selectByParams(array("A.DOCUMENT_ID" => $reqId));
    $dokumen_other->firstRow();
    $reqId                           = $dokumen_other->getField("DOCUMENT_ID");
    $reqCategoryId                    = $dokumen_other->getField("CATEGORY_ID");
    $reqName                         = $dokumen_other->getField("NAME");
    $reqDescription                  = $dokumen_other->getField("DESCRIPTION");
    $reqPath                         = $dokumen_other->getField("PATH");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css">

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/others">Menu Document Others</a> &rsaquo; Form Menu Document Others
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_format()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Format</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>

                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry Document of OTHER
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqCategoryId" class="control-label col-md-2">Format</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqCategoryId" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/combo_categori_other'" value="<?= $reqCategoryId ?>" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">Nama</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" name="reqName" value="<?= $reqName ?>" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Description</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqDescription" style="width:100%; height:200px"><?= $reqDescription ?></textarea>
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
                                              <a href="uploads/orders/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $text  ?> </span>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                              <a onclick="openAdd(`uploads/orders/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $text  ?> </span>
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

                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqTipe" value="Orders" />

                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()">Clear</a>
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
                url: 'web/dokumen_other_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');
                    //alert(data);
                     if(datas[0]=='xxx'){
                             show_toast('error', 'Have troube in ',  datas[1]);
                            $.messager.alert('Info', datas[1], 'info'); 
                    }else{
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/order_add?reqId=" + datas[0]);
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
    </script>

</div>

</div>