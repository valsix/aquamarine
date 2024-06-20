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

class MasterTenderPeriode   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function MasterTenderPeriode()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("MASTER_TENDER_PERIODE_ID", $this->getNextId("MASTER_TENDER_PERIODE_ID","MASTER_TENDER_PERIODE")); 

        $str = "INSERT INTO MASTER_TENDER_PERIODE (MASTER_TENDER_PERIODE_ID, TAHUN, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("MASTER_TENDER_PERIODE_ID")."',
        '".$this->getField("TAHUN")."',
        
        '".$this->USERNAME."',
           CURRENT_DATE
    )";

    $this->id = $this->getField("MASTER_TENDER_PERIODE_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
}

function updateProses()
{
    $str = "
    UPDATE MASTER_TENDER_PERIODE
    SET    
    
  
    UPDATED_BY ='".$this->USERNAME."',
    UPDATED_DATE =CURRENT_DATE
    WHERE MASTER_TENDER_PERIODE_ID= '".$this->getField("MASTER_TENDER_PERIODE_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}

function update()
{
    $str = "
    UPDATE MASTER_TENDER_PERIODE
    SET    
    MASTER_TENDER_PERIODE_ID ='".$this->getField("MASTER_TENDER_PERIODE_ID")."',
    TAHUN ='".$this->getField("TAHUN")."',
  
    UPDATED_BY ='".$this->USERNAME."',
    UPDATED_DATE =CURRENT_DATE
    WHERE MASTER_TENDER_PERIODE_ID= '".$this->getField("MASTER_TENDER_PERIODE_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}
function delete($statement= "")
{
    $str = "DELETE FROM MASTER_TENDER_PERIODE
    WHERE MASTER_TENDER_PERIODE_ID= '".$this->getField("MASTER_TENDER_PERIODE_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}
function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.MASTER_TENDER_PERIODE_ID ASC")
{
    $str = "
    SELECT A.MASTER_TENDER_PERIODE_ID,A.TAHUN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,
    ( CASE WHEN A.UPDATED_DATE IS NULL THEN  A.CREATED_DATE ELSE A.UPDATED_DATE END) LAST_UPDATE

    FROM MASTER_TENDER_PERIODE A
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
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM MASTER_TENDER_PERIODE A WHERE 1=1 ".$statement;
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
