<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class combo_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		//kauth
		if (!$this->kauth->getInstance()->hasIdentity()) {
			// trow to unauthenticated page!
			redirect('login');
		}
	}

	function combo_tree_view(){
		$this->load->model("MasterTenerMenus");
		$this->load->model("TenderEvaluation");
		$val   = $this->input->get("reqKategoriId");
		$arrKategoriId = explode(',', $val);
		$master_tener_menus = new MasterTenerMenus();
		$master_tener_menus->selectByParamsMonitoring(array());
		$attData = array();
		while ( $master_tener_menus->nextRow()) {
			array_push($attData , strtoupper($master_tener_menus->getField('NAMA')));
		}
		 $aColumns = array(
            "LAST_UPDATE","INDEX","NAMA_PSC","TITLE","TENDER_NO","CLOSING","OPENING"
        );
		$aColumns= array_merge($aColumns,$attData);
		$arDataOther = array("STATUS","OWNER","BID_VALUE","TKDN","BID_BOUDS","BID_VALIDATY","NOTES");
		$aColumns=    array_merge($aColumns,$arDataOther);
		// print_r($aColumns);

		$i=0;
		$arr_json[$i]['id']='ALL';
 		$arr_json[$i]['text']='ALL';
 		if (in_array('ALL', $arrKategoriId)) {
 				$arr_json[$i]['checked'] = "true";
 			}

 		$arrJson2= array();
		for($k=0;$k<count($aColumns);$k++){
			$arrJson2[$k]['id']=$aColumns[$k];
 			$arrJson2[$k]['text']=$aColumns[$k];
 			if (in_array($aColumns[$k], $arrKategoriId)) {
 				$arrJson2[$k]['checked'] = "true";
 			}
		}
	

		$arr_json[$i]['children']	= $arrJson2;
		echo json_encode($arr_json);
	}

	function combo_fail(){
			$i=0;
			$arr_json[$i]['id']='Failed';
 			$arr_json[$i]['text']='Failed';
 			$i++;
 			$arr_json[$i]['id']='Decline';
 			$arr_json[$i]['text']='Decline';
 			$i++;
 			echo json_encode($arr_json);
	}

	function combo_pcs(){
		$this->load->model("MasterPsc");
		$master_psc = new MasterPsc();
		$arr_json = array();
		$master_psc->selectByParamsMonitoring(array());
		$i=0;
 		while ($master_psc->nextRow()) {
 			# code...
 		
 			$arr_json[$i]['id']=$master_psc->getField("MASTER_PSC_ID");
 			$arr_json[$i]['text']=$master_psc->getField("NAMA");
 		$i++;	
 		}

 		echo json_encode($arr_json);
	}

	function combo_master_surveyor(){
		$this->load->model("MasterSurveyor");
		$master_psc = new MasterSurveyor();

		$reqMode = $this->input->get('reqMode');
		$arr_json = array();
		$master_psc->selectByParamsMonitoring(array());
		$i=0;
		if($reqMode=='ALL'){
			$arr_json[$i]['id']='';
 			$arr_json[$i]['text']='ALL';
 			$i++;
		}
 		while ($master_psc->nextRow()) {
 			# code...
 		
 			$arr_json[$i]['id']=$master_psc->getField("MASTER_SURVEYOR_ID");
 			$arr_json[$i]['text']=$master_psc->getField("NAMA");
 		$i++;	
 		}

 		echo json_encode($arr_json);
	}

	function contact_support(){
		$this->load->model("CostumerSupport");
		$costumer_support = new CostumerSupport();
		$reqCompanyId = $this->input->get('reqCompanyId');
		$costumer_support->selectByParamsMonitoring(array("CAST(A.COMPANY_ID AS VARCHAR)"=>$reqCompanyId));
 		$i=0;
 		$arr_json = array();
 		while ($costumer_support->nextRow()) {
 			# code...
 		
 			$arr_json[$i]['id']=$costumer_support->getField("COSTUMER_SUPPORT_ID");
 			$arr_json[$i]['text']=$costumer_support->getField("NAMA");
 		$i++;	
 		}

 		echo json_encode($arr_json);
	}


	function comboSurveryour(){
		$this->load->model('CostProject');
		$cost_project = new CostProject();
		$cost_project->selectByParamsMonitoring(array());
		$arrData = array();
		while ($cost_project->nextRow()) {
 				array_push($arrData, $cost_project->getField('SURVEYOR'));
 		}
 		// print_r($arrData);
 		$arrrUnix =array_unique($arrData);
 		sort($arrrUnix);
 		
 		$i=0;
 		$arr_json = array();
 		foreach($arrrUnix as $x => $val) {
 			$arr_json[$i]['id']=$val;
 			$arr_json[$i]['text']=$val;
 		$i++;	
 		}

 		echo json_encode($arr_json);
	}

	function combo_lokasiNama(){
			$this->load->model('MasterLokasi');
			$master_lokasi = new MasterLokasi();
			$master_lokasi->selectByParamsMonitoring(array());
			$reqMode = $this->input->get('reqMode');
				$arr_json = array();
			$i=0;
			if($reqMode=='All'){
					$arr_json[$i]['id'] ='';
				$arr_json[$i]['text']='All';
				$i++;
			}

		
			while ( $master_lokasi->nextRow()) {
				$arr_json[$i]['id'] =$master_lokasi->getField("NAMA");
				$arr_json[$i]['text']=$master_lokasi->getField("NAMA");
				$i++;
			}
			echo json_encode($arr_json);
	}

	function combo_lokasi(){
			$this->load->model('MasterLokasi');
			$master_lokasi = new MasterLokasi();
			$master_lokasi->selectByParamsMonitoring(array());
			$reqMode = $this->input->get('reqMode');
			$i=0;
			if($reqMode=='All'){
					$arr_json[$i]['id'] ='';
				$arr_json[$i]['text']='All';
				$i++;
			}

			$arr_json = array();
			while ( $master_lokasi->nextRow()) {
				$arr_json[$i]['id'] =$master_lokasi->getField("MASTER_LOKASI_ID");
				$arr_json[$i]['text']=$master_lokasi->getField("NAMA");
				$i++;
			}
			echo json_encode($arr_json);
	}
		function combo_reason(){
			$this->load->model('MasterReason');
			$master_lokasi = new MasterReason();
			$master_lokasi->selectByParamsMonitoring(array());
			$reqMode = $this->input->get('reqMode');
			$i=0;	
			$arr_json = array();
			if($reqMode=='All'){
				$arr_json[$i]['id'] ='';
				$arr_json[$i]['text']='All';
				$i++;
			}
			while ( $master_lokasi->nextRow()) {
				$arr_json[$i]['id'] =$master_lokasi->getField("MASTER_REASON_ID");
				$arr_json[$i]['text']=$master_lokasi->getField("NAMA");
				$i++;
			}
			echo json_encode($arr_json);
	}

	function comboOperator(){
		$this->load->model('CostProject');
		$cost_project = new CostProject();
		
		$cost_project->selectByParamsMonitoring(array());
		$arrData = array();
		while ($cost_project->nextRow()) {
 				array_push($arrData, $cost_project->getField('OPERATOR'));
 		}
 		// print_r($arrData);
 		$arrrUnix =array_unique($arrData);
 		sort($arrrUnix);

 		$i=0;
 		$arr_json = array();
 		foreach($arrrUnix as $x => $val) {
 			$arr_json[$i]['id']=$val;
 			$arr_json[$i]['text']=$val;
 		$i++;	
 		}

 		echo json_encode($arr_json);
	}
	function combo_offer(){

        $this->load->model("Offer");
        $offer = new Offer();
        $offer->selectByParams(array());
        $i=0;
        while ( $offer->nextRow()) {
        	$arr_json[$i]['id'] =$offer->getField("NO_ORDER");
        	$arr_json[$i]['text']=$offer->getField("NO_ORDER");
        $i++;		
        }
        echo json_encode($arr_json);
	}
	function ComboBulan(){
		// $i = 0;
		for($i=1;$i<=12;$i++){
		$arr_json[($i-1)]['id'] =getNameMonth($i);
		$arr_json[($i-1)]['text']=getNameMonth($i);
		}
		echo json_encode($arr_json);
	}
	function ComboBulanId2(){
		// $i = 0;
		

		for($i=1;$i<=12;$i++){
		$arr_json[($i-1)]['id'] =get_null_10($i);
		$arr_json[($i-1)]['text']=getNameMonth($i);
		}
		echo json_encode($arr_json);
	}
	function ComboBulanId(){
		// $i = 0;
		

		for($i=1;$i<=12;$i++){
		$arr_json[($i-1)]['id'] =get_null_10($i);
		$arr_json[($i-1)]['text']=getNameMonth($i);
		}
		echo json_encode($arr_json);
	}
	function ComboBulanAll(){
		// $i = 0;
		$i = 0;
		$arr_json[$i]['id']		= "";
		$arr_json[$i]['text']	= "ALL";
		for($i=1;$i<=12;$i++){
		$arr_json[($i)]['id'] =getNameMonth($i);
		$arr_json[($i)]['text']=getNameMonth($i);
		}
		echo json_encode($arr_json);
	}
	function ComboBulanAllBaru(){
		// $i = 0;
		$i = 0;
		$arr_json[$i]['id']		= "";
		$arr_json[$i]['text']	= "ALL";
		for($i=1;$i<=12;$i++){
		$arr_json[($i)]['id'] =get_null_10($i);
		$arr_json[($i)]['text']=getNameMonth($i);
		}
		echo json_encode($arr_json);
	}

	function comboTypePo(){
		$i = 0;

		$this->load->model("IssuePoType");
		$issue_po_type  = new IssuePoType();
		$issue_po_type->selectByParamsMonitoring(array());
		while ($issue_po_type->nextRow()) {
			$arr_json[$i]['id']		= $issue_po_type->getField("NAME");
			$arr_json[$i]['text']	= $issue_po_type->getField("NAME");
			$i++;
		}
		echo json_encode($arr_json);
	}

	function comboTypePersiapan(){
		$i = 0;

		$this->load->model("TenderTypeUpload");
		$tender_type_upload  = new TenderTypeUpload();
		$tender_type_upload->selectByParamsMonitoring(array("TYPE" => "Persiapan"));
		while ($tender_type_upload->nextRow()) {
			$arr_json[$i]['id']		= $tender_type_upload->getField("TENDER_TYPE_UPLOAD_ID");
			$arr_json[$i]['text']	= $tender_type_upload->getField("NAME");
			$i++;
		}
		echo json_encode($arr_json);
	}

	function comboTypePelaksanaan(){
		$i = 0;

		$this->load->model("TenderTypeUpload");
		$tender_type_upload  = new TenderTypeUpload();
		$tender_type_upload->selectByParamsMonitoring(array("TYPE" => "Pelaksanaan"));
		while ($tender_type_upload->nextRow()) {
			$arr_json[$i]['id']		= $tender_type_upload->getField("TENDER_TYPE_UPLOAD_ID");
			$arr_json[$i]['text']	= $tender_type_upload->getField("NAME");
			$i++;
		}
		echo json_encode($arr_json);
	}

	function comboTypePenyelesaian(){
		$i = 0;

		$this->load->model("TenderTypeUpload");
		$tender_type_upload  = new TenderTypeUpload();
		$tender_type_upload->selectByParamsMonitoring(array("TYPE" => "Penyelesaian"));
		while ($tender_type_upload->nextRow()) {
			$arr_json[$i]['id']		= $tender_type_upload->getField("TENDER_TYPE_UPLOAD_ID");
			$arr_json[$i]['text']	= $tender_type_upload->getField("NAME");
			$i++;
		}
		echo json_encode($arr_json);
	}
	function comboOpsiPpn(){
		$i = 0;
		$arr_json[$i]['id']		= "Tidak";
		$arr_json[$i]['text']	= "Tidak";
		$i++;
		$arr_json[$i]['id']		= "Ya";
		$arr_json[$i]['text']	= "Ya";
		$i++;

		echo json_encode($arr_json);

	}

	function comboAnnouncement()
	{
		$i = 0;
		$arr_json[$i]['id']		= "Pass";
		$arr_json[$i]['text']	= "Pass";
		$i++;
		$arr_json[$i]['id']		= "On Progress";
		$arr_json[$i]['text']	= "On Progress";
		$i++;
		$arr_json[$i]['id']		= "Fail";
		$arr_json[$i]['text']	= "Fail";
		$i++;


		echo json_encode($arr_json);
	}

	function comboAnnouncementProject()
	{
		$i = 0;
		$arr_json[$i]['id']		= "Fail";
		$arr_json[$i]['text']	= "Fail";
		$i++;
		$arr_json[$i]['id']		= "Pass";
		$arr_json[$i]['text']	= "Pass";
		$i++;
		$arr_json[$i]['id']		= "On Progress";
		$arr_json[$i]['text']	= "On Progress";
		$i++;
		$arr_json[$i]['id']		= "Complete";
		$arr_json[$i]['text']	= "Complete";
		$i++;

		echo json_encode($arr_json);
	}

	function comboStatusReport()
	{
		$i = 0;
		$mode = $this->input->get('reqMode');
		if($mode=='ALL'){
			$arr_json[$i]['id']		= "";
			$arr_json[$i]['text']	= "ALL";
			$i++;

		}

		$arr_json[$i]['id']		= "Send";
		$arr_json[$i]['text']	= "Send";
		$i++;
		$arr_json[$i]['id']		= "Pending";
		$arr_json[$i]['text']	= "Pending";
		$i++;
		$arr_json[$i]['id']		= "Cancel";
		$arr_json[$i]['text']	= "Cancel";
		$i++;

		echo json_encode($arr_json);
	}
	function comboStatusWeekly()
	{
		$i = 0;
		$mode = $this->input->get('reqMode');
		if($mode=='ALL'){
			$arr_json[$i]['id']		= "";
			$arr_json[$i]['text']	= "ALL";
			$i++;

		}else{

		}

		
		$arr_json[$i]['id']		= "Complated";
		$arr_json[$i]['text']	= "Complated";
		$i++;
		$arr_json[$i]['id']		= "Progress";
		$arr_json[$i]['text']	= "Progress";
		$i++;
		$arr_json[$i]['id']		= "Not Respon";
		$arr_json[$i]['text']	= "Not Respon";
		$i++;

		echo json_encode($arr_json);
	}

	function comboRemarks()
	{
		$i = 0;
		$arr_json[$i]['id']		= "Lengkap";
		$arr_json[$i]['text']	= "Lengkap";
		$i++;
		$arr_json[$i]['id']		= "Tidak Lengkap";
		$arr_json[$i]['text']	= "Tidak Lengkap";
		$i++;

		echo json_encode($arr_json);
	}

	function comboSolusi()
	{
		$this->load->model('MasterSolusi');
		$penanggung_jawab = new MasterSolusi();
		$penanggung_jawab->selectByParamsMonitoring(array());
		$i = 0;
		while ($penanggung_jawab->nextRow()) {
			$arr_json[$i]['id']		= $penanggung_jawab->getField("MASTER_SOLUSI_ID");
			$arr_json[$i]['text']	= strtoupper($penanggung_jawab->getField("NAMA"));
			$i++;
		}
		echo json_encode($arr_json);
	}
	function comboDepartments()
	{
		$reqMode = $this->input->get("reqMode");	
		$this->load->model('Departement');
		$penanggung_jawab = new Departement();
		$penanggung_jawab->selectByParamsMonitoring(array());
		$i = 0;
		if($reqMode=='ALL'){
			$arr_json[$i]['id']		= '';
			$arr_json[$i]['text']	= 'ALL';
			$i++;
		}
		while ($penanggung_jawab->nextRow()) {
			$arr_json[$i]['id']		= $penanggung_jawab->getField("DEPARTEMENT_ID");
			$arr_json[$i]['text']	= strtoupper($penanggung_jawab->getField("NAMA"));
			$i++;
		}
		echo json_encode($arr_json);
	}
	function comboPenanggungJawab()
	{
		$this->load->model('PenanggungJawab');
		$penanggung_jawab = new PenanggungJawab();
		$penanggung_jawab->selectByParamsMonitoring(array());
		$i = 0;
		while ($penanggung_jawab->nextRow()) {
			$arr_json[$i]['id']		= $penanggung_jawab->getField("PENANGGUNG_JAWAB_ID");
			$arr_json[$i]['text']	= strtoupper($penanggung_jawab->getField("NAMA"));
			$i++;
		}
		echo json_encode($arr_json);
	}

	function comboQms()
	{
		$this->load->model('FormatQm');
		$jenis_kualifikasi = new FormatQm();
		$jenis_kualifikasi->selectByParamsMonitoring(array());
		$i = 0;
		while ($jenis_kualifikasi->nextRow()) {
			$arr_json[$i]['id']		= $jenis_kualifikasi->getField("FORMAT_ID");
			$arr_json[$i]['text']	= strtoupper($jenis_kualifikasi->getField("FORMAT"));
			$i++;
		}
		echo json_encode($arr_json);
	}

	function comboCondisi()
	{
		$i = 0;
		$arr_json[$i]['id']		= "Good";
		$arr_json[$i]['text']	= "Good";
		$i++;
		$arr_json[$i]['id']		= "Repair";
		$arr_json[$i]['text']	= "Repair";
		$i++;
		$arr_json[$i]['id']		= "Review";
		$arr_json[$i]['text']	= "Review";
		$i++;

		echo json_encode($arr_json);
	}

	function comboQmss()
	{
		$i = 0;
		$arr_json[$i]['id']		= "QMS";
		$arr_json[$i]['text']	= "QMS";
		$i++;
		$arr_json[$i]['id']		= "QPS";
		$arr_json[$i]['text']	= "QPS";


		echo json_encode($arr_json);
	}


	function comboValueClassOfSurvey()
	{
		$i = 0;
		$arr_json[$i]['id']		= "UW";
		$arr_json[$i]['text']	= "UW";
		$i++;
		$arr_json[$i]['id']		= "UT";
		$arr_json[$i]['text']	= "UT";
		$i++;
		$arr_json[$i]['id']		= "CRANE TEST";
		$arr_json[$i]['text']	= "CRANE TEST";
		$i++;
		$arr_json[$i]['id']		= "HULL REPAIR";
		$arr_json[$i]['text']	= "HULL REPAIR";
		$i++;
		echo json_encode($arr_json);
	}

	function personalCertificate(){
		$this->load->model('PersonalCertificate');
		$certificate = new PersonalCertificate();
		$certificate->selectByParamsMonitoring(array());
		$arrDatas = array();
		$no = 0;
		while ($certificate->nextRow()) {
			$arr_json[$no]['id']	     = $certificate->getField("CERTIFICATE_ID");
			$arr_json[$no]['text']   = $certificate->getField("CERTIFICATE");
			$no++;
		}
		echo json_encode($arr_json);
	}
	function comboValueKategori()
	{
		$i = 0;
		$arr_json[$i]['id']		= "BODY";
		$arr_json[$i]['text']	= "BODY";
		$i++;
		$arr_json[$i]['id']		= "FOOTER";
		$arr_json[$i]['text']	= "FOOTER";
		$i++;
		
		echo json_encode($arr_json);
	}

	function comboSatuan(){
		$i = 0;

		$this->load->model("Satuan");
		$satuan  = new Satuan();
		$satuan->selectByParamsMonitoring(array());
		while ($satuan->nextRow()) {
			$arr_json[$i]['id']		= $satuan->getField("NAME");
			$arr_json[$i]['text']	= $satuan->getField("NAME");
			$i++;
		}
		echo json_encode($arr_json);
	}

	function comboEvidance()
	{
		$i = 0;
		$arr_json[$i]['id']		= "Ada - Sesuai";
		$arr_json[$i]['text']	= "Ada - Sesuai";
		$i++;
		$arr_json[$i]['id']		= "Ada - Tidak sesuai";
		$arr_json[$i]['text']	= "Ada - Tidak sesuai";
		$i++;
		$arr_json[$i]['id']		= "Tidak ada";
		$arr_json[$i]['text']	= "Tidak ada";
		$i++;

		echo json_encode($arr_json);
	}

	function combo_cost_code()
	{

		$this->load->model("CostCode");
		$cost_code = new CostCode();
		$group_by = ' ORDER BY A.KODE ASC';
		$cost_code->selectByParamsMonitoring(array(), -1, -1, '', $group_by);
		$i = 0;
		while ($cost_code->nextRow()) {
			$arr_json[$i]['id']        = $cost_code->getField("KODE");
			$arr_json[$i]['text']    = $cost_code->getField("KODE") . ' - ' . $cost_code->getField("NAMA");
			$i++;
		}

		echo json_encode($arr_json);
	}

	function combo_categori_other()
	{
		$this->load->model('CategoryOther');
		$jenis_kualifikasi = new CategoryOther();
		$jenis_kualifikasi->selectByParamsMonitoring(array());
		$i = 0;
		while ($jenis_kualifikasi->nextRow()) {
			$arr_json[$i]['id']		= $jenis_kualifikasi->getField("CATEGORY_ID");
			$arr_json[$i]['text']	= strtoupper($jenis_kualifikasi->getField("CATEGORY"));
			$i++;
		}
		echo json_encode($arr_json);
	}

	function comboDokumentPath()
	{
		$this->load->model('Document');
		$jenis_kualifikasi = new Document();
		$jenis_kualifikasi->selectByParamsDocCategori(array());
		$i = 0;
		while ($jenis_kualifikasi->nextRow()) {
			$arr_json[$i]['id']		= $jenis_kualifikasi->getField("CATEGORY");
			$arr_json[$i]['text']	= strtoupper($jenis_kualifikasi->getField("CATEGORY"));
			$i++;
		}
		echo json_encode($arr_json);
	}

	function combo_jenis_kwalifikasi()
	{
		$mode = $this->input->get('mode');
		$this->load->model('JenisKualifikasi');
		$jenis_kualifikasi = new JenisKualifikasi();
		$jenis_kualifikasi->selectByParamsMonitoring(array());
		$i = 0;
		if($mode == "all")
		{
			$arr_json[$i]['id']		= "";
			$arr_json[$i]['text']	= "ALL";
			$i++;
		}
		while ($jenis_kualifikasi->nextRow()) {
			$arr_json[$i]['id']		= $jenis_kualifikasi->getField("JENIS_ID");
			$arr_json[$i]['text']	= strtoupper($jenis_kualifikasi->getField("JENIS"));
			$i++;
		}
		echo json_encode($arr_json);
	}

	function comboCertificate()
	{
		$this->load->model('Certificate');
		$certificate = new Certificate();
		$certificate->selectByParams(array());
		
		$i = 0;
		$reqMode = $this->input->get('reqMode');
		if($reqMode=='opsi'){
			$arr_json[$i]['id']		='';
			$arr_json[$i]['text']	= 'ALL';
			$i++;
		}

		while ($certificate->nextRow()) {
			$arr_json[$i]['id']		= $certificate->getField("CERTIFICATE_ID");
			$arr_json[$i]['text']	= strtoupper($certificate->getField("CERTIFICATE"));
			$i++;
		}
		echo json_encode($arr_json);
	}

	function comboValueClassOfVessel()
	{
		$i = 0;
		$reqMode = $this->input->get("reqMode");
		if($reqMode == "ALL"){
			$arr_json[$i]['id']		= "";
			$arr_json[$i]['text']	= "ALL";
			$i++;
		}
		
		$this->load->model("ClassOfVessel");
		$class_of_vessel = new ClassOfVessel();
		$class_of_vessel->selectByParamsMonitoring(array(), -1, -1, $statement);
		while ($class_of_vessel->nextRow()) {
			$arr_json[$i]['id']		= $class_of_vessel->getField("NAME");

			$arr_json[$i]['text']	= $class_of_vessel->getField("NAME");
			$i++;
			# code...
		}
		// $arr_json[$i]['id']		= "ABS";
		// $arr_json[$i]['text']	= "ABS";
		// $i++;
		// $arr_json[$i]['id']		= "BKI";
		// $arr_json[$i]['text']	= "BKI";
		// $i++;
		// $arr_json[$i]['id']		= "BV";
		// $arr_json[$i]['text']	= "BV";
		// $i++;
		// $arr_json[$i]['id']		= "DNV";
		// $arr_json[$i]['text']	= "DNV";
		// $i++;
		// $arr_json[$i]['id']		= "GL";
		// $arr_json[$i]['text']	= "GL";
		// $i++;
		// $arr_json[$i]['id']		= "LR";
		// $arr_json[$i]['text']	= "LR";
		// $i++;
		// $arr_json[$i]['id']		= "NK";
		// $arr_json[$i]['text']	= "NK";
		// $i++;
		// $arr_json[$i]['id']		= "RINA";
		// $arr_json[$i]['text']	= "RINA";


		// $i++;
		echo json_encode($arr_json);
	}

	function comboVessel()
	{
		$this->load->model("Vessel");
		$vessel = new Vessel();
		$reqId = $this->input->get("reqId");
		$i = 0;
		$vessel->selectByParamsMonitoring(array("A.COMPANY_ID" => $reqId));
		while ($vessel->nextRow()) {
			$arr_json[$i]['id']		= $vessel->getField("NAME");
			$arr_json[$i]['text']	= $vessel->getField("NAME");
			$i++;
		}

		echo json_encode($arr_json);
	}
	function ambil_all_tahun()
	{
		$tahun = Date('Y');
		$i = 0;
		$reqMode = $this->input->get('reqMode');
		if($reqMode=='ALL'){
				$arr_json[$i]['id']		= '';
		     	$arr_json[$i]['text']	= 'ALL';
		     	$i++;
		}

		for ($j =$tahun ; $j >= 2000; $j--) {
			$arr_json[$i]['id']		= $j;
			$arr_json[$i]['text']	= $j;
			$i++;
		}
		// $arr_json[$i]['id']		= "All Year";
		// $arr_json[$i]['text']	= "All Year";
		echo json_encode($arr_json);
	}



	function ambil_tahun()
	{
		$tahun = Date('Y');
		$i = 0;
		for ($j = 2017; $j <= $tahun; $j++) {
			$arr_json[$i]['id']		= $j;
			$arr_json[$i]['text']	= $j;
			$i++;
		}
		$arr_json[$i]['id']		= "All Year";
		$arr_json[$i]['text']	= "All Year";
		echo json_encode($arr_json);
	}
	function comboTypeContact()
	{
		// $i = 0;
		// $arr_json[$i]['id']		= "Company";
		// $arr_json[$i]['text']	= "Company";
		// $i++;
		// $arr_json[$i]['id']		= "Driver";
		// $arr_json[$i]['text']	= "Driver";
		// $i++;

		// $i++;
		$i = 0;
		
		$reqMode = $this->input->get("reqMode");
		
		if($reqMode == "ALL"){
			$arr_json[$i]['id']		= "ALL";
			$arr_json[$i]['text']	= "ALL";
			$i++;
		}

		
		$this->load->model("TypeContract");
		$type_contract = new TypeContract();
		$type_contract->selectByParamsMonitoring(array());
		while ( $type_contract->nextRow()) {
			$arr_json[$i]['id']		= $type_contract->getField("NAME");
			$arr_json[$i]['text']	= $type_contract->getField("NAME");
			# code...
			$i++;
		}
		echo json_encode($arr_json);
	}
	function comboValueTypeOfVessel()
	{
		$i = 0;
		$reqMode = $this->input->get("reqMode");
		if($reqMode == "ALL"){
			$arr_json[$i]['id']		= "";
			$arr_json[$i]['text']	= "ALL";
			$i++;
		}

		$this->load->model("TypeOfVessel");
		$type_of_vessel = new TypeOfVessel();
		$type_of_vessel->selectByParamsMonitoring(array());
		while ( $type_of_vessel->nextRow()) {
			$arr_json[$i]['id']		= $type_of_vessel->getField("NAME");
			$arr_json[$i]['text']	= $type_of_vessel->getField("NAME");
			# code...
			$i++;
		}

		// $arr_json[$i]['id']		= "Tanker";
		// $arr_json[$i]['text']	= "Tanker";
		// $i++;
		// $arr_json[$i]['id']		= "Bulk Carrier";
		// $arr_json[$i]['text']	= "Bulk Carrier";
		// $i++;
		// $arr_json[$i]['id']		= "Cargo";
		// $arr_json[$i]['text']	= "Cargo";
		// $i++;
		// $arr_json[$i]['id']		= "Tug Boat";
		// $arr_json[$i]['text']	= "Tug Boat";
		// $i++;
		// $arr_json[$i]['id']		= "Barge";
		// $arr_json[$i]['text']	= "Barge";
		// $i++;
		// $arr_json[$i]['id']		= "F.S.O";
		// $arr_json[$i]['text']	= "F.S.O";
		// $i++;
		// $arr_json[$i]['id']		= "Rig";
		// $arr_json[$i]['text']	= "Rig";
		// $i++;
		// $arr_json[$i]['id']		= "Container";
		// $arr_json[$i]['text']	= "Container";

		echo json_encode($arr_json);
	}


	function equipmentKategori(){
		$i = 0;

		$arr_json[$i]['id']		= "SCUBA";
		$arr_json[$i]['text']	= "SCUBA";
		$i++;
		$arr_json[$i]['id']		= "HEAD";
		$arr_json[$i]['text']	= "HEAD";
		$i++;
		$arr_json[$i]['id']		= "Underwater Camera";
		$arr_json[$i]['text']	= "Underwater Camera";
		$i++;
		$arr_json[$i]['id']		= "Auxiliary Equipment";
		$arr_json[$i]['text']	= "Auxiliary Equipment";
		$i++;

		echo json_encode($arr_json);

	}
	function equipmentStatus(){
		$i = 0;
		$reqMode = $this->input->get("reqMode");
		
		if($reqMode == "ALL"){
			$arr_json[$i]['id']		= "ALL";
			$arr_json[$i]['text']	= "ALL";
			$i++;
		}
		$arr_json[$i]['id']		= "Good";
		$arr_json[$i]['text']	= "Good";
		$i++;
		$arr_json[$i]['id']		= "Broken";
		$arr_json[$i]['text']	= "Broken";
		$i++;
		$arr_json[$i]['id']		= "Missing";
		$arr_json[$i]['text']	= "Missing";
		$i++;
		$arr_json[$i]['id']		= "Repair";
		$arr_json[$i]['text']	= "Repair";
		$i++;
		echo json_encode($arr_json);

	}
	function comboTimeOfTest()
	{
		$i = 0;

		$arr_json[$i]['id']		= "1";
		$arr_json[$i]['text']	= "Daily";
		$i++;
		$arr_json[$i]['id']		= "2";
		$arr_json[$i]['text']	= "Weekly";
		$i++;
		$arr_json[$i]['id']		= "3";
		$arr_json[$i]['text']	= "Monthly";
		$i++;
		$arr_json[$i]['id']		= "4";
		$arr_json[$i]['text']	= "6 Monthly";
		$i++;
		$arr_json[$i]['id']		= "5";
		$arr_json[$i]['text']	= "Yearly";
		$i++;
		$arr_json[$i]['id']		= "6";
		$arr_json[$i]['text']	= "2,5 Yearly";
		$i++;
		$arr_json[$i]['id']		= "7";
		$arr_json[$i]['text']	= "5 Yearly";
		$i++;


		echo json_encode($arr_json);
	}


	function comboPeriod()
	{
		$i = 0;

		$arr_json[$i]['id']		= "1 Year";
		$arr_json[$i]['text']	= "1 Year";
		$i++;
		$arr_json[$i]['id']		= "2 Year";
		$arr_json[$i]['text']	= "2 Year";
		$i++;


		echo json_encode($arr_json);
	}




	function comboTypeOfService()
	{
		$i = 0;
		$this->load->model("TypeOfService");
		$type_of_service = new TypeOfService();
		$type_of_service->selectByParamsMonitoring(array());
		while ( $type_of_service->nextRow()) {
			$arr_json[$i]['id']		= $type_of_service->getField("NAME");
			$arr_json[$i]['text']	= $type_of_service->getField("NAME");
			# code...
			$i++;
		}

		echo json_encode($arr_json);
	}

	function comboFindMenthod()
	{
		$i = 0;

		$arr_json[$i]['id']		= "1";
		$arr_json[$i]['text']	= "By Application";
		$i++;
		$arr_json[$i]['id']		= "2";
		$arr_json[$i]['text']	= "By File Content";

		echo json_encode($arr_json);
	}

	function comboTypeProject()
	{
		$i = 0;

		$arr_json[$i]['id']		= "Project Kecil";
		$arr_json[$i]['text']	= "Project Kecil";
		$i++;
		$arr_json[$i]['id']		= "Project Besar";
		$arr_json[$i]['text']	= "Project Besar";

		echo json_encode($arr_json);
	}

	function comboReport()
	{
		$i = 0;
		$this->load->model("Report");
		$report = new Report();

		$report->selectByParamsCombo(array());
		while ($report->nextRow()) {
			$arr_json[$i]['id']		= $report->getField("REPORT_ID");
			$arr_json[$i]['text']	= $report->getField("REPORT");
			$i++;
		}
		echo json_encode($arr_json);
	}

	function ComboBank()
	{
		$this->load->model("Bank");
		$bank = new Bank();
		$bank->selectByParamsMonitoring(array());
		$arr_json = array();
		$i = 0;
		while ($bank->nextRow()) {
			$arr_json[$i]['id']		= $bank->getField("BANK_ID");
			$arr_json[$i]['text']	= $bank->getField("NAMA");
			$i++;
		}
		echo json_encode($arr_json);
	}

	function comboValueDollar()
	{

		$reqMode = $this->input->get('reqMode'); 
		$this->load->model("MasterCurrency");
		$master_currency = new MasterCurrency();
		$master_currency->selectByParamsMonitoring(array(),-1,-1,$statement);

		$i = 0;
		if(!empty($reqMode) && $reqMode =='ALL'){
			$arr_json[$i]['id']		= '';
			$arr_json[$i]['text']	= 'All';
			$i++;
		}
		while($master_currency->nextRow()){
		$arr_json[$i]['id']		= $master_currency->getField("NAMA");
		$arr_json[$i]['text']	= $master_currency->getField("NAMA");
		$i++;
		}
		

		echo json_encode($arr_json);
	}

	function comboStatusProses(){
		$i = 0;

		$arr_json[$i]['id']		= "";
		$arr_json[$i]['text']	= "Pilih Status";
		$i++;
		$arr_json[$i]['id']		= "1";
		$arr_json[$i]['text']	= "Bayar";
		$i++;
		$arr_json[$i]['id']		= "2";
		$arr_json[$i]['text']	= "Belum Bayar";
		$i++;
		echo json_encode($arr_json);

	}
	function comboValueDollar2()
	{
		$this->load->model('MasterCurrency');
		$master_currency = new MasterCurrency();
		$i = 0;

		$master_currency->selectByParamsMonitoring();
		while ( $master_currency->nextRow()) {
			$arr_json[$i]['id']		= $master_currency->getField("MASTER_CURRENCY_ID");
			$arr_json[$i]['text']	= $master_currency->getField("NAMA");
		$i++;
		}
		
		

		echo json_encode($arr_json);
	}

	function comboEquipCategori()
	{
		$this->load->model('EquipCategory');
		$equip_category = new EquipCategory();
		$equip_category->selectByParamsMonitoring(array());

		$reqMode = $this->input->get("reqMode");
		$i = 0;
		if($reqMode == "ALL"){
			$arr_json[$i]['id']		= "ALL";
			$arr_json[$i]['text']	= "ALL";
			$i++;
		}
		while ($equip_category->nextRow()) {
			$arr_json[$i]['id']		= $equip_category->getField("EC_ID");
			$arr_json[$i]['text']	= strtoupper($equip_category->getField("EC_NAME"));
			$i++;
		}
		echo json_encode($arr_json);
	}

	function comboEquipmentCategori()
	{
		$this->load->model('EquipmentKategori');
		$equip_category = new EquipmentKategori();
		$equip_category->selectByParamsMonitoring(array());
		$i = 0;
		while ($equip_category->nextRow()) {
			$arr_json[$i]['id']		= $equip_category->getField("EQUIPMENT_KATEGORI_ID");
			$arr_json[$i]['text']	= strtoupper($equip_category->getField("NAMA"));
			$i++;
		}
		echo json_encode($arr_json);
	}
	function comboEquipList()
	{
		$this->load->model('EquipmentList');
		$equipment_list = new EquipmentList();
		$equipment_list->selectByParamsMonitoring(array());
		$i = 0;
		while ($equipment_list->nextRow()) {
			$arr_json[$i]['id']		= $equipment_list->getField("EQUIP_ID");
			$arr_json[$i]['text']	= strtoupper($equipment_list->getField("EQUIP_NAME"));
			$i++;
		}
		echo json_encode($arr_json);
	}
	function comboEquipment()
	{
		$this->load->model('EquipmentList');
		$equipment_list = new EquipmentList();
		$equipment_list->selectByParamsMonitoringEquipment(array());
		$i = 0;
		while ($equipment_list->nextRow()) {
			$arr_json[$i]['id']		= $equipment_list->getField("ID");
			$arr_json[$i]['text']	= $equipment_list->getField("NAME")." | ".$equipment_list->getField("KATEGORI");
			$i++;
		}
		echo json_encode($arr_json);
	}


	function comboKategori()
	{
		$i = 0;
		$arr_json[$i]['id']		= "COMPANY";
		$arr_json[$i]['text']	= "COMPANY";
		$i++;
		$arr_json[$i]['id']		= "VESSEL";
		$arr_json[$i]['text']	= "VESSEL";

		echo json_encode($arr_json);
	}

	function comboJenisAplikasi()
	{
		$i = 0;
		$arr_json[$i]['id']		= "URL";
		$arr_json[$i]['text']	= "URL";
		$i++;
		$arr_json[$i]['id']		= "MOBILEAPP";
		$arr_json[$i]['text']	= "MOBILEAPP";

		echo json_encode($arr_json);
	}


	function status()
	{
		$i = 0;
		$arr_json[$i]['id']		= "0";
		$arr_json[$i]['text']	= "Pending";
		$i++;
		$arr_json[$i]['id']		= "1";
		$arr_json[$i]['text']	= "Realisasi";
		$i++;
		$arr_json[$i]['id']		= "2";
		$arr_json[$i]['text']	= "Cancel";
		$i++;
		$arr_json[$i]['id']		= "";
		$arr_json[$i]['text']	= "Not Respond";

		echo json_encode($arr_json);
	}


	function aduan()
	{
		$i = 0;
		$arr_json[$i]['id']		= "BELUM";
		$arr_json[$i]['text']	= "BELUM DIBALAS";
		$i++;
		$arr_json[$i]['id']		= "SUDAH";
		$arr_json[$i]['text']	= "SUDAH DIBALAS";
		$i++;
		$arr_json[$i]['id']		= "SEMUA";
		$arr_json[$i]['text']	= "SEMUA";

		echo json_encode($arr_json);
	}

	function personil_combo()
	{
		$this->load->model("DokumenKualifikasi");
		$dokumen_kualifikasi = new DokumenKualifikasi();
		$dokumen_kualifikasi->selectByParamsMonitoringPersonil(array());
		$i = 0;
		while ($dokumen_kualifikasi->nextRow()) {
			$arr_json[$i]['id']		= $dokumen_kualifikasi->getField("DOCUMENT_ID");
			$arr_json[$i]['text']	= $dokumen_kualifikasi->getField("NAME") . ' - ' . $dokumen_kualifikasi->getField("POSITION_NAMA");
			$i++;
		}
		echo json_encode($arr_json);
	}

	function validasi()
	{
		$i = 0;
		$arr_json[$i]['id']		= "validasi";
		$arr_json[$i]['text']	= "BELUM DIVALIDASI";
		$i++;
		$arr_json[$i]['id']		= "tolak";
		$arr_json[$i]['text']	= "DITOLAK";

		echo json_encode($arr_json);
	}


	function jenis_kelamin()
	{
		$i = 0;
		$arr_json[$i]['id']		= "";
		$arr_json[$i]['text']	= "SEMUA";
		$i++;
		$arr_json[$i]['id']		= "PRIA";
		$arr_json[$i]['text']	= "PRIA";
		$i++;
		$arr_json[$i]['id']		= "WANITA";
		$arr_json[$i]['text']	= "WANITA";


		echo json_encode($arr_json);
	}


	function golongan_darah()
	{
		$i = 0;
		$arr_json[$i]['id']		= "";
		$arr_json[$i]['text']	= "SEMUA";
		$i++;
		$arr_json[$i]['id']		= "A";
		$arr_json[$i]['text']	= "A";
		$i++;
		$arr_json[$i]['id']		= "B";
		$arr_json[$i]['text']	= "B";
		$i++;
		$arr_json[$i]['id']		= "AB";
		$arr_json[$i]['text']	= "AB";
		$i++;
		$arr_json[$i]['id']		= "O";
		$arr_json[$i]['text']	= "O";

		echo json_encode($arr_json);
	}


	function cabang()
	{
		$this->load->model("Master");
		$master = new Master();

		$master->selectCabang(array());
		$i = 0;
		while ($master->nextRow()) {
			$arr_json[$i]['id']		= $master->getField("CABANG_ID");
			$arr_json[$i]['text']	= $master->getField("NAMA");
			$i++;
		}

		echo json_encode($arr_json);
	}


	function comboStatus()
	{
		$i = 0;
		$arr_json[$i]['id']		= "1";
		$arr_json[$i]['text']	= "Lunas";
		$i++;
		$arr_json[$i]['id']		= "0";
		$arr_json[$i]['text']	= "Belum Lunas";
		
		$i++;

		echo json_encode($arr_json);
	}


	function comboStatus2()
	{
		$i = 0;
		$arr_json[$i]['id']		= "Lunas";
		$arr_json[$i]['text']	= "Lunas";
		$i++;
		$arr_json[$i]['id']		= "Belum Lunas";
		$arr_json[$i]['text']	= "Belum Lunas";
		$i++;
		$arr_json[$i]['id']		= "Pending";
		$arr_json[$i]['text']	= "Pending";
		$i++;

		echo json_encode($arr_json);
	}
	
	function comboStatus3()
	{
		$reqMode = $this->input->get('reqMode');
		$i = 0;
		if($reqMode=='ALL'){
			$arr_json[$i]['id']		= "";
			$arr_json[$i]['text']	= "All";
			$i++;
		}
		
		$arr_json[$i]['id']		= "Lunas";
		$arr_json[$i]['text']	= "Lunas";
		$i++;
		$arr_json[$i]['id']		= "Belum Lunas";
		$arr_json[$i]['text']	= "Belum Lunas";
		$i++;
		$arr_json[$i]['id']		= "Pending";
		$arr_json[$i]['text']	= "On Progress";
		$i++;

		echo json_encode($arr_json);
	}

	function comboStatusPph()
	{
		$i = 0;
		$arr_json[$i]['id']		= "23";
		$arr_json[$i]['text']	= "PPH-23";
		$i++;
		$arr_json[$i]['id']		= "4";
		$arr_json[$i]['text']	= "PPH-4";
		$i++;
		

		echo json_encode($arr_json);
	}

	function comboValueCategoryOfferProject()
	{
		$this->load->model("CategoryProject");
		$category_project = new CategoryProject();
		$category_project->selectByParamsMonitoring(array());
		$i = 0;
		while ($category_project->nextRow()) {
			$arr_json[$i]['id']		= $category_project->getField("NAME");
			$arr_json[$i]['text']	= $category_project->getField("NAME");
			$i++;
		}
		echo json_encode($arr_json);

		// $i = 0;
		// $arr_json[$i]['id']		= "Marine Spread";
		// $arr_json[$i]['text']	= "Marine Spread";
		// $i++;
		// $arr_json[$i]['id']		= "Personnel";
		// $arr_json[$i]['text']	= "Personnel";
		// $i++;
		// $arr_json[$i]['id']		= "Document";
		// $arr_json[$i]['text']	= "Document";
		// $i++;
		// $arr_json[$i]['id']		= "Equipment";
		// $arr_json[$i]['text']	= "Equipment";
		// $i++;
		// $arr_json[$i]['id']		= "Insurance";
		// $arr_json[$i]['text']	= "Insurance";
		// $i++;
		// $arr_json[$i]['id']		= "MATERIAL + 15% Handling fee (Specification refer to drawing)";
		// $arr_json[$i]['text']	= "MATERIAL + 15% Handling fee (Specification refer to drawing)";
		// $i++;
		// $arr_json[$i]['id']		= "Refurbishment in Workshop";
		// $arr_json[$i]['text']	= "Refurbishment in Workshop";
		// $i++;
		// echo json_encode($arr_json);
	}

	function comboDepartment()
	{
		$i = 0;
		$arr_json[$i]['id']		= "Disnaker";
		$arr_json[$i]['text']	= "Disnaker";
		$i++;
		$arr_json[$i]['id']		= "Hubla";
		$arr_json[$i]['text']	= "Hubla";
		$i++;
		$arr_json[$i]['id']		= "Migas";
		$arr_json[$i]['text']	= "Migas";
		$i++;

		echo json_encode($arr_json);
	}
}
