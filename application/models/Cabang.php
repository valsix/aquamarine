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

  class Cabang  extends Entity{

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function Cabang()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("CABANG_ID", $this->getNextId("CABANG_ID","CABANG")); 

    	$str = "INSERT INTO CABANG (CABANG_ID, KODE, NAMA, DESCRIPTION, CREATED_BY, CREATED_DATE)VALUES (
    	'".$this->getField("CABANG_ID")."',
    	'".$this->getField("KODE")."',
    	'".$this->getField("NAMA")."',
    	'".$this->getField("DESCRIPTION")."',
    	'".$this->USERNAME."',
    	now()
    	
    )";

    $this->id = $this->getField("CABANG_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE CABANG
		SET    
		CABANG_ID ='".$this->getField("CABANG_ID")."',
		KODE ='".$this->getField("KODE")."',
		NAMA ='".$this->getField("NAMA")."',
		DESCRIPTION ='".$this->getField("DESCRIPTION")."',
		
		UPDATED_BY ='".$this->USERNAME."',
		UPDATED_DATE = now() 
		WHERE CABANG_ID= '".$this->getField("CABANG_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM CABANG
		WHERE CABANG_ID= '".$this->getField("CABANG_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.CABANG_ID ASC")
	{
		$str = "
		SELECT A.CABANG_ID,A.KODE,A.NAMA,A.DESCRIPTION,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
		FROM CABANG A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM CABANG A WHERE 1=1 ".$statement;
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
