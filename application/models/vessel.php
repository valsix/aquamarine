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

class Vessel  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function Vessel()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("VESSEL_ID", $this->getNextId("VESSEL_ID", "VESSEL"));

        $str = "INSERT INTO VESSEL (VESSEL_ID, COMPANY_ID, NAME, DIMENSION_L, DIMENSION_B, DIMENSION_D,TYPE_VESSEL, CLASS_VESSEL, TYPE_SURVEY, LOCATION_SURVEY, CONTACT_PERSON,        VALUE_SURVEY, SURVEYOR_NAME, SURVEYOR_PHONE, CURRENCY,CURRENCY_VALUE,VALUE_DEADWEIGHT,VALUE_NET)VALUES (
            '" . $this->getField("VESSEL_ID") . "',
            '" . $this->getField("COMPANY_ID") . "',
            '" . $this->getField("NAME") . "',
            '" . $this->getField("DIMENSION_L") . "',
            '" . $this->getField("DIMENSION_B") . "',
            '" . $this->getField("DIMENSION_D") . "',
            '" . $this->getField("TYPE_VESSEL") . "',
            '" . $this->getField("CLASS_VESSEL") . "',
            '" . $this->getField("TYPE_SURVEY") . "',
            '" . $this->getField("LOCATION_SURVEY") . "',
            '" . $this->getField("CONTACT_PERSON") . "',
            '" . $this->getField("VALUE_SURVEY") . "',
            '" . $this->getField("SURVEYOR_NAME") . "',
            '" . $this->getField("SURVEYOR_PHONE") . "',
            '" . $this->getField("CURRENCY") . "',
            " . $this->getField("CURRENCY_VALUE") . ",
            " . $this->getField("VALUE_DEADWEIGHT") . ",
            " . $this->getField("VALUE_NET") . "
         
    )";

        $this->id = $this->getField("VESSEL_ID");
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function insert_offer()
    {
        $this->setField("VESSEL_ID", $this->getNextId("VESSEL_ID", "VESSEL"));

        $str = "
        INSERT INTO VESSEL (
            VESSEL_ID, COMPANY_ID, NAME,TYPE_VESSEL, CLASS_VESSEL,
            DIMENSION_L, DIMENSION_B, DIMENSION_D
        )
        VALUES (
            '" . $this->getField("VESSEL_ID") . "',
            '" . $this->getField("COMPANY_ID") . "',
            '" . $this->getField("NAME") . "',
            '" . $this->getField("TYPE_VESSEL") . "',
            '" . $this->getField("CLASS_VESSEL") . "',
            '" . $this->getField("DIMENSION_L") . "',
            '" . $this->getField("DIMENSION_B") . "',
            '" . $this->getField("DIMENSION_D") . "'
        )";

        $this->id = $this->getField("VESSEL_ID");
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function update_offer()
    {
        $str = "
            UPDATE VESSEL
                SET    
                COMPANY_ID ='" . $this->getField("COMPANY_ID") . "',
                NAME ='" . $this->getField("NAME") . "',
                TYPE_VESSEL ='" . $this->getField("TYPE_VESSEL") . "',
                CLASS_VESSEL ='" . $this->getField("CLASS_VESSEL") . "',
                DIMENSION_L ='" . $this->getField("DIMENSION_L") . "',
                DIMENSION_B ='" . $this->getField("DIMENSION_B") . "',
                DIMENSION_D ='" . $this->getField("DIMENSION_D") . "'
            WHERE CAST(VESSEL_ID AS VARCHAR)= '" . $this->getField("VESSEL_ID") . "'
        ";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function insert_reminder()
    {
        $this->setField("VESSEL_ID", $this->getNextId("VESSEL_ID", "VESSEL"));

        $str = "
        INSERT INTO VESSEL (
            VESSEL_ID, COMPANY_ID, NAME,TYPE_VESSEL, CLASS_VESSEL
        )
        VALUES (
            '" . $this->getField("VESSEL_ID") . "',
            '" . $this->getField("COMPANY_ID") . "',
            '" . $this->getField("NAME") . "',
            '" . $this->getField("TYPE_VESSEL") . "',
            '" . $this->getField("CLASS_VESSEL") . "'
        )";

        $this->id = $this->getField("VESSEL_ID");
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function update_reminder()
    {
        $str = "
            UPDATE VESSEL
                SET    
                COMPANY_ID ='" . $this->getField("COMPANY_ID") . "',
                NAME ='" . $this->getField("NAME") . "',
                TYPE_VESSEL ='" . $this->getField("TYPE_VESSEL") . "',
                CLASS_VESSEL ='" . $this->getField("CLASS_VESSEL") . "'
            WHERE VESSEL_ID= '" . $this->getField("VESSEL_ID") . "'
        ";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function update_baru()
    {
        $str = "
            UPDATE VESSEL
                SET    
              
                TELP ='" . $this->getField("TELP") . "',
                DATE_ORDER =" . $this->getField("DATE_ORDER") . ",
                DATE_SURVEY =" . $this->getField("DATE_SURVEY") ."
            WHERE VESSEL_ID= '" . $this->getField("VESSEL_ID") . "'
        ";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "UPDATE VESSEL
                SET    
                VESSEL_ID ='" . $this->getField("VESSEL_ID") . "',
                COMPANY_ID ='" . $this->getField("COMPANY_ID") . "',
                NAME ='" . $this->getField("NAME") . "',
                DIMENSION_L ='" . $this->getField("DIMENSION_L") . "',
                DIMENSION_B ='" . $this->getField("DIMENSION_B") . "',
                DIMENSION_D ='" . $this->getField("DIMENSION_D") . "',
                TYPE_VESSEL ='" . $this->getField("TYPE_VESSEL") . "',
                CLASS_VESSEL ='" . $this->getField("CLASS_VESSEL") . "',
                TYPE_SURVEY ='" . $this->getField("TYPE_SURVEY") . "',
                LOCATION_SURVEY ='" . $this->getField("LOCATION_SURVEY") . "',
                CONTACT_PERSON ='" . $this->getField("CONTACT_PERSON") . "',
                VALUE_SURVEY ='" . $this->getField("VALUE_SURVEY") . "',
                SURVEYOR_NAME ='" . $this->getField("SURVEYOR_NAME") . "',
                SURVEYOR_PHONE ='" . $this->getField("SURVEYOR_PHONE") . "',
                CURRENCY ='" . $this->getField("CURRENCY") . "', 
                CURRENCY_VALUE =" . $this->getField("CURRENCY_VALUE") . ",
                VALUE_DEADWEIGHT =" . $this->getField("VALUE_DEADWEIGHT") . ",
                VALUE_NET =" . $this->getField("VALUE_NET") . " 
        
        WHERE VESSEL_ID= '" . $this->getField("VESSEL_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

       function deleteParent(){
        $str = "DELETE FROM VESSEL
        WHERE COMPANY_ID= '" . $this->getField("COMPANY_ID") . "'";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);

       } 

    function delete($statement = "")
    {
        $str = "DELETE FROM VESSEL
        WHERE VESSEL_ID= '" . $this->getField("VESSEL_ID") . "'";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.VESSEL_ID ASC")
    {
        $str = "SELECT A.VESSEL_ID,A.COMPANY_ID,A.NAME,A.DIMENSION_L,A.DIMENSION_B,A.DIMENSION_D,A.TYPE_VESSEL,A.CLASS_VESSEL,COALESCE(B.NAMA, A.TYPE_SURVEY) TYPE_SURVEY,A.LOCATION_SURVEY,A.CONTACT_PERSON,A.VALUE_SURVEY,A.SURVEYOR_NAME,A.SURVEYOR_PHONE,A.CURRENCY,A.CURRENCY_VALUE,A.VALUE_DEADWEIGHT,A.VALUE_NET
        FROM VESSEL A
        LEFT JOIN SERVICES B ON A.TYPE_SURVEY = B.SERVICES_ID::VARCHAR
        WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParamsMonitoringDetailVesssel($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.VESSEL_ID ASC")
    {
                $str = "SELECT CONCAT(A.VESSEL_ID,'-',B.OFFER_ID) VESSEL_ID,A.COMPANY_ID,
        (CASE WHEN B.OFFER_ID IS NULL THEN A.NAME ELSE B.VESSEL_NAME END) AS  NAME,
         (CASE WHEN B.OFFER_ID IS NOT NULL THEN B.VESSEL_DIMENSION_L ELSE A.DIMENSION_L END ) AS DIMENSION_L,
         (CASE WHEN B.OFFER_ID IS NOT NULL THEN B.VESSEL_DIMENSION_B ELSE A.DIMENSION_B END ) AS DIMENSION_B,
         (CASE WHEN B.OFFER_ID IS NOT NULL THEN B.VESSEL_DIMENSION_D ELSE A.DIMENSION_D END) DIMENSION_D,
          (CASE WHEN B.OFFER_ID IS NOT NULL THEN B.TYPE_OF_VESSEL ELSE A.TYPE_VESSEL END) TYPE_VESSEL,
        (CASE WHEN B.OFFER_ID IS NOT NULL THEN B.CLASS_OF_VESSEL ELSE  A.CLASS_VESSEL END) CLASS_VESSEL
         ,(CASE WHEN C.NAMA IS  NULL THEN A.TYPE_SURVEY ELSE C.NAMA END ) AS TYPE_SURVEY,
         (CASE WHEN B.OFFER_ID IS NOT NULL THEN B.DESTINATION ELSE A.LOCATION_SURVEY END) AS LOCATION_SURVEY,
         (CASE WHEN B.OFFER_ID IS NOT NULL THEN 
         B.DOCUMENT_PERSON  ELSE A.CONTACT_PERSON END) AS CONTACT_PERSON ,

         ( CASE WHEN B.OFFER_ID IS NOT NULL THEN 
            (CASE WHEN A.TELP IS NULL OR A.TELP ='' THEN B.TELEPHONE ELSE A.TELP END)

            ELSE A.TELP END ) AS CONTACT_TELEPONE,
            ( CASE WHEN B.OFFER_ID IS NOT NULL THEN 
        (SELECT TO_CHAR(XX.START_DATE, 'DD-MM-YYYY') FROM DOKUMEN_REPORT XX WHERE XX.OFFER_ID = B.OFFER_ID LIMIT 1)
         ELSE TO_CHAR(A.DATE_ORDER , 'DD-MM-YYYY')END ) AS DATE_ORDER,
           A.DATE_SURVEY AS DATE_SERVICE,


         A.VALUE_SURVEY,A.SURVEYOR_NAME,A.SURVEYOR_PHONE,A.CURRENCY,A.CURRENCY_VALUE,A.VALUE_DEADWEIGHT,A.VALUE_NET,B.MASTER_REASON_ID,D.NAMA NAMA_REASON
                FROM VESSEL A
                LEFT JOIN OFFER B ON B.COMPANY_ID = A.COMPANY_ID AND A.VESSEL_ID=B.VESSEL_ID
                LEFT JOIN SERVICES C ON B.GENERAL_SERVICE = C.SERVICES_ID::VARCHAR
                LEFT JOIN MASTER_REASON D ON D.MASTER_REASON_ID = B.MASTER_REASON_ID
                
        WHERE 1=1  ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->selectLimit($str, $limit, $from);
    }

    function getCountByParamsMonitoringVessel($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM VESSEL A 

         INNER JOIN OFFER B ON B.COMPANY_ID = A.COMPANY_ID AND A.VESSEL_ID=B.VESSEL_ID
LEFT JOIN SERVICES c ON B.general_service = c.SERVICES_ID::VARCHAR
        WHERE 1=1 AND A.CURRENCY_VALUE IS NOT NULL  " . $statement;
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

    function selectByParamsHistory($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.VESSEL_ID ASC")
    {
        $str = "
            SELECT B.*
                FROM VESSEL A
                INNER JOIN SERVICE_ORDER B ON A.VESSEL_ID = B.VESSEL_ID
            WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    function getCountByParamsMonitoring($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM VESSEL A WHERE 1=1 " . $statement;
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
