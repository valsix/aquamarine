<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class so_template_json extends CI_Controller
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
        $this->load->model("SoTemplate");
        $so_template = new SoTemplate();

        $aColumns = array("SO_TEMPLATE_ID", "NAMA","KETERANGAN","JUMLAH");

        $aColumnsAlias = array("SO_TEMPLATE_ID", "NAMA","KETERANGAN","JUMLAH");
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

        if(empty($sOrder)){
            $sOrder = "ORDER BY A.".$aColumns[0]." asc";
        }

        // $statement = " AND (UPPER(TANGGAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $so_template->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $so_template->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $so_template->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $so_template->query;exit;
     
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $allRecord,
            "iTotalDisplayRecords" => $allRecordFilter,
            "aaData" => array()
        );
        while ($so_template->nextRow()) {
            $row = array();
            $reqSoTemplateId = $so_template->getField("SO_EQUIP_ID");
            for ($i = 0; $i < count($aColumns); $i++) {
                $row[] = $so_template->getField($aColumns[$i]);
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }

    function add()
    {
        $this->load->model("SoTemplate");
        $this->load->model("SoTemplateEquip");
        $so_template = new SoTemplate();
        
        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqName = $this->input->post("reqName");
        $reqDescription = $this->input->post("reqDescription");
        $reqSoTemplateEquipId = $this->input->post("reqSoTemplateEquipId");
        $reqEquipId = $this->input->post("reqEquipId");
        $reqOutCondition = $this->input->post("reqOutCondition");
        $reqInCondition = $this->input->post("reqInCondition");
        $reqRemark = $this->input->post("reqRemark");

        $reqSoTemplateEquipIdDelete = $this->input->post("reqSoTemplateEquipIdDelete");
        $reqSoTemplateEquipIdDelete = json_decode($reqSoTemplateEquipIdDelete);

        $so_template->setField("SO_TEMPLATE_ID", $reqId);
        $so_template->setField("NAMA", $reqName);
        $so_template->setField("KETERANGAN", $reqDescription);

        if ($reqId == "") {
            $so_template->insert();
            $reqId = $so_template->id;
        } else {
            $so_template->update();
        }

        for ($i=0; $i < count($reqSoTemplateEquipIdDelete); $i++) 
        { 
            if($reqSoTemplateEquipIdDelete[$i] != "")
            {
                $so_template_equip_delete = new SoTemplateEquip();
                $so_template_equip_delete->setField("SO_TEMPLATE_EQUIP_ID", $reqSoTemplateEquipIdDelete[$i]);
                $so_template_equip_delete->delete();
            }
        }


        for ($i=0; $i < count($reqEquipId); $i++) 
        { 
            if($reqEquipId[$i] != "")
            {
                $so_template_equip = new SoTemplateEquip();
                $so_template_equip->setField("SO_TEMPLATE_EQUIP_ID", $reqSoTemplateEquipId[$i]);
                $so_template_equip->setField("SO_TEMPLATE_ID", $reqId);
                $so_template_equip->setField("EQUIP_ID", $reqEquipId[$i]);
                $so_template_equip->setField("OUT_CONDITION", $reqOutCondition[$i]);
                $so_template_equip->setField("IN_CONDITION", $reqInCondition[$i]);
                $so_template_equip->setField("REMARK", $reqRemark[$i]);
                if ($reqSoTemplateEquipId[$i] == "") {
                    $so_template_equip->insert();
                } else {
                    $so_template_equip->update();
                }
            }    
        }

        echo "Data berhasil disimpan.";
    }

    function delete()
    {
        $reqId = $this->input->get('reqId');
        $this->load->model("SoTemplate");
        $this->load->model("SoTemplateEquip");
        $so_template = new SoTemplate();

        $so_template->setField("SO_TEMPLATE_ID", $reqId);
        if ($so_template->delete()) 
        {
            $so_template_equip = new SoTemplateEquip();
            $so_template_equip->setField("SO_TEMPLATE_ID", $reqId);
            $so_template_equip->deleteParent();
            $arrJson["PESAN"] = "Data berhasil dihapus.";
        }
        else
            $arrJson["PESAN"] = "Data gagal dihapus.";

        echo json_encode($arrJson);
    }

}
