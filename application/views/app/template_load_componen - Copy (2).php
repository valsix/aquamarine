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
$this->load->model("Pms");
$pms = new Pms();

$reqId = $this->input->get("reqId");
$reqChildId = $this->input->get("reqChild");
$reqPicPath='images/icon-user-login.png';


$pms->selectByParams(array("A.PMS_ID" => $reqId));
$pms->firstRow();
$reqPmsId                        = $pms->getField("PMS_ID");
$reqPmsName                         = $pms->getField("NAME");    
$reqDateArrive                   = $pms->getField("DATE_ARRIVE_NEW");
$reqTimeTest                     = $pms->getField("TIME_TEST");
$reqCompetentPerson              = $pms->getField("COMPETENT");
// echo $reqCompetent   ;

if ($reqChildId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    
  
    $statement= " AND A.PMS_DETIL_ID = ".$reqChildId.' AND PMS_ID='.$reqId;
    $pms_equip_detil->selectByParamsMonitoring(array(), -1,-1, $statement);

    $pms_equip_detil->firstRow();
    $reqPmsDetilId          = $pms_equip_detil->getField("PMS_DETIL_ID");
    $reqPmsId               = $pms_equip_detil->getField("PMS_ID");
    $reqName                = $pms_equip_detil->getField("EQUIP_NAME");
    $reqTimeTest            = $pms_equip_detil->getField("TIME_TEST");
    $reqCompetent           = $pms_equip_detil->getField("COMPETENT");
    $reqDateLastcal         = $pms_equip_detil->getField("LAST");
    $reqDateNextcal         = $pms_equip_detil->getField("NEXT");
    $reqEquipCondition      = $pms_equip_detil->getField("EQUIP_CONDITION");
    $reqPicPaths             = $pms_equip_detil->getField("PIC_PATH");
    $reqKeterangan          = $pms_equip_detil->getField("KETERANGAN");
    $reqNoSparepart         = $pms_equip_detil->getField("NO_SPAREPART");
    $reqCompetentPerson         = $pms_equip_detil->getField("COMPETENT_PERSON");
    $reqEquipListId         = $pms_equip_detil->getField("EQUIP_LIST_ID");

    

     if(!empty($reqPicPaths)){
        $reqPicPath='uploads/equipment_detail/'.$reqPicPaths;
    }
    // ECHO $reqDateNextcal;
}
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
                                <td style="width: 10%"> Equipment List</td>
                                 <td style="width: 30%"> 
                                     <div class="input-group">
                                        <span class="input-group-addon" onclick="openEquipmentList()"><i class="fa fa-address-book-o fa-lg"></i> </span>
                                    
                                           <input type="text" id="reqEquipmentName" name="reqEquipmentName" class="easyui-validatebox textbox form-control" value="<?= $reqName ?>" style=" width:90%" required  readOnly  /> 

                                    </div>
                                   </td>
                                  
                                   <td style="width: 20%" rowspan="6" valign="top">
                                    <div style="background: white;height: auto;color: black;height: 360px;width: 300px;border: 1px solid black;padding: 20px" >
                                    <img id="imgLogo" src="<?=$reqPicPath?>" style="height: 100%;width: 100%">

                                    </div>
                                     <input type="file" id="reqFilesName" name="reqFilesName[]" class="form-control" style="width: 60%" accept="image/*"> 
                                        <input type="hidden" name="reqFilesNames" value="<?=$reqPicPath?>"> 
                                    </td>
                                   
                            </tr>

                            <tr>
                                <td> Equipment </td>
                                <td>  <input type="text"  class="easyui-validatebox textbox form-control" value="<?= $reqPmsName ?>" style=" width:90%" disabled readOnly /> 
                                 
                              
                                 

                                   <input type="hidden" value="<?=$reqEquipListId?>" name="reqPmsDetilId" id="reqPmsDetilId"></td>
                            </tr>
                            <tr>
                                <td> Date of Arrive </td>
                                <td>
                                      <input type="text" class="easyui-datebox form-control" name="reqDateArrive" id="reqDateArrive" value="<?=$reqDateArrive?>" style=" width:170px" /> 
                                  
                                     </td>
                            </tr>
                            <tr>
                                <td> No Spare Part  </td>
                                <td><input type="text" id="reqNoSparepart" class="easyui-validatebox textbox form-control" name="reqNoSparepart" value="<?= $reqNoSparepart ?>" style=" width:50%" /> </td>
                            </tr>   
                              <tr>
                                <td> Component </td>
                                <td><input type="text" id="reqComponent" class="easyui-validatebox textbox form-control" name="reqComponent" value="<?=$reqCompetent?>" style=" width:90%" /> </td>
                            </tr>
                            <tr>
                                <td> Last Calibration  </td>
                                <td>

                                    <input type="text" class="easyui-datebox form-control" name="reqDateLastcal" id="reqDateLastcal" value="<?=$reqDateLastcal?>" style=" width:170px" />  </td>
                            </tr>
                            <tr>
                                <td>  Next Calibration </td>
                                <td><input type="text"  name="reqDateNextcal" id="reqDateNextcal" class="easyui-datebox form-control" value="<?=$reqDateNextcal?>" style=" width:170px"  ></td>
                            </tr>
                            <tr>
                                <td> Component Person </td>
                                <td><input type="text" id="reqCompetent" class="easyui-validatebox textbox form-control" name="reqCompetent" value="<?= $reqCompetentPerson ?>" style=" width:90%" /> </td>
                            </tr>
                            
                         
                             
                         
                            <tr>
                                <td> Condition </td>
                                <td> <input class="easyui-combobox form-control" style="width:100%" id="reqEquipCondition" name="reqEquipCondition" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'combo_json/comboCondisi'" value="<?= $reqEquipCondition ?>" />
 </td>
                            </tr>
                            
                            <tr>
                                <td> Time of Test </td>
                                <td> <input class="easyui-combobox form-control" style="width:100%" name="reqTimeTest" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'combo_json/comboTimeOfTest'" value="<?= $reqTimeTest ?>">  </td>
                            </tr>
                             
                              <tr>
                                <td> Remarks </td>
                                <td><textarea type="text" id="reqKeterangan" class="easyui-validatebox textbox form-control" name="reqKeterangan" rows="2" cols="2" style=" width:90%" ><?= $reqKeterangan ?></textarea> </td>
                            </tr>
                            
                        </table>
                    
                    
                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                     <input type="hidden" name="reqChild" value="<?= $reqPmsDetilId ?>" />
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
                     $.messager.alert('Info', datas[1], 'info');
                     top.Refresh();
                    //alert(data);
                    // $.messager.alertLink('Info', datas[1], 'info', "app/index/template_load_componen?reqId=<?=$reqId?>&reqChildId="+datas[0]);
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
        $("#reqFilesName").change(function () {
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
    </script>

</div>
</div>
 <script>
        window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>