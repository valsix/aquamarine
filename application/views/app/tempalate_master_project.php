<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("MasterProject");
$personal_certificate= new MasterProject();

$reqId = $this->input->get("reqId");

if($reqId == ""){
$reqMode = "insert";
}else{
$reqMode = "ubah";
$statement= " AND A.MASTER_PROJECT_ID  = ".$reqId;
$personal_certificate->selectByParamsMonitoring(array(), -1,-1, $statement);

$personal_certificate->firstRow();
$reqId          = $personal_certificate->getField("MASTER_PROJECT_ID");
$reqJenis = $personal_certificate->getField("NAMA");
$reqDescription= $personal_certificate->getField("KETERANGAN");
$reqCode = $personal_certificate->getField("CODE");
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
        #tablei tr td{
            color: black;
            font-weight: bold;
            padding: 5px;
            border-bottom: 1px solid black;
        }

</style>

<div class="col-md-12">

    <div class="judul-halaman">Master of Project </div>

    
    <div class="konten-area">
        <div class="konten-inner">
            <div>
                 <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                 

                     <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Master Project</h3>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">  Code</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqCode" id="reqCode" value="<?= $reqCode ?>" style=" width:100%" />
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">  Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqJenis" id="reqJenis" value="<?= $reqJenis ?>" style=" width:100%" />
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="form-group">
                        <label for="reqPhone" class="control-label col-md-2">No Po</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input type="text" class="easyui-validatebox textbox form-control" name="reqDescription" id="reqDescription" value="<?= $reqDescription ?>" style=" width:100%" />
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                  

                      <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                    <div style="text-align:center;padding:5px">
                        <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                    </div>
                </form>

                  <?
                    

                  

                    ?>
                    <table style="width: 100%" id="tablei" class="table table-striped table-hover dt-responsive" style="background: white" >
                        <thead>
                        <tr>
                            <th width="10%">Aksi   </th>
                             <th width="20%">Code   </th>
                            <th width="30%">Name   </th>
                           
                            <th width="40%"> No Po </th>
                        </tr>
                    </thead>
                    <tbody id="tambahAttacment">
                        <?
                       
                         $personal_certificate= new MasterProject();
                         $personal_certificate->selectByParamsMonitoring(array());
                          while (  $personal_certificate->nextRow()) {
                            
                             $reqIds             = $personal_certificate->getField("MASTER_PROJECT_ID");
                             $reqJenis           = $personal_certificate->getField("NAMA");
                               $reqCode           = $personal_certificate->getField("CODE");
                             $reqDescription     = $personal_certificate->getField("KETERANGAN");
                        ?>  
                            <tr>
                                <td> <a onclick="tambahPenyebab(<?=$reqIds?>)" id="btnPenyebab" class="btn btn-info"><i class="fa fa-pencil"></i></a>
                                 <a onclick="hapus(<?= $reqIds ?>)"  class="btn btn-danger"><i class="fa fa-trash"></i></a>
</td>
      <td><?=$reqCode?> </td>
                                <td><?=$reqJenis?> </td>
                                
                                  <td> <?=$reqDescription?> </td>
                            </tr>
                            <?
                           }
                            ?>

                    </tbody>
                    </table>

            </div>
        </div>
    </div>

      

    </div>
    
</div>
<script type="text/javascript">
      function submitForm() {
            $('#ff').form('submit', {
                url: 'web/master_project_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                   
                    //alert(data);
                    $.messager.alertLink('Info', data, 'info', "app/loadUrl/app/tempalate_master_project");
                }
            });
        }
        function hapus(id){
        deleteData('web/master_project_json/delete',id);
    }
        function clearForm() {
            $('#ff').form('clear');
        }

        function tambahPenyebab(id){
            window.location.href="app/loadUrl/app/tempalate_master_project?reqId="+id;

        }
</script>

 <script>
        window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>

