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

class SoEquip  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function SoEquip()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("SO_EQUIP_ID", $this->getNextId("SO_EQUIP_ID","SO_EQUIP")); 

        $str = "INSERT INTO SO_EQUIP (SO_EQUIP_ID, SO_ID, EQUIP_ID, EQUIP_QTY, EQUIP_ITEM, OUT_CONDITION,        IN_CONDITION, REMARK, IS_BACK, IS_POST)VALUES (
        '".$this->getField("SO_EQUIP_ID")."',
        '".$this->getField("SO_ID")."',
        '".$this->getField("EQUIP_ID")."',
        '".$this->getField("EQUIP_QTY")."',
        '".$this->getField("EQUIP_ITEM")."',
        '".$this->getField("OUT_CONDITION")."',
        '".$this->getField("IN_CONDITION")."',
        '".$this->getField("REMARK")."',
        '".$this->getField("IS_BACK")."',
        '".$this->getField("IS_POST")."' 
    )";

    $this->id = $this->getField("SO_EQUIP_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update_flag(){
        $str = "
        UPDATE SO_EQUIP
        SET  
        FLAG =NULL  
        WHERE SO_ID= '".$this->getField("SO_ID")."'";
        $this->query = $str;
        return $this->execQuery($str);
    }
      function update_flag2(){
        $str = "
        UPDATE SO_EQUIP
        SET  
        FLAG ='".$this->getField("FLAG")."'
        WHERE SO_EQUIP_ID= '".$this->getField("SO_EQUIP_ID")."'";
        $this->query = $str;
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE SO_EQUIP
        SET    
        SO_EQUIP_ID ='".$this->getField("SO_EQUIP_ID")."',
        SO_ID ='".$this->getField("SO_ID")."',
        EQUIP_ID ='".$this->getField("EQUIP_ID")."',
        EQUIP_QTY ='".$this->getField("EQUIP_QTY")."',
        EQUIP_ITEM ='".$this->getField("EQUIP_ITEM")."',
        OUT_CONDITION ='".$this->getField("OUT_CONDITION")."',
        IN_CONDITION ='".$this->getField("IN_CONDITION")."',
        REMARK ='".$this->getField("REMARK")."',
        IS_BACK ='".$this->getField("IS_BACK")."',
        IS_POST ='".$this->getField("IS_POST")."' 
        WHERE SO_EQUIP_ID= '".$this->getField("SO_EQUIP_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

     function delete_from_flag($statement= "")
    {
        $str = " DELETE FROM SO_EQUIP
        WHERE FLAG= 'BARU'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM SO_EQUIP
        WHERE SO_EQUIP_ID= '".$this->getField("SO_EQUIP_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SO_EQUIP_ID ASC")
    {
        $str = "
        SELECT A.SO_EQUIP_ID,A.SO_ID,A.EQUIP_ID,A.EQUIP_QTY,A.EQUIP_ITEM,A.OUT_CONDITION,A.IN_CONDITION,A.REMARK,A.IS_BACK,A.IS_POST
        FROM SO_EQUIP A
        WHERE 1=1 ";
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoringEquips($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SO_EQUIP_ID ASC")
    {
        $str = "
        SELECT A.SO_EQUIP_ID,A.SO_ID, A.EQUIP_ID, B.PIC_PATH, B.EQUIP_CONDITION,A.OUT_CONDITION,A.IN_CONDITION,C.EC_NAME,
        B.EQUIP_NAME, B.EQUIP_ITEM, B.EQUIP_SPEC, A.REMARK,  A.EQUIP_QTY AS QTY, A.EQUIP_ITEM AS ITEM,
        B.SERIAL_NUMBER,B.BARCODE,
        
    ( SELECT COUNT(*) FROM so_equip_pengembalian DD WHERE DD.so_id = A.so_id AND DD.equip_id= A.equip_id and dd.FLAG='1' ) kembali
        FROM SO_EQUIP A, EQUIPMENT_LIST B
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM SO_EQUIP A, EQUIPMENT_LIST B
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM SO_EQUIP A WHERE 1=1 ".$statement;
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
