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

class CostCode  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function CostCode()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("COST_CODE_ID", $this->getNextId("COST_CODE_ID","COST_CODE")); 

        $str = "INSERT INTO COST_CODE (COST_CODE_ID, KODE, NAMA, CREATED_BY, CREATED_DATE, PARENT_ID)VALUES (
        '".$this->getField("COST_CODE_ID")."',
        '".$this->getField("KODE")."',
        '".$this->getField("NAMA")."',
        '".$this->USER_LOGIN_ID."',
        now(),     
        '".$this->getField("PARENT_ID")."' 
    )";

    $this->id = $this->getField("COST_CODE_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE COST_CODE
        SET    
        COST_CODE_ID ='".$this->getField("COST_CODE_ID")."',
        KODE ='".$this->getField("KODE")."',
        NAMA ='".$this->getField("NAMA")."',      
        UPDATED_BY ='".$this->USER_LOGIN_ID."',
        UPDATED_DATE =now(),
        PARENT_ID ='".$this->getField("PARENT_ID")."' 
        WHERE COST_CODE_ID= '".$this->getField("COST_CODE_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM COST_CODE
        WHERE COST_CODE_ID= '".$this->getField("COST_CODE_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.COST_CODE_ID ASC")
    {
        $str = "
        SELECT A.COST_CODE_ID,A.KODE,A.NAMA,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.PARENT_ID
        FROM COST_CODE A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM COST_CODE A WHERE 1=1 ".$statement;
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
