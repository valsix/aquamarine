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

  class TenderTypeUpload extends Entity{

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function TenderTypeUpload()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("TENDER_TYPE_UPLOAD_ID", $this->getNextId("TENDER_TYPE_UPLOAD_ID","TENDER_TYPE_UPLOAD")); 

    	$str = "INSERT INTO TENDER_TYPE_UPLOAD (TENDER_TYPE_UPLOAD_ID, TYPE, NAME, DESCRIPTION, TENDER_ID, CREATED_BY, CREATED_DATE)VALUES (
	    	'".$this->getField("TENDER_TYPE_UPLOAD_ID")."',
	    	'".$this->getField("TYPE")."',
	    	'".$this->getField("NAME")."',
	    	'".$this->getField("DESCRIPTION")."',
	    	".ValToNullDB($this->getField("TENDER_ID")).",
	    	'".$this->USERNAME."',
	    	now()
	    	
	    )";

	    $this->id = $this->getField("TENDER_TYPE_UPLOAD_ID");
	    $this->query= $str;
			// echo $str;exit();
	    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE TENDER_TYPE_UPLOAD
		SET    
		TENDER_TYPE_UPLOAD_ID ='".$this->getField("TENDER_TYPE_UPLOAD_ID")."',
		TYPE ='".$this->getField("TYPE")."',
		NAME ='".$this->getField("NAME")."',
		DESCRIPTION ='".$this->getField("DESCRIPTION")."',
		TENDER_ID =".ValToNullDB($this->getField("TENDER_ID")).",
		UPDATED_BY ='".$this->USERNAME."',
		UPDATED_DATE =now()
		WHERE TENDER_TYPE_UPLOAD_ID= '".$this->getField("TENDER_TYPE_UPLOAD_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM TENDER_TYPE_UPLOAD
		WHERE TENDER_TYPE_UPLOAD_ID= '".$this->getField("TENDER_TYPE_UPLOAD_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.TENDER_TYPE_UPLOAD_ID ASC")
	{
		$str = "
		SELECT A.TENDER_TYPE_UPLOAD_ID,A.TYPE,A.NAME,A.DESCRIPTION,A.TENDER_ID,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
		FROM TENDER_TYPE_UPLOAD A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM TENDER_TYPE_UPLOAD A WHERE 1=1 ".$statement;
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
