<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class bank_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->aduanId				  = $this->kauth->getInstance()->getIdentity()->Dokumen_id;
		$this->Nip				  = $this->kauth->getInstance()->getIdentity()->Nip;
		$this->nama							= $this->kauth->getInstance()->getIdentity()->nama;
		$this->Aduan				= $this->kauth->getInstance()->getIdentity()->Aduan;
		$this->linkFile				= $this->kauth->getInstance()->getIdentity()->link_file;
		$this->createdBy				= $this->kauth->getInstance()->getIdentity()->created_by;
		$this->createdDate			= $this->kauth->getInstance()->getIdentity()->created_date;
		$this->updateBy				= $this->kauth->getInstance()->getIdentity()->update_by;
		$this->updateDate			= $this->kauth->getInstance()->getIdentity()->update_date;
	}

	function add()
	{
		$this->load->model("Bank");
		$reqId =	$this->input->post("reqId");

		$bank = new Bank();

	
		$reqJenis 			= $this->input->post("reqNama");
		$reqDescription 	= $this->input->post("reqKodeRekening");

		$bank->setField("BANK_ID", $reqId);
		$bank->setField("NAMA", $reqJenis);
		$bank->setField("KODE_REKENING", $reqDescription);

		if (empty($reqId)) {
			$bank->insert();
		} else {
			$bank->update();
		}

		echo 'Data Berhasil di simpan';
	}
}
