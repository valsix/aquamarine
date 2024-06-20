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

class Statistic   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function Statistic()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("STATISTIC_ID", $this->getNextId("STATISTIC_ID", "STATISTIC"));

        $str = "INSERT INTO STATISTIC (STATISTIC_ID, DESCRIPTION,TAHUN)VALUES (
        '" . $this->getField("STATISTIC_ID") . "',
        '" . $this->getField("DESCRIPTION") . "' ,
         '" . $this->getField("TAHUN") . "' 
    )";

        $this->id = $this->getField("STATISTIC_ID");
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE STATISTIC
        SET    
        STATISTIC_ID ='" . $this->getField("STATISTIC_ID") . "',
        TAHUN ='" . $this->getField("TAHUN") . "',
        DESCRIPTION ='" . $this->getField("DESCRIPTION") . "' 
        WHERE STATISTIC_ID= '" . $this->getField("STATISTIC_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement = "")
    {
        $str = "DELETE FROM STATISTIC
        WHERE STATISTIC_ID= '" . $this->getField("STATISTIC_ID") . "'";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }
     function deleteParent($statement = "")
    {
        $str = "DELETE FROM STATISTIC_DETIL
        WHERE STATISTIC_ID= '" . $this->getField("STATISTIC_ID") . "'";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.STATISTIC_ID ASC")
    {
        $str = "SELECT A.STATISTIC_ID,A.DESCRIPTION,A.TAHUN
                FROM STATISTIC A
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParamsMonitoringOffer($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.STATISTIC_ID ASC")
    {
        $str = "SELECT A.STATISTIC_ID,A.DESCRIPTION
                FROM STATISTIC_OFFER A
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParamsMonitoringAll($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.STATISTIC_ID ASC")
    {
        $str = "SELECT A.STATISTIC_ID,A.DESCRIPTION, '' AS TIPE, A.TAHUN
                FROM STATISTIC A
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " ";
        $str .= "
            UNION ALL
            SELECT A.STATISTIC_ID,A.DESCRIPTION, 'OFFER' AS TIPE, TAHUN
                FROM STATISTIC_OFFER A
        ";

        $str .= $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParamsMonitoringCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.STATISTIC_ID ASC")
    {
        $str = "SELECT A.STATISTIC_ID,A.DESCRIPTION
                FROM STATISTIC A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM STATISTIC A WHERE 1=1 " . $statement;
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
