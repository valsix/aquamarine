<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");

class login extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		/* GLOBAL VARIABLE */
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
	}

	public function index()
	{

		$pg = $this->uri->segment(3, "home");
		$reqParse1 = $this->uri->segment(4, "");
		$reqParse2 = $this->uri->segment(5, "");
		$reqParse3 = $this->uri->segment(6, "");
		$reqParse4 = $this->uri->segment(7, "");
		$reqParse5 = $this->uri->segment(5, "");

		$view = array(
			'pg' => $pg,
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5
		);

		$data = array(
			'pg' => $pg,
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5
		);

		$this->load->view('login/login', $data);
	}

	public function action()
	{

		$reqUser = $this->input->post("reqUser");
		$reqPasswd = $this->input->post("reqPasswd");
		if (!empty($reqUser) and !empty($reqPasswd)) {
			if ($this->kauth->localAuthenticate($reqUser, $reqPasswd)) {
				redirect('app');
			} else {
				$data['pesan'] = "Username atau password salah.";
				$this->load->view('login/login', $data);
			}
		} else {
			$data['pesan'] = "Masukkan username dan password.";
			$this->load->view('login/login', $data);
		}
	}

	public function logout()
	{
		$this->kauth->getInstance()->clearIdentity();
		 session_destroy();
		redirect('app');
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
		$data = array(
			'reqParse1' => urldecode($reqParse1),
			'reqParse2' => urldecode($reqParse2),
			'reqParse3' => urldecode($reqParse3),
			'reqParse4' => urldecode($reqParse4),
			'reqParse5' => urldecode($reqParse5)
		);
		$this->load->view($reqFolder . '/' . $reqFilename, $data);
	}
}