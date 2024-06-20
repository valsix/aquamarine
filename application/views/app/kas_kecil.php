<?php
// Header Nama TABEL TH
$aColumns = array(
    "KAS_KECIL_ID", "TANGGAL", "DESKRIPSI", "STATUS", "TOTAL_KREDIT", "TOTAL_DEBET", "TOTAL_BALANCE"
);



$reqCariPeriodeYearFrom = $_SESSION[$pg."reqCariPeriodeYearFrom"];
$reqCariPeriodeYearTo = $_SESSION[$pg."reqCariPeriodeYearTo"];



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
                null,
                null,
                {
                    bVisible: false
                },
                null,
                null,
                null
            ],
            "bSort": true,
            "bProcessing": true,
             "bServerSide": true,
            "bScrollInfinite": true,
            "sAjaxSource": "web/kas_kecil_json/json?pg=<?= $pg ?>",
            columnDefs: [{
                className: 'never',
                targets: [0,3]
            }],
            // "bStateSave": true,
            // "fnStateSave": function(oSettings, oData) {
            //     localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            // },
            // "fnStateLoad": function(oSettings) {
            //     var data = localStorage.getItem('DataTables_' + window.location.pathname);
            //     return JSON.parse(data);
            // },
            "fnServerParams": function ( aoData ) {
                    aoData.push( { "name": "reqCariPeriodeYearFrom", "value": $("#reqCariPeriodeYearFrom").datebox('getValue') } );
                    aoData.push( { "name": "reqCariPeriodeYearTo", "value": $("#reqCariPeriodeYearTo").datebox('getValue') } );
                    
            },
            "sPaginationType": "full_numbers",
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData[3] == "red") {
                    $('td', nRow).addClass('redClass');
                } else if (aData[3] == "yellow") {
                    $('td', nRow).addClass('yellowClass');
                } else {
                    // $('td', nRow).addClass('greenClass');
                }
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
            document.location.href = "app/index/kas_kecil_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });


        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/kas_kecil_add";

        });


        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/kas_kecil_add?reqId=" + anSelectedId;

        });


        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            deleteData_for_table('web/kas_kecil_json/delete', anSelectedId, anIndex, 1);
            // deleteData("web/kas_kecil_json/delete", anSelectedId);
        });


        $('#btnRefresh').on('click', function() {
            Refresh();
        });


        $('#btnPrint').on('click', function() {
         
                     var add_str = "reqCariPeriodeYearFrom="+$("#reqCariPeriodeYearFrom").datebox('getValue') ;  add_str += "&reqCariPeriodeYearTo="+$("#reqCariPeriodeYearTo").datebox('getValue');
                       

            openAdd('app/loadUrl/report/cetak_kas_kecil_pdf?'+add_str);

            // openAdd('report/index/report_cetak_project_cost_pdf?reqId=' + anSelectedId);
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

    <div class="judul-halaman"> Kas Kecil</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah</a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh</a></span>
            <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">

                            <tr>
                                <td>Date of Report</td>
                                <td colspan="3"><input type="text" name="reqCariPeriodeYearFrom" class="easyui-datebox " id="reqCariPeriodeYearFrom" value="<?= $reqCariPeriodeYearFrom ?>"> To <input type="text" name="reqCariPeriodeYearTo" class="easyui-datebox " id="reqCariPeriodeYearTo" value="<?= $reqCariPeriodeYearTo ?>"></td>

                                <td><button type="button"  onclick="searching_post()" class="btn btn-default"> Searching </button>
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