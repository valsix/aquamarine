<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$aColumns =  array("ISSUE_PO_DETAIL_ID","KETERANGAN","QTY","SATUAN","AMOUNT","AMOUNT_IDR","AMOUNT_USD","TOTAL","CURENCY","TERM","STATUS_BAYAR","AKSI");

$this->load->model("IssuePoNonProject");
$issue_po = new IssuePoNonProject();

$reqId = $this->input->get("reqId");

$termsAndCondition = '<table>
<tbody>
<tr>
<td style="width: 3%; vertical-align: top;">1</td>
<td style="width: 20%; vertical-align: top;">Currency in</td>
<td style="vertical-align: top;">:</td>
<td>IDR</td>
</tr>
<tr>
<td style="width: 3%; vertical-align: top;">2</td>
<td style="width: 20%; vertical-align: top;">Payment Terms</td>
<td style="vertical-align: top;">:</td>
<td>After job completion &amp; 30 days after official invoice received</td>
</tr>
<tr>
<td style="width: 3%; vertical-align: top;">3</td>
<td style="width: 20%; vertical-align: top;">Delivery Time</td>
<td style="vertical-align: top;">:</td>
<td>As per instruction from Originators/Buyer</td>
</tr>
<tr>
<td style="width: 3%; vertical-align: top;">4</td>
<td style="width: 20%; vertical-align: top;">Applicable Taxes</td>
<td style="vertical-align: top;">:</td>
<td>VAT 10%</td>
</tr>
<tr>
<td style="width: 3%; vertical-align: top;">5</td>
<td style="width: 20%; vertical-align: top;">Exclude</td>
<td style="vertical-align: top;">:</td>
<td>Delivery, customs, etc.</td>
</tr>
<tr>
<td style="width: 3%; vertical-align: top;">6</td>
<td style="width: 20%; vertical-align: top;">Insurance</td>
<td style="vertical-align: top;">:</td>
<td>Delivery, customs, etc.</td>
</tr>
<tr>
<td style="width: 3%; vertical-align: top;">7</td>
<td style="width: 20%; vertical-align: top;">Others</td>
<td style="vertical-align: top;">:</td>
<td>SO Number must be referenced on all documents (delivery order, invoice, etc). Any questions pertaining to this SO payment status to be addressed to our Finance/Accounting:&nbsp;<u>aquamarine@gmail.com</u>&nbsp;with cc to&nbsp;<u>qhse@aquamarine.id</u>.</td>
</tr>
</tbody>
</table>';


if ($reqId == "") {
    $reqMode = "insert";
    $reqTermsAndCondition = $termsAndCondition;
} else {
    $reqMode = "ubah";
    $statement = " AND A.ISSUE_PO_ID = " . $reqId;
    $issue_po->selectByParamsMonitoring(array(), -1, -1, $statement);
    // echo $issue_po->query;exit;
    $issue_po->firstRow();

    $reqId              = $issue_po->getField("ISSUE_PO_ID");
    $reqNomerPo         = $issue_po->getField("NOMER_PO");
    $reqPoDate          = $issue_po->getField("PO_DATE");
    $reqDocLampiran     = $issue_po->getField("DOC_LAMPIRAN");
    $reqReferensi       = $issue_po->getField("REFERENSI");
    $reqPathLampiran    = $issue_po->getField("PATH_LAMPIRAN");
    $reqFinance         = $issue_po->getField("FINANCE");
    $reqCompanyId       = $issue_po->getField("COMPANY_ID");
    $reqCompanyName     = $issue_po->getField("COMPANY_NAME");
    $reqContact         = $issue_po->getField("CONTACT");
    $reqAddress         = $issue_po->getField("ADDRESS");
    $reqEmail           = $issue_po->getField("EMAIL");
    $reqTelp            = $issue_po->getField("TELP");
    $reqFax             = $issue_po->getField("FAX");
    $reqHp              = $issue_po->getField("HP");
    $reqBuyerId         = $issue_po->getField("BUYER_ID");
    $reqOther           = $issue_po->getField("OTHER");
    $reqPpn             = $issue_po->getField("PPN");
    $reqPpnPercent      = $issue_po->getField("PPN_PERCENT");
    // echo $reqPpn;exit;

    $reqPic         = $issue_po->getField("PIC");
    $reqDepartement = $issue_po->getField("DEPARTEMENT");
    $reqTermsAndCondition = $issue_po->getField("TERMS_AND_CONDITION");
    $reqTypePo = $issue_po->getField("TYPE");
    $reqAcknowledgedBy = $issue_po->getField("ACKNOWLEDGED_BY");
    $reqAcknowledgedDept = $issue_po->getField("ACKNOWLEDGED_DEPT");
    $reqApproved1By = $issue_po->getField("APPROVED1_BY");
    $reqApproved1Dept = $issue_po->getField("APPROVED1_DEPT");
    $reqApproved2By = $issue_po->getField("APPROVED2_BY");
    $reqApproved2Dept = $issue_po->getField("APPROVED2_DEPT");
    
    $reqNote                = $issue_po->getField("NOTE");
    $reqTypeCur             = $issue_po->getField("TYPE_CUR");
    $reqStatusProses        = $issue_po->getField("STATUS");
    $reqTanggalPembayaran   = $issue_po->getField("TANGGAL_BAYAR");
    $reqDiskon = $issue_po->getField("DISKON");
    $reqDiskonPercent = $issue_po->getField("DISKON_PERCENT");
    $reqBayar2 = $issue_po->getField("STATUS_BAYAR");
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
            "responsive":true,
            "sAjaxSource": "web/issue_po_non_project_detail_json/json?reqId=" + reqIds,
            "fnServerParams": function(aoData) {
                // aoData.push( { "name": "input1", "value": $("#input1").val() } );
                // aoData.push( { "name": "input2", "value": $("#input2").val() } );
                // aoData.push( { "name": "input3", "value": $("#input3").val() } );
            },
            columnDefs: [{
                className: 'never',
                targets: [0,5,6,8,10]
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
            // "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            //   if (aData[12] == "2") {
            //     $('td', nRow).addClass('redClass');
            //     }
            // }, 
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;
                var intVal = function(i) {
                    // console.log(i);
                    return typeof i === 'string' ?
                        i.replaceAll(',', '') * 1 :
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


                var checkBox = $('#chek').prop("checked");
                var ppn = $("#reqPpnPercent").val();
                // console.log(ppn);

                if (checkBox == true) {
                    var total_ppn_idr = (total_idr * ppn) / 100;
                    var total_ppn_usd = (total_usd * ppn) / 100;
                    // total_usd = total_usd - total_ppn_usd;
                    // total_idr = total_idr - total_ppn_idr;
                    total_usd = total_usd;
                    total_idr = total_idr;
                }

                var currency = " Rupiah ";
                var total = total_idr;

                if(total == 0){
                    currency = " USD ";
                    total = total_usd;
                }
                
                $.get("app/terbilang?angka="+total, function(data) {
                    $("#totalWord").val(data + currency);
                });

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

    <div class="judul-halaman"> <a href="app/index/issue_po_non_project">Puchase Order non Project </a> &rsaquo;Puchase Order add
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="cetakPdf()"><i class="fa fa-file-pdf-o "> </i><span> Print</span> </a>
         <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="openAdd('app/loadUrl/app/tempalate_master_lampiran?reqModul=SO_PO_NON&reqId=<?=$reqId?>')"><i class="fa fa-file "> </i><span> Lampiran</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_type_po()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Type PO</span> </a>
      
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
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Type</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqTypePo" name="reqTypePo" data-options="width:'300',editable:false, valueField:'id',textField:'text',url:'combo_json/comboTypePo'" value="<?=$reqTypePo?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">No. PO</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNomerPo" class="easyui-validatebox textbox form-control" name="reqNomerPo" value="<?= $reqNomerPo ?>" style=" width:100%" />
                                    <!--   <input type="text" id="reqNomerPo2" class="easyui-validatebox textbox form-control" value="<?= $reqNomerPo ?>" style=" width:100%" /> -->
                                    <button type="button" class="btn btn-default pull-right" onclick="pilih_project()">...</button>
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
                        <label for="reqFinance" class="control-label col-md-2"> Status  </label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input class="easyui-combobox form-control" style="width:100%" id="reqStatusProses" name="reqStatusProses" data-options="width:'122',editable:false, valueField:'id',textField:'text',url:'combo_json/comboStatusProses'" value="<?= $reqStatusProses ?>" required />
                                </div>
                            </div>
                        </div>

                         <label for="reqFinance" class="control-label col-md-1"> &nbsp; Date </label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-4">
                                     <input class="easyui-datebox textbox form-control" style="width:190px" name="reqTanggalPembayaran" value="<?=$reqTanggalPembayaran?>" />
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
                        <label for="reqTelephone" class="control-label col-md-2">Finance Reference</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <input type="text" id="reqReferensi" class="easyui-validatebox textbox form-control" name="reqReferensi" value="<?= $reqReferensi ?>" placeholder="Reference" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?
                        $checks = '';
                        if ($reqPpn == 1) {
                            $checks = "checked";
                        }
                        ?>
                        <label for="reqPpn" class="control-label col-md-2">Ppn <input type="checkbox" <?= $checks ?> name="reqPpn" id="reqPpn" onchange="getPPn()" onkeyup="getPPn()" value="1" /> </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPpnPercent" maxlength="2" class=" form-control" id="reqPpnPercent" name="reqPpnPercent" value="<?= $reqPpnPercent ?>" style=" width:20%" onchange="numberWithCommas('reqPpnPercent'),getPPn()" onkeyup="numberWithCommas('reqPpnPercent'),getPPn()" /> % <strong> Currency Ppn</strong>

                                </div>
                            </div>
                        </div>
                        <label for="reqTelephone" class="control-label col-md-2">Currency</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input class="easyui-combobox form-control" style="width:100%" id="reqTypeCur" name="reqTypeCur" data-options="width:'372',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar2'" value="<?= $reqTypeCur ?>" required />

                                  
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <?
                        $checks = '';
                        if ($reqDiskon == 1) {
                            $checksDiskon = "checked";
                        }
                        ?>
                        <label for="reqPpn" class="control-label col-md-2">Diskon <input type="checkbox" <?= $checksDiskon ?> name="reqDiskon" id="reqDiskon" onchange="getPPn()" onkeyup="getPPn()" value="1" /> </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDiskonPercent"  class=" form-control" id="reqDiskonPercent" name="reqDiskonPercent" value="<?= currencyToPage2($reqDiskonPercent) ?>" style=" width:80%" onchange="numberWithCommas('reqDiskonPercent'),getPPn()" onkeyup="numberWithCommas('reqDiskonPercent'),getPPn()" />

                                </div>
                            </div>
                        </div>
                       
                    </div>
                    <div class="form-group">
                        <label for="reqTermsAndCondition" class="control-label col-md-2">Terms And Condition</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control tinyMCES" name="reqTermsAndCondition" cols="4" rows="3" style="width:100%;"><?= $reqTermsAndCondition; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Note</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea rows="5" cols="4" class="form-control" name="reqNote" id="reqNote"><?=$reqNote?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                    <label for="reqBayar" class="control-label col-md-2"> Status Pembayaran </label>
                        <div class="col-md-7">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <select name="reqBayar2" id="reqBayar2">
                                      <option value="">Pilih Status</option>   
                                      <option value="1"<? if($reqBayar2 == '1') echo 'selected'?>>Bayar</option>   
                                      <option value="2"<? if($reqBayar2 == '2') echo 'selected'?>>Belum Bayar</option> 
                                  </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> PIC </h3>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Requested by</label>
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

                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Acknowledged by</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqAcknowledgedBy" class="easyui-validatebox textbox form-control" name="reqAcknowledgedBy" value="<?= $reqAcknowledgedBy ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqAcknowledgedDept" class="control-label col-md-2">Departement</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqAcknowledgedDept" class="easyui-validatebox textbox form-control" name="reqAcknowledgedDept" value="<?= $reqAcknowledgedDept ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Approved I by</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqApproved1By" class="easyui-validatebox textbox form-control" name="reqApproved1By" value="<?= $reqApproved1By ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqApproved1Dept" class="control-label col-md-2">Departement</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqApproved1Dept" class="easyui-validatebox textbox form-control" name="reqApproved1Dept" value="<?= $reqApproved1Dept ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Approved II by</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqApproved2By" class="easyui-validatebox textbox form-control" name="reqApproved2By" value="<?= $reqApproved2By ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqApproved2Dept" class="control-label col-md-2">Departement</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqApproved2Dept" class="easyui-validatebox textbox form-control" name="reqApproved2Dept" value="<?= $reqApproved2Dept ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Company </h3>
                    </div>

                    <div class="form-group">
                        <div class="col-md-7">
                            <label for="reqCompanyName" class="control-label col-md-2">Costumer</label>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-md-11">
                                        <input type="text" onclick="openCompany()" id="reqCompanyName" class="easyui-validatebox textbox form-control" name="reqCompanyName" value="<?= $reqCompanyName ?>" style=" width:100%" />
                                        <input type="hidden" id="reqCompanyId" class="easyui-validatebox textbox form-control" name="reqCompanyId" value="<?= $reqCompanyId ?>" style=" width:100%" />
                                    </div>
                                </div>
                            </div>
                            <label for="reqInvoiceNumber" class="control-label col-md-2">Address</label>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-md-11">
                                        <textarea rows="5" cols="4" class="form-control tinyMCES" name="reqAddress" id="reqAddress"><?=$reqAddress?></textarea>
                                    </div>
                                </div>
                            </div>
                            <label for="reqInvoiceNumber" class="control-label col-md-2">Contact</label>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-md-11">
                                        <input type="text" id="reqContact" class="easyui-validatebox textbox form-control" name="reqContact" value="<?= $reqContact ?>" style=" width:100%" />
                                    </div>
                                </div>
                            </div>
                            <label for="reqInvoiceNumber" class="control-label col-md-2">Email</label>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="col-md-11">
                                        <input type="text" id="reqEmail" class="easyui-validatebox textbox form-control" name="reqEmail" value="<?= $reqEmail ?>" style=" width:100%" />
                                    </div>
                                </div>
                            </div>

                        </div>




                        <div class="col-md-5">
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
                                                           $reqBuyerId = explode(",", $reqBuyerId);
                                                        for ($i = 0; $i < count($arrDatas); $i++) {
                                                            $checked = '';
                                                         
                                                            // for ($j = 0; $j < count($reqBuyerId); $j++) {
                                                            //     if ($arrDatas[$i] == $reqBuyerId[$j]) {
                                                            //         $checked = "checked";
                                                            //     }
                                                            // }
                                                            if (in_array($arrDatas[$i], $reqBuyerId)){
                                                                $checked = "checked";
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
                        <label for="reqInvoiceNumber" class="control-label col-md-1" style="margin-left: 25px">HP</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <input type="text" onkeypress='validatePhoneNumber(event)' id="reqTelp" class="easyui-validatebox textbox form-control" name="reqTelp" value="<?= $reqTelp ?>" style=" width:100%" />

                                </div>
                                <div class="col-md-3">
                                    <b> Telp :</b>
                                    <input type="text" id="reqHp" onkeypress='validatePhoneNumber(event)' class="easyui-validatebox textbox form-control" name="reqHp" value="<?= $reqHp ?>" style=" width:80%" />


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
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea rows="5" cols="4" name="reqKeterangan" id="reqKeterangan" class="form-control tinyMCES"></textarea>
                                </div>
                            </div>
                        </div>
                        <label for="reqStatus" class="control-label col-md-2" style="display: none;">Currency</label>
                        <div class="col-md-4" style="display: none;">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqCurrencys" name="reqCurrencys" data-options="width:'372',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar2'" value="<?= $reqCurrency ?>" />

                                </div>
                            </div>
                        </div>
                        
                        <?php /*
                        <input type="hidden" name="reqPpn" id="reqPpn" value="1" />
                        
                        <label for="reqStatus" class="control-label col-md-2">Ppn <input type="checkbox" <?= $checks ?> name="chek" id="chek" onchange="getPPn()" onkeyup="getPPn()" value="1" /> </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPpnPercent" maxlength="2" class=" form-control" id="reqPpnPercent" name="reqPpnPercent" value="<?= $reqPpnPercent ?>" style=" width:20%" onchange="numberWithCommas('reqPpnPercent'),getPPn()" onkeyup="numberWithCommas('reqPpnPercent'),getPPn()" /> % <strong> Currency Ppn</strong>

                                </div>
                            </div>
                        </div>
                        */
                        ?>
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
                    <div class="form-group" style="display: none;">
                        <label for="reqBayar" class="control-label col-md-2"> Status Pembayaran </label>
                        <div class="col-md-7">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <select name="reqBayar" id="reqBayar">
                                      <option value="">Pilih Status</option>   
                                      <option value="1"<? if($reqBayar == '1') echo 'selected'?>>Bayar</option>   
                                      <option value="2"<? if($reqBayar == '2') echo 'selected'?>>Belum Bayar</option> 
                                  </select>
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
                <td style="width: 30%"> Total Payable </td>
                <td style="width: 20%"><b> RP <b> <input style="width: 60%" type="text" id="totalIDR" class="form-control" disabled readonly /> </td>

                <td style="width: 20%"><b> USD<b> <input style="width: 60%" type="text" id="totalUSD" class="form-control" disabled readonly /></td>
                <td style="width: 30%"> <input style="width: 100%" type="text" id="totalWord" class="form-control" /></td>


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
                url: 'web/issue_po_non_project_detail_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    // console.log(data);return false;
                    var datas = data.split('-');
                    var reqId = datas[1];

                    if (reqId == '') {
                        show_toast('info', 'Information', datas[0]);
                    } else {
                        // $('#reqId').val(datas[1]);
                        // reqIds = datas[1];
                        show_toast('info', 'Information', 'Header success added <br>' + datas[0]);
                        $.messager.alertLink('Info', datas[0], 'info', "app/index/issue_po_non_project_add/?reqId=" + reqId);
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
                    console.log(datas);
                    $("#reqCompanyName").val(datas.NAME);
                    $("#reqContactName").val(datas.CP1_NAME);
                    console.log(datas.ADDRESS);
                    // $("#reqAddress").val(datas.ADDRESS);
                    $(tinymce.get('reqAddress').getBody()).html(datas.ADDRESS);
                    $("#reqTelephone").val(datas.PHONE);
                    $("#reqFaximile").val(datas.FAX);
                    $("#reqEmail").val(datas.EMAIL);
                    $("#reqContact").val(datas.CP1_NAME);
                    $("#reqTelp").val(datas.CP1_TELP);
                    $("#reqFax").val(datas.FAX);
                    $("#reqHp").val(datas.PHONE);

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
            $(tinymce.get('reqKeterangan').getBody()).html(elements[1]);
            // $("#reqKeterangan").val(elements[1]);
            $("#reqTerm").val(elements[9]);
            $("#reqAmount").val(elements[4]);
            $("#reqBayar").val(elements[12]);
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
            $("#reqBayar").val('');
            $("#reqCurrencys").combobox('setValue', '');
        }

        function pilih_project() {
            openAdd('app/loadUrl/app/template_load_offering');
        }

        function addOffering(id, kode,date,location,detail,cp,clvessel,nmvessel,tpvessel,company,companyid) {
            $("#reqIdReport").val(id);
            $("#reqNomerPo").val(kode);
            $("#reqNomerPo2").val(kode);
            // $("#reqDateOfService").val(date);
            $("#reqCompanyName").val(company);
            company_pilihan(companyid);
            
        }

        function deleting(id) {
            var elements = oTable.fnGetData(id);
            var kata = '<b>Detail </b><br>' + elements[2] + '<br> At' + elements[3];

            $.get("web/issue_po_detail_json/deletedetailNonProject?reqId=" + elements[0], function(data) {
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
            openAdd('app/loadUrl/app/template_report_issue_po_non_project_pdf?reqId=<?= $reqId ?>');
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
             $(tinymce.get('reqAddress').getBody()).html(reqFaximile);
            // $("#reqAddress").val(reqAddress);
            $("#reqEmail").val(reqAddress);
            $("#reqTelp").val(reqEmail);
            $("#reqFax").val(reqTelephone);
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