<?php
// Header Nama TABEL TH
$aColumns =array("PENYIMPANAN_ID","TANGGAL","NAMA_PARAF","QTY","MASUK_G","MASUK_R","KELUAR_G","KELUAR_R","PERSEDIAN_G","PERSEDIAN_R");
$categori = "pms_equip_detil";
$add_str = "reqKategori=" . $categori;


$reqCariName = $_SESSION[$pg."reqCariName"];
$reqCariDescription = $_SESSION[$pg."reqCariDescription"];
$reqJenis = $_SESSION[$pg."reqJenis"];

$this->load->model("DokumenQm");
$offer = new DokumenQm();
$statement =  $_SESSION[$pg."reqCariSession"];
$total_row = $offer->getCountByParams(array(),$statement);

?>
<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" language="javascript" class="init">
    var oTable;
    
    $(document).ready(function() {
          
        oTable = $('#example').dataTable({
            "oSearch": {"sSearch": sessionStorage.getItem("<?=$pg?>_sSearch") || ""},
            bJQueryUI: true,
            "iDisplayLength": 25,
            /* UNTUK MENGHIDE KOLOM ID */
            "aoColumns": [{
                    bVisible: false
                },
                <?
                for($i=1;$i<count($aColumns)-1;$i++){
                    echo 'null,';
                }
                ?>
                null
                
            ],
            "bSort": true,
            "bProcessing": true,
            "bServerSide": true,
            "responsive": false,
            "bScrollInfinite": true,
            "sAjaxSource": "web/penyimpanan_json/json?<?= $add_str ?>&pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0]
            },
            {
"targets": 1,
"orderable": false
}],
            "bStateSave": true,
            "fnStateSave": function(oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function(oSettings) {
                var data = localStorage.getItem('DataTables_' + window.location.pathname);
                return JSON.parse(data);
            },
            "fnServerParams": function ( aoData ) {
                    aoData.push( { "name": "reqCariNoOrder", "value": $("#reqCariNoOrder").val() } );
                  
                },
            "sPaginationType": "full_numbers",
        
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
               
                // if (aData[13] == "2") {
                //     $('td', nRow).addClass('redClass');
                // } 
                
            }


        });
        
        oTable.on('search.dt', function (e, settings) {
            sessionStorage.setItem("<?=$pg?>_sSearch", $('.dataTables_filter input').val())
        });
        /* Click event handler */

        /* RIGHT CLICK EVENT */
        var anSelectedData = '';
        var anSelectedId = '';
        var anSelectedDownload = '';
        var anSelectedPosition = '';
        var anIndex = '';

        function fnGetSelected(oTableLocal) {
            var aReturn = new Array();
            var aTrs = oTableLocal.fnGetNodes();
            for (var i = 0; i < aTrs.length; i++) {
                if ($(aTrs[i]).hasClass('row_selected')) {
                    aReturn.push(aTrs[i]);
                    anSelectedPosition = i;
                }
            }
            return aReturn;
        }

        $("#example tbody").click(function(event) {
            $(oTable.fnSettings().aoData).each(function() {
                $(this.nTr).removeClass('row_selected');
            });
            $(event.target.parentNode).addClass('row_selected');

            var anSelected = fnGetSelected(oTable);
            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            anSelectedId = element[0];
            anIndex = anSelected[0];
        });

        // double click
        $('#example tbody').on('dblclick', 'tr', function() {

            var anSelected = fnGetSelected(oTable);
            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            anIndex = anSelected[0];
            anSelectedId = element[0];
             var elements = oTable.fnGetData(anSelected[0]);
             anSelectedId = elements[0];
            document.location.href = "app/index/penyimpanan_add?reqId=" + anSelectedId;

            // console.log(anSelectedId);
        });

         $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/penyimpanan_add?reqId=" + anSelectedId;

        });

        
        



    });
</script>
<script type="text/javascript">
     $(document).ready(function() {
       $('#btnMaster').on('click', function() {
            openAdd('app/loadUrl/app/tempalate_master_project');

        });
       $('#btnAdd').on('click', function() {
        window.location.href='app/index/penyimpanan_add'

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
</style>

<div class="col-md-12">

    <div class="judul-halaman"> Penyimpanan</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah</a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
           
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
                                <td>Name </td>
                                <td colspan="2"><input type="text" name="reqCariName" class="form-control" id="reqCariName" value="<?= $reqCariName ?>"></td>

                                <td colspan="2">&nbsp;


                                </td>

                            </tr>
                            <tr>
                                <td>Description </td>
                                <td colspan="2"><input type="text" name="reqCariDescription" class="form-control" id="reqCariDescription" value="<?= $reqCariDescription ?>"></td>
                                <td colspan="2">&nbsp;</td>
                            </tr>

                            <tr>
                                <td>Format </td>
                                <td colspan="2">
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqJenis" name="reqJenis" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'combo_json/comboQmss'" value="<?= $reqJenis ?>" />

                                </td>
                                <td colspan="2"><button type="button"  onclick="searching_post()" class="btn btn-default"> Searching </button>
 <button type="submit" style="display: none" id="btn_cari_filters" class="btn btn-default"> Searching </button></td>
                            </tr>
                        </table>
                    </form>

                </div>
            </div>
        </div>



        <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <?php
                    for ($i = 1; $i < count($aColumns); $i++) {
                    ?>
                        <th><?= str_replace('_', ' ', $aColumns[$i])  ?></th>
                    <?php

                    };
                    ?>
                </tr>
            </thead>
        </table>

    </div>


</div>

<!------>