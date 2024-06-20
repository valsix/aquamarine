<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once 'kloader.php';
include_once("libraries/nusoap-0.9.5/lib/nusoap.php");
class kauth
{
	//put your code here
	private $ldap_config = array('server1' => array(
		'host' => '10.0.0.11',
		'useStartTls' => false,
		'accountDomainName' => 'pp3.co.id',
		'accountDomainNameShort' => 'PP3',
		'accountCanonicalForm' => 3,
		'baseDn' => "DC=pp3,DC=co,DC=id"
	));


	function __construct()
	{
		//        load the auth class
		kloader::load('Zend_Auth');
		kloader::load('Zend_Auth_Storage_Session');

		//        set the unique storege
		Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session("p3rumBUlogOffice"));
	}

	public function localAuthenticate($username, $credential)
	{
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();

		$CI = &get_instance();
		$CI->load->model("Users");
		// $CI->load->model("UserType");

		$users = new Users();
		$users->selectByIdPassword($username, md5($credential));

		if ($users->firstRow()) {

			$identity = new stdClass();

			$identity->USERID = $users->getField("USERID");
			$identity->USERNAME = $users->getField("USERNAME");
			$identity->FULLNAME = $users->getField("FULLNAME");
			$identity->USERPASS = $users->getField("USERPASS");
			$identity->LEVEL = $users->getField("LEVEL");
			$identity->MENUMARKETING = $users->getField("MENUMARKETING");
			$identity->MENUFINANCE = $users->getField("MENUFINANCE");
			$identity->MENUPRODUCTION = $users->getField("MENUPRODUCTION");
			$identity->MENUDOCUMENT = $users->getField("MENUDOCUMENT");
			$identity->MENUSEARCH = $users->getField("MENUSEARCH");
			$identity->MENUOTHERS = $users->getField("MENUOTHERS");
			$identity->MENUEPL = $users->getField("MENUEPL");
			$identity->MENUUWILD = $users->getField("MENUUWILD");
			$identity->MENUWP = $users->getField("MENUWP");
			$identity->MENUPL = $users->getField("MENUPL");
			$identity->MENUEL = $users->getField("MENUEL");
			$identity->MENUPMS = $users->getField("MENUPMS");
			$identity->MENURS = $users->getField("MENURS");
			$identity->MENUSTD = $users->getField("MENUSTD");
			$identity->MENUSTEN = $users->getField("MENUSTEN");
			$identity->MENUSWD = $users->getField("MENUSWD");
			$identity->MENUINVPROJECT = $users->getField("MENUINVPROJECT");
			$identity->MENUWAREHOUSE = $users->getField("MENUWAREHOUSE");

			$auth->getStorage()->write($identity);

			if ($users->getField("USERID") == "")
				return false;
			else
				return true;
		} else
			return false;
	}

	public function reAuthenticate($username, $credential)
	{
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();

		$CI = &get_instance();
		$CI->load->model("Users");
		// $CI->load->model("UserType");

		$users = new Users();
		$users->selectByPegawai($username);

		if ($users->firstRow()) {

			$identity = new stdClass();

			$identity->USERID = $users->getField("USERID");
			$identity->USERNAME = $users->getField("USERNAME");
			$identity->FULLNAME = $users->getField("FULLNAME");
			$identity->USERPASS = $users->getField("USERPASS");
			$identity->LEVEL = $users->getField("LEVEL");
			$identity->MENUMARKETING = $users->getField("MENUMARKETING");
			$identity->MENUFINANCE = $users->getField("MENUFINANCE");
			$identity->MENUPRODUCTION = $users->getField("MENUPRODUCTION");
			$identity->MENUDOCUMENT = $users->getField("MENUDOCUMENT");
			$identity->MENUSEARCH = $users->getField("MENUSEARCH");
			$identity->MENUOTHERS = $users->getField("MENUOTHERS");
			$identity->MENUEPL = $users->getField("MENUEPL");
			$identity->MENUUWILD = $users->getField("MENUUWILD");
			$identity->MENUWP = $users->getField("MENUWP");
			$identity->MENUPL = $users->getField("MENUPL");
			$identity->MENUEL = $users->getField("MENUEL");
			$identity->MENUPMS = $users->getField("MENUPMS");
			$identity->MENURS = $users->getField("MENURS");
			$identity->MENUSTD = $users->getField("MENUSTD");

			$auth->getStorage()->write($identity);

			if ($users->getField("USERID") == "")
				return false;
			else
				return true;
		} else
			return false;
	}


	public function mobileAuthenticate($username, $credential)
	{
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();

		$CI = &get_instance();
		$CI->load->model("Users");
		// $CI->load->model("UserType");

		$users = new Users();
		$users->selectByIdPasswordMobile($username, md5($credential));

		if ($users->firstRow()) {

			$identity = new stdClass();

			$identity->PEGAWAI_ID = $users->getField("NIP");
			$identity->CABANG_ID = $users->getField("CABANG_ID");
			$identity->NO_SEKAR = $users->getField("NO_SEKAR");
			$identity->NRP = $users->getField("NRP");
			$identity->NIP = $users->getField("NIP");
			$identity->NAMA = $users->getField("NAMA");
			$identity->NAMA_PANGGILAN = $users->getField("NAMA_PANGGILAN");
			$identity->JENIS_KELAMIN = $users->getField("JENIS_KELAMIN");
			$identity->TEMPAT_LAHIR = $users->getField("TEMPAT_LAHIR");
			$identity->TANGGAL_LAHIR = $users->getField("TANGGAL_LAHIR");
			$identity->UNIT_KERJA = $users->getField("UNIT_KERJA");
			$identity->ALAMAT = $users->getField("ALAMAT");
			$identity->NOMOR_HP = $users->getField("NOMOR_HP");
			$identity->EMAIL_PRIBADI = $users->getField("EMAIL_PRIBADI");
			$identity->EMAIL_BULOG = $users->getField("EMAIL_BULOG");
			$identity->NOMOR_WA = $users->getField("NOMOR_WA");
			$identity->GOLONGAN_DARAH = $users->getField("GOLONGAN_DARAH");
			$identity->VALIDASI = $users->getField("VALIDASI");

			$auth->getStorage()->write($identity);

			if ($users->getField("PEGAWAI_ID") == "")
				return "0";
			else
				return "1";
		} else
			return "0";
	}

	public function multiAkses($groupId)
	{

		$auth = Zend_Auth::getInstance();
		$CI = &get_instance();

		$CI->load->model("UserType");

		$identity = new stdClass();
		$identity->USER_LOGIN_ID = $auth->getIdentity()->USER_LOGIN_ID;
		$identity->USER_LOGIN = $auth->getIdentity()->USER_LOGIN;
		$identity->USER_STATUS = "1";
		$identity->USER_NAMA = $auth->getIdentity()->USER_NAMA;
		$identity->ANAK_PERUSAHAAN = "0";

		$identity->ID = $auth->getIdentity()->ID;
		$identity->UNIT_KERJA_ID = $auth->getIdentity()->UNIT_KERJA_ID;
		$identity->UNIT_KERJA = $auth->getIdentity()->UNIT_KERJA;
		$identity->NIP = $auth->getIdentity()->NIP;
		$identity->LOGIN_TIME = time();
		$identity->LOGIN_DATE = date("l, j M Y, H:i", time());

		$identity->ANAK_PERUSAHAAN = $auth->getIdentity()->ANAK_PERUSAHAAN;

		$identity->HAKAKSES = $auth->getIdentity()->HAKAKSES;
		$identity->HAKAKSES_DESC = $auth->getIdentity()->HAKAKSES_DESC;

		$OuserLevel = new UserType();
		$OuserLevel->selectByParams(array('NAMA' => $groupId));
		if (!$OuserLevel->firstRow()) {
			return "Role tidak ditemukan!!";
		}

		$identity->USER_TYPE = $OuserLevel->getField('NAMA');
		$identity->USER_TYPE_ID = $OuserLevel->getField('USER_TYPE_ID');

		$auth->getStorage()->write($identity);
		return "1";
	}

	public function getInstance()
	{
		return Zend_Auth::getInstance();
	}
}
