<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class type_of_service_json  extends CI_Controller
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
		$this->load->model("TypeOfService");
		$type_of_service = new TypeOfService();
		$reqId= $this->input->post("reqId");

		
		$reqName= $this->input->post("reqName");
		$reqDescription= $this->input->post("reqDescription");

		$type_of_service->setField("TYPE_OF_SERVICE_ID", $reqId);
		$type_of_service->setField("NAME", $reqName);
		$type_of_service->setField("DESCRIPTION", $reqDescription);
		if(empty($reqId)){
			$type_of_service->insert();
		}else{
			$type_of_service->update();	
		}
		

		echo "Data berhasil disimpan.";
	}


	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("TypeOfService");
		$type_of_service = new TypeOfService();


		$type_of_service->setField("TYPE_OF_SERVICE_ID", $reqId);
		if ($type_of_service->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo 'Data berhasil dihapus';
	}



	
	function combo()
	{
		$this->load->model("ClassOfVessel");
		$ClassOfVessel = new ClassOfVessel();

		$aduan->selectByParams(array());
		$i = 0;
		while ($aduan->nextRow()) {
			$arr_json[$i]['id']		= $aduan->getField("ADUAN_ID");
			$arr_json[$i]['text']	= $aduan->getField("NAMA");
			$i++;
		}

		echo json_encode($arr_json);
	}
}
