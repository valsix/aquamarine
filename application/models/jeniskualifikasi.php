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

class JenisKualifikasi  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function JenisKualifikasi()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("JENIS_ID", $this->getNextId("JENIS_ID", "JENIS_KUALIFIKASI"));

        $str = "INSERT INTO JENIS_KUALIFIKASI ( JENIS_ID, JENIS, DESCRIPTION ,KODE)
        VALUES (
        " . $this->getField("JENIS_ID") . ",
        '" . $this->getField("JENIS") . "',
        '" . $this->getField("DESCRIPTION") . "' ,
        '" . $this->getField("KODE") . "' 
         )";

        $this->id = $this->getField("JENIS_ID");
        $this->query = $str;
        // ECHO $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "UPDATE JENIS_KUALIFIKASI
        SET    
        JENIS_ID ='" . $this->getField("JENIS_ID") . "',
        JENIS ='" . $this->getField("JENIS") . "',
          KODE ='" . $this->getField("KODE") . "',
        DESCRIPTION ='" . $this->getField("DESCRIPTION") . "' 
        WHERE JENIS_ID= '" . $this->getField("JENIS_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement = "")
    {
        $str = "DELETE FROM JENIS_KUALIFIKASI   
        WHERE JENIS_ID= '" . $this->getField("JENIS_ID") . "'";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.JENIS_ID ASC")
    {
        $str = "SELECT A.JENIS_ID,A.JENIS,A.DESCRIPTION ,A.KODE
        FROM JENIS_KUALIFIKASI A
        WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }


    // function selectByParamsMonitoringPersonalKualifikasi($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.JENIS_ID ASC")
    // {
    //     $str = " SELECT A.DOCUMENT_ID, A.NAME , A.ADDRESS , TO_CHAR(A.BIRTH_DATE, 'DAY,MONTH DD YYYY') AS BIRTH_DATE,
    //     A.PHONE , B.JENIS AS QUALIFICATION,A.LIST_CERTIFICATE,B.KODE,
    //     (SELECT D.CERTIFICATE FROM DETIL_PERSONAL_CERTIFICATE C, PERSONAL_CERTIFICATE D WHERE C.CERTIFICATE_ID = D.CERTIFICATE_ID AND C.DOCUMENT_ID = A.DOCUMENT_ID LIMIT 1) CERTIFICATE
    //     ,A.ID_NUMBER
    //     FROM DOKUMEN_KUALIFIKASI A, JENIS_KUALIFIKASI B
    //     WHERE 1 = 1
    //     AND A.JENIS_ID = B.JENIS_ID
    //     ";
    //     while (list($key, $val) = each($paramsArray)) {
    //         $str .= " AND $key = '$val'";
    //     }

    //     $str .= $statement . " " . $order;
    //     $this->query = $str;
    //     return $this->selectLimit($str, $limit, $from);
    // }

    function selectByParamsMonitoringPersonalKualifikasi($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID DESC")
    {
        $str = " SELECT A.DOCUMENT_ID, A.NAME , A.ADDRESS , TO_CHAR(A.BIRTH_DATE, 'DAY,MONTH DD YYYY') AS BIRTH_DATE, A.REMARKS,
        A.PHONE , B.JENIS AS QUALIFICATION,A.LIST_CERTIFICATE,B.KODE,
        (SELECT D.CERTIFICATE FROM DETIL_PERSONAL_CERTIFICATE C, PERSONAL_CERTIFICATE D WHERE C.CERTIFICATE_ID = D.CERTIFICATE_ID AND C.DOCUMENT_ID = A.DOCUMENT_ID LIMIT 1) CERTIFICATE
        ,A.ID_NUMBER,C.NAMA NAMA_CABANG
        , EXTRACT(YEAR FROM AGE(A.BIRTH_DATE)) UMUR
        , A.PHONE
        FROM DOKUMEN_KUALIFIKASI A 
        LEFT JOIN JENIS_KUALIFIKASI B ON A.JENIS_ID = B.JENIS_ID 
        LEFT JOIN CABANG C ON C.CABANG_ID = A.CABANG_ID::integer
        WHERE 1 = 1
        ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }


    function selectByParamsMonitoringPersonalKualifikasiCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.JENIS_ID ASC")
    {
        $str = " SELECT A.DOCUMENT_ID, A.NAME , A.ADDRESS , TO_CHAR(A.BIRTH_DATE, 'DAY,MONTH DD YYYY') AS BIRTH_DATE,
        A.PHONE , B.JENIS AS QUALIFICATION,B.KODE
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


    function getCountByParamsMonitoring($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM JENIS_KUALIFIKASI A WHERE 1=1 " . $statement;
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
