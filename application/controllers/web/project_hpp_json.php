<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class project_hpp_json extends CI_Controller
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
        $this->load->model("ProjectHpp");
        $projectCost = new ProjectHpp();

        $aColumns = array("HPP_PROJECT_ID","NO","REF_NO","OWNER","NAMA","PEKERJAAN_NAMA","JENIS_KAPAL","CLASS","SWL","LOA","LOKASI_PEKERJAAN","REF_NO","ESTIMASI_PEKERJAAN","COST_FROM_AMDI","PROFIT","STATUS_APPROVED");

        $aColumnsAlias =  array("HPP_PROJECT_ID","NO","REF_NO","OWNER","NAMA","PEKERJAAN_NAMA","JENIS_KAPAL","CLASS","SWL","LOA","LOKASI_PEKERJAAN","REF_NO","ESTIMASI_PEKERJAAN","COST_FROM_AMDI","PROFIT","STATUS_APPROVED");

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
            if (trim($sOrder) == "ORDER BY HPP_PROJECT_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY HPP_PROJECT_ID desc";
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
        // $reqCariCompanyName          = $this->input->get('reqCariCompanyName');
        $reqCariPeriodeYearFrom      = $this->input->get('reqCariPeriodeYearFrom');
        $reqCariPeriodeYearTo        = $this->input->get('reqCariPeriodeYearTo');
        $reqCariPeriodeYear = $this->input->get('reqCariPeriodeYear');
        // $reqCariVasselName           = $this->input->get('reqCariVasselName');
        // $reqCariGlobal               = $this->input->get('reqCariGlobal');

        $reqCariPekerjaan        = $this->input->get('reqCariPekerjaan');
        $reqCariClass            = $this->input->get('reqCariClass');
        $reqCariTypeVessel        = $this->input->get('reqCariTypeVessel');
        $reqCariOwner           = $this->input->get('reqCariOwner');
         $reqCariVessel           = $this->input->get('reqCariVessel');
          $reqCariLocation           = $this->input->get('reqCariLocation');
         

        $_SESSION[$this->input->get("pg")."reqCariNoOrder"] = $reqCariNoOrder;
        // $_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariPeriodeYearFrom"] = $reqCariPeriodeYearFrom;
        $_SESSION[$this->input->get("pg")."reqCariPeriodeYearTo"] = $reqCariPeriodeYearTo;
        // $_SESSION[$this->input->get("pg")."reqCariVasselName"] = $reqCariVasselName;
        // $_SESSION[$this->input->get("pg")."reqCariGlobal"] = $reqCariGlobal;

        $_SESSION[$this->input->get("pg")."reqCariPekerjaan"] = $reqCariPekerjaan;
        $_SESSION[$this->input->get("pg")."reqCariClass"] = $reqCariClass;
        $_SESSION[$this->input->get("pg")."reqCariTypeVessel"] = $reqCariTypeVessel;
        $_SESSION[$this->input->get("pg")."reqCariOwner"] = $reqCariOwner;
        $_SESSION[$this->input->get("pg")."reqCariVessel"] = $reqCariVessel;
        $_SESSION[$this->input->get("pg")."reqCariLocation"] = $reqCariLocation;
        $_SESSION[$this->input->get("pg")."reqCariPeriodeYear"] = $reqCariPeriodeYear;
     
        if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
            $reqCariPeriodeYearFromS = explode('-', $reqCariPeriodeYearFrom);
            $reqCariPeriodeYearFrom =$reqCariPeriodeYearFromS[2].'-'.$reqCariPeriodeYearFromS[1].'-'.$reqCariPeriodeYearFromS[0];
            $reqCariPeriodeYearToS = explode('-', $reqCariPeriodeYearTo);
              $reqCariPeriodeYearTo =$reqCariPeriodeYearToS[2].'-'.$reqCariPeriodeYearToS[1].'-'.$reqCariPeriodeYearToS[0];
          $statement .= " AND ( A.DATE_PROJECT  >=   TO_DATE('" . $reqCariPeriodeYearFrom . "', 'yyyy-mm-dd')  AND A.DATE_PROJECT <=  TO_DATE('" . $reqCariPeriodeYearTo . "', 'yyyy-mm-dd')  )";
      }

      if (!empty($reqCariNoOrder)) {
          $statement .= " AND UPPER(A.REF_NO) LIKE '%" . strtoupper($reqCariNoOrder) . "%' ";
      }
       if (!empty($reqCariPeriodeYear)) {
          $statement .= " AND TO_CHAR(A.DATE_PROJECT,'YYYY') = '" . strtoupper($reqCariPeriodeYear) . "' ";
      }
      if (!empty($reqCariClass)) {
          $statement .= " AND UPPER(A.JENIS_KAPAL) LIKE '%" . strtoupper($reqCariClass) . "%' ";
      }
       if (!empty($reqCariVessel)) {
          $statement .= " AND UPPER(A.NAMA) LIKE '%" . strtoupper($reqCariVessel) . "%' ";
      }
      if (!empty($reqCariTypeVessel)) {
          $statement .= " AND UPPER(A.CLASS) LIKE '%" . strtoupper($reqCariTypeVessel) . "%' ";
      }
      if (!empty($reqCariOwner)) {
          $statement .= " AND UPPER(A.OWNER) LIKE '%" . strtoupper($reqCariOwner) . "%' ";
      }
      if (!empty($reqCariPekerjaan)) {
          $statement .= " AND UPPER(A.JENIS_PEKERJAAN) = '" . strtoupper($reqCariPekerjaan) . "' ";
      }
        if (!empty($reqCariLocation)) {
          $statement .= " AND A.MASTER_LOKASI_ID = '" . strtoupper($reqCariLocation) . "' ";
      }
      
        if($_GET['sSearch'] != ""){
            $statement .= " AND (
                UPPER(A.NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
                
                UPPER(A.LOCATION) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
                 UPPER(A.JENIS_KAPAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
                 UPPER(A.CLASS) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
                 UPPER(A.OWNER) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR
                 EXISTS (SELECT 1 FROM SERVICES CC WHERE CAST(CC.SERVICES_ID AS VARCHAR)=A.JENIS_PEKERJAAN AND CC.NAMA LIKE '%".strtoupper($_GET['sSearch'])."%')
            ) ";
        }

          $_SESSION[$this->input->get("pg")."reqCariSession"] = $statement_privacy . $statement;

        $allRecord = $projectCost->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
        // echo $allRecord;
        // exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $projectCost->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $projectCost->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
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
            $row = array();
            $total_pagination  = ($dsplyStart)+$nomer;
      $penomoran = $allRecordFilter-($total_pagination);
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NO_PROJECT") {
                    $row[] = truncate($projectCost->getField($aColumns[$i]), 2);
                
          } else if ($aColumns[$i] == "NO") {
                    $row[] = $penomoran;
                } else if ($aColumns[$i] == "DATE_PROJECT") {
                    $MONTH = getFormattedDateEng($projectCost->getField($aColumns[$i]));
                     $MONTH = explode(' ',  $MONTH);
                    $row[] =$MONTH[1] ;
                } else if ($aColumns[$i] == "COST_FROM_AMDI") {
                    $row[] = currencyToPage2($projectCost->getField($aColumns[$i]));
                } else if ($aColumns[$i] == "PROFIT") {
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


    function update_cost($reqId=''){
        $reqProjectHppDetailId= $this->input->post("reqProjectHppDetailId");
         $reqCostFromAmdi=$this->input->post("reqCostFromAmdi");
          $reqQty= $this->input->post("reqQty");
          $reqQty = ifZero2($reqQty);
		  // ECHO  $reqQty.'aRIK';

           $project_hpp_detail2 = new ProjectHppDetail();
           $project_hpp_detail2->selectByParamsMonitoring(array("CAST(A.HPP_PROJECT_ID AS VARCHAR)"=>$reqId,"A.HPP_MASTER_ID"=>'28'));
        // echo  $project_hpp_detail2->query;
           while($project_hpp_detail2->nextRow()){
              // $reqHppMaster =  $project_hpp_detail2->getField("HPP_MASTER_ID");
              $reqQtss = $project_hpp_detail2->getField('QTY');
           }

           $project_hpp_detail3 = new ProjectHppDetail();
           $project_hpp_detail3->selectByParamsMonitoring(array("CAST(A.HPP_PROJECT_ID AS VARCHAR)"=>$reqId));
		   // echo $project_hpp_detail3->query;
         
           while($project_hpp_detail3->nextRow()){
              $reqHppMaster =  $project_hpp_detail3->getField("HPP_MASTER_ID");
              // if($reqHppMaster =='28'){
              //     $reqQtss      = $project_hpp_detail3->getField('QTY');
              // }
            
           }
           

          if($reqHppMaster != '28'){
			  if(!empty($reqQtss)){
            $reqQty = $reqQtss;
          }
		  }
		// ECHO  $reqQty.'aRIK 2';
          $this->load->model("ProjectHppDetail");
          $project_hpp_detail = new ProjectHppDetail();
          $persenCal = '0.24';
          $totals = ifZero($reqCostFromAmdi);
          $total = ($persenCal/100)* $totals;
          $total = $reqQty*$total;
         $project_hpp_detail->setField("QTY",$reqQty);
          $project_hpp_detail->setField("TOTAL",$total);
          $project_hpp_detail->setField("HPP_MASTER_ID",'28');
          $project_hpp_detail->setField("HPP_PROJECT_ID",$reqId);
          $project_hpp_detail->update_cost();
           
          
    }

    function add_detail_from_master_hpp($reqId=''){
            $reqCostFromAmdi=$this->input->post("reqCostFromAmdi");

            $this->load->model("HppMaster");
            $this->load->model("ProjectHppDetail");
            $hpp_master = new HppMaster();
            $hpp_master->selectByParamsMonitoring(array());
            while ( $hpp_master->nextRow()) {
                $qty =0;
                $unit_rate=0;
                $days=0;
                $total=0;
                if($hpp_master->getField("HPP_MASTER_ID")=='28'){
                    $qty=1;
                    $unit_rate=1;
                    $days=1;
                    $persenCal = '0.24';
                    $totals = ifZero($reqCostFromAmdi);
                    $total = ($persenCal/100)* $totals;

                }
                $project_hpp_detail = new ProjectHppDetail();
                $project_hpp_detail->setField("HPP_PROJECT_ID", $reqId);
                $project_hpp_detail->setField("CODE", $hpp_master->getField("CODE"));
                $project_hpp_detail->setField("DESCRIPTION", $hpp_master->getField("KETERANGAN"));
                $project_hpp_detail->setField("QTY", $qty);
                $project_hpp_detail->setField("UNIT_RATE",$unit_rate );
                $project_hpp_detail->setField("DAYS", $days);
                $project_hpp_detail->setField("TOTAL", $total);
                $project_hpp_detail->setField("HPP_MASTER_ID", $hpp_master->getField("HPP_MASTER_ID"));
                $project_hpp_detail->insert();
            }
    }
    function approval(){
        $reqId = $this->input->get("reqId");
        $this->load->model("ProjectHpp");
        $project_hpp = new ProjectHpp();
        $project_hpp->setField('HPP_PROJECT_ID',$reqId);
        $project_hpp->approval();
        echo ' Data berhasil di approval';
        
    }
    function cancel_approval(){
        $reqId = $this->input->get("reqId");
        $this->load->model("ProjectHpp");
        $project_hpp = new ProjectHpp();
        $project_hpp->setField('HPP_PROJECT_ID',$reqId);
        $project_hpp->cancel_approval();
        echo ' Data berhasil di cancel approval';
        
    }
    
    function add()
    {
        // echo "adaadad";
        // exit;
        $this->load->model("ProjectHpp");
        $this->load->model("Offer");
        $this->load->model("ProjectHppDetail");
        $this->load->model("CostProject");
        $this->load->model("CostProjectDetil");
        $this->load->model("MasterLokasi");
        
        $this->load->model("Company");
        $project_hpp = new ProjectHpp();
        $project_hpp_detail = new ProjectHppDetail();
        $offer = new Offer();
       
        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqHppProjectId= $this->input->post("reqHppProjectId");
        $reqNama= $this->input->post("reqNama");
        $reqLoa= $this->input->post("reqLoa");
        $reqLocation= $this->input->post("reqLocation");
        $reqRefNo= $this->input->post("reqRefNo");
        $reqBulanHpp= $this->input->post("reqBulanHpp");
        $reqDateProject= $this->input->post("reqDateProject");
        $reqForApproved= $this->input->post("reqForApproved");
        $reqSwl= $this->input->post("reqSwl");
        



        $project_hpp->setField("HPP_PROJECT_ID", $reqId);
        $project_hpp->setField("NAMA", $reqNama);
        $project_hpp->setField("LOA", $reqLoa);
         $project_hpp->setField("SWL", $reqSwl);
        $project_hpp->setField("LOCATION", $reqLocation);
        $project_hpp->setField("REF_NO", $reqRefNo);
        $project_hpp->setField("FOR_APPROVED", $reqForApproved);
        $project_hpp->setField("BULAN_HPP", $reqBulanHpp);
        $project_hpp->setField("DATE_PROJECT", $reqDateProject);
        if(empty($reqId)){
            $project_hpp->insert();
            $reqId = $project_hpp->id;
            $this->add_detail_from_master_hpp($reqId);
        }else{
             $project_hpp->update();
        }

       

         
        $reqProjectHppDetailId= $this->input->post("reqProjectHppDetailId");
        $reqHppProjectId=  $reqId;
        $reqCode= $this->input->post("reqCode");
        $reqDescription= $this->input->post("reqDescription");
        $reqQty= $this->input->post("reqQty");
        $reqQty = ifZero2($reqQty);
        $reqUnitRate = $this->input->post("reqUnitRate");
        $reqUnitRate = normal_angka($reqUnitRate);
        $reqDays= $this->input->post("reqDays");
        $reqDays = ifZero2($reqDays);
        $reqTotal= ($reqQty*$reqUnitRate) * $reqDays;


        $project_hpp_detail->setField("PROJECT_HPP_DETAIL_ID", $reqProjectHppDetailId);
        $project_hpp_detail->setField("HPP_PROJECT_ID", $reqHppProjectId);
        $project_hpp_detail->setField("CODE", $reqCode);
        $project_hpp_detail->setField("DESCRIPTION", $reqDescription);
        $project_hpp_detail->setField("QTY", $reqQty);
        $project_hpp_detail->setField("UNIT_RATE",$reqUnitRate );
        $project_hpp_detail->setField("DAYS", $reqDays);
        $project_hpp_detail->setField("TOTAL", $reqTotal);
        $ids_detail= '';
        if(empty($reqProjectHppDetailId)){

            if(!empty($reqCode)){    
            $project_hpp_detail->insert();
            $ids_detail = $project_hpp_detail->id;
            }
        }else{
            $project_hpp_detail->update();
        }
          $this->update_cost($reqId);


        $reqJenisPekerjaan = $this->input->post("reqJenisPekerjaan");
        $reqOwner = $this->input->post("reqOwner");
        $reqCompanyId = $this->input->post("reqCompanyId");
        $reqVesselId = $this->input->post("reqVesselId");
        
        $reqJenisKapal= $this->input->post("reqJenisKapal");
        $reqFlag= $this->input->post("reqFlag");
        $reqClass= $this->input->post("reqClass");
        $reqEstimasiPekerjaan= $this->input->post("reqEstimasiPekerjaan");
        $reqLokasiPekerjaan= $this->input->post("reqLokasiPekerjaan");


        $reqCostFromAmdi=$this->input->post("reqCostFromAmdi");
        $reqCostFromAmdi=    ifZero($reqCostFromAmdi);
        $reqAgent= $this->input->post("reqAgent");
        $reqAgent=    ifZero($reqAgent);
        $reqCostToClient= ($reqCostFromAmdi + $reqAgent);
      

        $project_hpp_detail = new ProjectHppDetail();
        $project_hpp_detail->selectByParamsMonitoring(array("A.HPP_PROJECT_ID"=>$reqId));
        $total_detail=0;
        while($project_hpp_detail->nextRow()){
              $total_detail +=$project_hpp_detail->getField("TOTAL");
        }

        // $profit = (int)(($reqCostToClient) - $total_detail);
        $profit = (int)(($reqCostFromAmdi) - $total_detail);
        $reqProfit= $profit;
        

        // $reqPrescentage=  ($reqProfit / $reqCostToClient)*100;
        $reqPrescentage=  ($reqProfit / $reqCostFromAmdi)*100;
        $reqPrescentage = round($reqPrescentage,2);

        $master_lokasi = new MasterLokasi();
        $master_lokasi->selectByParamsMonitoring(array("CAST(A.MASTER_LOKASI_ID AS VARCHAR)"=>$reqLokasiPekerjaan));
        $master_lokasi->firstRow();
        $nama_lokasi =  $master_lokasi->getField('NAMA');

        $project_hpp->setField("JENIS_PEKERJAAN", $reqJenisPekerjaan);
        $project_hpp->setField("OWNER", $reqOwner);
        $project_hpp->setField("JENIS_KAPAL", $reqJenisKapal);
        $project_hpp->setField("COMPANY_ID", $reqCompanyId);
        $project_hpp->setField("VESSEL_ID", $reqVesselId);
        $project_hpp->setField("FLAG", $reqFlag);
        $project_hpp->setField("CLASS", $reqClass);
        $project_hpp->setField("ESTIMASI_PEKERJAAN", $reqEstimasiPekerjaan);
        $project_hpp->setField("LOKASI_PEKERJAAN", $nama_lokasi );
          $project_hpp->setField("MASTER_LOKASI_ID", ValToNullDB($reqLokasiPekerjaan));
        $project_hpp->setField("COST_FROM_AMDI", $reqCostFromAmdi);
        $project_hpp->setField("AGENT", $reqAgent);
        $project_hpp->setField("COST_TO_CLIENT", $reqCostToClient);
        $project_hpp->setField("PROFIT", $reqProfit);
        $project_hpp->setField("STATUS_CHANGE", 'HPP');
        $project_hpp->setField("PRESCENTAGE", $reqPrescentage);
        $project_hpp->update_part2();

        $offer = new Offer();
        $total = $offer->getCountByParams(array("A.HPP_PROJECT_ID"=>$reqId));
        $offer = new Offer();
        $offer->setField("HPP_PROJECT_ID",$reqId);
        $offer->setField("NO_ORDER",$reqRefNo);
        if($total==0){
            $offer->insert_offer_from_hpp();
        }
         $company = new Company();
         $company->selectByParamsMonitoring(array("CAST(A.COMPANY_ID AS VARCHAR)"=>$reqCompanyId));
         $company->firstRow();

         $this->load->model("Vessel");
        
         


        $offer->setField("COMPANY_NAME",$reqOwner);
        $offer->setField("COMPANY_ID",$reqCompanyId);
        $offer->setField("ADDRESS",$company->getField('ADDRESS'));
        $offer->setField("FAXIMILE",$company->getField('FAX'));
        $offer->setField("EMAIL",$company->getField('EMAIL'));
        $offer->setField("TELEPHONE",$company->getField('CP1_TELP'));        
        $offer->setField("HP",$company->getField('PHONE'));
        $offer->setField("DOCUMENT_PERSON",$company->getField('CP1_NAME'));
        $offer->setField("DATE_OF_ORDER",dateToDBCheck($reqDateProject));
        $offer->setField("VESSEL_NAME",$reqNama);
        $offer->setField("TYPE_OF_VESSEL",$reqClass);
        $offer->setField("CLASS_OF_VESSEL",$reqJenisKapal);
        $offer->setField("GENERAL_SERVICE",$reqJenisPekerjaan);
        $offer->setField("VESSEL_ID",$reqVesselId);
        $offer->setField("DESTINATION",$nama_lokasi);
         $offer->setField("TOTAL_PRICE",'IDR '.$reqCostFromAmdi);
        $offer->update_offer_from_hpp();
        $vessel = new Vessel();
        $vessel->selectByParamsMonitoring(array("CAST(A.VESSEL_ID AS VARCHAR)"=>$reqVesselId));
        $vessel->firstRow();
        if(!empty($vessel->getField("DIMENSION_L") || 
           !empty($vessel->getField("DIMENSION_B") || 
           !empty($vessel->getField("DIMENSION_B"))
         ))){


        $offer->setField("VESSEL_DIMENSION_L",$vessel->getField("DIMENSION_L"));
        $offer->setField("VESSEL_DIMENSION_B",$vessel->getField("DIMENSION_B"));
        $offer->setField("VESSEL_DIMENSION_D",$vessel->getField("DIMENSION_D"));
        $offer->update_vessel_form_hpp();
        }


        $offer = new Offer();
        $offer->selectByParamsMonitoring(array("A.HPP_PROJECT_ID"=>$reqId));
        $offer->firstRow();
        $reqOfferId = $offer->getField("OFFER_ID");

        $cost_project = new CostProject();
        $total = $cost_project->getCountByParamsMonitoring(array("A.HPP_PROJECT_ID"=> $reqId));
        $cost_project->setField("HPP_PROJECT_ID",$reqId);
         $cost_project->setField("NO_PROJECT",$reqRefNo);
        $cost_project->setField("COMPANY_NAME",$reqOwner);
        $cost_project->setField("VESSEL_NAME",$reqNama);
        $cost_project->setField("TYPE_OF_VESSEL",$reqClass);
        $cost_project->setField("OFFER_ID",$reqOfferId);
         
        if($total == 0){
            // $cost_project->insert_form_hpp();
        }else{
            // $cost_project->update_form_hpp();
        }



        $cost_project = new CostProject();
        $cost_project->selectByParamsMonitoring(array("A.HPP_PROJECT_ID"=>$reqId));
        $cost_project->firstRow();
        $reqCostProjectId =  $cost_project->getField('COST_PROJECT_ID');

        $cost_project_detil = new CostProjectDetil();
        $cost_project_detil->setField('HPP_PROJECT_ID',$reqId);
        $cost_project_detil->setField('PROJECT_HPP_DETAIL_ID',$reqProjectHppDetailId);
        $cost_project_detil->setField('DESCRIPTION',$reqCode.' - '.$reqDescription);
        $cost_project_detil->setField('COST_PROJECT_ID',$reqCostProjectId);
        
        
        if(!empty($reqCostProjectId)){
        if(empty($reqProjectHppDetailId)){

            if(!empty($reqCode)){
                 $cost_project_detil->setField('PROJECT_HPP_DETAIL_ID',$ids_detail);
               
            $cost_project_detil->insert_form_hpp();
			//$PESAN = 'TAMBAHKAN KE HPP';
            }
        }else{
            $cost_project_detil->update_from_hpp();
			//$PESAN = 'TAMBAHKAN KE HPP';
        }

        }

        $this->load->library("FileHandler");
        $file = new FileHandler();
        $filesData = $_FILES["document"];
        $reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
        $file->cekSize($filesData,$reqLinkFileTemp);
        $name_folder  = 'hpp_file';
        $FILE_DIR = "uploads/" . $name_folder . "/" . $reqId . "/";
        makedirs($FILE_DIR);

        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile = $reqId . ' - ' . getExtension($filesData['name'][$i]);
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
      
        $projecthpp = new ProjectHpp();
        $projecthpp->setField('HPP_PROJECT_ID',$reqId);
        $projecthpp->setField('PATH_FILE',$str_name_path);
        $projecthpp->update_patch();
        echo $reqId."-Data berhasil disimpan.".$PESAN;
    }



    function delete()
    {
        $reqId = $this->input->get('reqId');
        $this->load->model("ProjectHpp");
        $this->load->model("ProjectHppDetail");
        $this->load->model("Offer");
        $this->load->model("CostProject");
        $this->load->model("CostProjectDetil");
        $this->load->model("OfferRevisi");
        $this->load->model("OfferProject");

        
        
        $Offers = new Offer();
        $Offers->selectByParamsMonitoring(array("A.HPP_PROJECT_ID"=>$reqId));
        $Offers->firstRow();
        $reqOfferId =  $Offers->getField("OFFER_ID");
        $projectCost = new ProjectHpp();
        $offer = new Offer();
         $costprojectdetil = new CostProjectDetil();
        $costproject = new CostProject();
        $ProjectHppDetail = new ProjectHppDetail();
        $ProjectHppDetail->setField("HPP_PROJECT_ID", $reqId);
        $offer->setField("HPP_PROJECT_ID", $reqId);
        $costproject->setField("HPP_PROJECT_ID", $reqId);
        $costprojectdetil->setField("HPP_PROJECT_ID", $reqId);
        $projectCost->setField("HPP_PROJECT_ID", $reqId);
        $offer_revisi = new OfferRevisi();
        $offerproject = new OfferProject();
        $offer_revisi->setField("OFFER_ID",$reqOfferId);
        $offerproject->setField("OFFER_ID",$reqOfferId);

        // $costproject = new CostProject();

        if ($projectCost->delete()) {
            $ProjectHppDetail->deleteParent();
            $offer->deleteOfferHpp();
             $costprojects = new CostProject();
            $costprojects->selectByParamsMonitoring(array("CAST(A.HPP_PROJECT_ID AS VARCHAR)"=>$reqId));
            // echo  $costprojects->query;exit;
            $costprojects->firstRow();
            $COST_PROJECT_ID = $costprojects->getField("COST_PROJECT_ID");
            $costprojectdetils = new CostProjectDetil();
            $costprojectdetils->setField("COST_PROJECT_ID", $COST_PROJECT_ID);
            $costprojectdetils->deleteParent();
            
            $costproject->deleteHpp();


              $costprojectdetil->delete_from_hpp_parent();
            if(!empty($reqOfferId)){
                $offer_revisi->delete();
                $offerproject->deleteParent();
            }
            
            
            

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
