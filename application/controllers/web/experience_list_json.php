<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class experience_list_json extends CI_Controller
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
        $this->load->model("ExperienceList");
        $experience_list = new ExperienceList();
        $this->load->model("Company");

        $kategori = $this->input->get('reqKategori');
        $reqResume = $this->input->get('reqResume');

        $aColumns = array("EXPERIENCE_LIST_ID","URUT","PROJECT_NAME","PROJECT_LOCATION","COSTUMER_ID","CLIENT","CONTACT_NO","FROM_DATE","TO_DATE","DURATION","LONG");

        $aColumnsAlias = array("EXPERIENCE_LIST_ID","URUT","PROJECT_NAME","PROJECT_LOCATION","COSTUMER_ID","CLIENT","CONTACT_NO","FROM_DATE","TO_DATE","DURATION","LONG");

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
            if (trim($sOrder) == "ORDER BY ".$aColumns[0]." asc") {
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
        // echo $kategori;

     

        $reqContactNo               = $this->input->get('reqContactNo');
        $reqProjectLocation         = $this->input->get('reqProjectLocation');
        $reqClientName              = $this->input->get('reqClientName');
        $reqProjectName             = $this->input->get('reqProjectName');
        $reqYear                    = $this->input->get('reqYear');
        
        $_SESSION[$this->input->get("pg")."reqContactNo"] = $reqContactNo;
        $_SESSION[$this->input->get("pg")."reqProjectLocation"] = $reqProjectLocation;
        $_SESSION[$this->input->get("pg")."reqClientName"] = $reqClientName;
        $_SESSION[$this->input->get("pg")."reqProjectName"] = $reqProjectName;
        $_SESSION[$this->input->get("pg")."reqYear"] = $reqYear;
                

        if (!empty($reqContactNo)) {
            $statement_privacy .= " AND UPPER(A.CONTACT_NO) LIKE '%" . strtoupper($reqContactNo) . "%'";
        }

        if (!empty($reqProjectLocation)) {
            $statement_privacy .= " AND UPPER(A.PROJECT_LOCATION) LIKE '%" . strtoupper($reqProjectLocation) . "%'";
        }

        if (!empty($reqClientName)) {
            $statement_privacy .= " AND UPPER(B.NAME) LIKE '%" . strtoupper($reqClientName) . "%'";
        }
        if (!empty($reqProjectName)) {
            $statement_privacy .= " AND (
                    UPPER(A.PROJECT_NAME) LIKE '%" . strtoupper($reqProjectName) . "%'
                )
            ";
        }
        if (!empty($reqYear)) {
            $statement_privacy .= " AND ( TO_CHAR(A.FROM_DATE,'YYYY') = '" . trim($reqYear) . "' OR TO_CHAR(A.TO_DATE,'YYYY') = '" . trim($reqYear) . "' ) ";
        }

        
        if($_GET['sSearch'] != ""){
            $statement .= " AND (
                UPPER(A.PROJECT_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
                UPPER(B.NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
                UPPER(A.PROJECT_LOCATION) LIKE '%" . strtoupper($_GET['sSearch']) . "%'
            )";
        }


        // if(empty($sOrder)){
        //     $sOrder = "ORDER BY A.".$aColumns[0].' DESC';
        // }
        $statement .= " ";
        $_SESSION[$this->input->get("pg")."reqStatement"] = $statement_privacy . $statement;
        $allRecord = $experience_list->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $experience_list->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $experience_list->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $experience_list->query;exit;
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
        while ($experience_list->nextRow()) {
            $row = array();
            $docId = $experience_list->getField("COSTUMER_ID");
             $total_pagination  = ($dsplyStart)+$nomer;
            $penomoran = $allRecordFilter-($total_pagination);
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "PROJECT_NAME" || $aColumns[$i] == "PROJECT_LOCATION"){
                    // $row[] = truncate($experience_list->getField($aColumns[$i]), 2);
                    $row[] = lineBreak($experience_list->getField($aColumns[$i]));
                }else if($aColumns[$i]=='CLIENT'){


                   $company = new Company();
                   $company->selectByParamsMonitoring(array("A.COMPANY_ID"=>$docId));
                   $company->firstRow();
                   $reqName = $company->getField('NAME');
                   $reqAddress = lineBreak($company->getField('ADDRESS'));
                   $row[]='<b>'.$reqName.'</b><br>'.$reqAddress;
                   } else if ($aColumns[$i] == "NO") {
                    $row[] = $penomoran;
                
                }else if($aColumns[$i]=='LONG'){
                    $tgl_awal= getFormattedDateView($experience_list->getField('FROM_DATE'));
                    $tgl_akhir= getFormattedDateView($experience_list->getField('TO_DATE'));
                    $row[]  = $tgl_awal .' - '.$tgl_akhir.'<br> ( '.$experience_list->getField('DURATION').' ) Days';
                }else{
                    $row[] = $experience_list->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
               $nomer++;
        }
        echo json_encode($output);
    }


    function add()
    {
        $this->load->model("ExperienceList");
        $experience_list = new ExperienceList();

        $reqId                  = $this->input->post("reqId");
        $reqUrut                = $this->input->post("reqUrut");
        $reqProjectName         = $this->input->post("reqProjectName");
        $reqProjectLocation     = $this->input->post("reqProjectLocation");
        $reqCostumerId          = $this->input->post("reqCompanyId");
        $reqContactNo           = $this->input->post("reqContactNo");
        $reqFromDate            = $this->input->post("reqFromDate");
        $reqToDate              = $this->input->post("reqToDate");
        $reqDuration            = $this->input->post("reqDuration");
        $reqLinkFileTemp        = $this->input->post("reqLinkFileTemp");

        $experience_list->setField("EXPERIENCE_LIST_ID", $reqId);
        $experience_list->setField("PROJECT_NAME", $reqProjectName);
        $experience_list->setField("PROJECT_LOCATION", $reqProjectLocation);
        $experience_list->setField("COSTUMER_ID", ValToNull($reqCostumerId));
        $experience_list->setField("CONTACT_NO", $reqContactNo);
        $experience_list->setField("FROM_DATE", dateToDBCheck2($reqFromDate));
        $experience_list->setField("TO_DATE", dateToDBCheck2($reqToDate));
        $experience_list->setField("DURATION", $reqDuration);
        $experience_list->setField("URUT", $reqUrut);

        if (empty($reqId)) {
            $jumlah = $this->db->query("SELECT COUNT(1) JUMLAH FROM EXPERIENCE_LIST WHERE URUT = '$reqUrut'")->row()->jumlah;
            if($jumlah > 0){
                echo $reqId."-No Urut $reqUrut sudah digunakan.";
                return;
            }
            $experience_list->insert();
            $reqId=$experience_list->id;
        } else {
            $jumlah = $this->db->query("SELECT COUNT(1) JUMLAH FROM EXPERIENCE_LIST WHERE URUT = '$reqUrut' AND EXPERIENCE_LIST_ID <> '$reqId'")->row()->jumlah;
            if($jumlah > 0){
                echo $reqId."-No Urut $reqUrut sudah digunakan.";
                return;
            }
            $experience_list->update();
        }


        $this->load->library("FileHandler");
        $file = new FileHandler();
        $filesData = $_FILES["document"];
        $FILE_DIR = "uploads/experience_list/" . $reqId . "/";
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

        $experience_list = new ExperienceList();
        $experience_list->setField("EXPERIENCE_LIST_ID", $reqId);
        $experience_list->setField("PATH", ($str_name_path));
        $experience_list->update_path();

        $reqCompanyId = $this->input->post('reqCompanyId');
        if($reqCompanyId != ""){
            $this->update_company();
        }

        echo $reqId."-Data berhasil disimpan.";
    }


    function update_company(){
         $this->load->model("Company");
         $reqCompanyName     = $this->input->post('reqCompanyName');
         $reqCompanyId       = $this->input->post('reqCompanyId');
         $reqDocumentPerson  = $this->input->post('reqDocumentPerson');
         $reqAddress         = $_POST['reqAddress'];
         $reqEmail           = $this->input->post('reqEmail');
         $reqTelephone       = $this->input->post('reqTelephone');
         $reqFaximile        = $this->input->post('reqFaximile');
         $reqHp              = $this->input->post('reqHp');

         $company = new Company();
         $company->setField("COMPANY_ID", $reqCompanyId);
         $company->setField("NAME", $reqCompanyName);
         $company->setField("ADDRESS", $reqAddress);
         $company->setField("PHONE", $reqHp);
         $company->setField("FAX", $reqFaximile);
         $company->setField("EMAIL", $reqEmail);
         $company->setField("CP1_NAME", $reqDocumentPerson);
         $company->setField("CP1_TELP", $reqTelephone);
         $company->update_offer();

    }
    function delete()
    {
        $this->load->model("ExperienceList");
        $experience_list = new ExperienceList();

        $reqId = $this->input->get('reqId');

        $experience_list->setField("EXPERIENCE_LIST_ID", $reqId);
        if ($experience_list->delete())
            $arrJson["PESAN"] = "Data berhasil dihapus.";
        else
            $arrJson["PESAN"] = "Data gagal dihapus.";

        echo json_encode($arrJson);
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
}
