<? 
/* *******************************************************************************************************
MODUL NAME          : MTSN LAWANG
FILE NAME           : 
AUTHOR              : 
VERSION             : 1.0
MODIFICATION DOC    :
DESCRIPTION         : 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');
  
  class Informasi extends Entity{ 

    var $query;
    /**
    * Class constructor.
    **/
    function Informasi()
    {
      $this->Entity(); 
    }
    
    function insert()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $this->setField("INFORMASI_ID", $this->getNextId("INFORMASI_ID","INFORMASI"));

        $str = "
                    INSERT INTO INFORMASI (
                           INFORMASI_ID, NAMA, TANGGAL, KETERANGAN, 
                           LINK_FOTO, LINK_FILE, STATUS, JENIS_INFORMASI,
                           LAST_CREATE_USER, LAST_CREATE_DATE) 
                        VALUES ( '".$this->getField("INFORMASI_ID")."', '".$this->getField("NAMA")."', ".$this->getField("TANGGAL").", '".$this->getField("KETERANGAN")."',
                            '".$this->getField("LINK_FOTO")."', '".$this->getField("LINK_FILE")."', '".$this->getField("STATUS")."', '".$this->getField("JENIS_INFORMASI")."',
                            '".$this->getField("LAST_CREATE_USER")."', ".$this->getField("LAST_CREATE_DATE").")
                "; 
        $this->query = $str;
        $this->id = $this->getField("INFORMASI_ID");
        //echo $str;
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "
                UPDATE INFORMASI
                SET    NAMA             = '".$this->getField("NAMA")."',
                       TANGGAL          = ".$this->getField("TANGGAL").",
                       KETERANGAN       = '".$this->getField("KETERANGAN")."',
                       LINK_FOTO        = '".$this->getField("LINK_FOTO")."',
                       LINK_FILE        = '".$this->getField("LINK_FILE")."',
                       STATUS           = '".$this->getField("STATUS")."',
                       JENIS_INFORMASI  = '".$this->getField("JENIS_INFORMASI")."',
                       LAST_UPDATE_USER = '".$this->getField("LAST_UPDATE_USER")."',
                       LAST_UPDATE_DATE = ".$this->getField("LAST_UPDATE_DATE")."
                WHERE  INFORMASI_ID     = '".$this->getField("INFORMASI_ID")."'
             "; 
             // echo $str;
        $this->query = $str;
        return $this->execQuery($str);
    }

    function updateByField()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $str = "UPDATE INFORMASI A SET
                  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
                WHERE INFORMASI_ID = ".$this->getField("INFORMASI_ID")."
                "; 
                $this->query = $str;
    
        return $this->execQuery($str);
    }   

    function delete()
    {
        $str = "DELETE FROM INFORMASI
                WHERE 
                  INFORMASI_ID = ".$this->getField("INFORMASI_ID").""; 
                  
        $this->query = $str;
        return $this->execQuery($str);
    }
    
    function publish()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $str = "
                UPDATE INFORMASI
                SET    STATUS              = '".$this->getField("STATUS")."'
                WHERE  INFORMASI_ID        = '".$this->getField("INFORMASI_ID")."'

                "; 
                $this->query = $str;
        return $this->execQuery($str);
    }
    
    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY INFORMASI_ID ASC")
    {
        $str = "
                    SELECT 
                    INFORMASI_ID, DEPARTEMEN_ID, USER_LOGIN_ID, 
                       NAMA, TO_CHAR(A.TANGGAL, 'YYYY-MM-DD HH24:MI') TANGGAL, KETERANGAN, 
                       LINK_FOTO, LINK_FILE, STATUS, JENIS_INFORMASI,
                       LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
                       LAST_UPDATE_DATE
                    FROM INFORMASI A
                    WHERE 1=1 AND STATUS = '1'
                "; 
        
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$order;
        $this->query = $str;
        // echo $str; exit();
        return $this->selectLimit($str,$limit,$from); 
    }
    
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY INFORMASI_ID ASC")
    {
        $str = "
                    SELECT 
                    INFORMASI_ID, DEPARTEMEN_ID, USER_LOGIN_ID, 
                       NAMA, TO_CHAR(A.TANGGAL, 'YYYY-MM-DD') TANGGAL, KETERANGAN, 
                       LINK_FOTO, LINK_FILE, STATUS, JENIS_INFORMASI, 
                       LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
                       LAST_UPDATE_DATE,
                       CASE 
                           WHEN A.STATUS = '1' THEN 'Aktif'
                        ELSE
                            'Non-aktif'
                       END STATUS_KETERANGAN
                    FROM INFORMASI A
                    WHERE 1=1
                "; 
        
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$order;
        $this->query = $str;
        
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsDashboarding($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY TANGGAL DESC")
    {
        $str = "
                    SELECT 
                    INFORMASI_ID, DEPARTEMEN_ID, USER_LOGIN_ID, 
                       NAMA, KETERANGAN, 
                       LINK_FOTO, LINK_FILE, STATUS, 
                       LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
                       LAST_UPDATE_DATE, TO_CHAR(TANGGAL, 'DD.MM.YYYY') TANGGAL_KETERANGAN, TANGGAL, 
                       TO_CHAR(TANGGAL, 'DD.MM') TANGGAL_PENDEK
                    FROM INFORMASI A
                    WHERE 1=1
                "; 
        
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$order;
        $this->query = $str;
        
        return $this->selectLimit($str,$limit,$from); 
    }
    
    function selectByParamsDetil($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY TANGGAL DESC")
    {
        $str = "
                    SELECT 
                    INFORMASI_ID, DEPARTEMEN_ID, USER_LOGIN_ID, 
                       NAMA, KETERANGAN, 
                       LINK_FOTO, LINK_FILE, STATUS, 
                       LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
                       LAST_UPDATE_DATE, TO_CHAR(A.TANGGAL, 'YYYY-MM-DD HH24:MI') TANGGAL_KETERANGAN, TANGGAL
                    FROM INFORMASI A
                    WHERE 1=1
                "; 
        
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$order;
        $this->query = $str;
        
        return $this->selectLimit($str,$limit,$from); 
    }  
    
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
    {
        $str = "SELECT COUNT(INFORMASI_ID) AS ROWCOUNT FROM INFORMASI A
                WHERE 1=1 AND INFORMASI_ID IS NOT NULL ".$statement; 
        
        while(list($key,$val)=each($paramsArray))
        {
            $str .= " AND $key = '$val' ";
        }
        
        $this->select($str); 
        if($this->firstRow()) 
            return $this->getField("ROWCOUNT"); 
        else 
            return 0; 
    }
    
    function getCountByParamsMonitoring($paramsArray=array(), $statement="")
    {
        $str = "SELECT COUNT(INFORMASI_ID) AS ROWCOUNT FROM INFORMASI A
                LEFT JOIN DEPARTEMEN@PJBS_PRESENSI B ON B.DEPARTEMEN_ID = A.DEPARTEMEN_ID
                LEFT JOIN USER_LOGIN@PJBS_PRESENSI C ON C.USER_LOGIN_ID = A.USER_LOGIN_ID
                WHERE 1=1 AND INFORMASI_ID IS NOT NULL ".$statement; 
        
        while(list($key,$val)=each($paramsArray))
        {
            $str .= " AND $key = '$val' ";
        }
        
        $this->select($str); 
        if($this->firstRow()) 
            return $this->getField("ROWCOUNT"); 
        else 
            return 0; 
    }

  } 
?>