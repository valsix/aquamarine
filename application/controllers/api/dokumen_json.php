<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class dokumen_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }
 
    // show data entitas
	function index_get() {
		
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
	
        $aColumns = array("DOKUMEN_ID", "NAMA", "KETERANGAN", "LINK_FILE", "FILESIZE");
        
        $this->load->model("Dokumen");
        $aduan = new Dokumen();

        $aduan->selectByParams();
        $result = array();
        while ($aduan->nextRow()) {
            for ($i=0; $i < count($aColumns); $i++) { 
                if($aColumns[$i] == "LINK_FILE")
                    $row[trim($aColumns[$i])] = base_url()."uploads/".$aduan->getField(trim($aColumns[$i]));
				elseif($aColumns[$i] == "FILESIZE")
				{
					$file = "uploads/".$aduan->getField("LINK_FILE");
					$filesize = filesize($file); // bytes
					$filesize = round($filesize / 1024, 2); // kilobytes with two digits
					$row[trim($aColumns[$i])] = (string)$filesize." kb";  
				}
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
			$this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
			return;	
		}
		/* END OF AMBIL TOKEN */
		
		
		$this->load->model("Dokumen");
		$dokumen = new Dokumen();

		$reqMode 						= $this->input->post("reqMode");
		$reqId 							= $this->input->post("reqId");
		$reqNama 						= $this->input->post("reqNama");
		$reqKeterangan			= $this->input->post("reqKeterangan");

		$dokumen->setField("DOKUMEN_ID", $reqId);
		$dokumen->setField("NAMA", $reqNama);
		$dokumen->setField("DOKUMEN", $reqDokumen);
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