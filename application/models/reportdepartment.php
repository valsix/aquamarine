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

class ReportDepartment  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function ReportDepartment()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("REPORT_DEPARTMENT_ID", $this->getNextId("REPORT_DEPARTMENT_ID", "REPORT_DEPARTMENT"));

        $str = "INSERT INTO REPORT_DEPARTMENT (REPORT_DEPARTMENT_ID, DEPARTMENT, PROJECT, CLIENT, SEND_DATE, RECEIVE_DATE, DESCRIPTION, PATH, CREATED_BY, CREATED_DATE)VALUES (
            '" . $this->getField("REPORT_DEPARTMENT_ID") . "',
            '" . $this->getField("DEPARTMENT") . "',
            '" . $this->getField("PROJECT") . "',
            '" . $this->getField("CLIENT") . "',
            " . $this->getField("SEND_DATE") . ",
            " . $this->getField("RECEIVE_DATE") . ",
            '" . $this->getField("DESCRIPTION") . "',
            '" . $this->getField("PATH") . "',
            '" . $this->getField("CREATED_BY") . "',
            CURRENT_DATE
        )";

        $this->id = $this->getField("REPORT_DEPARTMENT_ID");
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE REPORT_DEPARTMENT
        SET    
            DEPARTMENT ='" . $this->getField("DEPARTMENT") . "',
            PROJECT ='" . $this->getField("PROJECT") . "',
            CLIENT ='" . $this->getField("CLIENT") . "',
            SEND_DATE =" . $this->getField("SEND_DATE") . ",
            RECEIVE_DATE =" . $this->getField("RECEIVE_DATE") . ",
            DESCRIPTION ='" . $this->getField("DESCRIPTION") . "',
            PATH ='" . $this->getField("PATH") . "',
            CREATED_BY ='" . $this->getField("CREATED_BY") . "',
            CREATED_DATE =CURRENT_DATE,
            WHERE REPORT_DEPARTMENT_ID= '" . $this->getField("REPORT_DEPARTMENT_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }
    function update_path()
    {
        $str = "
        UPDATE REPORT_DEPARTMENT
        SET    
        PATH ='" . $this->getField("PATH") . "'
       
        WHERE REPORT_DEPARTMENT_ID= '" . $this->getField("REPORT_DEPARTMENT_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement = "")
    {
        $str = "DELETE FROM REPORT_DEPARTMENT
                WHERE REPORT_DEPARTMENT_ID= " . $this->getField("REPORT_DEPARTMENT_ID") . "";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.REPORT_DEPARTMENT_ID ASC")
    {
        $str = "SELECT REPORT_DEPARTMENT_ID, DEPARTMENT, PROJECT, CLIENT, SEND_DATE, 
                    RECEIVE_DATE, DESCRIPTION, PATH
                FROM REPORT_DEPARTMENT A
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }


    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.REPORT_DEPARTMENT_ID ASC")
    {
        $str = "SELECT REPORT_DEPARTMENT_ID, DEPARTMENT, PROJECT, CLIENT, SEND_DATE, 
                    RECEIVE_DATE, DESCRIPTION, PATH
                FROM REPORT_DEPARTMENT A
                WHERE 1 = 1
        ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }


    function selectByParamsCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.REPORT_DEPARTMENT_ID ASC")
    {
        $str = "SELECT REPORT_DEPARTMENT_ID, DEPARTMENT, PROJECT, CLIENT, SEND_DATE, 
                    RECEIVE_DATE, DESCRIPTION, PATH
                FROM REPORT_DEPARTMENT A
                WHERE 1 = 1
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
        $str = "SELECT COUNT(1) AS ROWCOUNT  FROM REPORT_DEPARTMENT A
                WHERE 1 = 1 " . $statement;
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM REPORT_DEPARTMENT A WHERE 1=1 " . $statement;
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
