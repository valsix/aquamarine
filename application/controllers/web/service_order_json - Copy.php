<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class service_order_json extends CI_Controller
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

        $this->load->model("Service_order");
        $serviceOrder = new Service_order();

        $aColumns = array(
            "SO_ID", "NO_ORDER", "PROJECT_NAME", "COMPANY_NAME", "VESSEL_NAME", "VESSEL_TYPE", "SURVEYOR", "DESTINATION", "SERVICE", "DATE_OF_START", "DATE_OF_FINISH",
            "EQUIPMENT", "DATE_OF_SERVICE"
        );
        $aColumnsAlias = array(
            "SO_ID", "NO_ORDER", "PROJECT_NAME", "COMPANY_NAME", "VESSEL_NAME", "VESSEL_TYPE", "SURVEYOR", "DESTINATION", "SERVICE", "DATE_OF_START", "DATE_OF_FINISH",
            "EQUIPMENT", "DATE_OF_SERVICE"
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
            if (trim($sOrder) == "ORDER BY A.SO_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY A.SO_ID asc";
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
        $reqCariNoOrder          = $this->input->get('reqCariNoOrder');
        $reqCariPeriodeYearFrom  = $this->input->get('reqCariPeriodeYearFrom');
        $reqCariPeriodeYearTo    = $this->input->get('reqCariPeriodeYearTo');
        $reqCariCompanyName      = $this->input->get('reqCariCompanyName');
        $reqCariPeriodeYear      = $this->input->get('reqCariPeriodeYear');
        $reqCariVasselName       = $this->input->get('reqCariVasselName');
        $reqCariProject          = $this->input->get('reqCariProject');
        $reqCariGlobal           = $this->input->get('reqCariGlobal');    


         if(!empty($reqCariNoOrder)){
                $statement_privacy .= " AND  UPPER(A.NO_ORDER) LIKE '%".strtoupper($reqCariNoOrder)."%' ";
         } 
         if(!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo) ){
                $statement_privacy .= " AND A.DATE_OF_SERVICE BETWEEN TO_DATE('".$reqCariPeriodeYearFrom."','dd-mm-yyyy') AND  TO_DATE('".$reqCariPeriodeYearTo."','dd-mm-yyyy')";      
         }   
         
         if(!empty($reqCariCompanyName)){
                $statement_privacy .= " AND  UPPER(A.COMPANY_NAME) LIKE '%".strtoupper($reqCariCompanyName)."%' ";
         }  
         if(!empty($reqCariPeriodeYear)){
                          $mtgl_awal = '01-01-'.$reqCariPeriodeYear ; 
                           $mtgl_akhir = '31-12-'.$reqCariPeriodeYear ; 
                           if($reqCariPeriodeYear != 'All Year'){
                                 $statement_privacy .= " AND A.DATE_OF_SERVICE BETWEEN TO_DATE('".$mtgl_awal."','dd-mm-yyyy') AND  TO_DATE('".$mtgl_akhir."','dd-mm-yyyy')";  
                           }
              
         }
         if(!empty($reqCariVasselName)){
                $statement_privacy .= " AND UPPER(A.VESSEL_NAME) LIKE '%".strtoupper($reqCariVasselName)."%' ";
         } 
          if(!empty($reqCariGlobal)){
                $statement_privacy .= " AND  UPPER(A.SERVICE)   '%".strtoupper($reqCariGlobal)."%' ";
         } 
         if(!empty($sOrder)){
             $sOrder = ' ORDER BY A.'.$aColumns[0].' DESC';
         }   

        $statement = " AND (UPPER(NO_ORDER) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $serviceOrder->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $serviceOrder->getCountByParams(array(), $statement_privacy . $statement);

        $serviceOrder->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $serviceOrder->query;
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

        while ($serviceOrder->nextRow()) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NO_ORDER")
                    $row[] = truncate($serviceOrder->getField($aColumns[$i]), 2);
                else
                    $row[] = $serviceOrder->getField($aColumns[$i]);
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }


    function add_new(){

        $this->load->model("ServiceOrder");
        $service_order = new ServiceOrder();

       $reqId              = $this->input->post('reqId');     
       $reqProjectName     = $this->input->post('reqProjectName');
       $reqNoOrder         = $this->input->post('reqNoOrder');
       $reqCompanyName     = $this->input->post('reqCompanyName');
       $reqVesselName      = $this->input->post('reqVesselName');
       $reqVesselType      = $this->input->post('reqVesselType');
       $reqSurveyor        = $this->input->post('reqSurveyor');
       $reqDestination     = $this->input->post('reqDestination');
       $reqService         = $this->input->post('reqService');
       $reqDateOfStart     = $this->input->post('reqDateOfStart');
       $reqDateOfFinish    = $this->input->post('reqDateOfFinish');
       $reqTransport       = $this->input->post('reqTransport');
       $reqEquipment       = $this->input->post('reqEquipment');
       // $reqObligation      = $this->input->post('reqObligation');
        $reqObligation      = $_POST['reqObligation'];
       $reqDateOfService   = $this->input->post('reqDateOfService');
       $reqPicEquip        = $this->input->post('reqPicEquip');
       $reqContactPerson   = $this->input->post('reqContactPerson');

       $service_order->setField("SO_ID", $reqId);
       $service_order->setField("PROJECT_NAME", $reqProjectName);
       $service_order->setField("NO_ORDER", $reqNoOrder);
       $service_order->setField("COMPANY_NAME", $reqCompanyName);
       $service_order->setField("VESSEL_NAME", $reqVesselName);
       $service_order->setField("VESSEL_TYPE", $reqVesselType);
       $service_order->setField("SURVEYOR", $reqSurveyor);
       $service_order->setField("DESTINATION", $reqDestination);
       $service_order->setField("SERVICE", $reqService);
       $service_order->setField("DATE_OF_START", dateToDBCheck($reqDateOfStart));
       $service_order->setField("DATE_OF_FINISH", dateToDBCheck($reqDateOfFinish));
       $service_order->setField("TRANSPORT", $reqTransport);
       $service_order->setField("EQUIPMENT", $reqEquipment);
       $service_order->setField("OBLIGATION", $reqObligation);
       $service_order->setField("DATE_OF_SERVICE", $reqDateOfService);
       $service_order->setField("PIC_EQUIP", $reqPicEquip);
       $service_order->setField("CONTACT_PERSON", $reqContactPerson);

       if(empty($reqId)){
            $service_order->insert();
            $reqId =$service_order->id;
       }else{
            $service_order->update();    
       }
       echo $reqId.'-Data berhasil di simpan';
    }

    function add_company(){
        $this->load->model("Company");
        $reqCompanyName     = $this->input->post('reqCompanyName');
        $reqCompanyId       = $this->input->post('reqCompanyId');
        $reqDocumentPerson  = $this->input->post('reqDocumentPerson');

        $company = new Company();
        $company->setField("COMPANY_ID", $reqCompanyId);
        $company->setField("NAME", $reqCompanyName);
        $company->setField("CP1_NAME", $reqDocumentPerson);


        if(empty($reqCompanyId)){
            $company->insert_owr();
            $reqCompanyId = $company->id;

        }else{
           $company->update_owr();
       }
   }


    function add()
    {
        // echo "adaadad";
        // exit;
        $this->load->model("Service_order");
        $serviceOrder = new Service_order();

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqNoOrder = $this->input->post("reqNoOrder");
        $reqProjectName = $this->input->post("reqProjectName");
        $reqCompanyName = $this->input->post("reqCompanyName");
        $reqVesselName = $this->input->post("reqVesselName");
        $reqVesseltype = $this->input->post("reqVesseltype");
        $reqSurveyor = $this->input->post("reqSurveyor");
        $reqDestination = $this->input->post("reqDestination");
        $reqService = $this->input->post("reqService");
        $reqDateOfStart = $this->input->post("reqDateOfStart");
        $reqDateOfFinish = $this->input->post("reqDateOfFinish");
        $reqEquipment = $this->input->post("reqEquipment");
        $reqDateOfService = $this->input->post("reqDateOfService");

        $serviceOrder->setField("SO_ID", $reqId);
        $serviceOrder->setField("NO_ORDER", $reqNoOrder);
        $serviceOrder->setField("PROJECT_NAME", $reqProjectName);
        $serviceOrder->setField("COMPANY_NAME", $reqCompanyName);
        $serviceOrder->setField("VESSEL_NAME", $reqVesselName);
        $serviceOrder->setField("VESSEL_TYPE", $reqVesseltype);
        $serviceOrder->setField("SURVEYOR", $reqSurveyor);
        $serviceOrder->setField("DESTINATION", $reqDestination);
        $serviceOrder->setField("SERVICE", $reqService);
        $serviceOrder->setField("DATE_OF_START", $reqDateOfStart);
        $serviceOrder->setField("DATE_OF_FINISH", $reqDateOfFinish);
        $serviceOrder->setField("EQUIPMENT", $reqEquipment);
        $serviceOrder->setField("DATE_OF_SERVICE", $reqDateOfService);

        if ($reqMode == "insert") {
            $serviceOrder->insert();
        } else {
            $serviceOrder->update();
        }

        echo "Data berhasil disimpan.";
    }

    function delete()
    {
        $reqId = $this->input->get('reqId');
        $this->load->model("Service_order");
        $serviceOrder = new Service_order();

        $serviceOrder->setField("SO_ID", $reqId);
        if ($serviceOrder->delete())
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
