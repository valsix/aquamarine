<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class offer_json extends CI_Controller
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

    function create_qr($reqId)
    {
        $this->load->library("qrcodegenerator");
        $qrcodegenerators =  new qrcodegenerator();
        $status = 'offering';
        $nipp = $this->USERNAME;
        $qrcodegenerators->generateQr($status, $reqId, $nipp);

        // echo 'QR sudah di create';
    }


    function json()
    {

        $this->load->model("Offer");
        $offer = new Offer();

        $aColumns = array(
            "OFFER_ID","NO", "NO_ORDER", "COMPANY_NAME", "VESSEL_NAME", "GENERAL_SERVICE_NAME", "DESTINATION","CLASS_OF_VESSEL", "TYPE_OF_VESSEL", "FAXIMILE", "TOTAL_PRICE", "SCOPE_OF_WORK", "EMAIL",'STATUS',"STATUS_DESC","REASON"
        );

        $aColumnsAlias = array(
            "OFFER_ID","NO", "NO_ORDER", "COMPANY_NAME", "VESSEL_NAME", "GENERAL_SERVICE_NAME", "DESTINATION","CLASS_OF_VESSEL", "TYPE_OF_VESSEL", "FAXIMILE", "TOTAL_PRICE", "SCOPE_OF_WORK", "EMAIL",'STATUS',"STATUS_DESC","REASON"
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
            // echo $sOrder;exit;

            //Check if there is an order by clause
            if (trim($sOrder) == "ORDER BY OFFER_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY OFFER_ID desc";
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

        $statement = "";
        
        $statement = " AND ( UPPER(A.NO_ORDER) LIKE '%" . strtoupper($_GET['sSearch']) . "%'
        OR UPPER(A.VESSEL_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%'
         OR UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%'
         OR UPPER(A.CLASS_OF_VESSEL) LIKE '%" . strtoupper($_GET['sSearch']) . "%'
        ) 
        ";


        $reqCariNoOrder = $this->input->get("reqCariNoOrder");
        $reqCariDateofServiceFrom = $this->input->get("reqCariDateofServiceFrom");
        $reqCariDateofServiceTo = $this->input->get("reqCariDateofServiceTo");
        $reqCariCompanyName = $this->input->get("reqCariCompanyName");
        $reqCariPeriodeYear = $this->input->get("reqCariPeriodeYear");
        $reqCariVasselName = $this->input->get("reqCariVasselName");
        $reqCariProject = $this->input->get("reqCariProject");
        $reqCariGlobalSearch = $this->input->get("reqCariGlobalSearch");
        $reqCariStatus = $this->input->get("reqCariStatus");
        $reqClass = $this->input->get("reqClass");
        $reqDestination = $this->input->get("reqDestination");
        
        $reqClassType= $this->input->get("reqClassType");
        $reqProjectName= $this->input->get("reqProjectName");
         $reqBulan= $this->input->get("reqBulan");
        

        $_SESSION[$this->input->get("pg")."reqCariNoOrder"] = $reqCariNoOrder;
        $_SESSION[$this->input->get("pg")."reqCariDateofServiceFrom"] = $reqCariDateofServiceFrom;
        $_SESSION[$this->input->get("pg")."reqCariDateofServiceTo"] = $reqCariDateofServiceTo;
        $_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariPeriodeYear"] = $reqCariPeriodeYear;
        $_SESSION[$this->input->get("pg")."reqCariVasselName"] = $reqCariVasselName;
        $_SESSION[$this->input->get("pg")."reqCariProject"] = $reqCariProject;
        $_SESSION[$this->input->get("pg")."reqCariGlobalSearch"] = $reqCariGlobalSearch;
        $_SESSION[$this->input->get("pg")."reqCariStatus"] = $reqCariStatus;
        $_SESSION[$this->input->get("pg")."reqDestination"] = $reqDestination;
        $_SESSION[$this->input->get("pg")."reqClass"] = $reqClass;
         $_SESSION[$this->input->get("pg")."reqBulan"] = $reqBulan;
        $_SESSION[$this->input->get("pg")."reqClassType"] = $reqClassType;
        $_SESSION[$this->input->get("pg")."reqProjectName"] = $reqProjectName;
        $_SESSION[$this->input->get("pg")."reqSearch"] =$statement;

        if (!empty($reqCariNoOrder)) {
            $statement_privacy .= " AND UPPER(A.NO_ORDER) LIKE '%" . strtoupper($reqCariNoOrder) . "' ";
        }
        // echo $reqCariDateofServiceFrom;exit;
        if (!empty($reqCariDateofServiceFrom) && !empty($reqCariDateofServiceTo)) {

            $statement_privacy .= " AND A.DATE_OF_SERVICE BETWEEN to_date('" . $reqCariDateofServiceTo . "', 'DD-MM-YYYY')  AND  to_date('" . $reqCariDateofServiceFrom . "', 'DD-MM-YYYY') ";
        }

        if (!empty($reqCariCompanyName)) {
            $statement_privacy .= " AND UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%' ";
        }

        if (!empty($reqCariPeriodeYear) && $reqCariPeriodeYear !='' && $reqCariPeriodeYear !='All Year' && !empty($reqBulan)) {
        
            $statement_privacy .= " AND   TO_CHAR(A.DATE_OF_ORDER, 'mmyyyy') ='".$reqBulan.$reqCariPeriodeYear."'";
            
        }

        if (!empty($reqCariVasselName)) {
            $statement_privacy .= " AND UPPER(A.VESSEL_NAME) LIKE '%" . strtoupper($reqCariVasselName) . "%' ";
        }

         if (!empty($reqCariProject)) {
          $statement_privacy .= " AND UPPER(A.GENERAL_SERVICE) = '" . strtoupper($reqCariProject) . "'  ";
        }
        if (!empty($reqProjectName)) {
          // $statement_privacy .= "  AND EXISTS( SELECT 1 FROM OFFER_PROJECT CC WHERE CC.OFFER_ID = A.OFFER_ID AND UPPER(A.DESCRIPTION) LIKE '%" . strtoupper($reqProjectName) . "%' ) ";
            $statement_privacy .=" AND A.MASTER_REASON_ID='".$reqProjectName."'";

        }

        if(!empty($reqClassType)){
            $statement_privacy .=" AND A.TYPE_OF_VESSEL='".$reqClassType."'";
        }

         if (!empty($reqClass)) {
            $statement_privacy .= " AND UPPER(A.CLASS_OF_VESSEL) LIKE '%" . strtoupper($reqClass) . "%' ";
        }

        if (!empty($reqCariProject)) {
            $statement_privacy .= " AND UPPER(A.GENERAL_SERVICE) = '" . strtoupper($reqCariProject) . "'  ";
        }
         if (!empty($reqDestination)) {
          
            $statement_privacy .= " AND UPPER(A.DESTINATION) LIKE '%" . strtoupper($reqDestination) . "%'  ";

            // echo $statement_privacy;exit;
        }

    
        if (!empty($reqCariGlobalSearch)) {
            $statement_privacy .= " AND (  UPPER(A.VESSEL_NAME) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' 
                                    OR UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' 
                                    OR UPPER(A.SCOPE_OF_WORK) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' 
                                    OR UPPER(A.CLASS_OF_VESSEL) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' 

                                    OR UPPER(A.NO_ORDER) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' ";
            $statement_privacy .= " OR A.TOTAL_PRICE LIKE '%" . $reqCariGlobalSearch . "%' OR UPPER(A.CONTACT_PERSON) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' OR UPPER(A.DESTINATION) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%')  ";
        }

        // if (!empty($reqCariStatus) && $reqCariStatus !='ALL') {
        //     $statement_privacy .= "  AND UPPER(A.STATUS)  LIKE '%" . strtoupper($reqCariStatus) . "%'";
        // }


        if (is_numeric($reqCariStatus)) {
                if($reqCariStatus=='3'){
                    $statement_privacy .= "  AND A.STATUS  IS NULL";
                }else{
                    $statement_privacy .= "  AND A.STATUS  =".$reqCariStatus ;
                }
        }

         $_SESSION[$this->input->get("pg")."reqCariSession"] = $statement_privacy . $statement;
        $allRecord = $offer->getCountByParams(array(), $statement_privacy . $statement);
                // echo $offer->query;exit;

        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $offer->getCountByParams(array(), $statement_privacy . $statement);

        //  ECHO 'All  Record :'.$allRecordFilter.'<br>';
        // ECHO 'Display Start :'.$dsplyStart.'<br>';
        //  ECHO 'Display range :'.$dsplyRange.'<br>';
        // exit;
        $offer->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
                        // echo $offer->query;exit;

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
        while ($offer->nextRow()) {
            $status = $offer->getField('STATUS');

            $color ='yellow';
            if($status=='1' ){
                $color ='green';
            }
            if($status=='2' ){
                $color ='red';
            }
            $total_pagination  = ($dsplyStart)+$nomer;
            $penomoran = $allRecordFilter-($total_pagination);
            


            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                 if ($aColumns[$i] == "OFFER_ID") {
                    $row[] = $offer->getField($aColumns[$i]);
                } else if ($aColumns[$i] == "NO") {
                    $row[] = $penomoran;
                }
                 else if ($aColumns[$i] == "TOTAL_PRICE") {
                    $text = $offer->getField($aColumns[$i]);
                    $text = substr($text, 0, 4) . currencyToPage2(substr($text, 4));
                    $row[] = $text;
                } else {
                    $row[] = $offer->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
            $nomer++;

        }
        echo json_encode($output);
    }


    function revisi_json()
    {

        $this->load->model("Offer");
        $offer = new Offer();

        $aColumns = array(
            "OFFER_REVISI_ID","REV_VERSI","REV_DATE","REASON","AKSI"
        );

        $aColumnsAlias = array(
            "OFFER_REVISI_ID","REV_VERSI","REV_DATE","REASON","AKSI"
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
            // echo $sOrder;exit;

            //Check if there is an order by clause
            if (trim($sOrder) == "ORDER BY OFFER_REVISI_ID asc") {
                /*
                * If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
                * If there is no order by clause there might be bugs in table display.
                * No order by clause means that the db is not responsible for the data ordering,
                * which means that the same row can be displayed in two pages - while
                * another row will not be displayed at all.
                */
                $sOrder = " ORDER BY OFFER_REVISI_ID asc";
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

        $statement = "";
        $statement = " AND (UPPER(REASON) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";

        $reqId = $this->input->get("reqId");

        if($reqId == ""){
            $statement .= " AND OFFER_ID = 0";    
        } else {
            $statement .= " AND OFFER_ID = ".$reqId;
        }
        

        $allRecord = $offer->getCountByParamsRevisi(array(), $statement_privacy . $statement);
                // echo $offer->query;exit;

        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $offer->getCountByParamsRevisi(array(), $statement_privacy . $statement);

        $offer->selectByParamsMonitoringRevisi(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
                        // echo $offer->query;exit;

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

        $index = 0;
        while ($offer->nextRow()) {
            $status = $offer->getField('STATUS');

            $color ='yellow';
            if($status=='1' ){
                $color ='green';
            }
            if($status=='2' ){
                $color ='red';
            }


            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "AKSI") {
                    $id = $offer->getField("OFFER_REVISI_ID");
                    $btn_edit = '<button type="button"  class="btn btn-info"  onclick="editing('.$id.','.$index.')"><i class="fa fa-pencil-square-o fa-lg"> </i> </button>';
                    $btn_delete = '<button type="button"  class="btn btn-warning hapusi"  onclick="deleting('.$id.')"><i class="fa fa-trash-o fa-lg"> </i> </button>';
                    $row[] = $btn_edit.$btn_delete;
                } else if ($aColumns[$i] == "REV_VERSI") {
                    $row[] = $index;
                } else {
                    $row[] = $offer->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
            $index++;
        }
        echo json_encode($output);
    }

    function add_cost_project_detail($reqHppId='',$reqCostProjectId=''){
        $this->load->model('ProjectHppDetail');
        $this->load->model('CostProjectDetil');
        $projecthppdetail = new ProjectHppDetail();
        $projecthppdetail->selectByParamsMonitoring(array("A.HPP_PROJECT_ID"=>$reqHppId));
        while ( $projecthppdetail->nextRow()) {
           $cost_project_detil = new CostProjectDetil();
           $cost_project_detil->setField('HPP_PROJECT_ID',$reqHppId);
           $cost_project_detil->setField('PROJECT_HPP_DETAIL_ID',$projecthppdetail->getField("PROJECT_HPP_DETAIL_ID"));
           $cost_project_detil->setField('DESCRIPTION',$projecthppdetail->getField("DESCRIPTION"));
           $cost_project_detil->setField('COST_PROJECT_ID',$reqCostProjectId);
           $cost_project_detil->insert_form_hpp();
        }
    }

    function add_project_hpp_in_project_cost($reqId=''){
        $this->load->model('Offer');
         $this->load->model('CostProject');
          $this->load->model('CostProjectDetil');
          $CostProjectDetil = new CostProjectDetil();
         // $reqStatus              = $this->input->post("reqStatus");
         $offer = new Offer();
         $offer->selectByParamsMonitoring(array("A.OFFER_ID"=>$reqId));
         $offer->firstRow();
         $HPP_PROJECT_ID = $offer->getField("HPP_PROJECT_ID");
        $OFFER_PRICE = $offer->getField("TOTAL_PRICE");
        $OFFER_PRICES = explode(" ", $OFFER_PRICE);
         $STATUS = $offer->getField("STATUS");
        $cost_projects = new CostProject();
        $cost_projects->setField("HPP_PROJECT_ID",$HPP_PROJECT_ID);
        $cost_projects->setField("OFFER_ID",$reqId);        
        $cost_projects->add_project_id_hpp();

        $cost_projectss = new CostProject();
        $cost_projectss->selectByParamsMonitoring(array("A.OFFER_ID"=>$reqId));
        $cost_projectss->firstRow();
        $reqCostProjectId = $cost_projectss->getField("COST_PROJECT_ID");
        $reqClassOfVessel       = $this->input->post("reqClassOfVessel");
         $cost_projectsss = new CostProject();
         $totali = $cost_projectsss->getCountByParamsMonitoring(array("CAST(A.HPP_PROJECT_ID AS VARCHAR)"=>$HPP_PROJECT_ID));

         if( !empty($HPP_PROJECT_ID) && $STATUS=='1' &&  $totali ==1 ){

            $cost_project = new CostProject();
            $total = $CostProjectDetil->getCountByParamsMonitoring(array("CAST(A.HPP_PROJECT_ID AS VARCHAR)"=> $HPP_PROJECT_ID));
            $cost_project->setField("HPP_PROJECT_ID",$HPP_PROJECT_ID);
            $cost_project->setField("NO_PROJECT",$offer->getField("NO_ORDER"));
            $cost_project->setField("COMPANY_NAME",$offer->getField("COMPANY_NAME"));
            $cost_project->setField("VESSEL_NAME",$offer->getField("VESSEL_NAME"));
            $cost_project->setField("OFFER_PRICE",$OFFER_PRICES[1]);
            $cost_project->setField("OFFER_CUR",$OFFER_PRICES[0]);
            $cost_project->setField("CLASS_OF_VESSEL",$reqClassOfVessel);


            $cost_project->setField("TYPE_OF_VESSEL",$offer->getField("TYPE_OF_VESSEL"));
            $cost_project->setField("OFFER_ID",$reqId);

            if($total == 0){
            // $cost_project->insert_form_hpp();
            // $reqCostProjectId =  $cost_project->id;
            $this->add_cost_project_detail($HPP_PROJECT_ID,$reqCostProjectId);
            }else{
                 $cost_project->update_form_hpp();
            }
            $cost_project->update_price_form_offer();

         }


        
    }

    function delete_project_cost($reqId =''){
       $this->load->model('Offer');
       $this->load->model('CostProject');
       $this->load->model('CostProjectDetil');

       $offer = new Offer();
       $offer->selectByParamsMonitoring(array("CAST(A.OFFER_ID AS VARCHAR)"=>$reqId));
       $offer->firstRow();
       $HPP_PROJECT_ID = $offer->getField("HPP_PROJECT_ID");
       $STATUS = $offer->getField("STATUS");

       $cost_project = new CostProject();
       $cost_project->selectByParamsMonitoring(array("CAST(A.HPP_PROJECT_ID AS VARCHAR)"=>$HPP_PROJECT_ID));
       $cost_project->firstRow(); 
       $COST_PROJECT_ID =  $cost_project->getField("COST_PROJECT_ID");
       if(!empty($COST_PROJECT_ID) && empty($STATUS)){
       $cost_project_detil = new CostProjectDetil();
       $cost_project_detil->setField("COST_PROJECT_ID",$COST_PROJECT_ID);
       $cost_project_detil->deleteParent();

       $cost_project = new CostProject();
       $cost_project->setField("COST_PROJECT_ID",$COST_PROJECT_ID);
       $cost_project->delete();
       } 

    }

    // function add_vessel($reqCompanyId='',$reqVesselId=''){
    //     $this->load->model("Vessel");   
    //      $this->load->model("Offer");
    //     $reqId   =  $this->input->get("reqId");
    //     $reqCompanyId   =  $this->input->get("reqCompanyId");

    //      $vessel= new Vessel();
    //     $total = $vessel->getCountByParamsMonitoring(array("CAST(A.VESSEL_ID AS VARCHAR)"=>$reqVesselId,"CAST(A.COMPANY_ID AS VARCHAR)"=>$reqCompanyId));
    //     $vessel->setField('VESSEL_ID',$reqVesselId);
    //     $vessel->setField('COMPANY_ID',$reqCompanyId);
    //     $vessel->setField('NAME',$reqName);
    //     $vessel->setField('DIMENSION_L',dotToNo($reqDimensionL));
    //     $vessel->setField('DIMENSION_B',dotToNo($reqDimensionB));
    //     $vessel->setField('DIMENSION_D',dotToNo($reqDimensionD));
    //     $vessel->setField('TYPE_VESSEL',$reqVasselType_vessel);
    //     $vessel->setField('CLASS_VESSEL',$reqVasselClass_vessel);
    //     $vessel->setField('TYPE_SURVEY',$reqVasselType_survey);
        
        
    //     if($total==0){
    //             $vessel->insert_offer();
    //             $reqId = $vessel->id;
    //     }else{
    //           $vessel->update_offer();
    //     }


    // }

    function add_perubahan_detail_invoice($reqId=''){
        $this->load->model("InvoiceDetail");
        $this->load->model("Invoice");

        $reqStatus           = $this->input->post("reqStatus");

        $invoice = new Invoice();
        $invoice->selectByParamsMonitoring(array("A.OFFER_ID"=>$reqId));
        $invoice->firstRow();
        $invoice_id = $invoice->getField('INVOICE_ID');

        $reqVasselCurrency = $this->input->post("reqVasselCurrency");
        $reqTotalPrice1 = $this->input->post("reqTotalPrice1");
        $reqTotalPrice1 =dotToNo($reqTotalPrice1);
        $reqTotalPrice1s =explode('.', $reqTotalPrice1);
        $reqSubject         = $this->input->post("reqSubject");
        $reqCur =0;
        if($reqVasselCurrency=='IDR'){
            $reqCur=1;
        }

     if($reqStatus =='1'){
        $invoice_detail = new InvoiceDetail();
        $invoice_detail->setField("AMOUNT",$reqTotalPrice1s[0]);
        $invoice_detail->setField("INVOICE_ID",$invoice_id);
        $invoice_detail->setField("CURRENCY",$reqCur);
        $invoice_detail->setField("SERVICE_TYPE",$reqSubject);
        $invoice_detail->updateRelasisai();
        }

    }

    function update_data_hpp($reqId =''){
         $this->load->model("Offer");
        $this->load->model('ProjectHpp');
      
        $offer = new Offer();
        $offer->selectByParamsMonitoring(array("A.OFFER_ID"=>$reqId));
        $offer->firstRow();
        $hpp_id =  $offer->getField("HPP_PROJECT_ID");

        $project_hpp = new ProjectHpp();
        $project_hpp->setField("HPP_PROJECT_ID",$hpp_id);
        $project_hpp->setField("JENIS_PEKERJAAN",$offer->getField("GENERAL_SERVICE"));
        $project_hpp->setField("OWNER",$offer->getField("COMPANY_NAME"));
        $project_hpp->setField("COMPANY_ID",$offer->getField("COMPANY_ID"));
        $project_hpp->setField("VESSEL_ID",$offer->getField("VESSEL_ID"));
        $project_hpp->setField("NAMA",$offer->getField("VESSEL_NAME"));
        $project_hpp->setField("JENIS_KAPAL",$offer->getField("CLASS_OF_VESSEL"));
        $project_hpp->setField("DATE_PROJECT",dateToDBCheck($offer->getField("DATE_OF_ORDER")));
        $project_hpp->setField("CLASS",$offer->getField("TYPE_OF_VESSEL"));
        $project_hpp->offer_to_hpp();

    }

    function add_new()
    {
        $this->load->model("Offer");
        $this->load->model("MasterReason");
        $offer = new Offer();

        $reqId                  = $this->input->post("reqId");

        $reqAddRev              = $this->input->post("reqAddRev");
        $reqDocumentId          = $this->input->post("reqDocumentId");
        $reqDocumentPerson      = $this->input->post("reqDocumentPerson");
        $reqDestination         = $this->input->post("reqDestination");
        $reqDateOfService       = $this->input->post("reqDateOfService");
        $reqTypeOfService       = $this->input->post("reqTypeOfService");
        $reqScopeOfWork         = $this->input->post("reqScopeOfWork");
        $reqTermAndCondition    = $_POST["reqTermAndCondition"];
        $reqPaymentMethod       = $_POST["reqPaymentMethod"];
        $reqTotalPrice          = $this->input->post("reqTotalPrice");
        $reqPriceUnit          = $this->input->post("reqPriceUnit");
        $reqTotalPriceWord      = $this->input->post("reqTotalPriceWord");
        $reqStatus              = $this->input->post("reqStatus");
        $reqReason              = $this->input->post("reqReason");
        $reqNoOrder             = $this->input->post("reqNoOrder");
        $reqDateOfOrder         = $this->input->post("reqDateOfOrder");
        $reqCompanyName         = $this->input->post("reqCompanyName");
        $reqAddress             = $_POST["reqAddress"];
        $reqFaximile            = $this->input->post("reqFaximile");
        $reqEmail               = $this->input->post("reqEmail");
        $reqTelephone           = $this->input->post("reqTelephone");
        $reqHp                  = $this->input->post("reqHp");
        $reqVesselName          = $this->input->post("reqVesselName");
        $reqTypeOfVessel        = $this->input->post("reqTypeOfVessel");
        $reqClassOfVessel       = $this->input->post("reqClassOfVessel");
        $reqMaker               = $this->input->post("reqMaker");
        $reqClassAddend         = $this->input->post("reqClassAddend");
        $reqClassAddend2         = $this->input->post("reqClassAddend2");
        $reqStandBy             = $this->input->post("reqStandBy");
        $reqLandTransport       = $this->input->post("reqLandTransport");
        $reqSoDays              = $this->input->post("reqSoDays");
        $reqDimensionL          = $this->input->post("reqDimensionL");
        $reqDimensionB          = $this->input->post("reqDimensionB");
        $reqDimensionD          = $this->input->post("reqDimensionD");
        $reqTTD                 = $this->input->post("reqTTD");
        $reqPOName              = $this->input->post("reqPOName");
        $reqPODesc              = $this->input->post("reqPODesc");        
        $reqLumpsumDays         = $this->input->post("reqLumpsumDays");
        $reqMinimumCharger      = $_POST["reqMinimumCharger"];
        $reqWorkTime            = $this->input->post("reqWorkTime");


        /* GENERATE OFFER REVISI */
        if($reqAddRev == "1")
        {
            $reqOfferRevisiId = $this->db->query("SELECT GENERATE_OFFER_REVISI(".$reqId.") OFFER_REVISI_ID ")->row()->offer_revisi_id;
        }

        $stringType = '';
        for ($i = 0; $i < count($reqTypeOfService); $i++) {
            $stringType .= $reqTypeOfService[$i] . ',';
        }

        $reqVasselCurrency = $this->input->post("reqVasselCurrency");
        $reqTotalPrice1 = $this->input->post("reqTotalPrice1");
         $reqTotalPrice1 =dotToNo($reqTotalPrice1);
        $reqTotalPrice1s =explode('.', $reqTotalPrice1);

        $reqTotalPrice = $reqVasselCurrency . ' ' . $reqTotalPrice1s[0];

        $offer->setField("OFFER_ID", $reqId);
        $offer->setField("DOCUMENT_ID", $reqDocumentId);
        $offer->setField("DOCUMENT_PERSON", $reqDocumentPerson);
        $offer->setField("DESTINATION", $reqDestination);
        $offer->setField("DATE_OF_SERVICE", dateToDBCheck($reqDateOfService));
        $offer->setField("TYPE_OF_SERVICE", $stringType);
        $offer->setField("SCOPE_OF_WORK", $reqScopeOfWork);
        $offer->setField("TERM_AND_CONDITION", $reqTermAndCondition);
        $offer->setField("PAYMENT_METHOD", $reqPaymentMethod);
        $offer->setField("TOTAL_PRICE", $reqTotalPrice);
        $offer->setField("PRICE_UNIT", $reqPriceUnit);
        $offer->setField("TOTAL_PRICE_WORD", $reqTotalPriceWord);
        $offer->setField("STATUS", ValToNullDB($reqStatus));
      
        $offer->setField("NO_ORDER", $reqNoOrder);
        $offer->setField("DATE_OF_ORDER", dateToDBCheck($reqDateOfOrder));
        $offer->setField("COMPANY_NAME", $reqCompanyName);
        $offer->setField("ADDRESS", $reqAddress);
        $offer->setField("FAXIMILE", $reqFaximile);
        $offer->setField("EMAIL", $reqEmail);
        $offer->setField("TELEPHONE", $reqTelephone);
        $offer->setField("HP", $reqHp);
        $offer->setField("VESSEL_NAME", $reqVesselName);
        $offer->setField("TYPE_OF_VESSEL", $reqTypeOfVessel);
        $offer->setField("CLASS_OF_VESSEL", $reqClassOfVessel);
        // $offer->setField("MAKER", $reqMaker);
        $offer->setField("CLASS_ADDEND", $reqClassAddend);
         $offer->setField("CLASS_ADDEND2", $reqClassAddend2);
        $offer->setField("STAND_BY_RATE", $reqStandBy);
        $offer->setField("LAND_TRANSPORT", $reqLandTransport);
        $offer->setField("SO_DAYS", $reqSoDays);
        $offer->setField("VESSEL_DIMENSION_L", $reqDimensionL);
        $offer->setField("VESSEL_DIMENSION_B", $reqDimensionB);
        $offer->setField("VESSEL_DIMENSION_D", $reqDimensionD);
        $offer->setField("PENANGGUNG_JAWAB_ID", ValToNullDB($reqMaker));
        $offer->setField("PO_NAME", setQuote($reqPOName));
        $offer->setField("PO_DESCRIPTION", setQuote($reqPODesc));
        $offer->setField("LUMPSUM_DAYS", setQuote($reqLumpsumDays));
        $offer->setField("MINIMUM_CHARGER", setQuote($reqMinimumCharger));
        $offer->setField("WORK_TIME", setQuote($reqWorkTime));

        $master_reason = new MasterReason();
        $master_reason->selectByParamsMonitoring(array("CAST(A.MASTER_REASON_ID AS VARCHAR)"=>$reqReason));
        $master_reason->firstRow();

        $offer->setField("REASON", $master_reason->getField("NAMA"));
        $offer->setField("MASTER_REASON_ID", ValToNullDB($reqReason));
        if (empty($reqId)) {
            $offer->insert_new();
            $reqId = $offer->id;

            
        } else {
            $offer->update_new();

                
        }



        // FILE PO
        $this->load->library("FileHandler");
        $file = new FileHandler();
        $filesData = $_FILES["document"];
        $reqLinkFileTemp = $this->input->post("reqLinkFileTemp");

        $FILE_DIR = "uploads/offer_po/" . $reqId . "/";
        makedirs($FILE_DIR);

        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
            if ($file->uploadToDirArray('document', $FILE_DIR, $renameFile, $i)) {
                array_push($arrData, $renameFile);
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

        $offer->setField("OFFER_ID", $reqId);
        $offer->setField("FIELD", "PO_PATH");
        $offer->setField("FIELD_VALUE", $str_name_path);
        $offer->updateByField();
        // FILE PO


        /* INSERT/UPDATE COMPANY AND OFFER */
        $reqCompanyId = $this->add_company();
        $offer->setField("OFFER_ID", $reqId);
        $offer->setField("FIELD", "COMPANY_ID");
        $offer->setField("FIELD_VALUE", $reqCompanyId);
        $offer->updateByField();

        $reqVesselId = $this->add_vessel($reqCompanyId);
        $offer->setField("OFFER_ID", $reqId);
        $offer->setField("FIELD", "VESSEL_ID");
        $offer->setField("FIELD_VALUE", $reqVesselId);
        $offer->updateByField();

        $this->create_qr($reqId);
        $this->add_new_field($reqId);

        /* JIKA OFFER DEAL GENERATE OPERATION WORK REQUEST, ISSUE PO, INVOICE PROJECT, COST REQUEST, EQUIPMENT PROJECT LIST */
        if($reqStatus == "1")
        {
            $this->db->query("SELECT GENERATE_OFFER_DEAL(".$reqId.", ".$reqVesselId.")");
        }
        else if ($reqStatus == "2")
        {
            $this->db->query("SELECT CANCEL_OFFER(".$reqId.")");
        }

        

          $this->add_project_hpp_in_project_cost($reqId);
          $this->delete_project_cost($reqId);
          $this->update_data_hpp($reqId);
          $this->add_perubahan_detail_invoice($reqId);

        echo $reqId . '-Data Berhasil di simpan';
    }

    function add_revisi()
    {

        // echo 'Test Arik';exit;
        $this->load->model("Offer");
        $offer = new Offer();

        $reqId                  = $this->input->post("reqId");
        $reqRevId               = $this->input->post("reqRevId");
        $reqAddRev              = $this->input->post("reqAddRev");
        $reqDocumentId          = $this->input->post("reqDocumentId");
        $reqDocumentPerson      = $this->input->post("reqDocumentPerson");
        $reqDestination         = $this->input->post("reqDestination");
        $reqDateOfService       = $this->input->post("reqDateOfService");
        $reqTypeOfService       = $this->input->post("reqTypeOfService");
        $reqScopeOfWork         = $this->input->post("reqScopeOfWork");
        $reqTermAndCondition    = $_POST["reqTermAndCondition"];
        $reqPaymentMethod       = $_POST["reqPaymentMethod"];
        $reqTotalPrice          = $this->input->post("reqTotalPrice");
        $reqPriceUnit          = $this->input->post("reqPriceUnit");
        $reqTotalPriceWord      = $this->input->post("reqTotalPriceWord");
        $reqStatus              = $this->input->post("reqStatus");
        $reqReason              = $this->input->post("reqReason");
        $reqNoOrder             = $this->input->post("reqNoOrder");
        $reqDateOfOrder         = $this->input->post("reqDateOfOrder");
        $reqCompanyName         = $this->input->post("reqCompanyName");
        $reqAddress             = $this->input->post("reqAddress");
        $reqFaximile            = $this->input->post("reqFaximile");
        $reqEmail               = $this->input->post("reqEmail");
        $reqTelephone           = $this->input->post("reqTelephone");
        $reqHp                  = $this->input->post("reqHp");
        $reqVesselName          = $this->input->post("reqVesselName");
        $reqTypeOfVessel        = $this->input->post("reqTypeOfVessel");
        $reqClassOfVessel       = $this->input->post("reqClassOfVessel");
        $reqMaker               = $this->input->post("reqMaker");
        $reqClassAddend         = $this->input->post("reqClassAddend");
        $reqStandBy             = $this->input->post("reqStandBy");
        $reqLandTransport       = $this->input->post("reqLandTransport");
        $reqSoDays              = $this->input->post("reqSoDays");
        $reqDimensionL          = $this->input->post("reqDimensionL");
        $reqDimensionB          = $this->input->post("reqDimensionB");
        $reqDimensionD          = $this->input->post("reqDimensionD");
        $reqTTD                 = $this->input->post("reqTTD");
        $reqPOName              = $this->input->post("reqPOName");
        $reqPODesc              = $this->input->post("reqPODesc");        
        $reqLumpsumDays         = $this->input->post("reqLumpsumDays");
        $reqMinimumCharger      = $this->input->post("reqMinimumCharger");
        $reqWorkTime            = $this->input->post("reqWorkTime");


        $stringType = '';
        for ($i = 0; $i < count($reqTypeOfService); $i++) {
            $stringType .= $reqTypeOfService[$i] . ',';
        }

        $reqVasselCurrency = $this->input->post("reqVasselCurrency");
        $reqTotalPrice1 = $this->input->post("reqTotalPrice1");

        $reqTotalPrice = $reqVasselCurrency . ' ' . dotToNo($reqTotalPrice1);

        $offer->setField("OFFER_ID", $reqId);
        $offer->setField("OFFER_REVISI_ID", $reqRevId);
        $offer->setField("DOCUMENT_ID", $reqDocumentId);
        $offer->setField("DOCUMENT_PERSON", $reqDocumentPerson);
        $offer->setField("DESTINATION", $reqDestination);
        $offer->setField("DATE_OF_SERVICE", dateToDBCheck($reqDateOfService));
        $offer->setField("TYPE_OF_SERVICE", $stringType);
        $offer->setField("SCOPE_OF_WORK", $reqScopeOfWork);
        $offer->setField("TERM_AND_CONDITION", $reqTermAndCondition);
        $offer->setField("PAYMENT_METHOD", $reqPaymentMethod);
        $offer->setField("TOTAL_PRICE", $reqTotalPrice);
        $offer->setField("PRICE_UNIT", $reqPriceUnit);
        $offer->setField("TOTAL_PRICE_WORD", $reqTotalPriceWord);
        $offer->setField("STATUS", ValToNullDB($reqStatus));
        $offer->setField("REASON", $reqReason);
        $offer->setField("NO_ORDER", $reqNoOrder);
        $offer->setField("DATE_OF_ORDER", dateToDBCheck($reqDateOfOrder));
        $offer->setField("COMPANY_NAME", $reqCompanyName);
        $offer->setField("ADDRESS", $reqAddress);
        $offer->setField("FAXIMILE", $reqFaximile);
        $offer->setField("EMAIL", $reqEmail);
        $offer->setField("TELEPHONE", $reqTelephone);
        $offer->setField("HP", $reqHp);
        $offer->setField("VESSEL_NAME", $reqVesselName);
        $offer->setField("TYPE_OF_VESSEL", $reqTypeOfVessel);
        $offer->setField("CLASS_OF_VESSEL", $reqClassOfVessel);
        // $offer->setField("MAKER", $reqMaker);
        $offer->setField("CLASS_ADDEND", $reqClassAddend);
        $offer->setField("STAND_BY_RATE", $reqStandBy);
        $offer->setField("LAND_TRANSPORT", $reqLandTransport);
        $offer->setField("SO_DAYS", $reqSoDays);
        $offer->setField("VESSEL_DIMENSION_L", $reqDimensionL);
        $offer->setField("VESSEL_DIMENSION_B", $reqDimensionB);
        $offer->setField("VESSEL_DIMENSION_D", $reqDimensionD);
        $offer->setField("PENANGGUNG_JAWAB_ID", ValToNullDB($reqMaker));
        $offer->setField("PO_NAME", setQuote($reqPOName));
        $offer->setField("PO_DESCRIPTION", setQuote($reqPODesc));
        $offer->setField("LUMPSUM_DAYS", setQuote($reqLumpsumDays));
        $offer->setField("MINIMUM_CHARGER", setQuote($reqMinimumCharger));
        $offer->setField("WORK_TIME", setQuote($reqWorkTime));

        $offer->update_revisi();

        // FILE PO
        $this->load->library("FileHandler");
        $file = new FileHandler();
        $filesData = $_FILES["document"];
        $reqLinkFileTemp = $this->input->post("reqLinkFileTemp");

        $FILE_DIR = "uploads/offer_po/" . $reqId . "/";
        makedirs($FILE_DIR);

        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
            if ($file->uploadToDirArray('document', $FILE_DIR, $renameFile, $i)) {
                array_push($arrData, $renameFile);
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

        $offer->setField("OFFER_REVISI_ID", $reqRevId);
        $offer->setField("FIELD", "PO_PATH");
        $offer->setField("FIELD_VALUE", $str_name_path);
        $offer->updateRevisiByField();
        // FILE PO


        // /* INSERT/UPDATE COMPANY AND OFFER */
        // $reqCompanyId = $this->add_company();
        // $offer->setField("OFFER_ID", $reqId);
        // $offer->setField("FIELD", "COMPANY_ID");
        // $offer->setField("FIELD_VALUE", $reqCompanyId);
        // $offer->updateByField();

        // $reqVesselId = $this->add_vessel($reqCompanyId);
        // $offer->setField("OFFER_ID", $reqId);
        // $offer->setField("FIELD", "VESSEL_ID");
        // $offer->setField("FIELD_VALUE", $reqVesselId);
        // $offer->updateByField();

        // $this->create_qr($reqId);
        // $this->add_new_field($reqId);

        /* JIKA OFFER DEAL GENERATE OPERATION WORK REQUEST, ISSUE PO, INVOICE PROJECT, COST REQUEST, EQUIPMENT PROJECT LIST */
        // if($reqStatus == "1")
        // {
        //     $this->db->query("SELECT GENERATE_OFFER_DEAL(".$reqId.", ".$reqVesselId.")");
        // }
        // else if ($reqStatus == "2")
        // {
        //     $this->db->query("SELECT CANCEL_OFFER(".$reqId.")");
        // }

        // /* GENERATE OFFER REVISI */
        // if($reqAddRev == "1")
        // {
        //     $reqOfferRevisiId = $this->db->query("SELECT GENERATE_OFFER_REVISI(".$reqId.") OFFER_REVISI_ID ")->row()->offer_revisi_id;
        // }

        echo $reqId . '-Data Berhasil di simpan';
    }


    function tree_reqTechicalScope(){

        
        $reqTechicalScopeRemark     = $this->input->post("reqTechicalScopeRemark");
        $reqTechicalScopeValidatyId = $this->input->post("reqTechicalScopeId");

        $arrData = array();
        for($i=0;$i<count($reqTechicalScopeValidatyId);$i++){
            $reqTechicalScopeInc        = $this->input->post("reqTechicalScopeInc".$reqTechicalScopeValidatyId[$i]);
            $reqTechicalScopeEnc        = $this->input->post("reqTechicalScopeEnc".$reqTechicalScopeValidatyId[$i]);
            $arrData[$reqTechicalScopeValidatyId[$i]]['INC']=$reqTechicalScopeInc;
            $arrData[$reqTechicalScopeValidatyId[$i]]['ENC']=$reqTechicalScopeEnc;
            $arrData[$reqTechicalScopeValidatyId[$i]]['REMARK']= $reqTechicalScopeRemark[$i];
            $arrData[$reqTechicalScopeValidatyId[$i]]['ID']=$reqTechicalScopeValidatyId[$i];
        }

        // print_r($arrData);
        return json_encode($arrData);

    }

    function tree_reqTechicalSupport(){

      
        $reqTechicalScopeRemark     = $this->input->post("reqTechicalSupportRemark");
        $reqTechicalScopeValidatyId = $this->input->post("reqTechicalSupportId");

        $arrData = array();
        for($i=0;$i<count($reqTechicalScopeValidatyId);$i++){
             $reqTechicalScopeInc        = $this->input->post("reqTechicalSupportInc".$reqTechicalScopeValidatyId[$i]);
            $reqTechicalScopeEnc        = $this->input->post("reqTechicalSupportEnc".$reqTechicalScopeValidatyId[$i]);
            $arrData[$reqTechicalScopeValidatyId[$i]]['INC']=$reqTechicalScopeInc;
            $arrData[$reqTechicalScopeValidatyId[$i]]['ENC']=$reqTechicalScopeEnc;
            $arrData[$reqTechicalScopeValidatyId[$i]]['REMARK']= $reqTechicalScopeRemark[$i];
            $arrData[$reqTechicalScopeValidatyId[$i]]['ID']=$reqTechicalScopeValidatyId[$i];
        }
        return json_encode($arrData);

    }
    function tree_reqCommercialSupport(){

        $reqTechicalScopeRemark     = $this->input->post("reqCommercialSupportRemark");
        $reqTechicalScopeValidatyId = $this->input->post("reqCommercialSupportId");

        $arrData = array();
        for($i=0;$i<count($reqTechicalScopeValidatyId);$i++){
             $reqTechicalScopeInc        = $this->input->post("reqCommercialSupportInc".$reqTechicalScopeValidatyId[$i]);
            $reqTechicalScopeEnc        = $this->input->post("reqCommercialSupportEnc".$reqTechicalScopeValidatyId[$i]);

            $arrData[$reqTechicalScopeValidatyId[$i]]['INC']=$reqTechicalScopeInc;
            $arrData[$reqTechicalScopeValidatyId[$i]]['ENC']=$reqTechicalScopeEnc;
            $arrData[$reqTechicalScopeValidatyId[$i]]['REMARK']= $reqTechicalScopeRemark[$i];
            $arrData[$reqTechicalScopeValidatyId[$i]]['ID']=$reqTechicalScopeValidatyId[$i];
        }
        return json_encode($arrData);

    }


    function add_new_field($id){
        $this->load->model("Offer");
        $offer = new Offer();

        $reqIssueDate       = $this->input->post("reqIssueDate");
        $reqPreparedBy      = $this->input->post("reqPreparedBy");
        $reqReviewedBy      = $this->input->post("reqReviewedBy");
        $reqApprovedBy      = $this->input->post("reqApprovedBy");
        $reqIssuePurpose    = $this->input->post("reqIssuePurpose");

        $offer_total = new Offer();
        $statement = " AND TO_CHAR(A.ISSUE_DATE, 'DD-MM-YYYY') = '".$reqIssueDate."'" ; 
        $total = $offer_total->getCountByParams(array(
            "A.PREPARED_BY"=>$reqPreparedBy,
            "A.REVIEWED_BY"=>$reqReviewedBy,
            "A.APPROVED_BY"=>$reqApprovedBy,
            "A.ISSUE_PURPOSE"=>$reqIssuePurpose
             
        ),$statement);



        $reqSubject         = $this->input->post("reqSubject");
        $reqGeneralService  = $this->input->post("reqGeneralService");
        $reqGeneralServiceDetail= $this->input->post("reqGeneralServiceDetail");
        $reqProposalValidaty = $this->input->post("reqProposalValidaty");

        $reqTechicalScope   = $this->tree_reqTechicalScope();
        $reqTechicalSupport = $this->tree_reqTechicalSupport();
        $reqCommercialSupport = $this->tree_reqCommercialSupport();

        

        $offer->setField("OFFER_ID", $id);
        $offer->setField("ISSUE_DATE", dateToDBCheck($reqIssueDate));
        $offer->setField("PREPARED_BY", $reqPreparedBy);
        $offer->setField("REVIEWED_BY", $reqReviewedBy);
        $offer->setField("APPROVED_BY", $reqApprovedBy);
        $offer->setField("ISSUE_PURPOSE", $reqIssuePurpose);
        $offer->setField("SUBJECT", $reqSubject);
        $offer->setField("GENERAL_SERVICE", $reqGeneralService);
        $offer->setField("GENERAL_SERVICE_DETAIL", $reqGeneralServiceDetail);
        $offer->setField("PROPOSAL_VALIDATY", $reqProposalValidaty);
        $offer->setField("TECHICAL_SCOPE", $reqTechicalScope);
        $offer->setField("TECHICAL_SUPPORT", $reqTechicalSupport);
        $offer->setField("COMMERCIAL_SUPPORT", $reqCommercialSupport);
        $offer->update_tambahan_baru();


        $offer = new Offer();
        $offer->selectByParamsMonitoring(array("A.OFFER_ID"=>$id));
        $offer->firstRow();
        $reqRevHistory = $offer->getField("REV_HISTORY");
        $reqRevHistory = json_decode($reqRevHistory,true);
        
        $arrData = array();
        $arrData['ISSUE_DATE']=$reqIssueDate;
        $arrData['PREPARED_BY']=$reqPreparedBy;
        $arrData['REVIEWED_BY']=$reqReviewedBy;
        $arrData['APPROVED_BY']=$reqApprovedBy;
        $arrData['ISSUE_PURPOSE']=$reqIssuePurpose;
        if(count($reqRevHistory)==0){
            $dataAr = array();
            array_push($dataAr,$arrData);
            $reqRevHistory = json_encode($dataAr);
        }else{
            array_push($reqRevHistory,$arrData);
            $reqRevHistory = json_encode($reqRevHistory);
        }

         if( $total==0){   
         $offer = new Offer();
         $offer->setField("OFFER_ID",$id);
         $offer->setField("REV_HISTORY",$reqRevHistory);
         $offer->update_rev_history();
        }
    }

    function add()
    {
        // echo "adaadad";
        // exit;
        $this->load->model("Offer");
        $this->load->model("Vessel");

        $offer = new Offer();

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqNoOrder = $this->input->post("reqNoOrder");
        $reqEmail = $this->input->post("reqEmail");
        $reqDestination = $this->input->post("reqDestination");
        $reqCompanyName = $this->input->post("reqCompanyName");
        $reqVesselName = $this->input->post("reqVesselName");
        $reqTypeOfVessel = $this->input->post("reqTypeOfVessel");
        $reqFaximile = $this->input->post("reqFaximile");
        $reqTypeOfService = $this->input->post("reqTypeOfService");
        $reqTotalPrice = $this->input->post("reqTotalPrice");
        $reqScopeOfWork = $this->input->post("reqScopeOfWork");

        $offer->setField("OFFER_ID", $reqId);
        $offer->setField("NO_ORDER", $reqNoOrder);
        $offer->setField("EMAIL", $reqEmail);
        $offer->setField("DESTINATION", $reqDestination);
        $offer->setField("COMPANY_NAME", $reqCompanyName);
        $offer->setField("VESSEL_NAME", $reqVesselName);
        $offer->setField("TYPE_OF_VESSEL", $reqTypeOfVessel);
        $offer->setField("FAXIMILE", $reqFaximile);
        $offer->setField("TYPE_OF_SERVICE", $reqTypeOfService);
        $offer->setField("TOTAL_PRICE", $reqTotalPrice);
        $offer->setField("SCOPE_OF_WORK", $reqScopeOfWork);

        if ($reqMode == "insert") {
            $offer->insert();
        } else {
            $offer->update();
        }

        echo "Data berhasil disimpan.";
    }

    function add_company()
    {
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
        $text = strtoupper(str_replace(" ", '', $reqCompanyName));
        $statement = " AND UPPER(REPLACE(name,' ','')) = '".$text."'";
        $total = $company->getCountByParamsMonitoring(array("CAST(A.COMPANY_ID AS VARCHAR)"=>$reqCompanyId),$statement);

        $company = new Company();
        $company->setField("COMPANY_ID", $reqCompanyId);
        $company->setField("NAME", $reqCompanyName);
        $company->setField("ADDRESS", $reqAddress);
        $company->setField("PHONE", $reqHp);
        $company->setField("FAX", $reqFaximile);
        $company->setField("EMAIL", $reqEmail);
        $company->setField("CP1_NAME", $reqDocumentPerson);
        $company->setField("CP1_TELP", $reqTelephone);
        $status = '';




        if (empty($reqCompanyId) && $total==0) {
            $company->insert_offer();
            $reqCompanyId = $company->id;
            $status = 'baru';
        } else {
            if(!empty($reqCompanyId)){
            $company->update_offer();
            }
        }

        return $reqCompanyId;
    }

    function add_vessel($reqCompanyId)
    {
        $this->load->model("Vessel");
        $vessel               = new Vessel();

        $reqVesselId         = $this->input->post('reqVesselId');
        $reqVesselName       = $this->input->post('reqVesselName');
        $reqClassOfVessel    = $this->input->post('reqClassOfVessel');
        $reqTypeOfVessel     = $this->input->post('reqTypeOfVessel');
        $reqVesselName       = $this->input->post('reqVesselName');
        $reqClassOfVessel    = $this->input->post('reqClassOfVessel');
        $reqTypeOfVessel     = $this->input->post('reqTypeOfVessel');
        $reqDimensionL       = $this->input->post('reqDimensionL');
        $reqDimensionB       = $this->input->post('reqDimensionB');
        $reqDimensionD       = $this->input->post('reqDimensionD');

        $vessel->setField("VESSEL_ID", $reqVesselId);
        $vessel->setField("COMPANY_ID", $reqCompanyId);
        $vessel->setField("NAME", $reqVesselName);
        $vessel->setField("TYPE_VESSEL", $reqTypeOfVessel);
        $vessel->setField("CLASS_VESSEL", $reqClassOfVessel);
        $vessel->setField("DIMENSION_L", $reqDimensionL);
        $vessel->setField("DIMENSION_B", $reqDimensionB);
        $vessel->setField("DIMENSION_D", $reqDimensionD);

        if (empty($reqVesselId)) {
            $vessel->insert_offer();
            $reqVesselId = $vessel->id;
        } else {
            $vessel->update_offer();
        }

        return $reqVesselId;
    }

    function validasi_hpp(){
        $this->load->model("Offer");
        $reqId = $this->input->get('reqId');
         $offer = new Offer();
         $offer->selectByParamsMonitoring(array("A.OFFER_ID"=>$reqId));
         $offer->firstRow();
       $temp_hpp =  $offer->getField("HPP_PROJECT_ID");
       if(!empty($temp_hpp)){
              echo ' Offer tidak bisa bisa di hapus terkoneksi dengan project hpp';exit;
       }


    }
    function delete()
    {
        $this->validasi_hpp();
        $reqId = $this->input->get('reqId');
        $this->load->model("Offer");
        $offer = new Offer();

        $offer->setField("OFFER_ID", $reqId);
        if ($offer->delete())
            $arrJson["PESAN"] = "Data berhasil dihapus.";
        else
            $arrJson["PESAN"] = "Data gagal dihapus.";

        echo json_encode($arrJson);
    }

    function deleteRevisi()
    {
        $reqId = $this->input->get('reqId');
        $this->load->model("Offer");
        $offer = new Offer();

        $offer->setField("OFFER_REVISI_ID", $reqId);
        if ($offer->deleteRevisi())
            $arrJson["PESAN"] = "Data berhasil dihapus.";
        else
            $arrJson["PESAN"] = "Data gagal dihapus.";

        echo json_encode($arrJson);
    }


    function  add_attactment()
    {

        $this->load->model('Document');
        $this->load->model('Offer');

          $this->load->library("FileHandler");
        $file = new FileHandler();
        $filesData = $_FILES["document"];
        $reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
        $file->cekSize($filesData,$reqLinkFileTemp);

        $reqId              = $this->input->post('reqId');
        $reqOfferId             = $this->input->post('reqOfferId');
        $reqKeterangan      = $this->input->post('reqKeterangan');
        $reqNama            = $this->input->post('reqNama');
        $reqTipe            = $this->input->post('reqTipe');

        $name_folder = strtolower(str_replace(' ', '_', $reqTipe));

        $document = new Document();
        $document->setField("DOCUMENT_ID", $reqId);
        $document->setField("NAME", $reqNama);
        $document->setField("CATEGORY", $reqTipe);
        $document->setField("DESCRIPTION", $reqKeterangan);

        if (empty($reqId)) {
            $document->insert();
            $reqId = $document->id;
        } else {
            $document->update();
        }


        
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
                    $str_name_path .= ',' . $arrData[$i];
                }
            }
        }

        $document = new Document();
        $document->setField("DOCUMENT_ID", $reqId);
        $document->setField("PATH", $str_name_path);
        $document->updatePath();

        $offer = new Offer();
        $offer->setField("DOCUMENT_ID", $reqId);
        $offer->setField("OFFER_ID", $reqOfferId);
        $offer->update_dokument();

        echo $reqOfferId . '- Data berhasil di simpan';
    }

    function ambil_detail(){
        $this->load->model("Offer");
        $offer = new Offer();

        $reqId = $this->input->get('reqId');
        $offer->selectByParamsMonitoring(array("A.NO_ORDER"=>$reqId ));
        $offer->firstRow();

        $arrData["VESSEL_NAME"] = $offer->getField('VESSEL_NAME');
        $arrData["GENERAL_SERVICE_NAME"] = $offer->getField('GENERAL_SERVICE_NAME');
        $arrData["DESTINATION"] = $offer->getField('DESTINATION');
        $arrData["COMPANY_NAME"] = $offer->getField('COMPANY_NAME');
        $arrData["VESSEL_NAME"] = $offer->getField('VESSEL_NAME');
        $arrData["TYPE_OF_VESSEL"] = $offer->getField('TYPE_OF_VESSEL');
        $arrData["TYPE_OF_SERVICE"] = $offer->getField('TYPE_OF_SERVICE');
        $arrData["CLASS_OF_VESSEL"] = $offer->getField('CLASS_OF_VESSEL');
        
        echo json_encode($arrData);

    }


    function sending_mail()
    {

        $this->load->model('Document');
        $this->load->model("Offer");
        $offer = new Offer();
        $reqId = $this->input->get("reqId");
        $reqBahasa = $this->input->get("reqBahasa");
        $offer->selectByParamsMonitoring(array("A.OFFER_ID" => $reqId));
        $offer->firstRow();
        $email = $offer->getField('EMAIL');
        $contact = $offer->getField('DOCUMENT_PERSON');
        $reqDocId = $offer->getField('DOCUMENT_ID');
        $reqMaker = $offer->getField('MAKER');

        if (!empty($reqDocId)) {
            $document = new Document();
            $document->selectByParams(array("A.DOCUMENT_ID" => $reqDocId));
            $document->firstRow();
            $reqPath = $document->getField("PATH");
        }
        $arrayData = array();
        $files_data = explode(',',  $reqPath);
        for ($i = 0; $i < count($files_data); $i++) {
            if (!empty($files_data[$i])) {
                $texts = explode('-', $files_data[$i]);
                $str = 'uploads/attachment/' . $reqDocId . '/' . $files_data[$i];
                array_push($arrayData, $str);
            }
        }

        // print_r($arrayData);
        $this->load->model("ResikoEmail");
        $resiko_email = new ResikoEmail();
        $arrData    = array();

        $arrData['reqIds'] = $reqId;
        try {
            $this->load->library("KMail");
            $mail = new KMail();
            $body =  $this->load->view('email/offer', $arrData, true);
            if ($reqBahasa == 'eng') {
                $body =  $this->load->view('email/offer_eng', $arrData, true);
            }
            $mail->Subject  =  " [AQUAMARINE] " . $reqSubject;
            $mail->AddEmbeddedImage('uploads/offering/' . $reqId . '/offering' . $reqMaker . '.png', 'logo_mynotescode', 'barcode.png');
            $mail->Body = $body;
            $mail->AddAddress($resiko_email->sendEmail($email), $contact);
            for ($i = 0; $i < count($arrayData); $i++) {
                $mail->addAttachment($arrayData[$i]);
            }

            // $mail->MsgHTML($body);
            if (!$mail->Send()) {

                echo "Error sending: " . $mail->ErrorInfo;
            } else {
                echo "E-mail sent to " . $email . '<br>';
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        // echo '';

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
