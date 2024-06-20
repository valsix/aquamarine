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

class StatisticOfferTahun    extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function StatisticOfferTahun()
    {
        $this->Entity();
    }


    

   
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.ID ASC")
    {
        $str = "
        SELECT A.ID,A.KETERANGAN,STATUS
        FROM STATISTIC_OFFER_TAHUN A
        
        WHERE 1=1 ";
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsOfferTahun($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.STATISTIC_ID ASC")
    {
        $str = "
        SELECT A.STATISTIC_ID,A.DESCRIPTION,A.TAHUN
        FROM STATISTIC_OFFER A
        
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM STATISTIC_OFFER_TAHUN A WHERE 1=1 ".$statement;
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
