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

class Bank   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function Bank()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("BANK_ID", $this->getNextId("BANK_ID","BANK")); 

        $str = "INSERT INTO BANK (BANK_ID, NAMA, KODE_REKENING, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("BANK_ID")."',
        '".$this->getField("NAMA")."',
        '".$this->getField("KODE_REKENING")."',
        '".$this->USER_LOGIN_ID."',
        now()
     
    )";

    $this->id = $this->getField("BANK_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE BANK
        SET    
        BANK_ID ='".$this->getField("BANK_ID")."',
        NAMA ='".$this->getField("NAMA")."',
        KODE_REKENING ='".$this->getField("KODE_REKENING")."',
        UPDATED_BY ='".$this->USER_LOGIN_ID."',
        UPDATED_DATE =now()
        WHERE BANK_ID= '".$this->getField("BANK_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM BANK
        WHERE BANK_ID= '".$this->getField("BANK_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.BANK_ID ASC")
    {
        $str = "
        SELECT A.BANK_ID,A.NAMA,A.KODE_REKENING,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
        FROM BANK A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM BANK A WHERE 1=1 ".$statement;
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
