<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get('reqId');
$reqV = $this->input->get('v');
$this->load->model('PembelianAlat');
$this->load->model('SparePart');
$this->load->model('PartPemakaian');

  if(empty($reqId)){
    $reqId=date('dmYHi');
    $reqMode='baru';
  }

$pembelianalat = new PembelianAlat();
$statement = " AND EXISTS(SELECT 1 FROM PEMBELIAN CC WHERE CC.PEMBELIAN_ID = A.PEMBELIAN_ID AND CC.STATUS_POSTING='POSTING')";
$statement .= " AND (REPLACE(LOWER(A.nama_alat), ' ', '_') || A.serial_number || B.EC_ID) ='".$reqId."'";


$pembelianalat->selectByParamsMonitoringKelola(array(),-1,-1,$statement);
$arrDataPembelian = $pembelianalat->rowResult;
$arrDataQty = array_column($arrDataPembelian, 'qty');
$arrColomJumlah = array_sum($arrDataQty);

$arrDataVal = $arrDataPembelian[0];
// print_r($arrDataPembelian);

$sparepart = new SparePart();
$sparepart->selectByParamsMonitoring(array('A.CODE'=>$reqId));
$arrDataPart = $sparepart->rowResult;
$arrDataPart = $arrDataPart[0];
$reqPicPath = $arrDataPart['gambar'];
$reqModel = $arrDataPart['model'];
$reqJumlahQty = $arrDataPart['jumlah'];

$reqPicPath = $reqPicPath?'uploads/part/'.$reqPicPath:'uploads/no-image.png';
$reqCertificatePath= $arrDataPart['lampiran'];
$reqDeskripisiNew = $arrDataPart['desktipsi'];



$reqSerialEquipment= $arrDataPart['serial_equip']?$arrDataPart['serial_equip']:$arrDataVal['serial_equip'];
$reqIdPart= $arrDataPart['id_part']?$arrDataPart['id_part']:$arrDataVal['deskipsi'];

$partpemakaian = new PartPemakaian();
$partpemakaian->selectByParamsMonitoring(array('A.CODE'=>$reqId));
$arrDataPemakaianPart = $partpemakaian->rowResult;
$arrDataPakai = array_column($arrDataPemakaianPart, 'jumlah');
// $arrDataPakaiPemakain = array_column($arrDataPemakaianPart, 'pemakaian');
// $arrDataPakaiPemakain = array_count_values($arrDataPakaiPemakain);

foreach ($arrDataPemakaianPart as  $value) {
   $arrDataPakaiPemakain[$value['pemakaian']] +=ifZero2($arrDataPakaiPemakain[$value['pemakaian']])+$value['jumlah'];
}


  if($reqModel=='baru'){
      $reqMode = $reqModel;
      $arrColomJumlah =  $reqJumlahQty;
}

$totalPemakaian = array_sum($arrDataPakai);
$totalKeluar = $totalPemakaian-ifZero2($arrDataPakaiPemakain['Broken']);
$totalKeluar = $totalKeluar +$arrDataPakaiPemakain['Broken'];
$reqSisa = ($arrColomJumlah-$totalKeluar);
$disable='';
if( $reqMode =='baru'){}else{
  $disable = 'disabled';
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />
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
    .form-group{
        margin-bottom: -30px !important;
    }
</style>
<div class="col-md-12">

    <div class="judul-halaman"> <a href="javascript:void(0)" onclick="goBack()"> Spare Part</a> &rsaquo; Spare Part Kelola
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

       
    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Spare Part Entry
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>

                    <br>

                    <table style="width: 100%" id="tableis">
                        <tr>
                           
                            <td >
                             <div class="form-group">
                                <label for="reqName" class="control-label col-md-2">No</label>
                                <div class="col-md-4">
                                    <div class="form-group">
                                       <input type="text" class="easyui-validatebox textbox form-control"  value="<?= $reqV ?>" style=" width:100%" />
                                   </div>
                               </div>
                               <label for="reqName" class="control-label col-md-2">Jumlah</label>
                               <div class="col-md-4">
                                <div class="form-group">
                                  <input type="text" class="easyui-validatebox textbox form-control" name="reqJumlah" id="reqJumlah" value="<?= $arrColomJumlah ?>" style=" width:100%"  <?=$disable?> />
                              </div>

                                 </div>
                             </div>
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
                            
                            <td>
                                 
                             <div class="form-group">
                                <label for="reqName" class="control-label col-md-2">Nama Spare Part</label>
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <?
                                      if($reqMode=='baru'){
                                       ?>
                                         <input class="easyui-combobox form-control" style="width:100%" name="reqNamaPart" data-options="width:'290',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_ambil_name?reqModul=SPARE_PART'" value="<?= $arrDataVal['nama_alat']?$arrDataVal['nama_alat']:$arrDataPart['nama_part']; ?>"   /> 

                                       <!--   <div class="input-group">
                                          <span class="input-group-addon" onclick="openEquipment()"><i class="fa fa-address-book-o fa-lg"></i> </span>
                                         
                                          <input type="text" class="form-control"  id="reqNamaPart" name="reqNamaPart" value="<?= $reqNamaPart ?>" 
                                          style=" width:100%"
                                          >

                                        </div> -->



                                       <?
                                      }else{
                                      ?>
                                       <input type="text" class="easyui-validatebox textbox form-control" name="reqNamaPart" id="reqNamaPart" value="<?= $arrDataVal['nama_alat']?$arrDataVal['nama_alat']:$arrDataPart['nama_part']; ?>" style=" width:100%"  <?=$disable?>   />
                                       <?
                                      }
                                       ?>
                                   </div>
                               </div>
                               <label for="reqName" class="control-label col-md-2">Baik</label>
                               <div class="col-md-4">
                                <div class="form-group">
                                  <input type="text" class="easyui-validatebox textbox form-control" name="reqJumlahBaik" id="reqNoPembelian" value="<?= ifZero2($arrColomJumlah-$arrDataPakaiPemakain['Broken']) ?>" style=" width:100%"  />
                              </div>

                                 </div>
                             </div>

                            </td>
                        </tr>
                        <tr>
                           
                            <td> <div class="form-group">
                                <label for="reqName" class="control-label col-md-2">Nama Alat</label>
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <!--  <input type="text" class="easyui-validatebox textbox form-control" name="reqNamaAlat" id="reqNamaAlat" value="<?= $arrDataVal['nama_part']?$arrDataVal['nama_part']:$arrDataPart['nama_alat']; ?>" style=" width:100%" <?=$disable?>   /> -->

                                         <div class="input-group">
                                          <span class="input-group-addon" onclick="openEquipment()"><i class="fa fa-address-book-o fa-lg"></i> </span>
                                         
                                          <input type="text" class="form-control"  id="reqNamaAlat" name="reqNamaAlat" value="<?= $reqNamaAlat ?>" 
                                          style=" width:100%"
                                          >

                                        </div>

                                   </div>
                               </div>
                               <label for="reqName" class="control-label col-md-2">Rusak</label>
                               <div class="col-md-4">
                                <div class="form-group">
                                  <input type="text" class="easyui-validatebox textbox form-control" name="reqJumlahRusak" id="reqNoPembelian" value="<?= ifZero2($arrDataPakaiPemakain['Broken']) ?>" style=" width:100%"  />
                              </div>

                                 </div>
                             </div> </td>
                        </tr>



                        <tr>
                           
                            <td> <div class="form-group">
                                <label for="reqName" class="control-label col-md-2">Kategory</label>
                                <div class="col-md-4">
                                    <div class="form-group">

                                        <input class="easyui-combobox form-control" style="width:100%" name="reqEcId" data-options="width:'290',editable:false, valueField:'id',textField:'text',url:'combo_json/comboEquipCategori'" value="<?= $arrDataVal['ec_id']?$arrDataVal['ec_id']:$arrDataPart['kategori']; ?>"  <?=$disable?>  />

                                      <!--  <input type="text" class="easyui-validatebox textbox form-control" name="reqNoPembelian" id="reqNoPembelian" value="<?= $arrDataVal['ec_name'] ?>" style=" width:100%"  /> -->
                                   </div>
                               </div>
                               <label for="reqName" class="control-label col-md-2">Keluar</label>
                               <div class="col-md-4">
                                <div class="form-group">
                                  <input type="text" class="easyui-validatebox textbox form-control" name="reqNoPembelian" id="reqNoPembelian" value="<?= $totalPemakaian-ifZero2($arrDataPakaiPemakain['Broken']) ?>" style=" width:100%"  />
                              </div>

                                 </div>
                             </div> </td>
                        </tr>
                        <tr>
                           
                            <td><div class="form-group">
                                <label for="reqName" class="control-label col-md-2">Serial Nomer</label>
                                <div class="col-md-4">
                                    <div class="form-group">
                                       <input type="text" class="easyui-validatebox textbox form-control" name="reqSerialNumber" id="reqSerialNumber" value="<?= $arrDataVal['serial_number']?$arrDataVal['serial_number']:$arrDataPart['serial_number']; ?>" style=" width:100%" <?=$disable?>   />
                                   </div>
                               </div>
                               <label for="reqName" class="control-label col-md-2">Sisa</label>
                               <div class="col-md-4">
                                <div class="form-group">
                                  <input type="text" class="easyui-validatebox textbox form-control" name="reqNoPembelian" id="reqNoPembelian" value="<?= $reqSisa ?>" style=" width:100%"  />
                              </div>

                                 </div>
                             </div> </td>
                        </tr>
                        <tr>
                          
                            <td><div class="form-group">
                                <label for="reqName" class="control-label col-md-2">Deskripsi</label>
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <textarea class="form-control" name='reqDeskripisi'><?=$reqDeskripisiNew?></textarea>
                                   </div>
                               </div>
                              
                             </div> </td>
                        </tr>
                       
                        <tr>
                          
                            <td><div class="form-group">
                                <label for="reqName" class="control-label col-md-2">Lokasi</label>
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <input class="easyui-combobox form-control" style="width:100%" name="reqLokasi" id="reqLokasi" data-options="width:'290', height: '36',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_lokasi'" id="reqCariCondition" value="<?=$arrDataPart['lokasi_id']?>" />
                                   </div>
                               </div>
                              
                             </div> </td>
                        </tr>
                         <tr>
                          
                            <td><div class="form-group">
                                <label for="reqName" class="control-label col-md-2">ID Part</label>
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <input type="text" class="easyui-validatebox textbox form-control" name="reqIdPart" id="reqIdPart" value="<?= $reqIdPart ?>" style=" width:100%"  />
                                   </div>
                               </div>
                                <label for="reqName" class="control-label col-md-2">Serial No. Equipment.</label>
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <input type="text" class="easyui-validatebox textbox form-control" name="reqSerialEquipment" id="reqSerialEquipment" value="<?= $reqSerialEquipment ?>" style=" width:100%"  />
                                   </div>
                               </div>
                              
                             </div> 


                           </td>
                        </tr>

                     

                       
                    

                    </table>


                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> HISTORY PEMAKAIAN
                        </h3>
                    </div>
                    <div style="text-align:left;padding:5px">
                         <!-- <a href="javascript:void(0)" class="btn btn-primary" onclick="tambahPemakaian()"><i class="fa fa-fw fa-plus"></i> Tambah</a> -->
                     <!--    <a href="javascript:void(0)" class="btn btn-warning" ><i class="fa fa-fw fa-pencil"></i> Edit</a>
                     
                         <a href="javascript:void(0)" class="btn btn-danger"><i class="fa fa-fw fa-remove"></i> Hapus</a> -->
                    </div>
                    <table class="table" id='tablePemakain'>
                        <thead>
                            <tr>
                                <th width="5%"> No</th>
                               <th width="15%"> Tanggal Keluar <a onclick="tambahPemakaian()" class="btn btn-info"><i class="fa fa-fw fa-plus-square"></i></a></th>
                                 <th width="10%">Pemakaian</th>
                                 <th  width="20%"> Project</th>
                                  <th  width="20%"> Deskripsi</th>
                                   <th width="5%"> Jumlah</th>
                                    <th width="20%"> Mengetahui</th>
                                     <th width="5%"> Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                            $no=1;
                            foreach ($arrDataPemakaianPart as  $value) {
                                $idRand =rand();
                            ?>
                            <tr>
                                <td><input type="text" class="form-control reqUrutan" name="reqUrutan[]" value="<?=$no?>"  />
                                 <input type="hidden" class="form-control " name="reqPemakaianPartId[]" value="<?=$value['part_pemakaian_id']?>" /> </td>
                                 <td> <input type="text"  class="easyui-datebox textbox form-control" name="reqTanggalPemakain[]" value="<?=$value['tanggal']?>" style=" width:190px" /> </td>
                                 <td><input class="easyui-combobox form-control" style="width:100%" name="reqPemakaian[]"  data-options="width:'90', height: '36',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_pemakain'"  value="<?=$value['pemakaian']?>" />  </td>
                                 <td>   <input class="easyui-combobox form-control" style="width:100%" name="reqProjectId[]"  data-options="width:'550',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_project'"  value="<?=$value['project_id']?>" /></td>
                                 <td><textarea name="reqDeskripisiD[]" ><?=$value['keterangan']?></textarea> </td>
                                 <td> <input type="text" class="easyui-validatebox textbox form-control" onkeypress='validate(event)' name="reqEquipQty[]" value="<?=$value['jumlah']?>" /> </td>
                                 <td>  <input type="text" class="easyui-validatebox textbox form-control" name="reqMengetahui[]" value="<?=$value['mengetahui']?>" /></td>
                                 <td>   
                                  <a onclick="openAdd('app/loadUrl/app/tempalate_master_lampiran?reqModul=SPARE_PART&reqId=<?=$value['part_pemakaian_id']?>')"><i class="fa fa-file fa-lg"></i></a>
                                  <a onclick="deleteData('web/spared_part_json/deletePekaianPart',<?=$value['part_pemakaian_id']?>)"><i class="fa fa-trash fa-lg"></i></a></td>

                            </tr>
                            <?
                            $no++;
                            }
                            ?>
                        </tbody>
                    </table>

                    <?
                    if(empty($reqMode)){
                    ?>

                    <div class="clearfix"></div>
                      <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> HISTORY PEMBELIAN
                        </h3>
                    </div>
                     <table class="table" id='tablePembelian'>
                        <thead>
                            <tr>
                                <th> No</th>
                               <th> No. Pembelian / <br>Tanggal Pembelian</th>
                                 <th>Supplier</th>
                                 <th> Harga</th>
                                   <th> Qty</th>
                                    <th> Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                            $no=1;
                            foreach ($arrDataPembelian as  $value) {
                            ?>    
                            <tr>
                                <td><?=$no?>  </td>
                                 <td><?=$value['no_pembelian']?><br> <?=$value['tanggal_bayar']?></td>
                                  <td><?=$value['nama_supplier']?>  </td>
                                   <td><?=currencyToPage2($value['harga'])?>  </td>
                                    <td><?=$value['qty']?>  </td>
                                    <td><?=currencyToPage2($value['total'])?></td>
                            </tr>
                               
                          
                            <?
                              $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                    <br>
                    <?
                    }
                    ?>
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
                                                  <a href="uploads/part/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileCertificate<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                  <a onclick="openAdd(`uploads/part/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileCertificate<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
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
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/spared_part_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {

                    var datas = JSON.parse(data);
                    //alert(data);
                    $.messager.alertReload('Info', datas['pesan'], 'info');
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
    <script type="text/javascript">
        var tablePemakain;
        $(document).ready(function() {
            $("#reqFilesName").change(function() {
                 // $.messager.alert('Info', this.files[0].size+' File Gambar terlalu besar', 'info');
               if(this.files[0].size > 148999){
                $.messager.alert('Info', 'File Gambar terlalu besar', 'info');
                 this.value = "";
             }else{
                 readURL(this);
             }
               
            });
   tablePemakain= $('#tablePemakain').DataTable();
            $('#tablePembelian').DataTable();
             
            

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

        function tambahPemakaian(){
            var random = Math.floor((Math.random() * 1000) + 1);

            var tbodyRowCount        = $('.reqUrutan').length+1;
            var total_nomer          =  $('.reqUrutan').length;
            var names                = document.getElementsByName("reqUrutan[]");
            for (var i = 0; i < total_nomer; i++) {
              names[i].value=((i+1));
            }
            var field1 = `<input type="text" class="form-control reqUrutan" name="reqUrutan[]" value="`+tbodyRowCount+`"  />
            <input type="hidden" class="form-control " name="reqPemakaianPartId[]" value="" />
             `;
            var field2 =  `<input type="text" id="reqTanggalPemakain`+random+`" class="easyui-datebox textbox form-control" name="reqTanggalPemakain[]" value="" style=" width:190px" /> `;
              var field3 =  `  <input class="easyui-combobox form-control" style="width:100%" name="reqPemakaian[]" id="reqPemakaian`+random+`" data-options="width:'90', height: '36',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_pemakain'"  value="" />`;
               var field4 =  `    <input class="easyui-combobox form-control" style="width:100%" name="reqProjectId[]"  data-options="width:'550',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_project'" id="reqProjectId`+random+`" value="" />`;
                var field5 =`<input type="text" class="easyui-validatebox textbox form-control" onkeypress='validate(event)' name="reqEquipQty[]" value="" />`;
                  var field6 =`<input type="text" class="easyui-validatebox textbox form-control" name="reqMengetahui[]" value="" />`;
                   var field8 =`<textarea name="reqDeskripisiD[]" ></textarea>`;
                   var field7 = `   <a onclick="tablePemakain.row($(this).parents('tr'))
        .remove()
        .draw();"><i class="fa fa-trash fa-lg"></i></a> `;
             $('#tablePemakain').DataTable().row.add([field1,field2,field3,field4 ,field8,field5,field6,field7]).draw();
             $('#reqTanggalPemakain'+random).datebox();
              $('#reqPemakaian'+random).combobox();
                 $('#reqProjectId'+random).combobox();

        }
    </script>

    <script type="text/javascript">
      function openEquipment(){
        openAdd('app/loadUrl/app/template_load_equip');
      }

      function pilih_equip(vData){
       var id = vData[1];
       var nama = vData[4];
       var serialNumber = vData[5];
       $('#reqNamaAlat').val(nama);
          $('#reqSerialEquipment ').val(serialNumber);

      }
    </script>
</div>
</div>