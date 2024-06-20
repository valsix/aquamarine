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

class PmsEquipDetil   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function PmsEquipDetil()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("PMS_DETIL_ID", $this->getNextId("PMS_DETIL_ID","PMS_EQUIP_DETIL")); 

        $str = "
            INSERT INTO PMS_EQUIP_DETIL(PMS_DETIL_ID, PMS_ID, NAME, TIME_TEST, DATE_TEST, DATE_NEXT_TEST, CONDITION, COMPENENT_PERSON, REMARKS, CERTIFICATE_NUMBER, MANUFACTURE, MODEL_NUMBER, SERIAL_NUMBER, CREATED_BY, CREATED_DATE)
            VALUES (
                '".$this->getField("PMS_DETIL_ID")."',
                '".$this->getField("PMS_ID")."',
                '".$this->getField("NAME")."',
                '".$this->getField("TIME_TEST")."',
                ".$this->getField("DATE_TEST").",
                ".$this->getField("DATE_NEXT_TEST").",
                '".$this->getField("CONDITION")."',
                '".$this->getField("COMPENENT_PERSON")."',
                '".$this->getField("REMARKS")."',
                '".$this->getField("CERTIFICATE_NUMBER")."',
                '".$this->getField("MANUFACTURE")."',
                '".$this->getField("MODEL_NUMBER")."',
                '".$this->getField("SERIAL_NUMBER")."',
                '".$this->getField("CREATED_BY")."',
                CURRENT_DATE
        )";

        $this->id = $this->getField("PMS_DETIL_ID");
        $this->query= $str;
            // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE PMS_EQUIP_DETIL
        SET    
            PMS_ID      ='".$this->getField("PMS_ID")."',
            NAME        ='".$this->getField("NAME")."',
            TIME_TEST   ='".$this->getField("TIME_TEST")."',
            DATE_TEST   =".$this->getField("DATE_TEST").",
            DATE_NEXT_TEST =".$this->getField("DATE_NEXT_TEST").",
            CONDITION   ='".$this->getField("CONDITION")."',
            COMPENENT_PERSON ='".$this->getField("COMPENENT_PERSON")."',
            REMARKS     ='".$this->getField("REMARKS")."',
            CERTIFICATE_NUMBER ='".$this->getField("CERTIFICATE_NUMBER")."',
            MANUFACTURE ='".$this->getField("MANUFACTURE")."',
            MODEL_NUMBER ='".$this->getField("MODEL_NUMBER")."',
            SERIAL_NUMBER ='".$this->getField("SERIAL_NUMBER")."' ,
            UPDATED_BY  ='".$this->getField("UPDATED_BY")."',
            UPDATED_DATE = CURRENT_DATE 
        WHERE PMS_DETIL_ID= '".$this->getField("PMS_DETIL_ID")."'";

        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function update_path(){
        $str = "
        UPDATE PMS_EQUIP_DETIL
        SET    
        PIC_PATH ='".$this->getField("PIC_PATH")."'
      
        WHERE PMS_DETIL_ID= '".$this->getField("PMS_DETIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);

    }

    function updateImage(){
        $str = "
        UPDATE PMS_EQUIP_DETIL
        SET    
        LINK_FILE ='".$this->getField("LINK_FILE")."'
        WHERE PMS_DETIL_ID= '".$this->getField("PMS_DETIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);

    }
    function update_pathS(){
        $str = "
        UPDATE PMS_EQUIP_DETIL
        SET    
        PATH ='".$this->getField("PATH")."'
        WHERE PMS_DETIL_ID= '".$this->getField("PMS_DETIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);

    }

    function delete($statement= "")
    {
        $str = "DELETE FROM PMS_EQUIP_DETIL
        WHERE PMS_DETIL_ID= '".$this->getField("PMS_DETIL_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

     function deleteParent($statement= "")
    {
        $str = "DELETE FROM PMS_EQUIP_DETIL
        WHERE PMS_ID= '".$this->getField("PMS_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.DATE_TEST DESC")
    {
        $str = "
        SELECT 
            PMS_DETIL_ID, PMS_ID, NAME, TIME_TEST, TO_CHAR(DATE_TEST,'DD-MM-YYYY') DATE_TEST, 
            TO_CHAR(DATE_NEXT_TEST,'DD-MM-YYYY') DATE_NEXT_TEST, CONDITION, 
            COMPENENT_PERSON, REMARKS, CERTIFICATE_NUMBER, MANUFACTURE, 
            MODEL_NUMBER, SERIAL_NUMBER, LINK_FILE,A.PATH,
            CASE WHEN DATE_NEXT_TEST < CURRENT_DATE THEN 'red' ELSE '' END STATUS
        FROM PMS_EQUIP_DETIL A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM PMS_EQUIP_DETIL A WHERE 1=1 ".$statement;
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

    function getCountByParamsTotalEquipDetail($paramsArray=array(), $statement="")
        {
            $str = " SELECT COUNT(*) AS ROWCOUNT FROM PMS_EQUIP_DETIL WHERE (DATE_NEXTCAL < NOW() OR (NOW() BETWEEN (DATE_NEXTCAL + INTERVAL '14 DAY') AND (DATE_NEXTCAL + INTERVAL '1 DAY'))); ".$statement;
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

    function getCountByParamsExpired($paramsArray=array(), $statement="")
        {
            $str = " SELECT COUNT(*) AS ROWCOUNT FROM PMS_EQUIP_DETIL WHERE DATE_NEXT_TEST < CURRENT_DATE ".$statement;
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
