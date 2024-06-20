<?
$reqId = $this->input->get("reqId");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?=base_url();?>" />

<link rel="stylesheet" href="css/gaya.css" type="text/css">
<link rel="stylesheet" href="css/gaya-bootstrap.css" type="text/css">

<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="libraries/bootstrap/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="libraries/font-awesome/4.5.0/css/font-awesome.css">

<script src="js/jquery-1.11.1.js" type="text/javascript" charset="utf-8"></script> 

    <style>
	.col-md-12{
		padding-left:0px;
		padding-right:0px;
	}
	</style>

<!-- EASYUI -->
    <link rel="stylesheet" type="text/css" href="libraries/easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="libraries/easyui/themes/icon.css">
    <script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="libraries/easyui/globalfunction.js"></script>
    <script type="text/javascript" src="libraries/easyui/kalender-easyui.js"></script>
    <script type="text/javascript" src="libraries/functions/string.func.js?n=1"></script>
    <script type="text/javascript" src="libraries/functions/command.js"></script>


<!-- FONT AWESOME -->
<link rel="stylesheet" href="libraries/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

    
</head>

<body class="body-popup">
	
    <div class="container-fluid container-treegrid">
    	
        <div class="row row-treegrid">
        	<div class="col-md-12 col-treegrid">
            	<div class="area-konten-atas">
                	<div class="judul-halaman">Certificate</div>
                    
                    <div class="area-menu-aksi">    
                        Pencarian : <input type="text" name="reqPencarian" id="reqPencarian">
                    </div>
                    
                </div>
                
				<div id="tableContainer" class="tableContainer tableContainer-treegrid">
                	<table id="treeSatker" class="easyui-treegrid" style="width:700px;height:300px"
                            data-options="
                                url: 'web/certificate_json/combo/',
                                method: 'get',
                                idField: 'id',
                                treeField: 'text'
                            ">
                        <thead>
                            <tr>
                                <th data-options="field:'NAME'" > Name</th>
                                 <th data-options="field:'ISSUED_DATE'" >Issue Date</th>
                                 <th data-options="field:'EXPIRED_DATE'" >Expired Date</th>
                                
                               
                            </tr>
                        </thead>
                    </table>
                </div>
                
            </div>
        </div>        
    </div>
    
	<script>
 $(document).ready( function () {
        $('input[name=reqPencarian]').keyup(function() {
            var value = this.value;
            $("html, body").animate({ scrollTop: 0 });
    
            var urlApp = 'direktorat_json/combo/?reqPencarian='+ value;
            $('#treeSatker').treegrid(
            {
                url: urlApp
            }); 
        });
        
        $('#treeSatker').treegrid({
              onDblClickRow: function(param){
                 top.tambahDirektorat(param.id, param.CERTIFICATE_ID, param.NAME, param.DESCRIPTION, param.ISSUED_DATE, param.EXPIRED_DATE, param.SURVEYOR);
				
                 $('#treeSatker').treegrid('deleteRow', param.id);
              }
        });
    });
        
        $("#dnd-example tr").click(function(){
           $(this).addClass('selected').siblings().removeClass('selected');
           var id = $(this).find('td:first').attr('id');
           var title = $(this).find('td:first').attr('title');

            
        });
    
    
	    
    
	</script>
    
    <script>
		// Mendapatkan tinggi .area-konten-atas
		var divTinggi = $(".area-konten-atas").height();
		//alert(divTinggi);
		
		// Menentukan tinggi tableContainer
		$('#tableContainer').css({ 'height': 'calc(100% - ' + divTinggi+ 'px)' });
	</script>

</body>
</html>
