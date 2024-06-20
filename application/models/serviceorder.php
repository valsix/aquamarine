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

class ServiceOrder  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function ServiceOrder()
    {
        $this->Entity();
    }

    function insert()
    {
            $this->setField("SO_ID", $this->getNextId("SO_ID","SERVICE_ORDER")); 

            $str = "INSERT INTO SERVICE_ORDER (SO_ID, PROJECT_NAME, NO_ORDER, COMPANY_NAME, VESSEL_NAME, VESSEL_TYPE, VESSEL_CLASS, SURVEYOR, DESTINATION, SERVICE, DATE_OF_START, DATE_OF_FINISH,        TRANSPORT, EQUIPMENT, OBLIGATION, DATE_OF_SERVICE, DATE_OWR, PIC_EQUIP,        CONTACT_PERSON,DOC_LAMPIRAN,PATH_LAMPIRAN,FINANCE, PENANGGUNG_JAWAB_ID, COMPANY_ID, VESSEL_ID
            )
            VALUES 
            (
            '".$this->getField("SO_ID")."',
            '".$this->getField("PROJECT_NAME")."',
            '".$this->getField("NO_ORDER")."',
            '".$this->getField("COMPANY_NAME")."',
            '".$this->getField("VESSEL_NAME")."',
            '".$this->getField("VESSEL_TYPE")."',
            '".$this->getField("VESSEL_CLASS")."',
            '".$this->getField("SURVEYOR")."',
            '".$this->getField("DESTINATION")."',
            '".$this->getField("SERVICE")."',
            ".$this->getField("DATE_OF_START").",
            ".$this->getField("DATE_OF_FINISH").",
            '".$this->getField("TRANSPORT")."',
            '".$this->getField("EQUIPMENT")."',
            '".$this->getField("OBLIGATION")."',
            ".$this->getField("DATE_OF_SERVICE").",
            ".$this->getField("DATE_OWR").",
            '".$this->getField("PIC_EQUIP")."',
            '".$this->getField("CONTACT_PERSON")."',
            '".$this->getField("DOC_LAMPIRAN")."',
            '".$this->getField("PATH_LAMPIRAN")."',
            '".$this->getField("FINANCE")."',
            ".$this->getField("PENANGGUNG_JAWAB_ID").",
            ".$this->getField("COMPANY_ID").",
            ".$this->getField("VESSEL_ID")."
        )";

        $this->id = $this->getField("SO_ID");
        $this->query= $str;
                // echo $str;exit();
        return $this->execQuery($str);
    }

    

    function update()
    {
        $str = "
        UPDATE SERVICE_ORDER
        SET    
        SO_ID ='".$this->getField("SO_ID")."',
        PROJECT_NAME ='".$this->getField("PROJECT_NAME")."',
        NO_ORDER ='".$this->getField("NO_ORDER")."',
        COMPANY_NAME ='".$this->getField("COMPANY_NAME")."',
        VESSEL_NAME ='".$this->getField("VESSEL_NAME")."',
        VESSEL_TYPE ='".$this->getField("VESSEL_TYPE")."',
        VESSEL_CLASS ='".$this->getField("VESSEL_CLASS")."',
        SURVEYOR ='".$this->getField("SURVEYOR")."',
        DESTINATION ='".$this->getField("DESTINATION")."',
        SERVICE ='".$this->getField("SERVICE")."',
        DATE_OF_START =".$this->getField("DATE_OF_START").",
        DATE_OF_FINISH =".$this->getField("DATE_OF_FINISH").",
        TRANSPORT ='".$this->getField("TRANSPORT")."',
        EQUIPMENT ='".$this->getField("EQUIPMENT")."',
        OBLIGATION ='".$this->getField("OBLIGATION")."',
        DATE_OF_SERVICE =".$this->getField("DATE_OF_SERVICE").",
        DATE_OWR =".$this->getField("DATE_OWR").",
        PIC_EQUIP ='".$this->getField("PIC_EQUIP")."',
        CONTACT_PERSON ='".$this->getField("CONTACT_PERSON")."',
        DOC_LAMPIRAN ='".$this->getField("DOC_LAMPIRAN")."',
        PATH_LAMPIRAN ='".$this->getField("PATH_LAMPIRAN")."',
        FINANCE ='".$this->getField("FINANCE")."',  
        PENANGGUNG_JAWAB_ID =".$this->getField("PENANGGUNG_JAWAB_ID").",
        COMPANY_ID =".$this->getField("COMPANY_ID").",
        VESSEL_ID =".$this->getField("VESSEL_ID")."  
        WHERE SO_ID= '".$this->getField("SO_ID")."'";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }

     function updateFlagService()
    {
        $str = "
        UPDATE SERVICE_ORDER
        SET    
        FLAG_ITEM ='".$this->getField("FLAG_ITEM")."'
        
        WHERE SO_ID= '".$this->getField("SO_ID")."'";
        $this->query = $str;
              // echo $str;exit;
        return $this->execQuery($str);
    }
      function update_owr_suryevor()
    {
        $str = "UPDATE SERVICE_ORDER
                SET

                OWR_SURYEVOR ='" . $this->getField("OWR_SURYEVOR") . "'
             
        
            WHERE OFFER_ID = " . $this->getField("OFFER_ID") . "
             ";
        $this->query = $str;
        // echo $str;
        // exit;
        return $this->execQuery($str);
    }

    function insert_no_pengiriman()
    {
            $this->setField("SO_ID", $this->getNextId("SO_ID","SERVICE_ORDER")); 

            $str = "INSERT INTO SERVICE_ORDER (SO_ID, NO_DELIVERY)VALUES (
            '".$this->getField("SO_ID")."',
            '".$this->getField("NO_DELIVERY")."'
          
        )";

        $this->id = $this->getField("SO_ID");
        $this->query= $str;
                // echo $str;exit();
        return $this->execQuery($str);
    }

    function update_no_pengiriman()
    {
        $str = "
        UPDATE SERVICE_ORDER
        SET    

        NO_DELIVERY ='".$this->getField("NO_DELIVERY")."',
         PATH ='".$this->getField("PATH")."'  
        WHERE SO_ID= '".$this->getField("SO_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function updatePath()
    {
        $str = "
        UPDATE SERVICE_ORDER
        SET    

        PATH_LAMPIRAN ='".$this->getField("PATH_LAMPIRAN")."'

        WHERE SO_ID= '".$this->getField("SO_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM SERVICE_ORDER
        WHERE SO_ID= '".$this->getField("SO_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SO_ID ASC")
    {
        $str = "
        SELECT A.SO_ID,A.PROJECT_NAME,A.NO_ORDER,A.COMPANY_NAME,A.VESSEL_NAME,A.VESSEL_TYPE,A.SURVEYOR,A.DESTINATION,A.SERVICE,A.DATE_OF_START,A.DATE_OF_FINISH,A.TRANSPORT,A.EQUIPMENT,A.OBLIGATION,A.DATE_OF_SERVICE,A.DATE_OWR,A.PIC_EQUIP,A.CONTACT_PERSON,A.NO_DELIVERY,A.DOC_LAMPIRAN,A.PATH_LAMPIRAN,A.FINANCE,A.OFFER_ID
        FROM SERVICE_ORDER A
        WHERE 1=1 ";
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsEquiment($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SO_ID ASC")
    {
        $str = "
        SELECT A.SO_ID, A.NO_ORDER, A.NO_DELIVERY, A.COMPANY_NAME ,  A.VESSEL_NAME , B.TYPE_OF_VESSEL VESSEL_TYPE, B.CLASS_OF_VESSEL VESSEL_CLASS, (SELECT NAMA FROM SERVICES X WHERE X.SERVICES_ID::VARCHAR = B.GENERAL_SERVICE) TYPE_OF_SERVICE, A.SURVEYOR , A.SERVICE , A.DESTINATION ,TO_CHAR(A.DATE_OF_SERVICE, 'DAY,MONTH DD YYYY') AS DATE_OF_SERVICE, TO_CHAR(A.DATE_OF_START, 'DAY,MONTH DD YYYY') DATE_OF_START, TO_CHAR(A.DATE_OF_FINISH, 'DAY,MONTH DD YYYY') DATE_OF_FINISH,A.FLAG_ITEM,
        PIC_EQUIP 
        FROM   SERVICE_ORDER A
        LEFT JOIN OFFER B ON A.OFFER_ID = B.OFFER_ID
        WHERE 1=1 ";
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoringEquiment($paramsArray=array(), $statement="")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM SERVICE_ORDER A  
         LEFT JOIN OFFER B ON A.OFFER_ID = B.OFFER_ID  
         WHERE 1=1 ".$statement;
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
     function getCountByParamsMonitoring($paramsArray=array(), $statement="")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM SERVICE_ORDER A WHERE 1=1 ".$statement;
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
