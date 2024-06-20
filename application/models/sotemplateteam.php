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

class SoTeam  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function SoTeam()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("SO_TEAM_ID", $this->getNextId("SO_TEAM_ID","SO_TEAM")); 

        $str = "INSERT INTO SO_TEAM (SO_TEAM_ID, SO_ID, DOCUMENT_ID, DESCRIPTION, POSITION)VALUES (
        '".$this->getField("SO_TEAM_ID")."',
        '".$this->getField("SO_ID")."',
        ".$this->getField("DOCUMENT_ID").",
        '".$this->getField("DESCRIPTION")."',
        '".$this->getField("POSITION")."' 
    )";

    $this->id = $this->getField("SO_TEAM_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE SO_TEAM
        SET    
        SO_TEAM_ID ='".$this->getField("SO_TEAM_ID")."',
        SO_ID ='".$this->getField("SO_ID")."',
        DOCUMENT_ID =".$this->getField("DOCUMENT_ID").",
        DESCRIPTION ='".$this->getField("DESCRIPTION")."',
        POSITION ='".$this->getField("POSITION")."' 
        WHERE SO_TEAM_ID= '".$this->getField("SO_TEAM_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM SO_TEAM
        WHERE SO_TEAM_ID= '".$this->getField("SO_TEAM_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SO_TEAM_ID ASC")
    {
        $str = "
        SELECT A.SO_TEAM_ID,A.SO_ID,A.DOCUMENT_ID,A.DESCRIPTION
        FROM SO_TEAM A
        WHERE 1=1 ";
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoringTeam($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SO_TEAM_ID ASC")
    {
        $str = "
        SELECT A.SO_TEAM_ID,
        A.SO_ID, A.DOCUMENT_ID,
        B.NAME, COALESCE(A.POSITION, C.JENIS) JENIS
        FROM SO_TEAM A, DOKUMEN_KUALIFIKASI B, JENIS_KUALIFIKASI C
        WHERE A.DOCUMENT_ID = B.DOCUMENT_ID AND B.JENIS_ID = C.JENIS_ID
        ";
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }
    function getCountByParamsMonitoringTeam($paramsArray=array(), $statement="")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT  FROM SO_TEAM A, DOKUMEN_KUALIFIKASI B, JENIS_KUALIFIKASI C
        WHERE A.DOCUMENT_ID = B.DOCUMENT_ID AND B.JENIS_ID = C.JENIS_ID AND 1=1 ".$statement;
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

    function getCountByParamsMonitoring($paramsArray=array(), $statement="")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM SO_TEAM A WHERE 1=1 ".$statement;
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
