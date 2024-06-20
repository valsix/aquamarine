<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class personal_kualifikasi_json extends CI_Controller
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
        $this->load->model("JenisKualifikasi");
        $jenis_kualifikasi = new JenisKualifikasi();
        // $this->load->model("DokumenCertificate");
        $this->load->model("DokumenSertifikat");
        // $dokumen_certificate = new DokumenCertificate();
        $dokumen_certificate = new DokumenSertifikat();


     
        $aColumns = array(
            "DOCUMENT_ID", "ID_NUMBER","STATUS","REMARKS", "NAME", "QUALIFICATION", "NAMA_CABANG", "UMUR", "PHONE"
        );
        $aColumnsAlias = array(
            "DOCUMENT_ID", "ID_NUMBER", "STATUS","REMARKS","NAME", "QUALIFICATION", "NAMA_CABANG", "UMUR", "PHONE"
        );
        $this->load->model('PersonalCertificate');
        $certificate = new PersonalCertificate();
        $certificate->selectByParamsMonitoring(array());
        $arrDatas = array();
        $no = 0;
        while ($certificate->nextRow()) {
            $arrDatas[]   = $certificate->getField("CERTIFICATE");
            $no++;
            $aColumns[] = $certificate->getField("CERTIFICATE");
            $aColumnsAlias[] = $certificate->getField("CERTIFICATE");
        }
        $aColumns[] = "REMARKS2";
        $aColumnsAlias[] = "REMARKS2";
        $aColumns[] = "STATUS2";
        $aColumnsAlias[] = "STATUS2";
        // $aColumns[] = "CERTIFICATE_EXPIRED";
        // $aColumnsAlias[] = "CERTIFICATE_EXPIRED";
       
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

            // echo $sOrder;exit;

            //Check if there is an order by clause
            if (trim($sOrder) == "ORDER BY DOCUMENT_ID asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY DOCUMENT_ID desc";
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
        $reqCariCompanyName = $this->input->get('reqCariCompanyName');
        $reqCariTypeofQualification = $this->input->get('reqCariTypeofQualification');
        $reqTypeOfService = $this->input->get('reqTypeOfService');
        $expired = $this->input->get('expired');
        $reqCariLokasi = $this->input->get('reqCariLokasi');
        $reqCariUmur =  $this->input->get('reqCariUmur');
        $reqCariWorkingHistory =  $this->input->get('reqCariWorkingHistory');
        $reqCariProjectHistory  = $this->input->get('reqCariProjectHistory');
        $reqCariDateofServiceFrom = $this->input->get('reqCariDateofServiceFrom');
        $reqCariDateofServiceTo = $this->input->get('reqCariDateofServiceTo');
         $reqCariExpired = $this->input->get('reqCariExpired');
        
        $_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariTypeofQualification"] = $reqCariTypeofQualification;
        $_SESSION[$this->input->get("pg")."reqTypeOfService"] = $reqTypeOfService;
        $_SESSION[$this->input->get("pg")."reqCariLokasi"] = $reqCariLokasi;
        $_SESSION[$this->input->get("pg")."reqCariUmur"] = $reqCariUmur;
        $_SESSION[$this->input->get("pg")."reqCariWorkingHistory"] = $reqCariWorkingHistory;
        $_SESSION[$this->input->get("pg")."reqCariProjectHistory"] = $reqCariProjectHistory;
        $_SESSION[$this->input->get("pg")."reqCariDateofServiceFrom"] = $reqCariDateofServiceFrom;
        $_SESSION[$this->input->get("pg")."reqCariDateofServiceTo"] = $reqCariDateofServiceTo;
        $_SESSION[$this->input->get("pg")."reqCariExpired"] = $reqCariExpired;
         

        if (!empty($reqCariCompanyName)) {
            $statement_privacy .= " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%'";
        }
        if (!empty($reqCariTypeofQualification)) {
            $statement_privacy .= " AND A.JENIS_ID='" . $reqCariTypeofQualification . "'";
        }
        if (!empty($reqCariLokasi)) {
            // $statement_privacy .= " AND EXISTS (
            //     SELECT 1 FROM CABANG CC WHERE CC.CABANG_ID =  A.CABANG_ID::integer AND UPPER(CC.NAMA) LIKE '%" . strtoupper($reqCariLokasi) . "%'
            // )";

              $statement_privacy .= " AND A.CABANG_ID ='" . $reqCariLokasi . "'";
            // echo $statement_privacy;exit;
        }
        if (!empty($reqTypeOfService)) {
            $reqTypeOfService =str_replace('-', ',', $reqTypeOfService);
            // $statement_privacy .= "   AND A.DOCUMENT_ID IN (SELECT C.DOCUMENT_ID FROM DETIL_PERSONAL_CERTIFICATE C WHERE C.CERTIFICATE_ID IN (" . $reqTypeOfService . "))";

          $statement_privacy .= " AND EXISTS(SELECT 1 FROM DOKUMEN_SERTIFIKAT DD LEFT JOIN PERSONAL_CERTIFICATE C ON C.CERTIFICATE_ID = DD.CERTIFICATE_ID WHERE 1=1 AND DD.DOKUMEN_ID=A.DOCUMENT_ID AND C.CERTIFICATE_ID IN  (".$reqTypeOfService.") ) ";

        }

         if (!empty($reqCariUmur)) {
            $statement_privacy .= " AND EXTRACT(YEAR FROM AGE(A.BIRTH_DATE)) ='" . $reqCariUmur . "'";
        }
          if (!empty($reqCariExpired)) {
            $statement_privacy .= " AND EXISTS (
                SELECT 1 
                FROM DOKUMEN_SERTIFIKAT X 
                WHERE A.DOCUMENT_ID = X.DOKUMEN_ID  AND X.EXPIRED_DATE IS NOT NULL
                AND X.EXPIRED_DATE < CURRENT_DATE + INTERVAL '1 DAY'
            )  ";
        }

        if (!empty($reqCariWorkingHistory)) {
            $statement_privacy .= " AND EXISTS(
             SELECT 1
                FROM SO_TEAM CC
                INNER JOIN SERVICE_ORDER BB ON BB.SO_ID = CC.SO_ID
            WHERE 1=1 AND A.DOCUMENT_ID = CC.DOCUMENT_ID  AND (UPPER(BB.PROJECT_NAME) LIKE '%" . strtoupper($reqCariWorkingHistory) . "%')
            )


            ";
        }
        if (!empty($reqCariProjectHistory)) {
            $statement_privacy .= " AND EXISTS(
              SELECT 1
        FROM so_team_new AA
        LEFT JOIN DOKUMEN_KUALIFIKASI B ON AA.DOCUMENT_ID = B.DOCUMENT_ID
        LEFT JOIN PERSONAL_CERTIFICATE C ON AA.SERTIFIKAT_ID = C.CERTIFICATE_ID
         LEFT JOIN SERVICE_ORDER_NEW D ON D.SERVICE_ORDER_NEW_ID = AA.SO_ID
         LEFT JOIN PROJECT_HPP_NEW E ON E.PROJECT_HPP_NEW_ID = D.HPP_PROJECT_ID
            LEFT JOIN MASTER_PROJECT F ON F.MASTER_PROJECT_ID::VARCHAR = E.CODE AND A.DOCUMENT_ID = AA.DOCUMENT_ID  WHERE 1=1  AND F.MASTER_PROJECT_ID = '" . strtoupper($reqCariProjectHistory) . "'
            )


            ";
        }
        if(!empty($reqCariDateofServiceFrom) && !empty($reqCariDateofServiceTo) ){
             $statement_privacy .= " AND EXISTS( SELECT 1 FROM DOKUMEN_SERTIFIKAT CC WHERE CC.DOKUMEN_ID = A.DOCUMENT_ID 
              AND CC.EXPIRED_DATE BETWEEN to_date('" . $reqCariDateofServiceFrom . "', 'DD-MM-YYYY')  AND  to_date('" . $reqCariDateofServiceTo . "', 'DD-MM-YYYY')
              )
               ";
        }

        $statement = " AND (UPPER(A.NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        
        if($expired == "1") {
            $statement .= " AND EXISTS (
                SELECT 1 
                FROM DOKUMEN_SERTIFIKAT X 
                WHERE A.DOCUMENT_ID = X.DOKUMEN_ID 
                AND X.EXPIRED_DATE < CURRENT_DATE + INTERVAL '3 MONTH'
            )  ";
        }

        $allRecord = $jenis_kualifikasi->getCountByParamsMonitoringPersonalKualifikasi(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $jenis_kualifikasi->getCountByParamsMonitoringPersonalKualifikasi(array(), $statement_privacy . $statement);

    
             // $orderby =" ORDER BY A.DOCUMENT_ID DESC";
        //}
                // echo $sOrder;exit;

        $jenis_kualifikasi->selectByParamsMonitoringPersonalKualifikasi(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $jenis_kualifikasi->query;exit;
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

        while ($jenis_kualifikasi->nextRow()) {
            $row = array();
            $dokumen_certificate = new DokumenSertifikat();
            $dokumen_certificate->selectByParams(array("A.DOKUMEN_ID" => $jenis_kualifikasi->getField("DOCUMENT_ID")));
            $dokumen_certificate->firstRow();

            $reqListCertificates    = $dokumen_certificate->getField("CERTIFICATE_ID");
            $reqListCertificate = explode(',', $reqListCertificates);

            /*
            // print_r ($reqListCertificate);
            $bollean=false;
            $certificate ='<ol>';
            for($i=0;$i<count($arrDatas);$i++){
                if(!empty($arrDatas[$i])){
                    $dokumen_certificate = new DokumenSertifikat();
                    $dokumen_certificate->selectByParams(array("A.DOKUMEN_ID" => $jenis_kualifikasi->getField("DOCUMENT_ID")));
                    // echo $dokumen_certificate->query;
                    $dokumen_certificate->firstRow();
                    $reqNames            = $dokumen_certificate->getField("NAME");
                    $reqIssuedDates      = $dokumen_certificate->getField("ISSUED_DATE");
                    $reqExpiredDates     = $dokumen_certificate->getField("EXPIRED_DATE");

                    $tgl_skrng = Date('d-m-Y');
                    $exp_date = $dokumen_certificate->getField("DATES");
                    $datetime1 = date_create($tgl_skrng);
                    $datetime2 = date_create($exp_date);
                    $interval = date_diff($datetime1, $datetime2);
                    $interval = $interval->format("%R%a");
                    $point = substr($interval, 0,1);
                    $y = $datetime2->diff( $datetime1)->y;
                    $m = $datetime2->diff( $datetime1)->m;
                    $d = $datetime2->diff( $datetime1)->d;
                    $tgls = $y." tahun ".$m." bulan ".$d." hari";
                  
                     $certificate .='<li>'.$reqNames.'==> EXPIRED_DATE '.$exp_date. ' ('. $tgls .') </li>';

                    if ($point == '-') {
                        $bollean = true;
                    }

                }

            }
            $color='';
            if($bollean || empty($reqListCertificates)){
                $color='red';
            }

            if( empty($reqListCertificates)){
                $certificate .='<li> Dont Have Certificate </li>';
            }

            $certificate .='</ol>';
            */
            $color = "";
             $color2 = "";
            $certificate_expired_col = array();

            // print_r($aColumns);
             $arrDataKode = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                $dokumen_certificate = new DokumenSertifikat();
                $dokumen_certificate2 = new DokumenSertifikat();
                $bollean =false;
                 // $color2='';
                if(in_array($aColumns[$i], $arrDatas))
                {
                    $dokumen_certificate->selectByParams(array("A.DOKUMEN_ID" => $jenis_kualifikasi->getField("DOCUMENT_ID"), "C.CERTIFICATE" => $aColumns[$i]));
                    $dokumen_certificate->firstRow();
                   
                    $is_expired = $dokumen_certificate->getField("IS_EXPIRED");
                    // echo ($dokumen_certificate->query)."<br><br><br>";
                    $text =$dokumen_certificate->getField("EXPIRED_DATE2");
                    if($is_expired == "EXPIRED")
                    {
                        $color = "red";
                        $text = '<div style="background:red"><b> '.$dokumen_certificate->getField("EXPIRED_DATE2").'</b></div> '.$dokumen_certificate->getField("REMARKS");
                        // echo $color.$jenis_kualifikasi->getField('DOCUMENT_ID')."<br><br><br>";
                        array_push($arrDataKode, $jenis_kualifikasi->getField('DOCUMENT_ID'));
                        $bollean=true;
                        array_push($certificate_expired_col, $i);
                    }
                     $row[] =$text;
                }else if ($aColumns[$i] == "JENIS"){
                    $row[] = $jenis_kualifikasi->getField($aColumns[$i]);
                }else if("DOCUMENT_ID"==$aColumns[$i]){
                    $row[] = $jenis_kualifikasi->getField($aColumns[$i]);
                
            }else if("STATUS2"==$aColumns[$i]){
                         $color2=$color;
                         $row[] = $color;
                }
                else if("STATUS"==$aColumns[$i]){
                    $total = $dokumen_certificate2->getCountByParamsMonitoring(array("A.DOKUMEN_ID"=>$jenis_kualifikasi->getField('DOCUMENT_ID')), " AND COALESCE(A.EXPIRED_DATE, A.ISSUE_DATE) < CURRENT_DATE");
                    $warna='';
                    if($total > 0){
                             $warna='red';
                    }

                 $row[]=$warna;
                }
                else if("REMARKS2"==$aColumns[$i]){

                 $row[]=$jenis_kualifikasi->getField('REMARKS');
                }
                else if("REMARKS"==$aColumns[$i]){

                 $row[]=$jenis_kualifikasi->getField('REMARKS');
                }
                else if("CERTIFICATE_EXPIRED"==$aColumns[$i]){
                    $row[] =$certificate_expired_col;
                }
                else{
                    $row[] =$jenis_kualifikasi->getField($aColumns[$i]);
                }
            }
            // print_r($arrDataKode)."<br><br><br>";
            $output['aaData'][] = $row;
        }
        // exit;
        echo json_encode($output);
    }


    function add()
    {
        $this->load->model("DokumenKualifikasi");
        $dokumen_kualifikasi = new DokumenKualifikasi();

        $this->load->library("FileHandler");
        $file = new FileHandler();
        $filesData = $_FILES["document"];
        $reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
        $file->cekSize($filesData,$reqLinkFileTemp);

        $reqMode = $this->input->post("reqMode");

        $reqId = $this->input->post("reqId");

        $dokumen_kualifikasi->setField("DOCUMENT_ID", $reqId);
        $reqJenisId             = $this->input->post("reqPosition");
        $reqName                = $this->input->post("reqName");
        $reqAddress             = $this->input->post("reqAddress");
        $reqBirthDate           = $this->input->post("reqBirthDate");
        $reqPhone               = $this->input->post("reqPhone");
        $reqPhone2              = $this->input->post("reqPhone2");
        $reqDescription         = $this->input->post("reqDescription");
        $reqPath                = $this->input->post("reqPath");
        $reqTipe                = $this->input->post("reqTipe");
        $reqRemarks             = $this->input->post("reqRemarks");
        // echo dateToDBCheck2($reqBirthDate);

        $reqTypeOfService        = $this->input->post("reqTypeOfService");
        $reqCertificateName      = $this->input->post("reqCertificateName");
        $reqIssueDate            = $this->input->post("reqIssueDate");
        $reqExpiredDate          = $this->input->post("reqExpiredDate");
        $reqIdSertifikat         = $this->input->post("reqIdSertifikat");
        $reqIdSertifikatDelete   = $this->input->post("reqIdSertifikatDelete");
        $reqIdSertifikatDelete   = explode(",", $reqIdSertifikatDelete);

        // print_r($reqIssueDate);exit;
        $str_desc = '';
        // for ($i = 0; $i < count($reqTypeOfService); $i++) {
        //     if (!empty($reqTypeOfService[$i])) {
        //         if ($i == 0) {
        //             $str_desc .= $reqTypeOfService[$i];
        //         } else {
        //             $str_desc .= ',' . $reqTypeOfService[$i];
        //         }
        //     }
        // }
        $dokumen_kualifikasi->setField("NAME", $reqId);
        $dokumen_kualifikasi->setField("NAME", $reqName);
        $dokumen_kualifikasi->setField("ADDRESS", $reqAddress);
        $dokumen_kualifikasi->setField("JENIS_ID", $reqJenisId);
        $dokumen_kualifikasi->setField("BIRTH_DATE", dateToDBCheck($reqBirthDate));
        $dokumen_kualifikasi->setField("PHONE", $reqPhone);
        $dokumen_kualifikasi->setField("PHONE2", $reqPhone2);
        $dokumen_kualifikasi->setField("DESCRIPTION", $str_desc);
        $dokumen_kualifikasi->setField("PATH", $reqPath);
        $dokumen_kualifikasi->setField("REMARKS", $reqRemarks);



        if (empty($reqId)) {
            $dokumen_kualifikasi->insert();
            $reqId = $dokumen_kualifikasi->id;
        } else {
            $dokumen_kualifikasi->update();
        }


        $name_folder = strtolower(str_replace(' ', '_', $reqTipe));
       
        $FILE_DIR = "uploads/" . $name_folder . "/" . $reqId . "/";
        makedirs($FILE_DIR);


        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
            if ($file->uploadToDirArray('document', $FILE_DIR, $renameFile, $i)) {
                array_push($arrData, setQuote($renameFile));
            } else {
                array_push($arrData, $reqLinkFileTemp[$i]);
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
        $str_name_path2 = '';
        for ($i = 0; $i < count($reqCertificateName); $i++) {
            if (!empty($reqCertificateName[$i])) {
                if ($i == 0) {
                    $str_name_path2 .= $reqCertificateName[$i];
                } else {
                    $str_name_path2 .= ';' . $reqCertificateName[$i];
                }
            }
        }


        $this->load->model("DokumenKualifikasi");
        $dokumen_kualifikasi = new DokumenKualifikasi();
        $dokumen_kualifikasi->setField("DOCUMENT_ID", $reqId);
        $dokumen_kualifikasi->setField("PATH", ($str_name_path));
        $dokumen_kualifikasi->update_path();


        $this->load->model("DokumenSertifikat");
        
        for ($i=0; $i < count($reqIdSertifikatDelete); $i++) 
        { 
            if($reqIdSertifikatDelete[$i] != "")
            {
                $dokumen_sertifikat_delete = new DokumenSertifikat();
                $dokumen_sertifikat_delete->setField("DOKUMEN_SERTIFIKAT_ID", $reqIdSertifikatDelete[$i]);
                $dokumen_sertifikat_delete->delete();
            }    
        }

        for ($i=0; $i < count($reqTypeOfService); $i++) 
        { 
            if($reqTypeOfService[$i] != "")
            {
                $dokumen_sertifikat = new DokumenSertifikat();
                $dokumen_sertifikat->setField("DOKUMEN_ID", $reqId);
                $dokumen_sertifikat->setField("NAME", $reqCertificateName[$i]);
                $dokumen_sertifikat->setField("CERTIFICATE_ID", ValToNullDB($reqTypeOfService[$i]));
                $dokumen_sertifikat->setField("ISSUE_DATE", dateToDBCheck($reqIssueDate[$i]));
                $dokumen_sertifikat->setField("EXPIRED_DATE", dateToDBCheck($reqExpiredDate[$i]));
                $reqIdxSertifikat =  $reqIdSertifikat[$i];
                if (empty($reqIdSertifikat[$i]))
                {
                    $dokumen_sertifikat->insert();
                     $reqIdxSertifikat = $dokumen_sertifikat->id;
                }
                else
                {
                    $dokumen_sertifikat->setField("DOKUMEN_SERTIFIKAT_ID", $reqIdSertifikat[$i]);
                    $dokumen_sertifikat->update();
                }
                 

            }
        }

       
        // $dokumen_sertifikat->insert();
        // echo $dokumen_sertifikat->query;exit;
          
        $this->addEmergencyContact($reqId);
        $this->add_tambahan($reqId);
        $this->updateLampiranSertifikat();

        echo $reqId . '-Data Berhasil di simpan';
    }

    function updateLampiranSertifikat(){
        $this->load->model("DokumenSertifikat");
       $this->load->library("FileHandler");
       $file = new FileHandler();
       
        $reqLampiranSertifikat2              =  $this->input->post("reqLampiranSertifikat2");
         $reqLampiranSertifikat2 = array_unique( $reqLampiranSertifikat2);
        // print_r($reqLampiranSertifikat2 );
       
       $reqTipe ='lampiran_sertifikat';
        $name_folder = strtolower(str_replace(' ', '_', $reqTipe));
       foreach ( $reqLampiranSertifikat2 as $value) {
         $reqId = $value;
         $filesData = $_FILES["document".$reqId];
            $reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp".$reqId);
       
        $FILE_DIR = "uploads/" . $name_folder . "/" . $reqId . "/";
        makedirs($FILE_DIR);


        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
            if ($file->uploadToDirArray('document'.$reqId, $FILE_DIR, $renameFile, $i)) {
                array_push($arrData, setQuote($renameFile));
            } else {
                array_push($arrData, $reqLinkFileTemp[$i]);
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

        $dokumensertifikat = new DokumenSertifikat();
        $dokumensertifikat->setField('DOKUMEN_SERTIFIKAT_ID',$reqId);
        $dokumensertifikat->setField('LAMPIRAN',$str_name_path);
        $dokumensertifikat->updateLampiran();
        }


    }

     function create_qr($reqId='',$nipp='',$lokasi){
            $this->load->library("qrcodegenerator");
            $qrcodegenerators =  new qrcodegenerator();

            $qrcodegenerators->generateQr($reqId,$nipp,$lokasi); 

            
    }
    function deleteEmergency(){
         $this->load->model('EmergencyContact');
         $reqId = $this->input->get('reqId');
         $emergencycontact = new EmergencyContact();
         $emergencycontact->setField('EMERGENCY_CONTACT_ID',$reqId);
         $emergencycontact->delete();
         echo 'Data berhasil di hapus';

    }
    function addEmergencyContact($reqId){
        $this->load->model('EmergencyContact');
        $this->load->model("DokumenKualifikasi");
       
         $reqFieldId = $this->input->post('reqFieldId');
         $reqModul = $this->input->post('reqModul');
         $reqNoHpEmergency = $this->input->post('reqNoHpEmergency');
         $reqNamaEmergeny = $this->input->post('reqNamaEmergeny');
         $reqKeteranganEmergeny = $this->input->post('reqKeteranganEmergeny');

         for($i=0;$i<count($reqFieldId);$i++){
               $emergencycontact = new EmergencyContact();
               $emergencycontact->setField('MODUL',$reqModul[$i]);
               $emergencycontact->setField('FIELD_ID',$reqId);
               $emergencycontact->setField('EMERGENCY_CONTACT_ID',$reqFieldId[$i]);
               $emergencycontact->setField('HP',$reqNoHpEmergency[$i]);
               $emergencycontact->setField('NAMA',$reqNamaEmergeny[$i]);
               $emergencycontact->setField('KETERANGAN',$reqKeteranganEmergeny[$i]);
               if(!empty($reqNoHpEmergency[$i])){

                        if(empty($reqFieldId[$i])){
                            $emergencycontact->insert();
                        }else{
                             $emergencycontact->update();
                        }

               }
         }

         $reqNoRekening = $this->input->post('reqNoRekening');
         $dokumen_kualifikasi = new DokumenKualifikasi();
         $dokumen_kualifikasi->setField('NO_REKENING',$reqNoRekening);
         $dokumen_kualifikasi->setField('DOCUMENT_ID',$reqId);
         $dokumen_kualifikasi->update_contact();
        

    }

    function add_tambahan($reqId){
        $this->load->model('JenisKualifikasi');

       $reqIdCard             = $this->input->post("reqIdCard");
       $reqIdNumber                = $this->input->post("reqIdNumber");
       // echo $reqIdNumber;exit;
       $reqCabangId             = $this->input->post("reqCabangId");
        $reqJenisId             = $this->input->post("reqPosition");
       $this->load->model("DokumenKualifikasi");
       $dokumen_kualifikasi = new DokumenKualifikasi();
       $dokumen_kualifikasi->setField("ID_NUMBER", $reqIdNumber);
       $dokumen_kualifikasi->setField("ID_CARD", $reqIdCard);
       $dokumen_kualifikasi->setField("CABANG_ID", ValToNullDB($reqCabangId));
       $dokumen_kualifikasi->setField("DOCUMENT_ID", $reqId);
       $dokumen_kualifikasi->update_tambahan();


       $reqBarcode = $reqId.'-'. $reqIdCard.'-'. $reqCabangId; 
       $this->create_qr($reqId,$reqBarcode,'personal_kualifikasi');


       $jeniskualifikasi = new JenisKualifikasi();
       $jeniskualifikasi->selectByParamsMonitoring(array("A.JENIS_ID"=>$reqJenisId));
       $arrJenis = $jeniskualifikasi->rowResult;
       $arrJenis = $arrJenis[0];
       $fzeropadded = sprintf("%04d", $reqId);
       $fzeroCabang = sprintf("%02d", $reqCabangId);
       $fzeropadded = $fzeropadded.'-'.$arrJenis['kode'].'-'.$fzeroCabang;
        $dokumen_kualifikasi->setField("DOCUMENT_ID", $reqId);
       $dokumen_kualifikasi->setField("ID_NUMBER", $fzeropadded);
       $dokumen_kualifikasi->updateIdNumber();


    }

    function delete()
    {
        $this->load->model("DokumenKualifikasi");
        $dokumen_kualifikasi = new DokumenKualifikasi();


        $reqId = $this->input->get('reqId');

        $dokumen_kualifikasi->setField("DOCUMENT_ID", $reqId);
        if ($dokumen_kualifikasi->delete())
            $arrJson["PESAN"] = "Data berhasil dihapus.";
        else
            $arrJson["PESAN"] = "Data gagal dihapus.";

        echo json_encode($arrJson);
    }

    public function getPosition($reqId="0")
    {
        $this->load->model("DokumenKualifikasi");
        $dokumen_kualifikasi = new DokumenKualifikasi();
        $dokumen_kualifikasi->selectByParamsMonitoringPersonil(array("DOCUMENT_ID" => $reqId));
        $dokumen_kualifikasi->firstRow();
        echo $dokumen_kualifikasi->getField("POSITION_NAMA");

    }
}
