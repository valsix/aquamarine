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

class Master extends Entity
{

	var $query;
	/**
	 * Class constructor.
	 **/
	function Master()
	{
		$this->Entity();
	}

	function selectCabang($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = " ORDER BY A.CABANG_ID ASC ")
	{
		$str = "SELECT CABANG_ID, NAMA, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE, 
					   LAST_UPDATE_USER, LAST_UPDATE_DATE, RADIUS
				  FROM CABANG A 
				WHERE 1 = 1
				";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectPegawai($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = " ORDER BY A.PEGAWAI_ID ASC ")
	{
		$str = "SELECT PEGAWAI_ID, CABANG_ID, NO_SEKAR, NRP, NIP, NAMA, NAMA_PANGGILAN, 
					   JENIS_KELAMIN, TEMPAT_LAHIR, TANGGAL_LAHIR, UNIT_KERJA, ALAMAT, 
					   NOMOR_HP, EMAIL_PRIBADI, EMAIL_BULOG, NOMOR_WA, GOLONGAN_DARAH, 
					   FOTO, CREATED_BY, CREATED_DATE, UPDATED_BY, UPDATED_DATE
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
}
