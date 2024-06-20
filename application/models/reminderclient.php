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

class ReminderClient extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function ReminderClient()
    {
        $this->Entity();
    }

    function insert()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $this->setField("REMINDER_CLIENT_ID", $this->getNextId("REMINDER_CLIENT_ID", "REMINDER_CLIENT"));

        $str = "INSERT INTO REMINDER_CLIENT(
             REMINDER_CLIENT_ID, URUT, COMPANY_ID, VESSEL_ID, IMO_NO, PORT_REGISTER, ANNUAL_DATE, INTERMEDIATE_DATE, SPECIAL_DATE, LOADTEST_DATE, ANNUAL_DUE_DATE, INTERMEDIATE_DUE_DATE, SPECIAL_DUE_DATE, LOADTEST_DUE_DATE, CREATED_BY, CREATED_DATE)
			VALUES (
            " . $this->getField("REMINDER_CLIENT_ID") . ",
			" . $this->getField("URUT") . ",
			" . $this->getField("COMPANY_ID") . ",
			" . $this->getField("VESSEL_ID") . ",
			'" . $this->getField("IMO_NO") . "',
			'" . $this->getField("PORT_REGISTER") . "',
			" . $this->getField("ANNUAL_DATE") . ",
			" . $this->getField("INTERMEDIATE_DATE") . ",
            " . $this->getField("SPECIAL_DATE") . ",
            " . $this->getField("LOADTEST_DATE") . ",
            " . $this->getField("ANNUAL_DUE_DATE") . ",
            " . $this->getField("INTERMEDIATE_DUE_DATE") . ",
            " . $this->getField("SPECIAL_DUE_DATE") . ",
			" . $this->getField("LOADTEST_DUE_DATE") . ",
			'" . $this->getField("CREATED_BY") . "',
			CURRENT_DATE
			)";

        $this->id = $this->getField("REMINDER_CLIENT_ID");
        $this->query = $str;
        // echo $str;
        // exit;

        return $this->execQuery($str);
    }

    function update()
    {
        $str = "UPDATE REMINDER_CLIENT
                SET    
                    COMPANY_ID =" . $this->getField("COMPANY_ID") . ",
                    URUT =" . $this->getField("URUT") . ",
                    VESSEL_ID =" . $this->getField("VESSEL_ID") . ",
                    IMO_NO ='" . $this->getField("IMO_NO") . "',
                    PORT_REGISTER ='" . $this->getField("PORT_REGISTER") . "',
                    ANNUAL_DATE =" . $this->getField("ANNUAL_DATE") . ",
                    INTERMEDIATE_DATE =" . $this->getField("INTERMEDIATE_DATE") . ",
                    SPECIAL_DATE =" . $this->getField("SPECIAL_DATE") . ",
                    LOADTEST_DATE =" . $this->getField("LOADTEST_DATE") . ",
                    ANNUAL_DUE_DATE =" . $this->getField("ANNUAL_DUE_DATE") . ",
                    INTERMEDIATE_DUE_DATE =" . $this->getField("INTERMEDIATE_DUE_DATE") . ",
                    SPECIAL_DUE_DATE =" . $this->getField("SPECIAL_DUE_DATE") . ",
                    LOADTEST_DUE_DATE =" . $this->getField("LOADTEST_DUE_DATE") . ",
                    UPDATED_BY ='" . $this->getField("UPDATED_BY") . "',
                    UPDATED_DATE =CURRENT_DATE
                WHERE REMINDER_CLIENT_ID= '" . $this->getField("REMINDER_CLIENT_ID") . "'";
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }
    function update_urut()
    {
        $str = "UPDATE REMINDER_CLIENT
                SET    
                    
                    URUT =" . $this->getField("URUT") . "
                    
                WHERE REMINDER_CLIENT_ID= '" . $this->getField("REMINDER_CLIENT_ID") . "'";
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }

    function updateByField()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $str = "UPDATE REMINDER_CLIENT A SET
				  " . $this->getField("FIELD") . " = '" . $this->getField("FIELD_VALUE") . "'
				WHERE REMINDER_CLIENT_ID = " . $this->getField("REMINDER_CLIENT_ID") . "
				";
        $this->query = $str;

        return $this->execQuery($str);
    }

    function delete()
    {
        $str = "DELETE 
                FROM REMINDER_CLIENT
                WHERE REMINDER_CLIENT_ID = " . $this->getField("REMINDER_CLIENT_ID") . "";

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

    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.REMINDER_CLIENT_ID ASC")
    {
        $str = "SELECT
                    A.REMINDER_CLIENT_ID, URUT, A.COMPANY_ID, A.VESSEL_ID,
                    B.NAME COMPANY_NAME, B.ADDRESS COMPANY_ADDRESS,
                    B.CP1_NAME COMPANY_CP, B.CP1_TELP COMPANY_PHONE,
                    B.EMAIL COMPANY_EMAIL, C.NAME VESSEL_NAME,
                    C.TYPE_VESSEL, C.CLASS_VESSEL, IMO_NO, OFFER_ID,
                    PORT_REGISTER, ANNUAL_DATE, INTERMEDIATE_DATE, 
                    LOADTEST_DATE, ANNUAL_DUE_DATE, INTERMEDIATE_DUE_DATE, 
                    SPECIAL_DUE_DATE, LOADTEST_DUE_DATE, SPECIAL_DATE, 
                    (ANNUAL_DUE_DATE - ANNUAL_DATE) ANNUAL_EXTEND, 
                    (INTERMEDIATE_DUE_DATE - INTERMEDIATE_DATE) INTERMEDIATE_EXTEND, 
                    (SPECIAL_DUE_DATE - SPECIAL_DATE) SPECIAL_EXTEND,
                    (LOADTEST_DUE_DATE - LOADTEST_DATE) LOADTEST_EXTEND,
                    CASE 
                        WHEN (
                            (A.ANNUAL_DATE < ANNUAL_DUE_DATE + INTERVAL '3 MONTH') OR 
                            (A.INTERMEDIATE_DATE < INTERMEDIATE_DUE_DATE + INTERVAL '3 MONTH') OR 
                            (A.SPECIAL_DATE < SPECIAL_DUE_DATE + INTERVAL '3 MONTH')  OR
                            (A.LOADTEST_DATE < LOADTEST_DUE_DATE + INTERVAL '3 MONTH')
                        ) THEN 'red'
                        ELSE ''
                    END STATUS,
                      CASE 
                        WHEN (
                            (A.ANNUAL_DUE_DATE < (CURRENT_DATE - INTERVAL '1 MONTH')) OR 
                            (A.INTERMEDIATE_DUE_DATE < (CURRENT_DATE + INTERVAL '1 MONTH')) OR 
                            (A.SPECIAL_DUE_DATE < (CURRENT_DATE + INTERVAL '1 MONTH')) OR
                            (A.LOADTEST_DUE_DATE < (CURRENT_DATE + INTERVAL '1 MONTH'))
                        ) THEN 'red'
                        ELSE ''
                    END STATUS2

                FROM
                    REMINDER_CLIENT A
                    LEFT JOIN COMPANY B ON A.COMPANY_ID = B.COMPANY_ID
                    LEFT JOIN VESSEL C ON A.VESSEL_ID = C.VESSEL_ID
                    LEFT JOIN FILTER_REMINDER_CLIENT D ON CAST(D.REMINDER_CLIENT_ID AS VARCHAR  )= CAST(A.REMINDER_CLIENT_ID AS VARCHAR)
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }


    function getCountByParams($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(REMINDER_CLIENT_ID) AS ROWCOUNT FROM REMINDER_CLIENT A
                    LEFT JOIN COMPANY B ON A.COMPANY_ID = B.COMPANY_ID
                    LEFT JOIN VESSEL C ON A.VESSEL_ID = C.VESSEL_ID

		        WHERE REMINDER_CLIENT_ID IS NOT NULL ";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

         $str =$str .' ' .$statement;
         $this->query =$str;
        // exit;
        $this->select($str);
        if ($this->firstRow())
            return $this->getField("ROWCOUNT");
        else
            return 0;
    }

}
