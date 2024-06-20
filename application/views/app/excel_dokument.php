<?
$fileName = 'print_report.xls';
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Content-Type: application/vnd.ms-excel;");
// $reqIds          = $this->input->get("reqIds");
// $reqId           = explode(',', $reqIds);
$this->load->model("DokumenMarketing");
// $reqId           = $this->input->get("reqIds");
$dokumen_marketing = new DokumenMarketing();
// $reqIds          = explode(',', $reqId);

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
$statement_privacy .= " ";
$reqCariFind = $this->input->get('reqCariFind');
$reqCariFindMenthod = $this->input->get('reqCariFindMenthod');

if (!empty($reqCariFind)) {
    $statement_privacy .= " AND A.COMPANY_NAME LIKE '%" . $reqCariFind . "%' ";
}


$aColumns = array("COMPANY_NAME", "VESSEL_NAME", "DESCRIPTION", "DATE", "LAST_REVISI", "TYPE_OF_SERVICE", "LOCATION", "DATE_OPERATION", "CLASS");


$dokumen_marketing->selectByParamsCetakExcel(array(), -1, -1, $statement_privacy);

?>
<H1 style="text-align: center;"> DOCUMENT </H1>
<table style="width: 100%;border-collapse: 1px solid black" border="1">
    <tr>
        <th>No </th>
        <?php
        for ($i = 1; $i < count($aColumns); $i++) {
        ?>
            <th><?= str_replace('_', ' ', $aColumns[$i])  ?></th>
        <?php

        };
        ?>
    </tr>
    <?
    $no = 1;
    while ($dokumen_marketing->nextRow()) {
    ?>
        <tr>
            <td><?= $no ?> </td>

            <?php
            for ($i = 1; $i < count($aColumns); $i++) {
            ?>
                <td><?= $dokumen_marketing->getField($aColumns[$i]) ?> </td>
            <?php

            };
            ?>

        </tr>
    <?
        $no++;
    }
    ?>

</table>