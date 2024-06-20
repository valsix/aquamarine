<base href="<?= base_url(); ?>" />
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get('reqId');
$reqMode = $this->input->get('reqMode');

$this->load->model("ProjectHpp");
$this->load->model("ProjectHppDetail");
$this->load->model("CostProjectDetil");


$project_hpp = new ProjectHpp();
$project_hpp->selectByParamsMonitoring(array("CAST(A.HPP_PROJECT_ID AS VARCHAR)" => $reqId));
$project_hpp->firstRow();
$reqId = $project_hpp->getField("HPP_PROJECT_ID");
$reqNama= $project_hpp->getField("NAMA");
$reqLoa= $project_hpp->getField("LOA");
$reqLocation= $project_hpp->getField("LOCATION");
$reqRefNo= $project_hpp->getField("REF_NO");
$reqBulanHpp= $project_hpp->getField("BULAN_HPP");
$reqDateProject= $project_hpp->getField("DATE_PROJECT");

$reqJenisPekerjaan= $project_hpp->getField("PEKERJAAN_NAMA");
$reqOwner= $project_hpp->getField("OWNER");
$reqJenisKapal= $project_hpp->getField("JENIS_KAPAL");
$reqFlag= $project_hpp->getField("FLAG");
$reqClass= $project_hpp->getField("CLASS");
$reqEstimasiPekerjaan= $project_hpp->getField("ESTIMASI_PEKERJAAN");
$reqLokasiPekerjaan= $project_hpp->getField("LOKASI_PEKERJAAN");

$reqCostFromAmdi= $project_hpp->getField("COST_FROM_AMDI");
$reqCostFromAmdis= $reqCostFromAmdi;
$reqCostFromAmdi =currencyToPage2($reqCostFromAmdi);
$reqAgent= $project_hpp->getField("AGENT");
$reqAgent =currencyToPage2($reqAgent);
$reqCostToClient= (int)($project_hpp->getField("COST_FROM_AMDI") + $project_hpp->getField("AGENT"));
$reqCostToClients =$reqCostToClient;
$reqCostToClient =currencyToPage2($reqCostToClient);
$reqProfit= $project_hpp->getField("PROFIT");
$reqProfit =currencyToPage2($reqProfit);
$reqPrescentage= $project_hpp->getField("PRESCENTAGE");


?>

<!-- <h1 style="text-align: center;font-size: 16px;font-family:Arial">  <b>HPP PROJECT</b>  </h1> -->


<div style="width: 100%">

    <table style="width: 100%;font-size: 10px;font-family: Arial;
    border-collapse: collapse;
    border-spacing: 0;font-weight: bold;"  >
    <tr>
        <td style="width: 20%"> Vessel Name  </td>
        <td style="width: 2%"> : </td>
        <td ><?=$reqNama?> </td>
        <td > &nbsp;&nbsp;&nbsp; </td>
        <td style="width: 20%"> Ref No </td>
        <td style="width: 2%"> : </td>
         <td ><?=$reqRefNo?> </td>
    </tr>
     <tr>
        <td> LOA</td>
        <td> :</td>
        <td >  <?=$reqLoa?> </td>
        <td > &nbsp;&nbsp;&nbsp; </td>
        <td >Date HPP</td>
        <td > : </td>
         <td > <?=getFormattedDateEng($reqDateProject)?> </td>
    </tr>
         <tr>
         <td> Location</td>
        <td> :</td>
        <td > <?=$reqLocation?> </td>
                
        <!-- <td colspan="3">   : <?=$reqBulanHpp?>  </td> -->
        
         <td colspan="4"> &nbsp;&nbsp;&nbsp; </td>
    </tr>
    </table>
    <br>
  

<table  style="width: 100%;font-size: 12px;font-family: Arial;border:1px solid black;border: 1px inset black;
    border-collapse: collapse;
    border-spacing: 0;" BORDER="1"   > 
    
    <tr>
        <th  width="5%"><b> No </b> </th>
      <!--   <th  width="15%" align="center"> <b>Code </b>  </th> -->
        <th  width="50%" > <b>Description </b>  </th>
        <th  width="5%"> <b>Qty </b> </th>
        <th   width="20%"> <b>Unit Rate </b> </th>
        <th   width="5%"> <b>Days </b> </th>
         <th   width="15%"> <b>Total </b> </th>
          <th   width="15%"> <b>Realisasi </b> </th>
    </tr>
    <?
    $project_hpp_detail = new ProjectHppDetail();
    $project_hpp_detail->selectByParamsMonitoring(array("A.HPP_PROJECT_ID"=>$reqId),-1,-1,'',' ORDER BY A.CODE ASC');
    $no=1;
    $arrKode = array();
    $totali=0;
    $totalRealisasi=0;
    while ($project_hpp_detail->nextRow()) {
        $kodes = $project_hpp_detail->getField("CODE");
        $kode = substr( $kodes, 0,1);
        // echo $kode.'-';
        $boolean = true;
        $nomer ='';
        if (in_array($kode, $arrKode)) {
            $boolean = false;
        }else{
             // $boolean = false;
             $nomer= $no;
             array_push($arrKode, $kode);
        }
       
    ?>
     <?  
    if($boolean){
        if($no !=1){
    ?>
        <tr>
            <td>&nbsp; </td>
            <!-- <td>&nbsp; </td> -->
            <td>&nbsp; </td>
            <td>&nbsp; </td>
            <td>&nbsp; </td>
            <td>&nbsp; </td>
            <td>&nbsp; </td>
             <td>&nbsp; </td>

        </tr>

    <?
        }
        $no++;
    }     

    $totali += $project_hpp_detail->getField("TOTAL");
    $cost_project_detil = new CostProjectDetil();
    $cost_project_detil->selectByParamsMonitoring(array("A.PROJECT_HPP_DETAIL_ID"=>$project_hpp_detail->getField("PROJECT_HPP_DETAIL_ID")));
     $cost_project_detil->firstRow();
     $cost =  ifZero($cost_project_detil->getField("COST"));
     $totalRealisasi +=$cost;
    ?>
    <tr>
        <td align="center"><?=$nomer?> </td>
        <!-- <td align="center"><?=$project_hpp_detail->getField("CODE");?> </td> -->
        <td style="padding-left: 5px;margin-left: 20px"><?=$project_hpp_detail->getField("DESCRIPTION");?> </td>
        <td align="center"><?=$project_hpp_detail->getField("QTY");?> </td>
        <td align="right"><?=currencyToPage2($project_hpp_detail->getField("UNIT_RATE"));?>  </td>
        <td align="center"><?=$project_hpp_detail->getField("DAYS");?>  </td>
        <td align="right"><b><?=currencyToPage2($project_hpp_detail->getField("TOTAL"));?></b> </td>
         <td align="right"><b><?=currencyToPage2($cost);?></b> </td>
      
    </tr>
   
    <?
    }

    // print_r($arrKode);
    ?>
    <tr>
        <th colspan="5"> TOTAL </th>
        <th align="right"> <?=currencyToPage2($totali)?> </th>
        <th align="right"> <?=currencyToPage2($totalRealisasi)?> </th>
    </tr>
    <tr>
        <th colspan="5"> SELL COST FROM AMDI </th>
        <th align="right"> <?=$reqCostFromAmdi?> </th>
         <th align="right"><?=$reqCostFromAmdi?> </th>
    </tr>
     <tr>
        <th colspan="5">AGENT </th>
        <th align="right" style="background-color: #FFFF00"> <?=$reqAgent?> </th>
          <th align="right" style="background-color: #FFFF00"><?=$reqAgent?> </th>
    </tr>
    <tr>
        <th colspan="5">SELL COST TO CLIENT </th>
        <th align="right" style="background-color: #FFFF00"> <?=$reqCostToClient?> </th>
         <th align="right" style="background-color: #FFFF00"><?=$reqCostToClient?> </th>
    </tr>
    <tr>
        <?
        $reqProfitRealisasi = $reqCostFromAmdis- $totalRealisasi;
        $reqPrescentageRealisasi=  ($reqProfitRealisasi / $reqCostFromAmdis)*100;
         $reqPrescentageRealisasi = round($reqPrescentageRealisasi,2);
        ?>
        <th colspan="5">PROFIT </th>
        <th align="right" style="background-color: #C4BD97"> <?=$reqProfit?> </th>
         <th align="right" style="background-color: #C4BD97"> <?=currencyToPage2($reqProfitRealisasi)?> </th>
        
    </tr>
    <tr>
        <th colspan="5">PRESCENTAGE </th>
        <th align="right"> <?=$reqPrescentage?> % </th>
         <th align="right"> <?=$reqPrescentageRealisasi?> %  </th>
       
    </tr>
    
</table>
<br>

<table style="width: 100%;font-size: 10px;font-family: Arial;
    border-collapse: collapse;
    border-spacing: 0;">
    
    <tr>
        <td style="width: 200px">JENIS PEKERJAAN </td>
        <td style="width: 10px">:</td>
        <td ><?=$reqJenisPekerjaan?></td>
    </tr>
    <tr>
        <td >OWNER </td>
        <td >:</td>
        <td ><?=$reqOwner?></td>
    </tr>
    <tr>
        <td >JENIS KAPAL </td>
        <td >:</td>
        <td ><?=$reqClass?></td>
    </tr>
    <tr>
        <td >FLAG </td>
        <td >:</td>
        <td ><?=$reqFlag?></td>
    </tr>
    <tr>
        <td >CLASS </td>
        <td >:</td>
        <td ><?=$reqJenisKapal?></td>
    </tr>
    <tr>
        <td >ESTIMASI PEKERJAAN </td>
        <td >:</td>
        <td ><?=$reqEstimasiPekerjaan?></td>
    </tr>
     <tr>
        <td >LOKASI PEKERJAAN </td>
        <td >:</td>
        <td ><?=$reqLokasiPekerjaan?></td>
    </tr>
</table>
<br>

<table style="width: 100%;font-size: 12px;font-family: Arial;border:1px solid black;border: 1px inset black;
    border-collapse: collapse;text-align: center;font-weight: bold;
    border-spacing: 0;" BORDER="1" >
        <tr>
            <td style="width: 100px">For Quotation </td>
             <td style="width: 100px">For Execution </td>
              <td style="width: 100px">For Review </td>
        </tr>
        <tr>
            <td style="height: 100px"> &nbsp;&nbsp;&nbsp; </td>
             <td> &nbsp;&nbsp;&nbsp; </td>
              <td> &nbsp;&nbsp;&nbsp; </td>
        </tr>
    </table>
</div>
