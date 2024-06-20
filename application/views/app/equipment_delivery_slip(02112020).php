<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$aColumns = array("SO_EQUIP_ID", "SO_ID","KATEGORI", "EQUIP_ID", "PIC_PATH","OUT_CONDITION","IN_CONDITION","EQUIP_NAME",
            "EQUIP_ITEM", "EQUIP_SPEC","REMARK","QTY", "AKSI" );

$this->load->model("Service_order");
$service_order = new Service_order();

$reqId = $this->input->get("reqId");




if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $service_order->selectByParamsMonitoring(array("A.SO_ID" => $reqId));
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
    $reqNoDelivery   = $service_order->getField("NO_DELIVERY");

    
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />


<script type="text/javascript" language="javascript" class="init">
    var oTable;
    var reqIds;
    $(document).ready(function() {
        reqIds = $("#reqId").val();
        if (reqIds == '') {
            reqIds = '-1';
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
            "bSort": false,
            "bProcessing": true,
            "bServerSide": false,
            "bScrollInfinite": true,
            "sAjaxSource": "web/so_equip_json/json?reqId="+reqIds,
            "fnServerParams": function(aoData) {
                // aoData.push( { "name": "input1", "value": $("#input1").val() } );
                // aoData.push( { "name": "input2", "value": $("#input2").val() } );
                // aoData.push( { "name": "input3", "value": $("#input3").val() } );
            },
            columnDefs: [{
                className: 'never',
                targets: [0, 1,3]
            }],
            "bStateSave": true,
            "fnStateSave": function(oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function(oSettings) {
                var data = localStorage.getItem('DataTables_' + window.location.pathname);
                return JSON.parse(data);
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
                var total = api
                    .column(6)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $("#reqTotal").val(FormatCurrencyBaru(total));

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

        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            // document.location.href = "app/index/cash_report_add?reqId=" + anSelectedId;
            // alert(anSelectedData);

            $("#reqCashReportDetilId").val(elements[0]);
            $('#reqDetailTanggal').datebox('setValue', elements[1]);
            $("#reqKeterangan").val(elements[2]);
            $("#reqPelunasan").val(elements[3]);
            $("#reqNoRek").val(elements[4]);
            $("#reqDebet").val(elements[5]);
            $("#reqKredit").val(elements[6]);
            // $("#reqSaldo").val(elements[7]);
            // console.log(elements[7]);

            $('#btnProses').show();
            $('#btnProses').html('Update');

        });

        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;
            // deleteData("web/cash_report_json/delete", anSelectedId);
            del(anSelectedId);
        });

        $('#btnRefresh').on('click', function() {
            Refresh();
        });

        $('#btnProses').on('click', function() {
            submitForm();
        });

    });
</script>
<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/equipment_delivery"> Equipment  </a> &rsaquo; Delivery Slip
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

         <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right:  10px" onclick="cetakPdf()"><i class="fa fa-file-pdf-o "> </i><span> Pdf</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Data
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>
                        </h3>
                        <br>
                    </div>


                   
                    <div class="form-group">
                        <label for="reqTanggal" class="control-label col-md-2">No Delivery</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNoDelivery" class="easyui-validatebox form-control" name="reqNoDelivery" value="<?=$reqNoDelivery?>" style=" width:100%" placeholder="No Delivery"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="text-align:center;padding:5px">
                        <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />
                        <input type="hidden" value="<?=$reqId?>" name="reqIds" id="reqIds">

                        <a href="javascript:void(0)" class="btn btn-warning" onclick="reseti()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                        <!-- <a href="javascript:void(0)" class="btn btn-primary" onclick="delete_datas(<?= $reqChild ?>)"><i class="fa fa-fw fa-trash"></i> Delete</a> -->

                    </div>


                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Detail Equipment Delivery</h3>
                    </div>
                    <br>


                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Project Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqProjectName" class="easyui-validatebox textbox form-control" name="reqProjectName" value="<?= $reqProjectName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqTelephone" class="control-label col-md-2">Client</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCompanyName" class="easyui-validatebox textbox form-control" name="reqCompanyName" value="<?= $reqCompanyName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                   
                   <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">No  Owr</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNoOrder" class="easyui-validatebox textbox form-control" name="reqNoOrder" value="<?= $reqNoOrder ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqTelephone" class="control-label col-md-2">Vessel Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqVesselName" class="easyui-validatebox textbox form-control" name="reqVesselName" value="<?= $reqVesselName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Location</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDestination" class="easyui-validatebox textbox form-control" name="reqDestination" value="<?= $reqDestination ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        
                    </div>
                   
                    

           

           
                    <div class="form-group ">
                       
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
             

            </form>
        </div>


    </div>

</div>

<script>
    function submitForm() {
        $('#ff').form('submit', {
            url: 'web/equipment_delivery_slip_json/add',
            onSubmit: function() {
                return $(this).form('enableValidation').form('validate');
            },
            success: function(data) {
                //alert(data);
                // var datas = data.split('-');
                // if (datas[1] != '') {
                //     $('#reqId').val(datas[1]);
                //     reqIds = datas[1];
                //     show_toast('info', 'Information', 'Header success added <br>' + datas[0]);
                //     $.messager.alertLink('Info', datas[0], 'info', "app/index/cost_request_add/?reqId="+datas[1]);

                // } else {
                //     oTable.api().ajax.reload(null,false);
                //     show_toast('info', 'Information', datas[0]);

                // }
                // reload_detail();
                // reseti();
                 show_toast('info', 'Information', data);
            }
        });
    }

    // function clearForm() {
    //     $('#ff').form('clear');
    //     $('#reqTanggal').datebox('setValue', '<?= $reqTanggal ?>');  
    //     $('#reqId').val('<?= $reqId ?>');  

    // }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#btnProses').hide();
        // reload_detail();
    });
</script>

<script type="text/javascript">
    function cetakPdf(){
        openAdd("app/loadUrl/report/equpment_delevery_pdf?reqId=<?=$reqId?>");

    }
</script>

<script type="text/javascript">
 function deleting(id){
  var elements  = oTable.fnGetData(id);
  var kata =  '<b>Detail </b><br>'+elements[6];

  $.get("web/so_equip_json/delete_equipment?reqId="+elements[0], function (data) {
     oTable.api().ajax.reload(null,false);
     show_toast('warning','Success delete row',kata);      
 });
}
    function editing(id) {
        var elements = oTable.fnGetData(id);

        // $("#reqCostRequestDetailId").val(elements[0]);
        // $('#reqKeterangan2').val(elements[2]);
        // $("#reqCostCode").combobox('setValue', elements[3]);
        // $("#reqCostCodeCategori").val(elements[4]);
        // $("#reqEvidance").combobox('setValue', elements[5]);
        // $("#reqAmount").val(elements[6]);
        // $("#reqProject").val(elements[7]);
        // $("#reqPaidTo").val(elements[8]);

        openAdd("app/loadUrl/app/template_add_equipment?reqId=<?=$reqId?>");

    }

    function reseti() {
        oTable.api().ajax.reload(null,false);
        $("#reqCostRequestDetailId").val();
        $('#reqKeterangan2').val('');
        $("#reqCostCode").combobox('setValue', '');
        $("#reqCostCodeCategori").val('');
        $("#reqEvidance").combobox('setValue', '');
        $("#reqAmount").val('');
        $("#reqProject").val('');
        $("#reqPaidTo").val('');
      
    }
</script>


<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript">
    $('#reqCostCode').combobox({
        onSelect: function(param) {
            var text = param.text;
            var datas = text.split('-');
            $("#reqCostCodeCategori").val(datas[1]);
        }
    });
</script>
</div>
</div>