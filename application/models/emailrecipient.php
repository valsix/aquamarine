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

class EmailRecipient  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function EmailRecipient()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("RECIPIENT_ID", $this->getNextId("RECIPIENT_ID","EMAIL_RECIPIENT")); 

        $str = "INSERT INTO EMAIL_RECIPIENT (RECIPIENT_ID, EMAIL_ID, COMPANY_ID)VALUES (
        '".$this->getField("RECIPIENT_ID")."',
        '".$this->getField("EMAIL_ID")."',
        '".$this->getField("COMPANY_ID")."' 
    )";

    $this->id = $this->getField("RECIPIENT_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE EMAIL_RECIPIENT
        SET    
        RECIPIENT_ID ='".$this->getField("RECIPIENT_ID")."',
        EMAIL_ID ='".$this->getField("EMAIL_ID")."',
        COMPANY_ID ='".$this->getField("COMPANY_ID")."' 
        WHERE RECIPIENT_ID= '".$this->getField("RECIPIENT_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM EMAIL_RECIPIENT
        WHERE RECIPIENT_ID= '".$this->getField("RECIPIENT_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.RECIPIENT_ID ASC")
    {
        $str = "
        SELECT A.RECIPIENT_ID,A.EMAIL_ID,A.COMPANY_ID
        FROM EMAIL_RECIPIENT A
        WHERE 1=1 ";
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

     function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.RECIPIENT_ID ASC")
    {
        $str = "
        SELECT A.RECIPIENT_ID,A.EMAIL_ID,A.COMPANY_ID
        FROM EMAIL_RECIPIENT A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM EMAIL_RECIPIENT A WHERE 1=1 ".$statement;
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
