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

class Project_cost_detil  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function Project_cost_detil()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("COST_PROJECT_DETIL_ID", $this->getNextId("COST_PROJECT_DETIL_ID", "COST_PROJECT_DETIL"));

        $str = "INSERT INTO COST_PROJECT_DETIL ( COST_PROJECT_DETIL_ID,COST_PROJECT_ID, COST_DATE, COST, DESCRIPTION, STATUS )
        VALUES (
        " . $this->getField("COST_PROJECT_DETIL_ID") . ",
        " . $this->getField("COST_PROJECT_ID") . ",
        " . $this->getField("COST_DATE") . ",
        " . $this->getField("COST") . ",
        '" . $this->getField("DESCRIPTION") . "',
        " . $this->getField("STATUS") . "
        )";

        $this->id = $this->getField("COST_PROJECT_DETIL_ID");
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }


    function update()
    {
        $str = "UPDATE COST_PROJECT_DETIL
                SET    
        COST_DATE = " . $this->getField("COST_DATE") . ",
        COST_PROJECT_ID = " . $this->getField("COST_PROJECT_ID") . ",
        COST = " . $this->getField("COST") . ",
        DESCRIPTION ='" . $this->getField("DESCRIPTION") . "' 
                WHERE COST_PROJECT_DETIL_ID= " . $this->getField("COST_PROJECT_DETIL_ID") . "
        ";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }


    function delete($statement = "")
    {
        $str = "DELETE FROM COST_PROJECT_DETIL   
        WHERE COST_PROJECT_DETIL_ID= '" . $this->getField("COST_PROJECT_DETIL_ID") . "'";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function deleteParent($statement = "")
    {
        $str = "DELETE FROM COST_PROJECT_DETIL   
        WHERE COST_PROJECT_ID= '" . $this->getField("COST_PROJECT_ID") . "'";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }


    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.COST_PROJECT_DETIL_ID ASC")
    {
        $str = "SELECT A.COST_PROJECT_DETIL_ID, TO_CHAR(A.COST_DATE, 'DAY, MONTH DD YYYY') AS COST_DATE, A.COST, A.DESCRIPTION,A.STATUS,A.COST_DATE DATES
                FROM COST_PROJECT_DETIL A
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->selectLimit($str, $limit, $from);
    }


    function selectByParamsMonitoringPersonalKualifikasi($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.JENIS_ID ASC")
    {
        $str = " SELECT A.DOCUMENT_ID, A.NAME , A.ADDRESS , TO_CHAR(A.BIRTH_DATE, 'DAY,MONTH DD YYYY') AS BIRTH_DATE,
        A.PHONE , B.JENIS AS QUALIFICATION,
        (SELECT D.CERTIFICATE FROM DETIL_PERSONAL_CERTIFICATE C, PERSONAL_CERTIFICATE D WHERE C.CERTIFICATE_ID = D.CERTIFICATE_ID AND C.DOCUMENT_ID = A.DOCUMENT_ID LIMIT 1) CERTIFICATE
        FROM DOKUMEN_KUALIFIKASI A, JENIS_KUALIFIKASI B
        WHERE 1 = 1
        AND A.JENIS_ID = B.JENIS_ID
        ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    function getCountByParamsMonitoringPersonalKualifikasi($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM DOKUMEN_KUALIFIKASI A, JENIS_KUALIFIKASI B
        WHERE 1 = 1
        AND A.JENIS_ID = B.JENIS_ID " . $statement;
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


    function getCountByParams($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM COST_PROJECT_DETIL A 
        WHERE 1=1 " . $statement;
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
