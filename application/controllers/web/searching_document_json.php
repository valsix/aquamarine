<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class searching_document_json extends CI_Controller
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
        $this->load->model("Dokumen");
        $dokumen = new Dokumen();

        $aColumns = array(
            "CASH_REPORT_ID", "TANGGAL", "DESKRIPSI"
        );

        $aColumnsAlias = array(
            "CASH_REPORT_ID", "TANGGAL", "DESKRIPSI"
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
            if (trim($sOrder) == "ORDER BY A.CASH_REPORT_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY A.CASH_REPORT_ID asc";
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

        // $statement = " AND (UPPER(TANGGAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        // $allRecord = $dokumen->getCountByParams(array(), $statement_privacy . $statement);
        // // echo $allRecord;exit;
        // if ($_GET['sSearch'] == "")
        //     $allRecordFilter = $allRecord;
        // else
        //     $allRecordFilter =  $dokumen->getCountByParams(array(), $statement_privacy . $statement);


        $reqCariLocationFolder  = $this->input->get('reqCariLocationFolder');
        $reqCariFindText        = $this->input->get('reqCariFindText');

        $_SESSION[$this->input->get("pg")."reqCariLocationFolder"] = $reqCariLocationFolder;
        $_SESSION[$this->input->get("pg")."reqCariFindText"] = $reqCariFindText;
        
        $statements ='';
        $statements .= " AND A.CATEGORY='".$reqCariLocationFolder."'";
       
        if(!empty($reqCariFindText)){
                // $statements .= " AND A.CATEGORY='".$reqCariLocationFolder."'";
        }

        $dokumen->selectByParamsDokument(array(),-1,-1,$statements);
        $arrDatas =array();
        $no=0;
        while($dokumen->nextRow()){
            $path = $dokumen->getField('PATH');
            $paths =explode(',', $path);
            for($i=0;$i<count($paths);$i++){
                $arrDatas[$no]=$paths[$i];
                 $no++;
            }
       
        }
        $allRecord = count($arrDatas);
        $allRecordFilter = $allRecord;
        // print_r( $arrDatas);exit;
        
        // echo $cashReport->query;
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
        $name_folder = strtolower(str_replace(' ','_', $reqCariLocationFolder));
        if(empty($reqCariLocationFolder)){
            $arrDatas =array();
        }
        for($i=0;$i<count($arrDatas);$i++){
              $row = array();

               $texts =explode('-',$arrDatas[$i] );
               $open    ="openAdd('uploads/".$name_folder."/".$texts[0]."/".$arrDatas[$i]."')";
                       $row[] = $arrDatas[$i]; 
                       $row[] = $reqCariLocationFolder;  
                       $row[] = '<a onclick="'.$open.'" style="margin-left: 20px" ><i class="fa fa-download fa-lg"></i></a>'.' '.$texts[2];
                       

                $output['aaData'][] = $row;
        }
        // while ($dokumen->nextRow()) {
        //     $row = array();
        //     for ($i = 0; $i < count($aColumns); $i++) {
        //         if ($aColumns[$i] == "DESKRIPSI")
        //             $row[] = truncate($dokumen->getField($aColumns[$i]), 2);
        //         else
        //             $row[] = $dokumen->getField($aColumns[$i]);
        //     }
        //     $output['aaData'][] = $row;
        // }
        echo json_encode($output);
    }


    function add()
    {
        $this->load->model("Cash_report");
        $cashReport = new Cash_report();

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqTanggal = $this->input->post("reqTanggal");
        $reqDeskripsi = $this->input->post("reqDeskripsi");

        $cashReport->setField("CASH_REPORT_ID", $reqId);
        $cashReport->setField("TANGGAL", $reqTanggal);
        $cashReport->setField("DESKRIPSI", $reqDeskripsi);

        if ($reqMode == "insert") {
            $cashReport->insert();
        } else {
            $cashReport->update();
        }

        echo "Data berhasil disimpan.";
    }

    function delete()
    {
        $this->load->model("Cash_report");
        $cashReport = new Cash_report();

        $reqId = $this->input->get('reqId');

        $cashReport->setField("CASH_REPORT_ID", $reqId);
        if ($cashReport->delete())
            $arrJson["PESAN"] = "Data berhasil dihapus.";
        else
            $arrJson["PESAN"] = "Data gagal dihapus.";

        echo json_encode($arrJson);
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
