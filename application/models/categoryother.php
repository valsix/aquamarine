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
  include_once(APPPATH.'/models/Entity.php');

  class CategoryOther  extends Entity{

	var $query;
  var $id;
    /**
    * Class constructor.
    **/
    function CategoryOther()
	{
      $this->Entity();
    }

    function insert()
    {
      $this->setField("CATEGORY_ID", $this->getNextId("CATEGORY_ID","CATEGORY_OTHER")); 

      $str = "INSERT INTO CATEGORY_OTHER (CATEGORY_ID, CATEGORY, DESCRIPTION)VALUES (
      '".$this->getField("CATEGORY_ID")."',
      '".$this->getField("CATEGORY")."',
      '".$this->getField("DESCRIPTION")."' 
    )";

    $this->id = $this->getField("CATEGORY_ID");
    $this->query= $str;
    // echo $str;exit();
    return $this->execQuery($str);
  }

  function update()
  {
    $str = "
    UPDATE CATEGORY_OTHER
    SET    
    CATEGORY_ID ='".$this->getField("CATEGORY_ID")."',
    CATEGORY ='".$this->getField("CATEGORY")."',
    DESCRIPTION ='".$this->getField("DESCRIPTION")."' 
    WHERE CATEGORY_ID= '".$this->getField("CATEGORY_ID")."'";
    $this->query = $str;
      // echo $str;exit;
    return $this->execQuery($str);
  }

  function delete($statement= "")
  {
    $str = "DELETE FROM CATEGORY_OTHER
    WHERE CATEGORY_ID= '".$this->getField("CATEGORY_ID")."'"; 
    $this->query = $str;
      // echo $str;exit();
    return $this->execQuery($str);
  }

  function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.CATEGORY_ID ASC")
  {
    $str = "
    SELECT A.CATEGORY_ID,A.CATEGORY,A.DESCRIPTION
    FROM CATEGORY_OTHER A
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
    $str = "SELECT COUNT(1) AS ROWCOUNT FROM CATEGORY_OTHER A WHERE 1=1 ".$statement;
    while(list($key,$val)=each($paramsArray))
    {
      $str .= " AND $key =  '$val' ";
    }
    $this->query = $str;
    $this->select($str); 
    if($this->firstRow()) 
      return $this->getField("ROWCOUNT"); 
    else 
      return 0; 
  }
	
  }
?>
