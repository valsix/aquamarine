<?
$fileName = 'report_cost_project.xls';
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Content-Type: application/vnd.ms-excel;");

$reqIds = $this->input->get("reqIds");
$reqId = explode(',', $reqIds);

$this->load->model("Invoice");
$reqId = $this->input->get("reqIds");

$invoice = new Invoice();
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
$invoice->selectByParamsCetakExcel(array(), -1, -1, $statement);

?>
<H1 style="text-align: center;"> Invoice Report </H1>
<table style="width: 100%;border-collapse: 1px solid black" border="1">
    <tr>
        <th>No </th>
        <th>Invoice Number </th>
        <th>Company Name </th>
        <th>Vessel Name </th>
        <th>Invoice Date </th>
        <th>PPN </th>
        <th>Status </th>
        <th>Total Amount </th>
    </tr>
    <?
    $no = 1;
    while ($invoice->nextRow()) {
    ?>
        <tr>
            <td><?= $no ?> </td>
            <td><?= $invoice->getField('INVOICE_NUMBER') ?> </td>
            <td><?= $invoice->getField('COMPANY_NAME') ?> </td>
            <td><?= $invoice->getField('VESSEL_NAME') ?> </td>
            <td><?= $invoice->getField('INVOICE_DATE') ?> </td>
            <td><?= $invoice->getField('PPN') ?> </td>
            <td><?= $invoice->getField('STATUS') ?> </td>
            <td><?= $invoice->getField('TOTAL_AMOUNT') ?> </td>
        </tr>
    <?
        $no++;
    }
    ?>

</table>