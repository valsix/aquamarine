<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("EquipmentList");
$equipment_list = new EquipmentList();
$this->load->model("EquipRepair");
$this->load->model("Company");


$reqId = $this->input->get("reqId");
$reqPicPath = 'images/icon-user-login.png';
if ($reqId == "") {
    $reqMode = "insert";
  $reqEquipId =  $equipment_list->getNextId("EQUIP_ID", "EQUIPMENT_LIST");
} else {
    $reqMode = "ubah";

    $equipment_list->selectByParamsMonitoring(array("A.EQUIP_ID" => $reqId));
    $equipment_list->firstRow();
    $reqEquipId                    = $equipment_list->getField("EQUIP_ID");
    $reqEquipParentId              = $equipment_list->getField("EQUIP_PARENT_ID");
    $reqEcId                       = $equipment_list->getField("EC_ID");
    $reqEquipName                  = $equipment_list->getField("EQUIP_NAME");
    $reqEquipQty                   = $equipment_list->getField("EQUIP_QTY");
    $reqEquipItem                  = $equipment_list->getField("EQUIP_ITEM");
    $reqEquipSpec                  = $equipment_list->getField("EQUIP_SPEC");
    $reqEquipSN                    = $equipment_list->getField("SERIAL_NUMBER");
    $reqEquipDatein                = $equipment_list->getField("EQUIP_DATEIN");
    $reqEquipLastcal               = $equipment_list->getField("EQUIP_LASTCAL");
    $reqEquipNextcal               = $equipment_list->getField("EQUIP_NEXTCAL");
    $reqEquipCondition             = $equipment_list->getField("EQUIP_CONDITION");
    $reqEquipStorage               = $equipment_list->getField("EQUIP_STORAGE");
    $reqEquipRemarks               = $equipment_list->getField("EQUIP_REMARKS");
    $reqBarcode                     = $equipment_list->getField("BARCODE");
    $reqEquipPrice                 = $equipment_list->getField("EQUIP_PRICE");
    $reqPicPaths                   = $equipment_list->getField("PIC_PATH");
    $reqStrorageId =$equipment_list->getField("STORAGE_ID");
    if (!empty($reqPicPaths)) {
        $reqPicPath = 'uploads/equipment/' . $reqPicPaths;
    }
    
    $reqCertificateId              = $equipment_list->getField("CERTIFICATE_ID");
    $reqCertificateName            = $equipment_list->getField("CERTIFICATE_NAME");
    $reqCertificateDescription     = $equipment_list->getField("CERTIFICATE_DESCRIPTION");
    $reqCertificatePath            = $equipment_list->getField("CERTIFICATE_PATH");
    $reqCertificateIssueDate       = $equipment_list->getField("CERTIFICATE_ISSUED_DATE");
    $reqCertificateExpiredDate     = $equipment_list->getField("CERTIFICATE_EXPIRED_DATE");
    $reqCertificateSurveyor        = $equipment_list->getField("CERTIFICATE_SURVEYOR");
    $reqInvoiceNumber              = $equipment_list->getField("INVOICE_NUMBER");
    $reqInvoiceDescription         = $equipment_list->getField("INVOICE_DESCRIPTION");
    $reqInvoicePath                = $equipment_list->getField("INVOICE_PATH");
    $reqVasselCurrency             = $equipment_list->getField("CURRENCY"); 
}
?>

<!--// plugin-specific resources //-->
<!-- <script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" /> -->
<style type="text/css">
    #tableis {
        background-color: white;
        padding: 10px;
        border-radius: 25px;
    }

    #tableis tr td {
        padding: 10px;

        font-weight: bold;
        color: black;
    }
</style>
<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/equipment"> Equipment</a> &rsaquo; Form Equipment
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="add_equipment()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Kategori Equip </span> </a>
    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Equipment Entry
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>

                    <br>

                    <table style="width: 100%" id="tableis">
                        <tr>
                            <td style="width: 10%; text-align: right;"> Category </td>
                            <td style="width: 30%">
                                <input class="easyui-combobox form-control" style="width:100%" name="reqEcId" data-options="width:'290',editable:false, valueField:'id',textField:'text',url:'combo_json/comboEquipCategori'" value="<?= $reqEcId ?>" />

                                <input type="hidden" value="<?= $reqId ?>" name="reqIds" id="reqIds">
                            </td>

                            <td style="width: 20%" rowspan="6" valign="top">
                                <div style="background: white;height: auto;color: black;height: 360px;width: 440px;border: 1px solid black;padding: 20px">
                                    <img id="imgLogo" src="<?= $reqPicPath ?>" style="height: 100%;width: 100%">

                                </div>
                                <input type="file" id="reqFilesName" name="reqFilesName[]" class="form-control" style="width: 60%" accept="image/*">
                                <input type="hidden" name="reqFilesNames" value="<?= $reqPicPath ?>">
                                


                            </td>
                          

                        </tr>
                         
                        <tr>
                            <td style="text-align: right;"> Equipment ID </td>
                            <td><input type="text" class="easyui-validatebox textbox form-control" value="<?= $reqEquipId ?>" style=" width:90%" disabled readOnly /> </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Barcode </td>
                            <td><input type="text" class="easyui-validatebox textbox form-control" value="<?= $reqBarcode ?>" style=" width:90%" name="reqBarcode" disabled readonly /> </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Name of Equipment </td>
                            <td>
                                 <input class="easyui-combobox form-control easyui-textbox" style="width:100%" id="reqEquipName" name="reqEquipName" data-options="width:'290',editable:true, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_ambil_name?reqModul=EQUIPMENT'" value="<?= $reqEquipName ?>" />
                                <!-- <input type="text" id="reqItem" class="easyui-validatebox textbox form-control" name="reqEquipName" value="<?= $reqEquipName ?>" style=" width:90%" /> </td> -->
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Part of Equipment </td>
                            <td><input class="easyui-combobox form-control" style="width:100%" name="reqEquipParentId" data-options="width:'300',editable:true, valueField:'id',textField:'text',url:'combo_json/comboEquipList'" value="<?= $reqEquipParentId ?>" /> </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Serial No. </td>
                            <td><input type="text" id="reqEquipSN" class="easyui-validatebox textbox form-control" name="reqEquipSN" value="<?= $reqEquipSN ?>" style=" width:90%" /> </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;" valign="top"> Specification </td>
                            <td valign="top"><textarea type="text" id="reqEquipSpec" class="form-control "  name="reqEquipSpec"  style=" width:90%" ><?= $reqEquipSpec ?></textarea> </td>
                             <td rowspan="4">
                                <?
                                if(!empty( $reqBarcode)){
                                ?>
                                
                                <div style="background: white;height: auto;color: black;height: 260px;width: 260px;border: 1px solid black;padding: 20px">
                                    <img id="imgLogo" src="uploads/equip_barcode/<?= $reqId ?>.png" style="height: 100%;width: 100%">

                                </div>
                                <?
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Quantity </td>
                            <td><input type="text" id="reqEquipQty" class="easyui-validatebox textbox form-control" name="reqEquipQty" value="<?= $reqEquipQty ?>" onkeypress='validate(event)' style=" width:90%" /> </td>
                        </tr>

                        <tr>
                            <td style="text-align: right;"> Incoming Date </td>
                            <td> <input type="text" class="easyui-datebox textbox form-control" name="reqEquipDatein" id="reqEquipDatein" value="<?= $reqEquipDatein ?>" style=" width:170px" /> </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Last Calibaration </td>
                            <td> <input type="text" class="easyui-datebox textbox form-control" name="reqEquipLastcal" id="reqEquipLastcal" value="<?= $reqEquipLastcal ?>" style=" width:170px" /></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Next Calibration </td>
                            <td> <input type="text" class="easyui-datebox textbox form-control" name="reqEquipNextcal" id="reqEquipNextcal" value="<?= $reqEquipNextcal ?>" style=" width:170px" /> </td>
                        </tr>

                        <tr>
                            <td style="text-align: right;"> Item </td>
                            <td>

                                 <input class="easyui-combobox form-control easyui-textbox" style="width:100%" id="reqEquipItem" name="reqEquipItem" data-options="width:'290',editable:true, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_ambil_name?reqModul=ITEM'" value="<?= $reqEquipItem ?>" />
                               <!--  <input type="text" id="reqEquipItem" class="easyui-validatebox textbox form-control" name="reqEquipItem" value="<?= $reqEquipItem ?>" style=" width:90%" /> </td> -->
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Condition </td>
                            <td>
                                  <input class="easyui-combobox form-control" style="width:100%" id="reqEquipCondition" name="reqEquipCondition" data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/equipmentStatus'" value="<?= $reqEquipCondition ?>" />


                        </tr>

                        <tr>
                            <td style="text-align: right;"> Storage </td>
                            <td>

                                 <input class="easyui-combobox form-control easyui-textbox" style="width:100%" id="reqEquipStorage" name="reqEquipStorage" data-options="width:'290',editable:true, valueField:'id',textField:'text',kodeField:'kode',url:'web/combo_baru_json/combo_ambil_name_storage'" value="<?=$reqStrorageId ?>" />
                               
                                <!-- <input type="text" id="reqEquipStorage" class="easyui-validatebox textbox form-control" name="reqEquipStorage" value="<?= $reqEquipStorage ?>" style=" width:90%" /> -->
                                 </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"> Price </td>
                            <td>
                                   <input class="easyui-combobox form-control" style="width:100%" id="reqVasselCurrency" name="reqVasselCurrency" data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="<?= $reqVasselCurrency ?>" />
                                <input type="text" id="reqEquipPrice" class="easyui-validatebox textbox form-control" name="reqEquipPrice" value="<?= currencyToPage2($reqEquipPrice) ?>" 
                                onchange="numberWithCommas('reqEquipPrice')" onkeyup="numberWithCommas('reqEquipPrice')"

                                style=" width:70%" /> </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;" valign="top"> Remarks </td>
                            <td valign="top" ><textarea type="text" rows="3" id="reqEquipRemarks" class="form-control "  name="reqEquipRemarks"  style=" width:90%" ><?= $reqEquipRemarks ?></textarea> </td>
                           
                        </tr>

                    </table>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Equipment History
                        </h3>
                    </div>

                    <div style="padding: 10px;height: 250px;overflow: auto;">
                        <table style="width: 100%" class="table">
                            <thead>
                                <tr>
                                    <th> Name of Project </th>
                                    <th> Name of Company </th>
                                    <th> Name of Vessel </th>
                                    <th> Date of Service </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <?php
                            $paramsArray = array("A.EQUIP_ID" => "0");
                            if($reqId != ""){
                                $paramsArray = array("A.EQUIP_ID" => $reqId);
                            }
                            $equipment_list->selectByParamsHistory($paramsArray,-1,-1,''," ORDER BY B.SO_ID DESC  ");
                            // $equipment_list->selectByParamsHistory();
                            // echo $equipment_list->query;
                            while ($equipment_list->nextRow()) {
                            ?> 
                                <tr>
                                    <td><?=$equipment_list->getField("PROJECT_NAME")?></td>
                                    <td><?=$equipment_list->getField("COMPANY_NAME")?></td>
                                    <td><?=$equipment_list->getField("VESSEL_NAME")?></td>
                                    <td><?=$equipment_list->getField("DATE_OF_SERVICE")?></td>
                                </tr>
                            <?php
                            }
                            ?>
                                
                            </tbody>
                        </table>
                    </div>


                       <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Equipment Repair <a onclick="addRepair()" id="addInvoice" class="btn btn-info"><i class="fa fa-plus-square"></i></a>
                        </h3>
                    </div>
                     <table style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                 <th> No </th>
                                 <th> Repair By </th>
                                 <th> Tanggal Repair </th>
                                 <th> Tanggal Selesai </th>
                                 <th> Keterangan </th>
                                 <th> Aksi </th>
                             </tr>
                            </thead>
                            <tbody >
                                <?
                                    $equip_repair = new EquipRepair();
                                    $equip_repair->selectByParamsMonitoring(array("CAST(A.EQUIP_ID AS VARCHAR)"=>$reqId));
                                    $no=1;
                                    while($equip_repair->nextRow()){
                                       $reqEquipRepairId   = $equip_repair->getField("EQUIP_REPAIR_ID");
                                       $reqEquipIdx         = $equip_repair->getField("EQUIP_ID");
                                       $reqRepairBy            = $equip_repair->getField("REPAIR_BY");
                                       $reqDateAwal     = $equip_repair->getField("TANGGAL_AWAL");
                                       $reqDateAkhir    = $equip_repair->getField("TANGGAL_AKHIR");
                                       $reqKeteranganRepair          = $equip_repair->getField("KETERANGAN");
   

                                ?>
                                    <tr>
                                        <td> <?=$no?> </td>
                                        <td> <?=$reqRepairBy?> </td>
                                        <td> <?=$reqDateAwal?> </td>
                                        <td> <?=$reqDateAkhir?> </td>
                                            <td> <?=$reqKeteranganRepair?> </td>
                                            <td>  
                                                 <a class="btn btn-primary" onclick="editRepair(<?=$reqEquipRepairId?>)"><i class="fa fa-pencil fa-lg"></i></a>
                                                <a class="btn btn-danger" onclick="deleteRepair(<?=$reqEquipRepairId?>)"><i class="fa fa-trash fa-lg"></i></a>
                                            </td>
                                    </tr>    

                                <?        
                                    $no++;
                                    }
                                ?>

                            </tbody>
                        </table>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>Equipment Certificate</h3>
                    </div>

                    <div class="form-group">
                        <label for="reqCertificate" class="control-label col-md-2">Type of Certificate</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqCertificateId" data-options="width:'230',editable:false, valueField:'id',textField:'text',url:'combo_json/comboCertificate'" value="<?= $reqCertificateId ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCertificateName" class="control-label col-md-2">Name of Certificate</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCertificateName" class="easyui-validatebox textbox form-control" name="reqCertificateName" value="<?= $reqCertificateName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCertificateDescription" class="control-label col-md-2">Description</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" rows="3" cols="2" id="reqCertificateDescription" class=" textbox form-control" name="reqCertificateDescription" style=" width:100%"><?= $reqCertificateDescription ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCertificateIssueDate" class="control-label col-md-2">Issued Date </label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCertificateIssueDate" class="easyui-datebox textbox form-control" name="reqCertificateIssueDate" value="<?= $reqCertificateIssueDate ?>" style=" width:190px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCertificateExpiredDate" class="control-label col-md-2">Expired Date </label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCertificateExpiredDate" class="easyui-datebox textbox form-control" name="reqCertificateExpiredDate" value="<?= $reqCertificateExpiredDate ?>" style=" width:190px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCertificateSurveyor" class="control-label col-md-2">Name of Surveyor </label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCertificateSurveyor" class="easyui-validatebox textbox form-control" name="reqCertificateSurveyor" value="<?= $reqCertificateSurveyor ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div style="padding: 10px">
                        
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
                                for ($i = 0; $i < count($files_data); $i++) {
                                    if (!empty($files_data[$i])) {
                                        $texts = explode('-', $files_data[$i]);
                                        $ext = substr($files_data[$i], -3);
                                ?>
                                        <tr>

                                            <td>
                                                <input type="file" onchange="getFileNameCertificate(this, '<?=($i+1)?>')" name="reqLinkFileCertificate[]" multiple class="form-control" style="width: 90%">
                                                <input type="hidden" name="reqLinkFileCertificateTemp[]" value="<?= $files_data[$i] ?>">
                                                <?if ($ext !=='pdf')
                                                {
                                                ?>
                                                  <a href="uploads/equipment_cerificate/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileCertificate<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                  <a onclick="openAdd(`uploads/equipment_cerificate/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileCertificate<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                <?
                                                }
                                                ?>
                                            </td>

                                            <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                        </tr>
                                <?
                                    }
                                }
                                ?>

                            </tbody>
                        </table>

                    </div>





                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Nomor</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqInvoiceNumber" class="easyui-validatebox textbox form-control" name="reqInvoiceNumber" value="<?= $reqInvoiceNumber ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoiceDescription" class="control-label col-md-2">Description</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea type="text" rows="3" cols="2" id="reqInvoiceDescription" class=" textbox form-control" name="reqInvoiceDescription" style=" width:100%"><?= $reqInvoiceDescription ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>List Supplier</h3>
                    </div>
                    <table class="table" id='example2'>
                        <thead>
                            <tr>
                                <th> No </th>
                                <th> Vendor </th>
                                <th> Alamat </th>
                                <th> Contanct </th>
                                <th> Telp </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                            $statement = " AND EXISTS(
                            SELECT 1 FROM SUPPLIER_BARANG CC WHERE CC.SUPPLIER_ID = A.COMPANY_ID AND UPPER(CC.NAMA) LIKE '%".strtoupper($reqEquipName). "%'
                            )";
                            if(empty($reqId) && empty($reqEquipName)){
                                $statement = ' AND 2=3';
                            }
                            $company = new Company();
                            $company->selectByParamsMonitoring(array('A.KATEGORI'=>'SUPPLIER'),-1,-1,$statement);
                            $arrDataSup =$company->rowResult;
                            $no=1;
                            foreach ($arrDataSup as  $value) {
                           ?>
                           <tr>
                            <td> <?=$no?> </td>
                            <td> <?=$value['name']?> </td>
                             <td> <?=$value['address']?> </td>
                             <td> <?=$value['cp1_name']?> </td>
                              <td> <?=$value['cp1_telp']?> </td>
                           </tr>
                           <?
                           $no++;    
                            }
                            ?>
                        </tbody>
                     </table>

                    <br>
                    <div style="padding: 10px">
                        
                        <table style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="90%"> File Name <a onclick="addInvoice()" id="addInvoice" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>

                                    <th width="10%"> Action </th>
                                </tr>
                            </thead>
                            <tbody id="tambahInvoice">
                                <?
                                $files_data = explode(';',  $reqInvoicePath);
                                for ($i = 0; $i < count($files_data); $i++) {
                                    if (!empty($files_data[$i])) {
                                        $texts = explode('-', $files_data[$i]);
                                        $ext = substr($files_data[$i], -3);
                                ?>
                                        <tr>

                                            <td>
                                                <input type="file" onchange="getFileNameInvoice(this, '<?=($i+1)?>')" name="reqLinkFileInvoice[]" multiple class="form-control" style="width: 90%">
                                                <input type="hidden" name="reqLinkFileInvoiceTemp[]" value="<?= $files_data[$i] ?>">
                                                <?if ($ext !=='pdf')
                                                {
                                                ?>
                                                  <a href="uploads/equipment_invoice/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileInvoice<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                  <a onclick="openAdd(`uploads/equipment_invoice/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileInvoice<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                <?
                                                }
                                                ?>
                                            </td>

                                            <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                        </tr>
                                <?
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
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

        function submitForm(){
           var test = $('#reqEquipName').combobox('getText');
           $('#reqEquipName').combobox('setValue',test);
            var test = $('#reqEquipItem').combobox('getText');
           $('#reqEquipItem').combobox('setValue',test);

           //  var test = $('#reqEquipStorage').combobox('getText');
           // $('#reqEquipStorage').combobox('setValue',test);
            
           //  $('#reqEquipStorageId').val(); 
           submitForm2();
              
        }
        function submitForm2() {
            $('#ff').form('submit', {
                url: 'web/equipment_json/add_new',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');
                    //alert(data);
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/equipment_add?reqId=" + datas[0]);
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            oTable2= $('#example2').dataTable();
            $("#reqFilesName").change(function() {
                 // $.messager.alert('Info', this.files[0].size+' File Gambar terlalu besar', 'info');
               if(this.files[0].size > 148999){
                $.messager.alert('Info', 'File Gambar terlalu besar', 'info');
                 this.value = "";
             }else{
                 readURL(this);
             }
               
            });

        });
    </script>
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    console.log(e.target.result);
                    //alert(e.target.result);
                    $('#imgLogo').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        function add_equipment(){
             openAdd('app/loadUrl/app/tempalate_master_kategori_equip');
            
        }
    </script>

    <script type="text/javascript">
        function addCerificate(filename='') {
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

        function addInvoice(filename='') {
            var id = $('#tambahInvoice tr').length+1;
            var data = `<tr>
                <td><input type="file" onchange="getFileNameInvoice(this, '${id}')" name="reqLinkFileInvoice[]" multiple id="reqLinkFileInvoice${id}" class="form-control">
                  <input type="hidden" name="reqLinkFileInvoiceTemp[]" id="reqLinkFileInvoiceTemp${id}" value="">
                  <span style="margin-left: 50px" class="nama-temp-file" id="namaFileInvoice${id}">${filename}</span>
                </td>

                <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
            </tr>
            `;
            $("#tambahInvoice").append(data);
        }

        function getFileNameInvoice(input, id) {
            for (var i = 0; i < input.files.length; i++) {
                if(i == 0)
                    $("#namaFileInvoice"+id).html(input.files[0].name);
                else
                    addInvoice((input.files[i].name))
            }
            
        }


        function getFileName(input, id) {
            console.log($(input));
            console.log($(input).parent());
        }

        function addRepair(){
            openAdd('app/loadUrl/app/tempalate_repair_euipment?reqEquipId=<?=$reqId?>');
        }

        function editRepair(id){
            openAdd('app/loadUrl/app/tempalate_repair_euipment?reqEquipId=<?=$reqId?>&reqId='+id);
        }
        function deleteRepair(id){
               deleteData("web/equip_repair_json/delete", id);
        }

        function reload_page(){
            window.location.reload();
        }
    </script>
</div>
</div>