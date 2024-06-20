<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class ulang_tahun_json extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
    }

    // show data entitas
    function index_get() {
		
        $this->load->model('UserLoginMobile');
        $user_login_mobile = new UserLoginMobile();
        $reqToken = $this->input->get('reqToken');
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        if($reqPegawaiId == "0" || $reqPegawaiId == "")
        {  
			$this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
			return;
        }
		
        $this->load->model("Pegawai");

		$pegawai = new Pegawai();


        $aColumns = array("PEGAWAI_ID", "NO_SEKAR","NRP", "NIP", "NAMA", "NAMA_PANGGILAN", "JENIS_KELAMIN", "TEMPAT_LAHIR", 
       "TANGGAL_LAHIR", "UNIT_KERJA", "ALAMAT", "NOMOR_HP", "EMAIL_PRIBADI", "EMAIL_BULOG", "GOLONGAN_DARAH",
       "NOMOR_WA", "FOTO");
        
		$pegawai->selectByParams(array("VALIDASI" => "1"), -1, -1, " AND TO_CHAR(TANGGAL_LAHIR, 'DDMM') = TO_CHAR(CURRENT_DATE, 'DDMM') ");
		$result = array();
		$i = 0;
		$pesan = "";
		while ($pegawai->nextRow()) 
		{
			if($i == 0)
				$pesan = $pegawai->getField("NAMA")." (".$pegawai->getField("NO_SEKAR").")";
			else
				$pesan .= ", ".$pegawai->getField("NAMA")." (".$pegawai->getField("NO_SEKAR").")";
				
				
			for ($i=0; $i < count($aColumns); $i++) { 
                if($aColumns[$i] == "FOTO")
				{ 
					if(trim($pegawai->getField(trim($aColumns[$i]))) == "")
                    	$row[trim($aColumns[$i])] = "";
					else
                   	 	$row[trim($aColumns[$i])] = base_url()."uploads/".$pegawai->getField(trim($aColumns[$i]));
				}
                elseif($aColumns[$i] == "NRP")
                    $row[trim($aColumns[$i])] = $pegawai->getField("NO_SEKAR");
                else
                    $row[trim($aColumns[$i])] = $pegawai->getField(trim($aColumns[$i]));
            }
            $result[] = $row;
		}
		
		if($i == 0)
			$pesan = "Tidak ada peserta yang ulang tahun hari ini...";
		else
			$pesan = "Selamat ulang tahun kepada ".ucwords(strtolower($pesan))." semoga panjang umur dan senantiasa diberikan kesehatan.";
		
		$this->response(array('status' => 'success', 'message' => $pesan, 'code' => 200, 'count' => count($result), 'result' => $result));
	
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
