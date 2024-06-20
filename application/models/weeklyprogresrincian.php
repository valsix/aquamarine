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

class WeeklyProgresRincian     extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function WeeklyProgresRincian()
    {
        $this->Entity();
    }

            function insert()
            {
                $this->setField("WEEKLY_PROGRES_RINCIAN_ID", $this->getNextId("WEEKLY_PROGRES_RINCIAN_ID","WEEKLY_PROGRES_RINCIAN")); 

                $str = "INSERT INTO WEEKLY_PROGRES_RINCIAN (WEEKLY_PROGRES_RINCIAN_ID, WEEKLY_PROGRES_INLINE_ID, WEEKLY_PROSES_DETAIL_ID,        WEEKLY_PROSES_ID, RINCIAN, CREATED_BY, CREATED_DATE, URUT)VALUES (
                '".$this->getField("WEEKLY_PROGRES_RINCIAN_ID")."',
                '".$this->getField("WEEKLY_PROGRES_INLINE_ID")."',
                '".$this->getField("WEEKLY_PROSES_DETAIL_ID")."',
                '".$this->getField("WEEKLY_PROSES_ID")."',
                '".$this->getField("RINCIAN")."',
                '".$this->USERNAME."',
                CURRENT_DATE,
                
                '".$this->getField("URUT")."' 
            )";

            $this->id = $this->getField("WEEKLY_PROGRES_RINCIAN_ID");
            $this->query= $str;
                // echo $str;exit();
            return $this->execQuery($str);
        }

        function update()
        {
            $str = "
            UPDATE WEEKLY_PROGRES_RINCIAN
            SET    
            WEEKLY_PROGRES_RINCIAN_ID ='".$this->getField("WEEKLY_PROGRES_RINCIAN_ID")."',
            WEEKLY_PROGRES_INLINE_ID ='".$this->getField("WEEKLY_PROGRES_INLINE_ID")."',
            WEEKLY_PROSES_DETAIL_ID ='".$this->getField("WEEKLY_PROSES_DETAIL_ID")."',
            WEEKLY_PROSES_ID ='".$this->getField("WEEKLY_PROSES_ID")."',
            RINCIAN ='".$this->getField("RINCIAN")."',
          
            UPDATED_BY ='".$this->USERNAME."',
            UPDATED_DATE =CURRENT_DATE,
            URUT ='".$this->getField("URUT")."' 
            WHERE WEEKLY_PROGRES_RINCIAN_ID= '".$this->getField("WEEKLY_PROGRES_RINCIAN_ID")."'";
            $this->query = $str;
          // echo $str;exit;
            return $this->execQuery($str);
        }

        function delete($statement= "")
        {
            $str = "DELETE FROM WEEKLY_PROGRES_RINCIAN
            WHERE CAST(WEEKLY_PROGRES_RINCIAN_ID AS VARCHAR)= '".$this->getField("WEEKLY_PROGRES_RINCIAN_ID")."'"; 
            $this->query = $str;
          // echo $str;exit();
            return $this->execQuery($str);
        }

         function deleteParentWeekly($statement= "")
        {
            $str = "DELETE FROM WEEKLY_PROGRES_RINCIAN
            WHERE CAST(WEEKLY_PROSES_ID AS VARCHAR)= '".$this->getField("WEEKLY_PROSES_ID")."'"; 
            $this->query = $str;
          // echo $str;exit();
            return $this->execQuery($str);
        }
        function deleteProses($statement= "")
        {
            $str = "DELETE FROM WEEKLY_PROGRES_RINCIAN
            WHERE CAST(WEEKLY_PROSES_DETAIL_ID AS VARCHAR)= '".$this->getField("WEEKLY_PROSES_DETAIL_ID")."'"; 
            $this->query = $str;
          // echo $str;exit();
            return $this->execQuery($str);
        }
        function deleteInline($statement= "")
        {
            $str = "DELETE FROM WEEKLY_PROGRES_RINCIAN
            WHERE CAST(WEEKLY_PROGRES_INLINE_ID AS VARCHAR)= '".$this->getField("WEEKLY_PROGRES_INLINE_ID")."'"; 
            $this->query = $str;
          // echo $str;exit();
            return $this->execQuery($str);
        }

        function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.WEEKLY_PROGRES_RINCIAN_ID ASC")
        {
            $str = "
            SELECT A.WEEKLY_PROGRES_RINCIAN_ID,A.WEEKLY_PROGRES_INLINE_ID,A.WEEKLY_PROSES_DETAIL_ID,A.WEEKLY_PROSES_ID,A.RINCIAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.URUT
            FROM WEEKLY_PROGRES_RINCIAN A
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
            $str = "SELECT COUNT(1) AS ROWCOUNT FROM WEEKLY_PROGRES_RINCIAN A WHERE 1=1 ".$statement;
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
