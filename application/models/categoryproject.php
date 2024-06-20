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

  class CategoryProject extends Entity{

	var $query;
    /**
    * Class constructor.
    **/
    function CategoryProject()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("CP_ID", $this->getNextId("CP_ID","CATEGORY_PROJECT")); 

    	$str = "INSERT INTO CATEGORY_PROJECT (CP_ID, NAME, DESCRIPTION)VALUES (
    	'".$this->getField("CP_ID")."',
    	'".$this->getField("NAME")."',
    	'".$this->getField("DESCRIPTION")."'
    )";

    $this->id = $this->getField("CP_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE CATEGORY_PROJECT
		SET    
		NAME ='".$this->getField("NAME")."',
		DESCRIPTION ='".$this->getField("DESCRIPTION")."' 
		WHERE CP_ID= '".$this->getField("CP_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM CATEGORY_PROJECT
		WHERE CP_ID= '".$this->getField("CP_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.CP_ID ASC")
	{
		$str = "
		SELECT A.CP_ID,A.NAME,A.DESCRIPTION
		FROM CATEGORY_PROJECT A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM CATEGORY_PROJECT A WHERE 1=1 ".$statement;
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
