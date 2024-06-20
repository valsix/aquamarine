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

class ProjectHpp    extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function ProjectHpp()
    {
        $this->Entity();
    }


    function insert()
    {
        $this->setField("HPP_PROJECT_ID", $this->getNextId("HPP_PROJECT_ID","PROJECT_HPP")); 

        $str = "INSERT INTO PROJECT_HPP (HPP_PROJECT_ID, NAMA, LOA, LOCATION, SWL,REF_NO, BULAN_HPP, DATE_PROJECT,FOR_APPROVED,        CREATED_BY, CREATED_DATE)VALUES (
        '".$this->getField("HPP_PROJECT_ID")."',
        '".$this->getField("NAMA")."',
        '".$this->getField("LOA")."',
         
        '".$this->getField("LOCATION")."',
        '".$this->getField("SWL")."',
        '".$this->getField("REF_NO")."',
        '".$this->getField("BULAN_HPP")."',
        '".$this->getField("DATE_PROJECT")."',
        '".$this->getField("FOR_APPROVED ")."',
        '".$this->USERNAME."',
         CURRENT_DATE
        
    )";

    $this->id = $this->getField("HPP_PROJECT_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
    }

    function update()
    {
        $str = "
        UPDATE PROJECT_HPP
        SET    
        HPP_PROJECT_ID ='".$this->getField("HPP_PROJECT_ID")."',
        NAMA ='".$this->getField("NAMA")."',
        LOA ='".$this->getField("LOA")."',
        LOCATION ='".$this->getField("LOCATION")."',
        REF_NO ='".$this->getField("REF_NO")."',
         SWL ='".$this->getField("SWL")."',
        BULAN_HPP ='".$this->getField("BULAN_HPP")."',
         FOR_APPROVED ='".$this->getField("FOR_APPROVED")."',
       
        DATE_PROJECT ='".$this->getField("DATE_PROJECT")."',
        UPDATED_BY ='".$this->USERNAME."',
        UPDATED_DATE =CURRENT_DATE
         
        WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function offer_to_hpp()
    {
        $str = "
        UPDATE PROJECT_HPP
        SET    
        
        OWNER ='".$this->getField("OWNER")."',
        JENIS_PEKERJAAN ='".$this->getField("JENIS_PEKERJAAN")."',
        COMPANY_ID =".retNullString($this->getField("COMPANY_ID")).",
        VESSEL_ID =".retNullString($this->getField("VESSEL_ID")).",
         CLASS ='".$this->getField("CLASS")."',
         DATE_PROJECT =".$this->getField("DATE_PROJECT").",
        JENIS_KAPAL ='".$this->getField("JENIS_KAPAL")."',
        NAMA ='".$this->getField("NAMA")."'
      
         
        WHERE CAST(HPP_PROJECT_ID AS VARCHAR)= '".$this->getField("HPP_PROJECT_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

    function approval()
    {
        $str = "
        UPDATE PROJECT_HPP
        SET    
        
        STATUS_APPROVED ='".$this->USERNAME."'
      
         
        WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }

     function update_patch()
    {
        $str = "
        UPDATE PROJECT_HPP
        SET    
        
        PATH_FILE ='".$this->getField("PATH_FILE")."'
      
         
        WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }


    function cancel_approval()
    {
        $str = "
        UPDATE PROJECT_HPP
        SET    
        
        STATUS_APPROVED =NULL
      
         
        WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }
    function update_part2()
    {
        $str = "
        UPDATE PROJECT_HPP
        SET    
        JENIS_PEKERJAAN ='".$this->getField("JENIS_PEKERJAAN")."',
        OWNER ='".$this->getField("OWNER")."',
         MASTER_LOKASI_ID =".$this->getField("MASTER_LOKASI_ID").",
           COMPANY_ID ='".$this->getField("COMPANY_ID")."',
           VESSEL_ID ='".$this->getField("VESSEL_ID")."',
        JENIS_KAPAL ='".$this->getField("JENIS_KAPAL")."',
        FLAG ='".$this->getField("FLAG")."',
        CLASS ='".$this->getField("CLASS")."',
        ESTIMASI_PEKERJAAN ='".$this->getField("ESTIMASI_PEKERJAAN")."',
        LOKASI_PEKERJAAN ='".$this->getField("LOKASI_PEKERJAAN")."',
        COST_FROM_AMDI ='".$this->getField("COST_FROM_AMDI")."',
        AGENT ='".$this->getField("AGENT")."',
        COST_TO_CLIENT ='".$this->getField("COST_TO_CLIENT")."',
        PROFIT ='".$this->getField("PROFIT")."',
         STATUS_CHANGE ='".$this->getField("STATUS_CHANGE")."',
        PRESCENTAGE ='".$this->getField("PRESCENTAGE")."'
      
         
        WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'";
        $this->query = $str;
          // echo $str;exit;
        return $this->execQuery($str);
    }
    function delete($statement= "")
    {
        $str = "DELETE FROM PROJECT_HPP
        WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'"; 
        $this->query = $str;
          // echo $str;exit();
        return $this->execQuery($str);
    }
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.HPP_PROJECT_ID ASC")
    {
        $str = "
        SELECT A.HPP_PROJECT_ID,A.NAMA,A.LOA,A.LOCATION,A.REF_NO,A.BULAN_HPP,A.DATE_PROJECT,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.JENIS_PEKERJAAN,A.OWNER,A.JENIS_KAPAL,A.FLAG,A.CLASS,A.ESTIMASI_PEKERJAAN,A.LOKASI_PEKERJAAN,A.COST_FROM_AMDI,A.AGENT,A.COST_TO_CLIENT,A.PROFIT,A.PRESCENTAGE,A.COMPANY_ID,A.VESSEL_ID,B.NAMA PEKERJAAN_NAMA,A.FOR_APPROVED,A.STATUS_APPROVED ,A.MASTER_LOKASI_ID,A.STATUS_CHANGE,A.SWL,A.PATH_FILE
        FROM PROJECT_HPP A
        LEFT JOIN SERVICES B ON CAST(B.SERVICES_ID AS VARCHAR)  = A.JENIS_PEKERJAAN
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
        $str = "SELECT COUNT(1) AS ROWCOUNT FROM PROJECT_HPP A WHERE 1=1 ".$statement;
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
