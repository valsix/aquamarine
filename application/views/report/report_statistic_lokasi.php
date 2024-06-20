<base href="<?= base_url(); ?>" />
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get('reqId');
$reqMode = $this->input->get('reqMode');

$this->load->model(ProjectHpp);
$this->load->model('MasterLokasi'); 
$this->load->model('StatisticOfferTahun'); 

$master_lokasi = new MasterLokasi();
// $statement = "  AND EXISTS (SELECT 1 FROM PROJECT_HPP X WHERE X.MASTER_LOKASI_ID = A.MASTER_LOKASI_ID 
// AND  TO_CHAR( X.DATE_PROJECT,'YYYY')='".$reqId."' AND X.MASTER_LOKASI_ID IS NOT NULL 
//  AND EXISTS (SELECT 1 FROM OFFER CC WHERE CC.HPP_PROJECT_ID = X.HPP_PROJECT_ID  AND CC.STATUS='1')

// )";
 $statements = "  AND EXISTS (SELECT 1 FROM PROJECT_HPP X WHERE X.MASTER_LOKASI_ID = A.MASTER_LOKASI_ID 
     AND  TO_CHAR( X.DATE_PROJECT,'YYYY')='".$reqId."' 

      )";
$master_lokasi->selectByParamsMonitoring(array(),-1,-1); 


$statement =  " AND A.ID ='LOKASI-".$reqId."'";
$statistic_offer_tahun = new StatisticOfferTahun();
 $statistic_offer_tahun->selectByParamsMonitoring(array(),-1,-1,$statement);
$statistic_offer_tahun->firstRow();
$reqId = $statistic_offer_tahun->getField("ID");
$reqDescription = $statistic_offer_tahun->getField("KETERANGAN");

?>
<div style="background: #4259c1;border-radius: 2px;padding-bottom: 9px;
margin: 20px 0 20px;">
<h3 style="font-size: 14px;
text-transform: uppercase;
margin-bottom: 0px;
padding-top: 8px;color: #FFFFFF;padding-left: 20px">Statistic Detail <?=$reqDescription ?> </h3>
</div>
<table  style="width: 100%;border-collapse: 1px solid black" border="1">
    <tr>
        <th align="left" style="padding-left: 20px"> Description </th>
        <th align="center"> Value </th>
       
    </tr>
    <?  
    
    $total=0;
    while ($master_lokasi->nextRow()) {
       $project_hpp = new ProjectHpp();
       $totalx =$project_hpp->getCountByParamsMonitoring(array("A.MASTER_LOKASI_ID"=>$master_lokasi->getField("MASTER_LOKASI_ID")),$statements);
        $total =  $totalx+ $total;
        ?>
        <tr>
            <td align="left" style="padding-left: 20px"><?= $master_lokasi->getField('NAMA') ?></td>
            <td align="center"><?= $totalx ?></td>
        </tr>

        <?
    }
    ?>
    <tr>
            <td align="left" style="padding-left: 20px"> <b>Grand Total</b></td>
            <td align="center"><?= $total ?></td>
        </tr>

</table>
<div style="text-align: center;">
    <?
    $lokasi = explode('-', $reqId);
    ?>
    <img src="uploads/piechar/<?=$lokasi[1]?>/<?='lokasi_'.$lokasi[1]?>.png" style="height: 270px;width: 270px">
</div>
<br>
