<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class polling_json extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
    }

    // show data entitas
    function index_get() {
        $aColumns = array("POLLING_ID","NAMA","KETERANGAN","TANGGAL_AWAL","TANGGAL_AKHIR","STATUS", "STATUS_VOTE");

        $this->load->model('Polling');
        $this->load->model('UserLoginMobile');

        $polling = new Polling;
        $user_login_mobile = new UserLoginMobile();
        
        $reqToken = $this->input->get('reqToken');
        
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {  
            $statement = "";

            $result = array();

            $polling->selectByParamsMonitoring(array(), -1, -1, $statement, '', $reqPegawaiId);
            while ($polling->nextRow()) {
                for ($i = 0; $i < count($aColumns); $i++) {
                    if(trim($aColumns[$i]) == 'TANGGAL_AWAL' || trim($aColumns[$i]) == 'TANGGAL_AKHIR'){
                        $row[trim($aColumns[$i])] = getFormattedDateTime($polling->getField(trim($aColumns[$i])));
                    }else
                        $row[trim($aColumns[$i])] = $polling->getField(trim($aColumns[$i]));
                }
                $result[] = $row;
            }

            // var_dump($polling); exit;
            // echo $result; exit;
            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($result), 'result' => $result));
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
