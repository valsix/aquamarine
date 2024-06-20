<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class users_management_json extends CI_Controller
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
        $this->load->model("Users_management");
        $usersManagement = new Users_management();

        $aColumns = array(
            "USERID", "USERNAME", "FULLNAME",  "LEVEL", 'MENUWAREHOUSE',"MENUMARKETING", "MENUFINANCE", "MENUPRODUCTION", "MENUDOCUMENT", "MENUSEARCH", "MENUOTHERS"
        );

        $aColumnsAlias = array(
            "USERID", "USERNAME", "FULLNAME",  "LEVEL",'MENUWAREHOUSE', "MENUMARKETING", "MENUFINANCE", "MENUPRODUCTION", "MENUDOCUMENT", "MENUSEARCH", "MENUOTHERS"
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
            if (trim($sOrder) == "ORDER BY A.USERID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY A.USERID asc";
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

        $statement = " AND (UPPER(FULLNAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $usersManagement->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $usersManagement->getCountByParams(array(), $statement_privacy . $statement);

        $usersManagement->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
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

        while ($usersManagement->nextRow()) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "LEVEL") {
                    $text = "User";
                    if ($usersManagement->getField($aColumns[$i]) == 0) {
                        $text = 'Administrator';
                    }
                    $row[] = $text;
                } elseif ($aColumns[$i] == "MENUWAREHOUSE" || $aColumns[$i] == "MENUMARKETING" || $aColumns[$i] == "MENUFINANCE" || $aColumns[$i] == "MENUPRODUCTION" || $aColumns[$i] == "MENUDOCUMENT" || $aColumns[$i] == "MENUSEARCH" || $aColumns[$i] == "MENUOTHERS") {
                    $checked = '';
                    if ($usersManagement->getField($aColumns[$i]) == 1) {
                        $checked = "checked";
                    }
                    $row[] = '<input type="checkbox" ' . $checked . '>';
                } else
                    $row[] = $usersManagement->getField($aColumns[$i]);
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }

    function add()
    {
        $this->load->model("Users_management");
        $usersManagement = new Users_management();

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqUsername = $this->input->post("reqUsername");
        $reqFullName = $this->input->post("reqFullName");
        $reqUserPass = $this->input->post("reqUserPass");
        $reqLevel = $this->input->post("reqLevel");
        $reqMenuMarketing = $this->input->post("MENUMARKETING");
        $reqMenuFinance = $this->input->post("MENUFINANCE");
        $reqMenuProduction = $this->input->post("MENUPRODUCTION");
        $reqMenuEPL = $this->input->post("MENUEPL");
        $reqMenuUWILD = $this->input->post("MENUUWILD");
        $reqMenuWP = $this->input->post("MENUWP");
        $reqMenuPL = $this->input->post("MENUPL");
        $reqMenuEL = $this->input->post("MENUEL");
        $reqMenuPMS = $this->input->post("MENUPMS");
        $reqMenuRS = $this->input->post("MENURS");
        $reqMenuSTD = $this->input->post("MENUSTD");
        $reqMenuDocument = $this->input->post("MENUDOCUMENT");
        $reqMenuSearch = $this->input->post("MENUSEARCH");
        $reqMenuOthers = $this->input->post("MENUOTHERS");
        $reqMenuTen    = $this->input->post("MENUSTEN");
        $reqMenuSwd    = $this->input->post("MENUSWD");
        $reqMenuWarehouse    = $this->input->post("MENUWAREHOUSE");
        $reqMenuInvProject    = $this->input->post("MENUINVPROJECT");

        if($reqId == ""){
            $statement_check_username = " AND USERNAME = '".$reqUsername."' ";
        } else {
            $statement_check_username = " AND USERNAME = '".$reqUsername."' AND USERID <> '".$reqId."' ";
        }

        $check_username = $usersManagement->getCountByParams(array(), $statement_check_username);
        if($check_username > 0){
            echo $reqId."-Username telah digunakan.-";
            return;
        }

        $reqMenuMarketing       =  $reqMenuMarketing == '' ? '0' : '1';
        $reqMenuFinance         =  $reqMenuFinance == '' ? '0' : '1';
        $reqMenuProduction      =  $reqMenuProduction == '' ? '0' : '1';
        $reqMenuEPL             =  $reqMenuEPL == '' ? '0' : '1';
        $reqMenuUWILD           =  $reqMenuUWILD == '' ? '0' : '1';
        $reqMenuWP              =  $reqMenuWP == '' ? '0' : '1';
        $reqMenuPL              =  $reqMenuPL == '' ? '0' : '1';
        $reqMenuEL              =  $reqMenuEL == '' ? '0' : '1';
        $reqMenuPMS             =  $reqMenuPMS == '' ? '0' : '1';
        $reqMenuRS              =  $reqMenuRS == '' ? '0' : '1';
        $reqMenuSTD             =  $reqMenuSTD == '' ? '0' : '1';
        $reqMenuDocument        =  $reqMenuDocument == '' ? '0' : '1';
        $reqMenuSearch          =  $reqMenuSearch == '' ? '0' : '1';
        $reqMenuOthers          =  $reqMenuOthers == '' ? '0' : '1';
        $reqMenuTen             =  $reqMenuTen == '' ? '0' : '1';
        $reqMenuSwd             =  $reqMenuSwd == '' ? '0' : '1';
        $reqMenuInvProject      =  $reqMenuInvProject == '' ? '0' : '1';
        $reqMenuWarehouse      =  $reqMenuWarehouse == '' ? '0' : '1';

        $usersManagement = new Users_management();

        $usersManagement->setField("USERID", $reqId);
        $usersManagement->setField("USERNAME", $reqUsername);
        $usersManagement->setField("FULLNAME", $reqFullName);
        $usersManagement->setField("LEVEL", $reqLevel);
        $usersManagement->setField("MENUMARKETING", $reqMenuMarketing);
        $usersManagement->setField("MENUFINANCE", $reqMenuFinance);
        $usersManagement->setField("MENUPRODUCTION", $reqMenuProduction);
        $usersManagement->setField("MENUEPL", $reqMenuEPL);
        $usersManagement->setField("MENUUWILD", $reqMenuUWILD);
        $usersManagement->setField("MENUWP", $reqMenuWP);
        $usersManagement->setField("MENUPL", $reqMenuPL);
        $usersManagement->setField("MENUEL", $reqMenuEL);
        $usersManagement->setField("MENUPMS", $reqMenuPMS);
        $usersManagement->setField("MENURS", $reqMenuRS);
        $usersManagement->setField("MENUSTD", $reqMenuSTD);
        $usersManagement->setField("MENUSTEN", $reqMenuTen);
        $usersManagement->setField("MENUSWD", $reqMenuSwd);
        $usersManagement->setField("MENUDOCUMENT", $reqMenuDocument);
          $usersManagement->setField("MENUWAREHOUSE", $reqMenuWarehouse);
        $usersManagement->setField("MENUSEARCH", $reqMenuSearch);
        $usersManagement->setField("MENUOTHERS", $reqMenuOthers);
         $usersManagement->setField("MENUINVPROJECT", $reqMenuInvProject);


        if ($reqMode == "insert") {
            // $usersManagement->setField("LAST_CREATE_USER", $this->USERNAME);
            $usersManagement->insert();
            $reqId = $usersManagement->id;
        } else {
            // $usersManagement->setField("LAST_UPDATE_USER", $this->USERNAME);
            $usersManagement->update();
        }

        if(!empty($reqUserPass))
        {
            $usersManagement->setField("USERID", $reqId);
            $usersManagement->setField("USERPASS", md5($reqUserPass));
            $usersManagement->updatePassword();
        }

        $this->kauth->reAuthenticate($this->USERNAME);

        echo $reqId."-Data berhasil disimpan.-";
    }

    function ganti_password()
    {
        $this->load->model("Users_management");
        $usersManagement = new Users_management();

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqUsername = $this->input->post("reqUsername");
        $reqFullName = $this->input->post("reqFullName");
        $reqUserPass = $this->input->post("reqUserPass");

        if($reqId == ""){
            $statement_check_username = " AND USERNAME = '".$reqUsername."' ";
        } else {
            $statement_check_username = " AND USERNAME = '".$reqUsername."' AND USERID <> '".$reqId."' ";
        }

        $check_username = $usersManagement->getCountByParams(array(), $statement_check_username);
        if($check_username > 0){
            echo $reqId."-Username telah digunakan.-";
            return;
        }

        $usersManagement = new Users_management();

        $usersManagement->setField("USERID", $reqId);
        $usersManagement->setField("USERNAME", $reqUsername);
        $usersManagement->setField("FULLNAME", $reqFullName);
        $usersManagement->updateUsername();

        if(!empty($reqUserPass))
        {
            $usersManagement->setField("USERID", $reqId);
            $usersManagement->setField("USERPASS", md5($reqUserPass));
            $usersManagement->updatePassword();
        }

        $this->kauth->reAuthenticate($this->USERNAME);

        echo $reqId."-Data berhasil disimpan.-";
    }

    function delete()
    {
        $this->load->model("Users_management");
        $usersManagement = new Users_management();

        $reqId = $this->input->get('reqId');

        $usersManagement->setField("USERID", $reqId);
        if ($usersManagement->delete())
            $arrJson["PESAN"] = "Data berhasil dihapus.";
        else
            $arrJson["PESAN"] = "Data gagal dihapus.";

        echo json_encode($arrJson);
    }

    function combo()
    {
        $this->load->model("UserLogin");
        $user_login = new UserLogin();

        $user_login->selectByParams(array());
        $i = 0;
        while ($user_login->nextRow()) {
            $arr_json[$i]['id']        = $user_login->getField("USER_LOGIN_ID");
            $arr_json[$i]['text']    = $user_login->getField("NAMA");
            $i++;
        }

        echo json_encode($arr_json);
    }
}
