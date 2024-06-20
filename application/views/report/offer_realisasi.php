<?
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");
 $this->load->model("Report");
 $projecthppnew = new Report();
 
$projecthppnew->selectByParamsRealisasi(array(),-1,-1, $STATEMENT,' ORDER BY A.URUT IS null  desc,A.URUT desc');
$arrData = $projecthppnew->rowResult;

?>
<h1 style="font-size: 16px;text-align: center;font-family: Arial;"><u> <b> OFFER REALISASI  </b> </u>
   
</h1>
<table  cellspacing="0" width="100%" border="1" style="border-collapse: 1px solid black">
    <thead>
      <tr>
        <th width="2%" style="background-color: #D9D9D9 ">NO</th>
        <th style="background-color: #D9D9D9 ">No. Report</th>
        <th style="background-color: #D9D9D9 ">Nama Kapal</th>

        <th style="background-color: #D9D9D9 ">Type Service</th>
        <th style="background-color: #D9D9D9 ">Lokasi Survey</th>
        <th style="background-color: #D9D9D9 ">Class </th>
        <th style="background-color: #D9D9D9 ">OWNER / AGENT</th>
        <th style="background-color: #D9D9D9 ">Work Date</th>
        <th style="background-color: #D9D9D9 ">Finish Date</th>
        <th style="background-color: #D9D9D9 ">Harga Jual</th>

        <th style="background-color: #D9D9D9 ">Operasional Cost</th>
        <th style="background-color: #D9D9D9 ">Profit</th>
        <th style="background-color: #D9D9D9 "> Status Pembayaran</th>
        <th style="background-color: #D9D9D9 ">%</th>
        <th style="background-color: #D9D9D9 ">Ket</th>              

    </tr>
</thead>
 <tbody>

              <?
              $no=1;
              foreach ($arrData as $value) {
              ?>
              <tr>
                <td style="padding: 5px"> <?=$value['urut']?> </td>
                <td style="padding: 5px;width: 10%"> <?=$value['no_report']?></td>
                 <td style="padding: 5px">
                  <?=$value['name_of_vessel']?><br> 
<b><?=$value['type_of_vessel']?></b> 
                    </td>
                  <td style="padding: 5px"> <?=$value['general_service_detail']?>  </td>
                   <td style="padding: 5px"> <?=$value['location']?> </td>
                   <td style="padding: 5px"> <?=$value['class_society']?> </td>
                   <td style="padding: 5px">  <?=$value['name']?> </td>
                   <td style="padding: 5px"> <?=$value['start_date']?> </td>
                   <td style="padding: 5px"> <?=$value['finish_date']?> </td>
                   <td style="padding: 5px"> <?=currencyToPage2($value['total'])?> </td>
                   
                    <td style="padding: 5px"> <?=currencyToPage2($value['total_realisasi'])?> </td>
                     <td style="padding: 5px"> <?=currencyToPage2($value['profit'])?> </td>
                     <td style="padding: 5px"> <?=$value['status_realisasi']?> </td>
                      <td style="padding: 5px"> <?=$value['prescentage']?> </td>
                       <td style="padding: 5px"> <?=$value['keterangan']?> </td>
              </tr>
              <?
              }
              ?>
             
               
            </tbody>
</table>
