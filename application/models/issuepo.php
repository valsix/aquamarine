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

  class IssuePo  extends Entity{

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function IssuePo()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("ISSUE_PO_ID", $this->getNextId("ISSUE_PO_ID","ISSUE_PO")); 

    	$str = "INSERT INTO ISSUE_PO ( ISSUE_PO_ID, NOMER_PO, PO_DATE, DOC_LAMPIRAN, REFERENSI, PATH_LAMPIRAN,        
    			FINANCE, COMPANY_ID,TYPE_CUR, COMPANY_NAME, CONTACT, ADDRESS, EMAIL, TELP,        
    			FAX, HP, BUYER_ID, OTHER, PPN, PPN_PERCENT, CREATED_BY, CREATED_DATE,PIC, DEPARTEMENT, 
    			TERMS_AND_CONDITION, NOTE, TYPE, ACKNOWLEDGED_BY, ACKNOWLEDGED_DEPT, APPROVED1_BY, 
    			APPROVED1_DEPT, APPROVED2_BY, APPROVED2_DEPT)VALUES (
    	'".$this->getField("ISSUE_PO_ID")."',
    	'".$this->getField("NOMER_PO")."',
    	".$this->getField("PO_DATE").",
    	'".$this->getField("DOC_LAMPIRAN")."',
    	'".$this->getField("REFERENSI")."',
    	'".$this->getField("PATH_LAMPIRAN")."',
    	'".$this->getField("FINANCE")."',
    	".$this->getField("COMPANY_ID").",
    	'".$this->getField("TYPE_CUR")."',
    	'".$this->getField("COMPANY_NAME")."',
    	'".$this->getField("CONTACT")."',
    	'".$this->getField("ADDRESS")."',
    	'".$this->getField("EMAIL")."',
    	'".$this->getField("TELP")."',
    	'".$this->getField("FAX")."',
    	'".$this->getField("HP")."',
    	'".$this->getField("BUYER_ID")."',
    	'".$this->getField("OTHER")."',
    	'".$this->getField("PPN")."',
    	'".$this->getField("PPN_PERCENT")."',
    	'".$this->USERNAME."',
    	now(),
    	'".$this->getField("PIC")."',
    	'".$this->getField("DEPARTEMENT")."',
    	'".$this->getField("TERMS_AND_CONDITION")."',
    	'".$this->getField("NOTE")."',
    	'".$this->getField("TYPE")."',
    	'".$this->getField("ACKNOWLEDGED_BY")."',
    	'".$this->getField("ACKNOWLEDGED_DEPT")."',
    	'".$this->getField("APPROVED1_BY")."',
    	'".$this->getField("APPROVED1_DEPT")."',
    	'".$this->getField("APPROVED2_BY")."',
    	'".$this->getField("APPROVED2_DEPT")."'
    	
    )";

    $this->id = $this->getField("ISSUE_PO_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE ISSUE_PO
		SET    
		ISSUE_PO_ID ='".$this->getField("ISSUE_PO_ID")."',
		NOMER_PO ='".$this->getField("NOMER_PO")."',
		PO_DATE =".$this->getField("PO_DATE").",
		DOC_LAMPIRAN ='".$this->getField("DOC_LAMPIRAN")."',
		REFERENSI ='".$this->getField("REFERENSI")."',
		PATH_LAMPIRAN ='".$this->getField("PATH_LAMPIRAN")."',
		FINANCE ='".$this->getField("FINANCE")."',
		COMPANY_ID =".$this->getField("COMPANY_ID").",
		COMPANY_NAME ='".$this->getField("COMPANY_NAME")."',
		CONTACT ='".$this->getField("CONTACT")."',
		ADDRESS ='".$this->getField("ADDRESS")."',
		TYPE_CUR ='".$this->getField("TYPE_CUR")."',
		EMAIL ='".$this->getField("EMAIL")."',
		TELP ='".$this->getField("TELP")."',
		FAX ='".$this->getField("FAX")."',
		HP ='".$this->getField("HP")."',
		PIC ='".$this->getField("PIC")."',
		DEPARTEMENT ='".$this->getField("DEPARTEMENT")."',
		BUYER_ID ='".$this->getField("BUYER_ID")."',
		OTHER ='".$this->getField("OTHER")."',
		PPN ='".$this->getField("PPN")."',
		PPN_PERCENT ='".$this->getField("PPN_PERCENT")."',		
		TERMS_AND_CONDITION ='".$this->getField("TERMS_AND_CONDITION")."',		
		NOTE ='".$this->getField("NOTE")."',		
		TYPE ='".$this->getField("TYPE")."',		
		ACKNOWLEDGED_BY ='".$this->getField("ACKNOWLEDGED_BY")."',		
		ACKNOWLEDGED_DEPT ='".$this->getField("ACKNOWLEDGED_DEPT")."',		
		APPROVED1_BY ='".$this->getField("APPROVED1_BY")."',		
		APPROVED1_DEPT ='".$this->getField("APPROVED1_DEPT")."',		
		APPROVED2_BY ='".$this->getField("APPROVED2_BY")."',		
		APPROVED2_DEPT ='".$this->getField("APPROVED2_DEPT")."',		
		UPDATED_BY ='".$this->USERNAME."',
		UPDATED_DATE =now()
		WHERE ISSUE_PO_ID= '".$this->getField("ISSUE_PO_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function updatePath()
	{
		$str = "
		UPDATE ISSUE_PO
		SET    
		
		PATH_LAMPIRAN ='".$this->getField("PATH_LAMPIRAN")."'
		
		WHERE ISSUE_PO_ID= '".$this->getField("ISSUE_PO_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function updateDiskon()
	{
		$str = "
		UPDATE ISSUE_PO
		SET    
		
		DISKON ='".$this->getField("DISKON")."',
		DISKON_PERCENT ='".$this->getField("DISKON_PERCENT")."',
		STATUS_BAYAR ='".$this->getField("STATUS_BAYAR")."',
		MASTER_PROJECT_ID =".$this->getField("MASTER_PROJECT_ID")."
		
		WHERE ISSUE_PO_ID= '".$this->getField("ISSUE_PO_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function updateLampiran()
	{
		$str = "
		UPDATE ISSUE_PO
		SET    
		
		LAMPIRAN ='".$this->getField("LAMPIRAN")."'
		
		WHERE ISSUE_PO_ID= '".$this->getField("ISSUE_PO_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM ISSUE_PO
		WHERE ISSUE_PO_ID= '".$this->getField("ISSUE_PO_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.ISSUE_PO_ID ASC")
	{
		$str = "
		SELECT A.ISSUE_PO_ID,A.NOMER_PO,A.PO_DATE,TO_CHAR(A.PO_DATE, 'DD/MM/YY') PO_DATE1,A.DOC_LAMPIRAN,A.REFERENSI,A.PATH_LAMPIRAN,A.FINANCE,A.COMPANY_ID,A.COMPANY_NAME,A.CONTACT,A.ADDRESS,A.EMAIL,A.TELP,A.FAX,A.HP,A.BUYER_ID,A.OTHER,A.PPN,A.PPN_PERCENT,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE, NOTE, TYPE, ACKNOWLEDGED_BY, ACKNOWLEDGED_DEPT, APPROVED1_BY, APPROVED1_DEPT, APPROVED2_BY, APPROVED2_DEPT,
			A.PIC, A.DEPARTEMENT, A.TERMS_AND_CONDITION,
			CASE
			(SELECT COUNT (1) FROM ISSUE_PO_DETAIL X WHERE A.ISSUE_PO_ID = X.ISSUE_PO_ID AND X.STATUS_BAYAR = 2)
			WHEN 0 THEN ''
			ELSE 'red'
			END STATUS2,
			CASE
			
			WHEN A.STATUS_BAYAR ='2' THEN 'red'
			ELSE ''
			END STATUS,
			CASE
			
			WHEN A.STATUS_BAYAR ='1' THEN 'Bayar'
			WHEN A.STATUS_BAYAR ='2' THEN 'Belum Bayar'
			ELSE ''
			END STATUS_KET,
			(SELECT MAX(KETERANGAN) FROM ISSUE_PO_DETAIL X WHERE A.ISSUE_PO_ID = X.ISSUE_PO_ID) DESCRIPTION,
			(SELECT SUM(TOTAL) FROM ISSUE_PO_DETAIL X WHERE A.ISSUE_PO_ID = X.ISSUE_PO_ID) TOTAL,B.NAMA CUR,B.INISIAL,B.FORMAT,A.TYPE_CUR,A.DISKON,A.DISKON_PERCENT,A.MASTER_PROJECT_ID,C.NAMA NAMA_PROJECT,C.CODE,A.STATUS_BAYAR
		FROM ISSUE_PO A
		LEFT JOIN MASTER_CURRENCY B ON CAST(B.MASTER_CURRENCY_ID AS VARCHAR) = A.TYPE_CUR
		LEFT JOIN MASTER_PROJECT C ON C.MASTER_PROJECT_ID = A.MASTER_PROJECT_ID
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM ISSUE_PO A WHERE 1=1 ".$statement;
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
