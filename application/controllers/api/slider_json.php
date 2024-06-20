<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class slider_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }
 
    // show data entitas
	function index_get() {
        
        $this->load->model("Slider");
        $slider = new Slider();

        $slider->selectByParams(array("JENIS" => "SLIDER", "A.STATUS_PUBLISH" => "Y"));
        $result = array();
        while ($slider->nextRow()) {
            $row['SLIDER_ID'] = $slider->getField("SLIDER_ID");
            $row['url'] = base_url()."uploads/".$slider->getField("LINK_FILE");
            $row['title'] = $slider->getField("NAMA");
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