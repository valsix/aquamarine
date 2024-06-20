<?php
// Header Nama TABEL TH
$aColumns = array(
    "COST_PROJECT_ID","NO.", "PROJECT NO.", "COMPANY OF NAME", "NAME OF VESSEL", "CLASS_OF_VESSEL","TYPE_OF_VESSEL", "TYPE_OF_SERVICE", "DATE_OF_SERVICE", "DATE_SERVICE1", "DATE_OF SERVICE", "LOCATION", "CONTACT_PERSON", "ADVANCE_SURVEY",
    "OFFER_PRICE", "REAL_PRICE", "SURVEYOR","OVER_HEAD"
);

$reqCariNoOrder = $_SESSION[$pg."reqCariNoOrder"];
$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqCariPeriodeYearFrom = $_SESSION[$pg."reqCariPeriodeYearFrom"];
$reqCariPeriodeYearTo = $_SESSION[$pg."reqCariPeriodeYearTo"];
$reqCariVasselName = $_SESSION[$pg."reqCariVasselName"];
$reqBulan  = $_SESSION[$pg."reqBulan"];
$reqCariScopeOfWork      =  $_SESSION[$pg."reqCariScopeOfWork"] ;
$reqCariOperator         =  $_SESSION[$pg."reqCariOperator"] ;
$reqCariSurveyor         =  $_SESSION[$pg."reqCariSurveyor"] ;
$reqCariLocation         =  $_SESSION[$pg."reqCariLocation"] ;
$reqCariVesselClass       =  $_SESSION[$pg."reqCariVesselClass"] ;
$reqCariVesselType       =  $_SESSION[$pg."reqCariVesselType"];
$reqBulan       =  $_SESSION[$pg."reqBulan"];
$reqCariPeriodeYear =  $_SESSION[$pg."reqCariPeriodeYear"];
$this->load->model("Project_cost");
$offer = new Project_cost();
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
            "sAjaxSource": "web/project_cost_json/json?<?= $add_str ?>&pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0, 7, 8,9],

            },{ "width": "5%", "targets": 3 },
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
                    aoData.push( { "name": "reqCariCompanyName", "value": $("#reqCariCompanyName").val() } );
                    aoData.push( { "name": "reqCariPeriodeYearFrom", "value": $("#reqCariPeriodeYearFrom").datebox('getValue') } );
                    aoData.push( { "name": "reqCariPeriodeYearTo", "value": $("#reqCariPeriodeYearTo").datebox('getValue') } );
                     aoData.push( { "name": "reqCariVasselName", "value": $("#reqCariVasselName").val() } );
                      aoData.push( { "name": "reqCariPeriodeYear", "value": $("#reqCariPeriodeYear").combobox('getValue') } );
                      aoData.push( { "name": "reqBulan", "value": $("#reqBulan").combobox('getValue') } );
                     aoData.push( { "name": "reqCariSurveyor", "value": $("#reqCariSurveyor").val() } );
                     aoData.push( { "name": "reqCariOperator", "value": $("#reqCariOperator").val() } );
                     aoData.push( { "name": "reqCariLocation", "value": $("#reqCariLocation").val() } );
                      aoData.push( { "name": "reqCariVesselClass", "value": $("#reqCariVesselClass").combobox('getValue')} );
                       aoData.push( { "name": "reqCariVesselType", "value": $("#reqCariVesselType").combobox('getValue') } );
                    aoData.push( { "name": "reqCariScopeOfWork", "value": $("#reqCariScopeOfWork").val() } );

                },
            "sPaginationType": "full_numbers",
            //   "fnCreatedRow": function (row, data, index,iEnd, aiDisplay ,iDataIndex) {
            //         var rowCount = <?=$total_row?>;
            //          $('td', row).eq(0).html('');
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
            anIndex = anSelected[0];
            anSelectedId = element[0];
        });


        // double click
        $('#example tbody').on('dblclick', 'tr', function() {

            var anSelected = fnGetSelected(oTable);
            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            anIndex = anSelected[0];
            anSelectedId = element[0];
            document.location.href = "app/index/project_cost_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });


        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/project_cost_add";

        });


        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/project_cost_add?reqId=" + anSelectedId;

        });


        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            deleteData_for_table('web/project_cost_json/delete', anSelectedId, anIndex, 1);

            // deleteData("web/project_cost_json/delete", anSelectedId);

        });


        $('#btnRefresh').on('click', function() {
            Refresh();

        });


        $('#btnExcel').on('click', function() {
            openAdd('app/loadUrl/app/excel_project_cost');
        });


        $('#btnPrint').on('click', function() {
           

                      var add_str = "reqCariNoOrder="+$("#reqCariNoOrder").val();                
                      add_str += "&reqCariCompanyName="+$("#reqCariCompanyName").val();
                      add_str += "&reqCariPeriodeYearFrom="+$("#reqCariPeriodeYearFrom").datebox('getValue');
                      add_str += "&reqCariPeriodeYearTo="+$("#reqCariPeriodeYearTo").datebox('getValue');
                      add_str += "&reqCariVasselName="+$("#reqCariVasselName").val();
            openAdd('app/loadUrl/report/project_cost_pdf?'+add_str);
            // openAdd('report/index/report_cetak_project_cost_pdf?reqId=' + anSelectedId);
        });

    });
</script>

<!-- FIXED AKSI AREA WHEN SCROLLING -->
<link rel="stylesheet" href="css/gaya-stick-when-scroll.css" type="text/css">
<script src="js/stick.js" type="text/javascript"></script>
<script>
    // $(document).ready(function() {
    //     var s = $("#bluemenu");

    //     var pos = s.position();
    //     $(window).scroll(function() {
    //         var windowpos = $(window).scrollTop();
    //         //s.html("Distance from top:" + pos.top + "<br />Scroll position: " + windowpos);
    //         if (windowpos >= pos.top) {
    //             s.addClass("stick");
    //             $('#example thead').addClass('stick-datatable');
    //         } else {
    //             s.removeClass("stick");
    //             $('#example thead').removeClass('stick-datatable');
    //         }
    //     });
    // });
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
    .text-wrap{
    white-space:normal;
}
.width-200{
    width:200px;
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

    <div class="judul-halaman"> Project Cost</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah </a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Delete</a></span>
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
                                <td>Order No. </td>
                                <td><input type="text" name="reqCariNoOrder" class="form-control" id="reqCariNoOrder" value="<?= $reqCariNoOrder ?>"></td>
                                <td>Date of Service</td>
                                <td colspan="2"><input type="text" name="reqCariPeriodeYearFrom" class="easyui-datebox " id="reqCariPeriodeYearFrom" value="<?= $reqCariPeriodeYearFrom ?>" data-options="height: 36"> To <input type="text" name="reqCariPeriodeYearTo" class="easyui-datebox " id="reqCariPeriodeYearTo" value="<?= $reqCariPeriodeYearTo ?>" data-options="height: 36">

                                </td>

                            </tr>
                            <tr>
                                <td>Company Name </td>
                                <td><input type="text" name="reqCariCompanyName" class="form-control" id="reqCariCompanyName" value="<?= $reqCariCompanyName ?>"></td>
                                <td>Name of Vessel</td>
                                <td>
                                    <input type="text" name="reqCariVasselName" class="form-control" id="reqCariVasselName" value="<?= $reqCariVasselName ?>">
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Class of Vessel </td>
                                <td >
                                <input class="easyui-combobox form-control" style="width:100%" id="reqCariVesselClass" name="reqCariVesselClass" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel?reqMode=ALL'" value="<?= $reqCariVesselClass ?>" />
                                </td>
                                <td align="left">Type of Vessel </td>
                                <td> 
                                    <input class="easyui-combobox form-control" style="width:100%" id="reqCariVesselType" name="reqCariVesselType" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueTypeOfVessel?reqMode=ALL'" value="<?= $reqCariVesselType ?>" />

                                </td>

                                <td >&nbsp;</td>

                            </tr>
                             </tr>
                               <tr>
                                <td>Services  </td>
                                <td colspan="2">
                                    
                                      <input type="text" name="reqCariScopeOfWork" class="form-control" id="reqCariScopeOfWork" value="<?= $reqCariScopeOfWork ?>">
                                </td>

                                <td colspan="2">&nbsp;

                                </td>

                            </tr>
                             <tr>
                                <td>Location  </td>
                                <td ><input type="text" name="reqCariLocation" class="form-control" id="reqCariLocation" value="<?= $reqCariLocation ?>"></td>

                                
                                 <td>Periode Year</td>
                                <td>

                                    <input class="easyui-combobox form-control" placeholder="Date" style="width:190px" id="reqBulan" name="reqBulan" data-options="editable:false,height: 34, valueField:'id',textField:'text',url:'combo_json/ComboBulan'" value="<?= $reqBulan ?>" /> /
                                      <input class="easyui-combobox form-control" style="width:100%" id="reqCariPeriodeYear" name="reqCariPeriodeYear" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/ambil_tahun'" value="<?= $reqCariPeriodeYear ?>" />


                                   
                                </td>
                                <td>&nbsp;</td>

                            </tr>
                            <tr>
                                <td> Surveyor / Operator </td>
                                <td colspan="3"> <input  style="width: 40%" type="text" name="reqCariSurveyor" class="form-control" id="reqCariSurveyor" value="<?= $reqCariSurveyor ?>" > / <input style="width: 30%" type="text" name="reqCariOperator" class="form-control" id="reqCariOperator" value="<?= $reqCariOperator ?>"></td>
                             <td >&nbsp;</td>

                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="3"></td>

                                <td><button type="button"  onclick="searching_post()" class="btn btn-default"> Searching </button>
 <button type="submit" style="display: none" id="btn_cari_filters" class="btn btn-default"> Searching </button></td>
                            </tr>

                        </table>
                    </form>

                </div>
            </div>
        </div>

        <table id="example" class="table table-striped table-hover dt-responsive text-wrap " cellspacing="0" width="100%">
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