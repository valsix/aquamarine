<?php
// Header Nama TABEL TH
$aColumns = array("NERACA_DETIL_ID", "NERACA_ID", "AKTIVA_KAS", "AKTIVA_USAHA", "AKTIVA_GIRO", "AKTIVA_PEMEGANG", "AKTIVA_KARYAWAN", "AKTIVA_PPH21", "AKTIVA_PPH25", "AKTIVA_BIAYA", "AKTIVA_PPN", "AKTIVA_TANAH", "AKTIVA_ALAT", "AKTIVA_KENDARAAN", "AKTIVA_INVENTARIS", "AKTIVA_AKUMULASI", "PASIVA_USAHA", "PASIVA_PAJAK", "PASIVA_LAIN", "PASIVA_BANK", "PASIVA_SAHAM", "PASIVA_MODAL", "PASIVA_DITAHAN", "PASIVA_DITANGGUHKAN", "PASIVA_TAHUN");
?>

<script type="text/javascript" language="javascript" class="init">
    var oTable;
    $(document).ready(function() {

        oTable = $('#example').dataTable({
            bJQueryUI: true,
            "iDisplayLength": 500,
            /* UNTUK MENGHIDE KOLOM ID */
            "aoColumns": [{
                    bVisible: false
                },
                null
            ],
            "bSort": true,
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "web/neraca_json/json",
            columnDefs: [{
                className: 'never',
                targets: [0]
            }],
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
            document.location.href = "app/index/neraca_detail_add";

        });

        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/neraca_detail_add?reqId=" + anSelectedId;

        });

        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            deleteData("web/neraca_detail_json/delete", anSelectedId);

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

    <div class="judul-halaman"> Neraca</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-plus fa-lg" aria-hidden="true"></i> Tambah</a></span>
            <span><a id="btnEdit"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-times fa-lg" aria-hidden="true"></i> Hapus</a></span>
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
        </div>

        <div id="parameter-tambahan">
            Bulan : <input id="reqProvinsi" class="easyui-combobox" name="reqProvinsi" data-options="url:'provinsi_combo_json/json',
					valueField:'id',
					textField:'text'" style="width:100px;" value="<?= $reqProvinsi ?>">
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