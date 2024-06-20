<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class template_email_json extends CI_Controller
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
        $this->load->model("Email");
        $customer = new Email();

        $reqId                  = $this->input->get("reqId");
        $reqMode                = $this->input->get("reqMode");

      
        // echo $reqKategori;exit;

        $aColumns = array(
            "EMAIL_ID","SUBJECT"
        );
        $aColumnsAlias = array(
            "EMAIL_ID","SUBJECT"
        );

        /*
		 * Ordering
		 */
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = " ORDER BY  ";

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
            if (trim($sOrder) == "ORDER BY A.EMAIL_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY A.EMAIL_ID asc";
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
            $sWhere = "AND 1=1";
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

      
            
       

       


        $statement = "AND (UPPER(A.SUBJECT) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $customer->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $customer->getCountByParams(array(), $statement_privacy . $statement);

        $customer->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $customer->query;
        // exit;
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

        while ($customer->nextRow()) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                
                    $row[] = $customer->getField($aColumns[$i]);
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }


	function add(){

		$reqId = $this->input->post("reqId");
		$reqSubject = $this->input->post("reqSubject");
		$reqBody  	= $_POST["reqDescription"];

		$this->load->model("Email");
		$email = new Email();
		$email->setField("EMAIL_ID",$reqId);
		$email->setField("SUBJECT",$reqSubject);
		$email->setField("BODY",$reqBody);
		if(empty($reqId)){
			$email->insert();
			$reqId = $email->id;
		}else{
			$email->update();
		}


		echo $reqId.'-Data Berhasil di simpan';
	}

	function delete_email(){
		
        $this->load->model("Email");
		$email = new Email();
        $reqId = $this->input->get('reqId');
        $email->setField("EMAIL_ID", $reqId);
        $pesan ='Data gagal dihapus.';
        if ($email->delete())
      	 $pesan = "Data berhasil dihapus.";
        
        echo $pesan;
    
	}

	function load_template_body(){
		  $reqId = $this->input->get("reqId");
		  $this->load->model("Email");
		  $email = new Email();
		  $email->selectByParams(array("A.EMAIL_ID"=> $reqId));
		  $email->firstRow();
		  $reqBody =  $email->getField("BODY");
		  echo  $reqBody;


	}


	}
