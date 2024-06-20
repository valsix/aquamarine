<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class cabang_json extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
    }

    // show data entitas
    function index_get() {

        $this->load->model("Master");
        $master = new Master();

        $master->selectCabang();
        $result = array();
		$i = 0;
        while ($master->nextRow()) 
		{
            $result[$i]['id'] 		= $master->getField("CABANG_ID");
            $result[$i]['text'] 	= $master->getField("NAMA");
			$i++;
        }

        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $result));

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
