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

class Invoices extends Entity
{

    var $query;
    /**
     * Class constructor.
     **/
    function Invoice()
    {
        $this->Entity();
    }

    function insert()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $this->setField("INVOICE_ID", $this->getNextId("INVOICE_ID", "INVOICE"));

        $str = "INSERT INTO INVOICE(
                INVOICE_ID, INVOICE_NUMBER, INVOICE_DATE, PPN, COMPANY_NAME, CONTACT_NAME, ADDRESS, TELEPHONE, FAXIMILE,
                EMAIL, PPN_PERCENT, STATUS, INVOICE_PO, INVOICE_TAX, TERMS, NO_KONTRAK, NO_REPORT)
			    VALUES (
                " . $this->getField("INVOICE_ID") . ",
                '" . $this->getField("INVOICE_NUMBER") . "',
                '" . $this->getField("INVOICE_DATE") . "',
                '" . $this->getField("PPN") . "',
                '" . $this->getField("COMPANY_NAME") . "',
                '" . $this->getField("CONTACT_NAME") . "',
                '" . $this->getField("ADDRESS") . "',
                '" . $this->getField("TELEPHONE") . "',
                '" . $this->getField("FAXIMILE") . "',
                '" . $this->getField("EMAIL") . "',
                " . $this->getField("PPN_PERCENT") . ",
                '" . $this->getField("STATUS") . "',
                '" . $this->getField("INVOICE_PO") . "',
                '" . $this->getField("INVOICE_TAX") . "',
                '" . $this->getField("TERMS") . "',
                '" . $this->getField("NO_KONTRAK") . "',
                '" . $this->getField("NO_REPORT") . "'
                )";

        $this->id = $this->getField("INVOICE_ID");
        $this->query = $str;
        // echo $str;
        // exit;

        return $this->execQuery($str);
    }

    function updateByField()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $str = "UPDATE PEGAWAI A SET
				  " . $this->getField("FIELD") . " = '" . $this->getField("FIELD_VALUE") . "',
						   LAST_UPDATED_USER  = '" . $this->getField("LAST_UPDATED_USER") . "',
						   LAST_UPDATED_DATE  = CURRENT_DATE
				WHERE PEGAWAI_ID = " . $this->getField("PEGAWAI_ID") . "
				";
        $this->query = $str;

        return $this->execQuery($str);
    }


    function validasi()
    {
        $str = "UPDATE PEGAWAI
				SET 
				VALIDASI='" . $this->getField("VALIDASI") . "',
				UPDATED_BY='" . $this->getField("UPDATED_BY") . "',
				UPDATED_DATE=CURRENT_DATE
				WHERE  PEGAWAI_ID = " . $this->getField("PEGAWAI_ID") . "
			 ";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }



    function validasiSetuju()
    {
        $str = "UPDATE PEGAWAI
				SET 
				VALIDASI='" . $this->getField("VALIDASI") . "',
				NO_SEKAR	= (SELECT MAX(NO_SEKAR::INT) + 1 FROM PEGAWAI)::text,
				UPDATED_BY='" . $this->getField("UPDATED_BY") . "',
				UPDATED_DATE=CURRENT_DATE
				WHERE  PEGAWAI_ID = " . $this->getField("PEGAWAI_ID") . "
			 ";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }


    function update()
    {
        $str = "UPDATE INVOICE
				SET
                    INVOICE_NUMBER = '" . $this->getField("INVOICE_NUMBER") . "',
                    INVOICE_DATE = '" . $this->getField("INVOICE_DATE") . "',
                    PPN = '" . $this->getField("PPN") . "',
                    COMPANY_NAME = '" . $this->getField("COMPANY_NAME") . "',
                    CONTACT_NAME = '" . $this->getField("CONTACT_NAME") . "',
                    ADDRESS = '" . $this->getField("ADDRESS") . "',
                    TELEPHONE = '" . $this->getField("TELEPHONE") . "',
                    FAXIMILE = '" . $this->getField("FAXIMILE") . "',
                    EMAIL = '" . $this->getField("EMAIL") . "',
                    PPN_PERCENT = " . $this->getField("PPN_PERCENT") . ",
                    STATUS = '" . $this->getField("STATUS") . "',
                    INVOICE_PO = '" . $this->getField("INVOICE_PO") . "',
                    INVOICE_TAX = '" . $this->getField("INVOICE_TAX") . "',
                    TERMS = '" . $this->getField("TERMS") . "',
                    NO_KONTRAK = '" . $this->getField("NO_KONTRAK") . "',
                    NO_REPORT = '" . $this->getField("NO_REPORT") . "'
            
			    WHERE INVOICE_ID = " . $this->getField("INVOICE_ID") . "
			 ";
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }


    function koreksi()
    {
        $str = "UPDATE PEGAWAI
				SET 
				NRP='" . $this->getField("NRP") . "',
				NIP='" . $this->getField("NIP") . "',
				UPDATED_BY='" . $this->getField("UPDATED_BY") . "',
				UPDATED_DATE=CURRENT_DATE
				WHERE  PEGAWAI_ID = " . $this->getField("PEGAWAI_ID") . "
			 ";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }


    function updateProfil()
    {
        $str = "UPDATE PEGAWAI
				SET 
				NAMA='" . $this->getField("NAMA") . "',
				NAMA_PANGGILAN='" . $this->getField("NAMA_PANGGILAN") . "',
				TEMPAT_LAHIR='" . $this->getField("TEMPAT_LAHIR") . "',
				TANGGAL_LAHIR= " . $this->getField("TANGGAL_LAHIR") . ",
				ALAMAT='" . $this->getField("ALAMAT") . "',
				NOMOR_HP='" . $this->getField("NOMOR_HP") . "',
				EMAIL_PRIBADI='" . $this->getField("EMAIL_PRIBADI") . "',
				EMAIL_BULOG='" . $this->getField("EMAIL_BULOG") . "',
				NOMOR_WA='" . $this->getField("NOMOR_WA") . "',
				CABANG_ID='" . $this->getField("CABANG_ID") . "',
				UNIT_KERJA='" . $this->getField("UNIT_KERJA") . "',
				GOLONGAN_DARAH='" . $this->getField("GOLONGAN_DARAH") . "',
				UPDATED_BY='" . $this->getField("UPDATED_BY") . "',
				UPDATED_DATE=CURRENT_DATE
				WHERE  NIP = '" . $this->getField("NIP") . "'
			 ";
        $this->query = $str;
        //echo $str;exit;
        return $this->execQuery($str);
    }


    function updateFoto()
    {
        $str = "UPDATE PEGAWAI
				SET   	   FOTO        = '" . $this->getField("FOTO") . "'
				WHERE  NIP = '" . $this->getField("NIP") . "'
			 ";
        $this->query = $str;
        return $this->execQuery($str);
    }

    function updateLinkPegawai()
    {
        $str = "UPDATE PEGAWAI
				SET   	   LINK_FILE        = '" . $this->getField("LINK_FILE") . "'
				WHERE  PEGAWAI_ID = '" . $this->getField("PEGAWAI_ID") . "'
			 ";
        $this->query = $str;
        return $this->execQuery($str);
    }

    function updateDetil()
    {
        $str = "UPDATE PEGAWAI_DETIL
				SET   	   NAMA        = '" . $this->getField("NAMA") . "'
				WHERE  PEGAWAI_DETIL_ID = '" . (int) $this->getField("PEGAWAI_DETIL_ID") . "'
			 ";
        $this->query = $str;
        return $this->execQuery($str);
    }

    function delete()
    {
        $str = "DELETE 
                FROM INVOICE
                WHERE INVOICE_ID = " . $this->getField("INVOICE_ID") . "";

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

    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY INVOICE_ID ASC")
    {
        $str = "SELECT  
                 A.INVOICE_ID, A.INVOICE_NUMBER, A.INVOICE_DATE, A.PPN, A.COMPANY_NAME, A.CONTACT_NAME, A.ADDRESS, A.TELEPHONE, A.FAXIMILE,
                 A.EMAIL, A.PPN_PERCENT, A.STATUS, A.INVOICE_PO, A.INVOICE_TAX, A.TERMS, A.NO_KONTRAK, A.NO_REPORT
                FROM INVOICE A
                WHERE 1=1
				";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        $str .= " " . $order;
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->selectLimit($str, $limit, $from);
    }


    function selectByParamsCetakExcel($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY INVOICE_ID ASC")
    {
        $str = "SELECT  
                 A.INVOICE_ID, A.INVOICE_NUMBER, A.INVOICE_DATE, A.PPN, A.COMPANY_NAME, A.CONTACT_NAME, A.ADDRESS, A.TELEPHONE, A.FAXIMILE,
                 A.EMAIL
                FROM INVOICE A
                WHERE 1=1
				";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }
        $str .= $statement . " " . $order;
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->selectLimit($str, $limit, $from);
    }
    // function selectByParamsLoginTerakhir($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.LOGIN_TERAKHIR DESC")
    // {
    //     $str = "SELECT PEGAWAI_ID, NAMA, CABANG, JABATAN, LOGIN_TERAKHIR
    // 			FROM PEGAWAI_LOGIN_TERAKHIR A   
    // 			WHERE 1 = 1
    // 			";

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
    // 	        WHERE 0=0 " . $statement;

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
        $str = "SELECT COUNT(INVOICE_ID) AS ROWCOUNT FROM INVOICE A

		        WHERE INVOICE_ID IS NOT NULL ";

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

    function getCountByParamsLike($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM PEGAWAI

		        WHERE PEGAWAI_ID IS NOT NULL " . $statement;
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key LIKE '%$val%' ";
        }

        $this->select($str);
        if ($this->firstRow())
            return $this->getField("ROWCOUNT");
        else
            return 0;
    }
}
