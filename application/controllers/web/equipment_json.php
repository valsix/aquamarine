<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class equipment_json extends CI_Controller
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
        $this->load->model("Equipment");
        $equipment = new Equipment();

        $aColumns = array(
            "EQUIP_ID", "EQUIP_NAME", "EQUIP_QTY", "EQUIP_ITEM", "EQUIP_SPEC", "EQUIP_DATEIN", "EQUIP_LASTCAL", "EQUIP_NEXTCAL", "EQUIP_CONDITION",
            "EQUIP_STORAGE", "EQUIP_REMARKS", "EQUIP_PRICE", "PIC_PATH"
        );
        $aColumnsAlias = array(
            "EQUIP_ID", "EQUIP_NAME", "EQUIP_QTY", "EQUIP_ITEM", "EQUIP_SPEC", "EQUIP_DATEIN", "EQUIP_LASTCAL", "EQUIP_NEXTCAL", "EQUIP_CONDITION",
            "EQUIP_STORAGE", "EQUIP_REMARKS", "EQUIP_PRICE", "PIC_PATH"
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
            if (trim($sOrder) == "ORDER BY A.EQUIP_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY A.EQUIP_ID asc";
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

        $statement = " AND (UPPER(EQUIP_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $equipment->getCountByParams(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $equipment->getCountByParams(array(), $statement_privacy . $statement);

        $equipment->selectByParams(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $equipment->query;exit;
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

        while ($equipment->nextRow()) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "EQUIP_NAME")
                    $row[] = truncate($equipment->getField($aColumns[$i]), 2);
                else
                    $row[] = $equipment->getField($aColumns[$i]);
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }


    function add()
    {
        $this->load->model("Equipment");
        $equipment = new Equipment();

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");
        $reqEquipName = $this->input->post("reqEquipName");
        $reqEquipQty = $this->input->post("reqEquipQty");
        $reqEquipItem = $this->input->post("reqEquipItem");
        $reqEquipSpec = $this->input->post("reqEquipSpec");
        $reqEquipDatein = $this->input->post("reqEquipDatein");
        $reqEquipLastCal = $this->input->post("reqEquipLastCal");
        $reqEquipNextCal = $this->input->post("reqEquipNextCal");
        $reqEquipCondition = $this->input->post("reqEquipCondition");
        $reqEquipStorage = $this->input->post("reqEquipStorage");
        $reqEquipRemarks = $this->input->post("reqEquipRemarks");
        $reqEquipPrice = $this->input->post("reqEquipPrice");
        $reqPicPath = $this->input->post("reqPicPath");

        $equipment->setField("EQUIP_ID", $reqId);
        $equipment->setField("EQUIP_NAME", $reqEquipName);
        $equipment->setField("EQUIP_QTY", $reqEquipQty);
        $equipment->setField("EQUIP_ITEM", $reqEquipItem);
        $equipment->setField("EQUIP_SPEC", $reqEquipSpec);
        $equipment->setField("EQUIP_DATEIN", $reqEquipDatein);
        $equipment->setField("EQUIP_LASTCAL", $reqEquipLastCal);
        $equipment->setField("EQUIP_NEXTCAL", $reqEquipNextCal);
        $equipment->setField("EQUIP_CONDITION", $reqEquipCondition);
        $equipment->setField("EQUIP_STORAGE", $reqEquipStorage);
        $equipment->setField("EQUIP_REMARKS", $reqEquipRemarks);
        $equipment->setField("EQUIP_PRICE", $reqEquipPrice);
        $equipment->setField("PIC_PATH", $reqPicPath);

        if ($reqMode == "insert") {
            $equipment->insert();
        } else {
            $equipment->update();
        }

        echo "Data berhasil disimpan.";
    }

    function add_new()
    {
        $reqId = $this->input->post("reqId");
        $reqBarcode                    = $this->input->post("reqBarcode");
        $reqEquipStorage =   $this->input->post("reqEquipStorage");
        $this->load->model("EquipmentList");
        $this->load->model("EquipStorage");


        $equipstorage = new EquipStorage();
        $equipstorage->selectByParamsMonitoring(array('A.EQUIP_STORAGE_ID::VARCHAR'=>$reqEquipStorage));
        $arrSetorgae =   $equipstorage->rowResult;
        $arrSetorgae =$arrSetorgae[0];
        $reqLokasiId = $arrSetorgae['equip_storage_id'];
         $reqKode = $arrSetorgae['kode'];
        $reqEquipStorage = $arrSetorgae['nama'];

          $equipment_list = new EquipmentList();
          $equipment_list->selectByParamsMonitoring(array("A.BARCODE"=>$reqBarcode));
          $equipment_list->firstRow();
          $reqIdx       = $equipment_list->getField("EQUIP_ID");
          if(!empty($reqId)){
          if( !empty($reqIdx) &&   $reqIdx !=$reqId  ){
              // echo $reqId . '-Data Gagal di simpan Barcode sudah ada';
              // exit;
          }
        }

        $equipment_list = new EquipmentList();
        // $equipment_list->selectByParamsMonitoring(array("A.EQUIP_ID" => $reqId));
        // $equipment_list->firstRow();
        
        $reqEquipId                    = $this->input->post("reqEquipId");
        $reqEquipParentId              = $this->input->post("reqEquipParentId");
        $reqEcId                       = $this->input->post("reqEcId");
        $reqEquipName                  = $this->input->post("reqEquipName");
        $reqEquipQty                   = $this->input->post("reqEquipQty");
        $reqEquipItem                  = $this->input->post("reqEquipItem");
        $reqEquipSpec                  = $this->input->post("reqEquipSpec");
        $reqEquipSN                    = $this->input->post("reqEquipSN");
        $reqEquipDatein                = $this->input->post("reqEquipDatein");
        $reqEquipLastcal               = $this->input->post("reqEquipLastcal");
        $reqEquipNextcal               = $this->input->post("reqEquipNextcal");
        $reqEquipCondition             = $this->input->post("reqEquipCondition");
        // $reqEquipStorage               = $this->input->post("reqEquipStorage");
        $reqEquipRemarks               = $_POST["reqEquipRemarks"];
        $reqEquipPrice                 = $this->input->post("reqEquipPrice");
        $reqVasselCurrency              = $this->input->post("reqVasselCurrency");
       

        $equipment_list->setField("EQUIP_ID", $reqId);
        $equipment_list->setField("EQUIP_PARENT_ID", ValToNullDB($reqEquipParentId));
        $equipment_list->setField("EC_ID", $reqEcId);
        $equipment_list->setField("EQUIP_NAME", $reqEquipName);
        $equipment_list->setField("EQUIP_QTY", ifZero2($reqEquipQty));
        $equipment_list->setField("EQUIP_ITEM", $reqEquipItem);
        $equipment_list->setField("EQUIP_SPEC", $reqEquipSpec);
        $equipment_list->setField("EQUIP_DATEIN", dateToDBCheck($reqEquipDatein));
        $equipment_list->setField("EQUIP_LASTCAL", dateToDBCheck($reqEquipLastcal));
        $equipment_list->setField("EQUIP_NEXTCAL", dateToDBCheck($reqEquipNextcal));
        $equipment_list->setField("EQUIP_CONDITION", $reqEquipCondition);
        $equipment_list->setField("EQUIP_STORAGE", $reqEquipStorage);
        $equipment_list->setField("EQUIP_REMARKS", $reqEquipRemarks);
        $equipment_list->setField("EQUIP_PRICE", dotToNo($reqEquipPrice));
        $equipment_list->setField("BARCODE", $reqBarcode);
        $equipment_list->setField("PIC_PATH", $reqPicPath);
        $equipment_list->setField("SERIAL_NUMBER", $reqEquipSN);

        if (empty($reqId)) {
            $equipment_list->insert();
            $reqId = $equipment_list->id;
        } else {
            $equipment_list->update();
        }
        


        // $reqPicPath                    = $equipment_list->post("reqPicPath");

        $this->load->library("FileHandler");
        $file = new FileHandler();

        $FILE_DIR = "uploads/equipment/";
        makedirs($FILE_DIR);
        $filesData = $_FILES["reqFilesName"];
        $reqLinkFileTempSize    =  $this->input->post("reqLinkFileTempSize");
        $reqLinkFileTempTipe    =  $this->input->post("reqLinkFileTempTipe");
        $reqFilesNames    =  $this->input->post("reqFilesNames");

        $renameFile = "IMG" . date("dmYhis") . '-' . $reqId . "." . getExtension2($filesData['name'][0]);
        if ($file->uploadToDirArray('reqFilesName', $FILE_DIR, $renameFile, 0)) {
            $reqPicPath                    = $renameFile;
            
            $equipment_list = new EquipmentList();
            $equipment_list->setField("EQUIP_ID", $reqId);
            $equipment_list->setField("PIC_PATH", setQuote($reqPicPath));
            $equipment_list->update_path();
        }

        $reqCertificateId                = $this->input->post("reqCertificateId");
        $reqCertificateName              = $this->input->post("reqCertificateName");
        $reqCertificateDescription       = $this->input->post("reqCertificateDescription");
        $reqCertificatePath              = $this->input->post("reqCertificatePath");
        $reqCertificateIssueDate         = $this->input->post("reqCertificateIssueDate");
        $reqCertificateExpiredDate       = $this->input->post("reqCertificateExpiredDate");
        $reqCertificateLastRevisi        = $this->input->post("reqCertificateLastRevisi");
        $reqCertificateSurveyor          = $this->input->post("reqCertificateSurveyor");
        $reqLinkFileCertificateTemp      = $this->input->post("reqLinkFileCertificateTemp");
        $reqLinkFileInvoiceTemp          = $this->input->post("reqLinkFileInvoiceTemp");

        $FILE_DIR = "uploads/equipment_cerificate/" . $reqId . "/";
        makedirs($FILE_DIR);

        $filesData = $_FILES["reqLinkFileCertificate"];

        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
            if ($file->uploadToDirArray('reqLinkFileCertificate', $FILE_DIR, $renameFile, $i)) {
                array_push($arrData, $renameFile);
            } else {
                array_push($arrData, $reqLinkFileCertificateTemp[$i]);
            }
        }
        $str_name_path = '';
        for ($i = 0; $i < count($arrData); $i++) {
            if (!empty($arrData[$i])) {
                if ($i == 0) {
                    $str_name_path .= $arrData[$i];
                } else {
                    $str_name_path .= ';' . $arrData[$i];
                }
            }
        }

        $equipment_list = new EquipmentList();
        $equipment_list->setField("EQUIP_ID", $reqId);
        $equipment_list->setField("CERTIFICATE_ID", ValToNullDB($reqCertificateId));
        $equipment_list->setField("CERTIFICATE_NAME", setQuote($reqCertificateName));
        $equipment_list->setField("CERTIFICATE_DESCRIPTION", setQuote($reqCertificateDescription));
        $equipment_list->setField("CERTIFICATE_PATH", setQuote($str_name_path));
        $equipment_list->setField("CERTIFICATE_ISSUED_DATE", dateToDBCheck($reqCertificateIssueDate));
        $equipment_list->setField("CERTIFICATE_EXPIRED_DATE", dateToDBCheck($reqCertificateExpiredDate));
        $equipment_list->setField("CERTIFICATE_LAST_REVISI", dateToDBCheck($reqCertificateLastRevisi));
        $equipment_list->setField("CERTIFICATE_SURVEYOR", $reqCertificateSurveyor);
        $equipment_list->update_certificate();


        $FILE_DIR = "uploads/equipment_invoice/" . $reqId . "/";
        makedirs($FILE_DIR);

        $filesData = $_FILES["reqLinkFileInvoice"];

        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
            if ($file->uploadToDirArray('reqLinkFileInvoice', $FILE_DIR, $renameFile, $i)) {
                array_push($arrData, $renameFile);
            } else {
                array_push($arrData, $reqLinkFileInvoiceTemp[$i]);
            }
        }
        $str_name_path = '';
        for ($i = 0; $i < count($arrData); $i++) {
            if (!empty($arrData[$i])) {
                if ($i == 0) {
                    $str_name_path .= $arrData[$i];
                } else {
                    $str_name_path .= ';' . $arrData[$i];
                }
            }
        }

        $reqInvoiceNumber            = $this->input->post("reqInvoiceNumber");
        $reqInvoiceDescription       = $this->input->post("reqInvoiceDescription");
        $reqInvoicePath              = $this->input->post("reqInvoicePath");

        $equipment_list = new EquipmentList();
        $equipment_list->setField("EQUIP_ID", $reqId);
        $equipment_list->setField("INVOICE_NUMBER", $reqInvoiceNumber);
        $equipment_list->setField("INVOICE_DESCRIPTION", setQuote($reqInvoiceDescription));
        $equipment_list->setField("INVOICE_PATH", setQuote($str_name_path));
        $equipment_list->setField("CURRENCY", $reqVasselCurrency);
        
        $equipment_list->update_invoice();
        $equipment_list->update_currency();

        if(!empty($reqLokasiId)){
        $reqBarcode = $reqId.'-'.$reqKode.'-'.$reqEquipSN;
        $equipment_list->setField("EQUIP_ID", $reqId);
        $equipment_list->setField("STORAGE_ID", ValToNullDB($reqLokasiId));
        $equipment_list->setField("BARCODE", $reqBarcode);
        $equipment_list->update_barcode();
        $this->create_qr($reqId, $reqBarcode);
        }


    

        echo $reqId . '-Data berhasil di simpan';
    }


    function create_qr($reqId='',$nipp=''){
            $this->load->library("qrcodegenerator");
            $qrcodegenerators =  new qrcodegenerator();

            $qrcodegenerators->generateQr($reqId,$nipp); 

            
    }


    function delete()
    {
        $this->load->model("Equipment");
        $equipment = new Equipment();

        $reqId = $this->input->get('reqId');

        $equipment->setField("EQUIP_ID", $reqId);
        if ($equipment->delete())
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


    function import_excel()
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
        // print_r( $data);
        // print_r($baris);
        $arrData = array();
        // $katerori = 'Company Profile';
        // $kategori = $this->input->post("reqTipe");

        $this->load->model("EquipmentList");

        for ($i = 2; $i <= $baris; $i++) {

            $equipment_list = new EquipmentList();
            $equipment_list->setField("EC_ID", $data->val($i, 2));
            $equipment_list->setField("EQUIP_NAME", $data->val($i, 3));
            $equipment_list->setField("EQUIP_QTY", $data->val($i, 4));
            $equipment_list->setField("EQUIP_ITEM", $data->val($i, 5));
            $equipment_list->setField("EQUIP_SPEC", $data->val($i, 6));
            $equipment_list->setField("EQUIP_DATEIN", $data->val($i, 7));
            $equipment_list->setField("EQUIP_LASTCAL", $data->val($i, 8));
            $equipment_list->setField("EQUIP_NEXTCAL", $data->val($i, 9));
            $equipment_list->setField("EQUIP_CONDITION", $data->val($i, 10));
            $equipment_list->setField("EQUIP_STORAGE", $data->val($i, 11));
            $equipment_list->setField("EQUIP_REMARKS", $data->val($i, 12));
            $equipment_list->setField("EQUIP_PRICE", $data->val($i, 13));
            $equipment_list->setField("PIC_PATH", $data->val($i, 14));
            $equipment_list->insert();
            $reqId = $equipment_list->id;

            $equipment_list = new EquipmentList();
            $equipment_list->setField("EQUIP_ID", $reqId);
            $equipment_list->setField("EC_ID", $reqId . ' - ' . $data->val($i, 2));
            $equipment_list->setField("EQUIP_NAME", $reqId . ' - ' . $data->val($i, 3));
            $equipment_list->setField("EQUIP_QTY", $reqId . ' - ' . $data->val($i, 4));
            $equipment_list->setField("EQUIP_ITEM", $reqId . ' - ' . $data->val($i, 5));
            $equipment_list->setField("EQUIP_SPEC", $reqId . ' - ' . $data->val($i, 6));
            $equipment_list->setField("EQUIP_DATEIN", $reqId . ' - ' . $data->val($i, 7));
            $equipment_list->setField("EQUIP_LASTCAL", $reqId . ' - ' . $data->val($i, 8));
            $equipment_list->setField("EQUIP_NEXTCAL", $reqId . ' - ' . $data->val($i, 9));
            $equipment_list->setField("EQUIP_CONDITION", $reqId . ' - ' . $data->val($i, 10));
            $equipment_list->setField("EQUIP_STORAGE", $reqId . ' - ' . $data->val($i, 11));
            $equipment_list->setField("EQUIP_REMARKS", $reqId . ' - ' . $data->val($i, 12));
            $equipment_list->setField("EQUIP_PRICE", $reqId . ' - ' . $data->val($i, 13));
            $equipment_list->setField("PIC_PATH", $reqId . ' - ' . $data->val($i, 14));
            $equipment_list->update();

            // echo $data->val($i,2).'<br>';
        }
        echo 'Data Berhasil di import';
    }
}
