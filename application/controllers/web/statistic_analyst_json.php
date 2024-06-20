<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class statistic_analyst_json extends CI_Controller
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
        $this->load->model("StatisticOfferTahun");
        $statisticAnalyst = new StatisticOfferTahun();

        $aColumns = array(
            "ID", "KETERANGAN","STATUS"
        );

        $aColumnsAlias = array(
            "ID", "KETERANGAN","STATUS"
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
            if (trim($sOrder) == "ORDER BY ID desc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY ID asc";
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

        $reqCariName  =  $this->input->get('reqCariName');
        $_SESSION[$this->input->get("pg")."reqCariName"] = $reqCariName;

        if (!empty($reqCariName)) {
            $statement_privacy .= " AND UPPER(A.KETERANGAN) LIKE '%" . strtoupper($reqCariName) . "%'";
        }

        $statement = " AND (UPPER(A.KETERANGAN) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $statisticAnalyst->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $statisticAnalyst->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $statisticAnalyst->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $statisticAnalyst->query;
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

        while ($statisticAnalyst->nextRow()) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "TIPE"){
                    $row[] = $statisticAnalyst->getField($aColumns[$i]);
                }
                else
                    $row[] = $statisticAnalyst->getField($aColumns[$i]);
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }


    function add_new()
    {
        $this->load->model("Statistic");
        $this->load->model("StatisticDetil");

        $statistic = new Statistic();
        $statisticdetil = new StatisticDetil();
        $reqId              = $this->input->post("reqId");
        $reqHeader          = $this->input->post("reqHeader");
        $reqTahun          = $this->input->post("reqTahun");

        $reqDescription     = $this->input->post("reqDescription");
        $reqValue           = $this->input->post("reqValue");
        $reqColor           = $this->input->post("reqColor");

        $statistic->setField("STATISTIC_ID", $reqId);
        $statisticdetil->setField("STATISTIC_ID", $reqId);
        $statistic->setField("DESCRIPTION", $reqHeader);
        $statistic->setField("TAHUN", $reqTahun);
        $statisticdetil->delete_parent();
        if (empty($reqId)) {
            $statistic->insert();
            $reqId = $statistic->id;
        } else {
            $statistic->update();
        }

        for ($i = 0; $i < count($reqDescription); $i++) {
            $statistic_detil = new StatisticDetil();
            $statistic_detil->setField("STATISTIC_ID", $reqId);
            $statistic_detil->setField("DESCRIPTION", $reqDescription[$i]);
            $statistic_detil->setField("VALUE", $reqValue[$i]);
            $statistic_detil->setField("COLOR", $reqColor[$i]);
            $statistic_detil->insert();
        }
        echo $reqId . '- Data berhasil di simpan';
    }

    function add()
    {
        $this->load->model("Statistic_analyst");
        $statisticAnalyst = new Statistic_analyst();

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqDescription = $this->input->post("reqDescription");
        $reqValue = $this->input->post("reqValue");
        $reqColor = $this->input->post("reqColor");

        $statisticAnalyst->setField("STATISTIC_DETIL_ID", $reqId);
        $statisticAnalyst->setField("DESCRIPTION", $reqDescription);
        $statisticAnalyst->setField("VALUE", $reqValue);
        $statisticAnalyst->setField("COLOR", $reqColor);

        if ($reqMode == "insert") {
            $statisticAnalyst->insert();
        } else {
            $statisticAnalyst->update();
        }

        echo "Data berhasil disimpan.";
    }

    function delete()
    {

        $this->load->model("Statistic");
        $this->load->model("StatisticDetil");
        $reqId = $this->input->get('reqId');

        $statistic = new Statistic();
        $statistic_detil = new StatisticDetil();
        $statistic->setField('STATISTIC_ID',$reqId);
        $statistic_detil->setField('STATISTIC_ID',$reqId);

        $statistic->delete();
        $statistic->deleteParent();

        echo 'Data berhasil dihapus.';

    }
    function singleChar(){
        $this->load->model("StatisticDetil");
        $statistic_detil = new StatisticDetil();
        $reqId = $this->input->get("reqId");
        $statement =  " AND CAST(A.STATISTIC_ID AS VARCHAR) ='".$reqId."'";
        $statistic_detil->selectByParamsMonitoring(array(),-1,-1,$statement);
       
        $color = array();
        $data[] = array('Task', '');
        while ($statistic_detil->nextRow()) {
            $data[] = array(
                $statistic_detil->getField("DESCRIPTION"),
                floatval($statistic_detil->getField("VALUE"))
            );
            $color[] = $statistic_detil->getField("COLOR");


        }
        echo json_encode(array(
            'data' => $data,
            'color' => $color,
        ));
    }
    
    function getGoogleChart()
    {

        $this->load->model("StatisticDetil");
        $statistic_detil = new StatisticDetil();
        
        $reqId = $this->input->get("reqId");
        $reqTahun = $this->input->get("reqTahun");
        $html = file_get_contents($this->config->item('base_report') . "report/index/new_pie2/?reqId=" . $reqId . "&reqTahun=" . $reqTahun);
        
        $statement =  " AND CAST(A.STATISTIC_ID AS VARCHAR) ='".$reqId."' AND A.TAHUN = '".$reqTahun."' ";

        $statistic_detil->selectByParamsMonitoringOffer(array(),-1,-1,$statement);
        $color = array();
        $data[] = array('Task', '');
        while ($statistic_detil->nextRow()) {
            $data[] = array(
                $statistic_detil->getField("DESCRIPTION"),
                floatval($statistic_detil->getField("VALUE"))
            );
            $color[] = $statistic_detil->getField("COLOR");


        }
        echo json_encode(array(
            'data' => $data,
            'color' => $color,
        ));
    }

    function getGoogleChartReport(){
        $this->load->model("DokumenReport");
        $reqTahun = $this->input->get("reqTahun");
        $reqId =$this->input->get("reqId");
          $reqModel =explode('-', $reqId);
          $reqUrut =$this->input->get("reqUrut");
        $arrValue =array("Preparation before working","Conduct of the team during","Knowledge of working","Concerns for safety","Performance of equipement","Performance of personnel","Overall Performance of team","etc");
        $arrLabel = array('SURYEVOR_SATISFACTION_SHEET','CLIENT_SATISFACTION_SHEET');
        $reqIds = explode('-', $reqTahun);
        $report = new DokumenReport();
        $statement = "  AND TO_CHAR(A.START_DATE,'YYYY')='".$reqIds[1]."'";

        $report->selectByParams(array(),-1,-1,$statement);
        // echo $report->query;exit;
        $arrLabel = array('SURYEVOR_SATISFACTION_SHEET','CLIENT_SATISFACTION_SHEET');

        for($i=0;$i<count($arrLabel);$i++){
         $html = file_get_contents($this->config->item('base_report') . "report/index/new_pie5/?reqId=" . $arrLabel[$i] . "&reqUrut=".$reqUrut."&reqTahun=" . $reqIds[1]);
        }


        $arrDataValue = array();
          $arrDataValue2 = array();
         $color = array();
        $data[] = array('Task', '');
        while ( $report->nextRow()) {
                $reqSurveyors = $report->getField("SURYEVOR");
                $reqSurveyorsx = json_decode($reqSurveyors,true);

                $reqClients = $report->getField("CLIENT");
                $reqClientsx = json_decode($reqClients,true); 


                for($i=0;$i<count($arrValue);$i++){
                    for($j=0;$j<5;$j++){
                         if(!empty($reqSurveyorsx['reqSurveyor'.$i.$j]) && $reqUrut==$j){
                            // $arrDataValue[$arrValue[$i]] += (5-$j);
                            $arrDataValue[$arrValue[$i]] +=1;
                             $arrDataValue[$j][$arrValue[$i]] += 1;
                         }
                         if(!empty($reqClientsx['reqClient'.$i.$j]) && $reqUrut==$j){
                             // $arrDataValue2[$arrValue[$i]] += (5-$j);
                             $arrDataValue2[$arrValue[$i]] +=1;
                              $arrDataValue2[$j][$arrValue[$i]] += 1;
                         }
                    }
                }
            }

            // print_r($arrDataValue);exit;

            for($i=0;$i<count($arrValue);$i++){

                if($reqModel[0]=='SURYEVOR_SATISFACTION_SHEET'){
                 $data[] = array(
                    $arrValue[$i],
                    floatval( ifZero($arrDataValue[$arrValue[$i]]))
                );
                
             }else{
                $data[] = array(
                    $arrValue[$i],
                    floatval( ifZero($arrDataValue2[$arrValue[$i]]))
                );

             }
              $color[] = '';
           }

             echo json_encode(array(
                'data' => $data,
                'color' => $color,
            ));
    }

    function getGoogleChartLokasi()
    {

     $this->load->model(ProjectHpp);
     $this->load->model('MasterLokasi');    
        $reqId = $this->input->get("reqId");
     $master_lokasi = new MasterLokasi();
     // $statement = "  AND EXISTS (SELECT 1 FROM PROJECT_HPP X WHERE X.MASTER_LOKASI_ID = A.MASTER_LOKASI_ID 
     // AND  TO_CHAR( X.DATE_PROJECT,'YYYY')='".$reqId."' AND X.MASTER_LOKASI_ID IS NOT NULL
     // AND EXISTS (SELECT 1 FROM OFFER CC WHERE CC.HPP_PROJECT_ID = X.HPP_PROJECT_ID  AND CC.STATUS='1')

     //  )";
       $statement = "  AND EXISTS (SELECT 1 FROM PROJECT_HPP X WHERE X.MASTER_LOKASI_ID = A.MASTER_LOKASI_ID 
     AND  TO_CHAR( X.DATE_PROJECT,'YYYY')='".$reqId."' 

      )";
       $master_lokasi->selectByParamsMonitoring(array(),-1,-1,$statement); 
        $html = file_get_contents($this->config->item('base_report') . "report/index/new_pie3/?reqId=" . $reqId . "&reqTahun=" . $reqTahun);
        $color = array();
        $data[] = array('Task', '');
        while ($master_lokasi->nextRow()) {
            $project_hpp = new ProjectHpp();
            $total =$project_hpp->getCountByParamsMonitoring(array("A.MASTER_LOKASI_ID"=>$master_lokasi->getField("MASTER_LOKASI_ID")));
            

            $data[] = array(
                $master_lokasi->getField("NAMA"),
                floatval($total)
            );
            $color[] = $master_lokasi->getField("COLOR");


        }
        echo json_encode(array(
            'data' => $data,
            'color' => $color,
        ));
    }
    function getGoogleChartReason()
    {

     $this->load->model('Offer');
     $this->load->model('MasterReason');    
        $reqId = $this->input->get("reqId");
     $master_lokasi = new MasterReason();
     $statement = "  AND EXISTS (SELECT 1 FROM OFFER X WHERE X.MASTER_REASON_ID = A.MASTER_REASON_ID 
     AND  TO_CHAR( X.DATE_OF_SERVICE,'YYYY')='".$reqId."' AND X.MASTER_REASON_ID IS NOT NULL )";
       $master_lokasi->selectByParamsMonitoring(array(),-1,-1,$statement); 
        $html = file_get_contents($this->config->item('base_report') . "report/index/new_pie4/?reqId=" . $reqId . "&reqTahun=" . $reqTahun);
        $color = array();
        $data[] = array('Task', '');
        while ($master_lokasi->nextRow()) {
            $project_hpp = new Offer();
            $total =$project_hpp->getCountByParams(array("A.MASTER_REASON_ID"=>$master_lokasi->getField("MASTER_REASON_ID")));
            

            $data[] = array(
                $master_lokasi->getField("NAMA"),
                floatval($total)
            );
            $color[] = $master_lokasi->getField("COLOR");


        }
        echo json_encode(array(
            'data' => $data,
            'color' => $color,
        ));
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
