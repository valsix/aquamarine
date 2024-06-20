<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class type_tender_upload_json  extends CI_Controller
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
		$this->load->model("TenderTypeUpload");
		$tender_type_upload = new TenderTypeUpload();
		$reqId= $this->input->post("reqId");

		
		$reqTenderId= $this->input->post("reqTenderId");
		$reqName= $this->input->post("reqName");
		$reqType= $this->input->post("reqType");
		$reqDescription= $this->input->post("reqDescription");

		$tender_type_upload->setField("TENDER_TYPE_UPLOAD_ID", $reqId);
		$tender_type_upload->setField("NAME", $reqName);
		$tender_type_upload->setField("TYPE", $reqType);
		$tender_type_upload->setField("DESCRIPTION", $reqDescription);
		$tender_type_upload->setField("TENDER_ID", $reqTenderId);
		if(empty($reqId)){
			$tender_type_upload->insert();
		}else{
			$tender_type_upload->update();	
		}
		

		echo "Data berhasil disimpan.";
	}


	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("TenderTypeUpload");
		$tender_type_upload = new TenderTypeUpload();


		$tender_type_upload->setField("TENDER_TYPE_UPLOAD_ID", $reqId);
		if ($tender_type_upload->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo 'Data berhasil dihapus';
	}

}
