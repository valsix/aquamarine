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

class CostumerSupport     extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function CostumerSupport()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("COSTUMER_SUPPORT_ID", $this->getNextId("COSTUMER_SUPPORT_ID","COSTUMER_SUPPORT")); 

        $str = "INSERT INTO COSTUMER_SUPPORT (COSTUMER_SUPPORT_ID, COMPANY_ID, NAMA, TELP, EMAIL, CREATED_BY,        CREATED_DATE)VALUES (
        '".$this->getField("COSTUMER_SUPPORT_ID")."',
        '".$this->getField("COMPANY_ID")."',
        '".$this->getField("NAMA")."',
        '".$this->getField("TELP")."',
        '".$this->getField("EMAIL")."',
        '".$this->USERNAME."',
         CURRENT_DATE
       
    )";

    $this->id = $this->getField("COSTUMER_SUPPORT_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE COSTUMER_SUPPORT
        SET    
        COSTUMER_SUPPORT_ID ='".$this->getField("COSTUMER_SUPPORT_ID")."',
        COMPANY_ID ='".$this->getField("COMPANY_ID")."',
        NAMA ='".$this->getField("NAMA")."',
        TELP ='".$this->getField("TELP")."',
        EMAIL ='".$this->getField("EMAIL")."',
     
        UPDATED_BY ='".$this->USERNAME."',
        UPDATED_DATE =CURRENT_DATE
        WHERE COSTUMER_SUPPORT_ID= '".$this->getField("COSTUMER_SUPPORT_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM COSTUMER_SUPPORT
        WHERE COSTUMER_SUPPORT_ID= '".$this->getField("COSTUMER_SUPPORT_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }
    function deleteParent($statement= "")
    {
        $str = "DELETE FROM COSTUMER_SUPPORT
        WHERE COMPANY_ID= '".$this->getField("COMPANY_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.COSTUMER_SUPPORT_ID ASC")
    {
        $str = "
        SELECT A.COSTUMER_SUPPORT_ID,A.COMPANY_ID,A.NAMA,A.TELP,A.EMAIL,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
        FROM COSTUMER_SUPPORT A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM COSTUMER_SUPPORT A WHERE 1=1 ".$statement;
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
