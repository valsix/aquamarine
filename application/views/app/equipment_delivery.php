<?php
// Header Nama TABEL TH
$aColumns = array(
    "SO_ID","NO.", "EQUIPMENT_LIST NO.", "COMPANY_OF_NAME", "VESSEL_OF_NAME", "TYPE_OF_SERVICE", "VESSEL_OF_CLASS", "LOCATION"
);

$reqCariGlobal = $_SESSION[$pg."reqCariGlobal"];
$reqCariProject = $_SESSION[$pg."reqCariProject"];
$reqCariVasselName = $_SESSION[$pg."reqCariVasselName"];
$reqCariPeriodeYear = $_SESSION[$pg."reqCariPeriodeYear"];
$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqCariPeriodeYearFrom = $_SESSION[$pg."reqCariPeriodeYearFrom"];
$reqCariPeriodeYearTo = $_SESSION[$pg."reqCariIncomingDateFrom"];
$reqDestination  = $_SESSION[$pg."reqDestination"];
$reqBulan   = $_SESSION[$pg."reqBulana"];
$reqClassType = $_SESSION[$pg."reqClassType"];
$reqClass = $_SESSION[$pg."reqClass"];
$this->load->model("ServiceOrder");
$offer = new ServiceOrder();
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
            "sAjaxSource": "web/equipment_delivery_json/json?pg=<?= $pg ?>",
            columnDefs: [{
                className: 'never',
                targets: [0]
            } ,{
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
                    aoData.push( { "name": "reqCariGlobal", "value": $("#reqCariGlobal").val() } );
                    aoData.push( { "name": "reqCariProject", "value": $("#reqCariProject").val() } );
                    aoData.push( { "name": "reqCariVasselName", "value": $("#reqCariVasselName").val() } );
                    aoData.push( { "name": "reqCariPeriodeYear", "value": $("#reqCariPeriodeYear").combobox('getValue') } );
                     aoData.push( { "name": "reqBulan", "value": $("#reqBulan").combobox('getValue') } );
                     aoData.push( { "name": "reqClass", "value": $("#reqClass").combobox('getValue') } );
                     aoData.push( { "name": "reqClassType", "value": $("#reqClassType").combobox('getValue') } );
                      aoData.push( { "name": "reqDestination", "value": $("#reqDestination").combobox('getValue') } );
                    aoData.push( { "name": "reqCariCompanyName", "value": $("#reqCariCompanyName").val() } );
                    aoData.push( { "name": "reqCariPeriodeYearFrom", "value": $("#reqCariPeriodeYearFrom").datebox('getValue') } );
                    aoData.push( { "name": "reqCariPeriodeYearTo", "value": $("#reqCariPeriodeYearTo").datebox('getValue') } );
                    
                },
                
                
            "sPaginationType": "full_numbers",
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
              
                if (aData[8] == "3") 
                {
                    $('td', nRow).addClass('greenClass');
                } 
                // if (aData[6] == "Lunas") 
                // {
                //     $('td', nRow).addClass('greenClass');
                // }      
                if (aData[8] == "2") 
                {
                    $('td', nRow).addClass('yellowClass');
                }

                 if (aData[8] == "1") 
                {
                    $('td', nRow).addClass('redClass');
                }
        }
            //  "fnCreatedRow": function (row, data, index,iEnd, aiDisplay ,iDataIndex) {
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
        });
         $('#example tbody').on('dblclick', 'tr', function() {

            var anSelected = fnGetSelected(oTable);
            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            anIndex = anSelected[0];
            anSelectedId = element[0];
            document.location.href = "app/index/equipment_delivery_slip?reqId="+anSelectedId;

            // openAdd("app/loadUrl/app/template_add_equipment?reqId="+anSelectedId);
            // openAdd("app/loadUrl/app/equipment_delivery_slip?reqId="+anSelectedId);
             // openAdd("app/index/equipment_delivery_slip?reqId="+anSelectedId);

            // console.log(anIndex);
        });


        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/equipment_delivery_slip";

        });

        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/equipment_delivery_slip?reqId=" + anSelectedId;

        });

        $('#btnPrint').on('click', function() {
            if (anSelectedData == ""){
                $.messager.alert('Info', "Pilih data terlebih dahulu", 'info');
                return false;
            }
         
            //          var add_str = "reqCariGlobal="+$("#reqCariGlobal").val() ;
            //               add_str += "&reqCariProject="+$("#reqCariProject").val() ;
            //               add_str += "&reqCariVasselName="+$("#reqCariVasselName").val() ;
            //               add_str += "&reqCariPeriodeYear="+$("#reqCariPeriodeYear").val() ;
            //               add_str += "&reqCariCompanyName="+$("#reqCariCompanyName").val() ;
            //               add_str += "&reqCariPeriodeYearFrom="+$("#reqCariPeriodeYearFrom").datebox('getValue') ;
            //               add_str += "&reqCariPeriodeYearTo="+$("#reqCariPeriodeYearTo").datebox('getValue') ;
            // openAdd('app/loadUrl/report/equipment_delivery_pdf?'+add_str);
            // deleteData("web/bussines_plan_json/delete", anSelectedId);

             openAdd("app/loadUrl/report/equpment_delevery_pdf?reqId="+anSelectedId);
        });

        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            // deleteData("web/service_order_json/delete", anSelectedId);
            deleteData_for_table('web/equipment_delivery_json/delete', anSelectedId, anIndex, 1);

        });


        $('#btnExcel').on('click', function() {
            openAdd('app/loadUrl/app/cetak_equipment_delivery_excel?=<?= $add_str ?>');

        });
         $('#btnPengembalian').on('click', function() {
              if (anSelectedData == "")
                return false;

            openAdd('app/loadUrl/app/template_add_equipment_pengembalian?reqId='+anSelectedId);

        });
          $('#btnEquipmentOut').on('click', function() {
              if (anSelectedData == "")
                return false;

            openAdd('app/loadUrl/app/template_stock?reqKebutuhan=NOBTN&reqModes=SOEQUIPS-'+anSelectedId);

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
</script> -->

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

    <!-- <div class="judul-halaman"> Monitoring Operation Work Request </div> -->
    <div class="judul-halaman"> Monitoring Equipment Project List </div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <!-- <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah</a></span> -->
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
            <span><a id="btnExcel"><i class="fa fa-fw fa-file-excel-o" aria-hidden="true"></i> Export Excel </a></span>
             <!-- <span><a id="btnPengembalian"><i class="fa fa-fw fa-cog" aria-hidden="true"></i> Pengembalian </a></span>
             <span><a id="btnEquipmentOut"><i class="fa fa-fw fa-plug" aria-hidden="true"></i> Equipment Out </a></span> -->
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td>No Order </td>
                                <td><input type="text" name="reqCariNoOrder" class="form-control" id="reqCariNoOrder" value="<?= $reqCariNoOrder ?>"></td>
                                <td>Date of Service</td>
                                <td colspan="2"><input type="text" name="reqCariPeriodeYearFrom" class="easyui-datebox " id="reqCariPeriodeYearFrom" value="<?= $reqCariPeriodeYearFrom ?>"> To <input type="text" name="reqCariPeriodeYearTo" class="easyui-datebox " id="reqCariPeriodeYearTo" value="<?= $reqCariPeriodeYearTo ?>">


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
                                <td>Location </td>
                                <td>   <input type="text" id="reqDestination" placeholder=" Lokasi"  class="easyui-combobox textbox form-control" name="reqDestination" value="<?= $reqDestination ?>" style=" width:250px"
                                     data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/combo_lokasiNama?reqMode=All'"

                                      />   </td>
                                <td colspan="2">

                                  </td>
                                <td>&nbsp;</td>
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
                                <td>Vassel of Name </td>
                                <td><input type="text" name="reqCariVasselName" class="form-control" id="reqCariVasselName" value="<?= $reqCariVasselName ?>"></td>
                                <td>Project Name</td>
                                <td><input type="text" name="reqCariProject" class=" form-control" id="reqCariProject" value="<?= $reqCariProject ?>"></td>
                                <td> </td>

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
        for (var i = 0; i <= limits; i++) {
            var selected = '';
            if ((year + i) == current) {

                selected = '';
                $("#reqCariPeriodeYear").append('<option  value="ALL" > ALL </option>');

                $("#reqCariPeriodeYear").append('<option  value="' + (year + i) + '" ' + selected + '>' + (year + i) + '</option>');
            }
           

        }

    });
</script>
<!------>