<base href="<?= base_url(); ?>" />

<?php 
include_once("functions/string.func.php");
require_once ('libraries/jpgraph-4.3.4/src/jpgraph.php');
require_once ('libraries/jpgraph-4.3.4/src/jpgraph_pie.php');
$this->load->model("DokumenReport");
$reqId = $this->input->get("reqId");
$reqTahun = $this->input->get("reqTahun");
$reqUrut = $this->input->get("reqUrut");
$reqModel = explode("-", $reqId);


$arrValuess =array("Preparation before working","Conduct of the team during","Knowledge of working","Concerns for safety","Performance of equipement","Performance of personnel","Overall Performance of team","etc");
$arrLabel = array('SURYEVOR_SATISFACTION_SHEET','CLIENT_SATISFACTION_SHEET');

$report = new DokumenReport();
$statement = "  AND TO_CHAR(A.START_DATE,'YYYY')='".$reqTahun."'";
$report->selectByParams(array(),-1,-1,$statement);
// echo $report->query;exit;

$arrColor = array();
$arrValue = array();
$arrDesc = array();
 $arrDataValue = array();
         $arrDataValue2 = array();
$i=0;
 while ( $report->nextRow()) {
 	$reqSurveyors = $report->getField("SURYEVOR");
 	$reqSurveyorsx = json_decode($reqSurveyors,true);
 	$reqClients = $report->getField("CLIENT");
 	$reqClientsx = json_decode($reqClients,true); 
 	for($i=0;$i<count($arrValuess);$i++){
 			for($j=0;$j<5;$j++){
                         if(!empty($reqSurveyorsx['reqSurveyor'.$i.$j])&& $reqUrut==$j){
 							$arrDataValue[$arrValuess[$i]] += 1;
                         }
 						if(!empty($reqClientsx['reqClient'.$i.$j]) && $reqUrut==$j){
                           $arrDataValue2[$arrValuess[$i]] += 1;
                        }
 			}

    }

	$i++;
}
$total =0;
                                 // while ( $master_lokasi->nextRow()) {
for($i=0;$i<count($arrValuess);$i++){
                                    // $project_hpp =  new ProjectHpp();
	if($reqModel[0]=='SURYEVOR_SATISFACTION_SHEET'){    
		$stotal = ifZero($arrDataValue[$arrValuess[$i]]);
		$total = $total+$stotal;
	}else{
		$stotal = ifZero($arrDataValue2[$arrValuess[$i]]);
		$total = $total+$stotal;
	}   
	$arrDesc[$i]=$arrValuess[$i]."\n(%.1f%%)";
	$arrValue[$i]=floatval($stotal);
	$arrColor[$i]=''; 
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

$FILE_DIR = "uploads/piechar/" . $reqTahun . "/";
$file_nama = 'report_'.$reqModel[0]."_".$reqUrut.".png";

makedirs($FILE_DIR);
$name = $FILE_DIR.$file_nama;
unlink($name);
// echo $name;exit;
$graph->Add($p1);
$graph->Stroke($name);

?>