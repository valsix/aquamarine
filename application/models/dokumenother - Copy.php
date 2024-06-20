<?
/* *******************************************************************************************************
MODUL NAME 			: MTSN LAWANG
FILE NAME 			:
AUTHOR				:
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			:
***************************************************************************************************** */

/***
 * Entity-base class untuk mengimplementasikan tabel kategori.
 *
 ***/
include_once("Entity.php");

class DokumenOther   extends Entity
{

  var $query;
  var $id;
  /**
   * Class constructor.
   **/
  function DokumenOther()
  {
    $this->Entity();
  }

  function insert()
  {
    $this->setField("DOCUMENT_ID", $this->getNextId("DOCUMENT_ID", "DOKUMEN_OTHER"));

    $str = "INSERT INTO DOKUMEN_OTHER (DOCUMENT_ID, CATEGORY_ID, NAME, DESCRIPTION, PATH, LAST_REVISI)VALUES (
            '".$this->getField("DOCUMENT_ID")."',
            '".$this->getField("CATEGORY_ID")."',
            '".$this->getField("NAME")."',
            '".$this->getField("DESCRIPTION")."',
            '".$this->getField("PATH")."',
            now();
    )";

    $this->id = $this->getField("DOCUMENT_ID");
    $this->query = $str;
    // echo $str;exit();
    return $this->execQuery($str);
  }

  function update()
  {
    $str = "
    UPDATE DOKUMEN_OTHER
    SET    
    DOCUMENT_ID ='".$this->getField("DOCUMENT_ID")."',
    CATEGORY_ID ='".$this->getField("CATEGORY_ID") ."',
    NAME ='" . $this->getField("NAME") . "',
    DESCRIPTION ='".$this->getField("DESCRIPTION")."',
    PATH ='" . $this->getField("PATH") . "',
    LAST_REVISI =now()
    WHERE DOCUMENT_ID= '".$this->getField("DOCUMENT_ID")."'";
    $this->query = $str;
    // echo $str;exit;
    return $this->execQuery($str);
  }

  function update_path()
  {
    $str = "
    UPDATE DOKUMEN_OTHER
    SET    
   
    PATH ='" . $this->getField("PATH") . "
   
    WHERE DOCUMENT_ID= '".$this->getField("DOCUMENT_ID")."'";
    $this->query = $str;
    // echo $str;exit;
    return $this->execQuery($str);
  }


  function delete($statement = "")
  {
    $str = "DELETE FROM DOKUMEN_OTHER
            WHERE DOCUMENT_ID='".$this->getField("DOCUMENT_ID") . "'";
    $this->query = $str;
    // echo $str;exit();
    return $this->execQuery($str);
  }


  function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
  {
    $str = "SELECT A.DOCUMENT_ID,A.CATEGORY_ID,A.NAME,A.DESCRIPTION,A.PATH,A.LAST_REVISI,B.CATEGORY NAMA_CATEGORI
            FROM DOKUMEN_OTHER A,CATEGORY_OTHER B
            WHERE 1=1 AND A.CATEGORY_ID= B.CATEGORY_ID ";
    while (list($key, $val) = each($paramsArray)) {
      $str .= " AND $key = '$val'";
    }

    $str .= $statement . " " . $order;
    $this->query = $str;
    return $this->selectLimit($str, $limit, $from);
  }


  function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
  {
    $str = "SELECT A.DOCUMENT_ID,A.CATEGORY_ID,A.NAME,A.DESCRIPTION,A.PATH,A.LAST_REVISI
            FROM DOKUMEN_OTHER A
            WHERE 1=1 ";
    while (list($key, $val) = each($paramsArray)) {
      $str .= " AND $key = '$val'";
    }

    $str .= $statement . " " . $order;
    $this->query = $str;
    return $this->selectLimit($str, $limit, $from);
  }


  function selectByParamsCetakPdf($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "ORDER BY A.DOCUMENT_ID ASC")
  {
    $str = "SELECT A.DOCUMENT_ID,A.CATEGORY_ID,A.NAME,A.DESCRIPTION,A.PATH,A.LAST_REVISI
            FROM DOKUMEN_OTHER A
            WHERE 1=1 ";
    while (list($key, $val) = each($paramsArray)) {
      $str .= " AND $key = '$val'";
    }

    $str .= $statement . " " . $order;
    $this->query = $str;
    return $this->selectLimit($str, $limit, $from);
  }


  function getCountByParams($paramsArray = array(), $statement = "")
  {
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM DOKUMEN_OTHER A WHERE 1=1 " . $statement;
    while (list($key, $val) = each($paramsArray)) {
      $str .= " AND $key =  '$val' ";
    }
    $str .= $statement . " " . $order;
    $this->query = $str;
    $this->select($str);
    if ($this->firstRow())
      return $this->getField("ROWCOUNT");
    else
      return 0;
  }


  function getCountByParamsMonitoring($paramsArray = array(), $statement = "")
  {
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM DOKUMEN_OTHER A,CATEGORY_OTHER B
    WHERE 1=1 AND A.CATEGORY_ID= B.CATEGORY_ID  " . $statement;
    while (list($key, $val) = each($paramsArray)) {
      $str .= " AND $key =  '$val' ";
    }
    $str .= $statement . " " . $order;
    $this->query = $str;
    $this->select($str);
    if ($this->firstRow())
      return $this->getField("ROWCOUNT");
    else
      return 0;
  }
}
