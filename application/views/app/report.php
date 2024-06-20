<?php
// Header Nama TABEL TH
$aColumns = array(
    "DOCUMENT_ID","NO", "NO_REPORT", "COMPANY NAME", "NAME_OF_VESSEL","CLASS_OF_VESSEL", "TYPE_OF_VESSEL", "SCOPE_OF_WORK", 
    "LOCATION", "STATUS", "DESCRIPTION", "PATH", "DATE_WORK", "FINISH_DATE", "DATE_DELIVERY_REPORT",
            "INVOICE_DATE", "NOTE", "CLASS_SOCIETY", "NO_OWR","SURVEYOR","OPERATOR"
);

$reqCariName             =  $_SESSION[$pg."reqCariName"] ;
$reqCariNameClient       =  $_SESSION[$pg."reqCariNameClient"];
$reqCariVesselClass       =  $_SESSION[$pg."reqCariVesselClass"] ;
$reqCariVesselType       =  $_SESSION[$pg."reqCariVesselType"];
$reqCariDateWork         =  $_SESSION[$pg."reqCariDateWork"];
$reqCariDateComplate     =  $_SESSION[$pg."reqCariDateComplate"] ;
$reqCariScopeOfWork      =  $_SESSION[$pg."reqCariScopeOfWork"] ;
$reqCariLocation         =  $_SESSION[$pg."reqCariLocation"] ;
$reqCariStatus           =  $_SESSION[$pg."reqCariStatus"] ;
$reqCariOperator         =  $_SESSION[$pg."reqCariOperator"] ;
$reqCariSurveyor         =  $_SESSION[$pg."reqCariSurveyor"] ;
$reqCariTahun            =  $_SESSION[$pg."reqCariTahun"] ;
$reqCariBulan            =  $_SESSION[$pg."reqCariBulan"] ;
$reqCariVesselName  =  $_SESSION[$pg."reqCariVesselName"] ;
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
            "aoColumns": [
            {
                    bVisible: false
                }
            
                ,
                <?
                for($i=1;$i<count($aColumns)-1;$i++){
                    echo 'null,';
                }
                ?>
                null
            ],
            "bSort": true,
            "bProcessing": true,
            "bServerSide": true,
            "bScrollInfinite": true,
            "sAjaxSource": "web/report_json/json?<?= $add_str ?>&pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [ 0,9,10,11,14,15,16,17,18]
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

                aoData.push( { "name": "reqCariName", "value": $("#reqCariName").val() } );
                aoData.push( { "name": "reqCariNameClient", "value": $("#reqCariNameClient").val() } );
                aoData.push( { "name": "reqCariVesselClass", "value": $("#reqCariVesselClass").combobox('getValue')} );
                aoData.push( { "name": "reqCariVesselType", "value": $("#reqCariVesselType").combobox('getValue') } );
                aoData.push( { "name": "reqCariDateWork", "value": $("#reqCariDateWork").datebox('getValue') } );
                aoData.push( { "name": "reqCariDateComplate", "value": $("#reqCariDateComplate").datebox('getValue') } );
                aoData.push( { "name": "reqCariScopeOfWork", "value": $("#reqCariScopeOfWork").combobox('getValue') } );
                aoData.push( { "name": "reqCariLocation", "value": $("#reqCariLocation").val() } );
                aoData.push( { "name": "reqCariStatus", "value": $("#reqCariStatus").combobox('getValue') } );
                aoData.push( { "name": "reqCariSurveyor", "value": $("#reqCariSurveyor").val() } );
                aoData.push( { "name": "reqCariOperator", "value": $("#reqCariOperator").val() } );
                 aoData.push( { "name": "reqCariVesselName", "value": $("#reqCariVesselName").val() } );
                aoData.push( { "name": "reqCariBulan", "value": $("#reqCariBulan").combobox('getValue') } );
                aoData.push( { "name": "reqCariTahun", "value": $("#reqCariTahun").combobox('getValue') } );
                aoData.push( { "name": "reqSuryeiOwr", "value": $("#reqSuryeiOwr").combobox('getValue') } );
             
                },
            "sPaginationType": "full_numbers",
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                if (aData[9] == "Pending") 
                {
                    $('td', nRow).addClass('yellowClass');
                }      
                else if (aData[9] == null || aData[9] == "") 
                {
                    $('td', nRow).addClass('grayClass');
                }
                else if (aData[9] == "Cancel") 
                {
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
            document.location.href = "app/index/report_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });


        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/report_add";

        });


        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/report_add?reqId=" + anSelectedId;

        });


        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            deleteData_for_table('web/report_json/delete', anSelectedId, anIndex, 1);
            // deleteData("web/report_json/delete", anSelectedId);
        });


        $('#btnRefresh').on('click', function() {
            Refresh();
        });


        $('#btnMasterQ').on('click', function() {
            openAdd('app/loadUrl/app/tempalate_master_type_qualification');
        });
         $('#btnMasterSuvey').on('click', function() {
            openAdd('app/loadUrl/app/tempalate_master_surveyor');
        });


        $('#btnPrint').on('click', function() {

            

                  var add_str = "reqCariName="+$("#reqCariName").val();                
                      add_str += "&reqCariNameClient="+$("#reqCariNameClient").val();
                      add_str += "&reqCariVesselClass="+$("#reqCariVesselClass").combobox('getValue');
                      add_str += "&reqCariVesselType="+$("#reqCariVesselType").combobox('getValue');
                      add_str += "&reqCariDateWork="+$("#reqCariDateWork").datebox('getValue');
                      add_str += "&reqCariDateComplate="+$("#reqCariDateComplate").datebox('getValue');
                      add_str += "&reqCariScopeOfWork="+$("#reqCariScopeOfWork").combobox('getValue');
                      add_str += "&reqCariLocation="+$("#reqCariLocation").val();
                      add_str += "&reqCariStatus="+$("#reqCariStatus").combobox('getValue');
                       

            openAdd('app/loadUrl/report/cetak_report_survey_pdf?'+add_str+"&pg=<?=$pg?>");
            // openAdd('report/index/report_cetak_project_cost_pdf?reqId=' + anSelectedId);
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

    <div class="judul-halaman">Report Survey</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah</a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
            <span><a id="btnMasterQ"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Master </a></span>
             <span><a id="btnMasterSuvey"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Master Suryevor</a></span>
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td>No Report </td>
                                <td colspan="2"><input type="text" name="reqCariName" class="form-control" id="reqCariName" value="<?= $reqCariName ?>"></td>

                                <td colspan="2">&nbsp;

                                </td>

                            </tr>
                            <tr>
                                <td>Nama Client </td>
                                <td colspan="2"><input type="text" name="reqCariNameClient" class="form-control" id="reqCariNameClient" value="<?= $reqCariNameClient ?>"></td>

                                <td colspan="2">&nbsp;

                                </td>

                            </tr>
                             <tr>
                                <td>Vessel Name </td>
                                <td colspan="2"><input type="text" name="reqCariVesselName" class="form-control" id="reqCariVesselName" value="<?= $reqCariVesselName ?>"></td>

                                <td colspan="2">&nbsp;

                                </td>

                            </tr>
                            <tr>
                                <td>Vessel Class </td>
                                <td >

                                     <input class="easyui-combobox form-control" style="width:100%" id="reqCariVesselClass" name="reqCariVesselClass" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel?reqMode=ALL'" value="<?= $reqCariVesselClass ?>" />
                                </td>

                             
                                 <td align="right">Vessel Type </td>
                                  <td> 
<input class="easyui-combobox form-control" style="width:100%" id="reqCariVesselType" name="reqCariVesselType" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueTypeOfVessel?reqMode=ALL'" value="<?= $reqCariVesselType ?>" />

                                  </td>
                              
                                   <td >&nbsp;</td>

                            </tr>
                             <tr>
                                <td>Date Work </td>
                                <td ><input type="text" name="reqCariDateWork" class="easyui-datebox" id="reqCariDateWork" value="<?= $reqCariDateWork ?>"></td>

                               
                                 <td align="right">Date Completed</td>
                                  <td> <input type="text" name="reqCariDateComplate" class="easyui-datebox" id="reqCariDateComplate" value="<?= $reqCariNameClient ?>"></td>
                                   <td >&nbsp;</td>
                              

                            </tr>
                               <tr>
                                <td>Services  </td>
                                <td colspan="2">
                                      <input class="easyui-combobox form-control" style="width:100%" id="reqCariScopeOfWork" name="reqCariScopeOfWork" data-options="width:'350',editable:false, valueField:'id',textField:'text',url:'web/services_json/comboAll'" value="<?= $reqCariScopeOfWork ?>" />
                                </td>

                                <td colspan="2">&nbsp;

                                </td>

                            </tr>
                             <tr>
                                <td>Location  </td>
                                <td ><input type="text" name="reqCariLocation" class="form-control" id="reqCariLocation" value="<?= $reqCariLocation ?>"></td>

                                
                                 <td align="right">Status</td>
                                  <td> 

                                    <input class="easyui-combobox form-control" style="width:100%" id="reqCariStatus" name="reqCariStatus" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboStatusReport?reqMode=ALL'" value="<?= $reqCariStatus ?>" /></td>
                                <td >&nbsp;</td>

                            </tr>
                            <tr>
                                <td> Surveyor / Operator </td>
                                <td colspan="3"> <input  style="width: 40%" type="text" name="reqCariSurveyor" class="form-control" id="reqCariSurveyor" value="<?= $reqCariSurveyor ?>" > / <input style="width: 30%" type="text" name="reqCariOperator" class="form-control" id="reqCariOperator" value="<?= $reqCariOperator ?>"></td>
                             <td >&nbsp;</td>

                            </tr>
                             <tr>
                                <td> Surveyor OWR </td>
                                <td colspan="3"> 
                                     <input class="easyui-combobox form-control" style="width:100%" id="reqSuryeiOwr" name="reqSuryeiOwr" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/combo_master_surveyor?reqMode=ALL'" value="<?= $reqSuryeiOwr ?>" />

                                </td>
                             <td >&nbsp;</td>

                            </tr>
                             <tr>
                                <td> Bulan / Tahun </td>
                                <td colspan="3">

                                    <input class="easyui-combobox form-control" style="width:100%" id="reqCariBulan" name="reqCariBulan" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/ComboBulanAllBaru?reqMode=ALL'" value="<?= $reqCariBulan ?>" />

                                 / 

                                   <input class="easyui-combobox form-control" style="width:100%" id="reqCariTahun" name="reqCariTahun" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/ambil_all_tahun?reqMode=ALL'" value="<?= $reqCariTahun ?>" />

                              


                                </td>
                             <td >&nbsp;</td>

                            </tr>
                           


                            <tr>
                                <td>&nbsp; </td>
                                <td colspan="3">&nbsp; </td>

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
                    <th>NO</th>
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