<? 
/* *******************************************************************************************************
MODUL NAME 			: MTSN LAWANG
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');
  
  class ResikoEmail   extends Entity{ 

	var $query;
  var $id;
    /**
    * Class constructor.
    **/
    function ResikoEmail()
	  {
      $this->Entity(); 
    }

    function sendEmail($alamatEmail){
      // $val ='dummy';
      $val ='real';
      $email="testrais123@gmail.com";
       // $email="notif@teluklamong.co.id";
      if($val=='dummy'){
         return  $email;
      }else{
        return $alamatEmail;
      }



    }
    
  }
