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

  class IssuePoType  extends Entity{

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function IssuePoType()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("ISSUE_PO_TYPE_ID", $this->getNextId("ISSUE_PO_TYPE_ID","ISSUE_PO_TYPE")); 

    	$str = "INSERT INTO ISSUE_PO_TYPE (ISSUE_PO_TYPE_ID, TYPE, NAME, DESCRIPTION, CREATED_BY, CREATED_DATE)VALUES (
	    	'".$this->getField("ISSUE_PO_TYPE_ID")."',
	    	'".$this->getField("TYPE")."',
	    	'".$this->getField("NAME")."',
	    	'".$this->getField("DESCRIPTION")."',
	    	'".$this->USERNAME."',
	    	now()
	    	
	    )";

	    $this->id = $this->getField("ISSUE_PO_TYPE_ID");
	    $this->query= $str;
			// echo $str;exit();
	    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE ISSUE_PO_TYPE
		SET    
		ISSUE_PO_TYPE_ID ='".$this->getField("ISSUE_PO_TYPE_ID")."',
		TYPE ='".$this->getField("TYPE")."',
		NAME ='".$this->getField("NAME")."',
		DESCRIPTION ='".$this->getField("DESCRIPTION")."',
		
		UPDATED_BY ='".$this->USERNAME."',
		UPDATED_DATE =now()
		WHERE ISSUE_PO_TYPE_ID= '".$this->getField("ISSUE_PO_TYPE_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM ISSUE_PO_TYPE
		WHERE ISSUE_PO_TYPE_ID= '".$this->getField("ISSUE_PO_TYPE_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.ISSUE_PO_TYPE_ID ASC")
	{
		$str = "
		SELECT A.ISSUE_PO_TYPE_ID,A.TYPE,A.NAME,A.DESCRIPTION,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
		FROM ISSUE_PO_TYPE A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM ISSUE_PO_TYPE A WHERE 1=1 ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = 	'$val' ";
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
