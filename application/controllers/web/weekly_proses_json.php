<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class weekly_proses_json   extends CI_Controller
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
		$this->MENUEPL = $this->kauth->getInstance()->getIdentity()->MENUEPL;
		$this->MENUUWILD = $this->kauth->getInstance()->getIdentity()->MENUUWILD;
		$this->MENUWP = $this->kauth->getInstance()->getIdentity()->MENUWP;
		$this->MENUPL = $this->kauth->getInstance()->getIdentity()->MENUPL;
		$this->MENUEL = $this->kauth->getInstance()->getIdentity()->MENUEL;
		$this->MENUPMS = $this->kauth->getInstance()->getIdentity()->MENUPMS;
		$this->MENURS = $this->kauth->getInstance()->getIdentity()->MENURS;
		$this->MENUSTD = $this->kauth->getInstance()->getIdentity()->MENUSTD;
	}

	function delete()
	{
		$this->load->model("Departement");
		$set = new Departement();
		$reqId= $this->input->get("reqId");
		$set->setField("DEPARTEMENT_ID",$reqId);

		if($set->delete($statement)){
			echo "Data berhasil dihapus.";
		}else{
			echo "xxx-Data gagal dihapus.";
		}
	}

    function add_history($arrData){
        $this->load->model("WeeklyProsesHistory");
        $this->load->model("Departement");
        
        $departement = new Departement();
        $departement->selectByParamsMonitoring(array("CAST(A.DEPARTEMENT_ID AS VARCHAR)"=>$arrData['DEPARTEMENT']));
        $departement->firstRow();
        $nama_departement =  $departement->getField("NAMA");

        $WeeklyProsesHistoryTotal = new WeeklyProsesHistory();
       

        if(!empty($arrData['WEEKLY_PROSES_DETAIL_ID']) && $arrData['WEEKLY_PROGRES_INLINE_ID'] ){
                       $total = $WeeklyProsesHistoryTotal->getCountByParamsMonitoring(array(
                        "A.WEEKLY_PROGRES_INLINE_ID"=>$arrData['WEEKLY_PROGRES_INLINE_ID'],
                        "A.WEEKLY_PROSES_DETAIL_ID"=>$arrData['WEEKLY_PROSES_DETAIL_ID'],
                        "A.WEEKLY_PROSES_ID"=>$arrData['WEEKLY_PROSES_ID'],
                        "A.PROSES"=>$arrData['PROSES'],
                        "A.STATUS"=>$arrData['STATUS'],
                        "A.DUE_PIC"=>$arrData['DUE_PIC'],
                        "A.PIC_PERSON"=>$arrData['PIC_PERSON']

                    )," AND TO_DATE('" . $arrData['DUE_DATE'] . "','dd-mm-yyyy') = TO_DATE(CAST(A.DUE_DATE AS VARCHAR),'dd-mm-yyyy')");
                       if($total==0){
                        $weekly_proses_history = new WeeklyProsesHistory();
                        $weekly_proses_history->setField("WEEKLY_PROGRES_INLINE_ID",$arrData['WEEKLY_PROGRES_INLINE_ID']);
                        $weekly_proses_history->setField("WEEKLY_PROSES_DETAIL_ID",$arrData['WEEKLY_PROSES_DETAIL_ID']);
                        $weekly_proses_history->setField("WEEKLY_PROSES_ID",$arrData['WEEKLY_PROSES_ID']);
                        $weekly_proses_history->setField("PROSES",$arrData['PROSES']);
                         $weekly_proses_history->setField("PIC_PERSON",$arrData['PIC_PERSON']);
                        $weekly_proses_history->setField("STATUS",$arrData['STATUS']);
                        $weekly_proses_history->setField("DUE_DATE",dateToDBCheck($arrData['DUE_DATE']));
                        $weekly_proses_history->setField("DUE_PIC",$arrData['DUE_PIC']);
                        $weekly_proses_history->setField("MASALAH",$arrData['MASALAH']);
                        $weekly_proses_history->setField("SOLUSI",$arrData['SOLUSI']);
                        $weekly_proses_history->setField("DEPARTEMENT", $nama_departement);
                        $weekly_proses_history->setField("TANGGAL_MASALAH",dateToDBCheck($arrData['TANGGAL_MASALAH']));
                        $weekly_proses_history->insert();
                    }
        }
    }

    function add(){
        $this->load->model("WeeklyProses");
        $this->load->model("WeeklyProsesDetail");
        $this->load->model("WeeklyProgresInline");
        $this->load->model("WeeklyProgresRincian");

        $this->load->library("FileHandler");
        $file = new FileHandler();
       
        
        
        $weekly_proses = new WeeklyProses();
        $reqId = $this->input->post("reqId");
        $reqDepartementId= $this->input->post("reqDepartementId");
        $reqMasalah= $this->input->post("reqMasalah");
        $reqTanggalMasalah= $this->input->post("reqTanggalMasalah");
        $weekly_proses->setField("WEEKLY_PROSES_ID", $reqId);
        $weekly_proses->setField("DEPARTEMENT_ID", $reqDepartementId);
        $weekly_proses->setField("MASALAH", $reqMasalah);
        $weekly_proses->setField("TANGGAL_MASALAH",  dateToDBCheck($reqTanggalMasalah));
        if(empty($reqId)){
            $weekly_proses->insert();
            $reqId = $weekly_proses->id;
        }else{
            $weekly_proses->update();
        }

        $reqUrutSolusi              = $this->input->post("reqUrutSolusi");
        $reqMasterSolusiId          = $this->input->post("reqMasterSolusiId");
        $reqWeeklyProsesDetailId    = $this->input->post("reqWeeklyProsesDetailId");


       
        // print_r($reqWeeklyProsesDetailId);exit;
      
        

        $reqParam    = $this->input->post("reqParams");

        // print_r($reqParam);exit;
        $reqParams = array();
        for($i=0;$i<count($reqParam );$i++){
            if(!empty($reqParam[$i])){
            array_push($reqParams, $reqParam[$i]);
            }
        }

        // if(!empty($reqParam[0])){
        for($i=0;$i<count($reqParams);$i++){


        $weekly_proses_detail = new WeeklyProsesDetail();
        $weekly_proses_detail->setField("WEEKLY_PROSES_ID",$reqId);
        $weekly_proses_detail->setField("URUT",$reqUrutSolusi[$i]);
        $weekly_proses_detail->setField("MASTER_SOLUSI_ID",$reqMasterSolusiId[$i]);
        $weekly_proses_detail->setField("WEEKLY_PROSES_DETAIL_ID",$reqWeeklyProsesDetailId[$i]);
        $reqWeeklyIdDetail = $reqWeeklyProsesDetailId[$i];
        if(empty($reqWeeklyProsesDetailId[$i])){
                $weekly_proses_detail->insert();
                $reqWeeklyIdDetail= $weekly_proses_detail->id;
        }else{
                $weekly_proses_detail->update();
        }

        
       
        $reqWeeklyProgresInlineId = $this->input->post("reqWeeklyProgresInlineId".$reqParam[$i]);
        $reqProses                = $this->input->post("reqProses".$reqParam[$i]);
        $reqStatusProgres         = $this->input->post("reqStatusProgres".$reqParam[$i]);
        $reqDueDate               = $this->input->post("reqDueDate".$reqParam[$i]);
        $reqDuePic                = $this->input->post("reqDuePic".$reqParam[$i]);
        $reqUrutInline            = $this->input->post("reqUrutInline".$reqParam[$i]);
        $reqPicPerson            = $this->input->post("reqPicPerson".$reqParam[$i]);
        $reqUrutNoInline            = $this->input->post("reqUrutNoInline".$reqParam[$i]);
        
        $reqParamInline            = $this->input->post("reqParamInline".$reqParam[$i]);
        $filesData                  = $_FILES["reqFilesName".$reqParam[$i]];
        $reqLinkFileTempSize    =  $this->input->post("reqLinkFileTempSize");
        $reqLinkFileTempTipe    =  $this->input->post("reqLinkFileTempTipe");
        $reqFilesNames          =  $this->input->post("reqFilesNames".$reqParam[$i]);

        
        // print_r($reqWeeklyProsesDetailId);

                    for($j=0;$j<count($reqProses);$j++){

                        $weekly_progres_inline    = new WeeklyProgresInline();
                        $weekly_progres_inline->setField("WEEKLY_PROGRES_INLINE_ID",$reqWeeklyProgresInlineId[$j] );
                        $weekly_progres_inline->setField("WEEKLY_PROSES_DETAIL_ID", $reqWeeklyIdDetail);
                        $weekly_progres_inline->setField("WEEKLY_PROSES_ID", $reqId);
                        $weekly_progres_inline->setField("PROSES",    $reqProses[$j]);
                        $weekly_progres_inline->setField("STATUS",   $reqStatusProgres[$j]);
                        $weekly_progres_inline->setField("URUT",   $reqUrutInline[$j]);
                        $weekly_progres_inline->setField("DUE_DATE", dateToDBCheck($reqDueDate[$j]));
                        $weekly_progres_inline->setField("PIC_PERSON", $reqPicPerson[$j]);
                        $weekly_progres_inline->setField("DUE_PIC",   $reqDuePic[$j]);
                        $reqInlineId = $reqWeeklyProgresInlineId[$j];
                        $arrDataHistory = array();
                        $arrDataHistory['WEEKLY_PROGRES_INLINE_ID']=$reqWeeklyProgresInlineId[$j];
                        $arrDataHistory['WEEKLY_PROSES_DETAIL_ID']=$reqWeeklyIdDetail;
                        $arrDataHistory['WEEKLY_PROSES_ID']=$reqId;
                        $arrDataHistory['PROSES']= $reqProses[$j];
                        $arrDataHistory['PIC_PERSON']=  $reqPicPerson[$j];
                        $arrDataHistory['STATUS']=$reqStatusProgres[$j];
                        $arrDataHistory['DUE_DATE']=$reqDueDate[$j];
                        $arrDataHistory['MASALAH']=$reqMasalah;
                        $arrDataHistory['SOLUSI']=$reqMasterSolusiId[$i];
                        $arrDataHistory['DEPARTEMENT']=$reqDepartementId;
                        $arrDataHistory['TANGGAL_MASALAH']=$reqTanggalMasalah;

                        

                        if(!empty( $reqProses[$j])){
                                if(empty($reqWeeklyProgresInlineId[$j])){
                                    $weekly_progres_inline->insert();
                                    $reqInlineId = $weekly_progres_inline->id;
                                }else{
                                    $weekly_progres_inline->update();
                                }
                        }

                         $FILE_DIR = "uploads/weekly_meeting/".$reqInlineId.'/';
                         makedirs($FILE_DIR);

                        $renameFile = "FILES" . date("dmYhis") . '-' . $reqInlineId . "." . getExtension2($filesData['name'][$j]);
                        if ($file->uploadToDirArray('reqFilesName'.$reqParam[$i], $FILE_DIR, $renameFile, $j)) {
                            $reqPicPath                    = $renameFile;
                          
                        }else{
                            $reqPicPath      = $reqFilesNames[$j];
                        }
                        $arrDataHistory['DUE_PIC']=$reqPicPath;

                        $equipment_list = new WeeklyProgresInline();
                        $equipment_list->setField("WEEKLY_PROGRES_INLINE_ID", $reqInlineId);
                        $equipment_list->setField("DUE_PIC", $reqPicPath);
                        $equipment_list->update_path();

                        $this->add_history($arrDataHistory);


                        $reqWeeklyProgresRincianId  = $this->input->post("reqWeeklyProgresRincianId".$reqParamInline[$j]);
                        $reqRincian                 = $this->input->post("reqRincian".$reqParamInline[$j]);
                        $reqUrutRincian             = $this->input->post("reqUrutRincian".$reqParamInline[$j]);   


                                for($k=0;$k<count($reqRincian);$k++){
                                $weekly_progres_rincian = new  WeeklyProgresRincian();
                                $weekly_progres_rincian->setField("WEEKLY_PROGRES_RINCIAN_ID", $reqWeeklyProgresRincianId[$k]);
                                $weekly_progres_rincian->setField("WEEKLY_PROGRES_INLINE_ID", $reqInlineId);
                                $weekly_progres_rincian->setField("WEEKLY_PROSES_DETAIL_ID", $reqWeeklyIdDetail);
                                $weekly_progres_rincian->setField("WEEKLY_PROSES_ID", $reqId);
                                $weekly_progres_rincian->setField("RINCIAN", $reqRincian[$k]);
                                 $weekly_progres_rincian->setField("URUT", $reqUrutRincian[$k]);
                                 if(!empty($reqRincian[$k])){
                                            if(empty($reqWeeklyProgresRincianId[$k])){
                                                $weekly_progres_rincian->insert();
                                            }else{
                                                $weekly_progres_rincian->update();
                                            }
                                    }

                                }



                    }

        



        }
        // }

        echo $reqId."- Data berhasil di simpan";

    }

    function delete1(){
        $reqId = $this->input->get("reqId");
       $this->load->model("WeeklyProses");
       $this->load->model("WeeklyProsesDetail");
       $this->load->model("WeeklyProgresInline");
       $this->load->model("WeeklyProgresRincian");

       $WeeklyProses = new WeeklyProses();
       $WeeklyProses->setField("WEEKLY_PROSES_ID",$reqId);
       $WeeklyProses->delete();
       $WeeklyProsesDetail = new WeeklyProsesDetail();
       $WeeklyProsesDetail->setField("WEEKLY_PROSES_ID",$reqId);
       $WeeklyProsesDetail->deleteParent();

       $WeeklyProgresInline = new WeeklyProgresInline();
       $WeeklyProgresInline->setField("WEEKLY_PROSES_ID",$reqId);
       $WeeklyProgresInline->deleteParentWeekly();

       $WeeklyProgresRincian = new WeeklyProgresRincian();
       $WeeklyProgresRincian->setField("WEEKLY_PROSES_ID",$reqId);
       $WeeklyProgresRincian->deleteParentWeekly();
       

    }

    function delete2(){
         $reqId = $this->input->get("reqId");
         $this->load->model("WeeklyProsesDetail");
         $this->load->model("WeeklyProgresInline");
         $this->load->model("WeeklyProgresRincian");

         $WeeklyProsesDetail = new WeeklyProsesDetail();
         $WeeklyProsesDetail->setField("WEEKLY_PROSES_DETAIL_ID",$reqId);
         $WeeklyProsesDetail->delete();
         $WeeklyProgresInline = new WeeklyProgresInline();
         $WeeklyProgresInline->setField("WEEKLY_PROSES_DETAIL_ID",$reqId);
         $WeeklyProgresInline->deleteProses();
         $WeeklyProgresRincian = new WeeklyProgresRincian();
         $WeeklyProgresRincian->setField("WEEKLY_PROSES_DETAIL_ID",$reqId);
         $WeeklyProgresRincian->deleteProses();
    }

    function delete3(){
         $reqId = $this->input->get("reqId");
          $this->load->model("WeeklyProgresInline");
          $this->load->model("WeeklyProgresRincian");

          $WeeklyProgresInline = new WeeklyProgresInline();
          $WeeklyProgresInline->setField("WEEKLY_PROGRES_INLINE_ID",$reqId);
          $WeeklyProgresInline->delete();
          $WeeklyProgresRincian = new WeeklyProgresRincian();
          $WeeklyProgresRincian->setField("WEEKLY_PROGRES_INLINE_ID",$reqId);
          $WeeklyProgresRincian->deleteInline();
    }

    function delete4(){
         $reqId = $this->input->get("reqId");
         $this->load->model("WeeklyProgresRincian");
        $WeeklyProgresRincian = new WeeklyProgresRincian();
         $WeeklyProgresRincian->setField("WEEKLY_PROGRES_RINCIAN_ID",$reqId);
         $WeeklyProgresRincian->delete();

    }


	function add2()
	{
		
      


        


        $reqWeeklyProsesId= $this->input->post("reqWeeklyProsesId");
        // $reqDepartementId= $this->input->post("reqDepartementId");
        // $reqMasalah= $this->input->post("reqMasalah");
        // $reqTanggalMasalah= $this->input->post("reqTanggalMasalah");
        // $weekly_proses->setField("WEEKLY_PROSES_ID", $reqWeeklyProsesId);
        // $weekly_proses->setField("DEPARTEMENT_ID", $reqDepartementId);
        // $weekly_proses->setField("MASALAH", $reqMasalah);
        // $weekly_proses->setField("TANGGAL_MASALAH", $reqTanggalMasalah);




        $reqWeeklyProsesDetailId= $this->input->post("reqWeeklyProsesDetailId");
        $reqMasterSolusiId= $this->input->post("reqMasterSolusiId");
        $reqWeeklyProsesId= $this->input->post("reqWeeklyProsesId");
        $reqUrut= $this->input->post("reqUrut");
        $weekly_proses_detail->setField("WEEKLY_PROSES_DETAIL_ID", $reqWeeklyProsesDetailId);
        $weekly_proses_detail->setField("MASTER_SOLUSI_ID", $reqMasterSolusiId);
        $weekly_proses_detail->setField("WEEKLY_PROSES_ID", $reqWeeklyProsesId);



        $reqWeeklyProsesHistoryId= $this->input->post("reqWeeklyProsesHistoryId");
        $reqWeeklyProgresInlineId= $this->input->post("reqWeeklyProgresInlineId");
        $reqWeeklyProsesDetailId= $this->input->post("reqWeeklyProsesDetailId");
        $reqWeeklyProsesId= $this->input->post("reqWeeklyProsesId");
        $reqProses= $this->input->post("reqProses");
        $reqStatus= $this->input->post("reqStatus");
        $reqDueDate= $this->input->post("reqDueDate");
        $reqDuePic= $this->input->post("reqDuePic");
        $reqRincian= $this->input->post("reqRincian");
		
		echo 'Data Berhasil di simpan';
	}


		function json()
    {
        $this->load->model("WeeklyProses");
        $pms = new WeeklyProses();
        
        

        $aColumns = array("WEEKLY_PROSES_ID","NO","HTML","NAMA_DEPARTEMEN","MASALAH","TANGGAL_MASALAH","CREATED_BY","CREATED_DATE","UPDATED_BY","UPDATED_DATE","WEEKLY_PROSES_ID");
        $aColumnsAlias =array("WEEKLY_PROSES_ID","HTML","NAMA_DEPARTEMEN","MASALAH","TANGGAL_MASALAH","CREATED_BY","CREATED_DATE","UPDATED_BY","UPDATED_DATE","WEEKLY_PROSES_ID");
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
        $reqCariDepartement =  $_SESSION[$this->input->get("pg")."reqCariDepartement"] =  $this->input->get("reqCariDepartement");
        $reqCariMasalah =$_SESSION[$this->input->get("pg")."reqCariMasalah"] = $this->input->get("reqCariMasalah");
        $reqCariSolusi = $_SESSION[$this->input->get("pg")."reqCariSolusi"] = $this->input->get("reqCariSolusi");
        $reqCariProses = $_SESSION[$this->input->get("pg")."reqCariProses"] = $this->input->get("reqCariProses");
        $reqCariStatus = $_SESSION[$this->input->get("pg")."reqCariStatus"] = $this->input->get("reqCariStatus");
        $reqCariDueDate = $_SESSION[$this->input->get("pg")."reqCariDueDate"] = $this->input->get("reqCariDueDate");
        $reqCariTanggalFrom = $_SESSION[$this->input->get("pg")."reqCariTanggalFrom"] = $this->input->get("reqCariTanggalFrom");
        $reqCariTanggalTo = $_SESSION[$this->input->get("pg")."reqCariTanggalTo"] = $this->input->get("reqCariTanggalTo");
        $reqCariDueDateTo = $_SESSION[$this->input->get("pg")."reqCariDueDateTo"] = $this->input->get("reqCariDueDateTo");
      
        if(!empty($reqCariDepartement)){
            $statement_privacy .=" AND (UPPER(A.DEPARTEMENT_ID) LIKE '%" . strtoupper($reqCariDepartement) . "%')";
        }
        if(!empty($reqCariMasalah)){
             $statement_privacy .=" AND (UPPER(A.MASALAH) LIKE '%" . strtoupper($reqCariMasalah) . "%')";
        }
        if(!empty($reqCariTanggalFrom) && !empty($reqCariTanggalTo) ){
             $statement_privacy .= " AND A.TANGGAL_MASALAH BETWEEN TO_DATE('" . $reqCariTanggalFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariTanggalTo . "','dd-mm-yyyy')";
           
        }
        if(!empty($reqCariSolusi)){
                    $statement_privacy .= " AND EXISTS( SELECT 1 FROM WEEKLY_PROSES_DETAIL CC WHERE CC.WEEKLY_PROSES_ID = A.WEEKLY_PROSES_ID 
                    AND (UPPER(CC.MASTER_SOLUSI_ID) LIKE '%" . strtoupper($reqCariSolusi) . "%')
                    )"   ;
        }
        if(!empty($reqCariProses)){
            $statement_privacy .= " AND EXISTS( SELECT 1 FROM WEEKLY_PROGRES_INLINE CC WHERE CC.WEEKLY_PROSES_ID = A.WEEKLY_PROSES_ID 
                    AND (UPPER(CC.PROSES) LIKE '%" . strtoupper($reqCariProses) . "%')
                    )"   ;
        }
        if(!empty($reqCariStatus)){
              $statement_privacy .= " AND EXISTS( SELECT 1 FROM WEEKLY_PROGRES_INLINE CC WHERE CC.WEEKLY_PROSES_ID = A.WEEKLY_PROSES_ID 
                    AND (UPPER(CC.STATUS) LIKE '%" . strtoupper($reqCariProses) . "%')
                    )"   ;
        }
        if(!empty($reqCariDueDate) && !empty($reqCariDueDateTo)){
             $statement_privacy .= " AND EXISTS( SELECT 1 FROM WEEKLY_PROGRES_INLINE CC WHERE CC.WEEKLY_PROSES_ID = A.WEEKLY_PROSES_ID 
                    AND CC.DUE_DATE BETWEEN TO_DATE('" . $reqCariDueDate . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariDueDateTo . "','dd-mm-yyyy')
                    )"   ;
          
        }
      
        $statement = " AND (UPPER(A.MASALAH) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $_SESSION[$this->input->get("pg")."statement"] =  $statement ." ". $statement_privacy;
        $_SESSION[$this->input->get("pg")."order"]      = $sOrder;


        $allRecord = $pms->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $pms->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $pms->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $pms->query;exit;
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
          $nomer=0;
        while ($pms->nextRow()) {
            $row = array();
            $ids = $pms->getField($aColumns[0]);
         $html = file_get_contents($this->config->item('base_report') . "report/index/tempalate_view_row_weekly/?reqId=".$ids);
         $total_pagination  = ($dsplyStart)+$nomer;
      $penomoran = $allRecordFilter-($total_pagination);
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NAME"){
                    $row[] = $pms->getField($aColumns[$i]);
                }if ($aColumns[$i] == "HTML"){
                    $row[] =$html;
                }  else if ($aColumns[$i] == "NO") {
                    $row[] = $penomoran;
                }
                else{
                    $row[] = $pms->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
               $nomer++;
        }
        echo json_encode($output);
    }

}
