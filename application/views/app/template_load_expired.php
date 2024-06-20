<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");




?>
<base href="<?= base_url(); ?>" />
<link href="libraries/bootstrap-3.3.7/docs/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="libraries/bootstrap-3.3.7/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="libraries/bootstrap-3.3.7/docs/examples/sticky-footer-navbar/sticky-footer-navbar.css" rel="stylesheet">

<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="libraries/bootstrap-3.3.7/docs/assets/js/ie-emulation-modes-warning.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<link rel="stylesheet" href="css/halaman.css" type="text/css">
<link rel="stylesheet" href="css/gaya-egateway.css" type="text/css">
<link rel="stylesheet" href="css/gaya-affix.css" type="text/css">
<link rel="stylesheet" href="css/gaya-pagination.css" type="text/css">
<link rel="stylesheet" href="css/gaya-datatable-egateway.css" type="text/css">
<link rel="stylesheet" href="libraries/font-awesome-4.7.0/css/font-awesome.css">

<script type='text/javascript' src="libraries/bootstrap/js/jquery-1.12.4.min.js"></script>


<!-- DATATABLE -->
<link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/extensions/Responsive/css/dataTables.responsive.css">
<link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/examples/resources/syntax/shCore.css">
<link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/examples/resources/demo.css">

<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/fnReloadAjax.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/examples/resources/demo.js"></script>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />
<!-- EASYUI 1.4.5 -->
<link rel="stylesheet" type="text/css" href="libraries/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="libraries/easyui/themes/icon.css">
<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="libraries/easyui/globalfunction.js"></script>
<script type="text/javascript" src="libraries/easyui/kalender-easyui.js"></script>

<script src="libraries/tinyMCE/tinymce.min.js"></script>

<script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        menubar: true,

    });
</script>


<?php
// Header Nama TABEL TH
// $aColumns = array(
//     "COMPANY_ID",  "NAME", "ADDRESS", "PHONE", "FAX", "EMAIL", "CONTACT","HP"

// );
$aColumns = array(
    "DOCUMENT_ID", "ID_NUMBER", "NAME", "POSITION", "BRANCH/LOCATION", "AGE", "PHONE","CERTIFICATE","REMARKS","STATUS","CERTIFICATE_EXPIRED"
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

?>
<style type="text/css">
    .redClass{
        background-color:  red !important; 
    }
    .yellowClass{
        background-color:  yellow !important; 
    }
    .greenClass{
        background-color:  green !important; 
    }
</style>
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
                null,
                <?php
                for ($i=1; $i < count($arrDatas); $i++) { 
                ?>
                null,
                <?php    
                }
                ?>
                null,
                null,
                null
            ],
            "bSort": true,
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "web/personal_kualifikasi_json/json",
            columnDefs: [{
                className: 'never',
                targets: [0,13,14],
            }],
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "expired", "value": "1" } );
                    
            },
            "sPaginationType": "full_numbers",
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData[13] == "red") {
                    for (var i = 0; i < aData[14].length; i++) {
                        $('td:nth-child('+aData[14][i]+')', nRow).addClass('redClass');
                    }
                } if (aData[13] == "green") {
                    $('td', nRow).addClass('greenClass');
                }if (aData[13] == "yellow") {
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
            anSelectedName = element[1];
            anSelectedAdress = element[2];
            anSelectedEmail = element[5];
            anSelectedTelephone = element[3];
            anSelectedFaximile = element[4];
            anSelectedHp = element[7];
            anSelectedContact = element[6];
        });
        $('#example tbody').on('dblclick', 'tr', function() {

            var anSelected = fnGetSelected(oTable);
            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            anSelectedId = element[0];
            anSelectedName = element[1];
            anSelectedAdress = element[2];
            anSelectedEmail = element[5];
            anSelectedTelephone = element[3];
            anSelectedFaximile = element[4];
            anSelectedHp = element[7];
            anSelectedContact = element[6];

            // // console.log();
            // companyPlihan(anSelectedId, anSelectedName, anSelectedContact, anSelectedAdress, anSelectedEmail, anSelectedTelephone, anSelectedFaximile, anSelectedHp);
            window.open('app/index/personal_kualifikasi_add?reqId='+anSelectedId); 



            // console.log(anIndex);
        });



        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;

            companyPlihan(anSelectedId, anSelectedName, anSelectedContact, anSelectedAdress, anSelectedEmail, anSelectedTelephone, anSelectedFaximile, anSelectedHp);


        });

        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;
            deleteData("web/bussines_plan_json/delete", anSelectedId);
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

<div class="col-md-12">

    <div class="judul-halaman">Personal Certificate</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <!-- <span><a id="btnEdit"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Pilih</a></span> -->

            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
        </div>


        <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th rowspan="2" >ID</th>
                    <?php
                    for ($i = 1; $i < count($aColumns); $i++) {
                        if($aColumns[$i] == "CERTIFICATE")
                        {
                    ?>
                        <th style="text-align: center;" colspan="<?=count($arrDatas)?>" ><?= str_replace('_', ' ', $aColumns[$i])  ?></th>
                    <?php
                        }
                        else
                        {
                    ?>
                        <th rowspan="2" ><?= str_replace('_', ' ', $aColumns[$i])  ?></th>
                    <?php
                        }

                    };
                    ?>
                </tr>
                <tr>
                    <?
                    for($jj=0;$jj<count($arrDatas);$jj++){
                    ?>
                  <th > <?=$arrDatas[$jj]['NAME']?></td>
                  
                  <?
                    }
                  ?>
                </tr>
            </thead>
        </table>

    </div>
    <script type="text/javascript">
        function load_proses(id) {
            console.log(id);
            top.terpilih_template(id);
            top.closePopup();
        }
    </script>
    <script type="text/javascript">
        anSelectedId, anSelectedName, anSelectedContact

        function companyPlihan(id, name, contact, anSelectedAdress, anSelectedEmail, anSelectedTelephone, anSelectedFaximile, anSelectedHp) {
            top.company_pilihans(id, name, contact, anSelectedAdress, anSelectedEmail, anSelectedTelephone, anSelectedFaximile, anSelectedHp);
            top.closePopup();
        }
    </script>

</div>

<script>
    window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
</script>
<script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>