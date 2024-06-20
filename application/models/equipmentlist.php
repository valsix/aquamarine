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

class EquipmentList  extends Entity
{

	var $query;
	var $id;
	/**
	 * Class constructor.
	 **/
	function EquipmentList()
	{
		$this->Entity();
	}


	
	function insert()
	{
		$this->setField("EQUIP_ID", $this->getNextId("EQUIP_ID", "EQUIPMENT_LIST"));

		$str = "INSERT INTO EQUIPMENT_LIST (EQUIP_ID, EQUIP_PARENT_ID, EC_ID, EQUIP_NAME, EQUIP_QTY, EQUIP_ITEM,        EQUIP_SPEC, EQUIP_DATEIN, EQUIP_LASTCAL, EQUIP_NEXTCAL, EQUIP_CONDITION,        EQUIP_STORAGE, EQUIP_REMARKS, EQUIP_PRICE, SERIAL_NUMBER,BARCODE)VALUES (
		'" . $this->getField("EQUIP_ID") . "',
		" . $this->getField("EQUIP_PARENT_ID") . ",
		'" . $this->getField("EC_ID") . "',
		'" . $this->getField("EQUIP_NAME") . "',
	'" . $this->getField("EQUIP_QTY") . "',
		'" . $this->getField("EQUIP_ITEM") . "',
		'" . $this->getField("EQUIP_SPEC") . "',
		" . $this->getField("EQUIP_DATEIN") . ",
		" . $this->getField("EQUIP_LASTCAL") . ",
		" . $this->getField("EQUIP_NEXTCAL") . ",
		'" . $this->getField("EQUIP_CONDITION") . "',
		'" . $this->getField("EQUIP_STORAGE") . "',
		'" . $this->getField("EQUIP_REMARKS") . "',
		'" . $this->getField("EQUIP_PRICE") . "',
		'" . $this->getField("SERIAL_NUMBER") . "' ,
		'" . $this->getField("BARCODE") . "'
	)";

		$this->id = $this->getField("EQUIP_ID");
		$this->query = $str;
		// echo $str;
		// exit;
		return $this->execQuery($str);
	}

	function insertFromPembelian()
	{
		$this->setField("EQUIP_ID", $this->getNextId("EQUIP_ID", "EQUIPMENT_LIST"));

		$str = "INSERT INTO EQUIPMENT_LIST (EQUIP_ID, EC_ID, EQUIP_NAME, EQUIP_QTY,CURRENCY, PEMBELIAN_ID,EQUIP_DATEIN,SERIAL_NUMBER,EQUIP_PRICE,EQUIP_SPEC,PEMBELIAN_DETAIL_ID)VALUES (
		'" . $this->getField("EQUIP_ID") . "',
		'" . $this->getField("EC_ID") . "',
		'" . $this->getField("EQUIP_NAME") . "',
		'" . $this->getField("EQUIP_QTY") . "',
		'" . $this->getField("CURRENCY") . "',
		'" . $this->getField("PEMBELIAN_ID") . "',
	" . $this->getField("EQUIP_DATEIN") . " ,
		'" . $this->getField("SERIAL_NUMBER") . "' ,
		'" . $this->getField("EQUIP_PRICE") . "' ,
		'" . $this->getField("EQUIP_SPEC") . "' ,
		'" . $this->getField("PEMBELIAN_DETAIL_ID") . "' 
		
		
		)";

		$this->id = $this->getField("EQUIP_ID");
		$this->query = $str;
		// echo $str;
		// exit;
		return $this->execQuery($str);
	}

	function deleteFromPembelian($statement = "")
	{
		$str = "DELETE FROM EQUIPMENT_LIST
			WHERE EQUIP_ID= '" . $this->getField("EQUIP_ID") . "' AND PEMBELIAN_DETAIL_ID= '" . $this->getField("PEMBELIAN_DETAIL_ID") . "'";
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}
	function updateFromPembelian()
	{
		$str = "
		UPDATE EQUIPMENT_LIST
		SET    
		EQUIP_ID ='" . $this->getField("EQUIP_ID") . "',
		
		EC_ID ='" . $this->getField("EC_ID") . "',
		CURRENCY ='" . $this->getField("CURRENCY") . "',
		EQUIP_DATEIN =" . $this->getField("EQUIP_DATEIN") . ",
		PEMBELIAN_ID ='" . $this->getField("PEMBELIAN_ID") . "',
		EQUIP_NAME ='" . $this->getField("EQUIP_NAME") . "',
	EQUIP_PRICE ='" . $this->getField("EQUIP_PRICE") . "',
		
		EQUIP_SPEC ='" . $this->getField("EQUIP_SPEC") . "',
		SERIAL_NUMBER ='" . $this->getField("SERIAL_NUMBER") . "'
	
		WHERE EQUIP_ID= '" . $this->getField("EQUIP_ID") . "' AND PEMBELIAN_DETAIL_ID= '" . $this->getField("PEMBELIAN_DETAIL_ID") . "'";
		$this->query = $str;
		// echo $str;
		// exit;
		return $this->execQuery($str);
	}

	function updateQtyPembelian()
	{
		$str = "
		UPDATE EQUIPMENT_LIST
		SET    
		
		EQUIP_QTY =EQUIP_QTY + " . $this->getField("EQUIP_QTY") . "
		
		
	
		WHERE EQUIP_ID= '" . $this->getField("EQUIP_ID") . "'";
		$this->query = $str;
		// echo $str;
		// exit;
		return $this->execQuery($str);
	}
	function updateHapusPembelian()
	{
		$str = "
		UPDATE EQUIPMENT_LIST
		SET    
		
		EC_ID =NULL,
		CURRENCY =NULL,
		EQUIP_DATEIN =NULL,
		
		EQUIP_NAME =NULL,
		EQUIP_QTY =NULL,
		
		EQUIP_PRICE =NULL,
		SERIAL_NUMBER =NULL
	
		WHERE  PEMBELIAN_DETAIL_ID= '" . $this->getField("PEMBELIAN_DETAIL_ID") . "'";
		$this->query = $str;
		// echo $str;
		// exit;
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE EQUIPMENT_LIST
		SET    
		EQUIP_ID ='" . $this->getField("EQUIP_ID") . "',
		EQUIP_PARENT_ID =" . $this->getField("EQUIP_PARENT_ID") . ",
		EC_ID ='" . $this->getField("EC_ID") . "',
		EQUIP_NAME ='" . $this->getField("EQUIP_NAME") . "',
		EQUIP_QTY ='" . $this->getField("EQUIP_QTY") . "',
		EQUIP_ITEM ='" . $this->getField("EQUIP_ITEM") . "',
		EQUIP_SPEC ='" . $this->getField("EQUIP_SPEC") . "',
		EQUIP_DATEIN =" . $this->getField("EQUIP_DATEIN") . ",
		EQUIP_LASTCAL =" . $this->getField("EQUIP_LASTCAL") . ",
		EQUIP_NEXTCAL =" . $this->getField("EQUIP_NEXTCAL") . ",
		EQUIP_CONDITION ='" . $this->getField("EQUIP_CONDITION") . "',
		EQUIP_STORAGE ='" . $this->getField("EQUIP_STORAGE") . "',
		EQUIP_REMARKS ='" . $this->getField("EQUIP_REMARKS") . "',
		EQUIP_PRICE ='" . $this->getField("EQUIP_PRICE") . "',
		SERIAL_NUMBER ='" . $this->getField("SERIAL_NUMBER") . "',
		BARCODE ='" . $this->getField("BARCODE") . "'
		WHERE EQUIP_ID= '" . $this->getField("EQUIP_ID") . "'";
		$this->query = $str;
		// echo $str;
		// exit;
		return $this->execQuery($str);
	}

	function update_path()
	{
		$str = "
			UPDATE EQUIPMENT_LIST
			SET    

			PIC_PATH ='" . $this->getField("PIC_PATH") . "' 
			WHERE EQUIP_ID= '" . $this->getField("EQUIP_ID") . "'";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}
	function update_currency()
	{
		$str = "
			UPDATE EQUIPMENT_LIST
			SET    

			CURRENCY ='" . $this->getField("CURRENCY") . "' 
			WHERE EQUIP_ID= '" . $this->getField("EQUIP_ID") . "'";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}
	function update_barcode()
	{
		$str = "
			UPDATE EQUIPMENT_LIST
			SET    

			STORAGE_ID =" . $this->getField("STORAGE_ID") . " ,
				BARCODE ='" . $this->getField("BARCODE") . "' 
			WHERE EQUIP_ID= '" . $this->getField("EQUIP_ID") . "'";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function update_urut()
	{
		$str = "
			UPDATE EQUIPMENT_LIST
			SET    

			URUT ='" . $this->getField("URUT") . "' 
			WHERE EQUIP_ID= '" . $this->getField("EQUIP_ID") . "'";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery2($str);
	}

	function update_certificate()
	{
		$str = "
			UPDATE EQUIPMENT_LIST
			SET    
				CERTIFICATE_ID =" . $this->getField("CERTIFICATE_ID") . ",
				CERTIFICATE_NAME ='" . $this->getField("CERTIFICATE_NAME") . "',
				CERTIFICATE_DESCRIPTION ='" . $this->getField("CERTIFICATE_DESCRIPTION") . "',
				CERTIFICATE_PATH ='" . $this->getField("CERTIFICATE_PATH") . "',
				CERTIFICATE_ISSUED_DATE =" . $this->getField("CERTIFICATE_ISSUED_DATE") . ",
				CERTIFICATE_EXPIRED_DATE =" . $this->getField("CERTIFICATE_EXPIRED_DATE") . ",
				CERTIFICATE_LAST_REVISI =" . $this->getField("CERTIFICATE_LAST_REVISI") . ",
				CERTIFICATE_SURVEYOR ='" . $this->getField("CERTIFICATE_SURVEYOR") . "'
			WHERE EQUIP_ID= '" . $this->getField("EQUIP_ID") . "'";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function update_invoice()
	{
		$str = "
			UPDATE EQUIPMENT_LIST
			SET    
				INVOICE_NUMBER ='" . $this->getField("INVOICE_NUMBER") . "',
				INVOICE_DESCRIPTION ='" . $this->getField("INVOICE_DESCRIPTION") . "',
				INVOICE_PATH ='" . $this->getField("INVOICE_PATH") . "'
			WHERE EQUIP_ID= '" . $this->getField("EQUIP_ID") . "'";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement = "")
	{
		$str = "DELETE FROM EQUIPMENT_LIST
			WHERE EQUIP_ID= '" . $this->getField("EQUIP_ID") . "'";
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoringUrutan($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = " ", $sort=' DESC')
	{
		$str = "
			SELECT EQUIP_ID,ROW_NUMBER() OVER (ORDER BY EQUIP_ID ".$sort.") URUT FROM EQUIPMENT_LIST 
			WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.EQUIP_ID ASC")
	{
		$str = "
			SELECT A.EQUIP_ID,A.EQUIP_PARENT_ID,A.EC_ID,A.EQUIP_NAME,A.EQUIP_QTY,A.EQUIP_ITEM,A.EQUIP_SPEC,A.EQUIP_DATEIN,A.EQUIP_LASTCAL,A.EQUIP_NEXTCAL,A.EQUIP_CONDITION,A.EQUIP_STORAGE,A.EQUIP_REMARKS,A.EQUIP_PRICE,A.PIC_PATH,A.SERIAL_NUMBER,
				CERTIFICATE_ID, CERTIFICATE_NAME, CERTIFICATE_DESCRIPTION, CERTIFICATE_PATH, 
				CERTIFICATE_ISSUED_DATE, CERTIFICATE_EXPIRED_DATE, CERTIFICATE_LAST_REVISI, 
				CERTIFICATE_SURVEYOR, INVOICE_NUMBER, INVOICE_DESCRIPTION, INVOICE_PATH,A.BARCODE,A.CURRENCY,A.STORAGE_ID
			FROM EQUIPMENT_LIST A
			WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsMonitoringStock($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.EQUIP_ID ASC")
	{
		$str = "
			SELECT A.EQUIP_ID,A.EQUIP_PARENT_ID,A.EC_ID,A.EQUIP_NAME,A.EQUIP_QTY,A.EQUIP_ITEM,A.EQUIP_SPEC,A.EQUIP_DATEIN,A.EQUIP_LASTCAL,A.EQUIP_NEXTCAL,A.EQUIP_CONDITION,A.EQUIP_STORAGE,A.EQUIP_REMARKS,A.EQUIP_PRICE,A.PIC_PATH,A.SERIAL_NUMBER,
		CERTIFICATE_ID, CERTIFICATE_NAME, CERTIFICATE_DESCRIPTION, CERTIFICATE_PATH, 
		CERTIFICATE_ISSUED_DATE, CERTIFICATE_EXPIRED_DATE, CERTIFICATE_LAST_REVISI, 
		CERTIFICATE_SURVEYOR, INVOICE_NUMBER, INVOICE_DESCRIPTION, INVOICE_PATH,A.BARCODE,A.CURRENCY,LK.EQUIP_STOK,LK.KK EQUIP_KELUAR
		FROM EQUIPMENT_LIST A
		LEFT JOIN (
				SELECT  A.EQUIP_ID,
				(COALESCE(A.EQUIP_QTY,0) -(COALESCE(KLM.EQUIP_QTY,0)-COALESCE(KLM.EQUIP_KEMBALI,0))) EQUIP_STOK,  COALESCE (KLM.EQUIP_QTY,0) EQUIP_KELUAR
,		COALESCE (KLM.EQUIP_QTY,0) - COALESCE(KLM.EQUIP_KEMBALI,0)  kk
				FROM EQUIPMENT_LIST A

				LEFT JOIN (
				SELECT C.EQUIP_ID,SUM(C.EQUIP_QTY) EQUIP_QTY,SUM(DD.EQUIP_QTY) EQUIP_KEMBALI FROM SO_EQUIP C 
				 LEFT JOIN SO_EQUIP_PENGEMBALIAN DD ON DD.equip_id = C.equip_id and C.so_id = DD.so_id
				 AND DD.FLAG='1'
				GROUP BY C.EQUIP_ID
			) KLM ON KLM.EQUIP_ID = A.EQUIP_ID
		) LK ON LK.EQUIP_ID = A.EQUIP_ID


		WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsMonitoringBaru($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.EQUIP_ID ASC")
	{
		$str = "
			SELECT A.EQUIP_ID,A.EQUIP_PARENT_ID,A.EC_ID,A.EQUIP_NAME,A.EQUIP_QTY,A.EQUIP_ITEM,A.EQUIP_SPEC,A.EQUIP_DATEIN,A.EQUIP_LASTCAL,A.EQUIP_NEXTCAL,A.EQUIP_CONDITION,A.EQUIP_STORAGE,A.EQUIP_REMARKS,A.EQUIP_PRICE,A.PIC_PATH,A.SERIAL_NUMBER,
				CERTIFICATE_ID, CERTIFICATE_NAME, CERTIFICATE_DESCRIPTION, CERTIFICATE_PATH, 
				CERTIFICATE_ISSUED_DATE, CERTIFICATE_EXPIRED_DATE, CERTIFICATE_LAST_REVISI, 
				CERTIFICATE_SURVEYOR, INVOICE_NUMBER, INVOICE_DESCRIPTION, INVOICE_PATH,A.BARCODE,A.CURRENCY
			FROM EQUIPMENT_LIST A
			LEFT JOIN  EQUIP_CATEGORY B ON B.EC_ID = A.EC_ID
			WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsMonitoringEquipment($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.EQUIP_ID ASC")
	{
		$str = "
			SELECT A.EQUIP_ID ID, EC.EC_NAME KATEGORI,  A.EQUIP_NAME AS NAME, A.EQUIP_ITEM ITEM, A.EQUIP_SPEC SPEC, A.EQUIP_CONDITION CONDITION,
			(A.EQUIP_QTY - COALESCE(B.EQUIP_QTY,0)) STOCK, A.PIC_PATH
			FROM EQUIPMENT_LIST A LEFT JOIN SO_EQUIP B  ON A.EQUIP_ID = B.EQUIP_ID LEFT JOIN EQUIP_CATEGORY EC ON A.EC_ID = EC.EC_ID
			WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsHistory($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.EQUIP_ID ASC")
	{
		$str = "
			SELECT B.*
                FROM SO_EQUIP A
                INNER JOIN SERVICE_ORDER B ON A.SO_ID = B.SO_ID
			WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsMonitoringEquipmentProd($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.EQUIP_ID DESC")
	{
		$str = "SELECT A.EQUIP_ID ,
				B.EC_ID, A.PIC_PATH,
				B.EC_NAME AS CATEGORY,
				A.EQUIP_NAME, A.SERIAL_NUMBER,
				(SELECT C.EQUIP_NAME FROM EQUIPMENT_LIST C WHERE A.EQUIP_PARENT_ID = C.EQUIP_ID) AS PART_OF_EQUIPMENT,
				A.EQUIP_SPEC AS SPECIFICATION,
				A.EQUIP_SPEC,
				A.EQUIP_QTY AS QUANTITY,A.URUT,
				A.EQUIP_ITEM AS ITEM,
				TO_CHAR(A.EQUIP_DATEIN, 'DD-MM-YYYY') AS INCOMING_DATE,
				TO_CHAR(A.EQUIP_LASTCAL, 'DD-MM-YYYY') AS LAST_CALIBRATION,
				TO_CHAR(A.EQUIP_NEXTCAL, 'DD-MM-YYYY') AS NEXT_CALIBRATION,
				(SELECT COUNT(C.EQUIP_ID) FROM EQUIPMENT_LIST C WHERE C.EQUIP_PARENT_ID = A.EQUIP_ID) AS QTY_DETAIL_EQUIPMENT,
				CASE A.EQUIP_CONDITION 
					WHEN 'G' THEN 'Good' 
					WHEN 'B' THEN 'Broken' 
					WHEN 'M' THEN 'Missing' 
					WHEN 'R' THEN 'Repair' 
					ELSE A.EQUIP_CONDITION 
				END CONDITION,
				A.EQUIP_STORAGE AS STORAGE,
				A.EQUIP_PRICE AS PRICE,
				A.EQUIP_REMARKS AS REMARKS,
				CASE 
					-- WHEN CERTIFICATE_EXPIRED_DATE < CURRENT_DATE THEN 'red'
					WHEN EQUIP_NEXTCAL < CURRENT_DATE THEN 'red'
					ELSE ''
				END STATUS,
				CASE A.EQUIP_CONDITION  
					WHEN 'B' THEN 'green' 
					WHEN 'Broken' THEN 'green' 
					WHEN 'M' THEN 'blue' 
					WHEN 'Missing' THEN 'blue' 
					WHEN 'R' THEN 'yellow' 
					WHEN 'Repair' THEN 'yellow' 

					ELSE ''
				END STATUS_CONDITION,
				A.BARCODE,
				CERTIFICATE_EXPIRED_DATE,
				CERTIFICATE_ISSUED_DATE
				FROM EQUIPMENT_LIST A
				LEFT JOIN  EQUIP_CATEGORY B ON B.EC_ID = A.EC_ID
				WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . "  " . $order;
		// echo $order;exit;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsMonitoringEquipmentProdCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.EQUIP_ID DESC")
	{
		$str = "SELECT A.EQUIP_ID ,
				B.EC_ID,
				B.EC_NAME AS CATEGORY,
				A.EQUIP_NAME ,
				(SELECT C.EQUIP_NAME FROM EQUIPMENT_LIST C WHERE A.EQUIP_PARENT_ID = C.EQUIP_ID) AS PART_OF_EQUIPMENT,
				A.EQUIP_SPEC AS SPECIFICATION,
				A.EQUIP_QTY AS QUANTITY,
				A.EQUIP_ITEM AS ITEM,
				TO_CHAR(A.EQUIP_DATEIN, 'DAY,MONTH DD YYYY') AS INCOMING_DATE,
				TO_CHAR(A.EQUIP_LASTCAL, 'DAY,MONTH DD YYYY') AS LAST_CALIBRATION,
				TO_CHAR(A.EQUIP_NEXTCAL, 'DAY,MONTH DD YYYY') AS NEXT_CALIBRATION,
				(SELECT COUNT(C.EQUIP_ID) FROM EQUIPMENT_LIST C WHERE C.EQUIP_PARENT_ID = A.EQUIP_ID) AS QTY_DETAIL_EQUIPMENT,
				A.EQUIP_CONDITION AS CONDITION,
				A.EQUIP_STORAGE AS STORAGE,
				A.EQUIP_PRICE AS PRICE,
				A.EQUIP_REMARKS AS REMARKS,
				A.PIC_PATH 	,	A.SERIAL_NUMBER	,A.BARCODE
				FROM EQUIPMENT_LIST A, EQUIP_CATEGORY B
				WHERE A.EC_ID = B.EC_ID ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val'";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function getCountByParamsMonitoringBaru($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM EQUIPMENT_LIST A 
			LEFT JOIN  EQUIP_CATEGORY B ON B.EC_ID = A.EC_ID
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

	function getCountByParamsMonitoringEquipment($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM EQUIPMENT_LIST A 
			LEFT JOIN SO_EQUIP B  ON A.EQUIP_ID = B.EQUIP_ID LEFT JOIN EQUIP_CATEGORY EC ON A.EC_ID = EC.EC_ID
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

	function getCountByParamsMonitoringEquipmentProd($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
				FROM EQUIPMENT_LIST A
				LEFT JOIN  EQUIP_CATEGORY B ON B.EC_ID = A.EC_ID
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


	function getCountByParamsMonitoring($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM EQUIPMENT_LIST A WHERE 1=1 " . $statement;
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

	function getCountByParamsTotalEquipment($paramsArray = array(), $statement = "")
	{
		$str = " SELECT COUNT(*) AS ROWCOUNT FROM EQUIPMENT_LIST WHERE CERTIFICATE_EXPIRED_DATE < CURRENT_DATE  " . $statement;
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key =    '$val' ";
		}
		$this->query = $str;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}

	function getCountByParamsExpired($paramsArray = array(), $statement = "")
	{
		$str = " SELECT COUNT(*) AS ROWCOUNT FROM EQUIPMENT_LIST WHERE CERTIFICATE_EXPIRED_DATE < CURRENT_DATE + INTERVAL '3 MONTH' " . $statement;
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key =    '$val' ";
		}
		$this->query = $str;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}
}
