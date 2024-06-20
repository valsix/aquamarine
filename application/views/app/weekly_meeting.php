<?php
// Header Nama TABEL TH
$aColumns =array("WEEKLY_PROSES_ID","NO","PROGRES WEEKLY MEETING");

$reqCariDepartement = $_SESSION[$pg."reqCariDepartement"];
$reqCariMasalah = $_SESSION[$pg."reqCariMasalah"];
$reqCariSolusi = $_SESSION[$pg."reqCariSolusi"];
$reqCariProses = $_SESSION[$pg."reqCariProses"];
$reqCariStatus = $_SESSION[$pg."reqCariStatus"];
$reqCariDueDate = $_SESSION[$pg."reqCariDueDate"];
$reqCariTanggalFrom = $_SESSION[$pg."reqCariTanggalFrom"];
$reqCariTanggalTo = $_SESSION[$pg."reqCariTanggalTo"];
$reqCariDueDateTo = $_SESSION[$pg."reqCariTanggalTo"];
 
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
            "bServerSide": true,
            "bScrollInfinite": true,
            "sAjaxSource": "web/weekly_proses_json/json?pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0]
            },
                 {
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap width-200'>" + data + "</div>";
                    },
                    targets: 1
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
          //    "fnCreatedRow": function (row, data, index) {
          //   $('td', row).eq(0).html(index + 1);
          // },
            "fnServerParams": function ( aoData ) {
                    aoData.push( { "name": "reqCariDepartement", "value": $("#reqCariDepartement").combobox('getValue') } );
                    aoData.push( { "name": "reqCariMasalah", "value": $("#reqCariMasalah").val() } );
                    aoData.push( { "name": "reqCariSolusi", "value": $("#reqCariSolusi").val() } );
                    aoData.push( { "name": "reqCariProses", "value": $("#reqCariProses").val() } );
                    aoData.push( { "name": "reqCariStatus", "value": $("#reqCariStatus").combobox('getValue') } );
                    aoData.push( { "name": "reqCariDueDate", "value": $("#reqCariDueDate").datebox('getValue') } );
                    aoData.push( { "name": "reqCariTanggalFrom", "value": $("#reqCariTanggalFrom").datebox('getValue') } );
                    aoData.push( { "name": "reqCariTanggalTo", "value": $("#reqCariTanggalTo").datebox('getValue') } );
                    aoData.push( { "name": "reqCariDueDateTo", "value": $("#reqCariDueDateTo").datebox('getValue') } );
                    
                    // aoData.push( { "name": "reqCariGlobalSearch", "value": $("#reqCariGlobalSearch").val() } );
                  
                },
            "sPaginationType": "full_numbers",
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

               if (aData[5] == "red") {
                $('td', nRow).addClass('redClass');
            } if (aData[5] == "green") {
                $('td', nRow).addClass('greenClass');
            }if (aData[5] == "yellow") {
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
            console.log(anSelected);
            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            anSelectedId = element[0];
            anIndex = anSelected[0];
        });



        // double click
        $('#example tbody').on('dblclick', 'tr', function() {

            var anSelected = fnGetSelected(oTable);
             // console.log(oTable.fnGetData(anSelected[0]));
            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            anIndex = anSelected[0];
            anSelectedId = anSelectedData[0];

            document.location.href = "app/index/weekly_meeting_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });


        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/weekly_meeting_add";

        });


        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/weekly_meeting_add?reqId=" + anSelectedId;

        });


        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;
 //            console.log('ARIK');
 // console.log(anIndex);

 //            console.log(oTable.fnGetData(1));
            deleteData_for_table('web/weekly_proses_json/delete1', anSelectedId, anIndex, 2);
            // deleteData("web/certificate_json/delete", anSelectedId);
        });


        $('#btnRefresh').on('click', function() {
            Refresh();

        });
         $('#btnMasterDPT').on('click', function() {
               openAdd('app/loadUrl/app/template_add_departement');

        });
         $('#btnMasterSolusi').on('click', function() {
               openAdd('app/loadUrl/app/template_add_solusi');

        });


        $('#btnPrint').on('click', function() {
             

         

            openAdd('app/loadUrl/report/cetak_weekly_pdf?pg=<?=$pg?>');
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
   .text-wrap{
    white-space:normal;
}
/*.width-200{
    width:200px;
}*/
}
</style>

<div class="col-md-12">

    <div class="judul-halaman"> Weekly Meeting</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah</a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
             <span><a id="btnMasterDPT"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Master Departement </a></span>
          <!--     <span><a id="btnMasterSolusi"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Master Solusi </a></span> -->

            

            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td> Departement </td>
                                <td colspan="3"> 
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqCariDepartement" id="reqCariDepartement" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboDepartments?reqMode=ALL'" value="<?= $reqCariDepartement ?>" />
                                </td>
                                <td colspan="2">&nbsp;</td>

                            </tr>
                            <tr>
                                <td> Masalah</td>
                                <td colspan="2"><input type="text" name="reqCariMasalah" class="form-control" id="reqCariMasalah" value="<?= $reqCariMasalah ?>"></td>
                                <td colspan="3">&nbsp;</td>

                            </tr>
                            <tr>
                                <td>Tanggal Masalah </td>
                                <td colspan="4"><input type="text" name="reqCariTanggalFrom" class="easyui-datebox " id="reqCariTanggalFrom" value="<?= $reqCariTanggalFrom ?>"> to <input type="text" name="reqCariTanggalTo" class="easyui-datebox" id="reqCariTanggalTo" value="<?= $reqCariTanggalTo ?>"> </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td> Solusi</td>
                                <td colspan="2"><input type="text" name="reqCariSolusi" class="form-control" id="reqCariSolusi" value="<?= $reqCariSolusi ?>"></td>
                                <td colspan="3">&nbsp;</td>

                            </tr>

                            <tr>
                                <td> Progres</td>
                                <td ><input type="text" name="reqCariProses" class="form-control" id="reqCariProses" value="<?= $reqCariProses ?>"></td>
                                <td>&nbsp;</td>
                                  <td>Status</td>
                                    <td>
                                        
                                   
                                     <input class="easyui-combobox form-control" style="width:100%" name="reqCariStatus" id="reqCariStatus" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboStatusWeekly?reqMode=ALL'" value="<?= $reqCariStatus ?>" />

                                    </td>
                                <td >&nbsp;</td>
                            </tr>

                           
                           
                            <tr>
                                <td>Due Date</td>
                                <td><input type="text" class="easyui-datebox " name="reqCariDueDate" id="reqCariDueDate" value="<?= $reqCariDueDate ?>">
                                    to <input type="text" name="reqCariDueDateTo" class="easyui-datebox" id="reqCariDueDateTo" value="<?= $reqCariDueDateTo ?>">
                                </td>
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
                        <th style="word-break:break-all;"><?= str_replace('_', ' ', $aColumns[$i])  ?></th>
                    <?php

                    };
                    ?>
                </tr>
            </thead>
        </table>

    </div>


</div>


<!------>