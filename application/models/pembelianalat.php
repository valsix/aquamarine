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

class PembelianAlat extends Entity
{

	var $query;
	/**
	 * Class constructor.
	 **/
	function PembelianAlat()
	{
		$this->Entity();
	}

			function insert()
			{
				$this->setField("PEMBELIAN_ALAT_ID", $this->getNextId("PEMBELIAN_ALAT_ID","PEMBELIAN_ALAT")); 

				$str = "INSERT INTO PEMBELIAN_ALAT ( PEMBELIAN_ALAT_ID, PEMBELIAN_ID, PEMBELIAN_DETAIL_ID,  HARGA, QTY,        TOTAL, KETERANGAN_TOTAL, NAMA_ALAT,SERIAL_NUMBER,DESKIPSI,CREATED_BY, CREATED_DATE)VALUES (
				'".$this->getField("PEMBELIAN_ALAT_ID")."',
				'".$this->getField("PEMBELIAN_ID")."',
				'".$this->getField("PEMBELIAN_DETAIL_ID")."',
				
				'".$this->getField("HARGA")."',
				'".$this->getField("QTY")."',
				'".$this->getField("TOTAL")."',
				'".$this->getField("KETERANGAN_TOTAL")."',
				'".$this->getField("NAMA_ALAT")."',
				'".$this->getField("SERIAL_NUMBER")."',
				'".$this->getField("DESKIPSI")."',
				'".$this->USERID."',
				CURRENT_DATE
				
			)";

			$this->id = $this->getField("PEMBELIAN_ALAT_ID");
			$this->query= $str;
				// echo $str;exit();
			return $this->execQuery($str);
		}

		function update()
		{
			$str = "
			UPDATE PEMBELIAN_ALAT
			SET    
			PEMBELIAN_ALAT_ID ='".$this->getField("PEMBELIAN_ALAT_ID")."',
			PEMBELIAN_ID ='".$this->getField("PEMBELIAN_ID")."',
			PEMBELIAN_DETAIL_ID ='".$this->getField("PEMBELIAN_DETAIL_ID")."',
			HARGA ='".$this->getField("HARGA")."',
			DESKIPSI ='".$this->getField("DESKIPSI")."',
			QTY ='".$this->getField("QTY")."',
			TOTAL ='".$this->getField("TOTAL")."',
			KETERANGAN_TOTAL ='".$this->getField("KETERANGAN_TOTAL")."',
			NAMA_ALAT ='".$this->getField("NAMA_ALAT")."',
			SERIAL_NUMBER ='".$this->getField("SERIAL_NUMBER")."',
			UPDATED_BY ='".$this->USERID."',
			UPDATED_DATE =CURRENT_DATE
			WHERE PEMBELIAN_ALAT_ID= '".$this->getField("PEMBELIAN_ALAT_ID")."'";
			$this->query = $str;
				  // echo $str;exit;
			return $this->execQuery($str);
		}
		function delete($statement= "")
		{
			$str = "DELETE FROM PEMBELIAN_ALAT
			WHERE PEMBELIAN_ALAT_ID= '".$this->getField("PEMBELIAN_ALAT_ID")."'"; 
			$this->query = $str;
				  // echo $str;exit();
			return $this->execQuery($str);
		}
		function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PEMBELIAN_ALAT_ID ASC")
		{
			$str = "
			SELECT A.PEMBELIAN_ALAT_ID,A.PEMBELIAN_ID,A.EQUIP_ID,A.CURRENCY,A.HARGA,A.QTY,A.TOTAL,A.KETERANGAN_TOTAL,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.PEMBELIAN_DETAIL_ID,A.NAMA_ALAT,A.SERIAL_NUMBER, REPLACE(LOWER(nama_alat), ' ', '_') || serial_number as code,A.DESKIPSI
			FROM PEMBELIAN_ALAT A
			WHERE 1=1 ";
			while(list($key,$val) = each($paramsArray))
			{
				$str .= " AND $key = '$val'";
			}

			$str .= $statement." ".$order;
			$this->query = $str;
			return $this->selectLimit($str,$limit,$from); 
		}

		function selectByParamsMonitoringKelola($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PEMBELIAN_ALAT_ID ASC")
		{
			$str = "
			SELECT A.PEMBELIAN_ALAT_ID,A.PEMBELIAN_ID,A.EQUIP_ID,A.CURRENCY,A.HARGA,A.QTY,A.TOTAL,A.KETERANGAN_TOTAL,A.CREATED_BY,A.CREATED_DATE,A.UPDATED_BY,A.UPDATED_DATE,A.PEMBELIAN_DETAIL_ID,A.NAMA_ALAT,A.SERIAL_NUMBER, REPLACE(LOWER(A.nama_alat), ' ', '_') || A.serial_number || B.EC_ID as code,C.ec_name,b.nama_alat nama_part,E.NAME NAMA_SUPPLIER,D.NO_PEMBELIAN,D.TANGGAL_BAYAR,A.DESKIPSI,B.SERIAL_NUMBER SERIAL_EQUIP,B.EC_ID
			FROM PEMBELIAN_ALAT A
			LEFT JOIN PEMBELIAN_DETAIL B ON B.PEMBELIAN_DETAIL_ID  = A.PEMBELIAN_DETAIL_ID
			LEFT JOIN EQUIP_CATEGORY C ON C.EC_ID = B.EC_ID
			LEFT JOIN PEMBELIAN D ON D.PEMBELIAN_ID = B.PEMBELIAN_ID
			LEFT JOIN COMPANY E ON E.COMPANY_ID = D.COMPANY_ID
			WHERE 1=1 ";
			while(list($key,$val) = each($paramsArray))
			{
				$str .= " AND $key = '$val'";
			}

			$str .= $statement." ".$order;
			$this->query = $str;
			return $this->selectLimit($str,$limit,$from); 
		}

		function selectByParamsMonitoringBaru($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PEMBELIAN_ID ASC")
		{
			$str = "
			SELECT D.*,B.NAME NAMA_SUPPLIER,B.ADDRESS,B.CP1_NAME,A.JENIS_PEMBAYARAN,A.NO_PO,C.NAMA NAMA_PROJECT,A.TANGGAL
			FROM DETAIL_PEMBELIAN D
			LEFT JOIN  PEMBELIAN A ON A.PEMBELIAN_ID = D.PEMBELIAN_ID
			LEFT JOIN COMPANY  B ON B.COMPANY_ID = A.COMPANY_ID
			LEFT JOIN MASTER_PROJECT C ON A.MASTER_PROJECT_ID = C.MASTER_PROJECT_ID
			WHERE 1=1  AND A.STATUS_DELETE IS NULL ";
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
			$str = "SELECT COUNT(1) AS ROWCOUNT FROM PEMBELIAN_ALAT A WHERE 1=1 ".$statement;
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
