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

class ECommerce extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function ECommerce()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("ECOMMERCE_ID", $this->getNextId("ECOMMERCE_ID","ECOMMERCE")); 

        $str = "INSERT INTO ECOMMERCE (ECOMMERCE_ID, EMAIL, CC, BCC, SUBJECT, BODY, ATTACHMENT, SEND_DATE, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("ECOMMERCE_ID")."',
        '".$this->getField("EMAIL")."',
        '".$this->getField("CC")."',
        '".$this->getField("BCC")."',
        '".$this->getField("SUBJECT")."',
        '".$this->getField("BODY")."',
        '".$this->getField("ATTACHMENT")."',
        ".$this->getField("SEND_DATE").",
        '".$this->USERNAME."',
        CURRENT_DATE
       
    )";

    $this->id = $this->getField("ECOMMERCE_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE ECOMMERCE
        SET    
        ECOMMERCE_ID ='".$this->getField("ECOMMERCE_ID")."',
        EMAIL ='".$this->getField("EMAIL")."',
        CC ='".$this->getField("CC")."',
        BCC ='".$this->getField("BCC")."',
        SUBJECT ='".$this->getField("SUBJECT")."',
        BODY ='".$this->getField("BODY")."',
        ATTACHMENT ='".$this->getField("ATTACHMENT")."',
        SEND_DATE =".$this->getField("SEND_DATE").",
        UPDATED_BY ='".$this->USERNAME."',
        UPDATED_DATE =CURRENT_DATE
        WHERE ECOMMERCE_ID= '".$this->getField("ECOMMERCE_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM ECOMMERCE
        WHERE ECOMMERCE_ID= '".$this->getField("ECOMMERCE_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.ECOMMERCE_ID ASC")
    {
        $str = "
        SELECT A.ECOMMERCE_ID,A.EMAIL,A.CC,A.BCC,A.SUBJECT,A.BODY,A.ATTACHMENT,TO_CHAR(A.SEND_DATE, 'DD-MM-YYYY HH24:MI') SEND_DATE
        FROM ECOMMERCE A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM ECOMMERCE A WHERE 1=1 ".$statement;
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
