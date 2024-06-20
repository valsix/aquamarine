<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class chat_json extends CI_Controller
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
		$this->load->model('Chat');
		$chat = new Chat();

		ini_set("memory_limit", "500M");
		ini_set('max_execution_time', 520);

		$aColumns = array('PEGAWAI_ID', 'WAKTU',  'PEGAWAI_ID', 'PEGAWAI', 'CABANG', 'JABATAN', 'NAMA', 'IP_ADDRESS');
		$aColumnsAlias = array('PEGAWAI_ID', 'WAKTU', 'PEGAWAI_ID', 'PEGAWAI', 'CABANG', 'JABATAN', 'NAMA',  'IP_ADDRESS');

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
				$sOrder = " ORDER BY JAM DESC";
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


		$allRecord = $chat->getCountByParamsTerakhir(array());
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter = $chat->getCountByParamsTerakhir(array(), " AND (UPPER(A.NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR UPPER(A.PEGAWAI_ID) LIKE '%" . strtoupper($_GET['sSearch']) . "%')");

		$chat->selectByParamsTerakhir(array(), $dsplyRange, $dsplyStart, " AND (UPPER(A.NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR UPPER(A.PEGAWAI_ID) LIKE '%" . strtoupper($_GET['sSearch']) . "%')", $sOrder);

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

		while ($chat->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "TANGGAL_FIX")
					$row[] = getDayMonth($chat->getField(trim($aColumns[$i])));
				else
					$row[] = $chat->getField(trim($aColumns[$i]));
			}

			$output['aaData'][] = $row;
			$duk++;
		}

		echo json_encode($output);
	}


	function php_shoutbox()
	{
		$this->load->model("Chat");


		$reqHalaman = $this->input->post("reqHalaman");
		$reqKode = $this->input->post("reqKode");
		$reqPegawaiId = $this->input->post("reqPegawaiId");
		$reqId = $this->input->get("reqId");
		//echo $reqPaketPenawaranId;exit;
		/* validasi */


		function replace(&$item, $key)
		{
			$item = str_replace('|', '-', $item);
		}

		if (!function_exists('file_put_contents')) {
			function file_put_contents($fileName, $data)
			{
				if (is_array($data)) {
					$data = join('', $data);
				}
				$res = @fopen($fileName, 'w+b');
				if ($res) {
					$write = @fwrite($res, $data);
					if ($write === false) {
						return false;
					} else {
						return $write;
					}
				}
			}
		}

		//file_put_contents('debug.txt', print_r($_GET, true));
		switch ($_GET['action']) {
			case 'add':
				array_walk($_POST, 'replace');
				$_POST['nickname'] = htmlentities($_POST['nickname']);
				$_POST['message'] = htmlentities($_POST['message']);
				$time = time();
				$arr[] = $time . '|' . $_POST['nickname'] . '|' . $_POST['message'] . '|' . $_SERVER['REMOTE_ADDR'] . "\n";
				//echo $reqPaketPenawaranId;exit;
				$php_shoutbox = new Chat();
				$php_shoutbox->setField("JAM", $time);
				$php_shoutbox->setField("NAMA", $_POST['nickname']);
				$php_shoutbox->setField("PESAN", formatTextToDb(strtoupper($_POST['message'])));
				$php_shoutbox->setField("IP_ADDRESS", $_SERVER['REMOTE_ADDR']);
				$php_shoutbox->setField("PEGAWAI_ID", ($reqPegawaiId));
				$php_shoutbox->setField("HALAMAN", $reqHalaman);
				$php_shoutbox->setField("KODE", $reqKode);
				$php_shoutbox->insert();

				$data['response'] = 'Good work';
				$data['nickname'] = $_POST['nickname'];
				$data['message'] = $_POST['message'];
				$data['waktu'] = $php_shoutbox->getWaktu();
				$data['time'] = $time;


				$reqTitle  = "Operator membalas pesan anda.";
				$reqBody   = $_POST['message'];

				/* INSERT NOTIFIKASI */
				$this->load->model("Notifikasi");
				$notifikasi = new Notifikasi();
				$notifikasi->setField("PEGAWAI_ID", $reqPegawaiId);
				$notifikasi->setField("NAMA", $reqTitle);
				$notifikasi->setField("KETERANGAN", $reqBody);
				$notifikasi->setField("JENIS", "CHAT");
				$notifikasi->setField("TYPE", "CHAT");
				$notifikasi->insert();

				$this->load->library("PushNotification");
				$this->load->model("UserLoginMobile");

				$user_login_mobile = new UserLoginMobile();
				$user_login_mobile->selectByParams(array("A.PEGAWAI_ID" => $reqPegawaiId, "A.STATUS" => "1"));

				while ($user_login_mobile->nextRow()) {

					$row = array();
					$row['to'] = $user_login_mobile->getField("TOKEN_FIREBASE");
					$row['data']["notifikasi_id"] = $notifikasi->id;
					$row['data']["title"] = $reqTitle;
					$row['data']["body"] = $reqBody;
					$row['data']["tipe"] = "CHAT"; // INFORMASI / CHAT

					$pushData = $row;
					$pushNotification = new PushNotification();
					$pushNotification->send_notification_v2($pushData);
					unset($row);
				}

				break;

			case 'view':
				$data = array();
				if (!$_GET['time'])
					$_GET['time'] = 0;
				$php_shoutbox = new Chat();
				$php_shoutbox->selectByParams(array("PEGAWAI_ID" => ($reqId)));

				while ($php_shoutbox->nextRow()) {
					$row = $php_shoutbox->getField("JAM") . "|" . $php_shoutbox->getField("WAKTU") . "|" . $php_shoutbox->getField("NAMA") . "|" . $php_shoutbox->getField("PESAN") . "|" . $php_shoutbox->getField("HALAMAN");
					list($aTemp['time'], $aTemp['waktu'], $aTemp['nickname'], $aTemp['message'], $aTemp['halaman']) = explode('|', $row);
					if ($aTemp['message'] and $aTemp['time'] > $_GET['time'])
						$data[] = $aTemp;
				}
				break;
		}

		require_once('libraries/JSON.php');
		$json = new Services_JSON();
		$out = $json->encode($data);
		print $out;
	}
}
