<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class aduan_json extends CI_Controller
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
		$this->load->model("Aduan");
		$aduan = new Aduan();

		$reqMode = $this->input->get("reqMode");

		// echo $reqKategori;exit;

		$aColumns		= array("ADUAN_ID", "NIP_NAMA", "NAMA", "CREATED_DATE", "ADUAN", "BALASAN");
		$aColumnsAlias	= array("ADUAN_ID", "B.NAMA", "A.NAMA", "A.CREATED_DATE", "A.ADUAN", "A.BALASAN");


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
			if (trim($sOrder) == "ORDER BY A.Aduan_id asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.Aduan_id asc";
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

		if ($reqMode == "BELUM")
			$statement = " AND COALESCE(NULLIF(BALASAN, ''), 'X') = 'X' ";
		elseif ($reqMode == "SUDAH")
			$statement = " AND NOT COALESCE(NULLIF(BALASAN, ''), 'X') = 'X' ";

		$statement .= " AND (UPPER(B.NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR UPPER(B.NIP) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR UPPER(A.NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$allRecord = $aduan->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $aduan->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

		$aduan->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

		// echo "IKI ".$_GET['iDisplayStart'];

		/*
			 * Output
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);

		while ($aduan->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($aduan->getField($aColumns[$i]), 10) . "...";
				elseif ($aColumns[$i] == "LINK_FILE")
					$row[] = "<img src='uploads/" . $aduan->getField($aColumns[$i]) . "' height='50px'>";
				elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
					if ($aduan->getField($aColumns[$i]) == "Y")
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					else
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
				} else
					$row[] = $aduan->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function add()
	{
		$this->load->model("Aduan");
		$aduan = new Aduan();

		$reqMode 						= $this->input->post("reqMode");
		$reqId 							= $this->input->post("reqId");
		$reqNip 						= $this->input->post("reqNip");
		$reqNama 						= $this->input->post("reqNama");
		$reqAduan 						= $this->input->post("reqAduan");
		$reqLinkFile 					= $this->input->post("reqLinkFile");
		$reqBalasan 					= $this->input->post("reqBalasan");

		$aduan->setField("ADUAN_ID", $reqId);
		$aduan->setField("NIP", $reqNip);
		$aduan->setField("NAMA", $reqNama);
		$aduan->setField("ADUAN", $reqAduan);
		$aduan->setField("LINK_FILE", $reqLinkFile);
		$aduan->setField("BALASAN", $reqBalasan);


		if ($reqMode == "insert") {
			$aduan->setField("CREATED_BY", $this->USERNAME);
			$aduan->insert();
		} else {
			$aduan->setField("BALASAN_BY", $this->USERNAME);
			$aduan->update();
		}

		echo "Data berhasil disimpan.";
	}


	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Aduan");
		$aduan = new Aduan();


		$aduan->setField("ADUAN_ID", $reqId);
		if ($aduan->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}



	function forward()
	{
		$this->load->model("Aduan");
		$aduan = new Aduan();

		$reqId = $this->input->post("reqId");
		$reqMail = $this->input->post("reqMail");

		$this->load->library("KMail");

		$mail = new KMail();
		$body = file_get_contents(base_url() . "login/loadUrl/email/aduan/" . $reqId);
		//$mail->AddAddress($auditor->getField("EMAIL") , $auditor->getField("NAMA"));
		$mail->AddAddress($reqMail, $reqMail);
		$mail->Subject  =  "[SEKAR] Aduan Anggota";
		$mail->MsgHTML($body);
		if ($mail->Send())
			echo "Aduan berhasil diforward ke " . $reqMail . ".";
		else
			echo "Forward email gagal";
	}

	function combo()
	{
		$this->load->model("Aduan");
		$aduan = new Aduan();

		$aduan->selectByParams(array());
		$i = 0;
		while ($aduan->nextRow()) {
			$arr_json[$i]['id']		= $aduan->getField("ADUAN_ID");
			$arr_json[$i]['text']	= $aduan->getField("NAMA");
			$i++;
		}

		echo json_encode($arr_json);
	}
}
