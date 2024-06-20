<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

// $aColumns = array("COST_PROJECT_DETIL_ID", "COST_PROJECT_ID", "COST_DATE", "DESCRIPTION", "COST", "STATUS", "COST_DATE", "STATUS", "CURRENCY", "CURRENCY_COST", "AKSI", "COST_VALUE","TOTAL_HPP_COST");

$this->load->model("Project_cost");
$this->load->model("Offer");
$projectCost = new Project_cost();

$this->load->model("Service_order");


$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $projectCost->selectByParamsNew(array("A.COST_PROJECT_ID" => $reqId));
    $projectCost->firstRow();

    $reqNoProject       = $projectCost->getField("NO_PROJECT");
    $reqVesselName      = $projectCost->getField("VESSEL_NAME");
    $reqTypeOfVessel    = $projectCost->getField("TYPE_OF_VESSEL");
    $reqTypeOfService   = $projectCost->getField("TYPE_OF_SERVICE");
    $reqDateService1    = $projectCost->getField("DATE_SERVICE1");
    $reqDateService2    = $projectCost->getField("DATE_SERVICE2");
    $reqDestination     = $projectCost->getField("DESTINATION");
    $reqCompanyName     = $projectCost->getField("COMPANY_NAME");
    $reqContactPerson   = $projectCost->getField("CONTACT_PERSON");
    $reqKasbon          = $projectCost->getField("KASBON");
    $reqOfferPrice      = $projectCost->getField("OFFER_PRICE");
    $reqRealPrice       = $projectCost->getField("REAL_PRICE");
    $reqSurveyor        = $projectCost->getField("SURVEYOR");
    $reqOperator        = $projectCost->getField("OPERATOR");
    $reqKasbonCur       = $projectCost->getField("KASBON_CUR");
    $reqOfferCur        = $projectCost->getField("OFFER_CUR");
    $reqRealCur         = $projectCost->getField("REAL_CUR");
    $reqAddService      = $projectCost->getField("ADD_SERVICE");
    $reqOfferId         = $projectCost->getField("OFFER_ID");
    $reqHppProjectId    = $projectCost->getField("HPP_PROJECT_ID");
    $reqClassOfVessel   = $projectCost->getField("CLASS_OF_VESSEL");
    

    $reqServiceOrderId  = $projectCost->getField("SERVICE_ORDER_ID");

    $serviceOrder = new Service_order();
    if (!empty($reqServiceOrderId)) {
        // $statement = " AND A.SO_ID =".ValToNullDB($reqServiceOrderId);
        $serviceOrder->selectByParams(array("A.SO_ID" => $reqServiceOrderId));
        $serviceOrder->firstRow();

        $reqKodeServiceOrder = $serviceOrder->getField("NO_ORDER");
    }
}
$CURRENCY_COST ='CURRENCY_COST';
$target ='0, 1,3, 6, 8, 9, 10,12,13,14,15,17,18,19';

if(!empty($reqHppProjectId)){
$CURRENCY_COST ='REALISASI';
$target ='0, 1,4, 6,7, 8, 9, 10,17,18,19';
$noDisplay = 'style="display:none"';
}
$aColumns = array("COST_PROJECT_DETIL_ID", "COST_PROJECT_ID", "NO","CODE","COST_DATE", "DESCRIPTION", "COST", "STATUS", "COST_DATE", "STATUS", "CURRENCY", $CURRENCY_COST,"QTY","UNIT_RATE","DAYS","TOTAL_HPP_COST", "AKSI", "COST_VALUE","COST_HPP","CODE");

// echo count($aColumns);exit;
?>


<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<script type="text/javascript" language="javascript" class="init">
    var oTable;
    var total;
    var reqIds;
     
    var pc = parseFloat('<?= $reqRealPrice ?>');
    $(document).ready(function() {
        reqIds = $("#reqId").val();
      
        if (reqIds == '') {
            reqIds = '-0';
        }
        oTable = $('#example').dataTable({
            bJQueryUI: true,
            "iDisplayLength": 10,
            /* UNTUK MENGHIDE KOLOM ID */
            "aoColumns": [{
                    bVisible: false
                },
                <?
                for ($i = 1; $i < count($aColumns) - 1; $i++) {
                ?>
                    null,
                <?
                }
                ?>
                null
            ],
            "bSort": true,
            "bProcessing": true,
            "bServerSide": false,
            "bScrollInfinite": true,
            "sAjaxSource": "web/cost_project_detil_json/json?reqId=" + reqIds,
            "fnServerParams": function(aoData) {
                // aoData.push( { "name": "input1", "value": $("#input1").val() } );
                // aoData.push( { "name": "input2", "value": $("#input2").val() } );
                // aoData.push( { "name": "input3", "value": $("#input3").val() } );
            },
            columnDefs: [{
                className: 'never',
                targets: [<?=$target?>]
            }],
            "bStateSave": true,
            "fnStateSave": function(oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function(oSettings) {
                var data = localStorage.getItem('DataTables_' + window.location.pathname);
                return JSON.parse(data);
            },
            "fnCreatedRow": function (row, data, index) {
                $('td', row).eq(0).html(index + 1);
            },
            "sPaginationType": "full_numbers",
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;
                var intVal = function(i) {
                    // console.log(i);
                    return typeof i === 'string' ?
                        i.replaceAll('.', '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };
                total = api
                    .column(17)
                    .data()
                    .reduce(function(a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);
                  var  total_hpp = api
                    .column(18)
                    .data()
                    .reduce(function(a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);
                
                    console.log(total_hpp);
                var saldo = pc - total;

                // if (pc == '' || pc == 0) {
                //     var al = $('#reqRealPrice').val();
                //     pc = parseInt(al.replace('.', ''));
                // }
                // console.log(pc)
                 $("#reqTotalHpp").val(FormatCurrencyBaru(total_hpp));
                $("#reqPc").val(FormatCurrencyBaru(pc));
                $("#reqOp").val(FormatCurrencyBaru(total));
                $("#reqSaldo").val(FormatCurrencyBaru(saldo));

                var profit = (saldo / pc) * 100;
                var formats_persen = round(profit, 2);
                // console.log(formats_persen);
                if (Number.isNaN(formats_persen) || formats_persen == "" ) 
                {
                    $("#reqProfit").html("( Profit: 0 % )");
                }
                else
                {
                    $("#reqProfit").html("( Profit: " + formats_persen + " % )");
                }
                    


            }
                

        });
        /* Click event handler */

        /* RIGHT CLICK EVENT */
        var anSelectedData = '';
        var anSelectedId = '';
        var elements = '';
        var anSelectedDownload = '';
        var anSelectedPosition = '';

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
            // console.log(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            elements = oTable.fnGetData(anSelected[0]);
            anSelectedId = element[0];
        });

        $('#btnAdd').on('click', function() {
            // document.location.href = "app/index/cash_report_add";

            $('#btnProses').show();
            $('#btnProses').html('Add');
            clearForm();

        });

        $('.editing').on('click', function() {
            if (anSelectedData == "")
                return false;






        });

        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            del(anSelectedId);
        });

        $('#btnRefresh').on('click', function() {
            Refresh();
        });

        $('#btnProses').on('click', function() {
            submitForm();
        });

        $('#btnExcel').on('click', function() {
            window.open('app/loadUrl/app/excel_project_cost_detail?reqId=<?=$reqId?>');
        });
        $('#btnPdf').on('click', function() {
            // window.open('app/loadUrl/app/excel_project_cost_detail?reqId=<?=$reqId?>');
            <?
            if(empty($reqHppProjectId)){
            ?>
            openAdd('app/loadUrl/report/report_cost_project_pdf?reqId=<?=$reqId?>');
            <?
            }else{
            ?>
             openAdd('app/loadUrl/report/report_cost_project_pdf?reqId=<?=$reqId?>&reqHppId=<?=$reqHppProjectId?>');
            <?
            }
            ?>
        });

    });
</script>


<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/project_cost">Project Cost</a> &rsaquo; Form Project Cost
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <?
        if(!empty($reqId)){
            if(empty($reqHppProjectId)){
        ?>
        <a id="btnExcel" class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" ><i class="fa fa-file-excel-o "> </i><span> Excel</span> </a>
        <?
                }
        ?>
        <a id="btnPdf" class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" ><i class="fa fa fa-file-pdf-o  "> </i><span> PDF</span> </a>
        <?
         }   
        ?>
    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Project Information
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>
                        </h3>
                        <br>

                    </div>



                    <div class="form-group">
                        <label for="reqNoProject" class="control-label col-md-2">Project No.</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group" style="width: 37%">
                                        <input type="text" id="reqNoProject" class="easyui-validatebox textbox form-control" name="reqNoProject" value="<?= $reqNoProject ?>" style=" width:100%" placeholder="Pilih Project No"  />
                                        <input type="hidden" value="<?=$reqOfferId?>" id="reqOfferId" name="reqOfferId">
                                        <!-- <input type="hidden" value="<?=$hppprojectid?>" id="hppprojectid" name="hppprojectid"> -->

                                        <?
                                        if(empty($reqHppProjectId)){
                                        ?>
                                        <span class="input-group-addon" onclick="open_service_order()"><i>...</i></span>
                                        <?
                                        }
                                        ?>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>

               <!--      <div class="form-group">
                        <label for="reqNoProject" class="control-label col-md-2">No OWR</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" id="reqServiceOrderId" name="reqServiceOrderId" class="easyui-validatebox textbox form-control" value="<?= $reqServiceOrderId ?>" data-options="required:true">
                                    <input onclick="open_service_order()" type="text" id="reqKodeServiceOrder" class="easyui-validatebox textbox form-control" name="reqKodeServiceOrder" value="<?= $reqKodeServiceOrder ?>" style=" width:100%" data-options="required:true" />
                                </div>
                            </div>
                        </div>

                        <label for="reqNoProject" class="control-label col-md-2">Add Service</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">

                                    <input type="text" id="reqAddService" class="easyui-validatebox textbox form-control" name="reqAddService" value="<?= $reqAddService ?>" style=" width:100%" data-options="required:true" />
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="form-group">
                        <label for="reqCompanyName" class="control-label col-md-2">Company of Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCompanyName" class="easyui-validatebox textbox form-control" name="reqCompanyName" value="<?= $reqCompanyName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqVesselName" class="control-label col-md-2">Name of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqVesselName" class="easyui-validatebox textbox form-control" name="reqVesselName" value="<?= $reqVesselName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                       
                    </div>
                     <div class="form-group">
                        <label for="reqClassOfVessel" class="control-label col-md-2">Class of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqClassOfVessel" class="easyui-validatebox textbox form-control" name="reqClassOfVessel" value="<?= $reqClassOfVessel ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                       
                    </div>

                    <div class="form-group">
                        <label for="reqTypeOfVessel" class="control-label col-md-2">Type of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqTypeOfVessel" class="easyui-validatebox textbox form-control" name="reqTypeOfVessel" value="<?= $reqTypeOfVessel ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqKasbon" class="control-label col-md-2">Advance Survey (Rp.)</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:90px" id="reqKasbonCur" name="reqKasbonCur" data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="<?= $reqKasbonCur ?>" />
                                    <input type="text" id="reqKasbon" class="easyui-validatebox textbox form-control" name="reqKasbon" value="<?= currencyToPage2($reqKasbon) ?>" style=" width:230px" onchange="numberWithCommas('reqKasbon')" onkeyup="numberWithCommas('reqKasbon')" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqTypeOfService" class="control-label col-md-2">Type of Service</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqTypeOfService" class="easyui-validatebox textbox form-control" name="reqTypeOfService" value="<?= $reqTypeOfService ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqOfferPrice" class="control-label col-md-2">Offer Price (Rp.)</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:90px" id="reqOfferCur" name="reqOfferCur" data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="<?= $reqOfferCur ?>" />
                                    <input type="text" id="reqOfferPrice" class="easyui-validatebox textbox form-control" name="reqOfferPrice" value="<?= currencyToPage2($reqOfferPrice) ?>" style=" width:230px" onchange="numberWithCommas('reqOfferPrice')" onkeyup="numberWithCommas('reqOfferPrice')" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDateService1" class="control-label col-md-2">Date Service Start</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDateService1" class="easyui-datebox textbox form-control" name="reqDateService1" value="<?= $reqDateService1 ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                        <label for="reqDateService2" class="control-label col-md-2">Date Service Finish</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDateService2" class="easyui-datebox textbox form-control" name="reqDateService2" value="<?= $reqDateService2 ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="reqDestination" class="control-label col-md-2">Location</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDestination" class="easyui-validatebox textbox form-control" name="reqDestination" value="<?= $reqDestination ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqRealPrice" class="control-label col-md-2">Real Price (Rp.)</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:90px" id="reqRealCur" name="reqRealCur" data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="<?= $reqRealCur ?>" />
                                    <input type="text" id="reqRealPrice" class="easyui-validatebox textbox form-control" name="reqRealPrice" value="<?= currencyToPage2($reqRealPrice) ?>" style=" width:230px" onchange="numberWithCommas('reqRealPrice')" onkeyup="numberWithCommas('reqRealPrice')" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="reqSurveyor" class="control-label col-md-2">Surveyor / Operator</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqSurveyor" class="easyui-validatebox textbox form-control" name="reqSurveyor" value="<?= $reqSurveyor ?>" style=" width:40%" /> / <input type="text" id="reqOperator" class="easyui-validatebox textbox form-control" name="reqOperator" value="<?= $reqOperator ?>" style=" width:40%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                         <label for="reqContactPerson" class="control-label col-md-2">Contact Person</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqContactPerson" class="easyui-validatebox textbox form-control" name="reqContactPerson" value="<?= $reqContactPerson ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Project Cost</h3>
                    </div>
                    <div class="form-group" >
                         <input type="hidden" name="reqCostProjectDetilId" id="reqCostProjectDetilId" class="hasChild">
                        <?
                        if(empty($reqHppProjectId)){
                        ?>
                        <label for="reqCostDate" id="costDate1" class="control-label col-md-2">Cost Date</label>
                        <div class="col-md-4" id="costDate2">
                            <div class="form-group">
                                <div class="col-md-11">
                                   
                                    <input type="text" id="reqCostDate" class="easyui-datebox textbox form-control hasChild" name="reqCostDate" value="" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <?
                        }
                        ?>
                        <label for="reqCost" class="control-label col-md-2">Cost </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:90px" id="reqCurrencys" name="reqCurrencys" data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="<?= $reqCurrency ?>" />
                                    <input type="text" id="reqCost" class="easyui-validatebox textbox form-control hasChild" name="reqCost" value="<?= $reqCost ?>" style=" width:230px" onchange="numberWithCommas('reqCost')" onkeyup="numberWithCommas('reqCost')" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <?
                    if(!empty($reqHppProjectId)){
                    ?>
                    <div class="form-group">
                        <label for="reqCode" class="control-label col-md-2">Code</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                       <div class="input-group" style="width: 100%">
                                    <input class="form-control " name="reqCode" id="reqCode" style="width: 100%;"><?= $reqCode; ?>
                                         <span class="input-group-addon" onclick="open_hpp_master()"><i> Ref ...</i></span>
    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?
                    }
                    ?>

                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Description</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control hasChild" name="reqDescription" id="reqDescription" style="width: 100%;"><?= $reqDescription; ?></textarea>
                                </div>
                            </div>
                        </div>
                       
                        <label for="reqStatus" class="control-label col-md-2" id="coststatus1" <?=$noDisplay?>>Status</label>
                        <div class="col-md-4" id="coststatus2" <?=$noDisplay?>>
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control hasChild" style="width:100%" id="reqStatus" name="reqStatus" data-options="width:'372',editable:false, valueField:'id',textField:'text',url:'combo_json/comboStatus'" value="<?= $ReqStatus ?>" />
                                </div>
                            </div>
                        </div>
                       
                    </div>

                    <div style="text-align:center;padding:5px">
                        <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />

                        <a href="javascript:void(0)" class="btn btn-warning" onclick="reseti()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                        <!-- <a href="javascript:void(0)" class="btn btn-primary" onclick="delete_datas(<?= $reqChild ?>)"><i class="fa fa-fw fa-trash"></i> Delete</a> -->

                    </div>
                    <div class="form-group">

                        <div>
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
                    <!--  <div id="bodys">
                    </div> -->
                </form>

            </div>
            <div class="page-header">
                <h3><i class="fa fa-file-text fa-lg"></i> Summary</h3>
            </div>
            <br>
            <table style="width: 100%" style="font-weight: bold;">
                <?
                if(!empty($reqHppProjectId)){
                ?>
                <td> Total HPP Cost (Rp.)</td>
                 <td> <input type="text" class="easyui-validatebox textbox form-control" disabled readonly id="reqTotalHpp"> </td>
                 <td>&nbsp;&nbsp;&nbsp;</td>
                <?
                }
                ?>
                
                <td> &nbsp;Project Cost (Rp.) </td>
                <td><input type="text" class="easyui-validatebox textbox form-control" disabled readonly id="reqPc"> </td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td> Overhead Project (Rp.) </td>
                <td><input type="text" class="easyui-validatebox textbox form-control" disabled readonly id="reqOp"> </td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td> Saldo (Rp.) </td>
                <td><input type="text" class="easyui-validatebox textbox form-control" disabled readonly id="reqSaldo"> </td>
                <td>
                    <p id="reqProfit"> (Profit : 82 % ) </p>
                </td>
            </table>

            <br>


        </div>

    </div>


    <script>
        function open_service_order() {
            // openAdd('app/loadUrl/app/template_load_service_order');
            openAdd('app/loadUrl/app/template_load_offering');
        }

        // function addOWR(id, kode,name,type,surveyor,project,company,location,contact,datestart,datefinish) {
        //     $("#reqNoProject").val(kode);
        //     $("#reqVesselName").val(name);
        //     $("#reqTypeOfVessel").val(type);
        //     $("#reqSurveyor").val(surveyor);
        //     $("#reqCompanyName").val(company);
        //     $("#reqAddService").val(project);
        //     $("#reqDestination").val(location);
        //     $("#reqContactPerson").val(contact);
        //     $('#reqDateService1').datebox('setValue', datestart);
        //     $('#reqDateService2').datebox('setValue', datefinish);
        //     $("#reqTypeOfService").val(project);

        //     // $("#reqServiceOrderId").val(id);
        // }

        function addOffering(id,kode,date,location,detail,cp,clvessel,nmvessel,tpvessel,company,companyid,tpservice,hppprojectid) {
            // console.log(id);
            
            $("#reqOfferId").val(id);
            $("#reqNoProject").val(kode);
            $("#reqVesselName").val(nmvessel);
            $("#reqTypeOfVessel").val(tpvessel);
            
              $("#reqClassOfVessel").val(clvessel);
            // $("#reqSurveyor").val(surveyor);
            $("#reqCompanyName").val(company);
            // $("#reqAddService").val(project);
            $("#reqDestination").val(location);
            $("#reqContactPerson").val(cp);
            $('#reqDateService1').datebox('setValue', date);
            $('#reqDateService2').datebox('setValue', date);
            $("#reqTypeOfService").val(tpservice);

            // console.log(hppprojectid);

            // $("#reqServiceOrderId").val(id);
        }

        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/project_cost_json/add2',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    // console.log(data);return false;
                    
                    var datas = data.split('-');
                    pc = datas[2];
                    reseti();
                    if (datas[1] == '') {
                        show_toast('info', 'Information', datas[0]);
                    } else {

                        $("#reqId").val(datas[1]);
                        reqIds = datas[1];
                        show_toast('info', 'Information', 'Header success added' + datas[0]);
                        $.messager.alertLink('Info', datas[0], 'info', "app/index/project_cost_add/?reqId=" + datas[1]);

                    }
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>

    <script type="text/javascript">
        function deleting(id) {
            var elements = oTable.fnGetData(id);
            var kata = '<b>Detail </b><br>' + elements[3] + '<br> At' + elements[4];

            $.get("web/cost_project_detil_json/delete?reqId=" + elements[0], function(data) {
                oTable.api().ajax.reload(null,false);
                show_toast('warning', 'Success delete row', kata);
            });
        }
        // btn open
        function editing(id) {
            var elements = oTable.fnGetData(id);
            // console.log(elements);
            $("#reqCostProjectDetilId").val(elements[0]);
            $('#reqCostDate').datebox('setValue', elements[8]);
            $("#reqDescription").val(elements[5]);
            $("#reqCost").val(elements[6]);
            $("#reqCode").val(elements[19]);
            $('#reqStatus').combobox('setValue', elements[9]);
            $('#reqCurrencys').combobox('setValue', elements[10]);
        }
    </script>

    <script>
        function open_hpp_master(){
            openAdd("app/loadUrl/app/template_add_hpp_master?reqModes=select");
        }
        function selected_one(data){
            // console.log('test data');
            $("#reqCode").val(data[1]);
            $("#reqDescription").val(data[2]);
        }
        $(document).ready(function() {
            console.log(total);
        });
        function reseti() {
            oTable.api().ajax.reload(null,false);
            $("#reqCostProjectDetilId").val('');
            $('#reqCostDate').datebox('setValue', '');
            $("#reqDescription").val('');
            $("#reqCost").val('');
            $("#reqCode").val('');
            $('#reqStatus').combobox('setValue', '');
            $('#reqCurrencys').combobox('setValue', '');
        }
    </script>
    <!-- //hitung summary project --> 
    <script type="text/javascript">
         $(function() {
            // $('#reqRealPrice').change(function() {
            //     var reqRealPrice=  document.getElementById('reqRealPrice').value;
            //    console.log(reqRealPrice);
            // });


            // $('input[name=reqRealPrice]').on('input',function(e){
            //     var total ='';
            //     var reqRealPrice='';
            //     var reqRealPrice=  document.getElementById('reqRealPrice').value;
            //     pc = reqRealPrice.split('.').join("");
            //     $("#reqPc").val(FormatCurrencyBaru(pc));
            //     reqOp =  $('#reqOp').val();
            //     if (reqOp != 0 || reqOp !== "" )
            //     {
            //        var reqCost=  document.getElementById('reqCost').value;
            //        total = reqCost.split('.').join("");
            //        var saldo = parseInt(pc) - parseInt(total);
            //        // console.log(pc);
            //        $("#reqOp").val(FormatCurrencyBaru(total));
            //        $("#reqSaldo").val(FormatCurrencyBaru(saldo));
            //        var profit = (saldo / pc) * 100;
            //        var formats_persen = round(profit, 2);
            //        if (Number.isNaN(formats_persen) || formats_persen == "" ) 
            //        {
            //          $("#reqProfit").html("( Profit: 0 % )");
            //        }
            //        else
            //        {
            //          $("#reqProfit").html("( Profit: " + formats_persen + " % )");
            //        }   
            //    }

            // });
            // $('input[name=reqCost]').on('input',function(e){
            //     reqPc =  $('#reqPc').val();
            //     if (reqPc != 0 )
            //     {
            //       pc = reqPc.split('.').join("");
            //       var reqCost=  document.getElementById('reqCost').value;
            //       total = reqCost.split('.').join("");
            //       var saldo = parseInt(pc) - parseInt(total);
            //       // console.log(pc);
            //       $("#reqOp").val(FormatCurrencyBaru(total));
            //       $("#reqSaldo").val(FormatCurrencyBaru(saldo));
            //       var profit = (saldo / pc) * 100;
            //       var formats_persen = round(profit, 2);
            //       if (Number.isNaN(formats_persen) || formats_persen == "" ) 
            //       {
            //         $("#reqProfit").html("( Profit: 0 % )");
            //       }
            //       else
            //       {
            //         $("#reqProfit").html("( Profit: " + formats_persen + " % )");
            //       }   
            //     }
            //     else
            //     {
            //       var reqCost=  document.getElementById('reqCost').value;
            //       total = reqCost.split('.').join("");
            //       // console.log(pc);
            //       $("#reqOp").val(FormatCurrencyBaru(total));

            //     }
              
            // });
        });
    </script>
</div>
</div>