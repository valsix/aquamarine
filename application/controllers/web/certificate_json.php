<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class certificate_json extends CI_Controller
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
        $this->load->model("Certificate");
        $certificate = new Certificate();

        $aColumns = array(
            "CERTIFICATE_ID", "CERTIFICATE", "DESCRIPTION"
        );
        $aColumnsAlias = array(
            "CERTIFICATE_ID", "CERTIFICATE", "DESCRIPTION"
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
            if (trim($sOrder) == "ORDER BY CERTIFICATE_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY CERTIFICATE_ID desc";
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

        $statement = " AND (UPPER(CERTIFICATE) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $certificate->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $certificate->getCountByParams(array(), $statement_privacy . $statement);

        $certificate->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $certificate->query;exit;
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

        while ($certificate->nextRow()) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "CERTIFICATE")
                    $row[] = truncate($certificate->getField($aColumns[$i]), 2);
                else
                    $row[] = $certificate->getField($aColumns[$i]);
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }


    function add()
    {
        $this->load->model("Certificate");
        $certificate = new Certificate();

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqCertificate = $this->input->post("reqCertificate");
        $reqDescription = $this->input->post("reqDescription");

        $certificate->setField("CERTIFICATE_ID", $reqId);
        $certificate->setField("CERTIFICATE", $reqCertificate);
        $certificate->setField("DESCRIPTION", $reqDescription);

        if (empty($reqId)) {
            $certificate->insert();
        } else {
            $certificate->update();
        }

        echo "Data berhasil disimpan.";
    }

    function delete()
    {
        $this->load->model("Certificate");
        $certificate = new Certificate();

        $reqId = $this->input->get('reqId');

        $certificate->setField("CERTIFICATE_ID", $reqId);
        if ($certificate->delete())
            $arrJson["PESAN"] = "Data berhasil dihapus.";
        else
            $arrJson["PESAN"] = "Data gagal dihapus.";

        echo json_encode($arrJson);
    }

   function combo() 
    {
        
        $this->load->model("DokumenCertificate");
// $dokumen_certificate = new DokumenCertificate();
        $dokumen_certificate = new DokumenCertificate();
        
        $reqPencarian = $this->input->get("reqPencarian");
        
        if($reqPencarian == "")
        {}
        else
            $statement = " AND (UPPER(A.NAME) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.NAME) LIKE '%".strtoupper($reqPencarian)."%') ";
        
        $dokumen_certificate->selectByParamsMonitoring(array(), -1, -1, $statement);
        $i = 0;
        while($dokumen_certificate->nextRow())
        {
          $arr_json[$i]['id']               = $dokumen_certificate->getField("DOCUMENT_ID");
          $arr_json[$i]['CERTIFICATE_ID']   = $dokumen_certificate->getField("CERTIFICATE_ID");
          $arr_json[$i]['NAME']             = $dokumen_certificate->getField("NAME");
          $arr_json[$i]['DESCRIPTION']      = $dokumen_certificate->getField("DESCRIPTION");
          $arr_json[$i]['PATH']             = $dokumen_certificate->getField("PATH");
          $arr_json[$i]['ISSUED_DATE']      = $dokumen_certificate->getField("ISSUED_DATE");
          $arr_json[$i]['EXPIRED_DATE']     = $dokumen_certificate->getField("EXPIRED_DATE");
          $arr_json[$i]['LAST_REVISI']      = $dokumen_certificate->getField("LAST_REVISI");
          $arr_json[$i]['SURVEYOR']         = $dokumen_certificate->getField("SURVEYOR");
           
          
            $i++;
        }
        
        echo json_encode($arr_json);
    }
}
