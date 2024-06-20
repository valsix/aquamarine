<?
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

/***
 * Entity-base class untuk mengimplementasikan tabel PANGKAT.
 * 
 ***/
include_once("Entity.php");

class Pegawai extends Entity
{

	var $query;
	/**
	 * Class constructor.
	 **/
	function Pegawai()
	{
		$this->Entity();
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PEGAWAI_ID", $this->getNextId("PEGAWAI_ID", "PEGAWAI"));
		$str = "INSERT INTO PEGAWAI(
            PEGAWAI_ID, CABANG_ID, NRP, NIP, NAMA, NAMA_PANGGILAN, JENIS_KELAMIN,
            TEMPAT_LAHIR, TANGGAL_LAHIR, UNIT_KERJA, ALAMAT, NOMOR_HP, EMAIL_PRIBADI,
            EMAIL_BULOG, NOMOR_WA, GOLONGAN_DARAH, CREATED_BY, CREATED_DATE)
			VALUES (
			'" . $this->getField("PEGAWAI_ID") . "',
			'" . $this->getField("CABANG_ID") . "',
			'" . $this->getField("NRP") . "',
			'" . $this->getField("NIP") . "',
			'" . $this->getField("NAMA") . "',
			'" . $this->getField("NAMA_PANGGILAN") . "',
			'" . $this->getField("JENIS_KELAMIN") . "',
			'" . $this->getField("TEMPAT_LAHIR") . "',
			" . $this->getField("TANGGAL_LAHIR") . ",
			'" . $this->getField("UNIT_KERJA") . "',
			'" . $this->getField("ALAMAT") . "',
			'" . $this->getField("NOMOR_HP") . "',
			'" . $this->getField("EMAIL_PRIBADI") . "',
			'" . $this->getField("EMAIL_BULOG") . "',
			'" . $this->getField("NOMOR_WA") . "',
			'" . $this->getField("GOLONGAN_DARAH") . "',
			'" . $this->getField("CREATED_BY") . "',CURRENT_DATE)";

		$this->query = $str;
		$this->id = $this->getField("PEGAWAI_ID");
		//echo $str;
		return $this->execQuery($str);
	}

	function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PEGAWAI A SET
				  " . $this->getField("FIELD") . " = '" . $this->getField("FIELD_VALUE") . "',
						   LAST_UPDATED_USER  = '" . $this->getField("LAST_UPDATED_USER") . "',
						   LAST_UPDATED_DATE  = CURRENT_DATE
				WHERE PEGAWAI_ID = " . $this->getField("PEGAWAI_ID") . "
				";
		$this->query = $str;

		return $this->execQuery($str);
	}


	function validasi()
	{
		$str = "
				UPDATE PEGAWAI
				SET 
				VALIDASI='" . $this->getField("VALIDASI") . "',
				UPDATED_BY='" . $this->getField("UPDATED_BY") . "',
				UPDATED_DATE=CURRENT_DATE
				WHERE  PEGAWAI_ID = " . $this->getField("PEGAWAI_ID") . "
			 ";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}



	function validasiSetuju()
	{
		$str = "
				UPDATE PEGAWAI
				SET 
				VALIDASI='" . $this->getField("VALIDASI") . "',
				NO_SEKAR	= (SELECT MAX(NO_SEKAR::INT) + 1 FROM PEGAWAI)::text,
				UPDATED_BY='" . $this->getField("UPDATED_BY") . "',
				UPDATED_DATE=CURRENT_DATE
				WHERE  PEGAWAI_ID = " . $this->getField("PEGAWAI_ID") . "
			 ";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}


	function update()
	{
		$str = "
				UPDATE PEGAWAI
				SET 
				NRP='" . $this->getField("NRP") . "',
				NIP='" . $this->getField("NIP") . "',
				NAMA='" . $this->getField("NAMA") . "',
				NAMA_PANGGILAN='" . $this->getField("NAMA_PANGGILAN") . "',
				JENIS_KELAMIN='" . $this->getField("JENIS_KELAMIN") . "',
				TEMPAT_LAHIR='" . $this->getField("TEMPAT_LAHIR") . "',
				TANGGAL_LAHIR=" . $this->getField("TANGGAL_LAHIR") . ",
				UNIT_KERJA='" . $this->getField("UNIT_KERJA") . "',
				ALAMAT='" . $this->getField("ALAMAT") . "',
				NOMOR_HP='" . $this->getField("NOMOR_HP") . "',
				EMAIL_PRIBADI='" . $this->getField("EMAIL_PRIBADI") . "',
				EMAIL_BULOG='" . $this->getField("EMAIL_BULOG") . "',
				NOMOR_WA='" . $this->getField("NOMOR_WA") . "',
				UPDATED_BY='" . $this->getField("UPDATED_BY") . "',
				UPDATED_DATE=CURRENT_DATE
				WHERE  PEGAWAI_ID = " . $this->getField("PEGAWAI_ID") . "
			 ";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}



	function koreksi()
	{
		$str = "
				UPDATE PEGAWAI
				SET 
				NRP='" . $this->getField("NRP") . "',
				NIP='" . $this->getField("NIP") . "',
				UPDATED_BY='" . $this->getField("UPDATED_BY") . "',
				UPDATED_DATE=CURRENT_DATE
				WHERE  PEGAWAI_ID = " . $this->getField("PEGAWAI_ID") . "
			 ";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}


	function updateProfil()
	{
		$str = "  
				UPDATE PEGAWAI
				SET 
				NAMA='" . $this->getField("NAMA") . "',
				NAMA_PANGGILAN='" . $this->getField("NAMA_PANGGILAN") . "',
				TEMPAT_LAHIR='" . $this->getField("TEMPAT_LAHIR") . "',
				TANGGAL_LAHIR= " . $this->getField("TANGGAL_LAHIR") . ",
				ALAMAT='" . $this->getField("ALAMAT") . "',
				NOMOR_HP='" . $this->getField("NOMOR_HP") . "',
				EMAIL_PRIBADI='" . $this->getField("EMAIL_PRIBADI") . "',
				EMAIL_BULOG='" . $this->getField("EMAIL_BULOG") . "',
				NOMOR_WA='" . $this->getField("NOMOR_WA") . "',
				CABANG_ID='" . $this->getField("CABANG_ID") . "',
				UNIT_KERJA='" . $this->getField("UNIT_KERJA") . "',
				GOLONGAN_DARAH='" . $this->getField("GOLONGAN_DARAH") . "',
				UPDATED_BY='" . $this->getField("UPDATED_BY") . "',
				UPDATED_DATE=CURRENT_DATE
				WHERE  NIP = '" . $this->getField("NIP") . "'
			 ";
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
	}



	function updateFoto()
	{
		$str = "UPDATE PEGAWAI
				SET   	   FOTO        = '" . $this->getField("FOTO") . "'
				WHERE  NIP = '" . $this->getField("NIP") . "'
			 ";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updateLinkPegawai()
	{
		$str = "UPDATE PEGAWAI
				SET   	   LINK_FILE        = '" . $this->getField("LINK_FILE") . "'
				WHERE  PEGAWAI_ID = '" . $this->getField("PEGAWAI_ID") . "'
			 ";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updateDetil()
	{
		$str = "
				UPDATE PEGAWAI_DETIL
				SET   	   NAMA        = '" . $this->getField("NAMA") . "'
				WHERE  PEGAWAI_DETIL_ID = '" . (int) $this->getField("PEGAWAI_DETIL_ID") . "'
			 ";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function delete()
	{
		$str = "DELETE FROM PEGAWAI
                WHERE 
                  PEGAWAI_ID = " . $this->getField("PEGAWAI_ID") . "";

		$this->query = $str;
		return $this->execQuery($str);
	}



	/** 
	 * Cari record berdasarkan array parameter dan limit tampilan 
	 * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
	 * @param int limit Jumlah maksimal record yang akan diambil 
	 * @param int from Awal record yang diambil 
	 * @return boolean True jika sukses, false jika tidak 
	 **/
	function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = " ORDER BY PEGAWAI_ID ASC ")
	{
		$str = "SELECT PEGAWAI_ID, CABANG_ID, NRP, NIP, NO_SEKAR, NAMA, NAMA_PANGGILAN, JENIS_KELAMIN,
       TEMPAT_LAHIR, TANGGAL_LAHIR, UNIT_KERJA, ALAMAT, NOMOR_HP, EMAIL_PRIBADI,
       EMAIL_BULOG, NOMOR_WA, GOLONGAN_DARAH, CREATED_BY, CREATED_DATE, UPDATED_BY, UPDATED_DATE, FOTO
  			FROM PEGAWAI A
				WHERE 1 = 1
				";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;

		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsLoginTerakhir($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.LOGIN_TERAKHIR DESC")
	{
		$str = "
				SELECT PEGAWAI_ID, NAMA, CABANG, JABATAN, LOGIN_TERAKHIR
  				FROM PEGAWAI_LOGIN_TERAKHIR A   
				WHERE 1 = 1
				";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;

		return $this->selectLimit($str, $limit, $from);
	}



	function getCountByParamsLoginTerakhir($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM PEGAWAI_LOGIN_TERAKHIR A
		        WHERE 0=0 " . $statement;

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}



	function getCountByParams($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM PEGAWAI A

		        WHERE PEGAWAI_ID IS NOT NULL " . $statement;

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}

	function getCountByParamsLike($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM PEGAWAI

		        WHERE PEGAWAI_ID IS NOT NULL " . $statement;
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key LIKE '%$val%' ";
		}

		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}
}
