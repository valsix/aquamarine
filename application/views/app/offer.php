<?php
// Header Nama TABEL TH
$aColumns = array(
    "OFFER_ID", "NO.","OFFER_NO.", "COMPANY_OF_NAME", "VESSEL_OF_NAME", "TYPE_OF_SERVICE", "LOCATION","CLASS", "TYPE_OF_VESSEL", "FAXIMILE", "TOTAL_PRICE", "SCOPE_OF_WORK", "EMAIL","STATUS","STATUS","REASON"
);

$reqCariNoOrder = $_SESSION[$pg."reqCariNoOrder"];
$reqCariDateofServiceFrom = $_SESSION[$pg."reqCariDateofServiceFrom"];
$reqCariDateofServiceTo = $_SESSION[$pg."reqCariDateofServiceTo"];
$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqCariPeriodeYear = $_SESSION[$pg."reqCariPeriodeYear"];
$reqCariVasselName = $_SESSION[$pg."reqCariVasselName"];
$reqCariProject = $_SESSION[$pg."reqCariProject"];
$reqCariGlobalSearch = $_SESSION[$pg."reqCariGlobalSearch"];
$reqCariStatus = $_SESSION[$pg."reqCariStatus"];
$reqDestination = $_SESSION[$pg."reqDestination"];
$reqClass = $_SESSION[$pg."reqClass"];
$reqClassType  = $_SESSION[$pg."reqClassType"];
$reqProjectName  = $_SESSION[$pg."reqProjectName"];
$reqBulan =  $_SESSION[$pg."reqBulan"];
$this->load->model("Offer");
$offer = new Offer();
$statement =  $_SESSION[$pg."reqCariSession"];
$total_row = $offer->getCountByParams(array(),$statement);
?>

<style type="text/css">
table.table tbody tr.even.row_selected td {
    background-color: #1f295a !important;
    color: #FFF;
}
table.table tbody tr.odd.row_selected td {
    background-color: #1f295a !important;
    color: #FFF;
}
</style>
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
                {
                    bVisible: false
                },                
                null,
                {
                    bVisible: false
                },
                null,
                null
            ],
            "bSort": true,
            "bProcessing": true,
            "bServerSide": true,
            "responsive": false,
            "bScrollInfinite": true,
            "sAjaxSource": "web/offer_json/json?<?= $add_str ?>&pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0,11,13]
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
                    aoData.push( { "name": "reqCariDateofServiceFrom", "value":$("#reqCariDateofServiceFrom").datebox('getValue') } );
                    aoData.push( { "name": "reqCariDateofServiceTo", "value":  $("#reqCariDateofServiceTo").datebox('getValue') } );
                    aoData.push( { "name": "reqCariCompanyName", "value": $("#reqCariCompanyName").val() } );
                    aoData.push( { "name": "reqCariPeriodeYear", "value": $("#reqCariPeriodeYear").combobox('getValue') } );
                    aoData.push( { "name": "reqBulan", "value": $("#reqBulan").combobox('getValue') } );
                    aoData.push( { "name": "reqCariVasselName", "value": $("#reqCariVasselName").val() } );
                    aoData.push( { "name": "reqCariProject", "value": $("#reqCariProject").datebox('getValue') } );
                    aoData.push( { "name": "reqCariGlobalSearch", "value": $("#reqCariGlobalSearch").val() } );
                    aoData.push( { "name": "reqCariStatus", "value": $("#reqCariStatus").val() } );
                    aoData.push( { "name": "reqDestination", "value": $("#reqDestination").combobox('getValue') } );
                    aoData.push( { "name": "reqClass", "value": $("#reqClass").combobox('getValue') } );
                    aoData.push( { "name": "reqClassType", "value": $("#reqClassType").combobox('getValue') } );
                    aoData.push( { "name": "reqProjectName", "value": $("#reqProjectName").combobox('getValue') } );
                    
                },
            "sPaginationType": "full_numbers",
          //    "fnCreatedRow": function (row, data, index,iEnd, aiDisplay ,iDataIndex) {
          //         var rowCount = oTable.fnGetData().length;
          //         // var rowCount = <?=$total_row?>;
          //       // console.log(iDataIndex);
          //        // console.log(data);
          //   // $('td', row).eq(0).html(rowCount-index);
          //    $('td', row).eq(0).html(rowCount);
          // },
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                // console.log(aData[11]);
                // console.log(nRow);
                  // $('td', nRow).addClass('redClass');
                // $(nRow+ "td").addClass('redClass');
                if (aData[13] == "2") {
                    $('td', nRow).addClass('redClass');
                } 
                // if (aData[11] == "1") {
                //     $('td', nRow).addClass('greenClass');
                // }
                if (aData[13] == "0") {
                    $('td', nRow).addClass('yellowClass');
                }
                if (aData[13] == null) {
                    $('td', nRow).addClass('grayClass');
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
             var elements = oTable.fnGetData(anSelected[0]);
             anSelectedId = elements[0];
            document.location.href = "app/index/offer_add?reqId=" + anSelectedId;

            // console.log(anSelectedId);
        });

        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/offer_add";

        });

        $('#btnExcel').on('click', function() {
            openAdd('app/loadUrl/app/excel_offer/pg=<?=$pg?>');
        });

        $('#btnPrint').on('click', function() {
                  var add_str = "reqCariNoOrder="+$("#reqCariNoOrder").val();
                      add_str += "&reqCariDateofServiceFrom="+$("#reqCariDateofServiceFrom").datebox('getValue') ;
                      add_str += "&reqCariDateofServiceTo="+$("#reqCariDateofServiceTo").datebox('getValue') ;
                      add_str += "&reqCariCompanyName="+$("#reqCariCompanyName").val();
                      add_str += "&reqCariPeriodeYear="+$("#reqCariPeriodeYear").val();
                      add_str += "&reqCariVasselName="+$("#reqCariVasselName").val();
                      add_str += "&reqCariProject="+$("#reqCariProject").combobox('getValue');
                      add_str += "&reqCariGlobalSearch="+$("#reqCariGlobalSearch").val();
                      add_str += "&reqCariStatus="+$("#reqCariStatus").val();
                      add_str += "&reqClass="+$("#reqClass").combobox('getValue');
                       add_str += "&reqDestination="+$("#reqDestination").combobox('getValue');

            openAdd("app/loadUrl/report/offer_pdf?pg=<?=$pg?>&"+add_str);
            // openAdd('report/index/report_cetak_offer_pdf?reqId=' + anSelectedId);
        });

        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/offer_add?reqId=" + anSelectedId;

        });

        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            // deleteData("web/offer_json/delete", anSelectedId);
            deleteData_for_table('web/offer_json/delete', anSelectedId, anIndex, 1);

        });

        $('#btnRefresh').on('click', function() {
            Refresh();
        });
        $('#btnTOV').on('click', function() {
            openAdd("app/loadUrl/app/tempalate_master_type_of_vessel");
        });
        $('#btnCOV').on('click', function() {
            openAdd("app/loadUrl/app/tempalate_master_class_of_service");
        });
        $('#btnReason').on('click', function() {
            openAdd("app/loadUrl/app/template_add_master_reason");
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
  /*  thead.stick-datatable th:nth-child(1) {
        width: 440px !important;
        *border: 1px solid cyan;
    }*/

    /** TBODY **/
  /*  thead.stick-datatable~tbody td:nth-child(1) {
        width: 440px !important;
        *border: 1px solid yellow;
    }*/
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

    <div class="judul-halaman"> OFFER LIST</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah </a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit </a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Delete </a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
            <!-- <span><a id="btnExcel"><i class="fa fa-fw fa-file-excel-o " aria-hidden="true"></i> Export Excel </a></span> -->
            <span><a id="btnClass" onclick="classes()"><i class="fa fa-fw fa-file-excel-o " aria-hidden="true"></i> Master Vessel's Class </a></span>
            <span><a id="btnTypes" onclick="types()"><i class="fa fa-fw fa-file-excel-o " aria-hidden="true"></i> Master Vessel's Type </a></span>
             <span><a id="btnReason" onclick="types()"><i class="fa fa-fw fa-folder " aria-hidden="true"></i> Master Reason  </a></span>
           <!-- <span><a id="btnCOV"><i class="fa fa-fw fa-gavel " aria-hidden="true"></i> Master Class of Vessel </a></span>
             <span><a id="btnTOV"><i class="fa fa-fw fa-gavel " aria-hidden="true"></i> Master Type of Service </a></span> -->
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
                                <td colspan="2"><input type="text" name="reqCariDateofServiceFrom" class="easyui-datebox " id="reqCariDateofServiceFrom" value="<?= $reqCariDateofServiceFrom ?>"> To <input type="text" name="reqCariDateofServiceTo" class="easyui-datebox " id="reqCariDateofServiceTo" value="<?= $reqCariDateofServiceTo ?>">


                                </td>

                            </tr>
                            <tr>
                                <td>Company of Name </td>
                                <td><input type="text" name="reqCariCompanyName" class="form-control" id="reqCariCompanyName" value="<?= $reqCariCompanyName ?>"></td>
                                <td>Periode Year</td>
                                <td>
                                     <input class="easyui-combobox form-control" placeholder="Date" style="width:190px" id="reqBulan" name="reqBulan" data-options="editable:false,height: 34, valueField:'id',textField:'text',url:'combo_json/ComboBulanId'" value="<?= $reqBulan ?>" /> /
                                      <input class="easyui-combobox form-control" style="width:100%" id="reqCariPeriodeYear" name="reqCariPeriodeYear" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/ambil_tahun'" value="<?= $reqCariPeriodeYear ?>" />
                                
                                </td>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td>Name of Vessel </td>
                                <td><input type="text" name="reqCariVasselName" class="form-control" id="reqCariVasselName" value="<?= $reqCariVasselName ?>"></td>
                                <td>Scope of Work ( General Services )</td>
                                <td>
                                    <!-- <input type="text" name="reqCariProject" class=" form-control" id="reqCariProject" value="<?= $reqCariProject ?>"> -->

                                      <input class="easyui-combobox form-control" style="width:100%" name="reqCariProject" id="reqCariProject" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'web/services_json/comboAll'" value="<?= $reqCariProject ?>" />

                                </td>
                                <td> </td>

                            </tr>
                            <tr>
                                <td>Class of Vessel</td>
                                <td >


                                    <input class="easyui-combobox form-control" style="width:100%" name="reqClass" id="reqClass" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel?reqMode=ALL'" value="<?= $reqClass ?>" />
                                </td>
                                <td>Class Type</td>
                                <td>
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqClassType" id="reqClassType" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueTypeOfVessel?reqMode=ALL'" value="<?= $reqClassType ?>" />
                                </td>
                                <td>&nbsp;</td>
                                
                            </tr>
                             <tr>
                                <td>Location </td>
                                <td>

                                 <!--   <input class="easyui-combobox form-control" style="width:100%" name="reqDestination" id="reqDestination" data-options="width:'250',editable:true, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_lokasi2?reqMode=ALL'" value="<?= $reqDestination ?>" / -->

                                 <input type="text" id="reqDestination" placeholder=" Lokasi"  class="easyui-combobox textbox form-control" name="reqDestination" value="<?= $reqDestination ?>" style=" width:250px"
                                     data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/combo_lokasiNama?reqMode=All'"

                                      />   

                                     

                                   </td>
                                <td>Reason </td>
                                <td>
                                 <!--    <input class="easyui-combobox form-control" style="width:100%" name="reqProjectName" id="reqProjectName" data-options="width:'250',editable:true, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_offer_project'" value="<?= $reqProjectName ?>" /> -->

                                      <input class="easyui-combobox form-control" style="width:350px" id='reqProjectName' name="reqProjectName" data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/combo_reason?reqMode=All'" value="<?= $reqProjectName ?>" />

                                </td>
                                <td>&nbsp;</td>
                                
                            </tr>

                            <tr>
                                <td>Global Search</td>
                                <td><input type="text" class="form-control" name="reqCariGlobalSearch" id="reqCariGlobalSearch" value="<?= $reqCariGlobalSearch ?>"></td>
                                <td>Status </td>
                                <td><select class="form-control" name="reqCariStatus" id="reqCariStatus" value="">
                                        <!-- <option value="">All </option>
                                        <option value="Pending">Pending </option>
                                        <option value="Real">Real </option>
                                        <option value="Cancel">Cancel </option> -->
                                        <option value="">All </option>
                                        <option value="0">Pending </option>
                                        <option value="1">Realisasi </option>
                                        <option value="2">Cancel </option>
                                        <option value="3">Not Respond </option>


                                    </select> </td>

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

<script type="text/javascript">
    $(document).ready(function() {
        var year = (new Date()).getFullYear();
        var current = year;
        var limit = 2018;
        var limits = current - limit;
        year -= limits;
        $("#reqCariPeriodeYear").append('<option  value="ALL" > ALL </option>');

        for (var i = 0; i <= limits; i++) {
            var selected = '';
            // if ((year + i) == current) {

            //     selected = 'selected';
            // }
            $("#reqCariPeriodeYear").append('<option  value="' + (year + i) + '" ' + selected + '>' + (year + i) + '</option>');

        }

    });
</script>