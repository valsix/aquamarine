<?php
// Header Nama TABEL TH
$aColumns = array(
    "MASTER_TENDER_PERIODE_ID","TAHUN","LAST_UPDATE"
);

$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqCariContactPerson = $_SESSION[$pg."reqCariContactPerson"];
$reqCariVasselName = $_SESSION[$pg."reqCariVasselName"];
$reqCariEmailPerson = $_SESSION[$pg."reqCariEmailPerson"];

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
                for ($i = 1; $i < count($aColumns) - 1; $i++) {
                    echo 'null,';
                }
                ?>
                null
            ],
            "bSort": true,
            "bProcessing": true,
            "bServerSide": false,
            "bScrollInfinite": false,
            "responsive":true,
            "sAjaxSource": "web/master_tender_periode_json/json?pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0]
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
                    // aoData.push( { "name": "reqCariCompanyName", "value": $("#reqCariCompanyName").val() } );
                    // aoData.push( { "name": "reqCariContactPerson", "value": $("#reqCariContactPerson").val() } );
                    // aoData.push( { "name": "reqCariVasselName", "value": $("#reqCariVasselName").val() } );
                    // aoData.push( { "name": "reqCariEmailPerson", "value": $("#reqCariEmailPerson").val() } );
                },
            "sPaginationType": "full_numbers"

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
            document.location.href = "app/index/tender_evaluation_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });


        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/tender_evaluation_add";
        });


        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/tender_evaluation_add?reqId=" + anSelectedId;

        });

        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;
            deleteData_for_table('web/master_tender_periode_json/delete', anSelectedId, anIndex, 1);
            // deleteData("web/rules_json/delete", anSelectedId);
        });

          $('#btnMasterPsc').on('click', function() {
            openAdd('app/loadUrl/app/template_add_master_pcs');
        });
            $('#btnMasterSubMenu').on('click', function() {
            openAdd('app/loadUrl/app/template_add_master_tender_menus');
        });


        $('#btnPrint').on('click', function() {
             
                      // var add_str = "reqCariCompanyName="+$("#reqCariCompanyName").val();                
                      // add_str += "&reqCariContactPerson="+$("#reqCariContactPerson").val();
                      //  add_str += "&reqCariVasselName="+$("#reqCariVasselName").val();
                      //  add_str += "&reqCariEmailPerson="+$("#reqCariEmailPerson").val();
            openAdd('app/loadUrl/report/cetak_rules_pdf?pg=<?=$pg?>');
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

    <div class="judul-halaman"> Tender Evaluation</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah</a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span>
            <span><a id="btnAdd"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnMasterPsc"><i class="fa fa-fw fa-folder-o" aria-hidden="true"></i> Master PSC  </a></span>
            <span><a id="btnMasterSubMenu"><i class="fa fa-fw fa-folder-o" aria-hidden="true"></i> Master Sub Menu  </a></span>
          <input class="easyui-combobox form-control" style="width:100%" name="reqTahun" id="reqTahun" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/ambil_all_tahun'" value="<?= $reqTahun ?>" />
        </div>

        
        <div class="table-responsive">
            <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <?php
                        for ($i = 1; $i < count($aColumns); $i++) {
                        // var_dump($aColumns[$i]);
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


</div>

