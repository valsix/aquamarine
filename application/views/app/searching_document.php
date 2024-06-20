<?php
// Header Nama TABEL TH
$aColumns = array(
    "DOCUMENT_ID", "DOCUMENT", "NAME_DOKUMENT"
);

$reqCariLocationFolder = $_SESSION[$pg."reqCariLocationFolder"];
$reqCariFindText = $_SESSION[$pg."reqCariFindText"];

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
                
                null
            ],
            "bSort": true,
            "bProcessing": true,
             "bServerSide": false,
            "bScrollInfinite": true,
            "sAjaxSource": "web/searching_document_json/json?<?=$add_str?>&pg=<?=$pg?>",
            columnDefs: [{
                className: 'never',
                targets: [0]
            }],
            "fnServerParams": function ( aoData ) {
                    aoData.push( { "name": "reqCariLocationFolder", "value": $("#reqCariLocationFolder").val() } );
                    aoData.push( { "name": "reqCariFindText", "value": $("#reqCariFindText").val() } );
                    
                },
            "sPaginationType": "full_numbers"

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
<script>
$(document).ready(function(){
    $("button.pencarian-detil").click(function(){
        $(".area-filter-tambahan").toggle();
        $("i", this).toggleClass("fa-caret-up fa-caret-down");
    });
});
</script>
<style>
.area-filter-tambahan{
    display: none;
}
</style>

<div class="col-md-12">

    <div class="judul-halaman"> Searching Document</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span>&nbsp;</span>
          <!--   <span><a id="btnEdit"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Hapus</a></span> -->
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
             <!-- <span><a id="btnImport"><i class="fa fa-fw fa-upload" aria-hidden="true"></i> Import </a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

      <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default" >
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td> Location Folder </td>
                                 <td>   <input class="easyui-combobox form-control" style="width:100%" name="reqCariLocationFolder" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/comboDokumentPath'" value="<?= $reqCariLocationFolder ?>" /> </td>
                                  <td > Find Text </td>
                                   <td ><input type="text" name="reqCariFindText" class="form-control" id="reqCariFindText" value="<?=$reqCariFindText?>"> </td>
                               
                               
                                <td><button type="button"  onclick="searching_post()" class="btn btn-default"> Searching </button>
 <button type="submit" style="display: none" id="btn_cari_filters" class="btn btn-default"> Searching </button> </td>
                                
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