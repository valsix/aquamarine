<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class equipment_delivery_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->CABANG		= $this->kauth->getInstance()->getIdentity()->CABANG;
	}

	function json()
	{
		$this->load->model("ServiceOrder");
		$this->load->model("SoEquipPengembalian");
		  $this->load->model("SoEquip");
		$service_order = new ServiceOrder();

		$reqMode = $this->input->get("reqMode");

		// echo $reqKategori;exit;

		$aColumns		= array("SO_ID","NO", "NO_DELIVERY", "COMPANY_NAME", "VESSEL_NAME", "TYPE_OF_SERVICE", "VESSEL_CLASS", "DESTINATION","FLAG_ITEM");
		$aColumnsAlias	= array("SO_ID", "NO","NO_DELIVERY", "COMPANY_NAME", "VESSEL_NAME", "TYPE_OF_SERVICE", "VESSEL_CLASS", "DESTINATION","FLAG_ITEM");



		/*
		 * Ordering
		 */
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = " ORDER BY ";

			//Go over all sorting cols
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
				//If need to sort by current col
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[intval($_GET['iSortCol_' . $i])];

					//Determine if it is sorted asc or desc
					if (strcasecmp(($_GET['sSortDir_' . $i]), "asc") == 0) {
						$sOrder .= " asc, ";
					} else {
						$sOrder .= " desc, ";
					}
				}
			}

			//Remove the last space / comma
			$sOrder = substr_replace($sOrder, "", -2);

			//Check if there is an order by clause
			if (trim($sOrder) == "ORDER BY SO_ID asc") {
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY SO_ID desc";
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
		if (isset($_GET['sSearch'])) {
			$sWhereGenearal = $_GET['sSearch'];
		} else {
			$sWhereGenearal = '';
		}

		if ($_GET['sSearch'] != "") {
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ($i = 0; $i < count($aColumnsAlias) + 1; $i++) {
				//If current col has a search param
				if ($_GET['bSearchable_' . $i] == "true") {
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ($i = 0; $i < count($aColumnsAlias); $i++) {
			if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
				//If there was no where clause
				if ($sWhere == "") {
					$sWhere = "AND ";
				} else {
					$sWhere .= " AND ";
				}

				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i] . " LIKE '%' || :whereSpecificParam" . $sWhereSpecificArrayCount . " || '%' ";

				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;

				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_' . $i];
			}
		}

		//If there is still no where clause - set a general - always true where clause
		if ($sWhere == "") {
			$sWhere = " AND 1=1";
		}

		//Bind variables.
		if (isset($_GET['iDisplayStart'])) {
			$dsplyStart = $_GET['iDisplayStart'];
		} else {
			$dsplyStart = 0;
		}
		if (isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart))) {
				$dsplyRange = 2147483645;
			} else {
				$dsplyRange = intval($dsplyRange);
			}
		} else {
			$dsplyRange = 2147483645;
		}

		$reqCariGlobal              	= $this->input->get('reqCariGlobal');
		$reqCariProject             	= $this->input->get('reqCariProject');
		$reqCariVasselName          	= $this->input->get('reqCariVasselName');
		$reqCariPeriodeYear         	= $this->input->get('reqCariPeriodeYear');
		$reqCariCompanyName         	= $this->input->get('reqCariCompanyName');
		$reqCariPeriodeYearFrom         = $this->input->get('reqCariPeriodeYearFrom');
		$reqCariPeriodeYearTo         	= $this->input->get('reqCariPeriodeYearTo');
		$reqCariPeriodeYearTo         	= $this->input->get('reqCariPeriodeYearTo');
		$reqClassType = $this->input->get('reqClassType');
		$reqDestination = $this->input->get('reqDestination');
		$reqBulan  = $this->input->get('reqBulan');
		$reqClass  = $this->input->get('reqClass');
        $_SESSION[$this->input->get("pg")."reqCariGlobal"] = $reqCariGlobal;
        $_SESSION[$this->input->get("pg")."reqCariProject"] = $reqCariProject;
        $_SESSION[$this->input->get("pg")."reqCariVasselName"] = $reqCariVasselName;
        $_SESSION[$this->input->get("pg")."reqCariPeriodeYear"] = $reqCariPeriodeYear;
        $_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariPeriodeYearFrom"] = $reqCariPeriodeYearFrom;
        $_SESSION[$this->input->get("pg")."reqCariPeriodeYearTo"] = $reqCariPeriodeYearTo;
          $_SESSION[$this->input->get("pg")."reqDestination"] = $reqDestination;
           $_SESSION[$this->input->get("pg")."reqBulan"] = $reqBulan;
             $_SESSION[$this->input->get("pg")."reqClassType"] = $reqClassType;
               $_SESSION[$this->input->get("pg")."reqClass"] = $reqClass;

		if (!empty($reqCariCompanyName)) {
			$statement_privacy .= " AND UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%'";
		}
		if (!empty($reqClassType)) {
			$statement_privacy .= " AND UPPER(A.VESSEL_TYPE) LIKE '%" . strtoupper($reqClassType) . "%'";
		}
		if (!empty($reqClass)) {
			$statement_privacy .= " AND UPPER(B.CLASS_OF_VESSEL) LIKE '%" . strtoupper($reqClass) . "%'";
		}
		if (!empty($reqDestination)) {
			$statement_privacy .= " AND UPPER(A.DESTINATION) LIKE '%" . strtoupper($reqDestination) . "%'";
		}
		if (!empty($reqCariPeriodeYearFrom) && !empty($reqCariPeriodeYearTo) ) {
			$statement_privacy .= "  AND A.DATE_OF_SERVICE BETWEEN  TO_DATE('" . $reqCariPeriodeYearFrom . "', 'dd-mm-yyy')  AND  TO_DATE('" . $reqCariPeriodeYearTo . "', 'dd-mm-yyy')  ";
			
		}
		// if (!empty($reqCariPeriodeYear)&& $reqCariPeriodeYear !='ALL') {
		// 	$statement_privacy .= " AND TO_CHAR(A.DATE_OF_SERVICE,'YYYY') = '".$reqCariPeriodeYear . "'";
		// }

		 if(!empty($reqCariPeriodeYear) && !empty($reqBulan) ){
            // $mtgl_awal = '01-01-'.$reqCariPeriodeYear ; 
            // $mtgl_akhir = '31-12-'.$reqCariPeriodeYear ; 
             if($reqCariPeriodeYear != 'All Year'){
            //     $statement_privacy .= " AND A.DATE_OF_SERVICE BETWEEN TO_DATE('".$mtgl_awal."','dd-mm-yyyy') AND  TO_DATE('".$mtgl_akhir."','dd-mm-yyyy')";  
                 $statement_privacy .= " AND   TO_CHAR(A.DATE_OF_SERVICE,'MMYYYY')='".$reqBulan.$reqCariPeriodeYear."' ";  
             }

        }
		if (!empty($reqCariVasselName)) {
			$statement_privacy .= " AND UPPER(A.VESSEL_NAME) LIKE '%" . strtoupper($reqCariVasselName) . "%' ";
		}
		if (!empty($reqCariProject)) {
			$statement_privacy .= " AND UPPER(A.SERVICE) LIKE '%" . strtoupper($reqCariProject) . "%' ";
		}
		if (!empty($reqCariGlobal)) {
			$statement_privacy .= " AND UPPER(A.NO_ORDER) LIKE '%" . strtoupper($reqCariGlobal) . "%' ";
		}

		$statement .= " AND (UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($_GET['sSearch']) . "%' OR UPPER(A.NO_ORDER) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
		// $statement = "";
 $_SESSION[$this->input->get("pg")."reqCariSession"] = $statement_privacy . $statement;
		$allRecord = $service_order->getCountByParamsMonitoringEquiment(array(), $statement_privacy . $statement);
		// echo $allRecord;exit;
		if ($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else
			$allRecordFilter =  $service_order->getCountByParamsMonitoringEquiment(array(), $statement_privacy . $statement);

		$service_order->selectByParamsEquiment(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);

		// echo $service_order->query;exit;

		// echo "IKI ".$_GET['iDisplayStart'];

		/*
			 * Output
			 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		 $nomer=0;
		while ($service_order->nextRow()) {
			$row = array();
			$total_pagination  = ($dsplyStart)+$nomer;
			$penomoran = $allRecordFilter-($total_pagination);

				
			
		  $soequippengembalian = new SoEquipPengembalian();
		  $soequip             = new SoEquip(); 
			$total 		= $soequip->getCountByParamsMonitoring(array("A.SO_ID"=>$service_order->getField($aColumns[0])));
			$FLAG_ITEM 	= $service_order->getField("FLAG_ITEM");

			if($FLAG_ITEM=='2' || $FLAG_ITEM == '3'){
				$FLAG_ITEM = $FLAG_ITEM;
			}else{
				if($total > 0 ){
					$FLAG_ITEM ='1';
				}

			}

			for ($i = 0; $i < count($aColumns); $i++) {
				if ($aColumns[$i] == "KETERANGAN"){
					$row[] = truncate($service_order->getField($aColumns[$i]), 10) . "...";
				}
				  else if ($aColumns[$i] == "NO") {
                    $row[] = $penomoran;
                }
                 else if ($aColumns[$i] == "EQUIP_OUT") {
                    $row[] =$total_so;
                }  else if ($aColumns[$i] == "EQUIP_IN") {
                    $row[] =$total_pengembalian;
                } else if ($aColumns[$i] == "FLAG_ITEM") {
                    $row[] =$FLAG_ITEM;
                }else if ($aColumns[$i] == "EQUIP_STATUS") {
                    $row[] =$reqComplate;
                }
				elseif ($aColumns[$i] == "LINK_FILE"){
					$row[] = "<img src='uploads/" . $service_order->getField($aColumns[$i]) . "' height='50px'>";
				}
				elseif ($aColumns[$i] == "STATUS_PUBLISH" || $aColumns[$i] == "STATUS_NOTIFIKASI") {
					if ($service_order->getField($aColumns[$i]) == "Y")
						$row[] = '<i class="fa fa-check fa-lg" aria-hidden="true"></i>';
					else
						$row[] = '<i class="fa fa-close fa-lg" aria-hidden="true"></i>';
				} else
					$row[] = $service_order->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
			 $nomer++;
		}
		echo json_encode($output);
	}

	function delete()
	{
		$reqId = $this->input->get('reqId');
		$this->load->model("ServiceOrder");
		$serviceOrder = new ServiceOrder();

		$serviceOrder->setField("SO_ID", $reqId);
		if ($serviceOrder->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}
}
