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

class Departement     extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function Departement()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("DEPARTEMENT_ID", $this->getNextId("DEPARTEMENT_ID","DEPARTEMENT")); 

        $str = "INSERT INTO DEPARTEMENT (DEPARTEMENT_ID, NAMA, KETERANGAN, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("DEPARTEMENT_ID")."',
        '".$this->getField("NAMA")."',
        '".$this->getField("KETERANGAN")."',
        '".$this->USERNAME."',
       CURRENT_DATE
        
    )";

    $this->id = $this->getField("DEPARTEMENT_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
}

function update()
{
    $str = "
    UPDATE DEPARTEMENT
    SET    
    DEPARTEMENT_ID ='".$this->getField("DEPARTEMENT_ID")."',
    NAMA ='".$this->getField("NAMA")."',
    KETERANGAN ='".$this->getField("KETERANGAN")."',
   
    UPDATED_BY ='".$this->USERNAME."',
    UPDATED_DATE =CURRENT_DATE
    WHERE DEPARTEMENT_ID= '".$this->getField("DEPARTEMENT_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}
function delete($statement= "")
{
    $str = "DELETE FROM DEPARTEMENT
    WHERE DEPARTEMENT_ID= '".$this->getField("DEPARTEMENT_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}
function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.DEPARTEMENT_ID ASC")
{
    $str = "
    SELECT A.DEPARTEMENT_ID,A.NAMA,A.KETERANGAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
    FROM DEPARTEMENT A
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
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM DEPARTEMENT A WHERE 1=1 ".$statement;
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
