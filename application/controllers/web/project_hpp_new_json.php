<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class project_hpp_new_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			redirect('login');
		}
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
		$this->MENUWAREHOUSE = $this->kauth->getInstance()->getIdentity()->MENUWAREHOUSE;
	}

	function add(){
		$this->load->model('ProjectHppNew');
		$this->load->model('ProjectHppNewD');
		
		$reqId = $this->input->post('reqId');
		$reqHppProjectNo = $this->input->post('reqHppProjectNo');
		$reqLocation = $this->input->post('reqLocation');
		$reqCode = $this->input->post('reqCode');
		$reqDateProject = $this->input->post('reqDateProject');
		$reqBulanHpp = $this->input->post('reqBulanHpp');
		$reqCompanyId = $this->input->post('reqCompanyId');
		$reqEstimasiPekerjaan = $this->input->post('reqEstimasiPekerjaan');
		$reqForApproved = $this->input->post('reqForApproved');


 		$reqHppDetailId = $this->input->post('reqHppDetailId');
		$reqHppUrut = $this->input->post('reqHppUrut');
		$reqHppDetailKeterangan = $this->input->post('reqHppDetailKeterangan');
		$reqHppCode = $this->input->post('reqHppCode');
		$reqHppPart = $this->input->post('reqHppPart');
 		$reqQtyK = $this->input->post('reqQtyK');
 		$reqDayK = $this->input->post('reqDayK');
 		$reqStatusK = $this->input->post('reqStatusK');
 		$reqHargaSatuanK = $this->input->post('reqHargaSatuanK');
 		$reqTotalHarianK = $this->input->post('reqTotalHarianK');
 		$reqTotalBulananK = $this->input->post('reqTotalBulananK');
 		$reqQtyM = $this->input->post('reqQtyM');
 		$reqDayM = $this->input->post('reqDayM');
 		$reqStatusM = $this->input->post('reqStatusM');
 		$reqTotalHarianM = $this->input->post('reqTotalHarianM');
 		$reqTotalBulananM = $this->input->post('reqTotalBulananM');
 		$reqNoPoContract = $this->input->post('reqNoPoContract');
 		$reqNamaProjectHpp = $this->input->post('reqNamaProjectHpp');


 		$grandTotalPengeluaranBualanan = $this->input->post('grandTotalPengeluaranBualanan');
 		$grandTotalPengeluaranHarian = $this->input->post('grandTotalPengeluaranHarian');
 		$grandTotalPemasukanBulanan = $this->input->post('grandTotalPemasukanBulanan');
 		$grandTotalPemasukanHarian = $this->input->post('grandTotalPemasukanHarian');
 		$grandTotalProfit = $this->input->post('grandTotalProfit');
 		$grandTotalPengeluaranAbcd = $this->input->post('grandTotalPengeluaranAbcd');
 		$grandTotalPemasukanAbcd = $this->input->post('grandTotalPemasukanAbcd');
 		$grandTotalPengeluaranEf = $this->input->post('grandTotalPengeluaranEf');
 		$grandTotalPemasukanEf = $this->input->post('grandTotalPemasukanEf');

 		// echo  $grandTotalPengeluaranBualanan;exit;

 		$project_hpp_new = new ProjectHppNew();
 		$project_hpp_new->setField("PROJECT_HPP_NEW_ID", $reqId);
 		$project_hpp_new->setField("NOMER", $reqHppProjectNo);
 		$project_hpp_new->setField("NAMA", $reqNama);
 		$project_hpp_new->setField("CODE", $reqCode);
 		$project_hpp_new->setField("LOKASI", $reqLocation);
 		$project_hpp_new->setField("HPP_DATE", $reqBulanHpp);
 		$project_hpp_new->setField("TANGGAL", dateToDBCheck($reqDateProject));
 		$project_hpp_new->setField("COMPANY_ID", ValToNullDB($reqCompanyId));
 		$project_hpp_new->setField("ESTIMASI", $reqEstimasiPekerjaan);
 		$project_hpp_new->setField("APPROVED", $reqForApproved);
 		$project_hpp_new->setField("NO_PO_CONTRACT", $reqNoPoContract);
 		$project_hpp_new->setField("NAMA_PROJECT", $reqNamaProjectHpp);
 		$project_hpp_new->setField("KELUAR_BULANAN", ifZero2(dotToNo($grandTotalPengeluaranBualanan)));
 		$project_hpp_new->setField("KELUAR_HARIAN",  ifZero2(dotToNo($grandTotalPengeluaranHarian)));
 		$project_hpp_new->setField("PEMASUKAN_BULANAN",ifZero2(dotToNo($grandTotalPemasukanBulanan)));
 		$project_hpp_new->setField("PEMASUKAN_HARIAN", ifZero2(dotToNo($grandTotalPemasukanHarian)));
 		$project_hpp_new->setField("PROFIT", ifZero2(dotToNo($grandTotalProfit)));
 		$project_hpp_new->setField("PENGELUARANABCD", ifZero2(dotToNo($grandTotalPengeluaranAbcd)));
 		$project_hpp_new->setField("PENGELUARANEF", ifZero2(dotToNo($grandTotalPemasukanAbcd)));
 		$project_hpp_new->setField("PEMASUKANABCD", ifZero2(dotToNo($grandTotalPengeluaranEf)));
 		$project_hpp_new->setField("PEMASUKANEF", ifZero2(dotToNo($grandTotalPemasukanEf)));




 		if(empty($reqId)){
 			$project_hpp_new->insert();
 			$reqId = $project_hpp_new->id;
 		}else{
 				$project_hpp_new->update();
 		}

 		for ($i=0;$i<count($reqHppDetailId);$i++) {
 			$project_hpp_new_d = new ProjectHppNewD();
 			$project_hpp_new_d->setField("PROJECT_HPP_NEW_D_ID", $reqHppDetailId[$i]);
 			$project_hpp_new_d->setField("PROJECT_HPP_NEW_ID", $reqId);
 			$project_hpp_new_d->setField("URUT", $reqHppUrut[$i]);
 			$project_hpp_new_d->setField("CODE", $reqHppCode[$i]);
 			$project_hpp_new_d->setField("PART", $reqHppPart[$i]);
 			$project_hpp_new_d->setField("KETERANGAN", $reqHppDetailKeterangan[$i]);
 			$project_hpp_new_d->setField("K_QTY", ifZero2($reqQtyK[$i]));
 			$project_hpp_new_d->setField("K_DAY", ifZero2($reqDayK[$i]));
 			$project_hpp_new_d->setField("K_STATUS", $reqStatusK[$i]);
 			$project_hpp_new_d->setField("K_HARGA", ifZero2(dotToNo($reqHargaSatuanK[$i])));
 			$project_hpp_new_d->setField("K_TOTAL", ifZero2(dotToNo($reqTotalHarianK[$i])));
 			$project_hpp_new_d->setField("K_BULANAN", ifZero2(dotToNo($reqTotalBulananK[$i])));
 			$project_hpp_new_d->setField("P_QTY", ifZero2($reqQtyM[$i]));
 			$project_hpp_new_d->setField("P_DAY", ifZero2($reqDayM[$i]));
 			$project_hpp_new_d->setField("P_STATUS", $reqStatusM[$i]);
 			$project_hpp_new_d->setField("P_HARGA", ifZero2(dotToNo($reqTotalHarianM[$i])));
 			$project_hpp_new_d->setField("P_BULANAN", ifZero2(dotToNo($reqTotalBulananM[$i])));
 			if(empty($reqHppDetailId[$i])){
 				$project_hpp_new_d->insert();
 			}else{
 				$project_hpp_new_d->update();
 			}
 		}

 		$this->connectServiceOrder($reqId);
 		$this->connectInvoiceNews($reqId);

 		echo $reqId.'- Data berhasil di simpan';

	}

	function connectInvoiceNews($reqId){
		$this->load->model("InvoiceNew");
		$serviceordernew = new InvoiceNew();
		$serviceordernew->selectByParamsMonitoring(array('A.HPP_PROJECT_ID'=>$reqId,'A.DARI'=>'HPP'));
		$arrdata = $serviceordernew->rowResult;
		$arrdata= $arrdata[0];

		$total = $serviceordernew->rowCount;
		$reqCompanyId = $this->input->post('reqCompanyId');
		$serviceordernew->setField('COMPANY_ID',$reqCompanyId);
		$serviceordernew->setField('HPP_PROJECT_ID',$reqId);
		if($total==0){
			$serviceordernew->insertFromHpp();
		}else{
			$serviceordernew->updateFromHpp();
		}

	}
	function connectServiceOrder($reqId){
			$this->load->model('ServiceOrderNew');
			$serviceordernew = new ServiceOrderNew();
			$serviceordernew->selectByParamsMonitoring(array('A.HPP_PROJECT_ID'=>$reqId,'A.DARI'=>'HPP'));
			$total = $serviceordernew->rowCount;
			$reqCompanyId = $this->input->post('reqCompanyId');
			$serviceordernew->setField('COMPANY_ID',$reqCompanyId);
			$serviceordernew->setField('HPP_PROJECT_ID',$reqId);
			if($total==0){
				$serviceordernew->insertFromHpp();
			}else{
				$serviceordernew->updateFromHpp();
			}


	}
	function ambil_detail(){
		$this->load->model('ProjectHppNew');
		$reqId = $this->input->get('reqId');
		$projecthppnew = new ProjectHppNew();
		$projecthppnew->selectByParamsMonitoring(array('A.PROJECT_HPP_NEW_ID'=>$reqId));
		$arrData = $projecthppnew->rowResult;
		$arrData = $arrData[0];
		echo json_encode($arrData);
	}

	function delete(){
		$this->load->model('ProjectHppNew');
		$this->load->model('ServiceOrderNew');
		$this->load->model('InvoiceNew');
		
		$reqId = $this->input->get('reqId');
		$projecthppnew = new ProjectHppNew();
		$projecthppnew->setField('PROJECT_HPP_NEW_ID',$reqId);
		$projecthppnew->delete();
		$projecthppnew = new ServiceOrderNew();
		$projecthppnew->setField('HPP_PROJECT_ID',$reqId);
		$projecthppnew->deleteFormHpp();
		$invoicenew = new InvoiceNew();
		$invoicenew->setField('HPP_PROJECT_ID',$reqId);
		$invoicenew->deleteFormHpp();
		
		echo 'Data berhasil di hapus';
	}

}
