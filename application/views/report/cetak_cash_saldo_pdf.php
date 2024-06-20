<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');

$reqCariPeriodeYearFrom     =  $this->input->get('reqCariPeriodeYearFrom');
$reqCariPeriodeYearTo       =  $this->input->get('reqCariPeriodeYearTo');

if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
    $statement_privacy = " AND A.TANGGAL BETWEEN TO_DATE('" . $reqCariPeriodeYearFrom . "', 'yyyy-MM-dd') AND TO_DATE('" . $reqCariPeriodeYearTo . "', 'yyyy-MM-dd')";
}

$this->load->model("CastSaldo");
$cash_saldo = new CastSaldo();
$this->load->model("CashSaldoDetail");
$cash_saldo_detail = new CashSaldoDetail();

$aColumns = array(
    "NO",
    "TANGGAL", "KETERANGAN","AMOUNT_IDR","AMOUNT_USD"

);

$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'CASH SALDO REPORT', 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < 5; $i++) {
    if ($i != 0) {
        $panjang_tabel = 64;
    }
    $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
// print_r($arrPanjang);exit;
$cash_saldo->selectByParamsMonitoringCetakPdf(array(), -1, -1, $statement . $statement_privacy);
// echo $cash_saldo->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 64, 64, 64, 64, 64, 64, 64));
$no = 1;
while ($cash_saldo->nextRow()) {
    $ids = $cash_saldo->getField("CAST_SALDO_ID");
  $cash_saldo_detail = new CashSaldoDetail();
  $cash_saldo_detail->selectByParamsMonitoring(array("A.CASH_SALDO_ID"=> $ids,"A.CURENCY"=>'1'));
  $total_amount_idr=0;
  while ($cash_saldo_detail->nextRow()) {
    $total_amount_idr +=$cash_saldo_detail->getField('AMOUNT');
  }

  $cash_saldo_detail = new CashSaldoDetail();
  $cash_saldo_detail->selectByParamsMonitoring(array("A.CASH_SALDO_ID"=> $ids,"A.CURENCY"=>'0'));
  $total_amount_usd=0;
  while ($cash_saldo_detail->nextRow()) {
    $total_amount_usd +=$cash_saldo_detail->getField('AMOUNT');
  }


    // $date1 =  $cash_saldo->getField('DATE1');
    // $date2 =  $cash_saldo->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $cash_saldo->getField($aColumns[1]),
    //         '' . $cash_saldo->getField($aColumns[2]),
    //         '' . $cash_saldo->getField($aColumns[3]),
    //         '' . $cash_saldo->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $cash_saldo->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $no,
        '' . $cash_saldo->getField($aColumns[1]),
        '' . $cash_saldo->getField($aColumns[2]),
        currencyToPage($total_amount_idr),
        currencyToPage($total_amount_usd)

    ));

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $cash_saldo->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'VESSEL NAME : ' . $cash_saldo->getField('VESSEL_NAME'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($cash_saldo->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($cash_saldo->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($cash_saldo->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $cash_saldo->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>