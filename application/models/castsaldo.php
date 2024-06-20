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

class CastSaldo   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function CastSaldo()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("CAST_SALDO_ID", $this->getNextId("CAST_SALDO_ID", "CAST_SALDO"));

        $str = "INSERT INTO CAST_SALDO (CAST_SALDO_ID, TANGGAL, KETERANGAN, CREATED_BY, CREATED_DATE     )VALUES (
        '" . $this->getField("CAST_SALDO_ID") . "',
            " . $this->getField("TANGGAL") . ",
        '" . $this->getField("KETERANGAN") . "',
        '" . $this->USER_LOGIN_ID . "',
       now()
      
    )";

        $this->id = $this->getField("CAST_SALDO_ID");
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE CAST_SALDO
        SET    
        CAST_SALDO_ID ='" . $this->getField("CAST_SALDO_ID") . "',
        TANGGAL =" . $this->getField("TANGGAL") . ",
        KETERANGAN ='" . $this->getField("KETERANGAN") . "',
       
        UPDATED_BY ='" . $this->USER_LOGIN_ID . "',
        UPDATED_DATE =now()
        WHERE CAST_SALDO_ID= '" . $this->getField("CAST_SALDO_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement = "")
    {
        $str = "DELETE FROM CAST_SALDO
        WHERE CAST_SALDO_ID= '" . $this->getField("CAST_SALDO_ID") . "'";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.CAST_SALDO_ID ASC")
    {
        $str = "
        SELECT A.CAST_SALDO_ID,A.TANGGAL,A.KETERANGAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
        FROM CAST_SALDO A
        WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParamsMonitoringCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.CAST_SALDO_ID ASC")
    {
        $str = "SELECT A.CAST_SALDO_ID,A.TANGGAL,A.KETERANGAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
                FROM CAST_SALDO A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM CAST_SALDO A WHERE 1=1 " . $statement;
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
