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

class KasKecilDetail extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function KasKecilDetail()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("KAS_KECIL_DETAIL_ID", $this->getNextId("KAS_KECIL_DETAIL_ID","KAS_KECIL_DETAIL")); 

        $str = "INSERT INTO KAS_KECIL_DETAIL (
                KAS_KECIL_DETAIL_ID, KAS_KECIL_ID, TANGGAL, KETERANGAN, 
                KATEGORI, DEBET, KREDIT, SALDO, DEBET_USD, KREDIT_USD, 
                SALDO_USD, CREATED_BY, CREATED_DATE
            )VALUES (
                '".$this->getField("KAS_KECIL_DETAIL_ID")."',
                '".$this->getField("KAS_KECIL_ID")."',
                ".$this->getField("TANGGAL").",
                '".$this->getField("KETERANGAN")."',
                '".$this->getField("KATEGORI")."',
                ".$this->getField("DEBET").",
                ".$this->getField("KREDIT").",
                ".$this->getField("SALDO").",
                ".$this->getField("DEBET_USD").",
                ".$this->getField("KREDIT_USD").",
                ".$this->getField("SALDO_USD").",
                '".$this->USER_LOGIN_ID."',
                CURRENT_DATE
            )";

        $this->id = $this->getField("KAS_KECIL_DETAIL_ID");
        $this->query= $str;
            // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
            UPDATE KAS_KECIL_DETAIL
            SET    
                KAS_KECIL_ID ='".$this->getField("KAS_KECIL_ID")."',
                TANGGAL     = ".$this->getField("TANGGAL").",
                KETERANGAN  = '".$this->getField("KETERANGAN")."',
                KATEGORI    = '".$this->getField("KATEGORI")."',
                DEBET       = ".$this->getField("DEBET").",
                KREDIT      = ".$this->getField("KREDIT").",
                SALDO       = ".$this->getField("SALDO").",
                DEBET_USD   = ".$this->getField("DEBET_USD").",
                KREDIT_USD  = ".$this->getField("KREDIT_USD").",
                SALDO_USD   = ".$this->getField("SALDO_USD").",
                UPDATED_BY  = '".$this->USER_LOGIN_ID."',
                UPDATED_DATE = CURRENT_DATE
            WHERE KAS_KECIL_DETAIL_ID= '".$this->getField("KAS_KECIL_DETAIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function updateBalance()
    {
        $str = "
            UPDATE KAS_KECIL_DETAIL
            SET    
                DEBET =".$this->getField("DEBET").",
                KREDIT =".$this->getField("KREDIT").",
                SALDO =".$this->getField("SALDO").",
                KAS_KECIL_ID ='".$this->getField("KAS_KECIL_ID")."'
            WHERE KAS_KECIL_DETAIL_ID= '".$this->getField("KAS_KECIL_DETAIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function updateBalanceUSD()
    {
        $str = "
            UPDATE KAS_KECIL_DETAIL
            SET    
                DEBET_USD =".$this->getField("DEBET_USD").",
                KREDIT_USD =".$this->getField("KREDIT_USD").",
                SALDO_USD =".$this->getField("SALDO_USD").",
                KAS_KECIL_ID ='".$this->getField("KAS_KECIL_ID")."'
            WHERE KAS_KECIL_DETAIL_ID= '".$this->getField("KAS_KECIL_DETAIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM KAS_KECIL_DETAIL
        WHERE KAS_KECIL_DETAIL_ID= '".$this->getField("KAS_KECIL_DETAIL_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function deleteParent($statement= "")
    {
        $str = "DELETE FROM KAS_KECIL_DETAIL
        WHERE KAS_KECIL_ID= '".$this->getField("KAS_KECIL_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.KAS_KECIL_DETAIL_ID ASC")
    {
        $str = "
            SELECT KAS_KECIL_DETAIL_ID, KAS_KECIL_ID, TANGGAL, KETERANGAN, 
                KATEGORI, DEBET, KREDIT, SALDO, DEBET_USD, KREDIT_USD, 
                SALDO_USD
            FROM KAS_KECIL_DETAIL A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM KAS_KECIL_DETAIL A WHERE 1=1 ".$statement;
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
