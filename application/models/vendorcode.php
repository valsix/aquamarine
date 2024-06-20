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
  * Entity-base class untuk mengimplementasikan tabel AGAMA.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class VendorCode extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function VendorCode()
		{
      parent::__construct(); 
    }

		    function insert()
		    {
		    	$this->setField("VENDOR_CODE_ID", $this->getNextId("VENDOR_CODE_ID","VENDOR_CODE")); 

		    	$str = "INSERT INTO VENDOR_CODE (VENDOR_CODE_ID, KODE, TYPE, AREA, SUPPLIER_ID, TAHUN, REV, STATUS_AKTIF, CREATED_BY, CREATED_DATE)VALUES (
		    	'".$this->getField("VENDOR_CODE_ID")."',
		    	'".$this->getField("KODE")."',
		    	'".$this->getField("TYPE")."',
		    	'".$this->getField("AREA")."',
		    	'".$this->getField("SUPPLIER_ID")."',
		    	'".$this->getField("TAHUN")."',
		    	'".$this->getField("REV")."',
		    	'".$this->getField("STATUS_AKTIF")."',
		    	'".$this->USERID."',
		    	CURRENT_DATE
		    	
		    )";

		    $this->id = $this->getField("VENDOR_CODE_ID");
		    $this->query= $str;
				// echo $str;exit();
		    return $this->execQuery($str);
		}

		function updateStatus()
		{
			$str = "
			UPDATE VENDOR_CODE
			SET    
		
			
			STATUS_AKTIF ='0',
			
			UPDATED_BY ='".$this->USERID."',
			UPDATED_DATE =CURRENT_DATE
			WHERE SUPPLIER_ID= '".$this->getField("SUPPLIER_ID")."'";
			$this->query = $str;
				  // echo $str;exit;
			return $this->execQuery($str);
		}

		function update()
		{
			$str = "
			UPDATE VENDOR_CODE
			SET    
			VENDOR_CODE_ID ='".$this->getField("VENDOR_CODE_ID")."',
			KODE ='".$this->getField("KODE")."',
			TYPE ='".$this->getField("TYPE")."',
			AREA ='".$this->getField("AREA")."',
			SUPPLIER_ID ='".$this->getField("SUPPLIER_ID")."',
			TAHUN ='".$this->getField("TAHUN")."',
			REV ='".$this->getField("REV")."',
			STATUS_AKTIF ='".$this->getField("STATUS_AKTIF")."',
			
			UPDATED_BY ='".$this->USERID."',
			UPDATED_DATE =CURRENT_DATE
			WHERE VENDOR_CODE_ID= '".$this->getField("VENDOR_CODE_ID")."'";
			$this->query = $str;
				  // echo $str;exit;
			return $this->execQuery($str);
		}
		function delete($statement= "")
		{
			$str = "DELETE FROM VENDOR_CODE
			WHERE VENDOR_CODE_ID= '".$this->getField("VENDOR_CODE_ID").""; 
			$this->query = $str;
				  // echo $str;exit();
			return $this->execQuery($str);
		}
		function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.VENDOR_CODE_ID ASC")
		{
			$str = "
			SELECT A.VENDOR_CODE_ID,A.KODE,A.TYPE,A.AREA,A.SUPPLIER_ID,A.TAHUN,A.REV,A.STATUS_AKTIF,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE
			FROM VENDOR_CODE A
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
			$str = "SELECT COUNT(1) AS ROWCOUNT FROM VENDOR_CODE A WHERE 1=1 ".$statement;
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