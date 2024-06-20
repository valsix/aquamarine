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

class ProjectHppNew   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function ProjectHppNew()
    {
        $this->Entity();
    }

    function insert()
    {
        $this->setField("PROJECT_HPP_NEW_ID", $this->getNextId("PROJECT_HPP_NEW_ID","PROJECT_HPP_NEW")); 

        $str = "INSERT INTO PROJECT_HPP_NEW (PROJECT_HPP_NEW_ID,NOMER,NAMA,CODE,LOKASI,HPP_DATE,TANGGAL,COMPANY_ID,ESTIMASI,APPROVED,KELUAR_BULANAN,KELUAR_HARIAN,PEMASUKAN_BULANAN,PEMASUKAN_HARIAN,NO_PO_CONTRACT,NAMA_PROJECT,PROFIT,PENGELUARANABCD,PENGELUARANEF,PEMASUKANABCD,PEMASUKANEF,CREATED_BY,CREATED_DATE)VALUES (
        '".$this->getField("PROJECT_HPP_NEW_ID")."',
        '".$this->getField("NOMER")."',
        '".$this->getField("NAMA")."',
        '".$this->getField("CODE")."',
        '".$this->getField("LOKASI")."',
        '".$this->getField("HPP_DATE")."',
        ".$this->getField("TANGGAL").",
        ".$this->getField("COMPANY_ID").",
        '".$this->getField("ESTIMASI")."',
        '".$this->getField("APPROVED")."',
        '".$this->getField("KELUAR_BULANAN")."',
        '".$this->getField("KELUAR_HARIAN")."',
        '".$this->getField("PEMASUKAN_BULANAN")."',
         '".$this->getField("PEMASUKAN_HARIAN")."',
        '".$this->getField("NO_PO_CONTRACT")."',
        '".$this->getField("NAMA_PROJECT")."',
         '".$this->getField("PROFIT")."',
          '".$this->getField("PENGELUARANABCD")."',
           '".$this->getField("PENGELUARANEF")."',
           '".$this->getField("PEMASUKANABCD")."',
            '".$this->getField("PEMASUKANEF")."',
        '".$this->USERID."',
        CURRENT_DATE
      
    )";

    $this->id = $this->getField("PROJECT_HPP_NEW_ID");
    $this->query= $str;
        // echo $str;exit();
    return $this->execQuery($str);
}

function update()
{
    $str = "
    UPDATE PROJECT_HPP_NEW
    SET    
    PROJECT_HPP_NEW_ID ='".$this->getField("PROJECT_HPP_NEW_ID")."',
    NOMER ='".$this->getField("NOMER")."',
    NAMA ='".$this->getField("NAMA")."',
    CODE ='".$this->getField("CODE")."',
     PENGELUARANABCD ='".$this->getField("PENGELUARANABCD")."',
      PENGELUARANEF ='".$this->getField("PENGELUARANEF")."',
       PEMASUKANABCD ='".$this->getField("PEMASUKANABCD")."',
        PEMASUKANEF ='".$this->getField("PEMASUKANEF")."',
    NO_PO_CONTRACT ='".$this->getField("NO_PO_CONTRACT")."',
    NAMA_PROJECT ='".$this->getField("NAMA_PROJECT")."',
    PROFIT ='".$this->getField("PROFIT")."',
    LOKASI ='".$this->getField("LOKASI")."',
    HPP_DATE ='".$this->getField("HPP_DATE")."',
    TANGGAL =".$this->getField("TANGGAL").",
    COMPANY_ID =".$this->getField("COMPANY_ID").",
    ESTIMASI ='".$this->getField("ESTIMASI")."',
    APPROVED ='".$this->getField("APPROVED")."',
    KELUAR_BULANAN ='".$this->getField("KELUAR_BULANAN")."',
    KELUAR_HARIAN ='".$this->getField("KELUAR_HARIAN")."',
    PEMASUKAN_BULANAN ='".$this->getField("PEMASUKAN_BULANAN")."',
    PEMASUKAN_HARIAN ='".$this->getField("PEMASUKAN_HARIAN")."',   
    UPDATED_BY ='".$this->USERID."',
    UPDATED_DATE =CURRENT_DATE
    WHERE PROJECT_HPP_NEW_ID= '".$this->getField("PROJECT_HPP_NEW_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}

function delete()
{
    $str = "
    UPDATE PROJECT_HPP_NEW
    SET    
   
    STATUS_DELETE ='DELETE'
 
    WHERE PROJECT_HPP_NEW_ID= '".$this->getField("PROJECT_HPP_NEW_ID")."'";
    $this->query = $str;
          // echo $str;exit;
    return $this->execQuery($str);
}


function delete2($statement= "")
{
    $str = "DELETE FROM PROJECT_HPP_NEW
    WHERE PROJECT_HPP_NEW_ID= '".$this->getField("PROJECT_HPP_NEW_ID")."'"; 
    $this->query = $str;
          // echo $str;exit();
    return $this->execQuery($str);
}
function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PROJECT_HPP_NEW_ID ASC")
{
    $str = "
    SELECT A.PROJECT_HPP_NEW_ID,A.NOMER,A.NAMA,A.CODE,A.LOKASI,A.HPP_DATE,A.TANGGAL,A.COMPANY_ID,A.ESTIMASI,A.APPROVED,A.KELUAR_BULANAN,A.KELUAR_HARIAN,A.PEMASUKAN_BULANAN,A.PEMASUKAN_HARIAN,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
     ,B.NAMA NAMA_PROJECT2, B.CODE CODE_PROJECT,C.NAME NAMA_COMPANY,A.NO_PO_CONTRACT,A.NAMA_PROJECT,A.PROFIT,A.PENGELUARANABCD,A.PENGELUARANEF,A.PEMASUKANABCD,A.PEMASUKANEF,B.KETERANGAN CODE_PROJECT_KET,TO_CHAR(A.TANGGAL,'YYYY') TAHUN ,C.CP1_NAME ,B.MASTER_PROJECT_ID,C.ADDRESS,C.EMAIL,C.PHONE,C.EMAIL,C.FAX,C.cp1_telp
    FROM PROJECT_HPP_NEW A
    LEFT JOIN MASTER_PROJECT B ON A.CODE = B.MASTER_PROJECT_ID::VARCHAR
    LEFT JOIN company C ON C.company_id::VARCHAR = A.company_id
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
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM PROJECT_HPP_NEW A WHERE 1=1  AND A.STATUS_DELETE IS NULL ".$statement;
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
