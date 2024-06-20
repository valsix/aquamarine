<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("PmsEquipment");
$pms_equipment = new PmsEquipment();
$this->load->model("EquipmentList");
$equipment_list = new EquipmentList();

$reqId = $this->input->get("reqId");
$reqExpired = $this->input->get("reqExpired");
$reqPicPath = 'images/icon-user-login.png';
if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";

    $pms_equipment->selectByParamsMonitoring(array("A.PMS_ID" => $reqId));
    $pms_equipment->firstRow();
    // /ECHO $pms_equipment->query;exit;
    // var_dump($pms_equipment); exit();
    $reqEquipId                     = $pms_equipment->getField("EQUIP_ID");
    $reqCategory                    = $pms_equipment->getField("CATEGORY");
    $reqEquipmentName               = $pms_equipment->getField("EQUIP_NAME");
    $reqSerialNumber                = $pms_equipment->getField("SERIAL_NUMBER");
    $reqSpec                        = $pms_equipment->getField("SPECIFICATION");
    $reqDateLastcal                 = $pms_equipment->getField("EQUIP_LASTCAL");
    $reqDateNextcal                 = $pms_equipment->getField("EQUIP_NEXTCAL");
    $reqEquipCondition              = $pms_equipment->getField("CONDITION");
    $reqKeterangan                  = $pms_equipment->getField("REMARKS2");
    $reqDateArrive                  = $pms_equipment->getField("EQUIP_DATEIN");
    $reqPicPath                     = $pms_equipment->getField("PIC_PATH");

    // ECHO $reqPicPath;exit();


}
?>
<style type="text/css">
    #tabel-vessel tr th {
        color: white;
        text-transform: uppercase;
        font-weight: bold;

    }

    #tabel-vessel tr td {
        color: black;


    }
</style>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />
<style type="text/css">
    #tableis {
        background-color: white;
        padding: 10px;
        border-radius: 25px;
    }

    #tableis tr td {
        padding: 10px;

        font-weight: bold;
        color: black;
    }
</style>
<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/pms"> PMS</a> &rsaquo; Form PMS
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Equipment Entry
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>

                    <br>

                    <!-- <table style="width: 100%" id="tableis">
                        <tr>
                            <td style="width: 10%"> Equipment </td>
                            <td style="width: 30%">
                                <input type="text" name="reqName" id="reqName" class="easyui-validatebox textbox form-control" value="<?= $reqName ?>" style=" width:90%" />

                                <input type="hidden" value="<?= $reqId ?>" name="reqIds" id="reqIds">
                            </td>

                            <td style="width: 20%" rowspan="6" valign="top">
                                <div style="background: white;height: auto;color: black;height: 260px;width: 200px;border: 1px solid black;padding: 20px">
                                    <img id="imgLogo" src="<?= $reqPicPath ?>" style="height: 100%;width: 100%">

                                </div>
                                <input type="file" id="reqFilesName" name="reqFilesName[]" class="form-control" style="width: 60%" accept="image/*">
                                <input type="hidden" name="reqFilesNames" value="<?= $reqPicPaths ?>">
                            </td>

                        </tr>
                        <tr>
                            <td> Competent Person </td>
                            <td><input type="text" name="reqCompetent" id="reqCompetent" class="easyui-validatebox textbox form-control" value="<?= $reqCompetent ?>" style=" width:90%" /> </td>
                        </tr>
                        <tr>
                            <td> Date of Arrive </td>
                            <td><input type="text" id="reqItem" class="easyui-datebox  form-control" name="reqDateArrive" value="<?= $reqDateArrive ?>" style=" width:250%" /> </td>
                        </tr>
                        <tr>
                            <td> Time of Test </td>
                            <td><input class="easyui-combobox form-control" style="width:100%" name="reqTimeTest" data-options="width:'200',editable:true, valueField:'id',textField:'text',url:'combo_json/comboTimeOfTest'" value="<?= $reqTimeTest ?>" /> </td>
                        </tr>




                    </table> -->

                    <table style="width: 100%" id="tableis">
                        <tr>
                            <td style="width: 15%; text-align: right;"> Equipment List</td>
                            <td style="width: 40%"> 
                                <div class="input-group">
                                    <span class="input-group-addon" onclick="openEquipmentList()"><i class="fa fa-address-book-o fa-lg"></i> </span>
                                
                                       <input type="text" id="reqEquipmentName" name="reqEquipmentName" class="easyui-validatebox textbox form-control" value="<?= $reqEquipmentName ?>" style=" width:90%" required  readOnly  /> 

                                </div>
                           </td>
                          
                           <td style="width: 45%" rowspan="6" valign="top">
                            <div style="background: white;height: auto;color: black;height: 360px;width: 450px;border: 1px solid black;padding: 20px" >
                            <img id="imgLogo" src="uploads/equipment/<?=$reqPicPath?>" style="height: 100%;width: 100%">

                            </div>
                                
                            </td>
                               
                        </tr>

                        <tr>
                            <td style="text-align: right;"> Equipment ID </td>
                            <td>  <input type="text"  class="easyui-validatebox textbox form-control" id="reqEquipId" name="reqEquipId" value="<?= $reqEquipId?>" style=" width:90%" disabled readOnly /> 
                        </tr>

                        <tr>
                            <td style="text-align: right;"> Category </td>
                            <td>  <input type="text"  class="easyui-validatebox textbox form-control" id="reqCategory" value="<?= $reqCategory?>" style=" width:90%" disabled readOnly /> 
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Serial Number </td>
                            <td>  <input type="text"  class="easyui-validatebox textbox form-control" id="reqSerialNumber" value="<?= $reqSerialNumber?>" style=" width:80%" disabled readOnly /> 
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Specification </td>
                            <td>  <input type="text"  class="easyui-validatebox textbox form-control" id="reqSpec" value="<?= $reqSpec?>" style=" width:80%" disabled readOnly /> 
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Incoming Date </td>
                            <td>
                                  <input type="text" class="easyui-validatebox form-control" name="reqDateArrive" id="reqDateArrive" value="<?=$reqDateArrive?>" style=" width:80%" disabled readOnly/> 
                              
                                 </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Last Calibration  </td>
                            <td>

                                <input type="text" class="easyui-validatebox form-control" name="reqDateLastcal" id="reqDateLastcal" value="<?=$reqDateLastcal?>" style=" width:80%" disabled readOnly />  </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">  Next Calibration </td>
                            <td><input type="text"  name="reqDateNextcal" id="reqDateNextcal" class="easyui-validatebox form-control" value="<?=$reqDateNextcal?>" disabled readOnly style=" width:80%"  ></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Condition </td>
                            <td> 
                                 <input class="easyui-combobox form-control" style="width:100%" id="reqEquipCondition" name="reqEquipCondition" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'combo_json/comboCondisi'" value="<?= $reqEquipCondition ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Remarks </td>
                            <td><textarea type="text" id="reqKeterangan" class="easyui-validatebox textbox form-control" name="reqKeterangan" rows="2" cols="2" style=" width:80%" disabled readOnly  ><?= $reqKeterangan ?></textarea> </td>
                        </tr>
                        
                    </table>
                    <br>
                    <br>
                    <?php
                    if($reqId != "")
                    {
                    ?>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Equipment History
                        </h3>
                    </div>

                    <div style="padding: 10px">
                        <table style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th> Project Name </th>
                                    <th> Company Name </th>
                                    <th> Vessel Name </th>
                                    <th> Service Date </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <?php
                            $equipment_list->selectByParamsMonitoring(array("A.EQUIP_ID" => $reqEquipId));
                            $equipment_list->firstRow();

                            $reqCertificateId              = $equipment_list->getField("CERTIFICATE_ID");
                            $reqCertificateName            = $equipment_list->getField("CERTIFICATE_NAME");
                            $reqCertificateDescription     = $equipment_list->getField("CERTIFICATE_DESCRIPTION");
                            $reqCertificatePath            = $equipment_list->getField("CERTIFICATE_PATH");
                            $reqCertificateIssueDate       = $equipment_list->getField("CERTIFICATE_ISSUED_DATE");
                            $reqCertificateExpiredDate     = $equipment_list->getField("CERTIFICATE_EXPIRED_DATE");
                            $reqCertificateSurveyor        = $equipment_list->getField("CERTIFICATE_SURVEYOR");


                            $paramsArray = array("A.EQUIP_ID" => "0");
                            if($reqId != ""){
                                $paramsArray = array("A.EQUIP_ID" => $reqEquipId);
                            }
                            $equipment_list->selectByParamsHistory($paramsArray);
                            while ($equipment_list->nextRow()) {
                            ?> 
                                <tr>
                                    <td><?=$equipment_list->getField("PROJECT_NAME")?></td>
                                    <td><?=$equipment_list->getField("COMPANY_NAME")?></td>
                                    <td><?=$equipment_list->getField("VESSEL_NAME")?></td>
                                    <td><?=$equipment_list->getField("DATE_OF_SERVICE")?></td>
                                </tr>
                            <?php
                            }
                            ?>
                                
                            </tbody>
                        </table>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>Equipment Cerificate</h3>
                    </div>

                    <div class="form-group">
                        <label for="reqCertificate" class="control-label col-md-2">Certificate Type</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqCertificateId" data-options="width:'230',editable:false, valueField:'id',textField:'text',url:'combo_json/comboCertificate'" value="<?= $reqCertificateId ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCertificateName" class="control-label col-md-2">Name of Certificate</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCertificateName" class="easyui-validatebox textbox form-control" name="reqCertificateName" value="<?= $reqCertificateName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCertificateDescription" class="control-label col-md-2">Description</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" rows="3" cols="2" id="reqCertificateDescription" class=" textbox form-control" name="reqCertificateDescription" style=" width:100%"><?= $reqCertificateDescription ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCertificateIssueDate" class="control-label col-md-2">Issued Date </label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCertificateIssueDate" class="easyui-datebox textbox form-control" name="reqCertificateIssueDate" value="<?= $reqCertificateIssueDate ?>" style=" width:190px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCertificateExpiredDate" class="control-label col-md-2">Expired Date </label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCertificateExpiredDate" class="easyui-datebox textbox form-control" name="reqCertificateExpiredDate" value="<?= $reqCertificateExpiredDate ?>" style=" width:190px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCertificateSurveyor" class="control-label col-md-2">Name of Surveyor </label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCertificateSurveyor" class="easyui-validatebox textbox form-control" name="reqCertificateSurveyor" value="<?= $reqCertificateSurveyor ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div style="padding: 10px">
                        
                        <table style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="90%"> File Name </th>

                                    <th width="10%"> Action </th>
                                </tr>
                            </thead>
                            <tbody id="tambahCerificate">
                                <?
                                $files_data = explode(';',  $reqCertificatePath);
                                for ($i = 0; $i < count($files_data); $i++) {
                                    if (!empty($files_data[$i])) {
                                        $texts = explode('-', $files_data[$i]);
                                        $ext = substr($files_data[$i], -3);
                                ?>
                                        <tr>

                                            <td>
                                                <input type="file" onchange="getFileNameCertificate(this, '<?=($i+1)?>')" name="reqLinkFileCertificate[]" multiple class="form-control" style="width: 90%">
                                                <input type="hidden" name="reqLinkFileCertificateTemp[]" value="<?= $files_data[$i] ?>">
                                                <?if ($ext !=='pdf')
                                                {
                                                ?>
                                                  <a href="uploads/equipment_cerificate/<?= $reqEquipId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileCertificate<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                  <a onclick="openAdd(`uploads/equipment_cerificate/<?= $reqEquipId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileCertificate<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
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




                    <div class="page-header">

                        <h3 style="height: 50px"><i class="fa fa-file-text fa-lg"></i> Entry Plan Maintenance System

                            
                            <a onclick="tambahPenyebab()" class="pull-right btn btn-info"  style="margin-right: 20px"><i  class="fa fa-fw fa-plus-square" style="margin-left: 0px"></i></a>
                        </h3>

                    </div>


                    <div class="table-responsive">
                        <div style="width: 100%" class="col-md-11">
                            <table style="width: 100%;" class="table table-striped table-hover dt-responsive nowrap" id="tabel-vessel">
                                <thead>
                                    <tr>
                                        <th width="30%"> Equipment Name </th>
                                        <th width="20%"> Person </th>
                                        <th width="20%"> SN </th>
                                        <th width="30%"> Daily </th>
                                        <th width="30%"> Weekly </th>
                                        <th width="30%"> Monthly </th>
                                        <th width="30%"> 6 Monthly </th>
                                        <th width="30%"> Yearly </th>
                                        <th width="30%"> 2,5 Yearly </th>
                                        <th width="30%"> 5 Yearly </th>
                                        <th width="10%"> Date Of Test </th>
                                        <th width="10%"> Next Of Test </th>

                                        <th width="10%"> Aksi </th>
                                    </tr>
                                </thead>
                                <tbody id="tambahVassel">
                                    <?
                                    $this->load->model("PmsEquipDetil");
                                    $pms_equip_detil = new PmsEquipDetil();
                                    $statements = " AND CAST(A.PMS_ID AS VARCHAR) ='".$reqId."'";
                                    if($reqExpired == "1"){
                                        $statements .= " AND A.DATE_NEXT_TEST < CURRENT_DATE ";
                                    }
                                    $pms_equip_detil->selectByParamsMonitoring(array(),-1,-1,$statements);
                                    // echo $pms_equip_detil->query;exit;
                                    while ($pms_equip_detil->nextRow()) {
                                        $reqPmsDetilId = $pms_equip_detil->getField("PMS_DETIL_ID");
                                        $reqTimeTest = $pms_equip_detil->getField("TIME_TEST");
                                        $reqStatus = $pms_equip_detil->getField("STATUS");
                                        if($reqStatus == 'red')
                                            $class = "redClass";
                                        else
                                            $class = "";
                                    ?>
                                        <tr>
                                            <td class="<?=$class?>"><?= $pms_equip_detil->getField("NAME") ?> </td class="<?=$class?>">
                                            <td class="<?=$class?>"><?= $pms_equip_detil->getField("COMPENENT_PERSON") ?> </td>
                                            <td class="<?=$class?>"><?= $pms_equip_detil->getField("SERIAL_NUMBER") ?> </td>
                                            <td class="<?=$class?>"><?=($reqTimeTest == "1" ? "<img style='width: 20px; height: 20px'  src='images/check-mark.png'>" : "")?></td>
                                            <td class="<?=$class?>"><?=($reqTimeTest == "2" ? "<img style='width: 20px; height: 20px'  src='images/check-mark.png'>" : "")?></td>
                                            <td class="<?=$class?>"><?=($reqTimeTest == "3" ? "<img style='width: 20px; height: 20px'  src='images/check-mark.png'>" : "")?></td>
                                            <td class="<?=$class?>"><?=($reqTimeTest == "4" ? "<img style='width: 20px; height: 20px'  src='images/check-mark.png'>" : "")?></td>
                                            <td class="<?=$class?>"><?=($reqTimeTest == "5" ? "<img style='width: 20px; height: 20px'  src='images/check-mark.png'>" : "")?></td>
                                            <td class="<?=$class?>"><?=($reqTimeTest == "6" ? "<img style='width: 20px; height: 20px'  src='images/check-mark.png'>" : "")?></td>
                                            <td class="<?=$class?>"><?=($reqTimeTest == "7" ? "<img style='width: 20px; height: 20px' src='images/check-mark.png'>" : "")?></td>
                                            <td class="<?=$class?>"><?= $pms_equip_detil->getField("DATE_TEST") ?> </td>
                                            <td class="<?=$class?>"><?= $pms_equip_detil->getField("DATE_NEXT_TEST") ?> </td>
                                            <td class="<?=$class?>">
                                                <a style="padding: 6px" onclick='deletes(<?= $reqPmsDetilId ?>)'><i class="fa fa-trash"></i></a>
                                                <a style="padding: 6px" onclick='openAdd("app/loadUrl/app/template_load_componen?reqId=<?= $reqId ?>&reqPmsDetilId=<?= $reqPmsDetilId ?>&reqEquipId="+$("#reqEquipId").val())'><i class="fa fa-pencil"></i></a>
                                            </td>

                                        </tr>
                                    <?
                                    }
                                    ?>





                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    }
                    ?>

                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>

        </div>

    </div>

    <script type="text/javascript">
        function deletes(id){
            deleteData("web/pms_equip_detil_json/delete",id);
        }
    </script>

    <script>
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/pms_equipment_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');
                    //alert(data);
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/pms_add?reqId=" + datas[0]);
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#reqFilesName").change(function() {
                readURL(this);
            });
            $('#tabel-vessel').dataTable();
        });
          function reload_table(){
                // $('#tabel-vessel').dataTable().api().ajax.reload(null,false);
                window.location.reload();
            }
    </script>
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
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
        function ambilStock(data){
            console.log(data);
            // "EQUIP_ID","NO", "EQUIP_ID", "CATEGORY", "EQUIP_NAME", "PART_OF_EQUIPMENT", "SPECIFICATION",
            // "INCOMING_DATE", "LAST_CALIBRATION","NEXT_CALIBRATION", "CONDITION",
            // "STORAGE", "PRICE", "REMARKS", "SERIAL_NUMBER", "QUANTITY", "ITEM", "PIC_PATH"
            $("#reqEquipId").val(data[0]);
            $("#reqCategory").val(data[3]);
            $("#reqEquipmentName").val(data[4]);
            $("#reqSerialNumber").val(data[14]);
            $("#reqSpec").val(data[6]);
            $("#reqDateArrive").val(data[7]);
            $("#reqDateLastcal").val(data[8]);
            $("#reqDateNextcal").val(data[9]);
            $("#reqEquipCondition").val(data[10]);
            $("#reqKeterangan").val(data[13]);
            $('#imgLogo').attr('src', 'uploads/equipment/'+data[17]);
        }
    </script>


    <script type="text/javascript">
        function tambahPenyebab() {
            openAdd('app/loadUrl/app/template_load_componen?reqId=<?= $reqId ?>&reqEquipId='+$("#reqEquipId").val());
        }
    </script>
</div>
</div>