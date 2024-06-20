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

class FormatQm  extends Entity
{

    var $query;
    /**
     * Class constructor.
     **/
    function FormatQm()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("FORMAT_ID", $this->getNextId("FORMAT_ID","FORMAT_QM")); 

        $str = "INSERT INTO FORMAT_QM (FORMAT_ID, FORMAT, DESCRIPTION)VALUES (
        '".$this->getField("FORMAT_ID")."',
        '".$this->getField("FORMAT")."',
        '".$this->getField("DESCRIPTION")."' 
    )";

    $this->id = $this->getField("FORMAT_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE FORMAT_QM
        SET    
        FORMAT_ID ='".$this->getField("FORMAT_ID")."',
        FORMAT ='".$this->getField("FORMAT")."',
        DESCRIPTION ='".$this->getField("DESCRIPTION")."' 
        WHERE FORMAT_ID= '".$this->getField("FORMAT_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM FORMAT_QM
        WHERE FORMAT_ID= '".$this->getField("FORMAT_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.FORMAT_ID ASC")
    {
        $str = "
        SELECT A.FORMAT_ID,A.FORMAT,A.DESCRIPTION
        FROM FORMAT_QM A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM FORMAT_QM A WHERE 1=1 ".$statement;
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
