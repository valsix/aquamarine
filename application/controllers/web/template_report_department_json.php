<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class template_report_department_json  extends CI_Controller
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
		$this->load->model("ReportDepartmentTemplate");
		$template = new ReportDepartmentTemplate();
		$reqId= $this->input->post("reqId");

		
		$reqNama= $this->input->post("reqNama");
		$reqKeterangan= $this->input->post("reqKeterangan");
		$reqLink= $this->input->post("reqLinkTTD");

		$this->load->library("FileHandler");
        $file = new FileHandler();

        $FILE_DIR = "uploads/template_report_department/";
        makedirs($FILE_DIR);
        $filesData = $_FILES["reqLinkFile"];
        $renameFile = $filesData['name'][0];
        if ($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, 0)) {
            $reqLinkFile = $FILE_DIR.$renameFile;
        } else {
            $reqLinkFile = $reqLink;
        }

		$template->setField("REPORT_DEPARTMENT_TEMPLATE_ID", $reqId);
		$template->setField("NAMA", $reqNama);
		$template->setField("KETERANGAN", $reqKeterangan);
		$template->setField("LINK", $reqLinkFile);
		if(empty($reqId)){
			$template->setField("CREATED_BY", $this->USERID);
			$template->insert();
		}else{
			$template->setField("UPDATED_BY", $this->USERID);
			$template->update();	
		}
		

		echo "Data berhasil disimpan.";
	}


	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("ReportDepartmentTemplate");
		$template = new ReportDepartmentTemplate();


		$template->setField("REPORT_DEPARTMENT_TEMPLATE_ID", $reqId);
		if ($template->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo 'Data berhasil dihapus';
	}

}
