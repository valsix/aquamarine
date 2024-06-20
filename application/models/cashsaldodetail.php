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

class CashSaldoDetail   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function CashSaldoDetail()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("CASH_SALDO_DETAIL_ID", $this->getNextId("CASH_SALDO_DETAIL_ID","CASH_SALDO_DETAIL")); 

        $str = "INSERT INTO CASH_SALDO_DETAIL (CASH_SALDO_DETAIL_ID, CASH_SALDO_ID, URAIAN, BANK_ID, CURENCY,        AMOUNT, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("CASH_SALDO_DETAIL_ID")."',
        '".$this->getField("CASH_SALDO_ID")."',
        '".$this->getField("URAIAN")."',
        '".$this->getField("BANK_ID")."',
        '".$this->getField("CURENCY")."',
        '".$this->getField("AMOUNT")."',
        '".$this->USER_LOGIN_ID."',
        now()
      
    )";

    $this->id = $this->getField("CASH_SALDO_DETAIL_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE CASH_SALDO_DETAIL
        SET    
        CASH_SALDO_DETAIL_ID ='".$this->getField("CASH_SALDO_DETAIL_ID")."',
        CASH_SALDO_ID ='".$this->getField("CASH_SALDO_ID")."',
        URAIAN ='".$this->getField("URAIAN")."',
        BANK_ID ='".$this->getField("BANK_ID")."',
        CURENCY ='".$this->getField("CURENCY")."',
        AMOUNT ='".$this->getField("AMOUNT")."',
        UPDATED_BY ='".$this->USER_LOGIN_ID."',
        UPDATED_DATE =now()
        WHERE CASH_SALDO_DETAIL_ID= '".$this->getField("CASH_SALDO_DETAIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM CASH_SALDO_DETAIL
        WHERE CASH_SALDO_DETAIL_ID= '".$this->getField("CASH_SALDO_DETAIL_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function deleteParent($statement= "")
    {
        $str = "DELETE FROM CASH_SALDO_DETAIL
        WHERE CASH_SALDO_ID= '".$this->getField("CASH_SALDO_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.CASH_SALDO_DETAIL_ID ASC")
    {
        $str = "
        SELECT A.CASH_SALDO_DETAIL_ID,A.CASH_SALDO_ID,A.URAIAN,A.BANK_ID,A.CURENCY,A.AMOUNT,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
        FROM CASH_SALDO_DETAIL A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM CASH_SALDO_DETAIL A WHERE 1=1 ".$statement;
        while(list($key,$val)=each($paramsArray))
        {
            $str .= " AND $key =    '$val' ";
        }
        $this->query = $str;
        $this->select($str); 
        if($this->firstRow()) 
            return $this->getField("ROWCOUNT"); 
        else 
            return 0; 
    }

    function getTotal($paramsArray=array(), $statement="")
    {
        $str = "SELECT SUM(AMOUNT) AS ROWCOUNT FROM CASH_SALDO_DETAIL A WHERE 1=1 ".$statement;
        while(list($key,$val)=each($paramsArray))
        {
            $str .= " AND $key =    '$val' ";
        }
        $this->query = $str;
        $this->select($str); 
        if($this->firstRow()) 
            return $this->getField("ROWCOUNT"); 
        else 
            return 0; 
    }
}
