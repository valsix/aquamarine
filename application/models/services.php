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

  class Services extends Entity{

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function Services()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("SERVICES_ID", $this->getNextId("SERVICES_ID","SERVICES")); 

    	$str = "INSERT INTO SERVICES (SERVICES_ID, NAMA, KET, CREATED_BY, CREATED_DATE)VALUES (
    	'".$this->getField("SERVICES_ID")."',
    	'".$this->getField("NAMA")."',
    	'".$this->getField("KET")."',
    	'".$this->USERNAME."',
    	now()
    	
    )";

    $this->id = $this->getField("SERVICES_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE SERVICES
		SET    
		SERVICES_ID ='".$this->getField("SERVICES_ID")."',
		NAMA ='".$this->getField("NAMA")."',
		KET ='".$this->getField("KET")."',

		UPDATED_BY ='".$this->USERNAME."',
		UPDATED_DATE =now()
		WHERE SERVICES_ID= '".$this->getField("SERVICES_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM SERVICES
		WHERE SERVICES_ID= '".$this->getField("SERVICES_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SERVICES_ID ASC")
	{
		$str = "
		SELECT A.SERVICES_ID,A.NAMA,A.KET,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
		FROM SERVICES A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SERVICES A WHERE 1=1 ".$statement;
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
