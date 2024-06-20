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
  * Entity-base class untuk mengimplementasikan tabel FORUM_DETIL.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class ForumDetil extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function ForumDetil()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FORUM_DETIL_ID", $this->getNextId("FORUM_DETIL_ID","FORUM_DETIL"));

		$str = "
					INSERT INTO FORUM_DETIL (
					   FORUM_DETIL_ID, FORUM_ID, PEGAWAI_ID, TANGGAL, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE)
 			  	VALUES (
				  '".$this->getField("FORUM_DETIL_ID")."',
				  '".$this->getField("FORUM_ID")."',
				  '".$this->getField("PEGAWAI_ID")."',
				  ".$this->getField("TANGGAL").",
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_TIMESTAMP
				)"; 
		$this->query = $str;
		
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE FORUM_DETIL
				SET    
					   FORUM_ID          	= '".$this->getField("FORUM_ID")."'
					   PEGAWAI_ID			= '".$this->getField("PEGAWAI_ID")."',
					   TANGGAL 				= ".$this->getField("TANGGAL").",
					   KETERANGAN 			= '".$this->getField("KETERANGAN")."',
					   LAST_UPDATE_USER 	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE 	= ".$this->getField("LAST_UPDATE_DATE")."
				WHERE  FORUM_DETIL_ID     	= '".$this->getField("FORUM_DETIL_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM FORUM_DETIL
                WHERE 
                  FORUM_DETIL_ID = ".$this->getField("FORUM_DETIL_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY FORUM_DETIL_ID ASC")
	{
		$str = "
					SELECT
						FORUM_DETIL_ID,
						FORUM_ID,
						B.NAMA,
						TO_CHAR( A.TANGGAL, 'YYYY-MM-DD HH24:MI:SS' ) TANGGAL,
						A.KETERANGAN ISI
					FROM
						FORUM_DETIL
						A LEFT JOIN PEGAWAI B ON A.PEGAWAI_ID = B.NIP
					WHERE
						FORUM_DETIL_ID IS NOT NULL
				"; 
		
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
		$str = "	SELECT 
					FORUM_DETIL_ID, FORUM_ID, PEGAWAI_ID, TANGGAL, KETERANGAN
					FROM FORUM_DETIL WHERE FORUM_DETIL_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY FORUM_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(FORUM_DETIL_ID) AS ROWCOUNT FROM FORUM_DETIL
		        WHERE FORUM_DETIL_ID IS NOT NULL ".$statement; 
		
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
		$str = "SELECT COUNT(FORUM_DETIL_ID) AS ROWCOUNT FROM FORUM_DETIL
		        WHERE FORUM_DETIL_ID IS NOT NULL ".$statement; 
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