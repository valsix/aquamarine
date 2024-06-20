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

class Penyimpanan   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function Penyimpanan()
    {
        $this->Entity();
    }

        function insert()
        {
            $this->setField("PENYIMPANAN_ID", $this->getNextId("PENYIMPANAN_ID","PENYIMPANAN")); 

            $str = "INSERT INTO PENYIMPANAN (PENYIMPANAN_ID, TANGGAL,  LOKASI, KETERANGAN, CREATED_BY,        CREATED_DATE)VALUES (
            '".$this->getField("PENYIMPANAN_ID")."',
            ".$this->getField("TANGGAL").",
            
            '".$this->getField("LOKASI")."',
            '".$this->getField("KETERANGAN")."',
            '".$this->USERID."',
            CURRENT_DATE

        )";

        $this->id = $this->getField("PENYIMPANAN_ID");
        $this->query= $str;
            // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE PENYIMPANAN
        SET    
        PENYIMPANAN_ID ='".$this->getField("PENYIMPANAN_ID")."',
        TANGGAL =".$this->getField("TANGGAL").",
       
        LOKASI ='".$this->getField("LOKASI")."',
        KETERANGAN ='".$this->getField("KETERANGAN")."',       
        UPDATED_BY ='".$this->USERID."',
        UPDATED_DATE =  CURRENT_DATE
        WHERE PENYIMPANAN_ID= '".$this->getField("PENYIMPANAN_ID")."'";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }
    function delete($statement= "")
    {
        $str = "DELETE FROM PENYIMPANAN
        WHERE PENYIMPANAN_ID= '".$this->getField("PENYIMPANAN_ID")."'"; 
        $this->query = $str;
              // echo $str;exit();
        return $this->execQuery($str);
    }
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PENYIMPANAN_ID ASC")
    {
        $str = "
        SELECT A.PENYIMPANAN_ID,A.TANGGAL,A.JENIS_ID,A.LOKASI,A.KETERANGAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
        FROM PENYIMPANAN A
        WHERE 1=1 ";
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }
    function selectByParamsMonitoringDasboard($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PENYIMPANAN_ID ASC")
    {
        $str = "
                    SELECT A.PENYIMPANAN_ID,A.TANGGAL,XD.NAMA_PARAF ,XC.QTY, XC.MASUK_G,
                XC.MASUK_R,XC.KELUAR_G,XC.KELUAR_R,XC.PERSEDIAN_G,XC.PERSEDIAN_R
                FROM PENYIMPANAN A
                LEFT JOIN (
                SELECT CC.PENYIMPANAN_ID, SUM(QTY) QTY,SUM(COALESCE (MASUK_G::NUMERIC,0)) MASUK_G, SUM(COALESCE (MASUK_R::NUMERIC,0)) MASUK_R,SUM(COALESCE (KELUAR_G::NUMERIC,0)) KELUAR_G,
                SUM(COALESCE (KELUAR_R::NUMERIC,0)) KELUAR_R,SUM(COALESCE (PERSEDIAN_G::NUMERIC,0)) PERSEDIAN_G, SUM(COALESCE (PERSEDIAN_R::NUMERIC,0)) PERSEDIAN_R  
                FROM PENYIMPANAN_DETAIL CC GROUP BY CC.PENYIMPANAN_ID
                ) XC ON XC.PENYIMPANAN_ID =A.PENYIMPANAN_ID
                LEFT JOIN (
                SELECT PENYIMPANAN_ID, STRING_AGG(NAMA, ',') NAMA_PARAF FROM PENYIMPANAN_PARAF GROUP BY PENYIMPANAN_ID
                ) XD   ON XD.PENYIMPANAN_ID =A.PENYIMPANAN_ID
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM PENYIMPANAN A WHERE 1=1 ".$statement;
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
