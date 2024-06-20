<?php
// Header Nama TABEL TH
$aColumns = array("HPP_PROJECT_ID","NO.","HPP PROJECT_NO.","COMPANY OF NAME","VESSEL OF NAMA","TYPE OF SERVICE","CLASS VESSEL","TYPE OF VESSEL","SWL","LOA","LOCATION","REF_NO","DATE","COST_FROM_AMDI","PROFIT","STATUS_APPROVED");

$reqCariNoOrder = $_SESSION[$pg."reqCariNoOrder"];
// $reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqCariPeriodeYearFrom = $_SESSION[$pg."reqCariPeriodeYearFrom"];
$reqCariPeriodeYearTo = $_SESSION[$pg."reqCariPeriodeYearTo"];
// $reqCariVasselName = $_SESSION[$pg."reqCariVasselName"];

$reqCariPekerjaan = $_SESSION[$pg."reqCariPekerjaan"];
$reqCariClass = $_SESSION[$pg."reqCariClass"];
$reqCariTypeVessel = $_SESSION[$pg."reqCariTypeVessel"];
$reqCariOwner = $_SESSION[$pg."reqCariOwner"];
$reqCariVessel = $_SESSION[$pg."reqCariVessel"];
$reqCariLocation= $_SESSION[$pg."reqCariLocation"];
$reqCariPeriodeYear = $_SESSION[$pg."reqCariPeriodeYear"];
  $this->load->model("ProjectHpp");
  $project_hpp = new ProjectHpp();
  $statement =  $_SESSION[$pg."reqCariSession"];
  $total_row = $project_hpp->getCountByParamsMonitoring(array(),$statement);

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
            "sAjaxSource": "web/project_hpp_json/json?<?= $add_str ?>&pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0,11,15],
            },
            {
"targets": 1,
"orderable": false
}
            ],
             "order": [[ 0, "asc" ]],
            "bStateSave": true,
          
            "fnServerParams": function ( aoData ) {
                    aoData.push( { "name": "reqCariNoOrder", "value": $("#reqCariNoOrder").val() } );
                    // aoData.push( { "name": "reqCariCompanyName", "value": $("#reqCariCompanyName").val() } );
                    aoData.push( { "name": "reqCariPeriodeYearFrom", "value": $("#reqCariPeriodeYearFrom").datebox('getValue') } );
                    aoData.push( { "name": "reqCariPeriodeYearTo", "value": $("#reqCariPeriodeYearTo").datebox('getValue') } );
                      // aoData.push( { "name": "reqCariPeriodeYearTo", "value": $("#reqCariPeriodeYearTo").datebox('getValue') } );


                        aoData.push( { "name": "reqCariClass", "value": $("#reqCariClass").combobox('getValue') } );
                         aoData.push( { "name": "reqCariVessel", "value": $("#reqCariVessel").val() } );
                          aoData.push( { "name": "reqCariTypeVessel", "value": $("#reqCariTypeVessel").combobox('getValue') } );
                          aoData.push( { "name": "reqCariOwner", "value": $("#reqCariOwner").val() } );
                           aoData.push( { "name": "reqCariPekerjaan", "value": $("#reqCariPekerjaan").combobox('getValue') } );
                           aoData.push( { "name": "reqCariLocation", "value": $("#reqCariLocation").combobox('getValue')  } );
                            aoData.push( { "name": "reqCariPeriodeYear", "value": $("#reqCariPeriodeYear").combobox('getValue') } );

                     // aoData.push( { "name": "reqCariVasselName", "value": $("#reqCariVasselName").val() } );
                },
            "sPaginationType": "full_numbers",
          //    "fnCreatedRow": function (row, data, index,iEnd, aiDisplay ,iDataIndex) {
          //         // var rowCount = oTable.fnGetData().length;
          //         var rowCount = <?=$total_row?>;
          //       // console.log(index);
          //        // console.log(data);
          //   $('td', row).eq(0).html(rowCount-index);
          // },
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                // console.log(iDisplayIndex);
                if (aData[15] != null) {
                    $('td', nRow).addClass('greenClass');
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
            document.location.href = "app/index/project_hpp_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });


        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/project_hpp_add";

        });


        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/project_hpp_add?reqId=" + anSelectedId;

        });


        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            deleteData_for_table('web/project_hpp_json/delete', anSelectedId, anIndex, 1);

            // deleteData("web/project_cost_json/delete", anSelectedId);

        });


        $('#btnRefresh').on('click', function() {
            Refresh();

        });

     $('#btnMaster').on('click', function() {
            openAdd('app/loadUrl/app/template_add_hpp_master');
        });
       $('#btnLokasi').on('click', function() {
            openAdd('app/loadUrl/app/template_add_master_lokasi');
        });


        $('#btnExcel').on('click', function() {
          var add_str = "reqCariNoOrder="+$("#reqCariNoOrder").val();                
                      // add_str += "&reqCariCompanyName="+$("#reqCariCompanyName").val();
                      add_str += "&reqCariPeriodeYearFrom="+$("#reqCariPeriodeYearFrom").datebox('getValue');
                      add_str += "&reqCariPeriodeYearTo="+$("#reqCariPeriodeYearTo").datebox('getValue');
            openAdd('app/loadUrl/app/excel_project_hpp?'+add_str);
        });


        $('#btnPrint').on('click', function() {
           

                      var add_str = "reqCariNoOrder="+$("#reqCariNoOrder").val();                
                      // add_str += "&reqCariCompanyName="+$("#reqCariCompanyName").val();
                      add_str += "&reqCariPeriodeYearFrom="+$("#reqCariPeriodeYearFrom").datebox('getValue');
                      add_str += "&reqCariPeriodeYearTo="+$("#reqCariPeriodeYearTo").datebox('getValue');
                      // add_str += "&reqCariVasselName="+$("#reqCariVasselName").val();
            openAdd('app/loadUrl/report/project_hpp_pdf?'+add_str);
            // openAdd('report/index/report_cetak_project_cost_pdf?reqId=' + anSelectedId);
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

    <div class="judul-halaman"> HPP PROJECT LIST</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah </a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Delete</a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
            <span><a id="btnExcel"><i class="fa fa-fw fa-file-excel-o " aria-hidden="true"></i> Export Excel </a></span>
             <span><a id="btnMaster"><i class="fa fa-fw fa-folder-o" aria-hidden="true"></i> Master Code  </a></span>
              <span><a id="btnLokasi"><i class="fa fa-fw fa-folder-o" aria-hidden="true"></i> Master Lokasi  </a></span>
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td>Nama Project </td>
                                <td><input type="text" name="reqCariNoOrder" class="form-control" id="reqCariNoOrder" value="<?= $reqCariNoOrder ?>"></td>
                                <td>Date of Project</td>
                                <td colspan="2"><input type="text" name="reqCariPeriodeYearFrom" class="easyui-datebox " id="reqCariPeriodeYearFrom" value="<?= $reqCariPeriodeYearFrom ?>" data-options="height: 36"> To <input type="text" name="reqCariPeriodeYearTo" class="easyui-datebox " id="reqCariPeriodeYearTo" value="<?= $reqCariPeriodeYearTo ?>" data-options="height: 36">

                                </td>

                            </tr>
                            <tr>
                                <td> Owner</td>
                                <td > <input type="text" name="reqCariOwner" class="easyui-textbox form-control" id="reqCariOwner" value="<?= $reqCariOwner ?>" style="width: 30%" data-options="height: 36"></td>
                                 <td>Periode Year</td>
                                <td>
                                     <input class="easyui-combobox form-control" style="width:100%" id="reqCariPeriodeYear" name="reqCariPeriodeYear" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/ambil_tahun'" value="<?= $reqCariPeriodeYear ?>" />
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                           
                            <tr>
                                <td> Vessel </td>
                                <td > <input type="text" name="reqCariVessel" class="easyui-textbox form-control" id="reqCariVessel" value="<?= $reqCariVessel ?>" style="width: 100%" data-options="height: 36"></td>
                                <td>Pekerjaan</td>
                                <td colspan="2"><input class="easyui-combobox form-control" style="width:100%" name="reqCariPekerjaan" id="reqCariPekerjaan" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'web/services_json/comboAll'" value="<?= $reqCariPekerjaan ?>" /> </td>
                            </tr>

                           <!--  <tr>
                                <td> Class </td>
                                <td > <input type="text" name="reqCariClass" class="easyui-textbox form-control" id="reqCariClass" value="<?= $reqCariClass ?>" style="width: 100%" data-options="height: 36"></td>
                                <td>Type Vessel</td>
                                <td colspan="2"><input type="text" name="reqCariTypeVessel" class="easyui-textbox form-control" id="reqCariTypeVessel" value="<?= $reqCariTypeVessel ?>" style="width: 40%" data-options="height: 36"> </td>
                            </tr> -->

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
                                <td>   </td>
                                <td > </td>
                                <td>Location</td>
                                <td colspan="2">
                                      <input type="text" id="reqCariLocation" placeholder=" Lokasi"  class="easyui-combobox textbox form-control" name="reqCariLocation" value="<?= $reqCariLocation ?>" style=" width:250px"
                                     data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/combo_lokasi?reqMode=All'"

                                      />

                               <!--      <input type="text" name="reqCariLokasi" class="easyui-textbox form-control" id="reqCariLokasi" value="<?= $reqCariLokasi ?>" style="width: 40%" data-options="height: 36"> </td> -->
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