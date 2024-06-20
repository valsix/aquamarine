<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class so_equip_pengembalian_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->CABANG			= $this->kauth->getInstance()->getIdentity()->CABANG;
	}

	function json()
    {
        $this->load->model("SoEquipPengembalian");
        $so_equip = new SoEquipPengembalian();

        $aColumns = array("SO_EQUIP_PENGEMBALIAN_ID","SO_EQUIP_ID", "EQUIP_ID","EC_NAME","EQUIP_NAME","SERIAL_NUMBER","QTY","EQUIP_ITEM","OUT_CONDITION","IN_CONDITION","REMARK", "AKSI" );

        $aColumnsAlias = array("SO_EQUIP_PENGEMBALIAN_ID","SO_EQUIP_ID", "EQUIP_ID","EC_NAME","EQUIP_NAME","SERIAL_NUMBER","QTY","EQUIP_ITEM","OUT_CONDITION","IN_CONDITION","REMARK", "AKSI" );
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
            $statement = " AND A.SO_ID ='".$reqId."'";
        }

        if(empty($sOrder)){
            $sOrder = "ORDER BY A.".$aColumns[0]." asc";
        }

         $statement .= " AND (UPPER(B.EQUIP_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%'
                        OR    UPPER(B.BARCODE) LIKE '%" . strtoupper($_GET['sSearch']) . "%' 

         )";
        $allRecord = $so_equip->getCountByParamsMonitoringEquips(array(), $statement_privacy . $statement);
       //  echo  $so_equip->query;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $so_equip->getCountByParamsMonitoringEquips(array(), $statement_privacy . $statement);

        $so_equip->selectByParamsMonitoringEquips(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $so_equip->query;exit;
     
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $allRecord,
            "iTotalDisplayRecords" => $allRecordFilter,
            "aaData" => array()
        );
        while ($so_equip->nextRow()) {
            $row = array();
            $reqSoEquipId = $so_equip->getField("SO_EQUIP_PENGEMBALIAN_ID");
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "DESKRIPSI"){
                    $row[] = truncate($so_equip->getField($aColumns[$i]), 2);
                }else if ($aColumns[$i] == "DEBET"){
                    $row[] = currencyToPage2($so_equip->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "KREDIT"){
                    $row[] = currencyToPage2($so_equip->getField($aColumns[$i]));
                }else if ($aColumns[$i] == "SALDO"){
                    $row[] = currencyToPage2($so_equip->getField($aColumns[$i]));
                }else if($aColumns[$i] == "AKSI"){
                 $btn_edit = '<button type="button"  class="btn btn-info " onclick=editing('.$reqSoEquipId.')  ><i class="fa fa-pencil-square-o fa-lg"> </i> </button>';
                 $btn_delete = '<button type="button"  class="btn btn-danger hapusi"  onclick="deleting('.$reqSoEquipId.')"><i class="fa fa-trash-o fa-lg"> </i> </button>';

                 $row[] =$btn_edit.$btn_delete;
                }


                else{
                    $row[] = $so_equip->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
		
    


    function add(){
        $this->load->model("ServiceOrder");
        $this->load->model("SoEquip");
        $this->load->model("SoEquipPengembalian");

        $reqId        = $this->input->post("reqId");
        $reqSoEquipId        = $this->input->post("reqEquipId");
        $reqSoEquipPengembalianId        = $this->input->post("reqSoEquipPengembalianId");
        $reqCheck               = $this->input->post("reqCheck");


       $so_equip = new SoEquipPengembalian();
       $so_equip->selectByParamsMonitoring(array("A.SO_EQUIP_ID"=> $reqSoEquipId,"A.SO_ID"=>$reqId));
       $so_equip->firstRow();
       $reqSoEquipPengembalianId        = $so_equip->getField("SO_EQUIP_PENGEMBALIAN_ID");

        $so_equip   = new SoEquip;
        $statement = " AND A.SO_EQUIP_ID='".$reqSoEquipId."'"; 
        $so_equip->selectByParamsMonitoringEquips(array(),-1,-1,$statement);
        $so_equip->firstRow();

        
        $reqItem            =  $so_equip->getField("ITEM");
        $reqQty             = $so_equip->getField("QTY");
        $reqOutCondition    = $so_equip->getField("OUT_CONDITION");
        $reqInCondition     = $this->input->post("reqInconditon");
        $reqRemark          = $this->input->post("reqRemark");
        $reqEquipId         = $so_equip->getField("EQUIP_ID");

        $so_equip = new SoEquipPengembalian();

        $so_equip->setField("SO_EQUIP_PENGEMBALIAN_ID",$reqSoEquipPengembalianId);
        $so_equip->setField("SO_EQUIP_ID",$reqSoEquipId);
        $so_equip->setField("SO_ID",$reqId);
        $so_equip->setField("EQUIP_ID",$reqEquipId);
        $so_equip->setField("EQUIP_QTY",$reqQty);
        $so_equip->setField("OUT_CONDITION",$reqOutCondition );
        $so_equip->setField("IN_CONDITION",$reqInCondition );
        $so_equip->setField("IS_BACK",0);
        $so_equip->setField("IS_POST",0);
        $so_equip->setField("EQUIP_ITEM",$reqItem);
        $so_equip->setField("REMARK",$reqRemark);
        $so_equip->setField("FLAG",$reqCheck);
        if(empty($reqSoEquipPengembalianId)){
            $so_equip->insert();
        }else{
            $so_equip->update();
        }


        $soequip = new SoEquip();
        $total = $soequip->getCountByParamsMonitoring(array("A.SO_ID"=>$reqId));
        $soequip = new SoEquipPengembalian();
        $total_pengembalian = $soequip->getCountByParamsMonitoring(array("A.SO_ID"=>$reqId,"A.FLAG"=>'1'));

        $value_flag =1;
        if($total_pengembalian==0){
              $value_flag =1;
        }else if($total_pengembalian > 0){
              $value_flag =2;
        }
        if($total_pengembalian == $total){
              $value_flag =3;
        }

        $serviceorder = new ServiceOrder();
        $serviceorder->setField("SO_ID",$reqId);
        $serviceorder->setField("FLAG_ITEM",$value_flag);
        $serviceorder->updateFlagService();

          $reqVal =  $reqCheck?'0':'1';
          $soequip = new SoEquip();
          $soequip->setField("SO_EQUIP_ID",$reqSoEquipId);
          $soequip->setField("FLAG", $reqVal);
          $soequip->update_flag2();  
        echo 'Data berhasil di simpan';
        
    }

    function ambilData(){
        $this->load->model("SoEquip");
        $so_equip = new SoEquip();
        $reqEquipId = $this->input->post('reqEquipId');
        $reqId     = $this->input->post('reqId');
        $reqParam  = $this->input->post('reqParam');
        // $reqEquipId =1514;
        // $reqId=61;

        $statement = " AND A.SO_ID='".$reqId."' AND (   B.BARCODE='".$reqParam."')"; 
        $so_equip->selectByParamsMonitoringEquips(array(),-1,-1,$statement);
      //  echo  $so_equip->query;exit;
      

         $so_equip->firstRow();

         $SO_EQUIP_ID       = $so_equip->getField("SO_EQUIP_ID");
         $PIC_PATH          = $so_equip->getField("PIC_PATH");
         $EQUIP_ID          = $so_equip->getField("EQUIP_ID");
         $OUT_CONDITION     = $so_equip->getField("OUT_CONDITION");
         $EQUIP_SPEC        = $so_equip->getField("EQUIP_SPEC");
         $REMARK            = $so_equip->getField("REMARK");
         $QTY               = $so_equip->getField("QTY");
         $ITEM              = $so_equip->getField("ITEM");
          $EC_NAME              = $so_equip->getField("EC_NAME");
         $SERIAL_NUMBER     = $so_equip->getField("SERIAL_NUMBER");
         $EQUIP_NAME         = $so_equip->getField("EQUIP_NAME");
           $BARCODE         = $so_equip->getField("BARCODE");
       
        $arrData = array(
            "SO_EQUIP_ID"=>$SO_EQUIP_ID,
            "PIC_PATH"=>$PIC_PATH,
            "OUT_CONDITION"=>$OUT_CONDITION,
             "BARCODE"=>$BARCODE,
            "EQUIP_SPEC"=>$EQUIP_SPEC,
            "REMARK"=>$REMARK,
            "QTY"=>$QTY,
            "ITEM"=>$ITEM,
            "EC_NAME"=>$EC_NAME,
            "EQUIP_ID"=>$EQUIP_ID,
            "EQUIP_NAME"=>$EQUIP_NAME,
            "SERIAL_NUMBER"=>$SERIAL_NUMBER
        );
       echo json_encode($arrData );
          
    }

   

	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("SoEquipPengembalian");
		$slider = new SoEquipPengembalian();


		$slider->setField("SO_EQUIP_PENGEMBALIAN_ID", $reqId);
	$slider->delete();
		echo "Data berhasil di hapus";
		
	}



	
}
