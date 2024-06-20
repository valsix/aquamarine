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

class TenderEvaluation      extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function TenderEvaluation()
    {
        $this->Entity();
    }
    
    function insert()
    {
        $this->setField("TENDER_EVALUATION_ID", $this->getNextId("TENDER_EVALUATION_ID","TENDER_EVALUATION")); 

        $str = "INSERT INTO TENDER_EVALUATION (TENDER_EVALUATION_ID, MASTER_TENDER_PERIODE_ID, INDEX, MASTER_PSC_ID,        TITLE, TENDER_NO, CLOSING, OPENING, STATUS, OWNER, BID_VALUE,        TKDN, BID_BOUDS, BID_VALIDATY, NOTES,CUR_BID,CUR_OWNER, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("TENDER_EVALUATION_ID")."',
        '".$this->getField("MASTER_TENDER_PERIODE_ID")."',
        '".$this->getField("INDEX")."',
        '".$this->getField("MASTER_PSC_ID")."',
        '".$this->getField("TITLE")."',
        '".$this->getField("TENDER_NO")."',
        ".$this->getField("CLOSING").",
        ".$this->getField("OPENING").",
        '".$this->getField("STATUS")."',
        '".$this->getField("OWNER")."',
        '".$this->getField("BID_VALUE")."',
        '".$this->getField("TKDN")."',
        '".$this->getField("BID_BOUDS")."',
        '".$this->getField("BID_VALIDATY")."',
        '".$this->getField("NOTES")."',
        '".$this->getField("CUR_BID")."',
        '".$this->getField("CUR_OWNER")."',
        '".$this->USERNAME."',
         CURRENT_DATE
        
    )";

    $this->id = $this->getField("TENDER_EVALUATION_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
}

function update()
{
    $str = "
    UPDATE TENDER_EVALUATION
    SET    
    TENDER_EVALUATION_ID ='".$this->getField("TENDER_EVALUATION_ID")."',
    MASTER_TENDER_PERIODE_ID ='".$this->getField("MASTER_TENDER_PERIODE_ID")."',
    INDEX ='".$this->getField("INDEX")."',
    MASTER_PSC_ID ='".$this->getField("MASTER_PSC_ID")."',
    TITLE ='".$this->getField("TITLE")."',
    TENDER_NO ='".$this->getField("TENDER_NO")."',
    CLOSING =".$this->getField("CLOSING").",
    OPENING =".$this->getField("OPENING").",
    STATUS ='".$this->getField("STATUS")."',
    OWNER ='".$this->getField("OWNER")."',
    CUR_BID ='".$this->getField("CUR_BID")."',
    CUR_OWNER ='".$this->getField("CUR_OWNER")."',
    BID_VALUE ='".$this->getField("BID_VALUE")."',
    TKDN ='".$this->getField("TKDN")."',
    BID_BOUDS ='".$this->getField("BID_BOUDS")."',
    BID_VALIDATY ='".$this->getField("BID_VALIDATY")."',
    NOTES ='".$this->getField("NOTES")."',
    
    UPDATED_BY ='".$this->USERNAME."',
    UPDATED_DATE =CURRENT_DATE
    WHERE TENDER_EVALUATION_ID= '".$this->getField("TENDER_EVALUATION_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}
function delete($statement= "")
{
    $str = "DELETE FROM TENDER_EVALUATION
    WHERE TENDER_EVALUATION_ID= '".$this->getField("TENDER_EVALUATION_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}
function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.TENDER_EVALUATION_ID ASC")
{
    $str = "
    SELECT A.TENDER_EVALUATION_ID,A.MASTER_TENDER_PERIODE_ID,A.INDEX,A.MASTER_PSC_ID,A.TITLE,A.TENDER_NO,A.CLOSING,A.OPENING,A.STATUS,A.OWNER,A.BID_VALUE,A.TKDN,A.BID_BOUDS,A.BID_VALIDATY,A.NOTES,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,B.NAMA NAMA_PSC,A.CUR_BID,A.CUR_OWNER, ( CASE WHEN A.UPDATED_DATE IS NULL THEN  A.CREATED_DATE ELSE A.UPDATED_DATE END) LAST_UPDATE

    FROM TENDER_EVALUATION A
    LEFT JOIN MASTER_PSC B ON B.MASTER_PSC_ID = A.MASTER_PSC_ID 
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
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM TENDER_EVALUATION A WHERE 1=1 ".$statement;
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
