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

class CostRequestDetail   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function CostRequestDetail()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("COST_REQUEST_DETAIL_ID", $this->getNextId("COST_REQUEST_DETAIL_ID","COST_REQUEST_DETAIL")); 

        $str = "
            INSERT INTO COST_REQUEST_DETAIL (
                COST_REQUEST_DETAIL_ID, COST_REQUEST_ID, 
                KETERANGAN, COST_CODE, COST_CODE_CATEGORI, TANGGAL, EVIDANCE, AMOUNT, 
                PROJECT, PAID_TO, CREATED_BY, CREATED_DATE
            )VALUES (
            '".$this->getField("COST_REQUEST_DETAIL_ID")."',
            '".$this->getField("COST_REQUEST_ID")."',
            '".$this->getField("KETERANGAN")."',
            '".$this->getField("COST_CODE")."',
            '".$this->getField("COST_CODE_CATEGORI")."',
            ".$this->getField("TANGGAL").",
            '".$this->getField("EVIDANCE")."',
            '".$this->getField("AMOUNT")."',
            '".$this->getField("PROJECT")."',
            '".$this->getField("PAID_TO")."',
            '".$this->USER_LOGIN_ID."',
           now()
    )";

    $this->id = $this->getField("COST_REQUEST_DETAIL_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE COST_REQUEST_DETAIL
        SET    
        COST_REQUEST_DETAIL_ID ='".$this->getField("COST_REQUEST_DETAIL_ID")."',
        COST_REQUEST_ID ='".$this->getField("COST_REQUEST_ID")."',
        KETERANGAN ='".$this->getField("KETERANGAN")."',
        COST_CODE ='".$this->getField("COST_CODE")."',
        COST_CODE_CATEGORI ='".$this->getField("COST_CODE_CATEGORI")."',
        TANGGAL =".$this->getField("TANGGAL").",
        EVIDANCE ='".$this->getField("EVIDANCE")."',
        AMOUNT ='".$this->getField("AMOUNT")."',
        PROJECT ='".$this->getField("PROJECT")."',
        PAID_TO ='".$this->getField("PAID_TO")."',
        UPDATED_BY ='".$this->USER_LOGIN_ID."',
        UPDATED_DATE =now()
        WHERE COST_REQUEST_DETAIL_ID= '".$this->getField("COST_REQUEST_DETAIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM COST_REQUEST_DETAIL
        WHERE COST_REQUEST_DETAIL_ID= '".$this->getField("COST_REQUEST_DETAIL_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

     function deleteParent($statement= "")
    {
        $str = "DELETE FROM COST_REQUEST_DETAIL
        WHERE COST_REQUEST_ID= '".$this->getField("COST_REQUEST_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.COST_REQUEST_DETAIL_ID ASC")
    {
        $str = "
        SELECT A.COST_REQUEST_DETAIL_ID,A.COST_REQUEST_ID,A.KETERANGAN,A.COST_CODE,A.COST_CODE_CATEGORI,A.EVIDANCE,A.AMOUNT,A.PROJECT,A.PAID_TO,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.TANGGAL
        FROM COST_REQUEST_DETAIL A
        WHERE 1=1 ";
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val'";
        }

        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsSum($paramsArray=array(), $statement="")
    {
        $str = "SELECT SUM(A.AMOUNT) AS ROWCOUNT FROM COST_REQUEST_DETAIL A WHERE 1=1 ".$statement;
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM COST_REQUEST_DETAIL A WHERE 1=1 ".$statement;
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
