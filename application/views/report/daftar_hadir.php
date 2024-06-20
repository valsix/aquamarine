<?
error_reporting(1);
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get("reqId");
$reqExcel = $this->input->get("reqExcel");

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=rekap_kehadiran.xls");

$this->load->model("Slider");

$slider = new Slider();
$slider->selectByParams(array("A.SLIDER_ID" => $reqId, "JENIS" => "AGENDA"));
$slider->firstRow();
$reqId             = $slider->getField("SLIDER_ID");
$reqTipe       		= $slider->getField("TIPE");
$reqTanggal                  = $slider->getField("HARI");
$reqJam                  = $slider->getField("JAM");
$reqNama                    = $slider->getField("NAMA");
$reqKeterangan              = $slider->getField("KETERANGAN");
$reqLinkFile				= $slider->getField("LINK_FILE");

$arrKolom = array("NRP", "NO ANGGOTA", "NAMA", "JAM HADIR");
$arrField = array("PEGAWAI_ID", "NO_SEKAR", "NAMA", "JAM_HADIR");


$slider = new Slider();
$slider->selectByParamsHadir(array("A.SLIDER_ID" => $reqId));
?>
 <!doctype html>
 <html>
 <head>
  <meta charset="utf-8">
  <base href="<?=base_url()?>" />
  <script>
    document.onkeydown = function(e) {
      if(e.keyCode == 123) {
       return false;
     }
     if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
       return false;
     }
     if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
       return false;
     }
     if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
       return false;
     }
     
     if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)){
       return false;
     }      
   }    
 </script>
 <link rel="stylesheet" type="text/css" href="css/gaya-laporan.css">
</head>

<body oncontextmenu="return false;">
  
   <table class="area-kop-slip">
      <tr>
          <td colspan="5"><strong>Serikat Karyawan Perum BULOG</strong></td>
        </tr>
      <tr>
        <td colspan="5">Jl. Jendral Gatot Subroto Kav. 49 Jakarta - 12950 </td>
      </tr>
      <tr>
        <td colspan="5">Telp. (6221) 525-2209</td>
      </tr>
  </table>
    
    
   <table class="area-kop-slip">
  <tr>
    <td colspan="5"></td>
  </tr>
  <tr>
    <td colspan="5" align="center"><strong>REKAP KEHADIRAN ANGGOTA</strong></td>
  </tr>
  <tr>
    <td colspan="2">Kegiatan</td>
    <td colspan="3"><?=$reqNama?></td>
  </tr>
  <tr>
    <td colspan="2">Tanggal</td>
    <td colspan="3"><?=getFormattedDate(($reqTanggal))?>, <?=$reqJam?></td>
  </tr>
  </table>
      <table class="area-data-slip" border="1">
        <tr>
         <th rowspan="2">No.</th>
         <?
         for($i=0;$i<count($arrKolom);$i++)
         {
          ?>
          <th rowspan="2"><?=$arrKolom[$i]?></th>
          <?
        }
        ?>
     <tr>
    <?
    $no = 1;
    while($slider->nextRow())
    {
      ?>
      <tr style="vertical-align: middle">
        <td width:"50px"><?=$no?></td>
        <?
        for($i=0;$i<count($arrField);$i++)
        {
          $align = "style=\"text-align:left\"";
          $nilai = ($slider->getField($arrField[$i]));
          ?>
          <td <?=$align?>><?=$nilai?></td>
          <?
        }
        ?>
      </tr>      
      <?
      $no++;
    }
    ?>    
  </table>

  
</body>
</html>