
<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class spared_part_json extends CI_Controller
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
        $this->load->model('SparePart');
        $this->load->model('PartPemakaian');
        $partpemakaian  = new PartPemakaian();
        $reqId = $this->input->post('reqId');
        $reqDeskripisi= $this->input->post('reqDeskripisi');
        $reqLokasi= $this->input->post('reqLokasi');
        $reqNomer  =  $this->input->post('reqNomer');
        $reqIdPart = $this->input->post('reqIdPart');
        $reqMode = $this->input->post('reqMode');
        $reqSerialEquipment = $this->input->post('reqSerialEquipment');

        $reqNamaPart = $this->input->post('reqNamaPart');
        $reqNamaAlat = $this->input->post('reqNamaAlat');
        $reqEcId  = $this->input->post('reqEcId');
        $reqSerialNumber =  $this->input->post('reqSerialNumber');
        $reqJumlah  =  $this->input->post('reqJumlah');
 
        $sparepart = new SparePart();
        $sparepart->selectByParamsMonitoring(array('A.CODE'=>$reqId));
        $totalRow = $sparepart->rowCount;	  
        $sparepart->setField('CODE',$reqId);
        $sparepart->setField('NOMER',$reqNomer);
        $sparepart->setField('desktipsi',$reqDeskripisi);
        $sparepart->setField('SERIAL_EQUIP',$reqSerialEquipment);
        $sparepart->setField('ID_PART',$reqIdPart);
        $sparepart->setField('lokasi_id',$reqLokasi);
        $sparepart->setField('MODEL',$reqMode);
        if($reqMode=='baru'){
        $sparepart->setField('JUMLAH',$reqJumlah);
        $sparepart->setField('NAMA_PART',$reqNamaPart);
        $sparepart->setField('NAMA_ALAT',$reqNamaAlat);
        $sparepart->setField('KATEGORI',$reqEcId);
        $sparepart->setField('SERIAL_NUMBER',$reqSerialNumber);
        }
        if($totalRow==0){
            $sparepart->insert();
        }else{
           $sparepart->update();
    
        }
       
        $this->load->library("FileHandler");
        $file = new FileHandler();

        $FILE_DIR = "uploads/part/";
        makedirs($FILE_DIR);
        $filesData = $_FILES["reqFilesName"];
        $reqLinkFileTempSize    =  $this->input->post("reqLinkFileTempSize");
        $reqLinkFileTempTipe    =  $this->input->post("reqLinkFileTempTipe");
        $reqFilesNames    =  $this->input->post("reqFilesNames");

        $renameFile = "IMG" . date("dmYhis") . '-' . $reqId . "." . getExtension2($filesData['name'][0]);
        if ($file->uploadToDirArray('reqFilesName', $FILE_DIR, $renameFile, 0)) {
            $reqPicPath                    = $renameFile;
            
           
            $sparepart->setField("CODE", $reqId);
            $sparepart->setField("GAMBAR", setQuote($reqPicPath));
            $sparepart->update_pic();

       }

       $reqTanggalPemakain=  $this->input->post('reqTanggalPemakain');
       $reqPemakaian = $this->input->post('reqPemakaian');
       $reqProjectId =  $this->input->post('reqProjectId');
       $reqEquipQty =  $this->input->post('reqEquipQty');
       $reqMengetahui =  $this->input->post('reqMengetahui');
       $reqPemakaianPartId  =  $this->input->post('reqPemakaianPartId');
        $reqDeskripisiD =  $this->input->post('reqDeskripisiD');
        $partpemakaian->setField('CODE',$reqId);

        for($i=0;$i<count($reqTanggalPemakain);$i++){
            $partpemakaian->setField('TANGGAL',dateToDBCheck($reqTanggalPemakain[$i]));
            $partpemakaian->setField('PEMAKAIAN',$reqPemakaian[$i]);
            $partpemakaian->setField('PART_PEMAKAIAN_ID',$reqPemakaianPartId[$i]);
            $partpemakaian->setField('PROJECT_ID',ValToNullDB($reqProjectId[$i]));
            $partpemakaian->setField('JUMLAH',ValToNullDB($reqEquipQty[$i]));
             $partpemakaian->setField('KETERANGAN',$reqDeskripisiD[$i]);
            $partpemakaian->setField('MENGETAHUI',$reqMengetahui[$i]);
            if($reqEquipQty[$i] > 0){
                    if(empty($reqPemakaianPartId[$i])){
                         $partpemakaian->insert();
                    }else{
                         $partpemakaian->update();    
                    }
            }
       }

        $FILE_DIR = "uploads/part/" . $reqId . "/";
        makedirs($FILE_DIR);

        $filesData = $_FILES["reqLinkFileCertificate"];
         $reqLinkFileCertificateTemp      = $this->input->post("reqLinkFileCertificateTemp");

        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
            if ($file->uploadToDirArray('reqLinkFileCertificate', $FILE_DIR, $renameFile, $i)) {
                array_push($arrData, $renameFile);
            } else {
                array_push($arrData, $reqLinkFileCertificateTemp[$i]);
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

        $sparepart->setField("CODE", $reqId);
        $sparepart->setField("LAMPIRAN", setQuote($str_name_path));
        $sparepart->update_lampiran();
      
        $arrJson['status']='sukses';
         $arrJson['pesan']='Data berhasil di simpan';
          $arrJson['id']=$reqId;
       echo json_encode($arrJson);
    }

    function deletePekaianPart(){
        $this->load->model('PartPemakaian');
        $reqId = $this->input->get('reqId');

        $partpemakaian = new PartPemakaian();
        $partpemakaian->setField('PART_PEMAKAIAN_ID',$reqId);
        $partpemakaian->delete();

    }
}
