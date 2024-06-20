<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class dokument_tender_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID = $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA = $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN = $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES = $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN = $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID = $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP = $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->CABANG_ID = $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->CABANG = $this->kauth->getInstance()->getIdentity()->CABANG;
	}

	function json()
	{
		// echo "tes";
		$this->load->model("DocumentTender");
		$document = new DocumentTender();
		// var_dump($document);
		// echo $reqKategori;exit;

		$reqKategori = $this->input->get('reqKategori');

		$aColumns		= array("DOCUMENT_TENDER_ID","NO","CATEGORY","NAME","DESCRIPTION");
		$aColumnsAlias	= array("DOCUMENT_TENDER_ID","NO","CATEGORY","NAME","DESCRIPTION");

		// var_dump($aColumns);


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
				$sOrder = " ORDER BY A.".$aColumns[0]." desc";
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

		if (!empty($reqKategori)) {
			// $statement_privacy = " AND A.CATEGORY='" . $reqKategori . "'";
		}

		$reqCariCompanyName = $this->input->get('reqCariCompanyName');
		$reqCariDescription = $this->input->get('reqCariDescription');
	

        $_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariDescription"] = $reqCariDescription;
     
		

		if (!empty($reqCariCompanyName)) {
			$statement_privacy .= " AND UPPER(A.NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%'";
		}
		if (!empty($reqCariDescription)) {
			$statement_privacy .= " AND UPPER(A.DESCRIPTION) LIKE'%" . $reqCariDescription . "%'";
		}
	

		$statement = " AND (UPPER(A.NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		$_SESSION[$this->input->get("pg")."reqCariSession"] = $statement_privacy . $statement;
		$allRecord = $document->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $document->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

		$document->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
		// echo $document->query;exit;

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
		 $nomer=0;
		while ($document->nextRow()) {
			$row = array();
			$total_pagination  = ($dsplyStart)+$nomer;
			$penomoran = $allRecordFilter-($total_pagination);
			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN"){
					$row[] = truncate($document->getField($aColumns[$i]), 10) . "...";
				}
				elseif ($aColumns[$i] == "LINK_FILE"){
					$row[] = "<a href='uploads/" . $document->getField($aColumns[$i]) . "' height='50px' target='_blank'>Unduh</a>";
				
				} else if ($aColumns[$i] == "NO") {
                    $row[] = $penomoran;
                }
				elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
					if ($document->getField($aColumns[$i]) == "Y")
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					else
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
				} else
					$row[] = $document->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
			 $nomer++;
		}
		echo json_encode($output);
	}

	function  add()
	{
		$this->load->model('DocumentTender');

		$this->load->library("FileHandler");
		$file = new FileHandler();


		$reqId 				= $this->input->post('reqId');
		$reqKeterangan 		= $this->input->post('reqKeterangan');
		$reqNama 		 	= $this->input->post('reqNama');
		$reqTipe 		 	= $this->input->post('reqTipe');


		$name_folder = strtolower(str_replace(' ', '_', $reqTipe));

		$document = new DocumentTender();
		$document->setField("DOCUMENT_TENDER_ID", $reqId);
		$document->setField("NAME", $reqNama);
		$document->setField("CATEGORY", $reqTipe);
		$document->setField("DESCRIPTION", $reqKeterangan);
			$bollean=false;
		if (empty($reqId)) {
			$document->insert();
			$reqId = $document->id;
				$bollean=true;
		} else {
			$document->update();
		}

		$arrMenu = array('dokument_adminitrasi','dokument_teknis','dokument_komersial');
		 $field_data =array('PATH1','PATH2',"PATH3");
		  $index_colom=0;

         $reqTipe = $this->input->post('reqTipe');
		for($kk=0;$kk<count($arrMenu);$kk++){
			$reqTypeDocTender				= $this->input->post("reqType".$arrMenu[$kk]);
			$reqLinkFileDocTender           = $_FILES["reqLinkFile".$arrMenu[$kk]];
			$reqLinkFileDocTenderTemp       = $this->input->post("reqLinkFile".$arrMenu[$kk]."Temp");
			$stringFileName = "reqLinkFile".$arrMenu[$kk];

			// print_r($reqLinkFileDocTender );
			  $FILE_DIR = "uploads/".$arrMenu[$kk]."/" . $reqId . "/";
       		  makedirs($FILE_DIR);

			$arrDocTender = array();
			for ($i = 0; $i < count($reqLinkFileDocTender['name']); $i++) {
				$renameFile = $reqId . '-' . $i ."-".$kk. "-"  . $reqLinkFileDocTender['name'][$i];
				if ($file->uploadToDirArray($stringFileName, $FILE_DIR, $renameFile, $i)) {
					array_push($arrDocTender, array(
						"type" => $reqTypeDocTender[$i],
						"file" => setQuote($renameFile)
					));
				} else {
					array_push($arrDocTender, array(
						"type" => $reqTypeDocTender[$i],
						"file" => $reqLinkFileDocTenderTemp[$i]
					));
				}
				$tender = new DocumentTender();
			 $tender->setField("DOCUMENT_TENDER_ID", $reqId);
             $tender->setField("COLOMN", $field_data[$index_colom]);
             // FIELD_NAMA
             $tender->setField("FIELD", json_encode($arrDocTender));
             $tender->update_path();
			}
				

			 $index_colom++;
    	}
    	$this->load->model("TenderTypeUpload");
    	if($bollean){
    			$arrField = array('Dokumen  Administrasi','Dokumen  Teknis','Dokumen  Komersial');
    			 $arrMenu = array('dokument_adminitrasi','dokument_teknis','dokument_komersial');
    		for($i=0;$i<count($arrField);$i++){ 
    			$tender_type_upload = new TenderTypeUpload();
    			$tender_type_upload->setField("NAME", $arrField[$i]);
    			$tender_type_upload->setField("TYPE",  $arrMenu[$i]);
    			$tender_type_upload->setField("DESCRIPTION", '-');
    			$tender_type_upload->setField("TENDER_ID", $reqId);
    			$tender_type_upload->insert();
    		}
    	}
		
		// $this->add_dokument_baru($reqId);

		echo $reqId . '- Data berhasil di simpan';
	}

	 function add_dokument_baru($reqId=''){
         $this->load->model("DocumentTender");
         $reqTipe = $this->input->post('reqTipe');
         $tender = new DocumentTender();
         $this->load->library("FileHandler");
         $file = new FileHandler();
        $field_data =array('PATH1','PATH2',"PATH3");
        $index_colom=0;
         for($kk=2;$kk<5;$kk++){
             $filesData = $_FILES["document".$kk];
             $reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp".$kk);
             $FILE_DIR = "uploads/".$reqTipe."/" . $reqId . "/".$kk."/";
             makedirs($FILE_DIR);

             $arrData = array();
             for ($i = 0; $i < count($filesData['name']); $i++) {
                $renameFile =   $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
                if ($file->uploadToDirArray('document'.$kk, $FILE_DIR, $renameFile, $i)) {
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

             $tender->setField("DOCUMENT_TENDER_ID", $reqId);
             $tender->setField("COLOMN", $field_data[$index_colom]);
             // FIELD_NAMA
             $tender->setField("FIELD", ($str_name_path));
             $tender->update_path();


             $index_colom++;
         }

        
        

    }


	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("DocumentTender");
		$pengurus = new DocumentTender();


		$pengurus->setField("DOCUMENT_TENDER_ID", $reqId);
		$pengurus->delete();
		echo ' Data berhasil di hapus';
	}

	

	
	function combo()
	{
		$this->load->model("Pengurus");
		$pengurus = new Pengurus();

		$pengurus->selectByParams(array());
		$i = 0;
		while ($pengurus->nextRow()) {
			$arr_json[$i]['id']		= $pengurus->getField("PENGURUS_ID");
			$arr_json[$i]['text']	= $pengurus->getField("NAMA");
			$i++;
		}

		echo json_encode($arr_json);
	}

	function send_as_email()
	{
		$this->load->model("ResikoEmail");
		$resiko_email = new ResikoEmail();
		$this->load->model('DocumentTender');


		$reqId    		= $this->input->post("reqId");
		$reqName3 		= $this->input->post("reqName3");
		$reqDescription = $_POST["reqDescription"];

		$arrData[$KETERANGAN] = $reqDescription;
		$reqSubject = ' Company Profile	';

		$document = new DocumentTender();
		$document->selectByParams(array("A.DOCUMENT_ID" => $reqId));
		$arrAttachemt = array();
		while ($document->nextRow()) {
			$reqPath                 = $document->getField("PATH");
			$files_data = explode(',',  $reqPath);
			for ($i = 0; $i < count($files_data); $i++) {
				if (!empty($files_data[$i])) {
					$texts = explode('-', $files_data[$i]);
					$doc_lampiran = "uploads/company_profile/" . $reqId . "/" . $files_data[$i];
					array_push($arrAttachemt, $doc_lampiran);
				}
			}
		}


		$arrData["KETERANGAN"] = $reqDescription;

		$arrDataAddres = array();
		$indexs = 0;
		$reqName3s = explode(',', $reqName3);
		for ($i = 0; $i < count($reqName3s); $i++) {
			$nama_emails = array();
			$nama_emails = explode('[', $reqName3s[$i]);
			$nama_penerima = $nama_emails[0];
			if (strpos($nama_emails, '@') !== false) {
				$nama_email =  str_replace("]", '', $nama_emails[1]);
				$arrDataAddres[$indexs]["EMAIL"] = $nama_email;
				$arrDataAddres[$indexs]["PENERIMA"] = $nama_penerima;
				$indexs++;
			}
		}

		try {
			$this->load->library("KMail");
			$mail = new KMail();
			$body =  $this->load->view('email/pesan', $arrData, true);

			// for ($i = 0; $i < count($reqName3s); $i++) {
			// 	$nama_emails = explode('[', $reqName3s[$i]);
			// 	$nama_email = str_replace(' ', '', $nama_emails[0]);

			// 	$nama_penerima = pre_regregName($reqName3s[$i]);
			// 	$mail->AddAddress($resiko_email->sendEmail($nama_email),  $nama_penerima);
			// }

			for ($i = 0; $i < count($arrDataAddres); $i++) {
				$mail->AddAddress($arrDataAddres[$i]['EMAIL'], $arrDataAddres[$i]['PENERIMA']);
			}

			for ($i = 0; $i < count($arrAttachemt); $i++) {
				$mail->addAttachment($arrAttachemt[$i]);
			}
			$mail->Subject  =  " [AQUAMARINE] " . $reqSubject;
			$mail->Body = $body;
			// $mail->MsgHTML($body);
			if (!$mail->Send()) {

				echo "Error sending: " . $mail->ErrorInfo;
			} else {
				// echo "E-mail sent to " . $email . '<br>';
			}
			// $mail->Send();

			unset($mail);
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		echo 'Email berhasil dikirim';
	}
}
