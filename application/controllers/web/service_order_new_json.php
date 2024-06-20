<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class service_order_new_json extends CI_Controller
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
		$this->MENUEPL = $this->kauth->getInstance()->getIdentity()->MENUEPL;
		$this->MENUUWILD = $this->kauth->getInstance()->getIdentity()->MENUUWILD;
		$this->MENUWP = $this->kauth->getInstance()->getIdentity()->MENUWP;
		$this->MENUPL = $this->kauth->getInstance()->getIdentity()->MENUPL;
		$this->MENUEL = $this->kauth->getInstance()->getIdentity()->MENUEL;
		$this->MENUPMS = $this->kauth->getInstance()->getIdentity()->MENUPMS;
		$this->MENURS = $this->kauth->getInstance()->getIdentity()->MENURS;
		$this->MENUSTD = $this->kauth->getInstance()->getIdentity()->MENUSTD;
		$this->MENUSTEN = $this->kauth->getInstance()->getIdentity()->MENUSTEN;
		$this->MENUSWD = $this->kauth->getInstance()->getIdentity()->MENUSWD;
		$this->MENUINVPROJECT = $this->kauth->getInstance()->getIdentity()->MENUINVPROJECT;
		$this->MENUWAREHOUSE = $this->kauth->getInstance()->getIdentity()->MENUWAREHOUSE;
	}

	function add(){
		$this->load->model('ServiceOrderNew');
		$reqId = $this->input->post('reqId');
		$reqCompanyId = $this->input->post('reqCompanyId');
		$reqHppProjectId = $this->input->post('reqHppProjectId');
		$reqCompanyName = $this->input->post('reqCompanyName');
		$reqNoOrder = $this->input->post('reqNoOrder');
		$reqDateOWR = $this->input->post('reqDateOWR');
		$reqDateOfService = $this->input->post('reqDateOfService');
		$reqDateOfStart = $this->input->post('reqDateOfStart');
		$reqDateOfFinish = $this->input->post('reqDateOfFinish');
		$reqProjectName = $this->input->post('reqProjectName');
		$reqDestination = $this->input->post('reqDestination');
		$reqPicEquip = $this->input->post('reqPicEquip'); 
		$reqTransport = $this->input->post('reqTransport');
		$reqObligation = $_POST['reqObligation'];
		$reqTTD = $this->input->post('reqTTD');

		$serviceordernew = new ServiceOrderNew();
		$serviceordernew->setField('SERVICE_ORDER_NEW_ID',$reqId);
		$serviceordernew->setField('COMPANY_ID',ValToNullDB($reqCompanyId));
		$serviceordernew->setField('HPP_PROJECT_ID',ValToNullDB($reqHppProjectId));
		$serviceordernew->setField('PROJECT_NAME',$reqProjectName);
		
		$serviceordernew->setField('DATE_OWR',dateToDBCheck($reqDateOWR));
		$serviceordernew->setField('DATE_WORK',dateToDBCheck($reqDateOfService));
		$serviceordernew->setField('DATE_DEPTURE',dateToDBCheck($reqDateOfStart));
		$serviceordernew->setField('DATE_FINISH',dateToDBCheck($reqDateOfFinish));
		$serviceordernew->setField('LOCATION',$reqDestination);
		$serviceordernew->setField('PIC_EQUIPMENT',$reqPicEquip);
		$serviceordernew->setField('TRANSPORTATION',$reqTransport);
		$serviceordernew->setField('OBLIGATION',setQuote($reqObligation));
		$serviceordernew->setField('PENANGGUNG_JAWAB',$reqTTD);
		if(empty($reqId)){
			$serviceordernew->insert();
			$reqId = $serviceordernew->id;
		}else{
			$serviceordernew->update();
		}
		echo $reqId.'- Data berhasil di simpan';


	}	

	function delete(){
			$this->load->model('ServiceOrderNew');
		$reqId = $this->input->post('reqId');

		$serviceordernew = new ServiceOrderNew();
		$serviceordernew->setField('SERVICE_ORDER_NEW_ID',$reqId);
		$serviceordernew->delete();
		echo 'Data berhasil dihapus';

	}


}
