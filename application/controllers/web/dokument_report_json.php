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

    function auto_urutan(){
         $this->load->model("DokumenReport");
        $reqTipe = $this->input->post('reqTipe');
        $dokumen_report = new DokumenReport();
        $order     = "   ";
        $add_tipe  = 


        $dokumen_report->selectByParamsTahun(array());
        // $arraData =array();
        // while ($dokumen_report->nextRow()) {
        //    array_push($arraData, $dokumen_report->getField('TAHUN'));
        // }

        // print_r($arraData);

       
      
        // for($i=0;$i<count($arraData);$i++){
        //     if(empty($arraData[$i])){
        //          $statement = " AND TO_CHAR((A.START_DATE)::TIMESTAMP WITH TIME ZONE, 'YYYY'::TEXT) IS NULL";
        //     }else{
        //          $statement = " AND TO_CHAR((A.START_DATE)::TIMESTAMP WITH TIME ZONE, 'YYYY'::TEXT) ='".$arraData[$i]."'";
        //     }
             $dokumen_report = new DokumenReport();
             $dokumen_report->selectByParamsTahun(array());
             // echo $dokumen_report->query.'<br><br>';
             $no=1;
             while ($dokumen_report->nextRow()) {
                 $dokumen_reports = new DokumenReport();
                 $dokumen_reports->setField("URUT",$no);
                 $dokumen_reports->setField("DOCUMENT_ID",$dokumen_report->getField("DOCUMENT_ID"));
                 $dokumen_reports->update_urutan();

                 $no++;
             }

        // }
    }


    function add_client_surveyor($reqId=''){
        $this->load->model('DokumenReport');
         $this->load->model("CostProject");

        $reqClientSheet   = $this->input->post('reqClientSheet');
        $reqClientRecom   = $this->input->post('reqClientRecom');
        $reqSurveyorSheet = $this->input->post('reqSurveyorSheet');
        $reqSurveyorRecom = $this->input->post('reqSurveyorRecom');


        $arrDataSurveryour = array();
        $arrDataClient = array();
        $arrDataClient['reqClientSheet']=$reqClientSheet;
        $arrDataClient['reqClientRecom']=$reqClientRecom;    
        $arrDataSurveryour['reqSurveyorRecom']=$reqSurveyorRecom;
        $arrDataSurveryour['reqSurveyorSheet']=$reqSurveyorSheet;

        $i=0;
        for($i=0;$i<8;$i++){
                $reqClientRemark  = $this->input->post('reqClientRemark'.$i);
                $reqSurveyorRemark = $this->input->post('reqSurveyorRemark'.$i);
                $arrDataClient['reqClientRemark'.$i] =$reqClientRemark;
                $arrDataSurveryour['reqSurveyorRemark'.$i]=$reqSurveyorRemark;
                for($j=0;$j<5;$j++){
                     $reqClient = $this->input->post('reqClient'.$i.$j);
                     $reqSurveyor = $this->input->post('reqSurveyor'.$i.$j);
                     $arrDataClient['reqClient'.$i.$j]=$reqClient ;
                     $arrDataSurveryour['reqSurveyor'.$i.$j]= $reqSurveyor ;
                }

        }

        $reqClient = json_encode($arrDataClient);
        $reqSurveyor = json_encode($arrDataSurveryour);

        $reqSurveyorCost   = $this->input->post('reqSurveyorCost');
        $reqOperatorCost   = $this->input->post('reqOperatorCost');

        $dokumen_report = new DokumenReport();
        $dokumen_report->setField("DOCUMENT_ID",$reqId);
        $dokumen_report->setField("SURYEVOR",$reqSurveyor);
        $dokumen_report->setField("CLIENT",$reqClient);
        $dokumen_report->setField("COST_SURYEVOR",$reqSurveyorCost);
        $dokumen_report->setField("COST_OPERATOR",$reqOperatorCost);
         $dokumen_report->update_new_suryevor();

         $reqNoReport = $this->input->post('reqNoReport');
             $reqFinishDate = $this->input->post("reqFinishDate");
            $reqStartDate = $this->input->post("reqStartDate");
         $cost_project = new CostProject();
          $cost_project->setField("DATE_SERVICE2",dateToDBCheck($reqFinishDate));
           $cost_project->setField("DATE_SERVICE1",dateToDBCheck($reqStartDate));
            $cost_project->setField("NO_PROJECT",$reqNoReport);
         $cost_project->setField("SURVEYOR",$reqSurveyorCost);
         $cost_project->setField("OPERATOR",$reqOperatorCost);
         $cost_project->update_surveyor_operator();


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
        $reqStatus = $this->input->post("reqStatus");
        $reqCompanyId = $this->input->post("reqCompanyId");
        $reqVesselId = $this->input->post("reqVesselId");
        $reqUrut = $this->input->post("reqNoUrut");


        $dokumen_report->setField("DOCUMENT_ID", $reqId);
        $dokumen_report->setField("REPORT_ID", ValToNullDB($reqReportId));
        $dokumen_report->setField("NAME", $reqName);
        $dokumen_report->setField("DESCRIPTION", $reqDescription);
        $dokumen_report->setField("PATH", $reqPath);
        $dokumen_report->setField("URUT", $reqUrut);
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
        $dokumen_report->setField("STATUS", $reqStatus);
        $dokumen_report->setField("COMPANY_ID", ValToNullDB($reqCompanyId));
        $dokumen_report->setField("VESSEL_ID", ValToNullDB($reqVesselId));


         if (empty($reqId)) {
            $jumlah = $this->db->query("SELECT COUNT(1) JUMLAH FROM DOKUMEN_REPORT WHERE URUT  = '$reqUrut'")->row()->jumlah;
            if($jumlah > 0){
                echo $reqId."-No Urut $reqUrut sudah digunakan.";
                return;
            }
           $dokumen_report->insert();
            $reqId  = $dokumen_report->id;
        } else {
            $jumlah = $this->db->query("SELECT COUNT(1) JUMLAH FROM DOKUMEN_REPORT WHERE URUT  = '$reqUrut' AND DOCUMENT_ID <> '$reqId'")->row()->jumlah;
            if($jumlah > 0){
                echo $reqId."-No Urut $reqUrut sudah digunakan.";
                return;
            }
             $dokumen_report->update();
        }



       


        $reqTipe = $this->input->post('reqTipe');

        $name_folder = strtolower(str_replace(' ', '_', $reqTipe));

        $FILE_DIR = "uploads/" . $name_folder . "/" . $reqId . "/";
        makedirs($FILE_DIR);

        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile = $reqId . ' - ' . getExtension($filesData['name'][$i]);
            if ($file->uploadToDirArray('document', $FILE_DIR, $renameFile, $i)) {
                array_push($arrData, setQuote($renameFile));
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
                    $str_name_path .= ';' . $arrData[$i];
                }
            }
        }

        $dokumen_report = new DokumenReport();
        $dokumen_report->setField("DOCUMENT_ID", $reqId);
        $dokumen_report->setField("PATH", ($str_name_path));
        $dokumen_report->update_path();

        /*MENYAMAKAN NILAI START DATE DI REPORT SURVEY DENGAN INTERMEDIATE_DATE*/
        $dokumen_report = new DokumenReport();
        $dokumen_report->selectByParams(array("DOCUMENT_ID" => $reqId));
        $dokumen_report->firstRow();
        $reqOfferId = $dokumen_report->getField("OFFER_ID");

        if($reqOfferId != ""){
            $this->db->query("
                update reminder_client set intermediate_date = ".dateToDBCheck($reqStartDate)." 
                where offer_id = ".$reqOfferId."
            ");   
        }

        /*MENYAMAKAN NILAI START DATE DI REPORT SURVEY DENGAN INTERMEDIATE_DATE*/
        // $this->auto_urutan();

        $this->add_client_surveyor($reqId);
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

        
         // $this->auto_urutan();
        echo json_encode($arrJson);
    }
}
