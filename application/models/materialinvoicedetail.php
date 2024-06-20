<?
/* *******************************************************************************************************
MODUL NAME          : IMASYS
FILE NAME           : 
AUTHOR              : 
VERSION             : 1.0
MODIFICATION DOC    :
DESCRIPTION         : 
***************************************************************************************************** */

/***
 * Entity-base class untuk mengimplementasikan tabel PANGKAT.
 * 
 ***/
include_once("Entity.php");

class MaterialInvoiceDetail extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function MaterialInvoiceDetail()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("MATERIAL_INVOICE_DETAIL_ID", $this->getNextId("MATERIAL_INVOICE_DETAIL_ID","MATERIAL_INVOICE_DETAIL")); 

        $str = "INSERT INTO MATERIAL_INVOICE_DETAIL (
                MATERIAL_INVOICE_DETAIL_ID, MATERIAL_INVOICE_ID, TANGGAL, 
                TANGGAL_PEMBELIAN, TANGGAL_TERIMA, DITERIMA_OLEH, TANGGAL_PEMBAYARAN, 
                NILAI_INVOICE, CREATED_BY, CREATED_DATE
            )VALUES (
                '".$this->getField("MATERIAL_INVOICE_DETAIL_ID")."',
                '".$this->getField("MATERIAL_INVOICE_ID")."',
                ".$this->getField("TANGGAL").",
                ".$this->getField("TANGGAL_PEMBELIAN").",
                ".$this->getField("TANGGAL_TERIMA").",
                '".$this->getField("DITERIMA_OLEH")."',
                ".$this->getField("TANGGAL_PEMBAYARAN").",
                ".$this->getField("NILAI_INVOICE").",
                '".$this->USER_LOGIN_ID."',
                CURRENT_DATE
            )";

        $this->id = $this->getField("MATERIAL_INVOICE_DETAIL_ID");
        $this->query= $str;
            // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
            UPDATE MATERIAL_INVOICE_DETAIL
            SET    
                MATERIAL_INVOICE_ID ='".$this->getField("MATERIAL_INVOICE_ID")."',
                TANGGAL     = ".$this->getField("TANGGAL").",
                TANGGAL_PEMBELIAN    = ".$this->getField("TANGGAL_PEMBELIAN").",
                TANGGAL_TERIMA       = ".$this->getField("TANGGAL_TERIMA").",
                DITERIMA_OLEH      = '".$this->getField("DITERIMA_OLEH")."',
                TANGGAL_PEMBAYARAN       = ".$this->getField("TANGGAL_PEMBAYARAN").",
                NILAI_INVOICE  = ".$this->getField("NILAI_INVOICE").",
                UPDATED_BY  = '".$this->USER_LOGIN_ID."',
                UPDATED_DATE = CURRENT_DATE
            WHERE MATERIAL_INVOICE_DETAIL_ID= '".$this->getField("MATERIAL_INVOICE_DETAIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function updatePathPembelian()
    {
        $str = "
            UPDATE MATERIAL_INVOICE_DETAIL
            SET    
                PATH_PEMBELIAN ='".$this->getField("PATH_PEMBELIAN")."'
            WHERE MATERIAL_INVOICE_DETAIL_ID= '".$this->getField("MATERIAL_INVOICE_DETAIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function updatePathInvoice()
    {
        $str = "
            UPDATE MATERIAL_INVOICE_DETAIL
            SET    
                PATH_INVOICE ='".$this->getField("PATH_INVOICE")."'
            WHERE MATERIAL_INVOICE_DETAIL_ID= '".$this->getField("MATERIAL_INVOICE_DETAIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM MATERIAL_INVOICE_DETAIL
        WHERE MATERIAL_INVOICE_DETAIL_ID= '".$this->getField("MATERIAL_INVOICE_DETAIL_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function deleteParent($statement= "")
    {
        $str = "DELETE FROM MATERIAL_INVOICE_DETAIL
        WHERE MATERIAL_INVOICE_ID= '".$this->getField("MATERIAL_INVOICE_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.MATERIAL_INVOICE_DETAIL_ID ASC")
    {
        $str = "
            SELECT MATERIAL_INVOICE_DETAIL_ID, MATERIAL_INVOICE_ID, TANGGAL, PATH_PEMBELIAN, 
                TANGGAL_PEMBELIAN, TANGGAL_TERIMA, DITERIMA_OLEH, TANGGAL_PEMBAYARAN, PATH_INVOICE, NILAI_INVOICE
            FROM MATERIAL_INVOICE_DETAIL A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM MATERIAL_INVOICE_DETAIL A WHERE 1=1 ".$statement;
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
