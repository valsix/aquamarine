<?php
// Header Nama TABEL TH
$aColumns = array(
    "DOCUMENT_ID","NO.", "CERTIFICATE OF NAME", "ISSUED_DATE", "EXPIRED_DATE", "SURVEYOR","STATUS","TYPE CERTIFICATE"

);

$reqCariNameofCertificate = $_SESSION[$pg."reqCariNameofCertificate"];
$reqCariTypeofCertificate = $_SESSION[$pg."reqCariTypeofCertificate"];
$reqCariIssueDateFrom = $_SESSION[$pg."reqCariIssueDateFrom"];
$reqCariIssueDateTo = $_SESSION[$pg."reqCariIssueDateTo"];
$reqCariExpiredDateFrom = $_SESSION[$pg."reqCariExpiredDateFrom"];
$reqCariExpiredDateTo = $_SESSION[$pg."reqCariExpiredDateTo"];
$reqCariGlobalSearch = $_SESSION[$pg."reqCariGlobalSearch"];

$this->load->model("DokumenCertificate");
$offer = new DokumenCertificate();
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
                null,
                null,
                 null,
                 null,
                null,
                 null,
                null
            ],
            "bSort": true,
            "bProcessing": true,
            "bServerSide": true,
            "bScrollInfinite": true,
            "sAjaxSource": "web/dokumen_certificate_json/json?pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0,6]
            },{
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
                    aoData.push( { "name": "reqCariNameofCertificate", "value": $("#reqCariNameofCertificate").val() } );
                    aoData.push( { "name": "reqCariTypeofCertificate", "value": $("#reqCariTypeofCertificate").combobox('getValue') } );
                    aoData.push( { "name": "reqCariIssueDateFrom", "value": $("#reqCariIssueDateFrom").datebox('getValue') } );
                    aoData.push( { "name": "reqCariIssueDateTo", "value": $("#reqCariIssueDateTo").datebox('getValue') } );
                    aoData.push( { "name": "reqCariExpiredDateFrom", "value": $("#reqCariExpiredDateFrom").datebox('getValue') } );
                    aoData.push( { "name": "reqCariExpiredDateTo", "value": $("#reqCariExpiredDateTo").datebox('getValue') } );
                    aoData.push( { "name": "reqCariGlobalSearch", "value": $("#reqCariGlobalSearch").val() } );
                  
                },
            "sPaginationType": "full_numbers",
            //  "fnCreatedRow": function (row, data, index,iEnd, aiDisplay ,iDataIndex) {
            //         var rowCount = <?=$total_row?>;
            //         $('td', row).eq(0).html(rowCount-index);
            // },
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

               if (aData[6] == "red") {
                $('td', nRow).addClass('redClass');
            } if (aData[6] == "green") {
                $('td', nRow).addClass('greenClass');
            }if (aData[6] == "yellow") {
                $('td', nRow).addClass('yellowClass');
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
            document.location.href = "app/index/certificate_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });


        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/certificate_add";

        });


        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/certificate_add?reqId=" + anSelectedId;

        });


        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;
            deleteData_for_table('web/dokumen_certificate_json/delete', anSelectedId, anIndex, 1);
            // deleteData("web/certificate_json/delete", anSelectedId);
        });


        $('#btnRefresh').on('click', function() {
            Refresh();

        });
         $('#btnMasterCertifikate').on('click', function() {
               openAdd('app/loadUrl/app/tempalate_master_certificate');

        });


        $('#btnPrint').on('click', function() {
             

          var add_str = "reqCariNameofCertificate="+$("#reqCariNameofCertificate").val() ;  
          add_str += "&reqCariTypeofCertificate="+$("#reqCariTypeofCertificate").combobox('getValue');
          add_str += "&reqCariIssueDateFrom="+$("#reqCariIssueDateFrom").datebox('getValue');
          add_str += "&reqCariIssueDateTo="+$("#reqCariIssueDateTo").datebox('getValue');
          add_str += "&reqCariExpiredDateFrom="+$("#reqCariExpiredDateFrom").datebox('getValue');
          add_str += "&reqCariExpiredDateTo="+$("#reqCariExpiredDateTo").datebox('getValue');
          add_str += "&reqCariGlobalSearch="+$("#reqCariGlobalSearch").val();

            openAdd('app/loadUrl/report/cetak_certificate_pdf?'+add_str);
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

    <div class="judul-halaman"> Company Certificates</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah</a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
             <span><a id="btnMasterCertifikate"><i class="fa fa-fw fa-gavel" aria-hidden="true"></i> Master Certificate </a></span>

            

            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Details <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td> Name of Certificate </td>
                                <td colspan="3"><input type="text" name="reqCariNameofCertificate" class="form-control" id="reqCariNameofCertificate" value="<?= $reqCariNameofCertificate ?>"></td>
                                <td colspan="2">&nbsp;</td>

                            </tr>
                            <tr>
                                <td> Type of Certificate</td>
                                <td colspan="2">
                                     <input class="easyui-combobox form-control" style="width:100%" id="reqCariTypeofCertificate" name="reqCariTypeofCertificate" data-options="width:'230',editable:false, valueField:'id',textField:'text',url:'combo_json/comboCertificate?reqMode=opsi'" value="<?= $reqCariTypeofCertificate ?>" />

                                </td>
                                <td colspan="3">&nbsp;</td>

                            </tr>

                            <tr>
                                <td>Issue Date </td>
                                <td colspan="4"><input type="text" name="reqCariIssueDateFrom" class="easyui-datebox " id="reqCariIssueDateFrom" value="<?= $reqCariIssueDateFrom ?>"> to <input type="text" name="reqCariIssueDateTo" class="easyui-datebox" id="reqCariIssueDateTo" value="<?= $reqCariIssueDateTo ?>"> </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Expired Date </td>
                                <td colspan="4"><input type="text" name="reqCariExpiredDateFrom" class="easyui-datebox " id="reqCariExpiredDateFrom" value="<?= $reqCariExpiredDateFrom ?>"> to <input type="text" name="reqCariExpiredDateTo" class="easyui-datebox" id="reqCariExpiredDateTo" value="<?= $reqCariExpiredDateTo ?>"> </td>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td>Survayor Name</td>
                                <td><input type="text" class="form-control" name="reqCariGlobalSearch" id="reqCariGlobalSearch" value="<?= $reqCariGlobalSearch ?>"></td>
                                <td colspan="2">&nbsp; </td>


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