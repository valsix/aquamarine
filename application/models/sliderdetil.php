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

class SliderDetil extends Entity
{

	var $query;
	/**
	 * Class constructor.
	 **/
	function SliderDetil()
	{
		$this->Entity();
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SLIDER_DETIL_ID", $this->getNextId("SLIDER_DETIL_ID", "SLIDER_DETIL"));
		//'".$this->getField("FOTO")."',  FOTO,
		$str = "INSERT INTO SLIDER_DETIL (
				   SLIDER_DETIL_ID, SLIDER_ID, NAMA, LINK_FILE,
				   LAST_CREATE_USER, LAST_CREATE_DATE)
   				VALUES (
				  '" . $this->getField("SLIDER_DETIL_ID") . "',
				  '" . $this->getField("SLIDER_ID") . "',
				  '" . $this->getField("NAMA") . "',
				  '" . $this->getField("LINK_FILE") . "',
				  '" . $this->getField("LAST_CREATE_USER") . "',
				  CURRENT_TIMESTAMP
				)";
		$this->id = $this->getField("SLIDER_DETIL_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "UPDATE SLIDER_DETIL
				SET    SLIDER_ID = '" . $this->getField("SLIDER_ID") . "',
					   NAMA = '" . $this->getField("NAMA") . "',
					   LINK_FILE = '" . $this->getField("LINK_FILE") . "',
				WHERE  SLIDER_DETIL_ID = '" . $this->getField("SLIDER_DETIL_ID") . "'

			 ";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function delete()
	{
		$str = "DELETE FROM SLIDER_DETIL
                WHERE 
                  SLIDER_DETIL_ID = " . $this->getField("SLIDER_DETIL_ID") . "";

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
		$str = "SELECT 
				SLIDER_DETIL_ID, SLIDER_ID, NAMA, LINK_FILE,
				   LAST_CREATE_USER, TO_CHAR(LAST_CREATE_DATE, 'DD-MM-YYYY') HARI, TO_CHAR(LAST_CREATE_DATE, 'HH24:MI') JAM
				FROM SLIDER_DETIL A
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

	function selectByParamsLike($paramsArray = array(), $limit = -1, $from = -1, $statement = "")
	{
		$str = "SELECT 
				SLIDER_DETIL_ID, SLIDER_ID, NAMA, LINK_FILE,
				   LAST_CREATE_USER, TO_CHAR(LAST_CREATE_DATE, 'DD-MM-YYYY') HARI, TO_CHAR(LAST_CREATE_DATE, 'HH24:MI') JAM
				FROM SLIDER_DETIL A
				WHERE 1 = 1
			    ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key LIKE '%$val%' ";
		}

		$this->query = $str;
		$str .= $statement . " ORDER BY TANGGAL ASC";
		return $this->selectLimit($str, $limit, $from);
	}
	/** 
	 * Hitung jumlah record berdasarkan parameter (array). 
	 * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
	 * @return long Jumlah record yang sesuai kriteria 
	 **/
	function getCountByParams($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(SLIDER_DETIL_ID) AS ROWCOUNT FROM SLIDER_DETIL A
		        WHERE SLIDER_DETIL_ID IS NOT NULL " . $statement;

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

	function getCountByParamsLike($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(SLIDER_DETIL_ID) AS ROWCOUNT FROM SLIDER_DETIL A
		        WHERE SLIDER_DETIL_ID IS NOT NULL " . $statement;
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
