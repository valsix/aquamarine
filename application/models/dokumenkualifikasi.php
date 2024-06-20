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

  class DokumenKualifikasi  extends Entity{

	var $query;
    /**
    * Class constructor.
    **/
    function DokumenKualifikasi()
	{
		
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("DOCUMENT_ID", $this->getNextId("DOCUMENT_ID","DOKUMEN_KUALIFIKASI")); 

    	$str = "INSERT INTO DOKUMEN_KUALIFIKASI (DOCUMENT_ID, JENIS_ID, NAME, DESCRIPTION, PATH, LAST_REVISI,        CERTIFICATE_ID, ADDRESS, BIRTH_DATE, PHONE, PHONE2, REMARKS)VALUES (
    	'".$this->getField("DOCUMENT_ID")."',
    	'".$this->getField("JENIS_ID")."',
    	'".$this->getField("NAME")."',
    	'".$this->getField("DESCRIPTION")."',
    	'".$this->getField("PATH")."',
    	NULL,
    	NULL,
    	'".$this->getField("ADDRESS")."',
      	".$this->getField("BIRTH_DATE").",
    	'".$this->getField("PHONE")."',
    	'".$this->getField("PHONE2")."',
    	'".$this->getField("REMARKS")."' 
    )";

    $this->id = $this->getField("DOCUMENT_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}



	function updateListCertificate(){
		$str = "
		UPDATE DOKUMEN_KUALIFIKASI
		SET    
		
		LIST_CERTIFICATE ='".$this->getField("LIST_CERTIFICATE")."'
		
		WHERE DOCUMENT_ID= '".$this->getField("DOCUMENT_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}
  

	function update_contact()
	{
		$str = "
		UPDATE DOKUMEN_KUALIFIKASI
		SET    
		
		NO_REKENING ='".$this->getField("NO_REKENING")."'
		
		WHERE DOCUMENT_ID= '".$this->getField("DOCUMENT_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function update_path()
	{
		$str = "
		UPDATE DOKUMEN_KUALIFIKASI
		SET    
		
		PATH ='".$this->getField("PATH")."'
		
		WHERE DOCUMENT_ID= '".$this->getField("DOCUMENT_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}
	function updateIdNumber()
	{
		$str = "
		UPDATE DOKUMEN_KUALIFIKASI
		SET    
		
		ID_NUMBER ='".$this->getField("ID_NUMBER")."'
		
		WHERE DOCUMENT_ID= '".$this->getField("DOCUMENT_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function update_tambahan(){
		$str = "
		UPDATE DOKUMEN_KUALIFIKASI
		SET    
		ID_NUMBER  ='".$this->getField("ID_NUMBER")."',
		ID_CARD    ='".$this->getField("ID_CARD")."',
		CABANG_ID  =".$this->getField("CABANG_ID")."
		
		WHERE DOCUMENT_ID= '".$this->getField("DOCUMENT_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE DOKUMEN_KUALIFIKASI
		SET    
		DOCUMENT_ID ='".$this->getField("DOCUMENT_ID")."',
		JENIS_ID ='".$this->getField("JENIS_ID")."',
		NAME ='".$this->getField("NAME")."',
		DESCRIPTION ='".$this->getField("DESCRIPTION")."',
		PATH ='".$this->getField("PATH")."',
		LAST_REVISI =	NULL,	
		CERTIFICATE_ID =NULL,
		ADDRESS ='".$this->getField("ADDRESS")."',
		BIRTH_DATE =".$this->getField("BIRTH_DATE").",
		PHONE ='".$this->getField("PHONE")."',
		PHONE2 ='".$this->getField("PHONE2")."',
		REMARKS ='".$this->getField("REMARKS")."' 
		WHERE DOCUMENT_ID= '".$this->getField("DOCUMENT_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM DOKUMEN_KUALIFIKASI
		WHERE DOCUMENT_ID= '".$this->getField("DOCUMENT_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.DOCUMENT_ID ASC")
	{
		$str = "
		SELECT A.DOCUMENT_ID,A.JENIS_ID,A.NAME,A.DESCRIPTION,A.PATH,A.LAST_REVISI,A.CERTIFICATE_ID,A.ADDRESS,A.BIRTH_DATE,A.PHONE,A.PHONE2,A.LIST_CERTIFICATE,A.ID_NUMBER,A.ID_CARD,A.CABANG_ID,A.NO_REKENING
		FROM DOKUMEN_KUALIFIKASI A
		WHERE 1=1 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParamsMonitoringPersonil($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.DOCUMENT_ID ASC")
	{
		$str = "
		SELECT   DOCUMENT_ID, NAME ,  A.DESCRIPTION, A.PATH, A.ADDRESS, A.BIRTH_DATE, A.PHONE, A.PHONE2,A.LIST_CERTIFICATE, A.REMARKS,
		B.JENIS_ID AS POSITION,B.JENIS AS POSITION_NAMA,A.ID_NUMBER,A.ID_CARD,A.CABANG_ID,A.NO_REKENING
		FROM DOKUMEN_KUALIFIKASI A
		INNER JOIN JENIS_KUALIFIKASI B ON A.JENIS_ID = B.JENIS_ID
		
		WHERE
		
		1=1 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}
	function getCountByParamsMonitoringPersonil($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM DOKUMEN_KUALIFIKASI A, JENIS_KUALIFIKASI B WHERE 1=1 AND A.JENIS_ID = B.JENIS_ID   ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = 	'$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}

	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM DOKUMEN_KUALIFIKASI A WHERE 1=1 ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = 	'$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}
  }
?>
