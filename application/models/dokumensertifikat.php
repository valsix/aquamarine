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

  class DokumenSertifikat  extends Entity{

	var $query;
    /**
    * Class constructor.
    **/
    function DokumenSertifikat()
	{
		
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("DOKUMEN_SERTIFIKAT_ID", $this->getNextId("DOKUMEN_SERTIFIKAT_ID","DOKUMEN_SERTIFIKAT")); 

    	$str = "INSERT INTO DOKUMEN_SERTIFIKAT (DOKUMEN_SERTIFIKAT_ID, DOKUMEN_ID, NAME, CERTIFICATE_ID, ISSUE_DATE, EXPIRED_DATE)VALUES (
    	'".$this->getField("DOKUMEN_SERTIFIKAT_ID")."',
    	".$this->getField("DOKUMEN_ID").",
    	'".$this->getField("NAME")."',
    	".$this->getField("CERTIFICATE_ID").",
    	".$this->getField("ISSUE_DATE").",
    	".$this->getField("EXPIRED_DATE")."
    )";

    $this->id = $this->getField("DOKUMEN_SERTIFIKAT_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}


	function update()
	{
		$str = "
		UPDATE DOKUMEN_SERTIFIKAT
		SET    
		DOKUMEN_SERTIFIKAT_ID ='".$this->getField("DOKUMEN_SERTIFIKAT_ID")."',
		DOKUMEN_ID = ".$this->getField("DOKUMEN_ID").",
		NAME ='".$this->getField("NAME")."',
		CERTIFICATE_ID =".$this->getField("CERTIFICATE_ID").",
		ISSUE_DATE =".$this->getField("ISSUE_DATE").",
		EXPIRED_DATE =".$this->getField("EXPIRED_DATE")."
		WHERE DOKUMEN_SERTIFIKAT_ID= '".$this->getField("DOKUMEN_SERTIFIKAT_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function updateLampiran()
	{
		$str = "
		UPDATE DOKUMEN_SERTIFIKAT
		SET    
		LAMPIRAN ='".$this->getField("LAMPIRAN")."'
		
		WHERE DOKUMEN_SERTIFIKAT_ID= '".$this->getField("DOKUMEN_SERTIFIKAT_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}


	function deleteParent()
	{
		$str = "DELETE FROM DOKUMEN_SERTIFIKAT
		WHERE DOKUMEN_ID= '".$this->getField("DOKUMEN_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function delete()
	{
		$str = "DELETE FROM DOKUMEN_SERTIFIKAT
		WHERE DOKUMEN_SERTIFIKAT_ID= '".$this->getField("DOKUMEN_SERTIFIKAT_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.DOKUMEN_SERTIFIKAT_ID ASC")
	{
		$str = "
		SELECT A.DOKUMEN_SERTIFIKAT_ID,A.DOKUMEN_ID,A.NAME,A.CERTIFICATE_ID,A.ISSUE_DATE,A.EXPIRED_DATE,C.CERTIFICATE,A.LAMPIRAN
		FROM DOKUMEN_SERTIFIKAT A
		LEFT JOIN PERSONAL_CERTIFICATE C ON C.CERTIFICATE_ID = A.CERTIFICATE_ID
		WHERE 1=1 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOKUMEN_ID DESC")
    {
        $str = "
        	SELECT A.DOKUMEN_ID, A.NAME , TO_CHAR(A.ISSUE_DATE, 'DAY,MONTH DD YYYY') AS ISSUED_DATE, 
        		TO_CHAR(COALESCE(A.EXPIRED_DATE, A.ISSUE_DATE), 'DAY,MONTH DD YYYY') AS EXPIRED_DATE, 
        		TO_CHAR(COALESCE(A.EXPIRED_DATE, A.ISSUE_DATE), 'DD-MM-YYYY') AS EXPIRED_DATE2, 
        		A.EXPIRED_DATE DATES,A.CERTIFICATE_ID,C.CERTIFICATE,
        		CASE WHEN COALESCE(A.EXPIRED_DATE, A.ISSUE_DATE) < CURRENT_DATE
					THEN 'EXPIRED'
					ELSE ''
				END IS_EXPIRED
            FROM DOKUMEN_SERTIFIKAT A
            LEFT JOIN DOKUMEN_KUALIFIKASI B ON B.DOCUMENT_ID = A.DOKUMEN_ID
            LEFT JOIN PERSONAL_CERTIFICATE C ON C.CERTIFICATE_ID = A.CERTIFICATE_ID
            WHERE 1 = 1
        ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }


	function getIds($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.DOKUMEN_SERTIFIKAT_ID ASC")
	{
		$str = "
		SELECT A.DOKUMEN_SERTIFIKAT_ID,A.DOKUMEN_ID,A.NAME,A.CERTIFICATE_ID,A.ISSUE_DATE,A.EXPIRED_DATE,C.CERTIFICATE
		FROM DOKUMEN_SERTIFIKAT A
		WHERE 1=1 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}

	
	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM DOKUMEN_SERTIFIKAT A WHERE 1=1 ".$statement;
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

	function getCountByParamsExpired($paramsArray=array(), $statement="")
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT
			FROM DOKUMEN_KUALIFIKASI A 
			WHERE EXISTS (
				SELECT 1 
				FROM DOKUMEN_SERTIFIKAT X 
				WHERE A.DOCUMENT_ID = X.DOKUMEN_ID 
				AND X.EXPIRED_DATE < CURRENT_DATE + INTERVAL '3 MONTH'
			) 
			".$statement;
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
