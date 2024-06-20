<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class offer_project_json extends CI_Controller
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
        $this->load->model("OfferProject");
        $offer_project = new OfferProject();

        $reqOfferId = $this->input->get('reqOfferId');
        $reqResume = $this->input->get('reqResume');

        $aColumns = array("OFFER_ID", "AKSI", "CATEGORY", "DESCRIPTION", "QUANTITY", "DURATION", "UOM", "PRICE", "TOTAL");

        $aColumnsAlias = array("OFFER_ID", "AKSI", "CATEGORY", "DESCRIPTION", "QUANTITY", "DURATION", "UOM", "PRICE", "TOTAL");

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
            if (trim($sOrder) == "ORDER BY OFFER_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY OFFER_ID desc";
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

        $statement_privacy = " ";
        
        $statement .= " AND (UPPER(DESCRIPTION) LIKE '%" . strtoupper($_GET['sSearch']) . "%') ";

        if (!empty($reqOfferId)) {
            $statement_privacy .= " AND A.OFFER_ID='" . $reqOfferId . "' ";
        } else {
        	$statement_privacy .= " AND A.OFFER_ID IS NULL ";
        }


        $allRecord = $offer_project->getCountByParams(array(), $statement_privacy);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $offer_project->getCountByParams(array(), $statement_privacy . $statement);

        $offer_project->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $offer_project->query;exit;
        // exit;

        /*
			 * Output
			 */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $allRecord,
            "iTotalDisplayRecords" => $allRecordFilter,
            "aaData" => array()
        );

        while ($offer_project->nextRow()) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
            	if($aColumns[$i] == "PRICE" || $aColumns[$i] == "TOTAL") {
            		$row[] = currencyToPage($offer_project->getField($aColumns[$i]));
            	} else if($aColumns[$i] == "AKSI"){
            		$id = $offer_project->getField("OFFER_PROJECT_ID");
                 	$btn_edit = '<button type="button"  class="btn btn-info " onclick=editing('.$id.')  ><i class="fa fa-pencil-square-o fa-lg"> </i> </button>';
                 	$btn_delete = '<button type="button"  class="btn btn-danger hapusi"  onclick="deleting('.$id.')"><i class="fa fa-trash-o fa-lg"> </i> </button>';

                 	$row[] =$btn_edit.$btn_delete;
                } else {
                	$row[] = $offer_project->getField($aColumns[$i]);
                }
                
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }

	function add()
	{

		$this->load->model("OfferProject");
		$reqId =	$this->input->post("reqId");

		$offer_project = new OfferProject();

		$reqOfferId 		= $this->input->post("reqOfferId");
		$reqCategory 		= $this->input->post("reqCategory");
		$reqDescription 	= $this->input->post("reqDescription");
		$reqQuantity 		= $this->input->post("reqQuantity");
		$reqDuration 		= $this->input->post("reqDuration");
		$reqUom 			= $this->input->post("reqUom");
		$reqPrice 			= $this->input->post("reqPrice");
		$reqTotal 			= $this->input->post("reqTotal");

		$offer_project->setField("OFFER_PROJECT_ID", $reqId);
		$offer_project->setField("OFFER_ID", $reqOfferId);
		$offer_project->setField("CATEGORY", $reqCategory);
		$offer_project->setField("DESCRIPTION", $reqDescription);
		$offer_project->setField("QUANTITY", dotToNo($reqQuantity));
		$offer_project->setField("DURATION", dotToNo($reqDuration));
		$offer_project->setField("UOM", $reqUom);
		$offer_project->setField("PRICE", dotToNo($reqPrice));
		$offer_project->setField("TOTAL", dotToNo($reqTotal));
		$offer_project->setField("CREATED_BY", $this->ID);
		$offer_project->setField("UPDATED_BY", $this->ID);

		if (empty($reqId)) {
			$offer_project->insert();
		} else {
			$offer_project->update();
		}

		echo 'Data Berhasil di simpan';
	}

	function delete(){
		$reqId =	$this->input->get("reqId");
		$this->load->model("OfferProject");
		$offer_project = new OfferProject();
		$offer_project->setField("OFFER_PROJECT_ID", $reqId);
		$offer_project->delete();


	}
}
