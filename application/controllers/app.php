<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");

class App extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		ini_set('session.gc_maxlifetime', 1);
		//kauth
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

	public function index()
	{

		$pg = $this->uri->segment(3, "home");
		$reqParse1 = $this->uri->segment(4, "");
		$reqParse2 = $this->uri->segment(5, "");
		$reqParse3 = $this->uri->segment(6, "");
		$reqParse4 = $this->uri->segment(7, "");
		$reqParse5 = $this->uri->segment(5, "");
		$reqId = $this->input->get("reqId");
		// echo $reqParse4;exit;
	$_SESSION['USERIDX']=$this->USERID;
		$_SESSION['HALAMAN_PATH']=$reqParse1;

		$view = array(
			'pg' => $pg,
			'linkBack' => $file . "_detil",
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5
		);

		$data = array(
			'breadcrumb' => $breadcrumb,
			'content' => $this->load->view("app/" . $pg, $view, TRUE),
			'pg' => $pg,
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5
		);
		
		$this->load->view('app/index', $data);
			

		
	}

	public function error404(){
		$this->load->view("error/404");
	}


	public function loadUrl()
	{

		$reqFolder = $this->uri->segment(3, "");
		$reqFilename = $this->uri->segment(4, "");
		$reqParse1 = $this->uri->segment(5, "");
		$reqParse2 = $this->uri->segment(6, "");
		$reqParse3 = $this->uri->segment(7, "");
		$reqParse4 = $this->uri->segment(8, "");
		$reqParse5 = $this->uri->segment(9, "");
		
		$_SESSION['USERIDX']=$this->USERID;
		$_SESSION['HALAMAN_PATH']=$reqParse1;
		
		$data = array(
			'reqParse1' => urldecode($reqParse1),
			'reqParse2' => urldecode($reqParse2),
			'reqParse3' => urldecode($reqParse3),
			'reqParse4' => urldecode($reqParse4),
			'reqParse5' => urldecode($reqParse5)
		);
		if ($reqFolder == "main")
			$this->session->set_userdata('currentUrl', $reqFilename);

		$this->load->view($reqFolder . '/' . $reqFilename, $data);
	}

	function mataUang(){
		$reqCurrency = $this->input->get("reqCur");
		$this->load->model("MasterCurrency");
		$master_currency = new MasterCurrency();
		$statement = " AND CAST(A.MASTER_CURRENCY_ID AS VARCHAR) ='".$reqCurrency."' OR NAMA ='".$reqCurrency."'";
		$master_currency->selectByParamsMonitoring(array(),-1,-1,$statement);
		$master_currency->firstRow();
		$reqCur = $master_currency->getField('NAMA');
		if($reqCurrency==0){
			$reqCur = 'IDR';
		}
		$reqCur = $reqCur?$reqCur:'Rupiah';

		echo $reqCur;
	}

	public function terbilang()
	{

		$this->load->model("MasterCurrency");


		$angka = $this->input->get("angka");
		$reqCur = $this->input->get("reqCur");
		$angka =round($angka,2);
		$arrData = explode('.',$angka);
		$reqNominal= $arrData[0];
		$reqCent= $arrData[1];
		$reqCent =round($reqCent,2);
		$reqCur = str_replace(" ",'',$reqCur);

		$master_currency = new MasterCurrency();
		$statement = " AND CAST(A.MASTER_CURRENCY_ID AS VARCHAR) ='".$reqCur."' OR NAMA ='".$reqCur."'";
		$master_currency->selectByParamsMonitoring(array(),-1,-1,$statement);
		$master_currency->firstRow();
		$reqCur = $master_currency->getField('FORMAT');
		// if($reqCur=='USD'){
		// 	$reqCur ='Dollar';
		// }
	
		$reqTextNominal=kekata_eng($reqNominal).' '.$reqCur;
		if(!empty($reqCent)){
			if(strlen($reqCent)==1){
				$reqCent .='0';
			}

			$reqTextNominal .=' and '.kekata_eng($reqCent).' Cent';
		}else{
			$reqTextNominal .=' Only';
		}
	
		echo $reqTextNominal;
	}

	
}
