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

  class Aduan extends Entity{

	var $query;
    /**
    * Class constructor.
    **/
    function Aduan()
	{
      $this->Entity();
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("ADUAN_ID", $this->getNextId("ADUAN_ID","ADUAN"));
		//'".$this->getField("FOTO")."',  FOTO,
		$str = "INSERT INTO ADUAN(
						ADUAN_ID, NIP, NAMA, ADUAN, LINK_FILE, BALASAN, CREATED_BY, CREATED_DATE)
				VALUES (
				  '".$this->getField("ADUAN_ID")."',
				  '".$this->getField("NIP")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("ADUAN")."',
				  '".$this->getField("LINK_FILE")."',
				  '".$this->getField("BALASAN")."',
				  '".$this->getField("CREATED_BY")."',
				  CURRENT_DATE  ) ";
		$this->id = $this->getField("ADUAN_ID");
		$this->query = $str;
		//echo $str;exit();
		return $this->execQuery($str);
    }


    function update()
	{
		$str = " UPDATE ADUAN
				   SET
				    BALASAN='".$this->getField("BALASAN")."',
				  	BALASAN_BY='".$this->getField("UPDATE_BY")."',
				  	BALASAN_DATE= CURRENT_DATE
 					WHERE  ADUAN_ID='".$this->getField("ADUAN_ID")."'
			 ";
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{

    //apa ini
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "    UPDATE aduan A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
						   UPDATE_BY  = '".$this->getField("UPDATE_BY")."',
						   UPDATE_DATE  = CURRENT_DATE
				WHERE ADUAN_ID = ".$this->getField("ADUAN_ID")."
				";
				$this->query = $str;

		return $this->execQuery($str);
    }


	function delete()
	{
        $str = "DELETE FROM aduan
                WHERE
                  aduan_id = ".$this->getField("ADUAN_ID")."";

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
		$str = "	SELECT ADUAN_ID, NIP, NAMA, ADUAN, LINK_FILE, BALASAN, CREATED_BY, CREATED_DATE,
       UPDATE_BY, UPDATE_DATE, BALASAN_BY, BALASAN_DATE
					  FROM ADUAN A
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

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "	SELECT A.ADUAN_ID, A.NIP, A.NAMA, A.ADUAN, A.LINK_FILE, A.BALASAN, A.CREATED_BY, A.CREATED_DATE,
       A.UPDATE_BY, A.UPDATE_DATE, A.BALASAN_BY, A.BALASAN_DATE, A.NIP || ' - ' || B.NAMA NIP_NAMA
					  FROM ADUAN A INNER JOIN PEGAWAI B ON A.NIP = B.NIP
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
		$str = "SELECT COUNT(ADUAN_ID) AS ROWCOUNT FROM aduan A
		        WHERE ADUAN_ID IS NOT NULL ".$statement;

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
		$str = "SELECT COUNT(ADUAN_ID) AS ROWCOUNT 
					  FROM ADUAN A INNER JOIN PEGAWAI B ON A.NIP = B.NIP
				WHERE 1 = 1 ".$statement;

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
		$str = "SELECT COUNT(ADUAN_ID) AS ROWCOUNT FROM aduan A
		        WHERE ADUAN_ID IS NOT NULL ".$statement;
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
