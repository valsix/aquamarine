<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class pegawai_json extends CI_Controller
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
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		$reqCabangId = $this->input->get("reqCabangId");
		$reqGolonganDarah = $this->input->get("reqGolonganDarah");
		$reqJenisKelamin = $this->input->get("reqJenisKelamin");
		$reqMode = $this->input->get("reqMode");

		// echo $reqKategori;exit;

		$aColumns		= array(
			"PEGAWAI_ID", "NO_SEKAR", "NIP", "NAMA", "NAMA_PANGGILAN", "JENIS_KELAMIN", "TEMPAT_LAHIR",
			"TANGGAL_LAHIR", "GOLONGAN_DARAH", "UNIT_KERJA", "ALAMAT", "NOMOR_HP", "EMAIL_PRIBADI", "EMAIL_BULOG",
			"NOMOR_WA"
		);
		$aColumnsAlias	= array(
			"PEGAWAI_ID", "NRP", "NIP", "NAMA", "NAMA_PANGGILAN", "JENIS_KELAMIN", "TEMPAT_LAHIR",
			"TANGGAL_LAHIR", "GOLONGAN_DARAH", "UNIT_KERJA", "ALAMAT", "NOMOR_HP", "EMAIL_PRIBADI", "EMAIL_BULOG",
			"NOMOR_WA"
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
			if (trim($sOrder) == "ORDER BY PEGAWAI_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY PEGAWAI_ID asc";
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

		if ($reqMode == "validasi")
			$statement_privacy = " AND VALIDASI = '0' ";
		elseif ($reqMode == "tolak")
			$statement_privacy = " AND VALIDASI = 'X' ";
		else
			$statement_privacy = " AND VALIDASI = '1' ";


		if (!empty($reqCabangId)) {
			$statement_privacy .= " AND A.CABANG_ID = '" . $reqCabangId . "' ";
		}
		if (!empty($reqGolonganDarah)) {
			$statement_privacy .= " AND UPPER(GOLONGAN_DARAH) = '" . $reqGolonganDarah . "' ";
		}
		if (!empty($reqJenisKelamin)) {
			$statement_privacy .= " AND UPPER(JENIS_KELAMIN) = '" . $reqJenisKelamin . "' ";
		}


		$statement = " AND (UPPER(NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$allRecord = $pegawai->getCountByParams(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $pegawai->getCountByParams(array(), $statement_privacy . $statement);

		$pegawai->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

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

		while ($pegawai->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($pegawai->getField($aColumns[$i]), 10) . "...";
				elseif ($aColumns[$i] == "LINK_FILE")
					$row[] = "<img src='uploads/" . $pegawai->getField($aColumns[$i]) . "' height='50px'>";
				elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
					if ($pegawai->getField($aColumns[$i]) == "Y")
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					else
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
				} else
					$row[] = $pegawai->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function pantau_json()
	{
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		$reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;

		$aColumns		= array("PEGAWAI_ID", "PEGAWAI_ID", "NAMA", "CABANG", "JABATAN", "LOGIN_TERAKHIR");
		$aColumnsAlias	= array("PEGAWAI_ID", "PEGAWAI_ID", "NAMA", "CABANG", "JABATAN", "LOGIN_TERAKHIR");


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
			if (trim($sOrder) == "ORDER BY A.PEGAWAI_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.NAMA asc";
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

		$statement_privacy .= " ";

		$statement = " AND (UPPER(NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$allRecord = $pegawai->getCountByParamsLoginTerakhir(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $pegawai->getCountByParamsLoginTerakhir(array(), $statement_privacy . $statement);

		$pegawai->selectByParamsLoginTerakhir(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

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

		while ($pegawai->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($pegawai->getField($aColumns[$i]), 2);
				else
					$row[] = $pegawai->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}

	function add()
	{
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		$reqMode 						= $this->input->post("reqMode");
		$reqId 							= $this->input->post("reqId");
		$reqNrp							= $this->input->post("reqNrp");
		$reqNip							= $this->input->post("reqNip");
		$reqNama						= $this->input->post("reqNama");
		$reqNamaPanggilan				= $this->input->post("reqNamaPanggilan");
		$reqJenisKelamin				= $this->input->post("reqJenisKelamin");
		$reqTempatLahir					= $this->input->post("reqTempatLahir");
		$reqTanggalLahir				= $this->input->post("reqTanggalLahir");
		$reqUnitKerja				= $this->input->post("reqUnitKerja");
		$reqAlamat					= $this->input->post("reqAlamat");
		$reqNomorHp					= $this->input->post("reqNomorHp");
		$reqEmailPribadi		= $this->input->post("reqEmailPribadi");
		$reqEmailBulog			= $this->input->post("reqEmailBulog");
		$reqNomorWa					= $this->input->post("reqNomorWa");
		//	echo $reqNomorHp	;
		$pegawai->setField("PEGAWAI_ID", $reqId);
		$pegawai->setField("NRP", $reqNrp);
		$pegawai->setField("NIP", $reqNip);
		$pegawai->setField("NAMA", $reqNama);
		$pegawai->setField("NAMA_PANGGILAN", $reqNamaPanggilan);
		$pegawai->setField("JENIS_KELAMIN", $reqJenisKelamin);
		$pegawai->setField("TEMPAT_LAHIR", $reqTempatLahir);
		$pegawai->setField("TANGGAL_LAHIR", $reqTanggalLahir);
		$pegawai->setField("UNIT_KERJA", $reqUnitKerja);
		$pegawai->setField("ALAMAT", $reqAlamat);
		$pegawai->setField("NOMOR_HP", $reqNomorHp);
		$pegawai->setField("EMAIL_PRIBADI", $reqEmailPribadi);
		$pegawai->setField("EMAIL_BULOG", $reqEmailBulog);
		$pegawai->setField("NOMOR_WA", $reqNomorWa);




		if ($reqMode == "insert") {
			$pegawai->setField("CREATED_BY", $this->USERNAME);
			$pegawai->insert();
		} else {
			$pegawai->setField("UPDATED_BY", $this->USERNAME);
			$pegawai->update();
		}

		echo "Data berhasil disimpan.";
	}



	function koreksi()
	{
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		$reqMode 						= $this->input->post("reqMode");
		$reqId 							= $this->input->post("reqId");
		$reqNrp							= $this->input->post("reqNrp");
		$reqNip							= $this->input->post("reqNip");
		$reqSekar						= $this->input->post("reqSekar");
		//	echo $reqNomorHp	;
		$pegawai->setField("PEGAWAI_ID", $reqId);
		$pegawai->setField("NRP", $reqNrp);
		$pegawai->setField("NIP", $reqNip);
		$pegawai->setField("NO_SEKAR", $reqSekar);
		$pegawai->setField("UPDATED_BY", $this->USERNAME);
		$pegawai->koreksi();
		echo "Data berhasil disimpan.";
	}



	function validasi()
	{
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		$reqMode 						= $this->input->post("reqMode");
		$reqId 							= $this->input->post("reqId");
		$reqNrp							= $this->input->post("reqNrp");
		$reqNip							= $this->input->post("reqNip");
		$reqNama						= $this->input->post("reqNama");
		$reqNamaPanggilan				= $this->input->post("reqNamaPanggilan");
		$reqJenisKelamin				= $this->input->post("reqJenisKelamin");
		$reqTempatLahir					= $this->input->post("reqTempatLahir");
		$reqTanggalLahir				= $this->input->post("reqTanggalLahir");
		$reqUnitKerja				= $this->input->post("reqUnitKerja");
		$reqAlamat					= $this->input->post("reqAlamat");
		$reqNomorHp					= $this->input->post("reqNomorHp");
		$reqEmailPribadi		= $this->input->post("reqEmailPribadi");
		$reqEmailBulog			= $this->input->post("reqEmailBulog");
		$reqNomorWa					= $this->input->post("reqNomorWa");
		$reqValidasi					= $this->input->post("reqValidasi");
		//	echo $reqNomorHp	;

		if ($reqValidasi == "TOLAK")
			$reqValidasi = "X";
		else
			$reqValidasi = "1";

		$pegawai->setField("VALIDASI", $reqValidasi);
		$pegawai->setField("PEGAWAI_ID", $reqId);
		$pegawai->setField("UPDATED_BY", $this->USERNAME);
		if ($reqValidasi == "X")
			$pegawai->validasi();
		else
			$pegawai->validasiSetuju();

		echo "Data berhasil disimpan.";
	}

	function import()
	{
		include "libraries/excel/excel_reader2.php";
		$data = new Spreadsheet_Excel_Reader($_FILES['reqLinkFile']['tmp_name']);

		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		$baris = $data->rowcount($sheet_index = 0);

		for ($i = 2; $i <= $baris; $i++) {
			$reqNrp				= $data->val($i, 1);
			$reqNip				= $this->val($i, 2);;
			$reqNama			= $this->val($i, 3);
			$reqNamaPanggilan	= $this->val($i, 4);
			$reqJenisKelamin	= $this->val($i, 5);
			$reqTempatLahir		= $this->val($i, 6);
			$reqTanggalLahir	= $this->val($i, 7);
			$reqUnitKerja		= $this->val($i, 8);
			$reqAlamat			= $this->val($i, 9);
			$reqNomorHp			= $this->val($i, 10);
			$reqEmailPribadi	= $this->val($i, 11);
			$reqEmailBulog		= $this->val($i, 12);
			$reqNomorWa			= $this->val($i, 13);

			$pegawai->setField("PEGAWAI_ID", $reqId);
			$pegawai->setField("NRP", $reqNrp);
			$pegawai->setField("NIP", $reqNip);
			$pegawai->setField("NAMA", $reqNama);
			$pegawai->setField("NAMA_PANGGILAN", $reqNamaPanggilan);
			$pegawai->setField("JENIS_KELAMIN", $reqJenisKelamin);
			$pegawai->setField("TEMPAT_LAHIR", $reqTempatLahir);
			$pegawai->setField("TANGGAL_LAHIR", $reqTanggalLahir);
			$pegawai->setField("UNIT_KERJA", $reqUnitKerja);
			$pegawai->setField("ALAMAT", $reqAlamat);
			$pegawai->setField("NOMOR_HP", $reqNomorHp);
			$pegawai->setField("EMAIL_PRIBADI", $reqEmailPribadi);
			$pegawai->setField("EMAIL_BULOG", $reqEmailBulog);
			$pegawai->setField("NOMOR_WA", $reqNomorWa);

			$pegawai->setField("CREATED_BY", $this->USERNAME);
			$pegawai->insert();
		}

		echo "Data berhasil diimport.";
	}




	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();


		$pegawai->setField("PEGAWAI_ID", $reqId);
		if ($pegawai->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}


	function combo()
	{
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
		$offset = ($page - 1) * $rows;
		$reqPencarian = $this->input->get("reqPencarian");


		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		if ($reqPencarian == "") {
		} else
			$statement = " AND (UPPER(A.NAMA) LIKE '%" . strtoupper($reqPencarian) . "%' OR UPPER(A.NIP) LIKE '%" . strtoupper($reqPencarian) . "%') ";

		$rowCount = $pegawai->getCountByParams(array("VALIDASI" => "1"), $statement);
		$pegawai->selectByParams(array("VALIDASI" => "1"), $rows, $offset, $statement);
		$i = 0;
		$items = array();
		while ($pegawai->nextRow()) {
			$row['id']		= $pegawai->getField("NIP");
			$row['text']	= $pegawai->getField("NAMA");
			$row['PEGAWAI_ID']	= $pegawai->getField("NIP");
			$row['NAMA']	= $pegawai->getField("NAMA");
			$row['CABANG']	= $pegawai->getField("UNIT_KERJA");
			$row['JABATAN']	= $pegawai->getField("JABATAN");
			$row['state'] = 'open';
			$i++;
			array_push($items, $row);
		}
		$result["rows"] = $items;
		$result["total"] = $rowCount;
		echo json_encode($result);
	}


	function excel()
	{

		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		$reqCabangId = $this->input->get("reqCabangId");
		$reqGolonganDarah = $this->input->get("reqGolonganDarah");
		$reqJenisKelamin = $this->input->get("reqJenisKelamin");
		$reqMode = $this->input->get("reqMode");


		$aColumns		= array(
			"PEGAWAI_ID", "NO_SEKAR", "NIP", "NAMA", "NAMA_PANGGILAN", "JENIS_KELAMIN", "TEMPAT_LAHIR",
			"TANGGAL_LAHIR", "GOLONGAN_DARAH", "UNIT_KERJA", "ALAMAT", "NOMOR_HP", "EMAIL_PRIBADI", "EMAIL_BULOG",
			"NOMOR_WA"
		);


		if ($reqMode == "validasi")
			$statement_privacy = " AND VALIDASI = '0' ";
		elseif ($reqMode == "tolak")
			$statement_privacy = " AND VALIDASI = 'X' ";
		else
			$statement_privacy = " AND VALIDASI = '1' ";


		if (!empty($reqCabangId)) {
			$statement_privacy .= " AND A.CABANG_ID = '" . $reqCabangId . "' ";
		}
		if (!empty($reqGolonganDarah)) {
			$statement_privacy .= " AND UPPER(GOLONGAN_DARAH) = '" . $reqGolonganDarah . "' ";
		}
		if (!empty($reqJenisKelamin)) {
			$statement_privacy .= " AND UPPER(JENIS_KELAMIN) = '" . $reqJenisKelamin . "' ";
		}


		$pegawai->selectByParams(array(), -1, -1, $statement_privacy . $statement, $sOrder);

		$iData = 0;
		while ($pegawai->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "NO_SEKAR")
					$arr_json[$iData][$aColumns[$i]]	= "NO:" . $pegawai->getField($aColumns[$i]);
				else
					$arr_json[$iData][$aColumns[$i]]	= $pegawai->getField($aColumns[$i]);
			}
			$iData++;
		}

		$fileName = "anggota_export.xls";

		if ($arr_json) {
			function filterData(&$str)
			{
				$str = preg_replace("/\t/", "\\t", $str);
				$str = preg_replace("/\r?\n/", "\\n", $str);


				if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
			}

			// headers for download
			header("Content-Disposition: attachment; filename=\"$fileName\"");
			header("Content-Type: application/vnd.ms-excel;");

			$flag = false;
			foreach ($arr_json as $row) {
				if (!$flag) {
					// display column names as first row
					echo implode("\t", array_keys($row)) . "\n";
					$flag = true;
				}
				// filter data
				array_walk($row, 'filterData');
				echo implode("\t", array_values($row)) . "\n";
			}
			exit;
		}
	}
}
