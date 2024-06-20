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

class LampiranData   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function LampiranData()
    {
        $this->Entity();
    }

     
            function insert()
            {
                $this->setField("LAMPIRAN_DATA_ID", $this->getNextId("LAMPIRAN_DATA_ID","LAMPIRAN_DATA")); 

                $str = "INSERT INTO LAMPIRAN_DATA (LAMPIRAN_DATA_ID,MODUL_ID,MODUL,NAMA,KETERANGAN,CREATED_BY,CREATED_DATE)VALUES (
                '".$this->getField("LAMPIRAN_DATA_ID")."',
                '".$this->getField("MODUL_ID")."',
                '".$this->getField("MODUL")."',
                '".$this->getField("NAMA")."',
                '".$this->getField("KETERANGAN")."',
                '".$this->USERID."',
                CURRENT_DATE
               
            )";

            $this->id = $this->getField("LAMPIRAN_DATA_ID");
            $this->query= $str;
                // echo $str;exit();
            return $this->execQuery($str);
        }

        function update()
        {
            $str = "
            UPDATE LAMPIRAN_DATA
            SET    
            LAMPIRAN_DATA_ID ='".$this->getField("LAMPIRAN_DATA_ID")."',
            MODUL_ID ='".$this->getField("MODUL_ID")."',
            MODUL ='".$this->getField("MODUL")."',
            NAMA ='".$this->getField("NAMA")."',
           
            
            UPDATED_BY ='".$this->USERID."',
            UPDATED_DATE =CURRENT_DATE
            WHERE LAMPIRAN_DATA_ID= '".$this->getField("LAMPIRAN_DATA_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }

        function delete()
        {
            $str = "
            UPDATE LAMPIRAN_DATA
            SET    
            STATUS_DELETE ='DELETE'
            
            WHERE LAMPIRAN_DATA_ID= '".$this->getField("LAMPIRAN_DATA_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }
        function delete2($statement= "")
        {
            $str = "DELETE FROM LAMPIRAN_DATA
            WHERE LAMPIRAN_DATA_ID= '".$this->getField("LAMPIRAN_DATA_ID")."'"; 
            $this->query = $str;
                  echo $str;exit();
            return $this->execQuery($str);
        }
        function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.LAMPIRAN_DATA_ID ASC")
        {
            $str = "
            SELECT A.LAMPIRAN_DATA_ID,A.MODUL_ID,A.MODUL,A.NAMA,A.KETERANGAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
            FROM lampiran_data A
            WHERE 1=1 AND A.STATUS_DELETE IS NULL ";
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
            $str = "SELECT COUNT(1) AS ROWCOUNT FROM LAMPIRAN_DATA A WHERE 1=1 ".$statement;
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
