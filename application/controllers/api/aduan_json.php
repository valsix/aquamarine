<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class aduan_json extends REST_Controller {
 
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
	
        $aColumns = array("ADUAN_ID", "NIP", "NAMA", "ADUAN", "LINK_FILE", "BALASAN", "CREATED_DATE", "BALASAN_DATE");
        
        $this->load->model("Aduan");
        $aduan = new Aduan();

        $aduan->selectByParams(array("NIP" => $reqPegawaiId));
        $result = array();
        while ($aduan->nextRow()) {
            for ($i=0; $i < count($aColumns); $i++) { 
                if($aColumns[$i] == "LINK_FILE")
                    $row[trim($aColumns[$i])] = base_url()."uploads/".$aduan->getField(trim($aColumns[$i]));
                else
                    $row[trim($aColumns[$i])] = $aduan->getField(trim($aColumns[$i]));
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
			$this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
			return;	
		}
		/* END OF AMBIL TOKEN */
		
		$this->load->model("Aduan");
		$aduan = new Aduan();

		$reqNama 						= $this->input->post("reqNama");
		$reqAduan 						= $this->input->post("reqAduan");
		$reqBalasan 					= $this->input->post("reqBalasan");

		$aduan->setField("ADUAN_ID", $reqId);
		$aduan->setField("NIP", $reqPegawaiId);
		$aduan->setField("NAMA", $reqNama);
		$aduan->setField("ADUAN", $reqAduan);
		$aduan->setField("CREATED_BY", $reqPegawaiId);
		
		
		/* WAJIB UNTUK UPLOAD DATA */
		$this->load->library("FileHandler");
		$file = new FileHandler();
		$FILE_DIR = "uploads/";
		$reqLinkFile = $_FILES["reqLinkFile"];

		$i=0;
		$renameFile = "ADUAN".$reqPegawaiId."".date("dmYhis").".".getExtension($reqLinkFile['name']);

		$insertLinkFile = "";
		if($file->uploadToDir('reqLinkFile', $FILE_DIR, $renameFile))
		{
			$insertLinkFile =  $renameFile;
		}

		$aduan->setField("LINK_FILE", $insertLinkFile);
		
		$aduan->insert();
		
		$this->response(array('status' => 'success', 'message' => 'Data berhasil disimpan.'));
		 


    }
 
    // update data entitas
    function index_put() {

    }
 
    // delete entitas
    function index_delete() {

    }
 
}