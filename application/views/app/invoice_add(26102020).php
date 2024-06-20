<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$aColumns = array("INVOICE_DETAIL_ID", "INVOICE_ID", "SERVICE_TYPE", "SERVICE_DATE", "LOCATION", "VESSEL", "AMOUNT_IDR", "AMOUNT_USD", "AMOUNT", "CURRENCY", "AKSI");

$this->load->model("Invoice");
$invoice = new Invoice();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $statement = " AND A.INVOICE_ID = " . $reqId;
    $invoice->selectByParamsMonitoring(array(), -1, -1, $statement);
    $invoice->firstRow();

    $reqInvoiceId       = $invoice->getField("INVOICE_ID");
    $reqInvoiceNumber   = $invoice->getField("INVOICE_NUMBER");
    $reqCompanyId       = $invoice->getField("COMPANY_ID");
    $reqInvoiceDate     = $invoice->getField("INVOICE_DATE");
    // echo $reqInvoiceDate;exit;
    $reqPpn             = $invoice->getField("PPN");
    $reqCompanyName     = $invoice->getField("COMPANY_NAME");
    $reqContactName     = $invoice->getField("CONTACT_NAME");
    $reqAddress         = $invoice->getField("ADDRESS");
    $reqTelephone       = $invoice->getField("TELEPHONE");
    $reqFaximile        = $invoice->getField("FAXIMILE");
    $reqEmail           = $invoice->getField("EMAIL");
    $reqPpnPercent      = $invoice->getField("PPN_PERCENT");
    $reqStatus          = $invoice->getField("STATUS");
    $reqInvoicePo       = $invoice->getField("INVOICE_PO");
    $reqInvoiceTax      = $invoice->getField("INVOICE_TAX");
    $reqTerms           = $invoice->getField("TERMS");
    $reqNoKontrak       = $invoice->getField("NO_KONTRAK");
    $reqNoReport        = $invoice->getField("NO_REPORT");
    $reqDays            = $invoice->getField("DAYS");
}

?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />
<script type="text/javascript" language="javascript" class="init">
    var oTable;
    var total_usd = 0;
    var total_idr = 0;
    var reqIds;
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
            "sAjaxSource": "web/invoice_detail_json/json?reqId=" + reqIds,
            "fnServerParams": function(aoData) {
                // aoData.push( { "name": "input1", "value": $("#input1").val() } );
                // aoData.push( { "name": "input2", "value": $("#input2").val() } );
                // aoData.push( { "name": "input3", "value": $("#input3").val() } );
            },
            columnDefs: [{
                className: 'never',
                targets: [0, 1, 8, 9]
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
                total_usd = api
                    .column(7)
                    .data()
                    .reduce(function(a, b) {
                        // console.log(a+'-'+b);
                        return intVal(a) + intVal(b);
                    }, 0);
                var total_idr = api
                    .column(6)
                    .data()
                    .reduce(function(a, b) {
                        // console.log(a+' - '+b);
                        return intVal(a) + intVal(b);
                    }, 0);

                // var pc = parseInt('<?= $reqRealPrice ?>');
                // var saldo=pc-total;

                // console.log(total_idr);

                var checkBox = $('#chek').prop("checked");
                var ppn = $("#reqPpn").val();
                if (checkBox == true) {
                    var total_ppn_idr = (total_idr * ppn) / 100;
                    var total_ppn_usd = (total_usd * ppn) / 100;
                    total_usd = total_usd - total_ppn_usd;
                    total_idr = total_idr - total_ppn_idr;
                }

                $("#totalIDR").val(FormatCurrencyBaru(total_idr));
                $("#totalUSD").val(FormatCurrencyBaru(total_usd));




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
            // document.location.href = "app/index/cash_report_add?reqId=" + anSelectedId;
            // alert(anSelectedData);





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

    <div class="judul-halaman"> <a href="app/index/invoice">Invoice</a> &rsaquo; Form Invoice
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>



    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Invoice
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                            <div class="btn-group pull-right " style="margin-right: 10px">



                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-file-pdf-o "> </i> Print Invoice English
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="javascript:void(0)" id="ProjectBesarPajakEng"><i class="fa fa-file-pdf-o "> </i> Project Besar dengan pajak</a></li>
                                        <li><a href="javascript:void(0)" id="ProjectBesarNonPajakEng"><i class="fa fa-file-pdf-o "> </i> Project Besar non pajak</a></li>
                                        <li><a href="javascript:void(0)" id="ProjectKecilPajakEng"><i class="fa fa-file-pdf-o "> </i> Project Kecil dengan Pajak</a></li>
                                        <li><a href="javascript:void(0)" id="ProjectKecilNonPajakEng"><i class="fa fa-file-pdf-o "> </i> Project Kecil non Pajak</a></li>

                                    </ul>
                                </div>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-file-pdf-o "> </i> Print Invoice Indonesia
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="javascript:void(0)" id="ProjectBesarPajakInd"><i class="fa fa-file-pdf-o "> </i>Project Besar dengan pajak</a></li>
                                        <li><a href="javascript:void(0)" id="ProjectBesarNonPajakInd"><i class="fa fa-file-pdf-o "> </i> Project Besar non pajak</a></li>
                                        <li><a href="javascript:void(0)" id="ProjectKecilPajakInd"><i class="fa fa-file-pdf-o "> </i> Project Kecil dengan Pajak</a></li>
                                        <li><a href="javascript:void(0)" id="ProjectKecilNonPajakInd"><i class="fa fa-file-pdf-o "> </i> Project Kecil non Pajak</a></li>

                                    </ul>
                                </div>

                            </div>



                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Invoice Number</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInvoiceNumber" class="easyui-validatebox textbox form-control" name="reqInvoiceNumber" value="<?= $reqInvoiceNumber ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqTelephone" class="control-label col-md-2">Telp</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqTelephone" class="easyui-validatebox textbox form-control" name="reqTelephone" value="<?= $reqTelephone ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoicePo" class="control-label col-md-2">No PO</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInvoicePo" class="easyui-validatebox textbox form-control" name="reqInvoicePo" value="<?= $reqInvoicePo ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                        <label for="reqFaximile" class="control-label col-md-2">Fax</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqFaximile" class="easyui-validatebox textbox form-control" name="reqFaximile" value="<?= $reqFaximile ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCompanyName" class="control-label col-md-2">Company Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">

                                    <table style="width: 100%">
                                        <tr>
                                            <td style="width: 90%"> <input type="text" id="reqCompanyName" class="easyui-validatebox textbox form-control" name="reqCompanyName" value="<?= $reqCompanyName ?>" style=" width:100%" onclick="pilih_company()" data-options="required:true" />
                                                <input type="hidden" class="form-control" name="reqCompanyId" id="reqCompanyId" value="<?= $reqCompanyId ?>">

                                            </td>

                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <label for="reqEmail" class="control-label col-md-2">Email </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqKasbon" class="easyui-validatebox textbox form-control" name="reqEmail" value="<?= $reqEmail ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqContactName" class="control-label col-md-2">Contact Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqContactName" class="easyui-validatebox textbox form-control" name="reqContactName" value="<?= $reqContactName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqStatus" class="control-label col-md-2">Status </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqStatus"  id="reqStatus" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboStatus2'" value="<?= $reqStatus ?>" />

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqAddress" class="control-label col-md-2">Address</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" rows="4" cols="2" id="reqAddress" class="easyui-validatebox textbox form-control" name="reqAddress" style=" width:100%" ><?= $reqAddress ?></textarea> 
                                </div>
                            </div>
                        </div>
                        <label for="reqInvoiceTax" class="control-label col-md-2">Invoice</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInvoiceTax" class="easyui-validatebox textbox form-control" name="reqInvoiceTax" value="<?= $reqInvoiceTax ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="reqInvoiceDate" class="control-label col-md-2">PO Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInvoiceDate" class="easyui-datebox textbox form-control" name="reqInvoiceDate" value="<?= $reqInvoiceDate ?>" style=" width:200px" />

                                </div>
                            </div>
                        </div>
                        <label for="reqRealPrice" class="control-label col-md-2">Days </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" onkeypress='validate(event)' id="reqDuration" class="easyui-validatebox textbox form-control readonly" name="reqDuration" value="<?= $reqDuration ?>" style=" width:40%" />
                                </div>
                            </div>
                        </div>
                        
                    </div>


                    <div class="form-group">
                        <label for="reqNoReport" class="control-label col-md-2">No Report</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" name="reqIdReport" id="reqIdReport">
                                    <input type="text" id="reqNoReport" onclick="pilih_report()" class="easyui-validatebox textbox form-control" name="reqNoReport" value="<?= $reqNoReport ?>" style=" width:40%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqRealPrice" class="control-label col-md-2">No Kontak</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNoKontrak" class="easyui-validatebox textbox form-control" name="reqNoKontrak" value="<?= $reqNoKontrak ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqTerms" class="control-label col-md-2">Term Condition</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control" cols="5" rows="3" name="reqTerms"> <?= $reqTerms ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Project Cost</h3>
                    </div>
                    <div class="form-group">
                        <label for="reqServiceType" class="control-label col-md-2">Type Of Service</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" id="reqInvoiceDetailId" name="reqInvoiceDetailId">
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqServiceType" id="reqServiceType" name="reqServiceType" data-options="width:'372',editable:false, valueField:'id',textField:'text',url:'combo_json/comboTypeOfService'" value="<?= $reqServiceType ?>" />


                                </div>
                            </div>
                        </div>
                        <label for="reqVessel" class="control-label col-md-2">Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">


                                    <input class="easyui-combobox form-control" style="width:200px" id="reqVessel" name="reqVessel" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/comboVessel?reqId=<?= $reqCompanyId ?>'" value="<?= $reqVessel ?>" />
                                    <button class="btn btn-info" type="button" onclick="edit_vessel()"><i class="fa fa-pencil fa lg"></i></button>
                                    <button class="btn btn-info" type="button" onclick="refresh_vessel()"><i class="fa fa-refresh fa lg"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqServiceDate" class="control-label col-md-2">Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqServiceDate" class="easyui-datebox textbox form-control" name="reqServiceDate" value="<?= $reqServiceDate ?>" style=" width:200px" />

                                </div>
                            </div>
                        </div>
                        <label for="reqAmount" class="control-label col-md-2">Amount</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqAmount" class="easyui-validatebox textbox form-control" name="reqAmount" value="<?= $reqAmount ?>" style=" width:100%" onchange="numberWithCommas('reqAmount')" onkeyup="numberWithCommas('reqAmount')" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Location</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLocation" class="easyui-validatebox textbox form-control" name="reqLocation" value="<?= $reqLocation ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqStatus" class="control-label col-md-2">Currency</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqCurrencys" name="reqCurrencys" data-options="width:'372',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar2'" value="<?= $reqCurrency ?>" />

                                </div>
                            </div>
                        </div>
                        <?
                        $checks = '';
                        if ($reqPpn == 1) {
                            $checks = "checked";
                        }


                        ?>
                        <label for="reqStatus" class="control-label col-md-2">Ppn <input type="checkbox" <?= $checks ?> name="chek" id="chek" onchange="getPPn()" onkeyup="getPPn()" value="1" /> </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPpn" maxlength="2" class=" form-control" id="reqPpn" name="reqPpn" value="<?= $reqPpnPercent ?>" style=" width:20%" onchange="numberWithCommas('reqPpn'),getPPn()" onkeyup="numberWithCommas('reqPpn'),getPPn()" /> % <strong> Currency Ppn</strong>

                                </div>
                            </div>
                        </div>
                    </div>
                    <br>


                    <div style="text-align:center;padding:5px">
                        <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />

                        <a href="javascript:void(0)" class="btn btn-warning" onclick="reseti()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                        <!-- <a href="javascript:void(0)" class="btn btn-danger " onclick="print_pdf()"><i class="fa fa-fw fa-print "></i> Print</a> -->
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

                        <!-- <div id="bodys">
                    </div> -->
                </form>

            </div>
            <div class="page-header">
                <h3><i class="fa fa-file-text fa-lg"></i> Summary</h3>
            </div>
            <br>
            <input type="hidden" id="RP_USD" value="">
            <input type="hidden" id="RP" value="">

            <table style="width: 100%" style="font-weight: bold;">
                <td style="width: 60%"> Total Payable </td>
                <td style="width: 20%"><b> RP <b> <input style="width: 60%" type="text" id="totalIDR" class="form-control" disabled readonly /> </td>

                <td style="width: 20%"><b> USD<b> <input style="width: 60%" type="text" id="totalUSD" class="form-control" disabled readonly /></td>


            </table>

            <br>


        </div>

    </div>

    <script type="text/javascript">
        function getPPn() {


            oTable.api().ajax.reload(null,false);
        }

       
    </script>



    <script>
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/invoice_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');

                    if (datas[1] == '') {
                        show_toast('info', 'Information', datas[0]);
                    } else {
                        $('#reqId').val(datas[1]);
                        reqIds = datas[1];
                        show_toast('info', 'Information', 'Header success added' + datas[0]);
                        $.messager.alertLink('Info', datas[0], 'info', "app/index/invoice_add/?reqId=" + datas[1]);
                    }
                    reseti();


                }
            });
        }
    </script>


    <script type="text/javascript">
        function company_pilihan(id) {
            $("#reqCompanyId").val(id);
            $.get("web/customer_json/company_detail_row?&reqId=" + id,
                function(data) {
                    var datas = JSON.parse(data);
                    // console.log(datas.NAME);
                    $("#reqCompanyName").val(datas.NAME);
                    $("#reqContactName").val(datas.CP1_NAME);
                    $("#reqAddress").val(datas.ADDRESS);
                    $("#reqTelephone").val(datas.PHONE);
                    $("#reqFaximile").val(datas.FAX);
                    $("#reqEmail").val(datas.EMAIL);

                    // tambahPenyebab2();
                    // clearFormDetil();
                });

        }
    </script>
    <script type="text/javascript">
        function editing(id) {

            var elements = oTable.fnGetData(id);
            $("#reqInvoiceDetailId").val(elements[0]);
            $("#reqServiceType").combobox('setValue', elements[2]);
            $("#reqVessel").combobox('setValue', elements[5]);
            $("#reqServiceDate").datebox('setValue', elements[3]);
            $("#reqAmount").val(FormatCurrencyBaru(elements[8]));
            $("#reqLocation").val(elements[4]);
            $("#reqCurrencys").combobox('setValue', elements[9]);

        }

        function reseti() {
            oTable.api().ajax.reload(null,false);
            $("#reqInvoiceDetailId").val('');
            $("#reqServiceType").combobox('setValue', '');
            $("#reqVessel").combobox('setValue', '');
            $("#reqServiceDate").datebox('setValue', '');
            $("#reqAmount").val('');
            $("#reqLocation").val('');
            $("#reqCurrencys").combobox('setValue', '');
        }

        function deleting(id) {
            var elements = oTable.fnGetData(id);
            var kata = '<b>Detail </b><br>' + elements[2] + '<br> At' + elements[3];

            $.get("web/invoice_detail_json/delete?reqId=" + elements[0], function(data) {
                oTable.api().ajax.reload(null,false);
                show_toast('warning', 'Success delete row', kata);
            });
        }

        function addReport(id, kode) {


            $("#reqIdReport").val(id);
            $("#reqNoReport").val(kode);

        }
    </script>


    <script type="text/javascript">
        function pilih_company() {
            openAdd('app/loadUrl/app/template_load_single');
        }

        function edit_vessel() {
            var reqCompanyId = $("#reqCompanyId").val();
            if (reqCompanyId == '') {
                $.messager.alert('Info', 'Pilih Company terlebih dahulu', 'info');
                return;
            }
            window.open("app/index/customer_list_add?reqId=" + reqCompanyId, "_blank", "width=700,height=800");

        }

        function refresh_vessel() {
            var reqCompanyId = $("#reqCompanyId").val();


            var url = 'combo_json/comboVessel?reqId=' + reqCompanyId;
            if (reqCompanyId == '') {
                $.messager.alert('Info', 'Pilih Company terlebih dahulu', 'info');
                return;
            }
            var cc = $('#reqVessel');
            cc.combobox('setText', '');
            cc.combobox('reload', url);
            // $("#reqVessel").combobox('setValue', '');
        }

        function pilih_report() {
            openAdd('app/loadUrl/app/template_load_service_report');
        }

        function print_pdf() {
            openAdd('app/loadUrl/report/invoice_pdf?reqId=<?= $reqId ?>');
        }
    </script>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script> -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> -->


    <script type="text/javascript">
        $(document).ready(function() {

            // indonesia
            $('#ProjectBesarPajakEng').on('click', function() {
                openAdd('app/loadUrl/report/template_report_besar_pajak_ind_pdf?reqId=<?= $reqId ?>&reqMode=ppn&reqBahasa=eng');
            });

            $('#ProjectBesarNonPajakEng').on('click', function() {
                 openAdd('app/loadUrl/report/template_report_besar_pajak_ind_pdf?reqId=<?= $reqId ?>&reqBahasa=eng');
                // openAdd('app/loadUrl/report/template_report_besar_non_pajak_ind_pdf?reqId=<?= $reqId ?>');
            });

            $('#ProjectKecilPajakEng').on('click', function() {
                openAdd('app/loadUrl/report/template_report_besar_non_pajak_ind_pdf?reqId=<?= $reqId ?>&reqMode=ppn&reqBahasa=eng');
                // openAdd('app/loadUrl/report/template_report_kecil_pajak_ind_pdf?reqId=<?= $reqId ?>');
            });

            $('#ProjectKecilNonPajakEng').on('click', function() {
                 openAdd('app/loadUrl/report/template_report_besar_non_pajak_ind_pdf?reqId=<?= $reqId ?>&reqBahasa=eng');
                 // openAdd('app/loadUrl/report/template_report_besar_non_pajak_ind_pdf?reqId=<?= $reqId ?>&reqMode=ppn&reqBahasa=eng');
                // openAdd('app/loadUrl/report/template_report_besar_non_pajak_ind_pdf?reqId=<?= $reqId ?>');
                // openAdd('app/loadUrl/report/template_report_kecil_non_pajak_ind_pdf?reqId=<?= $reqId ?>');
            });


            // english
            $('#ProjectBesarPajakInd').on('click', function() {
                openAdd('app/loadUrl/report/template_report_besar_pajak_ind_pdf?reqId=<?= $reqId ?>&reqMode=ppn&reqBahasa=ind');
                // openAdd('app/loadUrl/report/template_report_besar_pajak_eng_pdf?reqId=<?= $reqId ?>');
            });

            $('#ProjectBesarNonPajakInd').on('click', function() {
                openAdd('app/loadUrl/report/template_report_besar_pajak_ind_pdf?reqId=<?= $reqId ?>&reqBahasa=ind');
                // openAdd('app/loadUrl/report/template_report_besar_non_pajak_eng_pdf?reqId=<?= $reqId ?>');
            });

            $('#ProjectKecilPajakInd').on('click', function() {
                 openAdd('app/loadUrl/report/template_report_besar_non_pajak_ind_pdf?reqId=<?= $reqId ?>&reqMode=ppn&reqBahasa=ind');
                // openAdd('app/loadUrl/report/template_report_besar_non_pajak_eng_pdf?reqId=<?= $reqId ?>&reqMode=ppn');
                // openAdd('app/loadUrl/report/template_report_kecil_pajak_eng_pdf?reqId=<?= $reqId ?>');
            });

            $('#ProjectKecilNonPajakInd').on('click', function() {
                  openAdd('app/loadUrl/report/template_report_besar_non_pajak_ind_pdf?reqId=<?= $reqId ?>&reqMode=ppn&reqBahasa=ind');
                // openAdd('app/loadUrl/report/template_report_besar_non_pajak_eng_pdf?reqId=<?= $reqId ?>');
                // openAdd('app/loadUrl/report/template_report_kecil_non_pajak_eng_pdf?reqId=<?= $reqId ?>');
            });

        });
    </script>
    <script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript">
        setTimeout(function(){
           ambil_interval();
        }, 1000);
    </script>
<script type="text/javascript">
     $('#reqInvoiceDate').datebox({
            onSelect: function(date){
                // alert(date.getFullYear()+":"+(date.getMonth()+1)+":"+date.getDate());
                ambil_interval();
            }
        }); 




     function ambil_interval(){
       var tgl1 =   $('#reqInvoiceDate').datebox('getValue');
       var today = new Date();
       var dd = String(today.getDate()).padStart(2, '0');
       var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
       var yyyy = today.getFullYear();

         tgl2 = mm + '/' + dd + '/' + yyyy;
         
        var selisih =hitungSelisihHari(tgl1,tgl2);
         // console.log(selisih+" Day ");
         var reqStatus =   $('#reqStatus').combobox('getValue');
         if('Lunas'!=reqStatus){
            $("#reqDuration").val(selisih);
         }
         

     }
</script>
</div>
</div>