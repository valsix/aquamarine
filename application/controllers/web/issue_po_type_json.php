<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class issue_po_type_json  extends CI_Controller
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
		$this->load->model("IssuePoType");
		$issue_po_type = new IssuePoType();
		$reqId= $this->input->post("reqId");

		
		$reqName= $this->input->post("reqName");
		$reqType= $this->input->post("reqType");
		$reqDescription= $this->input->post("reqDescription");

		$issue_po_type->setField("ISSUE_PO_TYPE_ID", $reqId);
		$issue_po_type->setField("NAME", $reqName);
		$issue_po_type->setField("TYPE", $reqType);
		$issue_po_type->setField("DESCRIPTION", $reqDescription);
		if(empty($reqId)){
			$issue_po_type->insert();
		}else{
			$issue_po_type->update();	
		}
		

		echo "Data berhasil disimpan.";
	}


	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("IssuePoType");
		$issue_po_type = new IssuePoType();


		$issue_po_type->setField("ISSUE_PO_TYPE_ID", $reqId);
		if ($issue_po_type->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo 'Data berhasil dihapus';
	}

}
