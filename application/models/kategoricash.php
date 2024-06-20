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

  class KategoriCash  extends Entity{

	var $query;
    /**
    * Class constructor.
    **/
    function KategoriCash()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("KATEGORI_CASH_ID", $this->getNextId("KATEGORI_CASH_ID","KATEGORI_CASH")); 

    	$str = "INSERT INTO KATEGORI_CASH (KATEGORI_CASH_ID, NAMA, KET,FLAG, CREATED_BY, CREATED_DATE)VALUES (
    	'".$this->getField("KATEGORI_CASH_ID")."',
    	'".$this->getField("NAMA")."',
    	'".$this->getField("KET")."',
    	'".$this->getField("FLAG")."',
    	'".$this->USERNAME."',
    	now()
    	
    )";

    $this->id = $this->getField("KATEGORI_CASH_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE KATEGORI_CASH
		SET    
		KATEGORI_CASH_ID ='".$this->getField("KATEGORI_CASH_ID")."',
		NAMA ='".$this->getField("NAMA")."',
		KET ='".$this->getField("KET")."',
		FLAG ='".$this->getField("FLAG")."',
		
		UPDATED_BY ='".$this->USERNAME."',
		UPDATED_DATE =now()
		WHERE KATEGORI_CASH_ID= '".$this->getField("KATEGORI_CASH_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM KATEGORI_CASH
		WHERE KATEGORI_CASH_ID= '".$this->getField("KATEGORI_CASH_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.KATEGORI_CASH_ID ASC")
	{
		$str = "
		SELECT A.KATEGORI_CASH_ID,A.NAMA,A.KET,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.FLAG
		FROM KATEGORI_CASH A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM KATEGORI_CASH A WHERE 1=1 ".$statement;
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
