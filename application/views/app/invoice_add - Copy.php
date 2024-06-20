<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Invoice");
$invoice = new Invoice();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $invoice->selectByParamsMonitoring(array("A.INVOICE_ID" => $reqId));
    $invoice->firstRow();

    $reqInvoiceNumber = $invoice->getField("INVOICE_NUMBER");
    $reqInvoiceDate = $invoice->getField("INVOICE_DATE");
    $reqPpn = $invoice->getField("PPN");
    $reqCompanyName = $invoice->getField("COMPANY_NAME");
    $reqContactName = $invoice->getField("CONTACT_NAME");
    $reqAddress = $invoice->getField("ADDRESS");
    $reqTelephone = $invoice->getField("TELEPHONE");
    $reqFaximile = $invoice->getField("FAXIMILE");
    $reqEmail = $invoice->getField("EMAIL");
    $reqPpnPercent = $invoice->getField("PPN_PERCENT");
    $reqStatus = $invoice->getField("STATUS");
    $reqInvoicePo = $invoice->getField("INVOICE_PO");
    $reqInvoiceTax = $invoice->getField("INVOICE_TAX");
    $reqTerms = $invoice->getField("TERMS");
    $reqNoKontrak = $invoice->getField("NO_KONTRAK");
    $reqNoReport = $invoice->getField("NO_REPORT");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/invoice"> Invoice</a> &rsaquo; Form Invoice</div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Data</h3>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Invoice Number</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInvoiceNumber" class="easyui-validatebox textbox form-control" required="true" name="reqInvoiceNumber" value="<?= $reqInvoiceNumber ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoiceDate" class="control-label col-md-2">Invoice Date</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="date" id="reqInvoiceDate" class="easyui-validatebox textbox form-control" required="true" name="reqInvoiceDate" value="<?= $reqInvoiceDate ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqPpn" class="control-label col-md-2">PPN</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="number" id="reqPpn" class="easyui-validatebox textbox form-control" required="true" name="reqPpn" value="<?= $reqPpn ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCompanyName" class="control-label col-md-2">Company Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCompanyName" class="easyui-validatebox textbox form-control" required="true" name="reqCompanyName" value="<?= $reqCompanyName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqContactName" class="control-label col-md-2">Contact Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqContactName" class="easyui-validatebox textbox form-control" required="true" name="reqContactName" value="<?= $reqContactName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqAddress" class="control-label col-md-2">Address</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqAddress" id="reqAddress" required="true" style="width:100%"><?= $reqAddress; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqTelephone" class="control-label col-md-2">Telephone</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="number" id="reqTelephone" class="easyui-validatebox textbox form-control" required="true" name="reqTelephone" value="<?= $reqTelephone ?>" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqFaximile" class="control-label col-md-2">Faximile</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="number" id="reqFaximile" class="easyui-validatebox textbox form-control" required="true" name="reqFaximile" value="<?= $reqFaximile ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqEmail" class="control-label col-md-2">Email</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="email" id="reqEmail" class="easyui-validatebox textbox form-control" required="true" name="reqEmail" value="<?= $reqEmail ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqPpnPercent" class="control-label col-md-2">PPN Percent</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="number" id="reqPpnPercent" class="easyui-validatebox textbox form-control" required="true" name="reqPpnPercent" value="<?= $reqPpnPercent ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqStatus" class="control-label col-md-2">Status</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqStatus" class="easyui-validatebox textbox form-control" required="true" name="reqStatus" value="<?= $reqStatus ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoicePo" class="control-label col-md-2">Invoice PO</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInvoicePo" class="easyui-validatebox textbox form-control" required="true" name="reqInvoicePo" value="<?= $reqInvoicePo ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoiceTax" class="control-label col-md-2">Invoice Tax</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInvoiceTax" class="easyui-validatebox textbox form-control" required="true" name="reqInvoiceTax" value="<?= $reqInvoiceTax ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqTerms" class="control-label col-md-2">Terms</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqTerms" id="reqTerms" required="true"><?= $reqTerms; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqNoKontrak" class="control-label col-md-2">No Kontak</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="number" id="reqNoKontrak" class="easyui-validatebox textbox form-control" required="true" name="reqNoKontrak" value="<?= $reqNoKontrak ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqNoReport" class="control-label col-md-2">Report No.</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNoReport" class="easyui-validatebox textbox form-control" required="true" name="reqNoReport" value="<?= $reqNoReport ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>

        </div>

    </div>

    <script>
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/invoice_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    $.messager.alertLink('Info', data, 'info', "app/index/invoice");
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
</div>
</div>