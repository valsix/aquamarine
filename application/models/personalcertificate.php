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
include_once(APPPATH . '/models/Entity.php');

class PersonalCertificate  extends Entity
{

	var $query;
	var $id;
	/**
	 * Class constructor.
	 **/
	function PersonalCertificate()
	{
		$this->Entity();
	}

	function insert()
	{
		$this->setField("CERTIFICATE_ID", $this->getNextId("CERTIFICATE_ID","PERSONAL_CERTIFICATE")); 

		$str = "INSERT INTO PERSONAL_CERTIFICATE (CERTIFICATE_ID, CERTIFICATE, DESCRIPTION)VALUES (
		'".$this->getField("CERTIFICATE_ID")."',
		'".$this->getField("CERTIFICATE")."',
		'".$this->getField("DESCRIPTION")."' 
	)";

	$this->id = $this->getField("CERTIFICATE_ID");
	$this->query= $str;
		// echo $str;exit();
	return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE PERSONAL_CERTIFICATE
		SET    
		CERTIFICATE_ID ='".$this->getField("CERTIFICATE_ID")."',
		CERTIFICATE ='".$this->getField("CERTIFICATE")."',
		DESCRIPTION ='".$this->getField("DESCRIPTION")."' 
		WHERE CERTIFICATE_ID= '".$this->getField("CERTIFICATE_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM PERSONAL_CERTIFICATE
		WHERE CERTIFICATE_ID= '".$this->getField("CERTIFICATE_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.CERTIFICATE_ID ASC")
	{
		$str = "
		SELECT A.CERTIFICATE_ID,A.CERTIFICATE,A.DESCRIPTION
		FROM PERSONAL_CERTIFICATE A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM PERSONAL_CERTIFICATE A WHERE 1=1 ".$statement;
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
