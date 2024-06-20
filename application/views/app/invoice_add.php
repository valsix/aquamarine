<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$aColumns = array("INVOICE_DETAIL_ID", "INVOICE_ID", "TYPE_PROJECT", "SERVICE_TYPE", "SERVICE_DATE", "LOCATION", "VESSEL", "AMOUNT", "AMOUNT_USD", "AMOUNT", "CURRENCY", "QUANTITY", "AKSI", "ADDITIONAL", "VALUE_IDR", "VALUE_USD", "QUANTITY", "QUANTITY_ITEM", "IS_ADDITIONAL","TGL_STATUS_BAYAR","AMOUNT_NILAI_IDR","AMOUNT_NILAI_USD","REMARK");

$this->load->model("Invoice");
$this->load->model("InvoicePayable");
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
    $reqInvoiceDate     = $invoice->getField("INVOICE_DATE2");
    $reqPoDate          = $invoice->getField("PO_DATE2");
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
    $reqNoHp            = $invoice->getField("HP");
    $reqNoReport        = $invoice->getField("NO_REPORT");
    $reqDays            = $invoice->getField("DAYS");
    $reqDP              = currencyToPage2($invoice->getField("DP"));
    $reqOpsiPpn         = $invoice->getField("MANUAL_PPN");
    $reqNominalPpn      = $invoice->getField("NOMINAL_MANUAL");
    $reqPph             = $invoice->getField("PPH");
    $reqPphPercent      = $invoice->getField("PPHPERCENT");    
    $reqTglStatusDate   = $invoice->getField("TGL_STATUS_BAYAR");    


    $invoice_payable = new InvoicePayable();
    $invoice_payable->selectByParamsMonitoring(array("A.INVOICE_ID"=>$reqId));
    $arrDataPayble = $invoice_payable->rowResult;
    $arrDataPayble=  $arrDataPayble[0];
    $reqInvoiceDatePayment = $arrDataPayble['tanggal'];
    $reqDeskriptionPayment = $arrDataPayble['keterangan'];
    $reqPath               = $arrDataPayble['path_link'];

    $reqJenisPPh   = $invoice->getField("JENIS_PPH");    
    $reqJenisPPh= $reqJenisPPh?$reqJenisPPh:'23';
    $reqTaxCheck   = $invoice->getField("TAX_MANUAL");    
    $reqTaxCodePpn =  $invoice->getField("JENIS_TAX");  

    $reqRoCheck =  $invoice->getField("RO_CHECK");    
    $reqRoDate =  $invoice->getField("RO_DATE");    
    $reqRoNomer =  $invoice->getField("RO_NOMER");    
    $reqRemarkInvoiceDetail =  $invoice->getField("REMARK");    
       
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

        setProjectKecil();

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
                targets: [0, 1, 9, 10, 13, 14, 15, 16, 17, 18,19,20,21,22]
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
                    .column(21)
                    .data()
                    .reduce(function(a, b) {
                        a = a.toString();
                        b = b.toString();
                        return parseFloat(a) + parseFloat(b);
                    }, 0);
                var total_idr = api
                    .column(20)
                    .data()
                    .reduce(function(a, b) {
                        a = a.toString();
                        b = b.toString();
                        return parseFloat(a) + parseFloat(b);
                    }, 0);

                // var pc = parseInt('<?= $reqRealPrice ?>');
                // var saldo=pc-total;

                // console.log(api);
                // var kode_currency = api.column(10).data().value;
                 var kode_currency = api.cell(0, 10).data();
                // console.log(kode_currency);

                var checkBox = $('#chek').prop("checked");
                var ppn = $("#reqPpn").prop("checked");
                var pph = $("#reqPPH").prop("checked");
                var ppnPercent = $("#reqPpnPercent").val();
                ppnPercent = parseFloat(ppnPercent);
                 var reqPpnPercentPPh = $("#reqPpnPercentPPh").val();
                 pphPercent = parseFloat(reqPpnPercentPPh);
                
                // var dp = $("#reqDP").val();
                var dp = '0';
                dp = dp.replaceAll('.', '');
                totaldp = dp.replaceAll(',', '.');
                totaldp = parseFloat(totaldp);
                var total_ppn_idr = 0;
                var total_ppn_usd = 0;

                var total_pph_idr = 0;
                var total_pph_usd = 0;

                ppn=false;
                if (ppn) {
                    total_ppn_idr = (total_idr * ppnPercent) / 100;
                    total_ppn_usd = (total_usd * ppnPercent) / 100;
                    // total_usd = total_usd - total_ppn_usd;
                    // total_idr = total_idr - total_ppn_idr;
                    // total_usd = total_usd ;
                    // total_idr = total_idr ;
                }
                pph=false;
                if(pph){
                     total_pph_idr = (total_idr * reqPpnPercentPPh) / 100;
                     total_pph_usd = (total_usd * reqPpnPercentPPh) / 100;
                }

                // console.log(total_pph_idr);

                total_usd = total_usd ;
                total_idr = total_idr - totaldp + total_ppn_idr -total_pph_idr;


                // console.log(total_idr);
                // total_idr = total_idr ;
                
                var currency = " Rupiah ";
                var total = total_idr;

                if(total == 0){
                    currency = " USD ";
                    total = total_usd;
                }
                
                $.get("app/terbilang?angka="+total+"&reqCur="+kode_currency, function(data) {

                    $("#totalWord").html(data.toString());
                });

                $.get("app/mataUang?reqCur="+kode_currency, function(data) {

                    $("#reqFormatUang").html(data.toString());
                 

                });

                // console.log(total_idr);

                $("#totalIDR").val(FormatCurrencyWithDecimal(total_idr));
                $("#totalIDR2").val(FormatCurrencyWithDecimal(total_idr));
                $("#totalUSD").val(FormatCurrencyWithDecimal(total_usd));
                $("#totalUSD2").val(FormatCurrencyWithDecimal(total_usd));
             

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

    function type_of_service() {
        openAdd("app/loadUrl/app/tempalate_master_type_of_service");
    }
</script>



<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/invoice">Invoice</a> &rsaquo; Form Invoice
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <!-- <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="type_of_service()"><i class="fa fa-fw fa-gavel fa lg"> </i><span> Master Type of Service</span> </a>
 -->

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
                <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Invoice
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                            <div class="btn-group pull-right " style="margin-right: 10px">



                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-file-pdf-o "> </i> Print Invoice
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="javascript:void(0)" id="ProjectBesarPajakEng"><i class="fa fa-file-pdf-o "> </i> Project Besar</a></li>
                                        <!-- <li><a href="javascript:void(0)" id="ProjectBesarNonPajakEng"><i class="fa fa-file-pdf-o "> </i> Project Besar non pajak</a></li> -->
                                        <li><a href="javascript:void(0)" id="ProjectKecilPajakEng"><i class="fa fa-file-pdf-o "> </i> Project Kecil</a></li>
                                        <!-- <li><a href="javascript:void(0)" id="ProjectKecilNonPajakEng"><i class="fa fa-file-pdf-o "> </i> Project Kecil non Pajak</a></li> -->

                                    </ul>
                                </div>

                                <!-- <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-file-pdf-o "> </i> Print Invoice Indonesia
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="javascript:void(0)" id="ProjectBesarPajakInd"><i class="fa fa-file-pdf-o "> </i>Project Besar dengan pajak</a></li>
                                        <li><a href="javascript:void(0)" id="ProjectBesarNonPajakInd"><i class="fa fa-file-pdf-o "> </i> Project Besar non pajak</a></li>
                                        <li><a href="javascript:void(0)" id="ProjectKecilPajakInd"><i class="fa fa-file-pdf-o "> </i> Project Kecil dengan Pajak</a></li>
                                        <li><a href="javascript:void(0)" id="ProjectKecilNonPajakInd"><i class="fa fa-file-pdf-o "> </i> Project Kecil non Pajak</a></li>

                                    </ul>
                                </div> -->

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
                        <label for="reqTelephone" class="control-label col-md-2">Telp. No.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqTelephone" class="easyui-validatebox textbox form-control" name="reqTelephone" value="<?= $reqTelephone ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoicePo" class="control-label col-md-2">PO No.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInvoicePo" class="easyui-validatebox textbox form-control" name="reqInvoicePo" value="<?= $reqInvoicePo ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                        <label for="reqFaximile" class="control-label col-md-2">Fax No.</label>
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
                                    <input type="text" id="reqEmail" class="easyui-validatebox textbox form-control" name="reqEmail" value="<?= $reqEmail ?>" style=" width:100%" />
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
                       
                    </div>

                    <div class="form-group">
                        <label for="reqAddress" class="control-label col-md-2">Address</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" rows="4" cols="2" id="reqAddress" class="easyui-validatebox textbox form-control tinyMCES" name="reqAddress" style=" width:100%" ><?= $reqAddress ?></textarea> 
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                    <label for="reqInvoiceTax" class="control-label col-md-2">Tax Invoice</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <?
                                 
                                    $sub = substr($reqInvoiceTax, 0, 3);
                                    $sub=$reqTaxCodePpn?$reqTaxCodePpn:$sub;
                                    if($reqTaxCheck=='1'){$checkTax='checked';}
                                    ?>
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqTaxCodePpn" name="reqTaxCodePpn"  data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'web/invoice_json/comboTax'" value="<?=$sub?>" />
                                      <input type="text" id="reqInvoiceTax" class="easyui-validatebox textbox form-control" name="reqInvoiceTax" onkeyup="changeTax()" onchange="changeTax()" value="<?= $reqInvoiceTax ?>" style=" width:50%" /> 
                                    (  <input type="checkbox" <?=$checkTax?> name="reqTaxCheck" id="reqTaxCheck" value="1" />  Manual )

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="reqInvoiceDate" class="control-label col-md-2">Invoice Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInvoiceDate" class="easyui-datebox form-control" name="reqInvoiceDate" value="<?=$reqInvoiceDate?>" data-options="formatter:myformatter,parser:myparser" style=" width:200px" />

                                </div>
                            </div>
                        </div>
                        <label for="reqRealPrice" class="control-label col-md-2">Days </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" onkeypress='validate(event)' id="reqDaysInvoice" class="easyui-validatebox textbox form-control readonly" name="reqDaysInvoice" value="" style=" width:40%" />
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <label for="reqPoDate" class="control-label col-md-2">PO Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPoDate" class="easyui-datebox form-control" name="reqPoDate" value="<?=$reqPoDate?>" data-options="formatter:myformatter,parser:myparser" style=" width:200px" />

                                </div>
                            </div>
                        </div>
                        <label for="reqRealPrice" class="control-label col-md-2">Days </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" onkeypress='validate(event)' id="reqDays" class="easyui-validatebox textbox form-control readonly" name="reqDays" value="<?= $reqDays ?>" style=" width:40%" />
                                </div>
                            </div>
                        </div>
                        
                    </div>


                    <div class="form-group">
                        <label for="reqNoReport" class="control-label col-md-2">Report No.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        
                                    <input type="hidden" name="reqIdReport" id="reqIdReport">
                                    <input type="text" id="reqNoReport" class="easyui-validatebox textbox form-control" name="reqNoReport" value="<?= $reqNoReport ?>" style=" width:100%" />
                                    <span class="input-group-addon" onclick="pilih_report()"><i></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label for="reqNoHp" class="control-label col-md-2">Contact No.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNoHp" class="easyui-validatebox textbox form-control" name="reqNoHp" value="<?= $reqNoHp ?>" style=" width:100%" />
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
                     <div class="form-group">
                        <label for="reqNoReport" class="control-label col-md-2">RO No.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <?   if($reqRoCheck=='1'){$RoCheck='checked';}?>
                                      <span class="input-group-addon"><i>  <input type="checkbox" <?=$RoCheck?> name="reqRoCheck" id="reqRoCheck" value="1" /> </i></span>
                                    <input type="text" id="reqRoNomer" class="easyui-validatebox textbox form-control" name="reqRoNomer" value="<?= $reqRoNomer ?>" style=" width:100%" />
                                  
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label for="reqNoHp" class="control-label col-md-2">RO Date.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqRoDate" class="easyui-datebox textbox form-control" name="reqRoDate" value="<?= $reqRoDate ?>" style=" width:200px"  />
                                </div>
                            </div>
                        </div>
                    </div>

                    <?
                    $checks = '';
                    if ($reqPpn == 1) {
                        $checks = "checked";
                    }
                       $checksPPh = '';
                    if ($reqPph == 1) {
                        $checksPPh = "checked";
                    }


                    ?>
                    <div class="form-group">
                        <label for="reqStatus" class="control-label col-md-2">Ppn <input type="checkbox" <?= $checks ?> name="reqPpn" id="reqPpn" onchange="getPPn()" onkeyup="getPPn()" value="1" /> </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" maxlength="4"  onkeypress='validate(event)' class=" form-control" id="reqPpnPercent" name="reqPpnPercent" value="<?= $reqPpnPercent ?>" style=" width:20%" onchange="getPPn()" onkeyup="getPPn()" /> % <strong> Currency Ppn</strong>

                                </div>
                            </div>
                        </div>
                        <label for="reqDP" class="control-label col-md-2 project-kecil">Advance Payment</label>
                        <div class="col-md-4 project-kecil">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDP" class="easyui-validatebox textbox form-control" name="reqDP" value="<?= $reqDP ?>" style=" width:100%" onchange="numberWithCommas('reqDP')" onkeyup="numberWithCommas('reqDP')" />
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="reqStatus" class="control-label col-md-2"><input class="easyui-combobox form-control" style="width:100%" name="reqJenisPPh" data-options="width:'100',editable:false, valueField:'id',textField:'text',url:'combo_json/comboStatusPph'" value="<?=$reqJenisPPh?>" /> <input type="checkbox" <?= $checksPPh ?> name="reqPPH" id="reqPPH" onchange="getPPn()" onkeyup="getPPn()" value="1" /> </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" maxlength="4" class=" form-control" id="reqPpnPercentPPh" name="reqPpnPercentPPh" onkeypress='validate(event)' value="<?= $reqPphPercent ?>" style=" width:20%" onchange="getPPn()" onkeyup="getPPn()" /> % <strong> Currency PPh</strong>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqTerms" class="control-label col-md-2">Manual Ppn</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqOpsiPpn" name="reqOpsiPpn" data-options="width:'100',editable:false, valueField:'id',textField:'text',url:'combo_json/comboOpsiPpn'" value="<?=$reqOpsiPpn?>" />
                                     <input type="text" id="reqNominalPpn" class="easyui-validatebox textbox form-control" name="reqNominalPpn" value="<?= $reqNominalPpn ?>" style=" width:30%" onchange="numberWithCommas('reqNominalPpn')" onkeyup="numberWithCommas('reqNominalPpn')" />
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div >
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Invoice Payment</h3>
                    </div>
                      <div class="form-group">
                     <label for="reqInvoiceDate" class="control-label col-md-2">Date Payment</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInvoiceDate" class="easyui-datebox form-control" name="reqInvoiceDatePayment" value="<?=$reqInvoiceDatePayment?>" data-options="formatter:myformatter,parser:myparser" style=" width:200px" />

                                </div>
                            </div>
                        </div>
                         <label for="reqStatus" class="control-label col-md-2">Status </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqStatus"  id="reqStatus" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboStatus3'" value="<?= $reqStatus ?>" />

                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="reqTerms" class="control-label col-md-2">Deskription</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control" cols="5" rows="3" name="reqDeskriptionPayment"> <?= $reqDeskriptionPayment ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                     </div>
                     <br>
                    <table style="width: 100%" class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="80%"> File Name <a onclick="tambahPenyebab()" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>
                                <th width="10%"> Type </th>
                                <th width="10%"> Action </th>
                            </tr>
                        </thead>
                        <tbody id="tambahAttacment">
                            <?php
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
                                              <a href="uploads/invoice_payable/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                              <a onclick="openAdd(`uploads/invoice_payable/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            ?>
                                        </td>
                                        <td><?=strtoupper($ext)?></td>
                                        <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>

                        </tbody>
                    </table>



                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Project Cost</h3>
                    </div>
                    <div class="form-group">
                        <label for="reqTypeProject" class="control-label col-md-2">Type Project</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" id="reqInvoiceDetailId" name="reqInvoiceDetailId">
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqTypeProject" name="reqTypeProject" data-options="width:'300',editable:false, valueField:'id',textField:'text',url:'combo_json/comboTypeProject'" value="Project Kecil" />
                                </div>
                            </div>
                        </div>
                        <label for="reqIsAdditional" class="control-label col-md-2 project-kecil">Additional</label>
                        <div class="col-md-10 project-kecil">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input id="reqIsAdditional" name="reqIsAdditional" type="checkbox" <?=($reqIsAdditional == "1" ? "checked" : "")?>  onclick="handleAdditional(this);">
                                </div>
                            </div>
                        </div>
                        <label for="reqServiceType" class="control-label col-md-2 project-kecil non-additional">Type Of Service</label>
                        <div class="col-md-4 project-kecil non-additional">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea class="form-control tinyMCES" cols="5" rows="3" id="reqServiceType" name="reqServiceType"> </textarea>
                                </div>
                            </div>
                        </div>
                        <label for="reqVessel" class="control-label col-md-2 project-kecil non-additional">Vessel of Name</label>
                        <div class="col-md-4 project-kecil  non-additional">
                            <div class="form-group">
                                <div class="col-md-11">


                                    <input class="easyui-combobox form-control" style="width:200px" id="reqVessel" name="reqVessel" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/comboVessel?reqId=<?= $reqCompanyId ?>'" value="<?= $reqVessel ?>" />
                                    <button class="btn btn-info" type="button" onclick="edit_vessel()"><i class="fa fa-pencil fa lg"></i></button>
                                    <button class="btn btn-info" type="button" onclick="refresh_vessel()"><i class="fa fa-refresh fa lg"></i></button>
                                </div>
                            </div>
                        </div>
                        <label for="reqServiceDate" class="control-label col-md-2 project-kecil non-additional">Date of Services</label>
                        <div class="col-md-4 project-kecil non-additional">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqServiceDate" class="easyui-datebox textbox form-control" name="reqServiceDate" value="<?= $reqServiceDate ?>" style=" width:200px" />

                                </div>
                            </div>
                        </div>
                        <label for="reqDescriptionProject" class="control-label col-md-2 project-besar">Description</label>
                        <div class="col-md-10 project-besar">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea id="reqDescriptionProject" class="easyui-validatebox textbox form-control tinyMCES" name="reqDescriptionProject" style=" width:100%">
                                        
                                    </textarea>

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
                        <label for="reqDescription" class="control-label col-md-2 project-kecil non-additional">Location</label>
                        <div class="col-md-4 project-kecil non-additional">
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
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqCurrencys" name="reqCurrencys" data-options="width:'300',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar2'" value="<?= $reqCurrency ?>" />

                                </div>
                            </div>
                        </div>
                         <label for="reqStatus" style="display:none;" class="control-label col-md-2">Tanggal Status Bayar</label>
                        <div class="col-md-4" style="display:none;">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <input type="text" id="reqTglStatusDate" class="easyui-datebox form-control" name="reqTglStatusDate" value="<?=$reqTglStatusDate?>" data-options="formatter:myformatter,parser:myparser" style=" width:200px" />
                                </div>
                            </div>
                        </div>

                        <label for="reqAdditional" class="control-label col-md-2 project-kecil additional">Additional</label>
                        <div class="col-md-4 project-kecil additional">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqAdditional" class="easyui-validatebox textbox form-control" name="reqAdditional" value="<?= $reqAdditional ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqQuantity" class="control-label col-md-2 project-besar">Quantity</label>
                        <div class="col-md-4  project-besar">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqQuantity" class="easyui-validatebox textbox form-control" name="reqQuantity" value="<?= $reqQuantity ?>" style=" width:30%" onchange="numberWithCommas('reqQuantity')" onkeyup="numberWithCommas('reqQuantity')" />
                                    <span style="width: 20%"> Item </span>
                                    <input type="text" id="reqQuantityItem"   class="easyui-validatebox textbox form-control" name="reqQuantityItem" value="<?= $reqQuantityItem ?>" style=" width:50%" />
                                </div>
                            </div>
                        </div>
                        
                    </div>
                      <div class="form-group">
                        <label for="reqRemark" class="control-label col-md-2">Note </label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-11">
                                            <textarea name="reqRemarkInvoiceDetail" id="reqRemarkInvoiceDetail" class="easyui-validatebox textbox form-control tinyMCES" ><?=$reqRemarkInvoiceDetail?></textarea>                    
                                </div>
                            </div>
                        </div>
                    <br>


                    <div style="text-align:center;padding:5px">
                        <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />
                         <input type="hidden" name="totalIDR" id="totalIDR" value="" />
                         <input type="hidden" name="totalUSD" id="totalUSD" value="" />

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
                

            </div>
            <div class="page-header">
                <h3><i class="fa fa-file-text fa-lg"></i> Summary</h3>
            </div>
            <br>
            <input type="hidden" id="RP_USD" value="">
            <input type="hidden" id="RP" value="">

            <table style="width: 100%" style="font-weight: bold;">
                <td style="width: 30%" valign="top"> Total Payable </td>
                <td style="width: 20%" valign="top"><b> <b id="reqFormatUang" > RP </b> <b> 
                    <input style="width: 60%" name="totalIDR2" type="text" id="totalIDR2" class="form-control" disabled readonly  /> 
                </td>

                <td style="width: 20%" valign="top"><b> USD <b> <input style="width: 60%" type="text" id="totalUSD2" class="form-control" disabled readonly /></td>
                <td style="width: 30%" valign="top"> <div style="height:100%;width: 90%;"  id="totalWord" name="totalWord" class="form-control" ></div></td>


            </table>
            </form>
            <br>


        </div>

    </div>

    <script type="text/javascript">
        function getPPn() {


            oTable.api().ajax.reload(null,false);
        }

        function _handleAdditional(value) {
            if(value) {
                $(".additional").show()
                $(".non-additional").hide()
            } else {
                $(".additional").hide();
                $(".non-additional").show();

                 // var comboVal =  $("#reqVessel").combobox('getValue');
                 // console.log(comboVal);

            }
        }

        function handleAdditional(val) {

            _handleAdditional(val.checked);
            if(val.checked){
                 // var comboVal =  $("#reqVessel").combobox('getValue');
                 // console.log(comboVal);
            }

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
                    // console.log(data);return false;
                    var datas = data.split('-');

                    if (datas[1] == '') {
                        show_toast('info', 'Information', datas[0]);
                    } else {
                        $('#reqId').val(datas[1]);
                        reqIds = datas[1];
                        show_toast('info', 'Information', 'Header success added' + datas[0]);
                        $.messager.alertLink('Info', datas[0], 'info', "app/index/invoice_add/?reqId=" + datas[1]);
                        // $.messager.alertLink('Info', datas[0], 'info', "app/index/invoice_add/?reqId=" + datas[1]);
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
                    $("#reqCompanyName").val(datas.NAME);
                    $("#reqContactName").val(datas.CP1_NAME);
                     $(tinymce.get('reqAddress').getBody()).html(datas.ADDRESS);
                    // $("#reqAddress").val(datas.ADDRESS);
                    $("#reqTelephone").val(datas.PHONE);
                    $("#reqFaximile").val(datas.FAX);
                    $("#reqEmail").val(datas.EMAIL);
                    $("#reqNoKontrak").val(datas.CP1_TELP);

                    // tambahPenyebab2();
                    // clearFormDetil();
                });

        }
    </script>
    <script type="text/javascript">
        function editing(id) {
            console.log(oTable.fnGetData(id))

            var elements = oTable.fnGetData(id);
              
            $("#reqTypeProject").combobox('select', elements[2]);
            $("#reqQuantity").val(elements[16]);
            $("#reqQuantityItem").val(elements[17]);
            // $("#reqDescriptionProject").val(elements[3]);
              $(tinymce.get('reqDescriptionProject').getBody()).html(elements[3]);
               $(tinymce.get('reqServiceType').getBody()).html(elements[3]);
             $("textarea#reqRemarkInvoiceDetail").val(elements[22]);
            
            $("#reqInvoiceDetailId").val(elements[0]);
           // $("#reqServiceType").val(elements[3]);
            $("#reqServiceDate").datebox('setValue', elements[4]);
            $("#reqAmount").val((elements[9]));
            $("#reqLocation").val(elements[5]);
            $("#reqCurrencys").combobox('setValue', elements[10]);
            $("#reqVessel").combobox('setValue', elements[6]);
            // $("#reqTglStatusDate").datebox('setValue', elements[19]);
            $("#reqAdditional").val(elements[13]);
            
            $("#reqIsAdditional").prop('checked', elements[18] == "1");
            var comboJenis =  $("#reqTypeProject").combobox('getValue');
            
            console.log(comboJenis);
            var bolleean = false;
         
       _handleAdditional(elements[18] == "1")
      //    _handleAdditional(bolleean)
            // $("#reqDP").val(elements[10]);
               if('Project Kecil'==comboJenis){
               setProjectKecil();
            }else if('Project Besar'==comboJenis){
                  setProjectBesar();
            }
        }

        function reseti() {
            oTable.api().ajax.reload(null,false);

                  $(tinymce.get('reqDescriptionProject').getBody()).html('');
               $(tinymce.get('reqServiceType').getBody()).html('');

            $("#reqInvoiceDetailId").val('');
           // $("#reqServiceType").val('');
            $("textarea#reqRemarkInvoiceDetail").val('');
            $("#reqServiceDate").datebox('setValue', '');
            $("#reqAmount").val('');
            $("#reqLocation").val('');
            $("#reqCurrencys").combobox('select', '');
            $("#reqVessel").combobox('select', '');
            $("#reqAdditional").val('');
            $("#reqIsAdditional").prop('checked', false);
            $("#reqQuantity").val('');
            $("#reqQuantityItem").val('');
        //    // $("#reqDescriptionProject").val('');
            $("#reqTypeProject").combobox('select', 'Project Kecil');
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
                  // openAdd('app/loadUrl/report/template_report_besar_non_pajak_ind_pdf?reqId=<?= $reqId ?>&reqMode=ppn&reqBahasa=ind');
                   openAdd('app/loadUrl/report/template_report_besar_non_pajak_ind_pdf?reqId=<?= $reqId ?>&reqBahasa=ind');
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
    <?
    if ($reqInvoiceDate == "")
    { 
        ?>
        
        <?
    }
    ?>
    $('#reqInvoiceDate').datebox({
        onSelect: function(date){
            // alert(date.getFullYear()+":"+(date.getMonth()+1)+":"+date.getDate());
            ambil_interval();
        }
    }); 

    $('#reqTypeProject').combobox({
        onSelect: function(val){
            if(val){    
                if(val.id == 'Project Besar'){
                    setProjectBesar();
                } else {
                    setProjectKecil();
                }
            }
        }
    }); 

    function setProjectBesar() {
        $(".project-besar").show();
        $(".project-kecil").hide();
    }

    function setProjectKecil() {
        $(".project-besar").hide();
        $(".project-kecil").show();
        _handleAdditional(false);
    }

    function ambil_interval(){
        var tgl1 =   $('#reqInvoiceDate').datebox('getValue');
        console.log(tgl1);
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        tgl2 = mm + '/' + dd + '/' + yyyy;
        arrTgl1 = tgl1.split('-');
        tgl1 = arrTgl1[1] + '/' + arrTgl1[0] + '/' + arrTgl1[2];

        var selisih =hitungSelisihHari(tgl2,tgl1);
        if(isNaN(selisih))
            selisih = 0;
        var reqStatus =   $('#reqStatus').combobox('getValue');
        if('Lunas'!=reqStatus){
            $("#reqDaysInvoice").val(selisih);
        }

        tgl1 =   $('#reqPoDate').datebox('getValue');
        console.log(tgl1);
        today = new Date();
        dd = String(today.getDate()).padStart(2, '0');
        mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        yyyy = today.getFullYear();

        tgl2 = mm + '/' + dd + '/' + yyyy;
        arrTgl1 = tgl1.split('-');
        tgl1 = arrTgl1[1] + '/' + arrTgl1[0] + '/' + arrTgl1[2];

        selisih =hitungSelisihHari(tgl2,tgl1);
        if(isNaN(selisih))
            selisih = 0;
        reqStatus =   $('#reqStatus').combobox('getValue');
        if('Lunas'!=reqStatus){
            $("#reqDays").val(selisih);
        }
         

    }
    
    function myformatter(date){
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
    }
    function myparser(s){
        var ss = (s.split('-'));
        var y = parseInt(ss[2],10);
        var m = parseInt(ss[1],10);
        var d = parseInt(ss[0],10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
            return new Date(y,m-1,d);
        } else {
            return new Date();
        }
    }

    function FormatCurrencyWithDecimal(num) 
{
    num = Math.round(num * 100)/100;
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
    
    if(cents != "00"){
        var legCent = cents.length;
        if(legCent==1){ cents = cents+'0';}
       // if(legCent > 2 ){ cents = Math.round(cents * 100)/100 ;}
        return (((sign)?'':'-') +  num + ',' + cents);
    }
    else{
        return (((sign)?'':'-') +  num);
    }
}
</script>
 <script type="text/javascript">
        function tambahPenyebab(filename='') {
            var id = $('#tambahAttacment tr').length+1;
            $.get("app/loadUrl/app/tempalate_row_attacment?id="+id+"&filename="+filename, function(data) {
                $("#tambahAttacment").append(data);
            });
        }

        function getFileName(input, id) {
            for (var i = 0; i < input.files.length; i++) {
                if(i == 0)
                {
                    $("#namaFile"+id).html(input.files[0].name);
                    var ext = input.files[0].name.split('.').pop();
                    ext = ext.toUpperCase();
                    if(ext.length > 3) ext = '';
                    if(ext == 'PNG' || ext == 'JPG' || ext == 'JPEG' || ext == 'BMP') ext = 'IMAGE'
                    $("#namaFile"+id).parent().next().html(ext);
                }
                else
                    tambahPenyebab(encodeURIComponent(input.files[i].name))
            }
            
        }
    </script>
    <script type="text/javascript">
        function changeTax(){
             var reqTaxCheck = $("#reqTaxCheck").prop("checked");
             if(reqTaxCheck){   return;   }
            var tax = $("#reqInvoiceTax").val();
            var combTax = $("#reqTaxCodePpn").combobox('getValue');
            var lengtText = tax.length;
            var idx = tax.indexOf(combTax);
            var str = tax.slice(0,4);
            var pilih= tax.slice(4);
           
            var tampilkan =  combTax+"."+pilih;
            $("#reqInvoiceTax").val(tampilkan);
        }
    </script>

</div>
</div>