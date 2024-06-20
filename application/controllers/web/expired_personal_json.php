<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class expired_personal_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->USERID 				= $this->kauth->getInstance()->getIdentity()->USERID;
		$this->USERNAME 			= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->FULLNAME 			= $this->kauth->getInstance()->getIdentity()->FULLNAME;
		$this->USERPASS 			= $this->kauth->getInstance()->getIdentity()->USERPASS;
		$this->LEVEL 				= $this->kauth->getInstance()->getIdentity()->LEVEL;
		$this->MENUMARKETING 		= $this->kauth->getInstance()->getIdentity()->MENUMARKETING;
		$this->MENUFINANCE 			= $this->kauth->getInstance()->getIdentity()->MENUFINANCE;
		$this->MENUPRODUCTION 		= $this->kauth->getInstance()->getIdentity()->MENUPRODUCTION;
		$this->MENUDOCUMENT 		= $this->kauth->getInstance()->getIdentity()->MENUDOCUMENT;
		$this->MENUSEARCH 			= $this->kauth->getInstance()->getIdentity()->MENUSEARCH;
		$this->MENUOTHERS 			= $this->kauth->getInstance()->getIdentity()->MENUOTHERS;
	}

	 function json()
    {
        $this->load->model("JenisKualifikasi");
        $jenis_kualifikasi = new JenisKualifikasi();
        $this->load->model("DokumenCertificate");
        $dokumen_certificate = new DokumenCertificate();

        $aColumns = array(
        	"DOCUMENT_ID", "NAME", "ADDRESS", "BIRTH_DATE", "PHONE", "QUALIFICATION", "CERTIFICATE","STATUS"
        );
        $aColumnsAlias = array(
        	"DOCUMENT_ID", "NAME", "ADDRESS", "BIRTH_DATE", "PHONE", "QUALIFICATION", "CERTIFICATE","STATUS"
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
        $statements ='';
        $statements .= " AND A.CATEGORY='".$reqCariLocationFolder."'";
       
        if(!empty($reqCariFindText)){
                // $statements .= " AND A.CATEGORY='".$reqCariLocationFolder."'";
        }

        $this->load->model("DokumenCertificate");
        $dokumen_certificate = new DokumenCertificate();

$this->load->model("JenisKualifikasi");
$jenis_kualifikasi = new JenisKualifikasi();
$jenis_kualifikasi->selectByParamsMonitoringPersonalKualifikasi(array(), -1,-1,$statement_privacy . $statement,$sOrder);
 $nomers=1;
 $arrDatas =array();
while ($jenis_kualifikasi->nextRow()) {

   $reqListCertificates    = $jenis_kualifikasi->getField("LIST_CERTIFICATE");
   $reqListCertificate = explode(',', $reqListCertificates);
   $bollean=false;
  $certificate ='<ol>';
   for($i=0;$i<count($reqListCertificate);$i++){
   	if(!empty($reqListCertificate[$i])){
   		$dokumen_certificate = new DokumenCertificate();
   		$dokumen_certificate->selectByParams(array("A.DOCUMENT_ID" => $reqListCertificate[$i]));
   		$dokumen_certificate->firstRow();
   		$reqNames            = $dokumen_certificate->getField("NAME");
   		$reqIssuedDates      = $dokumen_certificate->getField("ISSUED_DATE");
   		$reqExpiredDates     = $dokumen_certificate->getField("EXPIRED_DATE");

   		$tgl_skrng = Date('d-m-Y');
   		$exp_date = $dokumen_certificate->getField("DATES");
                // echo $tgl_skrng.'-'.$exp_date;
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
   if( empty($reqListCertificates)){
   	$certificate .="<li> Don't Have Certificate </li>";
   }

   $certificate .='</ol>';
   if($bollean || empty($reqListCertificates)){
   	for($jj=0;$jj<count($aColumns);$jj++){
   		if($aColumns[$jj]=='CERTIFICATE'){
   			$arrDatas[$nomers][$aColumns[$jj]] =$certificate;
   		}if($aColumns[$jj]=='STATUS'){
        $arrDatas[$nomers][$aColumns[$jj]] ="red";
      }else{
   			$arrDatas[$nomers][$aColumns[$jj]] =$jenis_kualifikasi->getField($aColumns[$jj]);
   		}
   		
   		
   	}
   	$nomers++;
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
       


         for($i=1;$i<count($arrDatas);$i++){
        	  $row = array();
        	 for ($j = 0; $j < count($aColumns); $j++) {
                if ($aColumns[$j] == "JENIS"){
                    $row[] = $arrDatas[$i][$aColumns[$j]];
                }else if("DOCUMENT_ID"==$aColumns[$j]){
                      $row[] = $arrDatas[$i][$aColumns[$j]];
                }
                else if("CERTIFICATE"==$aColumns[$j]){
                      $row[] = $certificate;
                }
                else{
                    $row[] = $arrDatas[$i][$aColumns[$j]];
                }
            }
        	 $output['aaData'][] = $row;
        }
		echo json_encode($output);

        
    }

}
