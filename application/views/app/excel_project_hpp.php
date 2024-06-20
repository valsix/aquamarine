
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$fileName = 'report_hpp_project.xls';
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Content-Type: application/vnd.ms-excel;");

$reqIds = $this->input->get("reqIds");
$reqId = explode(',', $reqIds);

$this->load->model("ProjectHpp");
$reqId = $this->input->get("reqIds");

$projectCost = new ProjectHpp();
$reqIds = explode(',', $reqId);
$aColumns = array(
 
"REF_NO","OWNER","NAMA","LOA","LOCATION","ESTIMASI_PEKERJAAN","COST_FROM_AMDI","PROFIT"
);
$aColumnsAlias = array(
 
"REF_NO","OWNER","VESSEL_NAME","LOA","LOCATION","DATE","COST_FROM_AMDI","PROFIT"
);
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
if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
  $statement .= " AND A.DATE_PROJECT BETWEEN  TO_CHAR(" . $reqCariPeriodeYearFrom . ", 'yyyy-MM-dd')  AND TO_CHAR(" . $reqCariPeriodeYearTo . ", 'yyyy-MM-dd') ";
}

if (!empty($reqCariNoOrder)) {
  $statement .= " AND UPPER(A.NAMA) LIKE '%" . strtoupper($reqCariNoOrder) . "%' ";
}
$projectCost->selectByParamsMonitoring(array(), -1, -1, $statement);

?>
<h1 style="text-align: center;">  Project HPP Report </h1>
<table style="width: 100%;border-collapse: 1px solid black" border="1">
    <tr>
        <th>No </th>
        <?php
        for ($i = 0; $i < count($aColumnsAlias); $i++) {

            ?>
            <th><?= str_replace('_', ' ', $aColumnsAlias[$i])  ?></th>
            <?php

        };
        ?>
    </tr>
    <?
    $no = 1;
    while ($projectCost->nextRow()) {
    ?>
        <tr>
            <td><?= $no ?> </td>
            <?php
            for ($i = 0; $i < count($aColumns); $i++) {
                 $VALUE = $projectCost->getField($aColumns[$i]);
                
                 if($aColumns[$i]=='DATE_PROJECT'){
                    $MONTH = getFormattedDateEng($projectCost->getField($aColumns[$i]));
                    $MONTH = explode(' ',  $MONTH);
                    $VALUE= $MONTH[1];
                 }else  if($aColumns[$i]=='PROFIT'){
                     $VALUE= currencyToPage2($projectCost->getField($aColumns[$i]));
                 }else  if($aColumns[$i]=='COST_FROM_AMDI'){
                     $VALUE= currencyToPage2($projectCost->getField($aColumns[$i]));
                 }    
               
                ?>
                <td><?= $VALUE ?> </td>
                <?

            };
            ?>
          
           

        </tr>
    <?
        $no++;
    }
    ?>

</table>