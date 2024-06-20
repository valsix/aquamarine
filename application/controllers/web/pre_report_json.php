<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class pre_report_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID = $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA = $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN = $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES = $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN = $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID = $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP = $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->CABANG_ID = $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->CABANG = $this->kauth->getInstance()->getIdentity()->CABANG;
	}

	function json()
	{
		// echo "tes";
		$this->load->model("Document");
		$document = new Document();
		// var_dump($document);
		// echo $reqKategori;exit;

		$reqKategori = $this->input->get('reqKategori');

		$aColumns		= array("DOCUMENT_ID", "CATEGORY", "NAME", "DESCRIPTION", "PATH", "LAST_REVISI", "EXPIRED_DATE", "EXP");
		$aColumnsAlias	= array("DOCUMENT_ID", "CATEGORY", "NAME", "DESCRIPTION", "PATH", "LAST_REVISI", "EXPIRED_DATE", "EXP");

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



		// $statement = " AND (UPPER(ANAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$allRecord = $document->getCountByParams(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $document->getCountByParams(array(), $statement_privacy . $statement);

		$document->selectByParamsDokument(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		// echo $document->query;exit;

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

		while ($document->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($document->getField($aColumns[$i]), 10) . "...";
				elseif ($aColumns[$i] == "LINK_FILE")
					$row[] = "<a href='uploads/" . $document->getField($aColumns[$i]) . "' height='50px' target='_blank'>Unduh</a>";
				elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
					if ($document->getField($aColumns[$i]) == "Y")
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					else
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
				} else
					$row[] = $document->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function  add()
	{
		$this->load->model('Document');

		$this->load->library("FileHandler");
		$file = new FileHandler();
		$filesData = $_FILES["document"];
		$reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
		$file->cekSize($filesData,$reqLinkFileTemp);


		$reqId 				= $this->input->post('reqId');
		$reqKeterangan 		= $this->input->post('reqKeterangan');
		$reqNama 		 	= $this->input->post('reqNama');
		$reqTipe 		 	= $this->input->post('reqTipe');


		$name_folder = strtolower(str_replace(' ', '_', $reqTipe));

		$document = new Document();
		$document->setField("DOCUMENT_ID", $reqId);
		$document->setField("NAME", $reqNama);
		$document->setField("CATEGORY", $reqTipe);
		$document->setField("DESCRIPTION", $reqKeterangan);

		if (empty($reqId)) {
			$document->insert();
			$reqId = $document->id;
		} else {
			$document->update();
		}


		
		// exit;
		$FILE_DIR = "uploads/" . $name_folder . "/" . $reqId . "/";
		makedirs($FILE_DIR);

		$arrData = array();
		for ($i = 0; $i < count($filesData['name']); $i++) {
			$renameFile = $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
			if ($file->uploadToDirArray('document', $FILE_DIR, $renameFile, $i)) {
				array_push($arrData, setQuote($renameFile));
			} else {
				array_push($arrData, $reqLinkFileTemp[$i]);
			}
		}
		
		$str_name_path = '';
		for ($i = 0; $i < count($arrData); $i++) {
			if (!empty($arrData[$i])) {
				if ($i == 0) {
					$str_name_path .= $arrData[$i];
				} else {
					$str_name_path .= ';' . $arrData[$i];
				}
			}
		}

		$document = new Document();
		$document->setField("DOCUMENT_ID", $reqId);
		$document->setField("PATH", ($str_name_path));
		$document->updatePath();



		echo $reqId . '- Data berhasil di simpan';
	}


	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Pengurus");
		$pengurus = new Pengurus();


		$pengurus->setField("PENGURUS_ID", $reqId);
		if ($pengurus->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
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
		$katerori = $this->input->post("reqTipe");



		$this->load->model('Document');
		for ($i = 2; $i <= $baris; $i++) {
			$document = new Document();
			$document->setField("CATEGORY", $katerori);
			$document->setField("NAME", $data->val($i, 2));
			$document->setField("DESCRIPTION", $data->val($i, 3));

			$document->insert();
			$reqId = $document->id;
			$document = new Document();
			$document->setField("DOCUMENT_ID", $reqId);
			$document->setField("CATEGORY", $katerori);
			$document->setField("NAME", $reqId . ' - ' . $data->val($i, 2));
			$document->setField("DESCRIPTION", $reqId . ' - ' . $data->val($i, 3));
			$document->update();

			// echo $data->val($i,2).'<br>';
		}
		echo 'Data Berhasil di import';
	}

	function import_excel2()
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
		$kategori = $this->input->post("reqTipe");



		$this->load->model('Document');

		for ($i = 2; $i <= $baris; $i++) {

			$document = new Document();
			$document->setField("CATEGORY", $kategori);
			$document->setField("NAME", $data->val($i, 2));
			$document->setField("DESCRIPTION", $data->val($i, 3));
			$document->setField("PATH", $data->val($i, 4));
			$document->setField("EXPIRED_DATE", $data->val($i, 5));
			$document->insert();
			$reqId = $document->id;

			$document = new Document();
			$document->setField("DOCUMENT_ID", $reqId);
			$document->setField("CATEGORY", $kategori);
			$document->setField("NAME", $reqId . ' - ' . $data->val($i, 2));
			$document->setField("DESCRIPTION", $reqId . ' - ' . $data->val($i, 3));
			$document->setField("PATH", $reqId . ' - ' . $data->val($i, 4));
			$document->setField("EXPIRED_DATE", $reqId . ' - ' . $data->val($i, 5));
			$document->update();

			// echo $data->val($i,2).'<br>';
		}
		echo 'Data Berhasil di import';
	}


	function import_excel3()
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
		$kategori = $this->input->post("reqTipe");


		$this->load->model('Document');

		for ($i = 2; $i <= $baris; $i++) {

			$document = new Document();
			$document->setField("CATEGORY", $kategori);
			$document->setField("NAME", $data->val($i, 2));
			$document->setField("DESCRIPTION", $data->val($i, 3));
			$document->setField("PATH", $data->val($i, 4));
			$document->setField("EXPIRED_DATE", $data->val($i, 5));
			$document->insert();
			$reqId = $document->id;

			$document = new Document();
			$document->setField("DOCUMENT_ID", $reqId);
			$document->setField("CATEGORY", $kategori);
			$document->setField("NAME", $reqId . ' - ' . $data->val($i, 2));
			$document->setField("DESCRIPTION", $reqId . ' - ' . $data->val($i, 3));
			$document->setField("PATH", $reqId . ' - ' . $data->val($i, 4));
			$document->setField("EXPIRED_DATE", $reqId . ' - ' . $data->val($i, 5));
			$document->update();

			// echo $data->val($i,2).'<br>';
		}
		echo 'Data Berhasil di import';
	}


	function import_excel4()
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
		$kategori = $this->input->post("reqTipe");


		$this->load->model('Document');

		for ($i = 2; $i <= $baris; $i++) {

			$document = new Document();
			$document->setField("CATEGORY", $kategori);
			$document->setField("NAME", $data->val($i, 2));
			$document->setField("DESCRIPTION", $data->val($i, 3));
			$document->setField("PATH", $data->val($i, 4));
			$document->setField("EXPIRED_DATE", $data->val($i, 5));
			$document->insert();
			$reqId = $document->id;

			$document = new Document();
			$document->setField("DOCUMENT_ID", $reqId);
			$document->setField("CATEGORY", $kategori);
			$document->setField("NAME", $reqId . ' - ' . $data->val($i, 2));
			$document->setField("DESCRIPTION", $reqId . ' - ' . $data->val($i, 3));
			$document->setField("PATH", $reqId . ' - ' . $data->val($i, 4));
			$document->setField("EXPIRED_DATE", $reqId . ' - ' . $data->val($i, 5));
			$document->update();

			// echo $data->val($i,2).'<br>';
		}
		echo 'Data Berhasil di import';
	}


	function import_excel5()
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
		$kategori = $this->input->post("reqTipe");


		$this->load->model('Document');

		for ($i = 2; $i <= $baris; $i++) {

			$document = new Document();
			$document->setField("CATEGORY", $kategori);
			$document->setField("NAME", $data->val($i, 2));
			$document->setField("DESCRIPTION", $data->val($i, 3));
			$document->setField("PATH", $data->val($i, 4));
			$document->setField("EXPIRED_DATE", $data->val($i, 5));
			$document->insert();
			$reqId = $document->id;

			$document = new Document();
			$document->setField("DOCUMENT_ID", $reqId);
			$document->setField("CATEGORY", $kategori);
			$document->setField("NAME", $reqId . ' - ' . $data->val($i, 2));
			$document->setField("DESCRIPTION", $reqId . ' - ' . $data->val($i, 3));
			$document->setField("PATH", $reqId . ' - ' . $data->val($i, 4));
			$document->setField("EXPIRED_DATE", $reqId . ' - ' . $data->val($i, 5));
			$document->update();

			// echo $data->val($i,2).'<br>';
		}
		echo 'Data Berhasil di import';
	}


	function import_excel6()
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
		$kategori = $this->input->post("reqTipe");


		$this->load->model('Document');

		for ($i = 2; $i <= $baris; $i++) {

			$document = new Document();
			$document->setField("CATEGORY", $kategori);
			$document->setField("NAME", $data->val($i, 2));
			$document->setField("DESCRIPTION", $data->val($i, 3));
			$document->setField("PATH", $data->val($i, 4));
			$document->setField("EXPIRED_DATE", $data->val($i, 5));
			$document->insert();
			$reqId = $document->id;

			$document = new Document();
			$document->setField("DOCUMENT_ID", $reqId);
			$document->setField("CATEGORY", $kategori);
			$document->setField("NAME", $reqId . ' - ' . $data->val($i, 2));
			$document->setField("DESCRIPTION", $reqId . ' - ' . $data->val($i, 3));
			$document->setField("PATH", $reqId . ' - ' . $data->val($i, 4));
			$document->setField("EXPIRED_DATE", $reqId . ' - ' . $data->val($i, 5));
			$document->update();

			// echo $data->val($i,2).'<br>';
		}
		echo 'Data Berhasil di import';
	}


	function import_excel7()
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
		$kategori = $this->input->post("reqTipe");


		$this->load->model('Document');

		for ($i = 2; $i <= $baris; $i++) {

			$document = new Document();
			$document->setField("CATEGORY", $kategori);
			$document->setField("NAME", $data->val($i, 2));
			$document->setField("DESCRIPTION", $data->val($i, 3));
			$document->setField("PATH", $data->val($i, 4));
			$document->setField("LAST_REVISI", $data->val($i, 5));
			$document->setField("EXPIRED_DATE", $data->val($i, 6));
			$document->insert();
			$reqId = $document->id;

			$document = new Document();
			$document->setField("DOCUMENT_ID", $reqId);
			$document->setField("CATEGORY", $kategori);
			$document->setField("NAME", $reqId . ' - ' . $data->val($i, 2));
			$document->setField("DESCRIPTION", $reqId . ' - ' . $data->val($i, 3));
			$document->setField("PATH", $reqId . ' - ' . $data->val($i, 4));
			$document->setField("LAST_REVISI", $reqId . ' - ' . $data->val($i, 5));
			$document->setField("EXPIRED_DATE", $reqId . ' - ' . $data->val($i, 6));
			$document->update();

			// echo $data->val($i,2).'<br>';
		}
		echo 'Data Berhasil di import';
	}


	function combo()
	{
		$this->load->model("Pengurus");
		$pengurus = new Pengurus();

		$pengurus->selectByParams(array());
		$i = 0;
		while ($pengurus->nextRow()) {
			$arr_json[$i]['id']		= $pengurus->getField("PENGURUS_ID");
			$arr_json[$i]['text']	= $pengurus->getField("NAMA");
			$i++;
		}

		echo json_encode($arr_json);
	}

	function send_as_email()
	{
		$this->load->model("ResikoEmail");
		$resiko_email = new ResikoEmail();
		$this->load->model('Document');


		$reqId    		= $this->input->post("reqId");
		$reqName3 		= $this->input->post("reqName3");
		$reqDescription = $_POST["reqDescription"];

		$arrData[$KETERANGAN] = $reqDescription;
		$reqSubject = ' Company Profile	';

		$document = new Document();
		$document->selectByParams(array("A.DOCUMENT_ID" => $reqId));
		$arrAttachemt = array();
		while ($document->nextRow()) {
			$reqPath                 = $document->getField("PATH");
			$files_data = explode(',',  $reqPath);
			for ($i = 0; $i < count($files_data); $i++) {
				if (!empty($files_data[$i])) {
					$texts = explode('-', $files_data[$i]);
					$doc_lampiran = "uploads/company_profile/" . $reqId . "/" . $files_data[$i];
					array_push($arrAttachemt, $doc_lampiran);
				}
			}
		}


		$arrData["KETERANGAN"] = $reqDescription;

		$arrDataAddres = array();
		$indexs = 0;
		$reqName3s = explode(',', $reqName3);
		for ($i = 0; $i < count($reqName3s); $i++) {
			$nama_emails = array();
			$nama_emails = explode('[', $reqName3s[$i]);
			$nama_penerima = $nama_emails[0];
			if (strpos($nama_emails, '@') !== false) {
				$nama_email =  str_replace("]", '', $nama_emails[1]);
				$arrDataAddres[$indexs]["EMAIL"] = $nama_email;
				$arrDataAddres[$indexs]["PENERIMA"] = $nama_penerima;
				$indexs++;
			}
		}

		try {
			$this->load->library("KMail");
			$mail = new KMail();
			$body =  $this->load->view('email/pesan', $arrData, true);

			// for ($i = 0; $i < count($reqName3s); $i++) {
			// 	$nama_emails = explode('[', $reqName3s[$i]);
			// 	$nama_email = str_replace(' ', '', $nama_emails[0]);

			// 	$nama_penerima = pre_regregName($reqName3s[$i]);
			// 	$mail->AddAddress($resiko_email->sendEmail($nama_email),  $nama_penerima);
			// }

			for ($i = 0; $i < count($arrDataAddres); $i++) {
				$mail->AddAddress($arrDataAddres[$i]['EMAIL'], $arrDataAddres[$i]['PENERIMA']);
			}

			for ($i = 0; $i < count($arrAttachemt); $i++) {
				$mail->addAttachment($arrAttachemt[$i]);
			}
			$mail->Subject  =  " [AQUAMARINE] " . $reqSubject;
			$mail->Body = $body;
			// $mail->MsgHTML($body);
			if (!$mail->Send()) {

				echo "Error sending: " . $mail->ErrorInfo;
			} else {
				// echo "E-mail sent to " . $email . '<br>';
			}
			// $mail->Send();

			unset($mail);
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		echo 'Email berhasil dikirim';
	}
}
