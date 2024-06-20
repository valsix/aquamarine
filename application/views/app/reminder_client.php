<?php
// Header Nama TABEL TH
// Menu yang dimunculkan: Nama Client , Contact Name, Vessel name, Vessels Class, Annual Survey, Extend, Intermediate Survey, Extend, Special Survey, Extend 
$aColumns = array(
    "REMINDER_CLIENT_ID", "NO", "COMPANY_NAME", "COMPANY_ADDRESS", "COMPANY_CP", "COMPANY_PHONE", "COMPANY_EMAIL", "VESSEL_NAME", "VESSEL_TYPE", "VESSEL_CLASS", "IMO_NO", "PORT_REGISTER",'ANNUAL_DATE',"DUE_DATE","INTERMEDIATE_DATE","DUE_DATE","SPECIAL_DATE","DUE_DATE", "LOADTEST_DATE", "DUE_DATE"
);

$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqCariVasselName = $_SESSION[$pg."reqCariVasselName"];
$reqCariVasselType = $_SESSION[$pg."reqCariVasselType"];
$reqCariVasselClass = $_SESSION[$pg."reqCariVasselClass"];
$reqCariAnnualDateFrom = $_SESSION[$pg."reqCariAnnualDateFrom"];
$reqCariAnnualDateTo = $_SESSION[$pg."reqCariAnnualDateTo"];
$reqCariAnnualDueDateFrom = $_SESSION[$pg."reqCariAnnualDueDateFrom"];
$reqCariAnnualDueDateTo = $_SESSION[$pg."reqCariAnnualDueDateTo"];
$reqCariIntermediateDateFrom = $_SESSION[$pg."reqCariIntermediateDateFrom"];
$reqCariIntermediateDateTo = $_SESSION[$pg."reqCariIntermediateDateTo"];
$reqCariIntermediateDueDateFrom = $_SESSION[$pg."reqCariIntermediateDueDateFrom"];
$reqCariIntermediateDueDateTo = $_SESSION[$pg."reqCariIntermediateDueDateTo"];
$reqCariSpecialDateFrom = $_SESSION[$pg."reqCariSpecialDateFrom"];
$reqCariSpecialDateTo = $_SESSION[$pg."reqCariSpecialDateTo"];
$reqCariSpecialDueDateFrom = $_SESSION[$pg."reqCariSpecialDueDateFrom"];
$reqCariSpecialDueDateTo = $_SESSION[$pg."reqCariSpecialDueDateTo"];
$reqCariLoadtestDateFrom = $_SESSION[$pg."reqCariLoadtestDateFrom"];
$reqCariLoadtestDateTo = $_SESSION[$pg."reqCariLoadtestDateTo"];
$reqCariLoadtestDueDateFrom = $_SESSION[$pg."reqCariLoadtestDueDateFrom"];
$reqCariLoadtestDueDateTo = $_SESSION[$pg."reqCariLoadtestDueDateTo"];


$this->load->model("ReminderClient");
$offer = new ReminderClient();
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
                {
                    bVisible: false
                },
                {
                    bVisible: false
                },
                {
                    bVisible: false
                },
                {
                    bVisible: false
                },
                null,
                null,
                null,
                {
                    bVisible: false
                },              
                {
                    bVisible: false
                },
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
            "responsive": false,
            "bScrollInfinite": true,
            "sAjaxSource": "web/reminder_client_json/json?<?= $add_str ?>&pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0, 2, 3, 4, 5, 7, 9, 10]
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
                aoData.push( { "name": "reqCariCompanyName", "value": $("#reqCariCompanyName").val() } );
                aoData.push( { "name": "reqCariVasselName", "value": $("#reqCariVasselName").val() } );
                aoData.push( { "name": "reqCariVasselType", "value": $("#reqCariVasselType").combobox('getValue') } );
                aoData.push( { "name": "reqCariVasselClass", "value": $("#reqCariVasselClass").combobox('getValue') } );
                aoData.push( { "name": "reqCariAnnualDateFrom", "value":$("#reqCariAnnualDateFrom").datebox('getValue') } );
                aoData.push( { "name": "reqCariAnnualDateTo", "value":  $("#reqCariAnnualDateTo").datebox('getValue') } );
                aoData.push( { "name": "reqCariAnnualDueDateFrom", "value":$("#reqCariAnnualDueDateFrom").datebox('getValue') } );
                aoData.push( { "name": "reqCariAnnualDueDateTo", "value":  $("#reqCariAnnualDueDateTo").datebox('getValue') } );
                aoData.push( { "name": "reqCariIntermediateDateFrom", "value":$("#reqCariIntermediateDateFrom").datebox('getValue') } );
                aoData.push( { "name": "reqCariIntermediateDateTo", "value":  $("#reqCariIntermediateDateTo").datebox('getValue') } );
                aoData.push( { "name": "reqCariIntermediateDueDateFrom", "value":$("#reqCariIntermediateDueDateFrom").datebox('getValue') } );
                aoData.push( { "name": "reqCariIntermediateDueDateTo", "value":  $("#reqCariIntermediateDueDateTo").datebox('getValue') } );
                aoData.push( { "name": "reqCariSpecialDateFrom", "value":$("#reqCariSpecialDateFrom").datebox('getValue') } );
                aoData.push( { "name": "reqCariSpecialDateTo", "value":  $("#reqCariSpecialDateTo").datebox('getValue') } );
                aoData.push( { "name": "reqCariSpecialDueDateFrom", "value":$("#reqCariSpecialDueDateFrom").datebox('getValue') } );
                aoData.push( { "name": "reqCariSpecialDueDateTo", "value":  $("#reqCariSpecialDueDateTo").datebox('getValue') } );
                aoData.push( { "name": "reqCariLoadtestDateFrom", "value":$("#reqCariLoadtestDateFrom").datebox('getValue') } );
                aoData.push( { "name": "reqCariLoadtestDateTo", "value":  $("#reqCariLoadtestDateTo").datebox('getValue') } );
                aoData.push( { "name": "reqCariLoadtestDueDateFrom", "value":$("#reqCariLoadtestDueDateFrom").datebox('getValue') } );
                aoData.push( { "name": "reqCariLoadtestDueDateTo", "value":  $("#reqCariLoadtestDueDateTo").datebox('getValue') } );
            },
            "sPaginationType": "full_numbers",
            //  "fnCreatedRow": function (row, data, index,iEnd, aiDisplay ,iDataIndex) {
            //         var rowCount = <?=$total_row?>;
            //         $('td', row).eq(0).html(rowCount-index);
            // },
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData[20] == "red") {
                    $('td', nRow).addClass('redClass');
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
            document.location.href = "app/index/reminder_client_add?reqId=" + anSelectedId;

            // console.log(anSelectedId);
        });

        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/reminder_client_add";

        });

        $('#btnExcel').on('click', function() {
            openAdd('app/loadUrl/app/excel_offer');
        });

        $('#btnPrint').on('click', function() {
                  var add_str = "reqCariNoOrder="+$("#reqCariNoOrder").val();
                      add_str += "&reqCariDateofServiceFrom="+$("#reqCariDateofServiceFrom").datebox('getValue') ;
                      add_str += "&reqCariDateofServiceTo="+$("#reqCariDateofServiceTo").datebox('getValue') ;
                      add_str += "&reqCariCompanyName="+$("#reqCariCompanyName").val();
                      add_str += "&reqCariPeriodeYear="+$("#reqCariPeriodeYear").val();
                      add_str += "&reqCariVasselName="+$("#reqCariVasselName").val();
                      add_str += "&reqCariProject="+$("#reqCariProject").val();
                      add_str += "&reqCariGlobalSearch="+$("#reqCariGlobalSearch").val();
                      add_str += "&reqCariStatus="+$("#reqCariStatus").val();

            openAdd("app/loadUrl/report/offer_pdf?"+add_str);
            // openAdd('report/index/report_cetak_offer_pdf?reqId=' + anSelectedId);
        });

        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/reminder_client_add?reqId=" + anSelectedId;

        });

        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            // deleteData("web/reminder_client_json/delete", anSelectedId);
            deleteData_for_table('web/reminder_client_json/delete', anSelectedId, anIndex, 1);

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

    <div class="judul-halaman"> Reminder Client</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah </a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit </a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Delete </a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <!-- <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
            <span><a id="btnExcel"><i class="fa fa-fw fa-file-excel-o " aria-hidden="true"></i> Export Excel </a></span> -->
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
                                <td>Company Name </td>
                                <td><input type="text" name="reqCariCompanyName" class="form-control" id="reqCariCompanyName" value="<?= $reqCariCompanyName ?>"></td>
                                <td width="10%"></td>
                                <td>Vessel's Name </td>
                                <td><input type="text" name="reqCariVasselName" class="form-control" id="reqCariVasselName" value="<?= $reqCariVasselName ?>"></td>                            
                            </tr>
                            <tr>
                                <td>Vessel's Type </td>
                                <td><input class="easyui-combobox form-control" style="width:100%" name="reqCariVasselType" id="reqCariVasselType" data-options="width:'200', height: 34,editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueTypeOfVessel?reqMode=ALL'" value="<?= $reqCariVasselType ?>" /></td>
                                <td width="10%"></td>
                                <td>Vessel's Class </td>
                                <td><input class="easyui-combobox form-control" style="width:100%" name="reqCariVasselClass" id="reqCariVasselClass" data-options="width:'250', height: 34,editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel?reqMode=ALL'" value="<?= $reqCariVasselClass ?>" /></td>                            
                            </tr>
                            <tr>
                                <td>Annual Date </td>
                                <td><input type="text" name="reqCariAnnualDateFrom" class="easyui-datebox " id="reqCariAnnualDateFrom" value="<?= $reqCariAnnualDateFrom ?>" data-options="height: 34"> To <input type="text" name="reqCariAnnualDateTo" class="easyui-datebox " id="reqCariAnnualDateTo" value="<?= $reqCariAnnualDateTo ?>" data-options="height: 34"></td>
                                <td width="10%"></td>
                                <td>Annual Due Date</td>
                                <td><input type="text" name="reqCariAnnualDueDateFrom" class="easyui-datebox " id="reqCariAnnualDueDateFrom" value="<?= $reqCariAnnualDueDateFrom ?>" data-options="height: 34"> To <input type="text" name="reqCariAnnualDueDateTo" class="easyui-datebox " id="reqCariAnnualDueDateTo" value="<?= $reqCariAnnualDueDateTo ?>" data-options="height: 34"></td>
                            </tr>
                            <tr>
                                <td>Intermediate Date </td>
                                <td><input type="text" name="reqCariIntermediateDateFrom" class="easyui-datebox " id="reqCariIntermediateDateFrom" value="<?= $reqCariIntermediateDateFrom ?>" data-options="height: 34"> To <input type="text" name="reqCariIntermediateDateTo" class="easyui-datebox " id="reqCariIntermediateDateTo" value="<?= $reqCariIntermediateDateTo ?>" data-options="height: 34"></td>
                                <td width="10%"></td>
                                <td>Intermediate Due Date</td>
                                <td><input type="text" name="reqCariIntermediateDueDateFrom" class="easyui-datebox " id="reqCariIntermediateDueDateFrom" value="<?= $reqCariIntermediateDueDateFrom ?>" data-options="height: 34"> To <input type="text" name="reqCariIntermediateDueDateTo" class="easyui-datebox " id="reqCariIntermediateDueDateTo" value="<?= $reqCariIntermediateDueDateTo ?>" data-options="height: 34"></td>
                            </tr>
                            <tr>
                                <td>Special Date </td>
                                <td><input type="text" name="reqCariSpecialDateFrom" class="easyui-datebox " id="reqCariSpecialDateFrom" value="<?= $reqCariSpecialDateFrom ?>" data-options="height: 34"> To <input type="text" name="reqCariSpecialDateTo" class="easyui-datebox " id="reqCariSpecialDateTo" value="<?= $reqCariSpecialDateTo ?>" data-options="height: 34"></td>
                                <td width="10%"></td>
                                <td>Special Due Date</td>
                                <td><input type="text" name="reqCariSpecialDueDateFrom" class="easyui-datebox " id="reqCariSpecialDueDateFrom" value="<?= $reqCariSpecialDueDateFrom ?>" data-options="height: 34"> To <input type="text" name="reqCariSpecialDueDateTo" class="easyui-datebox " id="reqCariSpecialDueDateTo" value="<?= $reqCariSpecialDueDateTo ?>" data-options="height: 34"></td>
                            </tr>
                            <tr>
                                <td>Load Test Date </td>
                                <td><input type="text" name="reqCariLoadtestDateFrom" class="easyui-datebox " id="reqCariLoadtestDateFrom" value="<?= $reqCariLoadtestDateFrom ?>" data-options="height: 34"> To <input type="text" name="reqCariLoadtestDateTo" class="easyui-datebox " id="reqCariLoadtestDateTo" value="<?= $reqCariLoadtestDateTo ?>" data-options="height: 34"></td>
                                <td width="10%"></td>
                                <td>Load Test Due Date</td>
                                <td><input type="text" name="reqCariLoadtestDueDateFrom" class="easyui-datebox " id="reqCariLoadtestDueDateFrom" value="<?= $reqCariLoadtestDueDateFrom ?>" data-options="height: 34"> To <input type="text" name="reqCariLoadtestDueDateTo" class="easyui-datebox " id="reqCariLoadtestDueDateTo" value="<?= $reqCariLoadtestDueDateTo ?>" data-options="height: 34"></td>
                            </tr>


                            <tr>
                                <td></td>
                                <td><button type="button"  onclick="searching_post()" class="btn btn-default"> Searching </button>
                                    <button type="submit" style="display: none" id="btn_cari_filters" class="btn btn-default"> Searching </button></td>
                                <td></td>
                                <td></td>

                                <td></td>
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