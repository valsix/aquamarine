<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class forum_kategori_json extends REST_Controller {

    function __construct() {
        parent::__construct();
    }

    // show data entitas
    function index_get() {
        $aColumns = array("FORUM_KATEGORI_ID", "NAMA", "KETERANGAN", "POSTS", "TANGGAL");

        $this->load->model('ForumKategori');
        $this->load->model('UserLoginMobile');

        $forum_kategori = new ForumKategori;
        $user_login_mobile = new UserLoginMobile();
        
        $reqToken = $this->input->get('reqToken');
        $reqSearch = $this->input->get('reqSearch');
        
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if($reqPegawaiId <> "0")
        {  
            $reqId = $this->input->get('reqId');

            if($reqSearch == '' || $reqSearch == null){
                $statements .= '' ;   
            }else{
                $statements .= " AND (UPPER(A.NAMA) LIKE '%".strtoupper(setQuote($reqSearch))."%' OR UPPER(A.KETERANGAN) LIKE '%".strtoupper(setQuote($reqSearch))."%')";
            }

            $result = array();
            $forum_kategori->selectByParams(array(), -1, -1, $statements);
            while ($forum_kategori->nextRow()) {
                for ($i = 0; $i < count($aColumns); $i++) {
                    if(trim($aColumns[$i]) == 'TANGGAL'){
                        $row[trim($aColumns[$i])] = getFormattedDate($forum_kategori->getField(trim($aColumns[$i])));
                    }
                    elseif (trim($aColumns[$i]) == 'KETERANGAN'){
                        $row[trim($aColumns[$i])] = dropAllHtml($forum_kategori->getField(trim($aColumns[$i])));
                    }
                    else
                        $row[trim($aColumns[$i])] = $forum_kategori->getField(trim($aColumns[$i]));
                }
                $result[] = $row;
            }

            // var_dump($forum_kategori); exit;
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
