<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("DocumentTender");
$this->load->model("TenderTypeUpload");
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

    <div class="judul-halaman"> <a href="app/index/tender_dok">Monitoring  Standart Dokumen Tender </a> &rsaquo; Document Tender
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <?
        if(!empty($reqId)){
        ?>
         <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_type_tender('dokument_adminitrasi', '<?=$reqId?>')"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Dokumen Administrasi</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_type_tender('dokument_teknis', '<?=$reqId?>')"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Dokumen Teknis</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_type_tender('dokument_komersial', '<?=$reqId?>')"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Dokumen Komersial</span> </a>
        <?
        }
        ?>
      
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
                    if(!empty($reqId)){
                    ?>
                    <div class="form-group">
                        <?
                        $arrField = array('Dokumen  Administrasi','Dokumen  Teknis','Dokumen  Komersial');
                        $arrMenu = array('dokument_adminitrasi','dokument_teknis','dokument_komersial');
                        // $arrMenuAlias = array('dokument_adminitrasi','dokument_teknis','dokument_komersial');
                        for($kk=0;$kk<count($arrField);$kk++){
                        ?>
                         <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i><?=$arrField[$kk]?></h3>
                        </div>

                   
                            <?
                              $tender_type_upload = new TenderTypeUpload();
                              $tender_type_upload->selectByParamsMonitoring(array("TYPE" => $arrMenu[$kk],"CAST(A.TENDER_ID AS VARCHAR)"=>$reqId));
                              while ($tender_type_upload->nextRow()) {
                                  $reqTypeId = $tender_type_upload->getField("TENDER_TYPE_UPLOAD_ID");
                                  $reqJenis           = $tender_type_upload->getField("NAME");
                                  $reqDescription     = $tender_type_upload->getField("DESCRIPTION");
                             
                            ?>
                            <div class="form-group">
                                <label for="reqCompanyName" class="control-label col-md-2"><?=$reqJenis?></label>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                     <th width="90%"> File Name <a onclick="tambahPenyebab('', '<?=$arrMenu[$kk]?>', '<?=$reqTypeId?>', '<?=$reqJenis?>')" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>
                                                    <th width="10%"> Action </th>          

                                                    </tr>
                                                </thead>
                                                 <tbody id="tambahAttacment<?=$arrMenu[$kk]?><?=$reqTypeId?>">

                                                <?
                                                $reqTipes = $arrMenu[$kk];
                                                $reqDocTenderPathJson    = $document->getField("PATH".($kk+1));
                                                $reqDocTenderPath        = json_decode($reqDocTenderPathJson, true);
                                                for ($i = 0; $i < count($reqDocTenderPath); $i++) 
                                                {
                                                    if($reqDocTenderPath[$i]["type"] == $reqJenis && $reqDocTenderPath[$i]["file"] != "")
                                                    {
                                                        $reqTypeDocTender = $reqDocTenderPath[$i]["type"];
                                                        $reqFileDocTender = $reqDocTenderPath[$i]["file"];
                                                        $ext = substr($reqFileDocTender, -3);
														 $texts = explode('-', $reqFileDocTender);
														$text ='';
									for($kk=3;$kk<count($texts);$kk++){
										$text .=$texts[$kk];
									}
                                                ?>
                                                        <tr>
                                                            <input type="hidden" style="width:100%" name="reqType<?=$arrMenu[$kk]?>[]" value="<?= $reqTypeDocTender ?>" />
                                                            <td>
                                                                <input type="file" onchange="getFileName(this, '<?=($i+1)?>', '<?=$arrMenu[$kk]?>', '<?=$reqTypeId?>', '<?=$reqJenis?>')" name="reqLinkFile<?=$arrMenu[$kk]?>[]" multiple class="form-control" style="width: 90%">
                                                                <input type="hidden" name="reqLinkFile<?=$arrMenu[$kk]?>Temp[]" value="<?= $reqFileDocTender ?>">
                                                                <?if ($ext !=='pdf')
                                                                {
                                                                ?>
                                                                  <a href="uploads/<?=$reqTipes?>/<?= $reqId ?>/<?= $reqFileDocTender ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=$arrMenu[$kk]?><?=$reqTypeId?><?=($i+1)?>"> <?= $text ?> </span>
                                                                <?
                                                                }
                                                                else
                                                                {
                                                                ?>
                                                                  <a onclick="openAdd(`uploads/<?=$reqTipes?>/<?= $reqId ?>/<?= $reqFileDocTender ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=$arrMenu[$kk]?><?=$reqTypeId?><?=($i+1)?>"> <?= $text ?> </span>
                                                                <?
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                                        </tr>
                                                <?
                                                    }
                                                }
                                                ?>

                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?
                            }
                            ?>

                    
                      <?
                        }
                      ?>

                    </div>
                    <?
                        }
                    ?>
                      
           

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
        
          async function tambahPenyebab(filename='',type='',typeId='',jenis='') {
            return new Promise(function(resolve, reject) {
                var id = $('#tambahAttacment'+type+typeId+' tr').length+1;
                $.get("app/loadUrl/app/tempalate_row_attacment_tender?id="+id+"&filename="+filename+"&type="+type+"&typeId="+typeId+"&jenis="+jenis, function(data) {
                    $("#tambahAttacment"+type+typeId).append(data);
                    resolve(true);
                });
            });
        }

        async function getFileName(input, id, type, typeId='', jenis='') {
            for (var i = 0; i < input.files.length; i++) {
                if(i == 0){
                    $("#namaFile"+type+typeId+id).html(input.files[0].name);
                    if(input.files.length > 1){
                        for (var j = 0; j < input.files.length - 1; j++) {
                            $("#namaFile"+type+typeId+id).append(`<input type="hidden" style="width:100%" name="reqType${type}[]"  value="${jenis}" />`);
                        }
                    }
                }
                else{
                    await tambahPenyebab(encodeURIComponent(input.files[i].name), type, typeId,jenis)
                }
            }
        }
            
    </script>

</div>

</div>