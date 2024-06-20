<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class format_qm_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
	}


	function add(){
		$this->load->model('FormatQm');
		$format_qm  = new FormatQm();

		$reqId          = $this->input->post("reqId");
		$reqFormat      = $this->input->post("reqFormat");
		$reqDescription = $this->input->post("reqDescription");

		$format_qm->setField("FORMAT_ID",$reqId);
		$format_qm->setField("FORMAT",$reqFormat);
		$format_qm->setField("DESCRIPTION",$reqDescription);

		if(empty($reqId)){
			$format_qm->insert();
			$reqId  = $format_qm->id;
		}else{
			$format_qm->update();
		}

		echo 'Data Berhasil di simpan';

	}
}