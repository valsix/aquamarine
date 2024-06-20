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

class DokumenCertificate  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function DokumenCertificate()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("DOCUMENT_ID", $this->getNextId("DOCUMENT_ID", "DOKUMEN_CERTIFICATE"));

        $str = "INSERT INTO DOKUMEN_CERTIFICATE (DOCUMENT_ID, CERTIFICATE_ID, NAME, DESCRIPTION, PATH, ISSUED_DATE,        EXPIRED_DATE, LAST_REVISI, SURVEYOR)VALUES (
        '" . $this->getField("DOCUMENT_ID") . "',
        '" . $this->getField("CERTIFICATE_ID") . "',
        '" . $this->getField("NAME") . "',
        '" . $this->getField("DESCRIPTION") . "',
        '" . $this->getField("PATH") . "',
        " . $this->getField("ISSUED_DATE") . ",
        " . $this->getField("EXPIRED_DATE") . ",
        now(),
        '" . $this->getField("SURVEYOR") . "' 
    )";

        $this->id = $this->getField("DOCUMENT_ID");
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE DOKUMEN_CERTIFICATE
        SET    
        DOCUMENT_ID ='" . $this->getField("DOCUMENT_ID") . "',
        CERTIFICATE_ID ='" . $this->getField("CERTIFICATE_ID") . "',
        NAME ='" . $this->getField("NAME") . "',
        DESCRIPTION ='" . $this->getField("DESCRIPTION") . "',
        PATH ='" . $this->getField("PATH") . "',
        ISSUED_DATE =" . $this->getField("ISSUED_DATE") . ",
        EXPIRED_DATE =" . $this->getField("EXPIRED_DATE") . ",
        LAST_REVISI =now(),
        SURVEYOR ='" . $this->getField("SURVEYOR") . "' 
        WHERE DOCUMENT_ID= '" . $this->getField("DOCUMENT_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }
    function update_path()
    {
        $str = "
        UPDATE DOKUMEN_CERTIFICATE
        SET    
        PATH ='" . $this->getField("PATH") . "'
       
        WHERE DOCUMENT_ID= '" . $this->getField("DOCUMENT_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement = "")
    {
        $str = "DELETE FROM DOKUMEN_CERTIFICATE
                WHERE DOCUMENT_ID= " . $this->getField("DOCUMENT_ID") . "";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
    {
        $str = "SELECT A.DOCUMENT_ID,A.CERTIFICATE_ID,A.NAME,A.DESCRIPTION,A.PATH,A.ISSUED_DATE,A.EXPIRED_DATE,A.LAST_REVISI,A.SURVEYOR
                FROM DOKUMEN_CERTIFICATE A
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
        $str = "SELECT A.DOCUMENT_ID, A.NAME , TO_CHAR(A.ISSUED_DATE, 'DD-MM-YYYY') AS ISSUED_DATE, TO_CHAR(A.EXPIRED_DATE, 'DD-MM-YYYY') AS EXPIRED_DATE, A.SURVEYOR ,A.EXPIRED_DATE DATES,B.CERTIFICATE
                FROM DOKUMEN_CERTIFICATE A, CERTIFICATE B 

                WHERE 1 = 1
                AND A.CERTIFICATE_ID = B.CERTIFICATE_ID
        ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }


    function selectByParamsCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
    {
        $str = "SELECT A.DOCUMENT_ID, A.NAME , TO_CHAR(A.ISSUED_DATE, 'DAY,MONTH DD YYYY') AS ISSUED_DATE, TO_CHAR(A.EXPIRED_DATE, 'DAY,MONTH DD YYYY') AS EXPIRED_DATE, A.SURVEYOR ,A.EXPIRED_DATE DATES
                FROM DOKUMEN_CERTIFICATE A, CERTIFICATE B
                WHERE 1 = 1
                AND A.CERTIFICATE_ID = B.CERTIFICATE_ID
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
        $str = "SELECT COUNT(1) AS ROWCOUNT  FROM DOKUMEN_CERTIFICATE A, CERTIFICATE B
                WHERE 1 = 1
                AND A.CERTIFICATE_ID = B.CERTIFICATE_ID  " . $statement;
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM DOKUMEN_CERTIFICATE A WHERE 1=1 " . $statement;
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


    function getCountByParamsTotalCertificateBak($paramsArray = array(), $statement = "")
    {
        $str = " SELECT COUNT(*) AS ROWCOUNT FROM DOKUMEN_CERTIFICATE A, CERTIFICATE B
                WHERE 1 = 1
                AND A.CERTIFICATE_ID = B.CERTIFICATE_ID AND (EXPIRED_DATE < NOW() OR (NOW() BETWEEN (EXPIRED_DATE + INTERVAL '2 MONTH') AND (EXPIRED_DATE + INTERVAL '1 DAY'))); " . $statement;
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


    function getCountByParamsTotalCertificate($paramsArray = array(), $statement = "")
    {
        $str = " SELECT COUNT(*) AS ROWCOUNT FROM DOKUMEN_CERTIFICATE A, CERTIFICATE B
                WHERE 1 = 1
                AND A.CERTIFICATE_ID = B.CERTIFICATE_ID AND EXPIRED_DATE < CURRENT_DATE + INTERVAL '2 MONTH' " . $statement;
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
