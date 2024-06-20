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

class MasterSolusi    extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function MasterSolusi()
    {
        $this->Entity();
    }

            function insert()
            {
                $this->setField("MASTER_SOLUSI_ID", $this->getNextId("MASTER_SOLUSI_ID","MASTER_SOLUSI")); 

                $str = "INSERT INTO MASTER_SOLUSI (MASTER_SOLUSI_ID, KETERANGAN, CREATED_BY, CREATED_DATE, NAMA)VALUES (
                '".$this->getField("MASTER_SOLUSI_ID")."',
                '".$this->getField("KETERANGAN")."',
                '".$this->USERNAME."',
                CURRENT_DATE,
                
                '".$this->getField("NAMA")."'
            )";

            $this->id = $this->getField("MASTER_SOLUSI_ID");
            $this->query= $str;
                // echo $str;exit();
            return $this->execQuery($str);
        }

        function update()
        {
            $str = "
            UPDATE MASTER_SOLUSI
            SET    
            MASTER_SOLUSI_ID ='".$this->getField("MASTER_SOLUSI_ID")."',
            KETERANGAN ='".$this->getField("KETERANGAN")."',
            
            UPDATED_BY ='".$this->USERNAME."',
            UPDATED_DATE =CURRENT_DATE,
            NAMA ='".$this->getField("NAMA")."' 
            WHERE MASTER_SOLUSI_ID= '".$this->getField("MASTER_SOLUSI_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }
        function delete($statement= "")
        {
            $str = "DELETE FROM MASTER_SOLUSI
            WHERE MASTER_SOLUSI_ID= '".$this->getField("MASTER_SOLUSI_ID")."'"; 
            $this->query = $str;
                  // echo $str;exit();
            return $this->execQuery($str);
        }
        function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.MASTER_SOLUSI_ID ASC")
        {
            $str = "
            SELECT A.MASTER_SOLUSI_ID,A.KETERANGAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.NAMA
            FROM MASTER_SOLUSI A
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
            $str = "SELECT COUNT(1) AS ROWCOUNT FROM MASTER_SOLUSI A WHERE 1=1 ".$statement;
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
