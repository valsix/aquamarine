<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class dokumen_certificate_json  extends CI_Controller
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
        $this->load->model("DokumenCertificate");
        $dokumen_certificate = new DokumenCertificate();

        $aColumns = array(
            "DOCUMENT_ID", "NO","NAME", "ISSUED_DATE", "EXPIRED_DATE", "SURVEYOR","STATUS","CERTIFICATE"
        );
        $aColumnsAlias = array(
            "DOCUMENT_ID","NO", "NAME", "ISSUED_DATE", "EXPIRED_DATE", "SURVEYOR","STATUS","CERTIFICATE"
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
            if (trim($sOrder) == "ORDER BY DOCUMENT_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY DOCUMENT_ID desc";
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
        $reqCariNameofCertificate = $this->input->get('reqCariNameofCertificate');
        $reqCariTypeofCertificate = $this->input->get('reqCariTypeofCertificate');
        $reqCariIssueDateFrom     = $this->input->get('reqCariIssueDateFrom');
        $reqCariIssueDateTo       = $this->input->get('reqCariIssueDateTo');
        $reqCariExpiredDateFrom   = $this->input->get('reqCariExpiredDateFrom');
        $reqCariExpiredDateTo     = $this->input->get('reqCariExpiredDateTo');
        $reqCariGlobalSearch      = $this->input->get('reqCariGlobalSearch');

        $_SESSION[$this->input->get("pg")."reqCariNameofCertificate"] = $reqCariNameofCertificate;
        $_SESSION[$this->input->get("pg")."reqCariTypeofCertificate"] = $reqCariTypeofCertificate;
        $_SESSION[$this->input->get("pg")."reqCariIssueDateFrom"] = $reqCariIssueDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariIssueDateTo"] = $reqCariIssueDateTo;
        $_SESSION[$this->input->get("pg")."reqCariExpiredDateFrom"] = $reqCariExpiredDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariExpiredDateTo"] = $reqCariExpiredDateTo;
        $_SESSION[$this->input->get("pg")."reqCariGlobalSearch"] = $reqCariGlobalSearch;


        if (!empty($reqCariNameofCertificate)) {
            $statement_privacy .= " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqCariNameofCertificate) . "%'";
        }
        if (!empty($reqCariTypeofCertificate)) {
            $statement_privacy .= " AND UPPER(A.CERTIFICATE_ID::varchar) LIKE '%" . strtoupper($reqCariTypeofCertificate) . "%'";
        }

        if (!empty($reqCariIssueDateFrom) && !empty($reqCariIssueDateTo)) {
            $statement_privacy .= " AND A.ISSUED_DATE BETWEEN TO_DATE('" . $reqCariIssueDateFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariIssueDateTo . "','dd-mm-yyyy')";
        }
        if (!empty($reqCariExpiredDateFrom) && !empty($reqCariExpiredDateTo)) {
            $statement_privacy .= " AND A.EXPIRED_DATE BETWEEN TO_DATE('" . $reqCariExpiredDateFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariExpiredDateTo . "','dd-mm-yyyy')";
        }
        if (!empty($reqCariGlobalSearch)) {
            $statement_privacy .= " AND UPPER(A.SURVEYOR) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%'";
        }


        $statement .= " AND ( (UPPER(CERTIFICATE) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
         $statement .= " OR (UPPER(A.NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%') )";
        $_SESSION[$this->input->get("pg")."reqCariSession"] = $statement_privacy . $statement;
        $allRecord = $dokumen_certificate->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $dokumen_certificate->getCountByParams(array(), $statement_privacy . $statement);

        $dokumen_certificate->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $dokumen_certificate->query;
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

         $nomer=0;

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
            $total_pagination  = ($dsplyStart)+$nomer;
            $penomoran = $allRecordFilter-($total_pagination);
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "DOCUMENT_ID") {
                    $row[] = $dokumen_certificate->getField($aColumns[$i]);
                } else if ($aColumns[$i] == "NO") {
                    $row[] = $penomoran;
                }else if ($aColumns[$i] == "STATUS") {
                    $row[] = $color;
                } else {
                    $row[] =  $dokumen_certificate->getField($aColumns[$i]) ;
                }
            }
            $output['aaData'][] = $row;
             $nomer++;
        }
        echo json_encode($output);
         $nomer++;
    }

    function add()
    {
        $this->load->model("DokumenCertificate");
        $dokumen_certificate = new DokumenCertificate();
          $this->load->library("FileHandler");
        $file = new FileHandler();
        $filesData = $_FILES["document"];
        $reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
        $file->cekSize($filesData,$reqLinkFileTemp);


        $reqId              = $this->input->post("reqId");
        $reqCertificateId   = $this->input->post("reqCertificateId");
        $reqName            = $this->input->post("reqName");
        $reqDescription     = $this->input->post("reqDescription");
        $reqPath            = $this->input->post("reqPath");
        $reqIssuedDate      = $this->input->post("reqIssuedDate");
        $reqExpiredDate     = $this->input->post("reqExpiredDate");
        $reqLastRevisi      = $this->input->post("reqLastRevisi");
        $reqSurveyor        = $this->input->post("reqSurveyor");

        $dokumen_certificate->setField("DOCUMENT_ID", $reqId);
        $dokumen_certificate->setField("CERTIFICATE_ID", $reqCertificateId);
        $dokumen_certificate->setField("NAME", $reqName);
        $dokumen_certificate->setField("DESCRIPTION", $reqDescription);
        $dokumen_certificate->setField("PATH", $reqPath);
        $dokumen_certificate->setField("ISSUED_DATE", dateToDBCheck($reqIssuedDate));
        $dokumen_certificate->setField("EXPIRED_DATE", dateToDBCheck($reqExpiredDate));
        $dokumen_certificate->setField("LAST_REVISI", $reqLastRevisi);
        $dokumen_certificate->setField("SURVEYOR", $reqSurveyor);

        if (empty($reqId)) {
            $dokumen_certificate->insert();
            $reqId = $dokumen_certificate->id;
        } else {
            $dokumen_certificate->update();
        }


        $reqTipe            = $this->input->post('reqTipe');

        $name_folder = strtolower(str_replace(' ', '_', $reqTipe));
       

     
        $FILE_DIR = "uploads/" . $name_folder . "/" . $reqId . "/";
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

        $dokumen_certificate = new DokumenCertificate();
        $dokumen_certificate->setField("DOCUMENT_ID", $reqId);
        $dokumen_certificate->setField("PATH", ($str_name_path));
        $dokumen_certificate->update_path();

        echo $reqId . '- Data berhasil di simpan';
    }


    function delete()
    {
        $reqId = $this->input->get("reqId");
        $this->load->model("DokumenCertificate");
        $dokumen_certificate = new DokumenCertificate();
        $dokumen_certificate->setField('DOCUMENT_ID', $reqId);
        $dokumen_certificate->delete();
        echo 'Data berhasil di hapus';
    }
}
