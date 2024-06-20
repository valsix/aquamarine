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

class PenyimpananDetail    extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function PenyimpananDetail()
    {
        $this->Entity();
    }

            function insert()
            {
                $this->setField("PENYIMPANAN_DETAIL_ID", $this->getNextId("PENYIMPANAN_DETAIL_ID","PENYIMPANAN_DETAIL")); 

                $str = "INSERT INTO PENYIMPANAN_DETAIL (PENYIMPANAN_DETAIL_ID, PENYIMPANAN_ID, EQUIP_ID,QTY, MASUK_G, MASUK_R,        KELUAR_G, KELUAR_R, PERSEDIAN_G, PERSEDIAN_R, CREATED_BY, CREATED_DATE)VALUES (
                '".$this->getField("PENYIMPANAN_DETAIL_ID")."',
                '".$this->getField("PENYIMPANAN_ID")."',
                '".$this->getField("EQUIP_ID")."',
                 '".$this->getField("QTY")."',
                '".$this->getField("MASUK_G")."',
                '".$this->getField("MASUK_R")."',
                '".$this->getField("KELUAR_G")."',
                '".$this->getField("KELUAR_R")."',
                '".$this->getField("PERSEDIAN_G")."',
                '".$this->getField("PERSEDIAN_R")."',
                '".$this->USERID."',
                CURRENT_DATE

            )";

            $this->id = $this->getField("PENYIMPANAN_DETAIL_ID");
            $this->query= $str;
                // echo $str;exit();
            return $this->execQuery($str);
        }

        function update()
        {
            $str = "
            UPDATE PENYIMPANAN_DETAIL
            SET    
            PENYIMPANAN_DETAIL_ID ='".$this->getField("PENYIMPANAN_DETAIL_ID")."',
            PENYIMPANAN_ID ='".$this->getField("PENYIMPANAN_ID")."',
            EQUIP_ID ='".$this->getField("EQUIP_ID")."',
            QTY ='".$this->getField("QTY")."',
            MASUK_G ='".$this->getField("MASUK_G")."',
            MASUK_R ='".$this->getField("MASUK_R")."',
            KELUAR_G ='".$this->getField("KELUAR_G")."',
            KELUAR_R ='".$this->getField("KELUAR_R")."',
            PERSEDIAN_G ='".$this->getField("PERSEDIAN_G")."',
            PERSEDIAN_R ='".$this->getField("PERSEDIAN_R")."',
          
            UPDATED_BY ='".$this->USERID."',
            UPDATED_DATE =CURRENT_DATE
            WHERE PENYIMPANAN_DETAIL_ID= '".$this->getField("PENYIMPANAN_DETAIL_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }
        function delete($statement= "")
        {
            $str = "DELETE FROM PENYIMPANAN_DETAIL
            WHERE PENYIMPANAN_DETAIL_ID= '".$this->getField("PENYIMPANAN_DETAIL_ID")."'"; 
            $this->query = $str;
                  // echo $str;exit();
            return $this->execQuery($str);
        }
        function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PENYIMPANAN_DETAIL_ID ASC")
        {
            $str = "
            SELECT A.PENYIMPANAN_DETAIL_ID,A.PENYIMPANAN_ID,A.EQUIP_ID,A.MASUK_G,A.MASUK_R,A.KELUAR_G,A.KELUAR_R,A.PERSEDIAN_G,A.PERSEDIAN_R,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,B.EQUIP_NAME,A.QTY
            FROM PENYIMPANAN_DETAIL A
            LEFT JOIN  EQUIPMENT_LIST B  ON B.EQUIP_ID = A.EQUIP_ID
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
            $str = "SELECT COUNT(1) AS ROWCOUNT FROM PENYIMPANAN_DETAIL A WHERE 1=1 ".$statement;
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
