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

class StatisticDetil   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function StatisticDetil()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("STATISTIC_DETIL_ID", $this->getNextId("STATISTIC_DETIL_ID","STATISTIC_DETIL")); 

        $str = "INSERT INTO STATISTIC_DETIL (STATISTIC_DETIL_ID, STATISTIC_ID, DESCRIPTION, VALUE, COLOR)VALUES (
        '".$this->getField("STATISTIC_DETIL_ID")."',
        '".$this->getField("STATISTIC_ID")."',
        '".$this->getField("DESCRIPTION")."',
        '".$this->getField("VALUE")."',
        '".$this->getField("COLOR")."' 
    )";

    $this->id = $this->getField("STATISTIC_DETIL_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }



    function update()
    {
        $str = "
        UPDATE STATISTIC_DETIL
        SET    
        STATISTIC_DETIL_ID ='".$this->getField("STATISTIC_DETIL_ID")."',
        STATISTIC_ID ='".$this->getField("STATISTIC_ID")."',
        DESCRIPTION ='".$this->getField("DESCRIPTION")."',
        VALUE ='".$this->getField("VALUE")."',
        COLOR ='".$this->getField("COLOR")."' 
        WHERE STATISTIC_DETIL_ID= '".$this->getField("STATISTIC_DETIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }
      function delete_parent(){
        $str = "DELETE FROM STATISTIC_DETIL
        WHERE CAST(STATISTIC_ID AS VARCHAR)= '".$this->getField("STATISTIC_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
        
    }
    function delete($statement= "")
    {
        $str = "DELETE FROM STATISTIC_DETIL
        WHERE STATISTIC_DETIL_ID= '".$this->getField("STATISTIC_DETIL_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.STATISTIC_DETIL_ID ASC")
    {
        $str = "
        SELECT A.STATISTIC_DETIL_ID,A.STATISTIC_ID,A.DESCRIPTION,A.VALUE,A.COLOR
        FROM STATISTIC_DETIL A
        WHERE 1=1 ";
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoringOffer($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.STATISTIC_ID ASC")
    {
        $str = "
        SELECT A.STATISTIC_ID,A.DESCRIPTION,A.VALUE,A.COLOR,A.TAHUN
        FROM STATISTIC_OFFER_DETIL A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM STATISTIC_DETIL A WHERE 1=1 ".$statement;
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
