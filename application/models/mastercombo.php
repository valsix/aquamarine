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

class MasterCombo   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function MasterCombo()
    {
        $this->Entity();
    }

                function insert()
                {
                    $this->setField("MASTER_COMBO_ID", $this->getNextId("MASTER_COMBO_ID","MASTER_COMBO")); 

                    $str = "INSERT INTO MASTER_COMBO (MASTER_COMBO_ID,NAMA,KETERANGAN,MODUL,CREATED_BY,CREATED_DATE)VALUES (
                    '".$this->getField("MASTER_COMBO_ID")."',
                    '".$this->getField("NAMA")."',
                    '".$this->getField("KETERANGAN")."',
                   
                    '".$this->getField("MODUL")."',
                    '".$this->USERID."',
                   CURRENT_DATE
                   
                )";

                $this->id = $this->getField("MASTER_COMBO_ID");
                $this->query= $str;
                    // echo $str;exit();
                return $this->execQuery($str);
            }

            function update()
            {
                $str = "
                UPDATE MASTER_COMBO
                SET    
                MASTER_COMBO_ID ='".$this->getField("MASTER_COMBO_ID")."',
                NAMA ='".$this->getField("NAMA")."',
                KETERANGAN ='".$this->getField("KETERANGAN")."',
               
                MODUL ='".$this->getField("MODUL")."',
               
                UPDATED_BY ='".$this->USERID."',
                UPDATED_DATE =CURRENT_DATE
                WHERE MASTER_COMBO_ID= '".$this->getField("MASTER_COMBO_ID")."'";
                $this->query = $str;
                      // echo $str;exit;
                return $this->execQuery($str);
            }


            function delete()
            {
                $str = "
                UPDATE MASTER_COMBO
                SET    
                STATUS_DELETE ='DELETE'
              
                WHERE MASTER_COMBO_ID= '".$this->getField("MASTER_COMBO_ID")."'";
                $this->query = $str;
                      // echo $str;exit;
                return $this->execQuery($str);
            }


            function delete2($statement= "")
            {
                $str = "DELETE FROM MASTER_COMBO
                WHERE MASTER_COMBO_ID= '".$this->getField("MASTER_COMBO_ID")."'"; 
                $this->query = $str;
                      // echo $str;exit();
                return $this->execQuery($str);
            }
            function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.MASTER_COMBO_ID ASC")
            {
                $str = "
                SELECT A.MASTER_COMBO_ID,A.NAMA,A.KETERANGAN,A.STATUS_DELETE,A.MODUL,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
                FROM master_combo A
                WHERE 1=1 AND A.STATUS_DELETE IS NULL";
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
                $str = "SELECT COUNT(1) AS ROWCOUNT FROM MASTER_COMBO A WHERE 1=1 ".$statement;
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
