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

class MasterLokasi    extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function MasterLokasi()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("MASTER_LOKASI_ID", $this->getNextId("MASTER_LOKASI_ID","MASTER_LOKASI")); 

        $str = "INSERT INTO MASTER_LOKASI ( MASTER_LOKASI_ID, NAMA, KETERANGAN, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("MASTER_LOKASI_ID")."',
        '".$this->getField("NAMA")."',
        '".$this->getField("KETERANGAN")."',
        '".$this->USERNAME."',
        CURRENT_DATE

    )";

    $this->id = $this->getField("MASTER_LOKASI_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
}

function update()
{
    $str = "
    UPDATE MASTER_LOKASI
    SET    
    MASTER_LOKASI_ID ='".$this->getField("MASTER_LOKASI_ID")."',
    NAMA ='".$this->getField("NAMA")."',
    KETERANGAN ='".$this->getField("KETERANGAN")."',
    UPDATED_BY ='".$this->USERNAME."',
    UPDATED_DATE = CURRENT_DATE
    WHERE MASTER_LOKASI_ID= '".$this->getField("MASTER_LOKASI_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}
function delete($statement= "")
{
    $str = "DELETE FROM MASTER_LOKASI
    WHERE MASTER_LOKASI_ID= '".$this->getField("MASTER_LOKASI_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}
function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.MASTER_LOKASI_ID ASC")
{
    $str = "
    SELECT A.MASTER_LOKASI_ID,A.NAMA,A.KETERANGAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
    FROM MASTER_LOKASI A
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
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM MASTER_LOKASI A WHERE 1=1 ".$statement;
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
