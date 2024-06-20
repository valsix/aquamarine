<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class slip_gaji_json extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
    }

    // show data entitas
    function index_get() {
        $aColumns = array(" GAJI_PERIODE_ID", "PERIODE");
        
        // $this->load->model('GajiPeriode');
        $this->load->model('UserLoginMobile');

        // $gaji_periode = new GajiPeriode;
        $user_login_mobile = new UserLoginMobile();
        
        $reqToken = $this->input->get('reqToken');
        
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {   
            // $gaji_periode->selectByParams();

            // $result = array();
            // while($gaji_periode->nextRow())
            // {
            //     for ( $i=0 ; $i<count($aColumns) ; $i++ )
            //     {
            //         if(trim($aColumns[$i]) == "PERIODE"){
            //             $row[trim($aColumns[$i])] = ($gaji_periode->getField(trim($aColumns[$i])));
            //             $row["TEXT_PERIODE"] = getNamePeriode($gaji_periode->getField(trim($aColumns[$i])));
            //         }
            //         else
            //             $row[trim($aColumns[$i])] = $gaji_periode->getField(trim($aColumns[$i]));
            //     }
            //     $result[] = $row;
            // }

            $result = array(
                array("PERIODE" => "012019", "TEXT_PERIODE" => "Januari 2019"),
                array("PERIODE" => "022019", "TEXT_PERIODE" => "Februari 2019"),
                array("PERIODE" => "032019", "TEXT_PERIODE" => "Maret 2019"),
                array("PERIODE" => "042019", "TEXT_PERIODE" => "April 2019")
            );

            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => $gaji_periode->rowCount ,'result' => $result));
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
