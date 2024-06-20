<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$aColumns =array("PROJECT_HPP_DETAIL_ID","HPP_PROJECT_ID","NO","CODE","DESCRIPTION","QTY","UNIT_RATE","DAYS","TOTALS","TOTAL","AKSI");

$this->load->model("ProjectHpp");
$project_hpp = new ProjectHpp();




$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
    $reqAgent=0;
    $reqCostFromAmdi=0;
    $reqCostToClient=0;
} else {
    $reqMode = "ubah";
    $project_hpp->selectByParamsMonitoring(array("CAST(A.HPP_PROJECT_ID AS VARCHAR)" => $reqId));
    $project_hpp->firstRow();

    $reqId = $project_hpp->getField("HPP_PROJECT_ID");
    $reqNama= $project_hpp->getField("NAMA");
    $reqLoa= $project_hpp->getField("LOA");
    $reqLocation= $project_hpp->getField("LOCATION");
    $reqRefNo= $project_hpp->getField("REF_NO");
    $reqBulanHpp= $project_hpp->getField("BULAN_HPP");
    $reqDateProject= $project_hpp->getField("DATE_PROJECT");

    $reqJenisPekerjaan= $project_hpp->getField("JENIS_PEKERJAAN");
    $reqOwner= $project_hpp->getField("OWNER");
    $reqJenisKapal= $project_hpp->getField("JENIS_KAPAL");
    $reqFlag= $project_hpp->getField("FLAG");
    $reqClass= $project_hpp->getField("CLASS");
    $reqEstimasiPekerjaan= $project_hpp->getField("ESTIMASI_PEKERJAAN");
    $reqLokasiPekerjaan= $project_hpp->getField("MASTER_LOKASI_ID");

    $reqCostFromAmdi= $project_hpp->getField("COST_FROM_AMDI");
     $reqCostFromAmdi =conver_number($reqCostFromAmdi);
    $reqAgent= $project_hpp->getField("AGENT");
    $reqAgent =conver_number($reqAgent);
    $reqCostToClient= $project_hpp->getField("COST_TO_CLIENT");
    $reqCostToClient =conver_number($reqCostToClient);
    $reqProfit= $project_hpp->getField("PROFIT");
    $reqPrescentage= $project_hpp->getField("PRESCENTAGE");

    $reqCompanyId   = $project_hpp->getField("COMPANY_ID");
    $reqVesselId    = $project_hpp->getField("VESSEL_ID");
    $reqForApproved = $project_hpp->getField("FOR_APPROVED");
    $reqStatusApproved  = $project_hpp->getField("STATUS_APPROVED");
    $reqSwl  = $project_hpp->getField("SWL");

}

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
            // "bSort": true,
            "bProcessing": true,
            "bServerSide": false,
            "bScrollInfinite": true,
            "sAjaxSource": "web/project_hpp_detil_json/json?reqId=" + reqIds,
            "fnServerParams": function(aoData) {
                // aoData.push( { "name": "input1", "value": $("#input1").val() } );
                // aoData.push( { "name": "input2", "value": $("#input2").val() } );
                // aoData.push( { "name": "input3", "value": $("#input3").val() } );
            },
            columnDefs: [{
                  "searchable": false,
            "orderable": false,
                className: 'never',
                targets: [0,1,8]
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
                    .column(8)
                    .data()
                    .reduce(function(a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);
                

                var reqCostFromAmdi = $("#reqCostFromAmdi").val();
                var reqAgent        = $("#reqAgent").val(); 
                if(reqAgent==''){
                    reqAgent=0;
                }
                if(reqCostFromAmdi==''){
                    reqCostFromAmdi=0;
                }
                 reqAgent =  parseInt(reqAgent.replaceAll(".",''));
                 reqCostFromAmdi =  parseInt(reqCostFromAmdi.replaceAll(".",'')); 
                 // var reqCostToClient = (reqAgent+reqCostFromAmdi)
                 var reqCostToClient = reqCostFromAmdi;
                 var total_agents = (reqCostToClient-total);
                // var saldo = pc - total;

                // var percents = (total_agents/reqCostToClient)*100;
                // var formats_persen = round(percents, 2);

                   var percents = (total_agents/total)*100;
                var formats_persen = round(percents, 2);
                
                // if (pc == '' || pc == 0) {
                //     var al = $('#reqRealPrice').val();
                //     pc = parseInt(al.replace('.', ''));
                // }
                // console.log(pc)

                // $("#reqPc").val(FormatCurrencyBaru(pc));
                // $("#reqOp").val(FormatCurrencyBaru(total));
                $("#reqSubTotal").val(FormatCurrencyBaru(total));
                 $("#reqSaldo").val(FormatCurrencyBaru(total_agents));

                // var profit = (saldo / pc) * 100;
                // var formats_persen = round(profit, 2);
                // // console.log(formats_persen);
                if (Number.isNaN(formats_persen) || formats_persen == "" ) 
                {
                    $("#reqProfit").html("( PRESCENTAGE: 0 % )");
                }
                else
                {
                    $("#reqProfit").html("( PRESCENTAGE: " + formats_persen + " % )");
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
            // window.open('app/loadUrl/app/excel_project_cost_detail?reqId=<?=$reqId?>');
             // openAdd('app/loadUrl/excel/cash_flow?reqId=<?=$reqId?>');
             openAdd('app/loadUrl/excel/project_hpp?reqId=<?=$reqId?>');
        });
        $('#btnPdf').on('click', function() {
            // window.open('app/loadUrl/app/excel_project_cost_detail?reqId=<?=$reqId?>');
             // openAdd('app/loadUrl/excel/cash_flow?reqId=<?=$reqId?>');
             openAdd('app/loadUrl/report/report_project_hpp_pdf?reqId=<?=$reqId?>');
        });
        

    });

</script>

<!-- <script type="text/javascript">
    $(document).ready(function() {
       oTable.on( 'order.dt search.dt', function () {
        oTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
   } );
</script> -->


<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/project_hpp">Project Hpp </a> &rsaquo; Form Hpp Project 
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <?
        if(!empty($reqId)){
        ?>
        <a id="btnPdf" class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" ><i class="fa fa fa-file-pdf-o  "> </i><span> Print PDF</span> </a>
         <a id="btnExcel" class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" ><i class="fa fa-file-excel-o "> </i><span> Download Excel</span> </a>
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
                        <h3><i class="fa fa-file-text fa-lg"></i> Project Hpp Information
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>
                        </h3>
                        <br>

                    </div>



             

                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">HPP Project No.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <!--  <input type="text" id="reqRefNo" placeholder="Ref No" class="easyui-combobox  form-control" name="reqRefNo" value="<?= $reqRefNo ?>" style=" width:200px"

                                     data-options="editable:true, valueField:'id',textField:'text',url:'combo_json/combo_offer', onSelect: function(param){
              ambil_offer(param.id);
        }"
                                      /> -->
                                       <input type="text" id="reqRefNo" placeholder="Ref No" class="easyui-textbox textbox form-control" name="reqRefNo" value="<?= $reqRefNo ?>" style=" width:100%" />
                                  
                                </div>
                            </div>
                        </div>
                        <label for="reqLocation" class="control-label col-md-2">SWL</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqSwl" placeholder="SWL"  class="easyui-textbox textbox form-control" name="reqSwl" value="<?= $reqSwl ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label for="reqLoa" class="control-label col-md-2">LOA</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLoa"  placeholder="Loa" class="easyui-textbox textbox form-control" name="reqLoa" value="<?= $reqLoa ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqLocation" class="control-label col-md-2">Location</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLocation" placeholder="Lokasi"  class="easyui-textbox textbox form-control" name="reqLocation" value="<?= $reqLocation ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDateProject" class="control-label col-md-2">Date HPP</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" required id="reqDateProject" placeholder="Date Hpp"  class="easyui-datebox textbox form-control" name="reqDateProject" value="<?= $reqDateProject ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                        <label for="reqDateService2" class="control-label col-md-2">Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input class="easyui-combobox form-control" placeholder="Date" style="width:190px" id="reqBulanHpp" name="reqBulanHpp" data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/ComboBulan'" value="<?= $reqBulanHpp ?>" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Project Relocation</h3>
                    </div>
                    <div class="form-group">
                        <label for="reqJenisPekerjaan" class="control-label col-md-2">Type of Service</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input type="text" id="reqJenisPekerjaan" placeholder="Jenis  Pekerjaan"  class="easyui-combobox textbox form-control" name="reqJenisPekerjaan" value="<?= $reqJenisPekerjaan ?>" style=" width:250px"
                                     data-options="editable:false, valueField:'id',textField:'text',url:'web/services_json/combo'"

                                      />
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="reqJenisPekerjaan" class="control-label col-md-2">Company of Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <div class="input-group">
                                        <span class="input-group-addon" onclick="openCompany()"><i class="fa fa-address-book-o fa-lg"></i> </span>
                                        <span class="input-group-addon" onclick="clearCompany()" ><i class="fa fa-times fa-lg"></i> </span>
                                        <input type="text" class="form-control"  id="reqCompanyName" name="reqOwner" value="<?= $reqOwner ?>" 
                                        style=" width:100%"
                                        >

                                    </div>

                                  
                                    <input type="hidden" class="easyui-validatebox textbox form-control" name="reqCompanyId" id="reqCompanyId" value="<?= $reqCompanyId ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                        <label for="reqOwner" class="control-label col-md-2">Name of Vessel</label>
                      

                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                <!--    <input type="text" id="reqNama" placeholder="Nama  Project" class="easyui-textbox textbox form-control" name="reqNama" value="<?= $reqNama ?>" style=" width:100%" /> -->


                                    <div class="input-group">
                                        <span class="input-group-addon" onclick="openVessel()"><i class="fa fa-address-book-o fa-lg"></i> </span>
                                        <span class="input-group-addon" onclick="clearVessel()" ><i class="fa fa-times fa-lg"></i> </span>
                                        <input type="text" class="form-control"  id="reqVesselName" name="reqNama" value="<?= $reqNama ?>" 
                                        style=" width:100%"
                                        >

                                    </div>
                                    <input type="hidden" id="reqVesselId" name="reqVesselId" value="<?= $reqVesselId ?>">
                                   

                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="reqJenisKapal" class="control-label col-md-2">Class of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input type="text" id="reqJenisKapal" placeholder="Jenis  Kapal"  class="easyui-combobox textbox form-control" name="reqJenisKapal" value="<?= $reqJenisKapal ?>" style=" width:250px"
                                      data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel'"

                                      />
                                </div>
                            </div>
                        </div>
                        <label for="reqClass" class="control-label col-md-2">Type of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <input type="text" id="reqClass" placeholder="Class"  class="easyui-combobox textbox form-control" name="reqClass" value="<?= $reqClass ?>" style=" width:250px"
                                       data-options="editable:false, valueField:'id',textField:'text',url:' combo_json/comboValueTypeOfVessel'"
                                       />

                                     
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="reqDateProject" class="control-label col-md-2">Estimated Work Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                       <input class="easyui-combobox form-control" placeholder="Estimasi Pekerjaan" style="width:190px" id="reqEstimasiPekerjaan" name="reqEstimasiPekerjaan" data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/ComboBulan'" value="<?= $reqEstimasiPekerjaan ?>" />
                                     <!-- <input type="text" id="reqEstimasiPekerjaan" placeholder="Estimasi Pekerjaan"  class="easyui-textbox textbox form-control" name="reqEstimasiPekerjaan" value="<?= $reqEstimasiPekerjaan ?>" style=" width:100%" /> -->
                                </div>
                            </div>
                        </div>
                        <label for="reqLokasiPekerjaan" class="control-label col-md-2">Location</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLokasiPekerjaan" placeholder=" Lokasi"  class="easyui-combobox textbox form-control" name="reqLokasiPekerjaan" value="<?= $reqLokasiPekerjaan ?>" style=" width:250px"
                                     data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/combo_lokasi'"

                                      />
                                    
                                      /
                                      <input type="text" id="reqFlag" placeholder="Negara / Flag"  class="easyui-textbox textbox form-control" name="reqFlag" value="<?= $reqFlag ?>" style=" width:28%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqJenisPekerjaan" class="control-label col-md-2">For Aproved </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                       <input type="text" id="reqForApproved " placeholder="For Aproved"  class="easyui-textbox textbox form-control" name="reqForApproved" value="<?= $reqForApproved ?>" style=" width:80%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Project Budget</h3>
                    </div>
                    <div class="form-group">
                        <label for="reqDateProject" class="control-label col-md-2">Sell Cost From Amdi</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input type="text" id="reqCostFromAmdi" placeholder="Sell Cost From Amdi"  class="easyui-textbox textbox form-control" name="reqCostFromAmdi" value="<?= $reqCostFromAmdi ?>" style=" width:100%"
                                      onchange="numberWithCommas('reqCostFromAmdi');test()" onkeyup="numberWithCommas('reqCostFromAmdi');test()"
                                      />
                                </div>
                            </div>
                        </div>
                        <label for="reqAgent" class="control-label col-md-2">Agent</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <input type="text" id="reqAgent" placeholder="Agent"  class="easyui-textbox textbox form-control" name="reqAgent" value="<?= $reqAgent ?>" style=" width:100%" 
                                      onchange="numberWithCommas('reqAgent');test()" onkeyup="numberWithCommas('reqAgent');test()"

                                      />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Sell Cost To Client</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input type="text" id="reqCostToClient" placeholder="Sell Cost To Client"  class="easyui-textbox textbox form-control" name="reqCostToClient" value="<?= $reqCostToClient ?>" style=" width:100%" readonly disabled />
                                </div>
                            </div>
                        </div>
                       
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Project Cost</h3>
                    </div>
                    <div class="form-group">
                        <label for="reqCostDate" class="control-label col-md-2">Cost </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" name="reqProjectHppDetailId" id="reqProjectHppDetailId" class="hasChild">
                                    <input type="text" id="reqCode" class="easyui-textbox textbox form-control hasChild" name="reqCode" value="" style=" width:100%" placeHolder="Cost Code" />

                                   <!--   <input class="easyui-combobox form-control " style="width:100%" id="reqCode" name="reqCode" data-options="width:'350',editable:true, valueField:'id',textField:'text',url:'combo_json/combo_cost_code'" value="" /> -->
                                </div>
                            </div>
                        </div>
                        <label for="reqQty" class="control-label col-md-2">Qty </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input type="text" id="reqQty" class="easyui-validatebox textbox form-control hasChild" name="reqQty" value="<?= $reqQty ?>" placeHolder="Qty" style=" width:50px" onkeypress='validate(event)' />

                                    <input type="text" id="reqUnitRate" class="easyui-validatebox textbox form-control hasChild" name="reqUnitRate" value="<?= $reqUnitRate ?>" style=" width:220px" onchange="numberWithCommas('reqUnitRate')" onkeyup="numberWithCommas('reqUnitRate')" placeHolder="Unit Rate" />
                                    /  <input type="text" id="reqDays" class="easyui-validatebox textbox form-control hasChild" name="reqDays" placeHolder="Days" value="<?= $reqDays ?>" style=" width:50px" onkeypress='validate(event)' /> Days
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Description</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control hasChild" name="reqDescription" id="reqDescription" style="width: 100%;" placeHolder="Description"><?= $reqDescription; ?></textarea>
                                </div>
                            </div>
                        </div>
                       
                    </div>

                    <div style="text-align:center;padding:5px">
                        <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />

                        <a href="javascript:void(0)" class="btn btn-warning" onclick="reseti()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                        <?
                        if(empty($reqStatusApproved) && $this->USERID=='6'){
                        ?>
                        <a href="javascript:void(0)" class="btn btn-danger" onclick="approval(<?= $reqId ?>)"><i class="fa fa-fw fa-paper-plane-o"></i> Approval</a>
                        <?
                        }
                        ?>
                         <?
                        if(!empty($reqStatusApproved) && $this->USERID=='6'){
                        ?>
                        <a href="javascript:void(0)" class="btn btn-success" onclick="cancel_approval(<?= $reqId ?>)"><i class="fa fa-fw fa-paper-plane-o"></i>Cancel Approval</a>
                        <?
                        }
                        ?>

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
                <td style="width: 50%"> &nbsp;&nbsp;&nbsp; </td>
                <td> Sub Total  </td>
                <td><input type="text" class="easyui-validatebox textbox form-control" disabled readonly id="reqSubTotal"> </td>
                <td> Profit  </td>
                <td><input type="text" class="easyui-validatebox textbox form-control" disabled readonly id="reqSaldo"> </td>
                <td>
                    <p id="reqProfit"> (PRESCENTAGE : 82 % ) </p>
                </td>
            </table>

            <br>


        </div>

    </div>


    <script>
         function company_pilihans(id, name, contact, reqAddress, reqEmail, reqTelephone, reqFaximile, reqHp) {
            $('#reqCompanyName').val(name);
            $('#reqCompanyId').val(id);
         }
       
        function openCompany() {
            openAdd('app/loadUrl/app/template_load_company_id_new');

        }
          function openVessel() {
            var companyId = $("#reqCompanyId").val();
            openAdd('app/loadUrl/app/template_load_vessel_id?reqCompanyId=' + companyId);
        }
         function company_vessel(id, name, type, clas, dimL, dimB, dimD) {
            $('#reqVesselId').val(id);
            $('#reqVesselName').val(name);
            $('#reqJenisKapal').combobox('setValue', clas);
            $('#reqClass').combobox('setValue', type);
            // $('#reqDimensionL').val(dimL);
            // $('#reqDimensionB').val(dimB);
            // $('#reqDimensionD').val(dimD);
        }
         function clearVessel(){
            $('#reqVesselId').val('');
            $('#reqVesselName').val('');
           
        }
        function clearCompany(){

            $('#reqCompanyName').val('');
            $('#reqCompanyId').val('');
            
        }
       

        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/project_hpp_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    // console.log(data);return false;
                    
                    var datas = data.split('-');
                     reseti();
                   

                       
                        reqIds = datas[1];
                        oTable.api().ajax.reload(null,false);


                        // show_toast('info', 'Information', 'Header success added' + datas[0]);
                        <?
                        if(empty($reqId)){
                        ?>
                         $.messager.alertLink('Info', datas[1], 'info', "app/index/project_hpp_add/?reqId=" + datas[0]);
                        <?    
                        }else{
                        ?>
                          show_toast('info', 'Information', datas[1]);
                            oTable.api().ajax.reload(null,false);
                        <?
                        }
                        ?>
                        //

                    
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>

    <script type="text/javascript">

        function approval(id){
            var delele_link='web/project_hpp_json/approval';
            $.messager.confirm('Konfirmasi', ' Yakin untuk aproved  project hpp ini ?<br>' , function(r) {
                if (r) {
                    var jqxhr = $.get(delele_link + '?reqId=' + id, function(data) {
                            
                            document.location.reload();
                        })
                        .done(function() {
                          
                            document.location.reload();
                        })
                        .fail(function() {
                           
                            alert("error");
                        });
                }
            });
            // $.get("web/project_hpp_json/approval?reqId=" + id, function(data) {
            //         window.location.reload();
            // });

        }
        function cancel_approval(id){
            var delele_link='web/project_hpp_json/cancel_approval';
            $.messager.confirm('Konfirmasi', ' Yakin untuk cancel aproved  project hpp ini ?<br>' , function(r) {
                if (r) {
                    var jqxhr = $.get(delele_link + '?reqId=' + id, function(data) {
                            
                            document.location.reload();
                        })
                        .done(function() {
                          
                            document.location.reload();
                        })
                        .fail(function() {
                           
                            alert("error");
                        });
                }
            });
            // $.get("web/project_hpp_json/approval?reqId=" + id, function(data) {
            //         window.location.reload();
            // });

        }
        function deleting(id) {
            var elements = oTable.fnGetData(id);
            var kata = '<b>Detail </b><br>' + elements[3] + '<br> At' + elements[4];

            $.get("web/project_hpp_detil_json/delete?reqId=" + elements[0], function(data) {
                oTable.api().ajax.reload(null,false);
                show_toast('warning', 'Success delete row', kata);
            });
        }
        // btn open
        function editing(id) {
            var elements = oTable.fnGetData(id);
            // console.log(elements);
            $("#reqProjectHppDetailId").val(elements[0]);
            $("#reqCode").val(elements[3]);
            $("#reqDescription").val(elements[4]);
            $("#reqQty").val(elements[5]);
            $('#reqUnitRate').val( elements[6]);
            $('#reqDays').val( elements[7]);
        }

        function test(){
           var  reqCostFromAmdi = $("#reqCostFromAmdi").val();
           var  reqAgent = $("#reqAgent").val();
           var val1='';
           var val2='';
           if(reqCostFromAmdi=='' ){
            reqCostFromAmdi=0;
           }
            if(reqAgent==''){
            reqAgent=0;
           }
           var val1 = reqCostFromAmdi.replaceAll('.','');
           var val2 = reqAgent.replaceAll('.','');
           var total = parseInt(val1)+parseInt(val2);

           // console.log(total);
           var values = formatRupiah(total.toString());
           $("#reqCostToClient").val(values);
            // console.log('Arik');
        }
    </script>
    <script type="text/javascript">
        function ambil_offer(id){
            // console.log(id);
            $.get("web/offer_json/ambil_detail?reqId="+id, function(data) {
                var obj = JSON.parse(data);
                $('#reqNama').val(obj.VESSEL_NAME);
                 $('#reqLocation').val(obj.DESTINATION);
                 $('#reqLokasiPekerjaan').val(obj.DESTINATION);
                 $('#reqOwner').val(obj.COMPANY_NAME);
                  $('#reqJenisPekerjaan').combobox('setValue',obj.GENERAL_SERVICE_NAME);
                  $('#reqClass').combobox('setValue',obj.TYPE_OF_VESSEL);
                   $('#reqJenisKapal').combobox('setValue',obj.CLASS_OF_VESSEL);
                  
                  
                 
                
                // window.location.reload();
            });

        }
    </script>

    <script>
        $(document).ready(function() {
            console.log(total);
        });
        function reseti() {
            oTable.api().ajax.reload(null,false);
            $("#reqProjectHppDetailId").val('');
             $("#reqCode").val('');
            $("#reqDescription").val('');
            $("#reqQty").val('');
            $("#reqUnitRate").val('');
            $("#reqDays").val('');
            
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