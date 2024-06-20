<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");


include_once("libraries/TCPDF/tcpdf.php");


$reqId = $this->input->get("reqId");



class MYPDF extends TCPDF {
  
  protected $last_page_flag = false;

  public function Close() {
    $this->last_page_flag = true;
    parent::Close();
  }
    //Page header
    public function Header() {
        $this->writeHTMLCell(
            $w = 0, $h = 0, $x = '', $y = '',
            '
            <div style="text-align: center;">
                <img src="images/logo.png" style="height: 70px;">
            </div>', $border = 0, $ln = 1, $fill = 0,
            $reseth = true, $align = 'top', $autopadding = true);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-25);
        if ($this->last_page_flag) {}
        else
        {
            $this->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0));
            $this->Cell(0,10,'','T','',0,0);
        }
        $this->SetY(-25);
        $this->writeHTMLCell(
        $w = 0, $h = 0, $x = '', $y = '',
            '
           
            ', $border = 0, $ln = 1, $fill = 0,
            $reseth = true, $align = 'top', $autopadding = true);
        $this->SetY(-10);
        $this->Cell(0, 0,  'halaman '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, $ln=0, 'R', 0, '', 0, false, 'B', 'B');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
// $pdf->SetCreator(PDF_CREATOR);
// $pdf->SetAuthor('RIMA - PT Terminal Teluk Lamong');
// $pdf->SetTitle('REVIEW PENYUSUNAN IDENTIFIKASI '.$reqNama);
// $pdf->SetSubject('Review Penyusunan Identifikasi');
// $pdf->SetKeywords('Review Penyusunan Identifikasi');


// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 35, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
// set font

// add a page
$pdf->AddPage('P', 'A4');
ob_end_clean();
// exit;

$html = file_get_contents($this->config->item('base_report')."report/index/report_cetak_company/?reqId=".$reqId);

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------
ob_end_clean();
//Close and output PDF document
$pdf->Output('identifikasi_resiko'.date("dmyhh24mi").'.pdf', 'I');

exit;
//==============================================================
//==============================================================
//==============================================================
?>

