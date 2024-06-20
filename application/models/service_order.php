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

class Service_order extends Entity
{

    var $query;
    /**
     * Class constructor.
     **/
    function Service_order()
    {
        $this->Entity();
    }

    function insert()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $this->setField("SO_ID", $this->getNextId("SO_ID", "SERVICE_ORDER"));

        $str = "INSERT INTO SERVICE_ORDER(
            SO_ID, NO_ORDER, PROJECT_NAME, COMPANY_NAME, VESSEL_NAME, VESSEL_TYPE, SURVEYOR, DESTINATION, SERVICE, DATE_OF_START, DATE_OF_FINISH,
            EQUIPMENT, DATE_OF_SERVICE)
			VALUES (
			" . $this->getField("SO_ID") . ",
			'" . $this->getField("NO_ORDER") . "',
			'" . $this->getField("PROJECT_NAME") . "',
			'" . $this->getField("COMPANY_NAME") . "',
			'" . $this->getField("VESSEL_NAME") . "',
			'" . $this->getField("VESSEL_TYPE") . "',
			'" . $this->getField("SURVEYOR") . "',
			'" . $this->getField("DESTINATION") . "',
			'" . $this->getField("SERVICE") . "',
			'" . $this->getField("DATE_OF_START") . "',
			'" . $this->getField("DATE_OF_FINISH") . "',
			'" . $this->getField("EQUIPMENT") . "',
			'" . $this->getField("DATE_OF_SERVICE") . "'
			)";

        $this->id = $this->getField("SO_ID");
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
        $str = "UPDATE SERVICE_ORDER
				SET

                NO_ORDER ='" . $this->getField("NO_ORDER") . "',
                PROJECT_NAME ='" . $this->getField("PROJECT_NAME") . "',
                COMPANY_NAME ='" . $this->getField("COMPANY_NAME") . "',
                VESSEL_NAME ='" . $this->getField("VESSEL_NAME") . "',
                VESSEL_TYPE ='" . $this->getField("VESSEL_TYPE") . "',
                SURVEYOR ='" . $this->getField("SURVEYOR") . "',
                DESTINATION ='" . $this->getField("DESTINATION") . "',
                SERVICE ='" . $this->getField("SERVICE") . "',
                DATE_OF_START ='" . $this->getField("DATE_OF_START") . "',
                DATE_OF_FINISH ='" . $this->getField("DATE_OF_FINISH") . "',
                EQUIPMENT ='" . $this->getField("EQUIPMENT") . "',
                DATE_OF_SERVICE ='" . $this->getField("DATE_OF_SERVICE") . "'
		
			WHERE SO_ID = " . $this->getField("SO_ID") . "
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
                FROM SERVICE_ORDER
                WHERE SO_ID = " . $this->getField("SO_ID") . "";

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

    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY SO_ID ASC")
    {
        $str = "SELECT  
                   A.SO_ID, A.NO_ORDER, A.PROJECT_NAME, A.COMPANY_NAME, A.VESSEL_NAME, A.VESSEL_TYPE, A.SURVEYOR, A.DESTINATION, A.SERVICE, A.DATE_OF_START, A.DATE_OF_FINISH,
                   A.EQUIPMENT, A.DATE_OF_SERVICE,A.DATE_OWR,A.NO_DELIVERY,A.CONTACT_PERSON,A.VESSEL_CLASS
                FROM SERVICE_ORDER A
                WHERE 1=1
				";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        $str .= $statement. " " . $order;
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParamsPopUp($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY SO_ID ASC")
    {
        $str = "SELECT  
        A.SO_ID, A.NO_ORDER, A.PROJECT_NAME, A.COMPANY_NAME, A.VESSEL_NAME, A.VESSEL_TYPE, A.SURVEYOR, A.DESTINATION, A.SERVICE, A.DATE_OF_START, A.DATE_OF_FINISH,
        A.EQUIPMENT, A.DATE_OF_SERVICE,A.DATE_OWR,A.NO_DELIVERY,A.CONTACT_PERSON,B.SCOPE_OF_WORK,C.SURVEYOR SURVEYOR_COST ,C.OPERATOR OPERATOR_COST 
        FROM SERVICE_ORDER A
        LEFT JOIN OFFER B ON B.OFFER_ID = A.OFFER_ID 
         LEFT JOIN COST_PROJECT C ON C.NO_PROJECT = A.NO_ORDER 
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

    function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.SO_ID ASC")
    {
        $str = "SELECT A.SO_ID,A.PROJECT_NAME,A.NO_ORDER,A.COMPANY_NAME,
                A.SURVEYOR,A.DESTINATION,A.SERVICE,A.DATE_OF_START,A.DATE_OF_FINISH,A.TRANSPORT,A.EQUIPMENT,
                A.OBLIGATION,A.DATE_OF_SERVICE,A.DATE_OWR,A.PIC_EQUIP,A.CONTACT_PERSON,A.NO_DELIVERY,A.DOC_LAMPIRAN,
                A.PATH_LAMPIRAN,A.FINANCE,A.PENANGGUNG_JAWAB_ID, B.NAMA TTD_NAMA, B.TTD_LINK, B.JABATAN TTD_JABATAN,
                A.VESSEL_NAME,A.VESSEL_TYPE,A.VESSEL_CLASS,A.COMPANY_ID,A.VESSEL_ID,A.PATH
                FROM SERVICE_ORDER A
                LEFT JOIN PENANGGUNG_JAWAB B ON A.PENANGGUNG_JAWAB_ID=B.PENANGGUNG_JAWAB_ID
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParamsTeam($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY B.SO_ID ASC")
    {
        $str = "
            SELECT B.*, A.POSITION
                FROM SO_TEAM A
                INNER JOIN SERVICE_ORDER B ON A.SO_ID = B.SO_ID
            WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
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
        $str = "SELECT COUNT(SO_ID) AS ROWCOUNT FROM SERVICE_ORDER A

		        WHERE SO_ID IS NOT NULL ";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        // echo $str;
        // exit;
        $str = $str.' '.$statement;
        $this->select($str);
        if ($this->firstRow())
            return $this->getField("ROWCOUNT");
        else
            return 0;
    }

    function getCountByParamsPopUp($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(SO_ID) AS ROWCOUNT FROM SERVICE_ORDER A
                LEFT JOIN OFFER B ON B.NO_ORDER = A.NO_ORDER 
                WHERE SO_ID IS NOT NULL ";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        // echo $str;
        // exit;
        $str = $str.' '.$statement;
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
