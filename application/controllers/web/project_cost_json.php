<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class project_cost_json extends CI_Controller
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
        $this->load->model("Project_cost");
        $this->load->model("CostProjectDetil");
        $projectCost = new Project_cost();
        $cost_project_detil = new CostProjectDetil();
        

        $aColumns = array(
            "COST_PROJECT_ID","NO", "NO_PROJECT", "COMPANY_NAME", "VESSEL_NAME", "CLASS_OF_VESSEL","TYPE_OF_VESSEL", "TYPE_OF_SERVICE", "DATE_OF_SERVICE", "DATE_SERVICE1", "DATE_SERVICE2", "DESTINATION", "CONTACT_PERSON", "KASBON",
            "OFFER_PRICE", "REAL_PRICE", "SURVEYOR","OVER"
        );

        $aColumnsAlias = array(
            "COST_PROJECT_ID","NO", "NO_PROJECT", "COMPANY_NAME", "VESSEL_NAME", "CLASS_OF_VESSEL","TYPE_OF_VESSEL", "TYPE_OF_SERVICE", "DATE_OF_SERVICE", "DATE_SERVICE1", "DATE_SERVICE2", "DESTINATION", "CONTACT_PERSON", "KASBON",
            "OFFER_PRICE", "REAL_PRICE", "SURVEYOR","OVER"
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
            if (trim($sOrder) == "ORDER BY COST_PROJECT_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY COST_PROJECT_ID desc";
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

        $reqCariNoOrder             = $this->input->get('reqCariNoOrder');
        $reqCariCompanyName          = $this->input->get('reqCariCompanyName');
        $reqCariPeriodeYearFrom      = $this->input->get('reqCariPeriodeYearFrom');
        $reqCariPeriodeYearTo        = $this->input->get('reqCariPeriodeYearTo');
        $reqCariVasselName           = $this->input->get('reqCariVasselName');
        $reqCariGlobal               = $this->input->get('reqCariGlobal');

        $reqCariSurveyor              = $this->input->get('reqCariSurveyor');
        $reqCariOperator              = $this->input->get('reqCariOperator');
        $reqCariScopeOfWork         = $this->input->get('reqCariScopeOfWork');
        $reqCariLocation            = $this->input->get('reqCariLocation');
        $reqCariVesselClass         = $this->input->get('reqCariVesselClass');
        $reqCariVesselType         = $this->input->get('reqCariVesselType');
        $reqCariPeriodeYear    = $this->input->get('reqCariPeriodeYear');
        $reqBulan  = $this->input->get('reqBulan');
        $_SESSION[$this->input->get("pg")."reqCariNoOrder"] = $reqCariNoOrder;
        $_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariPeriodeYearFrom"] = $reqCariPeriodeYearFrom;
        $_SESSION[$this->input->get("pg")."reqCariPeriodeYearTo"] = $reqCariPeriodeYearTo;
        $_SESSION[$this->input->get("pg")."reqCariVasselName"] = $reqCariVasselName;
        $_SESSION[$this->input->get("pg")."reqCariGlobal"] = $reqCariGlobal;

        $_SESSION[$this->input->get("pg")."reqCariLocation"]        = $reqCariLocation;
         $_SESSION[$this->input->get("pg")."reqCariOperator"]        = $reqCariOperator;
        $_SESSION[$this->input->get("pg")."reqCariSurveyor"]       = $reqCariSurveyor;
        $_SESSION[$this->input->get("pg")."reqCariVesselClass"]     = $reqCariVesselClass;
        $_SESSION[$this->input->get("pg")."reqCariVesselType"]      = $reqCariVesselType;
        $_SESSION[$this->input->get("pg")."reqCariScopeOfWork"]     = $reqCariScopeOfWork;
           $_SESSION[$this->input->get("pg")."reqCariPeriodeYear"]     = $reqCariPeriodeYear;
            $_SESSION[$this->input->get("pg")."reqBulan"]     = $reqBulan;


        if (!empty($reqCariCompanyName)) {
            $statement .= " AND UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%' ";
        }
        if (!empty($reqCariVasselName)) {
            $statement .= " AND UPPER(A.VESSEL_NAME) LIKE '%" . strtoupper($reqCariVasselName) . "%' ";
        }
        if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
            $statement .= " AND DATE_SERVICE1 BETWEEN  TO_DATE('" . $reqCariPeriodeYearFrom . "', 'DD-MM-YYYY')  AND TO_DATE('" . $reqCariPeriodeYearTo . "', 'DD-MM-YYYY') ";
        }
        if (!empty($reqCariNoOrder)) {
            $statement .= " AND UPPER(A.NO_PROJECT) LIKE '%" . strtoupper($reqCariNoOrder) . "%' ";
        }
        if (!empty($reqCariLocation)) {
            $statement .= " AND UPPER(A.DESTINATION) LIKE '%" . strtoupper($reqCariLocation) . "%' ";
        }
        if (!empty($reqCariSurveyor)) {
            $statement .= " AND UPPER(A.SURVEYOR) LIKE '%" . strtoupper($reqCariSurveyor) . "%' ";
        }
          if (!empty($reqCariOperator)) {
            $statement .= " AND UPPER(A.OPERATOR) LIKE '%" . strtoupper($reqCariOperator) . "%' ";
        }
        if (!empty($reqCariScopeOfWork)) {
            $statement .= " AND UPPER(A.TYPE_OF_SERVICE) LIKE '%" . strtoupper($reqCariScopeOfWork) . "%' ";
        }
        if (!empty($reqCariVesselClass)) {
            $statement .= " AND UPPER(A.CLASS_OF_VESSEL) LIKE '%" . strtoupper($reqCariVesselClass) . "%' ";
        }
         if (!empty($reqCariVesselType)) {
            $statement .= " AND UPPER(A.TYPE_OF_VESSEL) LIKE '%" . strtoupper($reqCariVesselType) . "%' ";
        }
    if(!empty($reqCariPeriodeYear) && !empty($reqBulan) ){
            // $mtgl_awal = '01-01-'.$reqCariPeriodeYear ; 
            // $mtgl_akhir = '31-12-'.$reqCariPeriodeYear ; 
             if($reqCariPeriodeYear != 'All Year'){
            //     $statement_privacy .= " AND A.DATE_OF_SERVICE BETWEEN TO_DATE('".$mtgl_awal."','dd-mm-yyyy') AND  TO_DATE('".$mtgl_akhir."','dd-mm-yyyy')";  
                 $statement_privacy .= " AND   TO_DATE(A.DATE_SERVICE1,'mmyyyy')='".$reqBulan.$reqCariPeriodeYear."' ";  
             }

        }
        

        if($_GET['sSearch'] != ""){
            $statement .= " AND (
                UPPER(A.NO_PROJECT) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
                UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
                UPPER(A.OPERATOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
                UPPER(A.SURVEYOR) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
                UPPER(A.TYPE_OF_SERVICE) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
                UPPER(A.CLASS_OF_VESSEL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
                UPPER(A.TYPE_OF_VESSEL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
                UPPER(A.DESTINATION) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR

                UPPER(A.VESSEL_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%'
            ) ";
        }
        $_SESSION[$this->input->get("pg")."reqCariSession"] = $statement_privacy . $statement;

        $allRecord = $projectCost->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;
        // exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $projectCost->getCountByParams(array(), $statement_privacy . $statement);

        $projectCost->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $projectCost->query; exit();
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
        while ($projectCost->nextRow()) {
            $ids = $projectCost->getField($aColumns[0]);
            $total_pagination  = ($dsplyStart)+$nomer;
            $penomoran = $allRecordFilter-($total_pagination);
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NO_PROJECT") {
                    $row[] = $projectCost->getField($aColumns[$i]);
                }   else if ($aColumns[$i] == "NO") {
                    $row[] = $penomoran;
                }else if ($aColumns[$i] == "KASBON") {
                    $row[] = currencyToPage2($projectCost->getField($aColumns[$i]));
                } else if ($aColumns[$i] == "OFFER_PRICE") {
                    $row[] = currencyToPage2($projectCost->getField($aColumns[$i]));
                }  else if ($aColumns[$i] == "OVER") {
                    $cost_project_detil = new CostProjectDetil();
                    $cost_project_detil->selectByParamsMonitoring(array("A.COST_PROJECT_ID"=>$ids));
                    $total = 0;
                    while ( $cost_project_detil->nextRow()) {
                        $total += ifZero2($cost_project_detil->getField("COST"));
                    }

                    $row[] = currencyToPage2($total);
                } else if ($aColumns[$i] == "REAL_PRICE") {
                    $row[] = currencyToPage2($projectCost->getField($aColumns[$i]));
                } else if ($aColumns[$i] == "DATE_OF_SERVICE") {
                    $date1 =  $projectCost->getField('DATE1');
                    $date2 =  $projectCost->getField('DATE2');
                    $row[] = getTglBlnTahun($date1) . ' - ' . getTglBlnTahun($date2);
                } else {
                    $row[] = $projectCost->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
             $nomer++;
        }
        echo json_encode($output);
    }

    function add2()
    {

        $this->load->model('CostProject');
        $this->load->model('CostProjectDetil');
        $cost_project = new CostProject();
        $reqId = $this->input->post("reqId");

        $reqNoProject           = $this->input->post("reqNoProject");
        $reqVesselName          = $this->input->post("reqVesselName");
        $reqTypeOfVessel        = $this->input->post("reqTypeOfVessel");
        $reqTypeOfService       = $this->input->post("reqTypeOfService");
        $reqDateService1        = $this->input->post("reqDateService1");
        $reqDateService2        = $this->input->post("reqDateService2");
        $reqDestination         = $this->input->post("reqDestination");
        $reqCompanyName         = $this->input->post("reqCompanyName");
        $reqContactPerson       = $this->input->post("reqContactPerson");
        $reqKasbon              = $this->input->post("reqKasbon");
        $reqOfferPrice          = $this->input->post("reqOfferPrice");
        $reqRealPrice           = $this->input->post("reqRealPrice");
        $reqSurveyor            = $this->input->post("reqSurveyor");
        $reqOperator            = $this->input->post("reqOperator");
        $reqKasbonCur            = $this->input->post("reqKasbonCur");
        $reqOfferCur            = $this->input->post("reqOfferCur");
        $reqRealCur             = $this->input->post("reqRealCur");
        $reqClassOfVessel       = $this->input->post("reqClassOfVessel");
        
        $reqServiceOrderId            = $this->input->post("reqServiceOrderId");
        $reqAddService            = $this->input->post("reqAddService");


        $cost_project->setField("COST_PROJECT_ID", $reqId);
        $cost_project->setField("NO_PROJECT", $reqNoProject);
        $cost_project->setField("VESSEL_NAME", $reqVesselName);
        $cost_project->setField("TYPE_OF_VESSEL", $reqTypeOfVessel);
        $cost_project->setField("TYPE_OF_SERVICE", $reqTypeOfService);
        $cost_project->setField("DATE_SERVICE1", dateToDBCheck($reqDateService1));
        $cost_project->setField("DATE_SERVICE2", dateToDBCheck($reqDateService2));
        $cost_project->setField("DESTINATION", $reqDestination);
        $cost_project->setField("COMPANY_NAME", $reqCompanyName);
        $cost_project->setField("CONTACT_PERSON", $reqContactPerson);
        $cost_project->setField("KASBON", dotToNo($reqKasbon));
        $cost_project->setField("OFFER_PRICE", dotToNo($reqOfferPrice));
        $cost_project->setField("REAL_PRICE", dotToNo($reqRealPrice));
        $cost_project->setField("SURVEYOR", $reqSurveyor);
        $cost_project->setField("OPERATOR", $reqOperator);
        $cost_project->setField("KASBON_CUR", $reqKasbonCur);
        $cost_project->setField("OFFER_CUR", $reqOfferCur);
        $cost_project->setField("CLASS_OF_VESSEL", $reqClassOfVessel);
        $cost_project->setField("REAL_CUR", $reqRealCur);
        $cost_project->setField("SERVICE_ORDER_ID", ValToNullDB($reqServiceOrderId));
        $cost_project->setField("ADD_SERVICE", $reqAddService);

        // echo 'sadasd'.normal_angka($reqOfferPrice);
        // exit;

        $status = '';
        if (empty($reqId)) {
            $cost_project->insert();
            $reqId = $cost_project->id;
            $status = 'baru';
        } else {
            $cost_project->update();
        }


        $reqCostProjectDetilId      = $this->input->post('reqCostProjectDetilId');
        $reqCostDate                = $this->input->post('reqCostDate');
        $reqCost                    = $this->input->post('reqCost');
        $reqDescription             = $this->input->post('reqDescription');
        $reqStatus                  = $this->input->post('reqStatus');
        $reqCurrencys               = $this->input->post('reqCurrencys');
        $reqCode               = $this->input->post('reqCode');


        $cost_project_detil = new CostProjectDetil();
        $cost_project_detil->setField('COST_PROJECT_DETIL_ID', $reqCostProjectDetilId);
        $cost_project_detil->setField('COST_PROJECT_ID', $reqId);
        $cost_project_detil->setField('COST_DATE', dateToDBCheck($reqCostDate));
        $cost_project_detil->setField('DESCRIPTION', $reqDescription);
        $cost_project_detil->setField('COST', dotToNo($reqCost));
        $cost_project_detil->setField('STATUS', $reqStatus);
        $cost_project_detil->setField('CURRENCY', $reqCurrencys);
        $cost_project_detil->setField('CODE', $reqCode);

        $CostProject2 = new CostProject();
        $CostProject2->selectByParamsMonitoring(array("A.COST_PROJECT_ID"=>$reqId));
        $CostProject2->firstRow();
        $reqHppProjectId = $CostProject2->getField('HPP_PROJECT_ID');
        if(!empty($reqHppProjectId)){
            $cost_project_detil->setField('COST_DATE','CURRENT_DATE');
            $cost_project_detil->setField('STATUS', '1');
            $reqCostDate='ada';
        }
        
        if (!empty($reqCost) && !empty($reqCostDate)) {

            if (empty($reqCostProjectDetilId)) {
                $cost_project_detil->insert();
                $reqCostProjectDetilId =  $cost_project_detil->id;
            } else {

                $cost_project_detil->update();
            }
            if(!empty($reqHppProjectId)){
               
            $cost_project_detil2 = new CostProjectDetil();
            $cost_project_detil2->setField('COST_PROJECT_DETIL_ID', $reqCostProjectDetilId);
            $cost_project_detil2->setField('CODE', $reqCode);
            $cost_project_detil2->setField('COST', dotToNo($reqCost));
            $cost_project_detil2->update_cost_code();
            }
        }
        if(!empty($reqOfferId)){
           $cost_projects = new CostProject();
           $cost_projects->setField("OFFER_ID",$reqOfferId);
           $cost_projects->setField("COST_PROJECT_ID",$reqId);
           $cost_projects->updateCostOffer();
        }

        $cost_projects = new CostProject();
        $cost_projects->setField("NO_REPORT", $reqNoProject);
        $cost_projects->setField("FINISH_DATE", dateToDBCheck($reqDateService2));
         $cost_projects->setField("START_DATE", dateToDBCheck($reqDateService1));
        $cost_projects->setField("COST_SURYEVOR", $reqSurveyor);
        $cost_projects->setField("COST_OPERATOR", $reqOperator);
        $cost_projects->update_surveyor_operator_report();

        $pesan = 'Data berhasil di simpan-';
        // if ($status == 'baru') {
        //     $pesan .= $reqId."-";
        // }
        $pesan .= $reqId."-";
        $pesan .= dotToNo($reqRealPrice)."-";
        echo $pesan;
    }

    function add()
    {
        // echo "adaadad";
        // exit;
        $this->load->model("Project_cost");
        $projectCost = new Project_cost();

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqNoProject = $this->input->post("reqNoProject");
        $reqVesselName = $this->input->post("reqVesselName");
        $reqTypeOfVessel = $this->input->post("reqTypeOfVessel");
        $reqTypeOfService = $this->input->post("reqTypeOfService");
        $reqDateService1 = $this->input->post("reqDateService1");
        $reqDateService2 = $this->input->post("reqDateService2");
        $reqDestination = $this->input->post("reqDestination");
        $reqCompanyName = $this->input->post("reqCompanyName");
        $reqContactPerson = $this->input->post("reqContactPerson");
        $reqKasbon = $this->input->post("reqKasbon");
        $reqOfferPrice = $this->input->post("reqOfferPrice");
        $reqRealPrice = $this->input->post("reqRealPrice");
        $reqSurveyor = $this->input->post("reqSurveyor");
        $reqOperator = $this->input->post("reqOperator");

        $projectCost->setField("COST_PROJECT_ID", $reqId);
        $projectCost->setField("NO_PROJECT", $reqNoProject);
        $projectCost->setField("VESSEL_NAME", $reqVesselName);
        $projectCost->setField("TYPE_OF_VESSEL", $reqTypeOfVessel);
        $projectCost->setField("TYPE_OF_SERVICE", $reqTypeOfService);
        $projectCost->setField("DATE_SERVICE1", dateToDBCheck($reqDateService1));
        $projectCost->setField("DATE_SERVICE2", dateToDBCheck($reqDateService2));
        $projectCost->setField("DESTINATION", $reqDestination);
        $projectCost->setField("COMPANY_NAME", $reqCompanyName);
        $projectCost->setField("CONTACT_PERSON", $reqContactPerson);
        $projectCost->setField("KASBON", $reqKasbon);
        $projectCost->setField("OFFER_PRICE", $reqOfferPrice);
        $projectCost->setField("REAL_PRICE", $reqRealPrice);
        $projectCost->setField("SURVEYOR", $reqSurveyor);
        $projectCost->setField("OPERATOR", $reqOperator);

        if ($reqMode == "insert") {
            $projectCost->insert();
        } else {
            $projectCost->update();
        }



        echo "Data berhasil disimpan.";
    }

   function validasi_hpp(){
         $this->load->model("CostProject");
        $reqId = $this->input->get('reqId');
         $offer = new CostProject();
         $offer->selectByParamsMonitoring(array("A.COST_PROJECT_ID"=>$reqId));
         $offer->firstRow();
        $temp_hpp =  $offer->getField("HPP_PROJECT_ID");
       if(!empty($temp_hpp)){
              echo ' Project Cost tidak bisa bisa di hapus terkoneksi dengan project hpp';exit;
       }


    }

    function delete()
    {
        $this->validasi_hpp();  
        $reqId = $this->input->get('reqId');
        $this->load->model("Project_cost");
        $this->load->model("Project_cost_detil");

        $projectCost = new Project_cost();
        $Project_cost_detil = new Project_cost_detil();
        $Project_cost_detil->setField("COST_PROJECT_ID", $reqId);

        $projectCost->setField("COST_PROJECT_ID", $reqId);
        if ($projectCost->delete()) {
            $Project_cost_detil->deleteParent();

            // $arrJson["PESAN"] = "Data berhasil dihapus.";
        } else {
            // $arrJson["PESAN"] = "Data gagal dihapus.";
        }

        echo 'Data berhasil di hapus';
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
