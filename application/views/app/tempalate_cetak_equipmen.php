 <base href="<?=base_url();?>" />
<?
$fileName = 'print_report.xls';
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Content-Type: application/vnd.ms-excel;");
$reqIds          = $this->input->get("reqIds");
$reqId           = explode(',', $reqIds);

$this->load->model("Company");

$reqId           = $this->input->get("reqIds");
$company         = new Company();
$reqIds          = explode(',', $reqId);

$this->load->model("EquipmentList");
$equipment_list = new EquipmentList();

$aColumns = array(
    "NO", "ID", "CATEGORY", "PICTURE", "EQUIPMENT", "ID_EQUIPMENT", "SERIAL_NO", "QTY", "ITEM",
    "INCOMING_DATE", "CONDITION","STORAGE", "REMARKS"
);
$aColumnsAlias = array(
    "NO", "EQUIP_ID", "CATEGORY", "PIC_PATH", "EQUIP_NAME", "BARCODE", "SERIAL_NUMBER", "QUANTITY", "ITEM",
    "INCOMING_DATE", "CONDITION", "STORAGE", "REMARKS"
);

 $statement_privacy =$_SESSION["reqCariSessionEquip"];

$equipment_list->selectByParamsMonitoringEquipmentProdCetakPdf(array(), -1, -1, $statement . $statement_privacy);
$arrDataEquipment = $equipment_list->rowResult;
?>
<H1 style="text-align: center;"> EQUIPMENT LIST </H1>
<table style="width: 100%;border-collapse: 1px solid black" border="1">
    <tr>
        <?
        foreach ($aColumns as $value) {
        ?>
         <th><?=$value?> </th>
        <?
        }
        ?>
       
       
    </tr>
    <?
    $no = 1;
       foreach ($arrDataEquipment as $value) {
         $image = 'uploads/equipment/'.$value[strtolower($aColumnsAlias[3])];
             if( $value[strtolower($aColumnsAlias[3])] == "" || !file_exists($image)){
                $image = 'uploads/no-image.png';
             }
             $imagexx = filesize($image);
             if($imagexx > 1500000){
               $image = 'images/big_images.jpg';
           }

            $reqGambar ='<img src="'.$image.'"  width="12%" height="15%" >';
            $heightz = 'height="150" width="150" ';
    ?>
        <tr>
            <td><?= $no ?> </td>
            <td><?= $value[strtolower($aColumnsAlias[1])] ?> </td>
            <td><?= $value[strtolower($aColumnsAlias[2])] ?> </td>
            <td <?=$heightz;?> align="center"><?= $reqGambar ?> </td>
            <td><?= $value[strtolower($aColumnsAlias[4])] ?> </td>
            <td><?= $value[strtolower($aColumnsAlias[5])] ?> </td>
            <td><?= $value[strtolower($aColumnsAlias[6])] ?> </td>
            <td><?= $value[strtolower($aColumnsAlias[7])] ?> </td>
            <td><?= $value[strtolower($aColumnsAlias[8])] ?> </td>
            <td><?= $value[strtolower($aColumnsAlias[9])] ?> </td>
            <td><?= $value[strtolower($aColumnsAlias[10])] ?> </td>
            <td><?= $value[strtolower($aColumnsAlias[11])] ?> </td>
            <td><?= $value[strtolower($aColumnsAlias[12])] ?> </td>
             <td><?= $value[strtolower($aColumnsAlias[13])] ?> </td>

        </tr>
    <?
        $no++;
    }
    ?>

</table>