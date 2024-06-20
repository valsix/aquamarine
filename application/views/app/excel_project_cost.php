<?
$fileName = 'report_cost_project.xls';
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Content-Type: application/vnd.ms-excel;");

$reqIds = $this->input->get("reqIds");
$reqId = explode(',', $reqIds);

$this->load->model("Project_cost");
$this->load->model("CostProjectDetil");

$reqId = $this->input->get("reqIds");

$projectCost = new Project_cost();
$reqIds = explode(',', $reqId);

// $statement='';
// for($i=0;$i<count($reqIds);$i++){
//     if(!empty($reqIds[$i])){
//         if($i==0){
//              $statement .= " AND A.COMPANY_ID=".$reqIds[$i];
//         }else{
//              $statement .= " OR A.COMPANY_ID=".$reqIds[$i];   
//         }   

//     }

// }
$projectCost->selectByParamsCetakExcel(array(), -1, -1, $statement);

?>
<h1 style="text-align: center;"> Cost Project Report </h1>
<table style="width: 100%;border-collapse: 1px solid black" border="1">
    <tr>
        <th>No </th>
        <th>No Project </th>
        <th>Vessel Name </th>
        <th>Type Of Vessel </th>
        <th>Type Of Service </th>
        
        <th>Location </th>
        <th>Company Name </th>
        <th>Contact Person </th>
        <th>Advance Survey </th>
        <th>Offer Price </th>
        <th>Real Price </th>
        <th>Surveyor </th>
         <th>Over Head </th>
    </tr>
    <?
    $no = 1;
    while ($projectCost->nextRow()) {
        $ids = $projectCost->getField('COST_PROJECT_ID');
        $cost_project_detil = new CostProjectDetil();
        $cost_project_detil->selectByParamsMonitoring(array("A.COST_PROJECT_ID"=>$ids));
        $total = 0;
        while ( $cost_project_detil->nextRow()) {
            $total += ifZero2($cost_project_detil->getField("COST"));
        }
  
    ?>
        <tr>
            <td><?= $no ?> </td>
            <td><?= $projectCost->getField('NO_PROJECT') ?> </td>
            <td><?= $projectCost->getField('VESSEL_NAME') ?> </td>
            <td><?= $projectCost->getField('TYPE_OF_VESSEL') ?> </td>
            <td><?= $projectCost->getField('TYPE_OF_SERVICE') ?> </td>
            
            <td><?= $projectCost->getField('DESTINATION') ?> </td>
            <td><?= $projectCost->getField('COMPANY_NAME') ?> </td>
            <td><?= $projectCost->getField('CONTACT_PERSON') ?> </td>
            <td><?= currencyToPage2($projectCost->getField('KASBON')) ?> </td>
            <td><?= currencyToPage2($projectCost->getField('OFFER_PRICE')) ?> </td>
            <td><?= currencyToPage2($projectCost->getField('REAL_PRICE')) ?> </td>
            <td><?= $projectCost->getField('SURVEYOR') ?> </td>
             <td><?= currencyToPage2($total); ?> </td>

        </tr>
    <?
        $no++;
    }
    ?>

</table>