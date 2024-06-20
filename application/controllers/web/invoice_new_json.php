<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");

class invoice_new_json extends CI_Controller
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
		$this->load->model('InvoiceNew');
	
		$reqId =  $this->input->post('reqId');
		$reqInvoiceNumber =  $this->input->post('reqInvoiceNumber');
		$reqNoPo =  $this->input->post('reqNoPo');
		$reqInvoiceDate =  $this->input->post('reqInvoiceDate');
		$reqCompanyId =  $this->input->post('reqCompanyId');
		$reqDaysInvoice =  $this->input->post('reqDaysInvoice');
		$reqPoDate =  $this->input->post('reqPoDate');
		$reqDays =  $this->input->post('reqDays');
		$reqTerms =  $this->input->post('reqTerms');
		$reqRoCheck =  $this->input->post('reqRoCheck');
		$reqRoNomer =  $this->input->post('reqRoNomer');
		$reqRoDate =  $this->input->post('reqRoDate');
		$reqPpn =  $this->input->post('reqPpn'); 
		$reqPpnPercent =  $this->input->post('reqPpnPercent');
		$reqDP =  $this->input->post('reqDP'); 
		$reqJenisPPh =  $this->input->post('reqJenisPPh');
		$reqPPH =  $this->input->post('reqPPH'); 
		$reqPpnPercentPPh =  $this->input->post('reqPpnPercentPPh');
		$reqOpsiPpn =  $this->input->post('reqOpsiPpn'); 
		$reqNominalPpn =  $this->input->post('reqNominalPpn');
		$reqInvoiceDatePayment =  $this->input->post('reqInvoiceDatePayment');
		$reqStatus =  $this->input->post('reqStatus');
		$reqDeskriptionPayment =  $this->input->post('reqDeskriptionPayment');
		$reqDescriptionProject =  $_POST['reqDescriptionProject'];
		$reqAmount =  $this->input->post('reqAmount'); 
		$reqCurrencys =  $this->input->post('reqCurrencys');
		$reqQuantity =  $this->input->post('reqQuantity');
		$reqRemarkInvoiceDetail =  $_POST['reqRemarkInvoiceDetail'];
		$reqInvoiceTax =  $this->input->post('reqInvoiceTax');
		$reqTaxCheck  =  $this->input->post('reqTaxCheck');
		$reqTaxCodePpn = $this->input->post('reqTaxCodePpn');
		$reqQuantityItem = $this->input->post('reqQuantityItem');

		$invoice_new = new InvoiceNew();
		$invoice_new->setField("INVOICE_NEW_ID", $reqId);
		$invoice_new->setField("HPP_PROJECT_ID", $reqHppProjectId);
		$invoice_new->setField("NOMER", $reqInvoiceNumber);
		$invoice_new->setField("PO_NOMER", $reqNoPo);
		$invoice_new->setField("COMPANY_ID", ValToNullDB($reqCompanyId));
		$invoice_new->setField("TAX_INVOICE", $reqTaxCheck);
		$invoice_new->setField("TAX_INVOICE_NOMINAL", $reqInvoiceTax);
		$invoice_new->setField("INVOICE_DATE", dateToDBCheck($reqInvoiceDate));
		$invoice_new->setField("INVOICE_DAY", $reqDaysInvoice);
		$invoice_new->setField("PO_DATE", dateToDBCheck($reqPoDate));
		$invoice_new->setField("PO_DAY", $reqDays);
		$invoice_new->setField("REPORT_ID", ValToNullDB($reqReportId));
		$invoice_new->setField("TERN_CONDITION", $reqTerms);
		$invoice_new->setField("RO_CHECK", $reqRoCheck);
		$invoice_new->setField("RO_NUMBER", $reqRoNomer);
		$invoice_new->setField("RO_DATE", dateToDBCheck($reqRoDate));
		$invoice_new->setField("PPN", $reqPpn);
		$invoice_new->setField("PPN_PERCEN", $reqPpnPercent);
		$invoice_new->setField("ADV_PAYMENT", ifZero2(dotToNo($reqDP)));
		$invoice_new->setField("PPH", $reqPPH);
		$invoice_new->setField("PPH_JENIS", $reqJenisPPh);
		$invoice_new->setField("PPH_CURRENCY", $reqPpnPercentPPh);
		$invoice_new->setField("MANUAL_PPN", $reqOpsiPpn);
		$invoice_new->setField("MANUAL_PPN_NOMINAL", ifZero2(dotToNo($reqNominalPpn)));
		$invoice_new->setField("STATUS_INVOICE", $reqStatus);
		$invoice_new->setField("PAYMENT_DATE", dateToDBCheck($reqInvoiceDatePayment));
		$invoice_new->setField("DESKRIPSI_PAYMENT", $reqDeskriptionPayment);
		$invoice_new->setField("CODE_TAX", $reqTaxCodePpn);
	
		$invoice_new->setField("TYPE_PROJECT", $reqTypeProject);
		$invoice_new->setField("DESKRIPSI", $reqDescriptionProject);
		$invoice_new->setField("AMOUNT", ifZero2(dotToNo($reqAmount)));
		$invoice_new->setField("CURRENCY", $reqCurrencys);
		$invoice_new->setField("QUANTITY", $reqQuantity);
		$invoice_new->setField("ITEM", $reqQuantityItem);
		$invoice_new->setField("NOTE", $reqRemarkInvoiceDetail);

		if(empty($reqId)){
			$invoice_new->insert();
			$reqId= $invoice_new->id;
		}else{
			$invoice_new->update();
		}

		$this->load->library("FileHandler");
            $file = new FileHandler();
             $FILE_DIR = "uploads/invoice_new/" . $reqId . "/";
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

        $invoice_new->setField("INVOICE_NEW_ID", $reqId);
        $invoice_new->setField("LAMPIRAN", setQuote($str_name_path));
        $invoice_new->updateLampiran();

		 echo $reqId.'-Data berhasil di simpan';
	}

	function delete(){
		$this->load->model('InvoiceNew');
		$reqId = $this->input->get('reqId');
		$invoice_new = new InvoiceNew();
		$invoice_new->setField('INVOICE_NEW_ID',$reqId);
		$invoice_new->delete();
		echo 'Data berhasil di hapus';

	}

}
