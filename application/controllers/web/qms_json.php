<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class qms_json extends CI_Controller
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
        $this->load->model("DokumenQm");
        $dokumen_qm = new DokumenQm();

        $reqKategori = $this->input->get('reqKategori');

        $aColumns = array(
            "DOCUMENT_ID","NO", "TYPE", "FORMAT", "NAME", "DESCRIPTION", "PATH"
        );

        $aColumnsAlias = array(
            "DOCUMENT_ID","NO", "TYPE", "FORMAT", "NAME", "DESCRIPTION", "PATH"
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

        if (!empty($reqKategori)) {
            $statement_privacy .= " AND A.TYPE='" . $reqKategori . "'";
        }

        $reqCariName = $this->input->get('reqCariName');
        $reqCariDescription = $this->input->get('reqCariDescription');
        $reqJenis = $this->input->get('reqJenis');

        $_SESSION[$this->input->get("pg")."reqCariName"] = $reqCariName;
        $_SESSION[$this->input->get("pg")."reqCariDescription"] = $reqCariDescription;
        $_SESSION[$this->input->get("pg")."reqJenis"] = $reqJenis;
        
        if (!empty($reqCariName)) {
            $statement_privacy .= " AND UPPER(A.NAME)  LIKE '%" . strtoupper($reqCariName) . "%'";
        }
        if (!empty($reqCariDescription)) {
            $statement_privacy .= " AND UPPER(A.DESCRIPTION)  LIKE '%" . strtoupper($reqCariDescription) . "%'";
        }
        if (!empty($reqJenis)) {
            $statement_privacy .= " AND A.FORMAT_ID='" . $reqJenis . "'";
        }


        // $statement = " AND (UPPER(FORMAT) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $_SESSION[$this->input->get("pg")."reqCariSession"] = $statement_privacy . $statement;
        $allRecord = $dokumen_qm->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $dokumen_qm->getCountByParams(array(), $statement_privacy . $statement);

        $dokumen_qm->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $qms->query;
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
        while ($dokumen_qm->nextRow()) {
            $row = array();
            $total_pagination  = ($dsplyStart)+$nomer;
            $penomoran = $allRecordFilter-($total_pagination);
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "FORMAT"){
                    $row[] = $dokumen_qm->getField($aColumns[$i]);
                } else if ($aColumns[$i] == "NO") {
                    $row[] = $penomoran;
                }
                else{
                    $row[] = $dokumen_qm->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
               $nomer++;
        }
        echo json_encode($output);
      
    }

    function add_new()
    {
        $this->load->model("DokumenQm");
        $dokumen_qm = new DokumenQm();
          $this->load->library("FileHandler");
        $file = new FileHandler();
        $filesData = $_FILES["document"];
        $reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
        $file->cekSize($filesData,$reqLinkFileTemp);


        $reqId      = $this->input->post("reqId");
        $reqDocumentId      =  $reqId;
        $reqType            = $this->input->post("reqTipe");
        $reqFormatId        = $this->input->post("reqFormatId");
        $reqName            = $this->input->post("reqName");
        $reqDescription     = $this->input->post("reqDescription");
        $reqPath            = $this->input->post("reqPath");
        $reqLastRevisi      = $this->input->post("reqLastRevisi");

        $dokumen_qm->setField("DOCUMENT_ID", $reqId);
        $dokumen_qm->setField("TYPE", $reqType);
        $dokumen_qm->setField("FORMAT_ID", $reqFormatId);
        $dokumen_qm->setField("NAME", $reqName);
        $dokumen_qm->setField("DESCRIPTION", $reqDescription);
        $dokumen_qm->setField("PATH", $reqPath);
        $dokumen_qm->setField("LAST_REVISI", $reqLastRevisi);

        if (empty($reqId)) {
            $dokumen_qm->insert();
            $reqId = $dokumen_qm->id;
        } else {
            $dokumen_qm->update();
        }
        $name_folder = strtolower(str_replace(' ', '_', $reqType));

       
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

        $dokumen_qm = new DokumenQm();
        $dokumen_qm->setField("DOCUMENT_ID", $reqId);
        $dokumen_qm->setField("PATH", ($str_name_path));
        $dokumen_qm->update_path();

        echo $reqId . '- Data berhasil di simpan';
    }


    function add()
    {
        $this->load->model("Qms");
        $qms = new Qms();

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqFormat = $this->input->post("reqFormat");
        $reqDescription = $this->input->post("reqDescription");

        $qms->setField("FORMAT_ID", $reqId);
        $qms->setField("FORMAT", $reqFormat);
        $qms->setField("DESCRIPTION", $reqDescription);

        if ($reqMode == "insert") {
            $qms->insert();
        } else {
            $qms->update();
        }

        echo "Data berhasil disimpan.";
    }

    function delete()
    {
        $this->load->model("Qms");
        $qms = new Qms();

        $reqId = $this->input->get('reqId');

        $qms->setField("FORMAT_ID", $reqId);
        if ($qms->delete())
            $arrJson["PESAN"] = "Data berhasil dihapus.";
        else
            $arrJson["PESAN"] = "Data gagal dihapus.";

        echo json_encode($arrJson);
    }

    function delete_new()
    {
        $this->load->model("DokumenQm");
        $dokumen_qm = new DokumenQm();
        $reqId = $this->input->get("reqId");
        $dokumen_qm->setField("DOCUMENT_ID", $reqId);
        $dokumen_qm->delete();
        echo 'Data berhasil dihapus';
    }

    // function combo()
    // {
    //     $this->load->model("ForumKategori");
    //     $forum_kategori = new ForumKategori();

    //     $forum_kategori->selectByParams(array());
    //     $i = 0;
    //     while ($forum_kategori->nextRow()) {
    //         $arr_json[$i]['id']        = $forum_kategori->getField("FORUM_KATEGORI_ID");
    //         $arr_json[$i]['text']    = $forum_kategori->getField("NAMA");
    //         $i++;
    //     }

    //     echo json_encode($arr_json);
    // }

    function import_excel()
    {
        header('Cache-Control:max-age=0');
        header('Cache-Control:max-age=1');
        ini_set('memory_limit', '-1');

        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        ini_set('max_execution_time', -1);

        include_once("libraries/excel/excel_reader2.php");
        $data = new Spreadsheet_Excel_Reader($_FILES['reqFiles']["tmp_name"]);
        $baris = $data->rowcount($sheet_index = 0);
        // print_r( $data);
        // print_r($baris);
        $arrData = array();
        // $katerori = 'Company Profile';
        $reqTipe = $this->input->post("reqTipe");

        $this->load->model("DokumenQm");

        for ($i = 2; $i <= $baris; $i++) {

            $qms = new DokumenQm();
            $qms->setField("TYPE", $reqTipe);
            $qms->setField("FORMAT_ID", $data->val($i, 2));
            $qms->setField("NAME", $data->val($i, 3));
            $qms->setField("DESCRIPTION", $data->val($i, 4));
            $qms->setField("PATH", $data->val($i, 5));
            $qms->setField("LAST_REVISI", $data->val($i, 6));
            $qms->insert();
            $reqId = $qms->id;

            $qms = new DokumenQm();
            $qms->setField("DOCUMENT_ID", $reqId);
            $qms->setField("TYPE", $reqTipe);
            $qms->setField("FORMAT_ID", $reqId . ' - ' . $data->val($i, 2));
            $qms->setField("NAME", $reqId . ' - ' . $data->val($i, 3));
            $qms->setField("DESCRIPTION", $reqId . ' - ' . $data->val($i, 4));
            $qms->setField("PATH", $reqId . ' - ' . $data->val($i, 5));
            $qms->setField("LAST_REVISI", $reqId . ' - ' . $data->val($i, 6));
            $qms->update();

            // echo $data->val($i,2).'<br>';
        }
        echo 'Data Berhasil di import';
    }

    function import_excel2()
    {
        header('Cache-Control:max-age=0');
        header('Cache-Control:max-age=1');
        ini_set('memory_limit', '-1');

        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        ini_set('max_execution_time', -1);

        include_once("libraries/excel/excel_reader2.php");
        $data = new Spreadsheet_Excel_Reader($_FILES['reqFiles']["tmp_name"]);
        $baris = $data->rowcount($sheet_index = 0);
        // print_r( $data);
        // print_r($baris);
        $arrData = array();
        // $katerori = 'Company Profile';
        $reqTipe = $this->input->post("reqTipe");

        $this->load->model("DokumenQm");

        for ($i = 2; $i <= $baris; $i++) {

            $qms = new DokumenQm();
            $qms->setField("TYPE", $reqTipe);
            $qms->setField("FORMAT_ID", $data->val($i, 2));
            $qms->setField("NAME", $data->val($i, 3));
            $qms->setField("DESCRIPTION", $data->val($i, 4));
            $qms->setField("PATH", $data->val($i, 5));
            $qms->setField("LAST_REVISI", $data->val($i, 6));
            $qms->insert();
            $reqId = $qms->id;

            $qms = new DokumenQm();
            $qms->setField("DOCUMENT_ID", $reqId);
            $qms->setField("TYPE", $reqTipe);
            $qms->setField("FORMAT_ID", $reqId . ' - ' . $data->val($i, 2));
            $qms->setField("NAME", $reqId . ' - ' . $data->val($i, 3));
            $qms->setField("DESCRIPTION", $reqId . ' - ' . $data->val($i, 4));
            $qms->setField("PATH", $reqId . ' - ' . $data->val($i, 5));
            $qms->setField("LAST_REVISI", $reqId . ' - ' . $data->val($i, 6));
            $qms->update();

            // echo $data->val($i,2).'<br>';
        }
        echo 'Data Berhasil di import';
    }
}
