<base href="<?= base_url(); ?>" />
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// $fileName = 'print_cash_report.xls';
$aColumns = array( "TANGGAL", "KETERANGAN",  "DEBET ( Rp. )", "KREDIT ( Rp. )", "BALENCE ( Rp. )", "STATUS", "ID_DETAIL");
$aColumns_alias = array("TANGGAL","KETERANGAN","DEBET","KREDIT","SALDO","CASH_REPORT_ID","CASH_REPORT_DETIL_ID");

$reqId = $this->input->get("reqId");

$this->load->model("Project_cost");
$this->load->model("ProjectHppDetail");

$projectCost = new Project_cost();
$projectCost->selectByParamsNew(array("A.COST_PROJECT_ID" => $reqId));
$projectCost->firstRow();

$reqNoProject       = $projectCost->getField("NO_PROJECT");
$reqVesselName      = $projectCost->getField("VESSEL_NAME");
$reqTypeOfVessel    = $projectCost->getField("TYPE_OF_VESSEL");
$reqTypeOfService   = $projectCost->getField("TYPE_OF_SERVICE");
$reqDateService1    = $projectCost->getField("DATE_SERVICE1");
$reqDateService2    = $projectCost->getField("DATE_SERVICE2");
$reqDestination     = $projectCost->getField("DESTINATION");
$reqCompanyName     = $projectCost->getField("COMPANY_NAME");
$reqContactPerson   = $projectCost->getField("CONTACT_PERSON");
$reqKasbon          = $projectCost->getField("KASBON");
$reqOfferPrice      = $projectCost->getField("OFFER_PRICE");
$reqRealPrice       = $projectCost->getField("REAL_PRICE");
$reqSurveyor        = $projectCost->getField("SURVEYOR");
$reqOperator        = $projectCost->getField("OPERATOR");
$reqKasbonCur       = $projectCost->getField("KASBON_CUR");
$reqOfferCur        = $projectCost->getField("OFFER_CUR");
$reqRealCur         = $projectCost->getField("REAL_CUR");
$reqAddService      = $projectCost->getField("ADD_SERVICE");
$reqServiceOrderId  = $projectCost->getField("SERVICE_ORDER_ID");
$reqHppProjectId  = $projectCost->getField("HPP_PROJECT_ID");

$sekarang = $this->db->query("SELECT TO_CHAR(CURRENT_DATE, 'Day, D Month, YYYY') SEKARANG ")->row()->sekarang;


$this->load->model("CostProjectDetil");
$cost_project_detil = new CostProjectDetil();
$cost_project_detil->selectByParamsMonitoring(array("COST_PROJECT_ID" => $reqId));



$this->load->model("ProjectHpp");
$this->load->model("ProjectHppDetail");
$this->load->model("CostProjectDetil");


$project_hpp = new ProjectHpp();
$project_hpp->selectByParamsMonitoring(array("CAST(A.HPP_PROJECT_ID AS VARCHAR)" => $reqHppProjectId));
$project_hpp->firstRow();
$reqHppProjectId = $project_hpp->getField("HPP_PROJECT_ID");
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
<br>

<div style="text-align: right;"><?=$sekarang?></div>
<table style="width: 100%;font-size: 12px;font-family: Arial;
    border-collapse: collapse;
    border-spacing: 0;font-weight: bold;" border="1">
    <tr>
        <td colspan="7" align="center">PROJECT COST </td>
    </tr>
        <tr>
            <td align="left" style="width: 20%;padding: 5px">No Project </td>
            <td style="width: 1%;padding: 5px" align="center">:</td>
            <td style="width: 27%;padding: 5px;border-right:  none;"><?=$reqNoProject?> </td>
            <td style="width: 4%;padding: 5px;border:none;border-bottom: 1px solid black" > &nbsp;&nbsp;&nbsp; </td>
            <td style="width: 20%;padding: 5px"> Owner / Client</td>
            <td style="width: 1%;padding: 5px" align="center"> :</td>
            <td style="width: 27%;padding: 5px"><?=$reqCompanyName?> </td>

        </tr>
        <tr>
            <td style="padding: 5px"> Vessel's Name</td>
            <td style="padding: 5px" align="center">: </td>
            <td style="border-right:  none;padding: 5px"><?=$reqVesselName?> </td>
            <td style="border:none;border-bottom: 1px solid black;padding: 5px"> &nbsp;&nbsp;&nbsp;</td>
            <td style="padding: 5px">Contact Person </td>
            <td style="padding: 5px" align="center">: </td>
            <td style="padding: 5px"><?=$reqContactPerson?> </td>

        </tr>
        <tr>
            <td style="padding: 5px"> Vessel's Name</td>
            <td style="padding: 5px" align="center">: </td>
            <td style="border-right:  none;padding: 5px"><?=$reqVesselName?> </td>
            <td style="border:none;border-bottom: 1px solid black;padding: 5px"> &nbsp;&nbsp;&nbsp;</td>
            <td style="padding: 5px">Contact Person </td>
            <td style="padding: 5px" align="center">: </td>
            <td style="padding: 5px"><?=$reqContactPerson?> </td>

        </tr>
        <tr>
            <td style="padding: 5px"> Vessel's Type</td>
            <td style="padding: 5px" align="center">: </td>
            <td style="border-right:  none;padding: 5px"><?=$reqTypeOfVessel?> </td>
            <td style="border:none;border-bottom: 1px solid black;padding: 5px"> &nbsp;&nbsp;&nbsp;</td>
            <td style="padding: 5px">Advance Survey </td>
            <td style="padding: 5px" align="center">: </td>
            <td style="padding: 5px"><?=currencyToPage2($reqKasbon)?> </td>

        </tr>
        <tr>
            <td style="padding: 5px"> Date Start</td>
            <td style="padding: 5px" align="center">: </td>
            <td style="border-right:  none;padding: 5px"><?=getFormattedDateMin($reqDateService1)?> </td>
            <td style="border:none;border-bottom: 1px solid black;padding: 5px"> &nbsp;&nbsp;&nbsp;</td>
            <td style="padding: 5px">Real Price </td>
            <td style="padding: 5px" align="center">: </td>
            <td style="padding: 5px"><?=currencyToPage2($reqRealPrice)?> </td>

        </tr>
        <tr>
            <td style="padding: 5px"> Date Finish</td>
            <td style="padding: 5px" align="center">: </td>
            <td style="border-right:  none;padding: 5px"><?=getFormattedDateMin($reqDateService2)?> </td>
            <td style="border:none;border-bottom: 1px solid black;padding: 5px"> &nbsp;&nbsp;&nbsp;</td>
            <td style="padding: 5px">Surveyor </td>
            <td style="padding: 5px" align="center">: </td>
            <td style="padding: 5px"><?=$reqSurveyor?> </td>

        </tr>
        <tr>
            <td style="padding: 5px"> Location</td>
            <td style="padding: 5px" align="center">: </td>
            <td style="border-right:  none;padding: 5px"><?=$reqNoProject?> </td>
            <td style="border:none;border-bottom: 1px solid black;padding: 5px"> &nbsp;&nbsp;&nbsp;</td>
            <td style="padding: 5px">Operator </td>
            <td style="padding: 5px" align="center">: </td>
            <td style="padding: 5px"><?=$reqOperator?> </td>

        </tr>

    </table>
    <br>
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
  
    $project_hpp_detail = new CostProjectDetil();
    $project_hpp_detail->selectByParamsMonitoring(array("A.COST_PROJECT_ID"=>$reqId),-1,-1,''," ORDER BY STRING_TO_ARRAY(CASE WHEN A.CODE IS NULL THEN B.CODE ELSE A.CODE END,'.')::int[] ASC");
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
    // $cost_project_detil = new CostProjectDetil();
    // $cost_project_detil->selectByParamsMonitoring(array("A.PROJECT_HPP_DETAIL_ID"=>$project_hpp_detail->getField("PROJECT_HPP_DETAIL_ID")));
    //  $cost_project_detil->firstRow();
     $cost =  ifZero($project_hpp_detail->getField("COST"));
     $totalRealisasi +=$cost;
    ?>
    <tr>
        <td align="center"><?=$nomer?> </td>
        <!-- <td align="center"><?=$project_hpp_detail->getField("CODE");?> </td> -->
        <td style="padding-left: 5px;margin-left: 20px"><?

 $texts = $project_hpp_detail->getField('DESCRIPTION');
                    $test_results = explode("-", $texts);
                    $text = $texts;
                    if(!empty($test_results[1])){
                        $text = $test_results[1];
                    }
                    echo  $text;

     // echo   $project_hpp_detail->getField('DESCRIPTION');
      ?> </td>
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
         <!-- <th align="right"><?=currencyToPage2($reqRealPrice)?> </th> -->
         <th align="right"><?=$reqCostFromAmdi?> </th>
    </tr>
     <!-- <tr>
        <th colspan="5">AGENT </th>
        <th align="right" style="background-color: #FFFF00"> <?=$reqAgent?> </th>
          <th align="right" style="background-color: #FFFF00"><?=$reqAgent?> </th>
    </tr>
    <tr>
        <th colspan="5">SELL COST TO CLIENT </th>
        <th align="right" style="background-color: #FFFF00"> <?=$reqCostToClient?> </th>
         <th align="right" style="background-color: #FFFF00"><?=$reqCostToClient?> </th>
    </tr> -->
    <tr>
        <?
        $reqProfitRealisasi = $reqRealPrice- $totalRealisasi;
        $reqPrescentageRealisasi=  ($reqProfitRealisasi / $reqRealPrice)*100;
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
