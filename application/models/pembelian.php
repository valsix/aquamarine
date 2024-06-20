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

class Pembelian   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function Pembelian()
    {
        $this->Entity();
    }

        function insert()
        {
            $this->setField("PEMBELIAN_ID", $this->getNextId("PEMBELIAN_ID","PEMBELIAN")); 

            $str = "INSERT INTO PEMBELIAN (PEMBELIAN_ID, TANGGAL, COMPANY_ID, MASTER_PROJECT_ID,CURRENCY,JENIS_PEMBAYARAN,NO_PO,TANGGAL_BAYAR,NO_PEMBELIAN,SUPPLIER_NAMA,SUPPLIER_CONTACT,PPN,PPN_VAL,VOUCHER,CREATED_BY,        CREATED_DATE)VALUES (
            '".$this->getField("PEMBELIAN_ID")."',
            ".$this->getField("TANGGAL").",
            ".$this->getField("COMPANY_ID").",
            ".$this->getField("MASTER_PROJECT_ID").",
            
            '".$this->getField("CURRENCY")."',
            '".$this->getField("JENIS_PEMBAYARAN")."',
            '".$this->getField("NO_PO")."',
            ".$this->getField("TANGGAL_BAYAR").",
             '".$this->getField("NO_PEMBELIAN")."',
            '".$this->getField("SUPPLIER_NAMA")."',
              '".$this->getField("SUPPLIER_CONTACT")."',
               '".$this->getField("PPN")."',
                '".$this->getField("PPN_VAL")."',
                 '".$this->getField("PPN_VAL")."',
            '".$this->USERID."',
            CURRENT_DATE
            
        )";

        $this->id = $this->getField("PEMBELIAN_ID");
        $this->query= $str;
            // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE PEMBELIAN
        SET    
        PEMBELIAN_ID ='".$this->getField("PEMBELIAN_ID")."',
        TANGGAL =".$this->getField("TANGGAL").",
        COMPANY_ID =".$this->getField("COMPANY_ID").",
        NO_PEMBELIAN ='".$this->getField("NO_PEMBELIAN")."',
         PPN ='".$this->getField("PPN")."',
         PPN_VAL ='".$this->getField("PPN_VAL")."',
         VOUCHER ='".$this->getField("VOUCHER")."',
        CURRENCY ='".$this->getField("CURRENCY")."',
        JENIS_PEMBAYARAN ='".$this->getField("JENIS_PEMBAYARAN")."',
        NO_PO ='".$this->getField("NO_PO")."',
         SUPPLIER_NAMA ='".$this->getField("SUPPLIER_NAMA")."',
         SUPPLIER_CONTACT ='".$this->getField("SUPPLIER_CONTACT")."',
        MASTER_PROJECT_ID =".$this->getField("MASTER_PROJECT_ID").",       
        TANGGAL_BAYAR =".$this->getField("TANGGAL_BAYAR").",       
        UPDATED_BY ='".$this->USERID."',
        UPDATED_DATE =CURRENT_DATE
        WHERE PEMBELIAN_ID= '".$this->getField("PEMBELIAN_ID")."'";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }
     function updatePost()
    {
        $str = "
        UPDATE PEMBELIAN
        SET    
        STATUS_POSTING ='POSTING'
       
        WHERE PEMBELIAN_ID= '".$this->getField("PEMBELIAN_ID")."'";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }

     function updateCompany()
    {
        $str = "
        UPDATE PEMBELIAN
        SET    
        COMPANY_ID ='".$this->getField("COMPANY_ID")."'
       
        WHERE PEMBELIAN_ID= '".$this->getField("PEMBELIAN_ID")."'";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }

    function updateLampiran()
    {
        $str = "
        UPDATE PEMBELIAN
        SET    
        LAMPIRAN ='".$this->getField("LAMPIRAN")."'
       
        WHERE PEMBELIAN_ID= '".$this->getField("PEMBELIAN_ID")."'";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete()
    {
        $str = "
        UPDATE PEMBELIAN
        SET    
        STATUS_DELETE ='DELETE'
       
        WHERE PEMBELIAN_ID= '".$this->getField("PEMBELIAN_ID")."' AND STATUS_DELETE IS NULL";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete2($statement= "")
    {
        $str = "DELETE FROM PEMBELIAN
        WHERE PEMBELIAN_ID= '".$this->getField("PEMBELIAN_ID")."'"; 
        $this->query = $str;
              // echo $str;exit();
        return $this->execQuery($str);
    }
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PEMBELIAN_ID ASC")
    {
        $str = "
        SELECT A.PEMBELIAN_ID,A.TANGGAL,A.COMPANY_ID,A.MASTER_PROJECT_ID,A.JENIS_ID,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
      
        
        ,A.CURRENCY,A.JENIS_PEMBAYARAN,A.NO_PO,C.NAMA NAMA_PROJECT,A.STATUS_POSTING,A.TANGGAL_BAYAR,A.NO_PEMBELIAN,A.LAMPIRAN,
        B.NAME NAMA_SUPPLIER,
        B.ADDRESS,B.PHONE,B.FAX,B.EMAIL,B.CP1_NAME,B.CP1_TELP,B.CP2_NAME,B.CP2_TELP,B.LA1_NAME,B.LA1_ADDRESS,B.LA1_PHONE,B.LA1_FAX,B.LA1_EMAIL,B.LA1_CP1,B.LA1_CP2,B.LA2_NAME,B.LA2_ADDRESS,B.LA2_TELP,B.LA2_FAX,B.LA2_EMAIL,B.LA2_CP1,B.LA2_CP2,B.LA1_CP1_PHONE,B.LA1_CP2_PHONE,B.LA2_CP1_PHONE,B.LA2_CP2_PHONE,B.TIPE,B.BARAG_JASA,B.TINGKAT_PELAYANG,B.KUALITAS,B.KETERANGAN_SUB,B.HARGA_KET,B.PROPINSI_ID,B.KABUPATEN_ID,A.PPN,A.PPN_VAL,A.VOUCHER,C.CODE,C.KETERANGAN NAMA_PROJECT_KET

        FROM PEMBELIAN A
        LEFT JOIN COMPANY  B ON B.COMPANY_ID = A.COMPANY_ID
        LEFT JOIN MASTER_PROJECT C ON A.MASTER_PROJECT_ID = C.MASTER_PROJECT_ID
        WHERE 1=1 and A.STATUS_DELETE IS NULL ";
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }


   





     function selectByParamsMonitoringDasboard($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PEMBELIAN_ID ASC")
    {
        $str = "
               SELECT
            A.PEMBELIAN_ID,
            A.TANGGAL,
            B.NAME NAMA_SUPPLIER ,B.ADDRESS
            ,HH.EC_NAMA
                ,HH.EQUIP_NAME
            ,HH.NAMA_ALAT
            ,HH.HARGA
            ,HH.QTY
            ,HH.TOTAL
            ,A.NO_PO
            ,A.JENIS_PEMBAYARAN
            ,D.NAMA NAMA_PROJECT
            ,A.CURRENCY
        FROM
            PEMBELIAN
            A  LEFT JOIN COMPANY B ON B.COMPANY_ID = A.COMPANY_ID
            LEFT JOIN 
            (
            SELECT L.PEMBELIAN_ID,STRING_AGG(L.EC_NAME, 'T&&T&T') EC_NAMA
            ,STRING_AGG(L.EQUIP_NAME, 'T&&T&T') EQUIP_NAME
            ,STRING_AGG(L.NAMA_ALAT, 'T&&T&T') NAMA_ALAT
            ,STRING_AGG(L.HARGA, 'T&&T&T') HARGA
            ,STRING_AGG(L.QTY, 'T&&T&T') QTY
            ,STRING_AGG(L.TOTAL, 'T&&T&T') TOTAL FROM VIEW_ALAT L
            GROUP BY L.PEMBELIAN_ID
            ) HH ON HH.PEMBELIAN_ID =A.PEMBELIAN_ID
                LEFT JOIN MASTER_PROJECT D ON D.MASTER_PROJECT_ID = A.MASTER_PROJECT_ID
        WHERE 1=1 and A.STATUS_DELETE IS NULL "; 
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM PEMBELIAN A WHERE 1=1 and A.STATUS_DELETE IS NULL ".$statement;
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
