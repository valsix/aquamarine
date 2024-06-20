<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Penyimpanan");
$this->load->model("PenyimpananDetail");
$this->load->model("PenyimpananParaf");

$dokumen_qm = new Penyimpanan();

$reqId = $this->input->get("reqId");


if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";

    $dokumen_qm->selectByParamsMonitoring(array("A.PENYIMPANAN_ID" => $reqId));
    // echo  $dokumen_qm->query;exit;
    $dokumen_qm->firstRow();

    $reqId      = $dokumen_qm->getField("PENYIMPANAN_ID");
    $reqTanggal      = $dokumen_qm->getField("TANGGAL");
    $reqDescription      = $dokumen_qm->getField("KETERANGAN");
    $reqLokasi      = $dokumen_qm->getField("LOKASI");
    

   
}
$penyimpanandetail = new PenyimpananDetail();
$penyimpanandetail->selectByParamsMonitoring(array("CAST(PENYIMPANAN_ID AS VARCHAR)"=>$reqId));
$arrDataDetail = $penyimpanandetail->rowResult;

$penyimpananparaf = new PenyimpananParaf();
$penyimpananparaf->selectByParamsMonitoring(array("CAST(PENYIMPANAN_ID AS VARCHAR)"=>$reqId));
$arrDataParaf = $penyimpananparaf->rowResult;


?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/pembelian"> Penyimpanan</a> &rsaquo; Form Penyimpanan
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Form Data Penyimpanan
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
                    </div>

                     <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Lokasi</label>
                        <div class="col-md-3">
                            <div class="form-group">
                                 <input type="text" class="easyui-validatebox  form-control" name="reqLokasi"  value="<?=$reqLokasi?>"  />
                            </div>
                        </div>
                        <label for="reqName" class="control-label col-md-2">Keterangan</label>
                        <div class="col-md-3">
                            <div class="form-group">
                                 <textarea class="form-control" name="reqKeterangan"><?=$reqDescription?></textarea>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Paraf</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <table class="table table-striped table-hover dt-responsive">
                                    <thead>
                                    <tr>
                                        <th width="15%"> No </th>
                                        <th width="70%"> Nama <a onClick="tambah_paraf()"><i class="fa fa-plus-circle fa-lg"></i></a> </th>
                                        <th width="15%"> Aksi </th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbodyParaf">
                                        <?
                                        $no=1;
                                        foreach ($arrDataParaf as $value) {
                                        ?>
                                        <tr>
                                            <td> <input type="text" disabled readonly  class="form-control reqUrutNama"  name="reqUrutNama[]" value="<?=$no?>"> </td>
                                          <td> <input type="text" name="reqParafNama[]" class="easyui-validatebox  form-control"  value="<?=$value['nama']?>" /></td>
                                          <td> 
                                              <a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a>
                                          </td>
                                      </tr>      
                                        <?  
                                        $no++;
                                        }
                                        ?>
                                        <?
                                        if($no==1){                                          
                                        ?>
                                        <tr id="tNotFoundsParaf">
                                            <td colspan="3" align="center"> No diplay Record  </td>
                                        </tr>
                                        <?
                                        }
                                        ?>
                                    </tbody>
                                 </table>
                            </div>
                        </div>
                    </div>
                  
                      <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>Transaksi Penyimpanan</h3>
                    </div>
                    <table class="table table-striped table-hover ">
                        <thead>
                            <tr>
                                <th width="5%" rowspan="3">No</th>
                                 <th width="60%" rowspan="3">Nama Barang  <a onClick="tambahBarang()"><i class="fa fa-plus-circle fa-lg"></i></a></th>
                                
                                 <th width="5%" rowspan="3">Qty</th>
                                 <th colspan="6">Keadaan</th>
                                  <th  rowspan="3">Aksi</th>
                            </tr>
                            <tr>
                                <th colspan="2"> Masuk </th>
                                 <th colspan="2"> Keluar </th>
                                 <th colspan="2"> Persediaan </th>
                            </tr>
                            <tr>
                                <th width="5%"> Baik  </th>
                                 <th width="5%"> Buruk  </th>
                                 <th width="5%"> Baik  </th>
                                 <th width="5%"> Buruk  </th>
                                 <th width="5%"> Baik  </th>
                                 <th width="5%"> Buruk  </th>
                            </tr>

                        </thead>
                        <tbody id="tbodyTansaksi">
                            <?
                            $no=1;
                           foreach($arrDataDetail as $value){
                            $idRand= rand();
                            ?>
                           <tr>
                                <td><input type="text" disabled readonly  class="form-control reqUrutTautan"  name="reqUrutTautan[]" value="<?=$no?>">
                                 <input type="hidden" class="form-control"  name="reqItemId[]"  value="<?=$value['penyimpanan_detail_id']?>"/>
                                </td>
                                <td> 
                                    <div class="input-group">
                                        <span class="input-group-addon" onclick="LambilStock(<?=$idRand?>)"><i class="fa fa-book fa-lg"></i> </span>
                                        <span class="input-group-addon" onclick="clearStock(<?=$idRand?>)" ><i class="fa fa-times fa-lg"></i> </span>
                                        <input type="text" class="form-control"  id="reqIdcEquipmentName<?=$idRand?>" name="reqIdcEquipmentName[]" value="<?=$value['equip_name']?>" 
                                        style=" width:100%"
                                        />
                                        <input type="hidden" class="form-control" id="reqIdcEquipment<?=$idRand?>" name="reqIdcEquipment[]"  value="<?=$value['equip_id']?>"/>
                                    </div>
                                </td>
                                <td>
                                     
                                <input type="text" class="easyui-validatebox textbox form-control" onkeypress='validate(event)' id="reqEquipPrice<?=$idRand?>" name="reqEquipTotal[]" value="<?=$value['qty']?>" 
                               

                                style=" width:70%" /> 
                              
                                </td>
                               <td> <input type="text" name="masukG[]" class="easyui-validatebox  form-control" value="<?=$value['masuk_g']?>" maxlength="1"  onkeypress='validate(event)' /></td>
                               <td> <input type="text" name="masukR[]" class="easyui-validatebox  form-control" value="<?=$value['masuk_r']?>" maxlength="1" onkeypress='validate(event)' /></td>
                               <td> <input type="text" name="keluarG[]" class="easyui-validatebox  form-control"  value="<?=$value['keluar_g']?>" maxlength="1" onkeypress='validate(event)' /></td>
                               <td> <input type="text" name="keluarR[]" class="easyui-validatebox  form-control"  value="<?=$value['keluar_r']?>"  maxlength="1" onkeypress='validate(event)' /></td>
                                <td> <input type="text" name="persedianG[]" class="easyui-validatebox  form-control" value="<?=$value['persedian_g']?>" maxlength="1" onkeypress='validate(event)' /></td>
                                 <td> <input type="text" name="persedianR[]" class="easyui-validatebox  form-control"  value="<?=$value['persedian_r']?>" maxlength="1" onkeypress='validate(event)' /></td>
                                 <td> 
                                      <a onclick="deleteData('web/penyimpanan_json/deleteDetail',<?=$value['penyimpanan_detail_id']?>);"><i class="fa fa-trash fa-lg"></i></a>
                                </td>

                            </tr>

                           <? 
                           $no++;
                           }
                           ?>
                           <?
                           if($no==1){
                           ?>
                            <tr id="tNotFounds">
                                <td colspan="10" align="center"> No Display Record  </td>
                            </tr>
                            <?
                             }
                            ?>
                        </tbody>
                        <tfoot>
                           
                        </tfoot>
                    </table>

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
               var win = $.messager.progress({
            title: 'Office Management  | PT Aquamarine Divindo',
            msg: 'proses data...'
        });
            $('#ff').form('submit', {
                url: 'web/penyimpanan_json/add',
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
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/penyimpanan_add?reqId=" + datas[0]);
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
        function tambah_paraf(){
           var tbodyRowCount = $('.reqUrutNama').length+1;
           var total_nomer   =  $('.reqUrutNama').length;
           var names = document.getElementsByName("reqUrutNama[]");
            for (var i = 0; i < total_nomer; i++) {

                names[i].value=((i+1));
            }
             var data = `
                      <tr>
                          <td> <input type="text" disabled readonly  class="form-control reqUrutNama"  name="reqUrutNama[]" value="`+tbodyRowCount+`"> </td>
                          <td> <input type="text" name="reqParafNama[]" class="easyui-validatebox  form-control"  value="" /></td>
                          <td> 
                          <a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a>
                          </td>
                      </tr>      
               `;
               $("#tbodyParaf").append(data);
                  $("#tNotFoundsParaf").remove();
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
                                 <input type="hidden" class="form-control"  name="reqItemId[]"  value=""/>
                                </td>
                                <td> 
                                    <div class="input-group">
                                        <span class="input-group-addon" onclick="LambilStock(`+no+`)"><i class="fa fa-book fa-lg"></i> </span>
                                        <span class="input-group-addon" onclick="clearStock(`+no+`)" ><i class="fa fa-times fa-lg"></i> </span>
                                        <input type="text" class="form-control"  id="reqIdcEquipmentName`+no+`" name="reqIdcEquipmentName[]" value="" 
                                        style=" width:100%"
                                        />
                                        <input type="hidden" class="form-control" id="reqIdcEquipment`+no+`" name="reqIdcEquipment[]"  value=""/>
                                    </div>
                                </td>
                                <td>
                                     
                                <input type="text" class="easyui-validatebox textbox form-control" id="reqEquipPrice`+no+`" name="reqEquipTotal[]" value="0" 
                                onchange="numberWithCommas('reqEquipPrice`+no+`');" onkeyup="numberWithCommas('reqEquipPrice`+no+`');"

                                style=" width:70%" /> 
                              
                                </td>
                               <td> <input type="text" name="masukG[]" class="easyui-validatebox  form-control" value="0" /></td>
                               <td> <input type="text" name="masukR[]" class="easyui-validatebox  form-control" value="0" /></td>
                               <td> <input type="text" name="keluarG[]" class="easyui-validatebox  form-control"  value="0" /></td>
                               <td> <input type="text" name="keluarR[]" class="easyui-validatebox  form-control"  value="0" /></td>
                                <td> <input type="text" name="persedianG[]" class="easyui-validatebox  form-control" value="0" /></td>
                                 <td> <input type="text" name="persedianR[]" class="easyui-validatebox  form-control"  value="0" /></td>
                                 <td> 
                                    <a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a>
                                </td>

                            </tr>

                `;

                $("#tbodyTansaksi").append(data);
                 $("#reqCurrency"+no).combobox();
                 $("#tNotFounds").remove();

        }
        var no_index_elament='';
        function LambilStock(no){
           
            openAdd('app/loadUrl/app/template_stock?');
            no_index_elament=no;
        }

        function ambilStock(element){
           
            $("#reqIdcEquipment"+no_index_elament).val(element[0]);
               $("#reqIdcEquipmentName"+no_index_elament).val(element[4]);

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
</div>
</div>