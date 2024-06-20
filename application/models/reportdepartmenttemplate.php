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

  class ReportDepartmentTemplate extends Entity{

	var $query;
    /**
    * Class constructor.
    **/
    function ReportDepartmentTemplate()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("REPORT_DEPARTMENT_TEMPLATE_ID", $this->getNextId("REPORT_DEPARTMENT_TEMPLATE_ID","REPORT_DEPARTMENT_TEMPLATE")); 

    	$str = "INSERT INTO REPORT_DEPARTMENT_TEMPLATE (REPORT_DEPARTMENT_TEMPLATE_ID, NAMA, KETERANGAN, LINK, CREATED_BY, CREATED_DATE)VALUES (
    	'".$this->getField("REPORT_DEPARTMENT_TEMPLATE_ID")."',
    	'".$this->getField("NAMA")."',
    	'".$this->getField("KETERANGAN")."',
    	'".$this->getField("LINK")."',
    	'".$this->getField("CREATED_BY")."',
    	CURRENT_DATE
    )";

    $this->id = $this->getField("REPORT_DEPARTMENT_TEMPLATE_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE REPORT_DEPARTMENT_TEMPLATE
		SET    
			NAMA 			='".$this->getField("NAMA")."',
			KETERANGAN 		='".$this->getField("KETERANGAN")."',
			LINK 		='".$this->getField("LINK")."', 
			UPDATED_BY 		='".$this->getField("UPDATED_BY")."',
			UPDATED_DATE 	=CURRENT_DATE
		WHERE REPORT_DEPARTMENT_TEMPLATE_ID= '".$this->getField("REPORT_DEPARTMENT_TEMPLATE_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM REPORT_DEPARTMENT_TEMPLATE
		WHERE REPORT_DEPARTMENT_TEMPLATE_ID= '".$this->getField("REPORT_DEPARTMENT_TEMPLATE_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.REPORT_DEPARTMENT_TEMPLATE_ID ASC")
	{
		$str = "
		SELECT A.REPORT_DEPARTMENT_TEMPLATE_ID,A.NAMA,A.KETERANGAN,A.LINK
		FROM REPORT_DEPARTMENT_TEMPLATE A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM REPORT_DEPARTMENT_TEMPLATE A WHERE 1=1 ".$statement;
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
