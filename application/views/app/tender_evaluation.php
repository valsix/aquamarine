<?php
// Header Nama TABEL TH

$aColumns = array(
            "LAST_UPDATE","INDEX","NAMA_PSC","TITLE","TENDER_NO","CLOSING","OPENING"
        );

 $this->load->model("MasterTenerMenus");
 $this->load->model("TenderEvaluation");
 $master_tener_menus = new MasterTenerMenus();
 $master_tener_menus->selectByParamsMonitoring(array());
 $attData = array();
 while ( $master_tener_menus->nextRow()) {
    array_push($attData , strtoupper($master_tener_menus->getField('NAMA')));
}

$aColumns= array_merge($aColumns,$attData);
$arDataOther = array("STATUS","OWNER","BID_VALUE","TKDN","BID_BOUDS","BID_VALIDATY","NOTES");
$aColumns=    array_merge($aColumns,$arDataOther);


$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqCariContactPerson = $_SESSION[$pg."reqCariContactPerson"];
$reqCariVasselName = $_SESSION[$pg."reqCariVasselName"];
$reqCariEmailPerson = $_SESSION[$pg."reqCariEmailPerson"];


$tender_evaluation = new TenderEvaluation();
$tender_evaluation->selectByParamsMonitoring(array("A.MASTER_TENDER_PERIODE_ID"=>1));

$reqValue= implode($aColumns, ',');


?>

<!-- FIXED AKSI AREA WHEN SCROLLING -->
<link rel="stylesheet" href="css/gaya-stick-when-scroll.css" type="text/css">
<script src="js/stick.js" type="text/javascript"></script>
<!-- <script>
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
 -->
<style>
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
</style>
<style type="text/css">
    #tablei tr td {
        padding: 5px;
        font-weight: bold;
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
     .text-wrap{
    white-space:normal !important;
}
</style>

<div class="col-md-12">

    <div class="judul-halaman"> Tender Evaluation</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
               <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah</a></span>
           <!--  <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span> -->
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span>
            <!-- <span><a id="btnAdd"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span> -->
            <span><a id="btnMasterPsc"><i class="fa fa-fw fa-folder-o" aria-hidden="true"></i> Master PSC  </a></span>
            <span><a id="btnMasterSubMenu"><i class="fa fa-fw fa-folder-o" aria-hidden="true"></i> Master Sub Menu  </a></span>
               <span><a id="btnExcel"><i class="fa fa-fw fa-file-excel-o" aria-hidden="true" ></i> Export Excel  </a></span>
             <span><a id="btnPDF"><i class="fa fa-fw fa-file-pdf-o " aria-hidden="true" ></i> PDF  </a></span>
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td>Tahun </td>
                                <td ><input class="easyui-combobox form-control" style="width:100%" name="reqTahun" id="reqTahun" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/ambil_all_tahun'" value="<?= date('Y') ?>" /></td>

                                <td >Mencari
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="reqCari" value="">
                                </td>
                                <td>
                                    &nbsp;
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>Colom  </td>
                                <td colspan="2"><select  class="easyui-combotree" style="width:400px;" id="combo_tree"
                                        data-options="valueField:'id',textField:'text',url:'combo_json/combo_tree_view?reqId=<?=$reqId?>&reqKategoriId=<?=$reqValue?>', onClick: function(node){clickNode($('#combo_tree'), node,'field_colom');},onCheck: function(node, checked){clickNode($('#combo_tree'), node,'field_colom');},checkbox:true,cascadeCheck:true," 
                                          multiple>
                                           </select>
                                           <input type="hidden" id="field_colom" value="<?=$reqValue?>"></td>
                                <td colspan="2">&nbsp;</td>
                            </tr>
 <tr>
                                <td colspan="4" style="padding: 10px 0px 10px 10px">
                                    <button class="btn btn-default" type="button" onclick="check()"> Check All </button>
                                    <button class="btn btn-default" type="button" onclick="uncheck()"> Uncheck All </button>
                                </td>
                                <td> 
                                    <button type="button"  onclick="filter()()" class="btn btn-default"> Searching </button>
 <button type="submit" style="display: none" id="btn_cari_filters" class="btn btn-default"> Searching </button>
                                </td>
                            </tr>
                          


                        </table>
                    </form>

                </div>
            </div>
        </div>



<div class="col-md-12" style="padding-left: 0;padding-right: 0">
        <div id="append_form">
    
        </div>  
    </div>

    </div>


</div>

<script type="text/javascript">
    function clickNode(cc, id,idField) {
            // console.log(cc);
            var opts = cc.combotree('options');
            var values = cc.combotree('getValues');
            $("#"+idField).val(values);
            
        }
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
    $('#btnAdd').on('click', function() {
         var reqTahun = $('#reqTahun').combobox('getValue');
            openAdd("app/loadUrl/app/template_load_tender_evaluasion?reqPeriode="+reqTahun);

    });
    $('#btnMasterPsc').on('click', function() {
         
            openAdd("app/loadUrl/app/template_add_master_pcs?");

    });
      $('#btnMasterSubMenu').on('click', function() {
        
            openAdd("app/loadUrl/app/template_add_master_tender_menus?");

    });
     $('#btnExcel').on('click', function() {
         var reqTahun = $('#reqTahun').combobox('getValue');
         var reqVieldColomn= $('#field_colom').val();
         var result = "";
         $('#example input:checked').each(function(item){
            result += $(this).val()+',';
        });


          window.location.href='app/loadUrl/app/excel_tender_evaluasi?reqId='+reqTahun+"&reqColomn="+reqVieldColomn+"&reqEvalusiId="+result;

    });

    $('#btnPDF').on('click', function() {
         var reqTahun = $('#reqTahun').combobox('getValue');
         var reqVieldColomn= $('#field_colom').val();
         var result = "";
         $('#example input:checked').each(function(item){
            result += $(this).val()+',';
        });


          // window.location.href='app/loadUrl/app/excel_tender_evaluasi?reqId='+reqTahun+"&reqColomn="+reqVieldColomn+"&reqEvalusiId="+result;

          openAdd("app/loadUrl/report/tender_monitoring_evaluasi_pdf?reqId="+reqTahun+"&reqColomn="+reqVieldColomn+"&reqEvalusiId="+result);

    });


    
     $('#btnDelete').on('click', function() {
         var result = "";
         $('#example input:checked').each(function(item){
            result += $(this).val()+',';
        });

        if(result !=""){
            deleteData('web/tender_evaluation_json/delete',result);
        } 
        // console.log(result);

    });

     function editing(id) {
           
            // var elements = oTable.fnGetData(id);
           var reqTahun = $('#reqTahun').combobox('getValue');
            openAdd("app/loadUrl/app/template_load_tender_evaluasion?reqPeriode="+reqTahun+"&reqDetailId="+id);
       

    }

    function deleting(id){
         // var elements = oTable.fnGetData(id);
          deleteData('web/tender_evaluation_json/delete',id);
    }

     function reload_table() {
            // oTable.api().ajax.reload(null,false);
            filter();
        }
    function filter(){
        var reqId = $('#field_colom').val();
        var reqFieldCari = $('#reqCari').val();
        var reqTahun = $('#reqTahun').combobox('getValue');
          $("#append_form").empty();
         $.get("app/loadUrl/app/template_load_tender_daftar?reqId="+reqId+"&reqCari="+reqFieldCari+"&reqTahun="+reqTahun, function(data) {
                $("#append_form").append(data);
            });

    }
</script>
<script type="text/javascript">
    setTimeout(function(){
   filter();
     }, 1000);
</script>
<script type="text/javascript">
   $(document).on("dblclick","#example tbody tr",function() {
      var trid = $(this).closest('tr').attr('id'); 
        var reqTahun = $('#reqTahun').combobox('getValue');
            openAdd("app/loadUrl/app/template_load_tender_evaluasion?reqPeriode="+reqTahun+"&reqDetailId="+trid);

});
</script>