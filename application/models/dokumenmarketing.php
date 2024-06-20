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

class DokumenMarketing  extends Entity
{

	var $query;
	var $id;
	/**
	 * Class constructor.
	 **/
	function DokumenMarketing()
	{
		$this->Entity();
	}

	function insert()
	{
		$this->setField("DOCUMENT_ID", $this->getNextId("DOCUMENT_ID", "DOKUMEN_MARKETING"));

		$str = "INSERT INTO DOKUMEN_MARKETING (DOCUMENT_ID, COMPANY_NAME, VESSEL_NAME, DESCRIPTION, PATH, LAST_REVISI,TYPE_OF_SERVICE, LOCATION, DATE_OPERATION, CLASS_RULES)VALUES (
		'" . $this->getField("DOCUMENT_ID") . "',
		'" . $this->getField("COMPANY_NAME") . "',
		'" . $this->getField("VESSEL_NAME") . "',
		'" . $this->getField("DESCRIPTION") . "',
		'" . $this->getField("PATH") . "',
		NULL,
		'" . $this->getField("TYPE_OF_SERVICE") . "',
		'" . $this->getField("LOCATION") . "',
		" . $this->getField("DATE_OPERATION") . ",
		'" . $this->getField("CLASS_RULES") . "' 
	)";

		$this->id = $this->getField("DOCUMENT_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}


	function update_file()
	{
		$str = "
			UPDATE DOKUMEN_MARKETING
			SET    
			PATH ='" . $this->getField("PATH") . "'
			WHERE DOCUMENT_ID= '" . $this->getField("DOCUMENT_ID") . "'";
		$this->query = $str;

		return $this->execQuery($str);
	}


	function update()
	{
		$str = "
			UPDATE DOKUMEN_MARKETING
			SET    
			DOCUMENT_ID ='" . $this->getField("DOCUMENT_ID") . "',
			COMPANY_NAME ='" . $this->getField("COMPANY_NAME") . "',
			VESSEL_NAME ='" . $this->getField("VESSEL_NAME") . "',
			DESCRIPTION ='" . $this->getField("DESCRIPTION") . "',
			PATH ='" . $this->getField("PATH") . "',
			LAST_REVISI =NULL,
			TYPE_OF_SERVICE ='" . $this->getField("TYPE_OF_SERVICE") . "',
			LOCATION ='" . $this->getField("LOCATION") . "',
			DATE_OPERATION =" . $this->getField("DATE_OPERATION") . ",
			CLASS_RULES ='" . $this->getField("CLASS_RULES") . "'	 
			WHERE DOCUMENT_ID= '" . $this->getField("DOCUMENT_ID") . "'";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}


	function delete($statement = "")
	{
		$str = "DELETE FROM DOKUMEN_MARKETING
			WHERE DOCUMENT_ID= '" . $this->getField("DOCUMENT_ID") . "'";
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}


	function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
	{
		$str = "
			SELECT A.DOCUMENT_ID,A.COMPANY_NAME,A.VESSEL_NAME,A.DESCRIPTION,A.PATH,A.LAST_REVISI,A.TYPE_OF_SERVICE,A.LOCATION,A.DATE_OPERATION,A.CLASS_RULES
			FROM DOKUMEN_MARKETING A
			WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}


	// function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
	// {
	// 	$str = "SELECT A.DOCUMENT_ID, A.COMPANY_NAME AS COMPANY_NAME, A.VESSEL_NAME AS VESSEL_NAME, TO_CHAR(A.DATE_OPERATION, 'DAY, MONTH DD YYYY') AS DATE, A.TYPE_OF_SERVICE AS TYPE_OF_SERVICE, A.LOCATION AS LOCATION, A.CLASS_RULES AS CLASS, A.DESCRIPTION AS DESCRIPTION
	// 			FROM DOKUMEN_MARKETING A
	// 			WHERE 1=1 ";
	// 	while (list($key, $val) = each($paramsArray)) {
	// 		$str .= " AND $key = '$val'";
	// 	}

	// 	$str .= $statement . " " . $order;
	// 	$this->query = $str;
	// 	return $this->selectLimit($str, $limit, $from);
	// }


	function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
	{
		$str = "SELECT A.DOCUMENT_ID, A.COMPANY_NAME AS COMPANY_NAME, A.VESSEL_NAME AS VESSEL_NAME, A.DESCRIPTION AS DESCRIPTION, A.LAST_REVISI AS LAST_REVISI, A.TYPE_OF_SERVICE AS TYPE_OF_SERVICE,  A.LOCATION AS LOCATION, TO_CHAR(A.DATE_OPERATION, 'DAY, MONTH DD YYYY') AS DATE_OPERATION,  A.CLASS_RULES AS CLASS_RULES
				FROM DOKUMEN_MARKETING A
				WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		// echo $str;
		// exit;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
	{
		$str = "SELECT A.DOCUMENT_ID, A.COMPANY_NAME AS COMPANY_NAME, A.VESSEL_NAME AS VESSEL_NAME, A.DESCRIPTION AS DESCRIPTION, A.LAST_REVISI AS LAST_REVISI, A.TYPE_OF_SERVICE AS TYPE_OF_SERVICE,  A.LOCATION AS LOCATION, TO_CHAR(A.DATE_OPERATION, 'DAY, MONTH DD YYYY') AS DATE_OPERATION,  A.CLASS_RULES AS CLASS_RULES
				FROM DOKUMEN_MARKETING A
				WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		// echo $str;
		// exit;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsCetakExcel($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
	{
		$str = "SELECT A.DOCUMENT_ID, A.COMPANY_NAME AS COMPANY_NAME, A.VESSEL_NAME AS VESSEL_NAME, TO_CHAR(A.DATE_OPERATION, 'DAY, MONTH DD YYYY') AS DATE, A.TYPE_OF_SERVICE AS TYPE_OF_SERVICE, A.LOCATION AS LOCATION, A.CLASS_RULES AS CLASS, A.DESCRIPTION AS DESCRIPTION
				FROM DOKUMEN_MARKETING A
				WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}


	function getCountByParamsMonitoring($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM DOKUMEN_MARKETING A WHERE 1=1 " . $statement;
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = 	'$val' ";
		}
		$this->query = $str;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}
}
