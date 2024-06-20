<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class hpp_master_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->aduanId				  = $this->kauth->getInstance()->getIdentity()->Dokumen_id;
		$this->Nip				  = $this->kauth->getInstance()->getIdentity()->Nip;
		$this->nama							= $this->kauth->getInstance()->getIdentity()->nama;
		$this->Aduan				= $this->kauth->getInstance()->getIdentity()->Aduan;
		$this->linkFile				= $this->kauth->getInstance()->getIdentity()->link_file;
		$this->createdBy				= $this->kauth->getInstance()->getIdentity()->created_by;
		$this->createdDate			= $this->kauth->getInstance()->getIdentity()->created_date;
		$this->updateBy				= $this->kauth->getInstance()->getIdentity()->update_by;
		$this->updateDate			= $this->kauth->getInstance()->getIdentity()->update_date;
	}

	function json()
	{
		// echo "tes";
		$this->load->model("HppMaster");
		$dokumen = new HppMaster();
		// var_dump($dokumen);
		// echo $reqKategori;exit;

		$aColumns		= array("HPP_MASTER_ID", "CODE", "KETERANGAN","AKSI");
		$aColumnsAlias	= array("HPP_MASTER_ID", "CODE", "KETERANGAN","AKSI");

		// var_dump($aColumns);
		$reqModes = $this->input->get('reqModes');

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
			if (trim($sOrder) == "ORDER BY A.HPP_MASTER_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.HPP_MASTER_ID desc";
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

		$statement = " AND (UPPER(A.CODE) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$allRecord = $dokumen->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $dokumen->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

		$dokumen->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

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
		 $nom=0;
		while ($dokumen->nextRow()) {
			$row = array();
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN")
					$row[] = $dokumen->getField($aColumns[$i]);
				elseif ($aColumns[$i] == "LINK_FILE")
					$row[] = "<a href='uploads/" . $dokumen->getField($aColumns[$i]) . "' height='50px' target='_blank'>Unduh</a>";
				elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
					if ($dokumen->getField($aColumns[$i]) == "Y")
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					else
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
				}else if($aColumns[$i] == "AKSI"){
                 $btn_edit = '<button type="button"  class="btn btn-success " onclick=editing('.$nom.')  ><i class="fa fa-pencil-square-o fa-lg"> </i> </button>';
                  $btn_choice = '<button type="button"  class="btn btn-info " onclick=selecteds('.$nom.')  ><i class="fa fa-hand-pointer-o fa-lg"> </i> </button>';
                 $btn_delete = '<button type="button"  class="btn btn-danger hapusi"  onclick="deleting('.$nom.')"><i class="fa fa-trash-o fa-lg"> </i> </button>';
                 if($reqModes=='select'){
                   $row[] =$btn_choice.'-'.$btn_edit.'-'.$btn_delete;
                  }else{
              	   $row[] =$btn_edit.'-'.$btn_delete;
             	  }
                } else
					$row[] = $dokumen->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
			   $nom++;
		}
		echo json_encode($output);
	}

	function delete(){
		$this->load->model("HppMaster");
		$reqId =	$this->input->get("reqId");
		if($reqId=='28'){
				echo ' Data tidak barhasil di delete';exit;
		}
		$bank = new HppMaster();
		$bank->setField('HPP_MASTER_ID',$reqId);
		$bank->delete();
		echo 'Data barhasil di delete';
	}

	function update_project_hpp_detail($reqId=''){
		$reqJenis 			= $this->input->post("reqCode");
		$reqDescription 	= $this->input->post("reqKeterangan");

		$this->load->model('ProjectHppDetail');
		$this->load->model('CostProjectDetil');
		$project_hpp_detail = new ProjectHppDetail();
		$project_hpp_detail->setField("CODE",$reqJenis);
		$project_hpp_detail->setField("DESCRIPTION",$reqDescription);
		$project_hpp_detail->setField("HPP_MASTER_ID",$reqId);
		$project_hpp_detail->update_detail();


		$project_hpp_detail = new ProjectHppDetail();
		$project_hpp_detail->selectByParamsMonitoring(array("CAST(A.HPP_MASTER_ID AS VARCHAR)"=>$reqId));
		$arrDataId = array();
		while ($project_hpp_detail->nextRow()) {
		  	$costprojectdetil =  new CostProjectDetil();
		  	$costprojectdetil->setField("CODE",$reqJenis);
			$costprojectdetil->setField("DESCRIPTION",$reqDescription);
			$costprojectdetil->setField("PROJECT_HPP_DETAIL_ID",$project_hpp_detail->getField("PROJECT_HPP_DETAIL_ID"));
			$costprojectdetil->update_detail();

		}


	}


	function add()
	{
		$this->load->model("HppMaster");
		$reqId =	$this->input->post("reqId");

		$bank = new HppMaster();

	
		$reqJenis 			= $this->input->post("reqCode");
		$reqDescription 	= $this->input->post("reqKeterangan");

		$bank->setField("HPP_MASTER_ID", $reqId);
		$bank->setField("CODE", $reqJenis);
		$bank->setField("KETERANGAN", $reqDescription);

		if (empty($reqId)) {
			$bank->insert();
			$reqId = $bank->id;
		} else {
			$bank->update();
		}

		$this->update_project_hpp_detail($reqId);
		echo 'Data Berhasil di simpan';
	}
}
