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

class PembelianDetail   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function PembelianDetail()
    {
        $this->Entity();
    }

        function insert()
        {
            $this->setField("PEMBELIAN_DETAIL_ID", $this->getNextId("PEMBELIAN_DETAIL_ID","PEMBELIAN_DETAIL")); 

            $str = "INSERT INTO PEMBELIAN_DETAIL (PEMBELIAN_DETAIL_ID, PEMBELIAN_ID, EQUIP_ID, CURRENCY, HARGA,        QTY, EC_ID,TOTAL, KETERANGAN_TOTAL, NAMA_ALAT,SERIAL_NUMBER,DESKRIPSI,CREATED_BY, CREATED_DATE)VALUES (
            '".$this->getField("PEMBELIAN_DETAIL_ID")."',
            '".$this->getField("PEMBELIAN_ID")."',
            ".$this->getField("EQUIP_ID").",
            '".$this->getField("CURRENCY")."',
            '".$this->getField("HARGA")."',
            '".$this->getField("QTY")."',
            '".$this->getField("EC_ID")."',
            '".$this->getField("TOTAL")."',
            '".$this->getField("KETERANGAN_TOTAL")."',
            '".$this->getField("NAMA_ALAT")."',
            '".$this->getField("SERIAL_NUMBER")."',
             '".$this->getField("DESKRIPSI")."',
            '".$this->USERID."',
            CURRENT_DATE

        )";

        $this->id = $this->getField("PEMBELIAN_DETAIL_ID");
        $this->query= $str;
            // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE PEMBELIAN_DETAIL
        SET    
        PEMBELIAN_DETAIL_ID ='".$this->getField("PEMBELIAN_DETAIL_ID")."',
        PEMBELIAN_ID ='".$this->getField("PEMBELIAN_ID")."',
        EQUIP_ID =".$this->getField("EQUIP_ID").",
        CURRENCY ='".$this->getField("CURRENCY")."',
        DESKRIPSI ='".$this->getField("DESKRIPSI")."',
        HARGA ='".$this->getField("HARGA")."',
        NAMA_ALAT ='".$this->getField("NAMA_ALAT")."',
        SERIAL_NUMBER ='".$this->getField("SERIAL_NUMBER")."',
        QTY ='".$this->getField("QTY")."',
         EC_ID ='".$this->getField("EC_ID")."',
        TOTAL ='".$this->getField("TOTAL")."',
        KETERANGAN_TOTAL ='".$this->getField("KETERANGAN_TOTAL")."',      
        UPDATED_BY ='".$this->USERID."',
        UPDATED_DATE =CURRENT_DATE
        WHERE PEMBELIAN_DETAIL_ID= '".$this->getField("PEMBELIAN_DETAIL_ID")."'";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete()
    {
        $str = "
        UPDATE PEMBELIAN_DETAIL
        SET    
        STATUS_DELETE ='DELETE'
       
        WHERE PEMBELIAN_DETAIL_ID= '".$this->getField("PEMBELIAN_DETAIL_ID")."'";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }


    function delete2($statement= "")
    {
        $str = "DELETE FROM PEMBELIAN_DETAIL
        WHERE PEMBELIAN_DETAIL_ID= '".$this->getField("PEMBELIAN_DETAIL_ID")."'"; 
        $this->query = $str;
              // echo $str;exit();
        return $this->execQuery($str);
    }
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PEMBELIAN_DETAIL_ID ASC")
    {
        $str = "
        SELECT A.PEMBELIAN_DETAIL_ID,A.PEMBELIAN_ID,A.EQUIP_ID,A.CURRENCY,A.HARGA,A.QTY,A.TOTAL,A.KETERANGAN_TOTAL,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.EC_ID,C.EC_NAME NAMA_KATEGORI, A.NAMA_ALAT, A.SERIAL_NUMBER NO_SERI
        , B.EQUIP_NAME,B.SERIAL_NUMBER,A.DESKRIPSI FROM PEMBELIAN_DETAIL A 
        LEFT JOIN EQUIPMENT_LIST B ON B.EQUIP_ID = A.EQUIP_ID
        LEFT JOIN EQUIP_CATEGORY C ON C.EC_ID = A.EC_ID
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM PEMBELIAN_DETAIL A WHERE 1=1 AND A.STATUS_DELETE IS NULL ".$statement;
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
