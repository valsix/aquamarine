<?
$reqCabangId = $this->input->get("reqCabangId");
$reqGolonganDarah = $this->input->get("reqGolonganDarah");
$reqJenisKelamin = $this->input->get("reqJenisKelamin");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?=base_url();?>" />


    <!-- Bootstrap core CSS -->
    <link href="libraries/bootstrap-3.3.7/docs/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="libraries/bootstrap-3.3.7/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="libraries/bootstrap-3.3.7/docs/examples/sticky-footer-navbar/sticky-footer-navbar.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="libraries/bootstrap-3.3.7/docs/assets/js/ie-emulation-modes-warning.js"></script>
<link rel="stylesheet" href="css/gaya-bootstrap.css" type="text/css">
    <link rel="stylesheet" href="css/gaya-egateway.css" type="text/css">

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
<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="libraries/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="libraries/easyui/globalfunction.js"></script>

<!-- FONT AWESOME -->
<link rel="stylesheet" href="libraries/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

    
</head>

<body class="body-popup">
	
    <div class="container-fluid" style="margin-top:-50px">
        <div class="row row-treegrid">
        	<div class="col-md-12 col-treegrid">
            	<div class="area-konten-atas">
                	<div class="judul-halaman">Filter Anggota</div>
                </div>
				<div class="col-md-12">
                <div class="konten-area">
                <div class="konten-inner">
                <div> 
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="reqNrp" class="control-label col-md-2">Unit Kerja</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input name="reqCabangId" class="easyui-combobox form-control" id="reqCabangId"
                                    data-options="width:'260',editable:false,valueField:'id',textField:'text',url:'combo_json/cabang',
                                        onSelect: function(rec){
                                        }" style="color:#FFFFFF !important" value="<?=$reqCabangId?>" /> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqNrp" class="control-label col-md-2">Jenis Kelamin</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input name="reqJenisKelamin" class="easyui-combobox form-control" id="reqJenisKelamin"
                                    data-options="width:'260',editable:false,valueField:'id',textField:'text',url:'combo_json/jenis_kelamin',
                                        onSelect: function(rec){
                                        }" style="color:#FFFFFF !important" value="<?=$reqJenisKelamin?>" /> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqNrp" class="control-label col-md-2">Gol Darah</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input name="reqGolonganDarah" class="easyui-combobox form-control" id="reqGolonganDarah"
                                    data-options="width:'260',editable:false,valueField:'id',textField:'text',url:'combo_json/golongan_darah',
                                        onSelect: function(rec){
                                        }" style="color:#FFFFFF !important" value="<?=$reqGolonganDarah?>" /> 
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
                </div>
                    <div style="text-align:center;padding:5px">
                     <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Pencarian</a>
                    </div>
                </div>
                </div>
                </div>
                </div>
                </div>
            </div>
        </div>        
    </div>
    
	<script>
		// A $( document ).ready() block.
		$( document ).ready(function() {
		});
		
		function submitForm()
		{
			var reqCabangId = $("#reqCabangId").combobox("getValue");
			var reqGolonganDarah = $("#reqGolonganDarah").combobox("getValue");
			var reqJenisKelamin = $("#reqJenisKelamin").combobox("getValue");	
			
			parent.setFilter(reqCabangId, reqGolonganDarah, reqJenisKelamin);
			parent.closePopup();
			
		}
	
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
