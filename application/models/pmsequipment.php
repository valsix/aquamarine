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

class PmsEquipment  extends Entity
{

	var $query;
	var $id;
	/**
	 * Class constructor.
	 **/
	function PmsEquipment()
	{
		$this->Entity();
	}

	function insert()
	{
		$this->setField("PMS_ID", $this->getNextId("PMS_ID", "PMS_EQUIPMENT"));

		$str = "INSERT INTO PMS_EQUIPMENT (PMS_ID, EQUIP_ID, CREATED_BY, CREATED_DATE)VALUES (
				'" . $this->getField("PMS_ID") . "',
				'" . $this->getField("EQUIP_ID") . "',
				'" . $this->getField("CREATED_BY") . "',
				CURRENT_DATE 
    	)";

		$this->id = $this->getField("PMS_ID");
		$this->query = $str;
		// echo $str;
		// exit;
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE PMS_EQUIPMENT
		SET    
			EQUIP_ID ='" . $this->getField("EQUIP_ID") . "',
            UPDATED_BY  ='".$this->getField("UPDATED_BY")."',
            UPDATED_DATE = CURRENT_DATE 
		WHERE PMS_ID= '" . $this->getField("PMS_ID") . "'";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}
	function update_path()
	{
		$str = "
		UPDATE PMS_EQUIPMENT
		SET    
		
		PIC_PATH ='" . $this->getField("PIC_PATH") . "'
		
		WHERE PMS_ID= '" . $this->getField("PMS_ID") . "'";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement = "")
	{
		$str = "DELETE FROM PMS_EQUIPMENT
		WHERE PMS_ID= '" . $this->getField("PMS_ID") . "'";
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.PMS_ID ASC")
	{
		$str = "SELECT A.PMS_ID,
				(SELECT EC_NAME FROM EQUIP_CATEGORY X WHERE X.EC_ID = B.EC_ID) CATEGORY,
				B.EQUIP_ID,
				B.EQUIP_NAME, B.SERIAL_NUMBER,
				B.EQUIP_SPEC AS SPECIFICATION,
				B.EQUIP_QTY AS QUANTITY,
				B.EQUIP_STORAGE AS STORAGE,B.EQUIP_DATEIN,B.EQUIP_LASTCAL,EQUIP_NEXTCAL,
				TO_CHAR(C.DATE_TEST, 'DD-MM-YYYY') DATE_TEST,
				TO_CHAR(C.DATE_NEXT_TEST, 'DD-MM-YYYY') DATE_NEXT_TEST, 
				B.EQUIP_CONDITION CONDITION, C.REMARKS,B.PIC_PATH,B.EQUIP_REMARKS REMARKS2,
				CASE C.TIME_TEST
					WHEN '1' THEN 'Daily'
					WHEN '2' THEN 'Weekly'
					WHEN '3' THEN 'Monthly'
					WHEN '4' THEN '6 Monthly'
					WHEN '5' THEN 'Yearly'
					WHEN '6' THEN '2,5 Yearly'
					WHEN '7' THEN '5 Yearly'
					ELSE ''
				END TIME_TEST,
				CASE 
					WHEN EXISTS (
						SELECT 1 FROM PMS_EQUIP_DETIL X WHERE A.PMS_ID = X.PMS_ID
						AND X.DATE_NEXT_TEST < CURRENT_DATE
					) THEN 'red' 
					ELSE '' 
				END STATUS
 				FROM PMS_EQUIPMENT A
				LEFT JOIN EQUIPMENT_LIST B ON A.EQUIP_ID = B.EQUIP_ID
				LEFT JOIN (
					SELECT * FROM PMS_EQUIP_DETIL
					WHERE PMS_DETIL_ID IN (
						SELECT MIN(PMS_DETIL_ID) FROM PMS_EQUIP_DETIL 
						WHERE DATE_NEXT_TEST < CURRENT_DATE 
						GROUP BY PMS_ID
					)
				) C ON A.PMS_ID = C.PMS_ID
				WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsMonitoringNextTest($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.PMS_ID ASC")
	{
		$str = "SELECT A.PMS_ID,
				D.PMS_DETIL_ID,
				B.EQUIP_ID ,
				C.EC_ID, B.PIC_PATH,
				C.EC_NAME AS CATEGORY,
				B.EQUIP_NAME, B.SERIAL_NUMBER,
				(SELECT C.EQUIP_NAME FROM EQUIPMENT_LIST C WHERE B.EQUIP_PARENT_ID = C.EQUIP_ID) AS PART_OF_EQUIPMENT,
				B.EQUIP_SPEC AS SPECIFICATION,
				B.EQUIP_QTY AS QUANTITY,
				B.EQUIP_ITEM AS ITEM,
				TO_CHAR(B.EQUIP_DATEIN, 'DD-MM-YYYY') AS INCOMING_DATE,
				TO_CHAR(B.EQUIP_LASTCAL, 'DD-MM-YYYY') AS LAST_CALIBRATION,
				TO_CHAR(B.EQUIP_NEXTCAL, 'DD-MM-YYYY') AS NEXT_CALIBRATION,
				TO_CHAR(D.DATE_NEXT_TEST, 'DD-MM-YYYY') AS NEXT_TEST,
				(SELECT COUNT(C.EQUIP_ID) FROM EQUIPMENT_LIST C WHERE C.EQUIP_PARENT_ID = B.EQUIP_ID) AS QTY_DETAIL_EQUIPMENT,
				B.EQUIP_CONDITION AS CONDITION,
				B.EQUIP_STORAGE AS STORAGE,
				B.EQUIP_PRICE AS PRICE,
				B.EQUIP_REMARKS AS REMARKS,
				CASE 
					WHEN EXISTS (
						SELECT 1 FROM PMS_EQUIP_DETIL X WHERE A.PMS_ID = X.PMS_ID
						AND X.DATE_NEXT_TEST < CURRENT_DATE
					) THEN 'red' 
					ELSE '' 
				END STATUS
 				FROM PMS_EQUIPMENT A
				LEFT JOIN EQUIPMENT_LIST B ON A.EQUIP_ID = B.EQUIP_ID
				LEFT JOIN EQUIP_CATEGORY C ON B.EC_ID = C.EC_ID
				LEFT JOIN PMS_EQUIP_DETIL D ON A.PMS_ID = D.PMS_ID
				WHERE 1=1 AND D.DATE_NEXT_TEST < CURRENT_DATE + INTERVAL '3 MONTH' ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsMonitoringCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.PMS_ID ASC")
	{
		$str = "SELECT 
					A.PMS_ID,C.EQUIP_NAME,B.NAME,TO_CHAR(B.DATE_TEST, 'DD-MM-YYYY') DATE_TEST,
					TO_CHAR(B.DATE_NEXT_TEST, 'DD-MM-YYYY') DATE_NEXT_TEST,B.TIME_TEST,
					B.COMPENENT_PERSON,B.LINK_FILE,C.EQUIP_DATEIN
				FROM PMS_EQUIPMENT A
				INNER JOIN PMS_EQUIP_DETIL B ON A.PMS_ID = B.PMS_ID
				INNER JOIN EQUIPMENT_LIST C ON A.EQUIP_ID = C.EQUIP_ID
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
		$str = "SELECT COUNT(1) AS ROWCOUNT 
			FROM PMS_EQUIPMENT A 
			LEFT JOIN EQUIPMENT_LIST B ON A.EQUIP_ID = B.EQUIP_ID
			LEFT JOIN  EQUIP_CATEGORY C ON B.EC_ID = C.EC_ID
			WHERE 1=1 " . $statement;
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

	function getCountByParamsTest($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
 				FROM PMS_EQUIPMENT A
				LEFT JOIN EQUIPMENT_LIST B ON A.EQUIP_ID = B.EQUIP_ID
				LEFT JOIN EQUIP_CATEGORY C ON B.EC_ID = C.EC_ID
				LEFT JOIN PMS_EQUIP_DETIL D ON A.PMS_ID = D.PMS_ID
			WHERE 1=1 AND D.DATE_NEXT_TEST < CURRENT_DATE + INTERVAL '3 MONTH' 
		" . $statement;
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
