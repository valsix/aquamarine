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

  class Satuan  extends Entity{

	var $query;
    /**
    * Class constructor.
    **/
    function Satuan()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("SATUAN_ID", $this->getNextId("SATUAN_ID","SATUAN")); 

    	$str = "INSERT INTO SATUAN (SATUAN_ID, NAME, DESCRIPTION, CREATED_BY, CREATED_DATE)VALUES (
    	'".$this->getField("SATUAN_ID")."',
    	'".$this->getField("NAME")."',
    	'".$this->getField("DESCRIPTION")."',
    	'".$this->USERNAME."',
    	now()
    	
    )";

    $this->id = $this->getField("SATUAN_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE SATUAN
		SET    
		SATUAN_ID ='".$this->getField("SATUAN_ID")."',
		NAME ='".$this->getField("NAME")."',
		DESCRIPTION ='".$this->getField("DESCRIPTION")."',
		
		UPDATED_BY ='".$this->USERNAME."',
		UPDATED_DATE =now()
		WHERE SATUAN_ID= '".$this->getField("SATUAN_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM SATUAN
		WHERE SATUAN_ID= '".$this->getField("SATUAN_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SATUAN_ID ASC")
	{
		$str = "
		SELECT A.SATUAN_ID,A.NAME,A.DESCRIPTION,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
		FROM SATUAN A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SATUAN A WHERE 1=1 ".$statement;
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
