<?php
// Header Nama TABEL TH
$aColumns = array(
    "DOCUMENT_ID", "NAME", "ADDRESS", "BIRTH_DATE", "PHONE", "QUALIFICATION", "CERTIFICATE","STATUS"
);

$this->load->model('PersonalCertificate');
$certificate = new PersonalCertificate();
$certificate->selectByParamsMonitoring(array());
$arrDatas = array();
$no = 0;
while ($certificate->nextRow()) {
    $arrDatas[$no]['ID'] = $certificate->getField("CERTIFICATE_ID");
    $arrDatas[$no]['NAME'] = $certificate->getField("CERTIFICATE");
    $no++;
}
// $reqCariCompanyName = $this->input->post('reqCariCompanyName');
// $reqCariTypeofQualification = $this->input->post('reqCariTypeofQualification');
// $reqTypeOfService = $this->input->post('reqTypeOfService');

// $reqCariCompanyName = $this->input->post('reqCariCompanyName');
// $reqCariDescription = $this->input->post('reqCariDescription');

// $add_str  = "reqCariCompanyName=" . $reqCariCompanyName;
// $add_str .= "&reqCariDescription=" . $reqCariDescription;
// $ids = '';
// for ($k = 0; $k < count($reqTypeOfService); $k++) {
//     if ($k == 0) {
//         $ids .= $reqTypeOfService[$k];
//     } else {
//         $ids .= $reqTypeOfService[$k];
//     }
// }
// $add_str .= "&reqTypeOfService=" . $ids;


?>
<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" language="javascript" class="init">
    var oTable;
    $(document).ready(function() {

        oTable = $('#example').dataTable({
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
            "bStateSave": true,
            "bProcessing": true,
             "bServerSide": false,
            "bScrollInfinite": true,

            "sAjaxSource": "web/personal_kualifikasi_json/json?<?= $add_str ?>",
            columnDefs: [{
                className: 'never',
                targets: [0,7]
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
                 var reqTypeOfService =  $( "input[name^='reqTypeOfService']" ).serializeArray();
                 // console.log(reqTypeOfService);
                 var text= '';
                 for(var i=0;i <reqTypeOfService.length;i++){
                    if(i==0){
                        text +=reqTypeOfService[i]['value'];
                    }else{
                        text +='-'+reqTypeOfService[i]['value'];
                    }
                 }
                 // console.log(text);
                    aoData.push( { "name": "reqCariCompanyName", "value": $("#reqCariCompanyName").val() } );
                 
                      aoData.push( { "name": "reqTypeOfService", "value": text } );
                    aoData.push( { "name": "reqCariTypeofQualification", "value": $("#reqCariTypeofQualification").combobox('getValue') } );
                    
                },
            "sPaginationType": "full_numbers",
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

             if (aData[7] == "red") {
                $('td', nRow).addClass('redClass');
            } if (aData[7] == "green") {
                $('td', nRow).addClass('greenClass');
            }if (aData[7] == "yellow") {
                $('td', nRow).addClass('yellowClass');
            }
        }

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

  $('#example tbody').on('dblclick', 'tr', function() {

            var anSelected = fnGetSelected(oTable);
            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            anIndex = anSelected[0];
            anSelectedId = element[0];
             document.location.href = "app/index/personal_kualifikasi_add?reqId=" + anSelectedId;

            // console.log(anIndex);
        });
       


        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/personal_kualifikasi_add";

        });


        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/personal_kualifikasi_add?reqId=" + anSelectedId;

        });


        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;
            // deleteData("web/personal_kualifikasi_json/delete", anSelectedId);
            deleteData_for_table('web/personal_kualifikasi_json/delete', anSelectedId, anIndex, 1);
        });


        $('#btnRefresh').on('click', function() {

            Refresh();

        });


        $('#btnMasterQ').on('click', function() {
            openAdd('app/loadUrl/app/tempalate_master_qualification');
        });


        $('#btnMasterP').on('click', function() {
            openAdd('app/loadUrl/app/tempalate_master_personal');
        });
        $('#btnMasterCabang').on('click', function() {
            openAdd('app/loadUrl/app/tempalate_master_personal_cabang');
        });


        $('#btnPrint').on('click', function() {
           var reqTypeOfService =  $( "input[name^='reqTypeOfService']" ).serializeArray();
                 // console.log(reqTypeOfService);
                 var text= '';
                 for(var i=0;i <reqTypeOfService.length;i++){
                    if(i==0){
                        text +=reqTypeOfService[i]['value'];
                    }else{
                        text +='-'+reqTypeOfService[i]['value'];
                    }
                }

                

                var add_str  = 'reqCariCompanyName='+$('#reqCariCompanyName').val();
                    add_str  += '&reqTypeOfService='+text;
                     add_str  += '&reqCariTypeofQualification='+$('#reqCariTypeofQualification').combobox('getValue');
            // openAdd('app/loadUrl/report/cetak_personal_kualifikasi_pdf?'+add_str);
            openAdd('app/loadUrl/report/list_personil_pdf?'+add_str);
            
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

    <div class="judul-halaman"> Personal List</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah</a></span>
            <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span>
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
            <span><a id="btnMasterQ"><i class="fa fa-fw fa-gavel " aria-hidden="true"></i> Master Qualification </a></span>
            <span><a id="btnMasterP"><i class="fa fa-fw fa-gavel " aria-hidden="true"></i> Master Personal Certificate </a></span>
            <span><a id="btnMasterCabang"><i class="fa fa-fw fa-gavel " aria-hidden="true"></i> Master Cabang </a></span>
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>
        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td>Name </td>
                                <td><input type="text" name="reqCariCompanyName" class="form-control" id="reqCariCompanyName" value="<?= $reqCariCompanyName ?>"></td>
                                <td></td>
                                <td colspan="2">&nbsp;
                                </td>

                            </tr>
                            <tr>
                                <td valign="top">Type of Qualification </td>
                                <td> <input class="easyui-combobox form-control" style="width:100%" name="reqCariTypeofQualification" id="reqCariTypeofQualification" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'combo_json/combo_jenis_kwalifikasi'" value="<?= $reqCariTypeofQualification ?>" /></td>
                                <td></td>
                                <td colspan="2">&nbsp;
                                </td>

                            </tr>


                            <tr>
                                <td valign="top">Type of Certificate </td>
                                <td colspan="3">
                                    <div style="background-color: white; height: 100px; width: 100%;  overflow-y: scroll;">
                                        <table style="width: 100%;color: black;font-weight: bold;" valign="top">
                                            <?
                                            $nomer = 1;
                                            for ($i = 0; $i < count($arrDatas); $i++) {
                                                $checked = '';
                                                // $reqTypeOfServices = explode(",", $reqTypeOfService);
                                                for ($j = 0; $j < count($reqTypeOfService); $j++) {
                                                    if ($arrDatas[$i]['ID'] == $reqTypeOfService[$j]) {
                                                        $checked = "checked";
                                                    }
                                                }


                                            ?>

                                                <tr>
                                                    <td style="padding: 0px 2px; width: 40px;"><input type="checkbox" class="form-control" name="reqTypeOfService[]" value="<?= $arrDatas[$i]['ID'] ?>" <?= $checked ?> style="margin-top: 0px;"></td>
                                                    <td style="padding: 0px 2px"> <?= $nomer . '. ' . $arrDatas[$i]['NAME'] ?> </td>
                                                </tr>
                                            <?
                                                $nomer++;
                                            }
                                            ?>

                                        </table>
                                    </div>

                                </td>

                                <td><button type="button"  onclick="searching_post()" class="btn btn-default"> Searching </button>
 <button type="submit" style="display: none" id="btn_cari_filters" class="btn btn-default"> Searching </button></td>
                            </tr>
                        </table>
                    </form>

                </div>
            </div>
        </div>
        <br>
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