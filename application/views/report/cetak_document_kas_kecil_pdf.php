<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf-kas.php');

function hapus_nol($value)
{
    if(trim($value) == "0,00"){
        $value = "";
    }
    return $value;
}

$reqCariFind = $this->input->post('reqCariFind');
$reqCariFindMenthod = $this->input->post('reqCariFindMenthod');


$this->load->model("KasKecil");
$this->load->model("KasKecilDetail");
$this->load->model("KategoriCash");
$kategori_cash = new KategoriCash();
$kas_kecil_detil = new KasKecilDetail();
$reqId = $this->input->get("reqId");

$kas_kecil = new KasKecil();
$kas_kecil->selectByParams(array("A.KAS_KECIL_ID"=>$reqId));
$kas_kecil->firstRow();
$tanggal = $kas_kecil->getField("TANGGAL");
$tanggal = explode('-', $tanggal);

$kas_kecil_detil = new KasKecilDetail();
$sOrder = "ORDER BY A.TANGGAL ASC, A.KAS_KECIL_DETAIL_ID ASC";
$kas_kecil_detil->selectByParamsMonitoring(array("A.KAS_KECIL_ID"=>$reqId),-1,-1,'',$sOrder);

$no=0;
$arrData = array();
while ( $kas_kecil_detil->nextRow()) {
   
    $arrData[$no]["TANGGAL"]=$kas_kecil_detil->getField("TANGGAL");
    $arrData[$no]["KETERANGAN"]=$kas_kecil_detil->getField("KETERANGAN");
    $arrData[$no]["KATEGORI"]=$kas_kecil_detil->getField("KATEGORI");
    $arrData[$no]["KREDIT"]=$kas_kecil_detil->getField("KREDIT");
    $arrData[$no]["DEBET"]=$kas_kecil_detil->getField("DEBET");
    $arrData[$no]["KREDIT_USD"]=$kas_kecil_detil->getField("KREDIT_USD");
    $arrData[$no]["DEBET_USD"]=$kas_kecil_detil->getField("DEBET_USD");
    $arrData[$no]["SALDO"]=$kas_kecil_detil->getField("SALDO");
    $arrData[$no]["SALDO_USD"]=$kas_kecil_detil->getField("SALDO_USD");
    $no++;
}

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
$pdf->AddFont('Calibri','','Calibri.php');
$pdf->AddFont('Calibri Bold','B','Calibri Bold.php');


$pdf->SetFont('Calibri Bold', 'B', 15);
$pdf->Cell($panjang, 10, 'SALDO KAS KECIL (IDR)', 0, 0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Calibri Bold', 'B', 13);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < 7; $i++) {
    if ($i != 0) {
        $panjang_tabel = 30;
    }else{
        $panjang_tabel=10;
    }
    // $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
$pdf->Cell((($pdf->w * 93) / 100), 5, "KAS ".getNameMonth((int)$tanggal[1]).' '.$tanggal[2], '', 0, 'R');

$pdf->Ln();
$pdf->Cell((($pdf->w * 8) / 100), 7, 'Tanggal', 1, 0, 'C');
$pdf->Cell((($pdf->w * 22) / 100), 7, 'Keterangan', 1, 0, 'C');
$pdf->Cell((($pdf->w * 18) / 100), 7, 'Kategori', 1, 0, 'C');
$pdf->Cell((($pdf->w * 15) / 100), 7, 'Kredit(+)', 1, 0, 'C');
$pdf->Cell((($pdf->w * 15) / 100), 7, 'Debet(-)', 1, 0, 'C');
$pdf->Cell((($pdf->w * 15) / 100), 7, 'Saldo', 1, 0, 'C');
$pdf->ln();

$pdf->SetFont('Calibri', '', 13);

$TOTAL_IDR_KREDIT=0;
$TOTAL_USD_KREDIT=0;
$TOTAL_IDR_DEBET=0;
$TOTAL_USD_DEBET=0;
$nomer_footer =1;
for($i=0;$i<count($arrData);$i++){
    $pdf->SetWidths(
        array(
            ($pdf->w * 8) / 100, 
            ($pdf->w * 22) / 100, 
            ($pdf->w * 18) / 100, 
            ($pdf->w * 15) / 100, 
            ($pdf->w * 15) / 100, 
            ($pdf->w * 15) / 100
        )
    );

    $nomers = 1+$i;
    $TOTAL_IDR_KREDIT +=$arrData[$i]['KREDIT'];
    $TOTAL_IDR_DEBET +=$arrData[$i]['DEBET'];
    $TOTAL_USD_KREDIT +=$arrData[$i]['KREDIT_USD'];
    $TOTAL_USD_DEBET +=$arrData[$i]['DEBET_USD'];

    $kreditIdr = hapus_nol(currencyToPage2($arrData[$i]['KREDIT']));
    $kreditUsd = hapus_nol(currencyToPage2($arrData[$i]['KREDIT_USD']));
    $debitIdr = hapus_nol(currencyToPage2($arrData[$i]['DEBET']));
    $debitUsd = hapus_nol(currencyToPage2($arrData[$i]['DEBET_USD']));
    $saldoIdr = hapus_nol(currencyToPage2($arrData[$i]['SALDO']));
    $saldoUsd = hapus_nol(currencyToPage2($arrData[$i]['SALDO_USD']));

    $pdf->RowLeft(array(
        $arrData[$i]['TANGGAL']  ,
        $arrData[$i]['KETERANGAN'] ,
        $arrData[$i]['KATEGORI'] ,
        $kreditIdr.'&&R' ,
        $debitIdr.'&&R' ,
        $saldoIdr.'&&R' 
    ), 7);

} 
$TOTAL_IDR_SALDO = $TOTAL_IDR_KREDIT - $TOTAL_IDR_DEBET;
$TOTAL_USD_SALDO = $TOTAL_USD_KREDIT - $TOTAL_USD_DEBET;

$pdf->Row(array('',''  ,'' , '' ,'' ,'' , '' , '', '' ));
$pdf->SetFont('Calibri Bold', 'B', 13);
$pdf->Cell((($pdf->w * 48) / 100), 7, 'TOTAL', 1, 0, 'C');
$pdf->Cell((($pdf->w * 15) / 100), 7, hapus_nol(currencyToPage2($TOTAL_IDR_KREDIT)), 1, 0, 'R');
$pdf->Cell((($pdf->w * 15) / 100), 7, hapus_nol(currencyToPage2($TOTAL_IDR_DEBET)), 1, 0, 'R');
$pdf->Cell((($pdf->w * 15) / 100), 7, hapus_nol(currencyToPage2($TOTAL_IDR_SALDO)), 1, 0, 'R');

ob_end_clean();
$pdf->Output();

?>