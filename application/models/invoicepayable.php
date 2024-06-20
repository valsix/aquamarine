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

class InvoicePayable    extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function InvoicePayable()
    {
        $this->Entity();
    }
    

            function insert()
            {
                $this->setField("INVOICE_PAYABLE_ID", $this->getNextId("INVOICE_PAYABLE_ID","INVOICE_PAYABLE")); 

                $str = "INSERT INTO INVOICE_PAYABLE (INVOICE_PAYABLE_ID, INVOICE_ID, TANGGAL, KETERANGAN, PATH_LINK,        CREATED_BY)VALUES (
                '".$this->getField("INVOICE_PAYABLE_ID")."',
                '".$this->getField("INVOICE_ID")."',
                ".$this->getField("TANGGAL").",
                '".$this->getField("KETERANGAN")."',
                '".$this->getField("PATH_LINK")."',
                '".$this->PEGAWAI_ID."'
               
                
            )";

            $this->id = $this->getField("INVOICE_PAYABLE_ID");
            $this->query= $str;
                // echo $str;exit();
            return $this->execQuery($str);
        }

        function update()
        {
            $str = "
            UPDATE INVOICE_PAYABLE
            SET    
            INVOICE_PAYABLE_ID ='".$this->getField("INVOICE_PAYABLE_ID")."',
            INVOICE_ID ='".$this->getField("INVOICE_ID")."',
            TANGGAL =".$this->getField("TANGGAL").",
            KETERANGAN ='".$this->getField("KETERANGAN")."',
            PATH_LINK ='".$this->getField("PATH_LINK")."',
           
            UPDATED_BY ='".$this->PEGAWAI_ID."'
           
            WHERE INVOICE_PAYABLE_ID= '".$this->getField("INVOICE_PAYABLE_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }
        function delete($statement= "")
        {
            $str = "DELETE FROM INVOICE_PAYABLE
            WHERE INVOICE_PAYABLE_ID= '".$this->getField("INVOICE_PAYABLE_ID")."'"; 
            $this->query = $str;
                  // echo $str;exit();
            return $this->execQuery($str);
        }
        function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.INVOICE_PAYABLE_ID ASC")
        {
            $str = "
            SELECT A.INVOICE_PAYABLE_ID,A.INVOICE_ID,A.TANGGAL,A.KETERANGAN,A.PATH_LINK,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
            FROM INVOICE_PAYABLE A
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
            $str = "SELECT COUNT(1) AS ROWCOUNT FROM INVOICE_PAYABLE A WHERE 1=1 ".$statement;
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
