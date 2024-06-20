<?php
// Header Nama TABEL TH
$aColumns = array("TENDER_ID","URUT", "TENDER_NO","CLIENT_NAME", "TENDER_NAME", "STATUS", "DATE ISSUED ANNOUNCEMENT", "REGISTER DATE & COLLECT DOCUMENT", 
                "PQ -  COMPANY", "PRE BID DATE", "PRE BID SOW
DOCUMENT", "DOK. TEKNIS & KOMERSIAL SUBMIT DATE", "OPENING BID DATE", "OPENING 2ND ENVELOPE", 
                "KONTRAK / LOA", "REMARK");

$reqCariProjectNo = $_SESSION[$pg."reqCariProjectNo"];
$reqCariProjectName = $_SESSION[$pg."reqCariProjectName"];
$reqCariIssuedDateFrom = $_SESSION[$pg."reqCariIssuedDateFrom"];
$reqCariIssuedDateTo = $_SESSION[$pg."reqCariIssuedDateTo"];
$reqCariRegisterDateFrom = $_SESSION[$pg."reqCariRegisterDateFrom"];
$reqCariRegisterDateTo = $_SESSION[$pg."reqCariRegisterDateTo"];
$reqCariPQDateFrom = $_SESSION[$pg."reqCariPQDateFrom"];
$reqCariPQDateTo = $_SESSION[$pg."reqCariPQDateTo"];
$reqCariPrebidDateFrom = $_SESSION[$pg."reqCariPrebidDateFrom"];
$reqCariPrebidDateTo = $_SESSION[$pg."reqCariPrebidDateTo"];
$reqCariSubmissionDateFrom = $_SESSION[$pg."reqCariSubmissionDateFrom"];
$reqCariSubmissionDateTo = $_SESSION[$pg."reqCariSubmissionDateTo"];
$reqCariOpening1DateFrom = $_SESSION[$pg."reqCariOpening1DateFrom"];
$reqCariOpening1DateTo = $_SESSION[$pg."reqCariOpening1DateTo"];
$reqCariOpening2DateFrom = $_SESSION[$pg."reqCariOpening2DateFrom"];
$reqCariOpening2DateTo = $_SESSION[$pg."reqCariOpening2DateTo"];


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
            "aoColumns": [
            {
                    bVisible: false
                }
               
                ,
                <?
                for ($i = 1; $i < count($aColumns) - 1; $i++) {
                    echo 'null,';
                }
                ?>
                
                null
            ],
            "responsive": false,
            "bSort": true,
            "bProcessing": true,
             "bServerSide": true,
            "bScrollInfinite": true,
            "sAjaxSource": "web/tender_json/json?pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0]
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
                aoData.push( { "name": "reqCariProjectNo", "value": $("#reqCariProjectNo").val() } );
                aoData.push( { "name": "reqCariProjectName", "value": $("#reqCariProjectName").val()} );
                aoData.push( { "name": "reqCariIssuedDateFrom", "value": $("#reqCariIssuedDateFrom").datebox('getValue')} );
                aoData.push( { "name": "reqCariIssuedDateTo", "value": $("#reqCariIssuedDateTo").datebox('getValue')} );
                aoData.push( { "name": "reqCariRegisterDateFrom", "value": $("#reqCariRegisterDateFrom").datebox('getValue')} );
                aoData.push( { "name": "reqCariRegisterDateTo", "value": $("#reqCariRegisterDateTo").datebox('getValue')} );
                aoData.push( { "name": "reqCariPQDateFrom", "value": $("#reqCariPQDateFrom").datebox('getValue')} );
                aoData.push( { "name": "reqCariPQDateTo", "value": $("#reqCariPQDateTo").datebox('getValue')} );
                aoData.push( { "name": "reqCariPrebidDateFrom", "value": $("#reqCariPrebidDateFrom").datebox('getValue')} );
                aoData.push( { "name": "reqCariPrebidDateTo", "value": $("#reqCariPrebidDateTo").datebox('getValue')} );
                aoData.push( { "name": "reqCariSubmissionDateFrom", "value": $("#reqCariSubmissionDateFrom").datebox('getValue')} );
                aoData.push( { "name": "reqCariSubmissionDateTo", "value": $("#reqCariSubmissionDateTo").datebox('getValue')} );
                aoData.push( { "name": "reqCariOpening1DateFrom", "value": $("#reqCariOpening1DateFrom").datebox('getValue')} );
                aoData.push( { "name": "reqCariOpening1DateTo", "value": $("#reqCariOpening1DateTo").datebox('getValue')} );
                aoData.push( { "name": "reqCariOpening2DateFrom", "value": $("#reqCariOpening2DateFrom").datebox('getValue')} );
                aoData.push( { "name": "reqCariOpening2DateTo", "value": $("#reqCariOpening2DateTo").datebox('getValue')} );
            },
            "sPaginationType": "full_numbers",
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData[5] == "Pass") {
                    $('td', nRow).addClass('greenClass');
                } 
                else if (aData[5] == "Fail") {
                    $('td', nRow).addClass('redClass');
                }
                else if (aData[5] == "On Progress") {
                    $('td', nRow).addClass('yellowClass');
                }
                else if (aData[5] == "Complete"){

                }
                 else if (aData[5] == ""){
                    $('td', nRow).addClass('grayClass');

                }
                else {
                    
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
        });


        // double click
        $('#example tbody').on('dblclick', 'tr', function() {

            var anSelected = fnGetSelected(oTable);
            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            anIndex = anSelected[0];
            anSelectedId = element[0];
            document.location.href = "app/index/tender_monitoring_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });


        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/tender_monitoring_add";

        });


        $('#btnExcel').on('click', function() {
            openAdd('app/loadUrl/app/excel_tender_monitoring?=<?= $add_str ?>');

        });


        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/tender_monitoring_add?reqId=" + anSelectedId;

        });


        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            deleteData_for_table('web/tender_json/delete', anSelectedId, anIndex, 1);
            // deleteData("web/document_json/delete", anSelectedId);
        });


        $('#btnRefresh').on('click', function() {
            Refresh();
        });
          $('#btnMaster').on('click', function() {
            openAdd('app/loadUrl/app/template_add_tender_master?');

        });

        $('#btnPrint').on('click', function() {
             
            var add_str = "reqCariProjectNo="+$("#reqCariProjectNo").val() ;
            add_str += "&reqCariProjectName="+$("#reqCariProjectName").val() ;
            add_str += "&reqCariIssuedDateFrom="+$("#reqCariIssuedDateFrom").datebox('getValue') ;
            add_str += "&reqCariIssuedDateTo="+$("#reqCariIssuedDateTo").datebox('getValue') ;
            add_str += "&reqCariRegisterDateFrom="+$("#reqCariRegisterDateFrom").datebox('getValue') ;
            add_str += "&reqCariRegisterDateTo="+$("#reqCariRegisterDateTo").datebox('getValue') ;
            add_str += "&reqCariPQDateFrom="+$("#reqCariPQDateFrom").datebox('getValue') ;
            add_str += "&reqCariPQDateTo="+$("#reqCariPQDateTo").datebox('getValue') ;
            add_str += "&reqCariPrebidDateFrom="+$("#reqCariPrebidDateFrom").datebox('getValue') ;
            add_str += "&reqCariPrebidDateTo="+$("#reqCariPrebidDateTo").datebox('getValue') ;
            add_str += "&reqCariSubmissionDateFrom="+$("#reqCariSubmissionDateFrom").datebox('getValue') ;
            add_str += "&reqCariSubmissionDateTo="+$("#reqCariSubmissionDateTo").datebox('getValue') ;
            add_str += "&reqCariOpening1DateFrom="+$("#reqCariOpening1DateFrom").datebox('getValue') ;
            add_str += "&reqCariOpening1DateTo="+$("#reqCariOpening1DateTo").datebox('getValue') ;
            add_str += "&reqCariOpening2DateFrom="+$("#reqCariOpening2DateFrom").datebox('getValue') ;
            add_str += "&reqCariOpening2DateTo="+$("#reqCariOpening2DateTo").datebox('getValue') ;
            
            openAdd('app/loadUrl/report/cetak_tender_monitoring_pdf?pg=<?=$pg?>&'+add_str);
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
</style>

<div class="col-md-12">

    <!-- <div class="judul-halaman"> Document</div> -->
    <div class="judul-halaman"> Tender Monitoring</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah</a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
            <!-- <span><a id="btnExcel"><i class="fa fa-fw fa-file-excel-o " aria-hidden="true"></i> Export Excel </a></span> -->
              <span><a id="btnMaster"><i class="fa fa-fw fa-folder-o " aria-hidden="true"></i> Master Tender </a></span>
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td>Project No </td>
                                <td><input type="text" name="reqCariProjectNo" class="easyui-validatebox textbox form-control" id="reqCariProjectNo" value="<?= $reqCariProjectNo ?>"></td>
                                <td>Project Name </td>
                                <td><input type="text" name="reqCariProjectName" class="easyui-validatebox textbox form-control" id="reqCariProjectName" value="<?= $reqCariProjectName ?>"></td>
                                <td>&nbsp;
                                </td>

                            </tr>
                            <tr>
                                <td>Issued Date </td>
                                <td><input type="text" name="reqCariIssuedDateFrom" id="reqCariIssuedDateFrom" class="easyui-datebox " value="<?=$reqCariIssuedDateFrom?>" data-options="width:'150', height: '36'"> To <input type="text" name="reqCariIssuedDateTo" id="reqCariIssuedDateTo" class="easyui-datebox " value="<?=$reqCariIssuedDateTo?>" data-options="width:'150', height: '36'"></td>
                                <td>Register Date </td>
                                <td><input type="text" name="reqCariRegisterDateFrom" id="reqCariRegisterDateFrom" class="easyui-datebox " value="<?=$reqCariRegisterDateFrom?>" data-options="width:'150', height: '36'"> To <input type="text" name="reqCariRegisterDateTo" id="reqCariRegisterDateTo" class="easyui-datebox " value="<?=$reqCariRegisterDateTo?>" data-options="width:'150', height: '36'"></td>
                                <td>&nbsp;
                                </td>

                            </tr>
                            <tr>
                                <td>PQ Date </td>
                                <td><input type="text" name="reqCariPQDateFrom" id="reqCariPQDateFrom" class="easyui-datebox " value="<?=$reqCariPQDateFrom?>" data-options="width:'150', height: '36'"> To <input type="text" name="reqCariPQDateTo" id="reqCariPQDateTo" class="easyui-datebox " value="<?=$reqCariPQDateTo?>" data-options="width:'150', height: '36'"></td>
                                <td>Pre Bid Date </td>
                                <td><input type="text" name="reqCariPrebidDateFrom" id="reqCariPrebidDateFrom" class="easyui-datebox " value="<?=$reqCariPrebidDateFrom?>" data-options="width:'150', height: '36'"> To <input type="text" name="reqCariPrebidDateTo" id="reqCariPrebidDateTo" class="easyui-datebox " value="<?=$reqCariPrebidDateTo?>" data-options="width:'150', height: '36'"></td>
                                <td>&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td>Submission Date </td>
                                <td><input type="text" name="reqCariSubmissionDateFrom" id="reqCariSubmissionDateFrom" class="easyui-datebox " value="<?=$reqCariSubmissionDateFrom?>" data-options="width:'150', height: '36'"> To <input type="text" name="reqCariSubmissionDateTo" id="reqCariSubmissionDateTo" class="easyui-datebox " value="<?=$reqCariSubmissionDateTo?>" data-options="width:'150', height: '36'"></td>
                                <td>Opening Bid Date </td>
                                <td><input type="text" name="reqCariOpening1DateFrom" id="reqCariOpening1DateFrom" class="easyui-datebox " value="<?=$reqCariOpening1DateFrom?>" data-options="width:'150', height: '36'"> To <input type="text" name="reqCariOpening1DateTo" id="reqCariOpening1DateTo" class="easyui-datebox " value="<?=$reqCariOpening1DateTo?>" data-options="width:'150', height: '36'"></td>
                                <td>&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td>Opening 2nd Date </td>
                                <td><input type="text" name="reqCariOpening2DateFrom" id="reqCariOpening2DateFrom" class="easyui-datebox " value="<?=$reqCariOpening2DateFrom?>" data-options="width:'150', height: '36'"> To <input type="text" name="reqCariOpening2DateTo" id="reqCariOpening2DateTo" class="easyui-datebox " value="<?=$reqCariOpening2DateTo?>" data-options="width:'150', height: '36'"></td>
                                <td colspan="3"><button type="button"  onclick="searching_post()" class="btn btn-default"> Searching </button>
                                    <button type="submit" style="display: none" id="btn_cari_filters" class="btn btn-default"> Searching </button>
                                </td>
                            </tr>
                        </table>
                    </form>

                </div>
            </div>
        </div>

        <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>NO</th>
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