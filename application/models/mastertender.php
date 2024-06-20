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
include_once("functions/string.func.php");
class MasterTender   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function MasterTender()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("MASTER_TENDER_ID", $this->getNextId("MASTER_TENDER_ID","MASTER_TENDER")); 

        $str = "INSERT INTO MASTER_TENDER (MASTER_TENDER_ID, NO_PROJECT,COMPANY_ID, KETERANGAN,COMPANY_NAME, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("MASTER_TENDER_ID")."',
        '".$this->getField("NO_PROJECT")."',
        ".retNullString($this->getField("COMPANY_ID")).",
        '".$this->getField("KETERANGAN")."',
          '".$this->getField("COMPANY_NAME")."',
        '".$this->USER_LOGIN_ID."',
        now()
     
    )";

    $this->id = $this->getField("MASTER_TENDER_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE MASTER_TENDER
        SET    
        MASTER_TENDER_ID ='".$this->getField("MASTER_TENDER_ID")."',
        NO_PROJECT ='".$this->getField("NO_PROJECT")."',
        COMPANY_ID =".retNullString($this->getField("COMPANY_ID")).",
        KETERANGAN ='".$this->getField("KETERANGAN")."',
        COMPANY_NAME ='".$this->getField("COMPANY_NAME")."',
        UPDATED_BY ='".$this->USER_LOGIN_ID."',
        UPDATED_DATE =now()
        WHERE MASTER_TENDER_ID= '".$this->getField("MASTER_TENDER_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM MASTER_TENDER
        WHERE MASTER_TENDER_ID= '".$this->getField("MASTER_TENDER_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.MASTER_TENDER_ID ASC")
    {
        $str = "
        SELECT A.MASTER_TENDER_ID,A.NO_PROJECT,A.KETERANGAN,A.COMPANY_ID,A.COMPANY_NAME,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
        FROM MASTER_TENDER A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM MASTER_TENDER A WHERE 1=1 ".$statement;
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
