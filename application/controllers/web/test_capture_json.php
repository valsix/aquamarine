<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class test_capture_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			redirect('Login');
		}       
		
		
		/* GLOBAL VARIABLE */
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->TAHUN_TERPILIH = substr($this->kauth->getInstance()->getIdentity()->TAHUN, 0, 4);   
		$this->KODE_CABANG = $this->kauth->getInstance()->getIdentity()->KODE_CABANG;   
		$this->NAMA = $this->kauth->getInstance()->getIdentity()->NAMA;   
		$this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;   
		$this->HAK_AKSES = $this->kauth->getInstance()->getIdentity()->HAK_AKSES; 
	}

		function uploads(){
			// print_r($this->input->post());
			$this->load->library("FileHandler");
			$file = new FileHandler();
			$FILE_DIR = "uploads/test_capture/";
			makedirs($FILE_DIR);
		$img =	$_FILES['img'];
		// print_r($img);
			$decode = $_POST['img'];
			$decode2 = $_POST['img'];
			$data = $_POST['img'];
			// $decode = base64_decode($decode);

			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);

			$imgPath = 'img'.time().'.png';
			file_put_contents($FILE_DIR.$imgPath, $data);
			print_r($decode2);
			echo 'Suksen Send';
		}
}

