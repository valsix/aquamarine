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
    $statement = "";
    $order = " ORDER BY A.SO_ID DESC";
    $service_order->selectByParamsMonitoring(array(), -1, -1, $statement,$order);
    $service_order->firstRow();
    // $reqObligation      = $service_order->getField("OBLIGATION");

    $reqObligation = '
        <ol>
            <li><span style="font-size: 10pt; font-family: arial;">Report to the Vessel&rsquo;s Master / Client Representative and respectful Surveyor of related Classification Society (whichever applicable) to get explanation of the work and explain the survey method to them.</span></li>
            <li><span style="font-size: 10pt; font-family: arial;">Commence the scope of work fully responsibility and maintain the reputation of personnel and Contractor.</span></li>
            <li><span style="font-size: 10pt; font-family: arial;">Ensure and maintain the work equipment is functioning properly.</span></li>
            <li><span style="font-size: 10pt; font-family: arial;">Return the work equipment to warehouse in good condition.</span></li>
            <li><span style="font-size: 10pt; font-family: arial;">Commence work as per the scope of work and work description respectively.</span></li>
            <li><span style="font-size: 10pt; font-family: arial;">Working properly according to the rules and procedures of work of Contractor.</span></li>
        </ol>';
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
    $reqVesselClass     = $service_order->getField("VESSEL_CLASS");
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
    $reqDocLampiran     = $service_order->getField("DOC_LAMPIRAN");
    $reqPathLampiran    = $service_order->getField("PATH_LAMPIRAN");
    $reqFinance         = $service_order->getField("FINANCE");
    $reqTTD             = $service_order->getField("PENANGGUNG_JAWAB_ID");
    $companyIds         = $service_order->getField('COMPANY_ID');
    $vesselIds          = $service_order->getField('VESSEL_ID');
    $reqDateOWR         = $service_order->getField('DATE_OWR');
    // echo $reqObligation;exit;

    if($reqObligation == "")
    {
        $reqObligation = '
        <ol>
            <li><span style="font-size: 10pt; font-family: arial;">Report to the Vessel&rsquo;s Master / Client Representative and respectful Surveyor of related Classification Society (whichever applicable) to get explanation of the work and explain the survey method to them.</span></li>
            <li><span style="font-size: 10pt; font-family: arial;">Commence the scope of work fully responsibility and maintain the reputation of personnel and Contractor.</span></li>
            <li><span style="font-size: 10pt; font-family: arial;">Ensure and maintain the work equipment is functioning properly.</span></li>
            <li><span style="font-size: 10pt; font-family: arial;">Return the work equipment to warehouse in good condition.</span></li>
            <li><span style="font-size: 10pt; font-family: arial;">Commence work as per the scope of work and work description respectively.</span></li>
            <li><span style="font-size: 10pt; font-family: arial;">Working properly according to the rules and procedures of work of Contractor.</span></li>
        </ol>';
    }


}

// $company = new Company();

// $statement  = " AND UPPER(A.NAME) = '" . strtoupper($reqCompanyName) . "' ";
// $company->selectByParamsMonitoring(array(), -1, -1, $statement);
// $company->firstRow();

// $companyIds = $company->getField('COMPANY_ID');
// echo $companyIds;
// exit;

// if (!empty($companyIds)) {

//     $vessel  = new Vessel();
//     $statement   =  " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqVesselName) . "%' ";
//     $vessel->selectByParamsMonitoring(array("A.COMPANY_ID" => $companyIds), -1, -1, $statement);
//     // echo $vessel->query;
//     // exit;
//     $vessel->firstRow();
//     $vesselIds = $vessel->getField('VESSEL_ID');
// }
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
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_ttd()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Penanggung Jawab</span> </a>

        
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
                        <label for="reqName" class="control-label col-md-2">Company of Name</label>
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
                        <h3><i class="fa fa-file-text fa-lg"></i> Vessel of Name</h3>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Vessel of Name</label>
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
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqVesselClass" id="reqVesselClass" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel'" value="<?= $reqVesselClass ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Type of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqVesselType" id="reqVesselType" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueTypeOfVessel'" value="<?= $reqVesselType ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Client Satisfaction Sheet

                         <!-- <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>
                         <br>
                         <br> -->
                     </h3>
                    </div>
                   
                    <div class="form-group">
                        <label for="reqFinance" class="control-label col-md-2"> Reference Name </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqFinance" class="easyui-validatebox textbox form-control" name="reqFinance" value="<?= $reqFinance ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div> 
                    </div>

                     <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Lampiran File</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <input type="text" placeholder="Doc Name" id="reqDocLampiran" class="easyui-validatebox textbox form-control" name="reqDocLampiran" value="<?= $reqDocLampiran ?>" style=" width:200px" />
                                   <br>
                                    <?
                                    if(!empty($reqId)){
                                    ?>
                                    <a onclick="openAdd('uploads/service_order/<?= $reqId ?>/<?= $reqPathLampiran ?>');" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file"> <?= $reqPathLampiran ?> </span>
                                    <?
                                    }
                                    ?>
                                    <input type="file" name="document[]" class="form-control" style="width: 70px">
                                    <input type="hidden" name="reqLinkFileTemp[]" value="<?=$reqPathLampiran?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Service Information</h3>
                    </div>

                    <div class="form-group">
                        <!-- <label for="reqName" class="control-label col-md-2">Project No.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqNoOrder" id="reqNoOrder" value="<?= $reqNoOrder ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div> -->
                        <label for="reqName" class="control-label col-md-2">Project No.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqNoOrder" id="reqNoOrder" value="<?= $reqNoOrder ?>" style=" width:100%"  />
                                    <button type="button" class="btn btn-default pull-right" onclick="pilih_project()">...</button>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <button type="button" onclick="loadTeam()" class="btn btn-default pull-right" style="width: 100px;height: 100px;margin-left: 10px"><img src="images/user.png" style="height: 70px;width: 70px" /><br><b>Team</b></button>
                                   <!--  <button type="button" onclick="loadEquipment()" class="btn btn-default pull-right " style="width: 100px;height: 100px"><img src="images/admenistrativ.png" style="height: 70px;width: 70px" /><br><b>Equipment</b></button> -->

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Date of OWR</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox  form-control" name="reqDateOWR" id="reqDateOfService" value="<?= $reqDateOWR ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Date of Work</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox  form-control" name="reqDateOfService" id="reqDateOfService" value="<?= $reqDateOfService ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>

		 	<div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Date of Departure</label>
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
                        <label for="reqName" class="control-label col-md-2">Project Name</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqProjectName" id="reqProjectName" cols="4" rows="3" style="width:100%;"><?= $reqProjectName; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Location</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqDestination" id="reqDestination" cols="4" rows="3" style="width:100%;"><?= $reqDestination; ?></textarea>
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
                    <div class="form-group">
                        <label for="reqTTD" class="control-label col-md-2">Penanggung Jawab</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqTTD" id="reqTTD" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/comboPenanggungJawab'" value="<?= $reqTTD ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />
                    <input type="hidden" name="reqTipe" id="reqTipe" value="service_order" />

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
                    // console.log(data);return false;
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
            openAdd('app/loadUrl/app/template_load_company_new');

        }

        // function company_pilihan(id, name, contact, reqAddress, reqEmail, reqTelephone, reqFaximile, reqHp) {
        //     $("#reqCompanyName").val(name);
        //     $("#reqCompanyId").val(id);
        //     $("#reqContactPerson").val(contact);
        // }
        function company_pilihan(id) {
            $("#reqCompanyId").val(id);
            $.get("web/customer_json/company_detail_row?&reqId=" + id,
                function(data) {
                    var datas = JSON.parse(data);
                    $("#reqCompanyName").val(datas.NAME);
                    $("#reqContactPerson").val(datas.CP1_NAME);
                    // $("#reqAddress").val(datas.ADDRESS);
                    // $("#reqTelephone").val(datas.PHONE);
                    // $("#reqFaximile").val(datas.FAX);
                    // $("#reqEmail").val(datas.EMAIL);
                    // $("#reqNoKontrak").val(datas.CP1_TELP);

                    // tambahPenyebab2();
                    // clearFormDetil();
                });

        }

        // vessel
        function openVessel() {
            var companyId = $("#reqCompanyId").val();
            // alert(companyId);
            openAdd('app/loadUrl/app/template_load_vessel_id?reqCompanyId=' + companyId);
        }

        function company_vessel(id, name, type, clas) {
            $('#reqVesselId').val(id);
            $('#reqVesselName').val(name);
            $('#reqVesselClass').combobox('setValue', clas);
            $('#reqVesselType').combobox('setValue', type);
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

        function pilih_project() {
            openAdd('app/loadUrl/app/template_load_offering');
        }
        function addOffering(id, kode,date,location,detail,cp,clvessel,nmvessel,tpvessel,company) {
            $("#reqIdReport").val(id);
            $("#reqNoOrder").val(kode);
            // $("#reqDateOfService").val(date);
            $("#reqDestination").val(location);
            $('#reqDateOfService').datebox('setValue', date);
            $("#reqProjectName").val(detail);
            $("#reqVesselName").val(nmvessel);
            $("#reqContactPerson").val(cp);
            $("#reqCompanyName").val(company);
            $('#reqSurveyor').combobox('setValue', clvessel);
            $('#reqVesselType').combobox('setValue', tpvessel);
            console.log(company);
        }

        
    </script>

    <script type="text/javascript">
        function clearCompany(){
            $('#reqCompanyName').val('');
            $('#reqCompanyId').val('');
            $('#reqContactPerson').val('');
            
        }
       

    </script>
</div>
</div>