<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("DokumenReport");
$this->load->model("Company");
$this->load->model("Vessel");
$this->load->model("Service_order");

$dokumen_report = new DokumenReport();

$reqId = $this->input->get("reqId");


if ($reqId == "") {
    $reqMode = "insert";
    $reqNoUrut = $this->db->query("SELECT COALESCE(MAX(URUT), 0) + 1 nilai FROM DOKUMEN_REPORT")->row()->nilai;
} else {
    $reqMode = "ubah";
    $dokumen_report->selectByParams(array("DOCUMENT_ID" => $reqId));
    $dokumen_report->firstRow();

    $reqDocumentId = $dokumen_report->getField("DOCUMENT_ID");
    $reqReportId = $dokumen_report->getField("REPORT_ID");
    $reqName = $dokumen_report->getField("NAME");
    $reqNoUrut = $dokumen_report->getField("URUT");
    $reqDescription = $dokumen_report->getField("DESCRIPTION");
    $reqPath = $dokumen_report->getField("PATH");
    $reqStartDate = $dokumen_report->getField("START_DATE");
    $reqFinishDate = $dokumen_report->getField("FINISH_DATE");
    $reqDeliveryDate = $dokumen_report->getField("DELIVERY_DATE");
    $reqInvoiceDate = $dokumen_report->getField("INVOICE_DATE");
    $reqReason = $dokumen_report->getField("REASON");
    $reqNoReport = $dokumen_report->getField("NO_REPORT");
    $reqNameOfVessel = $dokumen_report->getField("NAME_OF_VESSEL");
    $reqTypeOfVessel = $dokumen_report->getField("TYPE_OF_VESSEL");
    $reqLocation = $dokumen_report->getField("LOCATION");
    $reqClassSociety = $dokumen_report->getField("CLASS_SOCIETY");
    $reqScopeOfWork = $dokumen_report->getField("SCOPE_OF_WORK");
    $reqNoOwr = $dokumen_report->getField("NO_OWR");
    $reqStatus = $dokumen_report->getField("STATUS");
    $companyIds = $dokumen_report->getField("COMPANY_ID");
    $vesselIds = $dokumen_report->getField("VESSEL_ID");
    $reqClients = $dokumen_report->getField("CLIENT");
    $reqSurveyors = $dokumen_report->getField("SURYEVOR");
     $reqClientsx = json_decode($reqClients,true);
      $reqSurveyorsx = json_decode($reqSurveyors,true);

      $reqCostSuryevor = $dokumen_report->getField("COST_SURYEVOR");
      $reqCostOperator = $dokumen_report->getField("COST_OPERATOR");

    $reqServiceOrderId  = $dokumen_report->getField("SERVICE_ORDER_ID");

    $service_order = new Service_order();
    if (!empty($reqServiceOrderId)) {

        $service_order->selectByParams(array("A.SO_ID" => $reqServiceOrderId));
        // echo $service_order->query;
        // exit;
        $service_order->firstRow();
        $reqKodeServiceOrder = $service_order->getField("NO_ORDER");
    }
}

// $company = new Company();

// $statement  = " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqName) . "%' ";
// $company->selectByParamsMonitoring(array(), -1, -1, $statement);
// $company->firstRow();

// $companyIds = $company->getField('COMPANY_ID');
// // echo $companyIds;
// // exit;

// if (!empty($companyIds)) {

//     $vessel  = new Vessel();
//     $statement   =  " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqDescription) . "%' ";
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

    <div class="judul-halaman"> <a href="app/index/report"> Report Survey</a> &rsaquo; Form Report Survey
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry Document of Report Survey
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>
                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqNoReport" class="control-label col-md-2">No Report</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <input class="easyui-textbox form-control"  id="reqNoReport" style="width:100%" name="reqNoReport" value="<?= $reqNoReport ?>" />
                                        <span class="input-group-addon" onclick="open_service_order()">...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <label for="reqNoUrut" class="control-label col-md-2">No Urut</label>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <input class="easyui-validatebox form-control" required onkeypress='validate(event)'  id="reqNoUrut" style="width:100%" name="reqNoUrut" value="<?= $reqNoUrut ?>" />
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <label for="reqNoOwr" class="control-label col-md-2">No OWR</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" id="reqServiceOrderId" name="reqServiceOrderId" class="easyui-validatebox textbox form-control" value="<?= $reqServiceOrderId ?>" data-options="required:true">
                                    <input onclick="open_service_order()" type="text" id="reqKodeServiceOrder" class="easyui-validatebox textbox form-control" name="reqNoOwr" value="<?= $reqNoOwr ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div> -->
                    </div>

                    <!-- <div class="form-group">
                        <label for="reqReportId" class="control-label col-md-2">Type of Report</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqReportId" data-options="width:'290',editable:false, valueField:'id',textField:'text',url:'combo_json/comboReport'" value="<?= $reqReportId ?>" />
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Name Of Client</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <input type="text" onclick="openCompany()" id="reqName" class="easyui-textbox textbox form-control" name="reqName" value="<?= $reqName ?>" style=" width:100%" />
                                        <span class="input-group-addon" onclick="clearCompany()" ><i class="fa fa-times fa-lg"></i> </span>
                                        <span class="input-group-addon" onclick="openCompany()"><i class="fa fa-address-book-o fa-lg"></i> </span>
                                    </div>
                                    <input type="hidden" class="easyui-validatebox textbox form-control" name="reqCompanyId" id="reqCompanyId" value="<?= $companyIds ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Vessel Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqName" class="easyui-textbox textbox form-control" name="reqDescription" value="<?= $reqDescription ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="form-group">
                        <label for="reqStartDate" class="control-label col-md-2">Date Of Work</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqStartDate" class="easyui-datebox textbox form-control" name="reqStartDate" value="<?= $reqStartDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                        <label for="reqFinishDate" class="control-label col-md-2">Date Of Complete</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqFinishDate" class="easyui-datebox textbox form-control" name="reqFinishDate" value="<?= $reqFinishDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                        <!-- <label for="reqName" class="control-label col-md-2">Delevery Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDeliveryDate" class="easyui-datebox textbox form-control" name="reqDeliveryDate" value="<?= $reqDeliveryDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div> -->
                    </div>

                    <div class="form-group">
                        <!-- <label for="reqName" class="control-label col-md-2">Finish Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqFinishDate" class="easyui-datebox textbox form-control" name="reqFinishDate" value="<?= $reqFinishDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div> -->
                        <label for="reqDeliveryDate" class="control-label col-md-2">Date of Delivery Report</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDeliveryDate" class="easyui-datebox textbox form-control" name="reqDeliveryDate" value="<?= $reqDeliveryDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                        <label for="reqInvoiceDate" class="control-label col-md-2">Date Of Invoice</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInvoiceDate" class="easyui-datebox textbox form-control" name="reqInvoiceDate" value="<?= $reqInvoiceDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqReason" class="control-label col-md-2">Note</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" id="reqReason" class="easyui-textbox textbox form-control" name="reqReason" style=" width:100%"><?= $reqReason ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqStatus" class="control-label col-md-2">Status</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqStatus" name="reqStatus" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboStatusReport'" value="<?= $reqStatus ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="form-group">
                        <label for="reqSurveyor" class="control-label col-md-2">Surveyor / Operator</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <!--  <input class="easyui-combobox form-control" style="width:100%"  name="reqSurveyorCost" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboSurveryour'" value="<?= $reqCostSuryevor ?>" /> -->
                                     <input type="text" id="reqSurveyorCost" class="easyui-validatebox textbox form-control" name="reqSurveyorCost" value="<?= $reqCostSuryevor ?>" style=" width:200px" />
                                     /   <!-- <input class="easyui-combobox form-control" style="width:100%" name="reqOperatorCost" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboOperator'" value="<?= $reqCostOperator ?>" />  -->
                                      <input type="text" id="reqOperatorCost" class="easyui-validatebox textbox form-control" name="reqOperatorCost" value="<?= $reqCostOperator ?>" style=" width:200px" />
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>Vessel Information</h3>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Vessel Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" id="reqVesselId" name="reqVesselId" value="<?= $vesselIds ?>">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqNameOfVessel" id="reqNameOfVessel" value="<?= $reqNameOfVessel ?>" style=" width:100%" />
                                    <button type="button" class="btn btn-default pull-right" onclick="openVessel()">...</button>
                                </div>
                            </div>
                        </div>
                        <label for="reqClassSociety" class="control-label col-md-2">Class Of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqClassSociety" name="reqClassSociety" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel'" value="<?= $reqClassSociety ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqTypeOfVessel" class="control-label col-md-2">Type Of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqTypeOfVessel" name="reqTypeOfVessel" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueTypeOfVessel'" value="<?= $reqTypeOfVessel ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqLocation" class="control-label col-md-2">Location</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" id="reqLocation" class="easyui-textbox textbox form-control" name="reqLocation" style=" width:100%"><?= $reqLocation ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqScopeOfWork" class="control-label col-md-2">Scope Of Work</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" id="reqScopeOfWork" class="easyui-textbox textbox form-control" name="reqScopeOfWork" style=" width:100%"><?= $reqScopeOfWork ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="page-header">
                            <h3><i class="fa fa-file-text fa-lg"></i>Suryevor Satisfaction Sheet</h3>
                        </div>
                        <div class="form-group">
                        <label for="reqLocation" class="control-label col-md-2">Nomer</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <input type="text"  class="easyui-textbox textbox form-control" name="reqSurveyorSheet" value="<?=$reqSurveyorsx['reqSurveyorSheet'] ?>" Placeholder=" No..../SSS/AMD/.../2021" style=" width:400px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <?
                    $arrValue =array("Preparation before working","Conduct of the team during","Knowledge of working","Concerns for safety","Performance of equipement","Performance of personnel","Overall Performance of team","etc");
                    // echo $arrValue;

                    ?>
                    <div style="padding: 20px">
                                        <table class="table-bordered table ">
                        <thead>
                            <tr>
                                <th rowspan="2" align="center" valign="center" style="text-align: center;width: 5%">No </th>
                                <th rowspan="2" align="center" valign="center" style="text-align: center;width: 25%">Description </th>
                                <th colspan="5" align="center" valign="center" style="text-align: center;width: 60%">Please Tick ( <i class=" fa fa-check"> </i> ) To Rate The Following *)  </th>
                                <th rowspan="2" align="center" valign="center" style="text-align: center;width: 10%">Remarks and Notes </th>
                            </tr>
                            <tr> 
                                <th valign="center" style="text-align: center;">Excellent ( 81-100 )</th>
                                <th valign="center" style="text-align: center;">Good ( 61- 80 ) </th>
                                <th valign="center" style="text-align: center;">Adequate ( 41- 60 ) </th>
                                <th valign="center" style="text-align: center;">Poor ( 21- 41 ) </th>
                                <th valign="center" style="text-align: center;">Very Poor ( 0- 20 ) </th>
                            </tr>
                        </thead>
                        <tbody>
                           
                                <?
                                $no=1;
                                for($i=0;$i<count($arrValue);$i++){
                                    ?>
                                     <tr> 
                                    <td><?=$no?> </td>
                                    <td><?=$arrValue[$i]?> </td>    
                                    <?
                                    for($j=0;$j<5;$j++){
                                        $checked = '';
                                        if(!empty($reqSurveyorsx['reqSurveyor'.$i.$j])){
                                            $checked = 'checked';
                                        }
                                    ?>
                                    <td><input type="checkbox" class="form-control" <?=$checked?> name="reqSurveyor<?=$i.$j?>" value="ya"> </td>
                                    <?
                                    }
                                   $no++;
                                   ?>
                                    <td>  <input type="text" class="easyui-textbox textbox form-control" name="reqSurveyorRemark<?=$i?>" value="<?=$reqSurveyorsx['reqSurveyorRemark'.$i] ?>" Placeholder="......." style=" width:200px" /> </td>
                            </tr>
                                   <?
                                }
                                ?>
                                <tr>
                                <td colspan="8"><div class="form-group">
                        <label for="reqSurveyorRecom" class="control-label col-md-2">Class Surveryor's comment / recommendation :</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text"  class="easyui-textbox textbox form-control" name="reqSurveyorRecom" style=" width:100%"><?=$reqSurveyorsx['reqSurveyorRecom'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div> </td> 
                                </tr>

                               
                        </tbody>
                     </table>
                   
                   
                     </div> 
                         <div class="page-header">
                            <h3><i class="fa fa-file-text fa-lg"></i>Client Satisfaction Sheet</h3>
                        </div>
                         <label for="reqLocation" class="control-label col-md-2">Nomer</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <input type="text"  class="easyui-textbox textbox form-control" name="reqClientSheet" value="<?= $reqClientsx['reqClientSheet'] ?>"  Placeholder=" No..../SSS/AMD/.../2021" style=" width:400px" />
                                </div>
                            </div>
                        </div>
                         <div style="padding: 20px">
                    <table class="table-bordered table ">
                        <thead>
                            <tr>
                                <th rowspan="2" align="center" valign="center" style="text-align: center;width: 5%">No </th>
                                <th rowspan="2" align="center" valign="center" style="text-align: center;width: 25%">Description </th>
                                <th colspan="5" align="center" valign="center" style="text-align: center;width: 60%">Please Tick ( <i class=" fa fa-check"> </i> ) To Rate The Following *)  </th>
                                <th rowspan="2" align="center" valign="center" style="text-align: center;width: 10%">Remarks and Notes </th>
                            </tr>
                            <tr> 
                                <th valign="center" style="text-align: center;">Excellent ( 81-100 )</th>
                                <th valign="center" style="text-align: center;">Good ( 61- 80 ) </th>
                                <th valign="center" style="text-align: center;">Adequate ( 41- 60 ) </th>
                                <th valign="center" style="text-align: center;">Poor ( 21- 41 ) </th>
                                <th valign="center" style="text-align: center;">Very Poor ( 0- 20 ) </th>
                            </tr>
                        </thead>
                        <tbody>
                           
                                <?
                                $no=1;
                                for($i=0;$i<count($arrValue);$i++){
                                    ?>
                                     <tr> 
                                    <td><?=$no?> </td>
                                    <td><?=$arrValue[$i]?> </td>    
                                    <?
                                    for($j=0;$j<5;$j++){
                                        $checked = '';
                                        if(!empty($reqClientsx['reqClient'.$i.$j])){
                                            $checked = 'checked';
                                        }
                                    ?>
                                    <td><input type="checkbox" class="form-control" <?=$checked?> name="reqClient<?=$i.$j?>" value="ya"> </td>
                                    <?
                                    }
                                   $no++;
                                   ?>
                                    <td>  <input type="text" class="easyui-textbox textbox form-control" name="reqClientRemark<?=$i?>" value="<?=$reqClientsx['reqClientRemark'.$i] ?>" Placeholder="......." style=" width:200px" /> </td>
                            </tr>
                                   <?
                                }
                                ?>
                                <tr>
                                <td colspan="8"><div class="form-group">
                        <label for="reqSurveyorRecom" class="control-label col-md-2">Client's comment  :</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text"  class="easyui-textbox textbox form-control" name="reqClientRecom" style=" width:100%"><?=$reqClientsx['reqClientRecom'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div> </td> 
                                </tr>

                               
                        </tbody>
                     </table>

                 </div>

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
                            <?php
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
                                              <a href="uploads/report/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                              <a onclick="openAdd(`uploads/report/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            ?>
                                        </td>
                                        <td><?=strtoupper($ext)?></td>
                                        <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>

                        </tbody>
                    </table>

                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />
                    <input type="hidden" name="reqTipe" value="Report" />

                </form>
            </div>

            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>

        </div>

    </div>

    <script type="text/javascript">
        function submitForm() {
              var win = $.messager.progress({
            title: 'Office Management  | PT Aquamarine Divindo',
            msg: 'proses data...'
        });
            $('#ff').form('submit', {
                url: 'web/dokument_report_json/add',
                onSubmit: function() {
                      $.messager.progress('close'); 
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');
                       if(datas[0]=='xxx'){
                             show_toast('error', 'Have troube in ',  datas[1]);
                            $.messager.alert('Info', datas[1], 'info'); 
                    }else{
                    // alert(data);
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/report_add?reqId=" + datas[0]);
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

    <script type="text/javascript">
        // vessel
        function openVessel() {
            var companyId = $("#reqCompanyId").val();
            // alert(companyId);
            openAdd('app/loadUrl/app/template_load_vessel_id?reqCompanyId=' + companyId);
        }

        function company_vessel(id, name, clas, type) {
            $('#reqVesselId').val(id);
            $('#reqNameOfVessel').val(name);
            $('#reqClassSociety').combobox('setValue', type);
            $('#reqTypeOfVessel').combobox('setValue', clas);
        }

        // service order/ owr
        function open_service_order() {
            openAdd('app/loadUrl/app/template_load_service_order');
        }

        function addOWR(id, kode,name,type,surveyor,project,company,location,contact,datestart
            ,datefinish,scope,sur,op)
        {
            $("#reqServiceOrderId").val(id);
            $("#reqName").val(company);
            $("#reqNoReport").val(kode);
            $("#reqDescription").val(name);
            $('#reqTypeOfVessel').combobox('setValue', type);
            $("#reqLocation").val(location);
            $('#reqClassSociety').combobox('setValue', surveyor);
            $('#reqStartDate').datebox('setValue', datestart);
            $('#reqFinishDate').datebox('setValue', datefinish);
            $("#reqScopeOfWork").val(scope);
              $("#reqSurveyorCost").val(sur);
                $("#reqOperatorCost").val(op);
        }

        // company
        function openCompany() {
            openAdd('app/loadUrl/app/template_load_company_id');
        }

        function clearCompany(){
            $('#reqCompanyId').val('');
            $('#reqName').val('');
        }

        function company_pilihans(id, name, contact, reqAddress, reqEmail, reqTelephone, reqFaximile, reqHp) {
            $("#reqName").val(name);
            $("#reqCompanyId").val(id);
            // $("#reqDocumentPerson").val(contact);
            // $("#reqAddress").val(reqAddress);
            // $("#reqEmail").val(reqEmail);
            // $("#reqTelephone").val(reqTelephone);
            // $("#reqFaximile").val(reqFaximile);
            // $("#reqHp").val(reqHp);
        }
    </script>

</div>
</div>