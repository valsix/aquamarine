<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class menu_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }
 
    // show data entitas
	function index_get() {
        $aColumns = array("MENU_ID", "NAMA", "LINK", "BACKGROUND", "ICON");
        
        $this->load->model("Menu");
        $menu = new Menu();

        $menu->selectByParams();
        $result = array();
        while ($menu->nextRow()) {
            for ($i=0; $i < count($aColumns); $i++) { 
                if($aColumns[$i] == "ICON")
                    $row[trim($aColumns[$i])] = base_url()."uploads/menu/".$menu->getField(trim($aColumns[$i]));
                else
                    $row[trim($aColumns[$i])] = $menu->getField(trim($aColumns[$i]));
            }
            $result[] = $row;
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