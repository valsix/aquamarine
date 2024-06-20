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

  class OfferProject extends Entity{

	var $query;
    /**
    * Class constructor.
    **/
    function OfferProject()
	{
      $this->Entity();
    }

    function insert()
    {
    	$this->setField("OFFER_PROJECT_ID", $this->getNextId("OFFER_PROJECT_ID","OFFER_PROJECT")); 

    	$str = "
		INSERT INTO OFFER_PROJECT (
			OFFER_PROJECT_ID, OFFER_ID, CATEGORY, 
			DESCRIPTION, QUANTITY, DURATION, UOM,
			PRICE, TOTAL, CREATED_BY, CREATED_DATE
		)
		VALUES (
	    	'".$this->getField("OFFER_PROJECT_ID")."','".$this->getField("OFFER_ID")."','".$this->getField("CATEGORY")."',
	    	'".$this->getField("DESCRIPTION")."',".$this->getField("QUANTITY").",".$this->getField("DURATION").",'".$this->getField("UOM")."',
	    	".$this->getField("PRICE").",".$this->getField("TOTAL").",'".$this->getField("CREATED_BY")."',CURRENT_DATE
    	)
	    ";

	    $this->id = $this->getField("OFFER_PROJECT_ID");
	    $this->query= $str;
			// echo $str;exit();
	    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE OFFER_PROJECT
		SET    
			OFFER_ID 		= '".$this->getField("OFFER_ID")."',
			CATEGORY 		= '".$this->getField("CATEGORY")."',
			DESCRIPTION 	= '".$this->getField("DESCRIPTION")."',
			QUANTITY 		= ".$this->getField("QUANTITY").",
			DURATION 		= ".$this->getField("DURATION").",
			UOM 			= '".$this->getField("UOM")."',
			PRICE 			= ".$this->getField("PRICE").",
			TOTAL 			= ".$this->getField("TOTAL").",
			UPDATED_BY 		= '".$this->getField("UPDATED_BY")."',
			UPDATED_DATE 	= CURRENT_DATE
		WHERE OFFER_PROJECT_ID= '".$this->getField("OFFER_PROJECT_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM OFFER_PROJECT
		WHERE OFFER_PROJECT_ID= '".$this->getField("OFFER_PROJECT_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}
	function deleteParent($statement= "")
	{
		$str = "DELETE FROM OFFER_PROJECT
		WHERE OFFER_ID= '".$this->getField("OFFER_ID")."'"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.OFFER_PROJECT_ID ASC")
	{
		$str = "
		SELECT OFFER_PROJECT_ID, OFFER_ID, CATEGORY, 
			DESCRIPTION, QUANTITY, DURATION, UOM,
			PRICE, TOTAL
		FROM OFFER_PROJECT A
		WHERE 1=1 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}

	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM OFFER_PROJECT A WHERE 1=1 ".$statement;
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
