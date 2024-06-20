<?
//include_once 'class.phpmailer.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
class KMail extends PHPMailer{


    function __construct($exceptions = false) {
        parent::__construct($exceptions);
        
        $this->IsSMTP();
        $this->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );      
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
                        
          // Ask for HTML-friendly debug output
        $this->Host     = "mail.aquamarine.id";
	    //$this->Host     = "outlook.office365.com";
		  $this->Port     = 587;
		     // $this->Username = "no.reply.presensipjb@gmail.com";  
        //this->Password = "P3lindoDaya"; 
        $this->SMTPDebug = 0;
        //$this->Host     = "smtp.office365.com";
	    $this->Port     = 587;
	    $this->SMTPAuth = TRUE;   
	//	$this->Username = "operation@aquamarine.id";  
      //  $this->Password = "Ot0103op"; 
	   // $this->From     = "operation@aquamarine.id";
	
//	$this->Username = "inspection@aquamarine.id";  
  //     $this->Password = "Amdi2021"; 
    //    $this->From     = "inspection@aquamarine.id";
	
		$this->Username = "inspection@aquamarine.id";  
		$this->Password = "indo332211*"; 
        $this->From     = "inspection@aquamarine.id";
	
        $this->FromName = "PT AQUAMARINE DIVINDO INSPECTION";
        $this->SMTPSecure  = "tls";

       
     
        $this->WordWrap = 50;           
        $this->Priority = 1;
        $this->CharSet = "UTF-8";
        $this->IsHTML(TRUE);
        $this->AltBody    = "To view the message, please use an HTML compatible email viewer!";
    }
}

?>
