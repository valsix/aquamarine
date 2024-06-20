<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel POLLING_PEGAWAI.
  * 
  ***/
  include_once("Entity.php");

  class CostProject  extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function CostProject()
	{
      $this->Entity(); 
    }
	
    function insert()
    {
    	$this->setField("COST_PROJECT_ID", $this->getNextId("COST_PROJECT_ID","COST_PROJECT")); 

    	$str = "INSERT INTO COST_PROJECT (COST_PROJECT_ID, NO_PROJECT, VESSEL_NAME, TYPE_OF_VESSEL, TYPE_OF_SERVICE,        DATE_SERVICE1, DATE_SERVICE2, DESTINATION, COMPANY_NAME, CONTACT_PERSON,        KASBON, OFFER_PRICE, REAL_PRICE, SURVEYOR, OPERATOR,
    	KASBON_CUR,OFFER_CUR,REAL_CUR,SERVICE_ORDER_ID,CLASS_OF_VESSEL,ADD_SERVICE)VALUES (
    	'".$this->getField("COST_PROJECT_ID")."',
    	'".$this->getField("NO_PROJECT")."',
    	'".$this->getField("VESSEL_NAME")."',
    	'".$this->getField("TYPE_OF_VESSEL")."',
    	'".$this->getField("TYPE_OF_SERVICE")."',
    	".$this->getField("DATE_SERVICE1").",
    	".$this->getField("DATE_SERVICE2").",
    	'".$this->getField("DESTINATION")."',
    	'".$this->getField("COMPANY_NAME")."',
    	'".$this->getField("CONTACT_PERSON")."',
    	".$this->getField("KASBON").",
    	".$this->getField("OFFER_PRICE").",
    	".$this->getField("REAL_PRICE").",
    	'".$this->getField("SURVEYOR")."',
    	'".$this->getField("OPERATOR")."' ,
    	'".$this->getField("KASBON_CUR")."', 
    	'".$this->getField("OFFER_CUR")."' ,
    	'".$this->getField("REAL_CUR")."' ,
    	".$this->getField("SERVICE_ORDER_ID").",
    	'".$this->getField("CLASS_OF_VESSEL")."' ,
    	'".$this->getField("ADD_SERVICE")."'

    )";

    $this->id = $this->getField("COST_PROJECT_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function insert_form_hpp(){
		$this->setField("COST_PROJECT_ID", $this->getNextId("COST_PROJECT_ID","COST_PROJECT")); 
		$str = "INSERT INTO COST_PROJECT (COST_PROJECT_ID ,OFFER_ID,HPP_PROJECT_ID,NO_PROJECT,COMPANY_NAME,VESSEL_NAME,TYPE_OF_VESSEL)VALUES (
    	'".$this->getField("COST_PROJECT_ID")."',
    	 '".$this->getField("OFFER_ID")."',
    	  '".$this->getField("HPP_PROJECT_ID")."',
    	   '".$this->getField("NO_PROJECT")."',
    	    '".$this->getField("COMPANY_NAME")."',
    	    '".$this->getField("VESSEL_NAME")."',
    	    '".$this->getField("TYPE_OF_VESSEL")."'

 	

    )";

    $this->id = $this->getField("COST_PROJECT_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function add_project_id_hpp(){
		$str = "
		UPDATE COST_PROJECT
		SET    
		HPP_PROJECT_ID =".retNullString($this->getField("HPP_PROJECT_ID"))."


		WHERE OFFER_ID= '".$this->getField("OFFER_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);

	}
	function update_price_form_offer(){
		$str = "
		UPDATE COST_PROJECT
		SET    
		OFFER_PRICE ='".$this->getField("OFFER_PRICE")."',
		CLASS_OF_VESSEL ='".$this->getField("CLASS_OF_VESSEL")."',
		OFFER_CUR ='".$this->getField("OFFER_CUR")."'
		
		
		
		WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}
	function update_form_hpp()
	{

		$str = "
		UPDATE COST_PROJECT
		SET    
		NO_PROJECT ='".$this->getField("NO_PROJECT")."',
		VESSEL_NAME ='".$this->getField("VESSEL_NAME")."',
		COMPANY_NAME ='".$this->getField("COMPANY_NAME")."',
		
		TYPE_OF_VESSEL ='".$this->getField("TYPE_OF_VESSEL")."'
		
		
		
		WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function update()
	{

		$str = "
		UPDATE COST_PROJECT
		SET    
		COST_PROJECT_ID ='".$this->getField("COST_PROJECT_ID")."',
		NO_PROJECT ='".$this->getField("NO_PROJECT")."',
		VESSEL_NAME ='".$this->getField("VESSEL_NAME")."',
		TYPE_OF_VESSEL ='".$this->getField("TYPE_OF_VESSEL")."',
		CLASS_OF_VESSEL ='".$this->getField("CLASS_OF_VESSEL")."',
		TYPE_OF_SERVICE ='".$this->getField("TYPE_OF_SERVICE")."',
		DATE_SERVICE1 =".$this->getField("DATE_SERVICE1").",
		DATE_SERVICE2 =".$this->getField("DATE_SERVICE2").",
		DESTINATION ='".$this->getField("DESTINATION")."',
		COMPANY_NAME ='".$this->getField("COMPANY_NAME")."',
		CONTACT_PERSON ='".$this->getField("CONTACT_PERSON")."',
		KASBON =".$this->getField("KASBON").",
		OFFER_PRICE =".$this->getField("OFFER_PRICE").",
		REAL_PRICE =".$this->getField("REAL_PRICE").",
		SURVEYOR ='".$this->getField("SURVEYOR")."',
		KASBON_CUR ='".$this->getField("KASBON_CUR")."',
		OFFER_CUR ='".$this->getField("OFFER_CUR")."',
		REAL_CUR ='".$this->getField("REAL_CUR")."',
		SERVICE_ORDER_ID = ".$this->getField("SERVICE_ORDER_ID").",
		ADD_SERVICE ='".$this->getField("ADD_SERVICE")."',
		OPERATOR ='".$this->getField("OPERATOR")."' 
		WHERE COST_PROJECT_ID= '".$this->getField("COST_PROJECT_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function update_surveyor_operator()
	{

		$str = "
		UPDATE COST_PROJECT
		SET    
		DATE_SERVICE2 =".$this->getField("DATE_SERVICE2").",
		DATE_SERVICE1 =".$this->getField("DATE_SERVICE1").",
		SURVEYOR ='".$this->getField("SURVEYOR")."',
		OPERATOR ='".$this->getField("OPERATOR")."' 
		WHERE NO_PROJECT= '".$this->getField("NO_PROJECT")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function update_surveyor_operator_report()
	{

		$str = "
		UPDATE DOKUMEN_REPORT
		SET    
		
		FINISH_DATE =".$this->getField("FINISH_DATE").",
		START_DATE =".$this->getField("START_DATE").",
		COST_SURYEVOR ='".$this->getField("COST_SURYEVOR")."',
		COST_OPERATOR ='".$this->getField("COST_OPERATOR")."' 
		WHERE NO_REPORT= '".$this->getField("NO_REPORT")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = " DELETE FROM COST_PROJECT
		WHERE COST_PROJECT_ID= '".$this->getField("COST_PROJECT_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}
	function deleteHpp($statement= "")
	{
		$str = "DELETE FROM COST_PROJECT
		WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.COST_PROJECT_ID ASC")
	{
		$str = "
		SELECT A.COST_PROJECT_ID,A.NO_PROJECT,A.VESSEL_NAME,A.TYPE_OF_VESSEL,A.TYPE_OF_SERVICE,A.DATE_SERVICE1,A.DATE_SERVICE2,A.DESTINATION,A.COMPANY_NAME,A.CONTACT_PERSON,A.KASBON,A.OFFER_PRICE,A.REAL_PRICE,A.SURVEYOR,A.OPERATOR,A.KASBON_CUR,A.OFFER_CUR,A.REAL_CUR,A.SERVICE_ORDER_ID,A.HPP_PROJECT_ID
		FROM COST_PROJECT A
		WHERE 1=1 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}


	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM COST_PROJECT A WHERE 1=1 ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = 	'$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}
	
  } 
?>