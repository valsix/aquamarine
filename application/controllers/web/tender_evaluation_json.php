<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class tender_evaluation_json  extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->kauth->getInstance()->hasIdentity()) {
            // redirect('login');
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
        $this->load->model("TenderEvaluation");
        $this->load->model("TenderEvaluationDetail");
        $this->load->model("MasterTenerMenus");
        $rules = new TenderEvaluation();
       
      

       
        $master_tener_menus = new MasterTenerMenus();
        $master_tener_menus->selectByParamsMonitoring(array());
        $attData = array();
        $attDataId = array();
        while ( $master_tener_menus->nextRow()) {
            array_push($attData , strtoupper($master_tener_menus->getField('NAMA')));
            $attDataId[strtoupper($master_tener_menus->getField('NAMA'))]= $master_tener_menus->getField("MASTER_TENDER_MENUS_ID");
        }


        $aColumns = array(
            "TENDER_EVALUATION_ID","MASTER_TENDER_PERIODE_ID","INDEX","NAMA_PSC","TITLE","TENDER_NO","CLOSING","OPENING"
        );
        $aColumns =array_merge($aColumns,$attData);
        $arDataOther = array("STATUS","OWNER","BID_VALUE","TKDN","BID_BOUDS","BID_VALIDATY","NOTES","AKSI");
        $aColumns = array_merge($aColumns,$arDataOther);

        $aColumnsAlias = array(
              "TENDER_EVALUATION_ID","MASTER_TENDER_PERIODE_ID","INDEX","NAMA_PSC","TITLE","TENDER_NO","CLOSING","OPENING"
        );
      $aColumnsAlias=  array_merge($aColumnsAlias,$attData);
       
       $aColumnsAlias= array_merge($aColumnsAlias,$arDataOther);


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
            if (trim($sOrder) == "ORDER BY A".$aColumns[0]." asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY A".$aColumns[0]." desc";
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
         $statement_privacy='';
        $reqId = $this->input->get('reqId');
        if(!empty($reqId)){
        $statement_privacy .= " AND CAST(A.MASTER_TENDER_PERIODE_ID AS VARCHAR)='".$reqId."'";
        }

        $statement = " AND (UPPER(A.INDEX) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $_SESSION[$this->input->get("pg")."reqCari"] = $statement;
        $allRecord = $rules->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $rules->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $rules->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $rules->query;exit;
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
 $nom=0;
        while ($rules->nextRow()) {
            $row = array();
            $reqIds = $rules->getField($aColumns[0]);

            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NAME"){
                    $row[] = $rules->getField($aColumns[$i]);
                }else if ($aColumns[$i] == "STATUS"){
                     $status = $rules->getField('STATUS');
                     $text = $status;
                     if(!empty($status)){
                         $html = '<div style="background:#404040;width=100%;text-align:center;font-weight:bold"><p style="font-size:15px;color:white">'.$status.'</p></div>';
                          $text= $html;
                     }
                    

                    $row[] = $text;
                }else if ($aColumns[$i] == "OWNER"){
                    $row[] = $rules->getField("CUR_OWNER")." " .currencyToPage2($rules->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "BID_VALUE"){
                    $row[] = $rules->getField("CUR_BID")." " .currencyToPage2($rules->getField($aColumns[$i]));
                }

                else if(in_array($aColumns[$i], $attData)){
                   $master_tener_menus = new MasterTenerMenus();
                   $master_tener_menus->selectByParamsMonitoring(array("UPPER(A.NAMA)"=>$aColumns[$i]));
                   // echo $master_tener_menus->query.'<br>';
                   $master_tener_menus->firstRow();
                  $color2 = $master_tener_menus->getField("COLOR2");
                      $tender_evaluation_detail = new TenderEvaluationDetail();
                      $tender_evaluation_detail->selectByParamsMonitoring(array("A.TENDER_EVALUTATION_ID"=>$reqIds,"A.MASTER_TENDER_MENUS_ID"=>$attDataId[$aColumns[$i]]));
                      $tender_evaluation_detail->firstRow();
                    
                     
                      $NILAI= $tender_evaluation_detail->getField('NILAI');
                      if($NILAI == '100'){
                         $color = $tender_evaluation_detail->getField("COLOR");
                      }else if(empty($NILAI)){
                        $color = $color2;
                        $NILAI='&nbsp;';
                      } else{
                        $color = $color2;
                        $NILAI='&nbsp;';
                      }
                      $html = '<div style="background:'.$color.';width=100%;text-align:center;font-weight:bold">'.$NILAI.'</div>';

                      $row[] = $html;
                } else if ($aColumns[$i] == "AKSI") {
                    $btn_edit = '<button type="button"  class="btn btn-info " onclick=editing(' . $nom . ')  ><i class="fa fa-pencil-square-o fa-lg"> </i> </button>';
                    $btn_delete = '<button type="button"  class="btn btn-danger hapusi"  onclick="deleting(' . $nom . ')"><i class="fa fa-trash-o fa-lg"> </i> </button>';

                    $row[] = $btn_edit . $btn_delete;
                }


                else{
                    $row[] = $rules->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
            $nom++;
        }
        echo json_encode($output);
    }


    function add(){
         $this->load->model("MasterTenderPeriode");
         $master_tender_periode = new MasterTenderPeriode();
         $reqId = $this->input->post('reqId');
         $reqTahun = $this->input->post('reqTahun');

         $master_tender_periode->setField("MASTER_TENDER_PERIODE_ID",$reqId);
         $master_tender_periode->setField("TAHUN",$reqTahun);

         if(empty($reqId)){
            $master_tender_periode->insert();
            $reqId = $master_tender_periode->id;
         }else{
            $master_tender_periode->update();
         }
         echo $reqId.'-Data berhasil di simpan';
    }



    function addDetail(){
         $this->load->model("MasterTenderPeriode");
         
         $this->load->model("TenderEvaluation");
         $this->load->model("TenderEvaluationDetail");
         $reqId = $this->input->post('reqId');



         $reqMasterTenderPeriodeId   = $this->input->post("reqPeriode");
         $reqIndex                   = $this->input->post("reqIndex");
         $reqMasterPscId             = $this->input->post("reqMasterPscId");
         $reqTitle                   = $this->input->post("reqTitle");
         $reqTenderNo                = $this->input->post("reqTenderNo");
         $reqClosing                 = $this->input->post("reqClosing");
         $reqOpening                 = $this->input->post("reqOpening");
         $reqStatus                  = $this->input->post("reqStatus");
         $reqOwner                   = $this->input->post("reqOwner");
         $reqBidValue                = $this->input->post("reqBidValue");
         $reqTkdn                    = $this->input->post("reqTkdn");
         $reqBidBouds                = $this->input->post("reqBidBouds");
         $reqBidValidaty             = $this->input->post("reqBidValidaty");
         $reqBidCur                  = $this->input->post("reqBidCur");
         $reqOwnerCur                = $this->input->post("reqOwnerCur");
         $reqNotes                   = $_POST["reqNotes"];

         $master_tender_periode = new MasterTenderPeriode();
         $master_tender_periode->setField('MASTER_TENDER_PERIODE_ID',$reqMasterTenderPeriodeId);
         $master_tender_periode->updateProses();

         $tender_evaluation = new TenderEvaluation();
         $tender_evaluation->setField("TENDER_EVALUATION_ID", $reqId);
         $tender_evaluation->setField("MASTER_TENDER_PERIODE_ID", $reqMasterTenderPeriodeId);
         $tender_evaluation->setField("INDEX", $reqIndex);
         $tender_evaluation->setField("MASTER_PSC_ID", $reqMasterPscId);
         $tender_evaluation->setField("TITLE", $reqTitle);
         $tender_evaluation->setField("TENDER_NO", $reqTenderNo);
         $tender_evaluation->setField("CLOSING", dateToDBCheck($reqClosing));
         $tender_evaluation->setField("OPENING", dateToDBCheck($reqOpening));
         $tender_evaluation->setField("STATUS", $reqStatus);
         $tender_evaluation->setField("OWNER", dotToNo($reqOwner));
         $tender_evaluation->setField("BID_VALUE", dotToNo($reqBidValue));
         $tender_evaluation->setField("TKDN", $reqTkdn);
         $tender_evaluation->setField("CUR_OWNER", $reqOwnerCur);
         $tender_evaluation->setField("CUR_BID", $reqBidCur);
         $tender_evaluation->setField("BID_BOUDS", $reqBidBouds);
         $tender_evaluation->setField("BID_VALIDATY", $reqBidValidaty);
         $tender_evaluation->setField("NOTES", $reqNotes);

         if(empty($reqId)){
            $tender_evaluation->insert();
            $reqId = $tender_evaluation->id;
         }else{
            $tender_evaluation->update();
         }


       
        $reqMenuId = $this->input->post('reqMenuId');
        $reqValueMenu = $this->input->post('reqValueMenu');
        
        for($i=0;$i<count($reqMenuId);$i++){
            $ids = $reqMenuId[$i];
            $val = $reqValueMenu[$i];

            $tender_evaluation_detail = new TenderEvaluationDetail();
            $total = $tender_evaluation_detail->getCountByParamsMonitoring(array("A.TENDER_EVALUTATION_ID"=>$reqId,"A.MASTER_TENDER_MENUS_ID"=>$ids));

            $tender_evaluation_detail = new TenderEvaluationDetail();
            $tender_evaluation_detail->setField("MASTER_TENDER_MENUS_ID",$ids);
            $tender_evaluation_detail->setField("TENDER_EVALUTATION_ID",$reqId);
            $tender_evaluation_detail->setField("NILAI",ValToNullDB($val));

            if( $total==0){
                $tender_evaluation_detail->insert();
            }else{
                $tender_evaluation_detail->update();
            }
        }

    }
    

    function delete()
    {

         $this->load->model("MasterTenderPeriode");
       
         $this->load->model("TenderEvaluation");
         $this->load->model("TenderEvaluationDetail");

         
         $reqId = $this->input->get('reqId');
         $reqIds = explode(',', $reqId);

         for($i=0;$i<count($reqIds);$i++){   
             $reqId = $reqIds[$i];
             if(!empty($reqId)){
             $tender_evaluation = new TenderEvaluation();
             $tender_evaluation_detail = new TenderEvaluationDetail();
             $tender_evaluation->selectByParamsMonitoring(array("A.TENDER_EVALUATION_ID"=>$reqId));

             $tender_evaluation->firstRow();
             $reqPeriodeId = $tender_evaluation->getField('MASTER_TENDER_PERIODE_ID');

             $master_tender_periode = new MasterTenderPeriode();
             $master_tender_periode->setField("MASTER_TENDER_PERIODE_ID",$reqPeriodeId);
             $master_tender_periode->updateProses();

             $tender_evaluation = new TenderEvaluation();
             $tender_evaluation->setField("TENDER_EVALUATION_ID", $reqId);
             $tender_evaluation_detail->setField("TENDER_EVALUTATION_ID", $reqId);
                     if ($tender_evaluation->delete()){
                        $tender_evaluation_detail->deleteParent();
                        // echo "Data berhasil dihapus.";
                    }
                    else{
                      // echo "Data gagal dihapus.";
                    }
            }
        }

       
    }

   
    

}
