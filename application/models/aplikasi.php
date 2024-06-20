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
  
  class Aplikasi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Aplikasi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("APLIKASI_ID", $this->getNextId("APLIKASI_ID","APLIKASI")); 		
		//'".$this->getField("FOTO")."',  FOTO,
		$str = "
				INSERT INTO APLIKASI (
				   APLIKASI_ID, URUT, KETERANGAN, NAMA, LINK_FILE, LINK_URL, JENIS,
				   LAST_CREATE_USER, LAST_CREATE_DATE)
   				VALUES (
				  '".$this->getField("APLIKASI_ID")."',
				  '".$this->getField("URUT")."',
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("LINK_FILE")."',
				  '".$this->getField("LINK_URL")."',
				  '".$this->getField("JENIS")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_TIMESTAMP
				)"; 
		$this->id = $this->getField("APLIKASI_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE APLIKASI
				SET    URUT    		 = '".$this->getField("URUT")."',
					   KETERANGAN   		 = '".$this->getField("KETERANGAN")."',
					   NAMA    				 = '".$this->getField("NAMA")."',
					   LINK_FILE    			 = '".$this->getField("LINK_FILE")."',
					   LINK_URL    			 = '".$this->getField("LINK_URL")."',
					   JENIS    			 = '".$this->getField("JENIS")."'
				WHERE  APLIKASI_ID    = '".$this->getField("APLIKASI_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE APLIKASI A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
						   LAST_UPDATE_USER  = '".$this->getField("LAST_UPDATE_USER")."',
						   LAST_UPDATE_DATE  = CURRENT_DATE
				WHERE APLIKASI_ID = ".$this->getField("APLIKASI_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	


	function delete()
	{
        $str = "DELETE FROM APLIKASI
                WHERE 
                  APLIKASI_ID = ".$this->getField("APLIKASI_ID").""; 
				  
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
				APLIKASI_ID, URUT, KETERANGAN, NAMA, LINK_FILE, LINK_URL, JENIS,  STATUS_PUBLISH, 
				   LAST_CREATE_USER
				FROM APLIKASI A
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
    
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(APLIKASI_ID) AS ROWCOUNT FROM APLIKASI A
		        WHERE APLIKASI_ID IS NOT NULL ".$statement; 
		
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
		$str = "SELECT COUNT(APLIKASI_ID) AS ROWCOUNT FROM APLIKASI A
		        WHERE APLIKASI_ID IS NOT NULL ".$statement; 
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