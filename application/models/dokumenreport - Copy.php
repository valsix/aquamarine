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

class DokumenReport   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function DokumenReport()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("DOCUMENT_ID", $this->getNextId("DOCUMENT_ID", "DOKUMEN_REPORT"));

        $str = "INSERT INTO DOKUMEN_REPORT 
                (DOCUMENT_ID, REPORT_ID, NAME, DESCRIPTION, PATH, START_DATE,        
                FINISH_DATE, DELIVERY_DATE, LAST_REVISI, INVOICE_DATE, REASON,
                NO_REPORT, NAME_OF_VESSEL, TYPE_OF_VESSEL, LOCATION, CLASS_SOCIETY, SCOPE_OF_WORK, NO_OWR)
                VALUES (
                '" . $this->getField("DOCUMENT_ID") . "',
                '" . $this->getField("REPORT_ID") . "',
                '" . $this->getField("NAME") . "',
                '" . $this->getField("DESCRIPTION") . "',
                '" . $this->getField("PATH") . "',
                " . $this->getField("START_DATE") . ",
                " . $this->getField("FINISH_DATE") . ",
                " . $this->getField("DELIVERY_DATE") . ",
                now(),
                " . $this->getField("INVOICE_DATE") . ",
                '" . $this->getField("REASON") . "',
                '" . $this->getField("NO_REPORT") . "',
                '" . $this->getField("NAME_OF_VESSEL") . "',
                '" . $this->getField("TYPE_OF_VESSEL") . "',
                '" . $this->getField("LOCATION") . "',
                '" . $this->getField("CLASS_SOCIETY") . "', 
                '" . $this->getField("SCOPE_OF_WORK") . "', 
                '" . $this->getField("NO_OWR") . "'
            )";

        $this->id = $this->getField("DOCUMENT_ID");
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }


    function update()
    {
        $str = "UPDATE DOKUMEN_REPORT
                SET    
                DOCUMENT_ID ='" . $this->getField("DOCUMENT_ID") . "',
                REPORT_ID ='" . $this->getField("REPORT_ID") . "',
                NAME ='" . $this->getField("NAME") . "',
                DESCRIPTION ='" . $this->getField("DESCRIPTION") . "',
                PATH ='" . $this->getField("PATH") . "',
                START_DATE =" . $this->getField("START_DATE") . ",
                FINISH_DATE =" . $this->getField("FINISH_DATE") . ",
                DELIVERY_DATE =" . $this->getField("DELIVERY_DATE") . ",
                LAST_REVISI = now(),
                INVOICE_DATE =" . $this->getField("INVOICE_DATE") . ",
                REASON ='" . $this->getField("REASON") . "',
                NO_REPORT ='" . $this->getField("NO_REPORT") . "',
                NAME_OF_VESSEL ='" . $this->getField("NAME_OF_VESSEL") . "',
                TYPE_OF_VESSEL ='" . $this->getField("TYPE_OF_VESSEL") . "',
                LOCATION ='" . $this->getField("LOCATION") . "',
                CLASS_SOCIETY ='" . $this->getField("CLASS_SOCIETY") . "', 
                SCOPE_OF_WORK ='" . $this->getField("SCOPE_OF_WORK") . "', 
                NO_OWR ='" . $this->getField("NO_OWR") . "'

                WHERE DOCUMENT_ID= '" . $this->getField("DOCUMENT_ID") . "'
                ";
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }


    function update_path()
    {
        $str = "UPDATE DOKUMEN_REPORT
                SET    
                PATH ='" . $this->getField("PATH") . "'
            
        WHERE DOCUMENT_ID= '" . $this->getField("DOCUMENT_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }


    function delete($statement = "")
    {
        $str = "DELETE FROM DOKUMEN_REPORT
        WHERE DOCUMENT_ID= '" . $this->getField("DOCUMENT_ID") . "'";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }


    function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
    {
        $str = "SELECT A.DOCUMENT_ID, A.REPORT_ID, A.NAME, A.DESCRIPTION, A.PATH, A.START_DATE,        
                        A.FINISH_DATE, A.DELIVERY_DATE, A.LAST_REVISI, A.INVOICE_DATE, A.REASON,
                        A.NO_REPORT, A.NAME_OF_VESSEL, A.TYPE_OF_VESSEL, A.LOCATION, A.CLASS_SOCIETY, A.SCOPE_OF_WORK, A.NO_OWR
                FROM DOKUMEN_REPORT A
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
    {
        $str = "SELECT A.DOCUMENT_ID, B.REPORT,B.REPORT_ID, A.NAME, A.DESCRIPTION, A.PATH, A.START_DATE, A.FINISH_DATE, A.DELIVERY_DATE, A.INVOICE_DATE, A.REASON,
                        A.NO_REPORT, A.NAME_OF_VESSEL, A.TYPE_OF_VESSEL, A.LOCATION, A.CLASS_SOCIETY, A.SCOPE_OF_WORK, A.NO_OWR
                FROM DOKUMEN_REPORT A, REPORT B
                WHERE 1=1 
                AND A.REPORT_ID = B.REPORT_ID
             ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }
    function getCountByParams($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM DOKUMEN_REPORT A   FROM DOKUMEN_REPORT A, REPORT B WHERE 1=1 AND A.REPORT_ID= B.REPORT_ID " . $statement;
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


    function getCountByParamsMonitoring($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM DOKUMEN_REPORT A WHERE 1=1 " . $statement;
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
