<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class forum_json extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
    }

    // show data entitas
    function index_get() {
        $aColumns = array("FORUM_ID", "JUDUL", "FORUM_KATEGORI_ID", "NAMA", "TANGGAL", "ISI", "STATUS", "COMMENTS");

        $this->load->model('Forum');
        $this->load->model('ForumKategori');
        $this->load->model('UserLoginMobile');

        $user_login_mobile = new UserLoginMobile();
        
        $reqToken = $this->input->get('reqToken');
        
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {  
            $forum = new Forum;
            $forum_kategori = new ForumKategori;


            $reqId = $this->input->get('reqId');

            $forum_kategori->selectByParams(array("A.FORUM_KATEGORI_ID" => $reqId), -1, -1, $statement);
            $forum_kategori->firstRow();

            $kategori['JUDUL'] = $forum_kategori->getField("NAMA");
            $kategori['ISI'] = dropAllHtml($forum_kategori->getField("KETERANGAN"));
            $kategori['POSTS'] = $forum_kategori->getField("POSTS");
            $tanggal = $forum_kategori->getField("TANGGAL");
            if($tanggal <> ''){
                $kategori['TANGGAL'] = getFormattedDate($tanggal);
            }else
                $kategori['TANGGAL'] = '';


            $statement = "";

            $diskusi = array();
            $forum->selectByParams(array("FORUM_KATEGORI_ID" => $reqId), -1, -1, $statement);
            // echo $forum->query; exit();
            while ($forum->nextRow()) {
                for ($i = 0; $i < count($aColumns); $i++) {
                    if(trim($aColumns[$i]) == 'TANGGAL'){
                        $row[trim($aColumns[$i])] = getFormattedDateTime($forum->getField(trim($aColumns[$i])));
                    }else
                        $row[trim($aColumns[$i])] = $forum->getField(trim($aColumns[$i]));
                }
                $diskusi[] = $row;
            }

            $result = array("diskusi" => $diskusi, "kategori" => $kategori);

            // var_dump($forum); exit;
            // echo $result; exit;
            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($result), 'result' => $result));
        }
        else
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
    }

    // insert new data to entitas
    function index_post() {
        $this->load->model('Forum');
        $this->load->model('UserLoginMobile');
        
        $user_login_mobile = new UserLoginMobile();
        
        $reqToken = $this->input->post('reqToken');
        
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {
            $reqId = $this->input->post('reqId');
            $reqMode = $this->input->post('reqMode');
            $reqKategoriId = $this->input->post('reqKategoriId');
            $reqNama = $this->input->post('reqNama');
            $reqKeterangan = $this->input->post('reqKeterangan');

            $forum = new Forum();

            $forum->setField("FORUM_ID", $reqId);
            $forum->setField("FORUM_KATEGORI_ID", $reqKategoriId);
            $forum->setField("PEGAWAI_ID", $reqPegawaiId);
            $forum->setField("NAMA", $reqNama);
            $forum->setField("KETERANGAN", $reqKeterangan);
            $forum->setField("TANGGAL", 'CURRENT_TIMESTAMP');
            $forum->setField("STATUS", '1');

            if($reqMode == 'insert'){
                $forum->setField("LAST_CREATE_USER", $reqPegawaiId);
                $forum->setField("LAST_CREATE_DATE", 'CURRENT_TIMESTAMP');
                $forum->insert();
            }else{
                $forum->setField("LAST_UPDATE_USER", $reqPegawaiId);
                $forum->setField("LAST_UPDATE_DATE", 'CURRENT_TIMESTAMP');
                $forum->update();
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
