<?php
$this->load->model('PembelianAlat');
$this->load->model('SparePart');
$this->load->model('PartPemakaian');




$pembelianalat = new PembelianAlat();
$partpemakaian = new PartPemakaian();
$sparepart = new SparePart();
$arrPost = $this->input->post();
foreach ($arrPost as $key => $value) {
   $_SESSION[$pg.$key] =$value;
}
$statement = " AND EXISTS(SELECT 1 FROM PEMBELIAN CC WHERE CC.PEMBELIAN_ID = A.PEMBELIAN_ID AND CC.STATUS_POSTING='POSTING')";


$reqCariCategori = $_SESSION[$pg."reqCariCategori"];
$reqCariStorage = $_SESSION[$pg."reqCariStorage"];
$reqCariCurrency = $_SESSION[$pg."reqCariCurrency"];
$reqCariSpesification = $_SESSION[$pg."reqCariSpesification"];
$reqNamaVendor = $_SESSION[$pg."reqNamaVendor"];
$reqNamaProject = $_SESSION[$pg."reqNamaProject"];
$reqNamaPart = $_SESSION[$pg."reqNamaPart"];
$reqLokasi = $_SESSION[$pg."reqLokasi"];


if(!empty($reqCariCategori) && $reqCariCategori !='ALL' ){
    $statement .= " AND B.EC_ID ='".$reqCariCategori."'";
    $statementPart .= " AND A.KATEGORI ='".$reqCariCategori."'";
}

if(!empty($reqNamaPart)){
    $statement .= " AND UPPER(A.NAMA_ALAT) LIKE  '%".strtoupper($reqNamaPart)."%'";
    $statementPart .= " AND UPPER(A.NAMA_PART) LIKE  '%".strtoupper($reqNamaPart)."%'";
}
if(!empty($reqCariStorage)){
    $statement .= " AND UPPER(B.NAMA_ALAT) LIKE  '%".strtoupper($reqCariStorage)."%'";
    $statementPart .= " AND UPPER(A.NAMA_ALAT) LIKE  '%".strtoupper($reqCariStorage)."%'";
}
if(!empty($reqCariSpesification)){
     $statement .= " AND UPPER(A.SERIAL_NUMBER) LIKE  '%".strtoupper($reqCariSpesification)."%'";
}
if(!empty($reqNamaVendor)){
     $statement .= " AND EXISTS( SELECT 1 FROM PEMBELIAN  CC 
        LEFT JOIN COMPANY DD ON DD.COMPANY_ID = CC.COMPANY_ID 
       
        WHERE CC.PEMBELIAN_ID = A.PEMBELIAN_ID AND
        UPPER(DD.NAME) LIKE  '%".strtoupper($reqNamaVendor)."%'
      )";
      
}

if(!empty($reqNamaProject)){
     $statement .= " AND EXISTS( SELECT 1 FROM PEMBELIAN  CC 
        LEFT JOIN MASTER_PROJECT DD ON DD.MASTER_PROJECT_ID = CC.MASTER_PROJECT_ID 
        WHERE CC.PEMBELIAN_ID = A.PEMBELIAN_ID AND
        UPPER(DD.NAMA) LIKE  '%".strtoupper($reqNamaProject)."%'
      )";
      
}

if(!empty($reqLokasi)){
     $statement .= " AND EXISTS( SELECT 1 FROM SPARE_PART  CC 
     
        WHERE CC.CODE = REPLACE(LOWER(A.NAMA_ALAT), ' ', '_') || A.SERIAL_NUMBER || B.EC_ID AND
        CC.LOKASI_ID =  '".$reqLokasi."'
      )";   
      $statementPart .= " AND UPPER(A.LOKASI_ID) ='".$reqLokasi."'";
}


$pembelianalat->selectByParamsMonitoringKelola(array(),-1,-1,$statement);
$arrDataPembelian = $pembelianalat->rowResult;
$arrDataCode = array_column($arrDataPembelian, 'code');
$arrDataCode = array_unique($arrDataCode);


$sparepart2 = new $sparepart();
$sparepart2->selectByParamsMonitoring(array('A.MODEL'=>'baru'),-1,-1,$statementPart);
$arrDataPart2 = $sparepart2->rowResult;
$arrDataCode2 = array_column($arrDataPart2, 'code');
// print_r($arrDataCode2);
$arrDataCode =array_merge($arrDataCode,$arrDataCode2);
?>
<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>

<script type="text/javascript">
  
     $(document).ready(function() {
       $('#btnMaster').on('click', function() {
            openAdd('app/loadUrl/app/tempalate_master_combo?reqModul=SPARE_PART');

        });
       $('#btnAdd').on('click', function() {
        document.location.href = "app/index/spare_part_kelola";

    });

    oTable2=   $('#example2').dataTable({
         "bPaginate": true,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": true,
        "bScrollCollapse": true,
        "bInfo": true,
        "bAutoWidth": false,"bStateSave": true,
            "fnStateSave": function(oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function(oSettings) {
                var data = localStorage.getItem('DataTables_' + window.location.pathname);
                return JSON.parse(data);
            },

});


         window.history.replaceState('','',window.location.href);

       });
</script>
<style type="text/css">
   .text-wrap{
    white-space:normal;
}
</style>

<!-- FIXED AKSI AREA WHEN SCROLLING -->
<link rel="stylesheet" href="css/gaya-stick-when-scroll.css" type="text/css">
<script src="js/stick.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        var s = $("#bluemenu");

        var pos = s.position();
        $(window).scroll(function() {
            var windowpos = $(window).scrollTop();
            //s.html("Distance from top:" + pos.top + "<br />Scroll position: " + windowpos);
            if (windowpos >= pos.top) {
                s.addClass("stick");
                $('#example thead').addClass('stick-datatable');
            } else {
                s.removeClass("stick");
                $('#example thead').removeClass('stick-datatable');
            }
        });

    });
</script>

<!-- <style>
    /** THEAD **/
    thead.stick-datatable th:nth-child(1) {
        width: 440px !important;
        *border: 1px solid cyan;
    }

    /** TBODY **/
    thead.stick-datatable~tbody td:nth-child(1) {
        width: 440px !important;
        *border: 1px solid yellow;
    }
</style> -->
<style type="text/css">
    #tablei tr td {
        padding: 5px;
        font-weight: bold;
    }
</style>

<!-- AREA FILTER TAMBAHAN -->
<script>
    $(document).ready(function() {
         $('#btnRefresh2').on('click', function() {
        window.location.reload();
       



    });
        $(".trclass").click(function() {
            var id = $(this).attr('id');
             var value = $(this).attr('value');

            $('#reqId').val(id);
             $('#reqNumber').val(value);
            
            });
          $(".trclass").dblclick(function() {
            var anSelectedId = $(this).attr('id');
             var v = $(this).attr('value');
            document.location.href = "app/index/spare_part_kelola?reqId=" + anSelectedId+'&v='+v;;
            });
          $('#btnEdit').on('click', function() {
            var anSelectedId = $('#reqId').val();
              var v = $('#reqNumber').val();
            if (anSelectedId == "")
                return false;
            document.location.href = "app/index/spare_part_kelola?reqId=" + anSelectedId+'&v='+v;

        });


        //  $('#btnEdit').on('click', function() {
        // window.location.href='app/index/spare_part_kelola';

   

        $("button.pencarian-detil").click(function() {
            $(".area-filter-tambahan").toggle();
            $("i", this).toggleClass("fa-caret-up fa-caret-down");
        });
    });

</script>
<style>
    .area-filter-tambahan {
        display: none;
    }
     
   
</style>

<div class="col-md-12">

    <div class="judul-halaman"> Pengelolaan Spare Part</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah </a></span>
             <!-- <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Kelola</a></span> -->
            
            <span><a id="btnRefresh2"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
              <span><a id="btnMaster"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Master Spare Part </a></span>
            <!-- <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span> -->
            
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

       <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                       <table id="tablei" style="width: 100%;padding: 10px;margin:10px">

                            <tr>
                                <td> Nama Vendor  </td>
                                 <td><input type="text" name="reqNamaVendor" class="easyui-validatebox textbox form-control" id="reqNamaVendor" value="<?=$reqNamaVendor?>"></td>
                                 <td>  Nama Project  </td>
                                 <td><input type="text" name="reqNamaProject" class="easyui-validatebox textbox form-control" id="reqNamaProject" value="<?=$reqNamaProject?>"></td>
                                  <td>&nbsp;</td>
                            </tr>
                            
                            <tr>
                                <td>Categori </td>
                                <td><input class="easyui-combobox form-control" style="width:100%" name="reqCariCategori" data-options="width:'290', height: '36',editable:false, valueField:'id',textField:'text',url:'combo_json/comboEquipCategori?reqMode=ALL'" id="reqCariCategori" value="<?= $reqCariCategori ?>" /></td>
                                <td>Nama Alat </td>
                                <td><input type="text" name="reqCariStorage" class="easyui-validatebox textbox form-control" id="reqCariStorage" value="<?=$reqCariStorage?>"></td>

                                <td>&nbsp;</td>

                            </tr>
                            <tr>
                                <td>  Nama Part</td>
                                  <td><input type="text" name="reqNamaPart" class="easyui-validatebox textbox form-control" id="reqNamaPart" value="<?=$reqNamaPart?>"></td>
                                <td> Lokasi </td>
                                <td> 
                                     <input class="easyui-combobox form-control" style="width:100%" name="reqLokasi" id="reqLokasi" data-options="width:'290', height: '36',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_lokasi?reqMode=ALL'" id="reqCariCondition" value="<?=$reqLokasi?>" />
                                 </td>
                            </tr>
                           
                           
                            
                            <tr>
                                <td> Serial Number </td>
                                <td colspan="2"><input type="text" name="reqCariSpesification" class="easyui-validatebox textbox form-control" id="reqCariSpesification" value="<?= $reqCariSpesification ?>"></td>
                                <td colspan="2"><button type="submit"   class="btn btn-default"> Searching </button>
 <button type="submit" style="display: none" id="btn_cari_filters" class="btn btn-default"> Searching </button></td>
                            </tr>
                        </table>
                    </form>

                </div>
            </div>
        </div>


        <input type="hidden" id='reqId' value="">
        <input type="hidden" id='reqNumber' value="">
        <table id="example2" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th rowspan="2">No </th>
                    <th rowspan="2">Aksi</th>
                    <th rowspan="2">Kategori </th>
                    <th rowspan="2">Nama Spare Part </th>
                    <th rowspan="2">Serial Number </th>
                    <th rowspan="2">Id Part </th>
                    <th rowspan="2" >Deskripsi </th>
                    <th rowspan="2">Terpasang </th>
                    <th colspan="3"> Stok Spare Part </th>
                    <th colspan="3"> Kondisi Spare Part </th>
                    <th rowspan="2"> Mengetahui </th>
                </tr>
                <tr>
                    <th> Jumlah </th>
                    <th> Keluar </th>
                    <th> Sisa </th>
                     <th> Baik </th>
                    <th> Rusak </th>
                    <th> Lokasi </th>
                </tr>
               
            </thead>
            <tbody>
               <?
               $no=1;
                foreach($arrDataCode as $value){

                    $arrDataFileter = multi_array_search($arrDataPembelian,array('code'=>$value));
                     $arrDataQty = array_column($arrDataFileter, 'qty');
                    $arrColomJumlah = array_sum($arrDataQty);
                    $arrDataFileter2 = $arrDataFileter[0];

                    $arrDataQty = array_column($arrDataFileter, 'qty');
                    $jmltotal = array_sum($arrDataQty);


                  
                    $sparepart->selectByParamsMonitoring(array('A.CODE'=>$value));
                    $arrDataPart = $sparepart->rowResult;
                  
                    $dataVal = $arrDataPart[0];
                 
                    $partpemakaian->selectByParamsMonitoring(array('A.CODE'=>$value));
                    $arrDataPemakaianPart = $partpemakaian->rowResult;
                  
                    $arrDataPakai = array_column($arrDataPemakaianPart, 'jumlah');
                    $arrDataMengetahui = array_column($arrDataPemakaianPart, 'mengetahui');
                    $arrDataMengetahui = array_unique($arrDataMengetahui);
                    $stringMengetahui = implode('<br>', $arrDataMengetahui);
                    $arrDataPakaiPemakain = array();
                    foreach ($arrDataPemakaianPart as  $valuex) {
                     $arrDataPakaiPemakain[$valuex['pemakaian']] +=ifZero2($arrDataPakaiPemakain[$valuex['pemakaian']])+$valuex['jumlah'];
                 }

                 $reqJumlahQty = $dataVal['jumlah'];
                 $reqMode = $dataVal['model'];
                 if($reqMode=='baru'){
                   $arrColomJumlah= $jmltotal = $reqJumlahQty;

                 }

                    $totalPemakaian = array_sum($arrDataPakai);
                    $totalKeluar = $totalPemakaian-ifZero2($arrDataPakaiPemakain['Broken']);
                    $totalKeluar = $totalKeluar +$arrDataPakaiPemakain['Broken'];

                    $reqSisa = $arrColomJumlah-$totalKeluar;
               ?>
                <tr id='<?=$value?>' class='trclass' value='<?=$no?>'>
                    <td class="reqNumber" id='<?=$no?>'> <?=$no?> </td>
                     <td><a href="javascript:void(0)" class="btn btn-warning" onclick="klikRubah('<?=$value?>',<?=$no?>)"><i class="fa fa-fw fa-pencil"></i></a>
                      <?
                      if($reqMode=='baru'){
                      ?>
                      <a href="javascript:void(0)" class="btn btn-danger" onclick="hapusDel('<?=$value?>',<?=$no?>)"><i class="fa fa-fw fa-remove"></i></a>
                      <?
                      }
                      ?>

                     </td>
                    <td><?=$arrDataFileter2['ec_name']?$arrDataFileter2['ec_name']:$dataVal['ec_name']?> </td>
                    <td> <?=$arrDataFileter2['nama_alat']?$arrDataFileter2['nama_alat']:$dataVal['nama_part'] ?> </td>
                    <td> <?=$arrDataFileter2['serial_number']?$arrDataFileter2['serial_number']:$dataVal['serial_number'] ?> </td>
                     <td> <?=$dataVal['id_part']?> </td>
                    <td> <?=$dataVal['desktipsi']?> </td>
                    <td> <?=$arrDataFileter2['nama_part']?$arrDataFileter2['nama_part']:$dataVal['nama_alat']?> </td>
                    <td> <?=$jmltotal?> </td>
                    <td> <?= $totalPemakaian-ifZero2($arrDataPakaiPemakain['Broken'])?> </td>
                    <td> <?=$reqSisa?> </td>
                    <td> <?=ifZero2($arrColomJumlah-$arrDataPakaiPemakain['Broken'])?> </td>
                    <td>  <?=ifZero2($arrDataPakaiPemakain['Broken'])?></td>
                    <td> <?= $dataVal['nama_lokasi']?> </td>
                    <td> <?=$stringMengetahui?> </td>
                </tr>
               <?     
                $no++;
               }
               ?>
            </tbody>
        </table>

    </div>


</div>

<script type="text/javascript">
     function klikRubah(id,no){
        window.location.href='app/index/spare_part_kelola?reqId='+id+'&v='+no;
     }
     function hapusDel(id,no){
        deleteData('web/spare_part_json/delete',id)
     }
</script>
<script type="text/javascript">
   
</script>