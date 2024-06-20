<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");
 
class agenda_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }
 
    // show data entitas
	function index_get() {
        
        $this->load->model("Slider");
        $slider = new Slider();

        $slider->selectByParams(array("JENIS" => "AGENDA"), -1, -1, " AND A.TANGGAL BETWEEN CURRENT_TIMESTAMP - INTERVAL '1' DAY AND CURRENT_TIMESTAMP + INTERVAL '1' DAY ");
        $result = array();
        while ($slider->nextRow()) {
			
            $row['SLIDER_ID'] = $slider->getField("SLIDER_ID");
            $row['TANGGAL'] = getFormattedDateTime($slider->getField("TANGGAL"));
            $row['NAMA'] = $slider->getField("NAMA");
            $row['KETERANGAN'] = dropAllHtml($slider->getField("KETERANGAN"));
			
			
			$reqId = $slider->getField("SLIDER_ID");
			
			$arrPeserta = array();
			$slider_komentar = new Slider();
			$slider_komentar->selectByParamsHadir(array("A.SLIDER_ID" => $reqId));
			$j = 0;
			while($slider_komentar->nextRow())
			{
				$arrPeserta[$j]["PEGAWAI_ID"] = $slider_komentar->getField("PEGAWAI_ID");
				$arrPeserta[$j]["NO_SEKAR"] = $slider_komentar->getField("NO_SEKAR");
				$arrPeserta[$j]["NAMA"] = $slider_komentar->getField("NAMA");
				$arrPeserta[$j]["CABANG"] = $slider_komentar->getField("CABANG");
				$arrPeserta[$j]["JAM_HADIR"] = $slider_komentar->getField("JAM_HADIR");
                $j++;
			}
			
            $row['PESERTA'] = $arrPeserta;
            $row['TOTAL_PESERTA'] = $j;
			
            $result[] = $row;
        }

        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $result));
        
    }
	
    // insert new data to entitas
    function index_post() {
		
		$reqId = $this->input->post("reqId");
		$reqQR = $this->input->post("reqQR");
		$reqJam = $this->input->post("reqJam");
		
		$arrQr = explode("(", $reqQR);
		
		$reqNama = trim($arrQr[0]);
		$reqNoSekar = $arrQr[1];
		$reqNoSekar = str_replace(")", "", $reqNoSekar);
		$reqNoSekar = trim($reqNoSekar);
		
		$this->load->model("Pegawai");
		$this->load->model("Slider");
		$pegawai = new Pegawai();
		$pegawai->selectByParams(array("NO_SEKAR" => $reqNoSekar));
		$pegawai->firstRow();
		$reqPegawaiId = $pegawai->getField("NIP");
		$reqNama 	  = $pegawai->getField("NAMA");
		$reqCabang 	  = $pegawai->getField("UNIT_KERJA");
		
		$slider = new Slider();
						
		$slider->setField("SLIDER_ID", $reqId);
		$slider->setField("NAMA", $reqNama);
		$slider->setField("PEGAWAI_ID", $reqPegawaiId);
		$slider->setField("CABANG", $reqCabang);
		$slider->setField("JAM", "TO_TIMESTAMP('".$reqJam."', 'DD-MM-YYYY HH24:MI:SS')");
		$slider->setField("LAST_CREATE_USER", $reqQR);
		if($slider->insertHadir())
		{
	        $this->response(array('status' => 'success', 'message' => 'Selamat datang '.$reqQR));	
		}
		
		
		
				
		
    }
 
    // update data entitas
    function index_put() {

    }
 
    // delete entitas
    function index_delete() {

    }
 
}