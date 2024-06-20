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

class SupplierBarang   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function SupplierBarang()
    {
        $this->Entity();
    }

        function insert()
        {
            $this->setField("SUPPLIER_BARANG_ID", $this->getNextId("SUPPLIER_BARANG_ID","SUPPLIER_BARANG")); 

            $str = "INSERT INTO SUPPLIER_BARANG (SUPPLIER_BARANG_ID, SUPPLIER_ID ,NAMA,QTY,SERIAL_NUMBER,HARGA,SATUAN,CURRENCY,CREATED_BY,CREATED_DATE)VALUES (
            '".$this->getField("SUPPLIER_BARANG_ID")."',
            '".$this->getField("SUPPLIER_ID")."',
            '".$this->getField("NAMA")."',
            '".$this->getField("QTY")."',
             '".$this->getField("SERIAL_NUMBER")."',
            '".$this->getField("HARGA")."',
            '".$this->getField("SATUAN")."',
            '".$this->getField("CURRENCY")."',
            '".$this->USERID."',
               CURRENT_DATE
            
        )";

        $this->id = $this->getField("SUPPLIER_BARANG_ID");
        $this->query= $str;
            // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE SUPPLIER_BARANG
        SET    
        SUPPLIER_BARANG_ID ='".$this->getField("SUPPLIER_BARANG_ID")."',
        SUPPLIER_ID ='".$this->getField("SUPPLIER_ID")."',
        NAMA ='".$this->getField("NAMA")."',
        QTY ='".$this->getField("QTY")."',
         SERIAL_NUMBER ='".$this->getField("SERIAL_NUMBER")."',
        HARGA ='".$this->getField("HARGA")."',
        SATUAN ='".$this->getField("SATUAN")."',
         CURRENCY ='".$this->getField("CURRENCY")."',
       
        UPDATED_BY ='".$this->USERID."',
        UPDATED_DATE =CURRENT_DATE
        WHERE SUPPLIER_BARANG_ID= '".$this->getField("SUPPLIER_BARANG_ID")."'";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }
    function delete($statement= "")
    {
        $str = "DELETE FROM SUPPLIER_BARANG
        WHERE SUPPLIER_BARANG_ID= '".$this->getField("SUPPLIER_BARANG_ID")."'"; 
        $this->query = $str;
              // echo $str;exit();
        return $this->execQuery($str);
    }
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SUPPLIER_BARANG_ID ASC")
    {
        $str = "
        SELECT A.SUPPLIER_BARANG_ID,A.SUPPLIER_ID,A.NAMA,A.QTY,A.HARGA,A.SATUAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.SERIAL_NUMBER,A.CURRENCY
        FROM SUPPLIER_BARANG A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM SUPPLIER_BARANG A WHERE 1=1 ".$statement;
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
