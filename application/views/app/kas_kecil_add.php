<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$aColumns = array("KAS_KECIL_DETAIL_ID", "NO", "TANGGAL", "KETERANGAN", "KATEGORI", "KREDIT ( RP. )", "DEBET ( RP. )", "BALENCE ( RP. )", "AKSI");
$this->load->model("KasKecil");
$kas_kecil = new KasKecil();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $kas_kecil->selectByParams(array("A.KAS_KECIL_ID" => $reqId));
    // echo $kas_kecil->query;exit;
    $kas_kecil->firstRow();

    $reqTanggal = $kas_kecil->getField("TANGGAL");
    $reqDeskripsi = $kas_kecil->getField("DESKRIPSI");
    $is_approve = $kas_kecil->getApproval(array("A.KAS_KECIL_ID" => $reqId));
}

$approval = 0;
if($this->FULLNAME == "Yunus Nafik" && $is_approve == "0")
    $approval = 1;

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
            "sAjaxSource": "web/kas_kecil_detail_json/json?reqId="+reqIds,
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
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;
                var getFloatVal = function(i) {
                    if(typeof i === 'string') {
                        i = i.replaceAll('.', '');
                        i = i.replaceAll(',', '.');
                    } else {
                        i = typeof i === 'number' ? i : 0;
                    }
                    return parseFloat(i);
                };
                
                var totalKreditIDR = api.column(5).data().reduce(function(a, b) {
                    return getFloatVal(a) + getFloatVal(b);
                }, 0);

                var totalDebetIDR = api.column(6).data().reduce(function(a, b) {
                    return getFloatVal(a) + getFloatVal(b);
                }, 0);

                var totalBalanceIDR = totalKreditIDR - totalDebetIDR;

                var totalKredit = totalKreditIDR;
                var totalDebet = totalDebetIDR;
                var totalBalance = totalBalanceIDR;

                $("#reqTotalKredit").val(FormatCurrencyBaruNew(totalKredit));
                $("#reqTotalDebet").val(FormatCurrencyBaruNew(totalDebet));
                $("#reqTotalBalance").val(FormatCurrencyBaruNew(totalBalance));

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

            $("#reqKasKecilDetailId").val(elements[0]);
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

    <div class="judul-halaman"> <a href="app/index/kas_kecil"> Kas Kecil</a> &rsaquo; Form Kas Kecil
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right:  10px" onclick="import_excel()"><i class="fa fa-file-excel-o "> </i><span> Import Detail </span> </a>


        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right:  10px" onclick="export_excel()"><i class="fa fa-file-excel-o "> </i><span> Export Excel </span> </a>

        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right:  10px" onclick="preview_pdf()"><i class="fa fa-file-pdf-o "> </i><span> Preview </span> </a>
        <?php
        if($approval == "1")
        {
        ?>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right:  10px" onclick="approve()"><i class="fa fa-check-circle-o "> </i><span> Approve </span> </a>
        <?php
        }
        else
        {
        ?>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right:  10px" onclick="batal_approve()"><i class="fa fa-times-circle-o  "> </i><span> Cancel Approve </span> </a>
        <?php  
        }
        ?>
        <!-- <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_kategori()"><i class="fa fa-fw fa-gavel fa lg"> </i><span> Master Kategori</span> </a> -->


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
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input id="reqTanggal" class="easyui-datebox textbox form-control" name="reqTanggal" value="<?= $reqTanggal ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Cash Report Detail</h3>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="reqDeskripsi" class="control-label col-md-2">Date </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" name="reqKasKecilDetailId" id="reqKasKecilDetailId">
                                    <input  id="reqDetailTanggal" class="easyui-datebox textbox form-control" name="reqDetailTanggal" value="" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="reqDeskripsi" class="control-label col-md-2">Description </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" id="reqKeterangan" rows="3" cols="3" class="form-control" name="reqKeterangan" value="" style=" width:100%"></textarea>
                                </div>
                            </div>
                        </div>
                        <label for="reqDeskripsi" class="control-label col-md-2">Credit  </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqKredit" class="easyui-validatebox form-control" name="reqKredit" value="" style="width:70%" onchange="numberWithCommas('reqKredit<?= $ids_id ?>')" onkeyup="numberWithCommas('reqKredit<?= $ids_id ?>')" />
                                    <input type="hidden" id="reqRealCurKredit" name="reqRealCurKredit" value="IDR" />
                                </div>
                            </div>
                        </div>
                        <label for="reqDeskripsi" class="control-label col-md-2">Debit  </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDebet"  class="easyui-validatebox form-control" name="reqDebet" value="" style="width:70%" onchange="numberWithCommas('reqDebet<?= $ids_id ?>')" onkeyup="numberWithCommas('reqDebet<?= $ids_id ?>')" />
                                    <input type="hidden" id="reqRealCurDebit" name="reqRealCurDebit"  value="IDR" />
                                </div>
                            </div>
                        </div>
                        <label for="reqDeskripsi" class="control-label col-md-2">Kategori </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" id="reqKategori" rows="3" cols="3" class="form-control" name="reqKategori" value="" style=" width:100%"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        
                        <div style="text-align:center;padding:5px">
                            <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />

                            <a href="javascript:void(0)" class="btn btn-warning" onclick="reseti()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                            <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                           

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
            <div class="page-header">
                <h3><i class="fa fa-file-text fa-lg"></i> Summary</h3>
            </div>
            <br>
            <table style="width: 100%" style="font-weight: bold;">
                <td width="10%" style="text-align: right; padding-right: 1%">Total Kredit </td>
                <td><input type="text" class="easyui-validatebox textbox form-control" disabled readonly id="reqTotalKredit"> </td>
                <td width="10%" style="text-align: right; padding-right: 1%">Total Debet </td>
                <td><input type="text" class="easyui-validatebox textbox form-control" disabled readonly id="reqTotalDebet"> </td>
                <td width="10%" style="text-align: right; padding-right: 1%">Total Balance </td>
                <td><input type="text" class="easyui-validatebox textbox form-control" disabled readonly id="reqTotalBalance"> </td>
            </table>

            <br>

        </div>

    </div>

    <script>
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/kas_kecil_detail_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    // console.log(data);return false;
                    var datas = data.split('-');
                    // console.log(datas);return false;
                    if (datas[1] != '') {
                        $('#reqId').val(datas[1]);
                        reqIds = datas[1];
                        // console.log(reqIds);return false;
                        show_toast('info', 'Information', 'Header success added'+datas[1]);
                          $.messager.alertLink('Info', datas[0], 'info', "app/index/kas_kecil_add/?reqId="+datas[1]);

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
            reload_detail();
        });
    </script>


    <script type="text/javascript">
        function export_excel(){
            openAdd('app/loadUrl/excel/cash_flow?reqId=<?=$reqId?>');
        }
        function import_excel(){
                openAdd('app/loadUrl/import/import_kas?reqId=<?=$reqId?>');
        }
        function preview_pdf(){
                openAdd('app/loadUrl/report/cetak_document_kas_kecil_pdf?reqId=<?=$reqId?>');
        }
        function master_kategori(){
            openAdd('app/loadUrl/app/tempalate_master_kategori?');
        }
        function deleting(id) {
            var elements = oTable.fnGetData(id);
            var kata = '<b>Detail </b><br>' + elements[2] + '<br> At' + elements[3];
            $.get("web/kas_kecil_detail_json/delete?reqId=" + elements[0], function(data) {
                oTable.api().ajax.reload(null,false);

                reload_detail();
                show_toast('warning', 'Success delete row', kata);


            });
        }

        function approve(){
            $.get("web/kas_kecil_json/approve?reqId=<?=$reqId?>", function(data) {
                var datas = data.split('-');
                show_toast('info', 'Information', 'Header success added'+datas[1]);
                $.messager.alertLink('Info', datas[0], 'info', "app/index/kas_kecil_add/?reqId="+datas[1]);
            });
        }

        function batal_approve(){
            $.get("web/kas_kecil_json/approve_cancel?reqId=<?=$reqId?>", function(data) {
                var datas = data.split('-');
                show_toast('info', 'Information', 'Header success added'+datas[1]);
                $.messager.alertLink('Info', datas[0], 'info', "app/index/kas_kecil_add/?reqId="+datas[1]);
            });
        }

        function editing(id) {
            var elements = oTable.fnGetData(id);
            $("#reqKasKecilDetailId").val(elements[0]);
            $('#reqDetailTanggal').datebox('setValue', elements[2]);

            $("#reqKeterangan").val(elements[3]);
            $("#reqKategori").val(elements[4]);
           $("#reqDebet").val(elements[6]);
            $("#reqKredit").val(elements[5]);

        }

        function reseti() {
            oTable.api().ajax.reload(null,false);
            $("#reqKasKecilDetailId").val('');
            $('#reqDetailTanggal').datebox('setValue', '');
            $("#reqKeterangan").val('');
            $("#reqPelunasan").val('');
            $("#reqNoRek").val('');
            $("#reqDebet").val('');
            $("#reqKredit").val('');
            $('#reqKategori').val('');
            $('#reqRealCurDebit').val('IDR');
            $('#reqRealCurKredit').val('IDR');
            reload_detail();
        }
    </script>
    <script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript">
        //  $('#reqRealCurDebit').combobox({
        //     onSelect: function(param) {
        //         // console.log(param.id);
        //            $('#reqRealCurKredit').combobox('setValue', param.id);
        //         // nominal_kekata();
        //     }
        // });
        //   $('#reqRealCurKredit').combobox({
        //     onSelect: function(param) {
        //         // console.log(param.id);
        //          $('#reqRealCurDebit').combobox('setValue', param.id);
        //     }
        // });
    </script>

    <script type="text/javascript">
        function reload_detail() {
           

            $.get("web/kas_kecil_detail_json/getSaldoAkhir?reqId=<?= $reqId ?>" , function(data) {

                $("#reqSaldo").html(data);

            });

             $.get("web/kas_kecil_detail_json/getSaldoAkhirUSD?reqId=<?= $reqId ?>" , function(data) {

                $("#reqSaldoUSD").html(data);

            });

        }
    </script>
        <script type="text/javascript">
        function FormatCurrencyBaruNew(num) 
{
    num = num.toString().replace(/\$|\,/g,'');
    if(isNaN(num))
        num = "0";
        
    sign = (num == (num = Math.abs(num)));
    
    num_str = num.toString();
    cents = 0;
    
    if(num_str.indexOf(".")>=0)
    {
        num_str = num.toString();
        angka = num_str.split(".");
        cents = angka[1];
    }
    
    num = Math.floor(num).toString();
    
        
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    {
        num = num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
    }
    console.log();
    
    if(cents != "00")
        return (((sign)?'':'-') +  num + ',' + cents);
    else
        return (((sign)?'':'-') +  num)+',00';
}
    </script>
</div>
</div>