<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class equipment_delivery_slip_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			// redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->aduanId				  = $this->kauth->getInstance()->getIdentity()->Dokumen_id;
		$this->Nip				  = $this->kauth->getInstance()->getIdentity()->Nip;
		$this->nama							= $this->kauth->getInstance()->getIdentity()->nama;
		$this->Aduan				= $this->kauth->getInstance()->getIdentity()->Aduan;
		$this->linkFile				= $this->kauth->getInstance()->getIdentity()->link_file;
		$this->createdBy				= $this->kauth->getInstance()->getIdentity()->created_by;
		$this->createdDate			= $this->kauth->getInstance()->getIdentity()->created_date;
		$this->updateBy				= $this->kauth->getInstance()->getIdentity()->update_by;
		$this->updateDate			= $this->kauth->getInstance()->getIdentity()->update_date;
	}

	function add()
	{
		$this->load->model("ServiceOrder");
		$this->load->model("SoEquip");
		$reqId =	$this->input->post("reqId");

		$bank = new ServiceOrder();

		$this->load->library("FileHandler");
		$file = new FileHandler();
		$filesData = $_FILES["document"];
		$reqLinkFileTemp              =  $this->input->post("reqLinkFileTemp");
		$file->cekSize($filesData,$reqLinkFileTemp);
	
		$reqNoDelivery 			= $this->input->post("reqNoDelivery");
		 $name_folder ="equipment_delivery_slip";
        $FILE_DIR = "uploads/" . $name_folder . "/" . $reqId . "/";
        makedirs($FILE_DIR);

        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
            if ($file->uploadToDirArray('document', $FILE_DIR, $renameFile, $i)) {
                array_push($arrData, setQuote($renameFile));
            } else {
                array_push($arrData, $reqLinkFileTemp[$i]);
            }
        }
        $str_name_path = '';
        for ($i = 0; $i < count($arrData); $i++) {
            if (!empty($arrData[$i])) {
                if ($i == 0) {
                    $str_name_path .= $arrData[$i];
                } else {
                    $str_name_path .= ';' . $arrData[$i];
                }
            }
        }
		if(!empty($reqId)){
		$bank->setField("SO_ID", $reqId);
		$bank->setField("NO_DELIVERY", $reqNoDelivery);
		$bank->setField("PATH", $str_name_path);
		// if (empty($reqId)) {
  //           $bank->insert_no_pengiriman();
  //           $reqId = $bank->id;
  //       } else {
        	$bank->update_no_pengiriman();
        // }
        	$so_equip = new SoEquip();
        	$so_equip->setField("SO_ID",$reqId);
        	$so_equip->update_flag();
        	$so_equip = new SoEquip();
        	$so_equip->delete_from_flag();
		}

		echo 'Data Berhasil di simpan';
	}
}
