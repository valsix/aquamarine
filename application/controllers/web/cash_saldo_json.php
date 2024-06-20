<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class cash_saldo_json extends CI_Controller
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
        $this->load->model("CastSaldo");
        $cast_saldo = new CastSaldo();
         $this->load->model("CashSaldoDetail");
        $cash_saldo_detail = new CashSaldoDetail();


        $aColumns =  array("CAST_SALDO_ID", "TANGGAL", "KETERANGAN","AMOUNT_IDR","AMOUNT_USD");

        $aColumnsAlias =  array("CAST_SALDO_ID", "TANGGAL", "KETERANGAN","AMOUNT_IDR","AMOUNT_USD");
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
            if (trim($sOrder) == "ORDER BY CAST_SALDO_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY CAST_SALDO_ID desc";
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

        // $statement = " AND (UPPER(TANGGAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $cast_saldo->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $cast_saldo->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $cast_saldo->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $cash_report->query;
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

        while ($cast_saldo->nextRow()) {
            $row = array();
            $ids  = $cast_saldo->getField($aColumns[0]);
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "DESKRIPSI") {
                    // $row[] = truncate($cash_report->getField($aColumns[$i]), 2);
                    $row[] = $cast_saldo->getField($aColumns[$i]);
                }else if ($aColumns[$i] == "AMOUNT_IDR") {
                    $cash_saldo_detail = new CashSaldoDetail();
                    $cash_saldo_detail->selectByParamsMonitoring(array("A.CASH_SALDO_ID"=> $ids,"A.CURENCY"=>'1'));
                    $total_amount=0;
                    while ($cash_saldo_detail->nextRow()) {
                        $total_amount +=$cash_saldo_detail->getField('AMOUNT');
                    }

                    $row[] =currencyToPage($total_amount);
                    // $row[] = $cast_saldo->getField($aColumns[$i]);
                }else if ($aColumns[$i] == "AMOUNT_USD") {
                    $cash_saldo_detail = new CashSaldoDetail();
                    $cash_saldo_detail->selectByParamsMonitoring(array("A.CASH_SALDO_ID"=> $ids,"A.CURENCY"=>'0'));
                    $total_amount=0;
                    while ($cash_saldo_detail->nextRow()) {
                        $total_amount +=$cash_saldo_detail->getField('AMOUNT');
                    }

                    $row[] =currencyToPage($total_amount);
                } else 
                if ($aColumns[$i] == "TANGGAL") {
                    // $row[] = truncate($cash_report->getField($aColumns[$i]), 2);
                    $tgl_nama = explode('-', $cast_saldo->getField($aColumns[$i]));
                    $str = ltrim($tgl_nama[1], '0');
                    $reqDeskripsi = getNameMonth($str) . ' ' . $tgl_nama[2];
                    $row[] = $reqDeskripsi;
                } else {
                    $row[] = $cast_saldo->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }


    function add()
    {
        $this->load->model("Cash_report");
        $cash_report = new Cash_report();

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqTanggal = $this->input->post("reqTanggal");
        $reqDeskripsi = $this->input->post("reqDeskripsi");

        $cash_report->setField("CASH_REPORT_ID", $reqId);
        $cash_report->setField("TANGGAL", $reqTanggal);
        $cash_report->setField("DESKRIPSI", $reqDeskripsi);

        if ($reqMode == "insert") {
            $cash_report->insert();
        } else {
            $cash_report->update();
        }

        echo "Data berhasil disimpan.";
    }

    function delete()
    {
        $this->load->model("CastSaldo");
        $this->load->model("CashSaldoDetail");

        $cast_saldo = new CastSaldo();
        $cast_saldo_detil = new CashSaldoDetail();
        $reqId = $this->input->get('reqId');

        $cast_saldo->setField("CAST_SALDO_ID", $reqId);
        $cast_saldo_detil->setField("CASH_SALDO_ID", $reqId);
        if ($cast_saldo->delete()) {
            $cast_saldo_detil->deleteParent();
            // $arrJson["PESAN"] = "Data berhasil dihapus.";
        } else {
            // $arrJson["PESAN"] = "Data gagal dihapus.";
        }

        echo "Data berhasil dihapus";
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
