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

class InvoiceDetail    extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function InvoiceDetail()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("INVOICE_DETAIL_ID", $this->getNextId("INVOICE_DETAIL_ID","INVOICE_DETAIL")); 

        $str = "INSERT INTO INVOICE_DETAIL ( INVOICE_DETAIL_ID, INVOICE_ID, SERVICE_TYPE, SERVICE_DATE, LOCATION,        VESSEL, AMOUNT, CURRENCY, ADDITIONAL, DESCRIPTION, QUANTITY, QUANTITY_ITEM, TYPE_PROJECT, IS_ADDITIONAL)VALUES (
        '".$this->getField("INVOICE_DETAIL_ID")."',
        '".$this->getField("INVOICE_ID")."',
        '".$this->getField("SERVICE_TYPE")."',
        ".$this->getField("SERVICE_DATE").",
        '".$this->getField("LOCATION")."',
        '".$this->getField("VESSEL")."',
        ".$this->getField("AMOUNT").",
        '".$this->getField("CURRENCY")."',
        '".$this->getField("ADDITIONAL")."',
        '".$this->getField("DESCRIPTION")."',
        '".$this->getField("QUANTITY")."',
        '".$this->getField("QUANTITY_ITEM")."',
        '".$this->getField("TYPE_PROJECT")."',
        '".$this->getField("IS_ADDITIONAL")."'
        )";

    $this->id = $this->getField("INVOICE_DETAIL_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE INVOICE_DETAIL
        SET    
        INVOICE_DETAIL_ID ='".$this->getField("INVOICE_DETAIL_ID")."',
        INVOICE_ID ='".$this->getField("INVOICE_ID")."',
        SERVICE_TYPE ='".$this->getField("SERVICE_TYPE")."',
        SERVICE_DATE =".$this->getField("SERVICE_DATE").",
        LOCATION ='".$this->getField("LOCATION")."',
        VESSEL ='".$this->getField("VESSEL")."',
        AMOUNT =".$this->getField("AMOUNT").",
        CURRENCY ='".$this->getField("CURRENCY")."',
        ADDITIONAL ='".$this->getField("ADDITIONAL")."',
        DESCRIPTION ='".$this->getField("DESCRIPTION")."',
        QUANTITY ='".$this->getField("QUANTITY")."',
        QUANTITY_ITEM ='".$this->getField("QUANTITY_ITEM")."',
        TYPE_PROJECT ='".$this->getField("TYPE_PROJECT")."',
        IS_ADDITIONAL ='".$this->getField("IS_ADDITIONAL")."'
        WHERE INVOICE_DETAIL_ID= '".$this->getField("INVOICE_DETAIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }
    function updateRelasisai()
    {
        $str = "
        UPDATE INVOICE_DETAIL
        SET    
        
        AMOUNT =".$this->getField("AMOUNT").",
        CURRENCY ='".$this->getField("CURRENCY")."'
       
        WHERE INVOICE_ID= '".$this->getField("INVOICE_ID")."' AND SERVICE_TYPE= '".$this->getField("SERVICE_TYPE")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

     function updateTglStatus()
    {
        $str = "UPDATE INVOICE_DETAIL
                SET    
                
              
                TGL_STATUS_BAYAR =" . $this->getField("TGL_STATUS_BAYAR") . "
              
                WHERE INVOICE_DETAIL_ID= " . $this->getField("INVOICE_DETAIL_ID") . "";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

     function updateRemark()
    {
        $str = "UPDATE INVOICE_DETAIL
                SET    
                
              
                REMARK ='" . $this->getField("REMARK") . "'
              
                WHERE INVOICE_DETAIL_ID= " . $this->getField("INVOICE_DETAIL_ID") . "";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }


    function delete($statement= "")
    {
        $str = "DELETE FROM INVOICE_DETAIL
        WHERE INVOICE_DETAIL_ID= '".$this->getField("INVOICE_DETAIL_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function deleteParent($statement= "")
    {
        $str = "DELETE FROM INVOICE_DETAIL
        WHERE INVOICE_ID= '".$this->getField("INVOICE_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.INVOICE_DETAIL_ID ASC")
    {
        $str = "
        SELECT A.INVOICE_DETAIL_ID,A.INVOICE_ID,A.SERVICE_TYPE,A.SERVICE_DATE,A.LOCATION,A.VESSEL,A.CURRENCY,A.ADDITIONAL,DESCRIPTION, QUANTITY, QUANTITY || ' ' || COALESCE(QUANTITY_ITEM, '') AS QUANTITYITEM, QUANTITY_ITEM, TYPE_PROJECT, IS_ADDITIONAL,A.TGL_STATUS_BAYAR,A.AMOUNT  ,
           ( A.AMOUNT::NUMERIC * ( (CASE WHEN QUANTITY ='' THEN '1' ELSE QUANTITY END)::INT )) AS AMOUNT_NILAI,A.REMARK
        FROM INVOICE_DETAIL A
        WHERE 1=1 ";
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoringNew($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.INVOICE_DETAIL_ID ASC")
    {
        $str = "
        SELECT A.INVOICE_DETAIL_ID,A.INVOICE_ID,
        ( CASE WHEN  A.IS_ADDITIONAL='1' THEN A.ADDITIONAL ELSE A.SERVICE_TYPE END )
        SERVICE_TYPE,A.SERVICE_DATE,A.LOCATION,
         ( CASE WHEN  A.VESSEL='' AND A.IS_ADDITIONAL='1' THEN (SELECT CC.VESSEL FROM INVOICE_DETAIL CC WHERE A.INVOICE_ID = CC.INVOICE_ID AND CC.VESSEL !='' LIMIT 1  ) ELSE A.VESSEL END )
        VESSEL

        ,A.CURRENCY,A.ADDITIONAL,DESCRIPTION, QUANTITY, QUANTITY || ' ' || COALESCE(QUANTITY_ITEM, '') AS QUANTITYITEM, QUANTITY_ITEM, TYPE_PROJECT, IS_ADDITIONAL,A.TGL_STATUS_BAYAR,A.AMOUNT  ,
        ( A.AMOUNT::NUMERIC * ( (CASE WHEN QUANTITY ='' THEN '1' ELSE QUANTITY END)::INT )) AS AMOUNT_NILAI,A.REMARK
        FROM INVOICE_DETAIL A
        WHERE 1=1 ";
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM INVOICE_DETAIL A WHERE 1=1 ".$statement;
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
