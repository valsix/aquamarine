
<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class master_lampiran_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->USERID = $this->kauth->getInstance()->getIdentity()->USERID;
		$this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->FULLNAME = $this->kauth->getInstance()->getIdentity()->FULLNAME;
		$this->USERPASS = $this->kauth->getInstance()->getIdentity()->USERPASS;
		$this->LEVEL = $this->kauth->getInstance()->getIdentity()->LEVEL;
		$this->MENUMARKETING = $this->kauth->getInstance()->getIdentity()->MENUMARKETING;
		$this->MENUFINANCE = $this->kauth->getInstance()->getIdentity()->MENUFINANCE;
		$this->MENUPRODUCTION = $this->kauth->getInstance()->getIdentity()->MENUPRODUCTION;
		$this->MENUDOCUMENT = $this->kauth->getInstance()->getIdentity()->MENUDOCUMENT;
		$this->MENUSEARCH = $this->kauth->getInstance()->getIdentity()->MENUSEARCH;
		$this->MENUOTHERS = $this->kauth->getInstance()->getIdentity()->MENUOTHERS;
		$this->MENUEPL = $this->kauth->getInstance()->getIdentity()->MENUEPL;
		$this->MENUUWILD = $this->kauth->getInstance()->getIdentity()->MENUUWILD;
		$this->MENUWP = $this->kauth->getInstance()->getIdentity()->MENUWP;
		$this->MENUPL = $this->kauth->getInstance()->getIdentity()->MENUPL;
		$this->MENUEL = $this->kauth->getInstance()->getIdentity()->MENUEL;
		$this->MENUPMS = $this->kauth->getInstance()->getIdentity()->MENUPMS;
		$this->MENURS = $this->kauth->getInstance()->getIdentity()->MENURS;
		$this->MENUSTD = $this->kauth->getInstance()->getIdentity()->MENUSTD;
		$this->MENUSTEN = $this->kauth->getInstance()->getIdentity()->MENUSTEN;
		$this->MENUSWD = $this->kauth->getInstance()->getIdentity()->MENUSWD;
		$this->MENUINVPROJECT = $this->kauth->getInstance()->getIdentity()->MENUINVPROJECT;


	}

	function add(){
        $this->load->model('LampiranData');
      
        $reqId = $this->input->post('reqId');
        $reqModul = $this->input->post('reqModul');
        $reqLinkFileLampiranId = $this->input->post('reqLinkFileLampiranId');
        $this->load->library("FileHandler");
        $file = new FileHandler();

      

        $FILE_DIR = "uploads/lampiran/" . $reqId . "/";
        makedirs($FILE_DIR);

        $filesData = $_FILES["reqLinkFileCertificate"];
         $reqLinkFileCertificateTemp      = $this->input->post("reqLinkFileCertificateTemp");

        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile = $reqId . '-' . $i.$reqModul . "-" . getExtension($filesData['name'][$i]);
            if ($file->uploadToDirArray('reqLinkFileCertificate', $FILE_DIR, $renameFile, $i)) {
                // array_push($arrData, $renameFile);
            } else {
                // array_push($arrData, $reqLinkFileCertificateTemp[$i]);
                $renameFile = $reqLinkFileCertificateTemp[$i];
            }

            $lampiran_data = new LampiranData();
            $lampiran_data->setField("LAMPIRAN_DATA_ID", $reqLinkFileLampiranId[$i]);
            $lampiran_data->setField("MODUL_ID", $reqId);
            $lampiran_data->setField("MODUL", $reqModul);
            $lampiran_data->setField("NAMA", $renameFile);
            $lampiran_data->setField("KETERANGAN", getExtension($filesData['name'][$i]));
            if(empty($reqLinkFileLampiranId[$i])){
                  $lampiran_data->insert();
            }else{
                 $lampiran_data->update();
            }
        }
       

       echo 'Data Berhasil disimpan'; 
      
       
    }


    function delete(){
        $reqId = $this->input->get('reqId');
         $this->load->model('LampiranData');
         $lampirandata = new LampiranData();
         $lampirandata->setField('LAMPIRAN_DATA_ID',$reqId);
          $lampirandata->delete();
    }
   
}
