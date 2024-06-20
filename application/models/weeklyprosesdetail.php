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

class WeeklyProsesDetail       extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function WeeklyProsesDetail()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("WEEKLY_PROSES_DETAIL_ID", $this->getNextId("WEEKLY_PROSES_DETAIL_ID","WEEKLY_PROSES_DETAIL")); 

        $str = "INSERT INTO WEEKLY_PROSES_DETAIL (WEEKLY_PROSES_DETAIL_ID, MASTER_SOLUSI_ID, WEEKLY_PROSES_ID,        URUT, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("WEEKLY_PROSES_DETAIL_ID")."',
        '".$this->getField("MASTER_SOLUSI_ID")."',
        '".$this->getField("WEEKLY_PROSES_ID")."',
        '".$this->getField("URUT")."',
        '".$this->USERNAME."',
       CURRENT_DATE
       
    )";

    $this->id = $this->getField("WEEKLY_PROSES_DETAIL_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
}

function update()
{
    $str = "
    UPDATE WEEKLY_PROSES_DETAIL
    SET    
    WEEKLY_PROSES_DETAIL_ID ='".$this->getField("WEEKLY_PROSES_DETAIL_ID")."',
    MASTER_SOLUSI_ID ='".$this->getField("MASTER_SOLUSI_ID")."',
    WEEKLY_PROSES_ID ='".$this->getField("WEEKLY_PROSES_ID")."',
    URUT ='".$this->getField("URUT")."',
    UPDATED_BY ='".$this->USERNAME."',
    UPDATED_DATE =CURRENT_DATE
    WHERE WEEKLY_PROSES_DETAIL_ID= '".$this->getField("WEEKLY_PROSES_DETAIL_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}
function delete($statement= "")
{
    $str = "DELETE FROM WEEKLY_PROSES_DETAIL
    WHERE WEEKLY_PROSES_DETAIL_ID= '".$this->getField("WEEKLY_PROSES_DETAIL_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}
function deleteParent($statement= "")
{
    $str = "DELETE FROM WEEKLY_PROSES_DETAIL
    WHERE WEEKLY_PROSES_ID= '".$this->getField("WEEKLY_PROSES_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}
function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.WEEKLY_PROSES_DETAIL_ID ASC")
{
    $str = "
    SELECT A.WEEKLY_PROSES_DETAIL_ID,A.MASTER_SOLUSI_ID,A.WEEKLY_PROSES_ID,A.URUT,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
    FROM WEEKLY_PROSES_DETAIL A
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
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM WEEKLY_PROSES_DETAIL A WHERE 1=1 ".$statement;
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
