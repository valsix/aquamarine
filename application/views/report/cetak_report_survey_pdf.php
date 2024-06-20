<base href="<?= base_url(); ?>" />

<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

require('libraries/fpdf182/pdf.php');
// ECHO 'aRIK';exit();


        $statement_privacy = " ";
        $reqCariName                = $this->input->get('reqCariName');
        $reqCariNameClient          = $this->input->get('reqCariNameClient');
        $reqCariVesselClass         = $this->input->get('reqCariVesselClass');
        $reqCariVesselType         = $this->input->get('reqCariVesselType');
        $reqCariDateWork            = $this->input->get('reqCariDateWork');
        $reqCariDateComplate        = $this->input->get('reqCariDateComplate');
        $reqCariScopeOfWork         = $this->input->get('reqCariScopeOfWork');
        $reqCariLocation            = $this->input->get('reqCariLocation');
        $reqCariStatus              = $this->input->get('reqCariStatus');
         $reqCariSurveyor              = $this->input->get('reqCariSurveyor');
        $reqCariOperator              = $this->input->get('reqCariOperator');
       
        if(!empty($reqCariName )){
            $statement_privacy .=" AND (UPPER(A.NO_REPORT) LIKE '%" . strtoupper($reqCariName) . "%')" ;
        }
         if(!empty($reqCariNameClient )){
            $statement_privacy .=" AND (UPPER(A.NAME) LIKE '%" . strtoupper($reqCariNameClient) . "%')" ;
        }
        if(!empty($reqCariVesselClass )){
            $statement_privacy .=" AND (UPPER(A.CLASS_SOCIETY) = '" . strtoupper($reqCariVesselClass) . "')" ;
        }
        if(!empty($reqCariVesselType )){
            $statement_privacy .=" AND (UPPER(A.TYPE_OF_VESSEL) = '" . strtoupper($reqCariVesselType) . "')" ;
        }
        if(!empty($reqCariScopeOfWork )){
             $statement_privacy .= " AND EXISTS ( SELECT 1 FROM OFFER XX WHERE XX.NO_ORDER = A.NO_REPORT";
             $statement_privacy .= " AND UPPER(XX.GENERAL_SERVICE) = '" . strtoupper($reqCariScopeOfWork) . "' )" ;
        }
        if(!empty($reqCariLocation )){
            $statement_privacy .=" AND (UPPER(A.LOCATION) LIKE '%" . strtoupper($reqCariLocation) . "%')" ;
        }
          if(!empty($reqCariStatus )){
            $statement_privacy .=" AND (UPPER(A.STATUS) = '" . strtoupper($reqCariStatus) . "')" ;
        }

          if (!empty($reqCariDateWork) && !empty($reqCariDateWork)) {
            $statement_privacy .= " AND A.START_DATE BETWEEN TO_DATE('" . $reqCariDateWork . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariDateWork . "','dd-mm-yyyy')";
        }
         if(!empty($reqCariSurveyor )){
             // $statement_privacy .= " AND EXISTS ( SELECT 1 FROM COST_PROJECT XX WHERE XX.NO_PROJECT = A.NO_REPORT";
             // $statement_privacy .=" AND UPPER(XX.SURVEYOR) LIKE '%" . strtoupper($reqCariSurveyor) . "%' )" ;
             $statement_privacy .=" AND UPPER(A.COST_SURYEVOR) LIKE '%" . strtoupper($reqCariSurveyor) . "%')" ;
        }
        if(!empty($reqCariOperator )){
             // $statement_privacy .= " AND EXISTS ( SELECT 1 FROM COST_PROJECT XX WHERE XX.NO_PROJECT = A.NO_REPORT";
             // $statement_privacy .=" AND UPPER(XX.OPERATOR) LIKE '%" . strtoupper($reqCariOperator) . "%' )" ;
            $statement_privacy .=" AND UPPER(A.COST_OPERATOR) LIKE '%" . strtoupper($reqCariOperator) . "%')" ;
        }

        $reqSort =  $_SESSION[$this->input->get("pg")."reqSort"];
        $reqStatement =  $_SESSION[$this->input->get("pg")."reqStatement"];
	//	echo $reqStatement;exit;
$this->load->model("Report");
$report_survey = new Report();

$aColumns = array(
    "NO",
    "NAME", "COMPANY_NAME", "NAME_OF_VESSEL", "TYPE_OF_VESSEL", "SCOPE_OF_WORK", "LOCATION", "DATE_WORK", "DATE_DELIVERY_REPORT","NOTE"

);
$aColumnsAlias = array(
    "NO",
    "NO_REPORT", "COMPANY_NAME", "NAME_OF_VESSEL", "TYPE_OF_VESSEL", "SCOPE_OF_WORK", "LOCATION", "DATE_WORK", "DATE_DELIVERY_REPORT","NOTE"

);



$pdf = new PDF();
ob_end_clean();
$pdf->AliasNbPages();

// ECHO $pdf->w;exit;
$pdf->AddPage('L', 'A4');
$panjang = (($pdf->w * 91) / 100);
// ECHO $pdf->w;exit;
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell($panjang, 10, 'SURVEY REPORT', 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 7);

// exit;
$panjang_tabel = 10;
$arrPanjang = array();
for ($i = 0; $i < 9; $i++) {
    if ($i != 0) {
        $panjang_tabel = 33;
    }
    $pdf->Cell($panjang_tabel, 10, str_replace('_', ' ', $aColumnsAlias[$i]), 1, 0, 'C');
    array_push($arrPanjang, $panjang_tabel);
}
$pdf->Ln();
// print_r($arrPanjang);exit;
$report_survey->selectByParams(array(), -1, -1, $statement . $statement_privacy.' '.$reqStatement,$reqSort);
// echo $report_survey->query;
// exit;
$pdf->SetFont('Arial', '', 8);
$pdf->SetWidths(array(10, 33, 33, 33, 33, 33, 33, 33, 33, 33));
$no = 1;
while ($report_survey->nextRow()) {
    // $date1 =  $report_survey->getField('DATE1');
    // $date2 =  $report_survey->getField('DATE2');
    // $date_val =
    //     $pdf->Row(array(
    //         $no,
    //         '' . $report_survey->getField($aColumns[1]),
    //         '' . $report_survey->getField($aColumns[2]),
    //         '' . $report_survey->getField($aColumns[3]),
    //         '' . $report_survey->getField($aColumns[4]),
    //         '' . getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2),
    //         '' . $report_survey->getField($aColumns[6]),

    //     ));

    $pdf->Row(array(
        // kolom tabel
        $report_survey->getField('URUT'),
        '' . $report_survey->getField('NO_REPORT'),
        '' . $report_survey->getField($aColumns[1]),
        '' . $report_survey->getField($aColumns[3]),
        '' . $report_survey->getField($aColumns[4]),
        '' . $report_survey->getField($aColumns[5]),
        '' . $report_survey->getField($aColumns[6]),
        '' . $report_survey->getField('START_DATE'),
        '' . $report_survey->getField('DELIVERY_DATE')

    ));

    // $pdf->MultiCell($panjang - 2.2, 5, 'DATE WORK    : ' . $report_survey->getField('START_DATE'), 1);
    //  $pdf->MultiCell($panjang - 2.2, 5, 'DATE DELIVERY REPORT  : ' . $report_survey->getField('DELIVERY_DATE'), 1);
   
    // $pdf->MultiCell($panjang - 2.2, 5, 'COMPANY NAME : ' . "\t" . $report_survey->getField($aColumns[7]), 1, 'J', 0, 10);
    // $pdf->MultiCell($panjang - 2.2, 5, 'PPN : ' . currencyToPage2($report_survey->getField('PPN')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'STATUS : ' . currencyToPage2($report_survey->getField('STATUS')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'TOTAL AMOUNT: ' . currencyToPage2($report_survey->getField('TOTAL_AMOUNT')), 1);
    // $pdf->MultiCell($panjang - 2.2, 5, 'INVOICE DATE : ' . $report_survey->getField('INVOICE_DATE'), 1);
    $no++;
}
// $pdf->Cell(60,10,'NO PROJECT',1,0,'C');
// $pdf->Cell(60,10,'VESSEL NAME',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');
// $pdf->Cell(60,10,'TYPE OF VESSEL',1,0,'C');


ob_end_clean();
$pdf->Output();

?>