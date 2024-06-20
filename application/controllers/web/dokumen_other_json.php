<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");


// include_once("lib/excel/excel_reader2.php");


class dokumen_other_json extends CI_Controller
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
        $this->load->model("DokumenOther");
        $dokumen_other = new DokumenOther();

        $reqId = $this->input->get("reqId");
        $reqMode = $this->input->get("reqMode");

        $reqCariCompanyName = $this->input->get("reqCariCompanyName");
        $reqCariContactPerson = $this->input->get("reqCariContactPerson");
        $reqCariVasselName = $this->input->get("reqCariVasselName");
        $reqCariEmailPerson = $this->input->get("reqCariEmailPerson");
        $reqCheck = $this->input->get("reqCheck");

        // echo $reqKategori;exit;

        $aColumns = array(
            "DOCUMENT_ID", "CATEGORY_ID", "NAME", "DESCRIPTION", "PATH", "LAST_REVISI", "NAMA_CATEGORI"
        );
        $aColumnsAlias = array(
            "DOCUMENT_ID", "CATEGORY_ID", "NAME", "DESCRIPTION", "PATH", "LAST_REVISI", "NAMA_CATEGORI"
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


        $reqCariCompanyName = $this->input->get('reqCariCompanyName');
        $reqCariDescription = $this->input->get('reqCariDescription');
        $reqCariCategori = $this->input->get('reqCariCategori');

        $_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariDescription"] = $reqCariDescription;
        $_SESSION[$this->input->get("pg")."reqCariCategori"] = $reqCariCategori;

        if (!empty($reqCariCompanyName)) {
            $statement_privacy = " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%'";
        }
        if (!empty($reqCariDescription)) {
            $statement_privacy .= " AND UPPER(A.DESCRIPTION) LIKE '%" . strtoupper($reqCariCompanyName) . "%'";
        }
        if (!empty($reqCariCategori)) {
            $statement_privacy .= " AND A.CATEGORY_ID = '" . $reqCariCategori . "'";
        }


        // $statement = "AND (UPPER(NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $dokumen_other->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $dokumen_other->getCountByParams(array(), $statement_privacy . $statement);

        $dokumen_other->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
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

        while ($dokumen_other->nextRow()) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NAME")
                    $row[] = $dokumen_other->getField($aColumns[$i]);
                elseif ($aColumns[$i] == "LINK_FILE")
                    $row[] = "<img src='uploads/" . $dokumen_other->getField($aColumns[$i]) . "' height='50px'>";
                   elseif ($aColumns[$i] == "DESCRIPTION")
                    $row[] = truncate_singkat($dokumen_other->getField($aColumns[$i]),100);
                elseif ($aColumns[$i] == "CHECK") {
                    if ($reqCheck != 'tidak') {
                        $row[] = '<input type="checkbox" value="' . $dokumen_other->getField('COMPANY_ID') . '" name="reqIds[]"/>';
                    }
                } elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
                    if ($dokumen_other->getField($aColumns[$i]) == "Y")
                        $row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
                    else
                        $row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
                } else
                    $row[] = $dokumen_other->getField($aColumns[$i]);
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }


    function add()
    {
        
     
        $this->load->library("FileHandler");
        $file = new FileHandler();
        $filesData = $_FILES["document"];
        $reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
        $file->cekSize($filesData,$reqLinkFileTemp);


        $reqId                           = $this->input->post("reqId");
        $reqCategoryId                   = $this->input->post("reqCategoryId");
        $reqName                         = $this->input->post("reqName");
        $reqDescription                  = $this->input->post("reqDescription");
        $reqPath                         = $this->input->post("reqPath");

        $this->load->model('DokumenOther');
         // echo  $reqDescription    ;exit;
         $document = new DokumenOther();
        $document->setField("DOCUMENT_ID", $reqId);
        $document->setField("CATEGORY_ID", $reqCategoryId);
        $document->setField("NAME", $reqName);
        $document->setField("DESCRIPTION", $reqDescription);
        $document->setField("PATH", $reqPath);


        // print_r($document->currentRow);exit;

        if (empty($reqId)) {
            $document->insert();
            $reqId = $document->id;
        } else {
            $document->update();
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

        $document = new DokumenOther();
        $document->setField("DOCUMENT_ID", $reqId);
        $document->setField("PATH", ($str_name_path));
        $document->update_path();



        echo $reqId . '- Data berhasil di simpan';
    }

    function delete()
    {
        $this->load->model("DokumenOther");
        $dokumen_other = new DokumenOther();

        $reqId = $this->input->get("reqId");
        $dokumen_other->setField("DOCUMENT_ID", $reqId);
        $dokumen_other->delete();

        echo 'Data berhasil dihapus';
    }
}
