<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class report_department_json  extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->kauth->getInstance()->hasIdentity()) {
            redirect('login');
        }

        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");
        $this->USERID = $this->kauth->getInstance()->getIdentity()->USERID;
        $this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
        $this->FULLNAME = $this->kauth->getInstance()->getIdentity()->FULLNAME;
        $this->USERPASS = $this->kauth->getInstance()->getIdentity()->USERPASS;
        $this->LEVEL = $this->kauth->getInstance()->getIdentity()->LEVEL;
        $this->MENUMARKETING = $this->kauth->getInstance()->getIdentity()->MENUMARKETING;
        $this->MENUFINANCE = $this->kauth->getInstance()->getIdentity()->MENUFINANCE;
        $this->MENUPRODUCTION = $this->kauth->getInstance()->getIdentity()->MENUPRODUCTION;
        $this->MENUDOCUMENT = $this->kauth->getInstance()->getIdentity()->MENUDOCUMENT;
        $this->MENUSEARCH = $this->kauth->getInstance()->getIdentity()->MENUSEARCH;
        $this->MENUOTHERS = $this->kauth->getInstance()->getIdentity()->MENUOTHERS;
    }

    function json()
    {
        $this->load->model("ReportDepartment");
        $report_department = new ReportDepartment();

        $aColumns = array(
            "REPORT_DEPARTMENT_ID", "NO", "DEPARTMENT", "PROJECT", "CLIENT", "SEND_DATE", "RECEIVE_DATE", "DESCRIPTION", "FILE"
        );
        $aColumnsAlias = array(
            "REPORT_DEPARTMENT_ID", "NO", "DEPARTMENT", "PROJECT", "CLIENT", "SEND_DATE", "RECEIVE_DATE", "DESCRIPTION", "FILE"
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
            if (trim($sOrder) == "ORDER BY REPORT_DEPARTMENT_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY REPORT_DEPARTMENT_ID desc";
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
        $reqCariDepartment          = $this->input->get('reqCariDepartment');
        $reqCariSendDateFrom        = $this->input->get('reqCariSendDateFrom');
        $reqCariSendDateTo          = $this->input->get('reqCariSendDateTo');
        $reqCariReceivedDateFrom    = $this->input->get('reqCariReceivedDateFrom');
        $reqCariReceivedDateTo      = $this->input->get('reqCariReceivedDateTo');

        $_SESSION[$this->input->get("pg")."reqCariDepartment"] = $reqCariDepartment;
        $_SESSION[$this->input->get("pg")."reqCariSendDateFrom"] = $reqCariSendDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariSendDateTo"] = $reqCariSendDateTo;
        $_SESSION[$this->input->get("pg")."reqCariReceivedDateFrom"] = $reqCariReceivedDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariReceivedDateTo"] = $reqCariReceivedDateTo;


        if (!empty($reqCariDepartment)) {
            $statement_privacy .= " AND UPPER(A.DEPARTMENT) LIKE '%" . strtoupper($reqCariNameofCertificate) . "%'";
        }
        if (!empty($reqCariSendDateFrom) && !empty($reqCariSendDateTo)) {
            $statement_privacy .= " AND A.SEND_DATE BETWEEN TO_DATE('" . $reqCariSendDateFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariSendDateTo . "','dd-mm-yyyy')";
        }
        if (!empty($reqCariReceivedDateFrom) && !empty($reqCariReceivedDateTo)) {
            $statement_privacy .= " AND A.RECEIVE_DATE BETWEEN TO_DATE('" . $reqCariReceivedDateFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariReceivedDateTo . "','dd-mm-yyyy')";
        }

        $statement = " AND (
                        UPPER(A.PROJECT) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
                        UPPER(A.CLIENT) LIKE '%" . strtoupper($_GET['sSearch']) . "%'
                    )";
        $allRecord = $report_department->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $report_department->getCountByParams(array(), $statement_privacy . $statement);

        $report_department->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
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
        $no = 1;
         $nomer=0;
        while ($report_department->nextRow()) {
            $row = array();
            $total_pagination  = ($dsplyStart)+$nomer;
            $penomoran = $allRecordFilter-($total_pagination);
            for ($i = 0; $i < count($aColumns); $i++) {
                if($aColumns[$i] == "NO"){
                    $row[] =  $penomoran;
                } else if($aColumns[$i] == "FILE"){
                    $reqId   = $report_department->getField("REPORT_DEPARTMENT_ID");
                    $reqPath = $report_department->getField("PATH");
                    $reqPath = explode(';',  $reqPath);
                    if($reqPath[0] == "")
                        $row[] = "-";
                    else
                        $row[] =  "<a href='uploads/report_department/".$reqId."/".$reqPath[0]."'>Download</a>";
                } else {
                    $row[] =  $report_department->getField($aColumns[$i]);    
                }
            }
            $no++;
            $output['aaData'][] = $row;
             $nomer++;
        }
        echo json_encode($output);
    }

    function add()
    {
        $this->load->model("ReportDepartment");
        $report_department = new ReportDepartment();
        $this->load->library("FileHandler");
        $file = new FileHandler();
        $filesData = $_FILES["document"];
        $reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
        $file->cekSize($filesData,$reqLinkFileTemp);


        $reqId              = $this->input->post("reqId");
        $reqDepartment      = $this->input->post("reqDepartment");
        $reqProject         = $this->input->post("reqProject");
        $reqClient          = $this->input->post("reqClient");
        $reqSendDate        = $this->input->post("reqSendDate");
        $reqReceivedDate    = $this->input->post("reqReceivedDate");
        $reqDescription     = $this->input->post("reqDescription");

        $report_department->setField("REPORT_DEPARTMENT_ID", $reqId);
        $report_department->setField("DEPARTMENT", $reqDepartment);
        $report_department->setField("PROJECT", $reqProject);
        $report_department->setField("CLIENT", $reqClient);
        $report_department->setField("SEND_DATE", dateToDBCheck($reqSendDate));
        $report_department->setField("RECEIVE_DATE", dateToDBCheck($reqReceivedDate));
        $report_department->setField("DESCRIPTION", $reqDescription);

        if (empty($reqId)) {
            $report_department->insert();
            $reqId = $report_department->id;
        } else {
            $report_department->update();
        }

        $FILE_DIR = "uploads/report_department/" . $reqId . "/";
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

        $report_department = new ReportDepartment();
        $report_department->setField("REPORT_DEPARTMENT_ID", $reqId);
        $report_department->setField("PATH", ($str_name_path));
        $report_department->update_path();

        echo $reqId . '- Data berhasil di simpan';
    }


    function delete()
    {
        $reqId = $this->input->get("reqId");
        $this->load->model("ReportDepartment");
        $report_department = new ReportDepartment();
        $report_department->setField('REPORT_DEPARTMENT_ID', $reqId);
        $report_department->delete();
        echo 'Data berhasil di hapus';
    }
}
