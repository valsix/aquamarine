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

class Invoice extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function Invoice()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("INVOICE_ID", $this->getNextId("INVOICE_ID", "INVOICE"));

        $str = "INSERT INTO INVOICE (INVOICE_ID, INVOICE_NUMBER, COMPANY_ID, INVOICE_DATE, PO_DATE, PPN, COMPANY_NAME,CONTACT_NAME, ADDRESS, TELEPHONE, FAXIMILE, EMAIL, PPN_PERCENT,STATUS, INVOICE_PO, INVOICE_TAX, TERMS, NO_KONTRAK, NO_REPORT,DAYS,TOTAL_AMOUNT,DP,HP,TOTAL_WORD,MANUAL_PPN,NOMINAL_MANUAL,PPH,JENIS_PPH,TAX_MANUAL,JENIS_TAX,PPHPERCENT)
        VALUES (
                '" . $this->getField("INVOICE_ID") . "',
                '" . $this->getField("INVOICE_NUMBER") . "',
                " . $this->getField("COMPANY_ID") . ",
                " . $this->getField("INVOICE_DATE") . ",
                " . $this->getField("PO_DATE") . ",
                '" . $this->getField("PPN") . "',
                '" . $this->getField("COMPANY_NAME") . "',
                '" . $this->getField("CONTACT_NAME") . "',
                '" . $this->getField("ADDRESS") . "',
                '" . $this->getField("TELEPHONE") . "',
                '" . $this->getField("FAXIMILE") . "',
                '" . $this->getField("EMAIL") . "',
                '" . $this->getField("PPN_PERCENT") . "',
                '" . $this->getField("STATUS") . "',
                '" . $this->getField("INVOICE_PO") . "',
                '" . $this->getField("INVOICE_TAX") . "',
                '" . $this->getField("TERMS") . "',
                '" . $this->getField("NO_KONTRAK") . "',
                '" . $this->getField("NO_REPORT") . "', 
                '" . $this->getField("DAYS") . "',
                " . $this->getField("TOTAL_AMOUNT") . ",
                " . $this->getField("DP") . ",
                '" . $this->getField("HP") . "',
                '" . $this->getField("TOTAL_WORD") . "',
                '" . $this->getField("MANUAL_PPN") . "',
                '" . $this->getField("NOMINAL_MANUAL") . "',
                 '" . $this->getField("PPH") . "',
                  '" . $this->getField("JENIS_PPH") . "',
                  '" . $this->getField("TAX_MANUAL") . "',
                     '" . $this->getField("JENIS_TAX") . "',
                '" . $this->getField("PPHPERCENT") . "'
            )";

        $this->id = $this->getField("INVOICE_ID");
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "UPDATE INVOICE
                SET    
                INVOICE_ID ='" . $this->getField("INVOICE_ID") . "',
                INVOICE_NUMBER ='" . $this->getField("INVOICE_NUMBER") . "',
                COMPANY_ID =" . $this->getField("COMPANY_ID") . ",
                INVOICE_DATE =" . $this->getField("INVOICE_DATE") . ",
                PO_DATE =" . $this->getField("PO_DATE") . ",
                PPN ='" . $this->getField("PPN") . "',
                COMPANY_NAME ='" . $this->getField("COMPANY_NAME") . "',
                CONTACT_NAME ='" . $this->getField("CONTACT_NAME") . "',
                ADDRESS ='" . $this->getField("ADDRESS") . "',
                TELEPHONE ='" . $this->getField("TELEPHONE") . "',
                FAXIMILE ='" . $this->getField("FAXIMILE") . "',
                EMAIL ='" . $this->getField("EMAIL") . "',
                PPN_PERCENT ='" . $this->getField("PPN_PERCENT") . "',
                STATUS ='" . $this->getField("STATUS") . "',
                INVOICE_PO ='" . $this->getField("INVOICE_PO") . "',
                INVOICE_TAX ='" . $this->getField("INVOICE_TAX") . "',
                TERMS ='" . $this->getField("TERMS") . "',
                TAX_MANUAL ='" . $this->getField("TAX_MANUAL") . "',
                NO_KONTRAK ='" . $this->getField("NO_KONTRAK") . "',
                DAYS ='" . $this->getField("DAYS") . "',
                NO_REPORT ='" . $this->getField("NO_REPORT") . "',
                 MANUAL_PPN ='" . $this->getField("MANUAL_PPN") . "',
                 NOMINAL_MANUAL ='" . $this->getField("NOMINAL_MANUAL") . "',
                TOTAL_AMOUNT =" . $this->getField("TOTAL_AMOUNT") . ",
                  PPH ='" . $this->getField("PPH") . "',
                PPHPERCENT ='" . $this->getField("PPHPERCENT") . "',
                DP =" . $this->getField("DP") . ",
                HP ='" . $this->getField("HP") . "',  
                JENIS_TAX ='" . $this->getField("JENIS_TAX") . "',  
                JENIS_PPH ='" . $this->getField("JENIS_PPH") . "',  
                TOTAL_WORD ='" . $this->getField("TOTAL_WORD") . "'  
                WHERE INVOICE_ID= '" . $this->getField("INVOICE_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

     function updateRo()
    {
        $str = "UPDATE INVOICE
                SET    
                
              
                RO_NOMER ='" . $this->getField("RO_NOMER") . "'
                , RO_DATE =" . $this->getField("RO_DATE") . "
                , RO_CHECK ='" . $this->getField("RO_CHECK") . "'
                , REMARK ='" . $this->getField("REMARK") . "'
              
                WHERE INVOICE_ID= " . $this->getField("INVOICE_ID") . "";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement = "")
    {
        $str = "DELETE FROM INVOICE
                WHERE INVOICE_ID= '" . $this->getField("INVOICE_ID") . "'";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.INVOICE_ID ASC")
    {
        $str = "SELECT A.INVOICE_ID,A.INVOICE_NUMBER,A.COMPANY_ID,A.INVOICE_DATE,A.PO_DATE,TO_CHAR(A.INVOICE_DATE, 'DD-MM-YYYY') INVOICE_DATE2,TO_CHAR(A.PO_DATE, 'DD-MM-YYYY') PO_DATE2,A.PPN,A.COMPANY_NAME,A.CONTACT_NAME,A.ADDRESS,A.TELEPHONE,A.FAXIMILE,A.EMAIL,A.PPN_PERCENT,A.STATUS,A.INVOICE_PO,A.INVOICE_TAX,A.TERMS,A.NO_KONTRAK,A.NO_REPORT,
            (A.INVOICE_DATE - CURRENT_DATE) DAYS,A.DP,A.OFFER_ID,B.SUBJECT,A.HP,A.TOTAL_WORD,A.MANUAL_PPN,A.NOMINAL_MANUAL,a.PPHPERCENT,A.PPH,A.JENIS_PPH,A.TAX_MANUAL,A.JENIS_TAX,A.RO_CHECK,A.RO_DATE,A.RO_NOMER,A.REMARK
                FROM INVOICE A
                LEFT JOIN OFFER B ON A.OFFER_ID = B.OFFER_ID
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    // function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY I.INVOICE_ID ASC")
    // {
    //     $str = "SELECT I.INVOICE_ID, I.INVOICE_NUMBER , I.COMPANY_NAME , 
    //             (SELECT VESSEL FROM INVOICE_DETAIL X WHERE X.INVOICE_ID = I.INVOICE_ID LIMIT 1) VESSEL_NAME, TO_CHAR(I.INVOICE_DATE, 'DAY,MONTH DD YYYY') INVOICE_DATE, 
    //             ( CASE WHEN CAST(I.PPN AS INT)  = 0  THEN 'NO'
    //             ELSE 'YES'
    //             END ) PPN,
    //             I.STATUS STATUS,

    //             ( CASE WHEN CAST(D.CURRENCY AS VARCHAR) = '0'  THEN CONCAT('USD ', FORMAT(CAST(AMOUNT AS VARCHAR), 2))
    //             ELSE CONCAT('RP. ', FORMAT(CAST(AMOUNT AS VARCHAR), 2)) 
    //             END ) TOTAL_AMOUNT
    //             ,I.DAYS

    //             FROM INVOICE I LEFT JOIN INVOICE_DETAIL D ON I.INVOICE_ID = D.INVOICE_ID
    //             WHERE 1=1 ";
    //     while (list($key, $val) = each($paramsArray)) {
    //         $str .= " AND $key = '$val'";
    //     }

    //     $str .= $statement . " " . $order;
    //     $this->query = $str;
    //     return $this->selectLimit($str, $limit, $from);
    // }
    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY I.INVOICE_ID ASC")
    {
        $str = "SELECT I.INVOICE_ID, I.INVOICE_NUMBER , I.COMPANY_NAME , 
                (SELECT VESSEL FROM INVOICE_DETAIL X WHERE X.INVOICE_ID = I.INVOICE_ID LIMIT 1) VESSEL_NAME, TO_CHAR(I.INVOICE_DATE, 'DD-MM-YYYY') INVOICE_DATE, PO_DATE, 
                ( CASE WHEN CAST(I.PPN AS INT)  = 0  THEN 'NO'
                ELSE 'YES'
                END ) PPN,
                I.STATUS STATUS, I.TOTAL_AMOUNT
                ,(I.INVOICE_DATE - CURRENT_DATE) DAYS,I.HP,I.MANUAL_PPN,I.NOMINAL_MANUAL

                FROM INVOICE I 
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }
     function selectByParamsNews($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY I.INVOICE_ID ASC")
    {
        $str = "SELECT I.INVOICE_ID, I.INVOICE_NUMBER , I.COMPANY_NAME , 
                (SELECT VESSEL FROM INVOICE_DETAIL X WHERE X.INVOICE_ID = I.INVOICE_ID AND X.VESSEL !='' LIMIT 1) VESSEL_NAME, TO_CHAR(I.INVOICE_DATE, 'DD-MM-YYYY') INVOICE_DATE, PO_DATE, 
                ( CASE WHEN CAST(I.PPN AS INT)  = 0  THEN 'NO'
                ELSE 'YES'
                END ) PPN,
                I.STATUS STATUS, I.TOTAL_AMOUNT
                ,(I.INVOICE_DATE - CURRENT_DATE) DAYS,I.HP,I.MANUAL_PPN,I.NOMINAL_MANUAL

                FROM INVOICE I 
                  -- LEFT JOIN INVOICE_DETAIL D ON I.INVOICE_ID = D.INVOICE_ID
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }


    function selectByParamsCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY I.INVOICE_ID ASC")
    {
        $str = "SELECT I.INVOICE_ID, I.INVOICE_NUMBER , I.COMPANY_NAME , 
                (SELECT VESSEL FROM INVOICE_DETAIL X WHERE X.INVOICE_ID = I.INVOICE_ID LIMIT 1) VESSEL_NAME, TO_CHAR(I.INVOICE_DATE, 'DAY,MONTH DD YYYY') INVOICE_DATE, PO_DATE, 
                ( CASE WHEN CAST(I.PPN AS INT)  = 0  THEN 'NO'
                ELSE 'YES'
                END ) PPN,
                I.STATUS STATUS,

                ( CASE WHEN CAST(D.CURRENCY AS VARCHAR) = '0'  THEN CONCAT('USD ', FORMAT(CAST(AMOUNT AS VARCHAR), 2))
                ELSE CONCAT('RP. ', FORMAT(CAST(AMOUNT AS VARCHAR), 2)) 
                END ) TOTAL_AMOUNT, I.HP,I.MANUAL_PPN,I.NOMINAL_MANUAL

                FROM INVOICE I LEFT JOIN INVOICE_DETAIL D ON I.INVOICE_ID = D.INVOICE_ID
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }


    function selectByParamsCetakExcel($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY I.INVOICE_ID ASC")
    {
        $str = "SELECT I.INVOICE_ID, I.INVOICE_NUMBER , I.COMPANY_NAME , 
                (SELECT VESSEL FROM INVOICE_DETAIL X WHERE X.INVOICE_ID = I.INVOICE_ID LIMIT 1) VESSEL_NAME, TO_CHAR(I.INVOICE_DATE, 'DAY,MONTH DD YYYY') INVOICE_DATE, PO_DATE, 
                ( CASE WHEN CAST(I.PPN AS INT)  = 0  THEN 'NO'
                ELSE 'YES'
                END ) PPN,
                I.STATUS STATUS,

                ( CASE WHEN CAST(D.CURRENCY AS VARCHAR) = '0'  THEN CONCAT('USD ', FORMAT(CAST(AMOUNT AS VARCHAR), 2))
                ELSE CONCAT('RP. ', FORMAT(CAST(AMOUNT AS VARCHAR), 2)) 
                END ) TOTAL_AMOUNT,I.HP

                FROM INVOICE I LEFT JOIN INVOICE_DETAIL D ON I.INVOICE_ID = D.INVOICE_ID
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM INVOICE A 
                LEFT JOIN INVOICE_DETAIL D ON A.INVOICE_ID = D.INVOICE_ID
                WHERE 1=1 " . $statement;
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key =    '$val' ";
        }
        $str = $str.' '.$statement;
        $this->query = $str;
        $this->select($str);
        if ($this->firstRow())
            return $this->getField("ROWCOUNT");
        else
            return 0;
    }
     function getCountByParamsNews($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM INVOICE I 
                -- LEFT JOIN INVOICE_DETAIL D ON I.INVOICE_ID = D.INVOICE_ID
                WHERE 1=1 " . $statement;
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key =    '$val' ";
        }
        $str = $str.' '.$statement;
        $this->query = $str;
        $this->select($str);
        if ($this->firstRow())
            return $this->getField("ROWCOUNT");
        else
            return 0;
    }
    function getCountByParamsNews2($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM INVOICE I 
                
                WHERE 1=1 " . $statement;
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key =    '$val' ";
        }
        $str = $str.' '.$statement;
        $this->query = $str;
        $this->select($str);
        if ($this->firstRow())
            return $this->getField("ROWCOUNT");
        else
            return 0;
    }

    function getCountByParamsMonitoring($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM INVOICE A WHERE 1=1 " . $statement;
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
