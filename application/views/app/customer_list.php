<?php
// Header Nama TABEL TH
$aColumns = array(
    "COMPANY_ID", "", "NO.","COMPANY NAME", "CONTACT NAME", "PHONE","HANDPHONE", "EMAIL", "ADDRESS", "FAX",
    "CP1_TELP","SUPPORT"
);
$arrWidthData= array("2", "2", "2", "20","8","8","8","8","20","8","10");

$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqCariContactPerson = $_SESSION[$pg."reqCariContactPerson"];
$reqCariVasselName = $_SESSION[$pg."reqCariVasselName"];
$reqCariEmailPerson = $_SESSION[$pg."reqCariEmailPerson"];

?>

<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" language="javascript" class="init">
  

   
    var oTable;
    $(document).ready(function() {

        oTable = $('#example').dataTable({
            "oSearch": {"sSearch": sessionStorage.getItem("<?=$pg?>_sSearch") || ""},
            bJQueryUI: true,
            "iDisplayLength": 25,
                "lengthMenu": [
        [10, 25, 50, -1],
        [10, 25, 50, 'All']
    ],
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
                null
            ],
            "bSort": true,
            // "bProcessing": true,
            // "bServerSide": true,
            "bScrollInfinite": true,
            "sAjaxSource": "web/customer_json/json?pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0,11]
            },{targets:[1,2,3,4,5,6,7,8,9,10,11,12], class:"text-wrap"},{ width: 200, targets: [4,9] },{ width: 50, targets: [1,2,3] },{ width: 150, targets: [5,6,7,10] },{ width: 55, targets: [9,10] },{ width: 160, targets: [8] },],
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
                    aoData.push( { "name": "reqCariContactPerson", "value": $("#reqCariContactPerson").val() } );
                    aoData.push( { "name": "reqCariVasselName", "value": $("#reqCariVasselName").val() } );
                    aoData.push( { "name": "reqCariEmailPerson", "value": $("#reqCariEmailPerson").val() } );
                },
            "sPaginationType": "full_numbers",
            fixedColumns: true

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

            var boolean =true;
            var params = oTable.$('input').serializeArray();
            $.each(params, function(i, field) {
                // kode = kode + field.value + ',';
                if(field.value !=''){
                boolean = false;
                }
            });

            if(boolean){
            document.location.href = "app/index/customer_list_add?reqId=" + anSelectedId;
            }
            // console.log(anIndex);
        });


        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/customer_list_add";

        });

        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/customer_list_add?reqId=" + anSelectedId;

        });

        $('#btnDelete').on('click', function() {
            



            var ttds = '';
            var kode = '';
            var params = oTable.$('input').serializeArray();
            $.each(params, function(i, field) {
                kode = kode + field.value + ',';
            });

            if (kode == '') {
                     if (anSelectedData == ""){
                        return false;
                    }else{
                         deleteData_for_table('web/customer_json/deleteCompany', anSelectedId, anIndex, 2);
                    }
            } else {
                // openAdd('app/loadUrl/app/tempalate_cetak_company?reqIds=' + kode);
                deleteData('web/customer_json/deleteCompany',kode);
            }



            // deleteData("web/customer_json/deleteCompany", anSelectedId);
             

        });

        $('#btnRefresh').on('click', function() {
            document.location.reload();

        });

        $('#btnExcel').on('click', function() {

            cetak_print_excel();
        });
         $('#btnExcelAll').on('click', function() {

            cetak_print_excel_all();
        });

        


        $('#btnPrint').on('click', function() {

            cetak_print_pdf();
        });


        $('#btnPrint2').on('click', function() {
            openAdd('app/loadUrl/report/cetak_customer_list_pdf?<?= $str_urlAdd ?>');
            // openAdd('report/index/report_cetak_project_cost_pdf?reqId=' + anSelectedId);
        });

        $('#btnSendMail').on('click', function() {

            sendMail();
        });

        $('#btnImport').on('click', function() {

            importFiles();
        });

        $('#btnSendMail').on('click', function() {

            company_terpilih();
        });
         $('#btnList').on('click', function() {

            openAdd('app/loadUrl/app/tempalate_attacment_experience');
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
    td {
        white-space: nowrap;
    }

    td.wrapok {
        white-space:normal
    }
     .text-wrap{
    white-space:normal !important;

}
#example_length{
    display: block;
}
</style>


<div class="col-md-12">
    <div class="judul-halaman"> Monitoring Costumer List </div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah </a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit </a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Delete </a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnImport"><i class="fa fa-fw fa-upload" aria-hidden="true"></i> Import </a></span>
            <span><a id="btnPrint2"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
            <span><a id="btnExcel"><i class="fa fa-fw fa-file-excel-o " aria-hidden="true"></i> Export Excel </a></span>
            <span><a id="btnExcelAll"><i class="fa fa-fw fa-file-excel-o " aria-hidden="true"></i> Export All Excel </a></span>
            <span><a id="btnSendMail"><i class="fa fa-fw fa-envelope-o" aria-hidden="true"></i> Send Email </a></span>
            <span><a id="btnList"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> List Experience </a></span>

            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table style="width: 100%">
                            <tr>
                                <td>Company Name </td>
                                <td><input type="text" name="reqCariCompanyName" id="reqCariCompanyName" value="<?= $reqCariCompanyName ?>"></td>
                                <td>Contact Person</td>
                                <td><input type="text" name="reqCariContactPerson" id="reqCariContactPerson" value="<?= $reqCariContactPerson ?>"></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Vessel Name </td>
                                <td><input type="text" name="reqCariVasselName" id="reqCariVasselName" value="<?= $reqCariVasselName ?>"></td>
                                <td>Email</td>
                                <td><input type="text" name="reqCariEmailPerson" id="reqCariEmailPerson" value="<?= $reqCariEmailPerson ?>"></td>
                                <td><button type="button"  onclick="searching_post()" class="btn btn-default"> Searching </button>
                                    <button type="submit" style="display: none" id="btn_cari_filters" class="btn btn-default"> Searching </button></td>
                            </tr>
                            <tr>
                                <td colspan="5" style="padding: 10px 0px 10px 10px">
                                    <button class="btn btn-default" type="button" onclick="check()"> Check All </button>
                                    <button class="btn btn-default" type="button" onclick="uncheck()"> Uncheck All </button>
                                </td>
                            </tr>

                        </table>
                    </form>

                </div>
            </div>
        </div>




        <!-- <div id="parameter-tambahan">
            Bulan : <input id="reqN" class="easyui-combobox" name="reqProvinsi" data-options="url:'provinsi_combo_json/json',
					valueField:'id',
					textField:'text'" style="width:100px;" value="<?= $reqProvinsi ?>">
        </div>
        <br> -->
        <div class="table-responsive">
        <table id="example" class="table table-striped table-hover dt-responsive" style=" width: 100%" cellspacing="0" style="width: 100%" >
            <thead>
                <tr>
                    <th>ID</th>
                    <th width="2%"></th>
                    <?php
                    for ($i = 1; $i < count($aColumns); $i++) {
                         // $width= $arrWidthData[$i];
                    ?>
                        <!-- <th width="<?=$width?>%"><?= str_replace('_', ' ', $aColumns[$i])  ?></th>  -->
                      <th  ><?= str_replace('_', ' ', $aColumns[$i])  ?></th> 
                    <?php

                    };
                    ?>
                </tr>
            </thead>
        </table>
    </div>
    </div>  


</div>
<form id="myForm" action="app/index/e_commerce" method="post" style="display: none;">
    <input type="text" name="reqKode" id="reqKode">
    <button type="submit" id="clik">Click</button>
</form>
<script type="text/javascript">
    $('#select_all').click(function(event) {
        if (this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $(':checkbox').each(function() {
                this.checked = false;
            });
        }
    });
</script>

<script type="text/javascript">
    function check() {
        $(':checkbox').each(function() {
            this.checked = true;
        });
    }


    function uncheck() {
        $(':checkbox').each(function() {
            this.checked = false;
        });
    }


        

    function cetak_print_excel() {
        var ttds = '';
        var kode = '';
        var params = oTable.$('input').serializeArray();
        $.each(params, function(i, field) {
            kode = kode + field.value + ',';
        });

        if (kode == '') {
            return;
        } else {
            openAdd('app/loadUrl/app/tempalate_cetak_company?reqIds=' + kode);
        }
    }

     function cetak_print_excel_all() {
        var ttds = '';
            openAdd('app/loadUrl/app/tempalate_cetak_company_all');
        
    }


    function cetak_print_pdf() {
        var ttds = '';
        var kode = '';
        var params = oTable.$('input').serializeArray();
        $.each(params, function(i, field) {
            kode = kode + field.value + ',';
        });

        if (kode == '') {
            return;
        } else {
            openAdd('report/index/costumer_list_pdf?reqId=' + kode);
        }
    }


    function sendMail() {
        window.location.href = "app/index/e_commerce"
    }


    function importFiles() {
        openAdd('app/loadUrl/app/import_template');
    }


    function company_terpilih() {
        var ttds = '';
        var kode = '';
        var params = oTable.$('input').serializeArray();
        $.each(params, function(i, field) {
            kode = kode + field.value + ',';
        });

        if (kode != '') {
            $("#reqKode").val(kode);
            $("#clik").click();
        }

    }
</script>


<!------>