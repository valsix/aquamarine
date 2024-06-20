<?php
// Header Nama TABEL TH

$this->load->model('LogBaru');
?>


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

    <div class="judul-halaman"> Log History</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
           
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
        </div>


        <table id="example2" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    
                    <th>TYPE</th>
                    <th>USERNAME</th>
                     <th>QUERY</th>
                       <th>TANGGAL LOG</th>
                       <th>TABEL LOG</th>
                    
                </tr>
            </thead>
            <tbody>
                <?
                $logbaru = new LogBaru();
                 $logbaru->selectByParamsMonitoring(array(),-1,-1 , '', ' ORDER BY CREATED_DATE DESC');
                 $arrdata = $logbaru->rowResult;
                 foreach ($arrdata as  $value) {
                ?>
                <tr>
                    <td> <?=$value['modul']?> </td>
                    <td> <?=$value['nama_user']?> </td>
                    <td> <?=$value['data_sql']?> </td>
                     <td> <?=$value['created_date']?> </td>
                     <td> <?=$value['tabel_log']?> </td>
                </tr>
                <?
                 }
                ?>
            </tbody>
        </table>

    </div>


</div>

<!------>

<script type="text/javascript">
    $(document).ready(function() {
         oTable2=   $('#example2').dataTable({
         "bPaginate": true,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": true,
        "bScrollCollapse": true,
        "bInfo": true,
        "bAutoWidth": false,"bStateSave": true,
            "fnStateSave": function(oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function(oSettings) {
                var data = localStorage.getItem('DataTables_' + window.location.pathname);
                return JSON.parse(data);
            },

});

          $('#btnRefresh').on('click', function() {
       Refresh();

    });
         });
</script>