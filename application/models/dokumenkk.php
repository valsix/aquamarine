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
  
  class DokumenKk  extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function DokumenKk()
	{
      $this->Entity(); 
    }
	
    function insert()
    {
    	$this->setField("DOCUMENT_ID", $this->getNextId("DOCUMENT_ID","DOKUMEN_KK")); 

    	$str = "INSERT INTO DOKUMEN_KK (DOCUMENT_ID, TYPE, NAME, DESCRIPTION, PATH, LAST_REVISI)
    	VALUES (
    	'".$this->getField("DOCUMENT_ID")."',
    	'".$this->getField("TYPE")."',
    	'".$this->getField("NAME")."',
    	'".$this->getField("DESCRIPTION")."',
    	'".$this->getField("PATH")."',
    	NOW()
    )";

    $this->id = $this->getField("DOCUMENT_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE DOKUMEN_KK
		SET    
		DOCUMENT_ID ='".$this->getField("DOCUMENT_ID")."',
		TYPE ='".$this->getField("TYPE")."',
		NAME ='".$this->getField("NAME")."',
		DESCRIPTION ='".$this->getField("DESCRIPTION")."',
		PATH ='".$this->getField("PATH")."',
		LAST_REVISI =now() 
		WHERE DOCUMENT_ID= '".$this->getField("DOCUMENT_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}
	function update_path()
	{
		$str = "
		UPDATE DOKUMEN_KK
		SET    
		
		PATH ='".$this->getField("PATH")."'
		
		WHERE DOCUMENT_ID= '".$this->getField("DOCUMENT_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM DOKUMEN_KK
		WHERE DOCUMENT_ID= '".$this->getField("DOCUMENT_ID").""; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.DOCUMENT_ID ASC")
	{
		$str = "
		SELECT A.DOCUMENT_ID,A.TYPE,A.NAME,A.DESCRIPTION,A.PATH,A.LAST_REVISI
		FROM dokumen_kk A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM DOKUMEN_KK A WHERE 1=1 ".$statement;
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