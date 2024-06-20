<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class polling_json	 extends CI_Controller {

	function __construct() {
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity())
		{
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
		$this->load->model("Polling");
		$polling = new Polling();

		// $reqKategori = $this->input->get("reqKategori");
		// echo $reqKategori;exit;

		$aColumns		= array("POLLING_ID", "NAMA", "KETERANGAN", "TANGGAL_AWAL", "TANGGAL_AKHIR");
		$aColumnsAlias	= array("POLLING_ID", "NAMA", "KETERANGAN", "TANGGAL_AWAL", "TANGGAL_AKHIR");


		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];

					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );

			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY A.POLLING_ID asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.POLLING_ID desc";

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
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}

		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];

			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else
			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			$dsplyRange = 2147483645;
		}

		$statement_privacy .= " ";

		 $statement= " AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $polling->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $polling->getCountByParams(array(), $statement_privacy.$statement);

		 $polling->selectByParamsEntri(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);

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

		while($polling->nextRow())
		{
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($polling->getField($aColumns[$i]), 2);
				else
					$row[] = $polling->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );


	}

	function add()
	{
		$this->load->model("Polling");
		$this->load->model("PollingDetil");
		$polling = new Polling();

		$reqMode 					= $this->input->post("reqMode");
		$reqId 						= $this->input->post("reqId");

		$reqNama 				 	= $this->input->post("reqNama");
		$reqTanggalAwal 				 	= $this->input->post("reqTanggalAwal");
		$reqTanggalAkhir 				 	= $this->input->post("reqTanggalAkhir");
		$reqKeterangan 				= $_POST["reqKeterangan"];
		$reqJawaban 				= $this->input->post("reqJawaban");
		$reqPollingDetilId			= $this->input->post("reqPollingDetilId");


		$polling->setField("POLLING_ID", $reqId);
		$polling->setField("NAMA", $reqNama);
		$polling->setField("TANGGAL_AWAL", dateToDBCheck($reqTanggalAwal));
		$polling->setField("TANGGAL_AKHIR", dateToDBCheck($reqTanggalAkhir));
		$polling->setField("KETERANGAN", $reqKeterangan);

		if($reqMode == "insert")
		{
			$polling->setField("LAST_CREATE_USER", $this->USERNAME);
			$polling->insert();
			$reqId = $polling->id;
		}
		else
		{
			$polling->setField("LAST_UPDATE_USER", $this->USERNAME);
			$polling->update();
		}

		for($i=0;$i<count($reqPollingDetilId);$i++)
		{
			$polling_detil = new PollingDetil();

			$polling_detil->setField("POLLING_DETIL_ID", $reqPollingDetilId[$i]);
			$polling_detil->setField("POLLING_ID", $reqId);
			$polling_detil->setField("NAMA", $reqJawaban[$i]);
			$polling_detil->setField("LAST_CREATE_USER", $this->USERNAME);
			$polling_detil->setField("LAST_UPDATE_USER", $this->USERNAME);

			if($reqPollingDetilId[$i] == "")
				$polling_detil->insert();
			else
				$polling_detil->update();
		}

		echo "Data berhasil disimpan.";

	}

	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Polling");
		$polling = new Polling();


		$polling->setField("POLLING_ID", $reqId);
		if($polling->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}


	function delete_jawaban()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("PollingDetil");
		$polling = new PollingDetil();


		$polling->setField("POLLING_DETIL_ID", $reqId);
		if($polling->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}



	function combo()
	{
		$this->load->model("Polling");
		$polling = new Polling();

		$polling->selectByParams(array());
		$i = 0;
		while($polling->nextRow())
		{
			$arr_json[$i]['id']		= $polling->getField("POLLING_ID");
			$arr_json[$i]['text']	= $polling->getField("NAMA");
			$i++;
		}

		echo json_encode($arr_json);
	}

}
