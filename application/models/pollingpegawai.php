<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel POLLING_PEGAWAI.
  * 
  ***/
  include_once("Entity.php");

  class PollingPegawai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PollingPegawai()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("POLLING_PEGAWAI_ID", $this->getNextId("POLLING_PEGAWAI_ID","POLLING_PEGAWAI")); 		
		//'".$this->getField("FOTO")."',  FOTO,
		$str = "
				INSERT INTO POLLING_PEGAWAI (
				   POLLING_PEGAWAI_ID, PEGAWAI_ID, POLLING_DETIL_ID, 
				   LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( 
				 '".$this->getField("POLLING_PEGAWAI_ID")."',
				 '".$this->getField("PEGAWAI_ID")."',
				 '".$this->getField("POLLING_DETIL_ID")."',
				 '".$this->getField("LAST_CREATE_USER")."',
				 ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id = $this->getField("POLLING_PEGAWAI_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE POLLING_PEGAWAI
				SET    PEGAWAI_ID    = '".$this->getField("PEGAWAI_ID")."',
				       POLLING_DETIL_ID    		 = '".$this->getField("POLLING_DETIL_ID")."',
				       LAST_UPDATE_USER    = '".$this->getField("LAST_UPDATE_USER")."',
				       LAST_UPDATE_DATE  = ".$this->getField("LAST_UPDATE_DATE")."
				WHERE  POLLING_PEGAWAI_ID    = '".$this->getField("POLLING_PEGAWAI_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM POLLING_PEGAWAI
                WHERE 
                  POLLING_PEGAWAI_ID = ".$this->getField("POLLING_PEGAWAI_ID").""; 
				  
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
				POLLING_PEGAWAI_ID, PEGAWAI_ID, POLLING_DETIL_ID, 
				   LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
				   UPDATE_DATE
				FROM POLLING_PEGAWAI A
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
				POLLING_PEGAWAI_ID, PEGAWAI_ID, POLLING_DETIL_ID, 
				   LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
				   UPDATE_DATE
				FROM POLLING_PEGAWAI A
				WHERE 1 = 1
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
		$str = "SELECT COUNT(POLLING_PEGAWAI_ID) AS ROWCOUNT FROM POLLING_PEGAWAI A
		        WHERE POLLING_PEGAWAI_ID IS NOT NULL ".$statement; 
		
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

    function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(POLLING_PEGAWAI_ID) AS ROWCOUNT FROM POLLING_PEGAWAI A
				LEFT JOIN POLLING_DETIL B ON A.POLLING_DETIL_ID = B.POLLING_DETIL_ID
		        WHERE POLLING_PEGAWAI_ID IS NOT NULL ".$statement; 
		
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
		$str = "SELECT COUNT(POLLING_PEGAWAI_ID) AS ROWCOUNT FROM POLLING_PEGAWAI A
		        WHERE POLLING_PEGAWAI_ID IS NOT NULL ".$statement; 
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