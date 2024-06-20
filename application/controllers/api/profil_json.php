<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class profil_json extends REST_Controller {
 
    function __construct() {
        parent::__construct(); 
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }
 
    // show data entitas
	function index_get() {
		
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");   
		/* AMBIL TOKEN */
        $this->load->model('UserLoginMobile');
        $user_login_mobile = new UserLoginMobile();
        $reqToken = $this->input->get('reqToken');
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
		
		if($reqPegawaiId == "0" || $reqPegawaiId == "")
		{
			$this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
			return;	
		}
		/* END OF AMBIL TOKEN */
	
        $aColumns = array("PEGAWAI_ID", "CABANG_ID", "NO_SEKAR","NRP", "NIP", "NAMA", "NAMA_PANGGILAN", "JENIS_KELAMIN", "TEMPAT_LAHIR", 
       "TANGGAL_LAHIR", "UNIT_KERJA", "ALAMAT", "NOMOR_HP", "EMAIL_PRIBADI", "EMAIL_BULOG", "GOLONGAN_DARAH",
       "NOMOR_WA", "FOTO");
        
        $this->load->model("Pegawai");
        $pegawai = new Pegawai();

        $pegawai->selectByParams(array("NIP" => $reqPegawaiId));
        $result = array();
        while ($pegawai->nextRow()) {
            for ($i=0; $i < count($aColumns); $i++) { 
                if($aColumns[$i] == "FOTO")
				{
					if(trim($pegawai->getField(trim($aColumns[$i]))) == "")
                    	$row[trim($aColumns[$i])] = "";
					else
                   	 	$row[trim($aColumns[$i])] = base_url()."uploads/".$pegawai->getField(trim($aColumns[$i]));
				}
                else
                    $row[trim($aColumns[$i])] = $pegawai->getField(trim($aColumns[$i]));
            }
            $result[] = $row;
        }

        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $result));
        
    }
	
    // insert new data to entitas
    function index_post() {
		
		/* AMBIL TOKEN */
        $this->load->model('UserLoginMobile');
        $user_login_mobile = new UserLoginMobile();
        $reqToken = $this->input->post('reqToken');
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
		
		if($reqPegawaiId == "0" || $reqPegawaiId == "")
		{
			$this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
			return;	
		}
		/* END OF AMBIL TOKEN */
		
		$this->load->model("Pegawai");
		$pegawai = new Pegawai();

		$reqMode 				= $this->input->post("reqMode");
		$reqId 					= $this->input->post("reqId");
		$reqNrp					= $this->input->post("reqNrp");
		$reqNip					= $this->input->post("reqNip");
		$reqJenisKelamin		= $this->input->post("reqJenisKelamin");
		$reqUnitKerja			= $this->input->post("reqUnitKerja");
		$reqUnitKerjaId			= $this->input->post("reqUnitKerjaId");
		$reqNama				= $this->input->post("reqNama");
		
		
		$reqNamaPanggilan		= $this->input->post("reqNamaPanggilan");
		$reqTempatLahir			= $this->input->post("reqTempatLahir");
		$reqTanggalLahir		= $this->input->post("reqTanggalLahir");
		$reqAlamat				= $this->input->post("reqAlamat");
		$reqNomorHp				= $this->input->post("reqNomorHp");
		$reqEmailPribadi		= $this->input->post("reqEmailPribadi");
		$reqEmailBulog			= $this->input->post("reqEmailBulog");
		$reqNomorWa				= $this->input->post("reqNomorWa");
		$reqGolonganDarah		= $this->input->post("reqGolonganDarah");
		
		
		$pegawai->setField("NIP", $reqPegawaiId);
		$pegawai->setField("NAMA", $reqNama);
		$pegawai->setField("NAMA_PANGGILAN", $reqNamaPanggilan);
		$pegawai->setField("JENIS_KELAMIN", $reqJenisKelamin);
		$pegawai->setField("TEMPAT_LAHIR", $reqTempatLahir);
		$pegawai->setField("TANGGAL_LAHIR", dateToDbCheck($reqTanggalLahir));
		$pegawai->setField("CABANG_ID", $reqUnitKerjaId);
		$pegawai->setField("UNIT_KERJA", $reqUnitKerja);
		$pegawai->setField("ALAMAT", $reqAlamat);
		$pegawai->setField("NOMOR_HP", $reqNomorHp);
		$pegawai->setField("EMAIL_PRIBADI", $reqEmailPribadi);
		$pegawai->setField("EMAIL_BULOG", $reqEmailBulog);
		$pegawai->setField("NOMOR_WA", $reqNomorWa);
		$pegawai->setField("GOLONGAN_DARAH", $reqGolonganDarah);
		$pegawai->setField("UPDATED_BY", $reqPegawaiId);
		$pegawai->updateProfil();
		
		/* WAJIB UNTUK UPLOAD DATA */
		include_once("functions/image.func.php");
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR = "uploads/";
		
		$reqLinkFile = $_FILES["reqLinkFile"];
		$reqLinkFileTempSize	=  $this->input->post("reqLinkFileTempSize");
		$reqLinkFileTempTipe	=  $this->input->post("reqLinkFileTempTipe");
		$reqLinkFileTemp		=  $this->input->post("reqLinkFileTemp");

		$i=0;
		
		$renameFile = date("dmYhis").rand().".".getExtension($reqLinkFile['name']);
		$renameFix  = $reqPegawaiId.".".getExtension($reqLinkFile['name']);
		if($file->uploadToDir('reqLinkFile', $FILE_DIR, $renameFile))
		{	
		
			createThumbnail($FILE_DIR.$renameFile, $FILE_DIR.$renameFix, 800);
			unlink($FILE_DIR.$renameFile);
			
			$insertLinkSize = $file->uploadedSize;
			$insertLinkTipe =  $file->uploadedExtension;
			$insertLinkFile =  $renameFix;
			
			$pegawai = new Pegawai();
			$pegawai->setField("FOTO", $insertLinkFile);
			$pegawai->setField("NIP", $reqPegawaiId);
			$pegawai->updateFoto();
		}
		
		
		$this->response(array('status' => 'success', 'message' => 'Data berhasil disimpan.'));
		 

    }
	
    // update data entitas
    function index_put() {
		

    }
 
    // delete entitas
    function index_delete() {
		
		
    }
	
	
	
	function security_mobile(){
		/* AMBIL TOKEN */
        $this->load->model('UserLoginMobile');
        $user_login_mobile = new UserLoginMobile();
        $reqToken = $this->input->post('reqToken');
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
		
		if($reqPegawaiId == "0" || $reqPegawaiId == "")
		{
			$this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
			return;	
		}
		/* END OF AMBIL TOKEN */
	}
 
}