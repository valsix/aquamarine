<?php
// Header Nama TABEL TH
$aColumns = array("SLIDER_ID","TANGGAL","PUBLISH","NOTIF","NAMA","KETERANGAN");
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
			  "sAjaxSource": "web/slider_json/json/?reqJenis=MESSAGEBOD",		
			  columnDefs: [{ className: 'never', targets: [ 0 ] }],
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
					  anSelectedNotif = element[2];
			  });
			  
			  
			  $('#btnAdd').on('click', function () {
				  document.location.href = "app/index/message_bod_add";  		
			  });
			  
			  $('#btnEdit').on('click', function () {
				  if(anSelectedData == "")
					  return false;	
				  document.location.href = "app/index/message_bod_add?reqId="+anSelectedId;  			

			  });
			  
			  $('#btnDelete').on('click', function () {
				  if(anSelectedData == "")
					  return false;	
					  
				  deleteData("web/slider_json/delete", anSelectedId);

			  });
			  
			  $('#btnNotifikasi').on('click', function () {
				  if(anSelectedData == "")
					  return false;	
					  
			      var pesan = "";
				  if(anSelectedNotif == '<i class="fa fa-close fa-lg" aria-hidden="true"></i>')
				  		pesan = "Kirim notifikasi?";	
				  else
				  		pesan = "Kirim ulang notifikasi?";	
				    
				  konfirmasiAksi(pesan, "web/slider_json/kirim_notifikasi", anSelectedId);

			  });
			  
			  $('#btnPublish').on('click', function () {
				  if(anSelectedData == "")
					  return false;	
					  
			      var pesan = "";
				  var status = "";
				  if(anSelectedPublish == '<i class="fa fa-close fa-lg" aria-hidden="true"></i>')
				  {
				  		pesan = "Publish data?";	
						status = "Y";
				  }
				  else
				  {
				  		pesan = "Unpublish data?";	
						status = "T";
				  }
						
				    
				  konfirmasiAksi(pesan, "web/slider_json/publish", anSelectedId+"&reqStatus="+status);

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

    <div class="judul-halaman">  Message BOD</div>
    
    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
    	<div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i> Tambah</a></span>
            <span><a id="btnEdit"><i class="fa fa-check-square fa-lg" aria-hidden="true"></i> Edit</a></span>
            <span><a id="btnDelete"><i class="fa fa-times-rectangle fa-lg" aria-hidden="true"></i> Hapus</a></span>
            <span><a id="btnNotifikasi"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Kirim Notifikasi</a></span>
            <span><a id="btnPublish"><i class="fa fa-share-alt fa-lg" aria-hidden="true"></i> Publish</a></span>
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
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

