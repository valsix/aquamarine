<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

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
<?


$this->load->model("PmsEquipDetil");
$pms_equip_detil = new PmsEquipDetil();

$reqId = $this->input->get("reqId");
$reqEquipId = $this->input->get("reqEquipId");
$reqPmsDetilId = $this->input->get("reqPmsDetilId");

if ($reqPmsDetilId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    
  
    $statement= " AND A.PMS_DETIL_ID = ".$reqPmsDetilId;
    $pms_equip_detil->selectByParamsMonitoring(array(), -1,-1, $statement);

    $pms_equip_detil->firstRow();
    $reqName                = $pms_equip_detil->getField("NAME");
    $reqTimeTest            = $pms_equip_detil->getField("TIME_TEST");
    $reqCertificateNo       = $pms_equip_detil->getField("CERTIFICATE_NUMBER");
    $reqManufacture         = $pms_equip_detil->getField("MANUFACTURE");
    $reqModelNo             = $pms_equip_detil->getField("MODEL_NUMBER");
    $reqSerialNo            = $pms_equip_detil->getField("SERIAL_NUMBER");
    $reqDateTest            = $pms_equip_detil->getField("DATE_TEST");
    $reqNextTest            = $pms_equip_detil->getField("DATE_NEXT_TEST");
    $reqComponentPerson     = $pms_equip_detil->getField("COMPENENT_PERSON");
    $reqCondition           = $pms_equip_detil->getField("CONDITION");
    $reqRemarks             = $pms_equip_detil->getField("REMARKS");
    $reqLinkFile            = $pms_equip_detil->getField("LINK_FILE");
    $reqPath                = $pms_equip_detil->getField("PATH");
}
$reqTipe ='pms_equip_detil';
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />
<style type="text/css">
        #tableis{
            background-color: white;
            padding: 10px;
            border-radius: 25px;
        }
     #tableis tr td{
        padding: 10px;

        font-weight: bold;
        color: black;
    }
</style>
<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/equipment">Entry Planned Maintenance </a> &rsaquo; System</div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry Component</h3>
                    </div>

                    <br>

                        <table style="width: 100%" id="tableis">
                            
                            <tr> 
                                <td style="width: 15%; text-align: right;"> Name</td>
                                <td style="width: 40%"> 
                                    <input type="text"  class="easyui-validatebox textbox form-control" name="reqName" value="<?= $reqName ?>" style=" width:90%" />
                                </td>
                                <td style="width: 30%" rowspan="6" valign="top">
                                    <div style="background: white;height: auto;color: black;height: 360px;width: 440px;border: 1px solid black;padding: 20px" >
                                    <img id="imgLogo" src="uploads/pms/<?=$reqLinkFile?>" style="height: 100%;width: 100%">
                                    </div>
                                    <input type="file" id="reqLinkFile" name="reqLinkFile[]" class="form-control" style="width: 60%" accept="image/*">
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;"> Certificate No.</td>
                                <td>
                                      <input type="text" class="easyui-validatebox form-control" name="reqCertificateNo" id="reqCertificateNo" value="<?=$reqCertificateNo?>" style=" width:50%" /> 
                                  
                                     </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;"> Manufacture</td>
                                <td>
                                      <input type="text" class="easyui-validatebox form-control" name="reqManufacture" id="reqManufacture" value="<?=$reqManufacture?>" style=" width:50%" /> 
                                  
                                     </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;"> Model No.</td>
                                <td>
                                      <input type="text" class="easyui-validatebox form-control" name="reqModelNo" id="reqModelNo" value="<?=$reqModelNo?>" style=" width:50%" /> 
                                  
                                     </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;"> Serial No.</td>
                                <td>
                                      <input type="text" class="easyui-validatebox form-control" name="reqSerialNo" id="reqSerialNo" value="<?=$reqSerialNo?>" style=" width:50%" /> 
                                  
                                     </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;"> Time of Test </td>
                                <td> <input class="easyui-combobox form-control" style="width:100%" name="reqTimeTest" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'combo_json/comboTimeOfTest'" value="<?= $reqTimeTest ?>">  </td>
                            </tr> 
                              <tr>
                                <td style="text-align: right;"> Date of Test </td>
                                <td><input type="text" id="reqDateTest" class="easyui-datebox textbox form-control" name="reqDateTest" value="<?=$reqDateTest?>" style=" width:170px" /> </td>
                            </tr>
                              <tr>
                                <td style="text-align: right;"> Next of Test </td>
                                <td><input type="text" id="reqNextTest" class="easyui-datebox textbox form-control" name="reqNextTest" value="<?=$reqNextTest?>" style=" width:170px" /> </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;"> Component Person </td>
                                <td><input type="text" id="reqComponentPerson" class="easyui-validatebox textbox form-control" name="reqComponentPerson" value="<?= $reqComponentPerson ?>" style=" width:90%" /> </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;"> Condition </td>
                                <td> <input class="easyui-combobox form-control" style="width:100%" id="reqCondition" name="reqCondition" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'combo_json/comboCondisi'" value="<?= $reqCondition ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;"> Remarks </td>
                                <td><textarea type="text" id="reqRemarks" class="easyui-validatebox textbox form-control" name="reqRemarks" rows="2" cols="2" style=" width:90%" ><?= $reqRemarks ?></textarea> </td>
                            </tr>
                            
                        </table>
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
                            ?>
                                    <tr>

                                        <td>
                                            <input type="file" onchange="getFileName(this, '<?=($i+1)?>')" name="document[]" multiple class="form-control" style="width: 90%">
                                            <input type="hidden" name="reqLinkFileTemp[]" value="<?= $files_data[$i] ?>">
                                            <?if ($ext !=='pdf')
                                            {
                                            ?>
                                              <a href="uploads/<?= $reqTipe ?>/<?= $reqPmsDetilId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                              <a onclick="openAdd(`uploads/<?= $reqTipe ?>/<?= $reqPmsDetilId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
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

                    <br>
                     <br>
                      <br>
                    
                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                     <input type="hidden" name="reqPmsDetilId" value="<?= $reqPmsDetilId ?>" />
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
                url: 'web/pms_equip_detil_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');
                     // $.messager.alert('Info', datas[1], 'info');
                     parent.reload_table();
                     // top.Refresh();
                    //alert(data);
                    $.messager.alertLink('Info', datas[1], 'info', "app/loadUrl/app/template_load_componen?reqId=<?=$reqId?>&reqEquipId=<?=$reqEquipId?>&reqPmsDetilId="+datas[0]);
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }

    </script>
    <script type="text/javascript">
        $(document).ready(function() {
        $("#reqLinkFile").change(function () {
            readURL(this);
        });
           
            });
    </script>
    <script type="text/javascript">
        function readURL(input) {
              if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                  console.log(e.target.result);
                //alert(e.target.result);
                $('#imgLogo').attr('src', e.target.result);
              }

              reader.readAsDataURL(input.files[0]);
            }
          }
    </script>
    <script type="text/javascript">
        function openEquipmentList(){
            openAdd('app/loadUrl/app/template_stock');
        }

        
    </script>
    <script type="text/javascript">
        function ambilStock(data){
            $("#reqPmsDetilId").val(data[0]);
            // $("#reqEquipmentKategori").val(data[3]);
            $("#imgLogo").attr("src",'uploads/equipment/'+data[8]);
        
            $("#reqEquipmentName").val(data[3]);
            // $("#reqDateArrive").val();
            $("#reqNoSparepart").val(data[5]);
            $("#reqName").val(data[1]);
            // $("#reqDateLastcal").datebox('setValue',data[11]);
            // $("#reqDateNextcal").datebox('setValue',data[12]);
            // $("#reqCompetent").val(data[1]);
            $("#reqEquipCondition").combobox('setValue',data[6]);
            // console.log(data[6]);
            // $("#reqTimeTest").val();
            $("#reqKeterangan").val(data[15]);
            // console.log(data);

        }
    </script>
      <!-- EMODAL -->
    <script src="libraries/emodal/eModal.js"></script>
    <script src="libraries/emodal/eModal-cabang.js"></script>
    <link rel="stylesheet" type="text/css" href="libraries/toast/toast.css" />
    <script type="text/javascript" language="javascript" src="libraries/toast/toast.js?n=1"></script>
    <script type="text/javascript" language="javascript" src="libraries/toast/costum.js"></script>
     <script>
        function openAdd(pageUrl) {
            eModal.iframe(pageUrl, 'Office Management | PT Aquamarine Divindo Inspection')
        }

        function openCabang(pageUrl) {
            eModalCabang.iframe(pageUrl, 'Office Management | PT Aquamarine Divindo Inspection')
        }

        function closePopup() {
            eModal.close();
        }

        function windowOpener(windowHeight, windowWidth, windowName, windowUri) {
            var centerWidth = (window.screen.width - windowWidth) / 2;
            var centerHeight = (window.screen.height - windowHeight) / 2;

            newWindow = window.open(windowUri, windowName, 'resizable=0,width=' + windowWidth +
                ',height=' + windowHeight +
                ',left=' + centerWidth +
                ',top=' + centerHeight);

            newWindow.focus();
            return newWindow.name;
        }

        function windowOpenerPopup(windowHeight, windowWidth, windowName, windowUri) {
            var centerWidth = (window.screen.width - windowWidth) / 2;
            var centerHeight = (window.screen.height - windowHeight) / 2;

            newWindow = window.open(windowUri, windowName, 'resizable=1,scrollbars=yes,width=' + windowWidth +
                ',height=' + windowHeight +
                ',left=' + centerWidth +
                ',top=' + centerHeight);

            newWindow.focus();
            return newWindow.name;
        }
         function tambahPenyebab(filename='') {
            var id = $('#tambahAttacment tr').length+1;
            $.get("app/loadUrl/app/tempalate_row_attacment?id="+id+"&filename="+filename, function(data) {
                $("#tambahAttacment").append(data);
            });
        }
    </script>

</div>
</div>
 <script>
        window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>