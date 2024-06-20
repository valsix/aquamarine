<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('Pembelian');
$this->load->model('PembelianDetail');
$this->load->model('PembelianAlat');
$this->load->model('EquipmentList');
$this->load->model('VendorCode');

$dokumen_qm = new Pembelian();

$reqId = $this->input->get("reqId");


if ($reqId == "") {
    $reqMode = "insert";
    $pembelian = new Pembelian();
    $nextId = $pembelian->getNextId("PEMBELIAN_ID","PEMBELIAN");
    $fzeropadded = sprintf("%03d", $nextId);
    $fzeropadded = $fzeropadded.'/';
} else {
    $reqMode = "ubah";

    $dokumen_qm->selectByParamsMonitoring(array("A.PEMBELIAN_ID" => $reqId));
    // echo  $dokumen_qm->query;exit;
    $dokumen_qm->firstRow();

    $reqId      = $dokumen_qm->getField("PEMBELIAN_ID");
    $reqEcId            = $dokumen_qm->getField("JENIS_ID");
    $companyIds        = $dokumen_qm->getField("COMPANY_ID");
    $reqProjectId           = $dokumen_qm->getField("MASTER_PROJECT_ID");
    $reqTanggal     = $dokumen_qm->getField("TANGGAL");    
    $reqNamaSupplier  = $dokumen_qm->getField("NAMA_SUPPLIER");
    $reqCP1NAME  = $dokumen_qm->getField("CP1_NAME");
    $reqCurrency  = $dokumen_qm->getField("CURRENCY");
    $reqPembayaran  = $dokumen_qm->getField("JENIS_PEMBAYARAN");
    $reqNoPo =  $dokumen_qm->getField("NO_PO");
      $reqNamaProject =  $dokumen_qm->getField("NAMA_PROJECT");
    $reqCertificatePath= $dokumen_qm->getField("LAMPIRAN");
     $reqStatusPostig =  $dokumen_qm->getField("STATUS_POSTING");
     $reqTanggalPembayaran =  $dokumen_qm->getField("TANGGAL_BAYAR");
     $reqNoPembelian = $dokumen_qm->getField("NO_PEMBELIAN");
    $pembelianalat = new PembelianAlat();
    $pembelianalat->selectByParamsMonitoring(array("A.PEMBELIAN_ID"=>$reqId));
    $arrDataPembelianAlat = $pembelianalat->rowResult;
    $arrTotal = array_column($arrDataPembelianAlat, 'total');
    $arrQty = array_column($arrDataPembelianAlat, 'qty');
    $jumlahtotalAlat = array_sum($arrTotal);
    $jumlahtotalQty = array_sum($arrQty);



    $reqNamaSupplier               = $dokumen_qm->getField("NAMA_SUPPLIER");
    $reqAddress            = $dokumen_qm->getField("ADDRESS");
    $reqPhone              = $dokumen_qm->getField("PHONE");
    $reqFax                = $dokumen_qm->getField("FAX");
    $reqEmail              = $dokumen_qm->getField("EMAIL");
    $reqCP1NAME            = $dokumen_qm->getField("CP1_NAME");
    $reqCp1Telp            = $dokumen_qm->getField("CP1_TELP");
    $reqCp2Name            = $dokumen_qm->getField("CP2_NAME");
    $reqCp2Telp            = $dokumen_qm->getField("CP2_TELP");
    $reqLa1Name            = $dokumen_qm->getField("LA1_NAME");
    $reqLa1Address         = $dokumen_qm->getField("LA1_ADDRESS");
    $reqLa1Phone           = $dokumen_qm->getField("LA1_PHONE");
    $reqLa1Fax             = $dokumen_qm->getField("LA1_FAX");
    $reqLa1Email           = $dokumen_qm->getField("LA1_EMAIL");
    $reqLa1Cp1             = $dokumen_qm->getField("LA1_CP1");
    $reqLa1Cp2             = $dokumen_qm->getField("LA1_CP2");
    $reqLa2Name            = $dokumen_qm->getField("LA2_NAME");
    $reqLa2Address         = $dokumen_qm->getField("LA2_ADDRESS");
    $reqLa2Telp            = $dokumen_qm->getField("LA2_TELP");
    $reqLa2Fax             = $dokumen_qm->getField("LA2_FAX");
    $reqLa2Email           = $dokumen_qm->getField("LA2_EMAIL");
    $reqLa2Cp1             = $dokumen_qm->getField("LA2_CP1");
    $reqLa2Cp2             = $dokumen_qm->getField("LA2_CP2");
    $reqLa1Cp1Phone        = $dokumen_qm->getField("LA1_CP1_PHONE");
    $reqLa1Cp2Phone        = $dokumen_qm->getField("LA1_CP2_PHONE");
    $reqLa2Cp1Phone        = $dokumen_qm->getField("LA2_CP1_PHONE");
    $reqLa2Cp2Phone        = $dokumen_qm->getField("LA2_CP2_PHONE");
    $reqTipe               = $dokumen_qm->getField("TIPE");
    $reqBarangDisuplay               = $dokumen_qm->getField("BARAG_JASA");
    $reqTingkatPelayanan               = $dokumen_qm->getField("TINGKAT_PELAYANG");
    $reqKualitas               = $dokumen_qm->getField("KUALITAS");
    $reqKeterangan               = $dokumen_qm->getField("KETERANGAN_SUB");
    $reqHargaKet= $dokumen_qm->getField("HARGA_KET");
    $reqPpn = $dokumen_qm->getField("PPN");
     $reqPpnPercent = $dokumen_qm->getField("PPN_VAL");
     $reqNoVoucher = $dokumen_qm->getField("VOUCHER");
     $reqProvinsi               = $dokumen_qm->getField("PROPINSI_ID");
    $combo_kabupaten               = $dokumen_qm->getField("KABUPATEN_ID");

    $vendorcode = new VendorCode();
    $vendorcode->selectByParamsMonitoring(array('A.SUPPLIER_ID'=>$companyIds,'A.STATUS_AKTIF'=>'1'));

    $arrDataVendor = $vendorcode->rowResult;
    $arrDataVendor =   $arrDataVendor[0];
    $reqKodeVendor = $arrDataVendor['kode'];
    $reqType = $arrDataVendor['type'];
    $reqLoc = $arrDataVendor['area'];
}

// $reqNoPembelian = $reqNoPembelian?$reqNoPembelian:$fzeropadded;
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />
<style>
.frmSearch {border: 1px solid #a8d4b1;background-color: #c6f7d0;margin: 2px 0px;padding:40px;border-radius:4px;}
.country-list{float:left;list-style:none;margin-top:30px;padding:0;width:190px;position: absolute; z-index: 3;}
.country-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
.country-list li:hover{background:#ece3d2;cursor: pointer;}
#search-box{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
</style>

<?
if($reqStatusPostig =="POSTING" ){
   
?>
<script>
$( document ).ready(function() {

  $(".btnAksi").hide();
  $(".fa-trash").hide();
  $(".fa-plus-circle").hide();
  $(".fa-id-card-o").hide();
  
  $('#ff').find('input, textarea, select').attr('disabled','disabled');
  $('.easyui-combobox').combobox({disabled: true});
  $('.easyui-datebox').datebox({disabled: true});
  $('.submit').remove();

});
</script>
<?
 } 
?>
<div class="col-md-12">

    <div class="judul-halaman"> <a href="javascript:void(0)" onclick="goBack()"> Pembelian Barang dan Jasa</a> &rsaquo; Pembelian Barang dan Jasa
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Form Data Pembelian
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>
                     <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Tanggal</label>
                        <div class="col-md-1">
                            <div class="form-group">
                                 <input type="text" class="easyui-datebox  form-control" name="reqDateOfService"  value="<?=$reqTanggal?>" style=" width:200px" />
                            </div>
                        </div>
                         <label for="reqName" class="control-label col-md-2">No Pembelian</label>
                        <div class="col-md-4">
                            <div class="form-group">
                              <input type="text" class="easyui-validatebox textbox form-control" name="reqNoPembelian" id="reqNoPembelian" value="<?= $reqNoPembelian ?>" style=" width:100%" />
                           </div>
                       </div>
                        <!--  <label for="reqName" class="control-label col-md-2">Jenis Pembelian</label>
                        <div class="col-md-2">
                            <div class="form-group">
                                     <input class="easyui-combobox form-control" style="width:100%" id="reqEcId" name="reqEcId" data-options="width:'290',editable:false, valueField:'id',textField:'text',url:'combo_json/comboEquipCategori'" value="<?= $reqEcId ?>" />
                            </div>
                        </div> -->
                       
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Currency</label>
                        <div class="col-md-1">
                            <div class="form-group">
                              <input class="easyui-combobox form-control" style="width:30%"  name="reqCurrencyValue" data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="<?=$reqCurrency?>" />
                           </div>
                       </div>
                       <label for="reqName" class="control-label col-md-2">Pembayaran</label>
                        <div class="col-md-1">
                            <div class="form-group">
                              <input class="easyui-combobox form-control" style="width:40%"  name="reqPembayaran" data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_pembayaran'" value="<?=$reqPembayaran?>" />
                           </div>
                       </div>
                       <label for="reqName" class="control-label col-md-2">Tanggal Pembayaran</label>
                        <div class="col-md-1">
                            <div class="form-group">
                                 <input type="text" class="easyui-datebox  form-control" name="reqTanggalBayar"  value="<?=$reqTanggalPembayaran?>" style=" width:200px" />
                           </div>
                       </div>
                   </div>
                    <div class="form-group">
                          <label for="reqName" class="control-label col-md-2">Code</label>
                        <div class="col-md-1">
                            <div class="form-group">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqMaker" id="reqMaker" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_project_code',onSelect: function(rec){
                                                  reloadComboJenus(rec.id);
                                           }" value="<?=$reqProjectId?>" />
                            </div>
                        </div>

                        <label for="reqName" class="control-label col-md-2">Project</label>
                        <div class="col-md-5">
                            <div class="form-group">
                                     <input type="text" class="easyui-validatebox  form-control" id='reqNamaProject' value="<?=$reqNamaProject?>"  disabled readonly />

                            </div>
                        </div>
                       
                   </div>
                   <div class="form-group">
                    <label for="reqName" class="control-label col-md-2">No Po</label>
                        <div class="col-md-4">
                            <div class="form-group">
                              <input type="text" class="easyui-validatebox textbox form-control"  id="reqNoPo2" value="<?= $reqNoPo ?>" style=" width:100%" disabled readonly/>
                              <input type="hidden" class="easyui-validatebox textbox form-control" name="reqNoPo" id="reqNoPo" value="<?= $reqNoPo ?>" style=" width:100%" />
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
                        <label for="reqStatusCheck" class="control-label col-md-2">Ppn <input type="checkbox" <?= $checks ?> name="reqPpn" id="reqPpn"  value="1" /> </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" maxlength="3"  onkeypress='validate(event)' class=" form-control" id="reqPpnPercent" name="reqPpnPercent" value="<?= $reqPpnPercent ?>" style=" width:20%"  /> % <strong> Currency Ppn</strong>

                                </div>
                            </div>
                        </div>
                         <label for="reqName" class="control-label col-md-2">No Voucher</label>
                        <div class="col-md-4">
                            <div class="form-group">
                              <input type="text" class="easyui-validatebox textbox form-control"   name='reqNoVoucher' id="reqNoVoucher" value="<?= $reqNoVoucher ?>" style=" width:100%" />
                            
                           </div>
                       </div>
                      </div>
                    
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>Supplier</h3>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Supplier Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <span class="input-group-addon" onclick="openCompany()"><i class="fa fa-address-book-o fa-lg"></i> </span>
                                        <span class="input-group-addon" onclick="clearCompany()" ><i class="fa fa-times fa-lg"></i> </span>
                                        <input type="text" class="form-control"  id="reqCompanyName" name="reqCompanyName" value="<?= $reqNamaSupplier ?>" 
                                        style=" width:150%"  
                                        >

                                    </div>

                                  
                                    <input type="hidden" class="easyui-validatebox textbox form-control" name="reqCompanyId" id="reqCompanyId" value="<?= $companyIds ?>" style=" width:100%" />

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
                                   <input type="text" class="easyui-validatebox textbox form-control" id="reqDocumentPerson" name="reqDocumentPerson" value="<?= $reqCP1NAME ?>" style=" width:100%"  />
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
                                  
                                     <input class="easyui-combobox form-control" style="width:40%" id='reqBarangDisuplay'  name="reqBarangDisuplay" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_suplay'" value="<?=$reqBarangDisuplay?>" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp2Telp" class="control-label col-md-2">Tingkat Pelayanan</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <input class="easyui-combobox form-control" style="width:40%" id='reqTingkatPelayanan'  name="reqTingkatPelayanan" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_pelayanan'" value="<?=$reqTingkatPelayanan?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCp2Name" class="control-label col-md-2">Kualitas</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:40%" id='reqKualitas'  name="reqKualitas" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_pelayanan'" value="<?=$reqKualitas?>" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp2Telp" class="control-label col-md-2">Keterangan</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control" id='reqKeterangan' name="reqKeterangan"><?=$reqKeterangan?></textarea>
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
                                    <input class="easyui-combobox form-control" style="width:40%" id='reqType'   name="reqType" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_type_sub'" value="<?=$reqType?>" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp2Telp" class="control-label col-md-2">Location Code</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                        <input class="easyui-combobox form-control" style="width:40%" id='reqLoc'  name="reqLoc" data-options="width:'290',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_type_location'" value="<?=$reqLoc?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCp2Telp" class="control-label col-md-2">Vendor Code</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                 <input type="text"  disabled readonly class="easyui-validatebox textbox form-control" id='vendorcode' value="<?=$reqKodeVendor?>"  style=" width:60%" disabled readonly />
                                
                             </div>
                         </div>
                     </div>
                 </div>
                      <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>Transaksi Pembelian</h3>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                 <th width="10%"> Jenis</th>
                                 <th width="30%">Nama Barang  <a onClick="tambahBarang()"><i class="fa fa-plus-circle fa-lg"></i></a></th>
                                   <th width="15%">Description</th>
                                 <th width="10%">Harga</th>
                                 <th width="5%">Qty</th>
                                 <th width="15%">Total</th>
                                 <th width="5%"> Aksi </th>
                            </tr>
                        </thead>

                        <?
                        $reqTotalQty =$reqTotalHarga =0;
                        ?>
                        <tbody id="tbodyTansaksi">
                            <?
                            $pembeliandetail = new PembelianDetail();
                            $pembeliandetail->selectByParamsMonitoring(array("CAST(A.PEMBELIAN_ID AS VARCHAR)"=>$reqId));
                            $nomer=1;
                            $totalItem=0;
                            $totalPrice=0;
                            while ($pembeliandetail->nextRow()) {
                                $idRand=rand();
                                 $reqPembelianDetailId = $pembeliandetail->getField('PEMBELIAN_DETAIL_ID');

                                $equipmentlist = new EquipmentList();
                                $total_euip = $equipmentlist->getCountByParamsMonitoring(array("A.PEMBELIAN_DETAIL_ID"=>$reqPembelianDetailId));


                                $reqCurrency = $pembeliandetail->getField('CURRENCY');
                                $reqQty = $pembeliandetail->getField('QTY');
                               
                                $reqHarga = $pembeliandetail->getField('HARGA');
                                $reqTotal = $pembeliandetail->getField('TOTAL');
                                $reqEquipName = $pembeliandetail->getField('NAMA_ALAT');
                                $reqEquipId = $pembeliandetail->getField('EQUIP_ID');
                                $reqECId = $pembeliandetail->getField('EC_ID');
                                $reqSerialNumber = $pembeliandetail->getField('NO_SERI');
                                $reqIdcEquipmentDesc = $pembeliandetail->getField('DESKRIPSI');
                                $totalItem += $reqQty;
                                $totalPrice += $reqTotal;

                                $reqTotalQty +=$reqQty;
                                $reqTotalHarga +=$reqTotal;

                                $reqTotal = currencyToPage2($reqTotal);
                                $reqHarga = currencyToPage2($reqHarga);
                                $reqQty = conver_number($reqQty);

                                $arrDataAlata = multi_array_search($arrDataPembelianAlat,array("pembelian_detail_id"=>$reqPembelianDetailId));
                                 $arrHargaAlat = array_column($arrDataAlata, 'harga');
                                $arrQtyAlat = array_column($arrDataAlata, 'qty');
                                $arrQtyTotal = array_column($arrDataAlata, 'total');
                                 $jumlahAlatHarga = array_sum($arrHargaAlat);
                                $jumlahAlatQty = array_sum($arrQtyAlat);
                                $jumlahAlatTotal = array_sum($arrQtyTotal);

                                $jumlahAlatQty = conver_number($jumlahAlatQty);
                                $jumlahAlatTotal = currencyToPage2($jumlahAlatTotal);
                                $jumlahAlatHarga = currencyToPage2($jumlahAlatHarga);
                            ?>    
                               <tr class="trNo<?=$idRand?>">
                                <td><input type="text" disabled readonly  class="form-control reqUrutTautan"  name="reqUrutTautan[]" value="<?=$nomer?>">
                                 <input type="hidden" class="form-control"  name="reqPembelianId[]"  value="<?=$reqPembelianDetailId?>"/>
                                </td>
                                <td>
                                   <input class="easyui-combobox form-control" style="width:100%" name="reqEcId[]" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'combo_json/comboEquipCategori'" value="<?=$reqECId?>" />
                                </td>
                                <td> 
                                    <div class="input-group">
                                        <span class="input-group-addon" onclick="TambilStock(<?=$idRand?>,<?=$reqPembelianDetailId?>)"><i class="fa fa-plus  fa-lg"></i> </span>
                                        
                                        <span class="input-group-addon" onclick="LambilStock(<?=$idRand?>)"><i class="fa fa-book fa-lg"></i> </span>
                                        <span class="input-group-addon" onclick="clearStock(<?=$idRand?>)" ><i class="fa fa-times fa-lg"></i> </span>
                                        
                                        <input type="text" class="form-control nama_alat_key"  id="reqIdcEquipmentName<?=$idRand?>" name="reqIdcEquipmentName[]" value="<?=$reqEquipName?>" 
                                        style=" width:69%" required
                                        />
                                         
                                        <input type="text" class="form-control"  name="reqIdcEquipmentSerial[]" id="reqIdcEquipmentSerial<?=$idRand?>" value="<?=$reqSerialNumber?>" 
                                        style=" width:30%" placeholder="Serial Number"
                                        />
                                        <input type="hidden" class="form-control" id="reqIdcEquipment<?=$idRand?>" name="reqIdcEquipment[]"  value="<?=$reqEquipId?>"/>
                                        <div id="AreqIdcEquipmentName<?=$idRand?>" class="complit"></div>
                                    </div>
                                </td>
                                <td>  <input type="text" class="form-control"  name="reqIdcEquipmentDesc[]" id="reqIdcEquipmentDesc<?=$idRand?>" value="<?=$reqIdcEquipmentDesc?>" 
                                        style=" width:100%" placeholder="Description "
                                        /> </td>
                                <td >
                                   
                                <input type="text" class="easyui-validatebox textbox form-control" id="reqEquipPrice<?=$idRand?>" name="reqEquipPrice[]" value="<?=$reqHarga?>" 
                                onchange="numberWithCommas('reqEquipPrice<?=$idRand?>');changeValueColomn(<?=$idRand?>)" onkeyup="numberWithCommas('reqEquipPrice<?=$idRand?>');changeValueColomn(<?=$idRand?>)"

                                style=" width:100%"  /> 
                              
                                </td>
                                <td > 
                                      <input type="text" id="reqEquipQty<?=$idRand?>" class="easyui-validatebox textbox form-control" name="reqEquipQty[]" value="<?=$reqQty?>" 
                                onchange="numberWithCommas('reqEquipQty<?=$idRand?>');changeValueColomn(<?=$idRand?>)" onkeyup="numberWithCommas('reqEquipQty<?=$idRand?>');changeValueColomn(<?=$idRand?>)"

                                style=" width:100%"  /> 
                                </td>
                                 <td > 
                                     <input type="text" id="reqEquipTotal<?=$idRand?>" class="easyui-validatebox textbox form-control" name="reqEquipTotal[]" value="<?=$reqTotal?>" 
                                onchange="numberWithCommas('reqEquipTotal<?=$idRand?>')" onkeyup="numberWithCommas('reqEquipTotal<?=$idRand?>')"

                                style=" width:100%" disabled readonly /> 
                                </td>
                                 <td> 
                                    <a onclick="deleteData('web/pembelian_json/deleteDetail',<?=$reqPembelianDetailId?>);"><i class="fa fa-trash fa-lg"></i></a>
                                </td>

                            </tr>

                         <?
                         foreach ($arrDataAlata as $alat) {
                         $idRnad  = rand();
                            $reqTotalQty +=$alat['qty'];
                                $reqTotalHarga +=$alat['total'];
                         ?>
                                <tr>
                                <td >
                                 <input type="hidden" value="<?=$reqPembelianDetailId?>" name="reqPembelidIdDetail[]" /> 
                                  <input type="hidden" value="<?=$alat['pembelian_alat_id']?>" name="reqPembelidIdAlat[]" /> 
                                </td>
                                <td>
                                </td>
                                <td >  

                                 <input type="text" class="easyui-validatebox textbox form-control nama_alat" name="reqNamaAlat[]" id="reqNamaAlat<?=$idRnad?>" value="<?=$alat['nama_alat']?>" style=" width:75%" placeholder="Masukkan Nama Spare Part"  />
                                    <input type="text" class="easyui-validatebox textbox form-control " id="reqNamaAlatSerial<?=$idRnad?>" name="reqNamaAlatSerial[]"  value="<?=$alat['serial_number']?>" style=" width:24%" placeholder=" Serial number"   />

                                    <div id="AreqNamaAlat<?=$idRnad?>" class="complit"></div></td>
                                      <td>   <input type="text" class="easyui-validatebox textbox form-control " id="reqNamaAlatDeskripsi<?=$idRnad?>" name="reqNamaAlatDeskripsi[]"  value="<?=$alat['deskipsi']?>" style=" width:100%" placeholder=" ID Part "   /> </td>
                                <td > <input type="text" onkeypress='validate(event)' id="reqAlatHarga<?=$idRnad?>" class="easyui-validatebox textbox form-control" name="reqAlatHarga[]"
                                  onchange="numberWithCommas('reqAlatHarga<?=$idRnad?>');changeValueColomnNew(<?=$idRnad?>)" onkeyup="numberWithCommas('reqAlatHarga<?=$idRnad?>');changeValueColomnNew(<?=$idRnad?>)"
                                     value="<?= currencyToPage2($alat['harga'])?>" style=" width:100%" placeholder="Masukkan Harga "  /></td>

                                <td> 
                                 
                                  <input type="text" onkeypress='validate(event)' class="easyui-validatebox textbox form-control" id="reqAlatQty<?=$idRnad?>" 
                                 onchange="numberWithCommas('reqAlatQty<?=$idRnad?>');changeValueColomnNew(<?=$idRnad?>)" onkeyup="numberWithCommas('reqAlatQty<?=$idRnad?>');changeValueColomnNew(<?=$idRnad?>)"
                                 name="reqAlatQty[]" value="<?=$alat['qty']?>" style=" width:100%" placeholder=" Qty " /> </td>
                                <td > <input type="text" class="easyui-validatebox textbox form-control" id="reqAlatTotal<?=$idRnad?>"  name="reqAlatTotal[]" value="<?=currencyToPage2($alat['total'])?>" style=" width:100%" disabled readonly /></td>
                                <td>
                                     <a onclick="deleteData('web/pembelian_json/deleteAlat',<?=$alat['pembelian_alat_id']?>);"><i class="fa fa-trash fa-lg"></i></a>
                                 </td>
                            </tr>
                            <?
                            }
                            ?>

                           
                            <?
                                $nomer++;
                            }
                            ?>

                            <?
                            if($nomer=='1'){
                            ?>
                            <tr id="tNotFounds">
                                <td colspan="6" align="center"> No Display Record  </td>
                            </tr>
                            <?
                            }else{
                            ?>

                             

                            
                            <?    

                            }    
                            ?>
                            
                        </tbody>
                        <tfoot>
                            <?
                             if($nomer > '1'){

                                if($reqPpn=='1'){

                              $reqPercen = ( ( $reqPpnPercent *$reqTotalHarga )/100);
                              $reqTotalHarga =$reqTotalHarga + $reqPercen;
                            ?>

                             <tr>
                                <td colspan="4" align="right"> <b> PPN </b>  </td>
                                <td  align="center" > <input type="text" class="form-control" value="<?=$reqPpnPercent?>" disabled readonly />  </td>
                                <td  align="center" colspan="2" > <input type="text" class="form-control" value="<?=currencyToPage2($reqPercen)?>" disabled readonly />  </td>
                               
                            </tr>
                              <?
                              }
                              ?>
                             <tr>
                                <td colspan="4" align="right"> <b> GRAND TOTAL </b>  </td>
                                <td  align="center" > <input type="text" class="form-control" value="<?=conver_number($reqTotalQty)?>" disabled readonly />  </td>
                                <td  align="center" colspan="2" > <input type="text" class="form-control" value="<?=currencyToPage2($reqTotalHarga)?>" disabled readonly />  </td>
                               
                            </tr>
                             <tr>
                                <td colspan="5" align="right">
                                   <b><em> 
                                    <?
                                    if($reqCurrency=='IDR'){
                                    ?>
                                    <?=kekata($reqTotalHarga)?> Rupiah
                                    <?
                                    }else{
                                    ?>
                                     <?=kekata_eng($reqTotalHarga)?> Dollar
                                    <?
                                    }
                                    ?>
                                    </em>

                                </b>
                                 </td>
                                 <td> </td>
                               </tr> 
                               <?
                            }
                               ?>
                        </tfoot>
                    </table>

                     <div class="clearfix"></div>
                      <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> LAMPIRAN
                        </h3>
                    </div>
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
                                                  <a href="uploads/pembelian/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileCertificate<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                  <a onclick="openAdd(`uploads/pembelian/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileCertificate<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
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
                                  <td colspan="2" align="center"> No Display Record   </td>
                                </tr>
                                <?  
                                }
                                ?>

                            </tbody>
                        </table>

                    </div>


                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                     <input type="hidden" name="reqValue" id="reqValue" value="" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />
                     <input type="hidden" name="reqSupplier" value="SUPPLIER" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning submit" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-primary submit" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                <a href="javascript:void(0)" class="btn btn-danger submit" onclick="POST()"><i class="fa fa-fw fa-bell-o "></i> Posting</a>


            </div>

        </div>

    </div>

    <script>
         function POST(){
                $.messager.confirm('Konfirmasi','Apakah anda yakin untuk melakukan posting ..?',function(r){
                    if (r){
                        $("#reqValue").val("POST");
                         submitForm();
                    }
                });     
            }


        function submitForm() {
               var win = $.messager.progress({
            title: 'Office Management  | PT Aquamarine Divindo',
            msg: 'proses data...'
        });
            $('#ff').form('submit', {
                url: 'web/pembelian_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                    if(datas[0]=='xxx'){
                             show_toast('error', 'Have troube in ',  datas[1]);
                            $.messager.alert('Info', datas[1], 'info'); 
                    }else{
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/pembelian_add?reqId=" + datas[0]);
                    }
                     $.messager.progress('close');
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
    
    <script type="text/javascript">
        function tambahBarang(){
            var tbodyRowCount = $('.reqUrutTautan').length+1;
            var total_nomer   =  $('.reqUrutTautan').length;
            var names = document.getElementsByName("reqUrutTautan[]");
            var namesId = document.getElementsByName("reqKronologisRef[]");

            for (var i = 0; i < total_nomer; i++) {

                names[i].value=((i+1));
            }
            var no = Math.floor((Math.random() * 1000) + 1);
               var data = `

                <tr>
                                <td><input type="text" disabled readonly  class="form-control reqUrutTautan"  name="reqUrutTautan[]" value="`+tbodyRowCount+`">
                                 <input type="hidden" class="form-control"  name="reqPembelianId[]"  value=""/>
                                </td>
                                <td>
                                 <input class="easyui-combobox form-control" style="width:100%" id="reqEcId`+no+`"  name="reqEcId[]" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'combo_json/comboEquipCategori'" value="" />
                                </td>
                                <td>   <div class="input-group">
                                     <span class="input-group-addon" onclick="LambilStock(`+no+`)"><i class="fa fa-book fa-lg"></i> </span>
                                        <span class="input-group-addon" onclick="clearStock(`+no+`)" ><i class="fa fa-times fa-lg"></i> </span>
                                    
                                        <input type="text" class="form-control nama_alat_key"  id="reqIdcEquipmentName`+no+`" name="reqIdcEquipmentName[]" value="" placeholder="Masukkan Nama Barang / Jasa"
                                        style=" width:69%"
                                        />
                                    
                                        
                                        <input type="text" class="form-control" name="reqIdcEquipmentSerial[]" id="reqIdcEquipmentSerial`+no+`" value="" 
                                        style=" width:30%" placeholder="Serial Number"
                                        />
                                        </div>
                                        <input type="hidden" class="form-control" id="reqIdcEquipment`+no+`" name="reqIdcEquipment[]"  value=""/>
                                      <div id="AreqIdcEquipmentName`+no+`" style="margin-top:-30px" class="complit"></div>
                                </td>
                                <td> 
                                 <input type="text" class="form-control"  name="reqIdcEquipmentDesc[]" id="reqIdcEquipmentDesc`+no+`" value="" 
                                        style=" width:100%" placeholder="Description "
                                        />
                                 </td>
                                <td>
                                     
                                <input type="text" class="easyui-validatebox textbox form-control" id="reqEquipPrice`+no+`" name="reqEquipPrice[]" value="0" 
                               
onchange="numberWithCommas('reqEquipPrice`+no+`');changeValueColomn(`+no+`)" onkeyup="numberWithCommas('reqEquipPrice`+no+`');changeValueColomn(`+no+`)"

                                style=" width:70%" /> 
                                 
                              
                                </td>
                                <td> 
                                      <input type="text" id="reqEquipQty`+no+`" class="easyui-validatebox textbox form-control" name="reqEquipQty[]" value="0" 
                                onchange="numberWithCommas('reqEquipQty`+no+`');changeValueColomn(`+no+`)" onkeyup="numberWithCommas('reqEquipQty`+no+`');changeValueColomn(`+no+`)"

                                style=" width:100%" /> 
                                </td>
                                 <td> 
                                     <input type="text" id="reqEquipTotal`+no+`" class="easyui-validatebox textbox form-control" 
                                     value="0" 
                                style=" width:100%" disabled readonly /> 
                                </td>
                                 <td> 
                                    <a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a>
                                </td>

                            </tr>

                `;

                $("#tbodyTansaksi").append(data);
                 $("#reqCurrency"+no).combobox();
                  $("#reqEcId"+no).combobox();
                 $("#tNotFounds").remove();
                 nama_alat_key();

        }
        var no_index_elament='';
        function LambilStock(no){
            // var kategoriId = $("#reqEcId").combobox('getValue');
            // if(kategoriId==''){
            //      $.messager.alert('Info', 'Jenis kategori masih belum ada pilihan', 'info'); 
            //      return;
            // }
            openAdd('app/loadUrl/app/template_stock?');
            no_index_elament=no;
        }

        function ambilStock(element){
            console.log(element);
           
            $("#reqIdcEquipment"+no_index_elament).val(element[0]);
               $("#reqIdcEquipmentName"+no_index_elament).val(element[4]);
                $("#reqIdcEquipmentSerial"+no_index_elament).val(element[6]);
                 $("#reqEcId"+no_index_elament).combobox('setValue',element[20]);

        }

        function clearStock(no){
            $("#reqIdcEquipment"+no).val('');
               $("#reqIdcEquipmentName"+no).val('');
        }
         function openCompany() {
            openAdd('app/loadUrl/app/template_load_company_id_new?reqKategori=SUPPLIER');

        }
        function company_pilihans(id, name, contact, anSelectedAdress, anSelectedEmail, anSelectedTelephone, anSelectedFaximile, anSelectedHp){
            $("#reqCompanyName").val(name);
            $("#reqCompanyId").val(id);
            $("#reqDocumentPerson").val(contact);

            $.post("web/pembelian_json/ambil_company_detail",{reqId:id})
            .done(function( data ) {
              var obj = JSON.parse(data);
              $('#reqPhone').val(obj['phone']);
              $(tinymce.get('reqAddress').getBody()).html(obj['address']);
             
              $('#reqFax').val(obj['fax']);
              $('#reqEmail').val(obj['email']);
              $('#reqDocumentPerson').val(obj['cp1_name']);
              $('#reqCp1Telp').val(obj['cp1_telp'])
              $('#reqCp2Name').val(obj['cp2_name'])
              $('#reqCp2Telp').val(obj['cp2_telp'])
              $('#reqBarangDisuplay').combobox('setValue',obj['barag_jasa']);
              $('#reqTingkatPelayanan').combobox('setValue',obj['tingkat_pelayang']);
              $('#reqKualitas').combobox('setValue',obj['kualitas']);
              $('#reqKeterangan').val(obj['keterangan_sub']);
              $('#reqProvinsi').combobox('setValue',obj['propinsi_id']);
              var url = 'web/combo_baru_json/combo_kabupaten?reqId='+obj['propinsi_id'];
              $('#combo_kabupaten').combobox('reload', url);
              setTimeout(function() {
              $('#combo_kabupaten').combobox('setValue',obj['kabupaten_id']);
               }, 1500);
              $('#reqType').combobox('setValue',obj['type']);
              $('#reqLoc').combobox('setValue',obj['loc']);
              $('#vendorcode').val(obj['vendorcode']);
            
            });

        }

        function clearCompany(){
            $("#reqCompanyName").val('');
            $("#reqCompanyId").val('');
            $("#reqDocumentPerson").val('');
        }

        


    </script>
    <script type="text/javascript">
        function changeValueColomn(no){
            var reqEquipPrice = $("#reqEquipPrice"+no).val();
            var reqEquipQty = $("#reqEquipQty"+no).val();

         

            var reqEquipPrice = reqEquipPrice.replaceAll('.', '');
            var reqEquipPrice = reqEquipPrice.replaceAll(',', '.');
            if (reqEquipPrice == '') {
                reqEquipPrice = 0;
            }

            var reqEquipQty = reqEquipQty.replaceAll('.', '');
            var reqEquipQty = reqEquipQty.replaceAll(',', '.');
            if (reqEquipQty == '') {
                reqEquipQty = 0;
            }

            var total = reqEquipQty * reqEquipPrice;

           $("#reqEquipTotal"+no).val(FormatCurrencyWithDecimal(total));

        }
         function changeValueColomnNew(no){
            var reqEquipPrice = $("#reqAlatHarga"+no).val();
            var reqEquipQty = $("#reqAlatQty"+no).val();

         

            var reqEquipPrice = reqEquipPrice.replaceAll('.', '');
            var reqEquipPrice = reqEquipPrice.replaceAll(',', '.');
            if (reqEquipPrice == '') {
                reqEquipPrice = 0;
            }

            var reqEquipQty = reqEquipQty.replaceAll('.', '');
            var reqEquipQty = reqEquipQty.replaceAll(',', '.');
            if (reqEquipQty == '') {
                reqEquipQty = 0;
            }

            var total = reqEquipQty * reqEquipPrice;

           $("#reqAlatTotal"+no).val(FormatCurrencyWithDecimal(total));

        }
    </script>

    <script type="text/javascript">
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
        function TambilStock(no,Pdetail){

              var random = Math.floor((Math.random() * 1000) + 1);
             var data = `  <tr>
                                <td >
                                 <input type="hidden" value="`+Pdetail+`" name="reqPembelidIdDetail[]" /> 
                                  <input type="hidden" value="" name="reqPembelidIdAlat[]" /> 
                                </td>
                                <td>
                                </td>
                                <td >   <input type="text" class="easyui-validatebox textbox form-control nama_alat" name="reqNamaAlat[]" id="reqNamaAlat`+random+`" value="" style=" width:75%" placeholder="Masukkan Nama Spare Part" />
                                <input type="text" class="easyui-validatebox textbox form-control " id="reqNamaAlatSerial`+random+`" name="reqNamaAlatSerial[]"  value="" style=" width:24%" placeholder=" Serial Number"   />
                                <div id="AreqNamaAlat`+random+`" style="margin-top:-30px" class="complit"></div></td>
                                <td>  <input type="text" class="easyui-validatebox textbox form-control " id="reqNamaAlatDeskripsi`+random+`" name="reqNamaAlatDeskripsi[]"  value="" style=" width:100%" placeholder=" ID Part "   /> </td>
                                <td > <input type="text" onkeypress='validate(event)' id="reqAlatHarga`+random+`" class="easyui-validatebox textbox form-control" name="reqAlatHarga[]"
                                  onchange="numberWithCommas('reqAlatHarga`+random+`');changeValueColomnNew(`+random+`)" onkeyup="numberWithCommas('reqAlatHarga`+random+`');changeValueColomnNew(`+random+`)"
                                     value="" style=" width:100%" placeholder="Masukkan Harga" /></td>
                                <td> <input type="text" onkeypress='validate(event)' class="easyui-validatebox textbox form-control" id="reqAlatQty`+random+`" 
                                 onchange="numberWithCommas('reqAlatQty`+random+`');changeValueColomnNew(`+random+`)" onkeyup="numberWithCommas('reqAlatQty`+random+`');changeValueColomnNew(`+random+`)"
                                 name="reqAlatQty[]" value="" style=" width:100%" placeholder=" Qty" /> </td>
                                <td > <input type="text" class="easyui-validatebox textbox form-control" id="reqAlatTotal`+random+`"  name="reqAlatTotal[]" value="" style=" width:100%" disabled readonly /></td>
                                <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                            </tr> `;
             var oldTr = $('.trNo'+no).parents("tr:last");
           
             $("#tbodyTansaksi "+'.trNo'+no).after(data);
             nama_key();
         }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
   nama_key();
    nama_alat_key();
});
        function selectCountry(val,id,serial) {
            var strId = id.replace("reqNamaAlat", "reqNamaAlatSerial"); 
            console.log(strId);
    $("#"+id).val(val);
     $("#"+strId).val(serial);
    $("#A"+id).hide();
}
   function selectCountry2(val,id,serial) {
    var strId = id.replace("reqIdcEquipmentName", "reqIdcEquipmentSerial"); 
   

    $("#"+id).val(val);
     $("#"+strId).val(serial);
    $("#A"+id).hide();
}

function nama_key(){
     $(".nama_alat").keyup(function() {
        var id = $(this).attr('id');
          var reqCompanyId = $('#reqCompanyId').val();
        if(reqCompanyId ==''){
           $.messager.alert('Info', 'Supplier Belum di pilih !', 'info'); 
           return;
        } 
        $.ajax({
            type: "POST",
            url: "web/pembelian_json/autoComplateSparedPart?reqId="+id+"&reqSupplierId="+reqCompanyId,
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

function reloadComboJenus(id){
  
  $.post("web/pembelian_json/ambilCodeProject",{reqId:id})
  .done(function( data ) {
    var obj = JSON.parse(data);
    $('#reqNamaProject').val(obj['NAMA']);
     $('#reqNoPo').val(obj['PO']);
     $('#reqNoPo2').val(obj['PO']);
  });
}

function nama_alat_key(){
     $(".nama_alat_key").keyup(function() {
        var id = $(this).attr('id');
        var reqCompanyId = $('#reqCompanyId').val();
        if(reqCompanyId ==''){
           $.messager.alert('Info', 'Supplier Belum di pilih !', 'info'); 
           return;
        } 
        $.ajax({
            type: "POST",
            url: "web/pembelian_json/autoComplate?reqId="+id+"&reqSupplierId="+reqCompanyId,
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

    </script>
     <script type="text/javascript">
        function gantiKota(id){
             var url = 'web/combo_baru_json/combo_kabupaten?reqId='+id;
            $('#combo_kabupaten').combobox('reload', url);
        }

    </script>
</div>
</div>