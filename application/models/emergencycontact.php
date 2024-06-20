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

class EmergencyContact    extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function EmergencyContact()
    {
        $this->Entity();
    }

            function insert()
            {
                $this->setField("EMERGENCY_CONTACT_ID", $this->getNextId("EMERGENCY_CONTACT_ID","EMERGENCY_CONTACT")); 

                $str = "INSERT INTO EMERGENCY_CONTACT (EMERGENCY_CONTACT_ID,MODUL,FIELD_ID,HP,NAMA,KETERANGAN,CREATED_BY,CREATED_DATE)VALUES (
                '".$this->getField("EMERGENCY_CONTACT_ID")."',
                '".$this->getField("MODUL")."',
                '".$this->getField("FIELD_ID")."',
                '".$this->getField("HP")."',
                '".$this->getField("NAMA")."',
                '".$this->getField("KETERANGAN")."',
                '".$this->USERID."',
                CURRENT_DATE
               
            )";

            $this->id = $this->getField("EMERGENCY_CONTACT_ID");
            $this->query= $str;
                // echo $str;exit();
            return $this->execQuery($str);
        }

        function update()
        {
            $str = "
            UPDATE EMERGENCY_CONTACT
            SET    
            EMERGENCY_CONTACT_ID ='".$this->getField("EMERGENCY_CONTACT_ID")."',
            MODUL ='".$this->getField("MODUL")."',
            FIELD_ID ='".$this->getField("FIELD_ID")."',
            HP ='".$this->getField("HP")."',
            NAMA ='".$this->getField("NAMA")."',
            KETERANGAN ='".$this->getField("KETERANGAN")."',
           
            UPDATED_BY ='".$this->USERID."',
            UPDATED_DATE =CURRENT_DATE
            WHERE EMERGENCY_CONTACT_ID= '".$this->getField("EMERGENCY_CONTACT_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }
        function delete($statement= "")
        {
            $str = "DELETE FROM EMERGENCY_CONTACT
            WHERE EMERGENCY_CONTACT_ID= '".$this->getField("EMERGENCY_CONTACT_ID")."'"; 
            $this->query = $str;
                  // echo $str;exit();
            return $this->execQuery($str);
        }
        function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.EMERGENCY_CONTACT_ID ASC")
        {
            $str = "
            SELECT A.EMERGENCY_CONTACT_ID,A.MODUL,A.FIELD_ID,A.HP,A.NAMA,A.KETERANGAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
            FROM EMERGENCY_CONTACT A
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
            $str = "SELECT COUNT(1) AS ROWCOUNT FROM EMERGENCY_CONTACT A WHERE 1=1 ".$statement;
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
