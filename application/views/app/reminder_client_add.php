<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("ReminderClient");

$aColumns = array("REMINDER_CLIENT_ID", "AKSI", "CATEGORY", "DESCRIPTION", "QUANTITY", "DURATION", "UOM", "PRICE", "TOTAL");

$reminder_client = new ReminderClient();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
    $reqUrut = $this->db->query("SELECT COALESCE(MAX(URUT), 0) + 1 nilai FROM REMINDER_CLIENT")->row()->nilai;
} else {
    $reqMode = "ubah";
    $reminder_client->selectByParams(array("A.REMINDER_CLIENT_ID" => $reqId));
    // echo  $reminder_client->query;exit;
    $reminder_client->firstRow();
    
    $reqReminderClientId    = $reminder_client->getField("REMINDER_CLIENT_ID");
    $reqUrut                = $reminder_client->getField("URUT");
    $reqCompanyId           = $reminder_client->getField("COMPANY_ID");
    $reqCompanyName         = $reminder_client->getField("COMPANY_NAME");
    $reqCompanyAddress      = $reminder_client->getField("COMPANY_ADDRESS");
    $reqCpName              = $reminder_client->getField("COMPANY_CP");
    $reqCpPhone             = $reminder_client->getField("COMPANY_PHONE");
    $reqEmail               = $reminder_client->getField("COMPANY_EMAIL");
    $reqVesselName          = $reminder_client->getField("VESSEL_NAME");
    $reqTypeOfVessel        = $reminder_client->getField("TYPE_VESSEL");
    $reqClassOfVessel       = $reminder_client->getField("CLASS_VESSEL");
    $reqVesselId            = $reminder_client->getField("VESSEL_ID");
    $reqImoNo               = $reminder_client->getField("IMO_NO");
    $reqPortRegister        = $reminder_client->getField("PORT_REGISTER");
    $reqAnnualDate          = $reminder_client->getField("ANNUAL_DATE");
    $reqIntermediateDate    = $reminder_client->getField("INTERMEDIATE_DATE");
    $reqSpecialDate         = $reminder_client->getField("SPECIAL_DATE");
    $reqLoadtestDate        = $reminder_client->getField("LOADTEST_DATE");
    $reqAnnualDueDate       = $reminder_client->getField("ANNUAL_DUE_DATE");
    $reqIntermediateDueDate = $reminder_client->getField("INTERMEDIATE_DUE_DATE");
    $reqSpecialDueDate      = $reminder_client->getField("SPECIAL_DUE_DATE");
    $reqLoadtestDueDate     = $reminder_client->getField("LOADTEST_DUE_DATE");
    $reqAnnualExtend        = $reminder_client->getField("ANNUAL_EXTEND");
    $reqIntermediateExtend  = $reminder_client->getField("INTERMEDIATE_EXTEND");
    $reqSpecialExtend       = $reminder_client->getField("SPECIAL_EXTEND");
    $reqLoadtestExtend      = $reminder_client->getField("LOADTEST_EXTEND");
}


// $company = new Company();

// $statement   =  " AND UPPER(A.NAME) LIKE '" . strtoupper($reqCompanyName) . "' ";
// $company->selectByParamsMonitoring(array(), -1, -1, $statement);
// $company->firstRow();

// $companyIds = $company->getField('COMPANY_ID');


// if (!empty($companyIds)) {
//     $vessel  = new Vessel();
//     $statement   =  " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqVesselName) . "%' ";
//     $vessel->selectByParamsMonitoring(array("A.COMPANY_ID" => $companyIds), -1, -1, $statement);
//     // echo $vessel->query;
//     $vessel->firstRow();
//     $vesselIds = $vessel->getField('VESSEL_ID');
// }

?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<script src="js/moment.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<style type="text/css">
    #datagrid-row-r9-2-10 td div .tree-title{
        height: 170px
    }
</style>

<script type="text/javascript" language="javascript" class="init">
    
</script>

<div class="col-md-12">

    <?php
    $mailto = "mailto:".$reqEmail."?body=%0D%0A";
    // $survey = base_url()."app/loadUrl/report/offer_new_pdf?reqId=".$reqId;
    // $project = base_url()."app/loadUrl/report/offer_project_pdf?reqId=".$reqId;
    // $mailto .= $survey."%0D%0A".$project;
    ?>

    <div class="judul-halaman"> <a href="app/index/reminder_client">Reminder Client</a> &rsaquo; Form Reminder Client
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold; margin-left: 10px;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <!-- <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-left: 10px" onclick="cetakPdfSurvey()"><i class="fa fa-file-pdf-o "> </i><span> PDF Survey</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-left: 10px" onclick="cetakPdfProject()"><i class="fa fa-file-pdf-o "> </i><span> PDF Project</span> </a>
        <a class="pull-right " href="<?=$mailto?>" style="color: white;font-weight: bold;margin-left: 10px"><i class="fa fa-envelope"> </i><span> Send As Email</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="loadAttacment()"><i class="fa fa-paperclip"> </i><span> Attachment</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="services()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Services </span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_ttd()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master PJ</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_category_project()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Category</span> </a> -->
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
                            <a href="javascript:void(0)" class="pull-right" style="margin-right: 20px;color: white">

                                <input type="radio" id="eng" name="bahasa" value="eng">
                                <label for="male">Eng</label>
                                <input type="radio" id="idr" name="bahasa" checked value="ind">
                                <label for="male">Ind</label><br>
                            </a>

                        </h3>
                        <br>
                    </div>

                    <div class="form-group" style="display: none;">
                        <label for="reqCompanyName" class="control-label col-md-2">Nomor Urut</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" style="padding: 0px 12px;" class="easyui-numberbox form-control" name="reqUrut" id="reqUrut" value="<?=($reqUrut)?>" data-options="min:0,precision:0, height: 30, width: 200, required: true" >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Company Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <span class="input-group-addon" onclick="openCompany()"><i class="fa fa-address-book-o fa-lg"></i> </span>
                                        <span class="input-group-addon" onclick="clearCompany()" ><i class="fa fa-times fa-lg"></i> </span>
                                        <input type="text" class="form-control"  id="reqCompanyName" name="reqCompanyName" value="<?= $reqCompanyName ?>" 
                                        style=" width:150%"
                                        >

                                    </div>

                                  
                                    <input type="hidden" class="easyui-validatebox textbox form-control" name="reqCompanyId" id="reqCompanyId" value="<?= $reqCompanyId ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Contact</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" id="reqDocumentPerson" name="reqDocumentPerson" value="<?= $reqCpName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">

                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Address</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control tinyMCES" name="reqAddress" id="reqAddress" style="width:100%;"><?= $reqCompanyAddress; ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                     <div class="form-group">
                    <label for="reqPhone" class="control-label col-md-2">Email</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqEmail" class="easyui-validatebox textbox form-control" name="reqEmail" value="<?= $reqEmail ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Telephone</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" onkeypress='validatePhoneNumber(event)' id="reqTelephone" class="easyui-validatebox textbox form-control" name="reqCpPhone" value="<?= $reqTelephone ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">

                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Vessel Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <span class="input-group-addon" onclick="openVessel()"><i class="fa fa-address-book-o fa-lg"></i> </span>
                                        <span class="input-group-addon" onclick="clearVessel()" ><i class="fa fa-times fa-lg"></i> </span>
                                        <input type="text" class="form-control"  id="reqVesselName" name="reqVesselName" value="<?= $reqVesselName ?>" 
                                        style=" width:100%"
                                        >

                                    </div>
                                    <input type="hidden" id="reqVesselId" name="reqVesselId" value="<?= $reqVesselId ?>">
                                   
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqClassOfVessel" class="control-label col-md-2">Class of Vessel</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqClassOfVessel" id="reqClassOfVessel" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel'" value="<?= $reqClassOfVessel ?>" />
                                </div>
                            </div>
                        </div>
                        <label for="reqTypeOfVessel" class="control-label col-md-2">Type Of Vessel</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqTypeOfVessel" id="reqTypeOfVessel" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueTypeOfVessel'" value="<?= $reqTypeOfVessel ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>Detail</h3>
                    </div>
                
                    <div class="form-group">
                        <label for="reqPhone" class="control-label col-md-2">IMO No.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqImoNo" class="easyui-validatebox textbox form-control" name="reqImoNo" value="<?= $reqImoNo ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Port of Register</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPortRegister" class="easyui-validatebox textbox form-control" name="reqPortRegister" value="<?= $reqPortRegister ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqName" class="control-label col-md-2">Annual Survey Date</label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqAnnualDate" id="reqAnnualDate" value="<?= $reqAnnualDate ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqName" class="control-label col-md-2">Due Date </label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqAnnualDueDate" id="reqAnnualDueDate" value="<?= $reqAnnualDueDate ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqAnnualExtend" id="reqAnnualExtend" value="<?= $reqAnnualExtend ?>" style=" width:20%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqName" class="control-label col-md-2">Intermediate Survey Date</label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqIntermediateDate" id="reqIntermediateDate" value="<?= $reqIntermediateDate ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqName" class="control-label col-md-2">Due Date</label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqIntermediateDueDate" id="reqIntermediateDueDate" value="<?= $reqIntermediateDueDate ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqIntermediateExtend" id="reqIntermediateExtend" value="<?= $reqIntermediateExtend ?>" style=" width:20%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqName" class="control-label col-md-2">Special Survey Date</label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqSpecialDate" id="reqSpecialDate" value="<?= $reqSpecialDate ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqName" class="control-label col-md-2">Due Date</label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqSpecialDueDate" id="reqSpecialDueDate" value="<?= $reqSpecialDueDate ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqSpecialExtend" id="reqSpecialExtend" value="<?= $reqSpecialExtend ?>" style=" width:20%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqName" class="control-label col-md-2">Load Test Date</label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqLoadtestDate" id="reqLoadtestDate" value="<?= $reqLoadtestDate ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqName" class="control-label col-md-2">Due Date</label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqLoadtestDueDate" id="reqLoadtestDueDate" value="<?= $reqLoadtestDueDate ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqLoadtestExtend" id="reqLoadtestExtend" value="<?= $reqLoadtestExtend ?>" style=" width:20%" />
                                </div>
                            </div>
                        </div>
                    </div>
                   

                    <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />
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
            var win = $.messager.progress({
                title: 'Office Management  | PT Aquamarine Divindo',
                msg: 'proses data...'
            });

            $('#ff').form('submit', {
                url: 'web/reminder_client_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');
                    show_toast('info', 'Information', 'Header success added <br>' + datas[1]);
                    // alert(data);
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/reminder_client_add?reqId=" + datas[0]);
                    $.messager.progress('close');
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
              // $('#tt').treegrid();
            setTimeout(function() {
                // tinymce.remove();
                $('#reqAnnualDate').datebox({
                    onSelect: function(date){
                        getAnnualExtend()
                    }
                });
                $('#reqIntermediateDate').datebox({
                    onSelect: function(date){
                        getIntermediateExtend()
                    }
                });
                $('#reqSpecialDate').datebox({
                    onSelect: function(date){
                        getSpecialExtend()
                    }
                });
                $('#reqLoadtestDate').datebox({
                    onSelect: function(date){
                        getLoadtestExtend()
                    }
                });
                $('#reqAnnualDueDate').datebox({
                    onSelect: function(date){
                        getAnnualExtend()
                    }
                });
                $('#reqIntermediateDueDate').datebox({
                    onSelect: function(date){
                        getIntermediateExtend()
                    }
                });
                $('#reqSpecialDueDate').datebox({
                    onSelect: function(date){
                        getSpecialExtend()
                    }
                });
                $('#reqLoadtestDueDate').datebox({
                    onSelect: function(date){
                        getLoadtestExtend()
                    }
                });
            }, 1000);

            function getAnnualExtend() {
                var reqDueDate = moment($('#reqAnnualDueDate').datebox('getValue'), 'DD-MM-YYYY');
                var reqDate = moment($('#reqAnnualDate').datebox('getValue'), 'DD-MM-YYYY');
                var diff = reqDueDate.diff(reqDate, 'days', true)
                if(isNaN(diff)) diff = 0;
                $('#reqAnnualExtend').val(Math.ceil(diff))
            }

            function getIntermediateExtend() {
                var reqDueDate = moment($('#reqIntermediateDueDate').datebox('getValue'), 'DD-MM-YYYY');
                var reqDate = moment($('#reqIntermediateDate').datebox('getValue'), 'DD-MM-YYYY');
                var diff = reqDueDate.diff(reqDate, 'days', true)
                if(isNaN(diff)) diff = 0;
                $('#reqIntermediateExtend').val(Math.ceil(diff))
            }

            function getSpecialExtend() {
                var reqDueDate = moment($('#reqSpecialDueDate').datebox('getValue'), 'DD-MM-YYYY');
                var reqDate = moment($('#reqSpecialDate').datebox('getValue'), 'DD-MM-YYYY');
                var diff = reqDueDate.diff(reqDate, 'days', true)
                if(isNaN(diff)) diff = 0;
                $('#reqSpecialExtend').val(Math.ceil(diff))
            }

            function getLoadtestExtend() {
                var reqDueDate = moment($('#reqLoadtestDueDate').datebox('getValue'), 'DD-MM-YYYY');
                var reqDate = moment($('#reqLoadtestDate').datebox('getValue'), 'DD-MM-YYYY');
                var diff = reqDueDate.diff(reqDate, 'days', true)
                if(isNaN(diff)) diff = 0;
                $('#reqLoadtestExtend').val(Math.ceil(diff))
            }
        });
    </script>

    <script type="text/javascript">
        function company_vessel(id, name, type, clas, dimL, dimB, dimD) {
            $('#reqVesselId').val(id);
            $('#reqVesselName').val(name);
            $('#reqClassOfVessel').combobox('setValue', clas);
            $('#reqTypeOfVessel').combobox('setValue', type);
            $('#reqDimensionL').val(dimL);
            $('#reqDimensionB').val(dimB);
            $('#reqDimensionD').val(dimD);
        }
    </script>

    <script type="text/javascript">
        function company_pilihans(id, name, contact, reqAddress, reqEmail, reqTelephone, reqFaximile, reqHp) {
            $("#reqCompanyName").val(name);
            $("#reqCompanyId").val(id);
            $("#reqDocumentPerson").val(contact);
             $(tinymce.get('reqAddress').getBody()).html(reqAddress);
            // $("#reqAddress").val(reqAddress);
            $("#reqEmail").val(reqEmail);
            $("#reqTelephone").val(reqTelephone);
            $("#reqFaximile").val(reqFaximile);
            $("#reqHp").val(reqHp);
            $.getJSON("web/vessel_json/json?reqCheck=tidak&reqCompanyId="+id, function(data) {
                var aaData = data.aaData;
                if(aaData.length == 1){
                    company_vessel(aaData[0][0], aaData[0][1], aaData[0][2], aaData[0][3], aaData[0][4], aaData[0][5], aaData[0][6])
                } else {
                    clearVessel()
                }
            });
        }

        function openCompany() {
            openAdd('app/loadUrl/app/template_load_company_id_new');

        }

        function addProject() {
            if('<?=$reqId?>' == ''){
                $.messager.confirm('Confirm','ReminderClient will be saved before adding project?',function(r){
                    if (r){
                        submitForm();
                    }
                });
            } else {
                openAdd('app/loadUrl/app/template_load_offer_project?reqReminderClientId=<?=$reqId?>');
            }
        }

        function editing(id) {
            openAdd('app/loadUrl/app/template_load_offer_project?reqReminderClientId=<?=$reqId?>&reqId='+id);
        }

        function deleting(id) {
            deleteData_for_table('web/offer_project_json/delete', id, anIndex, 3);
        }

        function reload(id) {
            oTable.api().ajax.reload(null,false);
        }

        function send_email() {
            var bahasa = $('input[name="bahasa"]:checked').val();
            var id = $("#reqId").val();
            if (id == '') {
                $.messager.alert('Info', 'Please complate offer ', 'info');
                return;
            }

            // var win = $.messager.progress({
            //     title: 'Office Management  | PT Aquamarine Divindo',
            //     msg: 'proses data...'
            // });
            // $.get("web/reminder_client_json/sending_mail?reqId=" + id + "&reqBahasa=" + bahasa, function(data) {

            //     $.messager.alert('Info', data, 'info');
            //     $.messager.progress('close');
            // });

        }

        function openVessel() {
            var companyId = $("#reqCompanyId").val();
            openAdd('app/loadUrl/app/template_load_vessel_id?reqCompanyId=' + companyId);
        }


        function loadAttacment() {
            openAdd('app/loadUrl/app/tempalate_attacment?reqReminderClientId=<?= $reqId ?>');
        }


        function cetakPdfSurvey() {
            // var url = "app/loadUrl/report/template_report_offer_pdf?reqId=<?= $reqId ?>";
            var url ="app/loadUrl/report/offer_new_pdf?reqId=<?= $reqId ?>";
            var bahasa = $('input[name="bahasa"]:checked').val();
            if (bahasa == 'eng') {
                // url = "app/loadUrl/report/template_report_offer_en_pdf?reqId=<?= $reqId ?>";
            }
            openAdd(url);
        }

        function cetakPdfProject() {
            // var url = "app/loadUrl/report/template_report_offer_pdf?reqId=<?= $reqId ?>";
            var url ="app/loadUrl/report/offer_project_pdf?reqId=<?= $reqId ?>";
            var bahasa = $('input[name="bahasa"]:checked').val();
            if (bahasa == 'eng') {
                // url = "app/loadUrl/report/template_report_offer_en_pdf?reqId=<?= $reqId ?>";
            }
            openAdd(url);
        }


        function tambahPenyebab(filename='') {
            var id = $('#tambahAttacment tr').length+1;
            $.get("app/loadUrl/app/tempalate_row_attacment?id="+id+"&filename="+filename, function(data) {
                $("#tambahAttacment").append(data);
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
 
    <script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>

    <script type="text/javascript">
        function clearCompany(){

            $('#reqCompanyName').val('');
            $('#reqCompanyId').val('');
            $('#reqDocumentPerson').val('');
             $(tinymce.get('reqAddress').getBody()).html('');
            // $('#reqAddress').val('');
            $('#reqEmail').val('');
            $('#reqTelephone').val('');
            $('#reqFaximile').val('');
            $('#reqHp').val('');
        }
        function clearVessel(){
            $('#reqVesselId').val('');
            $('#reqVesselName').val('');
            $('#reqClassOfVessel').combobox('setValue', '');
            $('#reqTypeOfVessel').combobox('setValue', '');
            $('#reqDimensionL').val('');
            $('#reqDimensionB').val('');
            $('#reqDimensionD').val('');
        }
        function validatePhoneNumber(evt) {
          var theEvent = evt || window.event;

          // Handle paste
          if (theEvent.type === 'paste') {
              key = event.clipboardData.getData('text/plain');
          } else {
          // Handle key press
              var key = theEvent.keyCode || theEvent.which;
              key = String.fromCharCode(key);
          }
          var regex = /[0-9]| |[-!$%^&*()_+|~=`{}\[\]:";'<>?,.\/]/;
          if( !regex.test(key) ) {
            theEvent.returnValue = false;
            if(theEvent.preventDefault) theEvent.preventDefault();
          }
        }
    </script>
</div>