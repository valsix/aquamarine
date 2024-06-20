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

class EquipStorage   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function EquipStorage()
    {
        $this->Entity();
    }

            function insert()
            {
                $this->setField("EQUIP_STORAGE_ID", $this->getNextId("EQUIP_STORAGE_ID","EQUIP_STORAGE")); 

                $str = "INSERT INTO EQUIP_STORAGE (EQUIP_STORAGE_ID,NAMA,KET,KODE,CREATED_USER,CREATED_DATE)VALUES (
                '".$this->getField("EQUIP_STORAGE_ID")."',
                '".$this->getField("NAMA")."',
                '".$this->getField("KET")."',
                 '".$this->getField("KODE")."',
                '".$this->USERID."',
                CURRENT_DATE
              
            )";

            $this->id = $this->getField("EQUIP_STORAGE_ID");
            $this->query= $str;
                // echo $str;exit();
            return $this->execQuery($str);
        }

        function update()
        {
            $str = "
            UPDATE EQUIP_STORAGE
            SET    
            EQUIP_STORAGE_ID ='".$this->getField("EQUIP_STORAGE_ID")."',
            NAMA ='".$this->getField("NAMA")."',
            KET ='".$this->getField("KET")."',
             KODE ='".$this->getField("KODE")."',
            UPDATED_BY ='".$this->USERID."',
            UPDATED_DATE =CURRENT_DATE
            WHERE EQUIP_STORAGE_ID= '".$this->getField("EQUIP_STORAGE_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }
        function delete()
        {
            $str = "
            UPDATE EQUIP_STORAGE
            SET    
            STATUS_DELETE ='DELETE'
          
            WHERE EQUIP_STORAGE_ID= '".$this->getField("EQUIP_STORAGE_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }
        function delete2($statement= "")
        {
            $str = "DELETE FROM EQUIP_STORAGE
            WHERE EQUIP_STORAGE_ID= '".$this->getField("EQUIP_STORAGE_ID")."'"; 
            $this->query = $str;
                  // echo $str;exit();
            return $this->execQuery($str);
        }
        function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.EQUIP_STORAGE_ID ASC")
        {
            $str = "
            SELECT A.EQUIP_STORAGE_ID,A.NAMA,A.KET,A.CREATED_USER,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.KODE
            FROM equip_storage A
            WHERE 1=1  AND A.STATUS_DELETE IS NULL ";
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
            $str = "SELECT COUNT(1) AS ROWCOUNT FROM EQUIP_STORAGE A WHERE 1=1 AND A.STATUS_DELETE IS NULL ".$statement;
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
