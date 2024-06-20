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

class SoTemplateEquip  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function SoTemplateEquip()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("SO_TEMPLATE_EQUIP_ID", $this->getNextId("SO_TEMPLATE_EQUIP_ID","SO_TEMPLATE_EQUIP")); 

        $str = "INSERT INTO SO_TEMPLATE_EQUIP (SO_TEMPLATE_EQUIP_ID, SO_TEMPLATE_ID, EQUIP_ID, OUT_CONDITION, IN_CONDITION, REMARK)VALUES (
        '".$this->getField("SO_TEMPLATE_EQUIP_ID")."',
        '".$this->getField("SO_TEMPLATE_ID")."',
        '".$this->getField("EQUIP_ID")."',
        '".$this->getField("OUT_CONDITION")."',
        '".$this->getField("IN_CONDITION")."',
        '".$this->getField("REMARK")."'
    )";

    $this->id = $this->getField("SO_TEMPLATE_EQUIP_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE SO_TEMPLATE_EQUIP
        SET    
        SO_TEMPLATE_EQUIP_ID ='".$this->getField("SO_TEMPLATE_EQUIP_ID")."',
        SO_TEMPLATE_ID ='".$this->getField("SO_TEMPLATE_ID")."',
        EQUIP_ID ='".$this->getField("EQUIP_ID")."',
        OUT_CONDITION ='".$this->getField("OUT_CONDITION")."',
        IN_CONDITION ='".$this->getField("IN_CONDITION")."',
        REMARK ='".$this->getField("REMARK")."'
        WHERE SO_TEMPLATE_EQUIP_ID= '".$this->getField("SO_TEMPLATE_EQUIP_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM SO_TEMPLATE_EQUIP
        WHERE SO_TEMPLATE_EQUIP_ID= '".$this->getField("SO_TEMPLATE_EQUIP_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function deleteParent($statement= "")
    {
        $str = "DELETE FROM SO_TEMPLATE_EQUIP
        WHERE SO_TEMPLATE_ID= '".$this->getField("SO_TEMPLATE_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SO_TEMPLATE_EQUIP_ID ASC")
    {
        $str = "
        SELECT A.SO_TEMPLATE_EQUIP_ID,A.SO_TEMPLATE_ID,A.EQUIP_ID,A.EQUIP_QTY,A.EQUIP_ITEM,A.OUT_CONDITION,A.IN_CONDITION,A.REMARK,A.IS_BACK,A.IS_POST
        FROM SO_TEMPLATE_EQUIP A
        WHERE 1=1 ";
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoringEquips($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SO_TEMPLATE_EQUIP_ID ASC")
    {
        $str = "
        SELECT A.SO_TEMPLATE_EQUIP_ID,A.SO_TEMPLATE_ID, A.EQUIP_ID, B.PIC_PATH,
        CASE B.EQUIP_CONDITION 
            WHEN 'G' THEN 'Good' 
            WHEN 'B' THEN 'Broken' 
            WHEN 'M' THEN 'Missing' 
            WHEN 'R' THEN 'Repair' 
            ELSE B.EQUIP_CONDITION 
        END EQUIP_CONDITION,
       CASE B.EQUIP_CONDITION 
            WHEN 'G' THEN 'Good' 
            WHEN 'B' THEN 'Broken' 
            WHEN 'M' THEN 'Missing' 
            WHEN 'R' THEN 'Repair' 
            ELSE B.EQUIP_CONDITION 
        END OUT_CONDITION,A.IN_CONDITION,C.EC_NAME,
        B.EQUIP_NAME, B.EQUIP_ITEM, B.EQUIP_SPEC, 
       (CASE WHEN ( A.REMARK = '' OR A.REMARK IS NULL ) THEN
        B.SERIAL_NUMBER  ELSE A.REMARK
    END ) REMARK, A.EQUIP_QTY AS QTY, A.EQUIP_ITEM AS ITEM,
        B.SERIAL_NUMBER
        FROM SO_TEMPLATE_EQUIP A, EQUIPMENT_LIST B
        LEFT JOIN EQUIP_CATEGORY C ON C.EC_ID = B.EC_ID

        WHERE A.EQUIP_ID = B.EQUIP_ID ";
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoringEquips($paramsArray=array(), $statement="")
    {
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM SO_TEMPLATE_EQUIP A, EQUIPMENT_LIST B
         LEFT JOIN EQUIP_CATEGORY C ON C.EC_ID = B.EC_ID
        WHERE A.EQUIP_ID = B.EQUIP_ID
        AND 1=1 ".$statement;
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM SO_TEMPLATE_EQUIP A WHERE 1=1 ".$statement;
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
