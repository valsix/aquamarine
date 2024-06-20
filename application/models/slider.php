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
  * Entity-base class untuk mengimplementasikan tabel PANGKAT.
  * 
  ***/
  include_once("Entity.php");

  class Slider extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Slider()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SLIDER_ID", $this->getNextId("SLIDER_ID","SLIDER"));
		$str = "INSERT INTO SLIDER (
				   SLIDER_ID, JENIS, NAMA, KETERANGAN, LINK_FILE, LINK_FILE_VIDEO, STATUS_PUBLISH, STATUS_NOTIFIKASI, TANGGAL, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (".$this->getField("SLIDER_ID").",
						'".$this->getField("JENIS")."',
						'".$this->getField("NAMA")."',
						'".$this->getField("KETERANGAN")."',
						'".$this->getField("LINK_FILE")."',
						'".$this->getField("LINK_FILE_VIDEO")."',
						'".$this->getField("STATUS_PUBLISH")."',
						'".$this->getField("STATUS_NOTIFIKASI")."',
						".$this->getField("TANGGAL").",
						'".$this->getField("LAST_CREATE_USER")."',
						CURRENT_DATE
						)"; 
		$this->query = $str;
		$this->id = $this->getField("SLIDER_ID");
		return $this->execQuery($str);
    }
	
	
	function insertDetil()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SLIDER_DETIL_ID", $this->getNextId("SLIDER_DETIL_ID","SLIDER_DETIL"));
		$str = "INSERT INTO SLIDER_DETIL(
						SLIDER_DETIL_ID, SLIDER_ID, NAMA, LINK_FILE, LAST_CREATE_USER, 
						LAST_CREATE_DATE)
				VALUES (".$this->getField("SLIDER_DETIL_ID").",
						'".$this->getField("SLIDER_ID")."',
						'".$this->getField("NAMA")."',
						'".$this->getField("LINK_FILE")."',
						'".$this->getField("LAST_CREATE_USER")."',
						CURRENT_DATE
						)"; 
		$this->query = $str;
		
		return $this->execQuery($str);
    }
	
	
	function insertHadir()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SLIDER_HADIR_ID", $this->getNextId("SLIDER_HADIR_ID","SLIDER_HADIR"));
		$str = "INSERT INTO SLIDER_HADIR(
						SLIDER_HADIR_ID, SLIDER_ID, PEGAWAI_ID, NAMA, CABANG, JAM, LAST_CREATE_USER, 
						LAST_CREATE_DATE)
				VALUES (".$this->getField("SLIDER_HADIR_ID").",
						'".$this->getField("SLIDER_ID")."',
						'".$this->getField("PEGAWAI_ID")."',
						'".$this->getField("NAMA")."',
						'".$this->getField("CABANG")."',
						".$this->getField("JAM").",
						'".$this->getField("LAST_CREATE_USER")."',
						CURRENT_TIMESTAMP
						)"; 
		$this->query = $str;
		
		return $this->execQuery($str);
    }


    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SLIDER A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
						   LAST_UPDATE_USER  = '".$this->getField("LAST_UPDATE_USER")."',
						   LAST_UPDATE_DATE  = CURRENT_DATE
				WHERE SLIDER_ID = ".$this->getField("SLIDER_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

    function update()
	{
		$str = "
				UPDATE SLIDER
				SET   	   NAMA        = '".$this->getField("NAMA")."',
						   LINK_FILE  = '".$this->getField("LINK_FILE")."',
						   LINK_FILE_VIDEO  = '".$this->getField("LINK_FILE_VIDEO")."',
						   KETERANGAN  = '".$this->getField("KETERANGAN")."',
						   TANGGAL  = ".$this->getField("TANGGAL").",
						   LAST_UPDATE_USER  = '".$this->getField("LAST_UPDATE_USER")."',
						   LAST_UPDATE_DATE  = CURRENT_DATE
				WHERE  SLIDER_ID = '".$this->getField("SLIDER_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	
    function updateLinkSlider()
	{
		$str = "
				UPDATE SLIDER
				SET   	   LINK_FILE        = '".$this->getField("LINK_FILE")."'
				WHERE  SLIDER_ID = '".$this->getField("SLIDER_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function updateDetil()
	{
		$str = "
				UPDATE SLIDER_DETIL
				SET   	   NAMA        = '".$this->getField("NAMA")."'
				WHERE  SLIDER_DETIL_ID = '".(int)$this->getField("SLIDER_DETIL_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM SLIDER
                WHERE 
                  SLIDER_ID = ".$this->getField("SLIDER_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	

	function deleteDetil()
	{
				  
				  
        $str = "DELETE FROM SLIDER_DETIL
                WHERE 
                  SLIDER_DETIL_ID = ".$this->getField("SLIDER_DETIL_ID").""; 
		$this->execQuery($str);
		
		/* JAGA2 KALAU ANAKNYA DIHAPUS */
		$str = " UPDATE SLIDER A SET LINK_FILE = (SELECT MAX(LINK_FILE) FROM SLIDER_DETIL X WHERE X.SLIDER_ID = A.SLIDER_ID)
				   WHERE 
				   	 SLIDER_ID = ".$this->getField("SLIDER_ID")." ";		  
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	

	function deleteKomentar()
	{
        $str = "DELETE FROM SLIDER_KOMENTAR
                WHERE 
                  SLIDER_KOMENTAR_ID = ".$this->getField("SLIDER_KOMENTAR_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.SLIDER_ID ASC ")
	{
		$str = "SELECT 
					SLIDER_ID, TANGGAL, TO_CHAR(TANGGAL, 'DD-MM-YYYY HH24:MI') TANGGAL_JAM, TO_CHAR(TANGGAL, 'DD-MM-YYYY') HARI, 
					TO_CHAR(TANGGAL, 'HH24:MI') JAM, JENIS, NAMA, LINK_FILE, LINK_FILE_VIDEO, STATUS_PUBLISH, STATUS_NOTIFIKASI, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE
					FROM SLIDER A 
				WHERE 1 = 1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	
    function selectByParamsDetil($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.SLIDER_DETIL_ID ASC ")
	{
		$str = "SELECT SLIDER_DETIL_ID, SLIDER_ID, NAMA, LINK_FILE, LAST_CREATE_USER, 
					   LAST_CREATE_DATE
				  FROM SLIDER_DETIL A 
				WHERE 1 = 1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsKomentar($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.LAST_CREATE_DATE DESC ")
	{
		$str = "SELECT SLIDER_KOMENTAR_ID, PEGAWAI_ID, NAMA, CABANG, KOMENTAR, LAST_CREATE_USER, 
					   LAST_CREATE_DATE
				  FROM SLIDER_KOMENTAR A 
				WHERE 1 = 1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	
    function selectByParamsHadir($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.JAM ASC ")
	{
		$str = "SELECT SLIDER_HADIR_ID, A.PEGAWAI_ID, B.NAMA, B.NO_SEKAR, B.UNIT_KERJA CABANG, JAM, TO_CHAR(JAM, 'DD-MM-YYYY HH24:MI:SS') JAM_HADIR, A.LAST_CREATE_USER, 
					   A.LAST_CREATE_DATE
				  FROM SLIDER_HADIR_AWAL A 
				  LEFT JOIN PEGAWAI B ON A.PEGAWAI_ID = B.NIP
				WHERE 1 = 1
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
		$str = "SELECT 
					SLIDER_ID, NAMA, LINK_FILE, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE
					FROM SLIDER
				WHERE 1 = 1
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
		$str = "SELECT COUNT(SLIDER_ID) AS ROWCOUNT FROM SLIDER

		        WHERE SLIDER_ID IS NOT NULL ".$statement; 
		
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
		$str = "SELECT COUNT(SLIDER_ID) AS ROWCOUNT FROM SLIDER

		        WHERE SLIDER_ID IS NOT NULL ".$statement; 
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