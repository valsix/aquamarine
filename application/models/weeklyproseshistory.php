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

class WeeklyProsesHistory        extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function WeeklyProsesHistory()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("WEEKLY_PROSES_HISTORY_ID", $this->getNextId("WEEKLY_PROSES_HISTORY_ID","WEEKLY_PROSES_HISTORY")); 

        $str = "INSERT INTO WEEKLY_PROSES_HISTORY (WEEKLY_PROSES_HISTORY_ID, WEEKLY_PROGRES_INLINE_ID, WEEKLY_PROSES_DETAIL_ID,        WEEKLY_PROSES_ID, PROSES, STATUS, DUE_DATE, DUE_PIC, RINCIAN,MASALAH,SOLUSI,PIC_PERSON,DEPARTEMENT,TANGGAL_MASALAH,        CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("WEEKLY_PROSES_HISTORY_ID")."',
        '".$this->getField("WEEKLY_PROGRES_INLINE_ID")."',
        '".$this->getField("WEEKLY_PROSES_DETAIL_ID")."',
        '".$this->getField("WEEKLY_PROSES_ID")."',
        '".$this->getField("PROSES")."',
        '".$this->getField("STATUS")."',
        ".$this->getField("DUE_DATE").",
        '".$this->getField("DUE_PIC")."',
        '".$this->getField("RINCIAN")."',
        '".$this->getField("MASALAH")."',
        '".$this->getField("SOLUSI")."',
        '".$this->getField("PIC_PERSON")."',
        '".$this->getField("DEPARTEMENT")."',
        ".$this->getField("TANGGAL_MASALAH").",
        '".$this->USERNAME."',
        CURRENT_DATE
       
    )";

    $this->id = $this->getField("WEEKLY_PROSES_HISTORY_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE WEEKLY_PROSES_HISTORY
        SET    
        WEEKLY_PROSES_HISTORY_ID ='".$this->getField("WEEKLY_PROSES_HISTORY_ID")."',
        WEEKLY_PROGRES_INLINE_ID ='".$this->getField("WEEKLY_PROGRES_INLINE_ID")."',
        WEEKLY_PROSES_DETAIL_ID ='".$this->getField("WEEKLY_PROSES_DETAIL_ID")."',
        WEEKLY_PROSES_ID ='".$this->getField("WEEKLY_PROSES_ID")."',
        PROSES ='".$this->getField("PROSES")."',
        STATUS ='".$this->getField("STATUS")."',
        DUE_DATE =".$this->getField("DUE_DATE").",
        DUE_PIC ='".$this->getField("DUE_PIC")."',
        RINCIAN ='".$this->getField("RINCIAN")."',
      
        UPDATED_BY ='".$this->USERNAME."',
        UPDATED_DATE =CURRENT_DATE
        WHERE WEEKLY_PROSES_HISTORY_ID= '".$this->getField("WEEKLY_PROSES_HISTORY_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM WEEKLY_PROSES_HISTORY
        WHERE WEEKLY_PROSES_HISTORY_ID= '".$this->getField("WEEKLY_PROSES_HISTORY_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.WEEKLY_PROSES_HISTORY_ID ASC")
    {
        $str = "
        SELECT A.WEEKLY_PROSES_HISTORY_ID,A.WEEKLY_PROGRES_INLINE_ID,A.WEEKLY_PROSES_DETAIL_ID,A.WEEKLY_PROSES_ID,A.PROSES,A.STATUS,A.DUE_DATE,A.DUE_PIC,A.RINCIAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.SOLUSI,A.MASALAH,A.DEPARTEMENT,A.TANGGAL_MASALAH,A.PIC_PERSON
        FROM WEEKLY_PROSES_HISTORY A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM WEEKLY_PROSES_HISTORY A WHERE 1=1 ".$statement;
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
