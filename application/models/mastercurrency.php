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

class MasterCurrency    extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function MasterCurrency()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("MASTER_CURRENCY_ID", $this->getNextId("MASTER_CURRENCY_ID","MASTER_CURRENCY")); 

        $str = "INSERT INTO MASTER_CURRENCY ( MASTER_CURRENCY_ID, NAMA, INISIAL, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("MASTER_CURRENCY_ID")."',
        '".$this->getField("NAMA")."',
        '".$this->getField("INISIAL")."',
        '".$this->USERNAME."',
        CURRENT_DATE

    )";

    $this->id = $this->getField("MASTER_CURRENCY_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
}

function update()
{
    $str = "
    UPDATE MASTER_CURRENCY
    SET    
    MASTER_CURRENCY_ID ='".$this->getField("MASTER_CURRENCY_ID")."',
    NAMA ='".$this->getField("NAMA")."',
    INISIAL ='".$this->getField("INISIAL")."',
    UPDATED_BY ='".$this->USERNAME."',
    UPDATED_DATE = CURRENT_DATE
    WHERE MASTER_CURRENCY_ID= '".$this->getField("MASTER_CURRENCY_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}
function delete($statement= "")
{
    $str = "DELETE FROM MASTER_CURRENCY
    WHERE MASTER_CURRENCY_ID= '".$this->getField("MASTER_CURRENCY_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}
function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.MASTER_CURRENCY_ID ASC")
{
    $str = "
    SELECT A.MASTER_CURRENCY_ID,A.NAMA,A.INISIAL,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.FORMAT
    FROM MASTER_CURRENCY A
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
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM MASTER_CURRENCY A WHERE 1=1 ".$statement;
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
