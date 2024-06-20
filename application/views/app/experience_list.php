<?php
// Header Nama TABEL TH
$aColumns = array("EXPERIENCE_LIST_ID","NO","PROJECT_NAME","PROJECT_LOCATION","COSTUMER_ID","CLIENT NAME / ADDRESS ","CONTACT_NO","FROM_DATE","TO_DATE","DURATION","PROJECT_DURATION");

$reqProjectName = $_SESSION[$pg."reqProjectName"];
$reqClientName = $_SESSION[$pg."reqClientName"];
$reqProjectLocation = $_SESSION[$pg."reqProjectLocation"];
$reqContactNo = $_SESSION[$pg."reqContactNo"];
$reqYear = $_SESSION[$pg."reqYear"];
// var_dump($_SESSION); exit();

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
                $hideColumn = array(0,3,4,6,7,8);
                for ($i = 1; $i < count($aColumns) - 1; $i++) {
                    if(in_array($i, $hideColumn))
                        echo '{bVisible: false},';
                    else
                        echo 'null,';
                }
                ?>
                
                null
            ],
            "bSort": true,
            "bProcessing": true,
             "bServerSide": true,
            "bScrollInfinite": true,
            "sAjaxSource": "web/experience_list_json/json?pg=<?=$pg?>",
            columnDefs: [
                {
                    className: 'never',
                    targets: [0,3,4,6,7,8]
                }
            ],
            "bStateSave": true,
            "fnStateSave": function(oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function(oSettings) {
                var data = localStorage.getItem('DataTables_' + window.location.pathname);
                return JSON.parse(data);
            },
            "fnServerParams": function ( aoData ) {
                    aoData.push( { "name": "reqProjectName", "value": $("#reqProjectName").val() } );
                    aoData.push( { "name": "reqClientName", "value": $("#reqClientName").val()} );
                     aoData.push( { "name": "reqProjectLocation", "value": $("#reqProjectLocation").val()} );
                     aoData.push( { "name": "reqContactNo", "value": $("#reqContactNo").val()} );
                      aoData.push( { "name": "reqYear", "value": $("#datepicker").val()} );
                    
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
        });


        // double click
        $('#example tbody').on('dblclick', 'tr', function() {

            var anSelected = fnGetSelected(oTable);
            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            anIndex = anSelected[0];
            anSelectedId = element[0];
            document.location.href = "app/index/experience_list_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });


        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/experience_list_add";

        });


        $('#btnExcel').on('click', function() {
            openAdd('app/loadUrl/app/excel_dokument?=<?= $add_str ?>');

        });


        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/experience_list_add?reqId=" + anSelectedId;

        });


        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            deleteData_for_table('web/experience_list_json/delete', anSelectedId, anIndex, 1);
            // deleteData("web/document_json/delete", anSelectedId);
        });


        $('#btnRefresh').on('click', function() {
            Refresh();
        });


        $('#btnPrint').on('click', function() {
             
           var add_str = "reqProjectName="+$("#reqProjectName").val() ;
           add_str += "&reqClientName="+$("#reqClientName").val() ;
           add_str += "&reqProjectLocation="+$("#reqProjectLocation").val() ;
           add_str += "&reqContactNo="+$("#reqContactNo").val() ;
           add_str += "&reqYear="+$("#datepicker").val() ;
           
            openAdd('app/loadUrl/report/cetak_experience_list_pdf?pg=<?=$pg?>&'+add_str);
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
            // if (windowpos >= pos.top) {
            //     s.addClass("stick");
            //     $('#example thead').addClass('stick-datatable');
            // } else {
            //     s.removeClass("stick");
            //     $('#example thead').removeClass('stick-datatable');
            // }
        });
    });
</script>

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
    table.dataTable {

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
</style>

<div class="col-md-12">

    <!-- <div class="judul-halaman"> Document</div> -->
    <div class="judul-halaman"> Experience List</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah</a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
            <span><a id="btnExcel"><i class="fa fa-fw fa-file-excel-o " aria-hidden="true"></i> Export Excel </a></span>
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">

                            <tr>
                                <td>Project Name</td>
                                <td><input type="text" class="form-control" name="reqProjectName" id="reqProjectName" value="<?=$reqProjectName?>"></td>
                                <td>Client Name</td>
                                <td colspan="2"> <input type="text" class="form-control" name="reqClientName" id="reqClientName" value="<?=$reqClientName?>">

                                </td>

                               
                            </tr>

                            <tr>
                                <td>Project Location</td>
                                <td><input type="text" class="form-control" name="reqProjectLocation" id="reqProjectLocation" value="<?=$reqProjectLocation?>"></td>
                                <td>Contact No</td>
                                <td colspan="2"> <input type="text" class="form-control" name="reqContactNo" id="reqContactNo" value="<?=$reqContactNo?>"></td>

                                
                            </tr>

                             <tr>
                                <td>Year</td>
                                <td><input type="text" id="datepicker" name="datepicker" class="datepicker form-control" value="<?=$reqYear?>" /></td>
                                <td></td>


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