<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class polling_detil_json extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
    }

    // show data entitas
    function index_get() {
        $aColumns = array("POLLING_ID", "NAMA", "JUMLAH_VOTE", "TANGGAL_AWAL", "TANGGAL_AKHIR", "POLLING_DETIL_ID", "NAMA_PILIHAN", "JUMLAH", "PROSENTASE");

        $this->load->model('Polling');
        $this->load->model('PollingPegawai');
        $this->load->model('UserLoginMobile');

        $polling = new Polling;
        $polling_pegawai = new PollingPegawai();
        $user_login_mobile = new UserLoginMobile();
        
        $reqToken = $this->input->get('reqToken');
        
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {  
            $statement = "";

            $reqId = $this->input->get('reqId');

            $status_vote = $polling_pegawai->getCountByParamsMonitoring(array("POLLING_ID" => $reqId, "PEGAWAI_ID" => $reqPegawaiId));

            $result = array();

            $polling->selectByParamsDetil(array("A.POLLING_ID" => $reqId), -1, -1, $statement);
            while ($polling->nextRow()) {
                for ($i = 0; $i < count($aColumns); $i++) {
                    if(trim($aColumns[$i]) == 'TANGGAL_AWAL' || trim($aColumns[$i]) == 'TANGGAL_AKHIR'){
                        $row[trim($aColumns[$i])] = getFormattedDate($polling->getField(trim($aColumns[$i])));
                    }elseif(trim($aColumns[$i]) == 'PROSENTASE'){
                        $total = $polling->getField("JUMLAH_VOTE");
                        $jumlah =  $polling->getField("JUMLAH");
                        if($jumlah == 0) 
                            $persen = 0;
                        else 
                            $persen = $jumlah/$total*100;

                        $row[trim($aColumns[$i])] = $persen;
                    }else
                        $row[trim($aColumns[$i])] = $polling->getField(trim($aColumns[$i]));
                }
                $result[] = $row;
            }

            // var_dump($polling); exit;
            // echo $result; exit;
            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($result), 'result' => $result, 'status_vote' => $status_vote));
        }
        else
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
    }

    // insert new data to entitas
    function index_post() {
        $this->load->model('UserLoginMobile');

        $reqToken = $this->input->post("reqToken");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_mobile = new UserLoginMobile();
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        
        if($reqPegawaiId <> "0")
        {

            $this->load->model('PollingPegawai');

            $reqPollingDetilId = $this->input->post('reqPollingDetilId');
            
            $polling_pegawai = new PollingPegawai;
            
            $polling_pegawai->setField("PEGAWAI_ID", $reqPegawaiId);
            $polling_pegawai->setField("POLLING_DETIL_ID", $reqPollingDetilId);
            $polling_pegawai->setField("LAST_CREATE_USER", $reqPegawaiId);
            $polling_pegawai->setField("LAST_CREATE_DATE", "CURRENT_DATE");

            if($polling_pegawai->insert()){
                $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => 'Berhasil Disimpan'));
            }else{
                $this->response(array('status' => 'fail', 'message' => 'success', 'code' => 502, 'result' => 'Gagal disimpan, silahkan coba beberapa saat lagi'));
            }
        }
        else
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));

    }

    // update data entitas
    function index_put() {
        
    }

    // delete entitas
    function index_delete() {
        
    }

}
