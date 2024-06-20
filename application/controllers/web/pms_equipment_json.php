<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class pms_equipment_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->CABANG		= $this->kauth->getInstance()->getIdentity()->CABANG;
	}

	function json()
	{
		$this->load->model("PmsEquipment");
		$pms_equipment = new PmsEquipment();

		$reqMode = $this->input->get("reqMode");

		// echo $reqKategori;exit;
		$aColumns		= array("PMS_ID", "NO","EQUIP_ID", "CATEGORY", "EQUIP_NAME", "SPECIFICATION", "QUANTITY", "DATE_TEST", "DATE_NEXT_TEST", "TIME_TEST", "CONDITION", "STORAGE", "REMARKS", "STATUS");
		$aColumnsAlias	= array("PMS_ID", "NO","EQUIP_ID", "CATEGORY", "EQUIP_NAME", "SPECIFICATION", "QUANTITY", "DATE_TEST", "DATE_NEXT_TEST", "TIME_TEST", "CONDITION", "STORAGE", "REMARKS", "STATUS");
		
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
			if (trim($sOrder) == "ORDER BY PMS_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY PMS_ID desc";
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

		// if ($reqMode == "BELUM")
		// 	$statement = " AND COALESCE(NULLIF(BALASAN, ''), 'X') = 'X' ";
		// elseif ($reqMode == "SUDAH")
		// 	$statement = " AND NOT COALESCE(NULLIF(BALASAN, ''), 'X') = 'X' ";

		
		// $reqCariCompetentPerson   = $this->input->get('reqCariCompetentPerson');
		// $reqCariTimeofTest        = $this->input->get('reqCariTimeofTest');
		$reqCariDateofArrivedFrom = $this->input->get('reqCariDateofArrivedFrom');
		$reqCariDateofArrivedTo   = $this->input->get('reqCariDateofArrivedTo');
		$reqCariName              = $this->input->get('reqCariName');
		$reqCariNoSerial          = $this->input->get('reqCariNoSerial');
		$reqCariCondition         = $this->input->get('reqCariCondition');
		$reqCariIdNumber          = $this->input->get('reqCariIdNumber');
		$reqCariCategori          = $this->input->get('reqCariCategori');

		// var_dump($this->input->get()); exit();

        $_SESSION[$this->input->get("pg")."reqCariDateofArrivedFrom"] = $reqCariDateofArrivedFrom;
        $_SESSION[$this->input->get("pg")."reqCariDateofArrivedTo"] = $reqCariDateofArrivedTo;
        $_SESSION[$this->input->get("pg")."reqCariName"] = $reqCariName;
        $_SESSION[$this->input->get("pg")."reqCariNoSerial"] = $reqCariNoSerial;
        $_SESSION[$this->input->get("pg")."reqCariCondition"] = $reqCariCondition;
        $_SESSION[$this->input->get("pg")."reqCariIdNumber"] = $reqCariIdNumber;
        $_SESSION[$this->input->get("pg")."reqCariCategori"] = $reqCariCategori;


		if (!empty($reqCariName)) {
			$statement_privacy = " AND UPPER(B.EQUIP_NAME) LIKE '%" . strtoupper($reqCariName) . "%'";
		}
		if (!empty($reqCariDateofArrivedTo) && !empty($reqCariDateofArrivedFrom)) {
			$statement_privacy .= " AND B.EQUIP_DATEIN BETWEEN TO_DATE('" . $reqCariDateofArrivedFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariDateofArrivedTo . "','dd-mm-yyyy')";
		}
		if (!empty($reqCariIdNumber)) {

			$statement_privacy  .= " AND B.EQUIP_ID = '" . strtoupper(dotToNo($reqCariIdNumber)) . "' ";
		}
		if (!empty($reqCariCondition) && $reqCariCondition != "ALL") {

			$statement_privacy  .= " AND UPPER(B.EQUIP_CONDITION) LIKE '" .  strtoupper($reqCariCondition) . "%' ";
		}
		if (!empty($reqCariCategori) && $reqCariCategori != "ALL") {

			$statement_privacy  .= " AND UPPER(C.EC_NAME) ='" .  strtoupper($reqCariCategori) . "' ";
		}
		if (!empty($reqCariNoSerial)) {
			$statement_privacy .= " AND UPPER(B.SERIAL_NUMBER) LIKE '%" . strtoupper($reqCariNoSerial) . "%'";
		}

		// if (!empty($reqCariTimeofTest)) {
		// 	$statement_privacy .= " AND B.TIME_TEST ='" . $reqCariTimeofTest	 . "'";
		// }
		// if (!empty($reqCariCompetentPerson)) {
		// 	$statement_privacy .= " AND UPPER(B.COMPENENT_PERSON) LIKE '%" . strtoupper($reqCariCompetentPerson) . "%'";
		// }
		

		$reqExpired = $this->input->get('reqExpired');

		$statement = " AND  UPPER(B.EQUIP_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%'";

		if($reqExpired == "1")
		{
			$statement .= "AND EXISTS (
				SELECT 1 FROM PMS_EQUIP_DETIL X WHERE A.PMS_ID = X.PMS_ID
				AND X.DATE_NEXT_TEST < CURRENT_DATE
			)";
		}
$_SESSION[$this->input->get("pg")."reqCariSession"] = $statement_privacy . $statement;
		$allRecord = $pms_equipment->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $pms_equipment->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

		$pms_equipment->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

		// echo "IKI ".$_GET['iDisplayStart'];
		// echo $pms_equipment->query; exit();
		/*
			 * Output
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		 $nomer=0;
		while ($pms_equipment->nextRow()) {
			$row = array();
			$total_pagination  = ($dsplyStart)+$nomer;
			$penomoran = $allRecordFilter-($total_pagination);

			for ($i = 0; $i < count($aColumns); $i++) {
				$check_val = $pms_equipment->getField("TIME_TEST") + 1;
				// $check_val = $check_val-3;
				if ($aColumns[$i] == "KETERANGAN") {
					$row[] = truncate($pms_equipment->getField($aColumns[$i]), 10) . "...";
				} else if ($aColumns[$i] == "NO") {
                    $row[] = $penomoran;
                }elseif ($aColumns[$i] == "LINK_FILE") {
					$row[] = "<img src='uploads/" . $pms_equipment->getField($aColumns[$i]) . "' height='50px'>";
				} elseif ($aColumns[$i] == "DAILY" || $aColumns[$i] == "WEEKLY" || $aColumns[$i] == "MOTHLY" || $aColumns[$i] == "SIX_MOTHLY" || $aColumns[$i] == "YEARLY" || $aColumns[$i] == "2.5_YEARLY" || $aColumns[$i] == "5_YEARLY") {
					$checked = '';
					if ($i == $check_val) {
						$checked = "checked";
					}
					$row[] = '<input type="checkbox" ' . $checked . '>';
				} elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
					if ($pms_equipment->getField($aColumns[$i]) == "Y")
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					else
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
				} else
					$row[] = $pms_equipment->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
			 $nomer++;
		}
		echo json_encode($output);
	}

	function home_json()
	{
		$this->load->model("PmsEquipment");
		$pms_equipment = new PmsEquipment();

		$reqMode = $this->input->get("reqMode");

		// echo $reqKategori;exit;
		$aColumns		= array("PMS_ID", "EQUIP_ID", "EQUIP_NAME", "PART_OF_EQUIPMENT", "SPECIFICATION", "QUANTITY", "ITEM", "INCOMING_DATE", "NEXT_TEST", "CONDITION", "STORAGE", "REMARKS", "STATUS");
		$aColumnsAlias	= array("PMS_ID", "EQUIP_ID", "EQUIP_NAME", "PART_OF_EQUIPMENT", "SPECIFICATION", "QUANTITY", "ITEM", "INCOMING_DATE", "NEXT_TEST", "CONDITION", "STORAGE", "REMARKS", "STATUS");


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
			if (trim($sOrder) == "ORDER BY PMS_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY PMS_ID desc";
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

		// if ($reqMode == "BELUM")
		// 	$statement = " AND COALESCE(NULLIF(BALASAN, ''), 'X') = 'X' ";
		// elseif ($reqMode == "SUDAH")
		// 	$statement = " AND NOT COALESCE(NULLIF(BALASAN, ''), 'X') = 'X' ";

		
		// $reqCariCompetentPerson   = $this->input->get('reqCariCompetentPerson');
		// $reqCariTimeofTest        = $this->input->get('reqCariTimeofTest');
		$reqCariDateofArrivedFrom = $this->input->get('reqCariDateofArrivedFrom');
		$reqCariDateofArrivedTo   = $this->input->get('reqCariDateofArrivedTo');
		$reqCariName              = $this->input->get('reqCariName');

        $_SESSION[$this->input->get("pg")."reqCariDateofArrivedFrom"] = $reqCariDateofArrivedFrom;
        $_SESSION[$this->input->get("pg")."reqCariDateofArrivedTo"] = $reqCariDateofArrivedTo;
        $_SESSION[$this->input->get("pg")."reqCariName"] = $reqCariName;


		if (!empty($reqCariName)) {
			$statement_privacy = " AND UPPER(B.EQUIP_NAME) LIKE '%" . strtoupper($reqCariName) . "%'";
		}
		if (!empty($reqCariDateofArrivedTo) && !empty($reqCariDateofArrivedFrom)) {
			$statement_privacy .= " AND B.EQUIP_DATEIN BETWEEN TO_DATE('" . $reqCariDateofArrivedFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariDateofArrivedTo . "','dd-mm-yyyy')";
		}

		// if (!empty($reqCariTimeofTest)) {
		// 	$statement_privacy .= " AND B.TIME_TEST ='" . $reqCariTimeofTest	 . "'";
		// }
		// if (!empty($reqCariCompetentPerson)) {
		// 	$statement_privacy .= " AND UPPER(B.COMPENENT_PERSON) LIKE '%" . strtoupper($reqCariCompetentPerson) . "%'";
		// }
		

		$reqExpired = $this->input->get('reqExpired');

		$statement = " AND  UPPER(B.EQUIP_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%'";

		$allRecord = $pms_equipment->getCountByParamsTest(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $pms_equipment->getCountByParamsTest(array(), $statement_privacy . $statement);

		$pms_equipment->selectByParamsMonitoringNextTest(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

		// echo "IKI ".$_GET['iDisplayStart'];
		// echo $pms_equipment->query; exit();
		/*
			 * Output
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);

		while ($pms_equipment->nextRow()) {
			$row = array();


			for ($i = 0; $i < count($aColumns); $i++) {
				$check_val = $pms_equipment->getField("TIME_TEST") + 1;
				// $check_val = $check_val-3;
				if ($aColumns[$i] == "KETERANGAN") {
					$row[] = truncate($pms_equipment->getField($aColumns[$i]), 10) . "...";
				} elseif ($aColumns[$i] == "LINK_FILE") {
					$row[] = "<img src='uploads/" . $pms_equipment->getField($aColumns[$i]) . "' height='50px'>";
				} elseif ($aColumns[$i] == "DAILY" || $aColumns[$i] == "WEEKLY" || $aColumns[$i] == "MOTHLY" || $aColumns[$i] == "SIX_MOTHLY" || $aColumns[$i] == "YEARLY" || $aColumns[$i] == "2.5_YEARLY" || $aColumns[$i] == "5_YEARLY") {
					$checked = '';
					if ($i == $check_val) {
						$checked = "checked";
					}
					$row[] = '<input type="checkbox" ' . $checked . '>';
				} elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
					if ($pms_equipment->getField($aColumns[$i]) == "Y")
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					else
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
				} else
					$row[] = $pms_equipment->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}


	function add()
	{
		$this->load->model('PmsEquipment');
		$pms_equipment  = new PmsEquipment();

		$reqId 			= $this->input->post("reqId");
		$reqEquipId    	= $this->input->post("reqEquipId");


		$pms_equipment->setField("PMS_ID", $reqId);
		$pms_equipment->setField("EQUIP_ID", $reqEquipId);

		if (empty($reqId)) {
			$pms_equipment->insert();
			$reqId = $pms_equipment->id;
		} else {
			$pms_equipment->update();
		}

		echo $reqId . '-Data berhasil di simpan';
	}

	function delete()
	{
		$this->load->model("PmsEquipment");
		$pms_equipment = new PmsEquipment();
		$this->load->model("PmsEquipDetil");
        $pms_equip_detil = new PmsEquipDetil();
		$reqId = $this->input->get("reqId");
		$pms_equipment->setField("PMS_ID", $reqId);
		$pms_equip_detil->setField("PMS_ID", $reqId);
		$pms_equipment->delete();
		$pms_equip_detil->deleteParent();
		echo 'Data berhasil di hapus';
	}


	function import_excel()
	{
		header('Cache-Control:max-age=0');
		header('Cache-Control:max-age=1');
		ini_set('memory_limit', '-1');

		ini_set('upload_max_filesize', '200M');
		ini_set('post_max_size', '200M');
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		ini_set('max_execution_time', -1);

		include_once("libraries/excel/excel_reader2.php");
		$data = new Spreadsheet_Excel_Reader($_FILES['reqFiles']["tmp_name"]);
		$baris = $data->rowcount($sheet_index = 0);
		// print_r( $data);
		// print_r($baris);
		$arrData = array();
		// $katerori = 'Company Profile';
		// $kategori = $this->input->post("reqTipe");

		$this->load->model("PmsEquipment");

		for ($i = 2; $i <= $baris; $i++) {

			$pms_equipment = new PmsEquipment();
			$pms_equipment->setField("NAME", $data->val($i, 2));
			$pms_equipment->setField("TIME_TEST", $data->val($i, 3));
			$pms_equipment->setField("COMPETENT", $data->val($i, 4));
			$pms_equipment->setField("PIC_PATH", $data->val($i, 5));
			$pms_equipment->setField("DATE_ARRIVE", $data->val($i, 6));
			$pms_equipment->insert();
			$reqId = $pms_equipment->id;

			$pms_equipment = new PmsEquipment();
			$pms_equipment->setField("PMS_ID", $reqId);
			$pms_equipment->setField("TIME_TEST", $reqId . ' - ' . $data->val($i, 2));
			$pms_equipment->setField("COMPETENT", $reqId . ' - ' . $data->val($i, 3));
			$pms_equipment->setField("PIC_PATH", $reqId . ' - ' . $data->val($i, 4));
			$pms_equipment->setField("DATE_ARRIVE", $reqId . ' - ' . $data->val($i, 5));
			$pms_equipment->update();

			// echo $data->val($i,2).'<br>';
		}
		echo 'Data Berhasil di import';
	}
}
