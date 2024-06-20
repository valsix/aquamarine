<? 
/* *******************************************************************************************************
MODUL NAME 			: E - OFFICE BULOG
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

  class EntitasDetail extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Entitas()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("ENTITAS_DETAIL_ID", $this->getNextId("ENTITAS_DETAIL_ID","ENTITAS_DETAIL"));  
		$str = "
				INSERT INTO ENTITAS_DETAIL(
            ENTITAS_DETAIL_ID,NAMA,ALAMAT, ENTITAS_ID)

    VALUES (
    				  '".$this->getField("ENTITAS_DETAIL_ID")."',
					  '".$this->getField("NAMA")."',
					   '".$this->getField("ALAMAT")."',
					  '".$this->getField("ENTITAS_ID")."'

				)"; 
		$this->id = $this->getField("ENTITAS_DETAIL_ID");

		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE ENTITAS
			   SET 
			   KODE 			= '".$this->getField("KODE")."', 
			   NAMA				= '".$this->getField("NAMA")."',
			   KETERANGAN		= '".$this->getField("KETERANGAN")."', 
			   ALAMAT 			= '".$this->getField("ALAMAT")."',
			   LOKASI 			= '".$this->getField("LOKASI")."', 
			   TELEPON			= '".$this->getField("TELEPON")."',
			   FAXIMILE 		= '".$this->getField("FAXIMILE")."', 
			   EMAIL			= '".$this->getField("EMAIL")."', 
			   STATUS_ENTITAS	= '".$this->getField("STATUS_ENTITAS")."',
			   LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."', 
			   LAST_UPDATE_DATE		= ".$this->getField("LAST_UPDATE_DATE")."
			   WHERE ENTITAS_DETAIL_ID = '".$this->getField("ENTITAS_DETAIL_ID")."';
 				"; 
				//echo $str;
				$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM ENTITAS
                WHERE 
				  ENTITAS_DETAIL_ID= '".$this->getField("ENTITAS_DETAIL_ID")."'
				  "; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT ENTITAS_DETAIL_ID, KODE, NAMA, KETERANGAN, ALAMAT, LOKASI, TELEPON, 
            FAXIMILE, EMAIL, STATUS_ENTITAS, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
            LAST_UPDATE_DATE
				  FROM ENTITAS WHERE 1=1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT ENTITAS_DETAIL_ID, KODE, NAMA, KETERANGAN, ALAMAT, LOKASI, TELEPON, 
            FAXIMILE, EMAIL, STATUS_ENTITAS, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
            LAST_UPDATE_DATE
				  FROM ENTITAS A WHERE 1=1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT FROM ENTITAS A WHERE 1=1
		 ".$statement; 
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
		$str = "
		SELECT COUNT(1) AS ROWCOUNT FROM ENTITAS A WHERE 1=1
		 ".$statement; 
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
  } 
?>