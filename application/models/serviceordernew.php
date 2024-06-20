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

class ServiceOrderNew   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function ServiceOrderNew()
    {
        $this->Entity();
    }

            function insert()
            {
                $this->setField("SERVICE_ORDER_NEW_ID", $this->getNextId("SERVICE_ORDER_NEW_ID","SERVICE_ORDER_NEW")); 

                $str = "INSERT INTO SERVICE_ORDER_NEW (SERVICE_ORDER_NEW_ID,COMPANY_ID,HPP_PROJECT_ID,DATE_OWR,DATE_WORK,DATE_DEPTURE,DATE_FINISH,PROJECT_NAME,LOCATION,PIC_EQUIPMENT,TRANSPORTATION,OBLIGATION,PENANGGUNG_JAWAB,CREATED_BY,CREATED_DATE)VALUES (
                '".$this->getField("SERVICE_ORDER_NEW_ID")."',
                ".$this->getField("COMPANY_ID").",
                ".$this->getField("HPP_PROJECT_ID").",
                ".$this->getField("DATE_OWR").",
                ".$this->getField("DATE_WORK").",
                ".$this->getField("DATE_DEPTURE").",
                ".$this->getField("DATE_FINISH").",
                '".$this->getField("PROJECT_NAME")."',
                '".$this->getField("LOCATION")."',
                '".$this->getField("PIC_EQUIPMENT")."',
                '".$this->getField("TRANSPORTATION")."',
                '".$this->getField("OBLIGATION")."',
                '".$this->getField("PENANGGUNG_JAWAB")."',
              
                '".$this->USERID."',
                CURRENT_DATE

            )";

            $this->id = $this->getField("SERVICE_ORDER_NEW_ID");
            $this->query= $str;
                // echo $str;exit();
            return $this->execQuery($str);
        }

         function insertFromHpp()
            {
                $this->setField("SERVICE_ORDER_NEW_ID", $this->getNextId("SERVICE_ORDER_NEW_ID","SERVICE_ORDER_NEW")); 

                $str = "INSERT INTO SERVICE_ORDER_NEW (SERVICE_ORDER_NEW_ID,COMPANY_ID,HPP_PROJECT_ID,DARI,CREATED_BY,CREATED_DATE)VALUES (
                '".$this->getField("SERVICE_ORDER_NEW_ID")."',
                '".$this->getField("COMPANY_ID")."',
                '".$this->getField("HPP_PROJECT_ID")."',
                 'HPP',
              
              
                '".$this->USERID."',
                CURRENT_DATE

            )";

            $this->id = $this->getField("SERVICE_ORDER_NEW_ID");
            $this->query= $str;
                // echo $str;exit();
            return $this->execQuery($str);
        }

        function update()
        {
            $str = "
            UPDATE SERVICE_ORDER_NEW
            SET    
            SERVICE_ORDER_NEW_ID ='".$this->getField("SERVICE_ORDER_NEW_ID")."',
            COMPANY_ID =".$this->getField("COMPANY_ID").",
            HPP_PROJECT_ID =".$this->getField("HPP_PROJECT_ID").",
            DATE_OWR =".$this->getField("DATE_OWR").",
            DATE_WORK =".$this->getField("DATE_WORK").",
            DATE_DEPTURE =".$this->getField("DATE_DEPTURE").",
            DATE_FINISH =".$this->getField("DATE_FINISH").",
            PROJECT_NAME ='".$this->getField("PROJECT_NAME")."',
            LOCATION ='".$this->getField("LOCATION")."',
            PIC_EQUIPMENT ='".$this->getField("PIC_EQUIPMENT")."',
            TRANSPORTATION ='".$this->getField("TRANSPORTATION")."',
            OBLIGATION ='".$this->getField("OBLIGATION")."',
            PENANGGUNG_JAWAB ='".$this->getField("PENANGGUNG_JAWAB")."',
        
            UPDATED_BY ='".$this->USERID."',
            UPDATED_DATE =CURRENT_DATE
            WHERE SERVICE_ORDER_NEW_ID= '".$this->getField("SERVICE_ORDER_NEW_ID")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }

         function updateFromHpp()
        {
            $str = "
            UPDATE SERVICE_ORDER_NEW
            SET    
            COMPANY_ID ='".$this->getField("COMPANY_ID")."',
            HPP_PROJECT_ID ='".$this->getField("HPP_PROJECT_ID")."',
          
        
            UPDATED_BY ='".$this->USERID."',
            UPDATED_DATE =CURRENT_DATE
            WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."' AND DARI='HPP'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }
        function delete()
        {
            $str = "
            UPDATE SERVICE_ORDER_NEW
            SET    

            STATUS_DELETE ='DELETE'

            WHERE SERVICE_ORDER_NEW_ID= '".$this->getField("SERVICE_ORDER_NEW_ID")."'";
            $this->query = $str;
          // echo $str;exit;
            return $this->execQuery($str);
        }
         function deleteFormHpp()
        {
            $str = "
            UPDATE SERVICE_ORDER_NEW
            SET    

            STATUS_DELETE ='DELETE'

            WHERE HPP_PROJECT_ID= '".$this->getField("HPP_PROJECT_ID")."'";
            $this->query = $str;
          // echo $str;exit;
            return $this->execQuery($str);
        }

        function delete2($statement= "")
        {
            $str = "DELETE FROM SERVICE_ORDER_NEW
            WHERE SERVICE_ORDER_NEW_ID= '".$this->getField("SERVICE_ORDER_NEW_ID")."'"; 
            $this->query = $str;
                  // echo $str;exit();
            return $this->execQuery($str);
        }
        function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SERVICE_ORDER_NEW_ID ASC")
        {
            $str = "
            SELECT A.SERVICE_ORDER_NEW_ID,A.COMPANY_ID,A.HPP_PROJECT_ID,A.DATE_OWR,A.DATE_WORK,A.DATE_DEPTURE,A.DATE_FINISH,A.PROJECT_NAME,A.PIC_EQUIPMENT,A.TRANSPORTATION,A.OBLIGATION,A.PENANGGUNG_JAWAB,A.DARI,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,B.NAME COMPANY_NAME,B.CP1_NAME CONTACT_PERSON
            ,C.NOMER NO_ORDER,C.NAMA_PROJECT,D.CODE CODE,D.NAMA NAMA_CODE_PROJECT,D.KETERANGAN NO_PO, C.LOKASI AS LOCATION
            FROM SERVICE_ORDER_NEW A 
            LEFT JOIN COMPANY B ON B.COMPANY_ID= A.COMPANY_ID
            LEFT JOIN PROJECT_HPP_NEW C ON C.PROJECT_HPP_NEW_ID = A.HPP_PROJECT_ID
            LEFT JOIN MASTER_PROJECT D ON D.MASTER_PROJECT_ID::VARCHAR = C.CODE

            WHERE 1=1  AND A.STATUS_DELETE IS NULL ";
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
            $str = "SELECT COUNT(1) AS ROWCOUNT FROM SERVICE_ORDER_NEW A WHERE 1=1 AND A.STATUS_DELETE IS NULL ".$statement;
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
