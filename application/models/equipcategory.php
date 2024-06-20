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

  class EquipCategory extends Entity{

	var $query;
    /**
    * Class constructor.
    **/
    function EquipCategory()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("EC_ID", $this->getNextId("EC_ID","EQUIP_CATEGORY")); 

    	$str = "INSERT INTO EQUIP_CATEGORY (EC_ID, EC_NAME, EC_DESCRIPTION)VALUES (
    	'".$this->getField("EC_ID")."',
    	'".$this->getField("EC_NAME")."',
    	'".$this->getField("EC_DESCRIPTION")."'
    )";

    $this->id = $this->getField("EC_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE EQUIP_CATEGORY
		SET    
		EC_ID ='".$this->getField("EC_ID")."',
		EC_NAME ='".$this->getField("EC_NAME")."',
		EC_DESCRIPTION ='".$this->getField("EC_DESCRIPTION")."' 
		WHERE EC_ID= '".$this->getField("EC_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM EQUIP_CATEGORY
		WHERE EC_ID= '".$this->getField("EC_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.EC_ID ASC")
	{
		$str = "
		SELECT A.EC_ID,A.EC_NAME,A.EC_DESCRIPTION
		FROM EQUIP_CATEGORY A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM EQUIP_CATEGORY A WHERE 1=1 ".$statement;
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
