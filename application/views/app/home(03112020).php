<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 $this->load->model("DokumenCertificate");
        $dokumen_certificate = new DokumenCertificate();

$this->load->model("JenisKualifikasi");
$jenis_kualifikasi = new JenisKualifikasi();
$jenis_kualifikasi->selectByParamsMonitoringPersonalKualifikasi(array());
 $nomers=1;
while ($jenis_kualifikasi->nextRow()) {

   $reqListCertificates    = $jenis_kualifikasi->getField("LIST_CERTIFICATE");
   $reqListCertificate = explode(',', $reqListCertificates);
   $bollean=false;
 
   for($i=0;$i<count($reqListCertificate);$i++){
    if(!empty($reqListCertificate[$i])){
        $dokumen_certificate = new DokumenCertificate();
        $dokumen_certificate->selectByParams(array("A.DOCUMENT_ID" => $reqListCertificate[$i]));
        $dokumen_certificate->firstRow();
        $reqNames            = $dokumen_certificate->getField("NAME");
        $reqIssuedDates      = $dokumen_certificate->getField("ISSUED_DATE");
        $reqExpiredDates     = $dokumen_certificate->getField("EXPIRED_DATE");

        $tgl_skrng = Date('d-m-Y');
        $exp_date = $dokumen_certificate->getField("DATES");
                // echo $tgl_skrng.'-'.$exp_date;
        $datetime1 = date_create($tgl_skrng);
        $datetime2 = date_create($exp_date);
        $interval = date_diff($datetime1, $datetime2);
        $interval = $interval->format("%R%a");
        $point = substr($interval, 0,1);
        if ($point == '-') {
            $bollean = true;
        }
    }

    
    }

    $color='';
    if($bollean || empty($reqListCertificates)){
       $nomers++;
    }

}

?>

<div class="col-md-12 area-home-egateway">
    <div class="row area-info-home">
    	<div class="col-md-1">
        	&nbsp;
        </div>
        <div class="col-md-10">
        	<div class="row">
            	<div class="col-md-4">
                    <div class="item">
                        <div class="ikon"><img src="images/icons8-vise.png"></div>
                        <?
                            $this->load->model("EquipmentList");
                            $equipment_list = new EquipmentList();
                            $total_equipment =  $equipment_list->getCountByParamsMonitoring(array());

                        ?>
                        <div class="nilai"><?=$total_equipment?></div>
                        <div class="title">Equipments in your warehouse</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="item">
                        <div class="ikon"><img src="images/icons8-user.png"></div>
                         <?
                            $this->load->model("Customer");
                            $equipment_list = new Customer();
                            $total_equipment =  $equipment_list->getCountByParams(array("A.TIPE"=>0));

                        ?>
                        <div class="nilai"><?=$total_equipment?></div>
                        <div class="title">New customer in this application</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="item">
                        <div class="ikon"><img src="images/icons8-collaborator_male.png"></div>
                         <?
                            $this->load->model("Customer");
                            $equipment_list = new Customer();
                            $total_equipment =  $equipment_list->getCountByParams(array("A.TIPE"=>1));

                        ?>
                        <div class="nilai"><?=$total_equipment?></div>
                        <div class="title">Customer in this application</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1">
        	&nbsp;
        </div>
        
    	
    </div>
    
    <div class="row area-info-home">
    	<div class="col-md-1">
        	&nbsp;
        </div>
    	<div class="col-md-2">
        	<div class="item">
            	<div class="ikon"><img src="images/icons8-info.png"></div>
            	<div class="title">Notifikasi</div>
            </div>
        </div>
    	<div class="col-md-8">
        	<div class="alert alert-info">
                <?
                      $this->load->model("DokumenCertificate");
                            $equipment_list = new DokumenCertificate();
                            $total_equipment =  $equipment_list->getCountByParamsTotalCertificate(array());
                ?>
                
            	<i class="fa fa-info-circle" aria-hidden="true"></i> You have <?=$total_equipment?> Certificate(s) must renew
            </div>
            <div class="alert alert-danger">
                <?      
                $this->load->model("EquipmentList");
                $equipment_list = new EquipmentList();
                $total_equipment =  $equipment_list->getCountByParamsTotalEquipment(array());
                ?>
                
            	<i class="fa fa-info-circle" aria-hidden="true"></i> You have <?=$total_equipment?> Equipment(s) must be calibrated.
            </div>

             <div class="alert alert-danger">
            
                
               <a href="javascript:void(0)" onclick="load_personal()"> <i class="fa fa-info-circle" aria-hidden="true" ></i> You have (<?=$nomers?>) Certificate Personnel (s) expired.</a>
            </div>
            <div class="alert alert-warning">
                 <?      
                $this->load->model("PmsEquipDetil");
                $equipment_list = new PmsEquipDetil();
                $total_equipment =  $equipment_list->getCountByParamsTotalEquipDetail(array());
                ?>
            	<i class="fa fa-info-circle" aria-hidden="true" ></i> You have <?=$total_equipment?> Component part(s) must be calibrated
            </div>
            <div class="alert alert-success">
                   <?      
                $this->load->model("Document");
                $equipment_list = new Document();
                $total_equipment =  $equipment_list->getCountByParamsTotalDocument(array());
                ?>
                
            	<i class="fa fa-info-circle" aria-hidden="true"></i> You have <?=$total_equipment?> Legality document must be renew
            </div>
        </div>
	</div>
    
    <!--<div class="row area-info-home">
    	<div class="col-md-3">
        	<div class="item">
            	<div class="ikon"><img src="images/icons8-collaborator_male.png"></div>
            	<div class="nilai">40</div>
            	<div class="title">Certificate(s) must renew</div>
            </div>
        </div>
    	<div class="col-md-3">
        	<div class="item">
            	<div class="ikon"><img src="images/icons8-collaborator_male.png"></div>
            	<div class="nilai">44</div>
            	<div class="title">Equipment(s) must be calibrated</div>
            </div>
        </div>
    	<div class="col-md-3">
        	<div class="item">
            	<div class="ikon"><img src="images/icons8-collaborator_male.png"></div>
            	<div class="nilai">113</div>
            	<div class="title">Component part(s) must be calibrated</div>
            </div>
        </div>
    	<div class="col-md-3">
        	<div class="item">
            	<div class="ikon"><img src="images/icons8-collaborator_male.png"></div>
            	<div class="nilai">7</div>
            	<div class="title">Legality document must be renew</div>
            </div>
        </div>
    </div>-->

    <!--<div class="area-home-egateway-inner">
    	<img src="images/logo-app.png">
    </div>-->

</div>

<script type="text/javascript">
    function load_personal(){
        openAdd('app/loadUrl/app/template_load_expired');
    }
</script>