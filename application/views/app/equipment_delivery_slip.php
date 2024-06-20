<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$aColumns = array("SO_EQUIP_ID", "ID","CATEGORY","EQUIPMENT_NAME","SN","QTY","ITEM","OUT_CONDITION","IN_CONDITION","REMARK", "EDIT" );

$this->load->model("Service_order");
$service_order = new Service_order();

$reqId = $this->input->get("reqId");

$this->load->model("SoEquip");
$so_equip = new SoEquip();
$so_equip->delete_from_flag();

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $service_order->selectByParamsMonitoring(array("A.SO_ID" => $reqId));
    // echo $service_order->query;exit;
    $service_order->firstRow();
   
    $reqSoId            = $service_order->getField("SO_ID");
    $reqProjectName     = $service_order->getField("PROJECT_NAME");
    $reqNoDelivery      = $service_order->getField("NO_ORDER");
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
    $reqNoDelivery      = $service_order->getField("NO_DELIVERY");
    $reqPath      = $service_order->getField("PATH");

    
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
    var anSelectedData = '';
    var anSelectedId = '';
    var anSelectedDownload = '';
    var anSelectedPosition = '';
    var anIndex = '';
    $(document).ready(function() {
        reqIds = $("#reqId").val();
        if (reqIds == '') {
            reqIds = '-1';
        }
        oTable = $('#example').dataTable({
            bJQueryUI: true,
            "iDisplayLength": 0,
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
            "bPaginate": false,
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
                targets: [0]
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
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
              
                if (aData[11] == "3") 
                {
                   // $('td', nRow).addClass('greenClass');
                } 
                // if (aData[6] == "Lunas") 
                // {
                //     $('td', nRow).addClass('greenClass');
                // }      
                if (aData[11] == "0") 
                {
                    $('td', nRow).addClass('yellowClass');
                }

                 if (aData[8] == "1") 
                {
                    // $('td', nRow).addClass('redClass');
                }
        },
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
            anIndex = anSelected[0];
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

        $("#reqKodeBarcode").on("keyup change", function(e) {
            var value = $("#reqKodeBarcode").val();
            $.post( "web/so_equip_json/add_from_barcode", { reqParam: value, reqId: "<?=$reqId?>" })
            .done(function( data ) {
                if(data==2){
                    show_toast('error', 'Information', 'Barcode item sudah ada');
                }else 
                if(data==3){
                    show_toast('error', 'Information', 'Barang Sudah Keluar');
                }
               oTable.api().ajax.reload(null,false);
               if(data==1){
                $("#reqKodeBarcode").val("");
               }
            });
        });

    });
</script>
<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/equipment_delivery"> Equipment Project List  </a> &rsaquo; Delivery Slip
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

         <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right:  10px" onclick="cetakPdf()"><i class="fa fa-file-pdf-o "> </i><span> Print</span> </a>
         <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="template_equipment_project()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Template Equipment Delivery </span> </a>
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
                        <label for="reqTanggal" class="control-label col-md-2">Delivery No.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <input type="text" id="reqNoDelivery" class="easyui-validatebox form-control" name="reqNoDelivery" value="<?=$reqNoDelivery?>" style=" width:100%" placeholder="No Delivery"/>
                                        <span class="input-group-addon" onclick="pilih_project()"><i>...</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="form-group">
                        <label for="reqRemarks" class="control-label col-md-2">Remarks</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqRemarks" class="easyui-validatebox textbox form-control" name="reqRemarks" value="<?= $reqRemarks ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div> -->
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
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Project of Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqProjectName" class="easyui-validatebox textbox form-control" name="reqProjectName" value="<?= $reqProjectName ?>" style=" width:100%" readonly/>
                                </div>
                            </div>
                        </div>
                        <label for="reqTelephone" class="control-label col-md-2">Company of Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCompanyName" class="easyui-validatebox textbox form-control" name="reqCompanyName" value="<?= $reqCompanyName ?>" style=" width:100%" readonly />
                                </div>
                            </div>
                        </div>
                    </div>
                   
                   <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Type Of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqVesselType" class="easyui-validatebox textbox form-control" name="reqVesselType" value="<?= $reqVesselType ?>" style=" width:100%" readonly />
                                </div>
                            </div>
                        </div>
                      <!--    <label for="reqInvoiceNumber" class="control-label col-md-2">No OWR</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNoOrder" class="easyui-validatebox textbox form-control" name="reqNoOrder" value="<?= $reqNoOrder ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div> -->
                        <label for="reqTelephone" class="control-label col-md-2">Name of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqVesselName" class="easyui-validatebox textbox form-control" name="reqVesselName" value="<?= $reqVesselName ?>" style=" width:100%" readonly />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Location</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDestination" class="easyui-validatebox textbox form-control" name="reqDestination" value="<?= $reqDestination ?>" style=" width:100%"  readonly/>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                     <br>

                     <?
                     if(!empty($reqId)){
                     ?>
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
                                              <a href="uploads/equipment_delivery_slip/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                              <a onclick="openAdd(`uploads/equipment_delivery_slip/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
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

                    <br>
                     <br>
<!-- 
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Out Checked by</h3>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="reqOutWarehouseName" class="control-label col-md-2">Warehouse Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqOutWarehouseName" class="easyui-validatebox textbox form-control" name="reqOutWarehouseName" value="<?= $reqOutWarehouseName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqOutWarehouseDate" class="control-label col-md-2">Warehouse Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox  form-control" name="reqOutWarehouseDate" id="reqOutWarehouseDate" value="<?= $reqOutWarehouseDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqOutDivingSuptName" class="control-label col-md-2">Diving Supt Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqOutDivingSuptName" class="easyui-validatebox textbox form-control" name="reqOutDivingSuptName" value="<?= $reqOutDivingSuptName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqOutDivingSuptDate" class="control-label col-md-2">Diving Supt Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox  form-control" name="reqOutDivingSuptDate" id="reqOutDivingSuptDate" value="<?= $reqOutDivingSuptDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqOutHSESpvName" class="control-label col-md-2">HSE Spv Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqOutHSESpvName" class="easyui-validatebox textbox form-control" name="reqOutHSESpvName" value="<?= $reqOutHSESpvName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqOutHSESpvDate" class="control-label col-md-2">HSE Spv Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox  form-control" name="reqOutHSESpvDate" id="reqOutHSESpvDate" value="<?= $reqOutHSESpvDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqReceivedBy" class="control-label col-md-2">Received By</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqReceivedBy" class="easyui-validatebox textbox form-control" name="reqReceivedBy" value="<?= $reqReceivedBy ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqReceivedDate" class="control-label col-md-2">Received Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox  form-control" name="reqReceivedDate" id="reqReceivedDate" value="<?= $reqReceivedDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqReceivedNote" class="control-label col-md-2">Received Note</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqReceivedNote" class="easyui-validatebox textbox form-control" name="reqReceivedNote" value="<?= $reqReceivedNote ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> In Checked by</h3>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="reqInWarehouseName" class="control-label col-md-2">Warehouse Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInWarehouseName" class="easyui-validatebox textbox form-control" name="reqInWarehouseName" value="<?= $reqInWarehouseName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqInWarehouseDate" class="control-label col-md-2">Warehouse Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox  form-control" name="reqInWarehouseDate" id="reqInWarehouseDate" value="<?= $reqInWarehouseDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqInDivingSuptName" class="control-label col-md-2">Diving Supt Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInDivingSuptName" class="easyui-validatebox textbox form-control" name="reqInDivingSuptName" value="<?= $reqInDivingSuptName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqInDivingSuptDate" class="control-label col-md-2">Diving Supt Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox  form-control" name="reqInDivingSuptDate" id="reqInDivingSuptDate" value="<?= $reqInDivingSuptDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqInHSESpvName" class="control-label col-md-2">HSE Spv Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInHSESpvName" class="easyui-validatebox textbox form-control" name="reqInHSESpvName" value="<?= $reqInHSESpvName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqInHSESpvDate" class="control-label col-md-2">HSE Spv Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox  form-control" name="reqInHSESpvDate" id="reqInHSESpvDate" value="<?= $reqInHSESpvDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqReturnedBy" class="control-label col-md-2">Returned By</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqReturnedBy" class="easyui-validatebox textbox form-control" name="reqReturnedBy" value="<?= $reqReturnedBy ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqReturnedDate" class="control-label col-md-2">Returned Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox  form-control" name="reqReturnedDate" id="reqReturnedDate" value="<?= $reqReturnedDate ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqReturnedNote" class="control-label col-md-2">Returned Note</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqReturnedNote" class="easyui-validatebox textbox form-control" name="reqReturnedNote" value="<?= $reqReturnedNote ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
 -->
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Equipment List</h3>
                    </div>
                    <div style="padding: 10px">
                        <div class="form-group ">
                            <div class="col-md-12">
                             
                            &nbsp;
                           
                            <button type="button" onclick="addEquipment()" class="btn btn-primary">Add Equipment</button>
                           
                            &nbsp;&nbsp;
                             
                            <button type="button" onclick="loadEquipment()" class="btn btn-primary">Load Equipment</button>
                          
                                 &nbsp;&nbsp;
                            <input type="text"  class="easyui-validatebox textbox form-control" name="reqKodeBarcode" id="reqKodeBarcode" value="" style=" width:30%" placeholder="Scan Barcode di sini" />
                             
                            
                            <button class="btn btn-default" type="button" onclick="check()"> Check All </button>
                            <button class="btn btn-default" type="button" onclick="uncheck()"> Uncheck All </button>
                             <button class="btn btn-danger" type="button" onclick="deletedSelected()"> Delete  </button>
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
                    <?
                    }
                    ?>
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
                // console.log(data);return false;
                var datas = data.split('-');
                // if (datas[1] != '') {
                //     $('#reqId').val(datas[1]);
                //     reqIds = datas[1];

                //     show_toast('info', 'Information', 'Header success added <br>' + datas[0]);
                // <?
                // if(empty($reqId)){
                // ?>
                //     $.messager.alertLink('Info', datas[0], 'info', "app/index/equipment_delivery_slip/?reqId="+datas[0]);
                //  <?
                //  }   
                //  ?>   
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

    function addEquipment() {
        if('<?=$reqId?>' == ''){
            $.messager.confirm('Confirm','Equipment Project List will be saved before adding equipment?',function(r){
                if (r){
                    submitForm();
                }
            });
        } else {
            openAdd('app/loadUrl/app/template_add_equipment?reqId=<?=$reqId?>');
        }
    }
    function loadEquipment() {
        openAdd('app/loadUrl/app/template_load_template_equipment?reqId=<?=$reqId?>');
    }
    function open_service_order() {
        openAdd('app/loadUrl/app/template_load_service_order');
    }
    function template_equipment_project(){
         openAdd('app/loadUrl/app/tempalate_master_so_template');
    }
    function addOWR(id, kode,name,type,surveyor,project,company,location) {
        $("#reqNoDelivery").val(kode);
        $("#reqVesselName").val(name);
        $("#reqVesselType").val(type);
        $("#reqProjectName").val(project);
        $("#reqCompanyName").val(company);
        $("#reqDestination").val(location);
        
        // $("#reqSurveyor").val(surveyor);
            // $("#reqServiceOrderId").val(id);
    }
    function pilih_project() {
        openAdd('app/loadUrl/app/template_load_offering');
    }
    function addOffering(id, kode,date,location,detail,cp,clvessel,nmvessel,tpvessel,company) {
        $("#reqNoDelivery").val(kode);
        $("#reqVesselName").val(nmvessel);
        $("#reqVesselType").val(tpvessel);
        $("#reqProjectName").val(detail);
        $("#reqCompanyName").val(company);
        $("#reqDestination").val(location);
    }
</script>

<script type="text/javascript">
    function deleting(id){
        deleteData_for_table('web/so_equip_json/delete_equipment', id, anIndex, 3);
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

        openAdd("app/loadUrl/app/template_add_equipment?reqId=<?=$reqId?>&reqSoEquipId="+id);

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

    function reload(id) {
        oTable.api().ajax.reload(null,false);
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
     function tambahPenyebab(filename='') {
            var id = $('#tambahAttacment tr').length+1;
            $.get("app/loadUrl/app/tempalate_row_attacment?id="+id+"&filename="+filename, function(data) {
                $("#tambahAttacment").append(data);
            });
        }

          function check() {
        $('.reqCheckboxDele').each(function() {
            this.checked = true;
        });
    }


    function uncheck() {
        $('.reqCheckboxDele').each(function() {
            this.checked = false;
        });
    }

    function deletedSelected(){
        var kode = '';
        var params = oTable.$('input').serializeArray();
        $.each(params, function(i, field) {
            kode = kode + field.value + ',';
        });

        if (kode == '') {
            return;
        } else {
            $.messager.confirm('Konfirmasi','Yakin menghapus data terpilih ?',function(r){
            if (r){

                    $.get("web/so_equip_json/deleteALlItemSo?reqId="+kode, function(data) {
                       show_toast('info', 'Information', data);
                        oTable.api().ajax.reload(null,false);
                    });

                    }
            });                 

        }
    }
</script>
</div>
</div>