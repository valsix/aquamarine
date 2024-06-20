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

class WeeklyProgresInline     extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function WeeklyProgresInline()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("WEEKLY_PROGRES_INLINE_ID", $this->getNextId("WEEKLY_PROGRES_INLINE_ID","WEEKLY_PROGRES_INLINE")); 

        $str = "INSERT INTO WEEKLY_PROGRES_INLINE (WEEKLY_PROGRES_INLINE_ID, WEEKLY_PROSES_DETAIL_ID, WEEKLY_PROSES_ID,        PROSES, STATUS, DUE_DATE,PIC_PERSON,  RINCIAN, CREATED_BY, CREATED_DATE, URUT)VALUES (
        '".$this->getField("WEEKLY_PROGRES_INLINE_ID")."',
        '".$this->getField("WEEKLY_PROSES_DETAIL_ID")."',
    '".$this->getField("WEEKLY_PROSES_ID")."',
        '".$this->getField("PROSES")."',
        '".$this->getField("STATUS")."',
        ".$this->getField("DUE_DATE").",
        '".$this->getField("PIC_PERSON")."',
        '".$this->getField("RINCIAN")."',
        '".$this->USERNAME."',
        CURRENT_DATE,
        
        '".$this->getField("URUT")."' 
    )";

    $this->id = $this->getField("WEEKLY_PROGRES_INLINE_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE WEEKLY_PROGRES_INLINE
        SET    
        WEEKLY_PROGRES_INLINE_ID ='".$this->getField("WEEKLY_PROGRES_INLINE_ID")."',
        WEEKLY_PROSES_DETAIL_ID ='".$this->getField("WEEKLY_PROSES_DETAIL_ID")."',
        WEEKLY_PROSES_ID ='".$this->getField("WEEKLY_PROSES_ID")."',
        PIC_PERSON ='".$this->getField("PIC_PERSON")."',
        PROSES ='".$this->getField("PROSES")."',
        STATUS ='".$this->getField("STATUS")."',
        DUE_DATE =".$this->getField("DUE_DATE").",
        
        RINCIAN ='".$this->getField("RINCIAN")."',
        
        UPDATED_BY ='".$this->USERNAME."',
        UPDATED_DATE =CURRENT_DATE,
        URUT ='".$this->getField("URUT")."' 
        WHERE WEEKLY_PROGRES_INLINE_ID= '".$this->getField("WEEKLY_PROGRES_INLINE_ID")."'";
        $this->query = $str;
          // echo $str;
        return $this->execQuery($str);
    }
    function update_path()
    {
        $str = "
        UPDATE WEEKLY_PROGRES_INLINE
        SET    
        
        DUE_PIC ='".$this->getField("DUE_PIC")."'
        WHERE CAST(WEEKLY_PROGRES_INLINE_ID AS VARCHAR)= '".$this->getField("WEEKLY_PROGRES_INLINE_ID")."'";
        $this->query = $str;
          // echo $str;
        return $this->execQuery($str);
    }

    function deleteParentWeekly($statement= "")
    {
        $str = "DELETE FROM WEEKLY_PROGRES_INLINE
        WHERE WEEKLY_PROSES_ID= '".$this->getField("WEEKLY_PROSES_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM WEEKLY_PROGRES_INLINE
        WHERE CAST(WEEKLY_PROGRES_INLINE_ID AS VARCHAR)= '".$this->getField("WEEKLY_PROGRES_INLINE_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }
    function deleteProses($statement= "")
    {
        $str = "DELETE FROM WEEKLY_PROGRES_INLINE
        WHERE WEEKLY_PROSES_DETAIL_ID= '".$this->getField("WEEKLY_PROSES_DETAIL_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.WEEKLY_PROGRES_INLINE_ID ASC")
    {
        $str = "
        SELECT A.WEEKLY_PROGRES_INLINE_ID,A.WEEKLY_PROSES_DETAIL_ID,A.WEEKLY_PROSES_ID,A.PROSES,A.STATUS,A.DUE_DATE,A.DUE_PIC,A.RINCIAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.URUT,A.PIC_PERSON
        FROM WEEKLY_PROGRES_INLINE A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM WEEKLY_PROGRES_INLINE A WHERE 1=1 ".$statement;
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
