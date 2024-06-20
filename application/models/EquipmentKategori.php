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

  class EquipmentKategori extends Entity{

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function EquipmentKategori()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("EQUIPMENT_KATEGORI_ID", $this->getNextId("EQUIPMENT_KATEGORI_ID","EQUIPMENT_KATEGORI")); 

    	$str = "INSERT INTO EQUIPMENT_KATEGORI (EQUIPMENT_KATEGORI_ID, NAMA, KET, CREATED_BY, CREATED_DATE)VALUES (
    	'".$this->getField("EQUIPMENT_KATEGORI_ID")."',
    	'".$this->getField("NAMA")."',
    	'".$this->getField("KET")."',
    	'".$this->USERNAME."',
    	now()
    	
    )";

    $this->id = $this->getField("EQUIPMENT_KATEGORI_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE EQUIPMENT_KATEGORI
		SET    
		EQUIPMENT_KATEGORI_ID ='".$this->getField("EQUIPMENT_KATEGORI_ID")."',
		NAMA ='".$this->getField("NAMA")."',
		KET ='".$this->getField("KET")."',

		UPDATED_BY ='".$this->USERNAME."',
		UPDATED_DATE =now()
		WHERE EQUIPMENT_KATEGORI_ID= '".$this->getField("EQUIPMENT_KATEGORI_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM EQUIPMENT_KATEGORI
		WHERE EQUIPMENT_KATEGORI_ID= '".$this->getField("EQUIPMENT_KATEGORI_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.EQUIPMENT_KATEGORI_ID ASC")
	{
		$str = "
		SELECT A.EQUIPMENT_KATEGORI_ID,A.NAMA,A.KET,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
		FROM EQUIPMENT_KATEGORI A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM EQUIPMENT_KATEGORI A WHERE 1=1 ".$statement;
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
