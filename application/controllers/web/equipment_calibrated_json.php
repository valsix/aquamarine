<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class equipment_calibrated_json extends CI_Controller
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




	function prod_json()
	{
		$this->load->model('EquipmentList');
		$chat = new EquipmentList();

		ini_set("memory_limit", "500M");
		ini_set('max_execution_time', 520);

		$aColumns = array(
			"EQUIP_ID", "NO","EQUIP_ID", "CATEGORY", "EQUIP_NAME", "PART_OF_EQUIPMENT",	"SERIAL_NUMBER",
			"INCOMING_DATE", "LAST_CALIBRATION","NEXT_CALIBRATION",	"CONDITION",
			"STORAGE", "PRICE", "REMARKS", "SPECIFICATION", "QUANTITY", "ITEM", "PIC_PATH", "STATUS"
		);
		$aColumnsAlias = array(
			"EQUIP_ID", "NO","EQUIP_ID", "CATEGORY", "EQUIP_NAME", "PART_OF_EQUIPMENT",	"SERIAL_NUMBER",
			"INCOMING_DATE", "LAST_CALIBRATION","NEXT_CALIBRATION",	"CONDITION",
			"STORAGE", "PRICE", "REMARKS", "SPECIFICATION", "QUANTITY", "ITEM", "PIC_PATH", "STATUS"
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


		$statement_privacy = " AND CERTIFICATE_EXPIRED_DATE < CURRENT_DATE  ";
		if (!empty($reqCariIdNumber)) {

			$statement_privacy  .= " AND UPPER(A.EQUIP_ID) = '" . strtoupper($reqCariIdNumber) . "' ";
		}
		if (!empty($reqCariCondition)) {

			$statement_privacy  .= " AND UPPER(A.EQUIP_CONDITION) LIKE '%" .  strtoupper($reqCariCondition) . "%' ";
		}
		if (!empty($reqCariCategori)) {

			$statement_privacy  .= " AND UPPER(B.EC_NAME) ='" .  strtoupper($reqCariCategori) . "' ";
		}
		if (!empty($reqCariStorage)) {

			$statement_privacy  .= " AND UPPER(B.EQUIP_STORAGE) LIKE '%" .  strtoupper($reqCariStorage) . "%' ";
		}
		if (!empty($reqCariCompanyName)) {

			$statement_privacy  .= " AND UPPER(A.EQUIP_NAME) LIKE '%" .  strtoupper($reqCariCompanyName) . "%' ";
		}
		if (!empty($reqCariIncomingDateFrom) && !empty($reqCariIncomingDateTo)) {

			$statement_privacy  .= " AND A.EQUIP_DATEIN BETWEEN  TO_DATE(" . $reqCariIncomingDateFrom . ",'dd-mm-yyyy')  AND TO_DATE(" . $reqCariIncomingDateFrom . ",'dd-mm-yyyy') ";
		}

		if (!empty($reqCariItemFrom) && !empty($reqCariItemTo)) {


			$statement_privacy  .= " AND A.EQUIP_QTY BETWEEN " . $reqCariItemFrom . " AND " . $reqCariItemTo;
		}

		if (!empty($reqCariLastCalibrationFrom) && !empty($reqCariLastCalibrationTo)) {

			$statement_privacy  .= " AND A.EQUIP_LASTCAL BETWEEN TO_DATE(" . $reqCariLastCalibrationFrom . ",'dd-mm-yyyy') AND TO_DATE(" . $reqCariLastCalibrationTo . ",'dd-mm-yyyy') ";
		}

		if (!empty($reqCariQuantity)) {

			$statement_privacy  .= " AND A.EQUIP_ITEM = '" . $reqCariQuantity . "' ";
		}
		if (!empty($reqCariNextCalibrationFrom) && !empty($reqCariNextCalibrationTo)) {
			$statement_privacy  .= " AND A.EQUIP_NEXTCAL BETWEEN TO_DATE(" . $reqCariNextCalibrationFrom . ",'dd-mm-yyyy') AND TO_DATE(" . $reqCariNextCalibrationTo . ",'dd-mm-yyyy') ";
		}

		if (!empty($reqCariSpesification)) {

			$statement_privacy  .= " AND UPPER(A.EQUIP_SPEC) LIKE '%" .  strtoupper($reqCariSpesification) . "%' ";
		}




		$allRecord = $chat->getCountByParamsMonitoringEquipmentProd(array(),$statement_privacy);
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter = $chat->getCountByParamsMonitoringEquipmentProd(array(), " AND (UPPER(A.EQUIP_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' )" . $statement_privacy);
		// echo $chat->query;exit;


		$orderby = ' ORDER BY A.EQUIP_ID DESC';

		$chat->selectByParamsMonitoringEquipmentProd(array(), $dsplyRange, $dsplyStart, " AND (UPPER(A.EQUIP_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' )" . $statement_privacy, $orderby);

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
				if ($aColumns[$i] == "NAME") {

					$row[] = $action . '  ' . $chat->getField(trim($aColumns[$i]));
				}
				elseif($aColumns[$i] == "NO")
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


}
