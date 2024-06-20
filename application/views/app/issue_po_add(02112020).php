<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$aColumns =  array("ISSUE_PO_DETAIL_ID","KETERANGAN","QTY","SATUAN","AMOUNT","AMOUNT_IDR","AMOUNT_USD","TOTAL","CURENCY","TERM","AKSI");

$this->load->model("IssuePo");
$issue_po = new IssuePo();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $statement = " AND A.ISSUE_PO_ID = " . $reqId;
    $issue_po->selectByParamsMonitoring(array(), -1, -1, $statement);
    $issue_po->firstRow();

    $reqId   = $issue_po->getField("ISSUE_PO_ID");
    $reqNomerPo     = $issue_po->getField("NOMER_PO");
    $reqPoDate      = $issue_po->getField("PO_DATE");
    $reqDocLampiran = $issue_po->getField("DOC_LAMPIRAN");
    $reqReferensi   = $issue_po->getField("REFERENSI");
    $reqPathLampiran = $issue_po->getField("PATH_LAMPIRAN");
    $reqFinance     = $issue_po->getField("FINANCE");
    $reqCompanyId   = $issue_po->getField("COMPANY_ID");
    $reqCompanyName = $issue_po->getField("COMPANY_NAME");
    $reqContact     = $issue_po->getField("CONTACT");
    $reqAddress     = $issue_po->getField("ADDRESS");
    $reqEmail       = $issue_po->getField("EMAIL");
    $reqTelp        = $issue_po->getField("TELP");
    $reqFax         = $issue_po->getField("FAX");
    $reqHp          = $issue_po->getField("HP");
    $reqBuyerId     = $issue_po->getField("BUYER_ID");
    $reqOther       = $issue_po->getField("OTHER");
    $reqPpn         = $issue_po->getField("PPN");
    $reqPpnPercent  = $issue_po->getField("PPN_PERCENT");
    // echo $reqPpn;exit;

    $reqPic         = $issue_po->getField("PIC");
    $reqDepartement = $issue_po->getField("DEPARTEMENT");
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
            "sAjaxSource": "web/issue_po_detail_json/json?reqId=" + reqIds,
            "fnServerParams": function(aoData) {
                // aoData.push( { "name": "input1", "value": $("#input1").val() } );
                // aoData.push( { "name": "input2", "value": $("#input2").val() } );
                // aoData.push( { "name": "input3", "value": $("#input3").val() } );
            },
            columnDefs: [{
                className: 'never',
                targets: [0,5,6,8]
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
                    .column(6)
                    .data()
                    .reduce(function(a, b) {
                        // console.log(a+'-'+b);
                        return intVal(a) + intVal(b);
                    }, 0);
                var total_idr = api
                    .column(5)
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

    <div class="judul-halaman"> <a href="app/index/issue_po">Puchase Order </a> &rsaquo;Puchase Order add
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="cetakPdf()"><i class="fa fa-file-pdf-o "> </i><span> Print</span> </a>
      
    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">


                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Purchase Order

                         <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>
                         <br>
                         <br>
                     </h3>
                    </div>
                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Po Number</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNomerPo" class="easyui-validatebox textbox form-control" name="reqNomerPo" value="<?= $reqNomerPo ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqTelephone" class="control-label col-md-2">Po Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPoDate" class="easyui-datebox textbox form-control" name="reqPoDate" value="<?= $reqPoDate ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqFinance" class="control-label col-md-2"> Reference </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqFinance" class="easyui-validatebox textbox form-control" name="reqFinance" value="<?= $reqFinance ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        

                    </div>

                     <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Doc</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <input type="text" placeholder="Doc Name" id="reqDocLampiran" class="easyui-validatebox textbox form-control" name="reqDocLampiran" value="<?= $reqDocLampiran ?>" style=" width:200px" />
                                   <br>
                                    <?
                                    if(!empty($reqId)){
                                    ?>
                                    <a onclick="openAdd('uploads/issue_po/<?= $reqId ?>/<?= $reqPathLampiran ?>');" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file"> <?= $reqPathLampiran ?> </span>
                                    <?
                                    }
                                    ?>
                                    <input type="file" name="document[]" class="form-control" style="width: 70px">
                                    <input type="hidden" name="reqLinkFileTemp[]" value="<?=$reqPathLampiran?>">
                                </div>
                            </div>
                        </div>
                        <label for="reqTelephone" class="control-label col-md-2">Reference</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <input type="text" id="reqReferensi" class="easyui-validatebox textbox form-control" name="reqReferensi" value="<?= $reqReferensi ?>" placeholder="Reference" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> PIC </h3>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">PIC</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPic" class="easyui-validatebox textbox form-control" name="reqPic" value="<?= $reqPic ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqDepartement" class="control-label col-md-2">Departement</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDepartement" class="easyui-validatebox textbox form-control" name="reqDepartement" value="<?= $reqDepartement ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Company </h3>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="reqCompanyName" class="control-label col-md-4">Custumer</label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="col-md-11">
                                        <input type="text" onclick="openCompany()" id="reqCompanyName" class="easyui-validatebox textbox form-control" name="reqCompanyName" value="<?= $reqCompanyName ?>" style=" width:100%" />
                                        <input type="hidden" id="reqCompanyId" class="easyui-validatebox textbox form-control" name="reqCompanyId" value="<?= $reqCompanyId ?>" style=" width:100%" />
                                    </div>
                                </div>
                            </div>
                            <label for="reqInvoiceNumber" class="control-label col-md-4">Address</label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="col-md-11">
                                        <textarea rows="5" cols="4" class="form-control" name="reqAddress" id="reqAddress"><?=$reqAddress?></textarea>
                                    </div>
                                </div>
                            </div>
                            <label for="reqInvoiceNumber" class="control-label col-md-4">Contact</label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="col-md-11">
                                        <input type="text" id="reqContact" class="easyui-validatebox textbox form-control" name="reqContact" value="<?= $reqContact ?>" style=" width:100%" />
                                    </div>
                                </div>
                            </div>
                            <label for="reqInvoiceNumber" class="control-label col-md-4">Email</label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="col-md-11">
                                        <input type="text" id="reqEmail" class="easyui-validatebox textbox form-control" name="reqEmail" value="<?= $reqEmail ?>" style=" width:100%" />
                                    </div>
                                </div>
                            </div>

                        </div>




                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reqInvoiceNumber" class="control-label col-md-4">Originator / Buyer</label>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="col-md-11">
                                            <?

                                            $arrDatas  = array(
                                                "General Manager",
                                                "Finance Manager",
                                                "Marketing Manager",
                                                "Operation Manager",
                                                "Finance Support",
                                                "Others"

                                            );
                                            ?>
                                            <div class="col-md-11">
                                                <div style="background-color: white;
                                    height: 250px;
                                    width: 100%;
                                    overflow-y: scroll;">
                                                    <table style="width: 100%;color: black;font-weight: bold;" valign="top">
                                                        <?
                                                        for ($i = 0; $i < count($arrDatas); $i++) {
                                                            $checked = '';
                                                            $reqBuyerId = explode(",", $reqBuyerId);
                                                            for ($j = 0; $j < count($reqBuyerId); $j++) {
                                                                if ($arrDatas[$i] == $reqBuyerId[$j]) {
                                                                    $checked = "checked";
                                                                }
                                                            }

                                                        ?>

                                                            <tr>
                                                                <td style="padding: 5px"><input 
                                                                    onchange="cek_value_check()" onkeyup="cek_value_check()"
                                                                    type="checkbox" class="form-control" name="reqBuyerId[]" value="<?= $arrDatas[$i] ?>" <?= $checked ?>> </td>
                                                                <td style="padding: 5px"> <?= $arrDatas[$i] ?> </td>
                                                            </tr>
                                                        <?
                                                        }
                                                        ?>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Telp</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <input type="text" onkeypress='validate(event)' id="reqTelp" class="easyui-validatebox textbox form-control" name="reqTelp" value="<?= $reqTelp ?>" style=" width:100%" />

                                </div>
                                <div class="col-md-3">
                                    <b> HP :</b>
                                    <input type="text" id="reqHp" onkeypress='validate(event)' class="easyui-validatebox textbox form-control" name="reqHp" value="<?= $reqHp ?>" style=" width:80%" />


                                </div>
                                <div class="col-md-3">
                                    <b> FAX :</b>
                                    <input type="text"  id="reqFax" class="easyui-validatebox textbox form-control" name="reqFax" value="<?= $reqFax ?>" style=" width:80%" />

                                </div>
                                <div class="col-md-3" id="other">
                                    <b> Other :</b>
                                    <input type="text" id="reqOther" class="easyui-validatebox textbox form-control reqOther" name="reqOther" value="<?= $reqOther ?>" style=" width:70%" />

                                </div>

                            </div>
                        </div>

                    </div>



                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Detail </h3>
                    </div>

                    <div class="form-group">
                        <label for="reqServiceDate" class="control-label col-md-2">Qty</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" id="reqIssuePoDetailId" name="reqIssuePoDetailId">
                                    <input type="text" id="reqQty"

                                    onchange="numberWithCommas('reqQty')" onkeyup="numberWithCommas('reqQty')"
                                     class="easyui-validatebox  form-control" name="reqQty" value="" style=" width:100px" />
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqSatuan" name="reqSatuan" data-options="width:'70',editable:false, valueField:'id',textField:'text',url:'combo_json/comboSatuan'" value="" /> / Satuan
                                    <button class="btn btn-info" type="button" onclick="edit_vessel()"><i class="fa fa-pencil fa lg"></i></button>
                                    <button class="btn btn-info" type="button" onclick="refresh_vessel()"><i class="fa fa-refresh fa lg"></i></button>


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
                        <label for="reqDescription" class="control-label col-md-2">Description</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea rows="5" cols="4" name="reqKeterangan" id="reqKeterangan" class="form-control"></textarea>
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
                                    <input type="text" id="reqPpnPercent" maxlength="2" class=" form-control" id="reqPpnPercent" name="reqPpnPercent" value="<?= $reqPpnPercent ?>" style=" width:20%" onchange="numberWithCommas('reqPpnPercent'),getPPn()" onkeyup="numberWithCommas('reqPpnPercent'),getPPn()" /> % <strong> Currency Ppn</strong>

                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="reqTerm" class="control-label col-md-2"> Term </label>
                        <div class="col-md-7">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" id="reqTerm" class="easyui-validatebox textbox form-control" name="reqTerm" value="" ><?= $reqTerm ?></textarea>
                                </div>
                            </div>
                        </div>
                        

                    </div>
                    <br>


                    <div style="text-align:center;padding:5px">
                        <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />
                        <input type="hidden" name="reqTipe" id="reqTipe" value="issue_po" />

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
                url: 'web/issue_po_detail_json/add',
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
                        show_toast('info', 'Information', 'Header success added <br>' + datas[0]);
                        $.messager.alertLink('Info', datas[0], 'info', "app/index/issue_po_add/?reqId=" + datas[1]);
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
            // console.log(elements);
            $("#reqIssuePoDetailId").val(elements[0]);
            $("#reqQty").val(FormatCurrencyBaru(elements[2]));
            $("#reqSatuan").combobox('setValue', elements[3]);
            $("#reqKeterangan").val(elements[1]);
            $("#reqTerm").val(elements[9]);
            $("#reqAmount").val(elements[4]);
            var cur =elements[8];
            if(cur=='' ||cur==null){
                cur =0;
            }
            // console.log(cur);
            $("#reqCurrencys").combobox('setValue', cur);

        }

        function reseti() {
            oTable.api().ajax.reload(null,false);
            $("#reqIssuePoDetailId").val('');
            $("#reqQty").val('');
            $("#reqSatuan").combobox('setValue', '');
            $("#reqKeterangan").val('');
            $("#reqAmount").val('');
            $("#reqTerm").val('');
            $("#reqCurrencys").combobox('setValue', '');
        }

        function deleting(id) {
            var elements = oTable.fnGetData(id);
            var kata = '<b>Detail </b><br>' + elements[2] + '<br> At' + elements[3];

            $.get("web/issue_po_detail_json/deletedetail?reqId=" + elements[0], function(data) {
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
            // var reqCompanyId = $("#reqCompanyId").val();
            // if (reqCompanyId == '') {
            //     $.messager.alert('Info', 'Pilih Company terlebih dahulu', 'info');
            //     return;
            // }
            // window.open("app/index/template_master_satuan?reqId=" + reqCompanyId, "_blank", "width=700,height=800");

            openAdd('app/loadUrl/app/tempalate_master_satuan');

        }

        function refresh_vessel() {
          
            var url = 'combo_json/comboSatuan';
            
            var cc = $('#reqSatuan');
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

        function cetakPdf() {
            openAdd('app/loadUrl/app/template_report_issue_po_pdf?reqId=<?= $reqId ?>');
        }

        function cetakPdf2() {
            openAdd('app/loadUrl/app/template_report_issue_po_pdf2?reqId=<?= $reqId ?>');
        }
    </script>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script> -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> -->


    <script type="text/javascript">
        function company_pilihans(id, name, contact, reqAddress, reqEmail, reqTelephone, reqFaximile, reqHp) {
            $("#reqCompanyName").val(name);
            $("#reqCompanyId").val(id);
            $("#reqContact").val(contact);
            $("#reqAddress").val(reqAddress);
            $("#reqEmail").val(reqEmail);
            $("#reqTelp").val(reqTelephone);
            $("#reqFax").val(reqFaximile);
            $("#reqHp").val(reqHp);

          
             


        }
        function openCompany() {
            openAdd('app/loadUrl/app/template_load_company_id');

        }
        function cek_value_check() {
            var params = $('input[name^="reqBuyerId"]').serializeArray();
            for(var i=0; i<params.length;i++){
               
                if(params[i]["value"]=='Others'){

                     $("#other").show(); 
                     return;
                }else{
                     
                }
            }
           $("#other").hide();
        }

        $(document).ready(function() {
           cek_value_check();

            // indonesia
            $('#ProjectBesarPajakInd').on('click', function() {
                openAdd('app/loadUrl/report/template_report_besar_pajak_ind_pdf?reqId=<?= $reqId ?>&reqMode=ppn');
            });

            $('#ProjectBesarNonPajakInd').on('click', function() {
                openAdd('app/loadUrl/report/template_report_besar_non_pajak_ind_pdf?reqId=<?= $reqId ?>');
            });

            $('#ProjectKecilPajakInd').on('click', function() {
                openAdd('app/loadUrl/report/template_report_besar_non_pajak_ind_pdf?reqId=<?= $reqId ?>&reqMode=ppn');
                // openAdd('app/loadUrl/report/template_report_kecil_pajak_ind_pdf?reqId=<?= $reqId ?>');
            });

            $('#ProjectKecilNonPajakInd').on('click', function() {
                openAdd('app/loadUrl/report/template_report_besar_non_pajak_ind_pdf?reqId=<?= $reqId ?>');
                // openAdd('app/loadUrl/report/template_report_kecil_non_pajak_ind_pdf?reqId=<?= $reqId ?>');
            });


            // english
            $('#ProjectBesarPajakEng').on('click', function() {
                openAdd('app/loadUrl/report/template_report_besar_pajak_ind_pdf?reqId=<?= $reqId ?>&reqMode=ppn');
                // openAdd('app/loadUrl/report/template_report_besar_pajak_eng_pdf?reqId=<?= $reqId ?>');
            });

            $('#ProjectBesarNonPajakEng').on('click', function() {
                openAdd('app/loadUrl/report/template_report_besar_pajak_ind_pdf?reqId=<?= $reqId ?>');
                // openAdd('app/loadUrl/report/template_report_besar_non_pajak_eng_pdf?reqId=<?= $reqId ?>');
            });

            $('#ProjectKecilPajakEng').on('click', function() {
                openAdd('app/loadUrl/report/template_report_besar_non_pajak_ind_pdf?reqId=<?= $reqId ?>&reqMode=ppn');
                // openAdd('app/loadUrl/report/template_report_kecil_pajak_eng_pdf?reqId=<?= $reqId ?>');
            });

            $('#ProjectKecilNonPajakEng').on('click', function() {
                openAdd('app/loadUrl/report/template_report_besar_non_pajak_ind_pdf?reqId=<?= $reqId ?>');
                // openAdd('app/loadUrl/report/template_report_kecil_non_pajak_eng_pdf?reqId=<?= $reqId ?>');
            });

        });
    </script>

</div>
</div>