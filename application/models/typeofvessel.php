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

  class TypeOfVessel extends Entity{

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function TypeOfVessel()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("TYPE_OF_VESSEL_ID", $this->getNextId("TYPE_OF_VESSEL_ID","TYPE_OF_VESSEL")); 

    	$str = "INSERT INTO TYPE_OF_VESSEL (TYPE_OF_VESSEL_ID, NAME, DESCRIPTION, CREATED_BY, CREATED_DATE)VALUES (
    	'".$this->getField("TYPE_OF_VESSEL_ID")."',
    	'".$this->getField("NAME")."',
    	'".$this->getField("DESCRIPTION")."',
    	'".$this->USERNAME."',
    	now()
    	
    )";

    $this->id = $this->getField("TYPE_OF_VESSEL_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE TYPE_OF_VESSEL
		SET    
		TYPE_OF_VESSEL_ID ='".$this->getField("TYPE_OF_VESSEL_ID")."',
		NAME ='".$this->getField("NAME")."',
		DESCRIPTION ='".$this->getField("DESCRIPTION")."',
		
		UPDATED_BY ='".$this->USERNAME."',
		UPDATED_DATE =now()
		WHERE TYPE_OF_VESSEL_ID= '".$this->getField("TYPE_OF_VESSEL_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM TYPE_OF_VESSEL
		WHERE TYPE_OF_VESSEL_ID= '".$this->getField("TYPE_OF_VESSEL_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.TYPE_OF_VESSEL_ID ASC")
	{
		$str = "
		SELECT A.TYPE_OF_VESSEL_ID,A.NAME,A.DESCRIPTION,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
		FROM TYPE_OF_VESSEL A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM TYPE_OF_VESSEL A WHERE 1=1 ".$statement;
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
