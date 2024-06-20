<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class supplier_json extends CI_Controller
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

		$this->load->model("SupplierBarang");
		$reqId =	$this->input->post("reqId");

		$personal_certificate = new SupplierBarang();

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
		$this->load->model("SupplierBarang");
		$personal_certificate = new SupplierBarang();
		$personal_certificate->setField("SUPPLIER_BARANG_ID", $reqId);
		$personal_certificate->delete();


	}
	function deletepart(){
		$reqId =	$this->input->get("reqId");
		$this->load->model("SupplierPart");
		$personal_certificate = new SupplierPart();
		$personal_certificate->setField("SUPPLIER_PART_ID", $reqId);
		$personal_certificate->delete();


	}

	function comboAutoComplate(){
		$keyword = $this->input->post('keyword');
		$reqId = $this->input->get('reqId');
		$this->load->model("SupplierBarang");
		$masteralat = new SupplierBarang();
		$masteralat->selectByParamsMonitoring(array(),-1,-1," AND UPPER(NAMA) LIKE '%".strtoupper($keyword)."%' ");
		// echo $masteralat->query;
		$arrDataAlat = $masteralat->rowResult;

		$text ='<ul id="country-list">';
		foreach ($arrDataAlat as $country) {
			$reqNama = $country['nama'];
			$text .= '<li
        onClick="selectCountry('. "'$reqNama'".','."'$reqId'".');">'
       .$country["nama"].'</li>';

		}
		$text .='</ul>';
		echo $text;
	}

	function combo_equipment_list(){
		$keyword = $this->input->post('keyword');
		$reqId = $this->input->get('reqId');
		$this->load->model("EquipmentList");
		$equipmentlist = new EquipmentList();
		$equipmentlist->selectByParamsMonitoringStock(array(),-1,-1," AND UPPER(EQUIP_NAME) LIKE '%".strtoupper($keyword)."%' "," ORDER BY equip_name ASC");
		$arrDataEquip = $equipmentlist->rowResult;

		$arrDataNamaEquip = array_column($arrDataEquip, 'equip_name');
		$arrDataNamaEquip = array_unique($arrDataNamaEquip);
		// print_r($arrDataNamaEquip);
	$text ='<ul id="country-list" class="alat_list"><li onClick="closeComplit()"> <i class="fa fa-times fa-lg"></i> CLOSE </li>';
		foreach ($arrDataNamaEquip as $country) {
			$reqNama = $country;
			$text .= '<li
			onClick="selectCountry('. "'$reqNama'".','."'$reqId'".');">'
			.$reqNama.'</li>';

		}
		$text .='</ul>';
		echo $text;

	}
}
