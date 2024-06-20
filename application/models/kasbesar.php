<?
/* *******************************************************************************************************
MODUL NAME          : IMASYS
FILE NAME           : 
AUTHOR              : 
VERSION             : 1.0
MODIFICATION DOC    :
DESCRIPTION         : 
***************************************************************************************************** */

/***
 * Entity-base class untuk mengimplementasikan tabel PANGKAT.
 * 
 ***/
include_once("Entity.php");

class KasBesar extends Entity
{

    var $query;
    /**
     * Class constructor.
     **/
    function KasBesar()
    {
        $this->Entity();
    }

    function insert()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $this->setField("KAS_BESAR_ID", $this->getNextId("KAS_BESAR_ID", "KAS_BESAR"));

        $str = "INSERT INTO KAS_BESAR(
                KAS_BESAR_ID, TANGGAL, DESKRIPSI,BANK_ID)
                VALUES (
                " . $this->getField("KAS_BESAR_ID") . ",
                " . $this->getField("TANGGAL") . ",
                '" . $this->getField("DESKRIPSI") . "',
                 '" . $this->getField("BANK_ID") . "'
                )";

        $this->id = $this->getField("KAS_BESAR_ID");
        $this->query = $str;
        // echo $str;
        // exit;

        return $this->execQuery($str);
    }

    function update()
    {
        $str = "UPDATE KAS_BESAR
                SET
                TANGGAL =" . $this->getField("TANGGAL") . ",
                DESKRIPSI ='" . $this->getField("DESKRIPSI") . "',
                BANK_ID ='" . $this->getField("BANK_ID") . "'
            
                WHERE KAS_BESAR_ID = " . $this->getField("KAS_BESAR_ID") . "
             ";
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }

    function approve()
    {
        $str = "UPDATE KAS_BESAR
                SET
                APPROVED_BY = '" . $this->getField("APPROVED_BY") . "',
                APPROVED_DATE = CURRENT_DATE
            
                WHERE KAS_BESAR_ID = " . $this->getField("KAS_BESAR_ID") . "
             ";
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }

    function approve_cancel()
    {
        $str = "UPDATE KAS_BESAR
                SET
                APPROVED_BY = '" . $this->getField("APPROVED_BY") . "',
                APPROVED_DATE = NULL
            
                WHERE KAS_BESAR_ID = " . $this->getField("KAS_BESAR_ID") . "
             ";
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }

    function delete()
    {
        $str = "DELETE 
                FROM KAS_BESAR
                WHERE KAS_BESAR_ID = " . $this->getField("KAS_BESAR_ID") . "";

        $this->query = $str;
        return $this->execQuery($str);
    }

    /** 
     * Cari record berdasarkan array parameter dan limit tampilan 
     * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
     * @param int limit Jumlah maksimal record yang akan diambil 
     * @param int from Awal record yang diambil 
     * @return boolean True jika sukses, false jika tidak 
     **/

    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY KAS_BESAR_ID DESC")
    {
        $str = "SELECT  
                A.KAS_BESAR_ID, A.TANGGAL, A.DESKRIPSI,
                CASE 
                    WHEN APPROVED_DATE IS NULL AND APPROVED_BY IS NULL THEN 'red'
                    WHEN APPROVED_DATE IS NULL AND APPROVED_BY IS NOT NULL THEN 'yellow'
                    ELSE 'green'
                END STATUS,
                (SELECT SUM(KREDIT) FROM KAS_BESAR_DETAIL X WHERE A.KAS_BESAR_ID=X.KAS_BESAR_ID) TOTAL_KREDIT_IDR,
                (SELECT SUM(DEBET) FROM KAS_BESAR_DETAIL X WHERE A.KAS_BESAR_ID=X.KAS_BESAR_ID) TOTAL_DEBET_IDR,
                (SELECT SUM(KREDIT) - SUM(DEBET) FROM KAS_BESAR_DETAIL X WHERE A.KAS_BESAR_ID=X.KAS_BESAR_ID) TOTAL_BALANCE_IDR,
                (SELECT SUM(KREDIT_USD) FROM KAS_BESAR_DETAIL X WHERE A.KAS_BESAR_ID=X.KAS_BESAR_ID) TOTAL_KREDIT_USD,
                (SELECT SUM(DEBET_USD) FROM KAS_BESAR_DETAIL X WHERE A.KAS_BESAR_ID=X.KAS_BESAR_ID) TOTAL_DEBET_USD,
                (SELECT SUM(KREDIT_USD) - SUM(DEBET_USD) FROM KAS_BESAR_DETAIL X WHERE A.KAS_BESAR_ID=X.KAS_BESAR_ID) TOTAL_BALANCE_USD,A.BANK_ID,B.NAMA NAMA_BANK
                FROM KAS_BESAR A
                LEFT JOIN BANK B ON A.BANK_ID = B.BANK_ID
                WHERE 1=1
                ";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        $str .= " " . $statement . ' ' . $order;
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->selectLimit($str, $limit, $from);
    }


    function selectByParamsCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY KAS_BESAR_ID ASC")
    {
        $str = "SELECT A.KAS_BESAR_ID, A.TANGGAL, A.DESKRIPSI
                FROM KAS_BESAR A
                WHERE 1=1
                ";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        $str .= " " . $statement . ' ' . $order;
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->selectLimit($str, $limit, $from);
    }


    // function selectByParamsLoginTerakhir($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.LOGIN_TERAKHIR DESC")
    // {
    //     $str = "SELECT PEGAWAI_ID, NAMA, CABANG, JABATAN, LOGIN_TERAKHIR
    //          FROM PEGAWAI_LOGIN_TERAKHIR A   
    //          WHERE 1 = 1
    //          ";

    //     while (list($key, $val) = each($paramsArray)) {
    //         $str .= " AND $key = '$val' ";
    //     }

    //     $str .= $statement . " " . $order;
    //     $this->query = $str;

    //     return $this->selectLimit($str, $limit, $from);
    // }



    // function getCountByParamsLoginTerakhir($paramsArray = array(), $statement = "")
    // {
    //     $str = "SELECT COUNT(1) AS ROWCOUNT FROM PEGAWAI_LOGIN_TERAKHIR A
    //          WHERE 0=0 " . $statement;

    //     while (list($key, $val) = each($paramsArray)) {
    //         $str .= " AND $key = '$val' ";
    //     }

    //     $this->select($str);
    //     if ($this->firstRow())
    //         return $this->getField("ROWCOUNT");
    //     else
    //         return 0;
    // }


    function getCountByParams($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(KAS_BESAR_ID) AS ROWCOUNT FROM KAS_BESAR A

                WHERE KAS_BESAR_ID IS NOT NULL ";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        // echo $str;
        // exit;
        $this->select($str);
        if ($this->firstRow())
            return $this->getField("ROWCOUNT");
        else
            return 0;
    }

    function getApproval($paramsArray = array(), $statement = "")
    {
        $str = "SELECT CASE WHEN APPROVED_DATE IS NULL THEN 0 ELSE 1 END ROWCOUNT FROM KAS_BESAR A

                WHERE KAS_BESAR_ID IS NOT NULL " . $statement;
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        $this->select($str);
        if ($this->firstRow())
            return $this->getField("ROWCOUNT");
        else
            return 0;
    }
}
