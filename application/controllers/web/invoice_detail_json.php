<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class invoice_detail_json extends CI_Controller
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
        $this->load->model("InvoiceDetail");
        $invoice_detail = new InvoiceDetail();

        $aColumns = array("INVOICE_DETAIL_ID", "INVOICE_ID", "TYPE_PROJECT", "SERVICE_TYPE", "SERVICE_DATE", "LOCATION", "VESSEL", "AMOUNT_IDR", "AMOUNT_USD", "AMOUNT", "CURRENCY", "QUANTITYITEM", "AKSI", "ADDITIONAL", "VALUE_IDR", "VALUE_USD", "QUANTITY", "QUANTITY_ITEM", "IS_ADDITIONAL","TGL_STATUS_BAYAR","AMOUNT_NILAI_IDR","AMOUNT_NILAI_USD","REMARK");

        $aColumnsAlias = array("INVOICE_DETAIL_ID", "INVOICE_ID", "TYPE_PROJECT", "SERVICE_TYPE", "SERVICE_DATE", "LOCATION", "VESSEL", "AMOUNT_IDR", "AMOUNT_USD", "AMOUNT", "CURRENCY", "QUANTITYITEM", "AKSI", "ADDITIONAL", "VALUE_IDR", "VALUE_USD", "QUANTITY", "QUANTITY_ITEM", "IS_ADDITIONAL","TGL_STATUS_BAYAR","AMOUNT_NILAI_IDR","AMOUNT_NILAI_USD","REMARK");

        /*
         * Ordering
         */

        $reqId = $this->input->get("reqId");

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
            if (trim($sOrder) == "ORDER BY A." . $aColumns[0] . " asc") {
                /*
                * If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
                * If there is no order by clause there might be bugs in table display.
                * No order by clause means that the db is not responsible for the data ordering,
                * which means that the same row can be displayed in two pages - while
                * another row will not be displayed at all.
                */
                $sOrder = " ORDER BY A." . $aColumns[0] . " asc";
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
        if (!empty($reqId)) {
            $statement = " AND A.INVOICE_ID ='" . $reqId . "'";
        }

        if (empty($sOrder)) {
            $sOrder = "ORDER BY A." . $aColumns[0] . " DESC";
        }

        // $statement = " AND (UPPER(TANGGAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $invoice_detail->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
     
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $invoice_detail->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $invoice_detail->selectByParamsMonitoringNew(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
   // echo $invoice_detail->query;exit;
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $allRecord,
            "iTotalDisplayRecords" => $allRecordFilter,
            "aaData" => array()
        );
        // var_dump($invoice_detail); exit();
        $nom = 0;
        while ($invoice_detail->nextRow()) {
            $row = array();
            $ids  = $invoice_detail->getField($aColumns[0]);
            // currencyToPage
            for ($i = 0; $i < count($aColumns); $i++) {

                if ($aColumns[$i] == "AMOUNT") {
                    $row[] = currencyToPage2($invoice_detail->getField($aColumns[$i]));
                } else if ($aColumns[$i] == "SERVICE_TYPE") {
                    $type_project =   $invoice_detail->getField('TYPE_PROJECT');
                    if ($type_project == "Project Besar") {
                        $row[] = $invoice_detail->getField('DESCRIPTION');
                    } else {
                        $row[] = $invoice_detail->getField('SERVICE_TYPE');
                    }
                } else if ($aColumns[$i] == "AMOUNT_IDR") {
                    $CUR =   $invoice_detail->getField('CURRENCY');
                    if ($CUR == 1 || $CUR == 2 || $CUR == 3) {
                        $row[] = currencyToPage2($invoice_detail->getField('AMOUNT'));
                    } else {
                        $row[] = '0';
                    }
                } else if ($aColumns[$i] == "AMOUNT_USD") {
                    $CUR =   $invoice_detail->getField('CURRENCY');
                    if ($CUR == 0 || $CUR == '') {
                        $row[] = currencyToPage2($invoice_detail->getField('AMOUNT'));
                    } else {
                        $row[] = '0';
                    }
                } else if ($aColumns[$i] == "VALUE_IDR") {
                    $CUR =   $invoice_detail->getField('CURRENCY');
                    if ($CUR == 1 || $CUR == 2 || $CUR == 3) {
                        $row[] = $invoice_detail->getField('AMOUNT');
                    } else {
                        $row[] = '0';
                    }
                } else if ($aColumns[$i] == "VALUE_USD") {
                    $CUR =   $invoice_detail->getField('CURRENCY');
                    if ($CUR == 0 || $CUR == '') {
                        $row[] = $invoice_detail->getField('AMOUNT');
                    } else {
                        $row[] = '0';
                    }
                } else if ($aColumns[$i] == "AMOUNT_NILAI_IDR") {
                    $CUR =   $invoice_detail->getField('CURRENCY');
                    if ($CUR == 1 || $CUR == 2 || $CUR == 3) {
                        $nilai = $invoice_detail->getField('AMOUNT_NILAI');
                         $nilai= $nilai? $nilai:0;
                        $row[] = $nilai ;
                    } else {
                        $row[] = '0';
                    }
                } else if ($aColumns[$i] == "AMOUNT_NILAI_USD") {
                    $CUR =   $invoice_detail->getField('CURRENCY');
                    if ($CUR == 0 || $CUR == '') {
                         $nilai = $invoice_detail->getField('AMOUNT_NILAI');
                         $nilai= $nilai? $nilai:0;
                        $row[] = $nilai ;
                    } else {
                        $row[] = '0';
                    }
                }else if ($aColumns[$i] == "AKSI") {
                    $btn_edit = '<button type="button"  class="btn btn-info " onclick=editing(' . $nom . ')  ><i class="fa fa-pencil-square-o fa-lg"> </i> </button>';
                    $btn_delete = '<button type="button"  class="btn btn-danger hapusi"  onclick="deleting(' . $nom . ')"><i class="fa fa-trash-o fa-lg"> </i> </button>';

                    $row[] = $btn_edit . $btn_delete;
                } else {
                    $row[] = $invoice_detail->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
            $nom++;
        }
        echo json_encode($output);
    }


    function delete()
    {
        $this->load->model("InvoiceDetail");
        $invoice_detail = new InvoiceDetail();

        $reqId = $this->input->get('reqId');

        $invoice_detail->setField('INVOICE_DETAIL_ID', $reqId);
        $invoice_detail->delete();

        echo 'Data berhasil di hapus';
    }
}
