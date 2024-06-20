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
  * Entity-base class untuk mengimplementasikan tabel FORUM.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class Forum extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Forum()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FORUM_ID", $this->getNextId("FORUM_ID","FORUM"));

		$str = "
					INSERT INTO FORUM (
					   FORUM_ID, NAMA, FORUM_KATEGORI_ID, PEGAWAI_ID, TANGGAL, KETERANGAN, STATUS, LAST_CREATE_USER, LAST_CREATE_DATE)
 			  	VALUES (
				  '".$this->getField("FORUM_ID")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("FORUM_KATEGORI_ID")."',
				  '".$this->getField("PEGAWAI_ID")."',
				  ".$this->getField("TANGGAL").",
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("STATUS")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_TIMESTAMP
				)"; 
		$this->id = $this->getField("FORUM_ID");
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE FORUM
				SET    
					   NAMA          		= '".$this->getField("NAMA")."',
					   FORUM_KATEGORI_ID 	= '".$this->getField("FORUM_KATEGORI_ID")."',
					   PEGAWAI_ID 			= '".$this->getField("PEGAWAI_ID")."',
					   TANGGAL 				= ".$this->getField("TANGGAL").",
					   KETERANGAN 			= '".$this->getField("KETERANGAN")."',
					   STATUS 				= '".$this->getField("STATUS")."',
					   LAST_UPDATE_USER 	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE 	= ".$this->getField("LAST_UPDATE_DATE")."
				WHERE  FORUM_ID     		= '".$this->getField("FORUM_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM FORUM
                WHERE 
                  FORUM_ID = ".$this->getField("FORUM_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
					SELECT
						A.FORUM_ID,
						A.NAMA JUDUL,
						A.KETERANGAN ISI,
						FORUM_KATEGORI_ID,
						B.NAMA,
						TO_CHAR( A.TANGGAL, 'YYYY-MM-DD HH24:MI:SS' ) TANGGAL,
						A.STATUS,
						COUNT(C.FORUM_DETIL_ID) AS COMMENTS
					FROM
						FORUM A 
						LEFT JOIN PEGAWAI B ON A.PEGAWAI_ID = B.NIP 
						LEFT JOIN FORUM_DETIL C ON A.FORUM_ID = C.FORUM_ID
					WHERE
						A.FORUM_ID IS NOT NULL
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " GROUP BY A.FORUM_ID, A.NAMA, A.KETERANGAN, FORUM_KATEGORI_ID, B.NAMA, A.TANGGAL, A.STATUS";
		$str .= $statement." ORDER BY A.TANGGAL DESC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	SELECT 
					FORUM_ID, NAMA, FORUM_KATEGORI_ID, PEGAWAI_ID, TANGGAL, KETERANGAN, STATUS
					FROM FORUM WHERE FORUM_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(FORUM_ID) AS ROWCOUNT FROM FORUM
		        WHERE FORUM_ID IS NOT NULL ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(FORUM_ID) AS ROWCOUNT FROM FORUM
		        WHERE FORUM_ID IS NOT NULL ".$statement; 
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