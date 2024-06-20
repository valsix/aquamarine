<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$aColumns = array("MATERIAL_INVOICE_DETAIL_ID", "NO", "TANGGAL", "FORM_PEMBELIAN", "TANGGAL_PEMBELIAN", "TANGGAL_TERIMA_BARANG", "DITERIMA_OLEH", "TANGGAL_PEMBAYARAN", "INVOICE/NOTA", "NILAI INVOICE/NOTA" , "AKSI");
$this->load->model("MaterialInvoice");
$material_invoice = new MaterialInvoice();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $material_invoice->selectByParams(array("A.MATERIAL_INVOICE_ID" => $reqId));
    // echo $material_invoice->query;exit;
    $material_invoice->firstRow();

    $reqTahun = $material_invoice->getField("TAHUN");
    $reqDeskripsi = $material_invoice->getField("DESKRIPSI");
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
            "sAjaxSource": "web/material_invoice_detail_json/json?reqId="+reqIds,
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
            "sPaginationType": "full_numbers"

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

            $("#reqMaterialInvoiceDetailId").val(elements[0]);
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

    <div class="judul-halaman"> <a href="app/index/material_invoice"> Material Invoice</a> &rsaquo; Form Material Invoice
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <!-- <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right:  10px" onclick="import_excel()"><i class="fa fa-file-excel-o "> </i><span> Import Detail </span> </a>
        -->
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right:  10px" onclick="export_excel()"><i class="fa fa-file-excel-o "> </i><span> Export Excel </span> </a>

        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right:  10px" onclick="preview_pdf()"><i class="fa fa-file-pdf-o "> </i><span> Preview </span> </a>
        
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
                        <label for="reqTahun" class="control-label col-md-2">Tahun</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqTahun" class="easyui-validatebox textbox form-control" name="reqTahun" value="<?=$reqTahun?>" maxlength="4" style=" width:20%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Detail</h3>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="reqDeskripsi" class="control-label col-md-2">Tanggal </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" name="reqMaterialInvoiceDetailId" id="reqMaterialInvoiceDetailId">
                                    <input  id="reqDetailTanggal" class="easyui-datebox textbox form-control" name="reqDetailTanggal" value="" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="reqLinkFilePembelian" class="control-label col-md-2">Form Pembelian </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <span class="input-group-addon" style="color: black"><input type="file" class="form-control"  id="reqLinkFilePembelian" name="reqLinkFilePembelian[]"  style=" width:100%; color: black"
                                        > </span>
                                        <span class="input-group-addon" ></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label for="reqTanggalPembelian" class="control-label col-md-2">Tanggal Pembelian  </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input  id="reqTanggalPembelian" class="easyui-datebox textbox form-control" name="reqTanggalPembelian" value="" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqDiterimaOleh" class="control-label col-md-2">Diterima Oleh  </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDiterimaOleh" class="easyui-validatebox textbox form-control" name="reqDiterimaOleh" value="" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqTanggalTerima" class="control-label col-md-2">Tanggal Terima  </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input  id="reqTanggalTerima" class="easyui-datebox textbox form-control" name="reqTanggalTerima" value="" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqLinkFileInvoice" class="control-label col-md-2">Form Invoice </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <span class="input-group-addon"><input type="file" class="form-control"  id="reqLinkFileInvoice" name="reqLinkFileInvoice[]"  style=" width:100%; color: black"
                                        > </span>
                                        <span class="input-group-addon" ></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label for="reqTanggalPembayaran" class="control-label col-md-2">Tanggal Pembayaran  </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input  id="reqTanggalPembayaran" class="easyui-datebox textbox form-control" name="reqTanggalPembayaran" value="" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqNilaiInvoice" class="control-label col-md-2">Nilai Invoice  </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNilaiInvoice"  class="easyui-validatebox form-control" name="reqNilaiInvoice" value="" style="width:70%" onchange="numberWithCommas('reqNilaiInvoice')" onkeyup="numberWithCommas('reqNilaiInvoice')" />
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


        </div>

    </div>

    <script>
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/material_invoice_detail_json/add',
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
                          $.messager.alertLink('Info', datas[0], 'info', "app/index/material_invoice_add/?reqId="+datas[1]);

                    } else {
                        oTable.api().ajax.reload(null,false);
                        show_toast('info', 'Information', datas[0]);
                    }
                    reseti();
                }
            });
        }

        // function clearForm() {
        //     $('#ff').form('clear');
        //     $('#reqTanggal').datebox('setValue', '<?= $reqTahun ?>');  
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
                openAdd('app/loadUrl/report/cetak_document_material_invoice_pdf?reqId=<?=$reqId?>');
        }
        function master_kategori(){
            openAdd('app/loadUrl/app/tempalate_master_kategori?');
        }
        function deleting(id) {
            var elements = oTable.fnGetData(id);
            var kata = '<b>Detail </b><br>' + elements[2] + '<br> At' + elements[3];
            $.get("web/material_invoice_detail_json/delete?reqId=" + elements[0], function(data) {
                oTable.api().ajax.reload(null,false);
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

        $aColumns = array("MATERIAL_INVOICE_DETAIL_ID", "NO", "TANGGAL", "FORM_PEMBELIAN", "TANGGAL_PEMBELIAN", "TANGGAL_TERIMA_BARANG", "DITERIMA_OLEH", "TANGGAL_PEMBAYARAN", "INVOICE/NOTA", "NILAI INVOICE/NOTA" , "AKSI");

        function editing(id) {
            var elements = oTable.fnGetData(id);
            $("#reqMaterialInvoiceDetailId").val(elements[0]);
            $('#reqDetailTanggal').datebox('setValue', elements[2]);
            $("#reqTanggalPembelian").datebox('setValue', elements[4]);
            $("#reqTanggalTerima").datebox('setValue', elements[5]);
            $("#reqTanggalPembayaran").datebox('setValue', elements[7]);
            $("#reqDiterimaOleh").val(elements[6]);
            $("#reqNilaiInvoice").val(elements[9]);

        }

        function reseti() {
            oTable.api().ajax.reload(null,false);
            $("#reqMaterialInvoiceDetailId").val('');
            $('#reqDetailTanggal').datebox('setValue', '');
            $("#reqTanggalPembelian").datebox('setValue', '');
            $("#reqTanggalTerima").datebox('setValue', '');
            $("#reqTanggalPembayaran").datebox('setValue', '');
            $("#reqDiterimaOleh").val('');
            $("#reqNilaiInvoice").val('');
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
</div>
</div>