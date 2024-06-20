<?php
// Header Nama TABEL TH
// $aColumns =array("PEMBELIAN_ID","TANGGAL","PEMBELIAN","NAMA_SUPPLIER","ALAMAT_SUPPLIER","NAMA_PROJECT","QTY","TOTAL","PEMBAYARAN");
 $aColumns = array("PEMBELIAN_ID","TANGGAL","PEMBELIAN DETAIL");
$categori = "pms_equip_detil";
$add_str = "reqKategori=" . $categori;

$arrPost = $this->input->post();
foreach ($arrPost as $key => $value) {
   $_SESSION[$pg.$key] =$value;
}


$reqCariName = $_SESSION[$pg."reqCariName"];
$reqCariIdNumber = $_SESSION[$pg."reqCariIdNumber"];
$reqCariCondition = $_SESSION[$pg."reqCariCondition"];
$reqCariCategori = $_SESSION[$pg."reqCariCategori"];
$reqCariStorage = $_SESSION[$pg."reqCariStorage"];
$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqCariCurrency = $_SESSION[$pg."reqCariCurrency"];
$reqCariPembayaran = $_SESSION[$pg."reqCariPembayaran"];
$reqCariSpesification = $_SESSION[$pg."reqCariSpesification"];
$reqCariIncomingDateFrom = $_SESSION[$pg."reqCariIncomingDateFrom"];
$reqCariIncomingDateTo = $_SESSION[$pg."reqCariIncomingDateTo"];
$reqCariSerialNumber = $_SESSION[$pg."reqCariSerialNumber"];
$reqCariPart = $_SESSION[$pg."reqCariPart"];
$reqNoVoucher = $_SESSION[$pg."reqNoVoucher"];
$reqKodeProject = $_SESSION[$pg."reqKodeProject"];
if (!empty($reqCariIdNumber)) {

    $statement_privacy  .= " AND  UPPER(B.NAME) LIKE '%".strtoupper($reqCariIdNumber)."%'  ";
}

if (!empty($reqCariCondition)) {

    $statement_privacy  .= " AND A.MASTER_PROJECT_ID='".$reqCariCondition."' ";
}
if (!empty($reqCariCategori) &&  $reqCariCategori !='ALL' ) {

    $statement_privacy  .= " AND EXISTS(
    SELECT 1 FROM PEMBELIAN_DETAIL UJ
    LEFT JOIN EQUIPMENT_LIST KL ON KL.EQUIP_ID = UJ.EQUIP_ID
    WHERE UJ.EC_ID ='".$reqCariCategori."' AND UJ.PEMBELIAN_ID = A.PEMBELIAN_ID
) ";
}

if (!empty($reqCariStorage)) {

 $statement_privacy  .= " AND EXISTS(
    SELECT 1 FROM PEMBELIAN_ALAT UJ
   
    WHERE 1=1  AND  UPPER(UJ.NAMA_ALAT) LIKE '%".strtoupper($reqCariStorage)."%'    AND UJ.PEMBELIAN_ID = A.PEMBELIAN_ID
) ";

 
}
if (!empty($reqCariCompanyName)) {

     $statement_privacy  .= " AND EXISTS(
    SELECT 1 FROM PEMBELIAN_DETAIL UJ
    LEFT JOIN EQUIPMENT_LIST KL ON KL.EQUIP_ID = UJ.EQUIP_ID
    WHERE 1=1 AND  UPPER(UJ.NAMA_ALAT) LIKE '%".strtoupper($reqCariCompanyName)."%'   AND UJ.PEMBELIAN_ID = A.PEMBELIAN_ID
) ";

   
}


if (!empty($reqCariCurrency)) {

    $statement_privacy  .= " AND A.CURRENCY='".$reqCariCurrency."' ";
}
if (!empty($reqCariPembayaran)) {

    $statement_privacy  .= " AND A.JENIS_PEMBAYARAN='".$reqCariPembayaran."' ";
}

if (!empty($reqCariIncomingDateFrom) && !empty($reqCariIncomingDateTo)) {
    $statement_privacy  .= " AND A.TANGGAL BETWEEN TO_DATE('" . $reqCariIncomingDateFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariIncomingDateTo . "','dd-mm-yyyy') ";
}

if (!empty($reqCariSpesification)) {

   $statement_privacy  .= " AND  UPPER(A.NO_PO) LIKE '%".strtoupper($reqCariSpesification)."%'  ";
}

if (!empty($reqNoVoucher)) {

   $statement_privacy  .= " AND  UPPER(A.VOUCHER) LIKE '%".strtoupper($reqNoVoucher)."%'  ";
}

if(!empty($reqKodeProject)){

   $statement_privacy  .= " AND EXISTS(
    SELECT 1 FROM MASTER_PROJECT UJ
  
    WHERE 1=1 AND  UPPER(UJ.CODE) LIKE '%".strtoupper($reqKodeProject)."%'   AND UJ.MASTER_PROJECT_ID = A.MASTER_PROJECT_ID
) ";
}
    
if (!empty($reqCariPart)) {

  $statement_privacy  .= " AND EXISTS(
    SELECT 1 FROM PEMBELIAN_ALAT UJ
   
    WHERE 1=1  AND  UPPER(UJ.SERIAL_NUMBER) LIKE '%".strtoupper($reqCariPart)."%'    AND UJ.PEMBELIAN_ID = A.PEMBELIAN_ID
) ";
}    

if (!empty($reqCariSerialNumber)) {

   
     $statement_privacy  .= " AND EXISTS(
    SELECT 1 FROM PEMBELIAN_DETAIL UJ
  
    WHERE 1=1 AND  UPPER(UJ.SERIAL_NUMBER) LIKE '%".strtoupper($reqCariSerialNumber)."%'   AND UJ.PEMBELIAN_ID = A.PEMBELIAN_ID
) ";
}    

$_SESSION['reqPembelianSession']=$statement_privacy;

$reqUniqId= "pembelian";
$reqParse1 = $this->uri->segment(4, "");
$reqParse1 = $reqParse1?$reqParse1:1;
$reqRuteId = $this->uri->segment(5, "");
$reqRute =  $reqRuteId? '/'.$reqRuteId:''; 
$reqParse2 = $reqParse1-1;
$limit =10;

    $from = $reqParse2* $limit;

$this->load->model('Pembelian');
$this->load->model('PembelianDetail');
$this->load->model('PembelianAlat');







$pembelian =new Pembelian();
$pembelian->selectByParamsMonitoring(array(),$limit,$from, $statement_privacy,' ORDER BY TANGGAL DESC');
// ECHO $pembelian->query;
$arrDataPembelian = $pembelian->rowResult;


$pembelian =new Pembelian();
$pembelian->selectByParamsMonitoring(array(),-1,-1, $statement_privacy);
$total = $pembelian->rowCount;
$totalPembelian = $pembelian->rowCount;
 
$pembeliandetail =new PembelianDetail();
$pembeliandetail->selectByParamsMonitoring(array());
$arrDataPembelianDetail = $pembeliandetail->rowResult;

$pembelianalat =new PembelianAlat();
$pembelianalat->selectByParamsMonitoring(array());
$arrDataPembelianAlat = $pembelianalat->rowResult;

// $reqUrut =$from +1;
$reqUrut = $totalPembelian - $from ;
?>

<script type="text/javascript">
     $(document).ready(function() {
       $('#btnMaster').on('click', function() {
            openAdd('app/loadUrl/app/tempalate_master_project');

        });
       $('#btnMasterAlat').on('click', function() {
            openAdd('app/loadUrl/app/tempalate_master_alat');

        });
       $('#btnAdd').on('click', function() {
        window.location.href='app/index/pembelian_add';

    });
          $('#btnRefresh').on('click', function() {
        window.location.reload();

    });
           $('#btnPrint').on('click', function() {
      openAdd('report/loadUrl/report/cetak_pembelian_list_pdf');

    });
       });
</script>

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
     .text-wrap{
    white-space:normal;
}
.biruMuda{
 background-color: #42f5dd !important; 
}
.KuningMuda{
 background-color: #f1f76f !important; 
}
.hijauMuda2{
 background-color: #42f554 !important; 
}
.hijauMuda{
 /*background-color: #42f554 !important; */
}
</style>

<!-- AREA FILTER TAMBAHAN -->
<script>
    $(document).ready(function() {
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
    table.table.table-striped.table-hover2.dt-responsive thead th {
        background: #4259c1 !important;
  color: #FFFFFF;
  *color: #333333;
  font-family: 'avenir-next-demibold';
    }
    table tbody tr td {
  *color: #FFFFFF;
  background: #FFFFFF;
  border-bottom: 1px solid rgba(0,0,0,0.1);
}
</style>

<div class="col-md-12">

    <div class="judul-halaman"> Pembelian Barang dan Jasa</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah</a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
              <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
            <span><a id="btnMaster"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Master Project </a></span>
            <!-- <span><a id="btnMasterAlat"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Master Alat </a></span> -->
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
                                <td>Company of Name  </td>
                                <td><input type="text" name="reqCariIdNumber" class="easyui-validatebox textbox form-control" id="reqCariIdNumber" value="<?= $reqCariIdNumber ?>"  ></td>
                                <td>Proyek </td>
                                <td><input class="easyui-combobox form-control" style="width:100%" name="reqCariCondition" id="reqCariCondition" data-options="width:'290', height: '36',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_project?reqMode=ALL'" id="reqCariCondition" value="<?= $reqCariCondition ?>" /></td>

                                <td>&nbsp;


                                </td>

                            </tr>
                            <tr>
                                <td>Categori </td>
                                <td><input class="easyui-combobox form-control" style="width:100%" name="reqCariCategori" data-options="width:'290', height: '36',editable:false, valueField:'id',textField:'text',url:'combo_json/comboEquipCategori?reqMode=ALL'" id="reqCariCategori" value="<?= $reqCariCategori ?>" /></td>
                                <td>Nama Alat </td>
                                <td><input type="text" name="reqCariStorage" class="easyui-validatebox textbox form-control" id="reqCariStorage" value="<?=$reqCariStorage?>"></td>

                                <td>&nbsp;


                                </td>

                            </tr>
                              <tr>
                                <td>Serial Number </td>
                                <td><input type="text" name="reqCariSerialNumber" class="easyui-validatebox textbox form-control" id="reqCariSerialNumber" value="<?=$reqCariSerialNumber?>"></td>
                                <td>Part </td>
                                <td><input type="text" name="reqCariPart" class="easyui-validatebox textbox form-control" id="reqCariPart" value="<?=$reqCariPart?>"></td>

                                <td>&nbsp;


                                </td>

                            </tr>
                            <tr>
                                <td>Equipment Name </td>
                                <td><input type="text" name="reqCariCompanyName" class="easyui-validatebox textbox form-control" id="reqCariCompanyName" value="<?= $reqCariCompanyName ?>"></td>
                                <td>Incoming Date </td>
                                <td><input type="text" name="reqCariIncomingDateFrom" id="reqCariIncomingDateFrom" class="easyui-datebox " value="<?=$reqCariIncomingDateFrom?>" data-options="width:'150', height: '36'"> To <input type="text" name="reqCariIncomingDateTo" id="reqCariIncomingDateTo" class="easyui-datebox " value="<?=$reqCariIncomingDateTo?>" data-options="width:'150', height: '36'"></td>

                                <td>&nbsp;


                                </td>

                            </tr>

                            <tr>
                                <td>Currency </td>
                                <td><input class="easyui-combobox form-control" style="width:100%" name="reqCariCurrency" data-options="width:'290', height: '36',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar?reqMode=ALL'" id="reqCariCurrency" value="<?= $reqCariCurrency ?>" /></td>
                                <td>Pembayaran </td>
                                <td><input class="easyui-combobox form-control" style="width:100%" name="reqCariPembayaran" data-options="width:'290', height: '36',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_pembayaran?reqMode=ALL'" id="reqCariPembayaran" value="<?= $reqCariPembayaran ?>" /></td>

                                <td>&nbsp;


                                </td>

                            </tr>
                            <tr>
                              <td>Voucher</td>
                              <td> <input type="text" name="reqNoVoucher" class="easyui-validatebox textbox form-control" id="reqNoVoucher" value="<?= $reqNoVoucher ?>"> </td>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                              <td>Code Project</td>
                              <td> <input type="text" name="reqKodeProject" class="easyui-validatebox textbox form-control" id="reqKodeProject" value="<?= $reqKodeProject ?>"> </td>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            
                            
                            <tr>
                                <td> No. Po </td>
                                <td colspan="2"><input type="text" name="reqCariSpesification" class="easyui-validatebox textbox form-control" id="reqCariSpesification" value="<?= $reqCariSpesification ?>"></td>
                                <td colspan="2"><button type="submit"   class="btn btn-default"> Searching </button>
 <button type="submit" style="display: none" id="btn_cari_filters" class="btn btn-default"> Searching </button></td>
                            </tr>
                        </table>
                    </form>

                </div>
            </div>
        </div>



        <div class="table-responsive" style="width: 2000px">
        <table id="example2" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%"  >
            <thead >
                <tr>
                    <th width="5%">NO</th>
                   
                     <th width="10%">TANGGAL <br>/ NO. PEMBELIAN</th>
                      <th width="10%">KETEGORI ALAT</th>
                       <th width="20%">NAMA BARANG</th>
                          <th width="20%">SERIAL NUMBER <br> / DESCRIPTION</th>
                        <th width="20%"> VENDOR</th>
                        <th width="25%">PROJECT / NO PO</th>
                          <th width="15%">VOUCHER</th>
                          <th width="10%">HARGA</th>
                           <th width="5%">QTY</th>
                           <th width="10%">TOTAL</th>
                             <th width="10%">PEMBAYARAN</th>
                 
            </thead>
            <tbody>
                <?
                $no=1;
                foreach ($arrDataPembelian as  $value) {
                    $reqPembelianId = $value['pembelian_id'];
                    $status_posting = $value['status_posting'];
                     $jenis_pembayaran = $value['jenis_pembayaran'];
                    $arrFilterDetail = multi_array_search($arrDataPembelianDetail,array("pembelian_id"=>$reqPembelianId));
                    $noAlat = $noPart =1;
                    $arrFilterDetail_1 = $arrFilterDetail[0];
                    $reqPembelianDetailId = $arrFilterDetail_1['pembelian_detail_id'];
                    $arrFilterDetailAlat = multi_array_search($arrDataPembelianAlat,array("pembelian_detail_id"=>$reqPembelianDetailId));

                    // $arrFilterDetailAlat2 = multi_array_search($arrDataPembelianAlat,array("pembelian_id"=>$reqPembelianId));

                    //  $total = count($arrFilterDetail);
                    //  $total_alat = count($arrFilterDetailAlat2);

                    //  $rowSpan = ($total + $total_alat) -1 ;
                    // $LLL=1;

                    $total_qty =$total_total= 0;
                    $total_qty +=$arrFilterDetail_1['qty'];
                    $total_total +=$arrFilterDetail_1['total'];

                    $reqCurrency = $arrFilterDetail_1['currency'];

                    $color = 'biruMuda';
                    if(empty($status_posting)){
                       $color = 'KuningMuda';
                    }else{
                      $color='';
                      if($jenis_pembayaran=='KREDIT'){
                      $color = 'hijauMuda2';
                      }
                    }
                ?>
              
                <tr id="<?=$reqPembelianId?>" class="trclass ">
                    <td class="<?=$color?>"> <?=$reqUrut;$reqUrut--?></td>
                      
                     <td class="<?=$color?>"> <?=$value['tanggal']?> <br> <?=$value['no_pembelian']?></td>
                     <td class="<?=$color?>"> <?=$noAlat;$noAlat++;?>.<?=$arrFilterDetail_1['nama_kategori']?></td>
                     <td class="<?=$color?>">   <?=$arrFilterDetail_1['nama_alat']?></td>
                     <td class="<?=$color?>"> <?=$arrFilterDetail_1['no_seri']?> <br> <?=$arrFilterDetail_1['deskripsi']?> </td>
                     <td class="<?=$color?>"> <?=$value['nama_supplier']?><br><?=$value['address']?></td>
                    
                     <td class="<?=$color?>"><?=$value['nama_project']?> <br> <?=$value['code']?> - <?=$value['nama_project_ket']?>
                     
                      </td>
                      <td class="<?=$color?>"> <?=$value['voucher']?> </td>
                     <td class="<?=$color?>"> <?=currencyToPage2($arrFilterDetail_1['harga'])?></td>
                     <td class="<?=$color?>"> <?=$arrFilterDetail_1['qty']?></td>
                     <td class="<?=$color?>" align='right'> <?=currencyToPage2($arrFilterDetail_1['total'])?></td>
                      <td class="<?=$color?>"> <?=$value['jenis_pembayaran']?><br><?=$value['tanggal_bayar']?></td>
                </tr>
                <?
                $noPart=1;
                foreach ($arrFilterDetailAlat as $aVal) {
             $total_qty +=$aVal['qty'];
                    $total_total +=$aVal['total'];
                
                ?>
                <tr id="<?=$reqPembelianId?>" class="trclass">
                    <td class="hijauMuda"> </td>
                    
                     <td class="hijauMuda"> </td>
                     <td class="hijauMuda"> </td>
                     <td class="hijauMuda">  <?=$noPart;$noPart++?>. <?=$aVal['nama_alat']?></td>
                     <td class="hijauMuda"> <?=$aVal['serial_number']?> <br><?=$aVal['deskipsi']?>  </td>
                     <td class="hijauMuda"> </td>
                    
                     <td class="hijauMuda">  </td>
                      <td class="hijauMuda">  </td>
                     <td class="hijauMuda" align='right'> <?=currencyToPage2($aVal['harga'])?></td>
                     <td class="hijauMuda"> <?=$aVal['qty']?></td>
                     <td class="hijauMuda" align='right'> <?=currencyToPage2($aVal['total'])?></td>
                      <td class="hijauMuda">  </td>

                </tr>
                <?
                }
                ?>


                <?
                for($kk=1;$kk<count($arrFilterDetail);$kk++){
                    $tVal = $arrFilterDetail[$kk];
                    $reqPembelianDetailId = $tVal['pembelian_detail_id'];
                    $arrFilterDetailAlat = multi_array_search($arrDataPembelianAlat,array("pembelian_detail_id"=>$reqPembelianDetailId));

                     $total_qty +=$tVal['qty'];
                    $total_total +=$tVal['total'];
                 ?>
                 <tr id="<?=$reqPembelianId?>" class="trclass">
                    <td class="<?=$color?>"> </td>
                     
                     <td class="<?=$color?>"></td>
                     <td class="<?=$color?>"> <?=$noAlat;$noAlat++;?>. <?=$tVal['nama_kategori']?></td>
                     <td class="<?=$color?>">  <?=$tVal['nama_alat']?></td>
                     <td class="<?=$color?>"> <?=$tVal['no_seri']?> <br> <?=$tVal['deskripsi']?></td>
                     <td class="<?=$color?>"> </td>
                    
                     <td class="<?=$color?>"> </td>
                       <td class="<?=$color?>"> </td>
                     <td class="<?=$color?>" align='right'> <?=currencyToPage2($tVal['harga'])?></td>
                     <td class="<?=$color?>"> <?=$tVal['qty']?></td>
                     <td class="<?=$color?>" align='right'> <?=currencyToPage2($tVal['total'])?></td>
                      <td class="<?=$color?>">  </td>
                </tr>


                        <?
                         $noPart=1;
                        foreach ($arrFilterDetailAlat as $aVal) {
                           $total_qty +=$aVal['qty'];
                    $total_total +=$aVal['total'];
                        
                        ?>
                        <tr id="<?=$reqPembelianId?>" class="trclass">
                            <td class="hijauMuda"> </td>
                           
                             <td class="hijauMuda"> </td>
                             <td class="hijauMuda"> </td>
                             <td class="hijauMuda"> <?=$noPart;$noPart++;?>. <?=$aVal['nama_alat']?></td>
                             <td class="hijauMuda"> <?=$aVal['serial_number']?><br><?=$aVal['deskipsi']?></td>
                             <td class="hijauMuda"> </td>
                            
                             <td class="hijauMuda">  </td>
                              <td class="hijauMuda">  </td>
                             <td class="hijauMuda" align='right'> <?=currencyToPage2($aVal['harga'])?></td>
                             <td class="hijauMuda"> <?=$aVal['qty']?></td>
                             <td class="hijauMuda" align='right'> <?=currencyToPage2($aVal['total'])?></td>
                              <td class="hijauMuda">  </td>
                        </tr>
                        <?

                        }
                        ?>

                 
                <?   
                }

                $reqPpn = $value["ppn"];
                $reqPpnPercent =$value["ppn_val"];
                if( $reqPpn ==1){
                $reqTotal_persen =( $reqPpnPercent *$total_total )/100;
               
                ?>
                 <tr id="<?=$reqPembelianId?>" class="trclass">
                    <td colspan="9" align="right" valign="top">  <b>Total</b> </td>
                     <td valign="center"><?=$total_qty?></td>
                      <td colspan="2" valign="center"> <?=currencyToPage2($total_total)?></td>
                </tr>
                <?
                 $total_total = $total_total+$reqTotal_persen;
                ?>
                <tr id="<?=$reqPembelianId?>" class="trclass">
                    <td colspan="9" align="right" valign="top">  <b>Ppn ( <?=$reqPpnPercent?>% )</b> </td>
                     <td valign="center">-</td>
                      <td colspan="2" valign="center"> <?=currencyToPage2($reqTotal_persen)?></td>
                </tr>
                <?
                }
                ?>

                <tr id="<?=$reqPembelianId?>" class="trclass">
                    <td colspan="9" align="right" valign="top">  <b>Grand Total</b> </td>
                     <td valign="center"> <?=$total_qty?></td>
                      <td colspan="2" valign="right"> <?=currencyToPage2($total_total)?></td>
                </tr>
                <tr id="<?=$reqPembelianId?>" class="trclass">
                   
                    <td colspan="11" align="right" valign="top"> <b><em> 
                                    <?
                                    if($reqCurrency=='IDR'){
                                    ?>
                                    <?=kekata($total_total)?> Rupiah
                                    <?
                                    }else{
                                    ?>
                                     <?=kekata_eng($total_total)?> Dollar
                                    <?
                                    }
                                    ?>
                                    </em>

                                </b> </td>
                                 <td></td>
                   
                </tr>

                <?
               
                $no++;
                }
                ?>
                <?
                if($no==1){
                ?>
                <tr >
                    <td colspan="12" align="center">  Tidak ada record </td>
                </tr>
                <?
                }
                ?>
            </tbody>
        </table>
    </div>
        <input type="hidden" id="reqId" value="">
        <div class="row">

            <div class="col-md-12" style="text-align: center;">

                <div class="pagination" >

                </div>
 <!-- <p> Menampilkan 1 sampai 25 dari 922 data (Menyaring dari 2,388 data) </p> -->
        </div>

    </div>


</div>
<script type="text/javascript">
     var     otable;
     $(document).ready(function() { 

         $(".trclass").click(function() {
            var id = $(this).attr('id');
            $('#reqId').val(id);
            });
          $(".trclass").dblclick(function() {
            var anSelectedId = $(this).attr('id');
            document.location.href = "app/index/pembelian_add?reqId=" + anSelectedId;
            });
          $('#btnEdit').on('click', function() {
            var anSelectedId = $('#reqId').val();
            if (anSelectedId == "")
                return false;
            document.location.href = "app/index/pembelian_add?reqId=" + anSelectedId;

        });
          $('#btnDelete').on('click', function() {
            var anSelectedId = $('#reqId').val();
            if (anSelectedId == "")
                return false;
            deleteData('web/pembelian_json/delete',anSelectedId);

        });
         

        table= $('#example3').DataTable({
            columnDefs: [{
                className: 'never',
                targets: [1]
            },{
            "bSort": true,"targets": 0,
               data: "status",
               "type": "num",
               "bScrollInfinite": true,
               "sPaginationType": "full_numbers",
               "bStateSave": true,
           }
           ],
           "iDisplayLength": 25,
           drawCallback: function (settings) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;

            api.rows({page:'current'} ).data().each( function ( data, i ) {
                var group = data[1];
                 var tanggal = data[2];
                  var jenis = data[3];
             var groupLink = '<a href="' + data[2] + '">' + $('<div>').text(group).html() + '</a>';

             if ( last !== group ) {
              $(rows).eq( i ).before(
               '<tr class="group"><td >'+group+'</td><td >'+tanggal+'</td><td colspan="10">'+jenis+'</td></tr>'
               );

              last = group;
          }
      });
        }
        },);
         window.history.replaceState('','',window.location.href);

     });
</script>


<script type="text/javascript">
   
    function getPageList(totalPages, page, maxLength) {
    if (maxLength < 5) throw "maxLength must be at least 5";

    function range(start, end) {
        return Array.from(Array(end - start + 1), (_, i) => i + start); 
    }

    var sideWidth = maxLength < 9 ? 1 : 2;
    var leftWidth = (maxLength - sideWidth*2 - 3) >> 1;
    var rightWidth = (maxLength - sideWidth*2 - 2) >> 1;
    if (totalPages <= maxLength) {
        // no breaks in list
        return range(1, totalPages);
    }
    if (page <= maxLength - sideWidth - 1 - rightWidth) {
        // no break on left of page
        return range(1, maxLength - sideWidth - 1)
            .concat(0, range(totalPages - sideWidth + 1, totalPages));
    }
    if (page >= totalPages - sideWidth - 1 - rightWidth) {
        // no break on right of page
        return range(1, sideWidth)
            .concat(0, range(totalPages - sideWidth - 1 - rightWidth - leftWidth, totalPages));
    }
    // Breaks on both sides
    return range(1, sideWidth)
        .concat(0, range(page - leftWidth, page + rightWidth),
                0, range(totalPages - sideWidth + 1, totalPages));
}

<?

?>

$(function () {
    // Number of items and limits the number of items per page
    var numberOfItems = <?=$total?>;
    var limitPerPage = <?=$limit?>;
    // Total pages rounded upwards
    var totalPages = Math.ceil(numberOfItems / limitPerPage);
    // Number of buttons at the top, not counting prev/next,
    // but including the dotted buttons.
    // Must be at least 5:
    var paginationSize = 7; 
    var currentPage;

    function showPage(whichPage) {
        if (whichPage < 1 || whichPage > totalPages) return false;
        currentPage = whichPage;
        $("#jar .content").hide()
            .slice((currentPage-1) * limitPerPage, 
                    currentPage * limitPerPage).show();
        // Replace the navigation items (not prev/next):            
        $(".pagination li").slice(1, -1).remove();
        getPageList(totalPages, currentPage, paginationSize).forEach( item => {
            $("<li>").addClass("page-item")
                     .addClass(item ? "current-page" : "disabled")
                     .toggleClass("active", item === currentPage).append(
                $("<a>").addClass("page-link").attr({
                    href: "app/index/<?=$reqUniqId?>/"+item+"<?=$reqRute?>"}).text(item || "...")
            ).insertBefore("#next-page");
        });
        // Disable prev/next when at first/last page:
        $("#previous-page").toggleClass("disabled", currentPage === 1);
        $("#next-page").toggleClass("disabled", currentPage === totalPages);
        return true;
    }

    // Include the prev/next buttons:
    $(".pagination").append(
        $("<li>").addClass("page-item").attr({ id: "previous-page" }).append(
            $("<a>").addClass("page-link").attr({
                href: "app/index/<?=$reqUniqId?>/<?=($reqParse1-1).$reqRute?>"}).text("Prev")
        ),
        $("<li>").addClass("page-item").attr({ id: "next-page" }).append(
            $("<a>").addClass("page-link").attr({
                href: "app/index/<?=$reqUniqId?>/<?=($reqParse1+1).$reqRute?>"}).text("Next")
        )
    );
    // Show the page links
    $("#jar").show();
    showPage(<?=$reqParse1?>);

    // Use event delegation, as these items are recreated later    
    $(document).on("click", ".pagination li.current-page:not(.active)", function () {
        return showPage(+$(this).text());
    });
    $("#next-page").on("click", function () {
        return showPage(currentPage+1);
    });

    $("#previous-page").on("click", function () {
        return showPage(currentPage-1);
    });
});
</script>



<!------>