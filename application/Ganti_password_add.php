<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once("functions/image.func.php");
/* INCLUDE FILE */
include_once("functions/string.func.php");
include_once("functions/date.func.php");

class ganti_password_add extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		//kauth
		if($this->session->userdata("HAKAKSES") == "") {
			//redirect('app');
		}

		$this->db->query("alter session set nls_date_format='DD-MM-YYYY'");
		$this->db->query("alter session set nls_numeric_characters='.,'");

		$this->CABANG_ID = $this->session->userdata("CABANG_ID");
$this->CABANG = $this->session->userdata("CABANG");
$this->PERIODE_ID = $this->session->userdata("PERIODE_ID");
		$this->PERIODE = $this->session->userdata("PERIODE");
		$this->USER_LOGIN_ID = $this->session->userdata("USER_LOGIN_ID");
		$this->KD_DIT = $this->session->userdata("KD_DIT");
		$this->KD_SUBDIT = $this->session->userdata("KD_SUBDIT");
		$this->DIREKTORAT = $this->session->userdata("DIREKTORAT");
		$this->SUBDIT = $this->session->userdata("SUBDIT");
		$this->NIP = $this->session->userdata("NIP");
		$this->USER_LOGIN = $this->session->userdata("USER_LOGIN");
		$this->NAMA = $this->session->userdata("NAMA");
		$this->JABATAN = $this->session->userdata("JABATAN");
		$this->USER_GROUP_ID = $this->session->userdata("USER_GROUP_ID");
		$this->USER_GROUP = $this->session->userdata("USER_GROUP");
		$this->HAKAKSES = $this->session->userdata("HAKAKSES");
		$this->HAKAKSES_DESC = $this->session->userdata("HAKAKSES_DESC");
		$this->LOGIN_TIME = $this->session->userdata("LOGIN_TIME");
		$this->LOGIN_DATE = $this->session->userdata("LOGIN_DATE");

		/* BLOCK AKSES MASTER SELAIN ADMINISTRATOR */
		if (stristr($this->uri->segment(3, ""), "master")) {
			if ($this->USER_TYPE_ID == "1") {
			} else
				redirect('app');
		}
	}

	public function index()
	{
		$this->load->model("UsersBase");


		$user_login = new UsersBase();

		$reqId =  $this->input->post('reqId');
		$reqPassword =  $this->input->post('reqPassword');
		$reqPasswordKonfirmasi =  $this->input->post('reqPasswordKonfirmasi');

		if ($reqPassword != $reqPasswordKonfirmasi) {
			echo "Password tidak sama";
			return;
		}

		$user_login->setField("USER_PASS", MD5($reqPassword));
		$user_login->setField("USER_LOGIN_ID", $reqId);
		$user_login->setField('LAST_UPDATE_USER', $this->NIP);
		$user_login->setField('LAST_UPDATE_DATE', "CURRENT_DATE");
		if ($user_login->updatePassword()) {
			echo "Password berhasil di ubah.";
		} else {
			echo "Password gagal di ubah.";
		}
	}
}
