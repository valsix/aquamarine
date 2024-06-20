    <?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Offer");
$this->load->model("OfferRevisi");
$this->load->model("Company");
$this->load->model("Vessel");

$aColumns = array("OFFER_ID", "AKSI", "CATEGORY", "DESCRIPTION", "QUANTITY", "DURATION", "UOM", "PRICE", "TOTAL");

$offer = new Offer();

$reqId = $this->input->get("reqId");
$reqRevId = $this->input->get("reqRevId");
$reqNom = $this->input->get("reqNom");

if ($reqId == "") {
    $reqMode = "insert";
    $reqPriceUnit = "Vessel";
    $reqPaymentMethod = "
        <p>Advance Payment&nbsp;&nbsp;&nbsp; : 50% - Upon SO issued and prior mobilization</p>
        <p>Balance Payment&nbsp;&nbsp;&nbsp;&nbsp; : 50% - Before Invoice and Report Sent</p>
    ";
    $reqMinimumCharger = "<p>7 Days (by hydraulic power pack to cover works for maximum marine growth is 2 cm with approx 50%. Coverage, Beyond & days, Daily rate will applicable)</p>";
} else {
    $reqMode = "ubah";
    if($reqRevId == "") {
        $offer->selectByParamsMonitoring(array("OFFER_ID" => $reqId));    
    } else {
        $offer->selectByParamsRevisi(array("OFFER_ID" => $reqId, "OFFER_REVISI_ID" => $reqRevId));
    }
    
    // echo  $offer->query;exit;
    $offer->firstRow();
    $reqOfferId          = $offer->getField("OFFER_ID");
    $reqDocumentId       = $offer->getField("DOCUMENT_ID");
    $reqDocumentPerson   = $offer->getField("DOCUMENT_PERSON");
    $reqDestination      = $offer->getField("DESTINATION");
    $reqDateOfService    = $offer->getField("DATE_OF_SERVICE");
    $reqTypeOfService    = $offer->getField("TYPE_OF_SERVICE");
    $reqScopeOfWork      = $offer->getField("SCOPE_OF_WORK");
    $reqTermAndCondition = $offer->getField("TERM_AND_CONDITION");
    $reqPaymentMethod    = $offer->getField("PAYMENT_METHOD");
    $reqTotalPrice       = $offer->getField("TOTAL_PRICE");
    $reqPriceUnit        = $offer->getField("PRICE_UNIT");
    $reqTotalPriceWord   = $offer->getField("TOTAL_PRICE_WORD");
    $reqStatus           = $offer->getField("STATUS");
    // $reqReason           = $offer->getField("REASON");
     $reqReason           = $offer->getField("MASTER_REASON_ID");
    $reqNoOrder          = $offer->getField("NO_ORDER");
    // echo $reqNoOrder;exit;
    $reqDateOfOrder      = $offer->getField("DATE_OF_ORDER");
    $reqCompanyName      = $offer->getField("COMPANY_NAME");
    $reqAddress          = $offer->getField("ADDRESS");
    $reqFaximile         = $offer->getField("FAXIMILE");
    $reqEmail            = $offer->getField("EMAIL");
    $reqTelephone        = $offer->getField("TELEPHONE");
    $reqHp               = $offer->getField("HP");
    $reqVesselName       = $offer->getField("VESSEL_NAME");
    $reqTypeOfVessel     = $offer->getField("TYPE_OF_VESSEL");
    $reqClassOfVessel    = $offer->getField("CLASS_OF_VESSEL");
    $reqDimensionL       = str_replace(",", ".", $offer->getField("VESSEL_DIMENSION_L"));
    $reqDimensionB       = str_replace(",", ".", $offer->getField("VESSEL_DIMENSION_B"));
    $reqDimensionD       = str_replace(",", ".", $offer->getField("VESSEL_DIMENSION_D"));
    $vesselIds           = $offer->getField("VESSEL_ID");
    // $reqMaker            = $offer->getField("MAKER");
    $reqMaker            = $offer->getField("PENANGGUNG_JAWAB_ID");


    $reqIssueDate       = $offer->getField("ISSUE_DATE");
    $reqPreparedBy      = $offer->getField("PREPARED_BY");
    $reqReviewedBy      = $offer->getField("REVIEWED_BY");
    $reqApprovedBy      = $offer->getField("APPROVED_BY");
    $reqIssuePurpose    = $offer->getField("ISSUE_PURPOSE");
    $reqSubject         = $offer->getField("SUBJECT");
    $reqGeneralService  = $offer->getField("GENERAL_SERVICE");
    $reqGeneralServiceDetail= $offer->getField("GENERAL_SERVICE_DETAIL");
    $reqProposalValidaty= $offer->getField("PROPOSAL_VALIDATY");
    $reqTechicalScope   = $offer->getField("TECHICAL_SCOPE");
    $reqTechicalSupport = $offer->getField("TECHICAL_SUPPORT");
    $reqCommercialSupport= $offer->getField("COMMERCIAL_SUPPORT");
    $companyIds          = $offer->getField("COMPANY_ID");
    $reqTTD             = $offer->getField("PENANGGUNG_JAWAB_ID");
    $reqPOName             = $offer->getField("PO_NAME");
    $reqPODesc             = $offer->getField("PO_DESCRIPTION");
    $reqPOPath             = $offer->getField("PO_PATH");
    $reqMinimumCharger     = $offer->getField("MINIMUM_CHARGER");
    $reqWorkTime           = $offer->getField("WORK_TIME");
    $reqRevVersi           = $offer->getField("REV_VERSI");

    // if($reqPaymentMethod == "")
    // {
    //     $reqPaymentMethod = "
    //         <p>Advance Payment&nbsp;&nbsp;&nbsp; : 50% - Upon SO issued and prior mobilization</p>
    //         <p>Balance Payment&nbsp;&nbsp;&nbsp;&nbsp; : 50% - Before Invoice and Report Sent</p>
    //     ";
    // }

    // if($reqMinimumCharger == "") {
    //     $reqMinimumCharger = "<p>7 Days (by hydraulic power pack to cover works for maximum marine growth is 2 cm with approx 50%. Coverage, Beyond & days, Daily rate will applicable)</p>";
    // }
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
 // $html = file_get_contents($this->config->item('base_report') . "report/index/offer_project/?reqId=" . $reqId);

 // echo $html;exit;
 // $html = urlencode($html);

    $mailto = "mailto:".$reqEmail."?body=%0D%0A ".$html."&subject=".$reqSubject;
?>
<?php
// $to      = 'nobody@example.com';
// $subject = 'the subject';
// $message = 'hello';
// $headers = 'From: webmaster@example.com' . "\r\n" .
//     'Reply-To: webmaster@example.com' . "\r\n" .
//     'X-Mailer: PHP/' . phpversion();

// mail($to, $subject, $message, $headers);
?> 

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<style type="text/css">
    #datagrid-row-r9-2-10 td div .tree-title{
        height: 170px
    }

</style>

<script type="text/javascript" language="javascript" class="init">
    var oTable;
    var anSelectedData = '';
    var anSelectedId = '';
    var anSelectedDownload = '';
    var anSelectedPosition = '';
    var anIndex = '';
    $(document).ready(function() {
        nominal_kekata()
        oTable = $('#example').dataTable({
            bJQueryUI: true,
            "iDisplayLength": 25,
            /* UNTUK MENGHIDE KOLOM ID */
            "aoColumns": [{
                    bVisible: false
                },
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null
            ],
            "bSort": true,
            "bProcessing": true,
            "bServerSide": true,
            "bScrollInfinite": true,
            "sAjaxSource": "web/offer_project_json/json?reqOfferId=<?=$reqId?>",
            columnDefs: [{
                className: 'never',
                targets: [0]
            }],
            responsive: false,
            "sPaginationType": "full_numbers"

        });
        /* Click event handler */

        /* RIGHT CLICK EVENT */
        

        function fnGetSelected(oTableLocal) {
            var aReturn = new Array();
            var aTrs = oTableLocal.fnGetNodes();
            for (var i = 0; i < aTrs.length; i++) {
                if ($(aTrs[i]).hasClass('row_selected')) {
                    aReturn.push(aTrs[i]);
                    anSelectedPosition = i;
                }
            }
            return aReturn;
        }

        $("#example tbody").click(function(event) {
            $(oTable.fnSettings().aoData).each(function() {
                $(this.nTr).removeClass('row_selected');
            });
            $(event.target.parentNode).addClass('row_selected');

            var anSelected = fnGetSelected(oTable);
            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            anSelectedId = element[0];
            anIndex = anSelected[0];
        });


        // double click
        $('#example tbody').on('dblclick', 'tr', function() {

            var anSelected = fnGetSelected(oTable);
            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            anIndex = anSelected[0];
            anSelectedId = element[0];
            document.location.href = "app/index/service_order_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });



        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/service_order_add";

        });

        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/service_order_add?reqId=" + anSelectedId;

        });
        $('#btnExcel').on('click', function() {
            openAdd('app/loadUrl/app/excel_service_order?=<?= $add_str ?>');

        });

        $('#btnPrint').on('click', function() {
           
      var add_str = "reqCariNoOrder="+$("#reqCariNoOrder").val() ;            
      add_str += "&reqCariPeriodeYearFrom="+$("#reqCariPeriodeYearFrom").datebox('getValue');
      add_str += "&reqCariPeriodeYearTo="+$("#reqCariPeriodeYearTo").datebox('getValue');
      add_str += "&reqCariCompanyName="+$("#reqCariCompanyName").val();
      add_str += "&reqCariPeriodeYear="+$("#reqCariPeriodeYear").val();
      add_str += "&reqCariVasselName="+$("#reqCariVasselName").val();
      add_str += "&reqCariProject="+$("#reqCariProject").val();
      add_str += "&reqCariGlobal="+$("#reqCariGlobal").val();
            openAdd('app/loadUrl/report/service_order_pdf?'+add_str);
            // openAdd('report/index/report_cetak_service_order_pdf?<?= $add_str ?>');

        });

        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            // deleteData("web/service_order_json/delete", anSelectedId);
            deleteData_for_table('web/service_order_json/delete', anSelectedId, anIndex, 1);

        });
        $('#btnRefresh').on('click', function() {
            Refresh();
        });

    });
</script>

<style type="text/css">
    .messager-body {
        font-size: 16px !important;
    }
    .panel-title {
        font-size: 18px !important;
    }
    .l-btn-text {
        font-size: 16px !important;
    }
    .messager-window {
        width: 400px !important;
    }
    .window-header {
        width: 387.5px !important;
    }
    .window-body {
        width: 387.5px !important;
    }
</style>

<div class="col-md-12">

    <?php

   
    // $survey = base_url()."app/loadUrl/report/offer_new_pdf?reqId=".$reqId;
    // $project = base_url()."app/loadUrl/report/offer_project_pdf?reqId=".$reqId;
    // $mailto .= $survey."%0D%0A".$project;
    ?>

    <div class="judul-halaman"> <a href="app/index/offer">Offer</a> &rsaquo; Form Offer
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold; margin-left: 10px;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-left: 10px" onclick="cetakPdfSurvey()"><i class="fa fa-file-pdf-o "> </i><span> PDF Survey</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-left: 10px" onclick="cetakPdfProject()"><i class="fa fa-file-pdf-o "> </i><span> PDF Project</span> </a>
        <a class="pull-right " href="<?=$mailto?>" style="color: white;font-weight: bold;margin-left: 10px"><i class="fa fa-envelope"> </i><span> Send As Email</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="loadAttacment()"><i class="fa fa-paperclip"> </i><span> Attachment</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="services()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Services </span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_ttd()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master PJ</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_category_project()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Category</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="rev_history()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Revisi History</span> </a>
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

                                  
                                    <input type="hidden" class="easyui-validatebox textbox form-control" name="reqCompanyId" id="reqCompanyId" value="<?= $companyIds ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Contact Person</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" id="reqDocumentPerson" name="reqDocumentPerson" value="<?= $reqDocumentPerson ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">List Support Contact</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <input class="easyui-combobox form-control" style="width:100%"  id="reqContactSupport" data-options="width:'250',editable:true, valueField:'id',textField:'text',url:'combo_json/contact_support?reqCompanyId=<?=$companyIds?>'" value="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">

                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Address</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control tinyMCES" name="reqAddress" id="reqAddress" style="width:100%;"><?= $reqAddress; ?></textarea>
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
                                   

                                    <input type="text" id="reqHp" onkeypress='validatePhoneNumber(event)' class="easyui-validatebox textbox form-control" name="reqHp" value="<?= $reqHp ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Fax</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqFaximile" id="reqFaximile" value="<?= $reqFaximile ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Handphone</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input type="text" onkeypress='validatePhoneNumber(event)' id="reqTelephone" class="easyui-validatebox textbox form-control" name="reqTelephone" value="<?= $reqTelephone ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">

                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Name of Vessel</label>
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
                                    <input type="hidden" id="reqVesselId" name="reqVesselId" value="<?= $vesselIds ?>">
                                   
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqClassOfVessel" class="control-label col-md-2">Class of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqClassOfVessel" id="reqClassOfVessel" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel'" value="<?= $reqClassOfVessel ?>" />
                                </div>
                            </div>
                        </div>
                        <label for="reqTypeOfVessel" class="control-label col-md-2">Type Of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqTypeOfVessel" id="reqTypeOfVessel" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueTypeOfVessel'" value="<?= $reqTypeOfVessel ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqPhone" class="control-label col-md-2">Dimension</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">L</span>
                                        <input type="text" class="form-control" name="reqDimensionL" id="reqDimensionL" value="<?=currencyToPage2($reqDimensionL)?>" onchange="numberWithCommas('reqDimensionL')" onkeyup="numberWithCommas('reqDimensionL')"

                                        >


                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">B</span>
                                        <input type="text" class="form-control"  id="reqDimensionB" name="reqDimensionB" value="<?=currencyToPage2($reqDimensionB)?>" onchange="numberWithCommas('reqDimensionB')" onkeyup="numberWithCommas('reqDimensionB')"

                                        >

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">D</span>
                                        <input type="text" class="form-control"  id="reqDimensionD" name="reqDimensionD" value="<?=currencyToPage2($reqDimensionD)?>" ononchange="numberWithCommas('reqDimensionD')" onkeyup="numberWithCommas('reqDimensionD')"

                                        >

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>Rev - <?=$reqRevVersi?></h3>
                    </div>
              
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Issue Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqIssueDate" id="reqIssueDate" value="<?= $reqIssueDate ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Prepared by</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqPreparedBy" id="reqPreparedBy" value="<?= $reqPreparedBy ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqReviewedBy" class="control-label col-md-2">Reviewed by</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqReviewedBy" id="reqReviewedBy" value="<?= $reqReviewedBy ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Approved By</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input type="text" class="easyui-validatebox textbox form-control" name="reqApprovedBy" id="reqApprovedBy" value="<?= $reqApprovedBy ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Issue Purpose</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                   
                                     <input type="text" class="easyui-validatebox textbox form-control" name="reqIssuePurpose" id="reqIssuePurpose" value="<?= $reqIssuePurpose ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>

                         <label for="reqName" class="control-label col-md-2">Marketing</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqMaker" id="reqMaker" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboPenanggungJawab'" value="<?= $reqMaker ?>" />
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>Service Information</h3>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reqName" class="control-label col-md-4"> Date of Order</label>
                                <div class="col-md-8">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqDateOfOrder" id="reqDateOfOrder" value="<?= $reqDateOfOrder ?>" style=" width:100%" />
                                </div>

                                <br>
                                <br>
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reqName" class="control-label col-md-4">Document No.</label>
                                <div class="col-md-8">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqNoOrder" id="reqNoOrder" value="<?= $reqNoOrder ?>" style=" width:100%" disabled />
                                </div>

                                <br>
                                <br>
                                <br>
                                <label for="reqName" class="control-label col-md-4"> Date Of Service</label>
                                <div class="col-md-8">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqDateOfService" id="reqDateOfService" value="<?= $reqDateOfService ?>" style=" width:100%" />
                                </div>

                                <br>
                                <br>
                                <br>
                                <label for="reqName" class="control-label col-md-4">Location</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="reqDestination" cols="4" rows="3" style="width:100%;"><?= $reqDestination; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">

                                 <label for="reqName" class="control-label col-md-4"> Subject</label>
                                <div class="col-md-8">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqSubject" id="reqSubject" value="<?= $reqSubject ?>" style=" width:100%" disabled />
                                </div>
                                <br>
                                <br>
                                <br>
                                <label for="reqGeneralService" class="control-label col-md-4"> General of Services</label>
                                <div class="col-md-8">
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqGeneralService" name="reqGeneralService" data-options="width:'350',editable:false, valueField:'id',textField:'text',url:'web/services_json/combo'" value="<?= $reqGeneralService ?>" />
                                   
                                </div>
                                <br>
                                <br>
                                <br>
                                <label for="reqName" class="control-label col-md-4">Detail of Project</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="reqGeneralServiceDetail" id="reqGeneralServiceDetail" cols="4" rows="3" style="width:100%;"><?= $reqGeneralServiceDetail; ?></textarea>
                                    
                                </div>
                                    
                                  
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2"> Minimum Charge </label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea class="form-control tinyMCES" name="reqMinimumCharger" cols="4" rows="3" style="width:100%;"><?= $reqMinimumCharger; ?></textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2"> Work Time </label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea class="form-control tinyMCES" name="reqWorkTime" cols="4" rows="3" style="width:100%;"><?= $reqWorkTime; ?></textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2"> Payment Method </label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea class="form-control tinyMCES" name="reqPaymentMethod" cols="4" rows="3" style="width:100%;"><?= $reqPaymentMethod; ?></textarea>
                                </div>

                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2"> Total Price </label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-2">

                                            <?
                                            $reqTotalPrice = explode(" ", $reqTotalPrice);
                                            ?>
                                            <input class="easyui-combobox form-control" style="width:100%" id="reqVasselCurrency" name="reqVasselCurrency" data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="<?= $reqTotalPrice[0] ?>" />
                                        </div>

                                        <div class="col-md-3">
                                            <input name="reqTotalPrice1" id="reqTotalPrice1" onkeyup="numberWithCommas('reqTotalPrice1');nominal_kekata()" onchange="numberWithCommas('reqTotalPrice1');nominal_kekata()" value="<?= currencyToPage2($reqTotalPrice[1]) ?>" type="text" class="easyui-validatebox textbox form-control">
                                        </div>
                                        <div class="col-md-7">
                                            <input name="reqTotalPriceWord" id="reqTotalPriceWord" value="<?= $reqTotalPriceWord ?>" type="text" class="easyui-validatebox textbox form-control">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqPriceUnit" class="control-label col-md-2"> Price Unit </label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-12">
                                  <input type="text" class="easyui-validatebox textbox form-control" name="reqPriceUnit" id="reqPriceUnit" value="<?= $reqPriceUnit ?>" style=" width:20%" /> 
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2"> Proposal Validaty </label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-12">
                                  <input type="text" onkeypress='validatePhoneNumber(event)' class="easyui-validatebox textbox form-control" name="reqProposalValidaty" id="reqProposalValidaty" value="<?= $reqProposalValidaty ?>" style=" width:20%" disabled /> / Days
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                            <label for="reqTermAndCondition" class="control-label col-md-2"> Terms and Condition </label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <textarea class="form-control tinyMCES" name="reqTermAndCondition" cols="4" rows="3" style="width:100%;"><?= $reqTermAndCondition; ?></textarea>
                                    </div>

                                </div>
                            </div>
                        </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Term  and Conditions</h3>
                    </div>
                        <div class="form-group">
                         <div class="col-md-12">
                            <br>
             
                            <b> A.  TECHICAL SCOPE </b>
                            <table id="tt" class="easyui-treegrid" style="width:100% !important;height:400px"
                            data-options="url:'web/tree_json/treegrid?reqParam=<?=$reqId?>&reqRevId=<?=$reqRevId?>',width:'100%',
                                idField:'ID',treeField:'NAMA',
                                rownumbers: true,
                                lines: true,
                                pagination:false,
                                remoteFilter:false,
                                animate: true,        
                                collapsible: false,
                                iconCls: 'icon-ok',
                                fitColumns: true,

                                onBeforeLoad: function(row,param){

                                    if (!row) { 
                                        // load top level rows
                                        param.id = 0; // set id=0, indicate to load new page rows                            
                                    }
                                }
                        ">
                        <thead>
                            <tr>
                                <th data-options="field:'NAMA',width:280">Description</th>
                                <th data-options="field:'INC',width:100">Include</th>
                                <th data-options="field:'ENC',width:100">Exclude</th>
                                <th data-options="field:'REMARK',width:100">Remark</th>

                            </tr>
                        </thead>
                    </table>
                </div>


                <div class="col-md-12">
                    <br>
                   <b> B. TECHICAL SUPPORT </b>
                            <table id="ts" class="easyui-treegrid" style="width:100% !important;height:400px"
                            data-options="url:'web/techical_support_json/treegrid?reqParam=<?=$reqId?>&reqRevId=<?=$reqRevId?>',width:'100%',
                            idField:'ID',treeField:'NAMA',
                            rownumbers: true,
                            lines: true,
                            pagination:false,
                            remoteFilter:false,
                            animate: true,        
                            collapsible: false,
                            iconCls: 'icon-ok',
                            fitColumns: true,

                            onBeforeLoad: function(row,param){

                            if (!row) { 
                            // load top level rows
                            param.id = 0; // set id=0, indicate to load new page rows                            
                        }}
                        ">
                        <thead>
                            <tr>
                                <th data-options="field:'NAMA',width:280">Description</th>
                                <th data-options="field:'INC',width:100">Include</th>
                                <th data-options="field:'ENC',width:100">Exclude</th>
                                <th data-options="field:'REMARK',width:100">Remark</th>
                                
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="col-md-12">
                    <br>
                   <b> C. COMMERCIAL SUPPORTS </b>
                            <table id="tc" class="easyui-treegrid" style="width:100% !important;height:400px"
                                data-options="url:'web/commercial_support_json/treegrid?reqParam=<?=$reqId?>&reqRevId=<?=$reqRevId?>',width:'100%',
                                idField:'ID',treeField:'NAMA',
                                rownumbers: true,
                                lines: true,
                                pagination:false,
                                remoteFilter:false,
                                animate: true,        
                                collapsible: false,
                                iconCls: 'icon-ok',
                                fitColumns: true,

                                onBeforeLoad: function(row,param){

                                    if (!row) { 
                                        // load top level rows
                                        param.id = 0; // set id=0, indicate to load new page rows                            
                                    }

                                }
                        ">
                        <thead>
                            <tr>
                                <th data-options="field:'NAMA',width:280">Description</th>
                                <th data-options="field:'INC',width:100">Include</th>
                                <th data-options="field:'ENC',width:100">Exclude</th>
                                <th data-options="field:'REMARK',width:100">Remark</th>
                                
                            </tr>
                        </thead>
                    </table>
                </div>
                 
                </div>

                <div class="page-header">
                    <h3><i class="fa fa-file-text fa-lg"></i> Project</h3>
                </div>


                <div class="form-group">
                    <div class="col-md-12">
                        <div class="form-group ">
                            <div class="col-md-12">
                                <button type="button" onclick="addProject()" class="btn btn-primary">Add Project</button>
                                <br>
                                <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <?php
                                            for ($i = 1; $i < count($aColumns); $i++) {
                                            ?>
                                                <th><?= str_replace('_', ' ', $aColumns[$i])  ?></th>
                                            <?php

                                            };
                                            ?>
                                        </tr>
                                    </thead>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="page-header">
                    <h3><i class="fa fa-file-text fa-lg"></i> Purchase Order</h3>
                </div>
                <div class="form-group">
                    <label for="reqPOName" class="control-label col-md-2"> Name</label>
                    <div class="col-md-10">
                        <div class="form-group">
                            <div class="col-md-11">
                                <input type="text" class="easyui-validatebox textbox form-control" name="reqPOName" id="reqPOName" value="<?= $reqPOName ?>" style=" width:50%" />
                            </div>
                        </div>
                    </div>
                    <label for="reqPODesc" class="control-label col-md-2"> Description</label>
                    <div class="col-md-10">
                        <div class="form-group">
                            <div class="col-md-11">
                                <textarea class="form-control" name="reqPODesc" id="reqPODesc" style="width:50%;"><?= $reqPODesc; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="padding: 10px">
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
                            $files_data = explode(';',  $reqPOPath);
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
                                              <a href="uploads/offer_po/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                              <a onclick="openAdd(`uploads/offer_po/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
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
                </div>

                <div class="page-header">
                    <h3><i class="fa fa-file-text fa-lg"></i> Service Status</h3>
                </div>

                <div class="form-group">
                    <label for="reqName" class="control-label col-md-2">Status Offer</label>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-md-11">
                                <input class="easyui-combobox form-control" style="width:100%" name="reqStatus" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'combo_json/status'" value="<?= $reqStatus ?>" />
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <label for="reqName" class="control-label col-md-2">Reason </label>
                    <div class="col-md-8">
                        <div class="form-group">
                            <div class="col-md-11">
                                 <input class="easyui-combobox form-control" style="width:350px" name="reqReason" data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/combo_reason'" value="<?= $reqReason ?>" />
                                <!-- <input name="reqReason" value="<?= $reqReason ?>" type="text" class="easyui-validatebox textbox form-control"> -->
                            </div>
                        </div>
                    </div>

                </div>

                <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />
                <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />
                <input type="hidden" name="reqAddRev" id="reqAddRev" value="" />
                <input type="hidden" name="reqRevId" id="reqRevId" value="<?=$reqRevId?>" />


                </form>
            </div>
            <?php
            if($reqRevId == "")
            {
            ?>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="beforeSubmit()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>  
            <?php
            }
            else
            {
            ?>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitFormRevisi()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>  
            <?php
            }
            ?>
        </div>

    </div>

    <script>
        function beforeSubmit() {
            if("<?=$reqId?>" == "") {
                submitForm();
            } else {
                $.messager.defaults.ok = 'Ya';
                $.messager.defaults.cancel = 'Tidak';
                $.messager.confirm('Konfirmasi', 'Apakah anda ingin menambahkan perubahan saat ini sebagai revisi?', function(r) {
                    if (r) {
                        $("#reqAddRev").val("1");
                        submitForm();
                    } else {
                        submitForm();
                    }
                });
            }
        }
        function submitForm() {
            
            var win = $.messager.progress({
                title: 'Office Management  | PT Aquamarine Divindo',
                msg: 'proses data...'
            });

            $('#ff').form('submit', {
                url: 'web/offer_json/add_new',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');
                    show_toast('info', 'Information', 'Header success added <br>' + datas[1]);
                    // alert(data);
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/offer_add?reqId=" + datas[0]);
                    $.messager.progress('close');
                }
            });
        }
        function submitFormRevisi() {
            
            var win = $.messager.progress({
                title: 'Office Management  | PT Aquamarine Divindo',
                msg: 'proses data...'
            });

            $('#ff').form('submit', {
                url: 'web/offer_json/add_revisi',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');
                    show_toast('info', 'Information', 'Header success added <br>' + datas[1]);
                    // alert(data);
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/offer_add?reqId=<?=$reqId?>&reqRevId=<?=$reqRevId?>");
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
                $('#tt').treegrid('expandAll');      
                $('#ts').treegrid('expandAll');      
                $('#tc').treegrid('expandAll');      
            }, 1000);
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

            var cc = $('#reqContactSupport');
            cc.combobox('setText', '');
            cc.combobox('setValue', '');
            var url = 'combo_json/contact_support?reqCompanyId=' + id;
            cc.combobox('reload', url);
        }

        function openCompany() {
            openAdd('app/loadUrl/app/template_load_company_id_new');

        }

        function addProject() {
            if('<?=$reqId?>' == ''){
                $.messager.confirm('Confirm','Offer will be saved before adding project?',function(r){
                    if (r){
                        submitForm();
                    }
                });
            } else {
                openAdd('app/loadUrl/app/template_load_offer_project?reqOfferId=<?=$reqId?>');
            }
        }

        function editing(id) {
            openAdd('app/loadUrl/app/template_load_offer_project?reqOfferId=<?=$reqId?>&reqId='+id);
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
            // $.get("web/offer_json/sending_mail?reqId=" + id + "&reqBahasa=" + bahasa, function(data) {

            //     $.messager.alert('Info', data, 'info');
            //     $.messager.progress('close');
            // });

        }

        function openVessel() {
            var companyId = $("#reqCompanyId").val();
            openAdd('app/loadUrl/app/template_load_vessel_id?reqCompanyId=' + companyId);
        }


        function loadAttacment() {
            openAdd('app/loadUrl/app/tempalate_attacment?reqOfferId=<?= $reqId ?>');
        }


        function cetakPdfSurvey() {
            // var url = "app/loadUrl/report/template_report_offer_pdf?reqId=<?= $reqId ?>";
            var url ="app/loadUrl/report/offer_new_pdf?reqId=<?= $reqId ?>&reqRevId=<?=$reqRevId?>&reqNom=<?=$reqNom?>";
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
        $('#reqVasselCurrency').combobox({
            onSelect: function(param) {
                nominal_kekata();
            }
        });

        function nominal_kekata() {
            var currenct = $('#reqVasselCurrency').combobox('getValue');
            var reqTotal = $("#reqTotalPrice1").val();
            var xx = reqTotal.replaceAll('.', '');
            var xx = xx.replaceAll(',', '.');
            if (xx == '') {
                xx = 0;
            }
            var text = '';
            $.get("app/terbilang?angka="+xx+'&reqCur='+currenct, function(data) {
                // if(currenct == 'IDR') {
                //     text = data + " Rupiah ";
                // } else {
                //     text = data + " Dollar ";
                // }
                $("#reqTotalPriceWord").val(data);
            });
            
        }
    </script>
    <script type="text/javascript">
        function services(){
            openAdd('app/loadUrl/app/tempalate_master_services');
        }
        function rev_history(){
            openAdd('app/loadUrl/app/tempalate_offer_revisi_history?reqId=<?=$reqId?>');
        }
    </script>
    <script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript">
        // $('#reqGeneralService').combobox({
        //     onSelect: function(param) {
        //      $.get("web/services_json/getValue?reqId="+param.id, function(data) {

        //         $("#reqGeneralServiceDetail").val(data);

        //     });
        //     }
        // });
    </script>

    <script type="text/javascript">
        function terbilang(bilangan) {

            bilangan = String(bilangan);
            var angka = new Array('0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
            var kata = new Array('', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan');
            var tingkat = new Array('', 'Ribu', 'Juta', 'Milyar', 'Triliun');

            var panjang_bilangan = bilangan.length;

            /* pengujian panjang bilangan */
            if (panjang_bilangan > 15) {
                kaLimat = "Diluar Batas";
                return kaLimat;
            }

            /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
            for (i = 1; i <= panjang_bilangan; i++) {
                angka[i] = bilangan.substr(-(i), 1);
            }

            i = 1;
            j = 0;
            kaLimat = "";


            /* mulai proses iterasi terhadap array angka */
            while (i <= panjang_bilangan) {

                subkaLimat = "";
                kata1 = "";
                kata2 = "";
                kata3 = "";

                /* untuk Ratusan */
                if (angka[i + 2] != "0") {
                    if (angka[i + 2] == "1") {
                        kata1 = "Seratus";
                    } else {
                        kata1 = kata[angka[i + 2]] + " Ratus";
                    }
                }

                /* untuk Puluhan atau Belasan */
                if (angka[i + 1] != "0") {
                    if (angka[i + 1] == "1") {
                        if (angka[i] == "0") {
                            kata2 = "Sepuluh";
                        } else if (angka[i] == "1") {
                            kata2 = "Sebelas";
                        } else {
                            kata2 = kata[angka[i]] + " Belas";
                        }
                    } else {
                        kata2 = kata[angka[i + 1]] + " Puluh";
                    }
                }

                /* untuk Satuan */
                if (angka[i] != "0") {
                    if (angka[i + 1] != "1") {
                        kata3 = kata[angka[i]];
                    }
                }

                /* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
                if ((angka[i] != "0") || (angka[i + 1] != "0") || (angka[i + 2] != "0")) {
                    subkaLimat = kata1 + " " + kata2 + " " + kata3 + " " + tingkat[j] + " ";
                }

                /* gabungkan variabe sub kaLimat (untuk Satu blok 3 angka) ke variabel kaLimat */
                kaLimat = subkaLimat + kaLimat;
                i = i + 3;
                j = j + 1;

            }

            /* mengganti Satu Ribu jadi Seribu jika diperlukan */
            if ((angka[5] == "0") && (angka[6] == "0")) {
                kaLimat = kaLimat.replace("Satu Ribu", "Seribu");
            }

            return kaLimat + "Rupiah";
        }
    </script>
    <script type="text/javascript">
        function clearCompany(){

            $('#reqCompanyName').val('');
            $('#reqCompanyId').val('');
            $('#reqDocumentPerson').val('');
             $(tinymce.get('reqAddress').getBody()).html('');
            $('#reqAddress').val('');
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

    <script type="text/javascript">
         $('#reqContactSupport').combobox({
            onSelect: function(param) {
              $.get("web/customer_json/ambil_costumer_support?reqId="+param.id, function(data) {
                var obj = JSON.parse(data);
                $("#reqDocumentPerson").val(obj.NAMA);
                 $("#reqEmail").val(obj.EMAIL);
                  $("#reqTelephone").val(obj.TELP);
                
                
                // $("#monitoring_attacemnt").append(data);
              });
            

            }
        });
    </script>
</div>