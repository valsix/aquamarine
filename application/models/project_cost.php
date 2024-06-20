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

class Project_cost extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function Project_cost()
    {
        $this->Entity();
    }

    function insert()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $this->setField("COST_PROJECT_ID", $this->getNextId("COST_PROJECT_ID", "COST_PROJECT"));

        $str = "INSERT INTO COST_PROJECT(
                COST_PROJECT_ID, NO_PROJECT, VESSEL_NAME, TYPE_OF_VESSEL, TYPE_OF_SERVICE, DATE_SERVICE1, DATE_SERVICE2,
                DESTINATION, COMPANY_NAME, CONTACT_PERSON, KASBON,
                OFFER_PRICE, REAL_PRICE, SURVEYOR,OPERATOR)
			VALUES (
			" . $this->getField("COST_PROJECT_ID") . ",
			'" . $this->getField("NO_PROJECT") . "',
			'" . $this->getField("VESSEL_NAME") . "',
			'" . $this->getField("TYPE_OF_VESSEL") . "',
			'" . $this->getField("TYPE_OF_SERVICE") . "',
			" . $this->getField("DATE_SERVICE1") . ",
			" . $this->getField("DATE_SERVICE2") . ",
			'" . $this->getField("DESTINATION") . "',
			'" . $this->getField("COMPANY_NAME") . "',
			'" . $this->getField("CONTACT_PERSON") . "',
			" . $this->getField("KASBON") . ",
			" . $this->getField("OFFER_PRICE") . ",
			" . $this->getField("REAL_PRICE") . ",
			'" . $this->getField("SURVEYOR") . "',
            '" . $this->getField("OPERATOR") . "'
			)";

        $this->id = $this->getField("COST_PROJECT_ID");
        $this->query = $str;
        // echo $str;
        // exit;

        return $this->execQuery($str);
    }

    function updateCostOffer()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $str = "UPDATE PEGAWAI A SET
                   OFFER_ID  = '" . $this->getField("OFFER_ID") . "'
                          
                         
                WHERE COST_PROJECT_ID = " . $this->getField("COST_PROJECT_ID") . "
                ";
        $this->query = $str;

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
        $str = "UPDATE COST_PROJECT
				SET
                    NO_PROJECT ='" . $this->getField("NO_PROJECT") . "',
                    VESSEL_NAME ='" . $this->getField("VESSEL_NAME") . "',
                    TYPE_OF_VESSEL ='" . $this->getField("TYPE_OF_VESSEL") . "',
                    TYPE_OF_SERVICE ='" . $this->getField("TYPE_OF_SERVICE") . "',
                    DATE_SERVICE1 =" . $this->getField("DATE_SERVICE1") . ",
                    DATE_SERVICE2 =" . $this->getField("DATE_SERVICE2") . ",
                    DESTINATION ='" . $this->getField("DESTINATION") . "',
                    COMPANY_NAME ='" . $this->getField("COMPANY_NAME") . "',
                    CONTACT_PERSON ='" . $this->getField("CONTACT_PERSON") . "',
                    KASBON =" . $this->getField("KASBON") . ",
                    OFFER_PRICE =" . $this->getField("OFFER_PRICE") . ",
                    REAL_PRICE = " . $this->getField("REAL_PRICE") . ",
                    SURVEYOR ='" . $this->getField("SURVEYOR") . "',
                    OPERATOR ='" . $this->getField("OPERATOR") . "'

			    WHERE COST_PROJECT_ID = '" . $this->getField("COST_PROJECT_ID") . "'
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
                FROM COST_PROJECT
                WHERE COST_PROJECT_ID = " . $this->getField("COST_PROJECT_ID") . "";

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

    function selectByParamsNew($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY COST_PROJECT_ID ASC")
    {
        $str = "SELECT A.NO_PROJECT, A.VESSEL_NAME,
        A.TYPE_OF_VESSEL, A.TYPE_OF_SERVICE, A.DATE_SERVICE1,
        A.DATE_SERVICE2, A.DESTINATION, A.COMPANY_NAME,A.ADD_SERVICE,
        A.CONTACT_PERSON, A.KASBON, A.OFFER_PRICE, A.REAL_PRICE, A.SURVEYOR, A.OPERATOR ,A.KASBON_CUR,A.OFFER_CUR,A.REAL_CUR,A.SERVICE_ORDER_ID,A.OFFER_ID,A.HPP_PROJECT_ID,A.CLASS_OF_VESSEL
        FROM COST_PROJECT A
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

    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY COST_PROJECT_ID ASC")
    {
        $str = "SELECT
                    A.COST_PROJECT_ID, A.NO_PROJECT, A.VESSEL_NAME, A.TYPE_OF_VESSEL, A.TYPE_OF_SERVICE, 
                    TO_CHAR(A.DATE_SERVICE1,'DAY, MONTH DD YYYY') AS DATE_SERVICE1, 
                    TO_CHAR(A.DATE_SERVICE2,'DAY, MONTH DD YYYY') AS DATE_SERVICE2, 
                    A.DESTINATION, A.COMPANY_NAME, A.CONTACT_PERSON, A.KASBON,A.DATE_SERVICE1 DATE1,
                    A.DATE_SERVICE2 DATE2,A.CLASS_OF_VESSEL,
                    A.OFFER_PRICE, A.REAL_PRICE, A.SURVEYOR 
                FROM COST_PROJECT A
                WHERE 1=1
				";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        $str .= " " .$statement.' '.$order;
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->selectLimit($str, $limit, $from);
    }


    function selectByParamsCetakExcel($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.COST_PROJECT_ID ASC")
    {
        $str = "SELECT A.COST_PROJECT_ID, A.NO_PROJECT, A.VESSEL_NAME, A.TYPE_OF_VESSEL,  
                A.TYPE_OF_SERVICE, TO_CHAR(A.DATE_SERVICE1, 'DAY, MONTH DD YYYY') DATESERVICE1, TO_CHAR(A.DATE_SERVICE2, 'DAY, MONTH DD YYYY') DATESERVICE2, A.DESTINATION, A.COMPANY_NAME, A.CONTACT_PERSON, A.KASBON,
                A.OFFER_PRICE, A.REAL_PRICE ,A.SURVEYOR
                FROM   COST_PROJECT A 
                WHERE  1 = 1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->selectLimit($str, $limit, $from);
    }


    function selectByParamsCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.COST_PROJECT_ID ASC")
    {
        $str = "SELECT A.COST_PROJECT_ID, A.NO_PROJECT, A.VESSEL_NAME, A.TYPE_OF_VESSEL,  
                A.TYPE_OF_SERVICE, TO_CHAR(A.DATE_SERVICE1, 'DAY, MONTH DD YYYY') DATESERVICE1, TO_CHAR(A.DATE_SERVICE2, 'DAY, MONTH DD YYYY') DATESERVICE2, A.DESTINATION, A.COMPANY_NAME, A.CONTACT_PERSON, A.KASBON,
                A.OFFER_PRICE, A.REAL_PRICE ,A.SURVEYOR
                FROM   COST_PROJECT A 
                WHERE  1 = 1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
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
        $str = "SELECT COUNT(COST_PROJECT_ID) AS ROWCOUNT FROM COST_PROJECT A

		        WHERE COST_PROJECT_ID IS NOT NULL ";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }
        $str = $str.' '.$statement;
        $this->query =$str;
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
