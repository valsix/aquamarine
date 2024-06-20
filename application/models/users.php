<?
/* *******************************************************************************************************
MODUL NAME 			: E LEARNING
FILE NAME 			:
AUTHOR				:
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			:
***************************************************************************************************** */

/***
 * Entity-base class untuk mengimplementasikan tabel KontakPegawai.
 *
 ***/
include_once(APPPATH . '/models/Entity.php');

class Users extends Entity
{

	var $query;
	/**
	 * Class constructor.
	 **/
	function Users()
	{
		$this->Entity();
	}

	function selectByIdPassword($username, $password)
	{
		/** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
		//$passwd = md5($passwd);

		$str = "SELECT A.USERID, A.USERNAME, A.FULLNAME, A.USERPASS, A.LEVEL, A.MENUMARKETING, 
					A.MENUFINANCE, A.MENUPRODUCTION, A.MENUDOCUMENT, A.MENUSEARCH, A.MENUOTHERS,
					MENUEPL, MENUUWILD, MENUWP, MENUPL, MENUEL, MENUPMS, MENURS, MENUSTD,MENUSTEN,MENUSWD,MENUINVPROJECT,MENUWAREHOUSE
				FROM USERS A
				WHERE USERNAME = '" . $username . "' AND USERPASS = '" . $password . "' ";
		$this->query = $str;
		// echo$str;exit();
		return $this->select($str);
	}

	function selectByIdPasswordMobile($username, $password = "")
	{
		/** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
		//$passwd = md5($passwd);

		$str = "SELECT A.PEGAWAI_ID, CABANG_ID, NO_SEKAR, NRP, NIP, NAMA, NAMA_PANGGILAN, 
			   JENIS_KELAMIN, TEMPAT_LAHIR, TANGGAL_LAHIR, UNIT_KERJA, ALAMAT, 
			   NOMOR_HP, EMAIL_PRIBADI, EMAIL_BULOG, NOMOR_WA, GOLONGAN_DARAH, 
			   FOTO, CREATED_BY, CREATED_DATE, UPDATED_BY, UPDATED_DATE, VALIDASI
				FROM PEGAWAI A WHERE (NIP = '" . $username . "') AND A.VALIDASI = '1' ";
		$this->query = $str;
		// echo$str;exit();
		return $this->select($str);
	}


	function selectByPegawaiId($pegawaiId)
	{
		/** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
		//$passwd = md5($passwd);

		$str = "SELECT A.PEGAWAI_ID, CABANG_ID, NO_SEKAR, NRP, NIP, NAMA, NAMA_PANGGILAN, 
			   JENIS_KELAMIN, TEMPAT_LAHIR, TANGGAL_LAHIR, UNIT_KERJA, ALAMAT, 
			   NOMOR_HP, EMAIL_PRIBADI, EMAIL_BULOG, NOMOR_WA, GOLONGAN_DARAH, 
			   FOTO, CREATED_BY, CREATED_DATE, UPDATED_BY, UPDATED_DATE
				FROM PEGAWAI A WHERE (PEGAWAI_ID = '" . $pegawaiId . "') ";
		$this->query = $str;
		// echo$str;exit();
		return $this->select($str);
	}


	function selectByPegawai($username)
	{
		/** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
		//$passwd = md5($passwd);

		$str = "SELECT A.USERID, A.USERNAME, A.FULLNAME, A.USERPASS, A.LEVEL, A.MENUMARKETING, A.MENUFINANCE, 
					A.MENUPRODUCTION, A.MENUDOCUMENT, A.MENUSEARCH, A.MENUOTHERS,
					MENUEPL, MENUUWILD, MENUWP, MENUPL, MENUEL, MENUPMS, MENURS, MENUSTD
				FROM USERS A
				WHERE USERNAME = '" . $username . "' ";
		$this->query = $str;
		$this->query = $str;

		return $this->select($str);
	}

	function selectBypass($id_usr)
	{
		/** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
		//$passwd = md5($passwd);

		$str = "SELECT
				A.PEGAWAI_ID, A.USER_LOGIN_ID, D.DEPARTEMEN_ID, A.USER_GROUP_ID, B.NAMA USER_GROUP,
				   D.NRP, A.NAMA, F.NAMA JABATAN, A.EMAIL,
				   A.TELEPON, STATUS, USER_LOGIN,
				   USER_PASS, C.NAMA DEPARTEMEN, CABANG_ID,
				   B.AKSES_APP_HELPDESK_ID, E.KODE HAK_AKSES, E.NAMA HAK_AKSES_DESC
				FROM IMASYS.USER_LOGIN A
				LEFT JOIN IMASYS.USER_GROUP B ON A.USER_GROUP_ID = B.USER_GROUP_ID
                LEFT JOIN IMASYS_SIMPEG.PEGAWAI D ON A.PEGAWAI_ID = D.PEGAWAI_ID
                LEFT JOIN IMASYS_SIMPEG.DEPARTEMEN C ON D.DEPARTEMEN_ID = C.DEPARTEMEN_ID
				LEFT JOIN IMASYS.AKSES_APP_HELPDESK E ON B.AKSES_APP_HELPDESK_ID = E.AKSES_APP_HELPDESK_ID
				LEFT JOIN IMASYS_SIMPEG.PEGAWAI_JABATAN_TERAKHIR F ON A.PEGAWAI_ID = F.PEGAWAI_ID
				WHERE 1 = 1 AND B.AKSES_APP_HELPDESK_ID IS NOT NULL AND IMASYS.MD5(A.PEGAWAI_ID || 'H3LPD35K') ='" . $id_usr . "' AND STATUS = 1 ";
		$this->query = $str;

		return $this->select($str);
	}

	function selectByOperator()
	{
		/** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
		//$passwd = md5($passwd);

		$str = "SELECT
				A.PEGAWAI_ID, A.NAMA, B.NAMA JABATAN, 'H' KEHADIRAN, CASE WHEN 'H' = 'H' THEN 'offline' ELSE 'online' END STATUS_ONLINE
				FROM IMASYS_SIMPEG.PEGAWAI A
				LEFT JOIN IMASYS_SIMPEG.PEGAWAI_JABATAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
				WHERE 1 = 1 AND A.STATUS_PEGAWAI_ID IN (1,5) AND A.DEPARTEMEN_ID = '8901' ";
		$this->query = $str;

		return $this->select($str);
	}

	function login_anggit($username)
	{
		/** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
		//$passwd = md5($passwd);

		$str = "SELECT * FROM PEGAWAI WHERE PEGAWAI_ID = '" . $username . "'";
		$this->query = $str;
		// echo $str;exit();

		return $this->select($str);
	}
}
