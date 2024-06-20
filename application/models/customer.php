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

class Customer extends Entity
{

    var $query;
    /**
     * Class constructor.
     **/
    function Customer()
    {
        $this->Entity();
    }

    function insert()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $this->setField("COMPANY_ID", $this->getNextId("COMPANY_ID", "COMPANY"));

        $str = "INSERT INTO COMPANY(
            COMPANY_ID, NAME, ADDRESS, PHONE, FAX, EMAIL, CP1_NAME,
            CP1_TELP, CP2_NAME, CP2_TELP )
			VALUES (
			" . $this->getField("COMPANY_ID") . ",
			'" . $this->getField("NAME") . "',
			'" . $this->getField("ADDRESS") . "',
			'" . $this->getField("PHONE") . "',
			'" . $this->getField("FAX") . "',
			'" . $this->getField("EMAIL") . "',
			'" . $this->getField("CP1_NAME") . "',
			'" . $this->getField("CP1_TELP") . "',
			'" . $this->getField("CP2_NAME") . "',
			'" . $this->getField("CP2_TELP") . "'
			)";

        $this->id = $this->getField("COMPANY_ID");
        $this->query = $str;
        echo $str;
        exit;

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
        $str = "UPDATE COMPANY
				SET 

				NAME ='" . $this->getField("NAME") . "',
				ADDRESS ='" . $this->getField("ADDRESS") . "',
				PHONE ='" . $this->getField("PHONE") . "',
				FAX ='" . $this->getField("FAX") . "',
				EMAIL ='" . $this->getField("EMAIL") . "',
                CP1_NAME = '" . $this->getField("CP1_NAME") . "',
                CP1_TELP ='" . $this->getField("CP1_TELP") . "',
                CP2_NAME ='" . $this->getField("CP2_NAME") . "',
                CP2_TELP ='" . $this->getField("CP2_TELP") . "'
		
				WHERE  COMPANY_ID = " . $this->getField("COMPANY_ID") . "
			 ";
        $this->query = $str;
        // echo $str;exit;
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
                FROM COMPANY
                WHERE COMPANY_ID = " . $this->getField("COMPANY_ID") . "";

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

    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY COMPANY_ID ASC")
    {
        $str = "SELECT  
                    A.COMPANY_ID, A.NAME, A.ADDRESS, A.PHONE, A.FAX, A.EMAIL, A.CP1_NAME,
                    A.CP1_TELP, A.CP2_NAME, A.CP2_TELP,(SELECT COUNT(B.COSTUMER_SUPPORT_ID) FROM COSTUMER_SUPPORT B WHERE B.COMPANY_ID = A.COMPANY_ID) SUPPORT,A.BARAG_JASA,A.TINGKAT_PELAYANG,A.KUALITAS,A.KETERANGAN_SUB,A.HARGA_KET
                FROM COMPANY A
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


    function selectByParamsCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY COMPANY_ID ASC")
    {
        $str = "SELECT  
                    A.COMPANY_ID, A.NAME, A.ADDRESS, A.PHONE, A.FAX, A.EMAIL, A.CP1_NAME,
                    A.CP1_TELP, A.CP2_NAME, A.CP2_TELP
                FROM COMPANY A
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
        $str = "SELECT COUNT(COMPANY_ID) AS ROWCOUNT FROM COMPANY A

		        WHERE COMPANY_ID IS NOT NULL ";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        $str .= $statement;
     $this->query = $str;
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
