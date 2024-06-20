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

class MasterListExperince  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function MasterListExperince()
    {
        $this->Entity();
    }
    function insert()
    {
        $this->setField("MASTER_LIST_EXPERINCE_ID", $this->getNextId("MASTER_LIST_EXPERINCE_ID","MASTER_LIST_EXPERINCE")); 

        $str = "INSERT INTO MASTER_LIST_EXPERINCE (MASTER_LIST_EXPERINCE_ID, NAME, KETERANGAN, PATH, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("MASTER_LIST_EXPERINCE_ID")."',
        '".$this->getField("NAME")."',
        '".$this->getField("KETERANGAN")."',
        '".$this->getField("PATH")."',
        '".$this->USERNAME."',
       CURRENT_DATE
      
    )";

    $this->id = $this->getField("MASTER_LIST_EXPERINCE_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
}

function update()
{
    $str = "
    UPDATE MASTER_LIST_EXPERINCE
    SET    
    MASTER_LIST_EXPERINCE_ID ='".$this->getField("MASTER_LIST_EXPERINCE_ID")."',
    NAME ='".$this->getField("NAME")."',
    KETERANGAN ='".$this->getField("KETERANGAN")."',
    PATH ='".$this->getField("PATH")."',
    UPDATED_BY ='".$this->USERNAME."',
    UPDATED_DATE = CURRENT_DATE
    WHERE MASTER_LIST_EXPERINCE_ID= '".$this->getField("MASTER_LIST_EXPERINCE_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}
function update_path()
{
    $str = "
    UPDATE MASTER_LIST_EXPERINCE
    SET    
  
    PATH ='".$this->getField("PATH")."'
  
    WHERE MASTER_LIST_EXPERINCE_ID= '".$this->getField("MASTER_LIST_EXPERINCE_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}
function delete($statement= "")
{
    $str = "DELETE FROM MASTER_LIST_EXPERINCE
    WHERE MASTER_LIST_EXPERINCE_ID= '".$this->getField("MASTER_LIST_EXPERINCE_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}
function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.MASTER_LIST_EXPERINCE_ID ASC")
{
    $str = "
    SELECT A.MASTER_LIST_EXPERINCE_ID,A.NAME,A.KETERANGAN,A.PATH,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
    FROM MASTER_LIST_EXPERINCE A
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
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM MASTER_LIST_EXPERINCE A WHERE 1=1 ".$statement;
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
