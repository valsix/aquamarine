<?
/* *******************************************************************************************************
MODUL NAME 			: MTSN LAWANG
FILE NAME 			:
AUTHOR				:
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			:
***************************************************************************************************** */

/***
 * Entity-base class untuk mengimplementasikan tabel kategori.
 *
 ***/
include_once(APPPATH . '/models/Entity.php');

class Dokumen extends Entity
{

	var $query;
	/**
	 * Class constructor.
	 **/
	function Dokumen()
	{
		$this->Entity();
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("dokumen_id", $this->getNextId("dokumen_id", "dokumen"));
		//'".$this->getField("FOTO")."',  FOTO,
		$str = "INSERT INTO DOKUMEN(
				DOKUMEN_ID, NAMA, KETERANGAN, LINK_FILE, CREATED_BY, CREATED_DATE)
				VALUES (
					'" . $this->getField("DOKUMEN_ID") . "',
					'" . $this->getField("NAMA") . "',
					'" . $this->getField("KETERANGAN") . "',
					'" . $this->getField("LINK_FILE") . "',
					'" . $this->getField("CREATED_BY") . "',
						CURRENT_DATE
					)";

		$this->query = $str;
		$this->id = $this->getField("DOKUMEN_ID");
		return $this->execQuery($str);
	}


	function update()
	{
		$str = "UPDATE DOKUMEN
				SET
					NAMA='" . $this->getField("NAMA") . "',
					KETERANGAN='" . $this->getField("KETERANGAN") . "',
					LINK_FILE='" . $this->getField("LINK_FILE") . "',
					UPDATE_BY='" . $this->getField("update_by") . "',
					UPDATE_DATE= CURRENT_DATE
				WHERE DOKUMEN_ID='" . $this->getField("DOKUMEN_ID") . "';
							";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updateByField()
	{

		//apa ini
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE dokumen A SET
				  " . $this->getField("FIELD") . " = '" . $this->getField("FIELD_VALUE") . "',
						   update_by  = '" . $this->getField("update_by") . "',
						   update_date  = CURRENT_DATE
				WHERE dokumen_id = " . $this->getField("dokumen_id") . "
				";
		$this->query = $str;

		return $this->execQuery($str);
	}


	function delete()
	{
		$str = "DELETE FROM DOCUMENT
                WHERE DOCUMENT_ID = " . $this->getField("DOCUMENT_ID") . "";

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
	function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "")
	{
		$str = "SELECT DOKUMEN_ID, NAMA, KETERANGAN, LINK_FILE, CREATED_BY, CREATED_DATE, UPDATE_BY, UPDATE_DATE
				FROM DOKUMEN
				WHERE 1 = 1
				";
		//, FOTO
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	/**
	 * Hitung jumlah record berdasarkan parameter (array).
	 * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy")
	 * @return long Jumlah record yang sesuai kriteria
	 **/
	function getCountByParams($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(A.DOCUMENT_ID) AS ROWCOUNT FROM DOCUMENT A
		        WHERE A.DOCUMENT_ID IS NOT NULL " . $statement;

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$this->select($str);
		$this->query = $str;
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}

	function selectByParamsDokument($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
	{
		$str = "SELECT A.DOCUMENT_ID,A.CATEGORY,A.NAME,A.DESCRIPTION,A.PATH,A.LAST_REVISI,A.EXPIRED_DATE,TO_CHAR(A.EXPIRED_DATE,'Day,Month dd yyyy') EXP
				FROM DOCUMENT A
				WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsDokumentCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
	{
		$str = "SELECT A.DOCUMENT_ID,A.CATEGORY,A.NAME,A.DESCRIPTION,A.PATH,A.LAST_REVISI,A.EXPIRED_DATE,TO_CHAR(A.EXPIRED_DATE,'Day,Month dd yyyy') EXP
				FROM DOCUMENT A
				WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}


	function getCountByParamsLike($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(dokumen_id) AS ROWCOUNT FROM dokumen A
		        WHERE dokumen_id IS NOT NULL " . $statement;
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
