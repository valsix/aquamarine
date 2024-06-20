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

class MasterTenerMenus  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function MasterTenerMenus()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("MASTER_TENDER_MENUS_ID", $this->getNextId("MASTER_TENDER_MENUS_ID","MASTER_TENER_MENUS")); 

        $str = "INSERT INTO MASTER_TENER_MENUS (MASTER_TENDER_MENUS_ID, NAMA, ALIAS, COLOR, URUTAN,COLOR2, CREATED_BY,        CREATED_DATE)VALUES (
        '".$this->getField("MASTER_TENDER_MENUS_ID")."',
        '".$this->getField("NAMA")."',
        '".$this->getField("ALIAS")."',
        '".$this->getField("COLOR")."',
        '".$this->getField("URUTAN")."',
        '".$this->getField("COLOR2")."',
        '".$this->USERNAME."',
         CURRENT_DATE
        
    )";

    $this->id = $this->getField("MASTER_TENDER_MENUS_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
}

function update()
{
    $str = "
    UPDATE MASTER_TENER_MENUS
    SET    
    MASTER_TENDER_MENUS_ID ='".$this->getField("MASTER_TENDER_MENUS_ID")."',
    NAMA ='".$this->getField("NAMA")."',
    ALIAS ='".$this->getField("ALIAS")."',
    COLOR ='".$this->getField("COLOR")."',
     COLOR2 ='".$this->getField("COLOR2")."',
    URUTAN ='".$this->getField("URUTAN")."',
   
    UPDATED_BY ='".$this->USERNAME."',
    UPDATED_DATE =CURRENT_DATE
    WHERE MASTER_TENDER_MENUS_ID= '".$this->getField("MASTER_TENDER_MENUS_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}
function delete($statement= "")
{
    $str = "DELETE FROM MASTER_TENER_MENUS
    WHERE MASTER_TENDER_MENUS_ID= '".$this->getField("MASTER_TENDER_MENUS_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}
function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.MASTER_TENDER_MENUS_ID ASC")
{
    $str = "
    SELECT A.MASTER_TENDER_MENUS_ID,A.NAMA,A.ALIAS,A.COLOR,A.URUTAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.COLOR2
    FROM MASTER_TENER_MENUS A
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
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM MASTER_TENER_MENUS A WHERE 1=1 ".$statement;
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
