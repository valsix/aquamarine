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

class InvoiceNew   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function InvoiceNew()
    {
        $this->Entity();
    }
        
            function insert()
            {
                $this->setField("INVOICE_NEW_ID", $this->getNextId("INVOICE_NEW_ID","INVOICE_NEW")); 

                $str = "INSERT INTO INVOICE_NEW (INVOICE_NEW_ID,COMPANY_ID,PO_NOMER,TAX_INVOICE,TAX_INVOICE_NOMINAL,INVOICE_DATE,INVOICE_DAY,PO_DATE,PO_DAY,REPORT_ID,TERN_CONDITION,RO_NUMBER,RO_DATE,PPN,PPN_PERCEN,ADV_PAYMENT,PPH,PPH_JENIS,PPH_CURRENCY,MANUAL_PPN,MANUAL_PPN_NOMINAL,STATUS_INVOICE,TYPE_PROJECT,DESKRIPSI,AMOUNT,CURRENCY,QUANTITY,ITEM,NOTE,NOMER,CODE_TAX,RO_CHECK,PAYMENT_DATE,DESKRIPSI_PAYMENT,CREATED_BY,CREATED_DATE)VALUES (
                '".$this->getField("INVOICE_NEW_ID")."',
          
                ".$this->getField("COMPANY_ID").",
                  '".$this->getField("PO_NOMER")."',
                '".$this->getField("TAX_INVOICE")."',
                '".$this->getField("TAX_INVOICE_NOMINAL")."',
                ".$this->getField("INVOICE_DATE").",
                '".$this->getField("INVOICE_DAY")."',
                ".$this->getField("PO_DATE").",
                '".$this->getField("PO_DAY")."',
                ".$this->getField("REPORT_ID").",
                '".$this->getField("TERN_CONDITION")."',
                '".$this->getField("RO_NUMBER")."',
                ".$this->getField("RO_DATE").",
                '".$this->getField("PPN")."',
                '".$this->getField("PPN_PERCEN")."',
                '".$this->getField("ADV_PAYMENT")."',
                '".$this->getField("PPH")."',
                '".$this->getField("PPH_JENIS")."',
                '".$this->getField("PPH_CURRENCY")."',
                '".$this->getField("MANUAL_PPN")."',
                '".$this->getField("MANUAL_PPN_NOMINAL")."',
                '".$this->getField("STATUS_INVOICE")."',
               
                '".$this->getField("TYPE_PROJECT")."',
                '".$this->getField("DESKRIPSI")."',
                '".$this->getField("AMOUNT")."',
                '".$this->getField("CURRENCY")."',
                '".$this->getField("QUANTITY")."',
                '".$this->getField("ITEM")."',
                '".$this->getField("NOTE")."',
                 '".$this->getField("NOMER")."',
                  '".$this->getField("CODE_TAX")."',
                   '".$this->getField("RO_CHECK")."',
                    ".$this->getField("PAYMENT_DATE").",
                     '".$this->getField("DESKRIPSI_PAYMENT")."',
                '".$this->USERID."',
               CURRENT_DATE
               
            )";

            $this->id = $this->getField("INVOICE_NEW_ID");
            $this->query= $str;
                // echo $str;exit();
            return $this->execQuery($str);
        }

        function update()
        {
            $str = "
            UPDATE INVOICE_NEW
            SET    
            INVOICE_NEW_ID ='".$this->getField("INVOICE_NEW_ID")."',
           
            COMPANY_ID =".$this->getField("COMPANY_ID").",
              PO_NOMER ='".$this->getField("PO_NOMER")."',
                 RO_CHECK ='".$this->getField("RO_CHECK")."',
               DESKRIPSI_PAYMENT ='".$this->getField("DESKRIPSI_PAYMENT")."',
                 PAYMENT_DATE =".$this->getField("PAYMENT_DATE").",
            TAX_INVOICE ='".$this->getField("TAX_INVOICE")."',
             NOMER ='".$this->getField("NOMER")."',
            TAX_INVOICE_NOMINAL ='".$this->getField("TAX_INVOICE_NOMINAL")."',
            INVOICE_DATE =".$this->getField("INVOICE_DATE").",
            INVOICE_DAY ='".$this->getField("INVOICE_DAY")."',
            PO_DATE =".$this->getField("PO_DATE").",
            PO_DAY ='".$this->getField("PO_DAY")."',
            REPORT_ID =".$this->getField("REPORT_ID").",
            TERN_CONDITION ='".$this->getField("TERN_CONDITION")."',
            RO_NUMBER ='".$this->getField("RO_NUMBER")."',
            RO_DATE =".$this->getField("RO_DATE").",
            PPN ='".$this->getField("PPN")."',
             CODE_TAX ='".$this->getField("CODE_TAX")."',
            PPN_PERCEN ='".$this->getField("PPN_PERCEN")."',
            ADV_PAYMENT ='".$this->getField("ADV_PAYMENT")."',
            PPH ='".$this->getField("PPH")."',
            PPH_JENIS ='".$this->getField("PPH_JENIS")."',
            PPH_CURRENCY ='".$this->getField("PPH_CURRENCY")."',
            MANUAL_PPN ='".$this->getField("MANUAL_PPN")."',
            MANUAL_PPN_NOMINAL ='".$this->getField("MANUAL_PPN_NOMINAL")."',
            STATUS_INVOICE ='".$this->getField("STATUS_INVOICE")."',
        
            TYPE_PROJECT ='".$this->getField("TYPE_PROJECT")."',
            DESKRIPSI ='".$this->getField("DESKRIPSI")."',
            AMOUNT ='".$this->getField("AMOUNT")."',
            CURRENCY ='".$this->getField("CURRENCY")."',
            QUANTITY ='".$this->getField("QUANTITY")."',
            ITEM ='".$this->getField("ITEM")."',
            NOTE ='".$this->getField("NOTE")."',
           
            UPDATED_BY ='".$this->USERID."',
            UPDATED_DATE =CURRENT_DATE
            WHERE INVOICE_NEW_ID= '".$this->getField("INVOICE_NEW_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }

         function insertFromHpp()
            {
                $this->setField("INVOICE_NEW_ID", $this->getNextId("INVOICE_NEW_ID","INVOICE_NEW")); 

                $str = "INSERT INTO INVOICE_NEW (INVOICE_NEW_ID,COMPANY_ID,HPP_PROJECT_ID,DARI,CREATED_BY,CREATED_DATE)VALUES (
                '".$this->getField("INVOICE_NEW_ID")."',
                '".$this->getField("COMPANY_ID")."',
                '".$this->getField("HPP_PROJECT_ID")."',
                 'HPP',
              
              
                '".$this->USERID."',
                CURRENT_DATE

            )";

            $this->id = $this->getField("INVOICE_NEW_ID");
            $this->query= $str;
                // echo $str;exit();
            return $this->execQuery($str);
        }

         function updateFromHpp()
        {
            $str = "
            UPDATE INVOICE_NEW
            SET    
            COMPANY_ID ='".$this->getField("COMPANY_ID")."',
            HPP_PROJECT_ID ='".$this->getField("HPP_PROJECT_ID")."',
          
        
            UPDATED_BY ='".$this->USERID."',
            UPDATED_DATE =CURRENT_DATE
            WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."' AND DARI='HPP'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }
         function updateLampiran()
        {
            $str = "
            UPDATE INVOICE_NEW
            SET    
            LAMPIRAN ='".$this->getField("LAMPIRAN")."'
          
          
            WHERE INVOICE_NEW_ID= '".$this->getField("INVOICE_NEW_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }

      function delete()
        {
            $str = "
            UPDATE INVOICE_NEW
            SET    

            STATUS_DELETE ='DELETE'

            WHERE INVOICE_NEW_ID= '".$this->getField("INVOICE_NEW_ID")."'";
            $this->query = $str;
          // echo $str;exit;
            return $this->execQuery($str);
        }

         function deleteFormHpp()
        {
            $str = "
            UPDATE INVOICE_NEW
            SET    

            STATUS_DELETE ='DELETE'

            WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'";
            $this->query = $str;
          // echo $str;exit;
            return $this->execQuery($str);
        }

        function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.INVOICE_NEW_ID ASC")
        {
            $str = "
            SELECT A.INVOICE_NEW_ID,A.HPP_PROJECT_ID,A.COMPANY_ID,A.TAX_INVOICE,A.TAX_INVOICE_NOMINAL,A.INVOICE_DATE,A.INVOICE_DAY,A.PO_DATE,A.PO_DAY,A.REPORT_ID,A.TERN_CONDITION,A.RO_NUMBER,A.RO_DATE,A.PPN,A.PPN_PERCEN,A.ADV_PAYMENT,A.PPH,A.PPH_JENIS,A.PPH_CURRENCY,A.MANUAL_PPN,A.MANUAL_PPN_NOMINAL,A.STATUS_INVOICE,A.LAMPIRAN,A.TYPE_PROJECT,A.DESKRIPSI,A.AMOUNT,A.CURRENCY,A.QUANTITY,A.ITEM,A.NOTE,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,C.NOMER  NOMER,A.PO_NOMER,A.CODE_TAX,A.RO_CHECK,A.PAYMENT_DATE,A.DESKRIPSI_PAYMENT,B.NAME COMPANY_NAME,B.CP1_NAME CONTACT_PERSON,A.DARI, C.NOMER NO_ORDER,C.NAMA_PROJECT,D.CODE CODE,D.NAMA NAMA_CODE_PROJECT,D.KETERANGAN NO_PO, 
            C.LOKASI AS LOCATION,B.ADDRESS,B.PHONE ,B.FAX,B.EMAIL
            FROM INVOICE_NEW A
             LEFT JOIN COMPANY B ON B.COMPANY_ID= A.COMPANY_ID
              LEFT JOIN PROJECT_HPP_NEW C ON C.PROJECT_HPP_NEW_ID = A.HPP_PROJECT_ID
            LEFT JOIN MASTER_PROJECT D ON D.MASTER_PROJECT_ID::VARCHAR = C.CODE
            WHERE 1=1  AND A.STATUS_DELETE IS NULL ";
            while(list($key,$val) = each($paramsArray))
            {
                $str .= " AND $key = '$val'";
            }

            $str .= $statement." ".$order;
            $this->query = $str;
            return $this->selectLimit($str,$limit,$from); 
        }

        function getCountByParamsMonitoring($paramsArray=array(), $statement="")
        {
            $str = "SELECT COUNT(1) AS ROWCOUNT FROM INVOICE_NEW A WHERE 1=1 AND A.STATUS_DELETE IS NULL ".$statement;
            while(list($key,$val)=each($paramsArray))
            {
                $str .= " AND $key =    '$val' ";
            }
            $this->query = $str;
            $this->select($str); 
            if($this->firstRow()) 
                return $this->getField("ROWCOUNT"); 
            else 
                return 0; 
        }    
    
}
