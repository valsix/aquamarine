<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");

class penyimpanan_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->aduanId				  = $this->kauth->getInstance()->getIdentity()->Dokumen_id;
		$this->Nip				  = $this->kauth->getInstance()->getIdentity()->Nip;
		$this->nama							= $this->kauth->getInstance()->getIdentity()->nama;
		$this->Aduan				= $this->kauth->getInstance()->getIdentity()->Aduan;
		$this->linkFile				= $this->kauth->getInstance()->getIdentity()->link_file;
		$this->createdBy				= $this->kauth->getInstance()->getIdentity()->created_by;
		$this->createdDate			= $this->kauth->getInstance()->getIdentity()->created_date;
		$this->updateBy				= $this->kauth->getInstance()->getIdentity()->update_by;
		$this->updateDate			= $this->kauth->getInstance()->getIdentity()->update_date;
	}
	function json()
    {
        $this->load->model("Penyimpanan");
        $pms = new Penyimpanan();

        $aColumns = array("PENYIMPANAN_ID","TANGGAL","NAMA_PARAF","QTY","MASUK_G","MASUK_R","KELUAR_G","KELUAR_R","PERSEDIAN_G","PERSEDIAN_R");
        $aColumnsAlias = array("PENYIMPANAN_ID","TANGGAL","NAMA_PARAF","QTY","MASUK_G","MASUK_R","KELUAR_G","KELUAR_R","PERSEDIAN_G","PERSEDIAN_R");
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
            if (trim($sOrder) == "ORDER BY A.".$aColumns[0]." asc") {
                /*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
                $sOrder = " ORDER BY A.".$aColumns[0]." asc";
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

        $statement_privacy .= " ";

        // $statement = " AND (UPPER(A.NAMA) LIKE '%" . strtoupper($_GET['sSearch']) . "%')";
        $allRecord = $pms->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $pms->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $pms->selectByParamsMonitoringDasboard(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $pms->query;
        // exit;
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
         $nom=0;
        while ($pms->nextRow()) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NAME"){
                    $row[] = $pms->getField($aColumns[$i]);
                }else  if ($aColumns[$i] == "TOTAL") {
                    $row[] = $pms->getField('CURRENCY').' '.currencyToPage2($pms->getField($aColumns[$i]));
                } else  if ($aColumns[$i] == "QTY") {
                    $row[] = conver_number($pms->getField($aColumns[$i]));
                } else  if ($aColumns[$i] == "TANGGAL") {
                    $row[] = getFormattedDateEng($pms->getField($aColumns[$i]));
                } 
                else{
                    $row[] = $pms->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	

	function add()
	{
		$this->load->model("Penyimpanan");
		$this->load->model("PenyimpananDetail");
		$this->load->model("PenyimpananParaf");
		$reqId =	$this->input->post("reqId");

		$bank = new Penyimpanan();	
		$reqDateOfService 			= $this->input->post("reqDateOfService");
		$reqDescription 	= $this->input->post("reqKeterangan");
		$reqLokasi = $this->input->post("reqLokasi");
		$reqParafNama = $this->input->post("reqParafNama");

		$reqItemId  = $this->input->post("reqItemId");
		$reqEquipTotal  = $this->input->post("reqEquipTotal");
		$reqIdcEquipment  = $this->input->post("reqIdcEquipment");
		$masukG  = $this->input->post("masukG");
		$masukG  = $this->input->post("masukG");
		$masukR  = $this->input->post("masukR");
		$keluarG  = $this->input->post("keluarG");
		$keluarR  = $this->input->post("keluarR");
		$persedianG  = $this->input->post("persedianG");
		$persedianR  = $this->input->post("persedianR");

		$bank->setField("PENYIMPANAN_ID", $reqId);
		$bank->setField("TANGGAL", dateToDBCheck($reqDateOfService));
		$bank->setField("KETERANGAN", $reqDescription);
		$bank->setField("LOKASI", $reqLokasi);

		if (empty($reqId)) {
			$bank->insert();
			$reqId  = $bank->id;
		} else {
			$bank->update();
		}
		$penyimpananparaf = new PenyimpananParaf();
		$penyimpananparaf->setField("PENYIMPANAN_ID",$reqId );
		$penyimpananparaf->deleteParent();
		foreach ($reqParafNama as $value) {
					$penyimpananparaf->setField("NAMA",$value );
					$penyimpananparaf->insert();

		}

		for($i=0;$i<count($reqItemId);$i++){
				$reqItemIdx = $reqItemId[$i];
				$penyimpanandetail = new PenyimpananDetail();
				$penyimpanandetail->setField("PENYIMPANAN_DETAIL_ID",$reqItemIdx);
				$penyimpanandetail->setField("PENYIMPANAN_ID",$reqId );
				$penyimpanandetail->setField("EQUIP_ID",$reqIdcEquipment[$i] );
				$penyimpanandetail->setField("QTY",$reqEquipTotal[$i] );
				$penyimpanandetail->setField("MASUK_G",ifZero2(dotToNo($masukG[$i])) );
				$penyimpanandetail->setField("MASUK_R",ifZero2(dotToNo($masukR[$i])) );
				$penyimpanandetail->setField("KELUAR_G",ifZero2(dotToNo($keluarG[$i])) );
				$penyimpanandetail->setField("KELUAR_R",ifZero2(dotToNo($keluarR[$i])) );
				$penyimpanandetail->setField("PERSEDIAN_G",ifZero2(dotToNo($persedianG[$i])) );
				$penyimpanandetail->setField("PERSEDIAN_R",ifZero2(dotToNo($persedianR[$i])) );
				if(empty($reqItemIdx)){
					$penyimpanandetail->insert();
				}else{
					$penyimpanandetail->update();
				}
		}


		echo $reqId.'-Data Berhasil di simpan';
	}

	function deleteDetail(){
		$reqId = $this->input->get('reqId');
		$this->load->model("PenyimpananDetail");
		$penyimpanandetail = new PenyimpananDetail();
		$penyimpanandetail->setField("PENYIMPANAN_DETAIL_ID",$reqId);
		$penyimpanandetail->delete();
		echo 'Data Berhasil di hapus';
	}
}
