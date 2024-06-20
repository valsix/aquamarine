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

class ProjectHppNewD   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function ProjectHppNewD()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("PROJECT_HPP_NEW_D_ID", $this->getNextId("PROJECT_HPP_NEW_D_ID","PROJECT_HPP_NEW_D")); 

        $str = "INSERT INTO PROJECT_HPP_NEW_D (PROJECT_HPP_NEW_D_ID,PROJECT_HPP_NEW_ID,URUT,CODE,PART,KETERANGAN,K_QTY,K_DAY,K_STATUS,K_HARGA,K_TOTAL,K_BULANAN,P_QTY,P_DAY,P_STATUS,P_HARGA,P_BULANAN,CREATED_BY,CREATED_DATE)VALUES (
        '".$this->getField("PROJECT_HPP_NEW_D_ID")."',
        '".$this->getField("PROJECT_HPP_NEW_ID")."',
        '".$this->getField("URUT")."',
        '".$this->getField("CODE")."',
        '".$this->getField("PART")."',
        '".$this->getField("KETERANGAN")."',
        '".$this->getField("K_QTY")."',
        '".$this->getField("K_DAY")."',
        '".$this->getField("K_STATUS")."',
        '".$this->getField("K_HARGA")."',
        '".$this->getField("K_TOTAL")."',
        '".$this->getField("K_BULANAN")."',
        '".$this->getField("P_QTY")."',
        '".$this->getField("P_DAY")."',
        '".$this->getField("P_STATUS")."',
        '".$this->getField("P_HARGA")."',
        '".$this->getField("P_BULANAN")."',
        '".$this->USERID."',
       CURRENT_DATE
       
    )";

    $this->id = $this->getField("PROJECT_HPP_NEW_D_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
}

function update()
{
    $str = "
    UPDATE PROJECT_HPP_NEW_D
    SET    
    PROJECT_HPP_NEW_D_ID ='".$this->getField("PROJECT_HPP_NEW_D_ID")."',
    PROJECT_HPP_NEW_ID ='".$this->getField("PROJECT_HPP_NEW_ID")."',
    URUT ='".$this->getField("URUT")."',
    CODE ='".$this->getField("CODE")."',
    PART ='".$this->getField("PART")."',
    KETERANGAN ='".$this->getField("KETERANGAN")."',
    K_QTY ='".$this->getField("K_QTY")."',
    K_DAY ='".$this->getField("K_DAY")."',
    K_STATUS ='".$this->getField("K_STATUS")."',
    K_HARGA ='".$this->getField("K_HARGA")."',
    K_TOTAL ='".$this->getField("K_TOTAL")."',
    K_BULANAN ='".$this->getField("K_BULANAN")."',
    P_QTY ='".$this->getField("P_QTY")."',
    P_DAY ='".$this->getField("P_DAY")."',
    P_STATUS ='".$this->getField("P_STATUS")."',
    P_HARGA ='".$this->getField("P_HARGA")."',
    P_BULANAN ='".$this->getField("P_BULANAN")."',
   
    UPDATED_BY ='".$this->USERID."',
    UPDATED_DATE =CURRENT_DATE
    WHERE PROJECT_HPP_NEW_D_ID= '".$this->getField("PROJECT_HPP_NEW_D_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}
function delete($statement= "")
{
    $str = "DELETE FROM PROJECT_HPP_NEW_D
    WHERE PROJECT_HPP_NEW_D_ID= '".$this->getField("PROJECT_HPP_NEW_D_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}
function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PROJECT_HPP_NEW_D_ID ASC")
{
    $str = "
    SELECT A.PROJECT_HPP_NEW_D_ID,A.PROJECT_HPP_NEW_ID,A.URUT,A.CODE,A.PART,A.KETERANGAN,A.K_QTY,A.K_DAY,A.K_STATUS,A.K_HARGA,A.K_TOTAL,A.K_BULANAN,A.P_QTY,A.P_DAY,A.P_STATUS,A.P_HARGA,A.P_BULANAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.URUT TEMPLATE_HPP_ID
    FROM PROJECT_HPP_NEW_D A
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
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM PROJECT_HPP_NEW_D A WHERE 1=1 ".$statement;
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
