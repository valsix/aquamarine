<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("DocumentTender");
$document = new DocumentTender();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
    $reqTanggal = date("d-m-Y");
    $reqJam = date("H:i");
} else {
    $reqMode = "ubah";
    $document->selectByParamsMonitoring(array("A.DOCUMENT_TENDER_ID" => $reqId));
    $document->firstRow();
    $reqId                   = $document->getField("DOCUMENT_TENDER_ID");
    $reqName                 = $document->getField("NAME");
    $reqDescription          = $document->getField("DESCRIPTION");
    $reqPath1                 = $document->getField("PATH1");
    $reqPath2                 = $document->getField("PATH2");
     $reqPath3                 = $document->getField("PATH3");
}
$reqTipe = 'dokument_tender';
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css">

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/tender_dok">Monitoring Document Tender </a> &rsaquo; Document Tender
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
         <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_type_tender('dokument_adminitrasi', '<?=$reqId?>')"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Dokument Adminitrasi</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_type_tender('dokument_teknis', '<?=$reqId?>')"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Dokument Teknis</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_type_tender('dokument_komersial', '<?=$reqId?>')"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Dokument Komersial</span> </a>
      
    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>

                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry Document Tender
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>




                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">Nama</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama" value="<?= $reqName ?>" data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqKeterangan" class="control-label col-md-2">Description</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqKeterangan" style="width:100%; height:200px" class="form-control"><?= $reqDescription ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    
                      <?
                    $arrField = array('Dokument  Adminitrasi','Dokument  Teknis','Dokument  Komersial');
                    $arrColomn = array('PATH1','PATH2','PATH3');
                    // $reqDok2  = $tender->getField("DOK_ADMINITISTRASI");
                    // $reqDok3  =  $tender->getField("DOK_TEKNIS");
                    // $reqDok4  =  $tender->getField("DOK_KOMERSIAL");
                    $FieldRow=2;
                    for($kk=0;$kk<count($arrField);$kk++){
                    ?>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i><?=$arrField[$kk]?></h3>
                    </div>
                    <table style="width: 100%" class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="80%"> File Name <a onclick="tambahPenyebab('','<?=$FieldRow?>')" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>
                                <th width="10%"> Type </th>
                                <th width="10%"> Action </th>
                            </tr>
                        </thead>
                        <tbody id="tambahAttacment<?=$FieldRow?>">
                            <?
                            $files_data = explode(';',  $document->getField($arrColomn[$kk]));
                            for ($i = 0; $i < count($files_data); $i++) {
                                if (!empty($files_data[$i])) {
                                    $texts = explode('-', $files_data[$i]);
                                    $ext = substr($files_data[$i], -3);
                            ?>
                                    <tr>

                                        <td>
                                            <input type="file" onchange="getFileName(this, '<?=($i+1)?>')" name="document<?=$FieldRow?>[]" multiple class="form-control" style="width: 90%">
                                            <input type="hidden" name="reqLinkFileTemp<?=$FieldRow?>[]" value="<?= $files_data[$i] ?>">
                                            <?if ($ext !=='pdf')
                                            {
                                            ?>
                                              <a href="uploads/<?= $reqTipe ?>/<?= $reqId ?>/<?=$FieldRow?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                              <a onclick="openAdd(`uploads/<?= $reqTipe ?>/<?= $reqId ?>/<?=$FieldRow?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
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
                   <?
                   $FieldRow++;
                    }
                   ?>

           <!-- </form> -->

                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqTipe" value="<?=$reqTipe?>" />

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
                url: 'web/dokument_tender_json/add',
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
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/tender_dok_add?reqId=" + datas[0]);
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
        function tambahPenyebab(filename='',idField='') {
            var id = $('#tambahAttacment'+idField+' tr').length+1;
            $.get("app/loadUrl/app/tempalate_row_attacment_with_field_nama?id="+id+"&filename="+idField, function(data) {
                $("#tambahAttacment"+idField).append(data);
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