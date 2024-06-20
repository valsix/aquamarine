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

class MaterialInvoice extends Entity
{

    var $query;
    /**
     * Class constructor.
     **/
    function MaterialInvoice()
    {
        $this->Entity();
    }

    function insert()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $this->setField("MATERIAL_INVOICE_ID", $this->getNextId("MATERIAL_INVOICE_ID", "MATERIAL_INVOICE"));

        $str = "INSERT INTO MATERIAL_INVOICE(
                MATERIAL_INVOICE_ID, TAHUN, DESKRIPSI)
                VALUES (
                " . $this->getField("MATERIAL_INVOICE_ID") . ",
                '" . $this->getField("TAHUN") . "',
                '" . $this->getField("DESKRIPSI") . "'
                )";

        $this->id = $this->getField("MATERIAL_INVOICE_ID");
        $this->query = $str;
        // echo $str;
        // exit;

        return $this->execQuery($str);
    }

    function update()
    {
        $str = "UPDATE MATERIAL_INVOICE
                SET
                TAHUN ='" . $this->getField("TAHUN") . "',
                DESKRIPSI ='" . $this->getField("DESKRIPSI") . "'
            
                WHERE MATERIAL_INVOICE_ID = " . $this->getField("MATERIAL_INVOICE_ID") . "
             ";
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }

    function approve()
    {
        $str = "UPDATE MATERIAL_INVOICE
                SET
                APPROVED_BY = '" . $this->getField("APPROVED_BY") . "',
                APPROVED_DATE = CURRENT_DATE
            
                WHERE MATERIAL_INVOICE_ID = " . $this->getField("MATERIAL_INVOICE_ID") . "
             ";
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }

    function approve_cancel()
    {
        $str = "UPDATE MATERIAL_INVOICE
                SET
                APPROVED_BY = '" . $this->getField("APPROVED_BY") . "',
                APPROVED_DATE = NULL
            
                WHERE MATERIAL_INVOICE_ID = " . $this->getField("MATERIAL_INVOICE_ID") . "
             ";
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }
    
    function delete()
    {
        $str = "DELETE 
                FROM MATERIAL_INVOICE
                WHERE MATERIAL_INVOICE_ID = " . $this->getField("MATERIAL_INVOICE_ID") . "";

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

    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY MATERIAL_INVOICE_ID DESC")
    {
        $str = "SELECT  
                A.MATERIAL_INVOICE_ID, A.TAHUN, A.DESKRIPSI,
                CASE 
                    WHEN APPROVED_DATE IS NULL AND APPROVED_BY IS NULL THEN 'red'
                    WHEN APPROVED_DATE IS NULL AND APPROVED_BY IS NOT NULL THEN 'yellow'
                    ELSE 'green'
                END STATUS
                FROM MATERIAL_INVOICE A
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


    function selectByParamsCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY MATERIAL_INVOICE_ID ASC")
    {
        $str = "SELECT A.MATERIAL_INVOICE_ID, A.TAHUN, A.DESKRIPSI
                FROM MATERIAL_INVOICE A
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
        $str = "SELECT COUNT(MATERIAL_INVOICE_ID) AS ROWCOUNT FROM MATERIAL_INVOICE A

                WHERE MATERIAL_INVOICE_ID IS NOT NULL ";

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
        $str = "SELECT CASE WHEN APPROVED_DATE IS NULL THEN 0 ELSE 1 END ROWCOUNT FROM MATERIAL_INVOICE A

                WHERE MATERIAL_INVOICE_ID IS NOT NULL " . $statement;
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
