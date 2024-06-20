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

class Tender extends Entity
{

	var $query;
	/**
	 * Class constructor.
	 **/
	function Tender()
	{
		$this->Entity();
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TENDER_ID", $this->getNextId("TENDER_ID", "TENDER"));
		
		$str = "
			INSERT INTO TENDER(
				TENDER_ID, COMPANY_ID, PROJECT_NAME, PROJECT_NO, ISSUED_DATE, REGISTER_DATE, 
				PQ_DATE, PREBID_DATE, SUBMISSION_DATE, OPENING1_DATE, OPENING2_DATE, 
				ANNOUNCEMENT, LOA, REMARK, CREATED_BY, CREATED_DATE)
			VALUES (
				'" . $this->getField("TENDER_ID") . "',
				" . $this->getField("COMPANY_ID") . ",
				'" . $this->getField("PROJECT_NAME") . "',
				'" . $this->getField("PROJECT_NO") . "',
				" . $this->getField("ISSUED_DATE") . ",
				" . $this->getField("REGISTER_DATE") . ",
				" . $this->getField("PQ_DATE") . ",
				" . $this->getField("PREBID_DATE") . ",
				" . $this->getField("SUBMISSION_DATE") . ",
				" . $this->getField("OPENING1_DATE") . ",
				" . $this->getField("OPENING2_DATE") . ",
				'" . $this->getField("ANNOUNCEMENT") . "',
				'" . $this->getField("LOA") . "',
				'" . $this->getField("REMARK") . "',
				'" . $this->getField("CREATED_BY") . "',
					CURRENT_DATE
			)";

		$this->query = $str;
		$this->id = $this->getField("TENDER_ID");
		return $this->execQuery($str);
	}

	function insertProject()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TENDER_ID", $this->getNextId("TENDER_ID", "TENDER"));
		
		$str = "
			INSERT INTO TENDER(
				TENDER_ID, COMPANY_ID, PROJECT_NAME, PROJECT_NO, ANNOUNCEMENT, CREATED_BY, CREATED_DATE)
			VALUES (
				'" . $this->getField("TENDER_ID") . "',
				" . $this->getField("COMPANY_ID") . ",
				'" . $this->getField("PROJECT_NAME") . "',
				'" . $this->getField("PROJECT_NO") . "',
				'" . $this->getField("ANNOUNCEMENT") . "',
				'" . $this->getField("CREATED_BY") . "',
				CURRENT_DATE
			)";

		$this->query = $str;
		$this->id = $this->getField("TENDER_ID");
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "UPDATE TENDER
				SET
					PROJECT_NAME='" . $this->getField("PROJECT_NAME") . "',
					COMPANY_ID=" . $this->getField("COMPANY_ID") . ",
					PROJECT_NO='" . $this->getField("PROJECT_NO") . "',
					ISSUED_DATE=" . $this->getField("ISSUED_DATE") . ",
					REGISTER_DATE=" . $this->getField("REGISTER_DATE") . ",
					PQ_DATE=" . $this->getField("PQ_DATE") . ",
					PREBID_DATE=" . $this->getField("PREBID_DATE") . ",
					SUBMISSION_DATE=" . $this->getField("SUBMISSION_DATE") . ",
					OPENING1_DATE=" . $this->getField("OPENING1_DATE") . ",
					OPENING2_DATE=" . $this->getField("OPENING2_DATE") . ",
					ANNOUNCEMENT='" . $this->getField("ANNOUNCEMENT") . "',
					LOA='" . $this->getField("LOA") . "',
					REMARK='" . $this->getField("REMARK") . "',
					UPDATED_BY='" . $this->getField("UPDATED_BY") . "',
					UPDATED_DATE= CURRENT_DATE
				WHERE TENDER_ID='" . $this->getField("TENDER_ID") . "';
							";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updateProject()
	{
		$str = "UPDATE TENDER
				SET
					PROJECT_NAME='" . $this->getField("PROJECT_NAME") . "',
					COMPANY_ID=" . $this->getField("COMPANY_ID") . ",
					PROJECT_NO='" . $this->getField("PROJECT_NO") . "',
					ANNOUNCEMENT='" . $this->getField("ANNOUNCEMENT") . "',
					UPDATED_BY='" . $this->getField("UPDATED_BY") . "',
					UPDATED_DATE= CURRENT_DATE
				WHERE TENDER_ID='" . $this->getField("TENDER_ID") . "';
							";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updatePath()
	{

		//apa ini
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE TENDER A SET
					DOC_TENDER_PATH='" . $this->getField("DOC_TENDER_PATH") . "',
					PERSIAPAN_PATH='" . $this->getField("PERSIAPAN_PATH") . "',
					PELAKSANAAN_PATH='" . $this->getField("PELAKSANAAN_PATH") . "',
					BA_PENY_PATH='" . $this->getField("BA_PENY_PATH") . "'
				WHERE TENDER_ID = " . $this->getField("TENDER_ID") . "
				";
		$this->query = $str;

		return $this->execQuery($str);
	}

	function updatePrebidPath()
	{

		//apa ini
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE TENDER A SET
					PREBID_PATH='" . $this->getField("PREBID_PATH") . "'
				WHERE TENDER_ID = " . $this->getField("TENDER_ID") . "
				";
		$this->query = $str;

		return $this->execQuery($str);
	}
	function updatePathBaru()
	{

		//apa ini
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE TENDER A SET
					".$this->getField("COLOM")."='" . $this->getField("DOK") . "'
				WHERE TENDER_ID = " . $this->getField("TENDER_ID") . "
				";
		$this->query = $str;

		return $this->execQuery($str);
	}
	function updateUrut()
	{

		//apa ini
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE TENDER  SET
					URUT=  '" . $this->getField("URUT") . "'
				WHERE TENDER_ID = '" . $this->getField("TENDER_ID") . "'
				";
		$this->query = $str;

		return $this->execQuery($str);
	}

	function delete()
	{
		$str = "DELETE FROM TENDER
                WHERE TENDER_ID = " . $this->getField("TENDER_ID") . "";

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

	function selectByParamsUrut($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "")
	{
		$str = " SELECT A.TENDER_ID,ROW_NUMBER() OVER (ORDER BY  (A.ISSUED_DATE IS NULL) DESC,A.ISSUED_DATE ASC) URUT FROM TENDER A
				WHERE 1 = 1
				";
		//, FOTO
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement . " " ;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}
	

	function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "")
	{
		$str = "SELECT TENDER_ID, A.COMPANY_ID, B.NAME COMPANY_NAME, PROJECT_NAME, PROJECT_NO, ISSUED_DATE, REGISTER_DATE, 
				PQ_DATE, PREBID_DATE, PREBID_PATH, SUBMISSION_DATE, OPENING1_DATE, OPENING2_DATE, 
				ANNOUNCEMENT, LOA, REMARK, PERSIAPAN_PATH, PELAKSANAAN_PATH, 
				BA_PENY_PATH, DOC_TENDER_PATH,A.URUT,A.DOK_ADMINITISTRASI,A.DOK_TEKNIS,A.DOK_KOMERSIAL
				FROM TENDER A
				LEFT JOIN COMPANY B ON A.COMPANY_ID=B.COMPANY_ID
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
		$str = "SELECT COUNT(A.TENDER_ID) AS ROWCOUNT FROM TENDER A
		        WHERE A.TENDER_ID IS NOT NULL " . $statement;

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
		$str = "SELECT COUNT(TENDER_ID) AS ROWCOUNT FROM TENDER A
		        WHERE TENDER_ID IS NOT NULL " . $statement;
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
