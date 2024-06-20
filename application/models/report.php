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

class Report extends Entity
{

    var $query;
    /**
     * Class constructor.
     **/
    function Report()
    {
        $this->Entity();
    }

    function insert()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $this->setField("DOCUMENT_ID", $this->getNextId("DOCUMENT_ID", "DOKUMEN_REPORT"));

        $str = "INSERT INTO DOKUMEN_REPORT 
                            (DOCUMENT_ID, REPORT_ID, NAME, DESCRIPTION, PATH, START_DATE,        
                            FINISH_DATE, DELIVERY_DATE, LAST_REVISI, INVOICE_DATE, REASON,
                            NO_REPORT, NAME_OF_VESSEL, TYPE_OF_VESSEL, LOCATION, CLASS_SOCIETY, SCOPE_OF_WORK, NO_OWR)
                VALUES (
                            '" . $this->getField("DOCUMENT_ID") . "',
                            '" . $this->getField("REPORT_ID") . "',
                            '" . $this->getField("NAME") . "',
                            '" . $this->getField("DESCRIPTION") . "',
                            '" . $this->getField("PATH") . "',
                            " . $this->getField("START_DATE") . ",
                            " . $this->getField("FINISH_DATE") . ",
                            " . $this->getField("DELIVERY_DATE") . ",
                            now(),
                            " . $this->getField("INVOICE_DATE") . ",
                            '" . $this->getField("REASON") . "',
                            '" . $this->getField("NO_REPORT") . "',
                            '" . $this->getField("NAME_OF_VESSEL") . "',
                            '" . $this->getField("TYPE_OF_VESSEL") . "',
                            '" . $this->getField("LOCATION") . "',
                            '" . $this->getField("CLASS_SOCIETY") . "', 
                            '" . $this->getField("SCOPE_OF_WORK") . "', 
                            '" . $this->getField("NO_OWR") . "'
                        )";

        $this->id = $this->getField("DOCUMENT_ID");
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

    function insert_new()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $this->setField("REPORT_ID", $this->getNextId("REPORT_ID", "REPORT"));

        $str = "INSERT INTO REPORT (
                 REPORT_ID, REPORT, DESCRIPTION)
                VALUES (
                " . $this->getField("REPORT_ID") . ",
                '" . $this->getField("REPORT") . "',
                '" . $this->getField("DESCRIPTION") . "'
               
                )";

        $this->id = $this->getField("REPORT_ID");
        $this->query = $str;


        return $this->execQuery($str);
    }

    function update_new()
    {
        $str = "UPDATE REPORT
                SET

                REPORT_ID ='" . $this->getField("REPORT_ID") . "',
                DESCRIPTION ='" . $this->getField("DESCRIPTION") . "',
                REPORT ='" . $this->getField("REPORT") . "'
                
            
                WHERE REPORT_ID = " . $this->getField("REPORT_ID") . "
             ";
        $this->query = $str;

        return $this->execQuery($str);
    }

    function selectByParamsReport($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.REPORT_ID ASC")
    {
        $str = "SELECT  
                A.REPORT_ID, A.REPORT, A.DESCRIPTION
                FROM REPORT A
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
        $str = "UPDATE DOKUMEN_REPORT
                SET    
                DOCUMENT_ID ='" . $this->getField("DOCUMENT_ID") . "',
                REPORT_ID ='" . $this->getField("REPORT_ID") . "',
                NAME ='" . $this->getField("NAME") . "',
                DESCRIPTION ='" . $this->getField("DESCRIPTION") . "',
                PATH ='" . $this->getField("PATH") . "',
                START_DATE =" . $this->getField("START_DATE") . ",
                FINISH_DATE =" . $this->getField("FINISH_DATE") . ",
                DELIVERY_DATE =" . $this->getField("DELIVERY_DATE") . ",
                LAST_REVISI = now(),
                INVOICE_DATE =" . $this->getField("INVOICE_DATE") . ",
                REASON ='" . $this->getField("REASON") . "',
                NO_REPORT ='" . $this->getField("NO_REPORT") . "',
                NAME_OF_VESSEL ='" . $this->getField("NAME_OF_VESSEL") . "',
                TYPE_OF_VESSEL ='" . $this->getField("TYPE_OF_VESSEL") . "',
                LOCATION ='" . $this->getField("LOCATION") . "',
                CLASS_SOCIETY ='" . $this->getField("CLASS_SOCIETY") . "', 
                SCOPE_OF_WORK ='" . $this->getField("SCOPE_OF_WORK") . "', 
                NO_OWR ='" . $this->getField("NO_OWR") . "'

                WHERE DOCUMENT_ID= '" . $this->getField("DOCUMENT_ID") . "'
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
                FROM DOKUMEN_REPORT
                WHERE DOCUMENT_ID = " . $this->getField("DOCUMENT_ID") . "";

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

    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY DOCUMENT_ID ASC")
    {
        $str = "SELECT  
                A.DOCUMENT_ID, A.NAME, A.DESCRIPTION, A.PATH, A.START_DATE, A.FINISH_DATE, A.DELIVERY_DATE,A.INVOICE_DATE,A.REASON,A.NO_REPORT, A.NAME_OF_VESSEL, A.TYPE_OF_VESSEL, A.LOCATION, A.CLASS_SOCIETY, A.SCOPE_OF_WORK, A.NO_OWR, A.STATUS, A.COMPANY_ID, A.VESSEL_ID,A.URUT,A.COST_SURYEVOR ,A.COST_OPERATOR 
                FROM DOKUMEN_REPORT A
                LEFT JOIN  COST_PROJECT B ON B.NO_PROJECT=A.NO_REPORT
                WHERE 1=1
				";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        $str .= " " .$statement. ' ' .$order;
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->selectLimit($str, $limit, $from);
    }
    function selectByParamsRealisasi($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY DOCUMENT_ID ASC")
    {
        $str = " SELECT A.DOCUMENT_ID, A.NAME, A.DESCRIPTION, A.PATH, A.START_DATE, A.FINISH_DATE, A.DELIVERY_DATE,A.INVOICE_DATE,A.REASON,A.NO_REPORT, A.NAME_OF_VESSEL, A.TYPE_OF_VESSEL, A.LOCATION, A.CLASS_SOCIETY, A.SCOPE_OF_WORK, A.NO_OWR, A.STATUS, A.COMPANY_ID, A.VESSEL_ID,A.URUT,A.COST_SURYEVOR ,A.COST_OPERATOR ,

        COALESCE(BB.TOTAL,0) TOTAL,CC.TOTAL TOTAL_REALISASI,DD.PROFIT,DD.PRESCENTAGE,EE.STATUS STATUS_REALISASI,GG.KETERANGAN ,FD.NAMA GENERAL_SERVICE_DETAIL FROM DOKUMEN_REPORT A LEFT JOIN 
        OFFER B ON B.OFFER_ID = A.OFFER_ID
        left join SERVICES FD ON FD.SERVICES_ID::VARCHAR = B.GENERAL_SERVICE
        LEFT JOIN INVOICE EE ON EE.OFFER_ID = B.OFFER_ID
        LEFT JOIN INVOICE_PAYABLE GG ON GG.INVOICE_ID = EE.INVOICE_ID
        LEFT JOIN PROJECT_HPP DD ON DD.HPP_PROJECT_ID = B.HPP_PROJECT_ID
        LEFT JOIN (
        SELECT HPP_PROJECT_ID,SUM(TOTAL::NUMERIC) AS TOTAL FROM PROJECT_HPP_DETAIL GROUP BY HPP_PROJECT_ID
    ) BB ON BB.HPP_PROJECT_ID =B.HPP_PROJECT_ID
    LEFT JOIN (
    SELECT CC.OFFER_ID,CC.TOTAL_PRICE AS TOTAL FROM OFFER CC 
) CC ON CC.OFFER_ID = B.OFFER_ID AND B.STATUS=1


                WHERE 1=1
                ";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        $str .= " " .$statement. ' ' .$order;
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParamsCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY DOCUMENT_ID ASC")
    {
        $str = "SELECT  
                A.DOCUMENT_ID, A.NAME, A.DESCRIPTION, A.PATH, A.START_DATE, A.FINISH_DATE, A.DELIVERY_DATE,A.INVOICE_DATE,A.REASON,A.NO_REPORT, A.NAME_OF_VESSEL, A.TYPE_OF_VESSEL, A.LOCATION, A.CLASS_SOCIETY, A.SCOPE_OF_WORK, A.NO_OWR
                FROM DOKUMEN_REPORT A
                WHERE 1=1
				";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        $str .= " " .$statement.' '. $order;
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParamsCombo($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.REPORT_ID DESC")
    {
        $str = "SELECT A.REPORT_ID, A.REPORT FROM REPORT A
             WHERE 1 = 1
             ";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
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
        $str = "SELECT COUNT(DOCUMENT_ID) AS ROWCOUNT FROM DOKUMEN_REPORT A

		        WHERE DOCUMENT_ID IS NOT NULL ";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        // echo $str;
        // exit;
        $str .= " ".$statement;
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
