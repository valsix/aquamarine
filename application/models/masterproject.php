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

class MasterProject   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function MasterProject()
    {
        $this->Entity();
    }

        function insert()
        {
            $this->setField("MASTER_PROJECT_ID", $this->getNextId("MASTER_PROJECT_ID","MASTER_PROJECT")); 

            $str = "INSERT INTO MASTER_PROJECT (MASTER_PROJECT_ID,CODE, NAMA, KETERANGAN, CREATED_BY, CREATED_DATE)VALUES (
            '".$this->getField("MASTER_PROJECT_ID")."',
             '".$this->getField("CODE")."',
            '".$this->getField("NAMA")."',
            '".$this->getField("KETERANGAN")."',
            '".$this->USERID."',
            CURRENT_DATE

        )";

        $this->id = $this->getField("MASTER_PROJECT_ID");
        $this->query= $str;
            // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE MASTER_PROJECT
        SET    
        MASTER_PROJECT_ID ='".$this->getField("MASTER_PROJECT_ID")."',
        NAMA ='".$this->getField("NAMA")."',
         CODE ='".$this->getField("CODE")."',
        KETERANGAN ='".$this->getField("KETERANGAN")."',        
        UPDATED_BY ='".$this->USERID."',
        UPDATED_DATE =CURRENT_DATE
        WHERE MASTER_PROJECT_ID= '".$this->getField("MASTER_PROJECT_ID")."'";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }
    function delete($statement= "")
    {
        $str = "DELETE FROM MASTER_PROJECT
        WHERE MASTER_PROJECT_ID= '".$this->getField("MASTER_PROJECT_ID")."'"; 
        $this->query = $str;
              // echo $str;exit();
        return $this->execQuery($str);
    }
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.MASTER_PROJECT_ID ASC")
    {
        $str = "
        SELECT A.MASTER_PROJECT_ID,A.NAMA,A.KETERANGAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.CODE
        FROM master_project A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM MASTER_PROJECT A WHERE 1=1 ".$statement;
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
