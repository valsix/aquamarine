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
  * Entity-base class untuk mengimplementasikan tabel KAPAL_JENIS.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class PegawaiSppd extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PegawaiSppd()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PEGAWAI_ID", $this->getNextId("PEGAWAI_ID","DATA_SPPD"));

		$str = "
				INSERT INTO DATA_SPPD (
				   	PEGAWAI_ID, TANGGAL, MULAI_TANGGAL, SAMPAI_TANGGAL,
					ASAL_KOTA, TUJUAN_KOTA, LAMA, STATUS,
					MAKSUD, KET_STATUS, BIAYA_TOTAL) 
				VALUES ( '".$this->getField("PEGAWAI_ID")."', '".$this->getField("TANGGAL")."', '".$this->getField("MULAI_TANGGAL")."',	'".$this->getField("SAMPAI_TANGGAL")."', '".$this->getField("ASAL_KOTA")."', '".$this->getField("TUJUAN_KOTA")."',".$this->getField("LAMA")."',".$this->getField("STATUS")."',".$this->getField("MAKSUD")."',".$this->getField("KET_STATUS")."',".$this->getField("BIAYA_TOTAL")." )
				"; 
		$this->id = $this->getField("PEGAWAI_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE DATA_SPPD
				SET    PEGAWAI_ID           = '".$this->getField("PEGAWAI_ID")."',
					   TANGGAL 				= '".$this->getField("TANGGAL")."',
					   MULAI_TANGGAL        = '".$this->getField("MULAI_TANGGAL")."',
					   SAMPAI_TANGGAL       = '".$this->getField("SAMPAI_TANGGAL")."',
					   ASAL_KOTA     		= '".$this->getField("ASAL_KOTA")."',
					   TUJUAN_KOTA    	 	= ".$this->getField("TUJUAN_KOTA")."
					   LAMA     			= ".$this->getField("LAMA")."
					   STATUS     			= ".$this->getField("STATUS")."
					   MAKSUD     			= ".$this->getField("MAKSUD")."
					   KET_STATUS     		= ".$this->getField("KET_STATUS")."
					   BIAYA_TOTAL     		= ".$this->getField("BIAYA_TOTAL")."
				WHERE  PEGAWAI_ID        	= '".$this->getField("PEGAWAI_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM DATA_SPPD
                WHERE 
                  PEGAWAI_ID = ".$this->getField("PEGAWAI_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY MULAI_TANGGAL ASC")
	{
		$str = "
				SELECT 
				PEGAWAI_ID, TANGGAL, MULAI_TANGGAL, SAMPAI_TANGGAL,
				ASAL_KOTA, TUJUAN_KOTA, LAMA, STATUS,
				MAKSUD, KET_STATUS, BIAYA_TOTAL
				FROM DATA_SPPD
				WHERE 1=1
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
    
	 
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	
				  SELECT PEGAWAI_ID, TANGGAL, MULAI_TANGGAL, SAMPAI_TANGGAL, MAKSUD
				  FROM DATA_SPPD                  
				  WHERE 0=0
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY MULAI_TANGGAL ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM DATA_SPPD 
		        WHERE 0=0 ".$statement; 
		
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
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM DATA_SPPD 
		        WHERE 0=0 ".$statement; 
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