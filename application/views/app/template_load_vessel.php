<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId   =  $this->input->get("reqId");

$reqCompanyId   =  $this->input->get("reqCompanyId");
$this->load->model("Vessel");
$vessel = new Vessel();

$statement =" AND CONCAT(A.VESSEL_ID,'-',B.OFFER_ID) ='".$reqId."'";
$vessel->selectByParamsMonitoringDetailVesssel(array("A.COMPANY_ID" => $reqCompanyId),-1,-1,$statement);


$vessel->firstRow();
$reqIds =explode('-', $vessel->getField("VESSEL_ID"));
$reqId  =   $reqIds[0];

// $reqCompanyId       = $vessel->getField("COMPANY_ID");
$reqName            = $vessel->getField("NAME");
$reqDimensionL      = $vessel->getField("DIMENSION_L");

$reqDimensionB      = $vessel->getField("DIMENSION_B");
$reqDimensionD      = $vessel->getField("DIMENSION_D");
$reqTypeVessel      = $vessel->getField("TYPE_VESSEL");
$reqClassVessel     = $vessel->getField("CLASS_VESSEL");
$reqTypeSurvey      = $vessel->getField("TYPE_SURVEY");
$reqLocationSurvey  = $vessel->getField("LOCATION_SURVEY");
$reqContactPerson   = $vessel->getField("CONTACT_PERSON");
$reqContactTelp   = $vessel->getField("CONTACT_TELEPONE");
$reqValueSurvey     = $vessel->getField("VALUE_SURVEY");
$reqSurveyorName    = $vessel->getField("SURVEYOR_NAME");
$reqSurveyorPhone   = $vessel->getField("SURVEYOR_PHONE");
$reqCurrency        = $vessel->getField("CURRENCY");
$reqCurrencyValue   = $vessel->getField("CURRENCY_VALUE");
$reqValueNet        = $vessel->getField("VALUE_NET");
$reqValueDeadweight = $vessel->getField("VALUE_DEADWEIGHT");


$reqTanggalSurvey  = $vessel->getField("DATE_ORDER");
$reqTanggalNext    = $vessel->getField("DATE_SERVICE");

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

    <link href='css/pagination.css' rel='stylesheet' type='text/css'>

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
    <script type="text/javascript" src="libraries/functions/string.func.js"></script>

    <script src="libraries/tinyMCE/tinymce.min.js"></script>

    <script type="text/javascript">
        tinymce.init({
            selector: "textarea",
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
$aColumns = array(
    "EMAIL_ID", "SUBJECT"
);

?>



<div class="col-md-12">

    

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
         <div id="bluemenu" class="aksi-area">
            <!-- <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Pilih</a></span> -->
          
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
        </div>
       <div class="konten-area">
        <div class="konten-inner">
            <div>
        <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
            <div class="page-header">
                <h3><i class="fa fa-file-text fa-lg"></i> Detail Vessel</h3>
            </div>
            <div class="form-group">
                <label for="reqName" class="control-label col-md-2">Vessel Name</label>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-11">
                            <input type="text" class="easyui-validatebox textbox form-control" name="reqName" id="reqName" value="<?= $reqName ?>" style=" width:100%" />
                        </div>
                    </div>
                </div>
               
             
        </div>
         <div class="form-group">
             <label for="reqPhone" class="control-label col-md-2">Dimension</label>
                <div class="col-md-10">
                    <div class="form-group">
                        <div class="col-md-11">
                           <div class="col-xs-4">
                            <div class="input-group">
                                <span class="input-group-addon">L</span>
                                <input type="text" class="form-control" name="reqDimensionL" id="reqDimensionL" value="<?=($reqDimensionL)?>" onchange="numberWithCommas('reqDimensionL')" onkeyup="numberWithCommas('reqDimensionL')"

                                >


                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="input-group">
                                <span class="input-group-addon">B</span>
                                <input type="text" class="form-control"  id="reqDimensionB" name="reqDimensionB" value="<?=($reqDimensionB)?>" onchange="numberWithCommas('reqDimensionB')" onkeyup="numberWithCommas('reqDimensionB')"

                                >

                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="input-group">
                                <span class="input-group-addon">D</span>
                                <input type="text" class="form-control"  id="reqDimensionD" name="reqDimensionD" value="<?=($reqDimensionD)?>" ononchange="numberWithCommas('reqDimensionD')" onkeyup="numberWithCommas('reqDimensionD')"

                                >

                            </div>
                        </div>
                    </div>
                       </div>
            </div>
         </div>

        <div class="form-group">
            <label for="reqLa2Cp2" class="control-label col-md-2">Type Vessel</label>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-md-11">
                        <input class="easyui-combobox form-control" style="width:100%" name="reqVasselType_vessel" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueTypeOfVessel'" value="<?= $reqTypeVessel ?>" />
                    </div>
                </div>
            </div>
            <label for="reqLa2Cp2Phone" class="control-label col-md-2">Class Vessel</label>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-md-11">
                        <input class="easyui-combobox form-control" style="width:100%" name="reqVasselClass_vessel" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel'" value="<?= $reqClassVessel ?>" />


                    </div>
                </div>
            </div>
        </div>
         <div class="form-group">
            <label for="reqLa2Cp2" class="control-label col-md-2">Type of Services</label>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-md-11">
                         <input class="easyui-combobox form-control" style="width:100%" name="reqVasselType_survey" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'web/services_json/combo'" value="<?= $reqTypeSurvey ?>" />
                    </div>
                </div>
            </div>
            <label for="reqLa2Cp2Phone" class="control-label col-md-2">Location of Survey</label>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-md-11">
                        <input type="text" id="reqLocationSurvey" class="easyui-validatebox textbox form-control" name="reqLocationSurvey" value="<?= $reqLocationSurvey ?>" style=" width:100%" />


                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="reqLa2Cp2" class="control-label col-md-2">Contact Person</label>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-md-11">
                        <input type="text" id="reqContactPerson" class="easyui-validatebox textbox form-control" name="reqContactPerson" value="<?= $reqContactPerson ?>" style=" width:100%" />
                    </div>
                </div>
            </div>
            <label for="reqLa2Cp2Phone" class="control-label col-md-2">Gross / Net / Deadweight Tonnage</label>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-md-11">
                         <input name="reqValueSurvey" id="reqValueSurvey" type="text" class="easyui-validatebox textbox form-control" value="<?= currencyToPage2($reqValueSurvey) ?>" style="width:33%" 
                          onchange="numberWithCommas('reqValueSurvey')"
                                    onkeyup="numberWithCommas('reqValueSurvey')" 
                         >
                         <input name="reqValueNet" id="reqValueNet" type="text" class="easyui-validatebox textbox form-control" value="<?= currencyToPage2($reqValueNet) ?>" style="width:33%" 
                          onchange="numberWithCommas('reqValueNet')"
                                    onkeyup="numberWithCommas('reqValueNet')" 
                         >
                         <input name="reqValueDeadweight" id="reqValueDeadweight" type="text" class="easyui-validatebox textbox form-control" value="<?= currencyToPage2($reqValueDeadweight) ?>" style="width:32%" 
                          onchange="numberWithCommas('reqValueDeadweight')"
                                    onkeyup="numberWithCommas('reqValueDeadweight')" 
                         >
                         <!-- <input class="easyui-combobox form-control" style="width:40%" name="reqVasselCurrency" data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="<?= $reqCurrency ?>" /> -->
                    </div>
                </div>
            </div>
        </div>
           <div class="form-group">
            <label for="reqLa2Cp2" class="control-label col-md-2">Telepone</label>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-md-11">
                        <input type="text" id="reqContactPerson" class="easyui-validatebox textbox form-control" name="reqContactTelp" value="<?= $reqContactTelp ?>" style=" width:100%" />
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="reqLa2Cp2" class="control-label col-md-2">IMO Number</label>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-md-11">
                        <input type="text" id="reqSurveyorName" class="easyui-validatebox textbox form-control" name="reqSurveyorName" value="<?= $reqSurveyorName ?>" style=" width:100%" />
                    </div>
                </div>
            </div>
            <label for="reqLa2Cp2Phone" class="control-label col-md-2">Port of Registry</label>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-md-11">
                        <input type="text" id="reqSurveyorPhone" class="easyui-validatebox textbox form-control" name="reqSurveyorPhone" value="<?= $reqSurveyorPhone    ?>" style=" width:100%" />


                    </div>
                </div>
            </div>
        </div>
           <div class="form-group">
            <label for="reqLa2Cp2" class="control-label col-md-2">Tanggal Survey</label>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-md-11">
                         <input type="text" class="easyui-datebox textbox form-control" name="reqTanggalSurvey"  value="<?= $reqTanggalSurvey ?>" style=" width:200px" />
                    </div>
                </div>
            </div>
            <label for="reqLa2Cp2Phone" class="control-label col-md-2">Next Survey</label>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-md-11">
                         <input type="text" class="easyui-datebox textbox form-control" name="reqTanggalNext"  value="<?= $reqTanggalNext ?>" style=" width:200px" />


                    </div>
                </div>
            </div>
        </div>
        <div class="page-header">
            <h3><i class="fa fa-file-text fa-lg"></i> Vessel History
            </h3>
        </div>

        <div style="padding: 10px">
            <table style="width: 100%" class="table table-bordered">
                <thead>
                    <tr>
                        <th> Project Name </th>
                        <th> Company </th>
                        <th> Service Date </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <?php
                $paramsArray = array("A.VESSEL_ID" => "0");
                if($reqId != ""){
                    $paramsArray = array("CAST(B.OFFER_ID AS VARCHAR)" => $reqIds[1]);
                }
                $vessel_history = new Vessel();
                $vessel_history->selectByParamsHistory($paramsArray);
                while ($vessel_history->nextRow()) {
                ?> 
                    <tr>
                        <td><?=$vessel_history->getField("PROJECT_NAME")?></td>
                        <td><?=$vessel_history->getField("COMPANY_NAME")?></td>
                        <td><?=$vessel_history->getField("DATE_OF_SERVICE")?></td>
                    </tr>
                <?php
                }
                ?>
                    
                </tbody>
            </table>
        </div>
        <div style="text-align:center;padding:5px">
             <input type="hidden" name="reqId" id="reqId" value="<?=$reqId ?>" />
             <input type="hidden" name="reqCompanyId" id="reqCompanyId" value="<?=$reqCompanyId ?>" />
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Close</a>
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
                url: 'web/vessel_detail_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                  

        // $.messager.alertLink('Info', datas[2], 'info', "app/loadUrl/app/template_load_vessel?reqId="+datas[0]+"&reqCompanyId="+datas[1]);
        clearForm() ;
                   
                }
            });
        }

        function clearForm() {
            top.reload_table();
            top.closePopup();
        }
    
</script>
 <script>
        window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>

