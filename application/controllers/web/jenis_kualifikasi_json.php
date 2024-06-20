<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class jenis_kualifikasi_json extends CI_Controller
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
		$this->load->model("JenisKualifikasi");
		$reqId =	$this->input->post("reqId");

		$jenis_kualifikasi = new JenisKualifikasi();

		$reqKode 			= $this->input->post("reqKode");
		$reqJenis 			= $this->input->post("reqJenis");
		$reqDescription 	= $this->input->post("reqDescription");

		$jenis_kualifikasi->setField("JENIS_ID", $reqId);
		$jenis_kualifikasi->setField("JENIS", $reqJenis);
		$jenis_kualifikasi->setField("DESCRIPTION", $reqDescription);
		$jenis_kualifikasi->setField("KODE", $reqKode);

		if (empty($reqId)) {
			$jenis_kualifikasi->insert();
		} else {
			$jenis_kualifikasi->update();
		}

		echo 'Data Berhasil di simpan';
	}

	function delete(){
		$this->load->model("JenisKualifikasi");
		$reqId =	$this->input->get("reqId");
		$jenis_kualifikasi = new JenisKualifikasi();
		$jenis_kualifikasi->setField("JENIS_ID", $reqId);
		$jenis_kualifikasi->delete();
		echo 'Data berhasil dihapus';



	}
}
