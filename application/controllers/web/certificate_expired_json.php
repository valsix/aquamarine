<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class certificate_expired_json extends CI_Controller
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
        $this->load->model("DokumenCertificate");
        $dokumen_certificate = new DokumenCertificate();

        $aColumns = array(
            "DOCUMENT_ID", "NAME", "ISSUED_DATE", "EXPIRED_DATE", "SURVEYOR"
        );
        $aColumnsAlias = array(
            "DOCUMENT_ID", "NAME", "ISSUED_DATE", "EXPIRED_DATE", "SURVEYOR"
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
            if (trim($sOrder) == "ORDER BY A.DOCUMENT_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY A.DOCUMENT_ID asc";
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

        $statement_privacy = " AND EXPIRED_DATE < CURRENT_DATE + INTERVAL '2 MONTH' ";
        $reqCariNameofCertificate = $this->input->get('reqCariNameofCertificate');
        $reqCariTypeofCertificate = $this->input->get('reqCariTypeofCertificate');
        $reqCariIssueDateFrom     = $this->input->get('reqCariIssueDateFrom');
        $reqCariIssueDateTo       = $this->input->get('reqCariIssueDateTo');
        $reqCariExpiredDateFrom   = $this->input->get('reqCariExpiredDateFrom');
        $reqCariExpiredDateTo     = $this->input->get('reqCariExpiredDateTo');
        $reqCariGlobalSearch      = $this->input->get('reqCariGlobalSearch');


        if (!empty($reqCariNameofCertificate)) {
            $statement_privacy .= " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqCariNameofCertificate) . "%'";
        }
        if (!empty($reqCariTypeofCertificate)) {
            $statement_privacy .= " AND UPPER(A.CERTIFICATE_ID) LIKE '%" . strtoupper($reqCariTypeofCertificate) . "%'";
        }

        if (!empty($reqCariIssueDateFrom) && !empty($reqCariIssueDateTo)) {
            $statement_privacy .= " AND A.ISSUED_DATE BETWEEN TO_DATE('" . $reqCariIssueDateFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariIssueDateTo . "','dd-mm-yyyy')";
        }
        if (!empty($reqCariExpiredDateFrom) && !empty($reqCariExpiredDateTo)) {
            $statement_privacy .= " AND A.EXPIRED_DATE BETWEEN TO_DATE('" . $reqCariIssueDateFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariIssueDateTo . "','dd-mm-yyyy')";
        }
        if (!empty($reqCariGlobalSearch)) {
            $statement_privacy .= " AND UPPER(A.SURVEYOR) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%'";
        }


        $statement = " AND (UPPER(CERTIFICATE) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $dokumen_certificate->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $dokumen_certificate->getCountByParams(array(), $statement_privacy . $statement);

        $dokumen_certificate->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $certificate->query;
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

        while ($dokumen_certificate->nextRow()) {
            $tgl_skrng = Date('Y-m-d');
            $exp_date = $dokumen_certificate->getField("DATES");
            $datetime1 = date_create($tgl_skrng);
            $datetime2 = date_create($exp_date);
            $interval = date_diff($datetime1, $datetime2);
            $interval = $interval->format("%R%a");
            $point = substr($interval, 0,1);

            $color = '';
            
                if ($point == '-') {
                    $color = 'red';
                } 

            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "DOCUMENT_ID") {
                    $row[] = $dokumen_certificate->getField($aColumns[$i]);
                }else if ($aColumns[$i] == "STATUS") {
                    $row[] = $color;
                } else {
                    $row[] =  $dokumen_certificate->getField($aColumns[$i]) ;
                }
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }


}
