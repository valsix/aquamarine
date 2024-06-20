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

  class Notifikasi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Notifikasi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("NOTIFIKASI_ID", $this->getNextId("NOTIFIKASI_ID","NOTIFIKASI")); 		
		//'".$this->getField("FOTO")."',  FOTO,
		$str = "
				INSERT INTO NOTIFIKASI (
				  NOTIFIKASI_ID, PEGAWAI_ID, NAMA, KETERANGAN, JENIS, TYPE, DIBACA, TANGGAL
				   ) 
 			  	VALUES (
				  	".$this->getField("NOTIFIKASI_ID").",
				  	'".$this->getField("PEGAWAI_ID")."',
          			'".$this->getField("NAMA")."',
				  	'".$this->getField("KETERANGAN")."',
				  	'".$this->getField("JENIS")."',
          			'".$this->getField("TYPE")."',
				 	'T',
				  	CURRENT_TIMESTAMP
				)"; 
		$this->id = $this->getField("NOTIFIKASI_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	
	
	function insertInformasi()
	{
		$str = "
				INSERT INTO NOTIFIKASI(
					NOTIFIKASI_ID, PEGAWAI_ID, NAMA, KETERANGAN, KETERANGAN_FULL, JENIS, TYPE, TANGGAL, 
					DIBACA, PRIMARY_ID)
					SELECT  COALESCE((SELECT MAX(NOTIFIKASI_ID) FROM NOTIFIKASI), 0) + ROW_NUMBER() OVER (), B.PEGAWAI_ID, A.NAMA, SUBSTR(A.KETERANGAN, 0, 50) || '...', A.KETERANGAN, A.JENIS, 'INFORMASI'::VARCHAR, CURRENT_TIMESTAMP, 'T'::VARCHAR, SLIDER_ID
					FROM SLIDER A, 
					(SELECT DISTINCT A.PEGAWAI_ID					
					FROM USER_LOGIN_MOBILE A) B
					WHERE SLIDER_ID = ".$this->getField("SLIDER_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }


	function insertPesan()
	{
		$str = "
				INSERT INTO NOTIFIKASI(
					NOTIFIKASI_ID, PEGAWAI_ID, NAMA, KETERANGAN, KETERANGAN_FULL, JENIS, TYPE, TANGGAL, 
					DIBACA, PRIMARY_ID)
					SELECT  COALESCE((SELECT MAX(NOTIFIKASI_ID) FROM NOTIFIKASI), 0) + ROW_NUMBER() OVER (), B.PEGAWAI_ID, A.NAMA, SUBSTR(A.KETERANGAN, 0, 50) || '...', A.KETERANGAN, A.JENIS, 'PESAN'::VARCHAR, CURRENT_TIMESTAMP, 'T'::VARCHAR, SLIDER_ID
					FROM SLIDER A, 
					(SELECT DISTINCT A.PEGAWAI_ID					
					FROM USER_LOGIN_MOBILE A) B
					WHERE SLIDER_ID = ".$this->getField("SLIDER_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		$str = "UPDATE public.NOTIFIKASI
                SET DIBACA = 'Y'
                WHERE 
                  NOTIFIKASI_ID = ".$this->getField("NOTIFIKASI_ID").""; 
          
		$this->query = $str;
		return $this->execQuery($str);
    }

    function read()
	{
		$str = "UPDATE public.NOTIFIKASI
                SET DIBACA = 'Y'
                WHERE 
                  NOTIFIKASI_ID = ".$this->getField("NOTIFIKASI_ID").""; 
          
		$this->query = $str;
		return $this->execQuery($str);
    }

    function readBySlider()
	{
		$str = "UPDATE public.NOTIFIKASI
                SET DIBACA = 'Y'
                WHERE 
                  PRIMARY_ID = '".$this->getField("PRIMARY_ID")."'
                  AND PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."'
                "; 
          
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM public.NOTIFIKASI
                WHERE 
                  NOTIFIKASI_ID = ".$this->getField("NOTIFIKASI_ID").""; 
          
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
    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "") {
        $str = "
              SELECT NOTIFIKASI_ID, PEGAWAI_ID, NAMA, KETERANGAN, KETERANGAN_FULL, JENIS, TYPE, DIBACA, TO_CHAR(TANGGAL, 'YYYY-MM-DD HH24:MI:SS') TANGGAL, PRIMARY_ID
              FROM public.NOTIFIKASI
                where 1=1
          ";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }
        $str .= $statement . " ORDER BY DIBACA ASC, TANGGAL DESC";
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{

		$str = "SELECT COUNT(NOTIFIKASI_ID) AS ROWCOUNT FROM public.NOTIFIKASI A WHERE 1 = 1 ".$statement; 
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