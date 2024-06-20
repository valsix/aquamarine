<?
include_once("functions/string.func.php");
$reqId = $this->input->get('reqId');

$query = $this->db->query('SELECT a.*,D.NAMA NAMA_PROJECT
            ,c.CURRENCY,
            B.NAME NAMA_SUPPLIER ,B.ADDRESS,
            c.JENIS_PEMBAYARAN FROM VIEW_ALAT a
  left join pembelian c on c.PEMBELIAN_ID = a.pembelian_id
     LEFT JOIN COMPANY B ON B.COMPANY_ID = c.COMPANY_ID
      LEFT JOIN MASTER_PROJECT D ON D.MASTER_PROJECT_ID = c.MASTER_PROJECT_ID
  WHERE a.PEMBELIAN_ID ='.$reqId);
$arrDataPembelian = $query->result_array();


?>

<table style="table-layout: fixed; width: 100%" class="table table-striped tablei" border="1">
  <thead>
    <tr>
      <th style="width: 15% !important"> JENIS PEMBELIAN </th>
      <th  style="width: 20% !important"> NAMA BARANG </th>
      <th style="width: 23% !important"> NAMA SUPLIER </th>
      <th style="width: 20% !important"> ALAMAT SUPLIER </th>
      <th style="width: 10% !important">  <div class='text-wrap width-200'>NAMA PROJECT </div></th>
      <th style="width: 5% !important">QTY </th>
       <th style="width: 15% !important">TOTAL</th>
        <th style="width: 5% !important"><div class='text-wrap width-200'> JENIS PEMBAYARAN</div></th>
    </tr>
    <tbody>
      <?
      $i=0;
      foreach ($arrDataPembelian as  $value) {
       $reqNamaAl = explode('&&&', $value['nama_alat']);
       $reqQty = explode('&&&',  $value['qty']);
       $reqTotal = explode('&&&',  $value['total']);
       $total_row =count($reqNamaAl);
       $reqNamaSuplier = $reqAlamat=$reqNamaProject=$reqJenisPembayaran='';
       if($i==0){
         $reqNamaSuplier = $value['nama_supplier'];
         $reqAlamat = $value['address'];
         $reqNamaProject = $value['nama_project'];
         $reqJenisPembayaran = $value['jenis_pembayaran'];
      }

      ?>  
      <tr>
        <td rowspan="<?=$total_row?>"><div class='text-wrap width-200'> <?=$value['ec_name']?> <br> <?=$value['equip_name']?> </div></td>
        <td><?=$reqNamaAl[0]?>  </td>
        <td><?=$reqNamaSuplier?>  </td>
        <td><div class='text-wrap width-200'> <?=$reqAlamat?></div>  </td>
        <td> <?=$reqNamaProject?>  </td>
        <td> <?=$reqQty[0]?> </td>
        <td>  <?=currencyToPage2($reqTotal[0])?></td>
        <td>   <?=$reqJenisPembayaran?> </td>
      </tr>
      
      <?
        for($kk=1;$kk<$total_row;$kk++){
        ?>
        <tr>
         
         <td><?=$reqNamaAl[$kk]?>  </td>
         <td>  </td>
         <td>  </td>
         <td>  </td>
         <td> <?=$reqQty[$kk]?> </td>
         <td>  <?=currencyToPage2($reqTotal[$kk])?></td>
         <td>  </td>

        </tr>
        
        <?  
        }


      $i++;
      }
      ?>

     
    </tbody>
  </thead>
</table>