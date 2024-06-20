<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$stylemenumarketing = "";
$stylemenufinance = "";
$stylemenuproduction = "";
$stylemenudocument = "";
$stylemenuresearch = "";
$stylemenuothers = "";

if($this->MENUMARKETING == "0")
    $stylemenumarketing = "style='display:none;'";

if($this->MENUFINANCE == "0")
    $stylemenufinance = "style='display:none;'";

if($this->MENUPRODUCTION == "0")
    $stylemenuproduction = "style='display:none;'";

if($this->MENUDOCUMENT == "0")
    $stylemenudocument = "style='display:none;'";

if($this->MENUSEARCH == "0")
    $stylemenuresearch = "style='display:none;'";

if($this->MENUOTHERS == "0")
    $stylemenuothers = "style='display:none;'";

?>
<style type="text/css">
    .alert{
        padding: 10px !important;
        margin-bottom: 12px !important;
        font-size: 15px;
    }
</style>
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
            <div class="alert alert-danger" <?=$stylemenuproduction?>>
                <?      
                $this->load->model("ReminderClient");
                $reminder_client = new ReminderClient();
                $statement = " AND (
                    (A.ANNUAL_DUE_DATE < CURRENT_DATE + INTERVAL '1 MONTH') OR 
                    (A.INTERMEDIATE_DUE_DATE < CURRENT_DATE + INTERVAL '1 MONTH') OR 
                    (A.SPECIAL_DUE_DATE < CURRENT_DATE + INTERVAL '1 MONTH')
                ) ";
                $total_reminder =  $reminder_client->getCountByParams(array(), $statement);
                ?>
                
                <a href="javascript:void(0)" onclick="load_reminder_client()"><i class="fa fa-info-circle" aria-hidden="true"></i> You have <?=$total_reminder?> Reminder Client.</a>
            </div>
        	<div class="alert alert-info" <?=$stylemenudocument?>>
                <?
                      $this->load->model("DokumenCertificate");
                            $equipment_list = new DokumenCertificate();
                            $total_equipment =  $equipment_list->getCountByParamsTotalCertificate(array());
                ?>
                
            	  <a href="javascript:void(0)" onclick="load_certificate()"><i class="fa fa-info-circle" aria-hidden="true"></i> You have <?=$total_equipment?> Certificate(s) must renew</a>
            </div>
            <div class="alert alert-danger" <?=$stylemenuproduction?>>
                <?      
                $this->load->model("EquipmentList");
                $equipment_list = new EquipmentList();
                $total_equipment =  $equipment_list->getCountByParamsTotalEquipment(array());
                ?>
                
            	<a href="javascript:void(0)" onclick="load_equipment()"><i class="fa fa-info-circle" aria-hidden="true"></i> You have <?=$total_equipment?> Equipment(s) must be calibrated.</a>
            </div>

             <div class="alert alert-danger" <?=$stylemenuproduction?>>
                <?
                $this->load->model("DokumenSertifikat");
                $dokumen_certificate = new DokumenSertifikat();
                ?>
                
               <a href="javascript:void(0)" onclick="load_personal()"> <i class="fa fa-info-circle" aria-hidden="true" ></i> You have (<?=$dokumen_certificate->getCountByParamsExpired()?>) Certificate Personnel (s) expired.</a>
            </div>
            <?php /* <div class="alert alert-warning">
                 <?      
                $this->load->model("PmsEquipDetil");
                $equipment_list = new PmsEquipDetil();
                $total_equipment =  $equipment_list->getCountByParamsExpired(array());
                ?>
            	<a href="javascript:void(0)" onclick="load_component()"><i class="fa fa-info-circle" aria-hidden="true" ></i> You have <?=$total_equipment?> Component part(s) must be calibrated</a>
            </div> */?>
            <div class="alert alert-success" <?=$stylemenudocument?>>
                   <?      
                $this->load->model("Document");
                $equipment_list = new Document();
                $total_equipment =  $equipment_list->getCountByParamsTotalDocument(array());
                ?>
                
            	<a href="javascript:void(0)" onclick="load_legality()"><i class="fa fa-info-circle" aria-hidden="true"></i> You have <?=$total_equipment?> Legality document must be renew </a>
            </div>
            <div class="alert alert-success" <?=$stylemenuproduction?>>
                   <?      
                $this->load->model("PmsEquipment");
                $pms_equipment = new PmsEquipment();
                $total_equipment =  $pms_equipment->getCountByParamsTest(array());
                ?>
                
                <a href="javascript:void(0)" onclick="load_pms()"><i class="fa fa-info-circle" aria-hidden="true"></i> You have <?=$total_equipment?> Equipment must be Test  </a>
            </div>
            <div class="alert alert-danger" <?=$stylemenuproduction?>>
                <?      
                $this->load->model("EquipmentList");
                $equipment_list = new EquipmentList();
                $total_equipment =  $equipment_list->getCountByParamsExpired(array());
                ?>
                
                <a href="javascript:void(0)" onclick="load_equipment_expired_cert()"><i class="fa fa-info-circle" aria-hidden="true"></i> You have <?=$total_equipment?> Equipment Certificate(s) expired.</a>
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
    function load_equipment(){
        openAdd('app/loadUrl/app/template_load_equipment_calibrated');
    }
    function load_certificate(){
        openAdd('app/loadUrl/app/template_load_certificate_expired');
    }
    function load_legality(){
        openAdd('app/loadUrl/app/template_load_legality');
    }
    function load_component(){
        openAdd('app/loadUrl/app/template_load_spare_part');
    }
    function load_pms(){
        openAdd('app/loadUrl/app/template_load_pms');
    }
    function load_equipment_expired_cert(){
        openAdd('app/loadUrl/app/template_load_equipment_expired_cert');
    }
    function load_reminder_client(){
        openAdd('app/loadUrl/app/template_load_reminder_client');
    }
</script>