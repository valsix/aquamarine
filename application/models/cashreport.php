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

class CashReport   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function CashReport()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("CASH_REPORT_ID", $this->getNextId("CASH_REPORT_ID","CASH_REPORT")); 

        $str = "INSERT INTO CASH_REPORT (CASH_REPORT_ID, TANGGAL, DESKRIPSI)VALUES (
        '".$this->getField("CASH_REPORT_ID")."',
        ".$this->getField("TANGGAL").",
        '".$this->getField("DESKRIPSI")."' 
    )";

    $this->id = $this->getField("CASH_REPORT_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE CASH_REPORT
        SET    
        CASH_REPORT_ID ='".$this->getField("CASH_REPORT_ID")."',
        TANGGAL =".$this->getField("TANGGAL").",
        DESKRIPSI ='".$this->getField("DESKRIPSI")."' 
        WHERE CASH_REPORT_ID= '".$this->getField("CASH_REPORT_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM CASH_REPORT
        WHERE CASH_REPORT_ID= '".$this->getField("CASH_REPORT_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.CASH_REPORT_ID ASC")
    {
        $str = "
        SELECT A.CASH_REPORT_ID,A.TANGGAL,A.DESKRIPSI
        FROM CASH_REPORT A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM CASH_REPORT A WHERE 1=1 ".$statement;
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
