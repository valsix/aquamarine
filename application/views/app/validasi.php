<?php
// Header Nama TABEL TH
$aColumns = array("PEGAWAI_ID", "NRP", "NIP", "NAMA", "NAMA_PANGGILAN", "JENIS_KELAMIN", 
            "TEMPAT_LAHIR", "TANGGAL_LAHIR", "UNIT_KERJA", "ALAMAT", "NOMOR_HP", "EMAIL_PRIBADI", 
            "EMAIL_BULOG", "NOMOR_WA");
?>


<script type="text/javascript" language="javascript" class="init">

	var oTable;
    $(document).ready( function () {

        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 25,
			  /* UNTUK MENGHIDE KOLOM ID */
			  "aoColumns": [
							 { bVisible:false },
						<?php
						//
						for($i=2;$i<count($aColumns);$i++){
							echo 'null'.',';
						}
						 ?>
							 null
						],
			  "bSort":true,
			  "bProcessing": true,
			  "bServerSide": true,
			  "sAjaxSource": "web/pegawai_json/json/?reqMode=validasi",
			  columnDefs: [{ className: 'never', targets: [ 0,1 ] }],
			  "sPaginationType": "full_numbers"
			  });
			/* Click event handler */

			  /* RIGHT CLICK EVENT */
			  var anSelectedData = '';
			  var anSelectedId = '';
			  var anSelectedDownload = '';
			  var anSelectedPosition = '';

			  function fnGetSelected( oTableLocal )
			  {
				  var aReturn = new Array();
				  var aTrs = oTableLocal.fnGetNodes();
				  for ( var i=0 ; i<aTrs.length ; i++ )
				  {
					  if ( $(aTrs[i]).hasClass('row_selected') )
					  {
						  aReturn.push( aTrs[i] );
						  anSelectedPosition = i;
					  }
				  }
				  return aReturn;
			  }

			  $("#example tbody").click(function(event) {
					  $(oTable.fnSettings().aoData).each(function (){
						  $(this.nTr).removeClass('row_selected');
					  });
					  $(event.target.parentNode).addClass('row_selected');

					  var anSelected = fnGetSelected(oTable);
					  anSelectedData = String(oTable.fnGetData(anSelected[0]));
					  var element = anSelectedData.split(',');
					  anSelectedId = element[0];
					  anSelectedPublish = element[1];
			  });

			  
			  
			  $('#btnKoreksi').on('click', function () {
				  if(anSelectedData == "")
					  return false;
				  document.location.href = "app/index/validasi_koreksi?reqId="+anSelectedId;

			  });


			  $('#btnEdit').on('click', function () {
				  if(anSelectedData == "")
					  return false;
				  document.location.href = "app/index/validasi_add?reqId="+anSelectedId;

			  });


			   $('#reqMode').combobox({
					onSelect: function(param){
						oTable.fnReloadAjax("web/pegawai_json/json/?reqMode="+param.id);
					}
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
thead.stick-datatable th:nth-child(1){	width:440px !important; *border:1px solid cyan;}

/** TBODY **/
thead.stick-datatable ~ tbody td:nth-child(1){	width:440px !important; *border:1px solid yellow;}

</style>


<div class="col-md-12">

    <div class="judul-halaman">Validasi Pendaftaran</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
    	<div id="bluemenu" class="aksi-area">
            <span>
            <a id="btnKoreksi"><i class="fa fa-edit fa-lg" aria-hidden="true"></i> Koreksi NIP</a>
            <a id="btnEdit"><i class="fa fa-check-square fa-lg" aria-hidden="true"></i> Validasi Pendaftaran</a>
            </span>
            <div class="area-filter">
            	<span style="color:#fff"><strong>Validasi :</strong></span>
                <input name="reqMode" class="easyui-combobox form-control" id="reqMode"
                data-options="width:'160',editable:false,valueField:'id',textField:'text',url:'combo_json/validasi',
                	onSelect: function(rec){
                    }" style="color:#FFFFFF !important" value="validasi" />
            </div>
        </div>

        <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <?php
                    for($i=1;$i<count($aColumns);$i++){
                    ?>
                     <th><?=str_replace('_',' ',$aColumns[$i])  ?></th>
                    <?php

					};
                    ?>
                </tr>
             </thead>
        </table>

    </div>


</div>

<!------>
