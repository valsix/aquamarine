<?php
// Header Nama TABEL TH
$aColumns = array(
    "EQUIP_ID", "NO.", "ID NO.", "CATEGORY", "EQUIPMENT_NAME", "SPESIFICATION", "SERIAL_NO.",
    "IN_DATE", "LAST_CALIBRATION", "NEXT_CALIBRATION", "CONDITION",
    "STORAGE", "PRICE", "REMARKS", "SERIAL_NUMBER", "QUANTITY", "ITEM", "PIC_PATH", "STATUS", "STATUS_CONDITION"
);

$reqCariIdNumber = $_SESSION[$pg."reqCariIdNumber"];
$reqCariCondition = $_SESSION[$pg."reqCariCondition"];
$reqCariCategori = $_SESSION[$pg."reqCariCategori"];
$reqCariStorage = $_SESSION[$pg."reqCariStorage"];
$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqCariIncomingDateFrom = $_SESSION[$pg."reqCariIncomingDateFrom"];
$reqCariIncomingDateTo = $_SESSION[$pg."reqCariIncomingDateTo"];
$reqCariItemFrom = $_SESSION[$pg."reqCariItemFrom"];
$reqCariItemTo = $_SESSION[$pg."reqCariItemTo"];
$reqCariLastCalibrationFrom = $_SESSION[$pg."reqCariLastCalibrationFrom"];
$reqCariLastCalibrationTo = $_SESSION[$pg."reqCariLastCalibrationTo"];
$reqCariQuantity = $_SESSION[$pg."reqCariQuantity"];
$reqCariNextCalibrationFrom = $_SESSION[$pg."reqCariNextCalibrationFrom"];
$reqCariNextCalibrationTo = $_SESSION[$pg."reqCariNextCalibrationTo"];
$reqCariSpesification = $_SESSION[$pg."reqCariSpesification"];

$this->load->model("EquipmentList");
$offer = new EquipmentList();
$statement =  $_SESSION[$pg."reqCariSession"];
$total_row = $offer->getCountByParamsMonitoringEquipmentProd(array(),$statement);


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
                  <?
                for ($i = 1; $i < count($aColumns) - 1; $i++) {
                    if($i == 12 || $i >= 14)
                        echo "{bVisible: false},";
                    else
                        echo 'null,';
                }
                ?>
                {bVisible: false}
            ],
            "bSort": true,
            "bProcessing": true,
            "bServerSide": true,
            "bScrollInfinite": true,
            "sAjaxSource": "web/equipment_list_json/prod_json?pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0,14,15,16,17,18,19]
            },{ width: 350, targets: [13] },{targets:[13], class:"text-wrap"},{ width: 450, targets: [5] },{targets:[5], class:"text-wrap"},{
"targets": 1,
"orderable": false
}],
            responsive: false,
            "bStateSave": true,
            "fnStateSave": function(oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function(oSettings) {
                var data = localStorage.getItem('DataTables_' + window.location.pathname);
                return JSON.parse(data);
            },
            "fnServerParams": function ( aoData ) {
                    aoData.push( { "name": "reqCariIdNumber", "value": $("#reqCariIdNumber").val() } );
                    aoData.push( { "name": "reqCariCondition", "value": $("#reqCariCondition").combobox('getValue') } );
                    aoData.push( { "name": "reqCariCategori", "value": $("#reqCariCategori").combobox('getText') } );
                    aoData.push( { "name": "reqCariStorage", "value": $("#reqCariStorage").val() } );
                    aoData.push( { "name": "reqCariCompanyName", "value": $("#reqCariCompanyName").val() } );
                    aoData.push( { "name": "reqCariIncomingDateFrom", "value": $("#reqCariIncomingDateFrom").datebox('getValue') } );
                    aoData.push( { "name": "reqCariIncomingDateTo", "value": $("#reqCariIncomingDateTo").datebox('getValue') } );
                    aoData.push( { "name": "reqCariItemFrom", "value": $("#reqCariItemFrom").val() } );
                    aoData.push( { "name": "reqCariItemTo", "value": $("#reqCariItemTo").val() } );
                    aoData.push( { "name": "reqCariLastCalibrationFrom", "value": $("#reqCariLastCalibrationFrom").datebox('getValue') } );
                    aoData.push( { "name": "reqCariLastCalibrationTo", "value": $("#reqCariLastCalibrationTo").datebox('getValue') } );
                    aoData.push( { "name": "reqCariQuantity", "value": $("#reqCariQuantity").val() } );
                    aoData.push( { "name": "reqCariNextCalibrationFrom", "value": $("#reqCariNextCalibrationFrom").datebox('getValue') } );
                    aoData.push( { "name": "reqCariNextCalibrationTo", "value": $("#reqCariNextCalibrationTo").datebox('getValue') } );
                      aoData.push( { "name": "reqCariSpesification", "value": $("#reqCariSpesification").val() } );
                },
            "sPaginationType": "full_numbers",
          //     "fnCreatedRow": function (row, data, index) {

          //   $('td', row).eq(0).html(index + 1);
          // },
          // "fnCreatedRow": function (row, data, index,iEnd, aiDisplay ,iDataIndex) {
          //           var rowCount = <?=$total_row?>;
          //           $('td', row).eq(0).html(rowCount-index);
          //   },
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData[18] == "red") {
                    $('td', nRow).addClass('redClass');
                } if (aData[18] == "green") {
                    $('td', nRow).addClass('grayClass');
                }if (aData[18] == "yellow") {
                    $('td', nRow).addClass('yellowClass');
                }
                if (aData[18] == "blue") {
                    $('td', nRow).addClass('blueClass');
                }
                if (aData[19] == "red") {
                    $('td', nRow).addClass('redClass');
                } if (aData[19] == "green") {
                    $('td', nRow).addClass('grayClass');
                }if (aData[19] == "yellow") {
                    $('td', nRow).addClass('yellowClass');
                }if (aData[19] == "blue") {
                    $('td', nRow).addClass('blueClass');
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
            document.location.href = "app/index/equipment_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });


        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/equipment_add";

        });


        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/equipment_add?reqId=" + anSelectedId;

        });


        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;
            // deleteData("web/equipment_json/delete", anSelectedId);
            deleteData_for_table('web/equipment_list_json/delete', anSelectedId, anIndex, 3);
        });


        $('#btnRefresh').on('click', function() {
            Refresh();
        });


        $('#btnPrint').on('click', function() {
              

            var add_str = "reqCariIdNumber="+$("#reqCariIdNumber").val() ;
            add_str += "&reqCariCondition="+$("#reqCariCondition").combobox('getValue') ;
            add_str += "&reqCariCategori="+$("#reqCariCategori").combobox('getText') ;
            add_str += "&reqCariStorage="+$("#reqCariStorage").val() ;
            add_str += "&reqCariCompanyName="+$("#reqCariCompanyName").val() ;
            add_str += "&reqCariIncomingDateFrom="+$("#reqCariIncomingDateFrom").datebox('getValue') ;
            add_str += "&reqCariIncomingDateTo="+$("#reqCariIncomingDateTo").datebox('getValue') ;
            add_str += "&reqCariItemTo="+$("#reqCariItemTo").val() ;
            add_str += "&reqCariItemFrom="+$("#reqCariItemFrom").val() ;
            add_str += "&reqCariLastCalibrationFrom="+$("#reqCariLastCalibrationFrom").datebox('getValue') ;
            add_str += "&reqCariLastCalibrationTo="+$("#reqCariLastCalibrationTo").datebox('getValue') ;
            add_str += "&reqCariQuantity="+$("#reqCariQuantity").val() ;
            add_str += "&reqCariNextCalibrationFrom="+$("#reqCariNextCalibrationFrom").datebox('getValue') ;
            add_str += "&reqCariNextCalibrationTo="+$("#reqCariNextCalibrationTo").datebox('getValue') ;
            add_str += "&reqCariSpesification="+$("#reqCariSpesification").val() ;
                         


            openAdd('report/loadUrl/report/cetak_equipment_list_pdf?reqId='+anSelectedId+"&"+add_str);
            // openAdd('report/index/report_cetak_project_cost_pdf?reqId=' + anSelectedId);
        });


        $('#btnImport').on('click', function() {
            openAdd("app/loadUrl/app/import_template_equipment_list");

        });
        $('#btnExcel').on('click', function() {
            openAdd("app/loadUrl/app/tempalate_cetak_equipmen");

        });
        
        $('#btnDownload').on('click', function() {
            openAdd("app/loadUrl/app/tempalate_attacment_equip");

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
     .text-wrap{
    white-space:normal !important;
    width: 450px;
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
    .aksi-area div button {
  display: inline-block;
  padding: 0 10px;
  height: 25px;
  line-height: 25px;
  *color: #03428b;
  font-size: 11px;
  *background: #FFFFFF;
  *background: #59e1f7;
  background: #4259c1;
  color: #FFFFFF;
  font-weight: bold;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
}
</style>

<div class="col-md-12">

    <div class="judul-halaman"> EQUIPMENT LIST</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah</a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
              <span><a id="btnExcel"><i class="fa fa-fw fa-file-excel-o " aria-hidden="true"></i> Export Excel </a></span>
            <span><a id="btnImport"><i class="fa fa-fw fa-upload" aria-hidden="true"></i> Import </a></span>
            <span><a id="btnDownload"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Dowload File </a></span>
               

                   <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       <i class="fa fa-fw fa-folder" aria-hidden="true"></i>  Master
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="padding: 5px;background: #d3ddf8;">
                      <span style="padding: 2px"><a  onclick="openAdd('app/loadUrl/app/tempalate_master_combo?reqModul=EQUIPMENT')"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Equipment </a></span>
                      <span style="padding: 2px"><a onclick="openAdd('app/loadUrl/app/tempalate_master_combo?reqModul=ITEM')"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Item </a></span>
                      <span style="padding: 2px"><a onclick="openAdd('app/loadUrl/app/tempalate_master_equip_storage')"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Storage  </a></span>
                      <!--  <span style="padding: 2px"><a onclick="openAdd('app/loadUrl/app/tempalate_master_combo?reqModul=PART')"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Part    </a></span> -->
                  </div>
              </div>

            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td>ID Number </td>
                                <td><input type="text" name="reqCariIdNumber" class="easyui-validatebox textbox form-control" id="reqCariIdNumber" value="<?= $reqCariIdNumber ?>" onkeypress='validate(event)' ></td>
                                <td>Condition </td>
                                <td><input class="easyui-combobox form-control" style="width:100%" name="reqCariCondition" id="reqCariCondition" data-options="width:'290', height: '36',editable:false, valueField:'id',textField:'text',url:'combo_json/equipmentStatus?reqMode=ALL'" id="reqCariCondition" value="<?= $reqCariCondition ?>" /></td>

                                <td>&nbsp;


                                </td>

                            </tr>
                            <tr>
                                <td>Categori </td>
                                <td><input class="easyui-combobox form-control" style="width:100%" name="reqCariCategori" data-options="width:'290', height: '36',editable:false, valueField:'id',textField:'text',url:'combo_json/comboEquipCategori?reqMode=ALL'" id="reqCariCategori" value="<?= $reqCariCategori ?>" /></td>
                                <td>Storage </td>
                                <td><input type="text" name="reqCariStorage" class="easyui-validatebox textbox form-control" id="reqCariStorage" value="<?=$reqCariStorage?>"></td>

                                <td>&nbsp;


                                </td>

                            </tr>
                            <tr>
                                <td>Equipment Name </td>
                                <td><input type="text" name="reqCariCompanyName" class="easyui-validatebox textbox form-control" id="reqCariCompanyName" value="<?= $reqCariCompanyName ?>"></td>
                                <td>Incoming Date </td>
                                <td><input type="text" name="reqCariIncomingDateFrom" id="reqCariIncomingDateFrom" class="easyui-datebox " value="<?=$reqCariIncomingDateFrom?>" data-options="width:'150', height: '36'"> To <input type="text" name="reqCariIncomingDateTo" id="reqCariIncomingDateTo" class="easyui-datebox " value="<?=$reqCariIncomingDateTo?>" data-options="width:'150', height: '36'"></td>

                                <td>&nbsp;


                                </td>

                            </tr>
                            <tr>
                                <td>Item </td>
                                <td><input style="width: 40%" type="text" name="reqCariItemFrom" class="easyui-validatebox textbox form-control" id="reqCariItemFrom" value=""> to <input type="text" style="width: 40%" name="reqCariItemTo" class="form-control" id="reqCariItemTo" value="<?= $reqCariItemTo ?>"></td>
                                <td>Last Calibration </td>
                                <td><input type="text" name="reqCariLastCalibrationFrom" id="reqCariLastCalibrationFrom" class="easyui-datebox " value="<?=$reqCariLastCalibrationFrom?>" data-options="width:'150', height: '36'"> To <input type="text" name="reqCariLastCalibrationTo" id="reqCariLastCalibrationTo" class="easyui-datebox " value="<?=$reqCariLastCalibrationTo?>" data-options="width:'150', height: '36'"></td>

                                <td>&nbsp;


                                </td>

                            </tr>
                            <tr>
                                <td>Quantity </td>
                                <td><input type="text" name="reqCariQuantity" class="easyui-validatebox textbox form-control" id="reqCariQuantity" value="<?= $reqCariQuantity ?>"></td>
                                <td>Next Calibration </td>
                                <td><input type="text" name="reqCariNextCalibrationFrom" id="reqCariNextCalibrationFrom" class="easyui-datebox " value="<?=$reqCariNextCalibrationFrom?>" data-options="width:'150', height: '36'"> To <input type="text" name="reqCariNextCalibrationTo" id="reqCariNextCalibrationTo" class="easyui-datebox " value="<?=$reqCariNextCalibrationTo?>" data-options="width:'150', height: '36'"></td>

                                <td>&nbsp;


                                </td>

                            </tr>
                            <tr>
                                <td>Serial No. </td>
                                <td colspan="2"><input type="text" name="reqCariSpesification" class="easyui-validatebox textbox form-control" id="reqCariSpesification" value="<?= $reqCariSpesification ?>"></td>
                                <td colspan="2"><button type="button"  onclick="searching_post()" class="btn btn-default"> Searching </button>
 <button type="submit" style="display: none" id="btn_cari_filters" class="btn btn-default"> Searching </button></td>
                            </tr>
                        </table>
                    </form>

                </div>
            </div>
        </div>
        <div class="table-responsive" style="width: 2200px !important">
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


</div>

<!------>