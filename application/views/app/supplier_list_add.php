<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$aColumns = array(
    "VESSEL_ID", "COMPANY_ID", "NAME", "LENGTH", "BREATH", "DEPTH",
    "TYPE_VESSEL", "CLASS_VESSEL", "TYPE_SURVEY", "LOCATION_SURVEY", "CONTACT_PERSON",
    "TELEPONE", "tanggal survey", "next survey", "NET Tonnage", "Reason", "AKSI"
);

$this->load->model("Company");
$this->load->model("CostumerSupport");
$this->load->model("PembelianAlat");
$this->load->model("SupplierBarang");
$this->load->model("SupplierPart");
$this->load->model("Pembelian");
$this->load->model('VendorCode');


$company = new Company();
$costumer_support = new CostumerSupport();
$reqId = $this->input->get("reqId");
// $reqId =3513;
if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $company->selectByParamsMonitoring(array("COMPANY_ID" => $reqId));
    $company->firstRow();

    $reqCompanyId          = $company->getField("COMPANY_ID");
    $reqName               = $company->getField("NAME");
    $reqAddress            = $company->getField("ADDRESS");
    $reqPhone              = $company->getField("PHONE");
    $reqFax                = $company->getField("FAX");
    $reqEmail              = $company->getField("EMAIL");
    $reqCp1Name            = $company->getField("CP1_NAME");
    $reqCp1Telp            = $company->getField("CP1_TELP");
    $reqCp2Name            = $company->getField("CP2_NAME");
    $reqCp2Telp            = $company->getField("CP2_TELP");
    $reqLa1Name            = $company->getField("LA1_NAME");
    $reqLa1Address         = $company->getField("LA1_ADDRESS");
    $reqLa1Phone           = $company->getField("LA1_PHONE");
    $reqLa1Fax             = $company->getField("LA1_FAX");
    $reqLa1Email           = $company->getField("LA1_EMAIL");
    $reqLa1Cp1             = $company->getField("LA1_CP1");
    $reqLa1Cp2             = $company->getField("LA1_CP2");
    $reqLa2Name            = $company->getField("LA2_NAME");
    $reqLa2Address         = $company->getField("LA2_ADDRESS");
    $reqLa2Telp            = $company->getField("LA2_TELP");
    $reqLa2Fax             = $company->getField("LA2_FAX");
    $reqLa2Email           = $company->getField("LA2_EMAIL");
    $reqLa2Cp1             = $company->getField("LA2_CP1");
    $reqLa2Cp2             = $company->getField("LA2_CP2");
    $reqLa1Cp1Phone        = $company->getField("LA1_CP1_PHONE");
    $reqLa1Cp2Phone        = $company->getField("LA1_CP2_PHONE");
    $reqLa2Cp1Phone        = $company->getField("LA2_CP1_PHONE");
    $reqLa2Cp2Phone        = $company->getField("LA2_CP2_PHONE");
    $reqTipe               = $company->getField("TIPE");
    $reqBarangDisuplay               = $company->getField("BARAG_JASA");
    $reqTingkatPelayanan               = $company->getField("TINGKAT_PELAYANG");
    $reqKualitas               = $company->getField("KUALITAS");
    $reqKeterangan               = $company->getField("KETERANGAN_SUB");
    $reqHargaKet= $company->getField("HARGA_KET");

     $reqProvinsi               = $company->getField("PROPINSI_ID");
    $combo_kabupaten               = $company->getField("KABUPATEN_ID");
    
    $pembelianalat = new PembelianAlat();
    $pembelianalat->selectByParamsMonitoring(array(),-1,-1," AND EXISTS( SELECT 1  FROM pembelian CC WHERE CC.pembelian_id = a.pembelian_id and cc.company_id='".$reqId."' and cc.status_delete is null)");
    $arrDataPembelian = $pembelianalat->rowResult;

    $supplierbarang = new SupplierBarang();
    $supplierbarang->selectByParamsMonitoring(array("A.SUPPLIER_ID"=>$reqId));
     $arrDataBarang = $supplierbarang->rowResult;

     $vendorcode = new VendorCode();
     $vendorcode->selectByParamsMonitoring(array('A.SUPPLIER_ID'=>$reqId,'A.STATUS_AKTIF'=>'1'));
     $arrDataVendor = $vendorcode->rowResult;
     $arrDataVendor =   $arrDataVendor[0];
     $reqKodeVendor = $arrDataVendor['kode'];
     $reqType = $arrDataVendor['type'];
      $reqLoc = $arrDataVendor['area'];

}


$total_support = $costumer_support->getCountByParamsMonitoring(array("CAST(A.COMPANY_ID AS VARCHAR)"=>$reqId));
$costumer_support->selectByParamsMonitoring(array("CAST(A.COMPANY_ID AS VARCHAR)"=>$reqId));



?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />
<style>
.frmSearch {border: 1px solid #a8d4b1;background-color: #c6f7d0;margin: 2px 0px;padding:40px;border-radius:4px;}
#country-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#country-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
#country-list li:hover{background:#ece3d2;cursor: pointer;}
#search-box{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
</style>

<style type="text/css">
    #tabel-vessel tr th {
        color: white;
        text-transform: uppercase;
        font-weight: bold;

    }
</style>
<div class="col-md-12">

    <div class="judul-halaman"> <a href="javascript:void(0)" onclick="goBack()">Supplier</a> &rsaquo; Form Supplier List
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

       

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Company
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                            <!-- <button type="button" id="" class="btn btn-default pull-right " style="margin-right: 10px" onclick="btn_next()"><i id="opens" class="fa fa-arrow-right fa-lg"></i><b id="htmlopen">Next</b></button> -->

                            <!-- <button type="button" id="" class="btn btn-default pull-right " style="margin-right: 10px" onclick="btn_prev()"><i id="opens" class="fa fa-arrow-left fa-lg"></i><b id="htmlopen">Prev</b></button> -->

                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Company Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqName" id="reqName" value="<?= $reqName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Telp</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPhone" class="easyui-validatebox textbox form-control" name="reqPhone" value="<?= $reqPhone ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqAddress" class="control-label col-md-2">Address</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqAddress" id="reqAddress" class="form-control tinyMCES" style="width:100%;"><?= $reqAddress ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                      <div class="form-group">
                    <label for="reqFax" class="control-label col-md-2">Fax</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqFax" class="easyui-validatebox textbox form-control" name="reqFax" value="<?= $reqFax ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>

                        <label for="reqEmail" class="control-label col-md-2">Email</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqEmail" class="easyui-validatebox textbox form-control" name="reqEmail" value="<?= $reqEmail ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCp1Name" class="control-label col-md-2">Contact Person I</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCp1Name" class="easyui-validatebox textbox form-control" name="reqCp1Name" value="<?= $reqCp1Name ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp1Telp" class="control-label col-md-2">Mobile Phone 1</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCp1Telp" class="easyui-validatebox textbox form-control" name="reqCp1Telp" value="<?= $reqCp1Telp ?>" onkeypress='validate(event)' style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCp2Name" class="control-label col-md-2">Contact Person II</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCp2Name" class="easyui-validatebox textbox form-control" name="reqCp2Name" value="<?= $reqCp2Name ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp2Telp" class="control-label col-md-2">Mobile Phone 2</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" onkeypress='validate(event)' id="reqCp2Telp" class="easyui-validatebox textbox form-control" name="reqCp2Telp" value="<?= $reqCp2Telp ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCp2Name" class="control-label col-md-2">Barang / Jasa yang disuplay</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:40%"   name="reqBarangDisuplay" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_suplay'" value="<?=$reqBarangDisuplay?>" />
                                    
                                </div>
                            </div>
                        </div>
                        <label for="reqCp2Telp" class="control-label col-md-2">Tingkat Pelayanan</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <input class="easyui-combobox form-control" style="width:40%"   name="reqTingkatPelayanan" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_pelayanan'" value="<?=$reqTingkatPelayanan?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCp2Name" class="control-label col-md-2">Kualitas</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:40%"   name="reqKualitas" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_pelayanan'" value="<?=$reqKualitas?>" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp2Telp" class="control-label col-md-2">Keterangan</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control" name="reqKeterangan"><?=$reqKeterangan?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="reqCp2Name" class="control-label col-md-2">Provinsi</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqProvinsi" id="reqProvinsi" data-options="width:'350',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_provinsi',onSelect: function(rec){

                                                  gantiKota(rec.id);
                                           }" value="<?= $reqProvinsi ?>" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp2Telp" class="control-label col-md-2">Kota</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input class="easyui-combobox form-control" style="width:100%" name="combo_kabupaten" id="combo_kabupaten" data-options="width:'350',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_kabupaten?reqId=<?= $reqProvinsi ?>'" value="<?= $combo_kabupaten ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        
                        <label for="reqCp2Telp" class="control-label col-md-2">Type</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:40%"   name="reqType" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_type_sub'" value="<?=$reqType?>" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp2Telp" class="control-label col-md-2">Location Code</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                        <input class="easyui-combobox form-control" style="width:40%"   name="reqLoc" data-options="width:'290',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_type_location'" value="<?=$reqLoc?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCp2Telp" class="control-label col-md-2">Vendor Code</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                 <input type="text"  disabled readonly class="easyui-validatebox textbox form-control" value="<?=$reqKodeVendor?>"  style=" width:60%" />
                                 <br><input type="checkbox" name ='reqCek' value="1" /> <em style="color: red">( ! ) Click Check  untuk melakukan revisi </em>
                             </div>
                         </div>
                     </div>
                 </div>
                  
                    <?
                    if(!empty($reqId)){
                    ?>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Support</h3>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-striped" id="table_support">
                        <thead>
                            <tr>
                            <th style="width: 40%">NAMA <a onclick="tambahPenyebabSupport()"  class="btn btn-info"><i class="fa fa-fw fa-plus-square"></i></a></th>
                            <th style="width: 20%">TELP / HP </th>
                            <th style="width: 30%">Email </th>
                            <th style="width: 10%">Aksi </th>
                            </tr>
                        </thead>
                        <tbody id="bodySupport">
                            <?
                           
                            while ($costumer_support->nextRow()) {
                                $no = $costumer_support->getField("COSTUMER_SUPPORT_ID");
                            ?>
                            <tr>
                                <td>
                                    <input type="hidden" class="form-control" value="<?=$costumer_support->getField("COSTUMER_SUPPORT_ID")?>" id="reqSupportId<?=$no?>"> 

                                    <input class="form-control" value="<?=$costumer_support->getField("NAMA")?>" id="reqSupportName<?=$no?>">  </td>
                                <td><input class="form-control" onkeypress='validate(event)' value="<?=$costumer_support->getField("TELP")?>" id="reqSupportTelp<?=$no?>"> </td>
                                <td><input class="form-control" value="<?=$costumer_support->getField("EMAIL")?>" id="reqSupportEmail<?=$no?>" ></td>
                                <td>

                                    <button type="button" class="btn btn-info " onclick="editing_support(<?=$costumer_support->getField("COSTUMER_SUPPORT_ID")?>)"><i class="fa fa-pencil-square-o fa-lg"> </i> </button>

                                    <button type="button" class="btn btn-danger hapusi" onclick="delete_support(<?=$costumer_support->getField("COSTUMER_SUPPORT_ID")?>)"><i class="fa fa-trash-o fa-lg"> </i> </button> </td>
                            </tr>
                            <?
                           
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                    <?
                        }   
                    ?>
                           
                     <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Daftar Alat</h3>
                    </div>
                    <div class="table-responsive">
                    <table class="table" id="barangSupplier">
                        <thead>
                        <tr>
                            <th width="5%"> No </th>
                            <th width="25%" > Nama <a onclick="tambahdatabarang()" class="btn btn-info"><i class="fa fa-fw fa-plus-square"></i></a></th>
                              <th width="15%"> Serial Number</th>
                               <th width="5%"> Qty </th>
                               <th width="5%"> Currency </th>

                            <th width="15%"> Harga</th>
                         
                       
                            <th width="5%"> Aksi </th>
                        </tr>
                       
                        </thead>
                        <tbody id="tbodyBarang">
                           <?
                           $nomer=1;
                           foreach ($arrDataBarang as  $value) {
                            $idrand = rand();
                            $curency =  $value['currency'];
                            $curency = $curency?$curency:'IDR';
                            ?>
                            <TR>
                                <td>  <input type="text" class="form-control "  value="<?=$nomer;$nomer++;?>"disabled readonly /> </td>
                             <td> 
                                <input type="hidden" name="reqSupplierBarangId[]" class="form-control" value="<?=$value['supplier_barang_id']?>" />
                              <input type="text" name="reqBarangNama[]" id="reqBarangNama<?=$idrand?>" class="form-control nama_alat"  value="<?=$value['nama']?>" />
                           <div id="AreqBarangNama<?=$idrand?>"></div></td>    
                            <td> <input type="text" name="reqSerial[]" class="form-control" value="<?=$value['serial_number']?>"  /></td>    
                              <td> <input type="text" name="reqQty[]" class="form-control" value="<?=$value['qty']?>" onkeypress='validate(event)' /></td>   
                              <td> 
                                      <input class="easyui-combobox form-control" style="width:30%"  name="reqCurrency[]" data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="<?=$curency?>"/>  </td>                
                              <td> <input type="text" name="reqHarga[]" id="reqHarga<?=$idrand?>" class="form-control" onkeypress='validate(event)'  value="<?=currencyToPage2($value['harga'])?>"
                                 onchange="numberWithCommas('reqHarga<?=$idrand?>');" onkeyup="numberWithCommas('reqHarga<?=$idrand?>');"
                                 /></td>
                              
                                
                                     <td><a onclick="deleteData('web/supplier_json/delete',<?=$value['supplier_barang_id']?>);"><i class="fa fa-trash fa-lg"></i></a> </td>
                                 </TR>

                            <?
                           }
                           ?>
                        </tbody>
                    </table>
                </div>
                <div class="page-header">
                    <h3><i class="fa fa-file-text fa-lg"></i> Daftar Spare Part </h3>
                </div>
                <div class="table-responsive">
                    <table class="table" id="barangSupplierpart">
                        <thead>
                            <tr>
                                <th width="5%"> No </th>
                               <th width="25%" > Nama <a onclick="tambahdatapart()" class="btn btn-info"><i class="fa fa-fw fa-plus-square"></i></a></th>
                                 <th width="15%"> Serial Number</th>
                               <th width="5%"> Qty </th>
                               <th width="5%"> Currency </th>
                            <th width="15%"> Harga</th>
                                              
                            <th width="5%"> Aksi </th>
                        </tr>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                            $supplierpart = new SupplierPart();
                            $supplierpart->selectByParamsMonitoring(array('CAST(A.SUPPLIER_ID AS VARCHAR)'=>$reqId));
                             $arrDataPart =  $supplierpart->rowResult;
                             $nomer=1;
                             foreach ($arrDataPart as $value) {
                            
                            $idrand = rand();
                              $curency =  $value['currency'];
                            $curency = $curency?$curency:'IDR';
                            ?>
                            <tr>
                                <td>   <input type="text"  class="form-control"  value="<?=$nomer;$nomer++;?>" disabled readonly /> </td>
                             <td> 
                                <input type="hidden" name="reqSupplierBarangIdPart[]" class="form-control" value="<?=$value['supplier_part_id']?>" />
                              <input type="text" name="reqBarangNamaPart[]" id="reqBarangNama<?=$idrand?>" class="form-control nama_part"  value="<?=$value['nama']?>" />
                           <div id="AreqBarangNama<?=$idrand?>"></div></td> 
                            <td> <input type="text" name="reqSerialPart[]" class="form-control" value="<?=$value['serial_number']?>"  /></td>        
                            <td> <input type="text" name="reqQtyPart[]" class="form-control" value="<?=$value['qty']?>" onkeypress='validate(event)' /></td>
                             <td> 
                                      <input class="easyui-combobox form-control" style="width:30%"  name="reqCurrencyPart[]" data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="<?=$curency?>"/>  </td>                   
                              <td> <input type="text" name="reqHargaPart[]" id="reqHarga<?=$idrand?>" class="form-control" onkeypress='validate(event)'  value="<?=currencyToPage2($value['harga'])?>"
                                 onchange="numberWithCommas('reqHarga<?=$idrand?>');" onkeyup="numberWithCommas('reqHarga<?=$idrand?>');"
                                 /></td>                            
                                <td><a onclick="deleteData('web/supplier_json/deletepart',<?=$value['supplier_part_id']?>);"><i class="fa fa-trash fa-lg"></i></a> </i></a> </td>
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                     <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> History Pembelian</h3>
                    </div>
                    <iframe src="app/loadUrl/app/pembelian_supplier/1/<?=$reqId?>" name="mainFrame" id="mainFrame"
                            style="display:block !important;height: 400px !important;width: 100%"></iframe>


                    <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />
                    <input type="hidden" name="reqSupplier" value="SUPPLIER" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>

        </div>

    </div>

    <script type="text/javascript">
        var halam = [];
        $(document).ready(function() {
            // setTimeout(function() {
            halam = [];
            halaman_ready();

            //     }, 1000);
            // new DataTable('#tabledef');
            $('#tabledef').dataTable();
            $('#barangSupplier').dataTable();
             $('#barangSupplierpart').dataTable();
            
            
            
            nama_key();
        });
    </script>
    <script type="text/javascript">
        function halaman_ready() {

            <?
            $company = new Company();
            $company->selectByParamsMonitoring(array());
            while ($company->nextRow()) {

            ?>


                halam.push("<?= $company->getField("COMPANY_ID") ?>");
            <?
            }
            ?>

        }

        function check_halaman() {
            var reqId = "<?= $reqId ?>";
            var index = 0;
            for (var i = 0; i < halam.length; i++) {
                if (halam[i] == reqId) {
                    index = i;
                }
            }
            return index;
        }

        function btn_next() {

            var index = check_halaman();
            var halaman = parseInt(index) + 1;
            var hal = halam[halaman];
            // console.log(hal);
            if (typeof hal === "undefined") {
                $.messager.alert('Info', "Halaman not Founds", 'info');

            } else {
                window.location.href = "app/index/customer_list_add?reqId=" + hal;
            }

        }

        function btn_prev() {

            var index = check_halaman();
            var halaman = parseInt(index) - 1;
            var hal = halam[halaman];
            // console.log(hal);
            if (typeof hal === "undefined") {

                $.messager.alert('Info', "Halaman not Founds", 'info');
            } else {
                window.location.href = "app/index/customer_list_add?reqId=" + hal;
            }

        }
    </script>
    <script>
        function submitForm() {

            $('#ff').form('submit', {
                url: 'web/customer_json/add_new',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    // alert(data);
                    var datas = data.split('-');
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/supplier_list_add?reqId=" + datas[0]);
                }
            });
        }

        function reload_table() {
            oTable.api().ajax.reload(null,false);
        }

        function clearForm() {
            $('#ff').form('clear');
        }

        function tambahPenyebab() {


            $.get("app/loadUrl/app/tempalate_vessel?", function(data) {
                $("#tambahVassel").append(data);
            });
        }

        function editing(id) {

            var elements = oTable.fnGetData(id);
            // console.log(elements[0]);

            openAdd('app/loadUrl/app/template_load_vessel?reqCompanyId=<?= $reqId ?>&reqId=' + elements[0]);

        }

        function deleting(id) {
            var elements = oTable.fnGetData(id);
            // var kata =  '<b>Detail </b><br>'+elements[2]+'<br> At'+elements[3];

            deleteData_for_table('web/vessel_detail_json/delete', elements[0], id, 2);


        }

        function delete_support(id){
            deleteData('web/customer_json/delete_costumer_support',id);
        }

        function tambahPenyebab2() {
            <?
            if (empty($reqId)) {
            ?>
                validate_next_proses();
            <?
            } else {
            ?>
                openAdd('app/loadUrl/app/template_load_vessel?reqCompanyId=<?= $reqId ?>&reqId=0');
            <?
            }
            ?>
        }
        function tambahPenyebabSupport() {
          $.get("app/loadUrl/app/tempalate_add_support?", function(data) {
            $("#bodySupport").append(data);
        });
        }

        function editing_support(id){
            var reqSupportId = $("#reqSupportId"+id).val();
            var reqSupportName = $("#reqSupportName"+id).val();
            var reqSupportTelp = $("#reqSupportTelp"+id).val();
            var reqSupportEmail = $("#reqSupportEmail"+id).val();
              var reqCompanyId = '<?=$reqId?>';
            var url = "web/customer_json/add_support";
            $.post(url,{reqSupportId:reqSupportId,reqSupportName:reqSupportName,reqSupportTelp:reqSupportTelp,reqSupportEmail:reqSupportEmail,reqCompanyId:reqCompanyId}, function(data) {
                var datas = data.split('-');
                var tambahan_form ='<button type="button" class="btn btn-info " onclick="editing_support('+datas[0]+')"><i class="fa fa-pencil-square-o fa-lg"> </i> </button><button type="button" class="btn btn-danger hapusi" onclick="delete_support('+datas[0]+')"><i class="fa fa-trash-o fa-lg"> </i> </button>';
             
                if(datas[1]=='tambah'){
                    $('#tdparent'+id).empty();
                    $('#tdparent'+id).append(tambahan_form);
                    $("#reqSupportId"+id).val(datas[0]);

                }

            });
        }
    </script>
    <script type="text/javascript">
        function tambah_row_barang(){
            $('#barangSupplier').DataTable().row.add([
  '1', '1', '1','2','2'
]).draw();
        }
    </script>

    <script type="text/javascript">
        function nama_key(){
           $(".nama_alat").keyup(function() {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                url: "web/supplier_json/combo_equipment_list?reqId="+id,
                data: 'keyword=' + $(this).val(),
                beforeSend: function() {
                    $("#"+id).css("background", "#FFF url(images/loader.gif) no-repeat 165px");
                },
                success: function(data) {
                    $("#A"+id).show();
                    $("#A"+id).html(data);
                    $("#"+id).css("background", "#FFF");
                }
            });
        });
       }
       function selectCountry(val,id) {
    $("#"+id).val(val);
    $("#A"+id).hide();
}
        function tambahdatabarang(){
            var random = Math.floor((Math.random() * 1000) + 1);

                 var fieldNomer =  ` <input type="text" class="form-control" value="-" disabled readonly  /> `; 

             var field1 = `  <input type="hidden" name="reqSupplierBarangId[]" class="form-control" />
              <input type="text" name="reqBarangNama[]" id="reqBarangNama`+random+`" class="form-control nama_alat" />
              <div id="AreqBarangNama`+random+`" class="complit"></div>  `;
               var fieldPart =  ` <input type="text" name="reqSerial[]" class="form-control" value=""  /> `; 
             var field2 = `   <input type="text" name="reqQty[]" class="form-control"  onkeypress='validate(event)' />   `;
              var field3 = `   <input type="text" name="reqHarga[]" id="reqHarga`+random+`" class="form-control" onkeypress='validate(event)' 
             onchange="numberWithCommas('reqHarga`+random+`');" onkeyup="numberWithCommas('reqHarga`+random+`');"
             />  `;
              var field4 = `   <a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> `;
              var fieldCurremcy =  ` <input class="easyui-combobox form-control" style="width:30%" id="reqCurrency`+random+`" name="reqCurrency[]" data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="IDR"/>`;


             // $("#tbodyBarang").append(data);
              $('#barangSupplier').DataTable().row.add([fieldNomer,field1,fieldPart,field2,fieldCurremcy ,field3,field4]).draw();
             $("#reqCurrency"+random).combobox();
             nama_key();
        }

         function tambahdatapart(){
            var random = Math.floor((Math.random() * 1000) + 1);

                 var fieldNomer =  ` <input type="text" class="form-control" value="-" disabled readonly  /> `; 


             var field1 = `  <input type="hidden" name="reqSupplierBarangIdPart[]" class="form-control" />
              <input type="text" name="reqBarangNamaPart[]" id="reqBarangNama`+random+`" class="form-control nama_alat" />
              <div id="AreqBarangNama`+random+`"></div>  `;
              var fieldPart =  ` <input type="text" name="reqSerialPart[]" class="form-control" value=""  /> `; 
             var field2 = `   <input type="text" name="reqQtyPart[]" class="form-control"  onkeypress='validate(event)' />   `;
              var field3 = `   <input type="text" name="reqHargaPart[]" id="reqHarga`+random+`" class="form-control" onkeypress='validate(event)' 
             onchange="numberWithCommas('reqHarga`+random+`');" onkeyup="numberWithCommas('reqHarga`+random+`');"
             />  `;
              var field4 = `   <a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> `;
              var fieldCurremcy =  ` <input class="easyui-combobox form-control" id="reqCurrencyPart`+random+`" style="width:30%"  name="reqCurrencyPart[]" data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="IDR"/>`;


             // $("#tbodyBarang").append(data);
              $('#barangSupplierpart').DataTable().row.add([fieldNomer,field1,fieldPart,field2,fieldCurremcy,field3,field4]).draw();
               $("#reqCurrencyPart"+random).combobox();
           
        }
    </script>
  <script type="text/javascript">
        function gantiKota(id){
             var url = 'web/combo_baru_json/combo_kabupaten?reqId='+id;
            $('#combo_kabupaten').combobox('reload', url);
        }

    </script>
</div>
</div>