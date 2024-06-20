<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class informasi_detil_json extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
    }

    // show data entitas
    function index_get() {
        $aColumns = array("SLIDER_ID", "JENIS", "TANGGAL", "NAMA", "KETERANGAN", "LINK_FILE", "LINK_FILE_VIDEO");
        $aColumnsKomentar = array("SLIDER_KOMENTAR_ID", "SLIDER_ID", "PEGAWAI_ID", "NAMA", "CABANG", "KOMENTAR", "TANGGAL");
        $aColumnsDetil = array("SLIDER_DETIL_ID", "SLIDER_ID", "NAMA", "LINK_FILE", "TANGGAL");

        $this->load->model("Slider");
        $this->load->model("SliderDetil");
        $this->load->model("SliderKomentar");
        $this->load->model('UserLoginMobile');

        $user_login_mobile = new UserLoginMobile();
        
        $reqToken = $this->input->get('reqToken');
        $reqJenis = $this->input->get('reqJenis');
        
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {  
            $slider = new Slider();

            $reqId = $this->input->get('reqId');

            if(isset($reqJenis)){
                $statement = " AND JENIS = '".$reqJenis."' ";
            }else{
                $statement = " AND JENIS IN ('ARTIKEL', 'BERITA') ";
            }

            $order = " ORDER BY TANGGAL DESC";
            

            $result = array();
            $row = array();
            $row_komentar = array();
            $row_detil = array();
            
            $slider->selectByParams(array('SLIDER_ID' => $reqId, "A.STATUS_PUBLISH" => "Y"), -1, -1, '', $order);
            $slider->firstRow();
            for ($i = 0; $i < count($aColumns); $i++) {
                if (trim($aColumns[$i]) == "TANGGAL") {
                    $row[trim($aColumns[$i])] = getFormattedDate($slider->getField("HARI"))." ".$slider->getField("JAM");
                }elseif (trim($aColumns[$i]) == "KETERANGAN"){
                    $row[trim($aColumns[$i])] = ($slider->getField(trim($aColumns[$i]))); 
                }elseif (trim($aColumns[$i]) == "LINK_FILE"){
                    $row[trim($aColumns[$i])] = base_url()."uploads/".($slider->getField(trim($aColumns[$i]))); 
                } elseif (trim($aColumns[$i]) == "LINK_FILE_VIDEO"){
                    $video = $slider->getField(trim($aColumns[$i]));
                    if(substr( $video, 0, 4 ) === "http")
                        $row[trim($aColumns[$i])] = $video; 
                    else
                        $row[trim($aColumns[$i])] = base_url()."uploads/".$video; 
                } 
                else
                    $row[trim($aColumns[$i])] = $slider->getField(trim($aColumns[$i]));
            }


            $slider_komentar = new SliderKomentar();

            $index = 0;
            $slider_komentar->selectByParams(array('SLIDER_ID' => $reqId), -1, -1, '');
            while($slider_komentar->nextRow()){
                for ($i = 0; $i < count($aColumnsKomentar); $i++) {
                    if (trim($aColumnsKomentar[$i]) == "TANGGAL") {
                        $row_komentar[$index][trim($aColumnsKomentar[$i])] = getFormattedDate($slider_komentar->getField("HARI"))." ".$slider_komentar->getField("JAM");
                    }else
                        $row_komentar[$index][trim($aColumnsKomentar[$i])] = $slider_komentar->getField(trim($aColumnsKomentar[$i]));
                }
                $index++;
            }


            $slider_detil = new SliderDetil();

            $index = 0;
            $slider_detil->selectByParams(array('SLIDER_ID' => $reqId), -1, -1, '');
            while($slider_detil->nextRow()){
                for ($i = 0; $i < count($aColumnsDetil); $i++) {
                    if (trim($aColumnsDetil[$i]) == "TANGGAL") {
                        $row_detil[$index][trim($aColumnsDetil[$i])] = getFormattedDate($slider_detil->getField("HARI"))." ".$slider_detil->getField("JAM");
                    }else if (trim($aColumnsDetil[$i]) == "NAMA") {
                        $row_detil[$index]['title'] = $slider_detil->getField(trim($aColumnsDetil[$i]));
                    }else if (trim($aColumnsDetil[$i]) == "LINK_FILE") {
                        $row_detil[$index]['url'] = base_url()."uploads/".$slider_detil->getField(trim($aColumnsDetil[$i]));
                    }else
                        $row_detil[$index][trim($aColumnsDetil[$i])] = $slider_detil->getField(trim($aColumnsDetil[$i]));
                }
                $index++;
            }

            $result = array('main' => $row, 'detil' => $row_detil, 'komentar' => $row_komentar);

            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($result), 'result' => $result));
        }
        else
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
    }

    // insert new data to entitas
    function index_post() {
        $this->load->model('SliderKomentar');
        $this->load->model('UserLoginMobile');
        $this->load->model('Pegawai');
        
        $user_login_mobile = new UserLoginMobile();
        
        $reqToken = $this->input->post('reqToken');
        
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {
            $pegawai = new Pegawai();
            $pegawai->selectByParams(array("NIP" => $reqPegawaiId));
            $pegawai->firstRow();

            $reqNama        = $pegawai->getField("NAMA");
            $reqCabang      = $pegawai->getField("UNIT_KERJA");

            $reqId = $this->input->post('reqId');
            $reqMode = $this->input->post('reqMode');
            $reqSliderId = $this->input->post('reqSliderId');
            $reqKomentar = $this->input->post('reqKomentar');

            $slider_komentar = new SliderKomentar();

            $slider_komentar->setField("SLIDER_KOMENTAR_ID", $reqId);
            $slider_komentar->setField("SLIDER_ID", $reqSliderId);
            $slider_komentar->setField("PEGAWAI_ID", $reqPegawaiId);
            $slider_komentar->setField("NAMA", $reqNama);
            $slider_komentar->setField("CABANG", $reqCabang);
            $slider_komentar->setField("KOMENTAR", $reqKomentar);

            $slider_komentar->setField("LAST_CREATE_USER", $reqPegawaiId);
            $slider_komentar->setField("LAST_CREATE_DATE", 'CURRENT_TIMESTAMP');
            $slider_komentar->insert();

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
