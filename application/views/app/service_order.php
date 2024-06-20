<?php
// Header Nama TABEL TH
$aColumns = array(
    "SO_ID","NO.", "ORDER NO.", "PROJECT_OF NAME", "COMPANY_OF NAME", "VESSEL_OF NAME","CLASS", "VESSEL_TYPE", "SURVEYOR", "LOCATION", "SERVICE", "DATE_OF_START", "DATE_OF_FINISH",
    "EQUIPMENT", "DATE_OF_SERVICE"
);

$reqCariNoOrder = $_SESSION[$pg."reqCariNoOrder"];
$reqCariPeriodeYearFrom = $_SESSION[$pg."reqCariPeriodeYearFrom"];
$reqCariPeriodeYearTo = $_SESSION[$pg."reqCariPeriodeYearTo"];
$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqCariPeriodeYear = $_SESSION[$pg."reqCariPeriodeYear"];
$reqCariVasselName = $_SESSION[$pg."reqCariVasselName"];
$reqCariProject = $_SESSION[$pg."reqCariProject"];
$reqCariGlobal = $_SESSION[$pg."reqCariGlobal"];
$reqCariTypeVessel = $_SESSION[$pg."reqCariTypeVessel"];
$reqDestination = $_SESSION[$pg."reqDestination"];
$reqBulan  = $_SESSION[$pg."reqBulan"];
$this->load->model("Service_order");
$offer = new Service_order();
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
                null,
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
            "sAjaxSource": "web/service_order_json/json?pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0,10]
            },{targets:[3], class:"text-wrap"},{ width: 450, targets: [3] },
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
                aoData.push( { "name": "reqCariPeriodeYearFrom", "value": $("#reqCariPeriodeYearFrom").datebox('getValue') } );
                aoData.push( { "name": "reqCariPeriodeYearTo", "value": $("#reqCariPeriodeYearTo").datebox('getValue') } );
                aoData.push( { "name": "reqCariCompanyName", "value": $("#reqCariCompanyName").val() } );
                aoData.push( { "name": "reqCariPeriodeYear", "value": $("#reqCariPeriodeYear").combobox('getValue') } );
                  aoData.push( { "name": "reqCariTypeVessel", "value": $("#reqCariTypeVessel").combobox('getValue') } );
                 aoData.push( { "name": "reqCariTypeVessel", "value": $("#reqDestination").combobox('getValue') } ); 
                 aoData.push( { "name": "reqBulan", "value": $("#reqBulan").combobox('getValue') } ); 
                aoData.push( { "name": "reqCariVasselName", "value": $("#reqCariVasselName").val() } );
                aoData.push( { "name": "reqCariProject", "value": $("#reqCariProject").val() } );
                aoData.push( { "name": "reqCariGlobal", "value": $("#reqCariGlobal").val() } );

                },
            "sPaginationType": "full_numbers",
            //    "fnCreatedRow": function (row, data, index,iEnd, aiDisplay ,iDataIndex) {
            //         var rowCount = <?=$total_row?>;
            //         $('td', row).eq(0).html(rowCount-index);
            // }

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
            document.location.href = "app/index/service_order_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });



        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/service_order_add";

        });

        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/service_order_add?reqId=" + anSelectedId;

        });
        $('#btnExcel').on('click', function() {
            openAdd('app/loadUrl/app/excel_service_order?=<?= $add_str ?>');

        });

        $('#btnPrint').on('click', function() {
           
      var add_str = "reqCariNoOrder="+$("#reqCariNoOrder").val() ;            
      add_str += "&reqCariPeriodeYearFrom="+$("#reqCariPeriodeYearFrom").datebox('getValue');
      add_str += "&reqCariPeriodeYearTo="+$("#reqCariPeriodeYearTo").datebox('getValue');
      add_str += "&reqCariCompanyName="+$("#reqCariCompanyName").val();
      add_str += "&reqCariPeriodeYear="+$("#reqCariPeriodeYear").val();
      add_str += "&reqCariVasselName="+$("#reqCariVasselName").val();
      add_str += "&reqCariProject="+$("#reqCariProject").val();
      add_str += "&reqCariGlobal="+$("#reqCariGlobal").val();
            openAdd('app/loadUrl/report/service_order_pdf?'+add_str);
            // openAdd('report/index/report_cetak_service_order_pdf?<?= $add_str ?>');

        });

        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            // deleteData("web/service_order_json/delete", anSelectedId);
            deleteData_for_table('web/service_order_json/delete', anSelectedId, anIndex, 1);

        });
        $('#btnRefresh').on('click', function() {
            Refresh();
        });

    });
</script>

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
    width: 450px;
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

    <div class="judul-halaman"> Operation Work Request</div>

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
                                <td>Project No. </td>
                                <td><input type="text" name="reqCariNoOrder" class="form-control" id="reqCariNoOrder" value="<?= $reqCariNoOrder ?>"></td>
                                <td>Date of Service</td>
                                <td colspan="2"><input type="text" name="reqCariPeriodeYearFrom" class="easyui-datebox " id="reqCariPeriodeYearFrom" value="<?= $reqCariPeriodeYearFrom ?>"> To <input type="text" name="reqCariPeriodeYearTo" class="easyui-datebox " id="reqCariPeriodeYearTo" value="<?= $reqCariPeriodeYearTo ?>">


                                </td>

                            </tr>
                            <tr>
                                <td>Company Name </td>
                                <td><input type="text" name="reqCariCompanyName" class="form-control" id="reqCariCompanyName" value="<?= $reqCariCompanyName ?>"></td>
                                <td>Periode Year</td>
                                <td>

                                    <input class="easyui-combobox form-control" placeholder="Date" style="width:190px" id="reqBulan" name="reqBulan" data-options="editable:false,height: 34, valueField:'id',textField:'text',url:'combo_json/ComboBulan'" value="<?= $reqBulan ?>" /> /



                                    <input class="easyui-combobox form-control" style="width:100%" id="reqCariPeriodeYear" name="reqCariPeriodeYear" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/ambil_tahun'" value="<?= $reqCariPeriodeYear ?>" />

                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Location </td>
                                <td>
                                    
                                 <input type="text" id="reqDestination" placeholder=" Lokasi"  class="easyui-combobox textbox form-control" name="reqDestination" value="<?= $reqDestination ?>" style=" width:250px"
                                     data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/combo_lokasiNama?reqMode=All'"

                                      />   

                                </td>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td>Vassel Name </td>
                                <td><input type="text" name="reqCariVasselName" class="form-control" id="reqCariVasselName" value="<?= $reqCariVasselName ?>"></td>
                                <td>Project Name</td>
                                <td><input type="text" name="reqCariProject" class=" form-control" id="reqCariProject" value="<?= $reqCariProject ?>"></td>
                                <td> </td>

                            </tr>
                             <tr>
                                <td>Class of Vessel</td>
                                <td >


                                    <input class="easyui-combobox form-control" style="width:100%" name="reqCariClass" id="reqCariClass" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel?reqMode=ALL'" value="<?= $reqCariClass ?>" />
                                </td>
                                <td>Class Type</td>
                                <td>
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqCariTypeVessel" id="reqCariTypeVessel" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueTypeOfVessel?reqMode=ALL'" value="<?= $reqCariTypeVessel ?>" />
                                </td>
                                <td>&nbsp;</td>
                                
                            </tr>
                            <tr>
                                <td>Global Search</td>
                                <td colspan="3"><input type="text" class="form-control" name="reqCariGlobal" id="reqCariGlobal" value="<?= $reqCariGlobal ?>"></td>

                                <td><button type="button"  onclick="searching_post()" class="btn btn-default"> Searching </button>
                                    <button type="submit" style="display: none" id="btn_cari_filters" class="btn btn-default"> Searching </button></td>
                            </tr>





                        </table>
                    </form>

                </div>
            </div>
        </div>
        <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%" >
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