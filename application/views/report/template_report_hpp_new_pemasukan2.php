<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get("reqId");
$this->load->model("SoTeamNew");
$this->load->model("ServiceOrderNew");
$this->load->model("ProjectHppNewD");
$this->load->model("ProjectHppNew");
$project_hpp_new = new ProjectHppNew();
$projecthppnewd = new ProjectHppNewD();
$projecthppnewd->selectByParamsMonitoring(array('A.PROJECT_HPP_NEW_ID::VARCHAR'=>$reqId),-1,-1," AND A.CODE NOT IN ('A','B','C','D')",' ORDER BY URUT ASC');
 $arrData =  $projecthppnewd->rowResult;
$arrDataColomn = array_column($arrData, 'code');
$arrDataColomn = array_unique($arrDataColomn);
$arrKeterangan = array('A'=>'VESSEL','B'=>'EQUIPMENT & MATERIAL ','C'=>'PERSONNEL','D'=>'PMT','E'=>'MOB','F'=>'SUPPORT ');
$arrCodeSub = array('C1'=>'Working','C2'=>'Stanby');
sort( $arrDataColomn);

   $project_hpp_new->selectByParamsMonitoring(array("CAST(A.PROJECT_HPP_NEW_ID AS VARCHAR)" => $reqId));
    $project_hpp_new->firstRow();

  $grandTotalPengeluaranBualanan = $project_hpp_new->getField("KELUAR_BULANAN");
  $grandTotalPengeluaranHarian = $project_hpp_new->getField("KELUAR_HARIAN");
  $grandTotalPemasukanBulanan = $project_hpp_new->getField("PEMASUKAN_BULANAN");
  $grandTotalPemasukanHarian = $project_hpp_new->getField("PEMASUKAN_HARIAN");
  $grandTotalProfit = $project_hpp_new->getField("PROFIT");
  $grandTotalPengeluaranAbcd = $project_hpp_new->getField("PENGELUARANABCD");
  $grandTotalPemasukanAbcd = $project_hpp_new->getField("PENGELUARANEF");
  $grandTotalPengeluaranEf = $project_hpp_new->getField("PEMASUKANABCD");
  $grandTotalPemasukanEf = $project_hpp_new->getField("PEMASUKANEF");
   $reqKodeProject = $project_hpp_new->getField("CODE_PROJECT_KET");
     $reqBulan = $project_hpp_new->getField("HPP_DATE");
       $reqTahun = $project_hpp_new->getField("TAHUN");
          $reqHppProjectNo= $project_hpp_new->getField("NOMER");
    
  $arrKeyTotal = array();
  foreach ($arrData as  $value) {
     $arrKeyTotal[$value['code']]['k_harian'] =   ifZero2($arrKeyTotal[$value['code']]['k_harian'])  + (int) $value['k_total'];
     $arrKeyTotal[$value['code']]['m_harian'] =ifZero2($arrKeyTotal[$value['code']]['m_harian']) + (int) $value['p_harga'];
  }



?>
<!-- <style>
    td, th {
        font-size: 10px;
    }
</style> -->

<h1 style="font-size: 16px;text-align: center;font-family: Arial;"><u> <b> RENCANA ANGGARAN BELANJA & PENDAPATAN RUTIN  </b> </u><br>
    <small style="font-size: 13px;font-family: Arial;font-weight: normal;   "><em> PROJECT IMR : <?=$reqHppProjectNo?>,  <?= $reqBulan?> <?=$reqTahun?></em></small>
</h1>




 <table style="font-size: 12px;font-family: Arial;border-collapse: 1px solid black;" border="1">
          <tr>
                                <th rowspan="2"  > # </th>
                                 <th rowspan="2" > Deskripsi </th>
                                  
                                   <th colspan="6" style="background-color: #70D59B"> Pemasukan  </th>
                            </tr>
                            <tr>
                              
                                  <th  style="background-color: #70D59B">Qty   </th>
                                 <th  style="background-color: #70D59B">Days    </th>
                                  <th style="background-color: #70D59B" width="30">Status    </th>
                                  <th  style="background-color: #70D59B">Harga Harian      </th>
                                  <th  style="background-color: #70D59B" >Harga Jual Bulanan     </th>
                            </tr>
                            <?
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
                                     <td valign="top" align="center"><?=$no;$no++?>.</td>
                                     <td valign="top" align="left" style="padding: 5px"> <?=$val['keterangan']?> </td>
                                     
                                       <td valign="top" align="center"> <?=$val['p_qty']?> </td>
                                         <td valign="top" align="center"> <?=$val['p_day']?> </td>
                                           <td valign="top" align="center"> <?=$val['p_status']?> </td>
                                           <td valign="top" align="right"> <?=currencyToPage2($val['p_harga'])?> </td>
                                           <td valign="top" style="background: #70D59B" align="right"> <?=currencyToPage2($val['p_bulanan'])?> </td>
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
                             <tr style="background: #BDD7EE">
                                        <td colspan="5" align="RIGHT"> <b>TOTAL <?=$value?> </b>  </td>
                                       
                                         <td align="right"><?=currencyToPage2($totalPHarga)?> </td>
                                          <td align="right"><?=currencyToPage2($totalPBulanan)?> </td>
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
                                       <td valign="top" align="center"><?=$no;$no++?>.</td>
                                     <td valign="top" align="left" style="padding: 5px"> <?=$val2['keterangan']?> </td>
                                        
                                              <td valign="top" align="center"> <?=$val2['p_qty']?></td>
                                             <td valign="top" align="center"> <?=$val2['p_day']?></td>
                                             <td valign="top" align="center"> <?=$val2['p_status']?></td>
                                             <td valign="top" align="right"> <?=currencyToPage2($val2['p_harga'])?></td>
                                             <td valign="top" style="background: #70D59B" align="right"> <?=currencyToPage2($val2['p_bulanan'])?></td>
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
                                    <tr style="background: #BDD7EE">
                                        <td colspan="5" align="RIGHT"> <b>TOTAL <?=$val2['code'].' '.$val2['part']?> </b>  </td>
                                         
                                             <td align="right"><?=currencyToPage2($totalPHarga)?></td>
                                             <td align="right"><?=currencyToPage2($totalPBulanan)?></td>
                                         </tr>
                                 <?
                                }

                            }
                            ?>

                            <?
                          
                        $keluar_harian =      $arrKeyTotal['A']['k_harian'] + $arrKeyTotal['B']['k_harian'] + $arrKeyTotal['C']['k_harian'] + $arrKeyTotal['D']['k_harian'];
                        $masuk_harian =      $arrKeyTotal['A']['m_harian'] + $arrKeyTotal['B']['m_harian'] + $arrKeyTotal['C']['m_harian'] + $arrKeyTotal['D']['m_harian'];

                         $keluar_harian2 =      $arrKeyTotal['E']['k_harian'] + $arrKeyTotal['F']['k_harian'] ;
                         $masuk_harian2 =      $arrKeyTotal['E']['m_harian'] + $arrKeyTotal['F']['m_harian'];

                         $reqSubTotalKeluar =(int) $grandTotalPengeluaranAbcd +(int) $grandTotalPengeluaranEf;
                         $reqSubTotalMasuk =(int) $grandTotalPemasukanAbcd +(int) $grandTotalPemasukanEf;

                         $reqSubTotalKHarian = (int) $masuk_harian +$masuk_harian2;
                           $reqSubTotalMHarian = (int) $keluar_harian +$keluar_harian2;
                            ?>
                           
                            <tr>
                                <td colspan="5" align="RIGHT"> <b>SUBTOTAL E + F  </b>  </td>
                               
                                <td align="right"><?=currencyToPage2($masuk_harian2) ?></td>
                                <td align="right"><?=currencyToPage2($grandTotalPemasukanEf) ?></td>
                            </tr>
                             
        </table>
    




