<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class offer_popup_json extends CI_Controller
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
            "OFFER_ID", "NO_ORDER", "EMAIL", "DESTINATION", "COMPANY_NAME", "VESSEL_NAME", "TYPE_OF_VESSEL", "FAXIMILE", "TYPE_OF_SERVICE", "TOTAL_PRICE", "SCOPE_OF_WORK",'STATUS','DATE_OF_SERVICE','DESTINATION',"GENERAL_SERVICE_DETAIL","DOCUMENT_PERSON","CLASS_OF_VESSEL","COMPANY_ID","HPP_PROJECT_ID"
        );

        $aColumnsAlias = array(
            "OFFER_ID", "NO_ORDER", "EMAIL", "DESTINATION", "COMPANY_NAME", "VESSEL_NAME", "TYPE_OF_VESSEL", "FAXIMILE", "TYPE_OF_SERVICE", "TOTAL_PRICE", "SCOPE_OF_WORK",'STATUS','DATE_OF_SERVICE','DESTINATION',"GENERAL_SERVICE_DETAIL","DOCUMENT_PERSON","CLASS_OF_VESSEL","COMPANY_ID","HPP_PROJECT_ID"
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
        // $statement = " AND (UPPER(NO_ORDER) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";


        $reqCariNoOrder = $this->input->get("reqCariNoOrder");
        $reqCariDateofServiceFrom = $this->input->get("reqCariDateofServiceFrom");
        $reqCariDateofServiceTo = $this->input->get("reqCariDateofServiceTo");
        $reqCariCompanyName = $this->input->get("reqCariCompanyName");
        $reqCariPeriodeYear = $this->input->get("reqCariPeriodeYear");
        $reqCariVasselName = $this->input->get("reqCariVasselName");
        $reqCariProject = $this->input->get("reqCariProject");
        $reqCariGlobalSearch = $this->input->get("reqCariGlobalSearch");
        $reqCariStatus = $this->input->get("reqCariStatus");



        $statement = '';
        $statement_privacy .= " AND   UPPER(A.NO_ORDER) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
        OR UPPER(A.EMAIL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
        OR UPPER(A.DESTINATION) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
        OR UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' ";

        $statement_privacy .= " OR   UPPER(A.VESSEL_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
        OR UPPER(A.TYPE_OF_VESSEL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
        OR UPPER(A.FAXIMILE) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 
        OR UPPER(A.SCOPE_OF_WORK) LIKE '%" . strtoupper($_GET['sSearch']) . "%' ";
        
        $allRecord = $offer->getCountByParams(array(), $statement_privacy . $statement);
                // echo $offer->query;exit;

        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $offer->getCountByParams(array(), $statement_privacy . $statement);

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
                 if ($aColumns[$i] == "OFFER_ID") {
                    $row[] = $offer->getField($aColumns[$i]);
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
        }
        echo json_encode($output);
    }


    function add_new()
    {
        $this->load->model("Offer");
        $offer = new Offer();

        $reqId                  = $this->input->post("reqId");

        $reqDocumentId          = $this->input->post("reqDocumentId");
        $reqDocumentPerson      = $this->input->post("reqDocumentPerson");
        $reqDestination         = $this->input->post("reqDestination");
        $reqDateOfService       = $this->input->post("reqDateOfService");
        $reqTypeOfService       = $this->input->post("reqTypeOfService");
        $reqScopeOfWork         = $this->input->post("reqScopeOfWork");
        $reqTermAndCondition    = $_POST["reqTermAndCondition"];
        $reqPaymentMethod       = $_POST["reqPaymentMethod"];
        $reqTotalPrice          = $this->input->post("reqTotalPrice");
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
        $reqClassAddend               = $this->input->post("reqClassAddend");

        $stringType = '';
        for ($i = 0; $i < count($reqTypeOfService); $i++) {
            $stringType .= $reqTypeOfService[$i] . ',';
        }

        $reqVasselCurrency = $this->input->post("reqVasselCurrency");
        $reqTotalPrice1 = $this->input->post("reqTotalPrice1");

        $reqTotalPrice = $reqVasselCurrency . ' ' . dotToNo($reqTotalPrice1);

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
        $offer->setField("TOTAL_PRICE_WORD", $reqTotalPriceWord);
        $offer->setField("STATUS", $reqStatus);
        $offer->setField("REASON", $reqReason);
        $offer->setField("NO_ORDER", $reqNoOrder);
        $offer->setField("DATE_OF_ORDER", $reqDateOfOrder);
        $offer->setField("COMPANY_NAME", $reqCompanyName);
        $offer->setField("ADDRESS", $reqAddress);
        $offer->setField("FAXIMILE", $reqFaximile);
        $offer->setField("EMAIL", $reqEmail);
        $offer->setField("TELEPHONE", $reqTelephone);
        $offer->setField("HP", $reqHp);
        $offer->setField("VESSEL_NAME", $reqVesselName);
        $offer->setField("TYPE_OF_VESSEL", $reqTypeOfVessel);
        $offer->setField("CLASS_OF_VESSEL", $reqClassOfVessel);
        $offer->setField("MAKER", $reqMaker);
        $offer->setField("CLASS_ADDEND", $reqClassAddend);

        if (empty($reqId)) {
            $offer->insert_new();
            $reqId = $offer->id;
        } else {
            $offer->update_new();
        }
        $this->add_company();
        $this->create_qr($reqId);
        $this->add_new_field($reqId);

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
        $this->load->model("Vessel");


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
            $company->insert_offer();
            $reqCompanyId = $company->id;
            $status = 'baru';
        } else {
            $company->update_offer();
        }

        $vessel               = new Vessel();
        $reqVesselId         = $this->input->post('reqVesselId');
        $reqVesselName       = $this->input->post('reqVesselName');
        $reqClassOfVessel    = $this->input->post('reqClassOfVessel');
        $reqTypeOfVessel     = $this->input->post('reqTypeOfVessel');

        $vessel->setField("VESSEL_ID", $reqVesselId);
        $vessel->setField("COMPANY_ID", $reqCompanyId);
        $vessel->setField("NAME", $reqVesselName);
        $vessel->setField("TYPE_VESSEL", $reqTypeOfVessel);
        $vessel->setField("CLASS_VESSEL", $reqClassOfVessel);




        if (empty($reqVesselId)) {
            $vessel->insert_offer();
        } else {
            $vessel->update_offer();
        }
    }

    function delete()
    {
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
