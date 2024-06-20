<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class cost_request_detail_json extends CI_Controller
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
        $this->load->model("CostRequestDetail");
        $cost_request_detail = new CostRequestDetail();

        $aColumns = array("COST_REQUEST_DETAIL_ID","COST_REQUEST_ID","KETERANGAN","COST_CODE","COST_CODE_CATEGORI","TANGGAL","EVIDANCE","AMOUNT","PROJECT","PAID_TO","AKSI","AMOUNT_VALUE","AMOUNT_VALS");
        $aColumnsAlias = array("COST_REQUEST_DETAIL_ID","COST_REQUEST_ID","KETERANGAN","COST_CODE","COST_CODE_CATEGORI","TANGGAL","EVIDANCE","AMOUNT","PROJECT","PAID_TO","AKSI","AMOUNT_VALUE","AMOUNT_VALS");
        /*
		 * Ordering
		 */

       $reqId =$this->input->get("reqId");     

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
            if (trim($sOrder) == "ORDER BY A.".$aColumns[1]." ASC") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY A.".$aColumns[1]." ASC";
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
        if(!empty($reqId)){
            $statement = " AND A.COST_REQUEST_ID ='".$reqId."'";
        }

        if(empty($sOrder)){
            $sOrder = "ORDER BY A.".$aColumns[1]." DESC";
        }

        // $statement = " AND (UPPER(TANGGAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $cost_request_detail->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $cost_request_detail->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $cost_request_detail->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // ECHO $cash_report_detil->query;exit;
     
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $allRecord,
            "iTotalDisplayRecords" => $allRecordFilter,
            "aaData" => array()
        );
        $nom=0;
        while ($cost_request_detail->nextRow()) {
            $row = array();
            // currencyToPage
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "DESKRIPSI"){
                    $row[] = truncate($cost_request_detail->getField($aColumns[$i]), 2);
                }else if ($aColumns[$i] == "DEBET"){
                    $row[] = currencyToPage2($cost_request_detail->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "KREDIT"){
                    $row[] = currencyToPage2($cost_request_detail->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "AMOUNT"){
                    $row[] = currencyToPage2($cost_request_detail->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "AMOUNT_VALS"){
                    $row[] = number_format($cost_request_detail->getField('AMOUNT'), 0,",",".");
                }else if ($aColumns[$i] == "AMOUNT_VALUE"){
                    $row[] = $cost_request_detail->getField("AMOUNT");
                }else if($aColumns[$i] == "AKSI"){
                 $btn_edit = '<button type="button"  class="btn btn-info " onclick=editing('.$nom.')  ><i class="fa fa-pencil-square-o fa-lg"> </i> </button>';
                 $btn_delete = '<button type="button"  class="btn btn-danger hapusi"  onclick="deleting('.$nom.')"><i class="fa fa-trash-o fa-lg"> </i> </button>';

                 $row[] =$btn_edit.$btn_delete;
                }


                else{
                    $row[] = $cost_request_detail->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
            $nom++;
        }
        echo json_encode($output);
    }


    function ambil_kode(){
        $this->load->model("CostRequest");
        $cost_request = new CostRequest();
         $reqId= $this->input->get("reqId");
         $cost_request->selectByParamsMonitoring(array("A.COST_REQUEST_ID " => $reqId));
         $cost_request->firstRow();
          $reqKode        = $cost_request->getField("KODE");
          echo  $reqKode;

    }   

    function add()
    {
       $this->load->model("CostRequest");
        $cost_request = new CostRequest();
    
        $this->load->model("CostRequestDetail");
        $cost_request_detail = new CostRequestDetail();

        $reqId= $this->input->post("reqId");

        
        $reqKode            = $this->input->post("reqKode");
        $reqTanggal         = $this->input->post("reqTanggal");
        $reqTotal           = $this->input->post("reqTotal");
        $reqKeterangan      = $this->input->post("reqKeterangan");
        $reqPengambilan      = $this->input->post("reqPengambilan");
        
        
        $cost_request->setField("COST_REQUEST_ID",  $reqId);
        $cost_request->setField("KODE", $reqKode);
        $cost_request->setField("TANGGAL", dateToDBCheck($reqTanggal));
        $cost_request->setField("TOTAL", dotToNo($reqTotal));
        $cost_request->setField("KETERANGAN", $reqKeterangan);
        $cost_request->setField("PENGAMBILAN", dotToNo($reqPengambilan));

        
        $status='';
        if(empty($reqId)){
            $cost_request->insert();
            $reqId = $cost_request->id;
            $status='baru';
        }else{
            $cost_request->update();
        }
       
        $reqCostRequestDetailId         = $this->input->post("reqCostRequestDetailId");
        $reqKeterangan                  = $this->input->post("reqKeterangan2");
        $reqCostCode                    = $this->input->post("reqCostCode");
        $reqCostCodeCategori            = $this->input->post("reqCostCodeCategori");
        $reqDetailTanggal               = $this->input->post("reqDetailTanggal");
        $reqEvidance                    = $this->input->post("reqEvidance");
        $reqAmount                      = $this->input->post("reqAmount");
        $reqProject                     = $this->input->post("reqProject");
        $reqPaidTo                      = $this->input->post("reqPaidTo");

        $cost_request_detail->setField("COST_REQUEST_DETAIL_ID", $reqCostRequestDetailId);
        $cost_request_detail->setField("COST_REQUEST_ID", $reqId);
        $cost_request_detail->setField("KETERANGAN", $reqKeterangan);
        $cost_request_detail->setField("COST_CODE", $reqCostCode);
        $cost_request_detail->setField("COST_CODE_CATEGORI", $reqCostCodeCategori);
        $cost_request_detail->setField("TANGGAL", dateToDBCheck($reqDetailTanggal));
        $cost_request_detail->setField("EVIDANCE", $reqEvidance);
        $cost_request_detail->setField("AMOUNT", dotToNo($reqAmount));
        $cost_request_detail->setField("PROJECT", $reqProject);
        $cost_request_detail->setField("PAID_TO", $reqPaidTo);
        if(!empty($reqCostCode) && !empty($reqAmount) ){
            if(empty($reqCostRequestDetailId)){
                $cost_request_detail->insert();
                
            }else{
                $cost_request_detail->update();
            } 
        }

        $pesan ="Data berhasil disimpan.-".$reqId."-";
      
        echo $pesan;
    }


    
    function delete(){
        $reqId = $this->input->get('reqId');
        $this->load->model("CostRequestDetail");
        $cost_request_detail = new CostRequestDetail(); 
        $cost_request_detail->setField('COST_REQUEST_DETAIL_ID',$reqId);
        $cost_request_detail->delete();
      
        echo 'Data berhasil di hapus';

    }

   
    
}
