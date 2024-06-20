<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class master_combo_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->CABANG		= $this->kauth->getInstance()->getIdentity()->CABANG;
	}

	function add()
	{

		$this->load->model("MasterCombo");
		$reqId =	$this->input->post("reqId");

		$personal_certificate = new MasterCombo();

		$reqJenisId 		= $this->input->post("reqJenisId");
		$reqJenis 			= $this->input->post("reqJenis");
		$reqCode 			= $this->input->post("reqCode");
		$reqDescription 	= $this->input->post("reqDescription");
		$reqModul 	= $this->input->post("reqModul");

		$personal_certificate->setField("MASTER_COMBO_ID", $reqId);
		$personal_certificate->setField("NAMA", $reqJenis);
		$personal_certificate->setField("MODUL", $reqModul);
		$personal_certificate->setField("KETERANGAN", $reqDescription);

		if (empty($reqId)) {
			$personal_certificate->insert();
		} else {
			$personal_certificate->update();
		}

		echo 'Data Berhasil di simpan';
	}
	function delete(){
		$reqId =	$this->input->get("reqId");
		$this->load->model("MasterCombo");
		$personal_certificate = new MasterCombo();
		$personal_certificate->setField("MASTER_COMBO_ID", $reqId);
		$personal_certificate->delete();


	}
}
