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

  class IssuePoDetail   extends Entity{

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function IssuePoDetail()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("ISSUE_PO_DETAIL_ID", $this->getNextId("ISSUE_PO_DETAIL_ID","ISSUE_PO_DETAIL")); 

    	$str = "INSERT INTO ISSUE_PO_DETAIL ( ISSUE_PO_DETAIL_ID, KETERANGAN, QTY, SATUAN, AMOUNT, TOTAL,ISSUE_PO_ID,TERM,CURENCY, CREATED_BY,CREATED_DATE,STATUS_BAYAR)VALUES (
    	'".$this->getField("ISSUE_PO_DETAIL_ID")."',
    	'".$this->getField("KETERANGAN")."',
    	'".$this->getField("QTY")."',
    	'".$this->getField("SATUAN")."',
    	'".$this->getField("AMOUNT")."',
    	'".$this->getField("TOTAL")."',
    	'".$this->getField("ISSUE_PO_ID")."',
    	'".$this->getField("TERM")."',
    	'".$this->getField("CURENCY")."',
    	'".$this->USERNAME."',    	  	
    	now(),
    	".$this->getField("STATUS_BAYAR")."
    )";

    $this->id = $this->getField("ISSUE_PO_DETAIL_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE ISSUE_PO_DETAIL
		SET    
		ISSUE_PO_DETAIL_ID ='".$this->getField("ISSUE_PO_DETAIL_ID")."',
		KETERANGAN ='".$this->getField("KETERANGAN")."',
		QTY ='".$this->getField("QTY")."',
		SATUAN ='".$this->getField("SATUAN")."',
		AMOUNT ='".$this->getField("AMOUNT")."',
		TOTAL ='".$this->getField("TOTAL")."',
		TERM ='".$this->getField("TERM")."',
		ISSUE_PO_ID ='".$this->getField("ISSUE_PO_ID")."',
		CURENCY ='".$this->getField("CURENCY")."',
		UPDATED_BY ='".$this->USERNAME."',
		UPDATED_DATE =now(),
		STATUS_BAYAR =".$this->getField("STATUS_BAYAR")."
		WHERE ISSUE_PO_DETAIL_ID= '".$this->getField("ISSUE_PO_DETAIL_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM ISSUE_PO_DETAIL
		WHERE ISSUE_PO_DETAIL_ID= '".$this->getField("ISSUE_PO_DETAIL_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function deleteParent($statement= "")
	{
		$str = "DELETE FROM ISSUE_PO_DETAIL
		WHERE ISSUE_PO_ID= '".$this->getField("ISSUE_PO_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.ISSUE_PO_DETAIL_ID ASC")
	{
		$str = "
		SELECT A.ISSUE_PO_DETAIL_ID,A.KETERANGAN,A.QTY,A.SATUAN,A.AMOUNT,A.TOTAL,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.CURENCY,A.TERM,A.STATUS_BAYAR,B.NAMA CUR,B.INISIAL,B.FORMAT,
		CASE 
		WHEN A.STATUS_BAYAR = 1
		THEN 'Bayar'
		WHEN A.STATUS_BAYAR = 2
		THEN 'Belum Bayar'
		END STATUS_BAYAR_INFO
		FROM ISSUE_PO_DETAIL A
		LEFT JOIN MASTER_CURRENCY B ON CAST(B.MASTER_CURRENCY_ID AS VARCHAR) = A.CURENCY
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM ISSUE_PO_DETAIL A WHERE 1=1 ".$statement;
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
