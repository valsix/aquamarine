<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");



$this->load->model("InvoiceNew");
$this->load->model("InvoicePayable");
$invoice = new InvoiceNew();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $statement = " AND A.INVOICE_NEW_ID = " . $reqId;
    $invoice->selectByParamsMonitoring(array(), -1, -1, $statement);

$arrData = $invoice->rowResult;
$arrData = $arrData[0];
    $invoice->firstRow();
    $reqId =   $invoice->getField('INVOICE_NEW_ID');
    $reqHppProjectId =   $invoice->getField('HPP_PROJECT_ID');
    $reqInvoiceNumber =  $invoice->getField('NOMER');
    $reqCompanyId =  $invoice->getField('COMPANY_ID');
    $reqNoPo =  $invoice->getField('CODE');
    $reqInvoiceDate =  $invoice->getField('INVOICE_DATE');
    $reqDaysInvoice =  $invoice->getField('INVOICE_DAY');
    $reqPoDate =  $invoice->getField('PO_DATE');
    $reqDays =  $invoice->getField('PO_DAY');
    $reqTerms =  $invoice->getField('TERN_CONDITION');
    $reqRoCheck =  $invoice->getField('RO_CHECK');
    $reqRoNomer =  $invoice->getField('RO_NUMBER');
    $reqRoDate =  $invoice->getField('RO_DATE');
    $reqPpn =  $invoice->getField('PPN');
    $reqPpnPercent =  $invoice->getField('PPN_PERCEN');
    $reqDP =  $invoice->getField('ADV_PAYMENT');
    $reqJenisPPh =  $invoice->getField('PPH_JENIS');
    $reqPPH =  $invoice->getField('PPH');
    $reqPpnPercentPPh =  $invoice->getField('PPH_CURRENCY');
    $reqOpsiPpn =  $invoice->getField('MANUAL_PPN');
    $reqNominalPpn =  $invoice->getField('MANUAL_PPN_NOMINAL');
    $reqInvoiceDatePayment =  $invoice->getField('PAYMENT_DATE');
    $reqStatus =  $invoice->getField('STATUS_INVOICE');
    $reqDeskriptionPayment =  $invoice->getField('DESKRIPSI_PAYMENT');
    $reqDescriptionProject =  $invoice->getField('DESKRIPSI');
    $reqAmount =  $invoice->getField('AMOUNT');
    $reqCurrencys =  $invoice->getField('CURRENCY');
    $reqQuantity =  $invoice->getField('QUANTITY');
    $reqRemarkInvoiceDetail =  $invoice->getField('NOTE');
    $reqTaxCodePpn=  $invoice->getField('CODE_TAX');
    $reqInvoiceTax  =  $invoice->getField('TAX_INVOICE_NOMINAL');
    $reqTaxCheck  =  $invoice->getField('TAX_INVOICE');
    $reqQuantityItem = $invoice->getField('ITEM');
      $reqDari = $invoice->getField('DARI');
    $reqCertificatePath = $invoice->getField('LAMPIRAN');
    $reqNoOrder = $invoice->getField("NO_PO");
     $reqDestination= $invoice->getField("LOCATION");
      $reqProjectName = $invoice->getField("NAMA_CODE_PROJECT");



     
        $totalAmount = ($arrData['amount']*$arrData['quantity']);
       
}

?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />
<style type="text/css">
    .inputRight { 
    text-align: right; 
}
</style>


<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/invoice_news">Invoice</a> &rsaquo; Form Invoice
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

                                 <button type="button" class="btn btn-default pull-right" style="margin-right: 10px" onclick='openAdd("report/index/template_report_besar_non_pajak_news_pdf?reqId=<?=$reqId?>")' >
                                        <i class="fa fa-file-pdf-o "  > </i> Print Invoice
                                    </button>
                            <div class="btn-group pull-right " style="margin-right: 10px">


                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-file-pdf-o "> </i> Print Lampiran
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="javascript:void(0)" onclick='openAdd("report/index/template_report_hpp_new_pemasukan_pdf?reqId=<?=$reqHppProjectId?>")' ><i class="fa fa-file-pdf-o "> </i> PRINT INVOICE</a></li>
                                        <!-- <li><a href="javascript:void(0)" id="ProjectBesarNonPajakEng"><i class="fa fa-file-pdf-o "> </i> Project Besar non pajak</a></li> -->
                                        <li><a href="javascript:void(0)" onclick='openAdd("report/index/template_report_hpp_new_pemasukan2_pdf?reqId=<?=$reqHppProjectId?>")' ><i class="fa fa-file-pdf-o "> </i> PRINT INV. MOB</a></li>
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
                        <label for="reqNoPo" class="control-label col-md-2">Code Project.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNoPo" class="easyui-validatebox textbox form-control" name="reqNoPo" value="<?= $reqNoPo ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="form-group">
                    <label for="reqName" class="control-label col-md-2">No Po.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="hidden" class="easyui-validatebox textbox form-control" name="reqHppProjectId" id="reqHppProjectId" value="<?= $reqHppProjectId ?>" style=" width:100%"  />
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqNoOrder" id="reqNoOrder" value="<?= $reqNoOrder ?>" style=" width:100%"  />
                                      <?
                                        if(empty($reqDari)){
                                        ?>
                                    <button type="button" class="btn btn-default pull-right" onclick="pilih_project()">...</button>
                                        <?
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                         <label for="reqNoPo" class="control-label col-md-2">Location</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDestination" class="easyui-validatebox textbox form-control" name="reqDestination" value="<?= $reqDestination ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                       <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Project Name</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqProjectName" class="easyui-validatebox textbox form-control" name="reqProjectName" value="<?= $reqProjectName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Company Relocation</h3>
                    </div>
                      <?
                      $arrValLink =array("ID"=>$reqCompanyId,"MODE"=>'',"OPEN"=>"no",'MODUL'=>'COSTUMER','DARI'=>$reqDari);
                      $this->load->view("app/company_form",$arrValLink);
                      ?>
                          <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Invoice Form</h3>
                    </div>
                  
                      <div class="form-group">
                    <label for="reqInvoiceTax" class="control-label col-md-2">Tax Invoice</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <?
                                   $checkTax='';
                                   if($reqTaxCheck==1){
                                    $checkTax = 'checked';
                                   }
                                   ?>
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqTaxCodePpn" name="reqTaxCodePpn"  data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'web/invoice_json/comboTax'" value="<?=$reqTaxCodePpn?>" />
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
                                    <input type="text" onkeypress='validate(event)' id="reqDaysInvoice" class="easyui-validatebox textbox form-control readonly" name="reqDaysInvoice" value="<?=$reqDaysInvoice?>" style=" width:40%" />
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
                     <div class="form-group">
                        <?
                        $checks='';
                        if($reqPpn=='1'){
                            $checks='checked';
                        }
                        ?>
                        <label for="reqStatus" class="control-label col-md-2">Ppn <input type="checkbox" <?= $checks ?> name="reqPpn" id="reqPpn" onchange="getPPn()" onkeyup="getPPn()" value="1" /> </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" maxlength="4"  onkeypress='validate(event)' class=" form-control" id="reqPpnPercent" name="reqPpnPercent" value="<?= $reqPpnPercent ?>" style=" width:20%"  /> % <strong> Currency Ppn</strong>

                                </div>
                            </div>
                        </div>
                        <label for="reqDP" class="control-label col-md-2 project-kecil">Advance Payment</label>
                        <div class="col-md-4 project-kecil">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqDP" class="easyui-validatebox textbox form-control numberWithCommas" name="reqDP" value="<?= currencyToPage2($reqDP) ?>" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <?
                        if($reqPPH==1){$checksPPh='checked';}
                        ?>
                        <label for="reqStatus" class="control-label col-md-2"><input class="easyui-combobox form-control" style="width:100%" name="reqJenisPPh" data-options="width:'100',editable:false, valueField:'id',textField:'text',url:'combo_json/comboStatusPph'" value="<?=$reqJenisPPh?>" /> <input type="checkbox" <?= $checksPPh ?> name="reqPPH" id="reqPPH" onchange="getPPn()" onkeyup="getPPn()" value="1" /> </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" maxlength="4" class=" form-control" id="reqPpnPercentPPh" name="reqPpnPercentPPh" onkeypress='validate(event)' value="<?= $reqPpnPercentPPh ?>" style=" width:20%" onchange="getPPn()" onkeyup="getPPn()" /> % <strong> Currency PPh</strong>

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
                                    <th width="90%"> File Name <a onclick="addCerificate()" id="addCerificate" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>

                                    <th width="10%"> Action </th>
                                </tr>
                            </thead>
                            <tbody id="tambahCerificate">
                                <?
                                $files_data = explode(';',  $reqCertificatePath);
                                $ll=0;
                                for ($i = 0; $i < count($files_data); $i++) {
                                    if (!empty($files_data[$i])) {
                                        $texts = explode('-', $files_data[$i]);
                                        $ext = substr($files_data[$i], -3);
                                        $ll++;
                                ?>
                                        <tr>

                                            <td>
                                                <input type="file" onchange="getFileNameCertificate(this, '<?=($i+1)?>')" name="reqLinkFileCertificate[]" multiple class="form-control" style="width: 90%">
                                                <input type="hidden" name="reqLinkFileCertificateTemp[]" value="<?= $files_data[$i] ?>">
                                                <?if ($ext !=='pdf')
                                                {
                                                ?>
                                                  <a href="uploads/so_team_new/<?= $reqSoTeamId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileCertificate<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                  <a onclick="openAdd(`uploads/so_team_new/<?= $reqSoTeamId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileCertificate<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                <?
                                                }
                                                ?>
                                            </td>

                                            <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                        </tr>
                                <?
                                    }
                                }
                                if($ll==0){
                                ?>
                                <tr>
                                  <td colspan="2" align="center" id='reqJumlahLampir'> No Display Record   </td>
                                </tr>
                                <?  
                                }
                                ?>

                            </tbody>
                        </table>

                         <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Project Cost</h3>
                    </div>
                    <div class="form-group">
                     <label for="reqDescriptionProject" class="control-label col-md-2 project-besar">Description</label>
                        <div class="col-md-10 project-besar">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea id="reqDescriptionProject" class="easyui-validatebox textbox form-control tinyMCES" name="reqDescriptionProject" style=" width:100%">
                                        <?=$reqDescriptionProject?>
                                    </textarea>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                     <label for="reqAmount" class="control-label col-md-2">Amount</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqAmount" class="easyui-validatebox textbox form-control numberWithCommas" name="reqAmount" value="<?= currencyToPage2($reqAmount) ?>" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                         <label for="reqStatus" class="control-label col-md-2">Currency</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqCurrencys" name="reqCurrencys" data-options="width:'300',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar2'" value="<?= $reqCurrencys ?>" />

                                </div>
                            </div>
                        </div>
                          <div class="form-group">
                         <label for="reqQuantity" class="control-label col-md-2 project-besar">Quantity</label>
                        <div class="col-md-4  project-besar">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqQuantity" class="easyui-validatebox textbox form-control numberWithCommas" name="reqQuantity" value="<?= $reqQuantity ?>" style=" width:30%"  />
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
                    </div>
                     </div>   

                     <?
                     $this->load->model("MasterCurrency");
$currencyId = $arrData['currency'];
$master_currency = new MasterCurrency();
$statement = " AND CAST(A.MASTER_CURRENCY_ID AS VARCHAR) ='".$currencyId."'";
$master_currency->selectByParamsMonitoring(array(),-1,-1,$statement);

$master_currency->firstRow();
$reqGlobalCurrencyNama = $master_currency->getField('NAMA');
$reqCurFormat = $master_currency->getField('FORMAT');

if($currencyId=='1' || empty($currencyId)){
  $reqGlobalCurrencyNama = 'USD';
}

                $currency = $arrData['currency'];
                
                
                if($currency == '1') {$currency ='IDR'; }else{$currency = 'USD';}
                $val[$currency]['total']= currencyToPage2($totalAmount);
                $val[$currency]['adv_payment']='-'. currencyToPage2($arrData['adv_payment']);
                $nilai_ppn =0;
                $PPN = $arrData['ppn'];
                $PPN_PERCENT = $arrData['ppn_percen'];

                if($PPN==1){
                    $nilai_ppn = ( $totalAmount * $PPN_PERCENT ) /100;
                }else { $PPN_PERCENT=0;}    
                 $val[$currency]['nilai_ppn']= currencyToPage2($nilai_ppn);
                 $PPH = $arrData['pph'];
                 $PPH_CURRENCY = $arrData['pph_currency'];
                 $nilai_pph=0;
                 if($PPH==1){
                    $nilai_pph = ( $totalAmount * $PPH_CURRENCY ) /100;
                }else { $PPN_PERCENT=0;}    
                $val[$currency]['nilai_pph']= currencyToPage2($nilai_pph);

                $PPH_JENIS = $arrData['pph_jenis'];

                $total_payable = ( $totalAmount  + $nilai_ppn + $nilai_pph) - $arrData['adv_payment'];
                $val[$currency]['total_payable']= currencyToPage2($total_payable);

                $angka =round($total_payable,2);
                $arrData2 = explode('.',$angka);
                $reqNominal= $arrData2[0];
                $reqCent= $arrData2[1];

            
                

                $reqTextNominal=kekata_eng($reqNominal).' '.$reqCurFormat;
                if(!empty($reqCent)){
                    if(strlen($reqCent)==1){
                        $reqCent .='0';
                    }
                    $reqTextNominal .=' and '.kekata_eng($reqCent).' Cent';
                }else{
                    $reqTextNominal .=' Only ';
                }

                ?>
                <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Invoice </h3>
                    </div>
                    
                <div class="col-md-12" style="padding: 10px">
                   <table  style="width: 100%" style="padding-right: 10px" >
                     <td align="right" width="75%" >  </td>
                     <td width="5%" rowspan="2"> :   </td>
                     <td align="center"  width="10%"><b> USD </b> </td>
                     <td align="center" width="10%"><b>  IDR <b></td>
                        <tr>
                            <td align="right" width="75%" >  <b>  TOTAL </b> </td>

                            <td align="right"> <input type="text" class="form-control inputRight" value="<?=$val['USD']['total']?>"/> </td>
                            <td align="right">  <input type="text" class="form-control inputRight" value="<?=$val['IDR']['total']?>"/> </td>
                        </tr>
                        <tr>
                            <td  align="right"><b> ADVANCE PAYMENT </b> </td>
                            <td> : </td>
                            <td align="right"> <input type="text" class="form-control inputRight" value="<?=$val['USD']['adv_payment']?>"/> </td>
                            <td align="right">  <input type="text" class="form-control inputRight" value="<?=$val['IDR']['adv_payment']?>"/> </td>
                        </tr>
                        <tr>
                            <td  align="right"><b> PPN ( <?=$PPN_PERCENT?> ) %  </b></td>
                            <td> : </td>
                            <td align="right"> <input type="text" class="form-control inputRight" value="<?=$val['USD']['nilai_ppn']?>"/> </td>
                            <td align="right">  <input type="text" class="form-control inputRight" value="<?=$val['IDR']['nilai_ppn']?>"/> </td>
                        </tr>
                        <tr>
                            <td  align="right"><b> PPH (  <?=$PPH_CURRENCY?> ) </b> </td>
                            <td> : </td>
                            <td align="right"> <input type="text" class="form-control inputRight" value="<?=$val['USD']['nilai_pph']?>"/> </td>
                            <td align="right">  <input type="text" class="form-control inputRight" value="<?=$val['IDR']['nilai_pph']?>"/> </td>
                        </tr>
                        <tr>
                            <td  align="right"><b> TOTAL PAYABLE  </b> </td>
                            <td> : </td>
                            <td align="right"> <input type="text" class="form-control inputRight" value="<?=$val['USD']['total_payable']?>"/> </td>
                            <td align="right">  <input type="text" class="form-control inputRight" value="<?=$val['IDR']['total_payable']?>"/> </td>
                        </tr>
                        <tr>
                        <td colspan="4" align="right"> <b> The Sum of :</b> ## <?=$reqTextNominal?> ##  </td>
                    
                    </tr>

                    </table>
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

  
    


    <script>
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/invoice_new_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    // console.log(data);return false;
                    var datas = data.split('-');

                    if (datas[0] == '') {
                        show_toast('info', 'Information', datas[0]);
                    } else {
                        $('#reqId').val(datas[0]);
                        reqIds = datas[0];
                        show_toast('info', 'Information', 'Header success added' + datas[0]);
                        $.messager.alertLink('Info', datas[1], 'info', "app/index/invoice_new_add/?reqId=" + datas[0]);
                        // $.messager.alertLink('Info', datas[0], 'info', "app/index/invoice_add/?reqId=" + datas[1]);
                    }
                    // reseti();


                }
            });
        }
    </script>
  <script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript">
         $('#reqInvoiceDate,#reqPoDate').datebox({
        onSelect: function(date){
            // alert(date.getFullYear()+":"+(date.getMonth()+1)+":"+date.getDate());
            ambil_interval();
        }
    }); 
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
    </script>

<script type="text/javascript">
    function pilih_project() {
            openAdd('app/loadUrl/app/template_load_hpp_project');
        }
         function pilih_hpp(vdata){
            var id = vdata[1];
            $.get("web/project_hpp_new_json/ambil_detail?&reqId=" + id,
                function(data) {
                   
                  var obj = JSON.parse(data);
                  
                  $('#reqCompanyId').val(obj['company_id']);
                  $('#reqCompanyName').val(obj['nama_company']);
                  $('#reqContactPerson').val(obj['cp1_name']);
                  $('#reqNoOrder').val(obj['no_po_contract']);
                  $('#reqDestination').val(obj['lokasi']);
                  $('#reqProjectName').val(obj['nama_project2']);
                  $('#reqInvoiceNumber').val(obj['nomer']);
                     
                     
                  
                  
                });

        }
</script>
   
   <script type="text/javascript">
      function addCerificate(filename='') {
        $('#reqJumlahLampir').remove();
            var id = $('#tambahCerificate tr').length+1;
            var data = `<tr>
                <td><input type="file" onchange="getFileNameCertificate(this, '${id}')" name="reqLinkFileCertificate[]" multiple id="reqLinkFileCertificate${id}" class="form-control">
                  <input type="hidden" name="reqLinkFileCertificateTemp[]" id="reqLinkFileCertificateTemp${id}" value="">
                  <span style="margin-left: 50px" class="nama-temp-file" id="namaFileCertificate${id}">${filename}</span>
                </td>

                <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
            </tr>
            `;
            $("#tambahCerificate").append(data);
        }

        function getFileNameCertificate(input, id) {
            for (var i = 0; i < input.files.length; i++) {
                if(i == 0)
                    $("#namaFileCertificate"+id).html(input.files[0].name);
                else
                    addCerificate((input.files[i].name))
            }
            
        }

    </script>
    <script type="text/javascript">
         $(document).ready(function() { 
       
        $(".numberWithCommas").on("keyup change", function(e) {
          var id = $(this).attr('id');
          numberWithCommas(id);
          });
          });
    </script>
</div>
</div>