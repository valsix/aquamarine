<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class slider_json extends CI_Controller
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
		$this->load->model("Slider");
		$slider = new Slider();

		$reqJenis = $this->input->get("reqJenis");
		// echo $reqKategori;exit;

		$aColumns		= array("SLIDER_ID", "STATUS_PUBLISH", "STATUS_NOTIFIKASI", "TANGGAL_JAM", "NAMA", "KETERANGAN", "LINK_FILE", "LAST_CREATE_USER");
		$aColumnsAlias	= array("SLIDER_ID", "STATUS_PUBLISH", "STATUS_NOTIFIKASI", "TANGGAL", "NAMA", "KETERANGAN", "LINK_FILE", "LAST_CREATE_USER");


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
			if (trim($sOrder) == "ORDER BY A.SLIDER_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.SLIDER_ID asc";
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

		$statement_privacy .= " AND JENIS = '" . $reqJenis . "' ";

		$statement = " AND (UPPER(NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$allRecord = $slider->getCountByParams(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $slider->getCountByParams(array(), $statement_privacy . $statement);

		$slider->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

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

		while ($slider->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($slider->getField($aColumns[$i]), 10) . "...";
				elseif ($aColumns[$i] == "LINK_FILE")
					$row[] = "<img src='uploads/" . $slider->getField($aColumns[$i]) . "' height='50px'>";
				elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
					if ($slider->getField($aColumns[$i]) == "Y")
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					else
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
				} else
					$row[] = $slider->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}



	function json_nostatus()
	{
		$this->load->model("Slider");
		$slider = new Slider();

		$reqJenis = $this->input->get("reqJenis");
		// echo $reqKategori;exit;

		$aColumns		= array("SLIDER_ID", "TANGGAL_JAM", "NAMA", "KETERANGAN", "LAST_CREATE_USER");
		$aColumnsAlias	= array("SLIDER_ID", "TANGGAL", "NAMA", "KETERANGAN", "LAST_CREATE_USER");


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
			if (trim($sOrder) == "ORDER BY A.SLIDER_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.SLIDER_ID asc";
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

		$statement_privacy .= " AND JENIS = '" . $reqJenis . "' ";

		$statement = " AND (UPPER(NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$allRecord = $slider->getCountByParams(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $slider->getCountByParams(array(), $statement_privacy . $statement);

		$slider->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

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

		while ($slider->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($slider->getField($aColumns[$i]), 10) . "...";
				elseif ($aColumns[$i] == "LINK_FILE")
					$row[] = "<img src='uploads/" . $slider->getField($aColumns[$i]) . "' height='50px'>";
				elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
					if ($slider->getField($aColumns[$i]) == "Y")
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					else
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
				} else
					$row[] = $slider->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}



	function json_nopublish()
	{
		$this->load->model("Slider");
		$slider = new Slider();

		$reqJenis = $this->input->get("reqJenis");
		// echo $reqKategori;exit;

		$aColumns		= array("SLIDER_ID", "TANGGAL_JAM", "STATUS_NOTIFIKASI", "NAMA", "KETERANGAN", "LINK_FILE", "LAST_CREATE_USER");
		$aColumnsAlias	= array("SLIDER_ID", "TANGGAL", "STATUS_NOTIFIKASI", "NAMA", "KETERANGAN", "LINK_FILE", "LAST_CREATE_USER");


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
			if (trim($sOrder) == "ORDER BY A.SLIDER_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.SLIDER_ID asc";
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

		$statement_privacy .= " AND JENIS = '" . $reqJenis . "' ";

		$statement = " AND (UPPER(NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$allRecord = $slider->getCountByParams(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $slider->getCountByParams(array(), $statement_privacy . $statement);

		$slider->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

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

		while ($slider->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($slider->getField($aColumns[$i]), 10) . "...";
				elseif ($aColumns[$i] == "LINK_FILE")
					$row[] = "<img src='uploads/" . $slider->getField($aColumns[$i]) . "' height='50px'>";
				elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
					if ($slider->getField($aColumns[$i]) == "Y")
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					else
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
				} else
					$row[] = $slider->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function add()
	{
		$this->load->model("Slider");
		$slider = new Slider();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		$reqJenis					= $this->input->post("reqJenis");

		$reqNama 				 	= $this->input->post("reqNama");
		$reqKeterangan 				= str_replace("'", "''", $_POST["reqKeterangan"]);
		$reqTanggal 				= $this->input->post("reqTanggal");
		$reqJam 				= $this->input->post("reqJam");

		$slider->setField("SLIDER_ID", $reqId);
		$slider->setField("NAMA", $reqNama);
		$slider->setField("JENIS", $reqJenis);
		$slider->setField("KETERANGAN", $reqKeterangan);
		$slider->setField("TANGGAL", "TO_TIMESTAMP('" . $reqTanggal . " " . $reqJam . "', 'DD-MM-YYYY HH24:MI')");



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
		$renameFile = "" . date("dmYhis") . rand() . "." . getExtension($reqLinkFile['name'][$i]);


		if ($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i)) {

			createThumbnail($FILE_DIR . $renameFile, $FILE_DIR . $reqJenis . $renameFile, 800);
			unlink($FILE_DIR . $renameFile);

			$insertLinkSize = $file->uploadedSize;
			$insertLinkTipe =  $file->uploadedExtension;
			$insertLinkFile =  $reqJenis . $renameFile;
		} else {

			$insertLinkSize = $reqLinkFileTempSize[$i];
			$insertLinkTipe =  $reqLinkFileTempTipe[$i];
			$insertLinkFile =  $reqLinkFileTemp[$i];
		}

		$slider->setField("LINK_FILE", $insertLinkFile);

		if ($reqMode == "insert") {
			$slider->setField("LAST_CREATE_USER", $this->USERNAME);
			$slider->insert();
		} else {
			$slider->setField("LAST_UPDATE_USER", $this->USERNAME);
			$slider->update();
		}

		echo "Data berhasil disimpan.";
	}


	function add_video()
	{
		$this->load->model("Slider");
		$slider = new Slider();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		$reqJenis					= $this->input->post("reqJenis");

		$reqNama 				 	= $this->input->post("reqNama");
		$reqKeterangan 				= $_POST["reqKeterangan"];
		$reqTanggal 				= $this->input->post("reqTanggal");
		$reqJam 				= $this->input->post("reqJam");
		$reqTipe 				= $this->input->post("reqTipe");
		$reqAlamatVideo 				= $this->input->post("reqAlamatVideo");


		$slider->setField("SLIDER_ID", $reqId);
		$slider->setField("NAMA", $reqNama);
		$slider->setField("JENIS", $reqJenis);
		$slider->setField("KETERANGAN", $reqKeterangan);
		$slider->setField("TANGGAL", "TO_TIMESTAMP('" . $reqTanggal . " " . $reqJam . "', 'DD-MM-YYYY HH24:MI')");



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
		$renameFile = "" . date("dmYhis") . rand() . "." . getExtension($reqLinkFile['name'][$i]);


		if ($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i)) {

			createThumbnail($FILE_DIR . $renameFile, $FILE_DIR . $reqJenis . $renameFile, 800);
			unlink($FILE_DIR . $renameFile);

			$insertLinkSize = $file->uploadedSize;
			$insertLinkTipe =  $file->uploadedExtension;
			$insertLinkFile =  $reqJenis . $renameFile;
		} else {

			$insertLinkSize = $reqLinkFileTempSize[$i];
			$insertLinkTipe =  $reqLinkFileTempTipe[$i];
			$insertLinkFile =  $reqLinkFileTemp[$i];
		}

		$slider->setField("LINK_FILE", $insertLinkFile);


		if ($reqTipe == "VIDEO") {

			/* WAJIB UNTUK UPLOAD DATA */
			include_once("functions/image.func.php");
			$this->load->library("FileHandler");
			$file = new FileHandler();
			$FILE_DIR = "uploads/";

			$reqLinkFileVideo = $_FILES["reqLinkFileVideo"];
			$reqLinkFileVideoTempSize	=  $this->input->post("reqLinkFileVideoTempSize");
			$reqLinkFileVideoTempTipe	=  $this->input->post("reqLinkFileVideoTempTipe");
			$reqLinkFileVideoTemp		=  $this->input->post("reqLinkFileVideoTemp");

			$i = 0;
			$renameFile = $reqJenis . date("dmYhis") . rand() . "." . getExtension($reqLinkFileVideo['name'][$i]);


			if ($file->uploadToDirArray('reqLinkFileVideo', $FILE_DIR, $renameFile, $i)) {

				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $renameFile;
			} else {

				$insertLinkSize = $reqLinkFileVideoTempSize[$i];
				$insertLinkTipe =  $reqLinkFileVideoTempTipe[$i];
				$insertLinkFile =  $reqLinkFileVideoTemp[$i];
			}
		} else {
			unlink($FILE_DIR . $reqLinkFileVideoTemp[0]);
			$insertLinkFile = $reqAlamatVideo;
		}

		$slider->setField("LINK_FILE_VIDEO", $insertLinkFile);

		if ($reqMode == "insert") {
			$slider->setField("LAST_CREATE_USER", $this->USERNAME);
			$slider->insert();
		} else {
			$slider->setField("LAST_UPDATE_USER", $this->USERNAME);
			$slider->update();
		}

		echo "Data berhasil disimpan.";
	}



	function add_gambar()
	{
		$this->load->model("Slider");
		$slider = new Slider();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");
		$reqJenis					= $this->input->post("reqJenis");
		$reqJudulFile					= $this->input->post("reqJudulFile");


		$reqNama 				 	= $this->input->post("reqNama");
		$reqKeterangan 				= $_POST["reqKeterangan"];
		$reqTanggal 				= $this->input->post("reqTanggal");
		$reqJam 				= $this->input->post("reqJam");

		$reqSliderDetilId = $this->input->post("reqSliderDetilId");
		$reqJudulFileEdit = $this->input->post("reqJudulFileEdit");

		$slider->setField("SLIDER_ID", $reqId);
		$slider->setField("NAMA", $reqNama);
		$slider->setField("JENIS", $reqJenis);
		$slider->setField("KETERANGAN", $reqKeterangan);
		$slider->setField("TANGGAL", "TO_TIMESTAMP('" . $reqTanggal . " " . $reqJam . "', 'DD-MM-YYYY HH24:MI')");



		/* WAJIB UNTUK UPLOAD DATA */
		include_once("functions/image.func.php");
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR = "uploads/";

		$reqLinkFile = $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");


		if ($reqMode == "insert") {

			$i = 0;
			$renameFile = "" . date("dmYhis") . rand() . "." . getExtension($reqLinkFile['name'][$i]);


			if ($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i)) {

				createThumbnail($FILE_DIR . $renameFile, $FILE_DIR . $reqJenis . $renameFile, 800);
				unlink($FILE_DIR . $renameFile);

				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $reqJenis . $renameFile;
			} else {

				$insertLinkSize = $reqLinkFileTempSize[$i];
				$insertLinkTipe =  $reqLinkFileTempTipe[$i];
				$insertLinkFile =  $reqLinkFileTemp[$i];
			}

			$slider->setField("LINK_FILE", $insertLinkFile);

			$slider->setField("LAST_CREATE_USER", $this->USERNAME);
			$slider->insert();
			$reqId = $slider->id;
		} else {

			$i = 0;
			$insertLinkFile =  $reqLinkFileTemp[$i];
			$slider->setField("LINK_FILE", $insertLinkFile);
			$slider->setField("LAST_UPDATE_USER", $this->USERNAME);
			$slider->update();

			for ($i = 0; $i < count($reqSliderDetilId); $i++) {

				$slider_detil = new Slider();
				$slider_detil->setField("NAMA", $reqJudulFileEdit[$i]);
				$slider_detil->setField("SLIDER_DETIL_ID", $reqSliderDetilId[$i]);
				$slider_detil->updateDetil();
			}
		}

		/* UPLOAD YANG BARU NIYEE */
		//$reqJudulFile
		//$reqLinkFile			
		$reqLinkFile = $_FILES["reqLinkFile"];
		$reqJenis = "GAMBARDET" . $reqId;
		for ($i = 0; $i < count($reqJudulFile); $i++) {

			$slider_detil = new Slider();
			$file = new FileHandler();

			$renameFile = "" . date("dmYhis") . rand() . "." . getExtension($reqLinkFile['name'][$i]);

			if ($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i)) {

				createThumbnail($FILE_DIR . $renameFile, $FILE_DIR . $reqJenis . $renameFile, 800);
				unlink($FILE_DIR . $renameFile);

				$insertLinkSize = $file->uploadedSize;
				$insertLinkTipe =  $file->uploadedExtension;
				$insertLinkFile =  $reqJenis . $renameFile;

				$slider_detil->setField("SLIDER_ID", $reqId);
				$slider_detil->setField("NAMA", $reqJudulFile[$i]);
				$slider_detil->setField("KETERANGAN", $reqKeterangan);
				$slider_detil->setField("LINK_FILE", $insertLinkFile);
				$slider_detil->setField("LAST_CREATE_USER", $this->USERNAME);
				$slider_detil->insertDetil();
				$slider_detil->updateLinkSlider();
			} else {
			}
			unset($file);
		}

		echo "Data berhasil disimpan.";
	}

	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Slider");
		$slider = new Slider();


		$slider->setField("SLIDER_ID", $reqId);
		if ($slider->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}



	function delete_detil()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Slider");

		$slider = new Slider();
		$slider->selectByParamsDetil(array("SLIDER_DETIL_ID" => $reqId));
		$slider->firstRow();
		$reqSliderId = $slider->getField("SLIDER_ID");
		$reqLinkFile = $slider->getField("LINK_FILE");

		$FILE_DIR = "uploads/" . $reqLinkFile;
		unlink($FILE_DIR);

		$slider = new Slider();


		$slider->setField("SLIDER_ID", $reqSliderId);
		$slider->setField("SLIDER_DETIL_ID", $reqId);
		if ($slider->deleteDetil())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}


	function delete_komentar()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Slider");
		$slider = new Slider();


		$slider->setField("SLIDER_KOMENTAR_ID", $reqId);
		if ($slider->deleteKomentar())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}


	function kirim_notifikasi()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Slider");
		$this->load->model("Notifikasi");
		$slider = new Slider();


		$slider->setField("SLIDER_ID", $reqId);
		$slider->setField("FIELD", "STATUS_NOTIFIKASI");
		$slider->setField("FIELD_VALUE", "Y");
		$slider->setField("LAST_UPDATE_USER", $this->USERNAME);
		if ($slider->updateByField()) {
			$slider = new Slider();
			$slider->selectByParams(array("A.SLIDER_ID" => $reqId));
			$slider->firstRow();

			$reqTitle  = $slider->getField("NAMA");
			$reqBody   = truncate($slider->getField("KETERANGAN"), 15) . "...";
			$reqGambar = base_url() . "uploads/" . $slider->getField("LINK_FILE");
			$reqJenis  = $slider->getField("JENIS");

			$this->load->library("PushNotification");

			$row = array();
			$row['to'] = "/topics/informasi";
			$row['data']["id"] = $reqId;
			$row['data']["title"] = $reqTitle;
			$row['data']["body"] = $reqBody;
			$row['data']["gambar"] = $reqGambar;
			$row['data']["jenis_informasi"] = $reqJenis;
			$row['data']["tipe"] = "INFORMASI"; // INFORMASI / CHAT

			$pushData = $row;
			$pushNotification = new PushNotification();
			$pushNotification->send_notification_v2($pushData);
			unset($row);

			/* INSERT NOTIFIKASI*/
			$notifikasi = new Notifikasi();
			$notifikasi->setField("SLIDER_ID", $reqId);
			$notifikasi->insertInformasi();

			echo "Data berhasil dikirim.";
		} else {
			echo "Data gagal dikirim.";
		}
	}



	function kirim_notifikasi_email()
	{

		ini_set("memory_limit", "500M");
		ini_set('max_execution_time', 5200);
		$reqId	= $this->input->get('reqId');
		$this->load->model("Slider");
		$this->load->model("Notifikasi");
		$slider = new Slider();

		$slider->setField("SLIDER_ID", $reqId);
		$slider->setField("FIELD", "STATUS_NOTIFIKASI");
		$slider->setField("FIELD_VALUE", "Y");
		$slider->setField("LAST_UPDATE_USER", $this->USERNAME);
		if ($slider->updateByField()) {
			$slider = new Slider();
			$slider->selectByParams(array("A.SLIDER_ID" => $reqId));
			$slider->firstRow();

			$reqTitle  = $slider->getField("NAMA");
			$reqBody   = truncate($slider->getField("KETERANGAN"), 15) . "...";
			$reqJenis  = "PESAN";

			$this->load->library("PushNotification");

			$row = array();
			$row['to'] = "/topics/informasi";
			$row['data']["id"] = $reqId;
			$row['data']["title"] = $reqTitle;
			$row['data']["body"] = $reqBody;
			$row['data']["jenis_informasi"] = $reqJenis;
			$row['data']["tipe"] = "INFORMASI"; // INFORMASI / CHAT

			$pushData = $row;
			$pushNotification = new PushNotification();
			$pushNotification->send_notification_v2($pushData);
			unset($row);

			/* INSERT NOTIFIKASI*/
			$notifikasi = new Notifikasi();
			$notifikasi->setField("SLIDER_ID", $reqId);
			$notifikasi->insertPesan();

			/* EMAIL NOTIFIKASI*/
			$this->load->library("KMail");
			$mail = new KMail();
			$body = file_get_contents(base_url() . "login/loadUrl/email/pesan/" . $reqId);

			$this->load->model("Pegawai");
			$pegawai = new Pegawai();

			$pegawai->selectByParams(array("VALIDASI" => "1"), -1, -1, $statementMail . " AND NOT COALESCE(NULLIF(EMAIL_PRIBADI, ''), 'X') = 'X' AND NOT COALESCE(NULLIF(EMAIL_BULOG, ''), 'X') = 'X' ");
			$i = 1;
			$dataKe = 1;
			while ($pegawai->nextRow()) {
				$mail = new KMail();
				$reqMailNama = $pegawai->getField("NAMA");
				$reqMail = $pegawai->getField("EMAIL_PRIBADI");
				if (strstr($reqMail, "@"))
					$mail->AddAddress($reqMail, $reqMailNama);

				if ($reqMail == "") {
					$reqMail = $pegawai->getField("EMAIL_BULOG");
					if (strstr($reqMail, "@"))
						$mail->AddAddress($reqMail, $reqMailNama);
				}

				$mail->Subject  =  "[SEKAR] " . $reqTitle;
				$mail->MsgHTML($body);
				$mail->Send();
				unset($mail);
			}

			echo "Data berhasil dikirim.";
		} else {
			echo "Data gagal dikirim.";
		}
	}

	function publish()
	{
		$reqId	= $this->input->get('reqId');
		$reqStatus	= $this->input->get('reqStatus');

		$this->load->model("Slider");
		$slider = new Slider();


		$slider->setField("SLIDER_ID", $reqId);
		$slider->setField("FIELD", "STATUS_PUBLISH");
		$slider->setField("FIELD_VALUE", $reqStatus);
		$slider->setField("LAST_UPDATE_USER", $this->USERNAME);
		if ($slider->updateByField()) {
			echo "Status publish berhasil diubah.";
		} else {
			echo "Status publish gagal diubah.";
		}
	}

	function combo()
	{
		$this->load->model("Slider");
		$slider = new Slider();

		$slider->selectByParams(array());
		$i = 0;
		while ($slider->nextRow()) {
			$arr_json[$i]['id']		= $slider->getField("SLIDER_ID");
			$arr_json[$i]['text']	= $slider->getField("NAMA");
			$i++;
		}

		echo json_encode($arr_json);
	}
}
