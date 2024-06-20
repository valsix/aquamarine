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

class ProjectHppDetail    extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function ProjectHppDetail()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("PROJECT_HPP_DETAIL_ID", $this->getNextId("PROJECT_HPP_DETAIL_ID","PROJECT_HPP_DETAIL")); 

        $str = "INSERT INTO PROJECT_HPP_DETAIL (PROJECT_HPP_DETAIL_ID, HPP_PROJECT_ID, CODE, DESCRIPTION, QTY,        UNIT_RATE, DAYS, TOTAL,HPP_MASTER_ID, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("PROJECT_HPP_DETAIL_ID")."',
        '".$this->getField("HPP_PROJECT_ID")."',
        '".$this->getField("CODE")."',
        '".$this->getField("DESCRIPTION")."',
        '".$this->getField("QTY")."',
        '".$this->getField("UNIT_RATE")."',
        '".$this->getField("DAYS")."',
        '".$this->getField("TOTAL")."',
        ".retNullString($this->getField("HPP_MASTER_ID")).",
        '".$this->USERNAME."',
        CURRENT_DATE
       
    )";

    $this->id = $this->getField("PROJECT_HPP_DETAIL_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update_detail(){
        $str = "
        UPDATE PROJECT_HPP_DETAIL
        SET
        CODE ='".$this->getField("CODE")."',
        DESCRIPTION ='".$this->getField("DESCRIPTION")."'
        WHERE CAST(HPP_MASTER_ID AS VARCHAR)= '".$this->getField("HPP_MASTER_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);

    }

    function update_cost(){
         $str = "
        UPDATE PROJECT_HPP_DETAIL
        SET    
        QTY ='".$this->getField("QTY")."',
        TOTAL ='".$this->getField("TOTAL")."'
       
        
        WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."' 
        AND HPP_MASTER_ID ='".$this->getField("HPP_MASTER_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }
    
    function update()
    {
        $str = "
        UPDATE PROJECT_HPP_DETAIL
        SET    
        PROJECT_HPP_DETAIL_ID ='".$this->getField("PROJECT_HPP_DETAIL_ID")."',
        HPP_PROJECT_ID ='".$this->getField("HPP_PROJECT_ID")."',
        CODE ='".$this->getField("CODE")."',
        DESCRIPTION ='".$this->getField("DESCRIPTION")."',
        QTY ='".$this->getField("QTY")."',
        UNIT_RATE ='".$this->getField("UNIT_RATE")."',
        DAYS ='".$this->getField("DAYS")."',
        TOTAL ='".$this->getField("TOTAL")."',
       
        UPDATED_BY ='".$this->USERNAME."',
        UPDATED_DATE =  CURRENT_DATE
        WHERE PROJECT_HPP_DETAIL_ID= '".$this->getField("PROJECT_HPP_DETAIL_ID")."' ";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM PROJECT_HPP_DETAIL
        WHERE PROJECT_HPP_DETAIL_ID= '".$this->getField("PROJECT_HPP_DETAIL_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }
    function deleteParent($statement= "")
    {
        $str = "DELETE FROM PROJECT_HPP_DETAIL
        WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PROJECT_HPP_DETAIL_ID ASC")
    {
        $str = "
        SELECT A.PROJECT_HPP_DETAIL_ID,A.HPP_PROJECT_ID,A.CODE,A.DESCRIPTION,A.QTY,A.UNIT_RATE,A.DAYS,A.TOTAL,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
        FROM PROJECT_HPP_DETAIL A
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM PROJECT_HPP_DETAIL A WHERE 1=1 ".$statement;
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
