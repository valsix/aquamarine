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

class PenyimpananParaf   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function PenyimpananParaf()
    {
        $this->Entity();
    }

        function insert()
        {
            $this->setField("PENYIMPANAN_PARAF_ID", $this->getNextId("PENYIMPANAN_PARAF_ID","PENYIMPANAN_PARAF")); 

            $str = "INSERT INTO PENYIMPANAN_PARAF (PENYIMPANAN_PARAF_ID, PENYIMPANAN_ID, NAMA, CREATED_BY, CREATED_DATE)VALUES (
            '".$this->getField("PENYIMPANAN_PARAF_ID")."',
            '".$this->getField("PENYIMPANAN_ID")."',
            '".$this->getField("NAMA")."',
            '".$this->USERID."',
           CURRENT_DATE
          
        )";

        $this->id = $this->getField("PENYIMPANAN_PARAF_ID");
        $this->query= $str;
            // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE PENYIMPANAN_PARAF
        SET    
        PENYIMPANAN_PARAF_ID ='".$this->getField("PENYIMPANAN_PARAF_ID")."',
        PENYIMPANAN_ID ='".$this->getField("PENYIMPANAN_ID")."',
        NAMA ='".$this->getField("NAMA")."',       
        UPDATED_BY ='".$this->getField("UPDATED_BY")."',
        UPDATED_DATE =CURRENT_DATE
        WHERE PENYIMPANAN_PARAF_ID= '".$this->getField("PENYIMPANAN_PARAF_ID")."'";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }
    function delete($statement= "")
    {
        $str = "DELETE FROM PENYIMPANAN_PARAF
        WHERE PENYIMPANAN_PARAF_ID= '".$this->getField("PENYIMPANAN_PARAF_ID")."'"; 
        $this->query = $str;
              // echo $str;exit();
        return $this->execQuery($str);
    }

    function deleteParent($statement= "")
    {
        $str = "DELETE FROM PENYIMPANAN_PARAF
        WHERE PENYIMPANAN_ID= '".$this->getField("PENYIMPANAN_ID")."'"; 
        $this->query = $str;
              // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PENYIMPANAN_PARAF_ID ASC")
    {
        $str = "
        SELECT A.PENYIMPANAN_PARAF_ID,A.PENYIMPANAN_ID,A.NAMA,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
        FROM PENYIMPANAN_PARAF A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM PENYIMPANAN_PARAF A WHERE 1=1 ".$statement;
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
