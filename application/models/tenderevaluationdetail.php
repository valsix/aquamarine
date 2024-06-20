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

class TenderEvaluationDetail     extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function TenderEvaluationDetail()
    {
        $this->Entity();
    }
    function insert()
    {
        $this->setField("TENDER_EVALUATION_DETAIL_ID", $this->getNextId("TENDER_EVALUATION_DETAIL_ID","TENDER_EVALUATION_DETAIL")); 

        $str = "INSERT INTO TENDER_EVALUATION_DETAIL (TENDER_EVALUATION_DETAIL_ID, TENDER_EVALUTATION_ID, MASTER_TENDER_MENUS_ID,NILAI, CREATED_BY,        CREATED_DATE)VALUES (
        '".$this->getField("TENDER_EVALUATION_DETAIL_ID")."',
        '".$this->getField("TENDER_EVALUTATION_ID")."',
        '".$this->getField("MASTER_TENDER_MENUS_ID")."',
        ".$this->getField("NILAI").",
        '".$this->USERNAME."',
        CURRENT_DATE

    )";

    $this->id = $this->getField("TENDER_EVALUATION_DETAIL_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
}

function update()
{
    $str = "
    UPDATE TENDER_EVALUATION_DETAIL
    SET    
    
    TENDER_EVALUTATION_ID ='".$this->getField("TENDER_EVALUTATION_ID")."',
    NILAI =".$this->getField("NILAI").",
    
    UPDATED_BY ='".$this->USERNAME."',
    UPDATED_DATE =CURRENT_DATE
    WHERE TENDER_EVALUTATION_ID= '".$this->getField("TENDER_EVALUTATION_ID")."' AND MASTER_TENDER_MENUS_ID='".$this->getField("MASTER_TENDER_MENUS_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}
function delete($statement= "")
{
    $str = "DELETE FROM TENDER_EVALUATION_DETAIL
    WHERE TENDER_EVALUATION_DETAIL_ID= '".$this->getField("TENDER_EVALUATION_DETAIL_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}

function deleteParent($statement= "")
{
    $str = "DELETE FROM TENDER_EVALUATION_DETAIL
    WHERE TENDER_EVALUTATION_ID= '".$this->getField("TENDER_EVALUTATION_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}
function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.TENDER_EVALUATION_DETAIL_ID ASC")
{
    $str = "
    SELECT A.TENDER_EVALUATION_DETAIL_ID,A.TENDER_EVALUTATION_ID,A.NILAI,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.MASTER_TENDER_MENUS_ID,B.COLOR,B.COLOR2
    FROM TENDER_EVALUATION_DETAIL A
    LEFT JOIN MASTER_TENER_MENUS B ON B.MASTER_TENDER_MENUS_ID = A.MASTER_TENDER_MENUS_ID
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
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM TENDER_EVALUATION_DETAIL A WHERE 1=1 ".$statement;
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
