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

class TechicalSupport    extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function TechicalSupport()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("ID", $this->getNextId("ID","TECHICAL_SUPPORT")); 

        $str = "INSERT INTO TECHICAL_SUPPORT ( ID, PARENT_ID, NAMA, KET, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("ID")."',
        '".$this->getField("PARENT_ID")."',
        '".$this->getField("NAMA")."',
        '".$this->getField("KET")."',
        '".$this->USERNAME."',
        now()
       
    )";

    $this->id = $this->getField("ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE TECHICAL_SUPPORT
        SET    
        ID ='".$this->getField("ID")."',
        PARENT_ID ='".$this->getField("PARENT_ID")."',
        NAMA ='".$this->getField("NAMA")."',
        KET ='".$this->getField("KET")."',
        UPDATED_BY ='".$this->USERNAME."',
        UPDATED_DATE =now()
        WHERE ID= '".$this->getField("ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM TECHICAL_SUPPORT
        WHERE ID= '".$this->getField("ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.ID ASC")
    {
        $str = "
        SELECT A.ID,A.PARENT_ID,A.NAMA,A.KET,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
        FROM TECHICAL_SUPPORT A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM TECHICAL_SUPPORT A WHERE 1=1 ".$statement;
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
