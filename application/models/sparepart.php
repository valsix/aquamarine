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

class SparePart   extends Entity
{

    var $query;
    var $id;
    /**
     * Class constructor.
     **/
    function SparePart()
    {
        $this->Entity();
    }

            function insert()
            {
                $this->setField("SPARE_PART_ID", $this->getNextId("SPARE_PART_ID","SPARE_PART")); 

                $str = "INSERT INTO SPARE_PART (SPARE_PART_ID,CODE,NAMA_PART,NAMA_ALAT,KATEGORI,SERIAL_NUMBER,DESKTIPSI,LOKASI_ID,GAMBAR,JUMLAH,BAIK,RUSAK,KELUAR,SISA,NOMER,ID_PART,SERIAL_EQUIP,MODEL,CREATED_USER,CREATED_DATE)VALUES (
                '".$this->getField("SPARE_PART_ID")."',
                '".$this->getField("CODE")."',
                '".$this->getField("NAMA_PART")."',
                '".$this->getField("NAMA_ALAT")."',
                '".$this->getField("KATEGORI")."',
                '".$this->getField("SERIAL_NUMBER")."',
                '".$this->getField("DESKTIPSI")."',
                '".$this->getField("LOKASI_ID")."',
                '".$this->getField("GAMBAR")."',
                '".$this->getField("JUMLAH")."',
                '".$this->getField("BAIK")."',
                '".$this->getField("RUSAK")."',
                '".$this->getField("KELUAR")."',
                '".$this->getField("SISA")."',
                 '".$this->getField("NOMER")."',
                 '".$this->getField("ID_PART")."',
                  '".$this->getField("SERIAL_EQUIP")."',
                   '".$this->getField("MODEL")."',
                '".$this->USERID."',
                CURRENT_DATE
               
            )";

            $this->id = $this->getField("SPARE_PART_ID");
            $this->query= $str;
                // echo $str;exit();
            return $this->execQuery($str);
        }

        function update()
        {
            $str = "
            UPDATE SPARE_PART
            SET    
          
            CODE ='".$this->getField("CODE")."',
            NAMA_PART ='".$this->getField("NAMA_PART")."',
            NAMA_ALAT ='".$this->getField("NAMA_ALAT")."',
            ID_PART ='".$this->getField("ID_PART")."',
             SERIAL_EQUIP ='".$this->getField("SERIAL_EQUIP")."',
            SERIAL_NUMBER ='".$this->getField("SERIAL_NUMBER")."',
            DESKTIPSI ='".$this->getField("DESKTIPSI")."',
            LOKASI_ID ='".$this->getField("LOKASI_ID")."',
            KATEGORI ='".$this->getField("KATEGORI")."',
            JUMLAH ='".$this->getField("JUMLAH")."',
            BAIK ='".$this->getField("BAIK")."',
            RUSAK ='".$this->getField("RUSAK")."',
            KELUAR ='".$this->getField("KELUAR")."',
            SISA ='".$this->getField("SISA")."',
            NOMER ='".$this->getField("NOMER")."',
            UPDATED_BY ='".$this->USERID."',
            UPDATED_DATE =CURRENT_DATE
            WHERE CODE= '".$this->getField("CODE")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);
        }

        function update_pic(){
            $str = "
            UPDATE SPARE_PART
            SET    
          
           
            GAMBAR ='".$this->getField("GAMBAR")."'
          
            WHERE CODE= '".$this->getField("CODE")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);

        }
        function update_lampiran(){
            $str = "
            UPDATE SPARE_PART
            SET    
          
           
            LAMPIRAN ='".$this->getField("LAMPIRAN")."'
          
            WHERE CODE= '".$this->getField("CODE")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);

        }
        function delete(){
            $str = "
            UPDATE SPARE_PART
            SET    
          
           
            STATUS_DELETE ='DELETE'
          
            WHERE CODE= '".$this->getField("CODE")."'";
            $this->query = $str;
                  // echo $str;exit;
            return $this->execQuery($str);

        }
        function delete2($statement= "")
        {
            $str = "DELETE FROM SPARE_PART
            WHERE SPARE_PART_ID= '".$this->getField("SPARE_PART_ID")."'"; 
            $this->query = $str;
                  // echo $str;exit();
            return $this->execQuery($str);
        }
        function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SPARE_PART_ID ASC")
        {
            $str = "
            SELECT A.SPARE_PART_ID,A.CODE,A.NAMA_PART,A.NAMA_ALAT,A.KATEGORI,A.SERIAL_NUMBER,A.DESKTIPSI,A.LOKASI_ID,A.GAMBAR,A.JUMLAH,A.BAIK,A.RUSAK,A.KELUAR,A.SISA,A.CREATED_USER,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.NOMER,A.LAMPIRAN,B.NAMA NAMA_LOKASI
            ,SERIAL_EQUIP,ID_PART,A.MODEL,C.EC_NAME 
            FROM SPARE_PART A
            LEFT JOIN EQUIP_STORAGE B ON B.EQUIP_STORAGE_ID::VARCHAR = A.LOKASI_ID
             LEFT JOIN EQUIP_CATEGORY C ON C.EC_ID::VARCHAR = A.KATEGORI
             WHERE 1=1 AND A.STATUS_DELETE IS NULL ";
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
            $str = "SELECT COUNT(1) AS ROWCOUNT FROM SPARE_PART A WHERE 1=1 ".$statement;
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
