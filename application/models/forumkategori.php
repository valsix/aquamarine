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
 * Entity-base class untuk mengimplementasikan tabel FORUM_KATEGORI.
 * 
 ***/
include_once(APPPATH . '/models/Entity.php');

class ForumKategori extends Entity
{

	var $query;
	/**
	 * Class constructor.
	 **/
	function ForumKategori()
	{
		$this->Entity();
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FORUM_KATEGORI_ID", $this->getNextId("FORUM_KATEGORI_ID", "FORUM_KATEGORI"));
		$str = "
					INSERT INTO FORUM_KATEGORI (
					   FORUM_KATEGORI_ID, FORUM_KATEGORI_PARENT_ID, NAMA, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE)
 			  	VALUES (
				  '" . $this->getField("FORUM_KATEGORI_ID") . "',
				  '" . $this->getField("FORUM_KATEGORI_PARENT_ID") . "',
				  '" . $this->getField("NAMA") . "',
				  '" . $this->getField("KETERANGAN") . "',
				  '" . $this->getField("LAST_CREATE_USER") . "',
				  CURRENT_DATE
				)";
		//echo $str;
		$this->query = $str;
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "
				UPDATE FORUM_KATEGORI
				SET    
					   NAMA= '" . $this->getField("NAMA") . "',
					   KETERANGAN= '" . $this->getField("KETERANGAN") . "',
					   LAST_UPDATE_USER  = '" . $this->getField("LAST_UPDATE_USER") . "',
					   LAST_UPDATE_DATE  = CURRENT_DATE
				WHERE  FORUM_KATEGORI_ID     = '" . $this->getField("FORUM_KATEGORI_ID") . "'

			 ";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function delete()
	{
		$str = "DELETE FROM FORUM_KATEGORI
                WHERE 
                  FORUM_KATEGORI_ID = '" . $this->getField("FORUM_KATEGORI_ID") . "'";

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
	function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "")
	{
		$str = "SELECT 
					A.FORUM_KATEGORI_ID, A.NAMA, A.KETERANGAN,
					COALESCE(JUMLAH, 0) POSTS, B.TANGGAL, B.TANGGAL TERAKHIR_POSTING
					FROM FORUM_KATEGORI A
					LEFT JOIN 
					(SELECT
						FORUM_KATEGORI_ID, COUNT(1) JUMLAH, TO_CHAR(MAX(COALESCE(X.LAST_UPDATE_DATE, X.LAST_CREATE_DATE)), 'DD-MM-YYYY') TANGGAL
						FROM FORUM X
						GROUP BY FORUM_KATEGORI_ID) B ON A.FORUM_KATEGORI_ID = B.FORUM_KATEGORI_ID
					WHERE A.FORUM_KATEGORI_ID IS NOT NULL
				";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement;
		$str .= " ORDER BY NAMA ASC";

		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsParent($paramsArray = array(), $limit = -1, $from = -1, $statement = "")
	{
		$str = "
					SELECT 
					FORUM_KATEGORI_PARENT_ID, (array_agg(NAMA))[1] AS NAMA, (array_agg(KETERANGAN))[1] AS KETERANGAN
					FROM FORUM_KATEGORI WHERE FORUM_KATEGORI_ID IS NOT NULL
				";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement . " GROUP BY FORUM_KATEGORI_PARENT_ID";

		$str .= " ORDER BY NAMA ASC";
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function getNavigasi($kategori_id, $forum_id = 0, $tampil_akhir = 0)
	{
		$str = " SELECT AMBIL_NAVIGASI_FORUM_KATEGORI('" . $kategori_id . "', '" . $forum_id . "', '" . $tampil_akhir . "') NAVIGASI FROM DUAL ";


		$this->select($str);
		if ($this->firstRow())
			return $this->getField("NAVIGASI");
		else
			return "";
	}

	function selectByParamsLike($paramsArray = array(), $limit = -1, $from = -1, $statement = "")
	{
		$str = "	SELECT 
					FORUM_KATEGORI_ID, FORUM_KATEGORI_PARENT_ID, NAMA, KETERANGAN
					FROM FORUM_KATEGORI WHERE FORUM_KATEGORI_ID IS NOT NULL
			    ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key LIKE '%$val%' ";
		}

		$this->query = $str;
		$str .= $statement . " ORDER BY FORUM_KATEGORI_PARENT_ID ASC";
		return $this->selectLimit($str, $limit, $from);
	}
	/** 
	 * Hitung jumlah record berdasarkan parameter (array). 
	 * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
	 * @return long Jumlah record yang sesuai kriteria 
	 **/
	function getCountByParams($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(A.FORUM_KATEGORI_ID) AS ROWCOUNT FROM FORUM_KATEGORI A
					LEFT JOIN 
					(SELECT
						FORUM_KATEGORI_ID, COUNT(1) JUMLAH, TO_CHAR(MAX(COALESCE(X.LAST_UPDATE_DATE, X.LAST_CREATE_DATE)), 'YYYY-MM-DD') TANGGAL
						FROM FORUM X
						GROUP BY FORUM_KATEGORI_ID) B ON A.FORUM_KATEGORI_ID = B.FORUM_KATEGORI_ID
					WHERE A.FORUM_KATEGORI_ID IS NOT NULL " . $statement;

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
		$str = "SELECT COUNT(FORUM_KATEGORI_ID) AS ROWCOUNT FROM FORUM_KATEGORI
		        WHERE FORUM_KATEGORI_ID IS NOT NULL " . $statement;
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
