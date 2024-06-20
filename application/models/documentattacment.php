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

class DocumentAttacment extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function DocumentAttacment()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("DOCUMENT_ATTACMENT_ID", $this->getNextId("DOCUMENT_ATTACMENT_ID","DOCUMENT_ATTACMENT")); 

        $str = "INSERT INTO DOCUMENT_ATTACMENT (DOCUMENT_ATTACMENT_ID, NAME, DESCIPTION, PATH, EXTENSION, SIZE,        CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("DOCUMENT_ATTACMENT_ID")."',
        '".$this->getField("NAME")."',
        '".$this->getField("DESCIPTION")."',
        '".$this->getField("PATH")."',
        '".$this->getField("EXTENSION")."',
        '".$this->getField("SIZE")."',
        '".$this->USERNAME."',
        now()
       
    )";

    $this->id = $this->getField("DOCUMENT_ATTACMENT_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE DOCUMENT_ATTACMENT
        SET    
        DOCUMENT_ATTACMENT_ID ='".$this->getField("DOCUMENT_ATTACMENT_ID")."',
        NAME ='".$this->getField("NAME")."',
        DESCIPTION ='".$this->getField("DESCIPTION")."',
        PATH ='".$this->getField("PATH")."',
        EXTENSION ='".$this->getField("EXTENSION")."',
        SIZE ='".$this->getField("SIZE")."',
        UPDATED_BY ='".$this->USERNAME."',
        UPDATED_DATE =now()
        WHERE DOCUMENT_ATTACMENT_ID= '".$this->getField("DOCUMENT_ATTACMENT_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function update_path()
    {
        $str = "
        UPDATE DOCUMENT_ATTACMENT
        SET    
        DOCUMENT_ATTACMENT_ID ='".$this->getField("DOCUMENT_ATTACMENT_ID")."',
       
        PATH ='".$this->getField("PATH")."'
        
      
        WHERE DOCUMENT_ATTACMENT_ID= '".$this->getField("DOCUMENT_ATTACMENT_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM DOCUMENT_ATTACMENT
        WHERE DOCUMENT_ATTACMENT_ID= '".$this->getField("DOCUMENT_ATTACMENT_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.DOCUMENT_ATTACMENT_ID ASC")
    {
        $str = "
        SELECT A.DOCUMENT_ATTACMENT_ID,A.NAME,A.DESCIPTION,A.PATH,A.EXTENSION,A.SIZE,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
        FROM DOCUMENT_ATTACMENT A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM DOCUMENT_ATTACMENT A WHERE 1=1 ".$statement;
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
