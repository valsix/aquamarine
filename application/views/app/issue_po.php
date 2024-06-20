<?php
// Header Nama TABEL TH
// $aColumns = array("ISSUE_PO_ID", "NOMER_PO", "PO_DATE", "DOC_LAMPIRAN", "REFERENSI", "PATH_LAMPIRAN", "FINANCE", "COMPANY_ID", "COMPANY_NAME", "CONTACT", "ADDRESS", "EMAIL", "TELP", "FAX", "HP", "BUYER_ID", "OTHER", "PPN", "PPN_PERCENT", "CREATED_BY", "CREATED_DATE", "UPDATED_BY", "UPDATED_DATE");

$aColumns = array("ISSUE_PO_ID","NO.", "PO NO.", "DATE PO",'CODE', "COMPANY_OF NAME",'PROJECT', "CONTACT NUMBER", "DESCRIPTION", "TOTAL", "STATUS", "DOC_LAMPIRAN", "FINANCE", "ADDRESS", "EMAIL", "TELP", "FAX", "HP", "BUYER_ID", "OTHER", "PPN", "PPN_PERCENT");


$reqPoNumber = $_SESSION[$pg."reqPoNumber"];
$reqCariDateofPoFrom = $_SESSION[$pg."reqCariDateofPoFrom"];
$reqCariDateofPoTo = $_SESSION[$pg."reqCariDateofPoTo"];
$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqAddress = $_SESSION[$pg."reqAddress"];
$reqEmail = $_SESSION[$pg."reqEmail"];
$reqTelp = $_SESSION[$pg."reqTelp"];
$reqFinance = $_SESSION[$pg."reqFinance"];
$reqBulan = $_SESSION[$pg."reqBulan"];
$reqCariPeriodeYear = $_SESSION[$pg."reqCariPeriodeYear"];
$reqBayar = $_SESSION[$pg."reqBayar"];
$reqCodeProject = $_SESSION[$pg."reqCodeProject"];
$reqCariCondition = $_SESSION[$pg."reqCariCondition"];

$this->load->model("IssuePo");
$offer = new IssuePo();
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
                for ($i = 1; $i < count($aColumns) - 1; $i++) {
                    echo 'null,';
                }
                ?>
                null,
            ],
            "bSort": true,
            "bProcessing": true,
            "bServerSide": true,
            "bScrollInfinite": true,
            "sAjaxSource": "web/issue_po_json/json?pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0,11,12,16,17,18,19,20,21]
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
                    aoData.push( { "name": "reqPoNumber", "value": $("#reqPoNumber").val() } );
                    aoData.push( { "name": "reqCariDateofPoFrom", "value": $("#reqCariDateofPoFrom").datebox('getValue') } );
                    aoData.push( { "name": "reqCariDateofPoTo", "value": $("#reqCariDateofPoTo").datebox('getValue') } );
                    aoData.push( { "name": "reqCariCompanyName", "value": $("#reqCariCompanyName").val() } );
                    aoData.push( { "name": "reqAddress", "value": $("#reqAddress").val() } );
                    aoData.push( { "name": "reqEmail", "value": $("#reqEmail").val() } );
                    aoData.push( { "name": "reqTelp", "value": $("#reqTelp").val() } );
                    aoData.push( { "name": "reqFinance", "value": $("#reqFinance").val() } );
                     aoData.push( { "name": "reqBulan", "value": $("#reqBulan").combobox('getValue') } );
                         aoData.push( { "name": "reqCariPeriodeYear", "value": $("#reqCariPeriodeYear").combobox('getValue') } );
                         aoData.push( { "name": "reqCodeProject", "value": $("#reqCodeProject").val() } );
                          aoData.push( { "name": "reqCariCondition", "value": $("#reqCariCondition").combobox('getValue') } );
                            aoData.push( { "name": "reqBayar", "value": $("#reqBayar").val() } );






                   
                },
            "sPaginationType": "full_numbers"
            ,
            //  "fnCreatedRow": function (row, data, index,iEnd, aiDisplay ,iDataIndex) {
            //         var rowCount = <?=$total_row?>;
            //         $('td', row).eq(0).html(rowCount-index);
            // },
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                if (aData[10] == "Belum Bayar") {
                    $('td', nRow).addClass('redClass');
                } if (aData[10] == "green") {
                    $('td', nRow).addClass('greenClass');
                }if (aData[10] == "") {
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
            document.location.href = "app/index/issue_po_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });


        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/issue_po_add";
        });


        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/issue_po_add?reqId=" + anSelectedId;
        });


        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;
            // deleteData("web/bussines_plan_json/delete", anSelectedId);
            deleteData_for_table('web/issue_po_json/delete', anSelectedId, anIndex, 2);
        });


        $('#btnRefresh').on('click', function() {
            Refresh();
        });


        $('#btnPrint').on('click', function() {
           var add_str = "reqPoNumber="+$("#reqPoNumber").val();
           add_str += "&reqCariDateofPoFrom="+$("#reqCariDateofPoFrom").datebox('getValue');
           add_str += "&reqCariDateofPoTo="+$("#reqCariDateofPoTo").datebox('getValue');
           add_str += "&reqCariCompanyName="+$("#reqCariCompanyName").val();
           add_str += "&reqAddress="+$("#reqAddress").val();
           add_str += "&reqEmail="+$("#reqEmail").val();
           add_str += "&reqTelp="+$("#reqTelp").val();
           add_str += "&reqFinance="+$("#reqFinance").val();
          openAdd('app/loadUrl/report/issue_po_pdf?'+add_str);
            // openAdd('report/index/report_cetak_project_cost_pdf?reqId=' + anSelectedId);
        });


        $('#btnImport').on('click', function() {
            openAdd("app/loadUrl/app/import_template_pre_report");

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
      .text-wrap{
    white-space:normal !important;
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

    <div class="judul-halaman"> ISSUE PO PROJECT</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah </a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit </a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Delete </a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
            <span><a id="btnImport"><i class="fa fa-fw fa-upload" aria-hidden="true"></i> Import </a></span>
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">

                            <tr>
                                <td>Order Number </td>
                                <td><input type="text" name="reqPoNumber" class="form-control" id="reqPoNumber" value=""></td>
                                <td>PO Date</td>
                                <td colspan="2"><input type="text" name="reqCariDateofPoFrom" class="easyui-datebox " id="reqCariDateofPoFrom" value=""> To <input type="text" name="reqCariDateofPoTo" class="easyui-datebox " id="reqCariDateofPoTo" value="">


                                </td>

                            </tr>
                            <tr>
                                <td>Company Name </td>
                                <td><input type="text" name="reqCariCompanyName" class="form-control" id="reqCariCompanyName" value=""></td>
                                <td>Address</td>
                                <td>
                                    <input type="text" name="reqAddress" class=" form-control" id="reqAddress" value="">
                                </td>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td>Email </td>
                                <td><input type="text" name="reqEmail" class="form-control" id="reqEmail" value="<?= $reqCariVasselName ?>"></td>
                                <td>Telp</td>
                                <td><input type="text" name="reqTelp" class=" form-control" id="reqTelp" value=""></td>
                                <td> </td>

                            </tr>
                             <tr>
                                <td>Project Code  </td>
                                <td><input type="text" name="reqCodeProject" class="form-control" id="reqCodeProject" value="<?= $reqCodeProject ?>"></td>
                                <td>Nama Project</td>
                                <td><input class="easyui-combobox form-control" style="width:100%" name="reqCariCondition" id="reqCariCondition" data-options="width:'290', height: '36',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_project?reqMode=ALL'" id="reqCariCondition" value="<?= $reqCariCondition ?>" /></td>
                                <td> </td>

                            </tr>
                             <tr>
                                <td>Periode  </td>
                                <td> <input class="easyui-combobox form-control" placeholder="Date" style="width:190px" id="reqBulan" name="reqBulan" data-options="editable:false,height: 34, valueField:'id',textField:'text',url:'combo_json/ComboBulanId'" value="<?= $reqBulan ?>" /> /
                                      <input class="easyui-combobox form-control" style="width:100%" id="reqCariPeriodeYear" name="reqCariPeriodeYear" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/ambil_tahun'" value="<?= $reqCariPeriodeYear ?>" /></td>
                                <td>Status</td>
                                <td>  <select name="reqBayar" id="reqBayar">
                                      <option value="">Pilih Status</option>   
                                      <option value="1"<? if($reqBayar == '1') echo 'selected'?>>Bayar</option>   
                                      <option value="2"<? if($reqBayar == '2') echo 'selected'?>>Belum Bayar</option> 
                                  </select></td>
                                <td> </td>

                            </tr>
                            <tr>
                                <td>Contact </td>
                                <td colspan="2"><input type="text" name="reqFinance" class="form-control" id="reqFinance" value=""></td>
                                <td colspan="2"><button type="button"  onclick="searching_post()" class="btn btn-default"> Searching </button>
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