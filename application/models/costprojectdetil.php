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

class CostProjectDetil  extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function CostProjectDetil()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("COST_PROJECT_DETIL_ID", $this->getNextId("COST_PROJECT_DETIL_ID","COST_PROJECT_DETIL")); 

        $str = "INSERT INTO COST_PROJECT_DETIL (COST_PROJECT_DETIL_ID, COST_PROJECT_ID, COST_DATE, DESCRIPTION,        COST, STATUS,CURRENCY)VALUES (
        '".$this->getField("COST_PROJECT_DETIL_ID")."',
        '".$this->getField("COST_PROJECT_ID")."',
        ".$this->getField("COST_DATE").",
        '".$this->getField("DESCRIPTION")."',
        ".$this->getField("COST").",
        '".$this->getField("STATUS")."' ,
         '".$this->getField("CURRENCY")."' 
    )";

    $this->id = $this->getField("COST_PROJECT_DETIL_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }



    function insert_form_hpp(){
        $this->setField("COST_PROJECT_DETIL_ID", $this->getNextId("COST_PROJECT_DETIL_ID","COST_PROJECT_DETIL")); 
         $str = "INSERT INTO COST_PROJECT_DETIL (COST_PROJECT_DETIL_ID, COST_PROJECT_ID,  DESCRIPTION,  STATUS,CURRENCY,PROJECT_HPP_DETAIL_ID,HPP_PROJECT_ID,COST_DATE)VALUES (
         '".$this->getField("COST_PROJECT_DETIL_ID")."',
         '".$this->getField("COST_PROJECT_ID")."',
         '".$this->getField("DESCRIPTION")."',
         '1' ,
         'IDR',         
         '".$this->getField("PROJECT_HPP_DETAIL_ID")."',
         '".$this->getField("HPP_PROJECT_ID")."',
         CURRENT_DATE

    )";

    $this->id = $this->getField("COST_PROJECT_DETIL_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update_cost_code(){
        // -- COST_DATE =CURRENT_DATE,--
        $str = "
        UPDATE COST_PROJECT_DETIL
        SET    
      
            
        CODE ='".$this->getField("CODE")."',
        COST ='".$this->getField("COST")."'
        WHERE COST_PROJECT_DETIL_ID= '".$this->getField("COST_PROJECT_DETIL_ID")."'";
        $this->query = $str;
           // echo $str;exit;
        return $this->execQuery($str);
    }

     function update_detail()
    {
        $str = "
        UPDATE COST_PROJECT_DETIL
        SET    
      
           
        DESCRIPTION ='".$this->getField("DESCRIPTION")."',
         CODE ='".$this->getField("CODE")."'
        WHERE CAST(PROJECT_HPP_DETAIL_ID AS VARCHAR)= '".$this->getField("PROJECT_HPP_DETAIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function update_from_hpp()
    {
        $str = "
        UPDATE COST_PROJECT_DETIL
        SET    
      
            COST_DATE =CURRENT_DATE,
        DESCRIPTION ='".$this->getField("DESCRIPTION")."'
        WHERE PROJECT_HPP_DETAIL_ID= '".$this->getField("PROJECT_HPP_DETAIL_ID")."' AND HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete_from_hpp(){
        $str = " DELETE FROM COST_PROJECT_DETIL
        WHERE PROJECT_HPP_DETAIL_ID= '".$this->getField("PROJECT_HPP_DETAIL_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }
    function delete_from_hpp_parent(){
        $str = " DELETE FROM COST_PROJECT_DETIL
        WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE COST_PROJECT_DETIL
        SET    
        COST_PROJECT_DETIL_ID ='".$this->getField("COST_PROJECT_DETIL_ID")."',
        COST_PROJECT_ID ='".$this->getField("COST_PROJECT_ID")."',
        COST_DATE =".$this->getField("COST_DATE").",
        DESCRIPTION ='".$this->getField("DESCRIPTION")."',
        COST =".$this->getField("COST").",
        CURRENCY ='".$this->getField("CURRENCY")."',
        STATUS ='".$this->getField("STATUS")."' 
        WHERE COST_PROJECT_DETIL_ID= '".$this->getField("COST_PROJECT_DETIL_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }
    function delete($statement= "")
    {
        $str = "DELETE FROM COST_PROJECT_DETIL
        WHERE COST_PROJECT_DETIL_ID= '".$this->getField("COST_PROJECT_DETIL_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }
    function deleteParent($statement= "")
    {
        $str = "DELETE FROM COST_PROJECT_DETIL
        WHERE CAST(COST_PROJECT_ID AS VARCHAR)= '".$this->getField("COST_PROJECT_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.COST_PROJECT_DETIL_ID ASC")
    {
        $str = "
        SELECT A.COST_PROJECT_DETIL_ID,A.COST_PROJECT_ID,A.COST_DATE,A.DESCRIPTION,A.COST,A.STATUS,TO_CHAR(A.COST_DATE, 'DD-MM-YYYY') AS COST_DATES,A.CURRENCY,B.TOTAL,A.HPP_PROJECT_ID,A.PROJECT_HPP_DETAIL_ID,(CASE WHEN A.CODE IS NULL THEN B.CODE ELSE A.CODE END) CODE,(CASE WHEN B.TOTAL IS NULL THEN '0' ELSE B.TOTAL END)
TOTALI,
(CASE WHEN B.QTY IS NULL THEN '0' ELSE B.QTY END)
QTY,
(CASE WHEN B.UNIT_RATE IS NULL THEN '0' ELSE B.UNIT_RATE END)
UNIT_RATE,
(CASE WHEN B.DAYS IS NULL THEN '0' ELSE B.DAYS END)
DAYS
        FROM COST_PROJECT_DETIL A
        LEFT JOIN  PROJECT_HPP_DETAIL B ON  CAST(B.PROJECT_HPP_DETAIL_ID AS VARCHAR) = A.PROJECT_HPP_DETAIL_ID
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM COST_PROJECT_DETIL A WHERE 1=1 ".$statement;
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
