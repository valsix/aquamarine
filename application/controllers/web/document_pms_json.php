<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class document_pms_json extends CI_Controller
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
        $this->load->model("DocumentPms");
        $document_attacment = new DocumentPms();

        $aColumns = array("DOCUMENT_PMS_ID","NAME","DESCIPTION","PATH","EXTENSION","SIZE","PRIVIEW","AKSI");
        $aColumnsAlias = array("DOCUMENT_PMS_ID","NAME","DESCIPTION","PATH","EXTENSION","SIZE","PRIVIEW","AKSI");
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
            if (trim($sOrder) == "ORDER BY A.COST_REQUEST_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY A.COST_REQUEST_ID asc";
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

        $reqCariNoOrder              = $this->input->get('reqCariNoOrder');
        $reqCariCompanyName          = $this->input->get('reqCariCompanyName');
        $reqCariPeriodeYearFrom      = $this->input->get('reqCariPeriodeYearFrom');
        $reqCariPeriodeYearTo        = $this->input->get('reqCariPeriodeYearTo');
        $reqCariVasselName           = $this->input->get('reqCariVasselName');
        $reqCariGlobal               = $this->input->get('reqCariGlobal');

        // if (!empty($reqCariCompanyName)) {
        //     $statement .= " AND A.COMPANY_NAME LIKE '%" . $reqCariCompanyName . "%' ";
        // }
        // if (!empty($reqCariVasselName)) {
        //     $statement .= " AND A.VESSEL_NAME LIKE '%" . $reqCariVasselName . "%' ";
        // }
        // if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
        //     $statement .= " AND DATE_SERVICE1 BETWEEN  TO_CHAR(" . $reqCariPeriodeYearFrom . ", 'yyyy-MM-dd')  AND TO_CHAR(" . $reqCariPeriodeYearTo . ", 'yyyy-MM-dd') ";
        // }

        // if (!empty($reqCariNoOrder)) {
        //     $statement .= " AND A.NO_PROJECT LIKE '%" . $reqCariNoOrder . "%' ";
        // }





        $statement = " AND (UPPER(A.NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $document_attacment->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
        // echo $allRecord;
        // exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $document_attacment->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $document_attacment->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $projectCost->query;
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
        while ($document_attacment->nextRow()) {
            $row = array();
            $ids = $document_attacment->getField($aColumns[0]);
           
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NO_PROJECT") {
                    $row[] = truncate($document_attacment->getField($aColumns[$i]), 2);
                } else if ($aColumns[$i] == "NAME") {
                    // $Checks = '<input type="checkbox" value="'.$ids.'" name="reqIds[]"/>';
                    $row[] = $Checks.' '.$document_attacment->getField($aColumns[$i]);
                }else if ($aColumns[$i] == "PRIVIEW") {
                    $link= "'uploads/dok_pms/".$ids."/".$document_attacment->getField("PATH")."'";
                    $links='<a onclick="openAdd('.$link.');" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a>';
                    $row[] = $links;
                }else if($aColumns[$i] == "AKSI"){
                $btn_edit = '<button type="button"  class="btn btn-info " onclick=editing('.$nom.')  ><i class="fa fa-pencil-square-o fa-lg"> </i> </button>';
                $btn_delete = '<button type="button"  class="btn btn-danger hapusi"  onclick="deleting('.$nom.')"><i class="fa fa-trash-o fa-lg"> </i> </button>';

                $row[] =$btn_edit.$btn_delete;
            }  else {
                    $row[] = $document_attacment->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
              $nom++;
        }
        echo json_encode($output);
    }


    function add(){

         $this->load->model("DocumentPms");
         $document_attacment = new DocumentPms();

        $reqId= $this->input->post("reqId");

       
        $reqName= $this->input->post("reqName");
        $reqDesciption= $this->input->post("reqDescription");
        $reqPath= $this->input->post("reqPath");
        $reqExtension= $this->input->post("reqExtension");
        $reqSize= $this->input->post("reqSize");

        $document_attacment->setField("DOCUMENT_PMS_ID", $reqId);
        $document_attacment->setField("NAME", $reqName);
        $document_attacment->setField("DESCIPTION", $reqDesciption);
        $document_attacment->setField("PATH", $reqPath);
        $document_attacment->setField("EXTENSION", $reqExtension);
        $document_attacment->setField("SIZE", $reqSize);

        if(empty($reqId)||$reqId=='-1'){
            $document_attacment->insert();
            $reqId =$document_attacment->id;
        }else{
            $document_attacment->update();
        }

        $reqTipe            = $this->input->post('reqTipe');

        $name_folder = strtolower(str_replace(' ','_', $reqTipe));
        $reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
        $this->load->library("FileHandler");
        $file = new FileHandler();
        $filesData=$_FILES["document"];
        $FILE_DIR= "uploads/".$name_folder."/".$reqId."/";         
        makedirs($FILE_DIR);

        $arrData =array();
        for($i=0;$i<count($filesData);$i++){
            $renameFile = $reqId.'-'.$i."-".getExtension($filesData['name'][$i]);
            if($file->uploadToDirArray('document', $FILE_DIR, $renameFile, $i))
            {
                array_push($arrData, setQuote($renameFile));

            }else{
                array_push($arrData, $reqLinkFileTemp[$i]);

            }
        }

        $str_name_path='';
        for($i=0;$i<count($arrData);$i++){
            if(!empty($arrData[$i])){
                if($i==0){
                    $str_name_path .=$arrData[$i];
                }else{
                    $str_name_path .=','.$arrData[$i];    
                }
            }    
        }
            $document_attacment = new DocumentPms();
            $document_attacment->setField("DOCUMENT_PMS_ID", $reqId);
            $document_attacment->setField("PATH", ($str_name_path));
            $document_attacment->update_path();
        
        echo 'Data berhasil disimpan';
    }

    function add_path_lampiran(){
        $reqIds = $this->input->get("reqId");
        $reqId = explode(",", $reqIds);
        $this->load->model("DocumentPms");
        $statement ='';
        for($i=0;$i<count($reqId);$i++){
            if(!empty($reqId[$i])){
                if($i==0){
                     $statement .= " AND A.DOCUMENT_PMS_ID='".$reqId[$i]."'";
                }else{
                    $statement .= "  OR A.DOCUMENT_PMS_ID='".$reqId[$i]."'";
                }
            }
        }
       
         $document_attacment = new DocumentPms();
         $document_attacment->selectByParamsMonitoring(array(),-1,-1, $statement);
         $str ='';
         while ($document_attacment->nextRow()) {
            $link = "'uploads/eccommerce/".$document_attacment->getField('DOCUMENT_PMS_ID')."/".$document_attacment->getField("PATH")."'";
            $str .='<a onclick="openAdd('.$link.');" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file"> '.$document_attacment->getField('PATH').' </span>';
             # code...
         }

         echo $str;

    }

    function delete()
    {
        $reqId = $this->input->get('reqId');
        $this->load->model("DocumentPms");
        $document_attacment = new DocumentPms();

        
      

        $document_attacment->setField("DOCUMENT_PMS_ID", $reqId);
        $document_attacment->delete();
        

        echo 'Data berhasil di hapus';
    }

    
}
