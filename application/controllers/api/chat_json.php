<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class chat_json extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
    }

    // show data entitas
    function index_get() {
        $aColumns = array("JAM", "NAMA", "PESAN", 
                    "IP_ADDRESS", "PEGAWAI_ID", "HALAMAN", "KODE", "WAKTU");

        $this->load->model('Chat');
        $this->load->model('UserLoginMobile');

        $user_login_mobile = new UserLoginMobile();
        
        $reqToken = $this->input->get('reqToken');
        
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {  
            $chat = new Chat;
            $reqId = $this->input->get('reqId');

            $statement = " ";

            $result = array();
            $chat->selectByParams(array("PEGAWAI_ID" => $reqPegawaiId), -1, -1, $statement, "  ORDER BY JAM DESC ");
            while ($chat->nextRow()) {
                for ($i = 0; $i < count($aColumns); $i++) {
                    if(trim($aColumns[$i]) == 'WAKTU'){
                        $row[trim($aColumns[$i])] = getFormattedDateTime($chat->getField(trim($aColumns[$i])));
                    }else
                        $row[trim($aColumns[$i])] = $chat->getField(trim($aColumns[$i]));
                }

                if($chat->getField("NAMA") == 'Operator'){
                    $row['POS'] = 'left';
                }else{
                    $row['POS'] = 'right';
                }

                $second = $chat->getField("JAM");
                $row['DATE'] = date("d-m-Y", $second);
                $row['TIME'] = date("H:i", $second);
                
                $index = $chat->currentRowIndex;
                if($index == 0){
                    $row['DATE_BEFORE'] = $row['DATE'];
                }else{
                    $row['DATE_BEFORE'] = $result[$index - 1]['DATE'];
                }

                $result[] = $row;
            }

            // var_dump($chat); exit;
            // echo $result; exit;
            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($result), 'result' => $result));
        }
        else
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
    }

    // insert new data to entitas
    function index_post() {
        $this->load->model('Chat');
        $this->load->model('UserLoginMobile');
        $this->load->model('UserLogin');
        
        $user_login_mobile = new UserLoginMobile();
        
        $reqToken = $this->input->post('reqToken');
        
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {
            $chat = new Chat();
            $user_login = new UserLogin();

            $reqPesan = $this->input->post('reqPesan');
            $reqIpAddress = $this->input->post('reqIpAddress');
            $reqHalaman = $this->input->post('reqHalaman');
            $reqKode = $this->input->post('reqKode');

            $reqNama = $user_login->getNamaPegawai($reqPegawaiId);
            
            $chat->setField("JAM", time());
            $chat->setField("NAMA", $reqNama);
            $chat->setField("PESAN", $reqPesan);
            $chat->setField("IP_ADDRESS", $reqIpAddress);
            $chat->setField("PEGAWAI_ID", $reqPegawaiId);
            $chat->setField("HALAMAN", $reqHalaman);
            $chat->setField("KODE", $reqKode);
            $chat->insert();

            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200));
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
