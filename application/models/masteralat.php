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

class MasterAlat   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function MasterAlat()
    {
        $this->Entity();
    }

        function insert()
        {
            $this->setField("MASTER_ALAT_ID", $this->getNextId("MASTER_ALAT_ID","MASTER_ALAT")); 

            $str = "INSERT INTO MASTER_ALAT (MASTER_ALAT_ID, NAMA, KETERANGAN, CREATED_BY, CREATED_DATE)VALUES (
            '".$this->getField("MASTER_ALAT_ID")."',
            '".$this->getField("NAMA")."',
            '".$this->getField("KETERANGAN")."',
            '".$this->USERID."',
            CURRENT_DATE

        )";

        $this->id = $this->getField("MASTER_ALAT_ID");
        $this->query= $str;
            // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE MASTER_ALAT
        SET    
        MASTER_ALAT_ID ='".$this->getField("MASTER_ALAT_ID")."',
        NAMA ='".$this->getField("NAMA")."',
        KETERANGAN ='".$this->getField("KETERANGAN")."',        
        UPDATED_BY ='".$$this->USERID."',
        UPDATED_DATE =CURRENT_DATE
        WHERE MASTER_ALAT_ID= '".$this->getField("MASTER_ALAT_ID")."'";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }
    function delete($statement= "")
    {
        $str = "DELETE FROM MASTER_ALAT
        WHERE MASTER_ALAT_ID= '".$this->getField("MASTER_ALAT_ID")."'"; 
        $this->query = $str;
              // echo $str;exit();
        return $this->execQuery($str);
    }
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.MASTER_ALAT_ID ASC")
    {
        $str = "
        SELECT A.MASTER_ALAT_ID,A.NAMA,A.KETERANGAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
        FROM master_alat A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM MASTER_ALAT A WHERE 1=1 ".$statement;
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
