
<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class pembelian_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->USERID = $this->kauth->getInstance()->getIdentity()->USERID;
		$this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->FULLNAME = $this->kauth->getInstance()->getIdentity()->FULLNAME;
		$this->USERPASS = $this->kauth->getInstance()->getIdentity()->USERPASS;
		$this->LEVEL = $this->kauth->getInstance()->getIdentity()->LEVEL;
		$this->MENUMARKETING = $this->kauth->getInstance()->getIdentity()->MENUMARKETING;
		$this->MENUFINANCE = $this->kauth->getInstance()->getIdentity()->MENUFINANCE;
		$this->MENUPRODUCTION = $this->kauth->getInstance()->getIdentity()->MENUPRODUCTION;
		$this->MENUDOCUMENT = $this->kauth->getInstance()->getIdentity()->MENUDOCUMENT;
		$this->MENUSEARCH = $this->kauth->getInstance()->getIdentity()->MENUSEARCH;
		$this->MENUOTHERS = $this->kauth->getInstance()->getIdentity()->MENUOTHERS;
		$this->MENUEPL = $this->kauth->getInstance()->getIdentity()->MENUEPL;
		$this->MENUUWILD = $this->kauth->getInstance()->getIdentity()->MENUUWILD;
		$this->MENUWP = $this->kauth->getInstance()->getIdentity()->MENUWP;
		$this->MENUPL = $this->kauth->getInstance()->getIdentity()->MENUPL;
		$this->MENUEL = $this->kauth->getInstance()->getIdentity()->MENUEL;
		$this->MENUPMS = $this->kauth->getInstance()->getIdentity()->MENUPMS;
		$this->MENURS = $this->kauth->getInstance()->getIdentity()->MENURS;
		$this->MENUSTD = $this->kauth->getInstance()->getIdentity()->MENUSTD;
		$this->MENUSTEN = $this->kauth->getInstance()->getIdentity()->MENUSTEN;
		$this->MENUSWD = $this->kauth->getInstance()->getIdentity()->MENUSWD;
		$this->MENUINVPROJECT = $this->kauth->getInstance()->getIdentity()->MENUINVPROJECT;


	}

	function json()
    {
        $this->load->model("Pembelian");
        $pms = new Pembelian();

        $aColumns = array("PEMBELIAN_ID","TANGGAL","HTML");
        $aColumnsAlias=  array("PEMBELIAN_ID","TANGGAL","HTML");
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

        $reqCariIdNumber = $this->input->get('reqCariIdNumber');
        $reqCariCondition = $this->input->get('reqCariCondition');
        $reqCariCategori = $this->input->get('reqCariCategori');
        $reqCariStorage = $this->input->get('reqCariStorage');
        $$reqCariCompanyName = $this->input->get('reqCariCompanyName');
        $reqCariCurrency = $this->input->get('reqCariCurrency');
        $reqCariPembayaran = $this->input->get('reqCariPembayaran');
        $reqCariSpesification = $this->input->get('reqCariSpesification');
        $reqCariIncomingDateFrom = $this->input->get('reqCariIncomingDateTo');
        $reqCariIncomingDateTo = $this->input->get('reqCariIncomingDateTo');

        $_SESSION[$this->input->get("pg")."reqCariIdNumber"] = $reqCariIdNumber;
        $_SESSION[$this->input->get("pg")."reqCariCondition"] = $reqCariCondition;
        $_SESSION[$this->input->get("pg")."reqCariCategori"] = $reqCariCategori;
        $_SESSION[$this->input->get("pg")."reqCariStorage"] = $reqCariStorage;
        $_SESSION[$this->input->get("pg")."reqCariCompanyName"] = $reqCariCompanyName;
        $_SESSION[$this->input->get("pg")."reqCariCurrency"] = $reqCariCurrency;
        $_SESSION[$this->input->get("pg")."reqCariPembayaran"] = $reqCariPembayaran;
        $_SESSION[$this->input->get("pg")."reqCariSpesification"] = $reqCariSpesification;
        $_SESSION[$this->input->get("pg")."reqCariIncomingDateFrom"] = $reqCariIncomingDateFrom;
        $_SESSION[$this->input->get("pg")."reqCariIncomingDateTo"] = $reqCariIncomingDateTo;

        if (!empty($reqCariIdNumber)) {

            $statement_privacy  .= " AND  UPPER(B.NAME) LIKE '%".strtoupper($reqCariIdNumber)."%'  ";
        }

        if (!empty($reqCariCondition)) {

            $statement_privacy  .= " AND A.MASTER_PROJECT_ID='".$reqCariCondition."' ";
        }
        if (!empty($reqCariCategori) &&  $reqCariCategori !='ALL' ) {

            $statement_privacy  .= " AND EXISTS(
            SELECT 1 FROM PEMBELIAN_DETAIL UJ
            LEFT JOIN EQUIPMENT_LIST KL ON KL.EQUIP_ID = UJ.EQUIP_ID
            WHERE KL.EC_ID ='".$reqCariCategori."' AND UJ.PEMBELIAN_ID = A.PEMBELIAN_ID
            ) ";
        }

         if (!empty($reqCariStorage)) {

             $statement_privacy  .= " AND  UPPER(HH.NAMA_ALAT) LIKE '%".strtoupper($reqCariStorage)."%'  ";
        }
         if (!empty($reqCariCompanyName)) {

             $statement_privacy  .= " AND  UPPER(HH.EQUIP_NAME) LIKE '%".strtoupper($reqCariCompanyName)."%'  ";
        }
        if (!empty($reqCariCurrency)) {

            $statement_privacy  .= " AND A.CURRENCY='".$reqCariCurrency."' ";
        }
         if (!empty($reqCariPembayaran)) {

            $statement_privacy  .= " AND A.JENIS_PEMBAYARAN='".$reqCariPembayaran."' ";
        }

        if (!empty($reqCariIncomingDateFrom) && !empty($reqCariIncomingDateTo)) {
            $statement_privacy  .= " AND A.TAGGAL BETWEEN TO_DATE('" . $reqCariIncomingDateFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariIncomingDateTo . "','dd-mm-yyyy') ";
        }

        if (!empty($reqCariSpesification)) {

             $statement_privacy  .= " AND  UPPER(A.NO_PO) LIKE '%".strtoupper($reqCariSpesification)."%'  ";
        }

         $_SESSION[$this->input->get("pg")."reqCariSession"] = $statement_privacy . $statement;
   
         $statement  = " AND  UPPER(A.NO_PO) LIKE '%".strtoupper($_GET['sSearch'])."%'  ";
        $allRecord = $pms->getCountByParamsMonitoring(array(), $statement_privacy . $statement);
        // echo $allRecord;exit;
        if ($_GET['sSearch'] == "")
            $allRecordFilter = $allRecord;
        else
            $allRecordFilter =  $pms->getCountByParamsMonitoring(array(), $statement_privacy . $statement);

        $pms->selectByParamsMonitoringDasboard(array(), $dsplyRange, $dsplyStart, $statement_privacy . $statement, $sOrder);
        // echo $pms->query;exit;
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
           
           $ids = $pms->getField($aColumns[0]);
            $html = file_get_contents($this->config->item('base_report') . "report/index/tempalate_view_row_pembelian/?reqId=".$ids);
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "NAME"){
                    $row[] = $pms->getField($aColumns[$i]);
                
                } else  if ($aColumns[$i] == "TANGGAL") {
                    $row[] = getFormattedDateEng($pms->getField($aColumns[$i]));
                } else  if ($aColumns[$i] == "HTML") {
                    $row[] =  $html;
                
                }else{
                    $row[] = $pms->getField($aColumns[$i]);
                }
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }


	function add(){
		$this->load->model('Pembelian');
        $this->load->model('LogPembelian');
		$this->load->model('PembelianDetail');
		$this->load->model('PembelianAlat');
        $this->load->model('EquipmentList');

         $this->load->library("FileHandler");
         $file = new FileHandler();
		
		$reqId = $this->input->post('reqId');
		$reqDateOfService = $this->input->post('reqDateOfService');
		$reqEcId = $this->input->post('reqEcId');
		$reqMaker = $this->input->post('reqMaker');
		$reqCompanyId = $this->input->post('reqCompanyId');

        $reqCompanyId = $this->add_company();
			$reqCurrency = $this->input->post('reqCurrencyValue');

		$reqIdcEquipmentName = $this->input->post('reqIdcEquipmentName');
		$reqIdcEquipment = $this->input->post('reqIdcEquipment');
		$reqEquipPrice = $this->input->post('reqEquipPrice');
		$reqEquipQty = $this->input->post('reqEquipQty');
		$reqEquipTotal = $this->input->post('reqEquipTotal');
		$reqPembelianId = $this->input->post('reqPembelianId');
		$reqPembayaran = $this->input->post('reqPembayaran');
		$reqNoPo   	= $this->input->post('reqNoPo');
        $reqEcId = $this->input->post('reqEcId');
        $reqIdcEquipmentSerial = $this->input->post('reqIdcEquipmentSerial');
        $reqIdcEquipmentName = $this->input->post('reqIdcEquipmentName');
        $reqTanggalBayar  = $this->input->post('reqTanggalBayar');
         $reqNoPembelian = $this->input->post('reqNoPembelian');
         $reqCompanyName =  $this->input->post('reqCompanyName');
        $reqDocumentPerson =  $this->input->post('reqDocumentPerson');
        $reqPpnPercent =  $this->input->post('reqPpnPercent');
        $reqNoVoucher = $this->input->post('reqNoVoucher');
        $reqPpn =  $this->input->post('reqPpn');
        $reqIdcEquipmentDesc =  $this->input->post('reqIdcEquipmentDesc');
         $bollean = false;
         foreach ($reqEcId as $value) {
          if(empty($value)){
            $bollean='true';
          }
         }

         if( $bollean){
            echo 'xxx- Belum ada pilihan jenis mohon di lengkapi';exit;
         }

       for($i=0;$i<count($reqIdcEquipmentName);$i++){
                    
           $equipmentlist = new EquipmentList();
           $equipmentlist->selectByParamsMonitoring(array('A.SERIAL_NUMBER'=>$reqIdcEquipmentSerial[$i],
            'UPPER(A.EQUIP_NAME)'=>strtoupper($reqIdcEquipmentName[$i])));
           $arrDataEquip = $equipmentlist->rowResult;
           if(count($arrDataEquip)>0 && $reqEquipQty[$i] > 0 ){
                  echo 'xxx- Check Kembali, Alat '.$reqIdcEquipmentName[$i].' <br> No Serial '.$reqIdcEquipmentSerial[$i].' <br> sudah terdaftar di Equipment List ';exit;
           }
        }

       if(empty($reqPpn)){
             $reqPpnPercent=0;
       }

       $pembelian = new Pembelian();
       $pembelian->setField('PEMBELIAN_ID',$reqId);
       $pembelian->setField('TANGGAL',dateToDBCheck($reqDateOfService));
       $pembelian->setField('TANGGAL_BAYAR',dateToDBCheck($reqTanggalBayar));
       $pembelian->setField('COMPANY_ID',ValToNull($reqCompanyId));
       $pembelian->setField('JENIS_ID',ValToNull($reqEcId));
       $pembelian->setField('CURRENCY',$reqCurrency);
       $pembelian->setField('JENIS_PEMBAYARAN',$reqPembayaran);
       $pembelian->setField('NO_PO',$reqNoPo);
       $pembelian->setField('NO_PEMBELIAN',$reqNoPembelian);
       $pembelian->setField('SUPPLIER_CONTACT',$reqDocumentPerson);
       $pembelian->setField('SUPPLIER_NAMA',$reqCompanyName);
       $pembelian->setField('PPN',$reqPpn);
       $pembelian->setField('PPN_VAL',$reqPpnPercent);
        $pembelian->setField('VOUCHER',$reqNoVoucher);
		
		$pembelian->setField('MASTER_PROJECT_ID',ValToNull($reqMaker));

		if(empty($reqId)){
			$pembelian->insert();
			$reqId=$pembelian->id;
		}else{
			$pembelian->update();
		}	

		for($i=0;$i<count($reqIdcEquipment);$i++){
			$harga = ifZero2(dotToNo($reqEquipPrice[$i]));
			$qty = ifZero2(dotToNo($reqEquipQty[$i]));
			$total = $harga * $qty;
            $pembeliandetail = new PembelianDetail();
            $reqIdPembeian = $pembeliandetail->getNextId("PEMBELIAN_DETAIL_ID","PEMBELIAN_DETAIL");

            $equipmentlist = new EquipmentList();
            $equipmentlist->setField('EC_ID',$reqEcId[$i]);
            $equipmentlist->setField('EQUIP_NAME',$reqIdcEquipmentName[$i]);
            $equipmentlist->setField('SERIAL_NUMBER',$reqIdcEquipmentSerial[$i]);
            $equipmentlist->setField('EQUIP_ID',$reqIdcEquipment[$i]);
            $equipmentlist->setField('EQUIP_PRICE',$harga);
             $equipmentlist->setField('EQUIP_DATEIN',dateToDBCheck($reqDateOfService));
             $equipmentlist->setField('CURRENCY',$reqCurrency);
            $equipmentlist->setField('EQUIP_QTY',$qty);
           

             $equipmentlist->setField('PEMBELIAN_ID',$reqId);
         
            $equipmentlist->setField('PEMBELIAN_DETAIL_ID',$reqIdPembeian);

            if(empty($reqIdcEquipment[$i])){
                // $equipmentlist->insertFromPembelian();
                // $reqIdcEquipment[$i] = $equipmentlist->id;
            }else{
                if(!empty($reqPembelianId[$i])){
                // $equipmentlist->setField('PEMBELIAN_DETAIL_ID',$reqPembelianId[$i]);

                // $equipmentlist->updateFromPembelian();
                }
            }



			
			$pembeliandetail->setField('PEMBELIAN_DETAIL_ID',$reqPembelianId[$i]);
			$pembeliandetail->setField('PEMBELIAN_ID',$reqId);
			// $pembeliandetail->setField('EQUIP_ID',ValToNull($reqIdcEquipment[$i]));
            $pembeliandetail->setField('EQUIP_ID',ValToNull('0'));
			$pembeliandetail->setField('CURRENCY',$reqCurrency);
			$pembeliandetail->setField('HARGA',$harga);
			$pembeliandetail->setField('QTY',$qty);
			$pembeliandetail->setField('TOTAL',$total);
              $pembeliandetail->setField('DESKRIPSI',$reqIdcEquipmentDesc[$i]);
             $pembeliandetail->setField('EC_ID',$reqEcId[$i]);
              $pembeliandetail->setField('NAMA_ALAT',$reqIdcEquipmentName[$i]);
               $pembeliandetail->setField('SERIAL_NUMBER',$reqIdcEquipmentSerial[$i]);
			
        if(!empty($reqIdcEquipmentName[$i])){
			if(empty($reqPembelianId[$i])){
				$pembeliandetail->insert();
			}else{
				$pembeliandetail->update();
			}
        }



		}


		$reqPembelidIdDetail = $this->input->post('reqPembelidIdDetail');
		$reqPembelidIdAlat = $this->input->post('reqPembelidIdAlat');
		$reqNamaAlat = $this->input->post('reqNamaAlat');
		$reqAlatHarga = $this->input->post('reqAlatHarga');
		$reqAlatQty = $this->input->post('reqAlatQty');
        $reqNamaAlatDeskripsi= $this->input->post('reqNamaAlatDeskripsi');
        $reqNamaAlatSerial  = $this->input->post('reqNamaAlatSerial');
		for($i=0;$i<count($reqPembelidIdAlat);$i++){
			$harga = ifZero2(dotToNo($reqAlatHarga[$i]));
			$qty = ifZero2(dotToNo($reqAlatQty[$i]));
			$total = $harga * $qty;
				$pembelianalat = new PembelianAlat();
				$pembelianalat->setField("PEMBELIAN_ALAT_ID",$reqPembelidIdAlat[$i]);
				$pembelianalat->setField("PEMBELIAN_ID",$reqId);
				$pembelianalat->setField("PEMBELIAN_DETAIL_ID",$reqPembelidIdDetail[$i]);
				$pembelianalat->setField("HARGA",$harga);
				$pembelianalat->setField("QTY",$qty);
				$pembelianalat->setField("TOTAL",$total);
                $pembelianalat->setField("DESKIPSI",$reqNamaAlatDeskripsi[$i]);
				$pembelianalat->setField("NAMA_ALAT",$reqNamaAlat[$i]);
                $pembelianalat->setField("SERIAL_NUMBER",$reqNamaAlatSerial[$i]);

            if(!empty($reqPembelidIdDetail[$i])){
				if(empty($reqPembelidIdAlat[$i])){
					$pembelianalat->insert();
				}else{
					$pembelianalat->update();
				}
            }
		}

       
        $pesan = $reqId."-Data berhasil disimpan.";
         $reqValue = $this->input->post('reqValue');
        if($reqValue=="POST"){
            $pembelian = new Pembelian();
            $pembelian->setField('PEMBELIAN_ID',$reqId);
            $pembelian->updatePost();
            $pesan = $reqId."-Data berhasil diposting.";

            $this->check_company($reqId);

            $pembeliandetail = new PembelianDetail();
            $pembeliandetail->selectByParamsMonitoring(array("A.PEMBELIAN_ID"=>$reqId));
            $arrDataDetail = $pembeliandetail->rowResult;

            $arrFilter  = array('16','17','18','20');

            foreach ($arrDataDetail as  $value) {

              $log_pembelian = new LogPembelian();
              $log_pembelian->setField("PEMBELIAN_ID", $value['pembelian_id']);
              $log_pembelian->setField("PEMBELIAN_DETAIL_ID", $value['pembelian_detail_id']);
           // $log_pembelian->setField("EQUIP_ID", $reqEquipId);
              $log_pembelian->setField("QTY", $value['qty']);
              $log_pembelian->setField("HARGA", $value['harga']);
              $log_pembelian->setField("NAMA_ALAT", $value['nama_alat']);
              $log_pembelian->setField("SERIAL_NUMBER",  $value['no_seri']);
               
                 if($value['equip_id'] !='0'){   
                    $equipmentlist = new EquipmentList();
                    $equipmentlist->setField('EQUIP_QTY',$value['qty']);
                    $equipmentlist->setField('EQUIP_ID',$value['equip_id']);                
                    $equipmentlist->setField('PEMBELIAN_DETAIL_ID',$value['pembelian_detail_id']);

                    if(!in_array($value['ec_id'], $arrFilter)){
                    $equipmentlist->updateQtyPembelian();

                    $log_pembelian->setField("EQUIP_ID", $value['equip_id']);
                    $log_pembelian->insert();
                    }
                }else{
                   $equipmentlist = new EquipmentList();
                   $equipmentlist->selectByParamsMonitoring(array('A.SERIAL_NUMBER'=>$value['no_seri'],
                    'UPPER(A.EQUIP_NAME)'=>strtoupper($value['nama_alat'])));
                   $arrDataEquip = $equipmentlist->rowResult;
                   $arrDataEquip = $arrDataEquip[0];
                   $reqEuipId =  $arrDataEquip['equip_id'];

                   if(empty($reqEuipId)){
                       $equipmentlist = new EquipmentList();
                       $equipmentlist->setField('EC_ID',$value['ec_id']);
                       $equipmentlist->setField('EQUIP_NAME',$value['nama_alat']);
                       $equipmentlist->setField('SERIAL_NUMBER',$value['no_seri']);
                        $equipmentlist->setField('EQUIP_SPEC',$value['deskripsi']); 
                       $equipmentlist->setField('EQUIP_PRICE',$value['harga']);
                       $equipmentlist->setField('EQUIP_DATEIN',dateToDBCheck($reqDateOfService));
                       $equipmentlist->setField('CURRENCY',$reqCurrency);
                       $equipmentlist->setField('EQUIP_QTY',$value['qty']);

                       $equipmentlist->setField('PEMBELIAN_ID',$reqId);

                       $equipmentlist->setField('PEMBELIAN_DETAIL_ID',$reqIdPembeian);
                         if(!in_array($value['ec_id'], $arrFilter)){
                              $equipmentlist->insertFromPembelian();
                        }
                    }else{
                       $equipmentlist = new EquipmentList();
                       $equipmentlist->setField('EQUIP_QTY',$value['qty']);
                       $equipmentlist->setField('EQUIP_ID',$reqEuipId);                
                       $equipmentlist->setField('PEMBELIAN_DETAIL_ID',$value['pembelian_detail_id']);
                       $equipmentlist->updateQtyPembelian();

                       $log_pembelian->setField("EQUIP_ID", $reqEuipId);
                         if(!in_array($value['ec_id'], $arrFilter)){
                             $log_pembelian->insert();
                        }
                    }


                }
            }
        }

        $FILE_DIR = "uploads/pembelian/" . $reqId . "/";
        makedirs($FILE_DIR);

        $filesData = $_FILES["reqLinkFileCertificate"];
         $reqLinkFileCertificateTemp      = $this->input->post("reqLinkFileCertificateTemp");

        $arrData = array();
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $renameFile = $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
            if ($file->uploadToDirArray('reqLinkFileCertificate', $FILE_DIR, $renameFile, $i)) {
                array_push($arrData, $renameFile);
            } else {
                array_push($arrData, $reqLinkFileCertificateTemp[$i]);
            }
        }
        $str_name_path = '';
        for ($i = 0; $i < count($arrData); $i++) {
            if (!empty($arrData[$i])) {
                if ($i == 0) {
                    $str_name_path .= $arrData[$i];
                } else {
                    $str_name_path .= ';' . $arrData[$i];
                }
            }
        }

        $pembelian->setField("PEMBELIAN_ID", $reqId);
        $pembelian->setField("LAMPIRAN", setQuote($str_name_path));
        $pembelian->updateLampiran();

        if($reqValue=="POST"){
                $this->tambahDataBarangDiSupplier($reqId);
        }

		echo   $pesan;
	}

    function check_company($reqId){
         $this->load->model('Pembelian');
         $this->load->model('Company');
        
         $reqCompanyId = $this->input->post('reqCompanyId');
         $reqCompanyName =  $this->input->post('reqCompanyName');
         $reqDocumentPerson =  $this->input->post('reqDocumentPerson');
         if(empty( $reqCompanyId )){
            $company = new Company();
            $company->setField("NAME", $reqCompanyName);
            $company->setField("CP1_NAME", $reqDocumentPerson);
            $company->insert();
            $reqCompanyId =  $company->id;
            $company->setField("COMPANY_ID", $reqCompanyId);
            $company->setField("KATEGORI", 'SUPPLIER');
            $company->updateSupplier();

            $pembelian = new Pembelian();
            $pembelian->setField("PEMBELIAN_ID", $reqId);
            $pembelian->setField("COMPANY_ID", $reqCompanyId);
            $pembelian->updateCompany();
         }
    }

    function tambahDataBarangDiSupplier($reqId){
        $this->load->model('SupplierBarang');
        $this->load->model('SupplierPart');
        $this->load->model('PembelianDetail');
        $this->load->model('PembelianAlat');
         $this->load->model('Pembelian');
        
       
        $supplierpart = new SupplierPart();
        $pembelian = new Pembelian();
        $arrFilter  = array('17','18','20');

        $pembelian->selectByParamsMonitoring(array('A.PEMBELIAN_ID'=>$reqId)); 
        $arrDataPembelian = $pembelian->rowResult; 
        $arrDataPembelian = $arrDataPembelian[0]; 
        $reqSupplierId = $arrDataPembelian['company_id'];
        $reqCurrency = $arrDataPembelian['currency'];

        $statement_filter = " AND A.EC_ID NOT IN (".implode(',', $arrFilter).") ";
        $pembeliandetail = new PembelianDetail();
        $pembeliandetail->selectByParamsMonitoring(array('A.PEMBELIAN_ID'=>$reqId),-1,-1,  $statement_filter);    
        $arrDataDetail = $pembeliandetail->rowResult;    
       
        $statementAlat = ' AND  EXISTS ( SELECT 1 FROM PEMBELIAN_DETAIL CC WHERE CC.PEMBELIAN_ID=A.PEMBELIAN_ID AND CC.EC_ID NOT IN ('.implode(',', $arrFilter).') )';

        $pembelianalat = new PembelianAlat();
        $pembelianalat->selectByParamsMonitoring(array('A.PEMBELIAN_ID'=>$reqId),-1,-1,$statementAlat);
      
        $arrDataAlat = $pembelianalat->rowResult;

        foreach($arrDataDetail as $value){
             $supplierbarang = new SupplierBarang();
             $statementSup = " AND UPPER(A.NAMA)||UPPER(A.SERIAL_NUMBER) = '".strtoupper($value['nama_alat']).strtoupper($value['serial_number'])."'";
             $supplierbarang->selectByParamsMonitoring(array('A.SUPPLIER_ID'=>$reqSupplierId),-1,-1, $statementSup);
             $arrDataSup = $supplierbarang->rowResult;
             $totalSup = $supplierbarang->rowCount;
             if($totalSup==0){
                $supplierbarang = new SupplierBarang();
                $supplierbarang->setField('SUPPLIER_ID',$reqSupplierId);
                $supplierbarang->setField('NAMA',$value['nama_alat']);
                $supplierbarang->setField('QTY',$value['qty']);
                $supplierbarang->setField('SERIAL_NUMBER',$value['serial_number']);
                $supplierbarang->setField('HARGA',$value['harga']);              
                $supplierbarang->setField('CURRENCY',$reqCurrency);
                $supplierbarang->insert();
             }

        }    

        foreach ($arrDataAlat as  $value) {
                     $statementSup = " AND UPPER(A.NAMA)||UPPER(A.SERIAL_NUMBER) = '".strtoupper($value['nama_alat']).strtoupper($value['serial_number'])."'";
                     $supplierpart = new SupplierPart();
                     $supplierpart->selectByParamsMonitoring(array('A.SUPPLIER_ID'=>$reqSupplierId),-1,-1,$statementSup);
                     // echo $supplierpart->query;
                     $arrDataSup = $supplierpart->rowResult;
                     $totalSup = $supplierpart->rowCount;
                     if($totalSup==0){
                                  
                        $supplierbarang = new SupplierPart();                       
                        $supplierbarang->setField('SUPPLIER_ID',$reqSupplierId);
                        $supplierbarang->setField('NAMA',$value['nama_alat']);
                        $supplierbarang->setField('QTY',$value['qty']);
                        $supplierbarang->setField('SERIAL_NUMBER',$value['serial_number']);
                        $supplierbarang->setField('HARGA',$value['harga']);              
                        $supplierbarang->setField('CURRENCY',$reqCurrency);
                        $supplierbarang->insert();
                     }
        }

       
    }




	function deleteDetail(){
		$this->load->model('PembelianDetail');
        $this->load->model('EquipmentList');
		$reqId = $this->input->get('reqId');

		$pembeliandetail = new PembelianDetail();
		$pembeliandetail->setField("PEMBELIAN_DETAIL_ID",$reqId);
		$pembeliandetail->delete();

        $equipmentlist = NEW EquipmentList();
        $equipmentlist->setField("PEMBELIAN_DETAIL_ID",$reqId);
        $equipmentlist->updateHapusPembelian();

		echo 'Data berhasil di hapus';
	}

    function ambilCodeProject(){
            $this->load->model('MasterProject');
            $reqId = $this->input->post('reqId');
            $masterproject = new MasterProject();
            $masterproject->selectByParamsMonitoring(array('A.MASTER_PROJECT_ID'=>$reqId));
            $reqData =   $masterproject->rowResult;
            $reqData = $reqData[0];

            $arrDataResult['NAMA']=$reqData['nama'];
             $arrDataResult['PO']=$reqData['keterangan'];

            echo  json_encode($arrDataResult);
    }

    function delete(){
        $this->load->model('Pembelian');
        $reqId = $this->input->get('reqId');

        $pembeliandetail = new Pembelian();
        $pembeliandetail->setField("PEMBELIAN_ID",$reqId);
        $pembeliandetail->delete();

        echo 'Data berhasil di hapus';
    }


    function deleteAlat(){
        $this->load->model('PembelianAlat');
        $reqId = $this->input->get('reqId');

        $pembeliandetail = new PembelianAlat();
        $pembeliandetail->setField("PEMBELIAN_ALAT_ID",$reqId);
        $pembeliandetail->delete();

        echo 'Data berhasil di hapus';
    }

    function autoComplate(){
          $this->load->model('SupplierBarang');
            $keyword = $this->input->post('keyword');
        $reqId = $this->input->get('reqId');
        $reqSupplierId = $this->input->get('reqSupplierId');
        $masteralat = new SupplierBarang();
        $masteralat->selectByParamsMonitoring(array("A.SUPPLIER_ID"=>$reqSupplierId),-1,-1," AND UPPER(NAMA) LIKE '%".strtoupper($keyword)."%' ");
        $arrDataAlat = $masteralat->rowResult;
        $text ='<ul class="country-list alat_list" ><li onClick="closeComplit()"> <i class="fa fa-times fa-lg"></i> CLOSE </li>';
        foreach ($arrDataAlat as $country) {
            $reqNama = $country['nama'];
             $reqSerialNumber = $country['serial_number'];
            $text .= '<li
        onClick="selectCountry2('. "'$reqNama'".','."'$reqId'".','."'$reqSerialNumber'".');">'
       .$country["nama"].'</li>';

        }
        $text .='</ul>';
        echo $text;
    }


    function autoComplateSparedPart(){
          $this->load->model('SupplierPart');
            $keyword = $this->input->post('keyword');
        $reqId = $this->input->get('reqId');
        $reqSupplierId = $this->input->get('reqSupplierId');
        $masteralat = new SupplierPart();
        $masteralat->selectByParamsMonitoring(array("A.SUPPLIER_ID"=>$reqSupplierId),-1,-1," AND UPPER(NAMA) LIKE '%".strtoupper($keyword)."%' ");
        $arrDataAlat = $masteralat->rowResult;
        $text ='<ul class="country-list alat_list" ><li onClick="closeComplit()"> <i class="fa fa-times fa-lg"></i> CLOSE </li>';
        foreach ($arrDataAlat as $country) {
            $reqNama = $country['nama'];
             $reqSerialNumber = $country['serial_number'];
            $text .= '<li
        onClick="selectCountry('. "'$reqNama'".','."'$reqId'".','."'$reqSerialNumber'".');">'
       .$country["nama"].'</li>';

        }
        $text .='</ul>';
        echo $text;
    }
	

    function ambil_company_detail(){
        $this->load->model("Company");
         $this->load->model("VendorCode");
        $reqId = $this->input->post('reqId');
        $company = new Company();
        $company->selectByParamsMonitoring(array('A.COMPANY_ID'=>$reqId));
        $arrData =  $company->rowResult;
        $arrData = $arrData[0];

        $vendorcode = new VendorCode();
        $vendorcode->selectByParamsMonitoring(array('A.SUPPLIER_ID'=>$reqId,'A.STATUS_AKTIF'=>'1'));

        $arrDataVendor = $vendorcode->rowResult;
        $arrDataVendor =   $arrDataVendor[0];
        $arrData['vendorcode'] = $arrDataVendor['kode'];
         $arrData['type'] = $arrDataVendor['type'];
         $arrData['loc']= $arrDataVendor['area'];
        echo json_encode($arrData);
    }

    function add_company()
    {
        $this->load->model("Company");
        $this->load->model("Vessel");
        $company = new Company();


        $reqMode = $this->input->post("reqMode");
      

        $reqCompanyId = $this->input->post("reqCompanyId");
        $reqId = $reqCompanyId;
        $reqName = $this->input->post("reqCompanyName");
        $reqAddress = $_POST["reqAddress"];
        $reqPhone = $this->input->post("reqPhone");
        $reqFax = $this->input->post("reqFax");
        $reqEmail = $this->input->post("reqEmail");
        $reqCp1Name = $this->input->post("reqDocumentPerson");
        $reqCp1Telp = $this->input->post("reqCp1Telp");
        $reqCp2Name = $this->input->post("reqCp2Name");
        $reqCp2Telp = $this->input->post("reqCp2Telp");
        $reqLa1Name = $this->input->post("reqLa1Name");
        $reqLa1Address = $_POST["reqLa1Address"];
        $reqLa1Phone = $this->input->post("reqLa1Phone");
        $reqLa1Fax = $this->input->post("reqLa1Fax");
        $reqLa1Email = $this->input->post("reqLa1Email");
        $reqLa1Cp1 = $this->input->post("reqLa1Cp1");
        $reqLa1Cp2 = $this->input->post("reqLa1Cp2");
        $reqLa2Name = $this->input->post("reqLa2Name");
        $reqLa2Address = $_POST["reqLa2Address"];
        $reqLa2Telp = $this->input->post("reqLa2Telp");
        $reqLa2Fax = $this->input->post("reqLa2Fax");
        $reqLa2Email = $this->input->post("reqLa2Email");
        $reqLa2Cp1 = $this->input->post("reqLa2Cp1");
        $reqLa2Cp2 = $this->input->post("reqLa2Cp2");
        $reqLa1Cp1Phone = $this->input->post("reqLa1Cp1Phone");
        $reqLa1Cp2Phone = $this->input->post("reqLa1Cp2Phone");
        $reqLa2Cp1Phone = $this->input->post("reqLa2Cp1Phone");
        $reqLa2Cp2Phone = $this->input->post("reqLa2Cp2Phone");
        $reqTipe = $this->input->post("reqTipe");

        $reqProvinsi= $this->input->post("reqProvinsi");
        $combo_kabupaten= $this->input->post("combo_kabupaten");


        $company->setField("COMPANY_ID", $reqId);
        $company->setField("NAME", $reqName);
        $company->setField("ADDRESS", $reqAddress);
        $company->setField("PHONE", $reqPhone);
        $company->setField("FAX", $reqFax);
        $company->setField("EMAIL", $reqEmail);
        $company->setField("CP1_NAME", $reqCp1Name);
        $company->setField("CP1_TELP", $reqCp1Telp);
        $company->setField("CP2_NAME", $reqCp2Name);
        $company->setField("CP2_TELP", $reqCp2Telp);
        $company->setField("LA1_NAME", $reqLa1Name);
        $company->setField("LA1_ADDRESS", $reqLa1Address);
        $company->setField("LA1_PHONE", $reqLa1Phone);
        $company->setField("LA1_FAX", $reqLa1Fax);
        $company->setField("LA1_EMAIL", $reqLa1Email);
        $company->setField("LA1_CP1", $reqLa1Cp1);
        $company->setField("LA1_CP2", $reqLa1Cp2);
        $company->setField("LA2_NAME", $reqLa2Name);
        $company->setField("LA2_ADDRESS", $reqLa2Address);
        $company->setField("LA2_TELP", $reqLa2Telp);
        $company->setField("LA2_FAX", $reqLa2Fax);
        $company->setField("LA2_EMAIL", $reqLa2Email);
        $company->setField("LA2_CP1", $reqLa2Cp1);
        $company->setField("LA2_CP2", $reqLa2Cp2);
        $company->setField("LA1_CP1_PHONE", $reqLa1Cp1Phone);
        $company->setField("LA1_CP2_PHONE", $reqLa1Cp2Phone);
        $company->setField("LA2_CP1_PHONE", $reqLa2Cp1Phone);
        $company->setField("LA2_CP2_PHONE", $reqLa2Cp2Phone);
        $company->setField("TIPE", $reqTipe);




        if (empty($reqId) || $reqId=='0') {
            $company->insert();
            $reqId  = $company->id;
        } else {
            $company->update();
        }

        $company->setField("COMPANY_ID", $reqId);
        $company->setField("PROPINSI_ID", $reqProvinsi);
        $company->setField("KABUPATEN_ID", $combo_kabupaten);
        $company->update_location();

        $reqSupplier = $this->input->post('reqSupplier');
        $reqBarangDisuplay = $this->input->post('reqBarangDisuplay');
        $reqTingkatPelayanan = $this->input->post('reqTingkatPelayanan');
        $reqKualitas = $this->input->post('reqKualitas');
        $reqKeterangan = $this->input->post('reqKeterangan');
        $reqHargaKet = $this->input->post('reqHargaKet');
        if($reqSupplier=='SUPPLIER'){
           $company->setField("COMPANY_ID", $reqId);
           $company->setField("KATEGORI", $reqSupplier);
           $company->setField("BARAG_JASA", $reqBarangDisuplay);
           $company->setField("TINGKAT_PELAYANG", $reqTingkatPelayanan);
           $company->setField("KUALITAS", $reqKualitas);
           $company->setField("KETERANGAN_SUB", $reqKeterangan);
           $company->setField("HARGA_KET", $reqHargaKet);
                 
             $company->updateSupplier();
             $company-> updateSupplierLain();
            

             // $this->tambah_data_barang($reqId);
             // $this->tambah_data_part($reqId);
             $this->vendor_code($reqId);
        }


       return $reqId;
    }

      function vendor_code($reqId){
        $this->load->model('VendorCode');
        $reqType = $this->input->post('reqType');
         $reqLoc = $this->input->post('reqLoc');
         $reqCek = $this->input->post('reqCek');
          $tahun = date('Y');
        $vendorcode = new VendorCode();
        $vendorcode->selectByParamsMonitoring(array('A.STATUS_AKTIF'=>'1','A.TAHUN'=>$tahun),-1,-1);
        $total_row = $vendorcode->rowCount;
        $arrDataVendor = $vendorcode->rowResult;
       
        $reqCodeTahun = substr($tahun,2);
        $arrData =  multi_array_search($arrDataVendor,array('status_aktif'=>1,'supplier_id'=>$reqId));
        $total = count($arrData);
        $reqCode = explode('_', $arrData[0]['kode']);
         $reqCodeK = substr($reqCode[2],2);
         $reqCodeT = substr($reqCode[2],0,2);
          $reqCodeUrut = intval($reqCode[3]) ;
          $reqCodeK = intval($reqCodeK)+1;
          $reqCodeK  = get_null_10($reqCodeK);
        if($total == 0){
            $reqCodeK ='00';
            $reqCodeT =   $reqCodeTahun;
            $reqCodeUrut = $total_row+1;
        }
       
      
       
       $reqKode = $reqCodeT.$reqCodeK;
       $reqPenomoran = $reqType.'_'.$reqLoc.'_'.$reqKode.'_'.sprintf("%03d", $reqCodeUrut);

       $vendorcode = new VendorCode();
       $vendorcode->setField('KODE', $reqPenomoran);
       $vendorcode->setField('TYPE', $reqType);
       $vendorcode->setField('AREA', $reqLoc);
       $vendorcode->setField('SUPPLIER_ID', $reqId);
       $vendorcode->setField('TAHUN', $tahun);
        $vendorcode->setField('REV', $reqCodeK);
       $vendorcode->setField('STATUS_AKTIF', '1');
       if($total == 0 ){

         $vendorcode->insert();

       }else{

            if( $reqCek=='1'){
               $vendorcode->updateStatus();
               $vendorcode->insert();
            }

       }
      

    }

}
