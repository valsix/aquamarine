<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf2.php');

$reqId = $this->input->get('reqId');


$this->load->model("CashSaldoDetail");
$this->load->model("Bank");
$cash_saldo_detail = new CashSaldoDetail();
$this->load->model("CastSaldo");


$this->load->model("Bank");
$bank = new Bank(); 
$bank->selectByParamsMonitoring(array(),-1,-1,'',"ORDER BY NAMA ASC");
$aColumns = array("NO","URAIAN");

while ($bank->nextRow()) {
	array_push($aColumns, $bank->getField('NAMA'));
}


$cast_saldo = new CastSaldo();
$cast_saldo->selectByParamsMonitoring(array("A.CAST_SALDO_ID"=>$reqId));
$cast_saldo->firstRow();
$tgl=$cast_saldo->getField('TANGGAL');
$tgl = explode('-', $tgl);
$bulan = getSelectFormattedDate($tgl[1]);
$tahun = $tgl[2];


$pdf = new PDF2();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'POSISI SALDO DAN PEMBAYARAN ', 0, 0, 'C');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($panjang, 10, 'Bulan : '.$bulan.' Tahun : '.$tahun, 0, 0, 'C');

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);



$panjang_tabel = 10;
$arrPanjang = array();
$header = array();
for ($i = 0; $i < count($aColumns); $i++) {
    if ($i != 0) {
        if($i % 2 == 1)
        	$panjang_tabel = $panjang/(count($aColumns))-4;
        else
        	$panjang_tabel = $panjang/(count($aColumns))+5;

        // echo 
    }
    if ($i == 1) {
        $panjang_tabel = 50;
    }
    $header[] =  str_replace('_', ' ', $aColumns[$i].'&&C');
    // $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}

$pdf->SetWidths($arrPanjang);
$pdf->RowLeft($header, 5);

$cash_saldo_detail->selectByParamsMonitoring(array("A.CASH_SALDO_ID"=>$reqId));

	

$no = 1;
$index = 0;
$arrDataVal=array();
$arrDataTotalPerBank=array();
$arrDataTotalGrand=array();
while ($cash_saldo_detail->nextRow()) {
	$row = array();
	$bank = new Bank(); 
	$bank->selectByParamsMonitoring(array("A.BANK_ID"=>$cash_saldo_detail->getField('BANK_ID')));
	$bank->firstRow();
	$bank_colomn = $bank->getField("NAMA");
	$curent = $cash_saldo_detail->getField("CURENCY");
	for ($i = 0; $i < count($aColumns); $i++) {
		if ($aColumns[$i] ==  $bank_colomn){
			// $text_tambahan = "";
			if($curent==1){
				$arrDataTotalGrand['IDR'] =($arrDataTotalGrand['IDR'])+dotToNo($cash_saldo_detail->getField('AMOUNT'));
				// $text_tambahan='';
			}else{
				$arrDataTotalGrand['USD'] = ($arrDataTotalGrand['USD'])+dotToNo($cash_saldo_detail->getField('AMOUNT'));
			}
			$texts =$text_tambahan.' '.currencyToPage2($cash_saldo_detail->getField('AMOUNT')).'&&R';
			$row[] =$texts;
			$arrDataTotalPerBank[$i]=dotToNo($arrDataTotalPerBank[$i])+$cash_saldo_detail->getField('AMOUNT');
		}if ($aColumns[$i] ==  "NO"){
			
			$row[] =$no ;
		}else{
			$row[] =$cash_saldo_detail->getField($aColumns[$i]);
		}
	}
	$arrDataVal['aaData'][] = $row;


	$no++;
	$index++;
}

$arrDataTotalGrand['IDR'] = $cash_saldo_detail->getTotal(array("A.CASH_SALDO_ID"=>$reqId, "CURENCY" => "1"));
$arrDataTotalGrand['USD'] = $cash_saldo_detail->getTotal(array("A.CASH_SALDO_ID"=>$reqId, "CURENCY" => "0"));

$ROWS =$arrDataVal['aaData'];
for($i=0;$i<count($ROWS);$i++){
	$pdf->RowLeft($ROWS[$i], 7);
}
$total10 = (10-count($ROWS));
// $arrKosong =array();
// for($i=0;$i<=$total10;$i++){
// 	$row = array();
// 	for ($j = 0; $j < count($aColumns); $j++) {
// 		$row[]='';
// 	}
// 	$arrKosong['aaData'][] = $row;
// 	$pdf->Row($row);
// }

$pdf->SetFont('Arial', 'B', 11);
$arrPanjang2 =array(); 
$arrtext =array(); 
array_push($arrPanjang2, 60);
array_push($arrtext,"SUB TOTAL : &&C");
for($i=2;$i<count($arrPanjang);$i++){
array_push($arrPanjang2, $arrPanjang[$i]);
array_push($arrtext, currencyToPage2($arrDataTotalPerBank[$i]));
}
// print_r($arrtext);exit;
$pdf->SetWidths($arrPanjang2);
$pdf->RowRight($arrtext, 7);

// $pdf->MultiCell($panjang - 4.5, 5, 'GRAND TOTAL IDR : '."           "."Rp. ".currencyToPage2($arrDataTotalGrand['IDR']), 1, 'R', 0, 10);

// // $pdf->MultiCell($panjang - 4.5, 5, 'Terbilang : '.terbilang($arrDataTotalGrand['IDR']).' Rupiah', 1, 'J', 0, 10);
// // $pdf->MultiCell($panjang - 4.5, 5, 'GRAND TOTAL USD : '."          "."US. ".currencyToPage2($arrDataTotalGrand['USD']), 1, 'R', 0, 10);
// $pdf->MultiCell($panjang - 4.5, 5, 'GRAND TOTAL USD : '."          "."US. ".currencyToPage2($arrDataTotalGrand['USD']), 1, 'R', 0, 10);

$y = $pdf->GetY();
//y = 135.00125
//x = 10.00125
$x = $pdf->GetX();

//echo $x;exit;
$width = 61;
$pdf->MultiCell($width, 6, 'GRAND TOTAL IDR :', 'BTL', 'L', FALSE);
$pdf->MultiCell($width, 6, 'GRAND TOTAL USD :', 'BTL', 'L', FALSE);

$pdf->SetXY($x + $width, $y);
$pdf->Cell((($pdf->w * 91) / 100) - 53,6, "Rp. ".currencyToPage2($arrDataTotalGrand['IDR']), 'BTR',0, "R");



// $y2 = 131;
// $x2 =10.00125;
// $width = 63;
$pdf->SetXY($x + $width, $y+6);
$pdf->Cell((($pdf->w * 91) / 100) - 53,6, "US. ".currencyToPage2($arrDataTotalGrand['USD']), 'BTR', 0, "R");



// $pdf->Cell(53.5, 2.7, 'A', 0, 1, 'L');
// $arrIdr =array(); 
// array_push($arrPanjang2, 63);
// array_push($arrIdr,"GRAND TOTAL IDR : &&L");
// array_push($arrIdr,"");
// array_push($arrIdr,"");
// array_push($arrIdr,"");
// array_push($arrIdr,"");
// array_push($arrIdr,"");
// array_push($arrIdr,"Rp. ".currencyToPage2($arrDataTotalGrand['IDR']));
// $pdf->RowRight($arrIdr);

// $pdf->MultiCell($panjang - 4.5, 5, 'In number : '.kekata_eng($arrDataTotalGrand['USD']), 1, 'J', 0, 10);




ob_end_clean();
$pdf->Output();

?>