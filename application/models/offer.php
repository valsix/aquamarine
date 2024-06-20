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
include_once("functions/string.func.php");
include_once("functions/date.func.php");
class Offer extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function Offer()
    {
        $this->Entity();
    }

    function insert()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $this->setField("OFFER_ID", $this->getNextId("OFFER_ID", "OFFER"));

        $str = "INSERT INTO OFFER(
             OFFER_ID, NO_ORDER, EMAIL, DESTINATION, COMPANY_NAME, VESSEL_NAME, TYPE_OF_VESSEL, FAXIMILE, TYPE_OF_SERVICE, TOTAL_PRICE, SCOPE_OF_WORK)
			VALUES (
			" . $this->getField("OFFER_ID") . ",
			'" . $this->getField("NO_ORDER") . "',
			'" . $this->getField("EMAIL") . "',
			'" . $this->getField("DESTINATION") . "',
			'" . $this->getField("COMPANY_NAME") . "',
			'" . $this->getField("VESSEL_NAME") . "',
			'" . $this->getField("TYPE_OF_VESSEL") . "',
			'" . $this->getField("FAXIMILE") . "',
			'" . $this->getField("TYPE_OF_SERVICE") . "',
			'" . $this->getField("TOTAL_PRICE") . "',
			'" . $this->getField("SCOPE_OF_WORK") . "'
			)";

        $this->id = $this->getField("OFFER_ID");
        $this->query = $str;
        // echo $str;
        // exit;

        return $this->execQuery($str);
    }
    function insert_offer_from_hpp(){
        $reqPriceUnit = "Vessel";
        $reqPaymentMethod = "
        <p>Advance Payment&nbsp;&nbsp;&nbsp; : 50% - Upon SO issued and prior mobilization</p>
        <p>Balance Payment&nbsp;&nbsp;&nbsp;&nbsp; : 50% - Before Invoice and Report Sent</p>
         ";
        $reqMinimumCharger = "<p>7 Days (by hydraulic power pack to cover works for maximum marine growth is 2 cm with approx 50%. Coverage, Beyond & days, Daily rate will applicable)</p>";
        $reqTechicalScope1 = array(
            2 => array("INC" => "Include", "ENC" => false, "REMARK" => "Propeller And Stern Tube Only "),
            3 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            4 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            5 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            6 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            7 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            8 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            9 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
            10 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            20 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
           
            21 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
             22 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            25 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
            29 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            30 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            31 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
          );
        $reqTechicalScope1 = json_encode($reqTechicalScope1);
        $reqTechicalScope2 = array(
                2 => array("INC" => false, "ENC" => "Exclude", "REMARK" => "Provided by Client"),
                3 => array("INC" => false, "ENC" => "Exclude", "REMARK" => "Provided by Client"),
                4 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                5 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                6 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                7 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                8 => array("INC" => "Include", "ENC" => false, "REMARK" => "Jamsostek"),
                9 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
                10 => array("INC" => "Include", "ENC" => false, "REMARK" => "At Cost by Client (Rapid Test Only)"),
                11 => array("INC" => false, "ENC" => "Exclude", "REMARK" => "At Cost by Client(If Needed)"),
                14 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                15 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                16 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                17 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                18 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
                19 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
                20 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
                21 => array("INC" => "Include", "ENC" => false, "REMARK" => "")
            );
        $reqTechicalScope2 = json_encode($reqTechicalScope2);
        $reqTechicalScope3 = array(
            1 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            2 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            3 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            4 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            5 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
            6 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
            7 => array("INC" => false, "ENC" => "Exclude", "REMARK" => ""),
            8 => array("INC" => "Include", "ENC" => false, "REMARK" => ""),
            9 => array("INC" => false, "ENC" => "Exclude", "REMARK" => "")
          );
        $reqTechicalScope3 = json_encode($reqTechicalScope3);
        
        
        
        
        
        

         $this->setField("OFFER_ID", $this->getNextId("OFFER_ID", "OFFER"));
          $str = "INSERT INTO OFFER(
             OFFER_ID, NO_ORDER,PRICE_UNIT,PAYMENT_METHOD,MINIMUM_CHARGER,TECHICAL_SCOPE,TECHICAL_SUPPORT,COMMERCIAL_SUPPORT,HPP_PROJECT_ID)
            VALUES (
            " . $this->getField("OFFER_ID") . ",
            '" . $this->getField("NO_ORDER") . "',
             '" . $reqPriceUnit . "',
              '" . $reqPaymentMethod . "',
               '" . $reqMinimumCharger . "',
                '" .$reqTechicalScope1 . "',
                 '" . $reqTechicalScope2 . "',
                  '" . $reqTechicalScope3 . "',
            '" . $this->getField("HPP_PROJECT_ID") . "'
            
            )";

        $this->id = $this->getField("OFFER_ID");
        $this->query = $str;
        // echo $str;
        // exit;

        return $this->execQuery($str);

    }

    function insert_new()
    {
        $this->setField("OFFER_ID", $this->getNextId("OFFER_ID", "OFFER"));

        $str = "INSERT INTO OFFER (OFFER_ID,  DOCUMENT_PERSON, DESTINATION, DATE_OF_SERVICE,TYPE_OF_SERVICE, SCOPE_OF_WORK, TERM_AND_CONDITION, PAYMENT_METHOD,        
                TOTAL_PRICE, PRICE_UNIT, TOTAL_PRICE_WORD, STATUS, REASON, NO_ORDER, DATE_OF_ORDER,        
                COMPANY_NAME, ADDRESS, FAXIMILE, EMAIL, TELEPHONE, HP, VESSEL_NAME,        
                TYPE_OF_VESSEL, CLASS_OF_VESSEL, MAKER,CLASS_ADDEND,CLASS_ADDEND2,CONTACT_PERSON,STAND_BY_RATE,LAND_TRANSPORT,
                SO_DAYS,LUMPSUM_DAYS,VESSEL_DIMENSION_L,VESSEL_DIMENSION_B,VESSEL_DIMENSION_D,PENANGGUNG_JAWAB_ID,PO_NAME,PO_DESCRIPTION,MINIMUM_CHARGER,WORK_TIME,MASTER_REASON_ID)
                VALUES (
                '" . $this->getField("OFFER_ID") . "',
                '" . $this->getField("DOCUMENT_PERSON") . "',
                '" . $this->getField("DESTINATION") . "',
                " . $this->getField("DATE_OF_SERVICE") . ",
                '" . $this->getField("TYPE_OF_SERVICE") . "',
                '" . $this->getField("SCOPE_OF_WORK") . "',
                '" . $this->getField("TERM_AND_CONDITION") . "',
                '" . $this->getField("PAYMENT_METHOD") . "',
                '" . $this->getField("TOTAL_PRICE") . "',
                '" . $this->getField("PRICE_UNIT") . "',
                '" . $this->getField("TOTAL_PRICE_WORD") . "',
                " . $this->getField("STATUS") . ",
                '" . $this->getField("REASON") . "',
                '" . $this->getField("NO_ORDER") . "',
                " . $this->getField("DATE_OF_ORDER") . ",
                '" . $this->getField("COMPANY_NAME") . "',
                '" . $this->getField("ADDRESS") . "',
                '" . $this->getField("FAXIMILE") . "',
                '" . $this->getField("EMAIL") . "',
                '" . $this->getField("TELEPHONE") . "',
                '" . $this->getField("HP") . "',
                '" . $this->getField("VESSEL_NAME") . "',
                '" . $this->getField("TYPE_OF_VESSEL") . "',
                '" . $this->getField("CLASS_OF_VESSEL") . "',
                '" . $this->getField("MAKER") . "', 
                '" . $this->getField("CLASS_ADDEND") . "', 
                '" . $this->getField("CLASS_ADDEND2") . "', 
                '" . $this->getField("CONTACT_PERSON") . "',
                '" . $this->getField("STAND_BY_RATE") . "',
                '" . $this->getField("LAND_TRANSPORT") . "',
                '" . $this->getField("SO_DAYS") . "',
                '" . $this->getField("LUMPSUM_DAYS") . "',
                '" . $this->getField("VESSEL_DIMENSION_L") . "', 
                '" . $this->getField("VESSEL_DIMENSION_B") . "',
                '" . $this->getField("VESSEL_DIMENSION_D") . "',
                " . $this->getField("PENANGGUNG_JAWAB_ID") . ",
                '" . $this->getField("PO_NAME") . "',
                '" . $this->getField("PO_DESCRIPTION") . "',
                '" . $this->getField("MINIMUM_CHARGER") . "',
                
                '" . $this->getField("WORK_TIME") . "',
                 " . $this->getField("MASTER_REASON_ID") . "
            )";

        $this->id = $this->getField("OFFER_ID");
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }

   function update_vessel_form_hpp(){
         $str = "
        UPDATE OFFER
        SET    
          VESSEL_DIMENSION_L ='".$this->getField("VESSEL_DIMENSION_L")."',
             VESSEL_DIMENSION_B ='".$this->getField("VESSEL_DIMENSION_B")."',
               VESSEL_DIMENSION_D ='".$this->getField("VESSEL_DIMENSION_D")."'
               WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'";
                 $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    

     function update_offer_from_hpp(){
        $str = "
        UPDATE OFFER
        SET    
        NO_ORDER ='".$this->getField("NO_ORDER")."',
         ADDRESS ='".$this->getField("ADDRESS")."',
         FAXIMILE ='".$this->getField("FAXIMILE")."',
         EMAIL ='".$this->getField("EMAIL")."',
         TELEPHONE ='".$this->getField("TELEPHONE")."',
         HP ='".$this->getField("HP")."',
         DATE_OF_ORDER =".$this->getField("DATE_OF_ORDER").",
         VESSEL_NAME ='".$this->getField("VESSEL_NAME")."',
         
         TYPE_OF_VESSEL ='".$this->getField("TYPE_OF_VESSEL")."',
         CLASS_OF_VESSEL ='".$this->getField("CLASS_OF_VESSEL")."',
         GENERAL_SERVICE ='".$this->getField("GENERAL_SERVICE")."',
         DOCUMENT_PERSON ='".$this->getField("DOCUMENT_PERSON")."',         
          COMPANY_ID =".retNullString($this->getField("COMPANY_ID")).",
           VESSEL_ID =".retNullString($this->getField("VESSEL_ID")).",
            DESTINATION ='".$this->getField("DESTINATION")."',
            TOTAL_PRICE ='".$this->getField("TOTAL_PRICE")."',

            

         COMPANY_NAME ='".$this->getField("COMPANY_NAME")."'
         WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function update_rev_history(){
        $str = "
        UPDATE OFFER
        SET    
        REV_HISTORY ='".$this->getField("REV_HISTORY")."'
         WHERE OFFER_ID= '".$this->getField("OFFER_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }
     function update_status(){
        $str = "
        UPDATE OFFER
        SET    
        STATUS =".retNullString($this->getField("REV_HISTORY"))."
         WHERE OFFER_ID= '".$this->getField("OFFER_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function update_tambahan_baru(){
      
        $str = "
        UPDATE OFFER
        SET    
        ISSUE_DATE =".$this->getField("ISSUE_DATE").",
        PREPARED_BY ='".$this->getField("PREPARED_BY")."',
        REVIEWED_BY ='".$this->getField("REVIEWED_BY")."',
        APPROVED_BY ='".$this->getField("APPROVED_BY")."',
        ISSUE_PURPOSE ='".$this->getField("ISSUE_PURPOSE")."',
        SUBJECT ='".$this->getField("SUBJECT")."',
        GENERAL_SERVICE ='".$this->getField("GENERAL_SERVICE")."',
        GENERAL_SERVICE_DETAIL ='".$this->getField("GENERAL_SERVICE_DETAIL")."',
        PROPOSAL_VALIDATY ='".$this->getField("PROPOSAL_VALIDATY")."',
        TECHICAL_SCOPE ='".$this->getField("TECHICAL_SCOPE")."',
        TECHICAL_SUPPORT ='".$this->getField("TECHICAL_SUPPORT")."',
        COMMERCIAL_SUPPORT ='".$this->getField("COMMERCIAL_SUPPORT")."' 
        WHERE OFFER_ID= '".$this->getField("OFFER_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);

    }

    function update_new()
    {
        $str = "UPDATE OFFER
                SET    
                    OFFER_ID ='" . $this->getField("OFFER_ID") . "',
                
                    DOCUMENT_PERSON ='" . $this->getField("DOCUMENT_PERSON") . "',
                    DESTINATION ='" . $this->getField("DESTINATION") . "',
                    DATE_OF_SERVICE =" . $this->getField("DATE_OF_SERVICE") . ",
                    TYPE_OF_SERVICE ='" . $this->getField("TYPE_OF_SERVICE") . "',
                    SCOPE_OF_WORK ='" . $this->getField("SCOPE_OF_WORK") . "',
                    TERM_AND_CONDITION ='" . $this->getField("TERM_AND_CONDITION") . "',
                    PAYMENT_METHOD ='" . $this->getField("PAYMENT_METHOD") . "',
                    TOTAL_PRICE ='" . $this->getField("TOTAL_PRICE") . "',
                    MASTER_REASON_ID =" . $this->getField("MASTER_REASON_ID") . ",
                    PRICE_UNIT ='" . $this->getField("PRICE_UNIT") . "',
                    TOTAL_PRICE_WORD ='" . $this->getField("TOTAL_PRICE_WORD") . "',
                    STATUS =" . $this->getField("STATUS") . ",
                    REASON ='" . $this->getField("REASON") . "',
                    NO_ORDER ='" . $this->getField("NO_ORDER") . "',
                    DATE_OF_ORDER =" . $this->getField("DATE_OF_ORDER") . ",
                    COMPANY_NAME ='" . $this->getField("COMPANY_NAME") . "',
                    CONTACT_PERSON ='" . $this->getField("CONTACT_PERSON") . "',
                    ADDRESS ='" . $this->getField("ADDRESS") . "',
                    FAXIMILE ='" . $this->getField("FAXIMILE") . "',
                    EMAIL ='" . $this->getField("EMAIL") . "',
                    TELEPHONE ='" . $this->getField("TELEPHONE") . "',
                    HP ='" . $this->getField("HP") . "',
                    VESSEL_NAME ='" . $this->getField("VESSEL_NAME") . "',
                    TYPE_OF_VESSEL ='" . $this->getField("TYPE_OF_VESSEL") . "',
                    CLASS_OF_VESSEL ='" . $this->getField("CLASS_OF_VESSEL") . "',
                    CLASS_ADDEND ='" . $this->getField("CLASS_ADDEND") . "',
                    CLASS_ADDEND2 ='" . $this->getField("CLASS_ADDEND2") . "',
                    MAKER ='" . $this->getField("MAKER") . "',
                    STAND_BY_RATE ='" . $this->getField("STAND_BY_RATE") . "',
                    LAND_TRANSPORT ='" . $this->getField("LAND_TRANSPORT") . "',
                    SO_DAYS ='" . $this->getField("SO_DAYS") . "',
                    LUMPSUM_DAYS ='" . $this->getField("LUMPSUM_DAYS") . "',
                    VESSEL_DIMENSION_L ='" . $this->getField("VESSEL_DIMENSION_L") . "',
                    VESSEL_DIMENSION_B ='" . $this->getField("VESSEL_DIMENSION_B") . "',
                    VESSEL_DIMENSION_D ='" . $this->getField("VESSEL_DIMENSION_D") . "', 
                    PENANGGUNG_JAWAB_ID =" . $this->getField("PENANGGUNG_JAWAB_ID") . ",
                    PO_NAME ='" . $this->getField("PO_NAME") . "', 
                    PO_DESCRIPTION ='" . $this->getField("PO_DESCRIPTION") . "',
                    MINIMUM_CHARGER ='" . $this->getField("MINIMUM_CHARGER") . "',
                    WORK_TIME ='" . $this->getField("WORK_TIME") . "'
                WHERE OFFER_ID= '" . $this->getField("OFFER_ID") . "'";
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }

    function update_revisi()
    {
        $str = "UPDATE OFFER_REVISI
                SET    
                    OFFER_ID ='" . $this->getField("OFFER_ID") . "',
                    DOCUMENT_PERSON ='" . $this->getField("DOCUMENT_PERSON") . "',
                    DESTINATION ='" . $this->getField("DESTINATION") . "',
                    DATE_OF_SERVICE =" . $this->getField("DATE_OF_SERVICE") . ",
                    TYPE_OF_SERVICE ='" . $this->getField("TYPE_OF_SERVICE") . "',
                    SCOPE_OF_WORK ='" . $this->getField("SCOPE_OF_WORK") . "',
                    TERM_AND_CONDITION ='" . $this->getField("TERM_AND_CONDITION") . "',
                    PAYMENT_METHOD ='" . $this->getField("PAYMENT_METHOD") . "',
                    TOTAL_PRICE ='" . $this->getField("TOTAL_PRICE") . "',
                    PRICE_UNIT ='" . $this->getField("PRICE_UNIT") . "',
                    TOTAL_PRICE_WORD ='" . $this->getField("TOTAL_PRICE_WORD") . "',
                    STATUS =" . $this->getField("STATUS") . ",
                    REASON ='" . $this->getField("REASON") . "',
                    NO_ORDER ='" . $this->getField("NO_ORDER") . "',
                    DATE_OF_ORDER =" . $this->getField("DATE_OF_ORDER") . ",
                    COMPANY_NAME ='" . $this->getField("COMPANY_NAME") . "',
                    CONTACT_PERSON ='" . $this->getField("CONTACT_PERSON") . "',
                    ADDRESS ='" . $this->getField("ADDRESS") . "',
                    FAXIMILE ='" . $this->getField("FAXIMILE") . "',
                    EMAIL ='" . $this->getField("EMAIL") . "',
                    TELEPHONE ='" . $this->getField("TELEPHONE") . "',
                    HP ='" . $this->getField("HP") . "',
                    VESSEL_NAME ='" . $this->getField("VESSEL_NAME") . "',
                    TYPE_OF_VESSEL ='" . $this->getField("TYPE_OF_VESSEL") . "',
                    CLASS_OF_VESSEL ='" . $this->getField("CLASS_OF_VESSEL") . "',
                    CLASS_ADDEND ='" . $this->getField("CLASS_ADDEND") . "',
                    MAKER ='" . $this->getField("MAKER") . "',
                    STAND_BY_RATE ='" . $this->getField("STAND_BY_RATE") . "',
                    LAND_TRANSPORT ='" . $this->getField("LAND_TRANSPORT") . "',
                    SO_DAYS ='" . $this->getField("SO_DAYS") . "',
                    LUMPSUM_DAYS ='" . $this->getField("LUMPSUM_DAYS") . "',
                    VESSEL_DIMENSION_L ='" . $this->getField("VESSEL_DIMENSION_L") . "',
                    VESSEL_DIMENSION_B ='" . $this->getField("VESSEL_DIMENSION_B") . "',
                    VESSEL_DIMENSION_D ='" . $this->getField("VESSEL_DIMENSION_D") . "', 
                    PENANGGUNG_JAWAB_ID =" . $this->getField("PENANGGUNG_JAWAB_ID") . ",
                    PO_NAME ='" . $this->getField("PO_NAME") . "', 
                    PO_DESCRIPTION ='" . $this->getField("PO_DESCRIPTION") . "',
                    MINIMUM_CHARGER ='" . $this->getField("MINIMUM_CHARGER") . "',
                    WORK_TIME ='" . $this->getField("WORK_TIME") . "'
                WHERE OFFER_REVISI_ID= '" . $this->getField("OFFER_REVISI_ID") . "'";
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }

    function updateByField()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $str = "UPDATE OFFER A SET
				  " . $this->getField("FIELD") . " = '" . $this->getField("FIELD_VALUE") . "'
				WHERE OFFER_ID = " . $this->getField("OFFER_ID") . "
				";
        $this->query = $str;

        return $this->execQuery($str);
    }

    function updateRevisiByField()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $str = "UPDATE OFFER_REVISI A SET
                  " . $this->getField("FIELD") . " = '" . $this->getField("FIELD_VALUE") . "'
                WHERE OFFER_REVISI_ID = " . $this->getField("OFFER_REVISI_ID") . "
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
        $str = "UPDATE OFFER
				SET
                    OFFER_ID =  " . $this->getField("OFFER_ID") . ",
                    NO_ORDER ='" . $this->getField("NO_ORDER") . "',
                    EMAIL ='" . $this->getField("EMAIL") . "',
                    DESTINATION ='" . $this->getField("DESTINATION") . "',
                    COMPANY_NAME ='" . $this->getField("COMPANY_NAME") . "',
                    VESSEL_NAME ='" . $this->getField("VESSEL_NAME") . "',
                    TYPE_OF_VESSEL ='" . $this->getField("TYPE_OF_VESSEL") . "',
                    FAXIMILE ='" . $this->getField("FAXIMILE") . "',
                    TYPE_OF_SERVICE ='" . $this->getField("TYPE_OF_SERVICE") . "',
                    TOTAL_PRICE ='" . $this->getField("TOTAL_PRICE") . "',
                    SCOPE_OF_WORK ='" . $this->getField("SCOPE_OF_WORK") . "'
                
                WHERE OFFER_ID = " . $this->getField("OFFER_ID") . "
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
				SET   FOTO = '" . $this->getField("FOTO") . "'
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

    function update_dokument()
    {
        $str = "UPDATE OFFER
                SET    
                    DOCUMENT_ID ='" . $this->getField("DOCUMENT_ID") . "'
       
        
        WHERE OFFER_ID= '" . $this->getField("OFFER_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete()
    {
        $str = "DELETE 
                FROM OFFER
                WHERE OFFER_ID = " . $this->getField("OFFER_ID") . "";

        $this->query = $str;
        return $this->execQuery($str);
    }

    function deleteRevisi()
    {
        $str = "DELETE 
                FROM OFFER_REVISI
                WHERE OFFER_REVISI_ID = " . $this->getField("OFFER_REVISI_ID") . "";

        $this->query = $str;
        return $this->execQuery($str);
    }

    function deleteOfferHpp()
    {
        $str = "DELETE 
                FROM OFFER
                WHERE HPP_PROJECT_ID = '" . $this->getField("HPP_PROJECT_ID") . "'";

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

    function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.OFFER_ID ASC")
    {
        $str = "SELECT A.OFFER_ID,(SELECT COUNT(1) FROM OFFER_REVISI X WHERE X.OFFER_ID=A.OFFER_ID) REV_VERSI,A.DOCUMENT_ID,A.DOCUMENT_PERSON,A.DESTINATION,A.DATE_OF_SERVICE,A.TYPE_OF_SERVICE,A.SCOPE_OF_WORK,A.TERM_AND_CONDITION,A.PAYMENT_METHOD,A.TOTAL_PRICE,A.PRICE_UNIT,A.TOTAL_PRICE_WORD,A.STATUS,A.REASON,A.NO_ORDER,A.DATE_OF_ORDER,A.COMPANY_NAME,A.ADDRESS,A.FAXIMILE,A.EMAIL,A.TELEPHONE,A.HP,A.VESSEL_NAME,A.TYPE_OF_VESSEL,A.CLASS_OF_VESSEL,A.MAKER,A.ISSUE_DATE,TO_CHAR(A.ISSUE_DATE,'Month DD, YYYY') ISSUE_DATE_FORMAT,A.PREPARED_BY,A.REVIEWED_BY,A.APPROVED_BY,A.ISSUE_PURPOSE,A.SUBJECT,A.GENERAL_SERVICE,A.GENERAL_SERVICE_DETAIL,A.PROPOSAL_VALIDATY,A.TECHICAL_SCOPE,A.TECHICAL_SUPPORT,A.COMMERCIAL_SUPPORT,A.REV_HISTORY,B.NAMA GENERAL_SERVICE_NAME,A.CLASS_ADDEND,A.STAND_BY_RATE,A.COMPANY_ID,A.VESSEL_DIMENSION_L,A.VESSEL_DIMENSION_B,A.VESSEL_DIMENSION_D, A.VESSEL_ID, A.PENANGGUNG_JAWAB_ID, C.NAMA PENANGGUNG_JAWAB,C.TTD_LINK, CASE WHEN A.STATUS = '0' THEN 'Pending' WHEN A.STATUS = '1' THEN 'Realisasi' ELSE 'Cancel' END STATUS_DESC,LAND_TRANSPORT,SO_DAYS,LUMPSUM_DAYS,PO_NAME,PO_DESCRIPTION,PO_PATH,MINIMUM_CHARGER,WORK_TIME,A.HPP_PROJECT_ID,A.CLASS_ADDEND2,A.MASTER_REASON_ID
                FROM OFFER A
                LEFT JOIN SERVICES B ON CAST(nullif(A.GENERAL_SERVICE, '') AS bigint) = B.SERVICES_ID
                LEFT JOIN PENANGGUNG_JAWAB C ON A.PENANGGUNG_JAWAB_ID=C.PENANGGUNG_JAWAB_ID
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParamsRevisi($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.OFFER_ID ASC")
    {
        $str = "SELECT A.OFFER_REVISI_ID,A.REV_VERSI,TO_CHAR(A.REV_DATE, 'DD-MM-YYYY HH24:MI:SS') REV_DATE,A.OFFER_ID,A.DOCUMENT_ID,A.DOCUMENT_PERSON,A.DESTINATION,A.DATE_OF_SERVICE,A.TYPE_OF_SERVICE,A.SCOPE_OF_WORK,A.TERM_AND_CONDITION,A.PAYMENT_METHOD,A.TOTAL_PRICE,A.PRICE_UNIT,A.TOTAL_PRICE_WORD,A.STATUS,A.REASON,A.NO_ORDER,A.DATE_OF_ORDER,A.COMPANY_NAME,A.ADDRESS,A.FAXIMILE,A.EMAIL,A.TELEPHONE,A.HP,A.VESSEL_NAME,A.TYPE_OF_VESSEL,A.CLASS_OF_VESSEL,A.MAKER,A.ISSUE_DATE,TO_CHAR(A.ISSUE_DATE,'Month DD, YYYY') ISSUE_DATE_FORMAT,A.PREPARED_BY,A.REVIEWED_BY,A.APPROVED_BY,A.ISSUE_PURPOSE,A.SUBJECT,A.GENERAL_SERVICE,A.GENERAL_SERVICE_DETAIL,A.PROPOSAL_VALIDATY,A.TECHICAL_SCOPE,A.TECHICAL_SUPPORT,A.COMMERCIAL_SUPPORT,A.REV_HISTORY,B.NAMA GENERAL_SERVICE_NAME,A.CLASS_ADDEND,A.STAND_BY_RATE,A.COMPANY_ID,A.VESSEL_DIMENSION_L,A.VESSEL_DIMENSION_B,A.VESSEL_DIMENSION_D, A.VESSEL_ID, A.PENANGGUNG_JAWAB_ID, C.NAMA PENANGGUNG_JAWAB,C.TTD_LINK, CASE WHEN A.STATUS = '0' THEN 'Pending' WHEN A.STATUS = '1' THEN 'Realisasi' ELSE 'Cancel' END STATUS_DESC,LAND_TRANSPORT,SO_DAYS,LUMPSUM_DAYS,PO_NAME,PO_DESCRIPTION,PO_PATH,MINIMUM_CHARGER,WORK_TIME
                FROM OFFER_REVISI A
                LEFT JOIN SERVICES B ON CAST(nullif(A.GENERAL_SERVICE, '') AS bigint) = B.SERVICES_ID
                LEFT JOIN PENANGGUNG_JAWAB C ON A.PENANGGUNG_JAWAB_ID=C.PENANGGUNG_JAWAB_ID
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParamsMonitoringRevisi($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.OFFER_ID ASC")
    {
        $str = "SELECT A.OFFER_REVISI_ID,A.REV_VERSI,TO_CHAR(A.REV_DATE, 'DD-MM-YYYY HH24:MI:SS') REV_DATE,A.OFFER_ID,A.DOCUMENT_ID,A.DOCUMENT_PERSON,A.DESTINATION,A.DATE_OF_SERVICE,A.TYPE_OF_SERVICE,A.SCOPE_OF_WORK,A.TERM_AND_CONDITION,A.PAYMENT_METHOD,A.TOTAL_PRICE,A.PRICE_UNIT,A.TOTAL_PRICE_WORD,A.STATUS,A.REASON,A.NO_ORDER,A.DATE_OF_ORDER,A.COMPANY_NAME,A.ADDRESS,A.FAXIMILE,A.EMAIL,A.TELEPHONE,A.HP,A.VESSEL_NAME,A.TYPE_OF_VESSEL,A.CLASS_OF_VESSEL,A.MAKER,A.ISSUE_DATE,TO_CHAR(A.ISSUE_DATE,'Month DD, YYYY') ISSUE_DATE_FORMAT,A.PREPARED_BY,A.REVIEWED_BY,A.APPROVED_BY,A.ISSUE_PURPOSE,A.SUBJECT,A.GENERAL_SERVICE,A.GENERAL_SERVICE_DETAIL,A.PROPOSAL_VALIDATY,A.TECHICAL_SCOPE,A.TECHICAL_SUPPORT,A.COMMERCIAL_SUPPORT,A.REV_HISTORY,B.NAMA GENERAL_SERVICE_NAME,A.CLASS_ADDEND,A.STAND_BY_RATE,A.COMPANY_ID,A.VESSEL_DIMENSION_L,A.VESSEL_DIMENSION_B,A.VESSEL_DIMENSION_D, A.VESSEL_ID, A.PENANGGUNG_JAWAB_ID, C.NAMA PENANGGUNG_JAWAB,C.TTD_LINK, CASE WHEN A.STATUS = '0' THEN 'Pending' WHEN A.STATUS = '1' THEN 'Realisasi' ELSE 'Cancel' END STATUS_DESC,LAND_TRANSPORT,SO_DAYS,LUMPSUM_DAYS,PO_NAME,PO_DESCRIPTION,PO_PATH,MINIMUM_CHARGER,WORK_TIME
                FROM OFFER_REVISI A
                LEFT JOIN SERVICES B ON CAST(nullif(A.GENERAL_SERVICE, '') AS bigint) = B.SERVICES_ID
                LEFT JOIN PENANGGUNG_JAWAB C ON A.PENANGGUNG_JAWAB_ID=C.PENANGGUNG_JAWAB_ID
                WHERE 1=1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParamsPrint($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.OFFER_ID ASC")
    {
        $str = "SELECT A.OFFER_ID, A.NO_ORDER, A.COMPANY_NAME, A.DOCUMENT_PERSON,  
                A.VESSEL_NAME, TO_CHAR(A.DATE_OF_SERVICE, 'DAY, MONTH DD YYYY') DATESERVICE,
                 A.TYPE_OF_SERVICE, A.DESTINATION, 
                CONCAT(
                ( CASE WHEN LEFT(A.TOTAL_PRICE, 3) ='RP.' THEN 'RP.'
                ELSE 'USD'
                END ),
                FORMAT(REPLACE(REPLACE(A.TOTAL_PRICE, 'USD ', ''), 'RP. ', ''), 0))
                TOTAL_PRICE, SUBSTRING(A.SCOPE_OF_WORK, 1, 50) SCOPEWORK,  
                CASE A.STATUS  
                WHEN 0 THEN 'PENDING'  
                WHEN 1 THEN 'REAL'  
                WHEN 2 THEN 'CANCEL'  
                END     
                FROM   OFFER A 
                WHERE  1 = 1 ";
        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement . " " . $order;
        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY OFFER_ID ASC")
    {
        $str = "SELECT  
                  A.OFFER_ID, A.NO_ORDER, A.EMAIL, A.DESTINATION, A.COMPANY_NAME, A.VESSEL_NAME, A.TYPE_OF_VESSEL, A.FAXIMILE, A.TYPE_OF_SERVICE, A.TOTAL_PRICE, A.SCOPE_OF_WORK,A.STATUS,A.REASON,A.DATE_OF_SERVICE,A.GENERAL_SERVICE_DETAIL,A.DOCUMENT_PERSON,A.CLASS_OF_VESSEL,B.COMPANY_ID,CASE WHEN A.STATUS = '0' THEN 'Pending' WHEN A.STATUS = '1' THEN 'Realisasi' WHEN A.STATUS = '2' THEN 'Cancel' ELSE '' END STATUS_DESC, C.NAMA GENERAL_SERVICE_NAME,A.HPP_PROJECT_ID,A.MASTER_REASON_ID
                FROM OFFER A
                LEFT JOIN COMPANY B ON CAST(B.COMPANY_ID AS VARCHAR) = CAST(A.COMPANY_ID AS VARCHAR)
                LEFT JOIN SERVICES C ON CAST(nullif(A.GENERAL_SERVICE, '') AS bigint) = C.SERVICES_ID
               
                WHERE 1=1
				";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        $str .= " " . $statement.' '. $order;
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
        $str = "SELECT COUNT(OFFER_ID) AS ROWCOUNT FROM OFFER A

		        WHERE OFFER_ID IS NOT NULL ";

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

    function getCountByParamsRevisi($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(OFFER_REVISI_ID) AS ROWCOUNT FROM OFFER_REVISI A

                WHERE OFFER_REVISI_ID IS NOT NULL ";

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
