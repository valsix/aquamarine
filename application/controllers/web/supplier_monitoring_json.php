<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");


// include_once("lib/excel/excel_reader2.php");


class supplier_monitoring_json extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->kauth->getInstance()->hasIdentity()) {
            redirect('login');
        }

        // $this->db->query("SET DATESTYLE TO PostgreSQL,European;");
        // $this->USERID = $this->kauth->getInstance()->geUSERDentity()->USERID;
        // $this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
        // $this->FULLNAME = $this->kauth->getInstance()->getIdentity()->FULLNAME;
        // $this->USERPASS = $this->kauth->getInstance()->getIdentity()->USERPASS;
        // $this->LEVEL = $this->kauth->getInstance()->getIdentity()->LEVEL;
        // $this->MENUMARKETING = $this->kauth->getInstance()->getIdentity()->MENUMARKETING;
        // $this->MENUFINANCE = $this->kauth->getInstance()->getIdentity()->MENUFINANCE;
        // $this->MENUPRODUCTION = $this->kauth->getInstance()->getIdentity()->MENUPRODUCTION;
        // $this->MENUDOCUMENT = $this->kauth->getInstance()->getIdentity()->MENUDOCUMENT;
        // $this->MENUSEARCH = $this->kauth->getInstance()->getIdentity()->MENUSEARCH;
        // $this->MENUOTHERS = $this->kauth->getInstance()->getIdentity()->MENUOTHERS;
    }



    function json()
    {   
        $this->load->model("Customer");
        $this->load->model("CostumerSupport");
        $customer = new Customer();

        $reqId = $this->input->get("reqId");
        $reqMode = $this->input->get("reqMode");

        $reqCariCompanyName = $this->input->get("reqCariCompanyName");
        $reqCariContactPerson = $this->input->get("reqCariContactPerson");
        $reqCariVasselName = $this->input->get("reqCariVasselName");
        $reqCariEmailPerson = $this->input->get("reqCariEmailPerson");
        $reqCheck = $this->input->get("reqCheck");
        $reqEmails = $this->input->get("reqEmail");

        $_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariContactPerson"] = $reqCariContactPerson;
        $_SESSION[$this->input->get("pg")."reqCariVasselName"] = $reqCariVasselName;
        $_SESSION[$this->input->get("pg")."reqCariEmailPerson"] = $reqCariEmailPerson;


        // echo $reqKategori;exit;

        $aColumns = array(
            "COMPANY_ID", "", "CHECK","NO", "NAME", "ADDRESS", "BARAG_JASA","CP1_NAME", "HARGA_KET", "TINGKAT_PELAYANG", "KUALITAS",
            "KETERANGAN_SUB","SUPPORT"
        );
        $aColumnsAlias = array(
            "COMPANY_ID", "", "CHECK","NO", "NAME", "ADDRESS", "BARAG_JASA","CP1_NAME", "HARGA_KET", "TINGKAT_PELAYANG", "KUALITAS",
            "KETERANGAN_SUB","SUPPORT"
        );

        /*
                        $sOrder .= " asc, ";
                    } else {
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
                        $sOrder .= " desc, ";
                    }
                }
            }

            //Remove the last space / comma
            $sOrder = substr_replace($sOrder, "", -2);

            //Check if there is an order by clause
            if (trim($sOrder) == "ORDER BY COMPANY_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY COMPANY_ID desc";
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
            $sWhere = "AND 1=1";
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

        // if ($reqMode == "validasi")
        //     $statement_privacy = " AND VALIDASI = '0' ";
        // elseif ($reqMode == "tolak")
        //     $statement_privacy = " AND VALIDASI = 'X' ";
        // else
        //     $statement_privacy = " AND VALIDASI = '1' ";


        if (!empty($reqId)) {
            $statement_privacy = " AND A.COMPANY_ID = '" . $reqId . "' ";
        }

        if (!empty($reqCariCompanyName)) {
            $statement_privacy .= " AND UPPER(A.NAME) LIKE UPPER('%" . $reqCariCompanyName . "%') ";
        }
        if (!empty($reqCariContactPerson)) {
            $statement_privacy .= " AND UPPER(A.CP1_NAME) LIKE UPPER('%" . $reqCariContactPerson . "%') ";
        }
        if (!empty($reqCariVasselName)) {
            $statement_privacy .= " AND EXISTS(SELECT 1 FROM vessel v WHERE v.COMPANY_ID = a.COMPANY_ID AND UPPER(v.NAME) LIKE UPPER('%" . $reqCariVasselName . "%')) ";
        }
        if (!empty($reqCariEmailPerson)) {
              $statement_privacy .= " AND UPPER(A.EMAIL) LIKE UPPER('%" . $reqCariEmailPerson . "%') ";
        }
        if ($reqEmails == 'not') {
            $statement_privacy .= " AND A.EMAIL IS NOT NULL  AND A.EMAIL <> '' AND A.EMAIL <> '-' ";
        }

         $reqKategori = $this->input->get('reqKategori');
        if(empty($reqKategori)){
            $statement_privacy .= " AND ( A.KATEGORI IS NULL OR A.KATEGORI='')";
        }else{
            $statement_privacy .= " AND  A.KATEGORI ='".$reqKategori."'";
        }
        // echo  $statement_privacy;
        // if (!empty($reqGolonganDarah)) {
        //     $statement_privacy .= " AND UPPER(GOLONGAN_DARAH) = '" . $reqGolonganDarah . "' ";
        // }
        // if (!empty($reqJenisKelamin)) {
        //     $statement_privacy .= " AND UPPER(JENIS_KELAMIN) = '" . $reqJenisKelamin . "' ";
        // }


        $statement = "AND (UPPER(NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $customer->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $customer->getCountByParams(array(), $statement_privacy . $statement);

        $customer->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $customer->query;exit;
        
    
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $allRecord,
            "iTotalDisplayRecords" => $allRecordFilter,
            "aaData" => array()
        );
         $nomer=0;
        while ($customer->nextRow()) {
            $row = array();
            $total_support = $customer->getField("SUPPORT");
            $total_pagination  = ($dsplyStart)+$nomer;
            $penomoran = $allRecordFilter-($total_pagination);
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "ADDRESS"){
                     $row[] = $customer->getField($aColumns[$i]);
                    // $row[] = truncate($customer->getField($aColumns[$i]), 10) . "...";
                }elseif ($aColumns[$i] == "LINK_FILE"){
                    $row[] = "<img src='uploads/" . $customer->getField($aColumns[$i]) . "' height='50px'>";
                }
                elseif ($aColumns[$i] == "CHECK") {
                    if ($reqCheck != 'tidak') {
                        $row[] = '<input type="checkbox" value="' . $customer->getField('COMPANY_ID') . '" name="reqIds[]"/>';
                    }
                } elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
                    if ($customer->getField($aColumns[$i]) == "Y")
                        $row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
                    else
                        $row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
                }  else if ($aColumns[$i] == "NO") {
                    $row[] = $penomoran;
                }else if ($aColumns[$i] == "SUPPORT") {
                   
                    $text ="No Record display";
                    if($total_support > 0){
                            $costumer_support = new CostumerSupport();
                            $costumer_support->selectByParamsMonitoring(array("A.COMPANY_ID"=>$customer->getField("COMPANY_ID")));
                            $text ='<table class="table table-striped"> <thead>
                            <tr>
                            <th style="width: 50%">NAMA </th>
                            <th style="width: 20%">TELP / HP </th>
                            <th style="width: 30%">Email </th>

                            </tr>
                            </thead>
                            <tbody>';

                            while ($costumer_support->nextRow()) {


                                $text .='<tr>
                                <td>'.$costumer_support->getField("NAMA").' </td>
                                <td>'.$costumer_support->getField("TELP").' </td>
                                <td>'.$costumer_support->getField("EMAIL").'</td>                            
                                </tr>';

                            }
                            $text .='      
                            </tbody>
                            </table>';
                      
                    }
                     $row[] =$text; 
                } else
                    $row[] = $customer->getField($aColumns[$i]);
            }
            $output['aaData'][] = $row;
               $nomer++;
        }
        echo json_encode($output);
    }

    function lookup_json()
    {   
        $this->load->model("Customer");
        $customer = new Customer();

        $reqId = $this->input->get("reqId");
        $reqMode = $this->input->get("reqMode");

        $reqCariCompanyName = $this->input->get("reqCariCompanyName");
        $reqCariContactPerson = $this->input->get("reqCariContactPerson");
        $reqCariVasselName = $this->input->get("reqCariVasselName");
        $reqCariEmailPerson = $this->input->get("reqCariEmailPerson");
        $reqCheck = $this->input->get("reqCheck");
        $reqEmails = $this->input->get("reqEmail");

        $_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariContactPerson"] = $reqCariContactPerson;
        $_SESSION[$this->input->get("pg")."reqCariVasselName"] = $reqCariVasselName;
        $_SESSION[$this->input->get("pg")."reqCariEmailPerson"] = $reqCariEmailPerson;


        // echo $reqKategori;exit;

        $aColumns = array(
            "COMPANY_ID", "CHECK", "NAME", "EMAIL","CP1_NAME","CP1_TELP"
        );
        $aColumnsAlias = array(
            "COMPANY_ID", "CHECK", "NAME", "EMAIL","CP1_NAME","CP1_TELP"
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
            if (trim($sOrder) == "ORDER BY COMPANY_ID asc") {
                /*
                * If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
                * If there is no order by clause there might be bugs in table display.
                * No order by clause means that the db is not responsible for the data ordering,
                * which means that the same row can be displayed in two pages - while
                * another row will not be displayed at all.
                */
                $sOrder = " ORDER BY COMPANY_ID desc";
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
            $sWhere = "AND 1=1";
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

        // if ($reqMode == "validasi")
        //     $statement_privacy = " AND VALIDASI = '0' ";
        // elseif ($reqMode == "tolak")
        //     $statement_privacy = " AND VALIDASI = 'X' ";
        // else
        //     $statement_privacy = " AND VALIDASI = '1' ";


        if (!empty($reqId)) {
            $statement_privacy = " AND A.COMPANY_ID = '" . $reqId . "' ";
        }

        if (!empty($reqCariCompanyName)) {
            $statement_privacy .= " AND UPPER(A.NAME) LIKE UPPER('%" . $reqCariCompanyName . "%') ";
        }
        if (!empty($reqCariContactPerson)) {
            $statement_privacy .= " AND UPPER(A.CP1_NAME) LIKE UPPER('%" . $reqCariContactPerson . "%') ";
        }
        if (!empty($reqCariVasselName)) {
            $statement_privacy .= " AND EXISTS(SELECT 1 FROM vessel v WHERE v.COMPANY_ID = a.COMPANY_ID AND UPPER(v.NAME) LIKE UPPER('%" . $reqCariVasselName . "%')) ";
        }
        if (!empty($reqCariEmailPerson)) {
              $statement_privacy .= " AND UPPER(A.EMAIL) LIKE UPPER('%" . $reqCariEmailPerson . "%') ";
        }
        if ($reqEmails == 'not') {
            $statement_privacy .= " AND A.EMAIL IS NOT NULL  AND A.EMAIL <> '' AND A.EMAIL <> '-' ";
        }
        

        // echo  $statement_privacy;
        // if (!empty($reqGolonganDarah)) {
        //     $statement_privacy .= " AND UPPER(GOLONGAN_DARAH) = '" . $reqGolonganDarah . "' ";
        // }
        // if (!empty($reqJenisKelamin)) {
        //     $statement_privacy .= " AND UPPER(JENIS_KELAMIN) = '" . $reqJenisKelamin . "' ";
        // }


        $statement = "AND (UPPER(NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $customer->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $customer->getCountByParams(array(), $statement_privacy . $statement);

        $customer->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $customer->query;exit;
        
    
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $allRecord,
            "iTotalDisplayRecords" => $allRecordFilter,
            "aaData" => array()
        );

        while ($customer->nextRow()) {
            $row = array();
            $total_support = $customer->getField("SUPPORT");
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NAME")
                     $row[] = $customer->getField($aColumns[$i]);
                    // $row[] = truncate($customer->getField($aColumns[$i]), 10) . "...";
                elseif ($aColumns[$i] == "LINK_FILE")
                    $row[] = "<img src='uploads/" . $customer->getField($aColumns[$i]) . "' height='50px'>";
                elseif ($aColumns[$i] == "CHECK") {
                    if ($reqCheck != 'tidak') {
                        $row[] = '<input type="checkbox" value="' . $customer->getField('COMPANY_ID') . '" name="reqIds[]"/>';
                    }
                } elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
                    if ($customer->getField($aColumns[$i]) == "Y")
                        $row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
                    else
                        $row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
                } else
                    $row[] = $customer->getField($aColumns[$i]);
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }

    function combo_json()
    {
        $this->load->model("Customer");
        $this->load->model("CostumerSupport");

        $customer = new Customer();

        $reqId = $this->input->get("reqId");
        $reqMode = $this->input->get("reqMode");

        $reqCariCompanyName = $this->input->get("reqCariCompanyName");
        $reqCariContactPerson = $this->input->get("reqCariContactPerson");
        $reqCariVasselName = $this->input->get("reqCariVasselName");
        $reqCariEmailPerson = $this->input->get("reqCariEmailPerson");
        $reqCheck = $this->input->get("reqCheck");
        $reqEmails = $this->input->get("reqEmail");
        // echo $reqEmails;


        // echo $reqKategori;exit;

        
        $aColumns = array(
            "COMPANY_ID", "", "CHECK", "NAME", "ADDRESS", "PHONE", "FAX", "EMAIL", "CP1_NAME",
            "CP1_TELP", "SUPPORT"
        );
        $aColumnsAlias = array(
            "COMPANY_ID", "", "CHECK", "NAME", "ADDRESS", "PHONE", "FAX", "EMAIL", "CP1_NAME",
            "CP1_TELP", "SUPPORT"
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
            if (trim($sOrder) == "ORDER BY COMPANY_ID asc") {
                /*
                * If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
                * If there is no order by clause there might be bugs in table display.
                * No order by clause means that the db is not responsible for the data ordering,
                * which means that the same row can be displayed in two pages - while
                * another row will not be displayed at all.
                */
                $sOrder = " ORDER BY COMPANY_ID desc";
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
            $sWhere = "AND 1=1";
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

        // if ($reqMode == "validasi")
        //     $statement_privacy = " AND VALIDASI = '0' ";
        // elseif ($reqMode == "tolak")
        //     $statement_privacy = " AND VALIDASI = 'X' ";
        // else
        //     $statement_privacy = " AND VALIDASI = '1' ";


        if (!empty($reqId)) {
            $statement_privacy = " AND A.COMPANY_ID = '" . $reqId . "' ";
        }

        if (!empty($reqCariCompanyName)) {
            $statement_privacy .= " AND UPPER(A.NAME) LIKE UPPER('%" . $reqCariCompanyName . "%') ";
        }
        if (!empty($reqCariContactPerson)) {
            $statement_privacy .= " AND UPPER(A.CP1_NAME) LIKE UPPER('%" . $reqCariContactPerson . "%') ";
        }
        if (!empty($reqCariVasselName)) {
            $statement_privacy .= " AND EXISTS(SELECT 1 FROM vessel v WHERE v.COMPANY_ID = a.COMPANY_ID AND UPPER(v.NAME) LIKE UPPER('%" . $reqCariVasselName . "%')) ";
        }
        if (!empty($reqCariEmailPerson)) {
              $statement_privacy .= " AND UPPER(A.EMAIL) LIKE UPPER('%" . $reqCariEmailPerson . "%') ";
        }
        if ($reqEmails == 'not') {
            $statement_privacy .= " AND A.EMAIL IS NOT NULL  AND A.EMAIL <> '' AND A.EMAIL <> '-' ";
        }

        $reqKategori = $this->input->get('reqKategori');

        if(!empty( $reqKategori)){
                $statement_privacy .=" AND A.KATEGORI='".$reqKategori."'";
        }else{
             $statement_privacy .=" AND A.KATEGORI is null ";
        }

        // echo  $statement_privacy;
        // if (!empty($reqGolonganDarah)) {
        //     $statement_privacy .= " AND UPPER(GOLONGAN_DARAH) = '" . $reqGolonganDarah . "' ";
        // }
        // if (!empty($reqJenisKelamin)) {
        //     $statement_privacy .= " AND UPPER(JENIS_KELAMIN) = '" . $reqJenisKelamin . "' ";
        // }


        $statement = "AND (UPPER(NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $customer->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $customer->getCountByParams(array(), $statement_privacy . $statement);

        $customer->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $customer->query;exit;
        


    
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $allRecord,
            "iTotalDisplayRecords" => $allRecordFilter,
            "aaData" => array()
        );

        while ($customer->nextRow()) {
            $costumer_support = new CostumerSupport();
            $total_support = $customer->getField("SUPPORT");
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NAME")
                     $row[] = $customer->getField($aColumns[$i]);
                    // $row[] = truncate($customer->getField($aColumns[$i]), 10) . "...";
                elseif ($aColumns[$i] == "LINK_FILE")
                    $row[] = "<img src='uploads/" . $customer->getField($aColumns[$i]) . "' height='50px'>";
                elseif ($aColumns[$i] == "CHECK") {
                    if ($reqCheck != 'tidak') {
                        $row[] = '<input type="checkbox" value="' . $customer->getField('COMPANY_ID') . '" name="reqIds[]"/>';
                    }
                } elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
                    if ($customer->getField($aColumns[$i]) == "Y")
                        $row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
                    else
                        $row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
                } elseif ($aColumns[$i] == "SUPPORT" ){
                    $text ="No Record display";
                    if($total_support > 0){
                            $costumer_support = new CostumerSupport();
                            $costumer_support->selectByParamsMonitoring(array("A.COMPANY_ID"=>$customer->getField("COMPANY_ID")));
                            $text ='<table class="table table-striped"> <thead>
                            <tr>
                            <th style="width: 50%">NAMA </th>
                            <th style="width: 20%">TELP / HP </th>
                            <th style="width: 30%">Email </th>

                            </tr>
                            </thead>
                            <tbody>';

                            while ($costumer_support->nextRow()) {


                                $text .='<tr>
                                <td>'.$costumer_support->getField("NAMA").' </td>
                                <td>'.$costumer_support->getField("TELP").' </td>
                                <td>'.$costumer_support->getField("EMAIL").'</td>                            
                                </tr>';

                            }
                            $text .='      
                            </tbody>
                            </table>';
                      
                    }
                     $row[] =$text; 
                } else
                    $row[] = $customer->getField($aColumns[$i]);
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }

    function json_lookup()
    {
        $this->load->model("Customer");
        $customer = new Customer();

        $reqId = $this->input->get("reqId");
        $reqMode = $this->input->get("reqMode");

        $reqCariCompanyName = $this->input->get("reqCariCompanyName");
        $reqCariContactPerson = $this->input->get("reqCariContactPerson");
        $reqCariVasselName = $this->input->get("reqCariVasselName");
        $reqCariEmailPerson = $this->input->get("reqCariEmailPerson");
        $reqCheck = $this->input->get("reqCheck");
        $reqEmails = $this->input->get("reqEmail");
        // echo $reqEmails;


        // echo $reqKategori;exit;

        $aColumns = array(
            "COMPANY_ID", "NAME", "ADDRESS", "PHONE", "FAX", "EMAIL", "CP1_NAME",
            "CP1_TELP", "CP2_NAME", "CP2_TELP"
        );
        $aColumnsAlias = array(
            "COMPANY_ID", "NAME", "ADDRESS", "PHONE", "FAX", "EMAIL", "CP1_NAME",
            "CP1_TELP", "CP2_NAME", "CP2_TELP"
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
            if (trim($sOrder) == "ORDER BY COMPANY_ID asc") {
                /*
                * If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
                * If there is no order by clause there might be bugs in table display.
                * No order by clause means that the db is not responsible for the data ordering,
                * which means that the same row can be displayed in two pages - while
                * another row will not be displayed at all.
                */
                $sOrder = " ORDER BY COMPANY_ID desc";
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
            $sWhere = "AND 1=1";
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

        // if ($reqMode == "validasi")
        //     $statement_privacy = " AND VALIDASI = '0' ";
        // elseif ($reqMode == "tolak")
        //     $statement_privacy = " AND VALIDASI = 'X' ";
        // else
        //     $statement_privacy = " AND VALIDASI = '1' ";


        if (!empty($reqId)) {
            $statement_privacy = " AND A.COMPANY_ID = '" . $reqId . "' ";
        }

        if (!empty($reqCariCompanyName)) {
            $statement_privacy .= " AND UPPER(A.NAME) LIKE UPPER('%" . $reqCariCompanyName . "%') ";
        }
        if (!empty($reqCariContactPerson)) {
            $statement_privacy .= " AND UPPER(A.CP1_NAME) LIKE UPPER('%" . $reqCariContactPerson . "%') ";
        }
        if (!empty($reqCariVasselName)) {
            $statement_privacy .= " AND EXISTS(SELECT 1 FROM vessel v WHERE v.COMPANY_ID = a.COMPANY_ID AND UPPER(v.NAME) LIKE UPPER('%" . $reqCariVasselName . "%')) ";
        }
        if (!empty($reqCariEmailPerson)) {
              $statement_privacy .= " AND UPPER(A.EMAIL) LIKE UPPER('%" . $reqCariEmailPerson . "%') ";
        }
        if ($reqEmails == 'not') {
            $statement_privacy .= " AND A.EMAIL IS NOT NULL  AND A.EMAIL <> '' AND A.EMAIL <> '-' ";
        }

        $reqKategori = $this->input->get('reqKategori');
        if(empty($reqKategori)){
            $statement_privacy .= " AND ( A.KATEGORI IS NULL OR A.KATEGORI='')";
        }else{
            $statement_privacy .= " AND  A.KATEGORI ='".$reqKategori."'";
        }
        // echo  $statement_privacy;
        // if (!empty($reqGolonganDarah)) {
        //     $statement_privacy .= " AND UPPER(GOLONGAN_DARAH) = '" . $reqGolonganDarah . "' ";
        // }
        // if (!empty($reqJenisKelamin)) {
        //     $statement_privacy .= " AND UPPER(JENIS_KELAMIN) = '" . $reqJenisKelamin . "' ";
        // }


        $statement = "AND (UPPER(NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $customer->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $customer->getCountByParams(array(), $statement_privacy . $statement);

        $customer->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $customer->query;exit;
        
    
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $allRecord,
            "iTotalDisplayRecords" => $allRecordFilter,
            "aaData" => array()
        );

        while ($customer->nextRow()) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NAME")
                     $row[] = $customer->getField($aColumns[$i]);
                    // $row[] = truncate($customer->getField($aColumns[$i]), 10) . "...";
                elseif ($aColumns[$i] == "LINK_FILE")
                    $row[] = "<img src='uploads/" . $customer->getField($aColumns[$i]) . "' height='50px'>";
                elseif ($aColumns[$i] == "CHECK") {
                    if ($reqCheck != 'tidak') {
                        $row[] = '<input type="checkbox" value="' . $customer->getField('COMPANY_ID') . '" name="reqIds[]"/>';
                    }
                } elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
                    if ($customer->getField($aColumns[$i]) == "Y")
                        $row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
                    else
                        $row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
                } else
                    $row[] = $customer->getField($aColumns[$i]);
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }

    function add()
    {
        $this->load->model("Customer");
        $customer = new Customer();

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqName = $this->input->post("reqName");
        $reqAddress = $this->input->post("reqAddress");
        $reqPhone = $this->input->post("reqPhone");
        $reqFax = $this->input->post("reqFax");
        $reqEmail = $this->input->post("reqEmail");
        $reqCp1Name = $this->input->post("reqCp1Name");
        $reqCp1Telp = $this->input->post("reqCp1Telp");
        $reqCp2Name = $this->input->post("reqCp2Name");
        $reqCp2Telp = $this->input->post("reqCp2Telp");

        //	echo $reqNomorHp	;
        $customer->setField("COMPANY_ID", $reqId);
        $customer->setField("NAME", $reqName);
        $customer->setField("ADDRESS", $reqAddress);
        $customer->setField("PHONE", $reqPhone);
        $customer->setField("FAX", $reqFax);
        $customer->setField("EMAIL", $reqEmail);
        $customer->setField("CP1_NAME", $reqCp1Name);
        $customer->setField("CP1_TELP", $reqCp1Telp);
        $customer->setField("CP2_NAME", $reqCp2Name);
        $customer->setField("CP2_TELP", $reqCp2Telp);

        if ($reqMode == "insert") {
            // $customer->setField("CREATED_BY", $this->USERNAME);
            $customer->insert();
        } else {
            // $customer->setField("UPDATED_BY", $this->USERNAME);
            $customer->update();
        }

        echo "Data berhasil disimpan.";
    }


    function add_new()
    {
        $this->load->model("Company");
        $this->load->model("Vessel");
        $company = new Company();


        $reqMode = $this->input->post("reqMode");
        $reqId = $this->input->post("reqId");

        $reqCompanyId = $this->input->post("reqCompanyId");
        $reqName = $this->input->post("reqName");
        $reqAddress = $_POST["reqAddress"];
        $reqPhone = $this->input->post("reqPhone");
        $reqFax = $this->input->post("reqFax");
        $reqEmail = $this->input->post("reqEmail");
        $reqCp1Name = $this->input->post("reqCp1Name");
        $reqCp1Telp = $this->input->post("reqCp1Telp");
        $reqCp2Name = $this->input->post("reqCp2Name");
        $reqCp2Telp = $this->input->post("reqCp2Telp");
        $reqLa1Name = $this->input->post("reqLa1Name");
        $reqLa1Address = $_POST["reqLa1Address"];
        $reqLa1Phone = $this->input->post("reqLa1Phone");
        $reqLa1Fax = $this->input->post("reqLa1Fax");
        $reqLa1Email = $this->input->post("reqLa1Email");
        $reqLa1Cp1 = $this->input->post("reqLa1Cp1");
        $reqLa1Cp2 = $this->input->post("reqLa1Cp2");
        $reqLa2Name = $this->input->post("reqLa2Name");
        $reqLa2Address = $_POST["reqLa2Address"];
        $reqLa2Telp = $this->input->post("reqLa2Telp");
        $reqLa2Fax = $this->input->post("reqLa2Fax");
        $reqLa2Email = $this->input->post("reqLa2Email");
        $reqLa2Cp1 = $this->input->post("reqLa2Cp1");
        $reqLa2Cp2 = $this->input->post("reqLa2Cp2");
        $reqLa1Cp1Phone = $this->input->post("reqLa1Cp1Phone");
        $reqLa1Cp2Phone = $this->input->post("reqLa1Cp2Phone");
        $reqLa2Cp1Phone = $this->input->post("reqLa2Cp1Phone");
        $reqLa2Cp2Phone = $this->input->post("reqLa2Cp2Phone");
        $reqTipe = $this->input->post("reqTipe");


        $company->setField("COMPANY_ID", $reqId);
        $company->setField("NAME", $reqName);
        $company->setField("ADDRESS", $reqAddress);
        $company->setField("PHONE", $reqPhone);
        $company->setField("FAX", $reqFax);
        $company->setField("EMAIL", $reqEmail);
        $company->setField("CP1_NAME", $reqCp1Name);
        $company->setField("CP1_TELP", $reqCp1Telp);
        $company->setField("CP2_NAME", $reqCp2Name);
        $company->setField("CP2_TELP", $reqCp2Telp);
        $company->setField("LA1_NAME", $reqLa1Name);
        $company->setField("LA1_ADDRESS", $reqLa1Address);
        $company->setField("LA1_PHONE", $reqLa1Phone);
        $company->setField("LA1_FAX", $reqLa1Fax);
        $company->setField("LA1_EMAIL", $reqLa1Email);
        $company->setField("LA1_CP1", $reqLa1Cp1);
        $company->setField("LA1_CP2", $reqLa1Cp2);
        $company->setField("LA2_NAME", $reqLa2Name);
        $company->setField("LA2_ADDRESS", $reqLa2Address);
        $company->setField("LA2_TELP", $reqLa2Telp);
        $company->setField("LA2_FAX", $reqLa2Fax);
        $company->setField("LA2_EMAIL", $reqLa2Email);
        $company->setField("LA2_CP1", $reqLa2Cp1);
        $company->setField("LA2_CP2", $reqLa2Cp2);
        $company->setField("LA1_CP1_PHONE", $reqLa1Cp1Phone);
        $company->setField("LA1_CP2_PHONE", $reqLa1Cp2Phone);
        $company->setField("LA2_CP1_PHONE", $reqLa2Cp1Phone);
        $company->setField("LA2_CP2_PHONE", $reqLa2Cp2Phone);
        $company->setField("TIPE", $reqTipe);



        if ($reqMode == "insert") {
            $company->insert();
            $reqId  = $company->id;
        } else {
            $company->update();
        }


        $reqSupplier = $this->input->post('reqSupplier');
        $reqBarangDisuplay = $this->input->post('reqBarangDisuplay');
        $reqTingkatPelayanan = $this->input->post('reqTingkatPelayanan');
        $reqKualitas = $this->input->post('reqKualitas');
        $reqKeterangan = $this->input->post('reqKeterangan');
        if($reqSupplier=='SUPPLIER'){
             $company->setField("COMPANY_ID", $reqId);
             $company->setField("KATEGORI", $reqSupplier);
              $company->setField("BARAG_JASA", $reqBarangDisuplay);
               $company->setField("TINGKAT_PELAYANG", $reqTingkatPelayanan);
                $company->setField("KUALITAS", $reqKualitas);
                 $company->setField("KETERANGAN_SUB", $reqKeterangan);
                 
             $company->updateSupplier();
             $company-> updateSupplierLain();
            

             $this->tambah_data_barang($reqId);
        }


        echo $reqId . '-Data berhasil di simpan';
    }

    function tambah_data_barang($reqId){
        $this->load->model("SupplierBarang");

        $reqSatuan   = $this->input->post('reqSatuan');
        $reqQty = $this->input->post('reqQty');
        $reqHarga = $this->input->post('reqHarga');
        $reqBarangNama = $this->input->post('reqBarangNama');
        $reqSupplierBarangId = $this->input->post('reqSupplierBarangId');

        for($i=0;$i<count($reqSupplierBarangId);$i++){
            $reqSubBarngId = $reqSupplierBarangId[$i];
            $supplierbarang = new SupplierBarang();
            $supplierbarang->setField("SUPPLIER_BARANG_ID",$reqSubBarngId);
            $supplierbarang->setField("NAMA",$reqBarangNama[$i]);
            $supplierbarang->setField("QTY",ifZero2(dotToNo($reqQty[$i])));
            $supplierbarang->setField("HARGA",ifZero2(dotToNo($reqHarga[$i])));
            $supplierbarang->setField("SATUAN",$reqSatuan[$i]);
            $supplierbarang->setField("SUPPLIER_ID",$reqId);

            if(empty($reqSubBarngId)){
                $supplierbarang->insert();
            }else{
                $supplierbarang->update();
            }

         }
        
    }


    function koreksi()
    {
        $this->load->model("Pegawai");
        $pegawai = new Pegawai();

        $reqMode = $this->input->post("reqMode");
        $reqId = $this->input->post("reqId");
        $reqNrp = $this->input->post("reqNrp");
        $reqNip = $this->input->post("reqNip");
        $reqSekar = $this->input->post("reqSekar");
        //	echo $reqNomorHp	;
        $pegawai->setField("PEGAWAI_ID", $reqId);
        $pegawai->setField("NRP", $reqNrp);
        $pegawai->setField("NIP", $reqNip);
        $pegawai->setField("NO_SEKAR", $reqSekar);
        $pegawai->setField("UPDATED_BY", $this->USERNAME);
        $pegawai->koreksi();
        echo "Data berhasil disimpan.";
    }



    // function validasi()
    // {
    //     $this->load->model("Pegawai");
    //     $pegawai = new Pegawai();

    //     $reqMode                         = $this->input->post("reqMode");
    //     $reqId                             = $this->input->post("reqId");
    //     $reqNrp                            = $this->input->post("reqNrp");
    //     $reqNip                            = $this->input->post("reqNip");
    //     $reqNama                        = $this->input->post("reqNama");
    //     $reqNamaPanggilan                = $this->input->post("reqNamaPanggilan");
    //     $reqJenisKelamin                = $this->input->post("reqJenisKelamin");
    //     $reqTempatLahir                    = $this->input->post("reqTempatLahir");
    //     $reqTanggalLahir                = $this->input->post("reqTanggalLahir");
    //     $reqUnitKerja                = $this->input->post("reqUnitKerja");
    //     $reqAlamat                    = $this->input->post("reqAlamat");
    //     $reqNomorHp                    = $this->input->post("reqNomorHp");
    //     $reqEmailPribadi        = $this->input->post("reqEmailPribadi");
    //     $reqEmailBulog            = $this->input->post("reqEmailBulog");
    //     $reqNomorWa                    = $this->input->post("reqNomorWa");
    //     $reqValidasi                    = $this->input->post("reqValidasi");
    //     //	echo $reqNomorHp	;

    //     if ($reqValidasi == "TOLAK")
    //         $reqValidasi = "X";
    //     else
    //         $reqValidasi = "1";

    //     $pegawai->setField("VALIDASI", $reqValidasi);
    //     $pegawai->setField("PEGAWAI_ID", $reqId);
    //     $pegawai->setField("UPDATED_BY", $this->USERNAME);
    //     if ($reqValidasi == "X")
    //         $pegawai->validasi();
    //     else
    //         $pegawai->validasiSetuju();

    //     echo "Data berhasil disimpan.";
    // }

    // function import()
    // {
    //     include "libraries/excel/excel_reader2.php";
    //     $data = new Spreadsheet_Excel_Reader($_FILES['reqLinkFile']['tmp_name']);

    //     $this->load->model("Pegawai");
    //     $pegawai = new Pegawai();

    //     $baris = $data->rowcount($sheet_index = 0);

    //     for ($i = 2; $i <= $baris; $i++) {
    //         $reqNrp                = $data->val($i, 1);
    //         $reqNip                = $this->val($i, 2);;
    //         $reqNama            = $this->val($i, 3);
    //         $reqNamaPanggilan    = $this->val($i, 4);
    //         $reqJenisKelamin    = $this->val($i, 5);
    //         $reqTempatLahir        = $this->val($i, 6);
    //         $reqTanggalLahir    = $this->val($i, 7);
    //         $reqUnitKerja        = $this->val($i, 8);
    //         $reqAlamat            = $this->val($i, 9);
    //         $reqNomorHp            = $this->val($i, 10);
    //         $reqEmailPribadi    = $this->val($i, 11);
    //         $reqEmailBulog        = $this->val($i, 12);
    //         $reqNomorWa            = $this->val($i, 13);

    //         $pegawai->setField("PEGAWAI_ID", $reqId);
    //         $pegawai->setField("NRP", $reqNrp);
    //         $pegawai->setField("NIP", $reqNip);
    //         $pegawai->setField("NAMA", $reqNama);
    //         $pegawai->setField("NAMA_PANGGILAN", $reqNamaPanggilan);
    //         $pegawai->setField("JENIS_KELAMIN", $reqJenisKelamin);
    //         $pegawai->setField("TEMPAT_LAHIR", $reqTempatLahir);
    //         $pegawai->setField("TANGGAL_LAHIR", $reqTanggalLahir);
    //         $pegawai->setField("UNIT_KERJA", $reqUnitKerja);
    //         $pegawai->setField("ALAMAT", $reqAlamat);
    //         $pegawai->setField("NOMOR_HP", $reqNomorHp);
    //         $pegawai->setField("EMAIL_PRIBADI", $reqEmailPribadi);
    //         $pegawai->setField("EMAIL_BULOG", $reqEmailBulog);
    //         $pegawai->setField("NOMOR_WA", $reqNomorWa);

    //         $pegawai->setField("CREATED_BY", $this->USERNAME);
    //         $pegawai->insert();
    //     }

    //     echo "Data berhasil diimport.";
    // }

    function deleteCompany()
    {
        $this->load->model("Company");
        $this->load->model("CostumerSupport");
        
        $this->load->model("Vessel");
       
        
        $reqIds = $this->input->get('reqId');
        $reqIds = explode(",", $reqIds);

        $booleanFalse=array();
        for($i=0;$i<count( $reqIds);$i++){
             $reqId = $reqIds[$i];
            if(!empty($reqId)){
        $vessel = new Vessel();
        $costumer_support = new CostumerSupport();
        $company = new Company();
       
        $company->setField("COMPANY_ID", $reqId);
        $vessel->setField("COMPANY_ID", $reqId);
        $costumer_support->setField("COMPANY_ID", $reqId);
        if ($company->delete()){
            $vessel->deleteParent();
            $costumer_support->deleteParent();

            // $arrJson["PESAN"] = "Data berhasil dihapus.";
        }
        else{
            array_push($booleanFalse, "YA");
            // $arrJson["PESAN"] = "Data gagal dihapus.";
        }
        }
        }

        if(count($booleanFalse )> 0){
        echo " Beberapa Data gagal dihapus.";
        }else{
             echo "Data berhasil dihapus.";
        }
        // echo json_encode($arrJson);
    }


    function delete()
    {
        $this->load->model("Customer");
        $customer = new Customer();

        $reqId = $this->input->get('reqId');

        $customer->setField("COMPANY_ID", $reqId);
        if ($customer->delete())
            $arrJson["PESAN"] = "Data berhasil dihapus.";
        else
            $arrJson["PESAN"] = "Data gagal dihapus.";

        echo json_encode($arrJson);
    }


    function combo()
    {
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 50;
        $offset = ($page - 1) * $rows;
        $reqPencarian = $this->input->get("reqPencarian");


        $this->load->model("Pegawai");
        $pegawai = new Pegawai();

        if ($reqPencarian == "") {
        } else
            $statement = "AND (UPPER(A.NAMA) LIKE '%" . strtoupper($reqPencarian) . "%' OR UPPER(A.NIP) LIKE '%" . strtoupper($reqPencarian) . "%') ";

        $rowCount = $pegawai->getCountByParams(array("VALIDASI" => "1"), $statement);
        $pegawai->selectByParams(array("VALIDASI" => "1"), $rows, $offset, $statement);
        $i = 0;
        $items = array();
        while ($pegawai->nextRow()) {
            $row['id'] = $pegawai->getField("NIP");
            $row['text'] = $pegawai->getField("NAMA");
            $row['PEGAWAI_ID'] = $pegawai->getField("NIP");
            $row['NAMA'] = $pegawai->getField("NAMA");
            $row['CABANG'] = $pegawai->getField("UNIT_KERJA");
            $row['JABATAN'] = $pegawai->getField("JABATAN");
            $row['state'] = 'open';
            $i++;
            array_push($items, $row);
        }
        $result["rows"] = $items;
        $result["total"] = $rowCount;
        echo json_encode($result);
    }




    function excel()
    {

        $this->load->model("Pegawai");
        $pegawai = new Pegawai();

        $reqCabangId = $this->input->get("reqCabangId");
        $reqGolonganDarah = $this->input->get("reqGolonganDarah");
        $reqJenisKelamin = $this->input->get("reqJenisKelamin");
        $reqMode = $this->input->get("reqMode");


        $aColumns        = array(
            "PEGAWAI_ID", "NO_SEKAR", "NIP", "NAMA", "NAMA_PANGGILAN", "JENIS_KELAMIN", "TEMPAT_LAHIR",
            "TANGGAL_LAHIR", "GOLONGAN_DARAH", "UNIT_KERJA", "ALAMAT", "NOMOR_HP", "EMAIL_PRIBADI", "EMAIL_BULOG",
            "NOMOR_WA"
        );


        if ($reqMode == "validasi")
            $statement_privacy = " AND VALIDASI = '0' ";
        elseif ($reqMode == "tolak")
            $statement_privacy = " AND VALIDASI = 'X' ";
        else
            $statement_privacy = " AND VALIDASI = '1' ";


        if (!empty($reqCabangId)) {
            $statement_privacy .= " AND A.CABANG_ID = '" . $reqCabangId . "' ";
        }
        if (!empty($reqGolonganDarah)) {
            $statement_privacy .= " AND UPPER(GOLONGAN_DARAH) = '" . $reqGolonganDarah . "' ";
        }
        if (!empty($reqJenisKelamin)) {
            $statement_privacy .= " AND UPPER(JENIS_KELAMIN) = '" . $reqJenisKelamin . "' ";
        }


        $pegawai->selectByParams(array(), -1, -1, $statement_privacy . $statement, $sOrder);

        $iData = 0;
        while ($pegawai->nextRow()) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NO_SEKAR")
                    $arr_json[$iData][$aColumns[$i]]    = "NO:" . $pegawai->getField($aColumns[$i]);
                else
                    $arr_json[$iData][$aColumns[$i]]    = $pegawai->getField($aColumns[$i]);
            }
            $iData++;
        }

        $fileName = "anggota_export.xls";

        if ($arr_json) {
            function filterData(&$str)
            {
                $str = preg_replace("/\t/", "\\t", $str);
                $str = preg_replace("/\r?\n/", "\\n", $str);


                if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
            }

            // headers for download
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header("Content-Type: application/vnd.ms-excel;");

            $flag = false;
            foreach ($arr_json as $row) {
                if (!$flag) {
                    // display column names as first row
                    echo implode("\t", array_keys($row)) . "\n";
                    $flag = true;
                }
                // filter data
                array_walk($row, 'filterData');
                echo implode("\t", array_values($row)) . "\n";
            }
            exit;
        }
    }


    function importExcel()
    {

        header('Cache-Control:max-age=0');
        header('Cache-Control:max-age=1');
        ini_set('memory_limit', '-1');

        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        ini_set('max_execution_time', -1);

        include_once("libraries/excel/excel_reader2.php");
        $data = new Spreadsheet_Excel_Reader($_FILES['reqFiles']["tmp_name"]);
        $baris = $data->rowcount($sheet_index = 0);
        // echo $baris;
        $arrData = array();

        $this->load->model("Company");


        for ($i = 2; $i <= count($baris); $i++) {
            $company = new Company();
            $company->setField("NAME", $data->val($i, 2));
            $company->setField("ADDRESS", $data->val($i, 3));
            $company->setField("PHONE", $data->val($i, 4));
            $company->setField("FAX", $data->val($i, 5));
            $company->setField("EMAIL", $data->val($i, 6));
            $company->setField("CP1_NAME", $data->val($i, 7));
            $company->setField("CP2_NAME", $data->val($i, 8));
            $company->setField("LA1_CP1", $data->val($i, 9));
            $company->setField("LA1_CP2", $data->val($i, 10));
            $company->insert();

            // echo $data->val($i,2);
        }


        echo 'Data Berhasil di import';
    }



    function sending_mail()
    {
        $this->load->model("ResikoEmail");
          $this->load->library("KMail");
        $resiko_email = new ResikoEmail();

        $this->load->model("DocumentAttacment");
        $this->load->model("ECommerce");

        $ecommerce = new ECommerce();
        


        $reqIdCompany1s = $this->input->post("reqIdCompany1");
        $reqIdCompany2s = $this->input->post("reqIdCompany2");
        $reqIdCompany3s = $this->input->post("reqIdCompany3");
        $reqSubject = $this->input->post("reqSubject");
        $reqName3 = $this->input->post("reqName3");
        $reqName1 = $this->input->post("reqName1");
        $reqName2 = $this->input->post("reqName2");
        $reqIdLampiran = $this->input->post("reqIdLampiran");
        
        $reqKeterangan = $_POST['reqDescription'];
        $arrData['KETERANGAN'] = $reqKeterangan;

        $ecommerce->setField("EMAIL", $reqName1);
        $ecommerce->setField("CC", $reqName2);
        $ecommerce->setField("BCC", $reqName3);
        $ecommerce->setField("SUBJECT", $reqSubject);
        $ecommerce->setField("BODY", $reqKeterangan);
        $ecommerce->setField("ATTACHMENT", $reqIdLampiran);
        $ecommerce->setField("SEND_DATE", "CURRENT_TIMESTAMP");
        $ecommerce->insert();
     
        $reqIdLampirans = explode(',', $reqIdLampiran);
        $statements='';
        for($i=0;$i<count($reqIdLampirans);$i++){
            if(!empty($reqIdLampirans[$i])){
                if($i==0){
                    $statements .= " AND A.DOCUMENT_ATTACMENT_ID='".$reqIdLampirans[$i]."'";
                }else{
                    $statements .= " OR A.DOCUMENT_ATTACMENT_ID='".$reqIdLampirans[$i]."'";
                }
            }
        } 
        
        $arrPath =array();  
        $document_attacment = new DocumentAttacment();   
        $document_attacment->selectByParamsMonitoring(array(),-1,-1,$statements);
        while($document_attacment->nextRow()){
            $str_path= 'uploads/eccommerce/'.$document_attacment->getField("DOCUMENT_ATTACMENT_ID").'/'.$document_attacment->getField("PATH");
            array_push($arrPath,  $str_path);
        }
        if(empty($reqIdLampiran)){
            $arrPath =array();  
        }


        
        $reqName1s = explode(',', $reqName1);   
        $reqName2s = explode(',', $reqName2);
        $reqName3s = explode(',', $reqName3);
        $arrDataBCC =array();
        $indexs =0;
        for($i=0;$i<count($reqName3s);$i++){
            $arrBCC =explode('[', $reqName3s[$i]);
            $nama_penerima = $arrBCC[0];
            $nama_email =$arrBCC[1];

            if (strpos($nama_email, '@') !== false) {
                $nama_email=  str_replace("]", '', $nama_email);
                $arrDataBCC[$indexs]["EMAIL"]=$nama_email;
                $arrDataBCC[$indexs]["PENERIMA"]=$nama_penerima;
                $indexs++;  
                
            }
        } 
        $arrDataCC =array();
        $indexs =0;
        for($i=0;$i<count($reqName2s);$i++){
            $arrCC =explode('[', $reqName2s[$i]);
            $nama_penerima = $arrCC[0];
            $nama_email =$arrCC[1];

            if (strpos($nama_email, '@') !== false) {
                $nama_email=  str_replace("]", '', $nama_email);
                $arrDataCC[$indexs]["EMAIL"]=$nama_email;
                $arrDataCC[$indexs]["PENERIMA"]=$nama_penerima;
                $indexs++;  
                
            }
        } 
        $arrDataAddres =array();
        $indexs =0;
        for($i=0;$i<count($reqName1s);$i++){
            $nama_emails=array();
            $nama_emails =explode('[', $reqName1s[$i]);
            $nama_penerima = $nama_emails[0];
            if (strpos($nama_emails, '@') !== false) {
                $nama_email=  str_replace("]", '', $nama_emails[1]);
                $arrDataAddres[$indexs]["EMAIL"]=$nama_email;
                $arrDataAddres[$indexs]["PENERIMA"]=$nama_penerima;
                $indexs++;  
            }
        }
             // print_r($arrPath);exit;
             // print_r($arrDataCC);exit;

        try {
            
            $mail = new KMail();
            $body =  $this->load->view('email/pesan', $arrData, true);
           

           
                // $nama_penerima = pre_regregName($reqName1s[$i]);
            for($i=0;$i<count($arrDataAddres);$i++){
                  $mail->AddAddress($arrDataAddres[$i]['EMAIL'], $arrDataAddres[$i]['PENERIMA']);
            
             
           }
                
                
            for($i=0;$i<count($arrDataCC);$i++){
                 $mail->AddCC($arrDataCC[$i]['EMAIL']);
                // $mail->AddCC($arrDataCC[$i]['EMAIL']);
            }

            for($i=0;$i<count($arrDataBCC);$i++){
                 $mail->AddBCC($arrDataBCC[$i]['EMAIL']);
                // $mail->AddCC($arrDataCC[$i]['EMAIL']);
            }

            for($i=0;$i<count($arrPath);$i++){
             $mail->addAttachment($arrPath[$i]);
            }

            $mail->Subject  =  " [AQUAMARINE] " . $reqSubject;
            $mail->Body = $body;
            // $mail->MsgHTML($body);
            if (!$mail->Send()) {

                echo "Error sending: " . $mail->ErrorInfo;
            } else {
                // echo "E-mail sent to ".$nama_penerima.'<br>';
            }
            // $mail->Send();
        
        
            unset($mail);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

               // }
            
        echo 'Email Berhasil di kirim';
    }

    function sending_mail_website()
    {
        $this->load->model("ResikoEmail");
          $this->load->library("KMail");
        $resiko_email = new ResikoEmail();

        $this->load->model("DocumentAttacment");
        $this->load->model("Website");

        $website = new Website();
        


        $reqIdCompany1s = $this->input->post("reqIdCompany1");
        $reqIdCompany2s = $this->input->post("reqIdCompany2");
        $reqIdCompany3s = $this->input->post("reqIdCompany3");
        $reqSubject = $this->input->post("reqSubject");
        $reqName3 = $this->input->post("reqName3");
        $reqName1 = $this->input->post("reqName1");
        $reqName2 = $this->input->post("reqName2");
        $reqIdLampiran = $this->input->post("reqIdLampiran");
        
        $reqKeterangan = $_POST['reqDescription'];
        $arrData['KETERANGAN'] = $reqKeterangan;

        $website->setField("EMAIL", $reqName1);
        $website->setField("CC", $reqName2);
        $website->setField("BCC", $reqName3);
        $website->setField("SUBJECT", $reqSubject);
        $website->setField("BODY", $reqKeterangan);
        $website->setField("ATTACHMENT", $reqIdLampiran);
        $website->setField("SEND_DATE", "CURRENT_TIMESTAMP");
        $website->insert();
     
        $reqIdLampirans = explode(',', $reqIdLampiran);
        $statements='';
        for($i=0;$i<count($reqIdLampirans);$i++){
            if(!empty($reqIdLampirans[$i])){
                if($i==0){
                    $statements .= " AND A.DOCUMENT_ATTACMENT_ID='".$reqIdLampirans[$i]."'";
                }else{
                    $statements .= " OR A.DOCUMENT_ATTACMENT_ID='".$reqIdLampirans[$i]."'";
                }
            }
        } 
        
        $arrPath =array();  
        $document_attacment = new DocumentAttacment();   
        $document_attacment->selectByParamsMonitoring(array(),-1,-1,$statements);
        while($document_attacment->nextRow()){
            $str_path= 'uploads/eccommerce/'.$document_attacment->getField("DOCUMENT_ATTACMENT_ID").'/'.$document_attacment->getField("PATH");
            array_push($arrPath,  $str_path);
        }
        if(empty($reqIdLampiran)){
            $arrPath =array();  
        }


        
        $reqName1s = explode(',', $reqName1);   
        $reqName2s = explode(',', $reqName2);
        $reqName3s = explode(',', $reqName3);
        $arrDataBCC =array();
        $indexs =0;
        for($i=0;$i<count($reqName3s);$i++){
            $arrBCC =explode('[', $reqName3s[$i]);
            $nama_penerima = $arrBCC[0];
            $nama_email =$arrBCC[1];

            if (strpos($nama_email, '@') !== false) {
                $nama_email=  str_replace("]", '', $nama_email);
                $arrDataBCC[$indexs]["EMAIL"]=$nama_email;
                $arrDataBCC[$indexs]["PENERIMA"]=$nama_penerima;
                $indexs++;  
                
            }
        } 
        $arrDataCC =array();
        $indexs =0;
        for($i=0;$i<count($reqName2s);$i++){
            $arrCC =explode('[', $reqName2s[$i]);
            $nama_penerima = $arrCC[0];
            $nama_email =$arrCC[1];

            if (strpos($nama_email, '@') !== false) {
                $nama_email=  str_replace("]", '', $nama_email);
                $arrDataCC[$indexs]["EMAIL"]=$nama_email;
                $arrDataCC[$indexs]["PENERIMA"]=$nama_penerima;
                $indexs++;  
                
            }
        } 
        $arrDataAddres =array();
        $indexs =0;
        for($i=0;$i<count($reqName1s);$i++){
            $nama_emails=array();
            $nama_emails =explode('[', $reqName1s[$i]);
            $nama_penerima = $nama_emails[0];
            if (strpos($nama_emails, '@') !== false) {
                $nama_email=  str_replace("]", '', $nama_emails[1]);
                $arrDataAddres[$indexs]["EMAIL"]=$nama_email;
                $arrDataAddres[$indexs]["PENERIMA"]=$nama_penerima;
                $indexs++;  
            }
        }
             // print_r($arrPath);exit;
             // print_r($arrDataCC);exit;

        try {
            
            $mail = new KMail();
            $body =  $this->load->view('email/pesan', $arrData, true);
           

           
                // $nama_penerima = pre_regregName($reqName1s[$i]);
            for($i=0;$i<count($arrDataAddres);$i++){
                  $mail->AddAddress($arrDataAddres[$i]['EMAIL'], $arrDataAddres[$i]['PENERIMA']);
            
             
           }
                
                
            for($i=0;$i<count($arrDataCC);$i++){
                 $mail->AddCC($arrDataCC[$i]['EMAIL']);
                // $mail->AddCC($arrDataCC[$i]['EMAIL']);
            }

            for($i=0;$i<count($arrDataBCC);$i++){
                 $mail->AddBCC($arrDataBCC[$i]['EMAIL']);
                // $mail->AddCC($arrDataCC[$i]['EMAIL']);
            }

            for($i=0;$i<count($arrPath);$i++){
             $mail->addAttachment($arrPath[$i]);
            }

            $mail->Subject  =  " [AQUAMARINE] " . $reqSubject;
            $mail->Body = $body;
            // $mail->MsgHTML($body);
            if (!$mail->Send()) {

                echo "Error sending: " . $mail->ErrorInfo;
            } else {
                // echo "E-mail sent to ".$nama_penerima.'<br>';
            }
            // $mail->Send();
        
        
            unset($mail);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

               // }
            
        echo 'Email Berhasil di kirim';
    }


    function company_detail_row()
    {
        $reqId = $this->input->get("reqId");
        $this->load->model("Company");
        $company = new Company();
        $aColumns = array(
            "COMPANY_ID", "NAME", "ADDRESS", "PHONE", "FAX", "EMAIL", "CP1_NAME",
            "CP1_TELP", "CP2_NAME", "CP2_TELP"
        );
        $company->selectByParamsMonitoring(array("A.COMPANY_ID" => $reqId));
        $company->firstRow();
        for ($i = 0; $i < count($aColumns); $i++) {
            $arr_json[$aColumns[$i]]      = $company->getField($aColumns[$i]);
        }

        echo json_encode($arr_json);
    }


    function  get_company_name()
    {
        $reqId = $this->input->get("reqId");
        $reqIds = explode(',', $reqId);

        $this->load->model("Company");
        $company = new Company();
        $statement = '';

        for ($i = 0; $i < count($reqIds); $i++) {
            if (!empty($reqIds[$i])) {
                if ($i == 0) {
                    $statement .= ' AND A.COMPANY_ID =' . $reqIds[$i];
                } else {
                    $statement .= ' OR A.COMPANY_ID =' . $reqIds[$i];
                }
            }
        }

        $company->selectByParamsMonitoring(array(), -1, -1, $statement);
        $text = '';
        while ($company->nextRow()) {
            $name  = $company->getField("NAME");
            $email =  '[' . $company->getField("EMAIL") . ']';
            $text .= $name . $email . ',';
        }
        echo $text;
    }

    function delete_costumer_support(){
        $this->load->model("CostumerSupport");
        $costumer_support = new CostumerSupport();
        $reqId = $this->input->get('reqId');
        $costumer_support->setField('COSTUMER_SUPPORT_ID',$reqId);
        $costumer_support->delete();
        echo 'Data berhasil di delete';

    }

    function ambil_costumer_support(){
       $this->load->model("CostumerSupport");
       $costumer_support = new CostumerSupport();
       $reqId = $this->input->get('reqId');

       $costumer_support->selectByParamsMonitoring(array("CAST(A.COSTUMER_SUPPORT_ID AS VARCHAR)"=>$reqId));
       $costumer_support->firstRow();

       $arrData = array();
       $arrData["NAMA"]=$costumer_support->getField('NAMA');
       $arrData["TELP"]=$costumer_support->getField('TELP');
       $arrData["EMAIL"]=$costumer_support->getField('EMAIL');

       echo json_encode($arrData);
    }

    function add_support(){
         $this->load->model("CostumerSupport");
        

        $reqSupportId     = $this->input->post('reqSupportId');
        $reqSupportName   = $this->input->post('reqSupportName');
        $reqSupportTelp   = $this->input->post('reqSupportTelp');
        $reqSupportEmail  = $this->input->post('reqSupportEmail');
        $reqCompanyId  = $this->input->post('reqCompanyId');

        $costumer_support = new CostumerSupport();
        $costumer_support->setField('COMPANY_ID',$reqCompanyId);
        $costumer_support->setField('COSTUMER_SUPPORT_ID',$reqSupportId);
        $costumer_support->setField('NAMA',$reqSupportName);
        $costumer_support->setField('TELP', $reqSupportTelp);
        $costumer_support->setField('EMAIL',$reqSupportEmail);
      

        if(empty($reqSupportId)){
            $costumer_support->insert();
             $reqSupportId = $costumer_support->id;
            echo $reqSupportId.'-tambah';
        }else{
            $costumer_support->update();
             echo $reqSupportId.'-update';
        }

    }

}
