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

  class Aduan_tes extends Entity{

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
		$this->setField("aduan_id", $this->getNextId("aduan_id","aduan"));
		//'".$this->getField("FOTO")."',  FOTO,
		$str = "INSERT INTO ADUAN(ADUAN_ID, NIP, NAMA, ADUAN, LINK_FILE, BALASAN, CREATED_BY, CREATED_DATE)
    VALUES (
          '".$this->getField("ADUAN_ID")."',
          '".$this->getField("NIP")."',
          '".$this->getField("NAMA")."',
          '".$this->getField("ADUAN")."',
          '".$this->getField("LINK_FILE")."',
          '".$this->getField("BALASAN")."',
          '".$this->getField("CREATED_BY")."',
              CURRENT_DATE
    )";
		$this->id = $this->getField("aduan_id");
		$this->query = $str;
		echo $str;exit();
		return $this->execQuery($str);
    }


    function update()
	{

    $str = "UPDATE aduan
   SET
    nip         = '".$this->getField("NIP")."',
    nama        =  '".$this->getField("NAMA")."',
    aduan       = '".$this->getField("ADUAN")."',
    link_file   = '".$this->getField("LINK_FILE")."',
    update_by   = '".$this->getField("update_by")."',
    update_date = CURRENT_DATE
 WHERE aduan_id = '".$this->getField("ADUAN_ID")."';
 ";

		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{

    //apa ini
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE aduan A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
						   update_by  = '".$this->getField("update_by")."',
						   update_date  = CURRENT_DATE
				WHERE aduan_id = ".$this->getField("aduan_id")."
				";
				$this->query = $str;

		return $this->execQuery($str);
    }


	function delete()
	{
        $str = "DELETE FROM aduan
                WHERE
                  aduan_id = ".$this->getField("aduan_id")."";

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
		$str = "SELECT aduan_id, nip, nama, aduan, link_file, balasan, created_by, created_date,
       update_by, update_date, balasan_by, balasan_date
  FROM aduan WHERE 1 = 1	";
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
		$str = "SELECT COUNT(aduan_id) AS ROWCOUNT FROM aduan A
		        WHERE aduan_id IS NOT NULL ".$statement;

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
		$str = "SELECT COUNT(aduan_id) AS ROWCOUNT FROM aduan A
		        WHERE aduan_id IS NOT NULL ".$statement;
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
