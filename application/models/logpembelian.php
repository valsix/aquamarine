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

class LogPembelian   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function LogPembelian()
    {
        $this->Entity();
    }

            function insert()
            {
                $this->setField("LOG_PEMBELIAN_ID", $this->getNextId("LOG_PEMBELIAN_ID","LOG_PEMBELIAN")); 

                $str = "INSERT INTO LOG_PEMBELIAN ( LOG_PEMBELIAN_ID, PEMBELIAN_ID, PEMBELIAN_DETAIL_ID, EQUIP_ID,        QTY, HARGA, NAMA_ALAT, SERIAL_NUMBER, CREATAD_BY, CREATAD_DATE)VALUES (
                '".$this->getField("LOG_PEMBELIAN_ID")."',
                '".$this->getField("PEMBELIAN_ID")."',
                '".$this->getField("PEMBELIAN_DETAIL_ID")."',
                '".$this->getField("EQUIP_ID")."',
                '".$this->getField("QTY")."',
                '".$this->getField("HARGA")."',
                '".$this->getField("NAMA_ALAT")."',
                '".$this->getField("SERIAL_NUMBER")."',
                '".$this->USERID."',
                CURRENT_DATE

            )";

            $this->id = $this->getField("LOG_PEMBELIAN_ID");
            $this->query= $str;
                // echo $str;exit();
            return $this->execQuery($str);
        }

        function update()
        {
            $str = "
            UPDATE LOG_PEMBELIAN
            SET    
            LOG_PEMBELIAN_ID ='".$this->getField("LOG_PEMBELIAN_ID")."',
            PEMBELIAN_ID ='".$this->getField("PEMBELIAN_ID")."',
            PEMBELIAN_DETAIL_ID ='".$this->getField("PEMBELIAN_DETAIL_ID")."',
            EQUIP_ID ='".$this->getField("EQUIP_ID")."',
            QTY ='".$this->getField("QTY")."',
            HARGA ='".$this->getField("HARGA")."',
            NAMA_ALAT ='".$this->getField("NAMA_ALAT")."',
            SERIAL_NUMBER ='".$this->getField("SERIAL_NUMBER")."',
           
            UPDATED_BY ='".$this->USERID."',
            UPDATED_DATE =CURRENT_DATE
            WHERE LOG_PEMBELIAN_ID= '".$this->getField("LOG_PEMBELIAN_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }
        function delete($statement= "")
        {
            $str = "DELETE FROM LOG_PEMBELIAN
            WHERE LOG_PEMBELIAN_ID= '".$this->getField("LOG_PEMBELIAN_ID")."'"; 
            $this->query = $str;
                  // echo $str;exit();
            return $this->execQuery($str);
        }
        function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.LOG_PEMBELIAN_ID ASC")
        {
            $str = "
            SELECT A.LOG_PEMBELIAN_ID,A.PEMBELIAN_ID,A.PEMBELIAN_DETAIL_ID,A.EQUIP_ID,A.QTY,A.HARGA,A.NAMA_ALAT,A.SERIAL_NUMBER,A.CREATAD_BY,A.CREATAD_DATE,A.UPDATED_BY,A.UPDATED_DATE
            FROM LOG_PEMBELIAN A
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
            $str = "SELECT COUNT(1) AS ROWCOUNT FROM LOG_PEMBELIAN A WHERE 1=1 ".$statement;
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
