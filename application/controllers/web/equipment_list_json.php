<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class equipment_list_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
	}


	function json()
	{
		$this->load->model('EquipmentList');
		$chat = new EquipmentList();

		ini_set("memory_limit", "500M");
		ini_set('max_execution_time', 520);
		$aColumns = array('ID', 'KATEGORI',  'AKSI', 'NAME', 'ITEM', 'SPEC', 'CONDITION', 'STOCK', 'PIC_PATH',"PART_OF_EQUIPMENT",
			"INCOMING_DATE", "LAST_CALIBRATION",	"NEXT_CALIBRATION",
			"STORAGE", "PRICE", "REMARKS");
		$aColumnsAlias = array('ID', 'KATEGORI',  'AKSI', 'NAME', 'ITEM', 'SPEC', 'CONDITION', 'STOCK', 'PIC_PATH',"PART_OF_EQUIPMENT",
			"INCOMING_DATE", "LAST_CALIBRATION",	"NEXT_CALIBRATION",
			"STORAGE", "PRICE", "REMARKS");


		/*
		 * Ordering
		 */


		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}


			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY A.EQUIP_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.EQUIP_ID DESC";
			}
		}


		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}





		//Bind variables.

		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}

		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		$allRecord = $chat->getCountByParamsMonitoringEquipment(array());
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter = $chat->getCountByParamsMonitoringEquipment(array(), " AND (UPPER(A.EQUIP_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' )");

		$chat->selectByParamsMonitoringEquipment(array(), $dsplyRange, $dsplyStart, " AND (UPPER(A.EQUIP_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' )", $sOrder);

		//echo "IKI ".$_GET['iDisplayStart'];

		/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		$nom = 0;
		 $nomer=0;
		while ($chat->nextRow()) {
			$equipment_id = $chat->getField("ID");
			$action = ' <a onclick="clickDetail(' . $nom . ')" class="pull-left" id="btnPenyebab" class="btn btn-info"><i class="fa fa-pencil-square-o"></i></a>';
			$row = array();
			$total_pagination  = ($dsplyStart)+$nomer;
			$penomoran = $allRecordFilter-($total_pagination);
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "TANGGAL_FIX") {
					$row[] = getDayMonth($chat->getField(trim($aColumns[$i])));
				}
				   else if ($aColumns[$i] == "NO") {
                    $row[] = $penomoran;
                }
				if ($aColumns[$i] == "AKSI") {

					$row[] = $action;
				} else {
					$row[] = $chat->getField(trim($aColumns[$i]));
				}
			}

			$output['aaData'][] = $row;
			$duk++;
			$nom++;
			 $nomer++;
		}

		echo json_encode($output);
	}



	function prod_json()
	{
		$this->load->model('EquipmentList');
		$chat = new EquipmentList();
		$this->buat_urutan();
		ini_set("memory_limit", "500M");
		ini_set('max_execution_time', 520);

		$aColumns = array(
			"EQUIP_ID","NO", "EQUIP_ID", "CATEGORY", "EQUIP_NAME", "EQUIP_SPEC",	"SERIAL_NUMBER",
			"INCOMING_DATE", "LAST_CALIBRATION","NEXT_CALIBRATION",	"CONDITION",
			"STORAGE", "PRICE", "REMARKS", "SPECIFICATION", "QUANTITY", "ITEM", "PIC_PATH", "STATUS", "STATUS_CONDITION","EC_ID"
		);
		$aColumnsAlias = array(
			"EQUIP_ID", "NO","EQUIP_ID", "CATEGORY", "EQUIP_NAME", "EQUIP_SPEC",	"SERIAL_NUMBER",
			"INCOMING_DATE", "LAST_CALIBRATION","NEXT_CALIBRATION",	"CONDITION",
			"STORAGE", "PRICE", "REMARKS", "SPECIFICATION", "QUANTITY", "ITEM", "PIC_PATH", "STATUS", "STATUS_CONDITION","EC_ID"
		);
		/*
		 * Ordering
		 */


		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}


			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

		// echo $sOrder;exit;
			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY EQUIP_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY URUT ASC";
			}
		}
// echo $sOrder;exit;

		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}





		//Bind variables.

		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}

		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		$reqCariIdNumber                 = $this->input->get("reqCariIdNumber");
		$reqCariCondition                = $this->input->get("reqCariCondition");
		$reqCariCategori                 = $this->input->get("reqCariCategori");
		$reqCariStorage                  = $this->input->get("reqCariStorage");
		$reqCariCompanyName              = $this->input->get("reqCariCompanyName");
		$reqCariIncomingDateFrom         = $this->input->get("reqCariIncomingDateFrom");
		$reqCariIncomingDateTo           = $this->input->get("reqCariIncomingDateTo");
		$reqCariItemFrom                 = $this->input->get("reqCariItemFrom");
		$reqCariItemTo                   = $this->input->get("reqCariItemTo");
		$reqCariLastCalibrationFrom      = $this->input->get("reqCariLastCalibrationFrom");
		$reqCariLastCalibrationTo        = $this->input->get("reqCariLastCalibrationTo");
		$reqCariQuantity                 = $this->input->get("reqCariQuantity");
		$reqCariNextCalibrationFrom      = $this->input->get("reqCariNextCalibrationFrom");
		$reqCariNextCalibrationTo        = $this->input->get("reqCariNextCalibrationTo");
		$reqCariSpesification            = $this->input->get("reqCariSpesification");
		$reqExpired            			 = $this->input->get("reqExpired");

        $_SESSION[$this->input->get("pg")."reqCariIdNumber"] = $reqCariIdNumber;
        $_SESSION[$this->input->get("pg")."reqCariCondition"] = $reqCariCondition;
        $_SESSION[$this->input->get("pg")."reqCariCategori"] = $reqCariCategori;
        $_SESSION[$this->input->get("pg")."reqCariStorage"] = $reqCariStorage;
        $_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariIncomingDateFrom"] = $reqCariIncomingDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariIncomingDateTo"] = $reqCariIncomingDateTo;
        $_SESSION[$this->input->get("pg")."reqCariItemFrom"] = $reqCariItemFrom;
        $_SESSION[$this->input->get("pg")."reqCariItemTo"] = $reqCariItemTo;
        $_SESSION[$this->input->get("pg")."reqCariLastCalibrationFrom"] = $reqCariLastCalibrationFrom;
        $_SESSION[$this->input->get("pg")."reqCariLastCalibrationTo"] = $reqCariLastCalibrationTo;
        $_SESSION[$this->input->get("pg")."reqCariQuantity"] = $reqCariQuantity;
        $_SESSION[$this->input->get("pg")."reqCariNextCalibrationFrom"] = $reqCariNextCalibrationFrom;
        $_SESSION[$this->input->get("pg")."reqCariNextCalibrationTo"] = $reqCariNextCalibrationTo;
        $_SESSION[$this->input->get("pg")."reqCariSpesification"] = $reqCariSpesification;
        $_SESSION[$this->input->get("pg")."reqExpired"] = $reqExpired;

        // echo $reqCariIncomingDateFrom;
        // echo $_SESSION[$this->input->get("pg")."reqCariIncomingDateFrom"];exit;

		$statement_privacy = '';
		if (!empty($reqCariIdNumber)) {

			$statement_privacy  .= " AND A.EQUIP_ID = '" . strtoupper($reqCariIdNumber) . "' ";
		}
		if (!empty($reqCariCondition) && $reqCariCondition != "ALL") {

			$statement_privacy  .= " AND UPPER(A.EQUIP_CONDITION) LIKE '" .  strtoupper($reqCariCondition) . "%' ";
		}
		if (!empty($reqCariCategori) && $reqCariCategori != "ALL") {

			$statement_privacy  .= " AND UPPER(B.EC_NAME) ='" .  strtoupper($reqCariCategori) . "' ";
		}
		if (!empty($reqCariStorage)) {

			$statement_privacy  .= " AND UPPER(A.EQUIP_STORAGE) LIKE '%" .  strtoupper($reqCariStorage) . "%' ";
		}
		if (!empty($reqCariCompanyName)) {

			$statement_privacy  .= " AND UPPER(A.EQUIP_NAME) LIKE '%" .  strtoupper($reqCariCompanyName) . "%' ";
		}
		if (!empty($reqCariIncomingDateFrom) && !empty($reqCariIncomingDateTo)) {

			$statement_privacy  .= " AND A.EQUIP_DATEIN BETWEEN  TO_DATE('" . $reqCariIncomingDateFrom . "','dd-mm-yyyy')  AND TO_DATE('" . $reqCariIncomingDateFrom . "','dd-mm-yyyy') ";
		}

		if (!empty($reqCariItemFrom) && !empty($reqCariItemTo)) {


			$statement_privacy  .= " AND A.EQUIP_QTY BETWEEN " . $reqCariItemFrom . " AND " . $reqCariItemTo;
		}

		if (!empty($reqCariLastCalibrationFrom) && !empty($reqCariLastCalibrationTo)) {

			$statement_privacy  .= " AND A.EQUIP_LASTCAL BETWEEN TO_DATE('" . $reqCariLastCalibrationFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariLastCalibrationTo . "','dd-mm-yyyy') ";
		}

		if (!empty($reqCariQuantity)) {

			$statement_privacy  .= " AND A.EQUIP_ITEM = '" . $reqCariQuantity . "' ";
		}
		if (!empty($reqCariNextCalibrationFrom) && !empty($reqCariNextCalibrationTo)) {
			$statement_privacy  .= " AND A.EQUIP_NEXTCAL BETWEEN TO_DATE('" . $reqCariNextCalibrationFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariNextCalibrationTo . "','dd-mm-yyyy') ";
		}

		if (!empty($reqCariSpesification)) {

			$statement_privacy  .= " AND UPPER(A.SERIAL_NUMBER) LIKE '%" .  strtoupper($reqCariSpesification) . "%' ";
		}

		if (!empty($reqExpired)) {

			$statement_privacy  .= " AND CERTIFICATE_EXPIRED_DATE < CURRENT_DATE ";
		}

		$reqKategoriId            			 = $this->input->get("reqKategoriId");
		if (!empty($reqKategoriId)) {

			$statement_privacy  .= " AND B.EC_ID ='".$reqKategoriId."' ";
		}

		$_SESSION[$this->input->get("pg")."reqCariSession"] = $statement_privacy . $statement;

		$reqModes = $this->input->get('reqModes');

		if(!empty($reqModes)){
				$reqModesArr = explode("-",$reqModes);
				if($reqModesArr[0]=='SOEQUIPS'){
				// 	$statement_privacy  .= " AND NOT EXISTS( SELECT 1 FROM SO_EQUIP XX WHERE XX.EQUIP_ID = A.EQUIP_ID AND XX.SO_ID = '".$reqModesArr[1]."'

				// 		AND NOT EXISTS(SELECT 1 FROM SO_EQUIP_PENGEMBALIAN TT WHERE TT.SO_EQUIP_ID = XX.SO_EQUIP_ID)

				// ) ";
				$statement_privacy  .= " AND NOT EXISTS( SELECT 1 FROM SO_EQUIP XX WHERE XX.EQUIP_ID = A.EQUIP_ID AND XX.SO_ID = '".$reqModesArr[1]."'

						

					) ";
				}
					$statement_privacy  .= " AND ( A.EQUIP_CONDITION ='Good' or  A.EQUIP_CONDITION ='G' ) ";
		}

		$_SESSION["reqCariSessionEquip"] = $statement_privacy . $statement;


		$allRecord = $chat->getCountByParamsMonitoringEquipmentProd(array(), $statement_privacy);

		 // ECHO $chat->query;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter = $chat->getCountByParamsMonitoringEquipmentProd(array(), " AND (UPPER(A.EQUIP_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' )" . $statement_privacy);

		// $orderby = ' ORDER BY A.EQUIP_ID DESC';

		$chat->selectByParamsMonitoringEquipmentProd(array(), $dsplyRange, $dsplyStart, " AND (UPPER(A.EQUIP_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' )" . $statement_privacy, $sOrder);
		// echo $chat->query;exit;

		//echo "IKI ".$_GET['iDisplayStart'];

		/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		$no_urut=1;
		 $nomer=0;
		while ($chat->nextRow()) {
			$equipment_id = $chat->getField("ID");
			$action = ' <a onclick="clickDetail(' . $equipment_id . ')" class="pull-left" id="btnPenyebab" class="btn btn-info"><i class="fa fa-pencil-square-o"></i></a>';
			$row = array();
			$total_pagination  = ($dsplyStart)+$nomer;
            $penomoran = $allRecordFilter-($total_pagination);
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "TANGGAL_FIX") {
					$row[] = getDayMonth($chat->getField(trim($aColumns[$i])));
				}
				else if ($aColumns[$i] == "NAME") {

					$row[] = $action . '  ' . $chat->getField(trim($aColumns[$i]));
				}
				else if($aColumns[$i] == "NO")
				{
				  $row[] = $penomoran;
				  	// $row[] = $chat->getField("URUT");
					// $row[]='';
				} 
				else 
				{
					$row[] = $chat->getField(trim($aColumns[$i]));
				}
			}
			$no_urut++;
			$output['aaData'][] = $row;
			$duk++;
			 $nomer++;
		}

		echo json_encode($output);
	}

	function buat_urutan(){
			$this->load->model('EquipmentList');
			$equipment_list = new EquipmentList();
			$equipment_list->selectByParamsMonitoringUrutan(array(),-1,-1,'','  ORDER BY EQUIP_ID DESC ','DESC');
			while ($equipment_list->nextRow()) {
				$equipment_list2 = new EquipmentList();
				$equipment_list2->setField("URUT",$equipment_list->getField("URUT"));
				$equipment_list2->setField("EQUIP_ID",$equipment_list->getField("EQUIP_ID"));
				$equipment_list2->update_urut();
			
			}
	}

	function expired_json()
	{
		$this->load->model('EquipmentList');
		$chat = new EquipmentList();

		ini_set("memory_limit", "500M");
		ini_set('max_execution_time', 520);
		$aColumns = array(
			"EQUIP_ID","NO", "EQUIP_ID", "CATEGORY", "EQUIP_NAME", "SERIAL_NUMBER", "CERTIFICATE_EXPIRED_DATE", "CERTIFICATE_ISSUED_DATE", "CONDITION",
			"STORAGE", "PRICE", "REMARKS", "SPECIFICATION", "QUANTITY", "ITEM", "PIC_PATH", "STATUS", "STATUS_CONDITION"
		);
		$aColumnsAlias = array(
			"EQUIP_ID", "NO","EQUIP_ID", "CATEGORY", "EQUIP_NAME", "SERIAL_NUMBER", "CERTIFICATE_EXPIRED_DATE", "CERTIFICATE_ISSUED_DATE", "CONDITION",
			"STORAGE", "PRICE", "REMARKS", "SPECIFICATION", "QUANTITY", "ITEM", "PIC_PATH", "STATUS", "STATUS_CONDITION"
		);
		/*
		 * Ordering
		 */


		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}


			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

		// echo $sOrder;exit;
			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY EQUIP_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY EQUIP_ID DESC";
			}
		}


		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}





		//Bind variables.

		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}

		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}


		$reqCariIdNumber                 = $this->input->get("reqCariIdNumber");
		$reqCariCondition                = $this->input->get("reqCariCondition");
		$reqCariCategori                 = $this->input->get("reqCariCategori");
		$reqCariStorage                  = $this->input->get("reqCariStorage");
		$reqCariCompanyName              = $this->input->get("reqCariCompanyName");
		$reqCariIncomingDateFrom         = $this->input->get("reqCariIncomingDateFrom");
		$reqCariIncomingDateTo           = $this->input->get("reqCariIncomingDateTo");
		$reqCariItemFrom                 = $this->input->get("reqCariItemFrom");
		$reqCariItemTo                   = $this->input->get("reqCariItemTo");
		$reqCariLastCalibrationFrom      = $this->input->get("reqCariLastCalibrationFrom");
		$reqCariLastCalibrationTo        = $this->input->get("reqCariLastCalibrationTo");
		$reqCariQuantity                 = $this->input->get("reqCariQuantity");
		$reqCariNextCalibrationFrom      = $this->input->get("reqCariNextCalibrationFrom");
		$reqCariNextCalibrationTo        = $this->input->get("reqCariNextCalibrationTo");
		$reqCariSpesification            = $this->input->get("reqCariSpesification");
		$reqExpired            			 = $this->input->get("reqExpired");

        $_SESSION[$this->input->get("pg")."reqCariIdNumber"] = $reqCariIdNumber;
        $_SESSION[$this->input->get("pg")."reqCariCondition"] = $reqCariCondition;
        $_SESSION[$this->input->get("pg")."reqCariCategori"] = $reqCariCategori;
        $_SESSION[$this->input->get("pg")."reqCariStorage"] = $reqCariStorage;
        $_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariIncomingDateFrom"] = $reqCariIncomingDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariIncomingDateTo"] = $reqCariIncomingDateTo;
        $_SESSION[$this->input->get("pg")."reqCariItemFrom"] = $reqCariItemFrom;
        $_SESSION[$this->input->get("pg")."reqCariItemTo"] = $reqCariItemTo;
        $_SESSION[$this->input->get("pg")."reqCariLastCalibrationFrom"] = $reqCariLastCalibrationFrom;
        $_SESSION[$this->input->get("pg")."reqCariLastCalibrationTo"] = $reqCariLastCalibrationTo;
        $_SESSION[$this->input->get("pg")."reqCariQuantity"] = $reqCariQuantity;
        $_SESSION[$this->input->get("pg")."reqCariNextCalibrationFrom"] = $reqCariNextCalibrationFrom;
        $_SESSION[$this->input->get("pg")."reqCariNextCalibrationTo"] = $reqCariNextCalibrationTo;
        $_SESSION[$this->input->get("pg")."reqCariSpesification"] = $reqCariSpesification;
        $_SESSION[$this->input->get("pg")."reqExpired"] = $reqExpired;

        // echo $_SESSION[$this->input->get("pg")."reqCariIncomingDateFrom"];exit;

		$statement_privacy = '';
		if (!empty($reqCariIdNumber)) {

			$statement_privacy  .= " AND A.EQUIP_ID = '" . strtoupper($reqCariIdNumber) . "' ";
		}
		if (!empty($reqCariCondition) && $reqCariCondition != "ALL") {

			$statement_privacy  .= " AND UPPER(A.EQUIP_CONDITION) LIKE '" .  strtoupper($reqCariCondition) . "%' ";
		}
		if (!empty($reqCariCategori) && $reqCariCategori != "ALL") {

			$statement_privacy  .= " AND UPPER(B.EC_NAME) ='" .  strtoupper($reqCariCategori) . "' ";
		}
		if (!empty($reqCariStorage)) {

			$statement_privacy  .= " AND UPPER(A.EQUIP_STORAGE) LIKE '%" .  strtoupper($reqCariStorage) . "%' ";
		}
		if (!empty($reqCariCompanyName)) {

			$statement_privacy  .= " AND UPPER(A.EQUIP_NAME) LIKE '%" .  strtoupper($reqCariCompanyName) . "%' ";
		}
		if (!empty($reqCariIncomingDateFrom) && !empty($reqCariIncomingDateTo)) {

			$statement_privacy  .= " AND A.EQUIP_DATEIN BETWEEN  TO_DATE('" . $reqCariIncomingDateFrom . "','dd-mm-yyyy')  AND TO_DATE('" . $reqCariIncomingDateTo . "','dd-mm-yyyy') ";
		}

		if (!empty($reqCariItemFrom) && !empty($reqCariItemTo)) {


			$statement_privacy  .= " AND A.EQUIP_QTY BETWEEN " . $reqCariItemFrom . " AND " . $reqCariItemTo;
		}

		if (!empty($reqCariLastCalibrationFrom) && !empty($reqCariLastCalibrationTo)) {

			$statement_privacy  .= " AND A.EQUIP_LASTCAL BETWEEN TO_DATE('" . $reqCariLastCalibrationFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariLastCalibrationTo . "','dd-mm-yyyy') ";
		}

		if (!empty($reqCariQuantity)) {

			$statement_privacy  .= " AND A.EQUIP_ITEM = '" . $reqCariQuantity . "' ";
		}
		if (!empty($reqCariNextCalibrationFrom) && !empty($reqCariNextCalibrationTo)) {
			$statement_privacy  .= " AND A.EQUIP_NEXTCAL BETWEEN TO_DATE('" . $reqCariNextCalibrationFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariNextCalibrationTo . "','dd-mm-yyyy') ";
		}

		if (!empty($reqCariSpesification)) {

			$statement_privacy  .= " AND UPPER(A.EQUIP_SPEC) LIKE '%" .  strtoupper($reqCariSpesification) . "%' ";
		}

		if (!empty($reqExpired)) {

			$statement_privacy  .= " AND CERTIFICATE_EXPIRED_DATE < CURRENT_DATE + INTERVAL '3 MONTH' ";
		}


		$allRecord = $chat->getCountByParamsMonitoringEquipmentProd(array(), $statement_privacy);
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter = $chat->getCountByParamsMonitoringEquipmentProd(array(), " AND (UPPER(A.EQUIP_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' )" . $statement_privacy);

		// $orderby = ' ORDER BY A.EQUIP_ID DESC';

		$chat->selectByParamsMonitoringEquipmentProd(array(), $dsplyRange, $dsplyStart, " AND (UPPER(A.EQUIP_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' )" . $statement_privacy, $sOrder);
		// echo $chat->query;exit;

		//echo "IKI ".$_GET['iDisplayStart'];

		/*
			 * Output 
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		$no_urut=1;
		while ($chat->nextRow()) {
			$equipment_id = $chat->getField("ID");
			$action = ' <a onclick="clickDetail(' . $equipment_id . ')" class="pull-left" id="btnPenyebab" class="btn btn-info"><i class="fa fa-pencil-square-o"></i></a>';
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "TANGGAL_FIX") {
					$row[] = getDayMonth($chat->getField(trim($aColumns[$i])));
				}
				else if ($aColumns[$i] == "NAME") {

					$row[] = $action . '  ' . $chat->getField(trim($aColumns[$i]));
				}
				else if($aColumns[$i] == "NO")
				{
					$row[] = $no_urut;
				} 
				else 
				{
					$row[] = $chat->getField(trim($aColumns[$i]));
				}
			}
			$no_urut++;
			$output['aaData'][] = $row;
			$duk++;
		}

		echo json_encode($output);
	}

	function detail_rows()
	{
		$reqId = $this->input->get("reqId");
		$this->load->model('EquipmentList');
		$equipment_list = new EquipmentList();
		$equipment_list->selectByParamsMonitoringEquipment(array("A.EQUIP_ID" => $reqId));
		$equipment_list->firstRow();
		$arrData = array();
		$arrData['ID'] = $equipment_list->getField("ID");
		$arrData['KATEGORI'] = $equipment_list->getField("KATEGORI");
		$arrData['NAME'] = $equipment_list->getField("NAME");
		$arrData['ITEM'] = $equipment_list->getField("ITEM");
		$arrData['SPEC'] = $equipment_list->getField("SPEC");
		$arrData['CONDITION'] = $equipment_list->getField("CONDITION");
		$arrData['STOCK'] = $equipment_list->getField("STOCK");
		$arrData['PIC_PATH'] = $equipment_list->getField("PIC_PATH");

		echo json_encode($arrData);
	}

	function delete()
	{
		$reqId = $this->input->get("reqId");
		$this->load->model('EquipmentList');
		$equipment_list = new EquipmentList();
		$equipment_list->setField("EQUIP_ID", $reqId);
		$equipment_list->delete();
		echo 'Data berhasil di hapus';
	}
}
