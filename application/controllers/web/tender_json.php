<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class tender_json extends CI_Controller
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

        header('Cache-Control:max-age=0');
        header('Cache-Control:max-age=1');
        ini_set('memory_limit', '-1');

        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        ini_set('max_execution_time', -1);

    }

    function json()
    {
        $this->load->model("Tender");
        $tender = new Tender();

        $aColumns =  array("TENDER_ID", "URUT","PROJECT_NO", "COMPANY_NAME", "PROJECT_NAME", 
                "ANNOUNCEMENT", "ISSUED_DATE", "REGISTER_DATE", 
                "PQ_DATE", "PREBID_DATE", "PREBID_PATH", "SUBMISSION_DATE", "OPENING1_DATE", "OPENING2_DATE", "LOA", "REMARK", "STATUS");

        $aColumnsAlias =  array("TENDER_ID","URUT", "PROJECT_NO", "B.NAME", "PROJECT_NAME", 
                "ANNOUNCEMENT", "ISSUED_DATE", "REGISTER_DATE", 
                "PQ_DATE", "PREBID_DATE", "PREBID_PATH", "SUBMISSION_DATE", "OPENING1_DATE", "OPENING2_DATE", "LOA", "REMARK", "STATUS");
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
            if (trim($sOrder) == "ORDER BY TENDER_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY URUT desc";
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

        $reqAnnouncement            =  $this->input->get('reqAnnouncement');
        $reqCariProjectNo           =  $this->input->get('reqCariProjectNo');
        $reqCariProjectName         =  $this->input->get('reqCariProjectName');
        $reqCariIssuedDateFrom      =  $this->input->get('reqCariIssuedDateFrom');
        $reqCariIssuedDateTo        =  $this->input->get('reqCariIssuedDateTo');
        $reqCariRegisterDateFrom    =  $this->input->get('reqCariRegisterDateFrom');
        $reqCariRegisterDateTo      =  $this->input->get('reqCariRegisterDateTo');
        $reqCariPQDateFrom          =  $this->input->get('reqCariPQDateFrom');
        $reqCariPQDateTo            =  $this->input->get('reqCariPQDateTo');
        $reqCariPrebidDateFrom      =  $this->input->get('reqCariPrebidDateFrom');
        $reqCariPrebidDateTo        =  $this->input->get('reqCariProjectName');
        $reqCariSubmissionDateFrom  =  $this->input->get('reqCariSubmissionDateFrom');
        $reqCariSubmissionDateTo    =  $this->input->get('reqCariSubmissionDateTo');
        $reqCariOpening1DateFrom    =  $this->input->get('reqCariOpening1DateFrom');
        $reqCariOpening1DateTo      =  $this->input->get('reqCariOpening1DateTo');
        $reqCariOpening2DateFrom    =  $this->input->get('reqCariOpening2DateFrom');
        $reqCariOpening2DateTo      =  $this->input->get('reqCariOpening2DateTo');

        $_SESSION[$this->input->get("pg")."reqCariProjectNo"] = $reqCariProjectNo;
        $_SESSION[$this->input->get("pg")."reqCariProjectName"] = $reqCariProjectName;
        $_SESSION[$this->input->get("pg")."reqCariIssuedDateFrom"]     =  $reqCariIssuedDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariIssuedDateTo"]       =  $reqCariIssuedDateTo;
        $_SESSION[$this->input->get("pg")."reqCariRegisterDateFrom"]   =  $reqCariRegisterDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariRegisterDateTo"]     =  $reqCariRegisterDateTo;
        $_SESSION[$this->input->get("pg")."reqCariPQDateFrom"]         =  $reqCariPQDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariPQDateTo"]           =  $reqCariPQDateTo;
        $_SESSION[$this->input->get("pg")."reqCariPrebidDateFrom"]     =  $reqCariPrebidDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariPrebidDateTo"]       =  $reqCariProjectName;
        $_SESSION[$this->input->get("pg")."reqCariSubmissionDateFrom"] =  $reqCariSubmissionDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariSubmissionDateTo"]   =  $reqCariSubmissionDateTo;
        $_SESSION[$this->input->get("pg")."reqCariOpening1DateFrom"]   =  $reqCariOpening1DateFrom;
        $_SESSION[$this->input->get("pg")."reqCariOpening1DateTo"]     =  $reqCariOpening1DateTo;
        $_SESSION[$this->input->get("pg")."reqCariOpening2DateFrom"]   =  $reqCariOpening2DateFrom;
        $_SESSION[$this->input->get("pg")."reqCariOpening2DateTo"]     =  $reqCariOpening2DateTo;
        $_SESSION[$this->input->get("pg")."reqCariOrder"]     =  $sOrder;

        if (!empty($reqCariProjectNo)) {
            $statement_privacy .= " AND UPPER(PROJECT_NO) LIKE '%" . strtoupper($reqCariProjectNo) . "%' ";
        }

        if (!empty($reqCariProjectName)) {
            $statement_privacy .= " AND UPPER(PROJECT_NAME) LIKE '%" . strtoupper($reqCariProjectName) . "%' ";
        }

        if (!empty($reqCariIssuedDateFrom) && !empty($reqCariIssuedDateTo)) {
            $statement_privacy .= " AND A.ISSUED_DATE BETWEEN TO_DATE('" . $reqCariIssuedDateFrom . "', 'DD-MM-YYYY') AND TO_DATE('" . $reqCariIssuedDateTo . "', 'DD-MM-YYYY')";
        }

        if (!empty($reqCariRegisterDateFrom) && !empty($reqCariRegisterDateTo)) {
            $statement_privacy .= " AND A.REGISTER_DATE BETWEEN TO_DATE('" . $reqCariRegisterDateFrom . "', 'DD-MM-YYYY') AND TO_DATE('" . $reqCariRegisterDateTo . "', 'DD-MM-YYYY')";
        }

        if (!empty($reqCariPQDateFrom) && !empty($reqCariPQDateTo)) {
            $statement_privacy .= " AND A.PQ_DATE BETWEEN TO_DATE('" . $reqCariPQDateFrom . "', 'DD-MM-YYYY') AND TO_DATE('" . $reqCariPQDateTo . "', 'DD-MM-YYYY')";
        }

        if (!empty($reqCariPrebidDateFrom) && !empty($reqCariPrebidDateTo)) {
            $statement_privacy .= " AND A.PREBID_DATE BETWEEN TO_DATE('" . $reqCariPrebidDateFrom . "', 'DD-MM-YYYY') AND TO_DATE('" . $reqCariPrebidDateTo . "', 'DD-MM-YYYY')";
        }

        if (!empty($reqCariSubmissionDateFrom) && !empty($reqCariSubmissionDateTo)) {
            $statement_privacy .= " AND A.SUBMISSION_DATE BETWEEN TO_DATE('" . $reqCariSubmissionDateFrom . "', 'DD-MM-YYYY') AND TO_DATE('" . $reqCariSubmissionDateTo . "', 'DD-MM-YYYY')";
        }

        if (!empty($reqCariOpening1DateFrom) && !empty($reqCariOpening1DateTo)) {
            $statement_privacy .= " AND A.OPENING1_DATE BETWEEN TO_DATE('" . $reqCariOpening1DateFrom . "', 'DD-MM-YYYY') AND TO_DATE('" . $reqCariOpening1DateTo . "', 'DD-MM-YYYY')";
        }

        if (!empty($reqCariOpening2DateFrom) && !empty($reqCariOpening2DateTo)) {
            $statement_privacy .= " AND A.OPENING2_DATE BETWEEN TO_DATE('" . $reqCariOpening2DateFrom . "', 'DD-MM-YYYY') AND TO_DATE('" . $reqCariOpening2DateTo . "', 'DD-MM-YYYY')";
        }

        if (!empty($reqAnnouncement)) {
            $reqAnnouncement = explode(",", $reqAnnouncement);
            $announcement = "";
            foreach ($reqAnnouncement as $key => $value) {
                if($key == 0){
                    $announcement .= "'$value'";
                } else {
                    $announcement .= ",'$value'";
                }
            }
            $statement_privacy .= " AND ANNOUNCEMENT IN (".$announcement.") ";
            // $sOrder = " ORDER BY A.URUT ASC ";
        }

        $statement = " AND (
            UPPER(PROJECT_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
            UPPER(PROJECT_NO) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR 
            UPPER(REMARK) LIKE '%" . strtoupper($_GET['sSearch']) . "%'
        )";


        $tender2 = new Tender();
        $tender2->selectByParamsUrut(array(),-1,-1,$statement_privacy . $statement); 
        // ECHO  $tender2->query;exit; 
        while ($tender2->nextRow()) {
            $tender3 = new Tender();
            $tender3->setField("URUT",$tender2->getField("URUT"));
            $tender3->setField("TENDER_ID",$tender2->getField("TENDER_ID"));
            $tender3->updateUrut();
        }


        $allRecord = $tender->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $tender->getCountByParams(array(), $statement_privacy . $statement);

        $tender->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $tender->query;
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

        while ($tender->nextRow()) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "PROJECT_NAME" || $aColumns[$i] == "REMARK") {
                    // $row[] = truncate($tender->getField($aColumns[$i]), 2);
                    $row[] = lineBreak($tender->getField($aColumns[$i]));
                } else if ($aColumns[$i] == "PREBID_PATH") {
                    $path = $tender->getField($aColumns[$i]);
                    $path = explode(";", $path);
                    $id = $tender->getField("TENDER_ID");
                    if($path[0] == "")
                        $row[] = "-";
                    else
                        $row[] = '<a href="uploads/prebid/'.$id.'/'.$path[0].'" style=""><i class="fa fa-download fa-lg"></i>'.lineBreak($path[0]).'</a>';
                } else if ($aColumns[$i] == "TANGGAL") {
                    // $row[] = truncate($tender->getField($aColumns[$i]), 2);
                    $tgl_nama = explode('-', $tender->getField($aColumns[$i]));
                    $str = ltrim($tgl_nama[1], '0');
                    $reqDeskripsi = getNameMonth($str) . ' ' . $tgl_nama[2];
                    $row[] = $reqDeskripsi;
                } else {
                    $row[] = $tender->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }


    function add_master(){
        $this->load->model("MasterTender");
        
        $reqProjectNo = $this->input->post('reqProjectNo');
        $reqProjectName = $this->input->post('reqProjectName');
        $reqCompanyId = $this->input->post("reqCompanyId");
        $reqCompanyName = $this->input->post("reqCompanyName");
          
        $statement = " AND UPPER(REPLACE(A.NO_PROJECT, ' ', '' )) = UPPER(REPLACE('".$reqProjectNo."', ' ', '' ))";
        $master_tenderTotal = new MasterTender();
        $total = $master_tenderTotal->getCountByParamsMonitoring(array(), $statement);

        if( $total == 0){
            $master_tender = new MasterTender();
            $master_tender->setField("NO_PROJECT",$reqProjectNo);
            $master_tender->setField("KETERANGAN",$reqProjectName);
            $master_tender->setField("COMPANY_ID",$reqCompanyId);
            $master_tender->setField("COMPANY_NAME",$reqCompanyName);
            $master_tender->insert();
        }


    }

    function pilih_detail(){
        $reqId = $this->input->get("reqId");
         $this->load->model("MasterTender");
         $master_tender = new MasterTender();
         $statement = " AND A.NO_PROJECT ='".$reqId."'";
         $master_tender->selectByParamsMonitoring(array(),-1,-1,$statement);
         $master_tender->firstRow();
         $arrData["KETERANGAN"]= $master_tender->getField("KETERANGAN");
         $arrData["COMPANY_ID"]= $master_tender->getField("COMPANY_ID");
         $arrData["COMPANY_NAME"]= $master_tender->getField("COMPANY_NAME");
         echo   json_encode($arrData);

    }
 
    function add()
    {
        $this->load->model("Tender");
        $tender = new Tender();
        $this->load->library("FileHandler");
        $file = new FileHandler();
        $filesData = $_FILES["document"];
        $reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqCompanyId = $this->input->post("reqCompanyId");
        $reqProjectNo = $this->input->post("reqProjectNo");
        $reqProjectName = $this->input->post("reqProjectName");
        $reqIssuedDate = $this->input->post("reqIssuedDate");
        $reqRegisterDate = $this->input->post("reqRegisterDate");
        $reqPQDate = $this->input->post("reqPQDate");
        $reqPrebidDate = $this->input->post("reqPrebidDate");
        $reqSubmisionDate = $this->input->post("reqSubmisionDate");
        $reqOpening1Date = $this->input->post("reqOpening1Date");
        $reqOpening2Date = $this->input->post("reqOpening2Date");
        $reqLoa = $this->input->post("reqLoa");
        $reqAnnouncement = $this->input->post("reqAnnouncement");
        $reqRemark = $this->input->post("reqRemark");

        $tender->setField("TENDER_ID", $reqId);
        $tender->setField("COMPANY_ID", ValToNullDB($reqCompanyId));
        $tender->setField("PROJECT_NAME", $reqProjectName);
        $tender->setField("PROJECT_NO", $reqProjectNo);
        $tender->setField("ISSUED_DATE", dateToDBCheck($reqIssuedDate));
        $tender->setField("REGISTER_DATE", dateToDBCheck($reqRegisterDate));
        $tender->setField("PQ_DATE", dateToDBCheck($reqPQDate));
        $tender->setField("PREBID_DATE", dateToDBCheck($reqPrebidDate));
        $tender->setField("SUBMISSION_DATE", dateToDBCheck($reqSubmisionDate));
        $tender->setField("OPENING1_DATE", dateToDBCheck($reqOpening1Date));
        $tender->setField("OPENING2_DATE", dateToDBCheck($reqOpening2Date));
        $tender->setField("ANNOUNCEMENT", $reqAnnouncement);
        $tender->setField("LOA", $reqLoa);
        $tender->setField("REMARK", $reqRemark);

        if ($reqMode == "insert") {
            $tender->insert();
            $reqId = $tender->id;
        } else {
            $tender->update();
        }

        $FILE_DIR = "uploads/prebid/" . $reqId . "/";
        makedirs($FILE_DIR);

        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile =   $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
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

        $tender->setField("TENDER_ID", $reqId);
        $tender->setField("PREBID_PATH", ($str_name_path));
        $tender->updatePrebidPath();


          $this->add_master();

          // $this->add_dokument_baru($reqId);

        echo $reqId."-Data berhasil disimpan.-";
    }

    function add_dokument_baru($reqId=''){
         $this->load->model("Tender");
         $reqTipe = 'tender_monitoring_baru';
         $tender = new Tender();
         $this->load->library("FileHandler");
         $file = new FileHandler();
        $field_data =array('DOK_ADMINITISTRASI',DOK_TEKNIS,"DOK_KOMERSIAL");
        $index_colom=0;
         for($kk=2;$kk<5;$kk++){
             $filesData = $_FILES["document".$kk];
             $reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp".$kk);
             $FILE_DIR = "uploads/".$reqTipe."/" . $reqId . "/".$kk."/";
             makedirs($FILE_DIR);

             $arrData = array();
             for ($i = 0; $i < count($filesData['name']); $i++) {
                $renameFile =   $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
                if ($file->uploadToDirArray('document'.$kk, $FILE_DIR, $renameFile, $i)) {
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

             $tender->setField("TENDER_ID", $reqId);
             $tender->setField("COLOM", $field_data[$index_colom]);
             // FIELD_NAMA
             $tender->setField("DOK", ($str_name_path));
             $tender->updatePathBaru();


             $index_colom++;
         }

        
        

    }

    // function add_project(){
    //       $reqProjectNo                   = $this->input->post("reqProjectNo");
    //       echo $reqProjectNo;
    // }
    function add_project()
    {
        $this->load->model("Tender");
        $tender = new Tender();

        $this->load->library("FileHandler");
        $file = new FileHandler();

        $reqLinkFilePersiapan           = $_FILES["reqLinkFilePersiapan"];
        $reqLinkFilePelaksanaan         = $_FILES["reqLinkFilePelaksanaan"];
        $reqLinkFilePenyelesaian        = $_FILES["reqLinkFilePenyelesaian"];
        $reqLinkFileDocTender           = $_FILES["reqLinkFileDocTender"];

        $reqLinkFilePersiapanTemp       = $this->input->post("reqLinkFilePersiapanTemp");
        $reqLinkFilePelaksanaanTemp     = $this->input->post("reqLinkFilePelaksanaanTemp");
        $reqLinkFilePenyelesaianTemp    = $this->input->post("reqLinkFilePenyelesaianTemp");
        $reqLinkFileDocTenderTemp       = $this->input->post("reqLinkFileDocTenderTemp");
        $reqTypePersiapan               = $this->input->post("reqTypePersiapan");
        $reqTypePelaksanaan             = $this->input->post("reqTypePelaksanaan");
        $reqTypePenyelesaian            = $this->input->post("reqTypePenyelesaian");
        $reqTypeDocTender               = $this->input->post("reqTypeDocTender");
        $reqId                          = $this->input->post("reqId");
        $reqCompanyId                   = $this->input->post("reqCompanyId");
        $reqProjectNo                   = $this->input->post("reqProjectNo");
        $reqProjectName                 = $this->input->post("reqProjectName");
        $reqAnnouncement                = $this->input->post("reqAnnouncement");


        $tender->setField("TENDER_ID", $reqId);
        $tender->setField("COMPANY_ID", ValToNullDB($reqCompanyId));
        $tender->setField("PROJECT_NAME", $reqProjectName);
        $tender->setField("PROJECT_NO", $reqProjectNo);
        $tender->setField("ANNOUNCEMENT", $reqAnnouncement);
        if($reqId == ""){
            $tender->insertProject();
            $reqId = $tender->id;
        } else {
            $tender->updateProject();
        }


        $FILE_DIR = "uploads/tender_persiapan/" . $reqId . "/";
        makedirs($FILE_DIR);

        $arrPersiapan = array();
        for ($i = 0; $i < count($reqLinkFilePersiapan['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-"  . $reqLinkFilePersiapan['name'][$i];
            if ($file->uploadToDirArray('reqLinkFilePersiapan', $FILE_DIR, $renameFile, $i)) {
                array_push($arrPersiapan, array(
                    "type" => $reqTypePersiapan[$i],
                    "file" => setQuote($renameFile)
                ));
            } else {
                array_push($arrPersiapan, array(
                    "type" => $reqTypePersiapan[$i],
                    "file" => $reqLinkFilePersiapanTemp[$i]
                ));
            }
        }

        $FILE_DIR = "uploads/tender_pelaksanaan/" . $reqId . "/";
        makedirs($FILE_DIR);

        $arrPelaksanaan = array();
        for ($i = 0; $i < count($reqLinkFilePelaksanaan['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-"  . $reqLinkFilePelaksanaan['name'][$i];
            if ($file->uploadToDirArray('reqLinkFilePelaksanaan', $FILE_DIR, $renameFile, $i)) {
                array_push($arrPelaksanaan, array(
                    "type" => $reqTypePelaksanaan[$i],
                    "file" => setQuote($renameFile)
                ));
            } else {
                array_push($arrPelaksanaan, array(
                    "type" => $reqTypePelaksanaan[$i],
                    "file" => $reqLinkFilePelaksanaanTemp[$i]
                ));
            }
        }

        $FILE_DIR = "uploads/tender_penyelesaian/" . $reqId . "/";
        makedirs($FILE_DIR);

        $arrPenyelesaian = array();
        for ($i = 0; $i < count($reqLinkFilePenyelesaian['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-"  . $reqLinkFilePenyelesaian['name'][$i];
            if ($file->uploadToDirArray('reqLinkFilePenyelesaian', $FILE_DIR, $renameFile, $i)) {
                array_push($arrPenyelesaian, array(
                    "type" => $reqTypePenyelesaian[$i],
                    "file" => setQuote($renameFile)
                ));
            } else {
                array_push($arrPenyelesaian, array(
                    "type" => $reqTypePenyelesaian[$i],
                    "file" => $reqLinkFilePenyelesaianTemp[$i]
                ));
            }
        }

        $FILE_DIR = "uploads/tender_doc_tender/" . $reqId . "/";
        makedirs($FILE_DIR);

        $arrDocTender = array();
        for ($i = 0; $i < count($reqLinkFileDocTender['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-"  . $reqLinkFileDocTender['name'][$i];
            if ($file->uploadToDirArray('reqLinkFileDocTender', $FILE_DIR, $renameFile, $i)) {
                array_push($arrDocTender, array(
                    "type" => $reqTypeDocTender[$i],
                    "file" => setQuote($renameFile)
                ));
            } else {
                array_push($arrDocTender, array(
                    "type" => $reqTypeDocTender[$i],
                    "file" => $reqLinkFileDocTenderTemp[$i]
                ));
            }
        }

        $tender = new Tender();
        $tender->setField("TENDER_ID", $reqId);
        $tender->setField("PERSIAPAN_PATH", json_encode($arrPersiapan));
        $tender->setField("PELAKSANAAN_PATH", json_encode($arrPelaksanaan));
        $tender->setField("BA_PENY_PATH", json_encode($arrPenyelesaian));
        $tender->setField("DOC_TENDER_PATH", json_encode($arrDocTender));
        $tender->updatePath();

        $this->add_master();

        echo $reqId."-Data berhasil disimpan.-";
    }

    function delete()
    {
        $this->load->model("Tender");

        $tender = new Tender();
        $reqId = $this->input->get('reqId');

        $tender->setField("TENDER_ID", $reqId);
        if ($tender->delete()) {
            // $arrJson["PESAN"] = "Data berhasil dihapus.";
        } else {
            // $arrJson["PESAN"] = "Data gagal dihapus.";
        }

        echo "Data berhasil dihapus";
    }

}
