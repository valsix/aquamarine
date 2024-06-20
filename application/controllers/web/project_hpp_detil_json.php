<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class project_hpp_detil_json extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->kauth->getInstance()->hasIdentity()) {
            redirect('login');
        }

        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");
        $this->aduanId = $this->kauth->getInstance()->getIdentity()->Dokumen_id;
        $this->Nip = $this->kauth->getInstance()->getIdentity()->Nip;
        $this->nama = $this->kauth->getInstance()->getIdentity()->nama;
        $this->Aduan = $this->kauth->getInstance()->getIdentity()->Aduan;
        $this->linkFile = $this->kauth->getInstance()->getIdentity()->link_file;
        $this->createdBy = $this->kauth->getInstance()->getIdentity()->created_by;
        $this->createdDate = $this->kauth->getInstance()->getIdentity()->created_date;
        $this->updateBy = $this->kauth->getInstance()->getIdentity()->update_by;
        $this->updateDate = $this->kauth->getInstance()->getIdentity()->update_date;
    }

   function json()
    {
        $this->load->model("ProjectHppDetail");
        $cost_project_detil = new ProjectHppDetail();

        $aColumns = array("PROJECT_HPP_DETAIL_ID","HPP_PROJECT_ID","NO","CODE","DESCRIPTION","QTY","UNIT_RATE","DAYS","TOTAL","TOTALS","AKSI");

        $aColumnsAlias =  array("NO","PROJECT_HPP_DETAIL_ID","HPP_PROJECT_ID","NO","CODE","DESCRIPTION","QTY","UNIT_RATE","DAYS","TOTAL","TOTALS","AKSI");

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
            if (trim($sOrder) == "ORDER BY  A.CODE,A.".$aColumns[0]." asc") {
                /*
                * If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
                * If there is no order by clause there might be bugs in table display.
                * No order by clause means that the db is not responsible for the data ordering,
                * which means that the same row can be displayed in two pages - while
                * another row will not be displayed at all.
                */
               // $sOrder = " ORDER BY  A.CODE,A.".$aColumns[0]." asc";
			  $sOrder = "  ORDER BY  STRING_TO_ARRAY(A.CODE, '.')::int[] ASC ";
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
            $statement = " AND A.".$aColumns[1]." ='".$reqId."'";
        }

        if(empty($sOrder)){
           // $sOrder = "ORDER BY A.CODE,A.PROJECT_HPP_DETAIL_ID ASC";
			  $sOrder = "  ORDER BY  STRING_TO_ARRAY(A.CODE, '.')::int[] ASC ";
        }

        // $statement = " AND (UPPER(TANGGAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $cost_project_detil->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $cost_project_detil->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $cost_project_detil->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		
		//echo $cost_project_detil->query;exit;
     
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $allRecord,
            "iTotalDisplayRecords" => $allRecordFilter,
            "aaData" => array()
        );
        $nom=0;
        while ($cost_project_detil->nextRow()) {
            $row = array();
            $ids  = $cost_project_detil->getField($aColumns[0]);
            // currencyToPage
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "STATUS2"){
                    $ReqStatus = $cost_project_detil->getField('STATUS');
                         if ($ReqStatus == 1) {
                            $text_status = "Lunas";
                        } else {
                            $text_status = "Belum Lunas";
                        }
                        $row[] =  $text_status;
                }else if($aColumns[$i] == "COST"){
                     $row[] = conver_number($cost_project_detil->getField($aColumns[$i]));
                }else if($aColumns[$i] == "COST_VALUE"){
                     $row[] = $cost_project_detil->getField("COST");
                }else if($aColumns[$i] == "UNIT_RATE"){
                     $row[] = conver_number($cost_project_detil->getField("UNIT_RATE"));
                }else if($aColumns[$i] == "TOTALS"){
                     $row[] = conver_number($cost_project_detil->getField("TOTAL"));
                }else if($aColumns[$i] == "AKSI"){
                    $btn_edit = '<button type="button"  class="btn btn-info " onclick=editing('.$nom.')  ><i class="fa fa-pencil-square-o fa-lg"> </i> </button>';
                    $btn_delete = '<button type="button"  class="btn btn-danger hapusi"  onclick="deleting('.$nom.')"><i class="fa fa-trash-o fa-lg"> </i> </button>';
                     
                     $row[] =$btn_edit.$btn_delete;
                }

                //     $row[] = currencyToPage2($cash_report_detil->getField($aColumns[$i]));
                // }else if ($aColumns[$i] == "KREDIT"){
                //     $row[] = currencyToPage2($cash_report_detil->getField($aColumns[$i]));
                // }else if ($aColumns[$i] == "SALDO"){
                //     $row[] = currencyToPage2($cash_report_detil->getField($aColumns[$i]));
                // }
                else{
                    $row[] = $cost_project_detil->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
            $nom++;
        }
        echo json_encode($output);
    }
     function delete(){
        $reqId = $this->input->get("reqId");
        $this->load->model('ProjectHppDetail');
        $this->load->model('CostProjectDetil');
        
        $cost_project_detil = new ProjectHppDetail();
        $cost_project_detil->setField('PROJECT_HPP_DETAIL_ID',$reqId);
        $cost_project_detil->delete();

        $cost_project_detils = new CostProjectDetil();
        $cost_project_detils->setField('PROJECT_HPP_DETAIL_ID',$reqId);
        $cost_project_detils->delete_from_hpp();
        
        echo 'Data berhasil di hapus';
    }   
}