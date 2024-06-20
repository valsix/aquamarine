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

class SupplierPart   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function SupplierPart()
    {
        $this->Entity();
    }
    function insert()
    {
        $this->setField("SUPPLIER_PART_ID", $this->getNextId("SUPPLIER_PART_ID","SUPPLIER_PART")); 

        $str = "INSERT INTO SUPPLIER_PART (SUPPLIER_PART_ID, SUPPLIER_ID,NAMA, QTY, HARGA,SERIAL_NUMBER, COMPETIBLE_ALAT,CURRENCY, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("SUPPLIER_PART_ID")."',
        '".$this->getField("SUPPLIER_ID")."',
         '".$this->getField("NAMA")."',
        '".$this->getField("QTY")."',
        '".$this->getField("HARGA")."',
        '".$this->getField("SERIAL_NUMBER")."',
        '".$this->getField("COMPETIBLE_ALAT")."',
        '".$this->getField("CURRENCY")."',
        '".$this->USERID."',
        CURRENT_DATE

    )";

    $this->id = $this->getField("SUPPLIER_PART_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
}

function update()
{
    $str = "
    UPDATE SUPPLIER_PART
    SET    
    SUPPLIER_PART_ID ='".$this->getField("SUPPLIER_PART_ID")."',
    SUPPLIER_ID ='".$this->getField("SUPPLIER_ID")."',
    QTY ='".$this->getField("QTY")."',
     NAMA ='".$this->getField("NAMA")."',
       SERIAL_NUMBER ='".$this->getField("SERIAL_NUMBER")."',
        CURRENCY ='".$this->getField("CURRENCY")."',
    HARGA ='".$this->getField("HARGA")."',
    COMPETIBLE_ALAT ='".$this->getField("COMPETIBLE_ALAT")."',
    
    UPDATED_BY ='".$this->USERID."',
    UPDATED_DATE = CURRENT_DATE
    WHERE SUPPLIER_PART_ID= '".$this->getField("SUPPLIER_PART_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}
function delete($statement= "")
{
    $str = "DELETE FROM SUPPLIER_PART
    WHERE SUPPLIER_PART_ID= '".$this->getField("SUPPLIER_PART_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}
function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SUPPLIER_PART_ID ASC")
{
    $str = "
    SELECT A.SUPPLIER_PART_ID,A.SUPPLIER_ID,A.QTY,A.HARGA,A.COMPETIBLE_ALAT,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.NAMA,A.SERIAL_NUMBER,A.CURRENCY
    FROM supplier_part A
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
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM SUPPLIER_PART A WHERE 1=1 ".$statement;
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
