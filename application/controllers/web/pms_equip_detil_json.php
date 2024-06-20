<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class pms_equip_detil_json extends CI_Controller {

	function __construct() {
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// redirect('login');
		}

		// $this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->Aduan_id				= $this->kauth->getInstance()->getIdentity()->Aduan_id;
		$this->nama				= $this->kauth->getInstance()->getIdentity()->nama;
		$this->keterangan			= $this->kauth->getInstance()->getIdentity()->keterangan;
		$this->link_file		= $this->kauth->getInstance()->getIdentity()->link_file;
		$this->created_by		= $this->kauth->getInstance()->getIdentity()->created_by;
		$this->created_date		= $this->kauth->getInstance()->getIdentity()->created_date;
		$this->update_by	= $this->kauth->getInstance()->getIdentity()->update_by;
		$this->update_date		= $this->kauth->getInstance()->getIdentity()->update_date;

	}

	function test(){
		echo 'adasdas';
	}

	function tambah_doc($reqId =''){
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$filesData = $_FILES["document"];
		$reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
		$file->cekSize($filesData,$reqLinkFileTemp);
		$name_folder = 'pms_equip_detil';
		$this->load->model('PmsEquipDetil');

       
        $FILE_DIR = "uploads/" . $name_folder . "/" . $reqId . "/";
        makedirs($FILE_DIR);

        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
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

        $pms_equip_detil = new PmsEquipDetil();
        $pms_equip_detil->setField("PMS_DETIL_ID", $reqId);
        $pms_equip_detil->setField("PATH", ($str_name_path));
        $pms_equip_detil->update_pathS();

	}

	function add(){
		$this->load->model("PmsEquipDetil");
		$pms_equip_detil = new PmsEquipDetil();

		$reqId= $this->input->post("reqId");
		$reqEquipId= $this->input->post("reqEquipId");

		$reqPmsDetilId          = $this->input->post("reqPmsDetilId");
	    $reqName                = $this->input->post("reqName");
	    $reqTimeTest            = $this->input->post("reqTimeTest");
	    $reqCertificateNo       = $this->input->post("reqCertificateNo");
	    $reqManufacture         = $this->input->post("reqManufacture");
	    $reqModelNo             = $this->input->post("reqModelNo");
	    $reqSerialNo            = $this->input->post("reqSerialNo");
	    $reqDateTest            = $this->input->post("reqDateTest");
	    $reqNextTest            = $this->input->post("reqNextTest");
	    $reqComponentPerson     = $this->input->post("reqComponentPerson");
	    $reqCondition           = $this->input->post("reqCondition");
	    $reqRemarks             = $this->input->post("reqRemarks");
	    $reqLinkFile            = $_FILES["reqLinkFile"];

		$pms_equip_detil->setField("PMS_DETIL_ID", $reqPmsDetilId);
		$pms_equip_detil->setField("PMS_ID", $reqId);
		$pms_equip_detil->setField("NAME", $reqName);
		$pms_equip_detil->setField("TIME_TEST", $reqTimeTest);
		$pms_equip_detil->setField("CERTIFICATE_NUMBER", $reqCertificateNo);
		$pms_equip_detil->setField("MANUFACTURE", $reqManufacture);
		$pms_equip_detil->setField("MODEL_NUMBER", $reqModelNo);
		$pms_equip_detil->setField("SERIAL_NUMBER", $reqSerialNo);
		$pms_equip_detil->setField("DATE_TEST", dateToDBCheck($reqDateTest));
		$pms_equip_detil->setField("DATE_NEXT_TEST", dateToDBCheck($reqNextTest));
		$pms_equip_detil->setField("COMPENENT_PERSON", $reqComponentPerson);
		$pms_equip_detil->setField("CONDITION", $reqCondition);
		$pms_equip_detil->setField("REMARKS", $reqRemarks);

		if(empty($reqPmsDetilId)){
			$pms_equip_detil->insert();
			$reqPmsDetilId = $pms_equip_detil->id;
		}else{
			$pms_equip_detil->update();
		}

		
	    $this->load->library("FileHandler");
        $file = new FileHandler();

        $FILE_DIR = "uploads/pms/";
        makedirs($FILE_DIR);
        $filesData = $_FILES["reqLinkFile"];
        $renameFile = "IMG" . date("dmYhis") . '-' . $reqId . "." . getExtension2($filesData['name'][0]);
        if ($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, 0)) {
            $pms_equip_detil = new PmsEquipDetil();
	        $pms_equip_detil->setField("PMS_DETIL_ID", $reqPmsDetilId);
	        $pms_equip_detil->setField("LINK_FILE", $renameFile);
	        $pms_equip_detil->updateImage();
        } 

        $this->tambah_doc($reqPmsDetilId);
		echo $reqPmsDetilId.'-Data berhasil di simpan';

	}
	 function delete()
    {
    	 $reqId = $this->input->get('reqId');
        $this->load->model("PmsEquipDetil");
        $pms_equip_detil = new PmsEquipDetil();
        $pms_equip_detil->setField("PMS_DETIL_ID",$reqId);

       

     
        $pesan ='';
        if ($pms_equip_detil->delete())
            $pesan = "Data berhasil dihapus.";
        else
            $pesan = "Data gagal dihapus.";

        echo $pesan;exit;
    }
}
