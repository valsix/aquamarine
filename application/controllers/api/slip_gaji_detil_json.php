<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class slip_gaji_detil_json extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
    }

    // show data entitas
    function index_get() {
        $this->load->model('UserLoginMobile');

        $user_login_mobile = new UserLoginMobile();
        
        $reqToken = $this->input->get('reqToken');
        $reqPeriode = $this->input->get("reqPeriode");
        
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {   
            $result = array(
                "NIP_NAMA" => "169316523 / MUHAMMAD EFFENDI",
                "GRADE_JABATAN" => "15 / STAF SUBDIVISI PENGEMBANGAN TEKNOLOGI INFORMASI",
                "DEPARTEMEN" => "DIVISI PENGEMBANGAN TEKNOLOGI INFORMASI",
                "PERIODE" => "Maret 2019",
                "MKP" => "22,2",
                "PENDIDIKAN" => "S2",
                "PENERIMAAN" => array(
                    array("KOLOM" => "Gaji Pokok", "NILAI" => "10.000.000"),
                    array("KOLOM" => "Tj. Perusahaan", "NILAI" => "1.000.000"),
                    array("KOLOM" => "Tj. Istri", "NILAI" => "0"),
                    array("KOLOM" => "Tj. Anak", "NILAI" => "0"),
                    array("KOLOM" => "Tj. Masa Kerja", "NILAI" => "500.000"),
                    array("KOLOM" => "Tj. PPH21", "NILAI" => "0")
                ),
                "POTONGAN" => array(
                    array("KOLOM" => "Potongan Absensi", "NILAI" => "0"),
                    array("KOLOM" => "BPJS", "NILAI" => "100.000"),
                    array("KOLOM" => "Jamsostek", "NILAI" => "100.000"),
                    array("KOLOM" => "Potongan PPH21", "NILAI" => "0")
                ),
                "JUMLAH_PENERIMAAN" => "11.500.000",
                "JUMLAH_POTONGAN" => "200.000",
                "JUMLAH_TOTAL" => "11.300.000"
            );

            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $result));
        }
        else
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
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
