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
 * Entity-base class untuk mengimplementasikan tabel FORUM_KATEGORI.
 * 
 ***/
include_once(APPPATH . '/models/Entity.php');

class Neraca extends Entity
{

    var $query;
    /**
     * Class constructor.
     **/
    function Neraca()
    {
        $this->Entity();
    }

    function insert()
    {
        /*Auto-generate primary key(s) by next max value (integer) */
        $this->setField("NERACA_ID", $this->getNextId("NERACA_ID", "NERACA"));

        $str = "INSERT INTO NERACA (
					   NERACA_ID, BULAN)
 			  	VALUES (
				  " . $this->getField("NERACA_ID") . ",
				  '" . $this->getField("BULAN") . "'
				)";
        //echo $str;
        $this->query = $str;
        return $this->execQuery($str);
    }

    function update()
    {
        $str = "UPDATE NERACA
				SET    
					   BULAN = '" . $this->getField("BULAN") . "'
				WHERE  NERACA_ID = " . $this->getField("NERACA_ID") . "

			 ";
        $this->query = $str;
        return $this->execQuery($str);
    }

    function delete()
    {
        $str = "DELETE FROM NERACA
                WHERE 
                  NERACA_ID = " . $this->getField("NERACA_ID") . "
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
    function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $Order = "ORDER BY BULAN DESC")
    {
        $str = "SELECT 
					A.NERACA_ID, A.BULAN
					FROM NERACA A
                where A.NERACA_ID IS NOT NULL
				";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        $str .= $Order;

        $this->query = $str;
        return $this->selectLimit($str, $limit, $from);
    }

    // function selectByParamsParent($paramsArray = array(), $limit = -1, $from = -1, $statement = "")
    // {
    //     $str = "SELECT 
    // 				FORUM_KATEGORI_PARENT_ID, (array_agg(NAMA))[1] AS NAMA, (array_agg(KETERANGAN))[1] AS KETERANGAN
    // 				FROM FORUM_KATEGORI WHERE FORUM_KATEGORI_ID IS NOT NULL
    // 			";

    //     while (list($key, $val) = each($paramsArray)) {
    //         $str .= " AND $key = '$val' ";
    //     }

    //     $str .= $statement . " GROUP BY FORUM_KATEGORI_PARENT_ID";

    //     $str .= " ORDER BY NAMA ASC";
    //     $this->query = $str;
    //     return $this->selectLimit($str, $limit, $from);
    // }

    // function getNavigasi($kategori_id, $forum_id = 0, $tampil_akhir = 0)
    // {
    //     $str = " SELECT AMBIL_NAVIGASI_FORUM_KATEGORI('" . $kategori_id . "', '" . $forum_id . "', '" . $tampil_akhir . "') NAVIGASI FROM DUAL ";


    //     $this->select($str);
    //     if ($this->firstRow())
    //         return $this->getField("NAVIGASI");
    //     else
    //         return "";
    // }

    // function selectByParamsLike($paramsArray = array(), $limit = -1, $from = -1, $statement = "")
    // {
    //     $str = "	SELECT 
    // 				FORUM_KATEGORI_ID, FORUM_KATEGORI_PARENT_ID, NAMA, KETERANGAN
    // 				FROM FORUM_KATEGORI WHERE FORUM_KATEGORI_ID IS NOT NULL
    // 		    ";

    //     while (list($key, $val) = each($paramsArray)) {
    //         $str .= " AND $key LIKE '%$val%' ";
    //     }

    //     $this->query = $str;
    //     $str .= $statement . " ORDER BY FORUM_KATEGORI_PARENT_ID ASC";
    //     return $this->selectLimit($str, $limit, $from);
    // }
    /** 
     * Hitung jumlah record berdasarkan parameter (array). 
     * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
     * @return long Jumlah record yang sesuai kriteria 
     **/
    function getCountByParams($paramsArray = array(), $statement = "")
    {
        $str = "SELECT COUNT(A.NERACA_ID) AS ROWCOUNT FROM NERACA A
					WHERE A.NERACA_ID IS NOT NULL";

        while (list($key, $val) = each($paramsArray)) {
            $str .= " AND $key = '$val' ";
        }

        $this->select($str);
        if ($this->firstRow())
            return $this->getField("ROWCOUNT");
        else
            return 0;
    }

    // function getCountByParamsLike($paramsArray = array(), $statement = "")
    // {
    //     $str = "SELECT COUNT(FORUM_KATEGORI_ID) AS ROWCOUNT FROM FORUM_KATEGORI
    // 	        WHERE FORUM_KATEGORI_ID IS NOT NULL " . $statement;
    //     while (list($key, $val) = each($paramsArray)) {
    //         $str .= " AND $key LIKE '%$val%' ";
    //     }

    //     $this->select($str);
    //     if ($this->firstRow())
    //         return $this->getField("ROWCOUNT");
    //     else
    //         return 0;
    // }
}
