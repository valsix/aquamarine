<base href="<?= base_url(); ?>" />

<?php 
include_once("functions/string.func.php");
require_once ('libraries/jpgraph-4.3.4/src/jpgraph.php');
require_once ('libraries/jpgraph-4.3.4/src/jpgraph_pie.php');
 
$reqId = $this->input->get("reqId");
$reqTahun = $this->input->get("reqTahun");

$this->load->model('Offer');
$this->load->model('MasterReason');    
$master_lokasi = new MasterReason();
$statement = "  AND EXISTS (SELECT 1 FROM OFFER X WHERE X.MASTER_REASON_ID = A.MASTER_REASON_ID 
AND  TO_CHAR( X.DATE_OF_SERVICE,'YYYY')='".$reqId."' AND X.MASTER_REASON_ID IS NOT NULL )";
$master_lokasi->selectByParamsMonitoring(array(),-1,-1,$statement); 
// echo ' asdsaasdas'.$master_lokasi->query;exit;

// $statement =  " AND CAST(A.STATISTIC_ID AS VARCHAR) ='".$reqId."' AND A.TAHUN = '".$reqTahun."' ";
// $statistic_detil->selectByParamsMonitoringOffer(array(),-1,-1,$statement);
$arrColor = array();
$arrValue = array();
$arrDesc = array();

$i=0;
while ($master_lokasi->nextRow()) {
	$project_hpp = new Offer();
	$total =$project_hpp->getCountByParams(array("A.MASTER_REASON_ID"=>$master_lokasi->getField("MASTER_REASON_ID")));
	$arrDesc[$i]=$master_lokasi->getField("NAMA")."\n(%.1f%%)";
	$arrValue[$i]=floatval($total);
	$arrColor[$i]=$master_lokasi->getField("COLOR");
	$i++;
}
       

 
// Create the Pie Graph.
$graph = new PieGraph(300,300);
$graph->SetShadow();
 
// Set A title for the plot
// $graph->title->Set('String labels with values');
// $graph->title->SetFont(FF_VERDANA,FS_BOLD,12);
// $graph->title->SetColor('black');
 
// Create pie plot
$p1 = new PiePlot($arrValue);
$p1->SetCenter(0.5,0.5);
$p1->SetSize(0.3);
 
// Setup the labels to be displayed
$p1->SetLabels($arrDesc);
 
// This method adjust the position of the labels. This is given as fractions
// of the radius of the Pie. A value < 1 will put the center of the label
// inside the Pie and a value >= 1 will pout the center of the label outside the
// Pie. By default the label is positioned at 0.5, in the middle of each slice.
$p1->SetLabelPos(1);
 $p1->SetSliceColors($arrColor);
// Setup the label formats and what value we want to be shown (The absolute)
// or the percentage.
$p1->SetLabelType(PIE_VALUE_PER);
$p1->value->Show();
$p1->value->SetFont(FF_ARIAL,FS_NORMAL,9);
$p1->value->SetColor('darkgray');

$FILE_DIR = "uploads/piechar/" . $reqId . "/";
$file_nama = 'reason_'.$reqId.".png";

makedirs($FILE_DIR);
$name = $FILE_DIR.$file_nama;
unlink($name);
// echo $name;exit;
$graph->Add($p1);
$graph->Stroke($name);

?>