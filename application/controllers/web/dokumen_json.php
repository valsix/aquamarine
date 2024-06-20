<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class dokumen_json extends CI_Controller
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
		$this->CABANG			= $this->kauth->getInstance()->getIdentity()->CABANG;
	}

	function json()
	{
		// echo "tes";
		$this->load->model("Dokumen");
		$dokumen = new Dokumen();
		// var_dump($dokumen);
		// echo $reqKategori;exit;

		$aColumns		= array("DOKUMEN_ID", "NO","NAMA", "KETERANGAN", "LINK_FILE");
		$aColumnsAlias	= array("DOKUMEN_ID", "NO","NAMA", "KETERANGAN", "LINK_FILE");

		// var_dump($aColumns);


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
			if (trim($sOrder) == "ORDER BY DOKUMEN_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY DOKUMEN_ID desc";
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

		$statement = " AND (UPPER(NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$_SESSION[$this->input->get("pg")."reqCariSession"] = $statement_privacy . $statement;
		$allRecord = $dokumen->getCountByParams(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $dokumen->getCountByParams(array(), $statement_privacy . $statement);

		$dokumen->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		// echo $dokumen->query;exit;

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

		while ($dokumen->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate_singkat($dokumen->getField($aColumns[$i]),100);
				elseif ($aColumns[$i] == "LINK_FILE")
					$row[] = "<a href='uploads/" . $dokumen->getField($aColumns[$i]) . "' height='50px' target='_blank'>Unduh</a>";
				elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
					if ($dokumen->getField($aColumns[$i]) == "Y")
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					else
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
				} else
					$row[] = $dokumen->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}


	function dok_json()
	{
		// echo "tes";
		$this->load->model("Dokumen");
		$dokumen = new Dokumen();
		// var_dump($dokumen);
		// echo $reqKategori;exit;

		$reqKategori = $this->input->get('reqKategori');

		$aColumns		= array("DOCUMENT_ID", "NO","CATEGORY", "NAME", "DESCRIPTION", "PATH", "LAST_REVISI", "EXPIRED_DATE", "EXP");
		$aColumnsAlias	= array("DOCUMENT_ID", "NO","CATEGORY", "NAME", "DESCRIPTION", "PATH", "LAST_REVISI", "EXPIRED_DATE", "EXP");

		// var_dump($aColumns);


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
			if (trim($sOrder) == "ORDER BY DOCUMENT_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY DOCUMENT_ID desc";
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

		if (!empty($reqKategori)) {
			$statement_privacy = " AND A.CATEGORY='" . $reqKategori . "'";
		}

		$reqCariCompanyName = $this->input->get('reqCariCompanyName');
		$reqCariDescription = $this->input->get('reqCariDescription');
		$reqCariExpiredDateFrom = $this->input->post('reqCariExpiredDateFrom');
		$reqCariExpiredDateTo = $this->input->post('reqCariExpiredDateTo');
		
		$_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariDescription"] = $reqCariDescription;
        $_SESSION[$this->input->get("pg")."reqCariExpiredDateFrom"] = $reqCariExpiredDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariExpiredDateTo"] = $reqCariExpiredDateTo;
        
		if (!empty($reqCariCompanyName)) {
			$statement_privacy .= " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%'";
		}
		if (!empty($reqCariDescription)) {
			$statement_privacy .= " AND UPPER(A.DESCRIPTION) LIKE'%" . $reqCariDescription . "%'";
		}
		if (!empty($reqCariExpiredDateFrom) &&  !empty($reqCariExpiredDateTo)) {
			$statement_privacy .= " AND A.EXPIRED_DATE BETWEEN  TO_DATE('" . $reqCariExpiredDateFrom . "','dd-mm-yyyy')  AND TO_DATE('" . $reqCariExpiredDateFrom . "','dd-mm-yyyy') ";
		}



		$statement = " AND (UPPER(A.NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$_SESSION[$this->input->get("pg")."reqCariSession"] = $statement_privacy . $statement;
		$allRecord = $dokumen->getCountByParams(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $dokumen->getCountByParams(array(), $statement_privacy . $statement);

		$dokumen->selectByParamsDokument(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		// echo $dokumen->query;exit;

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
		 $nomer=0;
		while ($dokumen->nextRow()) {
			$row = array();
			$total_pagination  = ($dsplyStart)+$nomer;
			$penomoran = $allRecordFilter-($total_pagination);
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "DESCRIPTION"){
					$row[] = truncate_singkat($dokumen->getField($aColumns[$i]), 120) ;
				}
				elseif ($aColumns[$i] == "LINK_FILE"){
					$row[] = "<a href='uploads/" . $dokumen->getField($aColumns[$i]) . "' height='50px' target='_blank'>Unduh</a>";
				
				} else if ($aColumns[$i] == "NO") {
                    $row[] = $penomoran;
                }
				elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
					if ($dokumen->getField($aColumns[$i]) == "Y")
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					else
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
				} else
					$row[] = $dokumen->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
			 $nomer++;
		}
		echo json_encode($output);
	}

	function add()
	{
		$this->load->model("Dokumen");
		$dokumen = new Dokumen();

		$reqMode = $this->input->post("reqMode");
		$reqId = $this->input->post("reqId");
		$reqNama = $this->input->post("reqNama");
		$reqKeterangan = $this->input->post("reqKeterangan");

		$dokumen->setField("DOKUMEN_ID", $reqId);
		$dokumen->setField("NAMA", $reqNama);
		$dokumen->setField("DOKUMEN", $reqDokumen);
		$dokumen->setField("KETERANGAN", $reqKeterangan);


		/* WAJIB UNTUK UPLOAD DATA */
		include_once("functions/image.func.php");
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR = "uploads/";

		$reqLinkFile = $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");

		$i = 0;
		$renameFile = "DOKUMEN" . date("dmYhis") . rand() . "." . getExtension($reqLinkFile['name'][$i]);


		if ($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i)) {

			$insertLinkSize = $file->uploadedSize;
			$insertLinkTipe =  $file->uploadedExtension;
			$insertLinkFile =  $reqJenis . $renameFile;
		} else {

			$insertLinkSize = $reqLinkFileTempSize[$i];
			$insertLinkTipe =  $reqLinkFileTempTipe[$i];
			$insertLinkFile =  $reqLinkFileTemp[$i];
		}

		$dokumen->setField("LINK_FILE", $insertLinkFile);
		/* AKHIR WAJIB UNTUK UPLOAD DATA */

		if ($reqMode == "insert") {
			$dokumen->setField("CREATED_BY", $this->USERNAME);
			$dokumen->insert();
		} else {
			$dokumen->setField("UPDATED_BY", $this->USERNAME);
			$dokumen->update();
		}
		echo "Data berhasil disimpan.";
	}



	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Document");
		$document = new Document();


		$document->setField("DOCUMENT_ID", $reqId);
		if ($document->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}


	function combo()
	{
		$this->load->model("Dokumen");
		$dokumen = new Dokumen();

		$dokumen->selectByParams(array());
		$i = 0;
		while ($dokumen->nextRow()) {
			$arr_json[$i]['id']		= $dokumen->getField("DOKUMEN_ID");
			$arr_json[$i]['text']	= $dokumen->getField("NAMA");
			$i++;
		}

		echo json_encode($arr_json);
	}
}
