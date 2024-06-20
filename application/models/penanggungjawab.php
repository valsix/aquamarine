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

  class PenanggungJawab extends Entity{

	var $query;
    /**
    * Class constructor.
    **/
    function PenanggungJawab()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("PENANGGUNG_JAWAB_ID", $this->getNextId("PENANGGUNG_JAWAB_ID","PENANGGUNG_JAWAB")); 

    	$str = "INSERT INTO PENANGGUNG_JAWAB (PENANGGUNG_JAWAB_ID, NAMA, JABATAN, TTD_LINK, CREATED_BY, CREATED_DATE)VALUES (
    	'".$this->getField("PENANGGUNG_JAWAB_ID")."',
    	'".$this->getField("NAMA")."',
    	'".$this->getField("JABATAN")."',
    	'".$this->getField("TTD_LINK")."',
    	'".$this->getField("CREATED_BY")."',
    	CURRENT_DATE
    )";

    $this->id = $this->getField("PENANGGUNG_JAWAB_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE PENANGGUNG_JAWAB
		SET    
			NAMA 			='".$this->getField("NAMA")."',
			JABATAN 		='".$this->getField("JABATAN")."',
			TTD_LINK 		='".$this->getField("TTD_LINK")."', 
			UPDATED_BY 		='".$this->getField("UPDATED_BY")."',
			UPDATED_DATE 	=CURRENT_DATE
		WHERE PENANGGUNG_JAWAB_ID= '".$this->getField("PENANGGUNG_JAWAB_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM PENANGGUNG_JAWAB
		WHERE PENANGGUNG_JAWAB_ID= '".$this->getField("PENANGGUNG_JAWAB_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PENANGGUNG_JAWAB_ID ASC")
	{
		$str = "
		SELECT A.PENANGGUNG_JAWAB_ID,A.NAMA,A.JABATAN,A.TTD_LINK
		FROM PENANGGUNG_JAWAB A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM PENANGGUNG_JAWAB A WHERE 1=1 ".$statement;
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
