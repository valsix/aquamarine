<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class pre_report_json extends CI_Controller
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

	
	function  add(){

		$this->load->model('Document');
		
		$reqId 				= $this->input->post('reqId');
		$reqKeterangan 		= $this->input->post('reqKeterangan');
		$reqNama 		 	= $this->input->post('reqNama');
		$reqTipe 		 	= $this->input->post('reqTipe');

		$name_folder = strtolower(str_replace(' ','_', $reqTipe));

		$document = new Document();
		$document->setField("DOCUMENT_ID",$reqId);
		$document->setField("NAME",$reqNama);
		$document->setField("CATEGORY",$reqTipe);
		$document->setField("DESCRIPTION",$reqKeterangan);

		if(empty($reqId)){
			$document->insert();
			$reqId =$document->id;

		}else{
			$document->update();
		}
		

		$reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$filesData=$_FILES["document"];
		$FILE_DIR= "uploads/".$name_folder."/".$reqId."/";         
		makedirs($FILE_DIR);

		$arrData =array();
		for($i=0;$i<count($filesData);$i++){
			$renameFile = "DOC".date("dmYhis").'-'.$reqId.'-'.$i.".".getExtension($filesData['name'][$i]);
			if($file->uploadToDirArray('document', $FILE_DIR, $renameFile, $i))
			{
				array_push($arrData, $renameFile);

			}else{
				array_push($arrData, $reqLinkFileTemp[$i]);

			}
		}
		$str_name_path='';
		for($i=0;$i<count($arrData);$i++){
			if(!empty($arrData[$i])){
				if($i==0){
					$str_name_path .=$arrData[$i];
				}else{
					$str_name_path .=','.$arrData[$i];    
				}
			}    
		}

		$document = new Document();
		$document->setField("DOCUMENT_ID",$reqId);
		$document->setField("PATH",$str_name_path);
		$document->updatePath();



		echo $reqId.'- Data berhasil di simpan';

	}


	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Pengurus");
		$pengurus = new Pengurus();


		$pengurus->setField("PENGURUS_ID", $reqId);
		if ($pengurus->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
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
}
