<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("EquipRepair");
$jenis_kualifikasi = new EquipRepair();

$reqId = $this->input->get("reqId");
$reqEquipId = $this->input->get("reqEquipId");
if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $statement = " AND A.EQUIP_REPAIR_ID = " . $reqId." AND A.EQUIP_ID=".$reqEquipId;
    $jenis_kualifikasi->selectByParamsMonitoring(array(), -1, -1, $statement);

    $jenis_kualifikasi->firstRow();
    $reqId              = $jenis_kualifikasi->getField("EQUIP_REPAIR_ID");
    $reqEquipId         = $jenis_kualifikasi->getField("EQUIP_ID");
    $reqName            = $jenis_kualifikasi->getField("REPAIR_BY");
    $reqDateAwal     = $jenis_kualifikasi->getField("TANGGAL_AWAL");
    $reqDateAkhir    = $jenis_kualifikasi->getField("TANGGAL_AKHIR");
    $reqALamat          = $jenis_kualifikasi->getField("KETERANGAN");
   
    $reqInvoicePath     =  $jenis_kualifikasi->getField("PATH_FILE");
}


?>
<base href="<?= base_url(); ?>" />
<link href="libraries/bootstrap-3.3.7/docs/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="libraries/bootstrap-3.3.7/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="libraries/bootstrap-3.3.7/docs/examples/sticky-footer-navbar/sticky-footer-navbar.css" rel="stylesheet">

<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="libraries/bootstrap-3.3.7/docs/assets/js/ie-emulation-modes-warning.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<link rel="stylesheet" href="css/halaman.css" type="text/css">
<link rel="stylesheet" href="css/gaya-egateway.css" type="text/css">
<link rel="stylesheet" href="css/gaya-affix.css" type="text/css">
<link rel="stylesheet" href="css/gaya-pagination.css" type="text/css">
<link rel="stylesheet" href="css/gaya-datatable-egateway.css" type="text/css">
<link rel="stylesheet" href="libraries/font-awesome-4.7.0/css/font-awesome.css">

<script type='text/javascript' src="libraries/bootstrap/js/jquery-1.12.4.min.js"></script>


<!-- DATATABLE -->
<link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/extensions/Responsive/css/dataTables.responsive.css">
<link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/examples/resources/syntax/shCore.css">
<link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/examples/resources/demo.css">

<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/fnReloadAjax.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/examples/resources/demo.js"></script>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />
<!-- EASYUI 1.4.5 -->
<link rel="stylesheet" type="text/css" href="libraries/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="libraries/easyui/themes/icon.css">
<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="libraries/easyui/globalfunction.js"></script>
<script type="text/javascript" src="libraries/easyui/kalender-easyui.js"></script>

<script src="libraries/tinyMCE/tinymce.min.js"></script>

<script type="text/javascript">
    tinymce.init({
        selector: ".tinyMCES",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        menubar: true,

    });
</script>


<?php
// Header Nama TABEL TH


?>

<style type="text/css">
    #tablei {
        background-color: white;
    }

    #tablei tr td {
        color: black;
        font-weight: bold;
        padding: 5px;
        border-bottom: 1px solid black;
    }
</style>

<div class="col-md-12">

    <div class="judul-halaman">REPAIR EQUIPMENT </div>


    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">



                    <div class="page-header" style="height:50px">
                        <h3><i class="fa fa-file-text fa-lg"></i> REPAIR DETAIL
                            <div class="btn-group pull-right " style="margin-right: 10px">

                        </h3>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2"> Repair By</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqName" id="reqName" value="<?= $reqName ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                    </div>
                       <div class="form-group">
                        <label for="reqContact" class="control-label col-md-2"> Tanggal Repair</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDateAwal" class="easyui-datebox form-control" name="reqDateAwal" value="<?=$reqDateAwal?>"  style=" width:200px" />

                                </div>
                            </div>
                        </div>
                         <label for="reqEmail" class="control-label col-md-2"> Tanggal Selesai</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <input type="text" id="reqDateAkhir" class="easyui-datebox form-control" name="reqDateAkhir" value="<?=$reqDateAkhir?>"  style=" width:200px" />

                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="reqALamat" class="control-label col-md-2">Description</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control" name="reqALamat" cols="4" rows="3" style="width:100%;"><?= $reqALamat; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                    <br>


                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqEquipId" value="<?= $reqEquipId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                 

                <?




                ?>
               <div style="padding: 10px">
                        
                        <table style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="90%"> File Name <a onclick="addInvoice()" id="addInvoice" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>

                                    <th width="10%"> Action </th>
                                </tr>
                            </thead>
                            <tbody id="tambahInvoice">
                                <?
                                $files_data = explode(';',  $reqInvoicePath);
                                for ($i = 0; $i < count($files_data); $i++) {
                                    if (!empty($files_data[$i])) {
                                        $texts = explode('-', $files_data[$i]);
                                        $ext = substr($files_data[$i], -3);
                                ?>
                                        <tr>

                                            <td>
                                                <input type="file" onchange="getFileNameInvoice(this, '<?=($i+1)?>')" name="reqLinkFileInvoice[]" multiple class="form-control" style="width: 90%">
                                                <input type="hidden" name="reqLinkFileInvoiceTemp[]" value="<?= $files_data[$i] ?>">
                                                <?if ($ext !=='pdf')
                                                {
                                                ?>
                                                  <a href="uploads/equipment_repair/<?= $reqEquipId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileInvoice<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                  <a onclick="openAdd(`uploads/equipment_repair/<?= $reqEquipId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileInvoice<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
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

                       <div style="text-align:center;padding:5px">
                        <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                    </div>
                </form>
            </div>
        </div>
    </div>



</div>

</div>
<script type="text/javascript">
    function submitForm() {
        $('#ff').form('submit', {
            url: 'web/equip_repair_json/add',
            onSubmit: function() {
                return $(this).form('enableValidation').form('validate');
            },
            success: function(data) {
                var datas = data.split('-');

                //alert(data);
                $.messager.alertLink('Info', datas[1], 'info', "app/loadUrl/app/tempalate_repair_euipment?reqEquipId=<?=$reqEquipId?>&reqId="+datas[0]);
                top.reload_page();
            }
        });
    }

    function clearForm() {
        $('#ff').form('clear');
    }

    function hapus(id){
        deleteData('web/equip_repair_json/delete',id);        
    }

    function tambahPenyebab(id) {
        window.location.href = "app/loadUrl/app/tempalate_master_surveyor?reqId=" + id;

    }
     function print() {
       
        openAdd('app/loadUrl/report/surveyor_pdf');
    }
</script>

<script>
    window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
</script>
<script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
 <!-- EMODAL -->
    <script src="libraries/emodal/eModal.js"></script>
    <script src="libraries/emodal/eModal-cabang.js"></script>
<script type="text/javascript">
     function openAdd(pageUrl) {
            eModal.iframe(pageUrl, 'Office Management | PT Aquamarine Divindo Inspection')
        }

            function addInvoice(filename='') {
            var id = $('#tambahInvoice tr').length+1;
            var data = `<tr>
                <td><input type="file" onchange="getFileNameInvoice(this, '${id}')" name="reqLinkFileInvoice[]" multiple id="reqLinkFileInvoice${id}" class="form-control">
                  <input type="hidden" name="reqLinkFileInvoiceTemp[]" id="reqLinkFileInvoiceTemp${id}" value="">
                  <span style="margin-left: 50px" class="nama-temp-file" id="namaFileInvoice${id}">${filename}</span>
                </td>

                <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
            </tr>
            `;
            $("#tambahInvoice").append(data);
        }

        function getFileNameInvoice(input, id) {
            for (var i = 0; i < input.files.length; i++) {
                if(i == 0)
                    $("#namaFileInvoice"+id).html(input.files[0].name);
                else
                    addInvoice((input.files[i].name))
            }
            
        }

</script>