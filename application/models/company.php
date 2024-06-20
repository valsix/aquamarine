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

class Company  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function Company()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("COMPANY_ID", $this->getNextId("COMPANY_ID", "COMPANY"));

        $str = "INSERT INTO COMPANY (COMPANY_ID, NAME, ADDRESS, PHONE, FAX, EMAIL, CP1_NAME, CP1_TELP,        
                            CP2_NAME, CP2_TELP, LA1_NAME, LA1_ADDRESS, LA1_PHONE, LA1_FAX,        
                            LA1_EMAIL, LA1_CP1, LA1_CP2, LA2_NAME, LA2_ADDRESS,        
                            LA2_TELP, LA2_FAX, LA2_EMAIL, LA2_CP1, LA2_CP2, LA1_CP1_PHONE,        
                            LA1_CP2_PHONE, LA2_CP1_PHONE, LA2_CP2_PHONE, TIPE)
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
                            '" . $this->getField("CP2_TELP") . "',
                            '" . $this->getField("LA1_NAME") . "',
                            '" . $this->getField("LA1_ADDRESS") . "',
                            '" . $this->getField("LA1_PHONE") . "',
                            '" . $this->getField("LA1_FAX") . "',
                            '" . $this->getField("LA1_EMAIL") . "',
                            '" . $this->getField("LA1_CP1") . "',
                            '" . $this->getField("LA1_CP2") . "',
                            '" . $this->getField("LA2_NAME") . "',
                            '" . $this->getField("LA2_ADDRESS") . "',
                            '" . $this->getField("LA2_TELP") . "',
                            '" . $this->getField("LA2_FAX") . "',
                            '" . $this->getField("LA2_EMAIL") . "',
                            '" . $this->getField("LA2_CP1") . "',
                            '" . $this->getField("LA2_CP2") . "',
                            '" . $this->getField("LA1_CP1_PHONE") . "',
                            '" . $this->getField("LA1_CP2_PHONE") . "',
                            '" . $this->getField("LA2_CP1_PHONE") . "',
                            '" . $this->getField("LA2_CP2_PHONE") . "',
                            0 
                        )";

        $this->id = $this->getField("COMPANY_ID");
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }
    function insert_offer()
    {
        $this->setField("COMPANY_ID", $this->getNextId("COMPANY_ID", "COMPANY"));

        $str = "INSERT INTO COMPANY (COMPANY_ID, NAME, ADDRESS, PHONE, FAX, EMAIL, CP1_NAME, CP1_TELP)VALUES (
        '" . $this->getField("COMPANY_ID") . "',
        '" . $this->getField("NAME") . "',
        '" . $this->getField("ADDRESS") . "',
        '" . $this->getField("PHONE") . "',
        '" . $this->getField("FAX") . "',
        '" . $this->getField("EMAIL") . "',
        '" . $this->getField("CP1_NAME") . "',
        '" . $this->getField("CP1_TELP") . "'
        
    )";

        $this->id = $this->getField("COMPANY_ID");
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }
    function insert_reminder()
    {
        $this->setField("COMPANY_ID", $this->getNextId("COMPANY_ID", "COMPANY"));

        $str = "INSERT INTO COMPANY (COMPANY_ID, NAME, ADDRESS, EMAIL, CP1_NAME, CP1_TELP)VALUES (
        '" . $this->getField("COMPANY_ID") . "',
        '" . $this->getField("NAME") . "',
        '" . $this->getField("ADDRESS") . "',
        '" . $this->getField("EMAIL") . "',
        '" . $this->getField("CP1_NAME") . "',
        '" . $this->getField("CP1_TELP") . "'
        
    )";

        $this->id = $this->getField("COMPANY_ID");
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }
     function updateSupplier()
    {
        $str = "UPDATE COMPANY
                SET        KATEGORI        = '" . $this->getField("KATEGORI") . "'
                WHERE  COMPANY_ID = '" . $this->getField("COMPANY_ID") . "'
             ";
        $this->query = $str;
        return $this->execQuery($str);
    }

     function updateSupplierLain()
    {
        $str = "UPDATE COMPANY
                SET       
                 BARAG_JASA        = '" . $this->getField("BARAG_JASA") . "'
                 , TINGKAT_PELAYANG        = '" . $this->getField("TINGKAT_PELAYANG") . "'
                  , KUALITAS        = '" . $this->getField("KUALITAS") . "'
                   , KETERANGAN_SUB        = '" . $this->getField("KETERANGAN_SUB") . "'
                    , HARGA_KET        = '" . $this->getField("HARGA_KET") . "'
                WHERE  COMPANY_ID = '" . $this->getField("COMPANY_ID") . "'
             ";
        $this->query = $str;
        return $this->execQuery($str);
    }


    function insert_owr()
    {
        $this->setField("COMPANY_ID", $this->getNextId("COMPANY_ID", "COMPANY"));

        $str = "INSERT INTO COMPANY (COMPANY_ID, NAME,  CP1_TELP)VALUES (
        '" . $this->getField("COMPANY_ID") . "',
        '" . $this->getField("NAME") . "',
      
        '" . $this->getField("CP1_TELP") . "'
        
    )";

        $this->id = $this->getField("COMPANY_ID");
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE COMPANY
        SET    
        COMPANY_ID ='" . $this->getField("COMPANY_ID") . "',
        NAME ='" . $this->getField("NAME") . "',
        ADDRESS ='" . $this->getField("ADDRESS") . "',
        PHONE ='" . $this->getField("PHONE") . "',
        FAX ='" . $this->getField("FAX") . "',
        EMAIL ='" . $this->getField("EMAIL") . "',
        CP1_NAME ='" . $this->getField("CP1_NAME") . "',
        CP1_TELP ='" . $this->getField("CP1_TELP") . "',
        CP2_NAME ='" . $this->getField("CP2_NAME") . "',
        CP2_TELP ='" . $this->getField("CP2_TELP") . "',
        LA1_NAME ='" . $this->getField("LA1_NAME") . "',
        LA1_ADDRESS ='" . $this->getField("LA1_ADDRESS") . "',
        LA1_PHONE ='" . $this->getField("LA1_PHONE") . "',
        LA1_FAX ='" . $this->getField("LA1_FAX") . "',
        LA1_EMAIL ='" . $this->getField("LA1_EMAIL") . "',
        LA1_CP1 ='" . $this->getField("LA1_CP1") . "',
        LA1_CP2 ='" . $this->getField("LA1_CP2") . "',
        LA2_NAME ='" . $this->getField("LA2_NAME") . "',
        LA2_ADDRESS ='" . $this->getField("LA2_ADDRESS") . "',
        LA2_TELP ='" . $this->getField("LA2_TELP") . "',
        LA2_FAX ='" . $this->getField("LA2_FAX") . "',
        LA2_EMAIL ='" . $this->getField("LA2_EMAIL") . "',
        LA2_CP1 ='" . $this->getField("LA2_CP1") . "',
        LA2_CP2 ='" . $this->getField("LA2_CP2") . "',
        LA1_CP1_PHONE ='" . $this->getField("LA1_CP1_PHONE") . "',
        LA1_CP2_PHONE ='" . $this->getField("LA1_CP2_PHONE") . "',
        LA2_CP1_PHONE ='" . $this->getField("LA2_CP1_PHONE") . "',
        LA2_CP2_PHONE ='" . $this->getField("LA2_CP2_PHONE") . "'
     
        WHERE COMPANY_ID= '" . $this->getField("COMPANY_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function update_offer()
    {
        $str = "
        UPDATE COMPANY
        SET    
        COMPANY_ID =" . $this->getField("COMPANY_ID") . ",
        NAME ='" . $this->getField("NAME") . "',
        ADDRESS ='" . $this->getField("ADDRESS") . "',
        PHONE ='" . $this->getField("PHONE") . "',
        FAX ='" . $this->getField("FAX") . "',
        EMAIL ='" . $this->getField("EMAIL") . "',
        CP1_NAME ='" . $this->getField("CP1_NAME") . "',
        CP1_TELP ='" . $this->getField("CP1_TELP") . "'
        
     
        WHERE COMPANY_ID= " . $this->getField("COMPANY_ID") . "";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function update_reminder()
    {
        $str = "
        UPDATE COMPANY
        SET    
        COMPANY_ID =" . $this->getField("COMPANY_ID") . ",
        NAME ='" . $this->getField("NAME") . "',
        ADDRESS ='" . $this->getField("ADDRESS") . "',
        EMAIL ='" . $this->getField("EMAIL") . "',
        CP1_NAME ='" . $this->getField("CP1_NAME") . "',
        CP1_TELP ='" . $this->getField("CP1_TELP") . "'
        
     
        WHERE COMPANY_ID= " . $this->getField("COMPANY_ID") . "";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }


    function update_owr()
    {
        $str = "
        UPDATE COMPANY
        SET    
        COMPANY_ID ='" . $this->getField("COMPANY_ID") . "',
        NAME ='" . $this->getField("NAME") . "',
        
        CP1_NAME ='" . $this->getField("CP1_NAME") . "'
      
        

        WHERE COMPANY_ID= '" . $this->getField("COMPANY_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function update_location()
    {
        $str = "
        UPDATE COMPANY
        SET    
        COMPANY_ID ='" . $this->getField("COMPANY_ID") . "',
        PROPINSI_ID ='" . $this->getField("PROPINSI_ID") . "',
        
        KABUPATEN_ID ='" . $this->getField("KABUPATEN_ID") . "'
      
        

        WHERE COMPANY_ID= '" . $this->getField("COMPANY_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement = "")
    {
        $str = "DELETE FROM COMPANY
        WHERE COMPANY_ID= '" . $this->getField("COMPANY_ID") . "'";
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    

    function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.COMPANY_ID ASC")
    {
        $str = "SELECT A.COMPANY_ID,A.NAME,A.ADDRESS,A.PHONE,A.FAX,A.EMAIL,A.CP1_NAME,A.CP1_TELP,A.CP2_NAME,A.CP2_TELP,A.LA1_NAME,A.LA1_ADDRESS,A.LA1_PHONE,A.LA1_FAX,A.LA1_EMAIL,A.LA1_CP1,A.LA1_CP2,A.LA2_NAME,A.LA2_ADDRESS,A.LA2_TELP,A.LA2_FAX,A.LA2_EMAIL,A.LA2_CP1,A.LA2_CP2,A.LA1_CP1_PHONE,A.LA1_CP2_PHONE,A.LA2_CP1_PHONE,A.LA2_CP2_PHONE,A.TIPE,A.BARAG_JASA,A.TINGKAT_PELAYANG,A.KUALITAS,A.KETERANGAN_SUB,A.HARGA_KET,A.PROPINSI_ID,A.KABUPATEN_ID
                FROM COMPANY A
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


    function getCountByParamsMonitoring($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM COMPANY A WHERE 1=1 " . $statement;
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
