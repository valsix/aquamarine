<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class master_alat_json extends CI_Controller
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

		$this->load->model("MasterAlat");
		$reqId =	$this->input->post("reqId");

		$personal_certificate = new MasterAlat();

		$reqJenisId 		= $this->input->post("reqJenisId");
		$reqJenis 			= $this->input->post("reqJenis");
		$reqDescription 	= $this->input->post("reqDescription");

		$personal_certificate->setField("MASTER_ALAT_ID", $reqId);
		$personal_certificate->setField("NAMA", $reqJenis);
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
		$this->load->model("MasterAlat");
		$personal_certificate = new MasterAlat();
		$personal_certificate->setField("MASTER_ALAT_ID", $reqId);
		$personal_certificate->delete();


	}

	function comboAutoComplate(){
		$keyword = $this->input->post('keyword');
		$reqId = $this->input->get('reqId');
		$reqSupplierId = $this->input->get('reqSupplierId');
		$this->load->model("MasterAlat");
		$masteralat = new MasterAlat();
		$masteralat->selectByParamsMonitoring(array(),-1,-1," AND UPPER(NAMA) LIKE '%".strtoupper($keyword)."%' ");
		$arrDataAlat = $masteralat->rowResult;
		$text ='<ul id="country-list"><li onClick="closeComplit()"> <i class="fa fa-times fa-lg"></i> CLOSE </li>';
		foreach ($arrDataAlat as $country) {
			$reqNama = $country['nama'];
			$text .= '<li
        onClick="selectCountry('. "'$reqNama'".','."'$reqId'".');">'
       .$country["nama"].'</li>';

		}
		$text .='</ul>';
		echo $text;
	}

	function comboAutoComplatePart(){
		$keyword = $this->input->post('keyword');
		$reqId = $this->input->get('reqId');
		$reqSupplierId = $this->input->get('reqSupplierId');
		$this->load->model("MasterAlat");
		$masteralat = new MasterAlat();
		$masteralat->selectByParamsMonitoring(array(),-1,-1," AND UPPER(NAMA) LIKE '%".strtoupper($keyword)."%' ");
		$arrDataAlat = $masteralat->rowResult;
		$text ='<ul id="country-list"><li onClick="closeComplit()"> <i class="fa fa-times fa-lg"></i> CLOSE </li>';
		foreach ($arrDataAlat as $country) {
			$reqNama = $country['nama'];
			$text .= '<li
        onClick="selectCountry('. "'$reqNama'".','."'$reqId'".');">'
       .$country["nama"].'</li>';

		}
		$text .='</ul>';
		echo $text;
	}

}
