<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class kas_kecil_json extends CI_Controller
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
        $this->load->model("KasKecil");
        $kas_kecil = new KasKecil();

        $aColumns = array(
            "KAS_KECIL_ID", "TANGGAL", "DESKRIPSI", "STATUS", "TOTAL_KREDIT", "TOTAL_DEBET", "TOTAL_BALANCE"
        );

        $aColumnsAlias = array(
            "KAS_KECIL_ID", "TANGGAL", "DESKRIPSI", "STATUS", "TOTAL_KREDIT", "TOTAL_DEBET", "TOTAL_BALANCE"
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
            if (trim($sOrder) == "ORDER BY KAS_KECIL_ID asc") {
                /*
                * If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
                * If there is no order by clause there might be bugs in table display.
                * No order by clause means that the db is not responsible for the data ordering,
                * which means that the same row can be displayed in two pages - while
                * another row will not be displayed at all.
                */
                $sOrder = " ORDER BY KAS_KECIL_ID desc";
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

        $reqCariPeriodeYearFrom     =  $this->input->get('reqCariPeriodeYearFrom');
        $reqCariPeriodeYearTo       =  $this->input->get('reqCariPeriodeYearTo');
        $_SESSION[$this->input->get("pg")."reqCariPeriodeYearFrom"] = $reqCariPeriodeYearFrom;
        $_SESSION[$this->input->get("pg")."reqCariPeriodeYearTo"] = $reqCariPeriodeYearTo;

        if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo)) {
            $statement = " AND A.TANGGAL BETWEEN TO_DATE('" . $reqCariPeriodeYearFrom . "', 'yyyy-MM-dd') AND TO_DATE('" . $reqCariPeriodeYearTo . "', 'yyyy-MM-dd')";
        }

        $statement = " AND (UPPER(DESKRIPSI) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $kas_kecil->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $kas_kecil->getCountByParams(array(), $statement_privacy . $statement);

        $kas_kecil->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $kas_kecil->query;
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

        while ($kas_kecil->nextRow()) {
            $row = array();

            $totalKreditIDR = $kas_kecil->getField("TOTAL_KREDIT_IDR");
            $totalDebetIDR = $kas_kecil->getField("TOTAL_DEBET_IDR");
            $totalBalanceIDR = $kas_kecil->getField("TOTAL_BALANCE_IDR");

            $totalKreditUSD = $kas_kecil->getField("TOTAL_KREDIT_USD");
            $totalDebetUSD = $kas_kecil->getField("TOTAL_DEBET_USD");
            $totalBalanceUSD = $kas_kecil->getField("TOTAL_BALANCE_USD");

            $totalKredit = $totalKreditIDR;
            $totalDebet = $totalDebetIDR;
            $totalBalance = $totalBalanceIDR;

            if($totalKreditIDR == 0 && $totalDebetIDR == 0 && $totalBalanceIDR == 0) {
                $totalKredit = $totalKreditUSD;
                $totalDebet = $totalDebetUSD;
                $totalBalance = $totalBalanceUSD;
            }
            
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "DESKRIPSI") {
                    // $row[] = truncate($kas_kecil->getField($aColumns[$i]), 2);
                    $row[] = $kas_kecil->getField($aColumns[$i]);
                } else 
                if ($aColumns[$i] == "TANGGAL") {
                    // $row[] = truncate($kas_kecil->getField($aColumns[$i]), 2);
                    $tgl_nama = explode('-', $kas_kecil->getField($aColumns[$i]));
                    $str = ltrim($tgl_nama[1], '0');
                    $reqDeskripsi = getNameMonth($str) . ' ' . $tgl_nama[2];
                    $row[] = $reqDeskripsi;
                } else if($aColumns[$i] == "TOTAL_KREDIT"){
                    $row[] = currencyToPage2($totalKredit);
                } else if($aColumns[$i] == "TOTAL_DEBET"){
                    $row[] = currencyToPage2($totalDebet);
                } else if($aColumns[$i] == "TOTAL_BALANCE"){
                    $row[] = currencyToPage2($totalBalance);
                } else {
                    $row[] = $kas_kecil->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }


    function add()
    {
        $this->load->model("KasKecil");
        $kas_kecil = new KasKecil();

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqTanggal = $this->input->post("reqTanggal");
        $reqDeskripsi = $this->input->post("reqDeskripsi");

        $kas_kecil->setField("KAS_KECIL_ID", $reqId);
        $kas_kecil->setField("TANGGAL", $reqTanggal);
        $kas_kecil->setField("DESKRIPSI", $reqDeskripsi);

        if ($reqMode == "insert") {
            $kas_kecil->insert();
        } else {
            $kas_kecil->update();
        }

        echo "Data berhasil disimpan.";
    }

    function delete()
    {
        $this->load->model("KasKecil");
        $this->load->model("KasKecilDetail");

        $kas_kecil = new KasKecil();
        $kas_kecil_detil = new KasKecilDetail();
        $reqId = $this->input->get('reqId');

        $kas_kecil->setField("KAS_KECIL_ID", $reqId);
        $kas_kecil_detil->setField("KAS_KECIL_ID", $reqId);
        if ($kas_kecil->delete()) {
            $kas_kecil_detil->deleteParent();
            // $arrJson["PESAN"] = "Data berhasil dihapus.";
        } else {
            // $arrJson["PESAN"] = "Data gagal dihapus.";
        }

        echo "Data berhasil dihapus";
    }

    function approve()
    {
        $this->load->model("KasKecil");

        $kas_kecil = new KasKecil();
        $reqId = $this->input->get('reqId');

        $kas_kecil->setField("KAS_KECIL_ID", $reqId);
        $kas_kecil->setField("APPROVED_BY", $this->USERID);
        $kas_kecil->approve();

        echo "Data berhasil diapprove.-".$reqId."-";
    }

    function approve_cancel()
    {
        $this->load->model("KasKecil");

        $kas_kecil = new KasKecil();
        $reqId = $this->input->get('reqId');

        $kas_kecil->setField("KAS_KECIL_ID", $reqId);
        $kas_kecil->setField("APPROVED_BY", $this->USERID);
        $kas_kecil->approve_cancel();

        echo "Data berhasil dicancel.-".$reqId."-";
    }
}
