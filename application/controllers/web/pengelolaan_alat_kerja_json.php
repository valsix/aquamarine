<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class pengelolaan_alat_kerja_json extends CI_Controller
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
       $this->load->model('EquipmentList');
        $document = new EquipmentList();

       

        $aColumns = array(
            "EQUIP_ID",  "TGL","EQUIP_NAME", "EQUIP_SPEC", "EQUIP_QTY", "EQUIP_CONDITION_G","EQUIP_CONDITION_R","EQUIP_STORAGE","EQUIP_LASTCAL","STOCK"
        );

        $aColumnsAlias =array(
            "EQUIP_ID",  "TGL","EQUIP_NAME", "EQUIP_SPEC", "EQUIP_QTY", "EQUIP_CONDITION_G","EQUIP_CONDITION_R","EQUIP_STORAGE","EQUIP_LASTCAL","STOCK"
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
            if (trim($sOrder) == "ORDER BY EQUIP_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY EQUIP_ID desc";
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
        $reqCariIdNumber                 = $this->input->get("reqCariIdNumber");
        $reqCariCondition                = $this->input->get("reqCariCondition");
        $reqCariCategori                 = $this->input->get("reqCariCategori");
        $reqCariStorage                  = $this->input->get("reqCariStorage");
        $reqCariCompanyName              = $this->input->get("reqCariCompanyName");
        $reqCariIncomingDateFrom         = $this->input->get("reqCariIncomingDateFrom");
        $reqCariIncomingDateTo           = $this->input->get("reqCariIncomingDateTo");
        $reqCariItemFrom                 = $this->input->get("reqCariItemFrom");
        $reqCariItemTo                   = $this->input->get("reqCariItemTo");
        $reqCariLastCalibrationFrom      = $this->input->get("reqCariLastCalibrationFrom");
        $reqCariLastCalibrationTo        = $this->input->get("reqCariLastCalibrationTo");
        $reqCariQuantity                 = $this->input->get("reqCariQuantity");
        $reqCariNextCalibrationFrom      = $this->input->get("reqCariNextCalibrationFrom");
        $reqCariNextCalibrationTo        = $this->input->get("reqCariNextCalibrationTo");
        $reqCariSpesification            = $this->input->get("reqCariSpesification");
        $reqExpired                      = $this->input->get("reqExpired");

        $_SESSION[$this->input->get("pg")."reqCariIdNumber"] = $reqCariIdNumber;
        $_SESSION[$this->input->get("pg")."reqCariCondition"] = $reqCariCondition;
        $_SESSION[$this->input->get("pg")."reqCariCategori"] = $reqCariCategori;
        $_SESSION[$this->input->get("pg")."reqCariStorage"] = $reqCariStorage;
        $_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariIncomingDateFrom"] = $reqCariIncomingDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariIncomingDateTo"] = $reqCariIncomingDateTo;
        $_SESSION[$this->input->get("pg")."reqCariItemFrom"] = $reqCariItemFrom;
        $_SESSION[$this->input->get("pg")."reqCariItemTo"] = $reqCariItemTo;
        $_SESSION[$this->input->get("pg")."reqCariLastCalibrationFrom"] = $reqCariLastCalibrationFrom;
        $_SESSION[$this->input->get("pg")."reqCariLastCalibrationTo"] = $reqCariLastCalibrationTo;
        $_SESSION[$this->input->get("pg")."reqCariQuantity"] = $reqCariQuantity;
        $_SESSION[$this->input->get("pg")."reqCariNextCalibrationFrom"] = $reqCariNextCalibrationFrom;
        $_SESSION[$this->input->get("pg")."reqCariNextCalibrationTo"] = $reqCariNextCalibrationTo;
        $_SESSION[$this->input->get("pg")."reqCariSpesification"] = $reqCariSpesification;
        $_SESSION[$this->input->get("pg")."reqExpired"] = $reqExpired;

        $statement_privacy = '';
        if (!empty($reqCariIdNumber)) {

            $statement_privacy  .= " AND A.EQUIP_ID = '" . strtoupper($reqCariIdNumber) . "' ";
        }
        if (!empty($reqCariCondition) && $reqCariCondition != "ALL") {

            $statement_privacy  .= " AND UPPER(A.EQUIP_CONDITION) LIKE '" .  strtoupper($reqCariCondition) . "%' ";
        }
        if (!empty($reqCariCategori) && $reqCariCategori != "ALL") {

            $statement_privacy  .= " AND UPPER(B.EC_NAME) ='" .  strtoupper($reqCariCategori) . "' ";
        }
        if (!empty($reqCariStorage)) {

            $statement_privacy  .= " AND UPPER(A.EQUIP_STORAGE) LIKE '%" .  strtoupper($reqCariStorage) . "%' ";
        }
        if (!empty($reqCariCompanyName)) {

            $statement_privacy  .= " AND UPPER(A.EQUIP_NAME) LIKE '%" .  strtoupper($reqCariCompanyName) . "%' ";
        }
        if (!empty($reqCariIncomingDateFrom) && !empty($reqCariIncomingDateTo)) {

            $statement_privacy  .= " AND A.EQUIP_DATEIN BETWEEN  TO_DATE('" . $reqCariIncomingDateFrom . "','dd-mm-yyyy')  AND TO_DATE('" . $reqCariIncomingDateFrom . "','dd-mm-yyyy') ";
        }

        if (!empty($reqCariItemFrom) && !empty($reqCariItemTo)) {


            $statement_privacy  .= " AND A.EQUIP_QTY BETWEEN " . $reqCariItemFrom . " AND " . $reqCariItemTo;
        }

        if (!empty($reqCariLastCalibrationFrom) && !empty($reqCariLastCalibrationTo)) {

            $statement_privacy  .= " AND A.EQUIP_LASTCAL BETWEEN TO_DATE('" . $reqCariLastCalibrationFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariLastCalibrationTo . "','dd-mm-yyyy') ";
        }

        if (!empty($reqCariQuantity)) {

            $statement_privacy  .= " AND A.EQUIP_ITEM = '" . $reqCariQuantity . "' ";
        }
        if (!empty($reqCariNextCalibrationFrom) && !empty($reqCariNextCalibrationTo)) {
            $statement_privacy  .= " AND A.EQUIP_NEXTCAL BETWEEN TO_DATE('" . $reqCariNextCalibrationFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariNextCalibrationTo . "','dd-mm-yyyy') ";
        }

        if (!empty($reqCariSpesification)) {

            $statement_privacy  .= " AND UPPER(A.SERIAL_NUMBER) LIKE '%" .  strtoupper($reqCariSpesification) . "%' ";
        }

        if (!empty($reqExpired)) {

            $statement_privacy  .= " AND CERTIFICATE_EXPIRED_DATE < CURRENT_DATE ";
        }

        $reqKategoriId                       = $this->input->get("reqKategoriId");
        if (!empty($reqKategoriId)) {

            $statement_privacy  .= " AND B.EC_ID ='".$reqKategoriId."' ";
        }

        $_SESSION[$this->input->get("pg")."reqCariSession"] = $statement_privacy . $statement;
       
        $allRecord = $document->getCountByParamsMonitoringBaru(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $document->getCountByParams(array(), $statement_privacy . $statement);

        $document->selectByParamsMonitoringBaru(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $document->query;exit;
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

        while ($document->nextRow()) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NAME"){
                    $row[] = $document->getField($aColumns[$i]);
                }else if($aColumns[$i] == "EQUIP_QTY"){
                     $row[] = $document->getField($aColumns[$i]).' '.$document->getField('EQUIP_ITEM');
                }else if($aColumns[$i] == "EQUIP_CONDITION_G"){
                    $reqKondition = $document->getField('EQUIP_CONDITION');
                    $icheck='';
                    if($reqKondition=='G' || strtoupper($reqKondition)=='GOOD'){
                        $icheck ='<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
                    }

                     $row[] = $icheck;
                }else if($aColumns[$i] == "EQUIP_CONDITION_R"){
                    $reqKondition = $document->getField('EQUIP_CONDITION');
                    $icheck='';
                    if($reqKondition=='R' || strtoupper($reqKondition)=='RUSAK'){
                        $icheck ='<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
                    }

                     $row[] = $icheck;
                }else if($aColumns[$i] == "EQUIP_LASTCAL"){
                    $reqKondition = $document->getField('EQUIP_LASTCAL');
                    $icheck='';
                    if(!empty($reqKondition)){
                        $icheck ='<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
                    }

                     $row[] = $icheck;
                }
                else{
                    $row[] = $document->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }


   
}
