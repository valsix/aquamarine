<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class combo_baru_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
	}

	// function comboOfferProject(){
	// 	$this->load->model("OfferProject");
	// 	$offer_project = new OfferProject();
	// 	$offer_project->selectByParamsMonitoring(array());
	// 	$arrData = $offer_project->rowResult;
	// 	$arrData = array_column($arrData, column_key)
	// 	$arrData = array_unique($arrData);
	// 	$arr_json = array();
	// 	$i=0;
	// 	foreach ($variable as  $value) {
	// 		$arr_json[$i]['id']='SER';
 // 			$arr_json[$i]['text']='SER';
 // 			$i++;
	// 	}
	// 	echo json_encode($arr_json);
	// }

	function combo_type_sub(){
		$i=0;
		$arr_json[$i]['id']='SUB';
 			$arr_json[$i]['text']='SUB';
 			$i++;
 			$arr_json[$i]['id']='SER';
 			$arr_json[$i]['text']='SER';
 			$i++;
 			$arr_json[$i]['id']='SNS';
 			$arr_json[$i]['text']='SNS';
 			$i++;
		echo json_encode($arr_json);
	}

	function combo_expired_personal(){
		$i=0;
		$arr_json[$i]['id']='';
 			$arr_json[$i]['text']='All';
 			$i++;
 			$arr_json[$i]['id']='1';
 			$arr_json[$i]['text']='Expired';
 			
 			$i++;
		echo json_encode($arr_json);
	}

	function combo_pemakain(){
		$i=0;
		$arr_json[$i]['id']='Good';
 			$arr_json[$i]['text']='Good';
 			$i++;
 			$arr_json[$i]['id']='Broken';
 			$arr_json[$i]['text']='Broken'; 			
 			$i++;
 			$arr_json[$i]['id']='Missing';
 			$arr_json[$i]['text']='Missing'; 			
 			$i++;
 			$arr_json[$i]['id']='Lost';
 			$arr_json[$i]['text']='Lost'; 			
 			$i++;
		echo json_encode($arr_json);
	}

	function combo_ambil_name(){
		$this->load->model("MasterCombo");
		$reqModul = $this->input->get('reqModul');

		$mastercombo = new MasterCombo();
		$mastercombo->selectByParamsMonitoring(array('A.MODUL'=>$reqModul));
		$arrData = $mastercombo->rowResult;
		$i=0;
		foreach ($arrData as  $value) {
			# code...
		
			$arr_json[$i]['id']=$value['nama'];
 			$arr_json[$i]['text']=$value['nama'];
 			$i++;
 		}		

		echo json_encode($arr_json);
	}

	function combo_ambil_name_storage(){
		$this->load->model("EquipStorage");
		$reqModul = $this->input->get('reqModul');

		$mastercombo = new EquipStorage();
		$mastercombo->selectByParamsMonitoring(array());
		$arrData = $mastercombo->rowResult;
		$i=0;
		foreach ($arrData as  $value) {
			# code...
		
			$arr_json[$i]['id']=$value['equip_storage_id'];
 			$arr_json[$i]['text']=$value['nama'];
 			$arr_json[$i]['kode']=$value['kode'];
 			
 			$i++;
 		}		

		echo json_encode($arr_json);
	}

	function combo_type_location(){
		$i=0;
		$arr_json[$i]['id']='01';
 			$arr_json[$i]['text']='Jakarta and West Java';
 			$i++;
 			$arr_json[$i]['id']='02';
 			$arr_json[$i]['text']='Central Java and East Java';
 			$i++;
 			$arr_json[$i]['id']='03';
 			$arr_json[$i]['text']='Outside Java';
 			$i++;
		echo json_encode($arr_json);
	}



	function combo_project(){
		$this->load->model("MasterProject");
		$masterproject= new MasterProject();

		$masterproject->selectByParamsMonitoring(array());
		$i=0;
		$reqMode = $this->input->get('reqMode'); 
			if(!empty($reqMode) && $reqMode =='ALL'){
				$arr_json[$i]['id']		= '';
				$arr_json[$i]['text']	= 'All';
				$i++;
			}
		while ($masterproject->nextRow()) {
			$arr_json[$i]['id']=$masterproject->getField("MASTER_PROJECT_ID");
 			$arr_json[$i]['text']=$masterproject->getField("NAMA");
 			$arr_json[$i]['code']=$masterproject->getField("CODE");
		$i++;	
		}
		echo json_encode($arr_json);
	}

	function combo_project_code(){
		$this->load->model("MasterProject");
		$masterproject= new MasterProject();

		$masterproject->selectByParamsMonitoring(array());
		$i=0;
		$reqMode = $this->input->get('reqMode'); 
			if(!empty($reqMode) && $reqMode =='ALL'){
				$arr_json[$i]['id']		= '';
				$arr_json[$i]['text']	= 'All';
				$i++;
			}
		while ($masterproject->nextRow()) {
			$arr_json[$i]['id']=$masterproject->getField("MASTER_PROJECT_ID");
 			$arr_json[$i]['text']=$masterproject->getField("CODE");
		$i++;	
		}
		echo json_encode($arr_json);
	}

	function combo_project_nama(){
		$this->load->model("MasterProject");
		$masterproject= new MasterProject();

		$masterproject->selectByParamsMonitoring(array());
		$i=0;
		$reqMode = $this->input->get('reqMode'); 
			if(!empty($reqMode) && $reqMode =='ALL'){
				$arr_json[$i]['id']		= '';
				$arr_json[$i]['text']	= 'All';
				$i++;
			}
		while ($masterproject->nextRow()) {
			$arr_json[$i]['id']=$masterproject->getField("MASTER_PROJECT_ID");
 			$arr_json[$i]['text']=$masterproject->getField("NAMA");
		$i++;	
		}
		echo json_encode($arr_json);
	}

	function combo_lokasi(){
		$this->load->model("EquipStorage");
		$masterproject= new EquipStorage();
		
		$masterproject->selectByParamsMonitoring(array());
		$i=0;
		
		$reqMode = $this->input->get('reqMode'); 
			if(!empty($reqMode) && $reqMode =='ALL'){
				$arr_json[$i]['id']		= '';
				$arr_json[$i]['text']	= 'All';
				$i++;
			}
		while ($masterproject->nextRow()) {
			$arr_json[$i]['id']=$masterproject->getField("EQUIP_STORAGE_ID");
 			$arr_json[$i]['text']=$masterproject->getField("NAMA");
		$i++;	
		}
		echo json_encode($arr_json);
	}

	function combo_vessel(){
		  $this->load->model("Vessel");
         $vessel = new Vessel();
         $vessel->selectByParamsMonitoring(array());
         $i=0;
         $arr_json[$i]['id']=$vessel->getField("NAMA");
         	$arr_json[$i]['text']=$vessel->getField("NAMA");
         		$i++;	
         while ($vessel->nextRow()) {
         	$arr_json[$i]['id']=$vessel->getField("NAMA");
         	$arr_json[$i]['text']=$vessel->getField("NAMA");
         	$i++;	
         }
         echo json_encode($arr_json);
	}

	function combo_provinsi(){
	 $query = $this->db->query('select propinsi_id,nama from propinsi' );

 	 $arrData = $query->result_array();
 	
 	 		$i=0;
		 	 foreach ($arrData as $value) {
			 	 	$arr_json[$i]['id']=$value['propinsi_id'];
			 	 	$arr_json[$i]['text']=$value['nama'];
		 	 $i++;	
		 	 }
		 	   echo json_encode($arr_json);
	}

	function combo_suplay(){
	 $query = $this->db->query('select nama from master_suplay' );
	 $reqMode = $this->input->get('reqMode');
	
 	 $arrData = $query->result_array();
 	
 	 		$i=0;
 	 		if($reqMode=='ALL'){
 	 				$arr_json[$i]['id']='';
			 	 	$arr_json[$i]['text']='All';
		 	 $i++;	
 	 		}
		 	 foreach ($arrData as $value) {
			 	 	$arr_json[$i]['id']=$value['nama'];
			 	 	$arr_json[$i]['text']=$value['nama'];
		 	 $i++;	
		 	 }
		 	   echo json_encode($arr_json);
	}

	function combo_kabupaten(){

	 $reqId = $this->input->get('reqId');	
	 $statement = ' AND propinsi_id='.ValToNullDB($reqId);
	 $query = $this->db->query('select kabupaten_id,nama from kabupaten where 1=1 '. $statement);

 	 $arrData = $query->result_array();
 	
 	 		$i=0;
		 	 foreach ($arrData as $value) {
			 	 	$arr_json[$i]['id']=$value['kabupaten_id'];
			 	 	$arr_json[$i]['text']=$value['nama'];
		 	 $i++;	
		 	 }
		 	   echo json_encode($arr_json);
	}

	function combo_offer_project(){
		$this->load->model("OfferProject");
        $offer_project = new OfferProject();
         $offer_project->selectByParams(array());
         $i=0;
         $arr_json[$i]['id']='';
         $arr_json[$i]['text']='All';
         $i++;	
          while ($offer_project->nextRow()) {
         	$arr_json[$i]['id']=$offer_project->getField("DESCRIPTION");
         	$arr_json[$i]['text']=$offer_project->getField("DESCRIPTION");
         	$i++;	
         }
         echo json_encode($arr_json);

	}

	function combo_lokasi2(){
		$this->load->model("EquipStorage");
		$masterproject= new EquipStorage();

		$masterproject->selectByParamsMonitoring(array());
		$i=0;
		$reqMode = $this->input->get('reqMode'); 
			if(!empty($reqMode) && $reqMode =='ALL'){
				$arr_json[$i]['id']		= '';
				$arr_json[$i]['text']	= 'All';
				$i++;
			}
		while ($masterproject->nextRow()) {
			if(!empty($masterproject->getField("NAMA"))){
			$arr_json[$i]['id']=$masterproject->getField("NAMA");
 			$arr_json[$i]['text']=$masterproject->getField("NAMA");
			$i++;	
			}
		}
		echo json_encode($arr_json);
	}

	function combo_pembayaran(){
		
			$i=0;
			$reqMode = $this->input->get('reqMode'); 
			if(!empty($reqMode) && $reqMode =='ALL'){
				$arr_json[$i]['id']		= '';
				$arr_json[$i]['text']	= 'All';
				$i++;
			}
			$arr_json[$i]['id']='CASH';
 			$arr_json[$i]['text']='CASH';
 			$i++;
 			$arr_json[$i]['id']='KREDIT';
 			$arr_json[$i]['text']='KREDIT';
 			$i++;
 			echo json_encode($arr_json);
	}

	function combo_pelayanan(){
		
			$i=0;
			$reqMode = $this->input->get('reqMode'); 
			if(!empty($reqMode) && $reqMode =='ALL'){
				$arr_json[$i]['id']		= '';
				$arr_json[$i]['text']	= 'All';
				$i++;
			}
			$arr_json[$i]['id']='BAIK';
 			$arr_json[$i]['text']='BAIK';
 			$i++;
 			$arr_json[$i]['id']='SEDANG';
 			$arr_json[$i]['text']='SEDANG';
 			$i++;
 			$arr_json[$i]['id']='BURUK';
 			$arr_json[$i]['text']='BURUK';
 			$i++;
 			echo json_encode($arr_json);
	}

}
