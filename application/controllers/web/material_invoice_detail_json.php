<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class material_invoice_detail_json extends CI_Controller
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
        $this->load->model("MaterialInvoiceDetail");
        $material_invoice_detil = new MaterialInvoiceDetail();

        $aColumns = array("MATERIAL_INVOICE_DETAIL_ID","NO","TANGGAL","PATH_PEMBELIAN","TANGGAL_PEMBELIAN","TANGGAL_TERIMA","DITERIMA_OLEH","TANGGAL_PEMBAYARAN", "PATH_INVOICE", "NILAI_INVOICE","AKSI");

        $aColumnsAlias = array("MATERIAL_INVOICE_DETAIL_ID","NO","TANGGAL","PATH_PEMBELIAN","TANGGAL_PEMBELIAN","TANGGAL_TERIMA","DITERIMA_OLEH","TANGGAL_PEMBAYARAN", "PATH_INVOICE", "NILAI_INVOICE","AKSI");
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
            if (trim($sOrder) == "ORDER BY A.".$aColumns[1]." asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY A.".$aColumns[1]." desc";
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
            $statement = " AND A.MATERIAL_INVOICE_ID ='".$reqId."'";
        }

        // if(empty($sOrder)){
        //     $sOrder = "ORDER BY A.".$aColumns[1]." DESC";
        // }

        $sOrder = " ORDER BY A.TANGGAL DESC, A.MATERIAL_INVOICE_DETAIL_ID DESC ";

        // $statement = " AND (UPPER(TANGGAL) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $material_invoice_detil->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $material_invoice_detil->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $material_invoice_detil->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $material_invoice_detil->query;exit;
     
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $allRecord,
            "iTotalDisplayRecords" => $allRecordFilter,
            "aaData" => array()
        );
        $nom=0;
        while ($material_invoice_detil->nextRow()) {
            $row = array();
            // currencyToPage
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NO"){
                    $row[] = $nom+1;
                }else if ($aColumns[$i] == "DESKRIPSI"){
                    $row[] = truncate($material_invoice_detil->getField($aColumns[$i]), 2);
                }else if ($aColumns[$i] == "PATH_PEMBELIAN" || $aColumns[$i] == "PATH_INVOICE"){
                    $reqId = $material_invoice_detil->getField("MATERIAL_INVOICE_ID");
                    $link = "uploads/material_invoice/".$reqId."/".$material_invoice_detil->getField($aColumns[$i]);
                    $row[] = '<a style="background: white; color: #3174c7;" href="'.$link.'" >Download </a>';
                }else if($aColumns[$i] == "AKSI"){
                 $btn_edit = '<button type="button"  class="btn btn-info " onclick=editing('.$nom.')  ><i class="fa fa-pencil-square-o fa-lg"> </i> </button>';
                 $btn_delete = '<button type="button"  class="btn btn-danger hapusi"  onclick="deleting('.$nom.')"><i class="fa fa-trash-o fa-lg"> </i> </button>';

                 $row[] =$btn_edit.$btn_delete;
                }


                else{
                    $row[] = $material_invoice_detil->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
            $nom++;
        }
        echo json_encode($output);
    }


    function add()
    {
        $this->load->model("MaterialInvoice");
        $this->load->model("MaterialInvoiceDetail");
        $material_invoice = new MaterialInvoice();
        

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");


        $reqTahun = $this->input->post("reqTahun");
        $reqDeskripsi = 'Material Pembelian dan Invoice Pembayaran Tahun '.$reqTahun;

        $material_invoice->setField("MATERIAL_INVOICE_ID", $reqId);
        $material_invoice->setField("TAHUN", $reqTahun);
        $material_invoice->setField("DESKRIPSI", $reqDeskripsi);
        $status='';
        if(empty($reqId)){
            $material_invoice->insert();
            $reqId = $material_invoice->id;
            $status ='baru';
        }else{
            $material_invoice->update();
        }

        $reqMaterialInvoiceDetailId = $this->input->post('reqMaterialInvoiceDetailId');
        $reqDetailTanggal           = $this->input->post('reqDetailTanggal');
        $reqTanggalPembelian        = $this->input->post('reqTanggalPembelian');
        $reqDiterimaOleh            = $this->input->post('reqDiterimaOleh');
        $reqTanggalTerima           = $this->input->post('reqTanggalTerima');
        $reqTanggalPembayaran       = $this->input->post('reqTanggalPembayaran');
        $reqNilaiInvoice            = $this->input->post('reqNilaiInvoice');


        $material_invoice_detil = new MaterialInvoiceDetail();
        $material_invoice_detil->setField("MATERIAL_INVOICE_DETAIL_ID", $reqMaterialInvoiceDetailId);
        $material_invoice_detil->setField("MATERIAL_INVOICE_ID", $reqId);
        $material_invoice_detil->setField("TANGGAL", dateToDBCheck($reqDetailTanggal));
        $material_invoice_detil->setField("TANGGAL_PEMBELIAN", dateToDBCheck($reqTanggalPembelian));
        $material_invoice_detil->setField("DITERIMA_OLEH", $reqDiterimaOleh);
        $material_invoice_detil->setField("TANGGAL_TERIMA", dateToDBCheck($reqTanggalTerima));
        $material_invoice_detil->setField("TANGGAL_PEMBAYARAN", dateToDBCheck($reqTanggalPembayaran));
        $material_invoice_detil->setField("NILAI_INVOICE", dotToNo($reqNilaiInvoice));


        if(!empty($reqDetailTanggal))
        {
            if(empty($reqMaterialInvoiceDetailId)){
                $material_invoice_detil->insert();
            }
            else{
                $material_invoice_detil->update();
            }
        }

        $this->load->library("FileHandler");
        $file = new FileHandler();

        $FILE_DIR = "uploads/material_invoice/".$reqId."/";
        makedirs($FILE_DIR);
        $filesData = $_FILES["reqLinkFilePembelian"];

        $renameFile = "PEMBELIAN-" . date("dmYhis") . '-' . $reqId . "." . getExtension2($filesData['name'][0]);
        if ($file->uploadToDirArray('reqLinkFilePembelian', $FILE_DIR, $renameFile, 0)) {
            $reqPath = $renameFile;
            
            $material_invoice_detil = new MaterialInvoiceDetail();
            $material_invoice_detil->setField("MATERIAL_INVOICE_DETAIL_ID", $reqId);
            $material_invoice_detil->setField("PATH_PEMBELIAN", setQuote($reqPath));
            $material_invoice_detil->updatePathPembelian();
        }

        $filesData = $_FILES["reqLinkFileInvoice"];

        $renameFile = "INVOICE-" . date("dmYhis") . '-' . $reqId . "." . getExtension2($filesData['name'][0]);
        if ($file->uploadToDirArray('reqLinkFileInvoice', $FILE_DIR, $renameFile, 0)) {
            $reqPath = $renameFile;
            
            $material_invoice_detil = new MaterialInvoiceDetail();
            $material_invoice_detil->setField("MATERIAL_INVOICE_DETAIL_ID", $reqId);
            $material_invoice_detil->setField("PATH_INVOICE", setQuote($reqPath));
            $material_invoice_detil->updatePathInvoice();
        }
        
        $pesan ="Data berhasil disimpan.-".$reqId."-";
      
        echo $pesan;
    }


    function generate_fifo($reqId){
        $this->load->model("MaterialInvoiceDetail");
        $material_invoice_detil = new MaterialInvoiceDetail(); 
        $sOrder = "ORDER BY A.TANGGAL ASC, A.MATERIAL_INVOICE_DETAIL_ID ASC";
        $material_invoice_detil->selectByParamsMonitoring(array("A.MATERIAL_INVOICE_ID"=>$reqId),-1,-1,'',$sOrder);
        // echo($material_invoice_detil->query); exit();
        $arrData = array();
        $nom=0;
        $arrDataTotal = array();
        $total =0;
          $total_usd =0;
        while ( $material_invoice_detil->nextRow()) {
          $arrData[$nom]["MATERIAL_INVOICE_DETAIL_ID"]=$material_invoice_detil->getField("MATERIAL_INVOICE_DETAIL_ID");
          $arrData[$nom]["SALDO"]=$material_invoice_detil->getField("SALDO");
          $arrData[$nom]["DEBET"]=$material_invoice_detil->getField("DEBET");
          $arrData[$nom]["KREDIT"]=$material_invoice_detil->getField("KREDIT");
          $arrData[$nom]["SALDO_USD"]=$material_invoice_detil->getField("SALDO_USD");
          $arrData[$nom]["DEBET_USD"]=$material_invoice_detil->getField("DEBET_USD");
          $arrData[$nom]["KREDIT_USD"]=$material_invoice_detil->getField("KREDIT_USD");
          $total_balalance =  $material_invoice_detil->getField("SALDO");
          $total_debet     =  $material_invoice_detil->getField("DEBET");
          $total_kredit    =  $material_invoice_detil->getField("KREDIT");
          $grand_total     = ($total_kredit-$total_debet)  ;
          $total = $total +$grand_total;

          $total_balalance_usd =  $material_invoice_detil->getField("SALDO_USD");
          $total_debet_usd     =  $material_invoice_detil->getField("DEBET_USD");
          $total_kredit_usd    =  $material_invoice_detil->getField("KREDIT_USD");
          $grand_total_usd     = ($total_kredit_usd-$total_debet_usd)  ;
          $total_usd = $total_usd +$grand_total_usd;



          $arrData[$nom]["SALDO_AWAL"]=$total;
          $arrData[$nom]["SALDO_AWAL_USD"]=$total_usd;
          array_push($arrDataTotal, $total);
           $nom++;
        }
        
        // var_dump($arrData); 
        $nomer=1;
        for($i=0;$i<count($arrData);$i++){

            $reqDebet  = $arrData[$i]["DEBET"];
            $reqKredit = $arrData[$i]["KREDIT"];
            $reqSaldo  = $arrData[$i]["SALDO"];
            $reqBalanceAwal = $arrData[$i]["SALDO_AWAL"] ;


            $reqDebet_usd       = $arrData[$i]["DEBET_USD"];
            $reqKredit_usd      = $arrData[$i]["KREDIT_USD"];
            $reqSaldo_usd       = $arrData[$i]["SALDO_USD"];
            $reqBalanceAwal_usd = $arrData[$i]["SALDO_AWAL_USD"] ;

          
            $material_invoice_detil = new MaterialInvoiceDetail();
            $material_invoice_detil->setField("MATERIAL_INVOICE_DETAIL_ID", $arrData[$i]["MATERIAL_INVOICE_DETAIL_ID"]);
            $material_invoice_detil->setField("DEBET", ValToNull($reqDebet));
            $material_invoice_detil->setField("KREDIT", ValToNull($reqKredit));
            $material_invoice_detil->setField("SALDO", ValToNull($reqBalanceAwal));
            $material_invoice_detil->setField("MATERIAL_INVOICE_ID", $reqId);

      
            $material_invoice_detil->updateBalance();

            $material_invoice_detil = new MaterialInvoiceDetail();
            $material_invoice_detil->setField("MATERIAL_INVOICE_DETAIL_ID", $arrData[$i]["MATERIAL_INVOICE_DETAIL_ID"]);
            $material_invoice_detil->setField("DEBET_USD", ValToNull($reqDebet_usd));
            $material_invoice_detil->setField("KREDIT_USD", ValToNull($reqKredit_usd));
            $material_invoice_detil->setField("SALDO_USD", ValToNull($reqBalanceAwal_usd));
            $material_invoice_detil->setField("MATERIAL_INVOICE_ID", $reqId);
            $material_invoice_detil->updateBalanceUSD();


        $nomer ++;
        }
    }

    function import_files(){

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

        // print_r($data);
        // exit;
        // print_r( $data);
        // print_r($baris);
        $arrData = array();
        // $katerori = 'Company Profile';
        $katerori = $this->input->post("reqTipe");
        $reqId = $this->input->post("reqId");

        $this->load->model("MaterialInvoiceDetail");
          for ($i = 2; $i <= $baris; $i++) {
               
                $material_invoice_detil = new MaterialInvoiceDetail();
                $material_invoice_detil->setField("TANGGAL", dateToDBCheck3($data->val($i, 1)));
                $material_invoice_detil->setField("KETERANGAN",$data->val($i, 2));
                $material_invoice_detil->setField("KATEGORI", $data->val($i, 3));
                $material_invoice_detil->setField("DEBET", dotToNo($data->val($i, 4)));
                $material_invoice_detil->setField("KREDIT", dotToNo($data->val($i, 5)));
                $material_invoice_detil->setField("DEBET_USD", dotToNo($data->val($i, 6)));
                $material_invoice_detil->setField("KREDIT_USD", dotToNo($data->val($i, 7)));
                $material_invoice_detil->setField("SALDO", 0);
                $material_invoice_detil->setField("SALDO_USD", 0);
                $material_invoice_detil->setField("MATERIAL_INVOICE_ID", $reqId);
                $material_invoice_detil->insert();

               $this->generate_fifo($reqId);

        }

        echo 'Data Berhasil diimport';

    }

    function delete(){
        $reqId = $this->input->get('reqId');
        $this->load->model("MaterialInvoiceDetail");
        $material_invoice_detil = new MaterialInvoiceDetail(); 
        $material_invoice_detil->setField('MATERIAL_INVOICE_DETAIL_ID',$reqId);
        $material_invoice_detil->delete();
        $this->generate_fifo($reqId);
        echo 'Data berhasil di hapus';

    }

    function getSaldoAkhir(){
       $this->load->model("MaterialInvoiceDetail");
       $reqId = $this->input->get('reqId');
       $material_invoice_detil = new MaterialInvoiceDetail(); 
       $material_invoice_detil->selectByParamsMonitoring(array("A.MATERIAL_INVOICE_ID"=>$reqId),-1,-1,''," ORDER BY TO_CHAR(A.TANGGAL,'dd-mm-yyyy hh24:mm:ss') desc");
        
        while ( $material_invoice_detil->nextRow()) {
              $ttotal =currencyToPage2($material_invoice_detil->getField('SALDO'));
            # code...
        }
     
       echo $ttotal;
    }
     function getSaldoAkhirUSD(){
       $this->load->model("MaterialInvoiceDetail");
       $reqId = $this->input->get('reqId');
       $material_invoice_detil = new MaterialInvoiceDetail(); 
       // $material_invoice_detil->selectByParamsMonitoring(array("A.MATERIAL_INVOICE_ID"=>$reqId),-1,-1,''," ORDER BY A.MATERIAL_INVOICE_DETAIL_ID ASC");
        $material_invoice_detil->selectByParamsMonitoring(array("A.MATERIAL_INVOICE_ID"=>$reqId),-1,-1,''," ORDER BY TO_CHAR(A.TANGGAL,'dd-mm-yyyy hh24:mm:ss') desc");
        
        while ( $material_invoice_detil->nextRow()) {
              $ttotal =currencyToPage2($material_invoice_detil->getField('SALDO_USD'));
            # code...
        }
       echo $ttotal;
    }

    function balance(){
       $this->load->model("CostProjectDetil");
       $cost_project_detil = new CostProjectDetil();
           $reqId = $this->input->post("reqId");
    }
    
}
