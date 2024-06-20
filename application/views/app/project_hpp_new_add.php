<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$aColumns =array("PROJECT_HPP_DETAIL_ID","HPP_PROJECT_ID","NO","CODE","DESCRIPTION","QTY","UNIT_RATE","DAYS","TOTALS","TOTAL","AKSI");

$this->load->model("ProjectHppNew");
$this->load->model("ProjectHppNewD");
$project_hpp_new = new ProjectHppNew();




$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
    $reqAgent=0;
    $reqCostFromAmdi=0;
    $reqCostToClient=0;
} else {
    $reqMode = "ubah";
    $project_hpp_new->selectByParamsMonitoring(array("CAST(A.PROJECT_HPP_NEW_ID AS VARCHAR)" => $reqId));
    $project_hpp_new->firstRow();
    $reqId= $project_hpp_new->getField("PROJECT_HPP_NEW_ID");
    $reqHppProjectNo= $project_hpp_new->getField("NOMER");
    $reqNama= $project_hpp_new->getField("NAMA");
    $reqCode= $project_hpp_new->getField("CODE");
    $reqLocation= $project_hpp_new->getField("LOKASI");
    $reqBulanHpp= $project_hpp_new->getField("HPP_DATE");
    $reqDateProject= $project_hpp_new->getField("TANGGAL");
    $reqCompanyId= $project_hpp_new->getField("COMPANY_ID");
    $reqEstimasiPekerjaan= $project_hpp_new->getField("ESTIMASI");
    $reqForApproved= $project_hpp_new->getField("APPROVED");
    $reqNamaProject =  $project_hpp_new->getField("NAMA_PROJECT2");
    $reqNamaCompany = $project_hpp_new->getField("NAMA_COMPANY");
    $reqNamaProjectHpp = $project_hpp_new->getField("NAMA_PROJECT");
    $reqNoPoContract = $project_hpp_new->getField("NO_PO_CONTRACT");

    $grandTotalPengeluaranBualanan = $project_hpp_new->getField("KELUAR_BULANAN");
    $grandTotalPengeluaranHarian = $project_hpp_new->getField("KELUAR_HARIAN");
    $grandTotalPemasukanBulanan = $project_hpp_new->getField("PEMASUKAN_BULANAN");
    $grandTotalPemasukanHarian = $project_hpp_new->getField("PEMASUKAN_HARIAN");
    $grandTotalProfit = $project_hpp_new->getField("PROFIT");
    $grandTotalPengeluaranAbcd = $project_hpp_new->getField("PENGELUARANABCD");
    $grandTotalPemasukanAbcd = $project_hpp_new->getField("PENGELUARANEF");
    $grandTotalPengeluaranEf = $project_hpp_new->getField("PEMASUKANABCD");
    $grandTotalPemasukanEf = $project_hpp_new->getField("PEMASUKANEF");

}

?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<script type="text/javascript" language="javascript" class="init">
    var oTable;
    var total;
    var reqIds;
    var pc = parseFloat('<?= $reqRealPrice ?>');
    

</script>

<!-- <script type="text/javascript">
    $(document).ready(function() {
       oTable.on( 'order.dt search.dt', function () {
        oTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
   } );
</script> -->


<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/project_hpp_new">Project Hpp </a> &rsaquo; Form Hpp Project 

        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        
        <?
        if(!empty($reqId)){
        ?>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="cetakPdf()"><i class="fa fa-file-pdf-o "> </i><span> Print</span> </a>
     <!--    
         <a id="btnExcel" class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" ><i class="fa fa-file-excel-o "> </i><span> Download Excel</span> </a> -->
         <?
        }
         ?>
    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Project Hpp Information
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>
                        </h3>
                        <br>

                    </div>



             

                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">HPP Project No.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                
                                       <input type="text" id="reqHppProjectNo" placeholder="Project Nomer" class="easyui-textbox textbox form-control" name="reqHppProjectNo" value="<?= $reqHppProjectNo ?>" style=" width:100%" />
                                  
                                </div>
                            </div>
                        </div>
                         <label for="reqNama" class="control-label col-md-2">No PO / No . Contract</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                
                                       <input type="text" id="reqNoPoContract" placeholder="Project Nomer" class="easyui-textbox textbox form-control" name="reqNoPoContract" value="<?= $reqNoPoContract ?>" style=" width:100%" />
                                  
                                </div>
                            </div>
                        </div>
                      
                        
                    </div>
                    

                    <div class="form-group">
                        <label for="reqLoa" class="control-label col-md-2">Code</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqCode" id="reqCode" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_project_code',onSelect: function(rec){
                                                  reloadComboJenus(rec.id);
                                           }" value="<?=$reqCode?>" />
                                </div>
                            </div>
                        </div>
                       
                        
                    </div>
                      <div class="form-group">
                      <label for="reqLocation" class="control-label col-md-2"> Project</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNamaProject" placeholder="Project"  class="easyui-textbox textbox form-control" name="reqNamaProject" value="<?= $reqNamaProject ?>" style=" width:100%"  disabled readonly />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqDateProject" class="control-label col-md-2">Date HPP</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" required id="reqDateProject" placeholder="Date Hpp"  class="easyui-datebox textbox form-control" name="reqDateProject" value="<?= $reqDateProject ?>" style=" width:200px" />
                                </div>
                            </div>
                        </div>
                        <label for="reqDateService2" class="control-label col-md-2">Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input class="easyui-combobox form-control" placeholder="Date" style="width:190px" id="reqBulanHpp" name="reqBulanHpp" data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/ComboBulan'" value="<?= $reqBulanHpp ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="reqDateProject" class="control-label col-md-2">Estimated Work Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                       <input class="easyui-combobox form-control" placeholder="Estimasi Pekerjaan" style="width:190px" id="reqEstimasiPekerjaan" name="reqEstimasiPekerjaan" data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/ComboBulan'" value="<?= $reqEstimasiPekerjaan ?>" />
                                     <!-- <input type="text" id="reqEstimasiPekerjaan" placeholder="Estimasi Pekerjaan"  class="easyui-textbox textbox form-control" name="reqEstimasiPekerjaan" value="<?= $reqEstimasiPekerjaan ?>" style=" width:100%" /> -->
                                </div>
                            </div>
                        </div>
                         <label for="reqLocation" class="control-label col-md-2">Location</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLocation" placeholder="Lokasi"  class="easyui-textbox textbox form-control" name="reqLocation" value="<?= $reqLocation ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label for="reqJenisPekerjaan" class="control-label col-md-2">For Aproved </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                       <input type="text" id="reqForApproved " placeholder="For Aproved"  class="easyui-textbox textbox form-control" name="reqForApproved" value="<?= $reqForApproved ?>" style=" width:80%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Project Relocation</h3>
                    </div>
                   

                    
                      
                      <?
                      $arrValLink =array("ID"=>$reqCompanyId,"MODE"=>'',"OPEN"=>"no",'MODUL'=>'COSTUMER');
                      $this->load->view("app/company_form",$arrValLink);
                      ?>
                      
                    
                     <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Mobilisasi</h3>
                    </div>
                    <div class="table-responsive" > 
                    <table class=" table-striped " >
                      
                            <tr>
                                <th rowspan="2" width="30" > # </th>
                                 <th rowspan="2" width="200"> Deskripsi </th>
                                  <th colspan="6"> Pengeluaran  </th>
                                   <th colspan="6"> PEMASUKAN  </th>
                            </tr>
                            <tr>
                                <th width="70">QTY   </th>
                                 <th  width="70" >DAYS    </th>
                                  <th >STATUS    </th>
                                  <th >HARGA SATUAN     </th>
                                  <th  >TOTAL HARIAN     </th>
                                  <th  >TOTAL BULANAN   </th>
                                  <th width="70" >QTY   </th>
                                 <th width="70">DAYS    </th>
                                  <th >STATUS    </th>
                                  <th  >HARGA HARIAN      </th>
                                  <th  >HARGA JUAL BULANAN     </th>
                            </tr>
                       
                       
                            <?
                            $query = $this->db->query('select template_hpp_id,code,keterangan,part from template_hpp where 1=1 order by template_hpp_id asc ');
                            $arrData = $query->result_array();

                            $projecthppnewd = new ProjectHppNewD();
                            $projecthppnewd->selectByParamsMonitoring(array('A.PROJECT_HPP_NEW_ID::VARCHAR'=>$reqId),-1,-1,'',' ORDER BY URUT ASC');
                            $arrDataD =  $projecthppnewd->rowResult;
                            $totatNew = $projecthppnewd->rowCount;
                            if($totatNew > 0){
                                 $arrData = $arrDataD;
                            }

                            $arrDataColomn = array_column($arrData, 'code');
                            $arrDataColomn = array_unique($arrDataColomn);
                            $arrKeterangan = array('A'=>'VESSEL','B'=>'EQUIPMENT & MATERIAL ','C'=>'PERSONNEL','D'=>'PMT','E'=>'MOB','F'=>'SUPPORT ');
                            $arrCodeSub = array('C1'=>'Working','C2'=>'Stanby');
                            sort( $arrDataColomn);
                            $arrrCombo = array('','Dayrate','Ton/Day','Man Hour','Lumpsump');
                            foreach ($arrDataColomn as $value) {
                                 $no=1;
                                $filterRow = multi_array_search($arrData,array('code'=>$value));
                                $arrDataColomnPart = array_column($filterRow, 'part');
                                $arrDataColomnPart = array_unique($arrDataColomnPart);
                                 $arrDataColomnPart = array_filter($arrDataColomnPart);
                                  $tampil='YA';
                                if(count($arrDataColomnPart)>0){
                                    $filterRow = array();
                                    $tampil='TIDAK';

                                }
                            ?>
                            <tr style="background: #BFBFBF">
                                <td style="padding: 5px">  <?=$value?> </td>
                                 <td colspan="12" style="padding: 5px">  <?=$arrKeterangan[$value]?></td>

                            </tr>
                            <?   
                             foreach ($filterRow as  $val) {
                                $randomId =rand();
                                $unixKode = $val['code'].$val['part'];
                                $colorKeluar ='#FFFF70'; $colorMasuk ='#70D59B';
                                if ($no % 2 == 0){
                                    $colorKeluar =$colorMasuk='#FFFFFF'; 
                                }
                            ?>

                            <tr>
                                    <td valign="top" align="center"><?=$no;$no++?>. 
                                    <input type="hidden" name="reqHppDetailId[]" value="<?=$val['project_hpp_new_d_id']?>">
                                      <input type="hidden" name="reqHppUrut[]" value="<?=$val['template_hpp_id']?>">
                                       <input type="hidden" id='reqCodeIk<?=$randomId?>' class='reqKodeCx' name="reqHppCode[]" value="<?=$val['code']?>">
                                        <input type="hidden" name="reqHppPart[]" value="<?=$val['part']?>">
                                           <input type="hidden" id='kodeUnik<?=$randomId?>'  value="<?=$unixKode?>">
                                          <input type="hidden" name="reqHppDetailKeterangan[]" value="<?=$val['keterangan']?>">
                                     </td>
                                    <td valign="top" align="left" style="padding: 5px"> <?=$val['keterangan']?> </td>
                                    <td valign="top"> <input type="text" id='reqQtyK<?=$randomId?>' name='reqQtyK[]' onkeypress='validate(event)' class="easyui-textbox textbox form-control cqty"  value="<?=$val['k_qty']?>" style="background:<?=$colorKeluar?>" /> </td>
                                    <td valign="top"> <input type="text"  id='reqDayK<?=$randomId?>' name='reqDayK[]' onkeypress='validate(event)' class="easyui-textbox textbox form-control cday"  value="<?=$val['k_day']?>" style="background:<?=$colorKeluar?>" /> </td>
                                    <td valign="top"> 
                                        <select class="form-control" name='reqStatusK[]' style="background:<?=$colorKeluar?>" >
                                            <?
                                            foreach ($arrrCombo as  $valCombo) {
                                                $select = '';
                                                if($valCombo == $val['k_status']){
                                                      $select = 'selected';
                                                }
                                            ?>
                                            <option value="<?=$valCombo?>"  <?=$select?>><?=$valCombo?></option>
                                            <?   
                                            }
                                            ?>
                                        </select>
                                     </td>
                                    <td valign="top"> <input type="text"  onkeypress='validate(event)' id='reqHargaSatuanK<?=$randomId?>' name='reqHargaSatuanK[]' class="easyui-textbox textbox form-control numberWithCommas csatauan"  value="<?=currencyToPage2($val['k_harga'])?>" style="background:<?=$colorKeluar?>" /> </td>
                                    <td valign="top"> <input type="text" onkeypress='validate(event)' id='reqTotalHarianK<?=$randomId?>' name='reqTotalHarianK[]' class="easyui-textbox textbox form-control numberWithCommas ctotalharian harian<?=$unixKode?>"  value="<?=currencyToPage2($val['k_total'])?>" style="background:<?=$colorKeluar?>" /> </td>
                                    <td valign="top"> <input type="text" onkeypress='validate(event)' id='reqTotalBulananK<?=$randomId?>' name='reqTotalBulananK[]' class="easyui-textbox textbox form-control numberWithCommas ctotalbulanan bulanan<?=$unixKode?> codebulanan<?=$val['code']?>" style="background:<?=$colorKeluar?>"  value="<?=currencyToPage2($val['k_bulanan'])?>" /> </td>
                                    <td valign="top">  <input type="text" onkeypress='validate(event)' id='reqQtyM<?=$randomId?>'  name='reqQtyM[]'  class="easyui-textbox textbox form-control mQty "  value="<?=$val['p_qty']?>" style="background:<?=$colorMasuk?>"/> </td> 
                                    <td valign="top"> <input type="text mDay"  onkeypress='validate(event)'  id='reqDayM<?=$randomId?>' name='reqDayM[]'  class="easyui-textbox textbox form-control"  value="<?=$val['p_day']?>" style="background:<?=$colorMasuk?>" /> </td>
                                    <td valign="top"> 
                                       <select class="form-control" name='reqStatusM[]' style="background:<?=$colorMasuk?>" >
                                        <?
                                        foreach ($arrrCombo as  $valCombo) {
                                               $select = '';
                                                if($valCombo == $val['p_status']){
                                                      $select = 'selected';
                                                }
                                            ?>
                                            <option value="<?=$valCombo?>" <?=$select?>><?=$valCombo?></option>
                                            <?   
                                        }
                                        ?>
                                    </select>

                                     </td>
                                    <td valign="top"> <input type="text" onkeypress='validate(event)'  id='reqTotalHarianM<?=$randomId?>' name='reqTotalHarianM[]' class="easyui-textbox textbox form-control numberWithCommas mTotal mharian<?=$unixKode?>"  value="<?=currencyToPage2($val['p_harga'])?>" style="background:<?=$colorMasuk?>" /> </td>
                                    <td valign="top"> <input type="text" onkeypress='validate(event)'  id='reqTotalBulananM<?=$randomId?>' name='reqTotalBulananM[]' class="easyui-textbox textbox form-control numberWithCommas mTotalBulanan mbulanan<?=$unixKode?> mcodebulanan<?=$val['code']?>" style="background:<?=$colorMasuk?>" value="<?=currencyToPage2($val['p_bulanan'])?>" /> </td>

                            </tr>
                           
                            <?
                            }   
                            ?>
                            <?
                            if($tampil=='YA'){
                                $totalHarian = array_column($filterRow, 'k_total');
                                $totalHarian = array_sum($totalHarian);
                                 $totalBulanan = array_column($filterRow, 'k_bulanan');
                                $totalBulanan = array_sum($totalBulanan);
                                $totalPHarga = array_column($filterRow, 'p_harga');
                                $totalPHarga = array_sum($totalPHarga);
                                 $totalPBulanan = array_column($filterRow, 'p_bulanan');
                                $totalPBulanan = array_sum($totalPBulanan);
                                  $colorKeluar ='#FFFF70'; $colorMasuk ='#70D59B';
                            ?>
                                    <tr>
                                        <td colspan="6" align="RIGHT"> <b>TOTAL <?=$value?> </b>  </td>
                                        <td > <input type="text" class="form-control globalHarian<?=$unixKode?> pHarianTotal" value="<?=currencyToPage2($totalHarian)?>" disabled readonly style="background:<?=$colorKeluar?>" /> </td>
                                        <td > <input type="text" class="form-control globalBulanan<?=$unixKode?> pBulananTotal" value="<?=currencyToPage2($totalBulanan)?>" style="background:<?=$colorKeluar?>" disabled readonly /> </td>
                                        <td colspan="3"> </td>
                                        <td ><input type="text" class="form-control mglobalHarian<?=$unixKode?> mHarianTotal" value="<?=currencyToPage2($totalPHarga)?>" disabled readonly style="background:<?=$colorMasuk?>" /></td>
                                        <td ><input type="text" class="form-control mglobalBulanan<?=$unixKode?> mBulananTotal" style="background:<?=$colorMasuk?>" value="<?=currencyToPage2($totalPBulanan)?>" disabled readonly /> </td>
                                    </tr>
                             <?
                            }
                             ?>       

                            <?
                            foreach ($arrDataColomnPart as $valuex) {
                                 $no=1;
                                $filterRow2 = multi_array_search($arrData,array('code'=>$value,'part'=>$valuex));
                            
                            ?>
                                <tr style="background: #BFBFBF">
                                    <td style="padding: 5px"> </td>
                                     <td colspan="12" style="padding: 5px">  <?=$valuex?>.<?=$arrCodeSub[$value.$valuex]?></td>

                                </tr>
                                <?   
                                foreach ($filterRow2 as  $val2) {
                                    $randomId = rand();
                                    $unixKode = $val2['code'].$val2['part'];
                                    $colorKeluar ='#FFFF70'; $colorMasuk ='#70D59B';
                                    if ($no % 2 == 0){
                                        $colorKeluar =$colorMasuk='#FFFFFF'; 
                                    }
                                    ?>
                                    <tr>
                                        <td valign="top" align="center"><?=$no;$no++?>. 
                                           <input type="hidden" name="reqHppDetailId[]" value="<?=$val2['project_hpp_new_d_id']?>">
                                    <input type="hidden" name="reqHppUrut[]" value="<?=$val2['template_hpp_id']?>">
                                       <input type="hidden" id='reqCodeIk<?=$randomId?>' class='reqKodeCx' name="reqHppCode[]" value="<?=$val2['code']?>">
                                        <input type="hidden" name="reqHppPart[]" value="<?=$val2['part']?>">
                                         <input type="hidden" id='kodeUnik<?=$randomId?>'  value="<?=$unixKode?>">
                                          <input type="hidden" name="reqHppDetailKeterangan[]" value="<?=$val2['keterangan']?>">
                                         </td>
                                        <td valign="top" align="left" style="padding: 5px"> <?=$val2['keterangan']?> </td>
                                        <td valign="top"> <input type="text"  id='reqQtyK<?=$randomId?>' name='reqQtyK[]' onkeypress='validate(event)' class="easyui-textbox textbox form-control cqty"  value="<?=$val2['k_qty']?>" style="background:<?=$colorKeluar?>" /> </td>
                                        <td valign="top"> <input type="text"  id='reqDayK<?=$randomId?>' name='reqDayK[]'  onkeypress='validate(event)' class="easyui-textbox textbox form-control cday"  value="<?=$val2['k_day']?>" style="background:<?=$colorKeluar?>" /> </td>
                                        <td valign="top">  <select class="form-control" name='reqStatusK[]' style="background:<?=$colorKeluar?>">
                                            <?
                                            foreach ($arrrCombo as  $valCombo) {

                                                 $select = '';
                                                if($valCombo == $val2['k_status']){
                                                      $select = 'selected';
                                                }


                                                ?>
                                                <option value="<?=$valCombo?>" <?= $select?>><?=$valCombo?></option>
                                                <?   
                                            }
                                            ?>
                                             </select>
                                             </td>
                                        <td valign="top"> <input type="text" name='reqHargaSatuanK[]' id='reqHargaSatuanK<?=$randomId?>' onkeypress='validate(event)' class="easyui-textbox textbox numberWithCommas form-control csatauan"  value="<?=currencyToPage2($val2['k_harga'])?>" style="background:<?=$colorKeluar?>" /> </td>
                                        <td valign="top"> <input type="text" name='reqTotalHarianK[]'   id='reqTotalHarianK<?=$randomId?>' onkeypress='validate(event)' class="easyui-textbox numberWithCommas textbox form-control ctotalharian harian<?=$unixKode?>"  value="<?=currencyToPage2($val2['k_total'])?>" style="background:<?=$colorKeluar?>" /> </td>
                                        <td valign="top"> <input type="text" name='reqTotalBulananK[]'  id='reqTotalBulananK<?=$randomId?>' onkeypress='validate(event)' class="easyui-textbox textbox numberWithCommas form-control ctotalbulanan bulanan<?=$unixKode?> codebulanan<?=$val2['code']?>" style="background:<?=$colorKeluar?>"  value="<?=currencyToPage2($val2['k_bulanan'])?>" /> </td>
                                        <td valign="top"> <input type="text" id='reqQtyM<?=$randomId?>' name='reqQtyM[]' onkeypress='validate(event)' class="easyui-textbox textbox form-control mQty"  style="background:<?=$colorMasuk?>" value="<?=$val2['p_qty']?>" /> </td>
                                        <td valign="top"> <input type="text"   id='reqDayM<?=$randomId?>' name='reqDayM[]' onkeypress='validate(event)' class="easyui-textbox textbox form-control mDay" style="background:<?=$colorMasuk?>" value="<?=$val2['p_day']?>" /> </td>
                                        <td valign="top">
                                             <select class="form-control" name='reqStatusM[]' style="background:<?=$colorMasuk?>" >
                                            <?
                                            foreach ($arrrCombo as  $valCombo) {

                                                $select = '';
                                                if($valCombo == $val2['p_status']){
                                                      $select = 'selected';
                                                }
                                            ?>
                                            <option value="<?=$valCombo?>" <?=$select ?>><?=$valCombo?></option>
                                            <?   
                                            }
                                            ?>
                                        </select>

                                         </td>
                                        <td valign="top"> <input type="text" onkeypress='validate(event)' id='reqTotalHarianM<?=$randomId?>' name='reqTotalHarianM[]' class="easyui-textbox numberWithCommas textbox form-control mTotal mharian<?=$unixKode?>"  value="<?=currencyToPage2($val2['p_harga'])?>" style="background:<?=$colorMasuk?>" /> </td>
                                        <td valign="top"> <input type="text" onkeypress='validate(event)' id='reqTotalBulananM<?=$randomId?>'  name='reqTotalBulananM[]' class="easyui-textbox  numberWithCommas textbox form-control  mTotalBulanan mbulanan<?=$unixKode?> mcodebulanan<?=$val2['code']?>"  value="<?=currencyToPage2($val2['p_bulanan'])?>" style="background:<?=$colorMasuk?>" /> </td>

                                    </tr>
                                    <?
                                }   

                                 $totalHarian = array_column($filterRow2, 'k_total');
                                $totalHarian = array_sum($totalHarian);
                                 $totalBulanan = array_column($filterRow2, 'k_bulanan');
                                $totalBulanan = array_sum($totalBulanan);
                                $totalPHarga = array_column($filterRow2, 'p_harga');
                                $totalPHarga = array_sum($totalPHarga);
                                 $totalPBulanan = array_column($filterRow2, 'p_bulanan');
                                $totalPBulanan = array_sum($totalPBulanan);
                                 $colorKeluar ='#FFFF70'; $colorMasuk ='#70D59B';
                                ?>
                                
                                     <tr>
                                        <td colspan="6" align="RIGHT"> <b>TOTAL <?=$val2['code'].' '.$val2['part']?> </b>  </td>
                                        <td > <input type="text" class="form-control globalHarian<?=$unixKode?> pHarianTotal" value="<?=currencyToPage2($totalHarian)?>" disabled readonly style="background:<?=$colorKeluar?>" /> </td>
                                        <td > <input type="text" class="form-control globalBulanan<?=$unixKode?> pBulananTotal" value="<?=currencyToPage2($totalBulanan)?>" style="background:<?=$colorKeluar?>" disabled readonly /> </td>
                                        <td colspan="3"> </td>
                                        <td ><input type="text" class="form-control mglobalHarian<?=$unixKode?> mHarianTotal" value="<?=currencyToPage2($totalPHarga)?>" style="background:<?=$colorMasuk?>" disabled readonly /></td>
                                        <td ><input type="text" class="form-control mglobalBulanan<?=$unixKode?> mBulananTotal" style="background:<?=$colorMasuk?>" value="<?=currencyToPage2($totalPBulanan)?>" disabled readonly /> </td>
                                    </tr>

                            <?
                            }
                            ?>
                            

                            <?
                            }
                            ?>

                       
                        
                    </table>
                </div>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Project Budget</h3>
                    </div>

                     <div class="form-group">
                        <label for="reqDateProject" class="control-label col-md-2">Total Pengeluaran A+B+C+D</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input type="text" id="grandTotalPengeluaranAbcd" placeholder=""  class="easyui-textbox textbox form-control numberWithCommas" name="grandTotalPengeluaranAbcd"
                                      value="<?=currencyToPage2($grandTotalPengeluaranAbcd) ?>" style=" width:100%"
                                     
                                      />
                                </div>
                            </div>
                        </div>
                        <label for="reqAgent" class="control-label col-md-2">Total Pemasukan A+B+C+D</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <input type="text" id="grandTotalPemasukanAbcd" placeholder=""  class="easyui-textbox textbox form-control numberWithCommas" name="grandTotalPemasukanAbcd"  
                                       value="<?=currencyToPage2($grandTotalPemasukanAbcd) ?>" style=" width:100%"
                                    
                                      />
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="reqDateProject" class="control-label col-md-2">Total Pengeluaran MOB E+F</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input type="text" id="grandTotalPengeluaranEf" placeholder=""  class="easyui-textbox textbox form-control numberWithCommas" name="grandTotalPengeluaranEf"
                                      value="<?=currencyToPage2($grandTotalPengeluaranEf) ?>" style=" width:100%"
                                     
                                      />
                                </div>
                            </div>
                        </div>
                        <label for="reqAgent" class="control-label col-md-2">Total Pemasukan MOB E+F</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <input type="text" id="grandTotalPemasukanEf" placeholder=""  class="easyui-textbox textbox form-control numberWithCommas" name="grandTotalPemasukanEf"  
                                       value="<?=currencyToPage2($grandTotalPemasukanEf) ?>" style=" width:100%"
                                    
                                      />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqAgent" class="control-label col-md-2">Pemasukan Harian</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <input type="text" id="grandTotalPemasukanHarian" placeholder="Pemasukan Harian"  class="easyui-textbox textbox form-control numberWithCommas" name="grandTotalPemasukanHarian"
                                     value="<?=currencyToPage2($grandTotalPemasukanHarian) ?>"
                                        style=" width:100%"
                                    
                                      />
                                </div>
                            </div>
                        </div>
                       
                        <label for="reqAgent" class="control-label col-md-2">Pengeluaran Harian</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <input type="text" id="grandTotalPengeluaranHarian" placeholder="Pengeluaran Harian"  class="easyui-textbox textbox form-control numberWithCommas" name="grandTotalPengeluaranHarian"  
                                       value="<?=currencyToPage2($grandTotalPengeluaranHarian) ?>" style=" width:100%"
                                    
                                      />
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="reqDateProject" class="control-label col-md-2">Pemasukan Bulanan</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input type="text" id="grandTotalPemasukanBulanan" placeholder="Pemasukan Bulanan"  class="easyui-textbox textbox form-control numberWithCommas" name="grandTotalPemasukanBulanan"
                                       value="<?=currencyToPage2($grandTotalPemasukanBulanan) ?>"
                                      style=" width:100%"
                                     
                                      />
                                </div>
                            </div>
                        </div>
                         <label for="reqDateProject" class="control-label col-md-2">Pengeluaran Bulanan</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input type="text" id="grandTotalPengeluaranBualanan" placeholder="Pengeluaran Bulanan"  class="easyui-textbox textbox form-control numberWithCommas" name="grandTotalPengeluaranBualanan"
                                      value="<?=currencyToPage2($grandTotalPengeluaranBualanan) ?>" style=" width:100%"
                                     
                                      />
                                </div>
                            </div>
                        </div>
                        
                    </div>
                     <div class="form-group">
                        <label for="reqDateProject" class="control-label col-md-2">Profit </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input type="text" id="grandTotalProfit" placeholder="Profit"  class="easyui-textbox textbox form-control numberWithCommas" name="grandTotalProfit"
                                       value="<?=currencyToPage2($grandTotalProfit) ?>"
                                      style=" width:100%"
                                     
                                      />
                                </div>
                            </div>
                        </div>
                    </div>
                   

                    
                      <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> HPP File</h3>
                    </div>
                     <table style="width: 100%" class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="80%"> File Name <a onclick="tambahPenyebab()" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>
                                <th width="10%"> Type </th>
                                <th width="10%"> Action </th>
                            </tr>
                        </thead>
                        <tbody id="tambahAttacment">
                            <?php
                            $files_data = explode(';',  $reqPath);
                            for ($i = 0; $i < count($files_data); $i++) {
                                if (!empty($files_data[$i])) {
                                    $texts = explode('-', $files_data[$i]);
                                    
                                    $ext = substr($files_data[$i], -3);
                            ?>
                                    <tr>
                                        <td>
                                            <input type="file" onchange="getFileName(this, '<?=($i+1)?>')" name="document[]" multiple class="form-control" style="width: 90%">
                                            <input type="hidden" name="reqLinkFileTemp[]" value="<?= $files_data[$i] ?>">
                                            <?if ($ext !=='pdf')
                                            {
                                            ?>
                                              <a href="uploads/hpp_file/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                              <a onclick="openAdd(`uploads/hpp_file/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            ?>
                                        </td>
                                        <td><?=strtoupper($ext)?></td>
                                        <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>

                        </tbody>
                    </table>

                    <div style="text-align:center;padding:5px">
                        <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />

                        <a href="javascript:void(0)" class="btn btn-warning" onclick="reseti()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                        <?
                        if(empty($reqStatusApproved) && $this->USERID=='6'){
                        ?>
                        <a href="javascript:void(0)" class="btn btn-danger" onclick="approval(<?= $reqId ?>)"><i class="fa fa-fw fa-paper-plane-o"></i> Approval</a>
                        <?
                        }
                        ?>
                         <?
                        if(!empty($reqStatusApproved) && $this->USERID=='6'){
                        ?>
                        <a href="javascript:void(0)" class="btn btn-success" onclick="cancel_approval(<?= $reqId ?>)"><i class="fa fa-fw fa-paper-plane-o"></i>Cancel Approval</a>
                        <?
                        }
                        ?>

                    </div>
                    
                   
                </form>

            </div>
           


        </div>

    </div>


    <script>
        
       
       
          function openVessel() {
            var companyId = $("#reqCompanyId").val();
            openAdd('app/loadUrl/app/template_load_vessel_id?reqCompanyId=' + companyId);
        }
         function company_vessel(id, name, type, clas, dimL, dimB, dimD) {
            $('#reqVesselId').val(id);
            $('#reqVesselName').val(name);
            $('#reqJenisKapal').combobox('setValue', clas);
            $('#reqClass').combobox('setValue', type);
            // $('#reqDimensionL').val(dimL);
            // $('#reqDimensionB').val(dimB);
            // $('#reqDimensionD').val(dimD);
        }
         function clearVessel(){
            $('#reqVesselId').val('');
            $('#reqVesselName').val('');
           
        }
     
       

        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/project_hpp_new_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');
                    reqIds = datas[1];
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/project_hpp_new_add/?reqId=" + datas[0]);
                    
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>

    <script type="text/javascript">

        function approval(id){
            var delele_link='web/project_hpp_json/approval';
            $.messager.confirm('Konfirmasi', ' Yakin untuk aproved  project hpp ini ?<br>' , function(r) {
                if (r) {
                    var jqxhr = $.get(delele_link + '?reqId=' + id, function(data) {
                            
                            document.location.reload();
                        })
                        .done(function() {
                          
                            document.location.reload();
                        })
                        .fail(function() {
                           
                            alert("error");
                        });
                }
            });
            // $.get("web/project_hpp_json/approval?reqId=" + id, function(data) {
            //         window.location.reload();
            // });

        }
        function cancel_approval(id){
            var delele_link='web/project_hpp_json/cancel_approval';
            $.messager.confirm('Konfirmasi', ' Yakin untuk cancel aproved  project hpp ini ?<br>' , function(r) {
                if (r) {
                    var jqxhr = $.get(delele_link + '?reqId=' + id, function(data) {
                            
                            document.location.reload();
                        })
                        .done(function() {
                          
                            document.location.reload();
                        })
                        .fail(function() {
                           
                            alert("error");
                        });
                }
            });
            // $.get("web/project_hpp_json/approval?reqId=" + id, function(data) {
            //         window.location.reload();
            // });

        }
        function deleting(id) {
            var elements = oTable.fnGetData(id);
            var kata = '<b>Detail </b><br>' + elements[3] + '<br> At' + elements[4];

            $.get("web/project_hpp_detil_json/delete?reqId=" + elements[0], function(data) {
                oTable.api().ajax.reload(null,false);
                show_toast('warning', 'Success delete row', kata);
            });
        }
        // btn open
        function editing(id) {
            var elements = oTable.fnGetData(id);
            // console.log(elements);
            $("#reqProjectHppDetailId").val(elements[0]);
            $("#reqCode").val(elements[3]);
            $("#reqDescription").val(elements[4]);
            $("#reqQty").val(elements[5]);
            $('#reqUnitRate').val( elements[6]);
            $('#reqDays').val( elements[7]);
        }

        function test(){
           var  reqCostFromAmdi = $("#reqCostFromAmdi").val();
           var  reqAgent = $("#reqAgent").val();
           var val1='';
           var val2='';
           if(reqCostFromAmdi=='' ){
            reqCostFromAmdi=0;
           }
            if(reqAgent==''){
            reqAgent=0;
           }
           var val1 = reqCostFromAmdi.replaceAll('.','');
           var val2 = reqAgent.replaceAll('.','');
           var total = parseInt(val1)+parseInt(val2);

           // console.log(total);
           var values = formatRupiah(total.toString());
           $("#reqCostToClient").val(values);
            // console.log('Arik');
        }
    </script>
    <script type="text/javascript">
        function ambil_offer(id){
            // console.log(id);
            $.get("web/offer_json/ambil_detail?reqId="+id, function(data) {
                var obj = JSON.parse(data);
                $('#reqNama').val(obj.VESSEL_NAME);
                 $('#reqLocation').val(obj.DESTINATION);
                 $('#reqLokasiPekerjaan').val(obj.DESTINATION);
                 $('#reqOwner').val(obj.COMPANY_NAME);
                  $('#reqJenisPekerjaan').combobox('setValue',obj.GENERAL_SERVICE_NAME);
                  $('#reqClass').combobox('setValue',obj.TYPE_OF_VESSEL);
                   $('#reqJenisKapal').combobox('setValue',obj.CLASS_OF_VESSEL);
                  
                  
                 
                
                // window.location.reload();
            });

        }
    </script>

    <script>
        $(document).ready(function() {
            console.log(total);
        });
        function reseti() {
            oTable.api().ajax.reload(null,false);
            $("#reqProjectHppDetailId").val('');
             $("#reqCode").val('');
            $("#reqDescription").val('');
            $("#reqQty").val('');
            $("#reqUnitRate").val('');
            $("#reqDays").val('');
            
        }
    </script>
    <!-- //hitung summary project --> 
    <script type="text/javascript">
         $(function() {
            // $('#reqRealPrice').change(function() {
            //     var reqRealPrice=  document.getElementById('reqRealPrice').value;
            //    console.log(reqRealPrice);
            // });


            // $('input[name=reqRealPrice]').on('input',function(e){
            //     var total ='';
            //     var reqRealPrice='';
            //     var reqRealPrice=  document.getElementById('reqRealPrice').value;
            //     pc = reqRealPrice.split('.').join("");
            //     $("#reqPc").val(FormatCurrencyBaru(pc));
            //     reqOp =  $('#reqOp').val();
            //     if (reqOp != 0 || reqOp !== "" )
            //     {
            //        var reqCost=  document.getElementById('reqCost').value;
            //        total = reqCost.split('.').join("");
            //        var saldo = parseInt(pc) - parseInt(total);
            //        // console.log(pc);
            //        $("#reqOp").val(FormatCurrencyBaru(total));
            //        $("#reqSaldo").val(FormatCurrencyBaru(saldo));
            //        var profit = (saldo / pc) * 100;
            //        var formats_persen = round(profit, 2);
            //        if (Number.isNaN(formats_persen) || formats_persen == "" ) 
            //        {
            //          $("#reqProfit").html("( Profit: 0 % )");
            //        }
            //        else
            //        {
            //          $("#reqProfit").html("( Profit: " + formats_persen + " % )");
            //        }   
            //    }

            // });
            // $('input[name=reqCost]').on('input',function(e){
            //     reqPc =  $('#reqPc').val();
            //     if (reqPc != 0 )
            //     {
            //       pc = reqPc.split('.').join("");
            //       var reqCost=  document.getElementById('reqCost').value;
            //       total = reqCost.split('.').join("");
            //       var saldo = parseInt(pc) - parseInt(total);
            //       // console.log(pc);
            //       $("#reqOp").val(FormatCurrencyBaru(total));
            //       $("#reqSaldo").val(FormatCurrencyBaru(saldo));
            //       var profit = (saldo / pc) * 100;
            //       var formats_persen = round(profit, 2);
            //       if (Number.isNaN(formats_persen) || formats_persen == "" ) 
            //       {
            //         $("#reqProfit").html("( Profit: 0 % )");
            //       }
            //       else
            //       {
            //         $("#reqProfit").html("( Profit: " + formats_persen + " % )");
            //       }   
            //     }
            //     else
            //     {
            //       var reqCost=  document.getElementById('reqCost').value;
            //       total = reqCost.split('.').join("");
            //       // console.log(pc);
            //       $("#reqOp").val(FormatCurrencyBaru(total));

            //     }
              
            // });
        });
    </script>
    <script type="text/javascript">
        function tambahPenyebab(filename='') {
            var id = $('#tambahAttacment tr').length+1;
            $.get("app/loadUrl/app/tempalate_row_attacment?id="+id+"&filename="+filename, function(data) {
                $("#tambahAttacment").append(data);
            });
        }

        function getFileName(input, id) {
            for (var i = 0; i < input.files.length; i++) {
                if(i == 0)
                {
                    $("#namaFile"+id).html(input.files[0].name);
                    var ext = input.files[0].name.split('.').pop();
                    ext = ext.toUpperCase();
                    if(ext.length > 3) ext = '';
                    if(ext == 'PNG' || ext == 'JPG' || ext == 'JPEG' || ext == 'BMP') ext = 'IMAGE'
                    $("#namaFile"+id).parent().next().html(ext);
                }
                else
                    tambahPenyebab(encodeURIComponent(input.files[i].name))
            }
            
        }
    </script>

    <script type="text/javascript">
        function reloadComboJenus(id){

          $.post("web/pembelian_json/ambilCodeProject",{reqId:id})
          .done(function( data ) {
            var obj = JSON.parse(data);
            $('#reqNamaProject').val(obj['NAMA']);
            $('#reqNoPoContract').val(obj['PO']);
     // $('#reqNoPo').val(obj['PO']);
     // $('#reqNoPo2').val(obj['PO']);
        });
      }
      let arrScript = ['reqQtyK', 'reqDayK', 'reqHargaSatuanK','reqTotalHarianK','reqTotalBulananK'];
      $(document).ready(function() { 
       
        $(".numberWithCommas").on("keyup change", function(e) {
          var id = $(this).attr('id');
          numberWithCommas(id);
          });

           $(".cqty ,.cday ,.csatauan ,.ctotalharian ,.ctotalbulanan").on("keyup change", function(e) {
            var id = $(this).attr('id');
            var number='';
            var res = id.replace(/\D/g, "");

            var qty = $('#reqQtyK'+res).val();
            var days = $('#reqDayK'+res).val();
            var hargaSatuan = $('#reqHargaSatuanK'+res).val();
            hargaSatuan = hargaSatuan.replaceAll('.', '');
            hargaSatuan = hargaSatuan.replaceAll(',', '.');
            var totalHarian =  hargaSatuan *  qty; 
            var totalBulanan =  totalHarian * days;
            $('#reqTotalHarianK'+res).val(FormatCurrencyWithDecimal(totalHarian));
            $('#reqTotalBulananK'+res).val(FormatCurrencyWithDecimal(totalBulanan));

            var kodeUnik = $('#kodeUnik'+res).val();
            var totalHarianGlobal =0;
            var totalBualananGlobal =0;
            $( '.harian'+kodeUnik ).each(function(index) { 
                var val = $(this).val();
                val = val.replaceAll('.', '');
                val = val.replaceAll(',', '.');
                totalHarianGlobal +=parseInt(val); 
            });
             $( '.bulanan'+kodeUnik ).each(function(index) { 
                var val = $(this).val();
                val = val.replaceAll('.', '');
                val = val.replaceAll(',', '.');
                totalBualananGlobal +=parseInt(val); 
            });

            $('.globalHarian'+kodeUnik).val(FormatCurrencyWithDecimal(totalHarianGlobal));
             $('.globalBulanan'+kodeUnik).val(FormatCurrencyWithDecimal(totalBualananGlobal));
             kalkulasi();

             kalukulasiHuruf(res);
                

          });
           $(".mQty,.mDay ,.mTotal ,.mTotalBulanan ").on("keyup change", function(e) {
              var id = $(this).attr('id');
             
              var res = id.replace(/\D/g, "");
      // var res;
             var qty = $('#reqQtyM'+res).val();
             var days = $('#reqDayM'+res).val();
             var hargaSatuan = $('#reqTotalHarianM'+res).val();
             hargaSatuan = hargaSatuan.replaceAll('.', '');
             hargaSatuan = hargaSatuan.replaceAll(',', '.');
             var totalHarian =  hargaSatuan *  qty; 
             var totalBulanan =  totalHarian * days;
             console.log(totalBulanan);
             $('#reqTotalBulananM'+res).val(FormatCurrencyWithDecimal(totalBulanan));



             var kodeUnik = $('#kodeUnik'+res).val();
             var totalHarianGlobal =0;
             var totalBualananGlobal =0;
             $( '.mharian'+kodeUnik ).each(function(index) { 
                var val = $(this).val();
                val = val.replaceAll('.', '');
                val = val.replaceAll(',', '.');
                totalHarianGlobal +=parseInt(val); 
            });
             $( '.mbulanan'+kodeUnik ).each(function(index) { 
                var val = $(this).val();
                val = val.replaceAll('.', '');
                val = val.replaceAll(',', '.');
                totalBualananGlobal +=parseInt(val); 
            });

             $('.mglobalHarian'+kodeUnik).val(FormatCurrencyWithDecimal(totalHarianGlobal));
             $('.mglobalBulanan'+kodeUnik).val(FormatCurrencyWithDecimal(totalBualananGlobal));
              kalkulasi();
              kalukulasiHuruf(res);
           });


     });

      function kalkulasi(){

        var totalBualananGlobal=0;
        var totalProfit=0;
        $( '.pHarianTotal' ).each(function(index) { 
            var val = $(this).val();
            val = val.replaceAll('.', '');
            val = val.replaceAll(',', '.');
            totalBualananGlobal +=parseInt(val); 
           
        });
         $('#grandTotalPengeluaranHarian').val(FormatCurrencyWithDecimal(totalBualananGlobal));
         var totalBualananGlobal=0;
          $( '.mBulananTotal' ).each(function(index) { 
            var val = $(this).val();
            val = val.replaceAll('.', '');
            val = val.replaceAll(',', '.');
            totalBualananGlobal +=parseInt(val); 
        });
          totalProfit = totalBualananGlobal;
           $('#grandTotalPemasukanBulanan').val(FormatCurrencyWithDecimal(totalBualananGlobal));
   
      
           var totalBualananGlobal=0;
         $( '.pBulananTotal' ).each(function(index) { 
            var val = $(this).val();
            val = val.replaceAll('.', '');
            val = val.replaceAll(',', '.');
            totalBualananGlobal +=parseInt(val); 
        });
        $('#grandTotalPengeluaranBualanan').val(FormatCurrencyWithDecimal(totalBualananGlobal));
            var totalBualananGlobal=0;
         $( '.mHarianTotal' ).each(function(index) { 
            var val = $(this).val();
            val = val.replaceAll('.', '');
            val = val.replaceAll(',', '.');
            totalBualananGlobal +=parseInt(val); 
         });
          totalProfit =  totalProfit-totalBualananGlobal;
          $('#grandTotalPemasukanHarian').val(FormatCurrencyWithDecimal(totalBualananGlobal));
       

           $('#grandTotalProfit').val(FormatCurrencyWithDecimal(totalProfit));
           
      }
        
      function kalukulasiHuruf(res){
           var totalHurufABCD= mtotalHurufABCD= totalHuruEf= mtotalHuruEf= 0 ;
           var arrr = ['A','B','C','D','E','F'];
          // $( '.reqKodeCx' ).each(function(index) { 
            for(var i=0;i<arrr.length;i++){

            var kodeUnikHuruf = arrr[i];
            console.log(kodeUnikHuruf);
         
            var totali=mTotali= 0 ;
             if(kodeUnikHuruf=='A' || kodeUnikHuruf=='B' || kodeUnikHuruf=='C' || kodeUnikHuruf=='D' ){
               $( '.codebulanan'+kodeUnikHuruf ).each(function(index) { 
                var val = $(this).val();
                val = val.replaceAll('.', '');
                val = val.replaceAll(',', '.');
                totali +=parseInt(val); 
              
              });
               totalHurufABCD +=totali;

                $( '.mcodebulanan'+kodeUnikHuruf ).each(function(index) { 
                var val = $(this).val();
                val = val.replaceAll('.', '');
                val = val.replaceAll(',', '.');
                mTotali +=parseInt(val); 
               
              });
                mtotalHurufABCD += mTotali;
               
            }

             var totali=mTotali= 0 ;
            if(kodeUnikHuruf=='E' || kodeUnikHuruf=='F'  ){
               $( '.codebulanan'+kodeUnikHuruf ).each(function(index) { 
                var val = $(this).val();
                val = val.replaceAll('.', '');
                val = val.replaceAll(',', '.');
                totali +=parseInt(val); 
              });
               totalHuruEf +=totali;
                $( '.mcodebulanan'+kodeUnikHuruf ).each(function(index) { 
                var val = $(this).val();
                val = val.replaceAll('.', '');
                val = val.replaceAll(',', '.');
                mTotali +=parseInt(val); 
              });
                 mtotalHuruEf += mTotali;
              
               
            }
        }

          $('#grandTotalPengeluaranAbcd').val(FormatCurrencyWithDecimal(totalHurufABCD));
          $('#grandTotalPemasukanAbcd').val(FormatCurrencyWithDecimal(mtotalHurufABCD));
          $('#grandTotalPengeluaranEf').val(FormatCurrencyWithDecimal(totalHuruEf));
          $('#grandTotalPemasukanEf').val(FormatCurrencyWithDecimal(mtotalHuruEf));

      }
    </script>
    <script type="text/javascript">
         function FormatCurrencyWithDecimal(num) 
{
    num = Math.round(num * 100)/100;
    num = num.toString().replace(/\$|\,/g,'');
    if(isNaN(num))
        num = "0";
        
    sign = (num == (num = Math.abs(num)));
    
    num_str = num.toString();
    cents = 0;
    
    if(num_str.indexOf(".")>=0)
    {
        num_str = num.toString();
        angka = num_str.split(".");
        cents = angka[1];
    }
    
    num = Math.floor(num).toString();
    
        
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    {
        num = num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
    }
    
    if(cents != "00"){
        var legCent = cents.length;
        if(legCent==1){ cents = cents+'0';}
       // if(legCent > 2 ){ cents = Math.round(cents * 100)/100 ;}
        return (((sign)?'':'-') +  num + ',' + cents);
    }
    else{
        return (((sign)?'':'-') +  num);
    }
}
    </script>
    <script type="text/javascript">
         function cetakPdf() {
            openAdd('app/loadUrl/report/template_report_hpp_new_pdf?reqId=<?= $reqId ?>');
        }
    </script>
</div>
</div>