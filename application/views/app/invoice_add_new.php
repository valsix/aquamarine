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
                    


                    <div style="text-align:center;padding:5px">
                        <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />
                         <input type="hidden" name="totalIDR" id="totalIDR" value="" />
                         <input type="hidden" name="totalUSD" id="totalUSD" value="" />

                        <a href="javascript:void(0)" class="btn btn-warning" onclick="reseti()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                        <!-- <a href="javascript:void(0)" class="btn btn-danger " onclick="print_pdf()"><i class="fa fa-fw fa-print "></i> Print</a> -->
                        <!-- <a href="javascript:void(0)" class="btn btn-primary" onclick="delete_datas(<?= $reqChild ?>)"><i class="fa fa-fw fa-trash"></i> Delete</a> -->

                    </div>
                   
         
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