<?php
error_reporting(1);
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class pegawai_sppd_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

    }
 
    // show data entitas
	function index_get() {

		$aColumns = array("PEGAWAI_ID", "TANGGAL", "MULAI_TANGGAL", "SAMPAI_TANGGAL", "ASAL_KOTA", "TUJUAN_KOTA", 
			"LAMA", "STATUS", "MAKSUD", "KET_STATUS", "BIAYA_TOTAL");
		$aColumnsAlias = array("PEGAWAI_ID", "TANGGAL", "MULAI_TANGGAL", "SAMPAI_TANGGAL", "ASAL_KOTA", "TUJUAN_KOTA", "LAMA", "STATUS", "MAKSUD", "KET_STATUS", "BIAYA_TOTAL");

        $this->load->model('UserLoginMobile');
		$user_login_mobile = new UserLoginMobile;
		
		$reqToken = $this->input->get("reqToken");
		$reqLimit = coalesce($this->input->get("reqLimit"), 0);

		//CEK PEGAWAI ID DARI TOKEN
        $user_login_mobile = new UserLoginMobile();
		$reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
		// $reqPegawaiId = '9014140KP';

		if($reqPegawaiId <> "0")
		{
			$this->load->model("PegawaiSppd");
			$pegawai_sppd = new PegawaiSppd();
			$pegawai_sppd->selectByParams(array("PEGAWAI_ID" => $reqPegawaiId), $reqLimit, 0, "", " ORDER BY MULAI_TANGGAL DESC" );
			$total = 0;
			while($pegawai_sppd->nextRow())
			{
				$row = array();
				$row['MULAI_TANGGAL'] = getFormattedDateView($pegawai_sppd->getField("MULAI_TANGGAL"));
				$row['SAMPAI_TANGGAL'] = getFormattedDateView($pegawai_sppd->getField("SAMPAI_TANGGAL"));
				$row['TUJUAN_KOTA'] = $pegawai_sppd->getField("TUJUAN_KOTA");
				$row['MAKSUD'] = $pegawai_sppd->getField("MAKSUD");
				$row['BIAYA_TOTAL'] = numberToIna($pegawai_sppd->getField("BIAYA_TOTAL"));
				$row['STATUS'] = $pegawai_sppd->getField("STATUS")." - ".$pegawai_sppd->getField("KET_STATUS")." (".getFormattedDateView($pegawai_sppd->getField("TANGGAL")).") ";
				$result[] = $row;

				$total++;
			}

			if($total == 0)
			{
				$row = array();
				$row['MULAI_TANGGAL'] = "";
				$row['SAMPAI_TANGGAL'] = "";
				$row['TUJUAN_KOTA'] = "";
				$row['MAKSUD'] = "";
				$row['BIAYA_TOTAL'] = "";
				$row['STATUS'] = "";
				$result[] = $row;
			}

			$this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
		}
		else
        {
        	$this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        }
			
    }
	
    // insert new data to entitas
    function index_post() {

    }
 
    // update data entitas
    function index_put() {

    }
 
    // delete entitas
    function index_delete() {
        
    }
 
}