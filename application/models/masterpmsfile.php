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

class MasterPmsFile  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function MasterPmsFile()
    {
        $this->Entity();
    }
    function insert()
    {
        $this->setField("MASTER_PMS_FILE_ID", $this->getNextId("MASTER_PMS_FILE_ID","MASTER_PMS_FILE")); 

        $str = "INSERT INTO MASTER_PMS_FILE (MASTER_PMS_FILE_ID, NAME, KETERANGAN, PATH, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("MASTER_PMS_FILE_ID")."',
        '".$this->getField("NAME")."',
        '".$this->getField("KETERANGAN")."',
        '".$this->getField("PATH")."',
        '".$this->USERNAME."',
       CURRENT_DATE
      
    )";

    $this->id = $this->getField("MASTER_PMS_FILE_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
}

function update()
{
    $str = "
    UPDATE MASTER_PMS_FILE
    SET    
    MASTER_PMS_FILE_ID ='".$this->getField("MASTER_PMS_FILE_ID")."',
    NAME ='".$this->getField("NAME")."',
    KETERANGAN ='".$this->getField("KETERANGAN")."',
    PATH ='".$this->getField("PATH")."',
    UPDATED_BY ='".$this->USERNAME."',
    UPDATED_DATE = CURRENT_DATE
    WHERE MASTER_PMS_FILE_ID= '".$this->getField("MASTER_PMS_FILE_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}
function update_path()
{
    $str = "
    UPDATE MASTER_PMS_FILE
    SET    
  
    PATH ='".$this->getField("PATH")."'
  
    WHERE MASTER_PMS_FILE_ID= '".$this->getField("MASTER_PMS_FILE_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}
function delete($statement= "")
{
    $str = "DELETE FROM MASTER_PMS_FILE
    WHERE MASTER_PMS_FILE_ID= '".$this->getField("MASTER_PMS_FILE_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}
function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.MASTER_PMS_FILE_ID ASC")
{
    $str = "
    SELECT A.MASTER_PMS_FILE_ID,A.NAME,A.KETERANGAN,A.PATH,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
    FROM MASTER_PMS_FILE A
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
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM MASTER_PMS_FILE A WHERE 1=1 ".$statement;
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
