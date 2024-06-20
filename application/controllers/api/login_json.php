<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class login_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
        
        
        $this->load->library('Kauth');
    }
 
    // show data entitas
    function index_get() {
  
    }
    
    // insert new data to entitas
    function index_post() {
        $reqUser = $this->input->post("reqUser");
        $reqPasswd = $this->input->post("reqPasswd");
        $reqDeviceID = $this->input->post("reqDeviceID");
        $reqImei = $this->input->post("reqImei");
        $reqTokenFirebase = $this->input->post("reqTokenFirebase");
        
        $this->load->model('UserLoginMobile');
        $this->load->model('Pegawai');
        $this->load->model('UserLogin');
        $this->load->model('Users');


		$user_login_mobile = new UserLoginMobile();
		$user_login = new Users();
		$user_login_count = new UserLogin();
		$pegawai_atasan = new Pegawai();        

        $temp = array();
        if(!empty($reqUser) AND !empty($reqPasswd))
        {
			
			if($reqUser == "absensi" && $reqPasswd == "absensi")
			{
				$this->response(array('status' => 'success', 'message' => 'Anda berhasil Login.', 'token' => "absensi", 'reqNamaPegawai' => "Presensi", 'code' => 200));
				return;	
			}
			
            $respon = $this->kauth->mobileAuthenticate($reqUser,$reqPasswd);

            if($respon == "1")
            {
                $reqPegawaiId = $this->kauth->getInstance()->getIdentity()->PEGAWAI_ID;
                $reqNamaPegawai = $this->kauth->getInstance()->getIdentity()->NAMA;
                $reqCabang = $this->kauth->getInstance()->getIdentity()->UNIT_KERJA;
                $reqNip = $this->kauth->getInstance()->getIdentity()->NIP;
				$reqValidasi = $this->kauth->getInstance()->getIdentity()->VALIDASI;
				
				if($reqValidasi == "X")
				{
                    $this->response(array('status' => 'fail', 'message' => 'Validasi pendaftaran anda ditolak, hubungi Administrator.', 'code' => 502));
					return;
				}
				
				if($reqValidasi == "0")
				{
                    $this->response(array('status' => 'fail', 'message' => 'Akun anda dalam proses verifikasi Administrator.', 'code' => 502));
					return;					
				}
				

				$user_login_mobile->setField("PEGAWAI_ID", trim($reqPegawaiId));
				$user_login_mobile->setField("WAKTU_LOGIN", "CURRENT_TIMESTAMP");
				$user_login_mobile->setField("STATUS", "1");
				$user_login_mobile->setField("DEVICE_ID", $reqDeviceID);
				$user_login_mobile->setField("IMEI", $reqImei);
				$user_login_mobile->setField("TOKEN_FIREBASE", $reqTokenFirebase);
				
                if ($user_login_mobile->insert()) 
                {
                    $this->response(array('status' => 'success', 'message' => 'Anda berhasil Login.', 'token' => $user_login_mobile->idToken, 'reqNamaPegawai' => $reqNamaPegawai, 'reqNip' => $reqNip, 'reqCabang' => $reqCabang, 'reqPegawaiId' => $reqPegawaiId, 'code' => 200));
                } 
                else 
                {
                    $this->response(array('status' => 'fail', 'message' => 'Anda Gagal Login, Coba beberapa saat lagi.', 'code' => 502));
                }
            }
            else
            {
                $this->response(array('status' => 'fail', 'message' => 'Username dan Password Anda Salah.', 'code' => 502));
            }
            
            
        }
        else
        {
                $this->response(array('status' => 'fail', 'message' => 'Masukkan Username dan Password.', 'code' => 502));
        }

    }
 
    // update data entitas
    function index_put() {
        $reqToken = $this->input->get('reqToken');
        $reqTokenFirebase = $this->input->get('reqTokenFirebase');

        $this->load->model('UserLoginMobile');

        $user_login_mobile = new UserLoginMobile();

        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {
            $user_login_mobile->setField("TOKEN_FIREBASE", $reqTokenFirebase);
            $user_login_mobile->setField("TOKEN", $reqToken);
            $user_login_mobile->updateTokenFirebase();
            $this->response(array('status' => 'success', 'message' => 'Berhasil diupdate'));
        }
        else
        {
            $this->response(array('status' => 'fail', 'message' => 'Gagal diupdate'));
        }
    }
 
    // delete entitas
    function index_delete() {
        /*
        $entitas_id = $this->delete('entitas_id');
        $this->db->where('entitas_id', $entitas_id);
        $delete = $this->db->delete('entitas');
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
        */
    }
 
}