<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class forum_detil_json extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
    }

    // show data entitas
    function index_get() {
        $aColumns = array("FORUM_DETIL_ID", "FORUM_ID", "NAMA", "TANGGAL", "ISI");

        $this->load->model('ForumDetil');
        $this->load->model('Forum');
        $this->load->model('UserLoginMobile');

        $user_login_mobile = new UserLoginMobile();
        
        $reqToken = $this->input->get('reqToken');
        
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {  
            $forum = new Forum;
            $forum_detil = new ForumDetil;


            $reqId = $this->input->get('reqId');

            $forum->selectByParams(array("A.FORUM_ID" => $reqId), -1, -1, $statement);
            $forum->firstRow();

            $diskusi['NAMA'] = $forum->getField("NAMA");
            $diskusi['JUDUL'] = $forum->getField("JUDUL");
            $diskusi['ISI'] = $forum->getField("ISI");
            $diskusi['COMMENTS'] = $forum->getField("COMMENTS");
            $tanggal = $forum->getField("TANGGAL");
            if($tanggal <> ''){
                $diskusi['TANGGAL'] = getFormattedDateTime($tanggal);
            }else
                $diskusi['TANGGAL'] = '';


            $statement = "";

            $detil = array();
            $forum_detil->selectByParams(array("FORUM_ID" => $reqId), -1, -1, $statement);
            // echo $forum_detil->query; exit();
            while ($forum_detil->nextRow()) {
                for ($i = 0; $i < count($aColumns); $i++) {
                    if(trim($aColumns[$i]) == 'TANGGAL'){
                        $row[trim($aColumns[$i])] = getFormattedDateTime($forum_detil->getField(trim($aColumns[$i])));
                    }else
                        $row[trim($aColumns[$i])] = $forum_detil->getField(trim($aColumns[$i]));
                }
                $detil[] = $row;
            }

            $result = array("diskusi" => $diskusi, "detil" => $detil);

            // var_dump($forum_detil); exit;
            // echo $result; exit;
            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($result), 'result' => $result));
        }
        else
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
    }

    // insert new data to entitas
    function index_post() {
        $this->load->model('ForumDetil');
        $this->load->model('UserLoginMobile');
        
        $user_login_mobile = new UserLoginMobile();
        
        $reqToken = $this->input->post('reqToken');
        
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {
            $reqId = $this->input->post('reqId');
            $reqMode = $this->input->post('reqMode');
            $reqForumId = $this->input->post('reqForumId');
            $reqKeterangan = $this->input->post('reqKeterangan');

            $forum_detil = new ForumDetil();

            $forum_detil->setField("FORUM_DETIL_ID", $reqId);
            $forum_detil->setField("FORUM_ID", $reqForumId);
            $forum_detil->setField("PEGAWAI_ID", $reqPegawaiId);
            $forum_detil->setField("KETERANGAN", $reqKeterangan);
            $forum_detil->setField("TANGGAL", 'CURRENT_TIMESTAMP');

            if($reqMode == 'insert'){
                $forum_detil->setField("LAST_CREATE_USER", $reqPegawaiId);
                $forum_detil->setField("LAST_CREATE_DATE", 'CURRENT_TIMESTAMP');
                $forum_detil->insert();
            }else{
                $forum_detil->setField("LAST_UPDATE_USER", $reqPegawaiId);
                $forum_detil->setField("LAST_UPDATE_DATE", 'CURRENT_TIMESTAMP');
                $forum_detil->update();
            }
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
