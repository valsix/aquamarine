<?php
// Header Nama TABEL TH
$aColumns = array(
    "COMPANY_ID", "", "NO.","COMPANY NAME", "ALAMAT", "BARANG JASA DISUPLAY","KONTAK", "HARGA", "TINGKAT_PELAYANAN", "KUALITAS",
    "KETERANGAN","SUPPORT"
);
$arrWidthData= array("2", "2", "2", "20","8","8","8","8","20","8","10");

$arrPost = $this->input->post();
foreach ($arrPost as $key => $value) {
   $_SESSION[$pg.$key] =$value;
}

$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqCariContactPerson = $_SESSION[$pg."reqCariContactPerson"];
$reqCariVasselName = $_SESSION[$pg."reqCariVasselName"];
$reqCariEmailPerson = $_SESSION[$pg."reqCariEmailPerson"];
$reqBarangDisuplay  = $_SESSION[$pg."reqBarangDisuplay"];
$reqLokasi  = $_SESSION[$pg."reqLokasi"];
$reqAlat  = $_SESSION[$pg."reqAlat"];
$reqPart  = $_SESSION[$pg."reqPart"];

if (!empty($reqCariCompanyName)) {
    $statement_privacy .= " AND UPPER(A.NAME) LIKE UPPER('%" . $reqCariCompanyName . "%') ";
}
if (!empty($reqCariContactPerson)) {
    $statement_privacy .= " AND UPPER(A.CP1_NAME) LIKE UPPER('%" . $reqCariContactPerson . "%') ";
}

if (!empty($reqCariEmailPerson)) {
  $statement_privacy .= " AND UPPER(A.EMAIL) LIKE UPPER('%" . $reqCariEmailPerson . "%') ";
}

if(!empty($reqBarangDisuplay)){
     $statement_privacy .= " AND UPPER(A.BARAG_JASA) ='".$reqBarangDisuplay."' ";
}
if(!empty($reqAlat)){
    $statement_privacy .= " 
    AND EXISTS( SELECT 1 FROM SUPPLIER_BARANG CC WHERE CC.SUPPLIER_ID = A.COMPANY_ID 

    AND ( UPPER(CC.NAMA) LIKE UPPER('%" . $reqAlat . "%') 
    OR   UPPER(CC.SERIAL_NUMBER) LIKE UPPER('%" . $reqAlat . "%') )
    )
    ";
}
if(!empty($reqPart)){
    $statement_privacy .= " 
    AND EXISTS( SELECT 1 FROM SUPPLIER_PART CC WHERE CC.SUPPLIER_ID = A.COMPANY_ID::VARCHAR  

    AND ( UPPER(CC.NAMA) LIKE UPPER('%" . $reqAlat . "%') 
    OR   UPPER(CC.SERIAL_NUMBER) LIKE UPPER('%" . $reqAlat . "%') )
    )
    ";
}

if(!empty($reqLokasi)){
    $statement_privacy .= " 
   ( AND EXISTS( SELECT 1 FROM kabupaten CC WHERE CC.propinsi_id = A.propinsi_id 

    AND  UPPER(CC.NAMA) LIKE UPPER('%" . $reqAlat . "%') 
   
    ) OR EXISTS(
    SELECT 1 FROM propinsi CC WHERE CC.kabupaten_id = A.kabupaten_id 

    AND  UPPER(CC.NAMA) LIKE UPPER('%" . $reqAlat . "%') 
   
    )

    )
    ";


}


$reqUniqId= "supplier_list";
$reqParse1 = $this->uri->segment(4, "");
$reqParse1 = $reqParse1?$reqParse1:1;
$reqRuteId = $this->uri->segment(5, "");
$reqRute =  $reqRuteId? '/'.$reqRuteId:''; 
$reqParse2 = $reqParse1-1;
$limit =10;
$from = $reqParse2* $limit;
$reqUrut =$from +1;


?>

<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>


<!-- FIXED AKSI AREA WHEN SCROLLING -->
<link rel="stylesheet" href="css/gaya-stick-when-scroll.css" type="text/css">
<script src="js/stick.js" type="text/javascript"></script>


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
    td {
        white-space: nowrap;
    }

    td.wrapok {
        white-space:normal
    }
     .text-wrap{
    white-space:normal !important;

}
#example_length{
    display: block;
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
    <div class="judul-halaman"> Monitoring Supplier List </div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah </a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit </a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Delete </a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
              <span><a id="btnMaster"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Master Supplay </a></span>
           <!--  <span><a id="btnPrint2"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
            <span><a id="btnExcel"><i class="fa fa-fw fa-file-excel-o " aria-hidden="true"></i> Export Excel </a></span>
            <span><a id="btnExcelAll"><i class="fa fa-fw fa-file-excel-o " aria-hidden="true"></i> Export All Excel </a></span> -->
            <!-- <span><a id="btnSendMail"><i class="fa fa-fw fa-envelope-o" aria-hidden="true"></i> Send Email </a></span> -->
            
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" formnovalidate>
                        <table style="width: 100%">
                            <tr>
                                <td>Company Name </td>
                                <td><input type="text" name="reqCariCompanyName" id="reqCariCompanyName" value="<?= $reqCariCompanyName ?>"></td>
                                <td>Contact Person</td>
                                <td><input type="text" name="reqCariContactPerson" id="reqCariContactPerson" value="<?= $reqCariContactPerson ?>"></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Barang yang di suplay </td>
                                <td>
                                      <input class="easyui-combobox form-control" style="width:40%"   name="reqBarangDisuplay" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_suplay?reqMode=ALL'" value="<?=$reqBarangDisuplay?>" />
                                  </td>
                                <td>Lokasi</td>
                                <td><input type="text" name="reqLokasi" id="reqLokasi" value="<?=$reqLokasi ?>"></td>
                                <td>&nbsp;</td>
                            </tr>
                             <tr>
                                <td>Alat </td>
                                <td><input type="text" name="reqAlat" id="reqAlat" value="<?= $reqAlat ?>"></td>
                                <td>Spare Part</td>
                                <td><input type="text" name="reqPart" id="reqPart" value="<?= $reqPart ?>"></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Email </td>
                                <td><input type="text" name="reqCariEmailPerson" id="reqCariEmailPerson" value="<?= $reqCariEmailPerson ?>"></td>
                                <td colspan="2">&nbsp;</td>
                               
                                <td><button type="submit"   class="btn btn-default"> Searching </button>
                                    <button type="submit" style="display: none" id="btn_cari_filters" class="btn btn-default"> Searching </button></td>
                            </tr>
                            <!-- <tr>
                                <td colspan="5" style="padding: 10px 0px 10px 10px">
                                    <button class="btn btn-default" type="button" onclick="check()"> Check All </button>
                                    <button class="btn btn-default" type="button" onclick="uncheck()"> Uncheck All </button>
                                </td>
                            </tr> -->

                        </table>
                    </form>

                </div>
            </div>
        </div>




        <!-- <div id="parameter-tambahan">
            Bulan : <input id="reqN" class="easyui-combobox" name="reqProvinsi" data-options="url:'provinsi_combo_json/json',
					valueField:'id',
					textField:'text'" style="width:100px;" value="<?= $reqProvinsi ?>">
        </div>
        <br> -->

        <table id="example2" class="table table-striped table-hover2 dt-responsive" cellspacing="0" width="100%" >
            <thead>
                <tr>
                    <th> No </th>
                     <th> COMPANY NAME   </th>
                      <th> ALAMAT   </th>
                       <th>  KONTAK </th>
                     <th> BARANG JASA DISUPLAY </th>
                    
                     <th> HARGA </th>
                     <th> TINGKAT PELAYANAN </th>
                     <th> KUALITAS </th>
                     <tH> KETERANGAN </tH>
                   
                </tr>
            </thead>
            <tbody>
                <?
                $this->load->model("Company");
                $this->load->model("SupplierBarang");
                $this->load->model("SupplierPart");
                $this->load->model('VendorCode');
                $company = new Company();
                $supplierbarang = new  SupplierBarang();
                $supplierpart = new SupplierPart();
                $company->selectByParamsMonitoring(array('A.KATEGORI'=>'SUPPLIER'),-1,-1, $statement_privacy);
                   $total = $company->rowCount;
                $company->selectByParamsMonitoring(array('A.KATEGORI'=>'SUPPLIER'),$limit,$from, $statement_privacy);

                 
                $arrDataSub = $company->rowResult;

             
                $no=1;
                foreach ($arrDataSub as $value) {
                    $reqCompanyId =$value['company_id'];
                    $supplierbarang->selectByParamsMonitoring(array('A.SUPPLIER_ID'=>$reqCompanyId));
                     $supplierpart->selectByParamsMonitoring(array('A.SUPPLIER_ID'=>$reqCompanyId));
                      $arrSupplierBarang = $supplierbarang->rowResult;
                      $arrSupplierPart = $supplierpart->rowResult;
                      
                        

                     $vendorcode = new VendorCode();
                     $vendorcode->selectByParamsMonitoring(array('A.SUPPLIER_ID'=>$reqCompanyId,'A.STATUS_AKTIF'=>'1'));
                     $arrDataVendor = $vendorcode->rowResult;
                      $arrDataVendor =   $arrDataVendor[0];
                     $reqKodeVendor = $arrDataVendor['kode'];

                  $NOMER_URUT=1;   

                ?>    
                <tr id="<?=$reqCompanyId?>" class="trclass">
                    <td> <?=$reqUrut;$reqUrut++?> </td>
                    <td> <?=$value['name']?> <br><em> <?= $reqKodeVendor?></em> </td>
                    <td> <?=$value['address']?>  </td>
                      <td> <?=$value['cp1_name']?>  </td>
                    <td>
                     <?
                     if(empty($arrSupplierBarang[0]['nama'])){}else{
                     echo $NOMER_URUT.'. '.$arrSupplierBarang[0]['nama'];
                     }
                     ?> </td>
                  
                      <td> <?=$arrSupplierBarang[0]['currency']?> <?=currencyToPage2($arrSupplierBarang[0]['harga'])?>  </td>
                    <td> <?=$value['tingkat_pelayang']?> </td>
                    <td> <?=$value['kualitas']?>  </td>
                    <td> <?=$value['keterangan_sub']?>  </td>
                </tr>
                
                <?    
                $no++;  $NOMER_URUT++;
                 for($ll=1;$ll<count($arrSupplierBarang);$ll++){
                ?>
                <tr id="<?=$reqCompanyId?>" class="trclass">
                    <td>  </td>
                     <td>  </td>
                     <td>  </td>
                         <td>  </td>
                     <td><?=$NOMER_URUT.' '.$arrSupplierBarang[$ll]['nama']?>  </td>
                 
                       <td><?=$arrSupplierBarang[0]['currency']?> <?=currencyToPage2($arrSupplierBarang[$ll]['harga'])?>  </td>
                       <td> </td>
                        <td> </td>
                         <td> </td>
                </tr>

                <?
                      $NOMER_URUT++;
                 }  
                }
                ?>
                 <?
                if($no==1){
                ?>
                <tr >
                    <td colspan="9" align="center">  Tidak ada record </td>
                </tr>
                <?
                }
                ?>
               
            </tbody>
        </table>
        <div class="row">
<input type="hidden" id="reqId" value="">
            <div class="col-md-12" style="text-align: center;">

                <div class="pagination" >

                </div>
 <!-- <p> Menampilkan 1 sampai 25 dari 922 data (Menyaring dari 2,388 data) </p> -->
        </div>

    </div>
    </div>


</div>
<form id="myForm" action="app/index/e_commerce" method="post" style="display: none;">
    <input type="text" name="reqKode" id="reqKode">
    <button type="submit" id="clik">Click</button>
</form>
<script type="text/javascript">
     $(document).ready(function() { 
        $(".trclass").click(function() {
            var id = $(this).attr('id');
            $('#reqId').val(id);
            });
          $(".trclass").dblclick(function() {
            var anSelectedId = $(this).attr('id');
            document.location.href = "app/index/supplier_list_add?reqId=" + anSelectedId;
            });
          $('#btnEdit').on('click', function() {
            var anSelectedId = $('#reqId').val();
            if (anSelectedId == "")
                return false;
            document.location.href = "app/index/supplier_list_add?reqId=" + anSelectedId;

        });
          $('#btnDelete').on('click', function() {
            var anSelectedId = $('#reqId').val();
            if (anSelectedId == "")
                return false;
            deleteData('web/customer_json/deleteCompany',anSelectedId);

        });
           $('#btnAdd').on('click', function() {
            
            document.location.href = "app/index/supplier_list_add";

        });
            $('#btnMaster').on('click', function() {
            
            openAdd('app/loadUrl/app/tempalate_master_suplay');

        });
            window.history.replaceState('','',window.location.href);

         });
</script>
<script type="text/javascript">
    $('#select_all').click(function(event) {
        if (this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $(':checkbox').each(function() {
                this.checked = false;
            });
        }
    });
</script>

<script type="text/javascript">
    function check() {
        $(':checkbox').each(function() {
            this.checked = true;
        });
    }


    function uncheck() {
        $(':checkbox').each(function() {
            this.checked = false;
        });
    }


        

    function cetak_print_excel() {
        var ttds = '';
        var kode = '';
        var params = oTable.$('input').serializeArray();
        $.each(params, function(i, field) {
            kode = kode + field.value + ',';
        });

        if (kode == '') {
            return;
        } else {
            openAdd('app/loadUrl/app/tempalate_cetak_company?reqIds=' + kode);
        }
    }

     function cetak_print_excel_all() {
        var ttds = '';
            openAdd('app/loadUrl/app/tempalate_cetak_company_all');
        
    }


    function cetak_print_pdf() {
        var ttds = '';
        var kode = '';
        var params = oTable.$('input').serializeArray();
        $.each(params, function(i, field) {
            kode = kode + field.value + ',';
        });

        if (kode == '') {
            return;
        } else {
            openAdd('report/index/costumer_list_pdf?reqId=' + kode);
        }
    }


    function sendMail() {
        window.location.href = "app/index/e_commerce"
    }


    function importFiles() {
        openAdd('app/loadUrl/app/import_template');
    }


    function company_terpilih() {
        var ttds = '';
        var kode = '';
        var params = oTable.$('input').serializeArray();
        $.each(params, function(i, field) {
            kode = kode + field.value + ',';
        });

        if (kode != '') {
            $("#reqKode").val(kode);
            $("#clik").click();
        }

    }
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
</script>

<!------>