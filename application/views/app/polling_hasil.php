<?
$this->load->model("Polling");
$this->load->model("PollingDetil");
$reqId = $this->input->get("reqId");

$polling = new Polling();
$polling->selectByParamsEntri(array("A.POLLING_ID" => $reqId));
$polling->firstRow();

$polling_detil = new Polling();
$polling_detil->selectByParamsDetil(array("A.POLLING_ID" => $reqId));
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


    <style>
	.col-md-12{
		padding-left:0px;
		padding-right:0px;
	}
	</style>

<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>  
<script type="text/javascript" src="js/jquery.canvasjs.min.js"></script>
<script type="text/javascript">
window.onload = function() {

var options = {
	title: {
		text: "<?=$polling->getField("NAMA")?>"
	},
	data: [{
			type: "pie",
			startAngle: 45,
			showInLegend: "true",
			legendText: "{label}",
			indexLabel: "{label} ({y})",
			yValueFormatString:"#,##0.#"%"",
			dataPoints: [
			<?
			$i=0;
			while($polling_detil->nextRow())
			{
				if($i == 0)
				{
			?>
				{ label: "<?=$polling_detil->getField("NAMA_PILIHAN")?>", y: <?=(int)$polling_detil->getField("JUMLAH")?> }
			<?
				}
				else
				{
				?>
				,{ label: "<?=$polling_detil->getField("NAMA_PILIHAN")?>", y: <?=(int)$polling_detil->getField("JUMLAH")?> }
				<?
				}
				$i++;
			}
			?>
			]
	}]
};
$("#chartContainer").CanvasJSChart(options);

}
</script>
<!-- FONT AWESOME -->
<link rel="stylesheet" href="libraries/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

    
</head>

<body class="body-popup">
	
    <div class="container-fluid container-treegrid">
    	
        <div class="row row-treegrid">
        	<div class="col-md-12 col-treegrid">
            	<div class="area-konten-atas">
                	<div class="judul-halaman">Hasil Polling</div>
                </div>
                
                
            </div>
        </div>        
    </div>
    <div id="tableContainer" class="tableContainer tableContainer-treegrid">
        <div id="chartContainer" style="height: 100%; width: 100%;"></div>
    </div>
</body>
</html>
