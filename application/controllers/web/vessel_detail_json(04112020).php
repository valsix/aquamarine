<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class vessel_detail_json extends CI_Controller
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
        $this->load->model("Vessel");
        $vessel = new Vessel();

        $aColumns = array("VESSEL_ID", "COMPANY_ID", "NAME", "DIMENSION_L", "DIMENSION_B", "DIMENSION_D", 
          "TYPE_VESSEL", "CLASS_VESSEL", "TYPE_SURVEY", "LOCATION_SURVEY", "CONTACT_PERSON", 
          "VALUE_SURVEY", "SURVEYOR_NAME", "SURVEYOR_PHONE", "CURRENCY", "CURRENCY_VALUE","AKSI"
            );

        $aColumnsAlias = array("VESSEL_ID", "COMPANY_ID", "NAME", "DIMENSION_L", "DIMENSION_B", "DIMENSION_D", 
          "TYPE_VESSEL", "CLASS_VESSEL", "TYPE_SURVEY", "LOCATION_SURVEY", "CONTACT_PERSON", 
          "VALUE_SURVEY", "SURVEYOR_NAME", "SURVEYOR_PHONE", "CURRENCY", "CURRENCY_VALUE","AKSI");
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
            if (trim($sOrder) == "ORDER BY A.".$aColumns[0]." asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY A.".$aColumns[0]." asc";
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
            $statement = " AND A.COMPANY_ID ='".$reqId."'";
        }

        if(empty($sOrder)){
            $sOrder = "ORDER BY A.".$aColumns[0]." asc";
        }

        // $statement = " AND (UPPER(TANGGAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $vessel->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $vessel->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $vessel->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
     
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $allRecord,
            "iTotalDisplayRecords" => $allRecordFilter,
            "aaData" => array()
        );
        $nom=0;
        while ($vessel->nextRow()) {
            $row = array();
            // currencyToPage
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "DESKRIPSI"){
                    $row[] = truncate($vessel->getField($aColumns[$i]), 2);
                }else if ($aColumns[$i] == "DEBET"){
                    $row[] = currencyToPage2($vessel->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "DIMENSION_L"){
                    $row[] = currencyToPage3($vessel->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "DIMENSION_B"){
                    $row[] = currencyToPage3($vessel->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "DIMENSION_D"){
                    $row[] = currencyToPage3($vessel->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "VALUE_SURVEY"){
                    $row[] = currencyToPage2($vessel->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "CURRENCY_VALUE"){
                    $row[] = currencyToPage2($vessel->getField($aColumns[$i]));
                }else if($aColumns[$i] == "AKSI"){
                 $btn_edit = '<button type="button"  class="btn btn-info " onclick=editing('.$nom.')  ><i class="fa fa-pencil-square-o fa-lg"> </i> </button>';
                 $btn_delete = '<button type="button"  class="btn btn-danger hapusi"  onclick="deleting('.$nom.')"><i class="fa fa-trash-o fa-lg"> </i> </button>';

                 $row[] =$btn_edit.$btn_delete;
                }


                else{
                    $row[] = $vessel->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
            $nom++;
        }
        echo json_encode($output);
    }


    function add(){
        $this->load->model('Vessel');

        $reqId                  =$this->input->post('reqId');
        $reqCompanyId           =$this->input->post('reqCompanyId');
        $reqName                =$this->input->post('reqName');
        $reqDimensionL          =$this->input->post('reqDimensionL');
        $reqDimensionB          =$this->input->post('reqDimensionB');
        $reqDimensionD          =$this->input->post('reqDimensionD');
        $reqVasselType_vessel   =$this->input->post('reqVasselType_vessel');
        $reqVasselClass_vessel  =$this->input->post('reqVasselClass_vessel');
        $reqVasselType_survey   =$this->input->post('reqVasselType_survey');
        $reqLocationSurvey      =$this->input->post('reqLocationSurvey');
        $reqContactPerson       =$this->input->post('reqContactPerson');
        $reqValueSurvey         =$this->input->post('reqValueSurvey');
        $reqVasselCurrency      =$this->input->post('reqVasselCurrency');
        $reqSurveyorName        =$this->input->post('reqSurveyorName');
        $reqSurveyorPhone       =$this->input->post('reqSurveyorPhone');

        $vessel = new Vessel();
        $vessel->setField('VESSEL_ID',$reqId);
        $vessel->setField('COMPANY_ID',$reqCompanyId);
        $vessel->setField('NAME',$reqName);
        $vessel->setField('DIMENSION_L',dotToNo($reqDimensionL));
        $vessel->setField('DIMENSION_B',dotToNo($reqDimensionB));
        $vessel->setField('DIMENSION_D',dotToNo($reqDimensionD));
        $vessel->setField('TYPE_VESSEL',$reqVasselType_vessel);
        $vessel->setField('CLASS_VESSEL',$reqVasselClass_vessel);
        $vessel->setField('TYPE_SURVEY',$reqVasselType_survey);
        $vessel->setField('LOCATION_SURVEY',$reqLocationSurvey);
        $vessel->setField('CONTACT_PERSON',$reqContactPerson);
        $vessel->setField('VALUE_SURVEY',dotToNo($reqValueSurvey));
        $vessel->setField('SURVEYOR_NAME',$reqSurveyorName);
        $vessel->setField('SURVEYOR_PHONE',$reqSurveyorPhone);
        $vessel->setField('CURRENCY',$reqVasselCurrency);
        $vessel->setField('CURRENCY_VALUE',dotToNo($reqValueSurvey));
        

        if(empty($reqId)){
                $vessel->insert();
                $reqId = $vessel->id;
        }else{
              $vessel->update();
        }
        
        echo $reqId.'-'.$reqCompanyId.'-Data berhasil di simpan';
    }
   

     function delete()
    {
        $this->load->model("Vessel");
        $vessel = new Vessel();

        $reqId = $this->input->get('reqId');

        $vessel->setField("VESSEL_ID", $reqId);
        $vessel->delete();
        
        echo 'Data berhasil di hapus';
        // echo json_encode($arrJson);
    }

    
}
