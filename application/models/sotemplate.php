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

  class SoTemplate extends Entity{

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function SoTemplate()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("SO_TEMPLATE_ID", $this->getNextId("SO_TEMPLATE_ID","SO_TEMPLATE")); 

    	$str = "INSERT INTO SO_TEMPLATE (SO_TEMPLATE_ID, NAMA, KETERANGAN, CREATED_BY, CREATED_DATE)VALUES (
    	'".$this->getField("SO_TEMPLATE_ID")."',
    	'".$this->getField("NAMA")."',
    	'".$this->getField("KETERANGAN")."',
    	'".$this->USERNAME."',
    	CURRENT_DATE
    	
    )";

    $this->id = $this->getField("SO_TEMPLATE_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE SO_TEMPLATE
		SET    
		NAMA ='".$this->getField("NAMA")."',
		KETERANGAN ='".$this->getField("KETERANGAN")."',
		UPDATED_BY ='".$this->USERNAME."',
		UPDATED_DATE =CURRENT_DATE
		WHERE SO_TEMPLATE_ID= '".$this->getField("SO_TEMPLATE_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM SO_TEMPLATE
		WHERE SO_TEMPLATE_ID= '".$this->getField("SO_TEMPLATE_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SO_TEMPLATE_ID ASC")
	{
		$str = "
		SELECT A.SO_TEMPLATE_ID,A.NAMA,A.KETERANGAN,(SELECT COUNT(1) FROM SO_TEMPLATE_EQUIP X WHERE X.SO_TEMPLATE_ID=A.SO_TEMPLATE_ID) JUMLAH
		FROM SO_TEMPLATE A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SO_TEMPLATE A WHERE 1=1 ".$statement;
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
