<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class notifikasi_json extends REST_Controller {

    function __construct() {
        parent::__construct();
    }

    // show data entitas
    function index_get() {
        $aColumns = array("NOTIFIKASI_ID", "NAMA", "KETERANGAN", "KETERANGAN_FULL", "TANGGAL", "JENIS", "TYPE", "DIBACA", "PRIMARY_ID");

        $this->load->model('Notifikasi');
        $reqToken = $this->input->get('reqToken');
        
        $this->load->model('UserLoginMobile');
        
        //CEK PEGAWAI ID DARI TOKEN
        $user_login_mobile = new UserLoginMobile();
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        
        if($reqPegawaiId <> "0")
        {

            $notifikasi = new Notifikasi;

            $statement = "";

            $result = array();

            $notifikasi_count = $notifikasi->getCountByParams(array('PEGAWAI_ID' => $reqPegawaiId, 'DIBACA' => 'T'));

            $notifikasi->selectByParams(array('PEGAWAI_ID' => $reqPegawaiId), -1, -1, $statement);
            while ($notifikasi->nextRow()) {
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (trim($aColumns[$i]) == "TANGGAL"){
                        $row[trim($aColumns[$i])] = getFormattedDateTime($notifikasi->getField(trim($aColumns[$i])));
                    }elseif (trim($aColumns[$i]) == 'KETERANGAN'){
                        $row[trim($aColumns[$i])] = dropAllHtml($notifikasi->getField(trim($aColumns[$i])));
                    }else
                        $row[trim($aColumns[$i])] = $notifikasi->getField(trim($aColumns[$i]));
                }
                $result[] = $row;
            }

            // echo $notifikasi->query; exit;
            // echo $result; exit;
            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($result), 'notifikasi_count' => $notifikasi_count,'result' => $result));
        }
        else
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
    }

    // insert new data to entitas
    function index_post() {
        $this->load->model('Notifikasi'); 
        $this->load->model('UserLoginMobile');

        $json = file_get_contents('php://input');
        $form = json_decode($json, true);
        
        $reqId = $this->input->post('reqId');
        $reqToken = $this->input->post('reqToken');
        $reqMode = $this->input->post('reqMode');
        $reqSliderId = $this->input->post('reqSliderId');
        
        //CEK PEGAWAI ID DARI TOKEN
        $user_login_mobile = new UserLoginMobile();
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        
        $notifikasi = new Notifikasi;   

        $notifikasi->setField("NOTIFIKASI_ID", $reqId);

        if($reqMode == 'delete'){
            $notifikasi->delete();
        }else{
            $notifikasi->read();
        }

        // echo 'Berhasil Disimpan';
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => 'Berhasil Dihapus'));
    }

    // update data entitas
    function index_put() {
        
    }

    // delete entitas
    function index_delete() {
        $this->load->model('Notifikasi');

        $json = file_get_contents('php://input');
        $form = json_decode($json, true);
        parse_str(file_get_contents("php://input"),$post_vars);
        //var_dump($json); exit;
        $reqId = $form['reqId'];
        //echo $reqId; exit;
        
        $notifikasi = new Notifikasi;

        $notifikasi->setField("NOTIFIKASI_ID", $reqId);
        $notifikasi->delete();

        // echo 'Berhasil Disimpan';
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => 'Berhasil Dihapus'));
    }

}
?>
