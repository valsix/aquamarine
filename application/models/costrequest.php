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

class CostRequest

extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function CostCode()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("COST_REQUEST_ID", $this->getNextId("COST_REQUEST_ID", "COST_REQUEST"));

        $str = "INSERT INTO COST_REQUEST (COST_REQUEST_ID, KODE, TANGGAL, KETERANGAN,PENGAMBILAN, CREATED_BY,        CREATED_DATE)VALUES (
        '" . $this->getField("COST_REQUEST_ID") . "',
        '" . $this->getField("KODE") . "',
        " . $this->getField("TANGGAL") . ",
        
        '" . $this->getField("KETERANGAN") . "',
        '" . $this->getField("PENGAMBILAN") . "',
        '" . $this->USER_LOGIN_ID . "',
        now()
      
    )";

        $this->id = $this->getField("COST_REQUEST_ID");
        $this->query = $str;

        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE COST_REQUEST
        SET    
        COST_REQUEST_ID ='" . $this->getField("COST_REQUEST_ID") . "',
        KODE ='" . $this->getField("KODE") . "',
        TANGGAL =" . $this->getField("TANGGAL") . ",
        TOTAL ='" . $this->getField("TOTAL") . "',
        KETERANGAN ='" . $this->getField("KETERANGAN") . "',        
        PENGAMBILAN ='" . $this->getField("PENGAMBILAN") . "', 
        UPDATED_BY ='" . $this->USER_LOGIN_ID . "',
        UPDATED_DATE =now() 
        WHERE COST_REQUEST_ID= '" . $this->getField("COST_REQUEST_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement = "")
    {
        $str = "DELETE FROM COST_REQUEST
                WHERE COST_REQUEST_ID= '" . $this->getField("COST_REQUEST_ID") . "'";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.COST_REQUEST_ID ASC")
    {
        $str = "SELECT A.COST_REQUEST_ID,A.KODE,A.TANGGAL,A.TOTAL,A.KETERANGAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.PENGAMBILAN,
            B.COMPANY_NAME,B.SUBJECT
                FROM COST_REQUEST A
                LEFT JOIN OFFER B ON A.OFFER_ID = B.OFFER_ID
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParamsMonitoringCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.COST_REQUEST_ID ASC")
    {
        $str = "SELECT A.COST_REQUEST_ID,A.KODE,A.TANGGAL,A.TOTAL,A.KETERANGAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
                FROM COST_REQUEST A
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }


    function getCountByParamsMonitoring($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM COST_REQUEST A WHERE 1=1 " . $statement;
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key =    '$val' ";
        }
        $this->query = $str;
        $this->select($str);
        if ($this->firstRow())
            return $this->getField("ROWCOUNT");
        else
            return 0;
    }
}
