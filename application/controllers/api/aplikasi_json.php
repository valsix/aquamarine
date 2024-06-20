<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class aplikasi_json extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
    }

    // show data entitas
    function index_get() {
        $aColumns = array("APLIKASI_ID","NAMA","JENIS","LINK_URL","LINK_FILE");

        $this->load->model("Aplikasi");
        $this->load->model('UserLoginMobile');

        $user_login_mobile = new UserLoginMobile();
        
        $reqToken = $this->input->get('reqToken');
        
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {  
            $aplikasi = new Aplikasi();

            $reqId = $this->input->get('reqId');


            $order = " ORDER BY URUT ASC";
            

            $result = array();

            $aplikasi->selectByParams(array("A.STATUS_PUBLISH" => "Y"), -1, -1, $statement, $order);
            while ($aplikasi->nextRow()) {
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (trim($aColumns[$i]) == "TANGGAL"){
                        $row[trim($aColumns[$i])] = getFormattedDate($aplikasi->getField("HARI"))." ".$aplikasi->getField("JAM");
                    }
                    elseif (trim($aColumns[$i]) == "KETERANGAN"){
                        $row[trim($aColumns[$i])] = dropAllHtml($aplikasi->getField(trim($aColumns[$i]))); 
                    }elseif (trim($aColumns[$i]) == "LINK_FILE"){
                        $row[trim($aColumns[$i])] = base_url()."uploads/".($aplikasi->getField(trim($aColumns[$i]))); 
                    } 
                    else
                        $row[trim($aColumns[$i])] = $aplikasi->getField(trim($aColumns[$i]));
                }
                $result[] = $row;
            }

            // var_dump($aplikasi); exit;
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
