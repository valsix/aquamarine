<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("LampiranData");
$personal_certificate= new LampiranData();

$reqId = $this->input->get("reqId");
$reqModul = $this->input->get("reqModul");


$lampirandata = new LampiranData();
$lampirandata->selectByParamsMonitoring(array('A.MODUL'=>$reqModul,'A.MODUL_ID'=>$reqId));
$arrData = $lampirandata->rowResult;

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
        #tablei tr td{
            color: black;
            font-weight: bold;
            padding: 5px;
            border-bottom: 1px solid black;
        }

</style>

<div class="col-md-12">

    <div class="judul-halaman">Lampiran  </div>

    
    <div class="konten-area">
        <div class="konten-inner">
            <div>
                 <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                 

                     <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Lampiran </h3>
                    </div>
                      <table style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="90%"> File Name <a onclick="addCerificate()" id="addCerificate" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>

                                    <th width="10%"> Action </th>
                                </tr>
                            </thead>
                            <tbody id="tambahCerificate">
                                <?
                               
                                // for ($i = 0; $i < count($files_data); $i++) {
                                foreach ($arrData  as $value) {
                                    # code...
                                 $files_data = explode(';',  $value['nama']);
                                 $i=0;
                                 $rand=rand();
                                    if (!empty($files_data[$i])) {
                                        $texts = explode('-', $files_data[$i]);
                                        $ext = substr($files_data[$i], -3);
                                ?>
                                        <tr>

                                            <td>
                                                <input type="file" onchange="getFileNameCertificate(this, '<?=($rand)?>')" name="reqLinkFileCertificate[]" multiple class="form-control" style="width: 90%">
                                                <input type="hidden" name="reqLinkFileCertificateTemp[]" value="<?= $files_data[$i] ?>">
                                                  <input type="hidden" name="reqLinkFileLampiranId[]" id="reqLinkFileCertificateTemp<?=$rand?>" value="<?=$value['lampiran_data_id']?>">
                                                <?if ($ext !=='pdf')
                                                {
                                                ?>
                                                  <a href="uploads/lampiran/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileCertificate<?=($rand)?>"> <?=  $value['keterangan'] ?> </span>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                  <a onclick="openAdd(`uploads/lampiran/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileCertificate<?=($rand)?>"> <?=  $value['keterangan'] ?> </span>
                                                <?
                                                }
                                                ?>
                                            </td>

                                            <td><a onclick="deleteData('web/master_lampiran_json/delete',<?=$value['lampiran_data_id']?>)"><i class="fa fa-trash fa-lg"></i></a> </td>
                                        </tr>
                                <?
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
                    <br>
                  

                      <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />
                     <input type="hidden" name="reqModul" value="<?= $reqModul ?>" />
                    <div style="text-align:center;padding:5px">
                        <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                    </div>
                </form>

                  <?
                    

                  

                    ?>
                 

            </div>
        </div>
    </div>

      

    </div>
    
</div>
<script type="text/javascript">
      function submitForm() {
            $('#ff').form('submit', {
                url: 'web/master_lampiran_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                   
                    //alert(data);
                    $.messager.alertReload('Info', data, 'info');
                }
            });
        }
        function hapus(id){
        deleteData('web/master_combo_json/delete',id);
    }
        function clearForm() {
            $('#ff').form('clear');
        }

        function tambahPenyebab(id){
            window.location.href="app/loadUrl/app/tempalate_master_combo?reqId="+id;

        }

        function addCerificate(filename='') {
            var id = $('#tambahCerificate tr').length+1;
            var data = `<tr>
                <td><input type="file" onchange="getFileNameCertificate(this, '${id}')" name="reqLinkFileCertificate[]" multiple id="reqLinkFileCertificate${id}" class="form-control">
                  <input type="hidden" name="reqLinkFileCertificateTemp[]" id="reqLinkFileCertificateTemp${id}" value="">
                  <input type="hidden" name="reqLinkFileLampiranId[]" id="reqLinkFileCertificateTemp${id}" value="">
                  <span style="margin-left: 50px" class="nama-temp-file" id="namaFileCertificate${id}">${filename}</span>
                </td>

                <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
            </tr>
            `;
            $("#tambahCerificate").append(data);
        }

        function getFileNameCertificate(input, id) {
            for (var i = 0; i < input.files.length; i++) {
                if(i == 0)
                    $("#namaFileCertificate"+id).html(input.files[0].name);
                else
                    addCerificate((input.files[i].name))
            }
            
        }
</script>

 <script>
        window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>

