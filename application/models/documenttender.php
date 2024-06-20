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

class DocumentTender   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function DocumentTender()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("DOCUMENT_TENDER_ID", $this->getNextId("DOCUMENT_TENDER_ID","DOCUMENT_TENDER")); 

        $str = "INSERT INTO DOCUMENT_TENDER (DOCUMENT_TENDER_ID, CATEGORY, NAME, DESCRIPTION,CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("DOCUMENT_TENDER_ID")."',
        '".$this->getField("CATEGORY")."',
        '".$this->getField("NAME")."',
        '".$this->getField("DESCRIPTION")."',

        '".$this->USERNAME."',
        CURRENT_DATE

    )";

    $this->id = $this->getField("DOCUMENT_TENDER_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

        function update()
        {
            $str = "
            UPDATE DOCUMENT_TENDER
            SET    
            DOCUMENT_TENDER_ID ='".$this->getField("DOCUMENT_TENDER_ID")."',
            CATEGORY ='".$this->getField("CATEGORY")."',
            NAME ='".$this->getField("NAME")."',
            DESCRIPTION ='".$this->getField("DESCRIPTION")."',    
            UPDATED_BY ='".$this->USERNAME."',
            UPDATED_DATE =CURRENT_DATE
            WHERE DOCUMENT_TENDER_ID= '".$this->getField("DOCUMENT_TENDER_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }
        function update_path()
        {
            $str = "
            UPDATE DOCUMENT_TENDER
            SET    
            DOCUMENT_TENDER_ID ='".$this->getField("DOCUMENT_TENDER_ID")."',
            
            ".$this->getField("COLOMN")." ='".$this->getField("FIELD")."',    
            UPDATED_BY ='".$this->USERNAME."',
            UPDATED_DATE =CURRENT_DATE
            WHERE DOCUMENT_TENDER_ID= '".$this->getField("DOCUMENT_TENDER_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }
        function delete($statement= "")
        {
            $str = "DELETE FROM DOCUMENT_TENDER
            WHERE DOCUMENT_TENDER_ID= '".$this->getField("DOCUMENT_TENDER_ID")."'"; 
            $this->query = $str;
                  // echo $str;exit();
            return $this->execQuery($str);
        }

        function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.DOCUMENT_TENDER_ID ASC")
        {
            $str = "
            SELECT A.DOCUMENT_TENDER_ID,A.CATEGORY,A.NAME,A.DESCRIPTION,A.PATH1,A.PATH2,A.PATH3,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
            FROM DOCUMENT_TENDER A
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
            $str = "SELECT COUNT(1) AS ROWCOUNT FROM DOCUMENT_TENDER A WHERE 1=1 ".$statement;
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
