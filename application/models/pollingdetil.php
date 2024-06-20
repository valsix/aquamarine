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
  
  class PollingDetil extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PollingDetil()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("POLLING_DETIL_ID", $this->getNextId("POLLING_DETIL_ID","POLLING_DETIL")); 		
		//'".$this->getField("FOTO")."',  FOTO,
		$str = "
				INSERT INTO POLLING_DETIL (
				   POLLING_DETIL_ID, POLLING_ID, NAMA, 
				   LAST_CREATE_USER, LAST_CREATE_DATE)
   				VALUES (
				  '".$this->getField("POLLING_DETIL_ID")."',
				  '".$this->getField("POLLING_ID")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->id = $this->getField("POLLING_DETIL_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE POLLING_DETIL
				SET    NAMA    		 = '".$this->getField("NAMA")."',
				       LAST_UPDATE_USER    = '".$this->getField("LAST_UPDATE_USER")."',
				       LAST_UPDATE_DATE  = CURRENT_DATE
				WHERE  POLLING_DETIL_ID    = '".$this->getField("POLLING_DETIL_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM POLLING_DETIL
                WHERE 
                  POLLING_DETIL_ID = ".$this->getField("POLLING_DETIL_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				SELECT 
				POLLING_DETIL_ID, POLLING_ID, NAMA, 
				   LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
				   LAST_UPDATE_DATE
				FROM POLLING_DETIL A
				WHERE 1 = 1
				"; 
		//, FOTO
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT 
				POLLING_DETIL_ID, POLLING_ID, NAMA, 
				   LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
				   LAST_UPDATE_DATE
				FROM POLLING_DETIL A
				WHERE 1 = 1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY TANGGAL ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(POLLING_DETIL_ID) AS ROWCOUNT FROM POLLING_DETIL A
		        WHERE POLLING_DETIL_ID IS NOT NULL ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(POLLING_DETIL_ID) AS ROWCOUNT FROM POLLING_DETIL A
		        WHERE POLLING_DETIL_ID IS NOT NULL ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
  } 
?>