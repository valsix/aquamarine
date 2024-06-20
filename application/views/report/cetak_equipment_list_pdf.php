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

        $reqCariIdNumber                 = $this->input->get("reqCariIdNumber");
        $reqCariCondition                = $this->input->get("reqCariCondition");
        $reqCariCategori                 = $this->input->get("reqCariCategori");
        $reqCariStorage                  = $this->input->get("reqCariStorage");
        $reqCariCompanyName              = $this->input->get("reqCariCompanyName");
        $reqCariIncomingDateFrom         = $this->input->get("reqCariIncomingDateFrom");
        $reqCariIncomingDateTo           = $this->input->get("reqCariIncomingDateTo");
        $reqCariItemFrom                 = $this->input->get("reqCariItemFrom");
        $reqCariItemTo                   = $this->input->get("reqCariItemTo");
        $reqCariLastCalibrationFrom      = $this->input->get("reqCariLastCalibrationFrom");
        $reqCariLastCalibrationTo        = $this->input->get("reqCariLastCalibrationTo");
        $reqCariQuantity                 = $this->input->get("reqCariQuantity");
        $reqCariNextCalibrationFrom      = $this->input->get("reqCariNextCalibrationFrom");
        $reqCariNextCalibrationTo        = $this->input->get("reqCariNextCalibrationTo");
        $reqCariSpesification            = $this->input->get("reqCariSpesification");


        $statement_privacy = '';
        if (!empty($reqCariIdNumber)) {

            $statement_privacy  .= " AND UPPER(A.EQUIP_ID) = '" . strtoupper($reqCariIdNumber) . "' ";
        }
        if (!empty($reqCariCondition)) {

            $statement_privacy  .= " AND UPPER(A.EQUIP_CONDITION) LIKE '%" .  strtoupper($reqCariCondition) . "%' ";
        }
        if (!empty($reqCariCategori)) {

            $statement_privacy  .= " AND UPPER(B.EC_NAME) ='" .  strtoupper($reqCariCategori) . "' ";
        }
        if (!empty($reqCariStorage)) {

            $statement_privacy  .= " AND UPPER(A.EQUIP_STORAGE) LIKE '%" .  strtoupper($reqCariStorage) . "%' ";
        }
      
        if (!empty($reqCariCompanyName)) {

            $statement_privacy  .= " AND UPPER(A.EQUIP_NAME) LIKE '%" .  strtoupper($reqCariCompanyName) . "%' ";
        }
        if (!empty($reqCariIncomingDateFrom) && !empty($reqCariIncomingDateTo)) {

            $statement_privacy  .= " AND A.EQUIP_DATEIN BETWEEN  TO_DATE(" . $reqCariIncomingDateFrom . ",'dd-mm-yyyy')  AND TO_DATE(" . $reqCariIncomingDateFrom . ",'dd-mm-yyyy') ";
        }

        if (!empty($reqCariItemFrom) && !empty($reqCariItemTo)) {


            $statement_privacy  .= " AND A.EQUIP_QTY BETWEEN " . $reqCariItemFrom . " AND " . $reqCariItemTo;
        }

        if (!empty($reqCariLastCalibrationFrom) && !empty($reqCariLastCalibrationTo)) {

            $statement_privacy  .= " AND A.EQUIP_LASTCAL BETWEEN TO_DATE(" . $reqCariLastCalibrationFrom . ",'dd-mm-yyyy') AND TO_DATE(" . $reqCariLastCalibrationTo . ",'dd-mm-yyyy') ";
        }

        if (!empty($reqCariQuantity)) {

            $statement_privacy  .= " AND A.EQUIP_ITEM = '" . $reqCariQuantity . "' ";
        }
        if (!empty($reqCariNextCalibrationFrom) && !empty($reqCariNextCalibrationTo)) {
            $statement_privacy  .= " AND A.EQUIP_NEXTCAL BETWEEN TO_DATE(" . $reqCariNextCalibrationFrom . ",'dd-mm-yyyy') AND TO_DATE(" . $reqCariNextCalibrationTo . ",'dd-mm-yyyy') ";
        }

        if (!empty($reqCariSpesification)) {

            $statement_privacy  .= " AND UPPER(A.SERIAL_NUMBER) LIKE '%" .  strtoupper($reqCariSpesification) . "%' ";
        }

        $statement_privacy =$_SESSION["reqCariSessionEquip"];

$this->load->model("EquipmentList");
$equipment_list = new EquipmentList();

$aColumns = array(
    "NO", "ID", "CATEGORY", "PICTURE", "EQUIPMENT", "ID_EQUIPMENT", "SERIAL_NO", "QTY", "ITEM",
    "INCOMING_DATE", "CONDITION","STORAGE", "REMARKS"
);
$aColumnsAlias = array(
    "NO", "EQUIP_ID", "CATEGORY", "PIC_PATH", "EQUIP_NAME", "BARCODE", "SERIAL_NUMBER", "QUANTITY", "ITEM",
    "INCOMING_DATE", "CONDITION", "STORAGE", "REMARKS"
);

$pdf = new PDF();
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
for ($i = 0; $i < count($aColumns); $i++) {
    if ($i == 0 || $i == 1 || $i == 7 || $i == 8) {
        $panjang_tabel = 9;
    }else if ($i == 2 || $i == 3 || $i == 9) {
        $panjang_tabel = 30;
    }else if ($i == 4 || $i == 5) {
        $panjang_tabel = 35;
    }else{
        $panjang_tabel = 20;
    }
    $pdf->SetFillColor(202, 202, 202);
    $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumns[$i]), 1, 0, 'C','true');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
// print_r($arrPanjang);exit;
$equipment_list->selectByParamsMonitoringEquipmentProdCetakPdf(array(), -1, -1, $statement . $statement_privacy);
 // echo $equipment_list->query;exit;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(9, 9, 30, 30, 35, 35, 20, 9, 9, 30, 20, 20, 20));
$no = 1;
$pdf->setImageKey([3]);
while ($equipment_list->nextRow()) {
    // $date1 =  $equipment_list->getField('DATE1');
    // $date2 =  $equipment_list->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $equipment_list->getField($aColumns[1]),
    //         '' . $equipment_list->getField($aColumns[2]),
    //         '' . $equipment_list->getField($aColumns[3]),
    //         '' . $equipment_list->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $equipment_list->getField($aColumns[6]),

    //     ));
    $image = 'uploads/equipment/'.$equipment_list->getField($aColumnsAlias[3]);
    // echo $equipment_list->getField($aColumnsAlias[1]);exit;
    // if (file_exists($image)) {
        if($equipment_list->getField($aColumnsAlias[3]) == "" || !file_exists($image))
            $image = 'uploads/no-image.png';

         // $image = 'uploads/no-image.png';
            
          // $image3 = 'uploads/no-image.png';
    //  $images =$pdf->Image($image,10,70,0,90);
        $imagexx = filesize($image);
        if($imagexx > 1500000){
             $image = 'images/big_images.jpg';
        }

        $pdf->RowImage(array(
        // kolom tabel
            $no,
            '' . $equipment_list->getField($aColumnsAlias[1]),
            '' . $equipment_list->getField($aColumnsAlias[2]),
            '' . $image,
            '' . $equipment_list->getField($aColumnsAlias[4]),
            '' . $equipment_list->getField($aColumnsAlias[5]),
            '' . $equipment_list->getField($aColumnsAlias[6]),
            '' . $equipment_list->getField($aColumnsAlias[7]),
            '' . $equipment_list->getField($aColumnsAlias[8]),
            '' . $equipment_list->getField($aColumnsAlias[9]),
            '' . $equipment_list->getField($aColumnsAlias[10]),
            '' . $equipment_list->getField($aColumnsAlias[11]),
            '' . $equipment_list->getField($aColumnsAlias[12]),
            '' . $equipment_list->getField($aColumnsAlias[13])
        ));
    // }

    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $equipment_list->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'ITEM : ' . $equipment_list->getField('ITEM'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INCOMING DATE : ' . currencyToPage2($equipment_list->getField('INCOMING_DATE')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'LAST CALIBRATION : ' . currencyToPage2($equipment_list->getField('LAST_CALIBRATION')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'NEXT CALIBRATION: ' . currencyToPage2($equipment_list->getField('NEXT_CALIBRATION')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'QTY DETAIL EQUIPMENT : ' . $equipment_list->getField('QTY_DETAIL_EQUIPMENT'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'CONDITION : ' . $equipment_list->getField('CONDITION'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STORAGE : ' . $equipment_list->getField('STORAGE'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PRICE : ' . $equipment_list->getField('PRICE'), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'REMARKS : ' . $equipment_list->getField('REMARKS'), 1);
    $no++;
}

// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>