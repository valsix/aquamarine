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

class HppMaster   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function HppMaster()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("HPP_MASTER_ID", $this->getNextId("HPP_MASTER_ID","HPP_MASTER")); 

        $str = "INSERT INTO HPP_MASTER (HPP_MASTER_ID, CODE, KETERANGAN, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("HPP_MASTER_ID")."',
        '".$this->getField("CODE")."',
        '".$this->getField("KETERANGAN")."',
        '".$this->USER_LOGIN_ID."',
        now()
     
    )";

    $this->id = $this->getField("HPP_MASTER_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE HPP_MASTER
        SET    
        HPP_MASTER_ID ='".$this->getField("HPP_MASTER_ID")."',
        CODE ='".$this->getField("CODE")."',
        KETERANGAN ='".$this->getField("KETERANGAN")."',
        UPDATED_BY ='".$this->USER_LOGIN_ID."',
        UPDATED_DATE =now()
        WHERE HPP_MASTER_ID= '".$this->getField("HPP_MASTER_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM HPP_MASTER
        WHERE HPP_MASTER_ID= '".$this->getField("HPP_MASTER_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.HPP_MASTER_ID ASC")
    {
        $str = "
        SELECT A.HPP_MASTER_ID,A.CODE,A.KETERANGAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
        FROM HPP_MASTER A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM HPP_MASTER A WHERE 1=1 ".$statement;
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
