<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");
ini_set('upload_max_filesize', '10M');
        ini_set('post_max_size', '10M');
        ini_set('memory_limit', '-1');
        ini_set('max_input_time', 520);
        ini_set('max_execution_time', 300);
        set_time_limit(0);

require('libraries/fpdf182/pdf-equipment.php');

class INIPDF extends PDF
{
// Page header
function Header()
{
    // Logo
    $this->Image('images/header-logo.jpg',(($this->w*3)/100),(($this->w*1)/100),(($this->w*12)/100), (($this->w*12)/100));
    // Arial bold 15
    $panjang = (($pdf->w * 80) / 100);
    // ECHO $pdf->w;exit;
    $this->SetFont('Arial', 'B', 18);
    $this->Cell($panjang, 10, 'LIST PEMBELIAN BARANG DAN JASA', 0, 0, 'C');
    // Line break
    $this->Ln(25);
}

function RowImage($data)
  {

     $this->aligns[7]='R';
     $this->aligns[8]='C';
      $this->aligns[9]='R';

    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
      $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));

    if(!empty($this->imageKey)){
      $widthimage = 0;
      for ($i=0; $i < count($this->imageKey); $i++) { 
        if($widthimage < $this->widths[$this->imageKey[$i]])
          $widthimage = $this->widths[$this->imageKey[$i]];
      }
      $h = $widthimage-8;
    } else {
      $h=5*$nb;
    }
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
      $w=$this->widths[$i];
      $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
      $x=$this->GetX();
      $y=$this->GetY();
        //Draw the border
      $this->Rect($x,$y,$w,$h);
        //Print the text
      // writeHTML
      //modify functions for image 
          if(!empty($this->imageKey) && in_array($i,$this->imageKey)){

        $ih = $h - 0.5;
        $iw = $w - 0.5;
        $ix = $x + 0.25;
        $iy = $y + 0.25;
        // $maskImg2 = $this->ImagePngWithAlpha($data[$i], $ix,$iy,$iw,$ih, '', '', true); 
        $this->MultiCell($w,5,$this->Image($data[$i],$ix,$iy,$iw,$ih),0,$a);
          }else{
            $this->MultiCell($w,5,$data[$i],0,$a);  
          }
      
        //Put the position to the right of the cell
      $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
  }

  function Row2($data)
  {
     $this->aligns[1]='R';
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
      $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
      $w=$this->widths[$i];
      $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
        //Save the current position
      $x=$this->GetX();
      $y=$this->GetY();
        //Draw the border
      $this->Rect($x,$y,$w,$h);
        //Print the text
      // writeHTML
      //modify functions for image 
          if(!empty($this->imageKey) && in_array($i,$this->imageKey)){

        $ih = $h - 0.5;
        $iw = $w - 0.5;
        $ix = $x + 0.25;
        $iy = $y + 0.25;
        $this->MultiCell($w,5,$this->Image ($data[$i],$ix,$iy,$iw,$ih),0,$a);
          }else{
            $this->MultiCell($w,5,$data[$i],0,$a);  
          }
      
        //Put the position to the right of the cell
      $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
  }


}




        // $statement_privacy =$_SESSION["reqCariSessionEquip"];
$this->load->model('Pembelian');
$this->load->model('PembelianDetail');
$this->load->model('PembelianAlat');
$this->load->model('MasterCurrency');


$mastercurrency = new MasterCurrency();
$mastercurrency->selectByParamsMonitoring();
$arrCur = $mastercurrency->rowResult;
$dataCur =array();
foreach ($arrCur as  $value) {
  $arrCur[$value['nama']]= $value['inisial'];
}

$statement_privacy=$_SESSION['reqPembelianSession'] ;    

$pembelian =new Pembelian();
$pembelian->selectByParamsMonitoring(array(),-1,-1, $statement_privacy);
$total = $pembelian->rowCount;
$arrDataPembelian =$pembelian->rowResult;

 
$pembeliandetail =new PembelianDetail();
$pembeliandetail->selectByParamsMonitoring(array());
$arrDataPembelianDetail = $pembeliandetail->rowResult;

$pembelianalat =new PembelianAlat();
$pembelianalat->selectByParamsMonitoring(array());
$arrDataPembelianAlat = $pembelianalat->rowResult;


$aColumns = array(
    "NO", "TANGGAL", "JENIS PEMBELIAN", "NAMA BARANG", "SERIAL NUMBER", "VENDOR", "PROJECT / NO PO", "HARGA", "QTY",
    "TOTAL", "PEMBAYARAN"
);
$aColumnsAlias = array(
     "NO", "TANGGAL", "JENIS PEMBELIAN", "NAMA BARANG", "SERIAL NUMBER", "VENDOR", "PROJECT / NO PO", "HARGA", "QTY",
    "TOTAL", "PEMBAYARAN"
);

$pdf = new INIPDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
// $pdf->SetFont('Arial', 'B', 16);
// $pdf->Cell($panjang, 10, 'EQUIPMENT LIST', 0, 0, 'C');
// $pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 9);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();

$pdf->SetWidths(array(9, 20, 22, 40, 24, 45, 40, 30, 10, 36));
 $pdf->Row(array(
    'No','Tanggal','Jenis Pembelian','Nama Barang','Serial','Vendor','Project / No Po','Harga','Qty','Total'
 ));


// print_r($arrPanjang);exit;

 // echo $pembelian->query;exit;
// exit;


$no = 1;
$TOTAL_KESELURUHAN=0;

// while ($pembelian->nextRow()) {
foreach ($arrDataPembelian as $value) {
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetWidths(array(9, 20, 22, 40, 24, 45, 40, 30, 10, 36));
         $reqPembelianId = $value['pembelian_id'];
         $arrFilterDetail = multi_array_search($arrDataPembelianDetail,array("pembelian_id"=>$reqPembelianId));

         $arrFilterDetail_1 = $arrFilterDetail[0];
         $reqPembelianDetailId = $arrFilterDetail_1['pembelian_detail_id'];
         $arrFilterDetailAlat = multi_array_search($arrDataPembelianAlat,array("pembelian_detail_id"=>$reqPembelianDetailId));

         $total_qty =$total_total= 0;
         $total_qty +=$arrFilterDetail_1['qty'];
         $total_total +=$arrFilterDetail_1['total'];

         $reqCurrency = $arrFilterDetail_1['currency'];   


        $pdf->RowImage(array(
        // kolom tabel
            $no,
            '' . $value['tanggal'],
            '' . $arrFilterDetail_1['nama_kategori'],
            '' . $arrFilterDetail_1['nama_alat'],
            '' . $arrFilterDetail_1['no_seri'],
            '' . $value['nama_supplier']."\n".$value['address'],
            '' . $value['nama_project']."\n".$value['no_po'],
            '' .$arrCur[$value['currency']].'. '. currencyToPage2($arrFilterDetail_1['harga']),
            '' . $arrFilterDetail_1['qty'],
            '' .$arrCur[$value['currency']].'. '. currencyToPage2($arrFilterDetail_1['total']),
          
            
           
        ));
    
    $no++;
     foreach ($arrFilterDetailAlat as $aVal) {
       $total_qty +=$aVal['qty'];
       $total_total +=$aVal['total'];
        $pdf->RowImage(array(
        // kolom tabel
            '',
            '' ,
            '' ,
            '' . $aVal['nama_alat'],
            '' . $aVal['serial_number'],
            '' ,
            '' ,
            '' .$arrCur[$value['currency']].'. '. currencyToPage2($aVal['harga']),
            '' . $aVal['qty'],
            '' .$arrCur[$value['currency']].'. '. currencyToPage2($aVal['total']),
           
            
           
        ));
    }

     for($kk=1;$kk<count($arrFilterDetail);$kk++){
      $tVal = $arrFilterDetail[$kk];
      $reqPembelianDetailId = $tVal['pembelian_detail_id'];
      $arrFilterDetailAlat = multi_array_search($arrDataPembelianAlat,array("pembelian_detail_id"=>$reqPembelianDetailId));

      $total_qty +=$tVal['qty'];
      $total_total +=$tVal['total'];

       $pdf->RowImage(array(
        // kolom tabel
            '',
            '' ,
            '' .$tVal['nama_kategori'],
            '' . $tVal['nama_alat'],
            '' . $tVal['no_seri'],
            '' ,
            '' ,
            '' .$arrCur[$value['currency']].'. '. currencyToPage2($tVal['harga']),
            '' . $tVal['qty'],
            '' .$arrCur[$value['currency']].'. '. currencyToPage2($tVal['total']),
            
           
        ));

        foreach ($arrFilterDetailAlat as $aVal) {
           $total_qty +=$aVal['qty'];
           $total_total +=$aVal['total'];
            $pdf->RowImage(array(
        // kolom tabel
            '',
            '' ,
            '' .$aVal['nama_kategori'],
            '' . $aVal['equip_name'],
            '' . $aVal['serial_number'],
            '' ,
            '' ,
            '' .$arrCur[$value['currency']].'. '. currencyToPage2($aVal['harga']),
            '' . $aVal['qty'],
            '' .$arrCur[$value['currency']].'. '. currencyToPage2($aVal['total']),
          
           
        ));
        }

     }
 $pdf->SetWidths(array(240, 36));
 $pdf->SetAligns(array('R','L'));
 $pdf->SetFont('Arial', 'B', 9);

 $reqPpn = $value["ppn"];
 $reqPpnPercent =$value["ppn_val"];
 $reqNameTotal = ' TOTAL';
 if( $reqPpn=='1'){
   $reqNameTotal='GRAND TOTAL';
 $reqTotal_persen =( $reqPpnPercent *$total_total )/100;
  $pdf->Row2(array(
                'TOTAL',
                $arrCur[$value['currency']].'. '.currencyToPage2($total_total)
            ));

  $total_total = $total_total+$reqTotal_persen;
   $pdf->Row2(array(
                'PPN ( '.$reqPpnPercent.'% )',
                $arrCur[$value['currency']].'. '.currencyToPage2($reqTotal_persen)
            ));
}
            $pdf->Row2(array(
                 $reqNameTotal,
                $arrCur[$value['currency']].'. '.currencyToPage2($total_total)
            ));
           $TOTAL[$value['currency']] +=$total_total;

}
        if($TOTAL['IDR'] > 0){
        $pdf->Row2(array(
                'GRAND TOTAL RP',
                $arrCur['IDR'].'. '.currencyToPage2($TOTAL['IDR'])
            ));
        }
        if($TOTAL['USD'] > 0){
         $pdf->Row2(array(
                'GRAND TOTAL USD',
                $arrCur['USD'].'. '.currencyToPage2($TOTAL['USD'])
            ));
       }
         if($TOTAL['EURO'] > 0){
          $pdf->Row2(array(
                'GRAND TOTAL EURO',
                $arrCur['EURO'].'. '.currencyToPage2($TOTAL['EURO'])
            ));
          }
           if($TOTAL['SGD'] > 0){
           $pdf->Row2(array(
                'GRAND TOTAL SGD',
                $arrCur['SGD'].'. '.currencyToPage2($TOTAL['SGD'])
            ));
         }


// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>