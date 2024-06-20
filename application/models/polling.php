<? 
/* *******************************************************************************************************
MODUL NAME 			: E LEARNING
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel KontakPegawai.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');
  
  class Polling extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Polling()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("POLLING_ID", $this->getNextId("POLLING_ID","POLLING")); 		
		$str = "		
				INSERT INTO POLLING (POLLING_ID, NAMA, KETERANGAN, TANGGAL_AWAL, TANGGAL_AKHIR, STATUS, 
					   LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("POLLING_ID")."', 
						'".$this->getField("NAMA")."', 
						'".$this->getField("KETERANGAN")."', 
						".$this->getField("TANGGAL_AWAL").",
						".$this->getField("TANGGAL_AKHIR").", 
						'".$this->getField("STATUS")."',					 
						'".$this->getField("LAST_CREATE_USER")."',
						CURRENT_DATE	
				)
				"; 
		$this->query = $str;
		$this->id = $this->getField("POLLING_ID");
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE POLLING
				SET    NAMA         = '".$this->getField("NAMA")."',
					   KETERANGAN   = '".$this->getField("KETERANGAN")."',
					   TANGGAL_AWAL = ".$this->getField("TANGGAL_AWAL").",
					   TANGGAL_AKHIR = ".$this->getField("TANGGAL_AKHIR").",
					   LAST_UPDATE_DATE = CURRENT_DATE,
					   LAST_UPDATE_USER   = '".$this->getField("LAST_UPDATE_USER")."',
					   STATUS   = '".$this->getField("STATUS")."'
				WHERE  POLLING_ID = '".$this->getField("POLLING_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE POLLING A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE POLLING_ID = '".$this->getField("POLLING_ID")."'
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }		

	function delete()
	{
		$str1= "DELETE FROM POLLING_DETIL
                WHERE 
                  POLLING_ID = ".$this->getField("POLLING_ID").""; 
				  
		$this->query = $str1;
        $this->execQuery($str1);

        $str = "DELETE FROM POLLING
                WHERE 
                  POLLING_ID = ".$this->getField("POLLING_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","BERITA_METODE_EVALUASI_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY POLLING_ID ASC')
	{
		$str = "
		SELECT 
		POLLING_ID, NAMA, KETERANGAN 
		, TO_CHAR(TANGGAL_AWAL, 'YYYY-MM-DD') TANGGAL_AWAL, TO_CHAR(TANGGAL_AKHIR, 'YYYY-MM-DD') TANGGAL_AKHIR
		, STATUS, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE
		FROM POLLING A
		WHERE 1 = 1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY POLLING_ID ASC', $pegawaiId='')
	{
		$str = "
		SELECT 
		POLLING_ID, NAMA, KETERANGAN 
		, TO_CHAR(TANGGAL_AWAL, 'YYYY-MM-DD') TANGGAL_AWAL, TO_CHAR(TANGGAL_AKHIR, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_AKHIR
		, STATUS, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE,
		(SELECT COUNT(POLLING_PEGAWAI_ID) AS ROWCOUNT FROM POLLING_PEGAWAI AA
				LEFT JOIN POLLING_DETIL BB ON AA.POLLING_DETIL_ID = BB.POLLING_DETIL_ID
		        WHERE POLLING_PEGAWAI_ID IS NOT NULL AND POLLING_ID = A.POLLING_ID AND PEGAWAI_ID = '".$pegawaiId."') STATUS_VOTE
		FROM POLLING A
		WHERE 1 = 1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		// echo $str; exit();
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsDetil($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				SELECT A.POLLING_ID, A.NAMA,
				       AMBIL_JUMLAH_VOTE_POLLING (A.POLLING_ID) JUMLAH_VOTE,
				       TO_CHAR(A.TANGGAL_AWAL, 'YYYY-MM-DD') TANGGAL_AWAL, 
				       TO_CHAR(A.TANGGAL_AKHIR, 'YYYY-MM-DD') TANGGAL_AKHIR, 
				       B.POLLING_DETIL_ID, B.NAMA NAMA_PILIHAN,
				       AMBIL_JUMLAH_POLLING_PILIHAN(B.POLLING_DETIL_ID) JUMLAH
				  FROM POLLING A
				  LEFT JOIN POLLING_DETIL B ON A.POLLING_ID = B.POLLING_ID
				  WHERE 1=1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	
    function selectByParamsEntri($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				SELECT POLLING_ID, NAMA, KETERANGAN, TO_CHAR(TANGGAL_AWAL, 'DD-MM-YYYY HH24:MI:SS')TANGGAL_AWAL, 
					   TO_CHAR(TANGGAL_AKHIR, 'DD-MM-YYYY HH24:MI:SS')TANGGAL_AKHIR, STATUS, 
					   LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE
				  FROM POLLING  A
				  WHERE 1=1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	 
	 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(POLLING_ID) AS ROWCOUNT 
					FROM    POLLING A
					WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		// echo $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

	function getCountByParamsAktivitas($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(KULIAH_AKTIVITAS_UJIAN_ID) AS ROWCOUNT 
					FROM    KULIAH_AKTIVITAS_UJIAN_BANK A
					WHERE 1 = 1 ".$statement; 
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
  } 
?>