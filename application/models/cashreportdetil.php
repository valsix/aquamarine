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

class CashReportDetil   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function CashReportDetil()
    {
        $this->Entity();
    }

    function insert()
    {
    	$this->setField("CASH_REPORT_DETIL_ID", $this->getNextId("CASH_REPORT_DETIL_ID","CASH_REPORT_DETIL")); 

    	$str = "INSERT INTO CASH_REPORT_DETIL 
    	(
    		CASH_REPORT_DETIL_ID, TANGGAL, KETERANGAN, PELUNASAN, NO_REK, DEBET, KREDIT, SALDO, KATEGORI_ID,CASH_REPORT_ID,DEBET_USD,KREDIT_USD,SALDO_USD
    	) VALUES 
    	(
    	'".$this->getField("CASH_REPORT_DETIL_ID")."',
    	".$this->getField("TANGGAL").",
    	'".$this->getField("KETERANGAN")."',
    	'".$this->getField("PELUNASAN")."',
    	'".$this->getField("NO_REK")."',
    	".$this->getField("DEBET").",
    	".$this->getField("KREDIT").",
    	".$this->getField("SALDO").",
    	'".$this->getField("KATEGORI_ID")."',
    	'".$this->getField("CASH_REPORT_ID")."',
    	".$this->getField("DEBET_USD").",
    	".$this->getField("KREDIT_USD").",
    	".$this->getField("SALDO_USD")."
    	
    )";

    $this->id = $this->getField("CASH_REPORT_DETIL_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE CASH_REPORT_DETIL
		SET    
		CASH_REPORT_DETIL_ID ='".$this->getField("CASH_REPORT_DETIL_ID")."',
		TANGGAL =".$this->getField("TANGGAL").",
		KATEGORI_ID ='".$this->getField("KATEGORI_ID")."',
		KETERANGAN ='".$this->getField("KETERANGAN")."',
		PELUNASAN ='".$this->getField("PELUNASAN")."',
		NO_REK ='".$this->getField("NO_REK")."',
		DEBET =".$this->getField("DEBET").",
		KREDIT =".$this->getField("KREDIT").",
		SALDO =".$this->getField("SALDO").",
		DEBET_USD =".$this->getField("DEBET_USD").",
		KREDIT_USD =".$this->getField("KREDIT_USD").",
		SALDO_USD =".$this->getField("SALDO_USD").",
		
		CASH_REPORT_ID ='".$this->getField("CASH_REPORT_ID")."'
		WHERE CASH_REPORT_DETIL_ID= '".$this->getField("CASH_REPORT_DETIL_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function updateBalance()
	{
		$str = "
		UPDATE CASH_REPORT_DETIL
		SET    
		CASH_REPORT_DETIL_ID ='".$this->getField("CASH_REPORT_DETIL_ID")."',
		DEBET =".$this->getField("DEBET").",
		KREDIT =".$this->getField("KREDIT").",
		SALDO =".$this->getField("SALDO").",
		CASH_REPORT_ID ='".$this->getField("CASH_REPORT_ID")."'
		WHERE CASH_REPORT_DETIL_ID= '".$this->getField("CASH_REPORT_DETIL_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function updateBalanceUSD()
	{
		$str = "
		UPDATE CASH_REPORT_DETIL
		SET    
		CASH_REPORT_DETIL_ID ='".$this->getField("CASH_REPORT_DETIL_ID")."',
		DEBET_USD =".$this->getField("DEBET_USD").",
		KREDIT_USD =".$this->getField("KREDIT_USD").",
		SALDO_USD =".$this->getField("SALDO_USD").",
		CASH_REPORT_ID ='".$this->getField("CASH_REPORT_ID")."'
		WHERE CASH_REPORT_DETIL_ID= '".$this->getField("CASH_REPORT_DETIL_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM CASH_REPORT_DETIL
		WHERE CASH_REPORT_DETIL_ID= '".$this->getField("CASH_REPORT_DETIL_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}
	function deleteParent($statement= "")
	{
		$str = "DELETE FROM CASH_REPORT_DETIL
		WHERE CASH_REPORT_ID= '".$this->getField("CASH_REPORT_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.TANGGAL DESC")
	{
		$str = "
		SELECT A.CASH_REPORT_DETIL_ID,A.TANGGAL,A.KETERANGAN,A.PELUNASAN,A.NO_REK,A.DEBET,A.KREDIT,A.SALDO,A.CASH_REPORT_ID,A.KATEGORI_ID,B.NAMA,
		A.DEBET_USD,A.KREDIT_USD,A.SALDO_USD
		FROM CASH_REPORT_DETIL A
		LEFT JOIN KATEGORI_CASH B ON B.KATEGORI_CASH_ID = A.KATEGORI_ID
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM CASH_REPORT_DETIL A WHERE 1=1 ".$statement;
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
