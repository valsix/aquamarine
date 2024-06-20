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

class DokumenQm extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function DokumenQm()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("DOCUMENT_ID", $this->getNextId("DOCUMENT_ID", "DOKUMEN_QM"));

        $str = "INSERT INTO DOKUMEN_QM (DOCUMENT_ID, TYPE, FORMAT_ID, NAME, DESCRIPTION, PATH, LAST_REVISI)
                VALUES (
                '" . $this->getField("DOCUMENT_ID") . "',
                '" . $this->getField("TYPE") . "',
                '" . $this->getField("FORMAT_ID") . "',
                '" . $this->getField("NAME") . "',
                '" . $this->getField("DESCRIPTION") . "',
                '" . $this->getField("PATH") . "',
                now()
            )";

        $this->id = $this->getField("DOCUMENT_ID");
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE DOKUMEN_QM
        SET    
        DOCUMENT_ID ='" . $this->getField("DOCUMENT_ID") . "',
        TYPE ='" . $this->getField("TYPE") . "',
        FORMAT_ID ='" . $this->getField("FORMAT_ID") . "',
        NAME ='" . $this->getField("NAME") . "',
        DESCRIPTION ='" . $this->getField("DESCRIPTION") . "',
        PATH ='" . $this->getField("PATH") . "',
        LAST_REVISI = now() 
        WHERE DOCUMENT_ID= '" . $this->getField("DOCUMENT_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function update_path()
    {
        $str = "
        UPDATE DOKUMEN_QM
        SET    
        
        PATH ='" . $this->getField("PATH") . "'
        
        WHERE DOCUMENT_ID= '" . $this->getField("DOCUMENT_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement = "")
    {
        $str = "DELETE FROM DOKUMEN_QM
        WHERE DOCUMENT_ID= '" . $this->getField("DOCUMENT_ID") . "'";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
    {
        $str = "
        SELECT A.DOCUMENT_ID,A.TYPE,A.FORMAT_ID,A.NAME,A.DESCRIPTION,A.PATH,A.LAST_REVISI
        FROM DOKUMEN_QM A
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
        $str = "
        SELECT A.DOCUMENT_ID, A.TYPE, B.FORMAT, A.NAME, A.DESCRIPTION, A.PATH,A.FORMAT_ID
        FROM DOKUMEN_QM A, FORMAT_QM B WHERE 1=1
        AND A.FORMAT_ID = B.FORMAT_ID 
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
        $str = "SELECT A.DOCUMENT_ID, A.TYPE, B.FORMAT, A.NAME, A.DESCRIPTION, A.PATH,A.FORMAT_ID
                FROM DOKUMEN_QM A, FORMAT_QM B WHERE 1=1
                AND A.FORMAT_ID = B.FORMAT_ID 
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM DOKUMEN_QM A, FORMAT_QM B WHERE 1=1
        AND A.FORMAT_ID = B.FORMAT_ID " . $statement;
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM DOKUMEN_QM A WHERE 1=1 " . $statement;
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
