<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$aColumns = array("COST_REQUEST_DETAIL_ID", "COST_REQUEST_ID", "KETERANGAN", "COST_CODE", "COST_CODE_CATEGORI", "EVIDANCE", "AMOUNT", "PROJECT", "PAID_TO", "AKSI");

$this->load->model("CostRequest");
$cost_request = new CostRequest();

$reqId = $this->input->get("reqId");


$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $cost_request->selectByParamsMonitoring(array("A.COST_REQUEST_ID " => $reqId));
    $cost_request->firstRow();
    $reqId          = $cost_request->getField("COST_REQUEST_ID");
    $reqKode        = $cost_request->getField("KODE");
    $reqTanggal     = $cost_request->getField("TANGGAL");
    $reqTotal       = $cost_request->getField("TOTAL");
    $reqKeterangan  = $cost_request->getField("KETERANGAN");
    $reqPengambilan = $cost_request->getField("PENGAMBILAN");
    
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
            "sAjaxSource": "web/cost_request_detail_json/json?reqId="+reqIds,
            "fnServerParams": function(aoData) {
                // aoData.push( { "name": "input1", "value": $("#input1").val() } );
                // aoData.push( { "name": "input2", "value": $("#input2").val() } );
                // aoData.push( { "name": "input3", "value": $("#input3").val() } );
            },
            columnDefs: [{
                className: 'never',
                targets: [0, 1]
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

    <div class="judul-halaman"> <a href="app/index/cost_request"> Cost Request </a> &rsaquo; Form Cost Request
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
                        <label for="reqTanggal" class="control-label col-md-2">Tanggal</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input id="reqTanggal" class="easyui-datebox textbox form-control" name="reqTanggal" value="<?= $reqTanggal ?>" style=" width:100%" required />
                                </div>
                            </div>
                        </div>
                        <label for="reqTanggal" class="control-label col-md-2">Kode</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqKode" class="easyui-validatebox form-control" name="reqKode" value="<?= $reqKode ?>" style=" width:100%" placeholder="Auto Number" disabled readonly />
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="form-group">
                        <label for="reqTanggal" class="control-label col-md-2">Pengambilan</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPengambilan" class="easyui-validatebox form-control" name="reqPengambilan"  style=" width:100%" placeholder="Pengambilan BCA " onchange="numberWithCommas('reqPengambilan')" onkeyup="numberWithCommas('reqPengambilan')" value="<?=currencyToPage2($reqPengambilan)?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqTanggal" class="control-label col-md-2">Description</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea rows="5" cols="3" name="reqKeterangan" id="reqKeterangan" class="easyui-validatebox form-control"><?= $reqKeterangan ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqTanggal" class="control-label col-md-2">Total</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqTotal" class="easyui-validatebox form-control" name="reqTotal" value="" style=" width:100%" placeholder="Auto Total " onchange="numberWithCommas('reqTotal')" onkeyup="numberWithCommas('reqTotal')" disabled="" readonly="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Cost Request Detail</h3>
                    </div>
                    <br>


                    <div class="form-group">
                        <label for="reqDeskripsi" class="control-label col-md-2">Desciption </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" id="reqKeterangan2" rows="3" cols="3" class="form-control" name="reqKeterangan2" value="" style=" width:100%"></textarea>
                                </div>
                            </div>
                        </div>
                        <label for="reqDeskripsi" class="control-label col-md-2">Cost Code </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" id="reqCostRequestDetailId" name="reqCostRequestDetailId" />
                                    <input class="easyui-combobox form-control " style="width:100%" id="reqCostCode" name="reqCostCode" data-options="width:'372',editable:true, valueField:'id',textField:'text',url:'combo_json/combo_cost_code'" value="" />
                                </div>
                            </div>
                        </div>
                        <label for="reqDeskripsi" class="control-label col-md-2">Cost Code Categori </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCostCodeCategori" class="easyui-validatebox form-control" name="reqCostCodeCategori" value="" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqPelunasan" class="control-label col-md-2"> Evidance </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">

                                    <input class="easyui-combobox form-control " style="width:100%" id="reqEvidance" name="reqEvidance" data-options="width:'372',editable:true, valueField:'id',textField:'text',url:'combo_json/comboEvidance'" value="" />
                                </div>
                            </div>
                        </div>
                        <label for="reqDeskripsi" class="control-label col-md-2"> Amount </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">

                                    <input type="text" id="reqAmount" class="easyui-validatebox form-control" name="reqAmount" value="" style=" width:100%" onchange="numberWithCommas('reqAmount')" onkeyup="numberWithCommas('reqAmount')" />

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqPelunasan" class="control-label col-md-2"> Project </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqProject" class="easyui-validatebox form-control" name="reqProject" value="" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqDeskripsi" class="control-label col-md-2"> Paid To </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">

                                    <input type="text" id="reqPaidTo" class="easyui-validatebox form-control" name="reqPaidTo" value="" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  <div class="form-group">
                        <div class="col-md-12">
                           <div class="form-group pull-right">
                               <div class="col-md-12">
                                <button type="button" class="btn btn-default" id="btnAdd"> New </button>
                                <button type="button" class="btn btn-default" id="btnProses"> New </button>
                                <button type="button" class="btn btn-default" id="btnEdit"> Edit </button>
                                <button type="button" class="btn btn-default" id="btnDelete"> Delete </button>
                            </div>
                           </div>   
                    </div> -->

                    <div style="text-align:center;padding:5px">
                        <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />

                        <a href="javascript:void(0)" class="btn btn-warning" onclick="reseti()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                        <!-- <a href="javascript:void(0)" class="btn btn-primary" onclick="delete_datas(<?= $reqChild ?>)"><i class="fa fa-fw fa-trash"></i> Delete</a> -->

                    </div>

            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <div class="form-group ">
                        <div class="col-md-12">
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

            </form>
        </div>


    </div>

</div>

<script>
    function submitForm() {
        $('#ff').form('submit', {
            url: 'web/cost_request_detail_json/add',
            onSubmit: function() {
                return $(this).form('enableValidation').form('validate');
            },
            success: function(data) {
                //alert(data);
                var datas = data.split('-');
                if (datas[1] != '') {
                    $('#reqId').val(datas[1]);
                    reqIds = datas[1];
                    show_toast('info', 'Information', 'Header success added <br>' + datas[0]);
                    $.messager.alertLink('Info', datas[0], 'info', "app/index/cost_request_add/?reqId="+datas[1]);

                } else {
                    oTable.api().ajax.reload(null,false);
                    show_toast('info', 'Information', datas[0]);

                }
                reload_detail();
                reseti();
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
        openAdd("app/loadUrl/report/cost_request_pdf?reqId=<?=$reqId?>");

    }
</script>

<script type="text/javascript">
    function deleting(id) {
        var elements = oTable.fnGetData(id);
        var kata = '<b>Detail </b><br>' + elements[2] + '<br> At' + elements[3];
        $.get("web/cost_request_detail_json/delete?reqId=" + elements[0], function(data) {
            oTable.api().ajax.reload(null,false);

            reload_detail();
            show_toast('warning', 'Success delete row', kata);



        });
    }

    function editing(id) {
        var elements = oTable.fnGetData(id);

        $("#reqCostRequestDetailId").val(elements[0]);
        $('#reqKeterangan2').val(elements[2]);
        $("#reqCostCode").combobox('setValue', elements[3]);
        $("#reqCostCodeCategori").val(elements[4]);
        $("#reqEvidance").combobox('setValue', elements[5]);
        $("#reqAmount").val(elements[6]);
        $("#reqProject").val(elements[7]);
        $("#reqPaidTo").val(elements[8]);

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
        reload_detail();
    }
</script>

<script type="text/javascript">
    function reload_detail() {

        $.get("web/cost_request_detail_json/ambil_kode?reqId=<?= $reqId ?>", function(data) {

            $("#reqKode").val(data);

        });

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