<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class pengurus_json extends CI_Controller {

	function __construct() {
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity())
		{
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->pengurus_id				= $this->kauth->getInstance()->getIdentity()->pengurus_id;
		$this->urut				= $this->kauth->getInstance()->getIdentity()->urut;
		$this->nip				= $this->kauth->getInstance()->getIdentity()->nip;
		$this->nama				= $this->kauth->getInstance()->getIdentity()->nama;
		$this->jabatan				= $this->kauth->getInstance()->getIdentity()->jabatan;
		$this->jabatan_Pengurus				= $this->kauth->getInstance()->getIdentity()->jabatan_Pengurus;
		$this->tanggal_mulai				= $this->kauth->getInstance()->getIdentity()->tanggal_mulai;
		$this->tanggal_akhir				= $this->kauth->getInstance()->getIdentity()->tanggal_akhir;
		$this->created_by		= $this->kauth->getInstance()->getIdentity()->created_by;
		$this->created_date		= $this->kauth->getInstance()->getIdentity()->created_date;
		$this->update_by	= $this->kauth->getInstance()->getIdentity()->update_by;
		$this->update_date		= $this->kauth->getInstance()->getIdentity()->update_date;

	}

	function json()	{
		$this->load->model("Pengurus");
		$pengurus = new Pengurus();
		// echo $reqKategori;exit;

		$aColumns		= array("PENGURUS_ID", "URUT", "NIP", "NAMA", "JABATAN", "JABATAN_PENGURUS", "TANGGAL_MULAI","TANGGAL_AKHIR");
		$aColumnsAlias	= array("PENGURUS_ID", "URUT", "NIP", "NAMA", "JABATAN", "JABATAN_PENGURUS", "TANGGAL_MULAI","TANGGAL_AKHIR");

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
			if ( trim($sOrder) == "ORDER BY A.pengurus_id asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.pengurus_id asc";

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

		 $statement= " AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $pengurus->getCountByParams(array(), $statement_privacy.$statement);
		// echo $allRecord;exit;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $pengurus->getCountByParams(array(), $statement_privacy.$statement);

		 $pengurus->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy.$statement, $sOrder);

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

		while($pengurus->nextRow())
		{
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($pengurus->getField($aColumns[$i]), 10)."...";
				elseif($aColumns[$i] == "LINK_FILE")
					$row[] = "<img src='uploads/".$pengurus->getField($aColumns[$i])."' height='50px'>";
				elseif($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI")
				{
					if($pengurus->getField($aColumns[$i]) == "Y")
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					else
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
				}
				else
					$row[] = $pengurus->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );
	}
	

	function add()
	{
		$this->load->model("Pengurus");
		$pengurus = new Pengurus();

		$reqMode 						= $this->input->post("reqMode");
		$reqId 							= $this->input->post("reqId");
		$reqNip 						= $this->input->post("reqNip")
		$reqNama 						= $this->input->post("reqNama")
		$reqJabatan 				= $this->input->post("reqJabatan")
		$reqJabatan_Pengurus= $this->input->post("reqJabatan_Pengurus")



		$pengurus->setField("PENGURUS_ID", $reqId);
		$pengurus->setField("NIP", $reqNip);
		$pengurus->setField("NAMA", $reqNama);
		$pengurus->setField("JABATAN", $reqJabatan);
		$pengurus->setField("JABATAN_PENGURUS", $reqJabatan_Pengurus);



		if($reqMode == "insert")
		{
			$pengurus->setField("CREATED_BY", $this->USERNAME);
			$pengurus->insert();
		}
		else
		{
			$pengurus->setField("UPDATED_BY", $this->USERNAME);
			$pengurus->update();
		}

		echo "Data berhasil disimpan.";

	}


	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Pengurus");
		$pengurus = new Pengurus();


		$pengurus->setField("PENGURUS_ID", $reqId);
		if($pengurus->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}


	function combo()
	{
		$this->load->model("Pengurus");
		$pengurus = new Pengurus();

		$pengurus->selectByParams(array());
		$i = 0;
		while($pengurus->nextRow())
		{
			$arr_json[$i]['id']		= $pengurus->getField("PENGURUS_ID");
			$arr_json[$i]['text']	= $pengurus->getField("NAMA");
			$i++;
		}

		echo json_encode($arr_json);
	}

}
