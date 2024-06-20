<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kauth
 *
 * @author user
 */
class usermobile{
	
	
	var $PEGAWAI_ID; 
	var $CABANG_ID; 
	var $NO_SEKAR; 
	var $NRP; 
	var $NIP; 
	var $NAMA; 
	var $NAMA_PANGGILAN; 
	var $JENIS_KELAMIN; 
	var $TEMPAT_LAHIR; 
	var $TANGGAL_LAHIR; 
	var $UNIT_KERJA; 
	var $ALAMAT; 
	var $NOMOR_HP; 
	var $EMAIL_PRIBADI; 
	var $EMAIL_BULOG; 
	var $NOMOR_WA; 
	var $GOLONGAN_DARAH; 
	
    /******************** CONSTRUCTOR **************************************/
    function usermobile(){
	
		 $this->emptyProps();
    }

    /******************** METHODS ************************************/
    /** Empty the properties **/
    function emptyProps(){
		$this->PEGAWAI_ID = "";
		$this->CABANG_ID = "";
		$this->NO_SEKAR = "";
		$this->NRP = "";
		$this->NIP = "";
		$this->NAMA = "";
		$this->NAMA_PANGGILAN = "";
		$this->JENIS_KELAMIN = "";
		$this->TEMPAT_LAHIR = "";
		$this->TANGGAL_LAHIR = "";
		$this->UNIT_KERJA = "";
		$this->ALAMAT = "";
		$this->NOMOR_HP = "";
		$this->EMAIL_PRIBADI = "";
		$this->EMAIL_BULOG = "";
		$this->NOMOR_WA = "";
		$this->GOLONGAN_DARAH = "";
				
    }
		
    
    /** Verify user login. True when login is valid**/
    function getInfo($userLoginId, $reqToken){			
		$CI =& get_instance();

		$CI =& get_instance();
		$CI->load->model("Users");	
		
		
		$users = new Users();
		$users->selectByPegawaiId($userLoginId);
		
		if($users->firstRow())
		{
			
            $this->PEGAWAI_ID = $users->getField("NIP");
			$this->CABANG_ID = $users->getField("CABANG_ID");
			$this->NO_SEKAR = $users->getField("NO_SEKAR");
			$this->NRP = $users->getField("NRP");
			$this->NIP = $users->getField("NIP");
			$this->NAMA = $users->getField("NAMA");
			$this->NAMA_PANGGILAN = $users->getField("NAMA_PANGGILAN");
			$this->JENIS_KELAMIN = $users->getField("JENIS_KELAMIN");
			$this->TEMPAT_LAHIR = $users->getField("TEMPAT_LAHIR");
			$this->TANGGAL_LAHIR = $users->getField("TANGGAL_LAHIR");
			$this->UNIT_KERJA = $users->getField("UNIT_KERJA");
			$this->ALAMAT = $users->getField("ALAMAT");
			$this->NOMOR_HP = $users->getField("NOMOR_HP");
			$this->EMAIL_PRIBADI = $users->getField("EMAIL_PRIBADI");
			$this->EMAIL_BULOG = $users->getField("EMAIL_BULOG");
			$this->NOMOR_WA = $users->getField("NOMOR_WA");
			$this->GOLONGAN_DARAH = $users->getField("GOLONGAN_DARAH");
			
		}

    }
			   
}
	
  /***** INSTANTIATE THE GLOBAL OBJECT */
  $userMobile = new usermobile();

?>
