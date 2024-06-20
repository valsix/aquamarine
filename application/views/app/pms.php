<?php
// Header Nama TABEL TH
$aColumns = array(
    "PMS_ID", "NO","ID", "CATEGORY", "EQUIPMENT_NAME", "SPECIFICATION", "QTY", "DATE OF TEST", "NEXT OF TEST",
    "TIME OF TEST", "CONDITION", "STORAGE", "REMARKS", "STATUS"
);

// $reqCariCompetentPerson = $_SESSION[$pg."reqCariCompetentPerson"];
// $reqCariTimeofTest = $_SESSION[$pg."reqCariTimeofTest"];
$reqCariDateofArrivedFrom = $_SESSION[$pg."reqCariDateofArrivedFrom"];
$reqCariDateofArrivedTo = $_SESSION[$pg."reqCariDateofArrivedTo"];
$reqCariName = $_SESSION[$pg."reqCariName"];
$reqCariNoSerial = $_SESSION[$pg."reqCariNoSerial"];
$reqCariIdNumber = $_SESSION[$pg."reqCariIdNumber"];
$reqCariCondition = $_SESSION[$pg."reqCariCondition"];
$reqCariCategori = $_SESSION[$pg."reqCariCategori"];

$this->load->model("PmsEquipment");
$offer = new PmsEquipment();
$statement =  $_SESSION[$pg."reqCariSession"];
$total_row = $offer->getCountByParamsMonitoring(array(),$statement);



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
                for ($i=1;$i<count($aColumns)-1;$i++){
                    echo "null,";
                }
                ?>
                {
                    bVisible: false
                }
            ],
            "bSort": true,
            "bProcessing": true,
             "bServerSide": true,
            "bScrollInfinite": true,
            "sAjaxSource": "web/pms_equipment_json/json?<?= $add_str ?>&pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0,13]
            },{ width: 450, targets: [5] },{targets:[5], class:"text-wrap"},{
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
                    // aoData.push( { "name": "reqCariCompetentPerson", "value": $("#reqCariCompetentPerson").val() } );
                    // aoData.push( { "name": "reqCariTimeofTest", "value": $("#reqCariTimeofTest").combobox('getValue') } );
                    aoData.push( { "name": "reqCariDateofArrivedFrom", "value": $("#reqCariDateofArrivedFrom").datebox('getValue') } );
                    aoData.push( { "name": "reqCariDateofArrivedTo", "value": $("#reqCariDateofArrivedTo").datebox('getValue') } );
                    aoData.push( { "name": "reqCariName", "value": $("#reqCariName").val() } );
                    aoData.push( { "name": "reqCariNoSerial", "value": $("#reqCariNoSerial").val() } );
                    aoData.push( { "name": "reqCariIdNumber", "value": $("#reqCariIdNumber").val() } );
                    aoData.push( { "name": "reqCariCondition", "value": $("#reqCariCondition").combobox('getValue') } );
                    aoData.push( { "name": "reqCariCategori", "value": $("#reqCariCategori").combobox('getText') } );
                },
            "sPaginationType": "full_numbers",
            //  "fnCreatedRow": function (row, data, index,iEnd, aiDisplay ,iDataIndex) {
            //         var rowCount = <?=$total_row?>;
            //         $('td', row).eq(0).html(rowCount-index);
            // },
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData[13] == "red") {
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
            document.location.href = "app/index/pms_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });




        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/pms_add";

        });

        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/pms_add?reqId=" + anSelectedId;

        });

        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            deleteData_for_table('web/pms_equipment_json/delete', anSelectedId, anIndex, 3);
            // deleteData("web/pms_json/delete", anSelectedId);
        });


        $('#btnRefresh').on('click', function() {
            Refresh();
        });


        $('#btnPrint').on('click', function() {
            
                     // var add_str = "reqCariCompetentPerson="+$("#reqCariCompetentPerson").val();                
                      // add_str += "&reqCariTimeofTest="+$("#reqCariTimeofTest").combobox('getValue');
                      var add_str = "&reqCariDateofArrivedFrom="+$("#reqCariDateofArrivedFrom").datebox('getValue');
                      add_str += "&reqCariDateofArrivedTo="+$("#reqCariDateofArrivedTo").datebox('getValue');
                      add_str += "&reqCariName="+$("#reqCariName").val();


            openAdd('app/loadUrl/report/cetak_pms_pdf?'+add_str);
            // openAdd('report/index/report_cetak_project_cost_pdf?reqId=' + anSelectedId);
        });

        $('#btnImport').on('click', function() {
            openAdd("app/loadUrl/app/import_template_pms");

        });
         $('#btnDoc').on('click', function() {
            openAdd("app/loadUrl/app/tempalate_attacment_pms");

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
</script> -->

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

    <div class="judul-halaman"> PLAN MAINTENANCE SYSTEM (PMS)</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah</a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
            <!-- <span><a id="btnImport"><i class="fa fa-fw fa-upload" aria-hidden="true"></i> Import </a></span> -->
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <span><a id="btnDoc"><i class="fa fa-fw fa-folder-o" aria-hidden="true"></i> Download Shcedule  </a></span>
           
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>

        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td>Equip ID </td>
                                <td><input type="text" name="reqCariIdNumber" class="easyui-validatebox textbox form-control" onkeypress='validate(event)' id="reqCariIdNumber" value="<?= $reqCariIdNumber ?>" ></td>
                                <td>Name </td>
                                <td><input type="text" name="reqCariName" class="form-control" id="reqCariName" value="<?= $reqCariName ?>"></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Serial Number </td>
                                <td><input type="text" name="reqCariNoSerial" class="form-control" id="reqCariNoSerial" value="<?= $reqCariNoSerial ?>"></td>
                                <td>Condition </td>
                                <td><input class="easyui-combobox form-control" style="width:100%" name="reqCariCondition" id="reqCariCondition" data-options="width:'290', height: '36',editable:false, valueField:'id',textField:'text',url:'combo_json/equipmentStatus?reqMode=ALL'" id="reqCariCondition" value="<?= $reqCariCondition ?>" /></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Categori </td>
                                <td><input class="easyui-combobox form-control" style="width:100%" name="reqCariCategori" data-options="width:'290', height: '36',editable:false, valueField:'id',textField:'text',url:'combo_json/comboEquipCategori?reqMode=ALL'" id="reqCariCategori" value="<?= $reqCariCategori ?>" /></td>
                                <td> Date of Arrived</td>
                                <td><input type="text" name="reqCariDateofArrivedFrom" class="easyui-datebox " id="reqCariDateofArrivedFrom" data-options="height: 36" value="<?= $reqCariDateofArrivedFrom ?>"> to <input type="text" name="reqCariDateofArrivedTo" class="easyui-datebox " id="reqCariDateofArrivedTo"  data-options="height: 36" value="<?= $reqCariDateofArrivedTo ?>"></td>
                                <td>&nbsp;
                                </td>
                            </tr>

                            <tr>
                                <!-- <td>Competent Person </td>
                                <td colspan="2"><input type="text" name="reqCariCompetentPerson" class="form-control" id="reqCariCompetentPerson" value="<?= $reqCariCompetentPerson ?>"></td> -->
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