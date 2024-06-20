<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class pengurus_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");   
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }
 
    // show data entitas
	function index_get() {
		
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");   
		
		$reqCabangId = $this->input->get("reqCabangId");
		
		if($reqCabangId == "")
			$reqCabangId = "00";
		
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
	
        $aColumns = array("PENGURUS_ID", "URUT", "NIP", "NAMA", "JABATAN", "JABATAN_PENGURUS", "TANGGAL_MULAI", "TANGGAL_AKHIR", "FOTO",
								"PEGAWAI_ID", "NO_SEKAR","NRP", "NAMA_PANGGILAN", "JENIS_KELAMIN", "TEMPAT_LAHIR", 
							   "TANGGAL_LAHIR", "UNIT_KERJA", "ALAMAT", "NOMOR_HP", "EMAIL_PRIBADI", "EMAIL_BULOG", "GOLONGAN_DARAH",
							   "NOMOR_WA");
        
        $this->load->model("Pengurus");
        $pengurus = new Pengurus();

        $pengurus->selectByParamsMonitoring(array("A.CABANG_ID" => $reqCabangId));
        $result = array();
        while ($pengurus->nextRow()) {
            for ($i=0; $i < count($aColumns); $i++) { 
                if($aColumns[$i] == "FOTO")
				{
					if(trim($pengurus->getField(trim($aColumns[$i]))) == "")
                    	$row[trim($aColumns[$i])] = "";
					else
                   	 	$row[trim($aColumns[$i])] = base_url()."uploads/".$pengurus->getField(trim($aColumns[$i]));
				}
                elseif($aColumns[$i] == "NRP")
                    $row[trim($aColumns[$i])] = $pengurus->getField("NO_SEKAR");
                else
                    $row[trim($aColumns[$i])] = $pengurus->getField(trim($aColumns[$i]));
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
		
		
		$this->load->model("Pengurus");
		$dokumen = new Pengurus();

		$reqMode 						= $this->input->post("reqMode");
		$reqId 							= $this->input->post("reqId");
		$reqNama 						= $this->input->post("reqNama");
		$reqKeterangan			= $this->input->post("reqKeterangan");

		$dokumen->setField("DOKUMEN_ID", $reqId);
		$dokumen->setField("NAMA", $reqNama);
		$dokumen->setField("DOKUMEN", $reqPengurus);
		$dokumen->setField("KETERANGAN", $reqKeterangan);


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
		$renameFile = "".date("dmYhis").rand().".".getExtension($reqLinkFile['name'][$i]);


		if($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i))
		{

			$insertLinkSize = $file->uploadedSize;
			$insertLinkTipe =  $file->uploadedExtension;
			$insertLinkFile =  $reqJenis.$renameFile;
		}
		else
		{

			$insertLinkSize = $reqLinkFileTempSize[$i];
			$insertLinkTipe =  $reqLinkFileTempTipe[$i];
			$insertLinkFile =  $reqLinkFileTemp[$i];
		}

		$dokumen->setField("LINK_FILE", $insertLinkFile);
		/* AKHIR WAJIB UNTUK UPLOAD DATA */

		if($reqMode == "insert")
		{
			$dokumen->setField("CREATED_BY", $reqPegawaiId);
			$dokumen->insert();
		}
		else
		{
			$dokumen->setField("UPDATED_BY", $reqPegawaiId);
			$dokumen->update();
		}
		
		 $this->response(array('status' => 'success', 'message' => 'Data berhasil disimpan.'));
		 


    }
 
    // update data entitas
    function index_put() {

    }
 
    // delete entitas
    function index_delete() {

    }
 
}