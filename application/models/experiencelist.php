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

class ExperienceList    extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function ExperienceList()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("EXPERIENCE_LIST_ID", $this->getNextId("EXPERIENCE_LIST_ID","EXPERIENCE_LIST")); 

        $str = "INSERT INTO EXPERIENCE_LIST (EXPERIENCE_LIST_ID, PROJECT_NAME, PROJECT_LOCATION, COSTUMER_ID,        CONTACT_NO, FROM_DATE, TO_DATE, DURATION, URUT, CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("EXPERIENCE_LIST_ID")."',
        '".$this->getField("PROJECT_NAME")."',
        '".$this->getField("PROJECT_LOCATION")."',
        ".$this->getField("COSTUMER_ID").",
        '".$this->getField("CONTACT_NO")."',
        ".$this->getField("FROM_DATE").",
        ".$this->getField("TO_DATE").",
        '".$this->getField("DURATION")."',
        '".$this->getField("URUT")."',
        '".$this->USERNAME."',
        now()
       
    )";

    $this->id = $this->getField("EXPERIENCE_LIST_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE EXPERIENCE_LIST
        SET    
        EXPERIENCE_LIST_ID ='".$this->getField("EXPERIENCE_LIST_ID")."',
        PROJECT_NAME ='".$this->getField("PROJECT_NAME")."',
        PROJECT_LOCATION ='".$this->getField("PROJECT_LOCATION")."',
        COSTUMER_ID =".$this->getField("COSTUMER_ID").",
        CONTACT_NO ='".$this->getField("CONTACT_NO")."',
        FROM_DATE =".$this->getField("FROM_DATE").",
        TO_DATE =".$this->getField("TO_DATE").",
        DURATION ='".$this->getField("DURATION")."',
        URUT ='".$this->getField("URUT")."',
        UPDATED_BY ='".$this->USERNAME."',
        UPDATED_DATE =now()
        WHERE EXPERIENCE_LIST_ID= '".$this->getField("EXPERIENCE_LIST_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function update_path()
    {
        $str = "
        UPDATE EXPERIENCE_LIST
        SET    
        PATH ='" . $this->getField("PATH") . "'
       
        WHERE EXPERIENCE_LIST_ID= '" . $this->getField("EXPERIENCE_LIST_ID") . "'";
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

    function delete($statement= "")
    {
        $str = "DELETE FROM EXPERIENCE_LIST
        WHERE EXPERIENCE_LIST_ID= '".$this->getField("EXPERIENCE_LIST_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.EXPERIENCE_LIST_ID ASC")
    {
        $str = "
        SELECT A.EXPERIENCE_LIST_ID,A.PROJECT_NAME,A.PROJECT_LOCATION,A.COSTUMER_ID,A.CONTACT_NO,A.FROM_DATE,A.TO_DATE,A.DURATION,A.URUT,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.PATH
        FROM EXPERIENCE_LIST A
        LEFT JOIN COMPANY B ON B.COMPANY_ID = A.COSTUMER_ID
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM EXPERIENCE_LIST A 
        LEFT JOIN COMPANY B ON B.COMPANY_ID = A.COSTUMER_ID WHERE 1=1 ".$statement;
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

    function getCountByParamsMinYear($paramsArray=array(), $statement="")
    {
        $str = "SELECT MIN(TO_CHAR(FROM_DATE,'YYYY')) AS ROWCOUNT FROM EXPERIENCE_LIST A WHERE 1=1 ".$statement;
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
    function getCountByParamsMaxYear($paramsArray=array(), $statement="")
    {
        $str = "SELECT MAX(TO_CHAR(FROM_DATE,'YYYY')) AS ROWCOUNT FROM EXPERIENCE_LIST A WHERE 1=1 ".$statement;
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
