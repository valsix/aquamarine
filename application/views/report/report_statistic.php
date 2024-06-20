<base href="<?= base_url(); ?>" />
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get('reqId');
$reqMode = $this->input->get('reqMode');


$this->load->model('StatisticOfferTahun');
$statistic_offer_tahun = new StatisticOfferTahun();
$statistic_offer_tahun->selectByParamsOfferTahun(array("A.TAHUN"=>$reqId)); 

$this->load->model("StatisticDetil");
$statistic_detil = new StatisticDetil();



 $i=1;
 while ($statistic_offer_tahun->nextRow()) {
   $statements =  " AND CAST(A.STATISTIC_ID AS VARCHAR) ='".$i."' AND A.TAHUN = '".$reqId."' ";
?>
<div style="background: #4259c1;border-radius: 2px;padding-bottom: 9px;
margin: 20px 0 20px;">
<h3 style="font-size: 14px;
text-transform: uppercase;
margin-bottom: 0px;
padding-top: 8px;color: #FFFFFF;padding-left: 20px">Statistic Detail <?=$statistic_offer_tahun->getField("DESCRIPTION")?> </h3>
</div>
<table  style="width: 100%;border-collapse: 1px solid black" border="1">
    <tr>
        <th align="left" style="padding-left: 20px"> Description </th>
        <th align="center"> Value </th>
       
    </tr>
    <?  
    $statistic_detil->selectByParamsMonitoringOffer(array(),-1,-1,$statements);
    $total=0;
    while ($statistic_detil->nextRow()) {
         $total += $statistic_detil->getField('VALUE');
        ?>
        <tr>
            <td align="left" style="padding-left: 20px"><?= $statistic_detil->getField('DESCRIPTION') ?></td>
            <td align="center"><?= $statistic_detil->getField('VALUE') ?></td>
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
    <img src="uploads/piechar/<?=$reqId?>/<?=$reqId?>_<?=$i?>.png" style="height: 270px;width: 270px">
</div>
<br>
<?
$i++;
}
?>