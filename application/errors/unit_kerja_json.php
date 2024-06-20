<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class unit_kerja_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}       
		
		/* GLOBAL VARIABLE */
		$this->ID = $this->kauth->getInstance()->getIdentity()->REKANAN_ID;   
	}
	
	function json() 
	{
			$this->load->library("crfs_protect"); $csrf = new crfs_protect('unit_kerja_json/json');
		if (!$csrf->isTokenValid($this->input->get("reqToken")))
			exit();
			
		$this->load->model("UnitKerja");
		$unit_kerja = new UnitKerja();
		
		$reqKeterangan = $this->input->post("reqKeterangan");
		$reqId = $this->input->get("reqId");
		$reqSearch = $this->input->post("reqSearch");
		$reqAgamaId = $this->input->post("reqAgamaId");
		
		$aColumns 			= array('UNIT_KERJA_ID', 'KODE', 'NAMA', 'ALAMAT', 'LOKASI', 'TELEPON', 'FAX', 'EMAIL');
		$aColumnsAlias		= array('UNIT_KERJA_ID', 'KODE', 'NAMA', 'ALAMAT', 'LOKASI', 'TELEPON', 'FAX', 'EMAIL');
		
		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 1)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY UNIT_KERJA_ID desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY UNIT_KERJA_ID ASC";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual aColumns filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else
			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			$dsplyRange = 2147483645;
		}
		
		$statement = "AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $unit_kerja->getCountByParams(array(), $statement, $sOrder);
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $unit_kerja->getCountByParams(array(), $statement, $sOrder);

		$unit_kerja->selectByParams(array(), $dsplyRange, $dsplyStart, $statement, $sOrder);
		
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($unit_kerja->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i]=='NO')		$row[] = $number;
					elseif($aColumns[$i]=='TANGGAL' || $aColumns[$i]=='TANGGAL_MULAI' || $aColumns[$i]=='TANGGAL_AKHIR')	$row[] = getFormattedDateJson($unit_kerja->getField(trim($aColumns[$i])));
					elseif($aColumns[$i]=='STATUS'){
						if( $unit_kerja->getField(trim($aColumns[$i])) == 1)	$st = 'Berlaku';					
						else												$st = 'Tidak Berlaku';				
						$row[] = $st;
					}
					elseif($aColumns[$i]=='UNIT_KERJA')	$row[] = $unit_kerja->getField(trim($aColumns[$i]))."*".$unit_kerja->getField("SK_PANITIA_ID");
					else	$row[] = $unit_kerja->getField(trim($aColumns[$i]));
			}
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	
	}	
	
	function unit_kerja_pic_json() 
	{
		$this->load->library("crfs_protect"); $csrf = new crfs_protect('unit_kerja_json/unit_kerja_pic_json');
		if (!$csrf->isTokenValid($this->input->get("reqToken")))
			exit();
			
		$this->load->model("UnitKerja");
		$unit_kerja = new UnitKerja();
		
		$reqKeterangan = $this->input->post("reqKeterangan");
		$reqId = $this->input->post("reqId");
		$reqSearch = $this->input->post("reqSearch");
		
		$aColumns 			= array('UNIT_KERJA_ID', 'UNIT_KERJA', 'JUMLAH');
		$aColumnsAlias		= array('UNIT_KERJA_ID', 'UNIT_KERJA', 'JUMLAH');
		
		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 1)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY unit_kerja_ELIMINASI desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.unit_kerja asc";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else
			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			$dsplyRange = 2147483645;
		}
		
		$statement = "AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $unit_kerja->getCountByParams(array(), $statement);
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $unit_kerja->getCountByParams(array(), $statement);

		$unit_kerja->selectByParamsMonitoringPic(array(), $dsplyRange, $dsplyStart, $statement, $sOrder);
		
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($unit_kerja->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{	
				if($aColumns[$i]=='NO')	$row[] = $number;
					else				$row[] = $unit_kerja->getField(trim($aColumns[$i]));
			}
			
			$output['aaData'][] = $row;
		}
		echo json_encode( $output );	
	}
	
	function add() 
	{
		$this->load->library("crfs_protect"); $csrf = new crfs_protect('_crfs_master_unit_kerja_add');
		if (!$csrf->isTokenValid($_POST['_crfs_master_unit_kerja_add']))
			exit();
			
		$this->load->model('UnitKerja');
		$this->load->library("FileHandler");
		$file = new FileHandler();
		
		$unit_kerja	= new UnitKerja();
		$FILE_DIR = "uploads/logo/";
		$reqId		= $this->input->post('reqId');
		$reqMode	= $this->input->post('reqMode');
		$reqKode	= $this->input->post('reqKode');
		$reqNama	= $this->input->post('reqNama');
		$reqAlamat	= $this->input->post('reqAlamat');
		$reqLokasi	= $this->input->post('reqLokasi');
		$reqTelepon	= $this->input->post('reqTelepon');
		$reqKeterangan	= $this->input->post('reqKeterangan');
		$reqFax	= $this->input->post('reqFax');
		$reqEmail	= $this->input->post('reqEmail');
		$reqUnitKerjaParentId	= $this->input->post('reqUnitKerjaParentId');
		$reqLinkFileLogoPerusahaan			= $_FILES['reqLinkFileLogoPerusahaan'];
		$reqLinkFileLogoPerusahaanTemp		= $this->input->post("reqLinkFileLogoPerusahaanTemp");
		$reqLinkFileLogoPerusahaanTempNama	= $this->input->post("reqLinkFileLogoPerusahaanTempNama");
		$reqLinkFileLogoReport			= $_FILES['reqLinkFileLogoReport'];
		$reqLinkFileLogoReportTemp		= $this->input->post("reqLinkFileLogoReportTemp");
		$reqLinkFileLogoReportTempNama	= $this->input->post("reqLinkFileLogoReportTempNama");
		
		if($reqMode == "insert")
		{
			$unit_kerja	= new UnitKerja();
			$unit_kerja->setField("UNIT_KERJA_ID", $reqId);
			$unit_kerja->setField("KODE",$reqKode);
			$unit_kerja->setField("NAMA",$reqNama);
			$unit_kerja->setField("ALAMAT",$reqAlamat);
			$unit_kerja->setField("LOKASI", $reqLokasi);
			$unit_kerja->setField("TELEPON",$reqTelepon );
			$unit_kerja->setField("FAX",$reqFax );
			$unit_kerja->setField("EMAIL",$reqEmail );
			$unit_kerja->setField("KETERANGAN",$reqKeterangan );
			if($reqUnitKerjaParentId == "")
				$unit_kerja->setField("UNIT_KERJA_PARENT_ID", "0" );
			else
				$unit_kerja->setField("UNIT_KERJA_PARENT_ID", $reqUnitKerjaParentId);
				
			$renameFileLogo = "logo"."-".md5(date("dmYHis").$reqLinkFileLogoPerusahaan['name'].$this->ID).".".getExtension($reqLinkFileLogoPerusahaan['name']);
			if($file->uploadToDir('reqLinkFileLogoPerusahaan', $FILE_DIR, $renameFileLogo))
			{
				$insertLinkFileLogo =  $renameFileLogo;
				$insertLinkFileLogoNama = $reqLinkFileLogoPerusahaan['name'];
			}
			else
			{
				$insertLinkFileLogo =  $reqLinkFileLogoPerusahaanTempNama;
				$insertLinkFileLogoNama = $reqLinkFileLogoPerusahaanTempNama;
			}
			/* END UPLOAD FILE */	
			$unit_kerja->setField("LOGO_PERUSAHAAN", $insertLinkFileLogo);
			
			$renameFile = "report"."-".md5(date("dmYHis").$reqLinkFileLogoReport['name'].$this->ID).".".getExtension($reqLinkFileLogoReport['name']);
			if($file->uploadToDir('reqLinkFileLogoReport', $FILE_DIR, $renameFile))
			{
				$insertLinkFile =  $renameFile;
				$insertLinkFileNama = $reqLinkFileLogoReport['name'];
			}
			else
			{
				$insertLinkFile =  $reqLinkFileLogoReportTemp;
				$insertLinkFileNama = $reqLinkFileLogoReportTempNama;
			}
			/* END UPLOAD FILE */	
			$unit_kerja->setField("LOGO_REPORT", $insertLinkFile);
			
			
			$unit_kerja->insert();
			
		}
		else
		{
			$unit_kerja	= new UnitKerja();
			$unit_kerja->setField("UNIT_KERJA_ID", $reqId);
			$unit_kerja->setField("KODE",$reqKode);
			$unit_kerja->setField("NAMA",$reqNama);
			$unit_kerja->setField("ALAMAT",$reqAlamat);
			$unit_kerja->setField("LOKASI", $reqLokasi);
			$unit_kerja->setField("TELEPON",$reqTelepon );
			$unit_kerja->setField("FAX",$reqFax );
			$unit_kerja->setField("EMAIL",$reqEmail );
			$unit_kerja->setField("KETERANGAN",$reqKeterangan );
			if($reqUnitKerjaParentId == "")
				$unit_kerja->setField("UNIT_KERJA_PARENT_ID", "0" );
			else
				$unit_kerja->setField("UNIT_KERJA_PARENT_ID", $reqUnitKerjaParentId);
				
				
			$renameFileLogo = "logo"."-".md5(date("dmYHis").$reqLinkFileLogoPerusahaan['name'].$this->ID).".".getExtension($reqLinkFileLogoPerusahaan['name']);
			if($file->uploadToDir('reqLinkFileLogoPerusahaan', $FILE_DIR, $renameFileLogo))
			{
				$insertLinkFileLogo =  $renameFileLogo;
				$insertLinkFileLogoNama = $reqLinkFileLogoPerusahaan['name'];
			}
			else
			{
				$insertLinkFileLogo =  $reqLinkFileLogoPerusahaanTempNama;
				$insertLinkFileLogoNama = $reqLinkFileLogoPerusahaanTempNama;
			}
			/* END UPLOAD FILE */	
			$unit_kerja->setField("LOGO_PERUSAHAAN", $insertLinkFileLogo);
			
			
			
			$renameFile = "report"."-".md5(date("dmYHis").$reqLinkFileLogoReport['name'].$this->ID).".".getExtension($reqLinkFileLogoReport['name']);
			if($file->uploadToDir('reqLinkFileLogoReport', $FILE_DIR, $renameFile))
			{
				$insertLinkFile =  $renameFile;
				$insertLinkFileNama = $reqLinkFileLogoReport['name'];
			}
			else
			{
				$insertLinkFile =  $reqLinkFileLogoReportTempNama;
				$insertLinkFileNama = $reqLinkFileLogoReportTempNama;
			}
			/* END UPLOAD FILE */	
			$unit_kerja->setField("LOGO_REPORT", $insertLinkFile);
			$unit_kerja->update();
		}
		
		echo "Data berhasil disimpan.";
	}
	
	function delete() 
	{
		$this->load->model('UnitKerja');
		
		$unit_kerja	= new UnitKerja();
		
		$reqId		= $this->input->get('reqId');
		
		$reqNama		= $this->input->post('reqNama');
		
		$unit_kerja	= new UnitKerja();
		$unit_kerja->setField("UNIT_KERJA_ID", $reqId);
		$unit_kerja->delete();
		
		echo "Data berhasil disimpan.";
	}
	
	function combo() 
	{
		$this->load->model('UnitKerja');
		$unit_kerja = new UnitKerja();
		
		$unit_kerja->selectByParams();
		
		$i = 0;
		while($unit_kerja->nextRow())
		{
			$arr_json[$i]['id']		= $unit_kerja->getField("UNIT_KERJA_ID");
			$arr_json[$i]['text']	= $unit_kerja->getField("NAMA");

			$arrTelp = explode(" ", trim($unit_kerja->getField("TELEPON")));
			$tempTelpPanitiaKode = $arrTelp[0];
			$tempTelpPanitia = $arrTelp[1];

			$arr_json[$i]['alamat']			= $unit_kerja->getField("ALAMAT");
			$arr_json[$i]['email']			= $unit_kerja->getField("EMAIL");
			$arr_json[$i]['telepon_kode']	= $tempTelpPanitiaKode;
			$arr_json[$i]['telepon']		= $tempTelpPanitia;
						
			$i++;
		}
		
		echo json_encode($arr_json);
	}	

	function combo_semua() 
	{
		$this->load->model('UnitKerja');
		$unit_kerja = new UnitKerja();
		
		$unit_kerja->selectByParams();

		$arr_json[0]['id']		= "";
		$arr_json[0]['text']	= "Semua";
					
		$i = 1;
		while($unit_kerja->nextRow())
		{
			$arr_json[$i]['id']		= $unit_kerja->getField("UNIT_KERJA_ID");
			$arr_json[$i]['text']	= $unit_kerja->getField("NAMA");

			$arrTelp = explode(" ", trim($unit_kerja->getField("TELEPON")));
			$tempTelpPanitiaKode = $arrTelp[0];
			$tempTelpPanitia = $arrTelp[1];

			$arr_json[$i]['alamat']			= $unit_kerja->getField("ALAMAT");
			$arr_json[$i]['email']			= $unit_kerja->getField("EMAIL");
			$arr_json[$i]['telepon_kode']	= $tempTelpPanitiaKode;
			$arr_json[$i]['telepon']		= $tempTelpPanitia;
						
			$i++;
		}
		
		echo json_encode($arr_json);
	}	
		
	function combo_parent() 
	{
		$this->load->model('UnitKerja');
		$unit_kerja = new UnitKerja();
		
		$statement = " AND UNIT_KERJA_PARENT_ID = 0 ";
		$unit_kerja->selectByParams(array(), -1,-1, $statement);
		
		$i = 0;
		while($unit_kerja->nextRow())
		{
			$arr_json[$i]['id']		= $unit_kerja->getField("UNIT_KERJA_ID");
			$arr_json[$i]['text']	= $unit_kerja->getField("NAMA");

			$arrTelp = explode(" ", trim($unit_kerja->getField("TELEPON")));
			$tempTelpPanitiaKode = $arrTelp[0];
			$tempTelpPanitia = $arrTelp[1];

			$arr_json[$i]['alamat']			= $unit_kerja->getField("ALAMAT");
			$arr_json[$i]['email']			= $unit_kerja->getField("EMAIL");
			$arr_json[$i]['telepon_kode']	= $tempTelpPanitiaKode;
			$arr_json[$i]['telepon']		= $tempTelpPanitia;
						
			$i++;
		}
		
		echo json_encode($arr_json);
	}	
	
	
}
?>
