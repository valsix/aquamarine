<?php
// Header Nama TABEL TH
$aColumns = array(
    "DOCUMENT_ID", "COMPANY_NAME", "VESSEL_NAME", "DESCRIPTION", "DATE", "LAST_REVISI", "TYPE_OF_SERVICE", "LOCATION", "DATE_OPERATION", "CLASS"
);

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
                null,
                null,
                null
            ],
            "bSort": true,
            "bProcessing": true,
             "bServerSide": false,
            "bScrollInfinite": true,
            "sAjaxSource": "web/dokumen_marketing_json/json",
            columnDefs: [{
                className: 'never',
                targets: [0, 5]
            }],
            "fnServerParams": function ( aoData ) {
                    aoData.push( { "name": "reqCariCompanyName", "value": $("#reqCariCompanyName").val() } );
                    aoData.push( { "name": "reqCariContactPerson", "value": $("#reqCariContactPerson").val() } );
                    aoData.push( { "name": "reqCariVasselName", "value": $("#reqCariVasselName").val() } );
                    aoData.push( { "name": "reqCariEmailPerson", "value": $("#reqCariEmailPerson").val() } );
                },
            "sPaginationType": "full_numbers"

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
        });

        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/bussines_plan_add";

        });

        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/bussines_plan_add?reqId=" + anSelectedId;

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

<style type="text/css">
    #tablei tr td {
        padding: 5px;
        font-weight: bold;
    }
</style>

<div class="col-md-12">

    <div class="judul-halaman"> Monitoring Company Experience</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Add </a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Delete </a></span>
            <span><a id="btnAdd"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>


        </div>

        <div class="col-md-7" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td>Find </td>
                                <td colspan="2"><input type="text" name="reqCariCompanyName" class="form-control" id="reqCariNoOrder" value="<?= $reqCariNoOrder ?>"></td>

                                <td colspan="2">&nbsp;


                                </td>

                            </tr>


                            <tr>
                                <td>Find Menthod </td>
                                <td colspan="2"> <select class="form-control">
                                        <option value=""> </option>
                                        <option value="Application"> Application</option>
                                        <option value="FileContent"> File Content </option>
                                    </select></td>
                                <td colspan="2">&nbsp;</td>
                            </tr>



                            <tr>
                                <td>&nbsp; </td>
                                <td colspan="3"> &nbsp; </td>

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