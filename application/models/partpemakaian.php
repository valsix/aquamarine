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

class PartPemakaian   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function PartPemakaian()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("PART_PEMAKAIAN_ID", $this->getNextId("PART_PEMAKAIAN_ID","PART_PEMAKAIAN")); 

        $str = "INSERT INTO PART_PEMAKAIAN (PART_PEMAKAIAN_ID,CODE,TANGGAL,PEMAKAIAN,PROJECT_ID,JUMLAH,MENGETAHUI,KETERANGAN,CREATED_USER,CREATED_DATE)VALUES (
        '".$this->getField("PART_PEMAKAIAN_ID")."',
        '".$this->getField("CODE")."',
        ".$this->getField("TANGGAL").",
        '".$this->getField("PEMAKAIAN")."',
        ".$this->getField("PROJECT_ID").",
        ".$this->getField("JUMLAH").",
        '".$this->getField("MENGETAHUI")."',
         '".$this->getField("KETERANGAN")."',
        '".$this->USERID."',
        CURRENT_DATE
       
    )";

    $this->id = $this->getField("PART_PEMAKAIAN_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

        function update()
        {
            $str = "
            UPDATE PART_PEMAKAIAN
            SET    
            PART_PEMAKAIAN_ID ='".$this->getField("PART_PEMAKAIAN_ID")."',
            CODE ='".$this->getField("CODE")."',
            TANGGAL =".$this->getField("TANGGAL").",
            PEMAKAIAN ='".$this->getField("PEMAKAIAN")."',
            PROJECT_ID =".$this->getField("PROJECT_ID").",
            JUMLAH =".$this->getField("JUMLAH").",
            MENGETAHUI ='".$this->getField("MENGETAHUI")."',
             KETERANGAN ='".$this->getField("KETERANGAN")."',
           
            UPDATED_BY ='".$this->USERID."',
            UPDATED_DATE =CURRENT_DATE
            WHERE PART_PEMAKAIAN_ID= '".$this->getField("PART_PEMAKAIAN_ID")."'";
            $this->query = $str;
              // echo $str;exit;
            return $this->execQuery($str);
        }
        function delete($statement= "")
        {
            $str = "DELETE FROM PART_PEMAKAIAN
            WHERE PART_PEMAKAIAN_ID= '".$this->getField("PART_PEMAKAIAN_ID")."'"; 
            $this->query = $str;
              // echo $str;exit();
            return $this->execQuery($str);
        }
        function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PART_PEMAKAIAN_ID ASC")
        {
            $str = "
            SELECT A.PART_PEMAKAIAN_ID,A.CODE,A.TANGGAL,A.PEMAKAIAN,A.PROJECT_ID,A.JUMLAH,A.MENGETAHUI,A.CREATED_USER,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.KETERANGAN
            FROM part_pemakaian A
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
            $str = "SELECT COUNT(1) AS ROWCOUNT FROM PART_PEMAKAIAN A WHERE 1=1 ".$statement;
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
