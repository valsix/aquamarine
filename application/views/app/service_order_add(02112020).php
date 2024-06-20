<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Service_order");
$service_order = new Service_order();

$this->load->model("Company");
$this->load->model("Vessel");

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $statement = " AND A.SO_ID = " . $reqId;
    $service_order->selectByParamsMonitoring(array(), -1, -1, $statement);
    $service_order->firstRow();


    $service_order->firstRow();
    $reqSoId            = $service_order->getField("SO_ID");
    $reqProjectName     = $service_order->getField("PROJECT_NAME");
    $reqNoOrder         = $service_order->getField("NO_ORDER");
    $reqCompanyName     = $service_order->getField("COMPANY_NAME");
    $reqVesselName      = $service_order->getField("VESSEL_NAME");
    $reqVesselType      = $service_order->getField("VESSEL_TYPE");
    $reqSurveyor        = $service_order->getField("SURVEYOR");
    $reqDestination     = $service_order->getField("DESTINATION");
    $reqService         = $service_order->getField("SERVICE");
    $reqDateOfStart     = $service_order->getField("DATE_OF_START");
    $reqDateOfFinish    = $service_order->getField("DATE_OF_FINISH");
    $reqTransport       = $service_order->getField("TRANSPORT");
    $reqEquipment       = $service_order->getField("EQUIPMENT");
    $reqObligation      = $service_order->getField("OBLIGATION");
    $reqDateOfService   = $service_order->getField("DATE_OF_SERVICE");
    $reqPicEquip        = $service_order->getField("PIC_EQUIP");
    $reqContactPerson   = $service_order->getField("CONTACT_PERSON");
}

$company = new Company();

$statement  = " AND UPPER(A.NAME) = '" . strtoupper($reqCompanyName) . "' ";
$company->selectByParamsMonitoring(array(), -1, -1, $statement);
$company->firstRow();

$companyIds = $company->getField('COMPANY_ID');
// echo $companyIds;
// exit;

if (!empty($companyIds)) {

    $vessel  = new Vessel();
    $statement   =  " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqVesselName) . "%' ";
    $vessel->selectByParamsMonitoring(array("A.COMPANY_ID" => $companyIds), -1, -1, $statement);
    // echo $vessel->query;
    // exit;
    $vessel->firstRow();
    $vesselIds = $vessel->getField('VESSEL_ID');
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/service_order">Operation Work Request</a> &rsaquo; Form Operation Work Request
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="cetakPdf()"><i class="fa fa-file-pdf-o "> </i><span> Print</span> </a>

        
    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Client Name
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>
                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Company Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                   
                                    <input type="hidden" class="easyui-validatebox textbox form-control" name="reqCompanyId" id="reqCompanyId" value="<?= $companyIds ?>" style=" width:100%" />

                                    <div class="input-group">
                                        <span class="input-group-addon" onclick="openCompany()"><i class="fa fa-address-book-o fa-lg"></i> </span>
                                        <span class="input-group-addon" onclick="clearCompany()" ><i class="fa fa-times fa-lg"></i> </span>
                                        <input type="text" class="form-control"  id="reqCompanyName" name="reqCompanyName" value="<?= $reqCompanyName ?>" 
                                        style=" width:100%"
                                        >

                                    </div>

                                    
                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Contact Person</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" id="reqContactPerson" name="reqContactPerson" value="<?= $reqContactPerson ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Vessel Name</h3>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Vessel Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" id="reqVesselId" name="reqVesselId" value="<?= $vesselIds ?>">
                                    <input type="text" class="easyui-validatebox textbox form-control" onclick="openVessel()" name="reqVesselName" id="reqVesselName" value="<?= $reqVesselName ?>" style=" width:100%" />
                                   
                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Class of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqSurveyor" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel'" value="<?= $reqSurveyor ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Type Of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqVesselType" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueTypeOfVessel'" value="<?= $reqVesselType ?>" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Service Information</h3>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">No Order</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqNoOrder" id="reqNoOrder" value="<?= $reqNoOrder ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <button type="button" onclick="loadTeam()" class="btn btn-default pull-right" style="width: 100px;height: 100px;margin-left: 10px"><img src="images/user.png" style="height: 70px;width: 70px" /><br><b>Team</b></button>
                                    <button type="button" onclick="loadEquipment()" class="btn btn-default pull-right " style="width: 100px;height: 100px"><img src="images/admenistrativ.png" style="height: 70px;width: 70px" /><br><b>Equipment</b></button>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Date of Service</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqDateOfService" id="reqDateOfService" value="<?= $reqDateOfService ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Project</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqProjectName" cols="4" rows="3" style="width:100%;"><?= $reqProjectName; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Location</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqDestination" cols="4" rows="3" style="width:100%;"><?= $reqDestination; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Date Start</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqDateOfStart" id="reqDateOfStart" value="<?= $reqDateOfStart ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                        <label for="reqName" class="control-label col-md-2">Date of Finish</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqDateOfFinish" id="reqDateOfFinish" value="<?= $reqDateOfFinish ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Pic Equipment</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqPicEquip" id="reqPicEquip" value="<?= $reqPicEquip ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Trasportation</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqTransport" id="reqTransport" value="<?= $reqTransport ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Obligation</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control tinyMCES" name="reqObligation" cols="4" rows="3" style="width:100%;"><?= $reqObligation; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

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

    <script>
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/service_order_json/add_new',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');
                    show_toast('info', 'Information', 'Header success added <br>' + datas[1]);
                    //alert(data);
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/service_order_add?reqId=" + datas[0]);
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
    <script type="text/javascript">
        // company
        function openCompany() {
            openAdd('app/loadUrl/app/template_load_company_id');

        }

        function company_pilihans(id, name, contact, reqAddress, reqEmail, reqTelephone, reqFaximile, reqHp) {
            $("#reqCompanyName").val(name);
            $("#reqCompanyId").val(id);
            $("#reqContactPerson").val(contact);
        }

        // vessel
        function openVessel() {
            var companyId = $("#reqCompanyId").val();
            // alert(companyId);
            openAdd('app/loadUrl/app/template_load_vessel_id?reqCompanyId=' + companyId);
        }

        function company_vessel(id, name, clas, type) {
            $('#reqVesselId').val(id);
            $('#reqVesselName').val(name);
            $('#$reqSurveyor').combobox('setValue', type);
            $('#reqVesselType').combobox('setValue', clas);
        }

        function loadTeam() {
            <?
            if (empty($reqId)) {
            ?>
                $.messager.alert('Info', 'You must complate service order', 'info');
                return;
            <?
            }
            ?>
            openAdd('app/loadUrl/app/template_add_team?reqId=<?= $reqId ?>');
        }

        function loadEquipment() {
            <?
            if (empty($reqId)) {
            ?>
                $.messager.alert('Info', 'You must complate service order', 'info');
                return;
            <?
            }
            ?>
            openAdd('app/loadUrl/app/template_add_equipment?reqId=<?= $reqId ?>');
        }


        function cetakPdf() {
            openAdd('app/loadUrl/app/template_report_owr_pdf?reqId=<?= $reqId ?>');
        }

        
    </script>

    <script type="text/javascript">
        function clearCompany(){

            $('#reqCompanyName').val('');
            $('#reqCompanyId').val('');
            $('#reqContactPerson').val('');
           
            
            
        }
        function company_pilihans(id, name, contact, reqAddress, reqEmail, reqTelephone, reqFaximile, reqHp) {
            $("#reqCompanyName").val(name);
            $("#reqCompanyId").val(id);
            $("#reqContactPerson").val(contact);
            
        }

    </script>
</div>
</div>