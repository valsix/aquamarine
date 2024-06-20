<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class dokument_report_json extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->kauth->getInstance()->hasIdentity()) {
            redirect('login');
        }

        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");
        $this->USERID = $this->kauth->getInstance()->getIdentity()->USERID;
        $this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
        $this->FULLNAME = $this->kauth->getInstance()->getIdentity()->FULLNAME;
        $this->USERPASS = $this->kauth->getInstance()->getIdentity()->USERPASS;
        $this->LEVEL = $this->kauth->getInstance()->getIdentity()->LEVEL;
        $this->MENUMARKETING = $this->kauth->getInstance()->getIdentity()->MENUMARKETING;
        $this->MENUFINANCE = $this->kauth->getInstance()->getIdentity()->MENUFINANCE;
        $this->MENUPRODUCTION = $this->kauth->getInstance()->getIdentity()->MENUPRODUCTION;
        $this->MENUDOCUMENT = $this->kauth->getInstance()->getIdentity()->MENUDOCUMENT;
        $this->MENUSEARCH = $this->kauth->getInstance()->getIdentity()->MENUSEARCH;
        $this->MENUOTHERS = $this->kauth->getInstance()->getIdentity()->MENUOTHERS;
    }


    function add()
    {

        $this->load->model("DokumenReport");
        $dokumen_report = new DokumenReport();

        $reqId = $this->input->post("reqId");

        $this->load->library("FileHandler");
        $file = new FileHandler();
        $filesData = $_FILES["document"];
        $reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
        $file->cekSize($filesData,$reqLinkFileTemp);


        // $reqDocumentId = $this->input->post("reqDocumentId");
        // $reqReportId = $this->input->post("reqReportId");
        // $reqName = $this->input->post("reqName");
        // $reqDescription = $this->input->post("reqDescription");
        // $reqPath = $this->input->post("reqPath");
        // $reqStartDate = $this->input->post("reqStartDate");
        // $reqFinishDate = $this->input->post("reqFinishDate");
        // $reqDeliveryDate = $this->input->post("reqDeliveryDate");
        // $reqInvoiceDate = $this->input->post("reqInvoiceDate");
        // $reqReason = $this->input->post("reqReason");

        $reqDocumentId = $this->input->post("reqDocumentId");
        $reqReportId = $this->input->post("reqReportId");
        $reqName = $this->input->post("reqName");
        $reqDescription = $this->input->post("reqDescription");
        $reqPath = $this->input->post("reqPath");
        $reqStartDate = $this->input->post("reqStartDate");
        $reqFinishDate = $this->input->post("reqFinishDate");
        $reqDeliveryDate = $this->input->post("reqDeliveryDate");
        $reqLastRevisi = $this->input->post("reqLastRevisi");
        $reqInvoiceDate = $this->input->post("reqInvoiceDate");
        $reqReason = $this->input->post("reqReason");
        $reqNoReport = $this->input->post("reqNoReport");
        $reqNameOfVessel = $this->input->post("reqNameOfVessel");
        $reqTypeOfVessel = $this->input->post("reqTypeOfVessel");
        $reqLocation = $this->input->post("reqLocation");
        $reqClassSociety = $this->input->post("reqClassSociety");
        $reqScopeOfWork = $this->input->post("reqScopeOfWork");
        $reqNoOwr = $this->input->post("reqNoOwr");


        $dokumen_report->setField("DOCUMENT_ID", $reqId);
        $dokumen_report->setField("REPORT_ID", $reqReportId);
        $dokumen_report->setField("NAME", $reqName);
        $dokumen_report->setField("DESCRIPTION", $reqDescription);
        $dokumen_report->setField("PATH", $reqPath);
        $dokumen_report->setField("START_DATE", dateToDBCheck($reqStartDate));
        $dokumen_report->setField("FINISH_DATE", dateToDBCheck($reqFinishDate));
        $dokumen_report->setField("DELIVERY_DATE", dateToDBCheck($reqDeliveryDate));
        $dokumen_report->setField("LAST_REVISI", dateToDBCheck($reqLastRevisi));
        $dokumen_report->setField("INVOICE_DATE", dateToDBCheck($reqInvoiceDate));
        $dokumen_report->setField("REASON", $reqReason);
        $dokumen_report->setField("NO_REPORT", $reqNoReport);
        $dokumen_report->setField("NAME_OF_VESSEL", $reqNameOfVessel);
        $dokumen_report->setField("TYPE_OF_VESSEL", $reqTypeOfVessel);
        $dokumen_report->setField("LOCATION", $reqLocation);
        $dokumen_report->setField("CLASS_SOCIETY", $reqClassSociety);
        $dokumen_report->setField("SCOPE_OF_WORK", $reqScopeOfWork);
        $dokumen_report->setField("NO_OWR", $reqNoOwr);


        if (empty($reqId)) {
            $dokumen_report->insert();
            $reqId  = $dokumen_report->id;
        } else {
            $dokumen_report->update();
        }


        $reqTipe = $this->input->post('reqTipe');

        $name_folder = strtolower(str_replace(' ', '_', $reqTipe));

        
        $FILE_DIR = "uploads/" . $name_folder . "/" . $reqId . "/";
        makedirs($FILE_DIR);

        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
            if ($file->uploadToDirArray('document', $FILE_DIR, $renameFile, $i)) {
                array_push($arrData, $renameFile);
            } else {
                array_push($arrData, $reqLinkFileTemp[$i]);
            }
        }

        $str_name_path = '';
        for ($i = 0; $i < count($arrData); $i++) {
            if (!empty($arrData[$i])) {
                if ($i == 0) {
                    $str_name_path .= $arrData[$i];
                } else {
                    $str_name_path .= ',' . $arrData[$i];
                }
            }
        }

        $dokumen_report = new DokumenReport();
        $dokumen_report->setField("DOCUMENT_ID", $reqId);
        $dokumen_report->setField("PATH", setQuote($str_name_path));
        $dokumen_report->update_path();

        echo $reqId . '- Data berhasil di simpan';
    }

    function delete()
    {
        $this->load->model("Cash_report");
        $cashReport = new Cash_report();

        $reqId = $this->input->get('reqId');

        $cashReport->setField("CASH_REPORT_ID", $reqId);
        if ($cashReport->delete())
            $arrJson["PESAN"] = "Data berhasil dihapus.";
        else
            $arrJson["PESAN"] = "Data gagal dihapus.";

        echo json_encode($arrJson);
    }
}
