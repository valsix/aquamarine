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


$this->load->model("MaterialInvoice");
$this->load->model("MaterialInvoiceDetail");

$material_invoice_detil = new MaterialInvoiceDetail();
$reqId = $this->input->get("reqId");

$material_invoice = new MaterialInvoice();
$arrayParams = array("A.MATERIAL_INVOICE_ID"=>"0");
if($reqId != ""){
    $arrayParams = array("A.MATERIAL_INVOICE_ID"=>$reqId);
}
$material_invoice->selectByParams($arrayParams);
$material_invoice->firstRow();
$tahun = $material_invoice->getField("TAHUN");

$material_invoice_detil = new MaterialInvoiceDetail();
$sOrder = "ORDER BY A.TANGGAL ASC, A.MATERIAL_INVOICE_DETAIL_ID ASC";
$material_invoice_detil->selectByParamsMonitoring($arrayParams,-1,-1,'',$sOrder);

$no=0;
$arrData = array();


$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
$pdf->AddFont('Calibri','','Calibri.php');
$pdf->AddFont('Calibri Bold','B','Calibri Bold.php');


$pdf->SetFont('Calibri Bold', 'B', 15);
$pdf->Cell($panjang, 10, 'MATERIAL PEMBELIAN DAN INVOICE PEMBAYARAN', 0, 0, 'C');
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
$pdf->Cell((($pdf->w * 93) / 100), 5, "Tahun ".$tahun, '', 0, 'R');
$pdf->SetWidths(
    array(
        ($pdf->w * 4) / 100, 
        ($pdf->w * 9) / 100, 
        ($pdf->w * 13) / 100, 
        ($pdf->w * 9) / 100, 
        ($pdf->w * 9) / 100, 
        ($pdf->w * 13) / 100, 
        ($pdf->w * 9) / 100,
        ($pdf->w * 15) / 100,
        ($pdf->w * 12) / 100
    )
);
$pdf->Ln();
$pdf->RowLeft(array(
    'No.&&C',
    'Tanggal&&C',
    'Form Pembelian&&C',
    'Tanggal Pembelian&&C',
    'Tanggal Terima&&C',
    'Diterima Oleh&&C',
    'Tanggal Pembayaran&&C',
    'Invoice / Nota&&C',
    'Nilai Invoice / Nota&&C'
), 7);

$pdf->SetFont('Calibri', '', 13);

$no = 1;
while ( $material_invoice_detil->nextRow()) {
    $pdf->RowLeft(array(
        $no,
        $material_invoice_detil->getField("TANGGAL").'&&C',
        $material_invoice_detil->getField("PATH_PEMBELIAN"),
        $material_invoice_detil->getField("TANGGAL_PEMBELIAN").'&&C',
        $material_invoice_detil->getField("TANGGAL_TERIMA").'&&C',
        $material_invoice_detil->getField("DITERIMA_OLEH"),
        $material_invoice_detil->getField("TANGGAL_PEMBAYARAN").'&&C',
        $material_invoice_detil->getField("PATH_INVOICE"),
        currencyToPage2($material_invoice_detil->getField("NILAI_INVOICE")).'&&R'
    ), 7);
    $no++;
}
ob_end_clean();
$pdf->Output();

?>