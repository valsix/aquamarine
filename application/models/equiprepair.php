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

class EquipRepair    extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function EquipRepair()
    {
        $this->Entity();
    }
             

            function insert()
            {
                $this->setField("EQUIP_REPAIR_ID", $this->getNextId("EQUIP_REPAIR_ID","EQUIP_REPAIR")); 

                $str = "INSERT INTO EQUIP_REPAIR (EQUIP_REPAIR_ID, EQUIP_ID, REPAIR_BY, TANGGAL_AWAL, TANGGAL_AKHIR,        KETERANGAN, PATH_FILE, CREATED_BY, CREATED_DATE)VALUES (
                '".$this->getField("EQUIP_REPAIR_ID")."',
                '".$this->getField("EQUIP_ID")."',
                '".$this->getField("REPAIR_BY")."',
                ".$this->getField("TANGGAL_AWAL").",
                ".$this->getField("TANGGAL_AKHIR").",
                '".$this->getField("KETERANGAN")."',
                '".$this->getField("PATH_FILE")."',
                '".$this->USERID."',
              CURRENT_DATE
               
            )";

            $this->id = $this->getField("EQUIP_REPAIR_ID");
            $this->query= $str;
                // echo $str;exit();
            return $this->execQuery($str);
        }

        function update()
        {
            $str = "
            UPDATE EQUIP_REPAIR
            SET    
            EQUIP_REPAIR_ID ='".$this->getField("EQUIP_REPAIR_ID")."',
            EQUIP_ID ='".$this->getField("EQUIP_ID")."',
            REPAIR_BY ='".$this->getField("REPAIR_BY")."',
            TANGGAL_AWAL =".$this->getField("TANGGAL_AWAL").",
            TANGGAL_AKHIR =".$this->getField("TANGGAL_AKHIR").",
            KETERANGAN ='".$this->getField("KETERANGAN")."',
            PATH_FILE ='".$this->getField("PATH_FILE")."',
          
            UPDATED_BY ='".$this->USERID."',
            UPDATED_DATE =CURRENT_DATE
            WHERE EQUIP_REPAIR_ID= '".$this->getField("EQUIP_REPAIR_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }
        function delete($statement= "")
        {
            $str = "DELETE FROM EQUIP_REPAIR
            WHERE EQUIP_REPAIR_ID= '".$this->getField("EQUIP_REPAIR_ID")."'"; 
            $this->query = $str;
                  // echo $str;exit();
            return $this->execQuery($str);
        }
        function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.EQUIP_REPAIR_ID ASC")
        {
            $str = "
            SELECT A.EQUIP_REPAIR_ID,A.EQUIP_ID,A.REPAIR_BY,A.TANGGAL_AWAL,A.TANGGAL_AKHIR,A.KETERANGAN,A.PATH_FILE,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
            FROM EQUIP_REPAIR A
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
            $str = "SELECT COUNT(1) AS ROWCOUNT FROM EQUIP_REPAIR A WHERE 1=1 ".$statement;
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
