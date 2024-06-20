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

  class Pengurus extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Pengurus()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PENGURUS_ID", $this->getNextId("PENGURUS_ID","PENGURUS"));
		$str = "INSERT INTO PENGURUS(
            PENGURUS_ID, CABANG_ID, URUT, NIP, NAMA, JABATAN_PENGURUS, TANGGAL_MULAI, 
            TANGGAL_AKHIR, CREATED_BY, CREATED_DATE)
			VALUES ('".$this->getField("PENGURUS_ID")."', '".$this->getField("CABANG_ID")."', '".$this->getField("URUT")."', '".$this->getField("NIP")."', 
			'".$this->getField("NAMA")."', '".$this->getField("JABATAN_PENGURUS")."', ".$this->getField("TANGGAL_MULAI").", 
            ".$this->getField("TANGGAL_AKHIR").", '".$this->getField("CREATED_BY")."',CURRENT_DATE)"; 
		$this->query = $str;
		$this->id = $this->getField("PENGURUS_ID");
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PENGURUS A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
						   LAST_UPDATED_USER  = '".$this->getField("LAST_UPDATED_USER")."',
						   LAST_UPDATED_DATE  = CURRENT_DATE
				WHERE PENGURUS_ID = ".$this->getField("PENGURUS_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

    function update()
	{
		$str = "
				UPDATE PENGURUS
				SET 
				CABANG_ID='".$this->getField("CABANG_ID")."', 
				URUT='".$this->getField("URUT")."', 
				NIP='".$this->getField("NIP")."', 
				NAMA='".$this->getField("NAMA")."', 
				JABATAN_PENGURUS='".$this->getField("JABATAN_PENGURUS")."', 
				TANGGAL_MULAI=".$this->getField("TANGGAL_MULAI").", 
				TANGGAL_AKHIR=".$this->getField("TANGGAL_AKHIR").", 
				UPDATED_BY='".$this->getField("UPDATED_BY")."', 
				UPDATED_DATE=CURRENT_DATE
				WHERE  PENGURUS_ID = '".$this->getField("PENGURUS_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	
    function updateLinkPengurus()
	{
		$str = "
				UPDATE PENGURUS
				SET   	   LINK_FILE        = '".$this->getField("LINK_FILE")."'
				WHERE  PENGURUS_ID = '".$this->getField("PENGURUS_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function updateDetil()
	{
		$str = "
				UPDATE PENGURUS_DETIL
				SET   	   NAMA        = '".$this->getField("NAMA")."'
				WHERE  PENGURUS_DETIL_ID = '".(int)$this->getField("PENGURUS_DETIL_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM PENGURUS
                WHERE 
                  PENGURUS_ID = ".$this->getField("PENGURUS_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.PENGURUS_ID ASC ")
	{
		$str = "SELECT PENGURUS_ID, CABANG_ID, URUT, NIP, NAMA, JABATAN_PENGURUS JABATAN, JABATAN_PENGURUS, TANGGAL_MULAI, 
					   TANGGAL_AKHIR, CREATED_BY, CREATED_DATE, UPDATED_BY, UPDATED_DATE 
				  FROM PENGURUS A 
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
	
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.URUT ASC ")
	{
		$str = "SELECT A.PENGURUS_ID, A.URUT, A.NIP, A.NAMA, A.JABATAN, A.JABATAN_PENGURUS, A.TANGGAL_MULAI, 
					   A.TANGGAL_AKHIR, A.CREATED_BY, A.CREATED_DATE, A.UPDATED_BY, A.UPDATED_DATE,
					   B.PEGAWAI_ID, B.NO_SEKAR,B.NRP, B.NAMA_PANGGILAN, B.JENIS_KELAMIN, B.TEMPAT_LAHIR, 
					   B.TANGGAL_LAHIR, B.UNIT_KERJA, B.ALAMAT, B.NOMOR_HP, B.EMAIL_PRIBADI, B.EMAIL_BULOG, B.GOLONGAN_DARAH,
					   B.NOMOR_WA, B.FOTO
				  FROM PENGURUS A
				  LEFT JOIN PEGAWAI B ON A.NIP = B.NIP
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
	
	
	
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PENGURUS_ID) AS ROWCOUNT FROM PENGURUS

		        WHERE PENGURUS_ID IS NOT NULL ".$statement; 
		
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
		$str = "SELECT COUNT(PENGURUS_ID) AS ROWCOUNT FROM PENGURUS

		        WHERE PENGURUS_ID IS NOT NULL ".$statement; 
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