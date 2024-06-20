<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class issue_po_non_project_detail_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->CABANG		= $this->kauth->getInstance()->getIdentity()->CABANG;
	}

	function json()
	{
		$this->load->model("IssuePoNonProjectDetail");
		$issue_po_detail = new IssuePoNonProjectDetail();

		$reqMode = $this->input->get("reqMode");
		$reqId = $this->input->get("reqId");

		// echo $reqKategori;exit;

		$aColumns = array("ISSUE_PO_DETAIL_ID","KETERANGAN","QTY","SATUAN","AMOUNT","AMOUNT_IDR","AMOUNT_USD","TOTAL","CURENCY","TERM","STATUS_BAYAR_INFO","AKSI","STATUS_BAYAR");
		$aColumnsAlias	=  array("ISSUE_PO_DETAIL_ID","KETERANGAN","QTY","SATUAN","AMOUNT","AMOUNT_IDR","AMOUNT_USD","TOTAL","CURENCY","TERM","STATUS_BAYAR_INFO","AKSI","STATUS_BAYAR");


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
			if (trim($sOrder) == "ORDER BY A.".$aColumns[0]." asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.".$aColumns[0]." asc";
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

		if ($reqMode == "BELUM")
			$statement = " AND COALESCE(NULLIF(BALASAN, ''), 'X') = 'X' ";
		elseif ($reqMode == "SUDAH")
			$statement = " AND NOT COALESCE(NULLIF(BALASAN, ''), 'X') = 'X' ";

		if ($reqId)
		{
			$statement = " AND A.ISSUE_PO_ID = ".$reqId;
		}

		// $statement .= " AND (UPPER(B.NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR UPPER(B.NIP) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR UPPER(A.NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$allRecord = $issue_po_detail->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $issue_po_detail->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

		$issue_po_detail->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

		// echo $issue_po_detail->query;exit;

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
		$nom=0;
		while ($issue_po_detail->nextRow()) {
			$row = array();
			  $ids  = $issue_po_detail->getField($aColumns[0]);
			   $cur  = $issue_po_detail->getField('CURENCY');
			   $mata_uang='$ ';
			   if( $cur==1){
			   	$mata_uang='Rp ';
			   }
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "TOTAL"){

					$row[] = $mata_uang. currencyToPage2($issue_po_detail->getField($aColumns[$i]));
				}else if($aColumns[$i] == "AMOUNT"){
					$row[] = currencyToPage2($issue_po_detail->getField($aColumns[$i]));
				}else if ($aColumns[$i] == "AMOUNT_IDR"){
					if($cur==1){
						$row[]=$issue_po_detail->getField("TOTAL");
					}else{
						$row[]=0;
					}
				}else if ($aColumns[$i] == "AMOUNT_USD"){
					if($cur!=1){
						$row[]=$issue_po_detail->getField("TOTAL");
					}else{
						$row[]=0;
					}
					// $row[] = "<img src='uploads/" . $issue_po_detail->getField($aColumns[$i]) . "' height='50px'>";
				}else if ($aColumns[$i] == "LINK_FILE"){
					$row[] = "<img src='uploads/" . $issue_po_detail->getField($aColumns[$i]) . "' height='50px'>";
				}else if ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
					if ($issue_po_detail->getField($aColumns[$i]) == "Y"){
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					}
					else{
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
					}
				} else if ($aColumns[$i] == "AKSI") {
                    $btn_edit = '<button type="button"  class="btn btn-info " onclick=editing(' . $nom . ')  ><i class="fa fa-pencil-square-o fa-lg"> </i> </button>';
                    $btn_delete = '<button type="button"  class="btn btn-danger hapusi"  onclick="deleting(' . $nom . ')"><i class="fa 	fa-trash-o fa-lg"> </i> </button>';

                    $row[] = $btn_edit . $btn_delete;
                } else{
					$row[] = $issue_po_detail->getField($aColumns[$i]);
                }

			}
			$output['aaData'][] = $row;
			$nom++;
		}
		echo json_encode($output);
	}

	 function add_company()
    {
    	$reqCompanyId   = $this->input->post("reqCompanyId");
    	$reqCompanyName = $this->input->post("reqCompanyName");
    	$reqContact     = $this->input->post("reqContact");
    	$reqAddress     = $_POST["reqAddress"];
    	$reqEmail       = $this->input->post("reqEmail");
    	$reqTelp        = $this->input->post("reqTelp");
    	$reqFax         = $this->input->post("reqFax");
    	$reqHp          = $this->input->post("reqHp");

    	$this->load->model("Company");
    	$company = new Company();
    	$company->setField("COMPANY_ID", $reqCompanyId);
    	$company->setField("NAME", $reqCompanyName);
    	$company->setField("ADDRESS", $reqAddress);
    	$company->setField("PHONE", $reqHp);
    	$company->setField("FAX", $reqFax);
    	$company->setField("EMAIL", $reqEmail);
    	$company->setField("CP1_NAME", $reqContact);
    	$company->setField("CP1_TELP", $reqTelp);
    	$company->update_offer();

    }
	function add()
	{
		 
		$this->load->model("IssuePoNonProject");
		$this->load->model("IssuePoNonProjectDetail");
		$issue_po_detail = new IssuePoNonProjectDetail();
		$issue_po = new IssuePoNonProject();

		
		$reqId 							= $this->input->post("reqId");
		

		$reqNomerPo     = $this->input->post("reqNomerPo");
		$reqPoDate      = $this->input->post("reqPoDate");
		$reqDocLampiran = $this->input->post("reqDocLampiran");
		$reqReferensi   = $this->input->post("reqReferensi");
		$reqPathLampiran= $this->input->post("reqPathLampiran");
		$reqFinance     = $this->input->post("reqFinance");
		$reqCompanyId   = $this->input->post("reqCompanyId");
		$reqCompanyName = $this->input->post("reqCompanyName");
		$reqContact     = $this->input->post("reqContact");
		$reqAddress     = $_POST["reqAddress"];
		$reqEmail       = $this->input->post("reqEmail");
		$reqTelp        = $this->input->post("reqTelp");
		$reqStatusProses        = $this->input->post("reqStatusProses");
		$reqFax         = $this->input->post("reqFax");
		$reqHp          = $this->input->post("reqHp");
		$reqBuyerId     = $this->input->post("reqBuyerId");
		$reqOther       = $this->input->post("reqOther");
		$reqPpn         = $this->input->post("reqPpn");
		$reqPpnPercent  = $this->input->post("reqPpnPercent");
		$reqPic         = $this->input->post("reqPic");
		$reqDepartement = $this->input->post("reqDepartement");
		$reqNote 		= $this->input->post("reqNote");
		$reqTermsAndCondition = $_POST["reqTermsAndCondition"];
		$reqTypeCur 		= $this->input->post("reqTypeCur");

		$reqTypePo 				= $this->input->post("reqTypePo");
		$reqAcknowledgedBy 		= $this->input->post("reqAcknowledgedBy");
		$reqAcknowledgedDept 	= $this->input->post("reqAcknowledgedDept");
		$reqApproved1By 		= $this->input->post("reqApproved1By");
		$reqApproved1Dept 		= $this->input->post("reqApproved1Dept");
		$reqApproved2By 		= $this->input->post("reqApproved2By");
		$reqApproved2Dept 		= $this->input->post("reqApproved2Dept");
		
		$reqTextBuyer='';
		for($i=0;$i<count($reqBuyerId);$i++){
			$reqTextBuyer .= $reqBuyerId[$i].',';
		}
		


		$reqTipe 		 	= $this->input->post('reqTipe');
		$name_folder 		= strtolower(str_replace(' ', '_', $reqTipe));

		$issue_po->setField("ISSUE_PO_ID", $reqId);
		$issue_po->setField("NOMER_PO", $reqNomerPo);
		$issue_po->setField("PO_DATE", dateToDBCheck($reqPoDate));
		$issue_po->setField("DOC_LAMPIRAN", $reqDocLampiran);
		$issue_po->setField("REFERENSI", $reqReferensi);
		$issue_po->setField("PATH_LAMPIRAN", $reqPathLampiran);
		$issue_po->setField("FINANCE", $reqFinance);
		$issue_po->setField("COMPANY_ID", $reqCompanyId);
		$issue_po->setField("COMPANY_NAME", $reqCompanyName);
		$issue_po->setField("CONTACT", $reqContact);
		$issue_po->setField("ADDRESS", $reqAddress);
		$issue_po->setField("STATUS", $reqStatusProses);
		$issue_po->setField("EMAIL", $reqEmail);
		$issue_po->setField("TELP", $reqTelp);
		$issue_po->setField("TYPE_CUR", $reqTypeCur);
		$issue_po->setField("FAX", $reqFax);
		$issue_po->setField("HP", $reqHp);
		$issue_po->setField("BUYER_ID", $reqTextBuyer);
		$issue_po->setField("OTHER", $reqOther);
		$issue_po->setField("PPN", dotToNo($reqPpn));
		$issue_po->setField("PPN_PERCENT", dotToNo($reqPpnPercent));
		$issue_po->setField("PIC", $reqPic);
		$issue_po->setField("DEPARTEMENT", $reqDepartement);
		$issue_po->setField("TERMS_AND_CONDITION", setQuote($reqTermsAndCondition));
		$issue_po->setField("NOTE", setQuote($reqNote));

		$issue_po->setField("TYPE", setQuote($reqTypePo));
		$issue_po->setField("ACKNOWLEDGED_BY", setQuote($reqAcknowledgedBy));
		$issue_po->setField("ACKNOWLEDGED_DEPT", setQuote($reqAcknowledgedDept));
		$issue_po->setField("APPROVED1_BY", setQuote($reqApproved1By));
		$issue_po->setField("APPROVED1_DEPT", setQuote($reqApproved1Dept));
		$issue_po->setField("APPROVED2_BY", setQuote($reqApproved2By));
		$issue_po->setField("APPROVED2_DEPT", setQuote($reqApproved2Dept));

		$status='';



		if (empty($reqId)) {
			$issue_po->setField("CREATED_BY", $this->USERNAME);
			$issue_po->insert();
			$reqId = $issue_po->id;
			$status ='baru';
		} else {
			$issue_po->setField("BALASAN_BY", $this->USERNAME);
			$issue_po->update();
		}

		$reqTipe 		 	= $this->input->post('reqTipe');
		$name_folder = strtolower(str_replace(' ', '_', $reqTipe));
		$reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$filesData = $_FILES["document"];
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
					$str_name_path .= ',' . $arrData[$i];
				}
			}
		}
		$this->load->model("IssuePoNonProject");
		$issue_po = new IssuePoNonProject();
		$issue_po->setField("ISSUE_PO_ID", $reqId);
		$issue_po->setField("PATH_LAMPIRAN", $str_name_path);
		$issue_po->updatePath();
		

		$reqIssuePoDetailId 	= $this->input->post("reqIssuePoDetailId");
		$reqKeterangan 			= $_POST["reqKeterangan"];
		$reqQty 				= $this->input->post("reqQty");
		$reqSatuan 				= $this->input->post("reqSatuan");
		$reqAmount 				= $this->input->post("reqAmount");			
		$reqTotal 				= $this->input->post("reqTotal");
		$reqCurency 			= $this->input->post("reqCurrencys");
		$reqTerm 				= $this->input->post("reqTerm");
		$reqBayar 				= $this->input->post("reqBayar");

		$issue_po_detail->setField("ISSUE_PO_DETAIL_ID", $reqIssuePoDetailId);
		$issue_po_detail->setField("ISSUE_PO_ID", $reqId);
		$issue_po_detail->setField("KETERANGAN", $reqKeterangan);
		$issue_po_detail->setField("QTY",dotToNo($reqQty));
		$issue_po_detail->setField("SATUAN", $reqSatuan);
		$issue_po_detail->setField("CURENCY", $reqTypeCur );
		$issue_po_detail->setField("TERM", $reqTerm  );
		$issue_po_detail->setField("AMOUNT", dotToNo($reqAmount));
		$issue_po_detail->setField("STATUS_BAYAR", ValToNullDB($reqStatusProses));

		$reqTotal = dotToNo($reqQty)*dotToNo($reqAmount);
		$issue_po_detail->setField("TOTAL", $reqTotal);
		if(!empty($reqAmount)&&!empty($reqKeterangan)&&!empty($reqSatuan)){
			if(empty($reqIssuePoDetailId)){
				$issue_po_detail->insert();
			}else{
				$issue_po_detail->update();
			}
		}
		
		$this->add_company();

		$pesan ="Data berhasil disimpan.-";
		$pesan = $pesan.$reqId."-";
		echo $pesan;
		exit();
	}


	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Aduan");
		$aduan = new Aduan();


		$aduan->setField("ADUAN_ID", $reqId);
		if ($aduan->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}

	function deletedetail()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("IssuePoNonProjectDetail");
		$detail = new IssuePoNonProjectDetail();


		$detail->setField("ISSUE_PO_DETAIL_ID", $reqId);
		if ($detail->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}



	function forward()
	{
		$this->load->model("Aduan");
		$aduan = new Aduan();

		$reqId = $this->input->post("reqId");
		$reqMail = $this->input->post("reqMail");

		$this->load->library("KMail");

		$mail = new KMail();
		$body = file_get_contents(base_url() . "login/loadUrl/email/aduan/" . $reqId);
		//$mail->AddAddress($auditor->getField("EMAIL") , $auditor->getField("NAMA"));
		$mail->AddAddress($reqMail, $reqMail);
		$mail->Subject  =  "[SEKAR] Aduan Anggota";
		$mail->MsgHTML($body);
		if ($mail->Send())
			echo "Aduan berhasil diforward ke " . $reqMail . ".";
		else
			echo "Forward email gagal";
	}

	function combo()
	{
		$this->load->model("Aduan");
		$aduan = new Aduan();

		$aduan->selectByParams(array());
		$i = 0;
		while ($aduan->nextRow()) {
			$arr_json[$i]['id']		= $aduan->getField("ADUAN_ID");
			$arr_json[$i]['text']	= $aduan->getField("NAMA");
			$i++;
		}

		echo json_encode($arr_json);
	}
}
