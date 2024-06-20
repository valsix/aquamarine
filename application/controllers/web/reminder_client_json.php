<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class reminder_client_json extends CI_Controller
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
        // $this->ubah_urutan();

        $this->load->model("ReminderClient");
        $reminder_client = new ReminderClient();

        $aColumns = array(
            "REMINDER_CLIENT_ID", "URUT", "COMPANY_NAME", "COMPANY_ADDRESS", "COMPANY_CP", "COMPANY_PHONE", "COMPANY_EMAIL", "VESSEL_NAME", "TYPE_VESSEL", "CLASS_VESSEL", "IMO_NO", "PORT_REGISTER",'ANNUAL_DATE',"ANNUAL_DUE_DATE","INTERMEDIATE_DATE","INTERMEDIATE_DUE_DATE","SPECIAL_DATE","SPECIAL_DUE_DATE", "LOADTEST_DATE", "LOADTEST_DUE_DATE", "STATUS2"
        );

        $aColumnsAlias = array(
            "REMINDER_CLIENT_ID", "URUT", "B.NAME", "B.ADDRESS", "B.CP1_NAME", "B.CP1_TELP", "EMAIL", "C.NAME", "TYPE_VESSEL", "CLASS_VESSEL", "IMO_NO", "PORT_REGISTER",'ANNUAL_DATE',"ANNUAL_DUE_DATE","INTERMEDIATE_DATE","INTERMEDIATE_DUE_DATE","SPECIAL_DATE","SPECIAL_DUE_DATE", "LOADTEST_DATE", "LOADTEST_DUE_DATE", "STATUS"
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
            if (trim($sOrder) == "ORDER BY REMINDER_CLIENT_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                // $sOrder = " ORDER BY A.URUT desc";
    //         $sOrder = "   ORDER BY
    // ( A.ANNUAL_DATE IS NULL , A.INTERMEDIATE_DATE IS NULL,A.SPECIAL_DATE IS NULL , A.LOADTEST_DUE_DATE IS NULL ,A.ANNUAL_DUE_DATE IS NULL,A.INTERMEDIATE_DUE_DATE IS NULL,A.SPECIAL_DUE_DATE IS NULL,A.LOADTEST_DUE_DATE IS NULL ),
    // (A.ANNUAL_DATE  , A.INTERMEDIATE_DATE,A.SPECIAL_DATE,A.LOADTEST_DUE_DATE ,A.ANNUAL_DUE_DATE,A.INTERMEDIATE_DUE_DATE,A.SPECIAL_DUE_DATE,A.LOADTEST_DUE_DATE) DESC ";

                $sOrder = " ORDER BY D.TANGGAL IS NULL,D.TANGGAL desc";
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
        $statement = " AND (UPPER(B.NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";

        $reqExpired  = $this->input->get("reqExpired");

        
        // ECHO $statement;exit;
        $reqCariCompanyName = $this->input->get("reqCariCompanyName");
        $reqCariVasselName = $this->input->get("reqCariVasselName");
        $reqCariVasselType = $this->input->get("reqCariVasselType");
        $reqCariVasselClass = $this->input->get("reqCariVasselClass");
        $reqCariAnnualDateFrom = $this->input->get("reqCariAnnualDateFrom");
        $reqCariAnnualDateTo = $this->input->get("reqCariAnnualDateTo");
        $reqCariAnnualDueDateFrom = $this->input->get("reqCariAnnualDueDateFrom");
        $reqCariAnnualDueDateTo = $this->input->get("reqCariAnnualDueDateTo");
        $reqCariIntermediateDateFrom = $this->input->get("reqCariIntermediateDateFrom");
        $reqCariIntermediateDateTo = $this->input->get("reqCariIntermediateDateTo");
        $reqCariIntermediateDueDateFrom = $this->input->get("reqCariIntermediateDueDateFrom");
        $reqCariIntermediateDueDateTo = $this->input->get("reqCariIntermediateDueDateTo");
        $reqCariSpecialDateFrom = $this->input->get("reqCariSpecialDateFrom");
        $reqCariSpecialDateTo = $this->input->get("reqCariSpecialDateTo");
        $reqCariSpecialDueDateFrom = $this->input->get("reqCariSpecialDueDateFrom");
        $reqCariSpecialDueDateTo = $this->input->get("reqCariSpecialDueDateTo");
        $reqCariLoadtestDateFrom = $this->input->get("reqCariLoadtestDateFrom");
        $reqCariLoadtestDateTo = $this->input->get("reqCariLoadtestDateTo");
        $reqCariLoadtestDueDateFrom = $this->input->get("reqCariLoadtestDueDateFrom");
        $reqCariLoadtestDueDateTo = $this->input->get("reqCariLoadtestDueDateTo");
        

        $_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariVasselName"] = $reqCariVasselName;
        $_SESSION[$this->input->get("pg")."reqCariAnnualDateFrom"] = $reqCariAnnualDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariAnnualDateTo"] = $reqCariAnnualDateTo;
        $_SESSION[$this->input->get("pg")."reqCariAnnualDueDateFrom"] = $reqCariAnnualDueDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariAnnualDueDateTo"] = $reqCariAnnualDueDateTo;
        $_SESSION[$this->input->get("pg")."reqCariIntermediateDateFrom"] = $reqCariIntermediateDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariIntermediateDateTo"] = $reqCariIntermediateDateTo;
        $_SESSION[$this->input->get("pg")."reqCariIntermediateDueDateFrom"] = $reqCariIntermediateDueDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariIntermediateDueDateTo"] = $reqCariIntermediateDueDateTo;
        $_SESSION[$this->input->get("pg")."reqCariSpecialDateFrom"] = $reqCariSpecialDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariSpecialDateTo"] = $reqCariSpecialDateTo;
        $_SESSION[$this->input->get("pg")."reqCariSpecialDueDateFrom"] = $reqCariSpecialDueDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariSpecialDueDateTo"] = $reqCariSpecialDueDateTo;
        $_SESSION[$this->input->get("pg")."reqCariLoadtestDateFrom"] = $reqCariLoadtestDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariLoadtestDateTo"] = $reqCariLoadtestDateTo;
        $_SESSION[$this->input->get("pg")."reqCariLoadtestDueDateFrom"] = $reqCariLoadtestDueDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariLoadtestDueDateTo"] = $reqCariLoadtestDueDateTo;

        if (!empty($reqCariCompanyName)) {
            $statement_privacy .= " AND UPPER(B.NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%' ";
        }

        if (!empty($reqCariVasselName)) {
            $statement_privacy .= " AND UPPER(C.NAME) LIKE '%" . strtoupper($reqCariVasselName) . "%' ";
        }

        if (!empty($reqCariVasselType) && $reqCariVasselType != "ALL") {
            $statement_privacy .= " AND C.TYPE_VESSEL = '" . ($reqCariVasselType) . "' ";
        }

        if (!empty($reqCariVasselClass) && $reqCariVasselClass != "ALL") {
            $statement_privacy .= " AND C.CLASS_VESSEL = '" . ($reqCariVasselClass) . "' ";
        }

        if (!empty($reqCariAnnualDateFrom) && !empty($reqCariAnnualDateTo)) {
            $statement_privacy .= " AND ANNUAL_DATE BETWEEN to_date('" . $reqCariAnnualDateFrom . "', 'DD-MM-YYYY')  AND  to_date('" . $reqCariAnnualDateTo . "', 'DD-MM-YYYY') ";
        }

        if (!empty($reqCariAnnualDueDateFrom) && !empty($reqCariAnnualDueDateTo)) {
            $statement_privacy .= " AND ANNUAL_DUE_DATE BETWEEN to_date('" . $reqCariAnnualDueDateFrom . "', 'DD-MM-YYYY')  AND  to_date('" . $reqCariAnnualDueDateTo . "', 'DD-MM-YYYY') ";
        }

        if (!empty($reqCariIntermediateDateFrom) && !empty($reqCariIntermediateDateTo)) {
            $statement_privacy .= " AND INTERMEDIATE_DATE BETWEEN to_date('" . $reqCariIntermediateDateFrom . "', 'DD-MM-YYYY')  AND  to_date('" . $reqCariIntermediateDateTo . "', 'DD-MM-YYYY') ";
        }

        if (!empty($reqCariIntermediateDueDateFrom) && !empty($reqCariIntermediateDueDateTo)) {
            $statement_privacy .= " AND INTERMEDIATE_DUE_DATE BETWEEN to_date('" . $reqCariIntermediateDueDateFrom . "', 'DD-MM-YYYY')  AND  to_date('" . $reqCariIntermediateDueDateTo . "', 'DD-MM-YYYY') ";
        }

        if (!empty($reqCariSpecialDateFrom) && !empty($reqCariSpecialDateTo)) {
            $statement_privacy .= " AND SPECIAL_DATE BETWEEN to_date('" . $reqCariSpecialDateFrom . "', 'DD-MM-YYYY')  AND  to_date('" . $reqCariSpecialDateTo . "', 'DD-MM-YYYY') ";
        }

        if (!empty($reqCariSpecialDueDateFrom) && !empty($reqCariSpecialDueDateTo)) {
            $statement_privacy .= " AND SPECIAL_DUE_DATE BETWEEN to_date('" . $reqCariSpecialDueDateFrom . "', 'DD-MM-YYYY')  AND  to_date('" . $reqCariSpecialDueDateTo . "', 'DD-MM-YYYY') ";
        }

        if (!empty($reqCariLoadtestDateFrom) && !empty($reqCariLoadtestDateTo)) {
            $statement_privacy .= " AND LOADTEST_DATE BETWEEN to_date('" . $reqCariLoadtestDateFrom . "', 'DD-MM-YYYY')  AND  to_date('" . $reqCariLoadtestDateTo . "', 'DD-MM-YYYY') ";
        }

        if (!empty($reqCariLoadtestDueDateFrom) && !empty($reqCariLoadtestDueDateTo)) {
            $statement_privacy .= " AND LOADTEST_DUE_DATE BETWEEN to_date('" . $reqCariLoadtestDueDateFrom . "', 'DD-MM-YYYY')  AND  to_date('" . $reqCariLoadtestDueDateTo . "', 'DD-MM-YYYY') ";
        }

        $statement = " AND (
            UPPER(B.NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
            UPPER(C.NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%'
        )";
        if (!empty($reqExpired)) {
            $statement .= " AND (
            (A.ANNUAL_DUE_DATE < CURRENT_DATE + INTERVAL '1 MONTH') OR 
            (A.INTERMEDIATE_DUE_DATE < CURRENT_DATE + INTERVAL '1 MONTH') OR 
            (A.SPECIAL_DUE_DATE < CURRENT_DATE + INTERVAL '1 MONTH') OR 
             (A.LOADTEST_DUE_DATE < CURRENT_DATE + INTERVAL '1 MONTH')
        ) ";
        $statement_privacy='';
    }
    $_SESSION[$this->input->get("pg")."reqCariSession"] = $statement_privacy . $statement;
        $allRecord = $reminder_client->getCountByParams(array(), $statement_privacy . $statement);


                // echo $reminder_client->query;exit;

        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $reminder_client->getCountByParams(array(), $statement_privacy . $statement);

        $reminder_client->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
                        // echo $reminder_client->query;exit;
        // $query = $reminder_client->query;
        // echo $query;exit;
        // exit;
        // echo "IKI ".$_GET['iDisplayStart'];

        /*
			 * Output
			 */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $allRecord,
            "iTotalDisplayRecords" => $allRecordFilter,
            "aaData" => array(),
            "query" => $query
        );
         $nomer=0;
        while ($reminder_client->nextRow()) {
            $status              = $reminder_client->getField('STATUS');
            $STATUS_INTERMEDIATE = $reminder_client->getField('STATUS_INTERMEDIATE');
            $STATUS_ANNUAL       = $reminder_client->getField('STATUS_ANNUAL');
            $STATUS_SPECIAL      = $reminder_client->getField('STATUS_SPECIAL');




           $color ='';
            // if($STATUS_INTERMEDIATE=='RED' || $STATUS_ANNUAL =='RED' || $STATUS_SPECIAL =='RED' ){
            //     $color ='red';
            // }

           $total_pagination  = ($dsplyStart)+$nomer;
            $penomoran = $allRecordFilter-($total_pagination);
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "TOTAL_PRICE") {
                    $text = $reminder_client->getField($aColumns[$i]);
                    $text = substr($text, 0, 4) . currencyToPage2(substr($text, 4));
                    $row[] = $text;
                }  else if ($aColumns[$i] == "URUT") {
                    $row[] = $penomoran;
                }else {
                    $row[] = $reminder_client->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
            $nomer++;
        }
        echo json_encode($output);
    }

    function ubah_urutan(){
        $this->load->model("ReminderClient");
        $reminder_client = new ReminderClient();
        $TOTAL = $reminder_client->getCountByParams(array());
        $reminder_client->selectByParams(array(),-1,-1,''," ORDER BY (A.ANNUAL_DATE IS NULL),A.ANNUAL_DATE DESC,(A.INTERMEDIATE_DATE IS NULL) ,A.INTERMEDIATE_DATE DESC,
(A.SPECIAL_DATE  IS NULL),A.SPECIAL_DATE DESC,(A.LOADTEST_DUE_DATE  IS NULL),A.LOADTEST_DUE_DATE DESC");
        // echo 'Arik';exit;
        // echo $reminder_client->query;exit;
        $no =$TOTAL;
        while($reminder_client->nextRow()){
             $reminder_client2 = new ReminderClient();
             $reminder_client2->setField("URUT",$no);
             $reminder_client2->setField("REMINDER_CLIENT_ID",$reminder_client->getField('REMINDER_CLIENT_ID'));
             $reminder_client2->update_urut();
        $no--;    
        }
    }


    function add()
    {
        $this->load->model("ReminderClient");
        $reminder_client = new ReminderClient();

        $reqId                  = $this->input->post("reqId");

        $reqDocumentPerson      = $this->input->post("reqDocumentPerson");
        $reqCompanyId           = $this->input->post("reqCompanyId");
        $reqUrut                = $this->input->post("reqUrut");
        $reqVesselId            = $this->input->post("reqVesselId");
        $reqCompanyName         = $this->input->post("reqCompanyName");
        $reqAddress             = $_POST["reqAddress"];
        $reqFaximile            = $this->input->post("reqFaximile");
        $reqEmail               = $this->input->post("reqEmail");
        $reqTelephone           = $this->input->post("reqTelephone");
        $reqHp                  = $this->input->post("reqHp");
        $reqVesselName          = $this->input->post("reqVesselName");
        $reqTypeOfVessel        = $this->input->post("reqTypeOfVessel");
        $reqClassOfVessel       = $this->input->post("reqClassOfVessel");
        $reqImoNo               = $this->input->post("reqImoNo");
        $reqPortRegister        = $this->input->post("reqPortRegister");
        $reqAnnualDate          = $this->input->post("reqAnnualDate");
        $reqIntermediateDate    = $this->input->post("reqIntermediateDate");
        $reqSpecialDate         = $this->input->post("reqSpecialDate");
        $reqLoadtestDate        = $this->input->post("reqLoadtestDate");
        $reqAnnualDueDate          = $this->input->post("reqAnnualDueDate");
        $reqIntermediateDueDate    = $this->input->post("reqIntermediateDueDate");
        $reqSpecialDueDate         = $this->input->post("reqSpecialDueDate");
        $reqLoadtestDueDate        = $this->input->post("reqLoadtestDueDate");

        $reminder_client->setField("REMINDER_CLIENT_ID", $reqId);
        $reminder_client->setField("URUT", $reqUrut);
        $reminder_client->setField("COMPANY_ID", ValToNull($reqCompanyId));
        $reminder_client->setField("VESSEL_ID", ValToNull($reqVesselId));
        $reminder_client->setField("IMO_NO", $reqImoNo);
        $reminder_client->setField("PORT_REGISTER", $reqPortRegister);
        $reminder_client->setField("ANNUAL_DATE", dateToDBCheck($reqAnnualDate));
        $reminder_client->setField("INTERMEDIATE_DATE", dateToDBCheck($reqIntermediateDate));
        $reminder_client->setField("SPECIAL_DATE", dateToDBCheck($reqSpecialDate));
        $reminder_client->setField("LOADTEST_DATE", dateToDBCheck($reqLoadtestDate));
        $reminder_client->setField("ANNUAL_DUE_DATE", dateToDBCheck($reqAnnualDueDate));
        $reminder_client->setField("INTERMEDIATE_DUE_DATE", dateToDBCheck($reqIntermediateDueDate));
        $reminder_client->setField("SPECIAL_DUE_DATE", dateToDBCheck($reqSpecialDueDate));
        $reminder_client->setField("LOADTEST_DUE_DATE", dateToDBCheck($reqLoadtestDueDate));

        if (empty($reqId)) {
            $jumlah = $this->db->query("SELECT COUNT(1) JUMLAH FROM REMINDER_CLIENT WHERE URUT = '$reqUrut'")->row()->jumlah;
            if($jumlah > 0){
                echo $reqId."-No Urut $reqUrut sudah digunakan.";
                return;
            }
            $reminder_client->insert();
            $reqId = $reminder_client->id;
        } else {
            $jumlah = $this->db->query("SELECT COUNT(1) JUMLAH FROM REMINDER_CLIENT WHERE URUT = '$reqUrut' AND REMINDER_CLIENT_ID <> '$reqId'")->row()->jumlah;
            if($jumlah > 0){
                echo $reqId."-No Urut $reqUrut sudah digunakan.";
                return;
            }
            $reminder_client->update();
        }


        /* INSERT/UPDATE COMPANY AND OFFER */
        $reqCompanyId = $this->add_company();
        $reminder_client->setField("REMINDER_CLIENT_ID", $reqId);
        $reminder_client->setField("FIELD", "COMPANY_ID");
        $reminder_client->setField("FIELD_VALUE", $reqCompanyId);
        $reminder_client->updateByField();

        $reqVesselId = $this->add_vessel($reqCompanyId);
        $reminder_client->setField("REMINDER_CLIENT_ID", $reqId);
        $reminder_client->setField("FIELD", "VESSEL_ID");
        $reminder_client->setField("FIELD_VALUE", $reqVesselId);
        $reminder_client->updateByField();

        /*MENYAMAKAN NILAI START DATE DI REPORT SURVEY DENGAN INTERMEDIATE_DATE*/
        $reminder_client = new ReminderClient();
        $reminder_client->selectByParams(array("A.REMINDER_CLIENT_ID" => $reqId));
        $reminder_client->firstRow();
        $reqOfferId = $reminder_client->getField("OFFER_ID");

        if($reqOfferId != ""){
            $this->db->query("
                update dokumen_report set start_date = ".dateToDBCheck($reqIntermediateDate)." 
                where offer_id = ".$reqOfferId."
            ");   
        }
        /*MENYAMAKAN NILAI START DATE DI REPORT SURVEY DENGAN INTERMEDIATE_DATE*/

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
        $this->load->model("ReminderClient");
        $reminder_client = new ReminderClient();

        $reqIssueDate       = $this->input->post("reqIssueDate");
        $reqPreparedBy      = $this->input->post("reqPreparedBy");
        $reqReviewedBy      = $this->input->post("reqReviewedBy");
        $reqApprovedBy      = $this->input->post("reqApprovedBy");
        $reqIssuePurpose    = $this->input->post("reqIssuePurpose");

        $reminder_client_total = new ReminderClient();
        $statement = " AND TO_CHAR(A.ISSUE_DATE, 'DD-MM-YYYY') = '".$reqIssueDate."'" ; 
        $total = $reminder_client_total->getCountByParams(array(
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

        

        $reminder_client->setField("OFFER_ID", $id);
        $reminder_client->setField("ISSUE_DATE", dateToDBCheck($reqIssueDate));
        $reminder_client->setField("PREPARED_BY", $reqPreparedBy);
        $reminder_client->setField("REVIEWED_BY", $reqReviewedBy);
        $reminder_client->setField("APPROVED_BY", $reqApprovedBy);
        $reminder_client->setField("ISSUE_PURPOSE", $reqIssuePurpose);
        $reminder_client->setField("SUBJECT", $reqSubject);
        $reminder_client->setField("GENERAL_SERVICE", $reqGeneralService);
        $reminder_client->setField("GENERAL_SERVICE_DETAIL", $reqGeneralServiceDetail);
        $reminder_client->setField("PROPOSAL_VALIDATY", $reqProposalValidaty);
        $reminder_client->setField("TECHICAL_SCOPE", $reqTechicalScope);
        $reminder_client->setField("TECHICAL_SUPPORT", $reqTechicalSupport);
        $reminder_client->setField("COMMERCIAL_SUPPORT", $reqCommercialSupport);
        $reminder_client->update_tambahan_baru();


        $reminder_client = new ReminderClient();
        $reminder_client->selectByParamsMonitoring(array("A.OFFER_ID"=>$id));
        $reminder_client->firstRow();
        $reqRevHistory = $reminder_client->getField("REV_HISTORY");
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
         $reminder_client = new ReminderClient();
         $reminder_client->setField("OFFER_ID",$id);
         $reminder_client->setField("REV_HISTORY",$reqRevHistory);
         $reminder_client->update_rev_history();
        }
    }

    function add_company()
    {
        $this->load->model("Company");

        $reqCompanyName     = $this->input->post('reqCompanyName');
        $reqCompanyId       = $this->input->post('reqCompanyId');
        $reqDocumentPerson  = $this->input->post('reqDocumentPerson');
        $reqAddress         = $this->input->post('reqAddress');
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
        $status = '';
        if (empty($reqCompanyId)) {
            $company->insert_reminder();
            $reqCompanyId = $company->id;
            $status = 'baru';
        } else {
            $company->update_reminder();
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
            $vessel->insert_reminder();
            $reqVesselId = $vessel->id;
        } else {
            $vessel->update_reminder();
        }

        return $reqVesselId;
    }

    function delete()
    {
        $reqId = $this->input->get('reqId');
        $this->load->model("ReminderClient");
        $reminder_client = new ReminderClient();

        $reminder_client->setField("REMINDER_CLIENT_ID", $reqId);
        if ($reminder_client->delete())
            $arrJson["PESAN"] = "Data berhasil dihapus.";
        else
            $arrJson["PESAN"] = "Data gagal dihapus.";

        echo json_encode($arrJson);
    }


    function  add_attactment()
    {

        $this->load->model('Document');
        $this->load->model('ReminderClient');

          $this->load->library("FileHandler");
        $file = new FileHandler();
        $filesData = $_FILES["document"];
        $reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
        $file->cekSize($filesData,$reqLinkFileTemp);

        $reqId              = $this->input->post('reqId');
        $reqReminderClientId             = $this->input->post('reqReminderClientId');
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

        $reminder_client = new ReminderClient();
        $reminder_client->setField("DOCUMENT_ID", $reqId);
        $reminder_client->setField("OFFER_ID", $reqReminderClientId);
        $reminder_client->update_dokument();

        echo $reqReminderClientId . '- Data berhasil di simpan';
    }


    function sending_mail()
    {

        $this->load->model('Document');
        $this->load->model("ReminderClient");
        $reminder_client = new ReminderClient();
        $reqId = $this->input->get("reqId");
        $reqBahasa = $this->input->get("reqBahasa");
        $reminder_client->selectByParamsMonitoring(array("A.OFFER_ID" => $reqId));
        $reminder_client->firstRow();
        $email = $reminder_client->getField('EMAIL');
        $contact = $reminder_client->getField('DOCUMENT_PERSON');
        $reqDocId = $reminder_client->getField('DOCUMENT_ID');
        $reqMaker = $reminder_client->getField('MAKER');

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
