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

class WeeklyProses      extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function WeeklyProses()
    {
        $this->Entity();
    }

        function insert()
        {
            $this->setField("WEEKLY_PROSES_ID", $this->getNextId("WEEKLY_PROSES_ID","WEEKLY_PROSES")); 

            $str = "INSERT INTO WEEKLY_PROSES (WEEKLY_PROSES_ID, DEPARTEMENT_ID, MASALAH, TANGGAL_MASALAH, CREATED_BY,        CREATED_DATE)VALUES (
            '".$this->getField("WEEKLY_PROSES_ID")."',
            '".$this->getField("DEPARTEMENT_ID")."',
            '".$this->getField("MASALAH")."',
            ".$this->getField("TANGGAL_MASALAH").",
            '".$this->USERNAME."',
            CURRENT_DATE

        )";

        $this->id = $this->getField("WEEKLY_PROSES_ID");
        $this->query= $str;
            // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE WEEKLY_PROSES
        SET    
        WEEKLY_PROSES_ID ='".$this->getField("WEEKLY_PROSES_ID")."',
        DEPARTEMENT_ID ='".$this->getField("DEPARTEMENT_ID")."',
        MASALAH ='".$this->getField("MASALAH")."',
        TANGGAL_MASALAH =".$this->getField("TANGGAL_MASALAH").",
        
        UPDATED_BY ='".$this->USERNAME."',
        UPDATED_DATE =CURRENT_DATE 
        WHERE WEEKLY_PROSES_ID= '".$this->getField("WEEKLY_PROSES_ID")."'";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }
    function delete($statement= "")
    {
        $str = "DELETE FROM WEEKLY_PROSES
        WHERE WEEKLY_PROSES_ID= '".$this->getField("WEEKLY_PROSES_ID")."'"; 
        $this->query = $str;
              // echo $str;exit();
        return $this->execQuery($str);
    }
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.WEEKLY_PROSES_ID ASC")
    {
        $str = "
        SELECT A.WEEKLY_PROSES_ID,A.DEPARTEMENT_ID,A.MASALAH,A.TANGGAL_MASALAH,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,C.NAMA NAMA_DEPARTEMEN
        FROM WEEKLY_PROSES A
        LEFT JOIN DEPARTEMENT C ON C.DEPARTEMENT_ID = A.DEPARTEMENT_ID
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM WEEKLY_PROSES A WHERE 1=1 ".$statement;
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
