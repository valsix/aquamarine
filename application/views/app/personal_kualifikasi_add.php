<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("DokumenKualifikasi");
$this->load->model("Service_order");
$this->load->model("EmergencyContact");
$this->load->model("SoTeamNew");


$dokumen_kualifikasi = new DokumenKualifikasi();
$service_order = new Service_order();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $dokumen_kualifikasi->selectByParamsMonitoringPersonil(array("DOCUMENT_ID" => $reqId));
    // echo $dokumen_kualifikasi->query;exit;
    $dokumen_kualifikasi->firstRow();

    $reqName        = $dokumen_kualifikasi->getField("NAME");
    $reqDescription = $dokumen_kualifikasi->getField("DESCRIPTION");
    $reqPath        = $dokumen_kualifikasi->getField("PATH");
    $reqAddress     = $dokumen_kualifikasi->getField("ADDRESS");
    $reqBirthDate   = $dokumen_kualifikasi->getField("BIRTH_DATE");
    $reqPhone       = $dokumen_kualifikasi->getField("PHONE");
    $reqPhone2      = $dokumen_kualifikasi->getField("PHONE2");
    $reqPosition    = $dokumen_kualifikasi->getField("POSITION");
    $reqListCertificate    = $dokumen_kualifikasi->getField("LIST_CERTIFICATE");
    $reqIdNumber    = $dokumen_kualifikasi->getField("ID_NUMBER");
    $reqIdCard      = $dokumen_kualifikasi->getField("ID_CARD");
    $reqCabangId    = $dokumen_kualifikasi->getField("CABANG_ID");
    $reqBirthDates  =str_replace('-', '/', $reqBirthDate);
    $reqRemarks     = $dokumen_kualifikasi->getField("REMARKS");     
    $reqNoRekening = $dokumen_kualifikasi->getField("NO_REKENING");

    $emergencycontact = new EmergencyContact();
    $emergencycontact->selectByParamsMonitoring(array('A.MODUL'=>'PERSONAL','A.FIELD_ID'=>$reqId));
    $arrDataEmergency = $emergencycontact->rowResult;


}
// echo $reqBirthDate;
$this->load->model('PersonalCertificate');
$certificate = new PersonalCertificate();
$certificate->selectByParamsMonitoring(array());
$arrDatas = array();
$no = 0;
while ($certificate->nextRow()) {
    $arrDatas[$no]['ID']     = $certificate->getField("CERTIFICATE_ID");
    $arrDatas[$no]['NAME']   = $certificate->getField("CERTIFICATE");
    $no++;
}

$reqBarcode = $reqId.'-'.$reqIdCard.'-'.$reqCabangId;
$reqBarcode = $reqId ?$reqBarcode:'';
// echo $reqPosition;
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/personal_kualifikasi"> Personnel List</a> &rsaquo; Form Personal List
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entri Document of Personnel List
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqJenis" class="control-label col-md-2">Personnel Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqName" class="easyui-validatebox textbox form-control" name="reqName" value="<?= $reqName ?>" style=" width:100%" />
                                </div>



                            </div>
                        </div>
                        <?
                        if(!empty($reqBarcode)){
                        ?>
                      <!--   <div class="col-md-4">
                            <div style="background: white;height: auto;color: black;height: 160px;width: 160px;border: 1px solid black;padding: 20px">
                                <img id="imgLogo" src="uploads/personal_kualifikasi/<?=$reqId?>.png" style="height: 100%;width: 100%">

                            </div>
                        </div> -->
                        <?
                        }
                        ?>

                    </div>

                     <div class="form-group">
                        <label for="reqJenis" class="control-label col-md-2">ID Number</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqIdNumber" class="easyui-validatebox textbox form-control" name="reqIdNumber" value="<?= $reqIdNumber ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                         <label for="reqJenis" class="control-label col-md-2">ID Card</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqIdCard" class="easyui-validatebox textbox form-control" name="reqIdCard" value="<?= $reqIdCard ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqAddress" class="control-label col-md-2">Address</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqAddress" cols="4" rows="3" style="width:100%;"><?= $reqAddress; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <label for="reqAddress" class="control-label col-md-2">City</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input class="easyui-combobox form-control" style="width:100%" name="reqCabangId"  id="reqCabangId" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'web/cabang_json/combo'" value="<?= $reqCabangId ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqBirthDate" class="control-label col-md-2">Birth Date</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    
                                    <input type="text" id="reqBirthDate" class="easyui-datebox textbox form-control" name="reqBirthDate" value="<?= $reqBirthDate ?>" data-options="onSelect:selisih_tahuns"style=" width:200px" />
                              
                                     <input type="text" id="reqYear" style="width: 10%"class="easyui-validatebox textbox form-control" name="reqYear" value="<?= $reqDuration ?>" readonly   /> / Year Old
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Phone 1</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPhone" class="easyui-validatebox textbox form-control" name="reqPhone" value="<?= $reqPhone ?>" style=" width:60%" />
                                </div>
                            </div>
                        </div>
                         <label for="reqDescription" class="control-label col-md-2">No Rekening </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPhone" class="easyui-validatebox textbox form-control" name="reqNoRekening" value="<?= $reqNoRekening ?>" style=" width:60%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="display: none">
                        <label for="reqDescription" class="control-label col-md-2">Phone 2</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPhone2" class="easyui-validatebox textbox form-control" name="reqPhone2" value="<?= $reqPhone2 ?>" style=" width:60%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Emergency contact</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-11">
                                 <table class="table">
                                    <thead>
                                       <tr>
                                        <th width="5%"> No </th>
                                        <th> Hp <a onClick="addRowEmergency()"><i class="fa fa-plus-circle fa-lg"></i></a> </th>
                                        <th> Nama </th>
                                        <th> Keterangan </th>
                                        <th width="5%"> Aksi </th>
                                    </tr>
                                    </thead>
                                    <tbody id='tbodyEmergency'>
                                        <?
                                        $nomer=1;
                                        foreach ($arrDataEmergency as $value) {
                                        
                                        
                                        ?>
                                        <tr>
                                         <td> <input class='form-control' disabled readonly value='<?=$nomer;$nomer++;?>' /> </td>
                                         <td> 
                                           <input class='form-control'  type='hidden'  name='reqFieldId[]' value='<?=$value['emergency_contact_id']?>' /> 
                                           <input class='form-control'  type='hidden'  name='reqModul[]' value='PERSONAL' /> 
                                           <input class='form-control' type='text' name='reqNoHpEmergency[]' onkeypress='validate(event)' value='<?=$value['hp']?>' /> 
                                       </td>
                                       <td> <input class='form-control' type='text' name='reqNamaEmergeny[]' value='<?=$value['nama']?>' />  </td>
                                       <td> <input class='form-control' type='text' name='reqKeteranganEmergeny[]' value='<?=$value['keterangan']?>' />  </td>

                                       <td><a onclick="deleteData('web/personal_kualifikasi_json/deleteEmergency',<?=$value['emergency_contact_id']?>)"><i class="fa fa-trash fa-lg"></i></a> </td>
                                   </tr>
                                   <?
                                    }
                                   ?>

                                    </tbody>
                                 </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2"> Type Of Qualification </label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqPosition" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/combo_jenis_kwalifikasi'" value="<?=$reqPosition?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqRemarks" class="control-label col-md-2"> Remarks </label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqRemarks" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboRemarks'" value="<?=$reqRemarks?>" />
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2"> Type of Certificate </label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div style="background-color: white;
                                    height: 150px;
                                    width: 100%;
                                    overflow-y: scroll;">
                                        <table style="width: 100%;color: black;font-weight: bold;" valign="top">
                                            <?
                                            $nomer = 1;
                                            for ($i = 0; $i < count($arrDatas); $i++) {
                                                $checked = '';
                                                $reqTypeOfServices = explode(",", $reqDescription);
                                                for ($j = 0; $j < count($reqTypeOfServices); $j++) {
                                                    if ($arrDatas[$i]['NAME'] == $reqTypeOfServices[$j]) {
                                                        $checked = "checked";
                                                    }
                                                }


                                            ?>

                                                <tr>
                                                    <td style="padding: 2px"><input type="checkbox" class="form-control" name="reqTypeOfService[]" value="<?= $arrDatas[$i]['NAME'] ?>" <?= $checked ?>> </td>
                                                    <td style="padding: 2px"> <?= $nomer . '. ' . $arrDatas[$i]['NAME'] ?> </td>
                                                </tr>
                                            <?
                                                $nomer++;
                                            }
                                            ?>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                     <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2"> List Certificate</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <table class="table" id="tablePegawai" >
                                        <thead>
                                            <tr>
                                                <th style="width:15%">Type of Certificate</th>
                                                <th style="width:45%">Name <a onClick="addRowCertificate()"><i class="fa fa-plus-circle fa-lg"></i></a></th>
                                                <th style="width:15%">Issue Date </th>
                                                <th style="width:15%">Expired Date</th>
                                                <th>Aksi</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyAuditee">
                                           
                                            <?
                                            $this->load->model("DokumenSertifikat");
                                            $dokumen_certificate = new DokumenSertifikat();
                                            $reqListCertificate = explode(',', $reqListCertificate);
                                            $statement = '';
                                            if ($reqId)
                                            {
                                                $statement = ' AND A.DOKUMEN_ID='.$reqId;
                                            }
                                            else
                                            {
                                                $statement = ' AND 1=2';
                                            }
                                        
                                            $dokumen_certificate->selectByParamsMonitoring(array(),-1,-1,$statement);
                                            // var_dump($dokumen_certificate->query);
                                            $i=0;
                                            while ($dokumen_certificate->nextRow()) 
                                            {
                                                if (empty($reqId)){}
                                                else
                                                {
                                                   $reqSertifikatId = $dokumen_certificate->getField("DOKUMEN_SERTIFIKAT_ID");
                                                   $reqNames            = $dokumen_certificate->getField("NAME");
                                                   $reqIssuedDates      = $dokumen_certificate->getField("ISSUE_DATE");
                                                   $reqExpiredDates     = $dokumen_certificate->getField("EXPIRED_DATE");
                                                   $reqTypeOfService     = $dokumen_certificate->getField("CERTIFICATE_ID");
                                                   $reqDescription     = $dokumen_certificate->getField("CERTIFICATE");
                                                   $reqIdSertifikat     = $dokumen_certificate->getField("DOKUMEN_SERTIFIKAT_ID");
                                                   $reqPathLampiran = $dokumen_certificate->getField("LAMPIRAN");
                                               }
                                               
                                                // var_dump  ($reqTypeOfService);exit;                                    
                                                // print_r($reqListCertificate);exit;
                                                // for($i=0;$i<count($reqListCertificate);$i++){
                                                //     if(!empty($reqListCertificate[$i])){
                                                //         $reqTypeOfServices = explode(",", $reqDescription);
                                                $id = rand();
                                                // var_dump ($id);
                                                // $dokumen_certificate->selectByParamsMonitoring(array("A.DOCUMENT_ID" => $reqListCertificate[$i]));
                                                // var_dump($dokumen_certificate->query);

                                                // $dokumen_certificate->firstRow();
                                                // $reqNames            = $dokumen_certificate->getField("NAME");
                                                // $reqIssuedDates      = $dokumen_certificate->getField("ISSUED_DATE");
                                                // $reqExpiredDates     = $dokumen_certificate->getField("EXPIRED_DATE");
                                            ?>
                                            <tr>
                                              <td>
                                                  <input class="easyui-combobox form-control" style="width:100%" name="reqTypeOfService[]"  id="reqTypeOfService<?=$id?>" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/personalCertificate'" value="<?=$reqTypeOfService?>" />
                                              </td>
                                              <td>
                                                <input readonly class="easyui-validatebox textbox form-control" type="hidden" name="reqIdCertifcate[]"  id="reqIdCertifcate<?=$id?>" value="<?=$reqListCertificate[$i]?>" data-options="required:true" style="width:100%">
                                                <input  class="easyui-validatebox textbox form-control" type="text" name="reqCertificateName[]"  id="reqCertificateName<?=$id?>" value="<?=$reqNames?>" data-options="required:true" style="width:100%">
                                            </td>
                                            <td>
                                                <input  class="easyui-datebox  form-control dates" type="text" data-options="formatter:myformatter,parser:myparser" name="reqIssueDate[]"  id="reqIssueDate<?=$id?>" value="<?=$reqIssuedDates?>" data-options="required:true" style="width:100%">
                                            </td>
                                            <td>
                                                <input  class="easyui-datebox  form-control dates" data-options="formatter:myformatter,parser:myparser" type="text" name="reqExpiredDate[]"  id="reqExpiredDate<?=$id?>" value="<?=$reqExpiredDates?>"  style="width:100%">
                                                <input  class="easyui-validatebox  form-control" type="hidden" name="reqIdSertifikat[]"  id="reqIdSertifikat<?=$id?>" value="<?=$reqIdSertifikat?>"  style="width:100%">

                                            </td>
                                            <td style="text-align:center">
                                                <a onclick="hapus_sertifikat($(this),'<?=$reqSertifikatId?>');"><i class="fa fa-trash fa-lg"></i></a>

                                            </td>     
                                           
                                        </tr>
                                        <tr>
                                            <td colspan="6">
                                                      <table style="width: 100%" class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="80%"> File Name <a onclick="tambahPenyebab2('<?=$reqSertifikatId?>');"  class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>
                                <th width="10%"> Type </th>
                                <th width="10%"> Action </th>
                            </tr>
                        </thead>
                        <tbody id="tambahAttacmentSertifikat<?=$reqSertifikatId?>" >

                             <?
                            $files_data = explode(';',  $reqPathLampiran);
                            for ($i = 0; $i < count($files_data); $i++) {
                                if (!empty($files_data[$i])) {
                                    $texts = explode('-', $files_data[$i]);
                                    $ext = substr($files_data[$i], -3);
                            ?>
                                    <tr>

                                        <td>
                                            <input type="file" onchange="getFileName(this, '<?=($i+1)?>')" name="document<?=$reqSertifikatId?>[]" multiple class="form-control" style="width: 90%">
                                            <input type="hidden" name="reqLinkFileTemp<?=$reqSertifikatId?>[]" value="<?= $files_data[$i] ?>">
                                            <input type="hidden" name="reqLampiranSertifikat2[]"  value="<?=$reqSertifikatId?>">
                                            <?if ($ext !=='pdf')
                                            {
                                            ?>
                                              <a href="uploads/lampiran_sertifikat/<?= $reqSertifikatId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                              <a onclick="openAdd(`uploads/lampiran_sertifikat/<?= $reqSertifikatId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            ?>
                                        </td>
                                        <td><?=strtoupper($ext)?> </td>
                                        <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                    </tr>
                            <?
                                }
                            }
                            ?>

                        </tbody>
                    </table>
                                              </td>
                                        </tr>

                                            <?
                                            
                                        }
                                        ?>
                                           
                                        </tbody>
                                    </table>
                                

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Working History
                        </h3>
                    </div>

                    <div style="padding: 10px">
                        <table style="width: 100%" class="table table-bordered" id='tableWorkingHistory'>
                            <thead>
                                <tr>
                                    <th> Project Name </th>
                                    <th> Company </th>
                                    <th> Vessel Name </th>
                                    <th> Position </th>
                                    <th> Service Date </th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $paramsArray = array("DOCUMENT_ID" => "0");
                            if($reqId != ""){
                                $paramsArray = array("DOCUMENT_ID" => $reqId);
                            }
                            $service_order->selectByParamsTeam($paramsArray);
                            while ($service_order->nextRow()) {
                            ?> 
                                <tr>
                                    <td><?=$service_order->getField("PROJECT_NAME")?></td>
                                    <td><?=$service_order->getField("COMPANY_NAME")?></td>
                                    <td><?=$service_order->getField("VESSEL_NAME")?></td>
                                    <td><?=$service_order->getField("POSITION")?></td>
                                    <td><?=$service_order->getField("DATE_OF_SERVICE")?></td>
                                </tr>
                            <?php
                            }
                            ?>
                                
                            </tbody>
                        </table>
                    </div>

                   <duv class='clearfix'> </duv>

                      <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Project History
                        </h3>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-bordered" id='tableProjectHistory'>  
                     
                            <thead>
                                <tr>  
                                    <th>  No. Contract </th>
                                      <th>  Nama Project </th>
                                      <th>  Lokasi </th>
                                       <th>  Tanggal mulai Kerja </th>
                                       <th>   tanggal Selesai</th>
                                        <th>   Total hari bekerja</th>
                                        <th>   Tanggal stanby </th>
                                        <th>   Selesai standby </th>
                                        <th> Total hari stand by </th>
                                         <th> Rate: Work (Rp) </th>
                                         <th> Standby (RP) Total Standby </th>
                                          <th> Total bekerja  </th>
                                        
                                </tr>
                            </thead>
                        <tbody>
                            <?
                            $soteamnew = new SoTeamNew();
                            $soteamnew->selectByParamsMonitoringDetail(array('A.DOCUMENT_ID::VARCHAR'=>$reqId));
                            $arrDataTeam = $soteamnew->rowResult;

                            while ($soteamnew->nextRow()) {
                                # code...
                            
                               $reqNoContact  = $soteamnew->getField("NO_CONTACT");
                               $reqTanggalContact  = $soteamnew->getField("TANGGAL_CONTACT");
                               $reqTanggalMulai  = $soteamnew->getField("TANGGAL_MULAI");
                               $reqWorkHitung  =$soteamnew->getField("TOTAL_HARI_KERJA");
                               $reqStandByMulai  = $soteamnew->getField("STAND_BY_MULAI");
                               $reqStandByTotal  = $soteamnew->getField("TOTAL_STANDBY");
                               $reqRateWork  =$soteamnew->getField("RATE_WORK");
                               $reqRateStandNy  = $soteamnew->getField("RATE_STAND_BY");
                               $reqTotalWork  = $soteamnew->getField("TOTAL_RATE_WORK");
                               $reqTotalStand  =$soteamnew->getField("TOTAL_RATE_STAND_BY");
                               $reqCertificatePath= $soteamnew->getField("LAMPIRAN");
                               $reqTypeOfService = $soteamnew->getField("SERTIFIKAT_ID");
                               $reqRemark  = $soteamnew->getField("REMARK");
                                $reqNamaCodeProject  = $soteamnew->getField("NAMA_CODE_PROJECT");
                                 $reqLokasi  = $soteamnew->getField("LOKASI");
                               $reqTanggalMulaix = explode(',', $reqTanggalMulai);
                               $reqTanggalMulaix = sortingDate($reqTanggalMulaix);

                                $reqStandByMulaix = explode(',',  $reqStandByMulai);
                                 $reqStandByMulaix = sortingDate($reqStandByMulaix);
                                 $reqTotal = $reqTotalWork +  $reqTotalStand;
                            ?>

                            <tr>
                                <td> <?=$reqNoContact?> </td>
                                <td> <?=$reqNamaCodeProject?> </td>
                                <td> <?=$reqLokasi?> </td>
                                <td> <?=$reqTanggalMulaix[0]?> </td>
                                <td> <?=$reqTanggalMulaix[count($reqTanggalMulaix)-1]?> </td>
                                <td> <?=$reqWorkHitung?> </td>
                                 <td> <?=$reqStandByMulaix[0]?> </td>
                                <td> <?=$reqStandByMulaix[count($reqStandByMulaix)-1]?> </td>
                                <td> <?=$reqStandByTotal?> </td>
                                <td> <?=currencyToPage2($reqTotalWork)?> </td>
                                <td><?=currencyToPage2($reqTotalStand)?> </td>
                                 <td> <?=currencyToPage2($reqTotal)?> </td>
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                    <div class="page-header">
                    </div>

                    <table style="width: 100%" class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="80%"> File Name <a onclick="tambahPenyebab()" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>
                                <th width="10%"> Type </th>
                                <th width="10%"> Action </th>
                            </tr>
                        </thead>
                        <tbody id="tambahAttacment">
                            <?
                            $files_data = explode(';',  $reqPath);
                            for ($i = 0; $i < count($files_data); $i++) {
                                if (!empty($files_data[$i])) {
                                    $texts = explode('-', $files_data[$i]);
                                    $ext = substr($files_data[$i], -3);
                            ?>
                                    <tr>

                                        <td>
                                            <input type="file" onchange="getFileName(this, '<?=($i+1)?>')" name="document[]" multiple class="form-control" style="width: 90%">
                                            <input type="hidden" name="reqLinkFileTemp[]" value="<?= $files_data[$i] ?>">
                                            <?if ($ext !=='pdf')
                                            {
                                            ?>
                                              <a href="uploads/personal_qualification/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                              <a onclick="openAdd(`uploads/personal_qualification/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            ?>
                                        </td>
                                        <td><?=strtoupper($ext)?></td>
                                        <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                    </tr>
                            <?
                                }
                            }
                            ?>

                        </tbody>
                    </table>
                    <input type="hidden" name="reqIdSertifikatDelete" id="reqIdSertifikatDelete" value="" />
                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqTipe" value="Personal Qualification" />

                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>

        </div>

    </div>
<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
    <script>
        function submitForm() {

        //      var win = $.messager.progress({
        //     title: 'Office Management  | PT Aquamarine Divindo',
        //     msg: 'proses data...'
        // });
            $('#ff').form('submit', {
                url: 'web/personal_kualifikasi_json/add',
                onSubmit: function() {
                    // return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    // console.log(data);return false;
                    var datas = data.split('-');
                      if(datas[0]=='xxx'){
                             show_toast('error', 'Have troube in ',  datas[1]);
                            $.messager.alert('Info', datas[1], 'info'); 
                    }else{
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/personal_kualifikasi_add?reqId=" + datas[0]);
                    }
                     $.messager.progress('close'); 
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
    <script type="text/javascript">
        function myformatter(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        }
        function myparser(s){
            if (!s) return new Date();
            var ss = (s.split('-'));
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(d,m-1,y);
            } else {
                return new Date();
            }
        }
    </script>

    <script type="text/javascript">
        function addRowEmergency(){
             var data = 
             `<tr>
               <td> <input class='form-control' disabled readonly value='-' /> </td>
                <td> 
                 <input class='form-control'  type='hidden'  name='reqFieldId[]' value='' /> 
                 <input class='form-control'  type='hidden'  name='reqModul[]' value='PERSONAL' /> 
                <input class='form-control' type='text' name='reqNoHpEmergency[]' onkeypress='validate(event)' value='' /> 
                </td>
                <td> <input class='form-control' type='text' name='reqNamaEmergeny[]' value='' />  </td>
                 <td> <input class='form-control' type='text' name='reqKeteranganEmergeny[]' value='' />  </td>

                <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
            </tr>
            `;
            $("#tbodyEmergency").append(data);
         
        }
    </script>
    <script type="text/javascript">
        function tambahPenyebab(filename='') {
            var id = $('#tambahAttacment tr').length+1;
            $.get("app/loadUrl/app/tempalate_row_attacment?id="+id+"&filename="+filename, function(data) {
                $("#tambahAttacment").append(data);
            });
        }

        function getFileName(input, id) {
            for (var i = 0; i < input.files.length; i++) {
                if(i == 0)
                {
                    $("#namaFile"+id).html(input.files[0].name);
                    var ext = input.files[0].name.split('.').pop();
                    ext = ext.toUpperCase();
                    if(ext.length > 3) ext = '';
                    if(ext == 'PNG' || ext == 'JPG' || ext == 'JPEG' || ext == 'BMP') ext = 'IMAGE'
                    $("#namaFile"+id).parent().next().html(ext);
                }
                else
                    tambahPenyebab(encodeURIComponent(input.files[i].name))
            }
            
        }
    </script>

     <script type="text/javascript">
        function tambahPenyebab2(idr,filename='') {
            var id = $('#tambahAttacmentSertifikat'+idr+' tr').length+1;
            $.get("app/loadUrl/app/tempalate_row_attacment2?id="+id+"&filename="+filename+"&reqPrimaryId="+idr, function(data) {
                $("#tambahAttacmentSertifikat"+idr).append(data);
            });
        }

        function getFileName2(input, id) {
            for (var i = 0; i < input.files.length; i++) {
                if(i == 0)
                {
                    $("#namaFile2"+id).html(input.files[0].name);
                    var ext = input.files[0].name.split('.').pop();
                    ext = ext.toUpperCase();
                    if(ext.length > 3) ext = '';
                    if(ext == 'PNG' || ext == 'JPG' || ext == 'JPEG' || ext == 'BMP') ext = 'IMAGE'
                    $("#namaFile2"+id).parent().next().html(ext);
                }
                else
                    tambahPenyebab2(encodeURIComponent(input.files[i].name))
            }
            
        }
    </script>
      <script type="text/javascript">
        $( document ).ready(function() {
        setTimeout(function(){
            selisih_tahuns();
        }, 1000);
        $('#tableWorkingHistory').dataTable();
         $('#tableProjectHistory').dataTable();
    });
    </script>

    <script type="text/javascript">

    function hapus_sertifikat(element, id) {
        var reqIdSertifikatDelete = $("#reqIdSertifikatDelete").val();
        $("#reqIdSertifikatDelete").val(reqIdSertifikatDelete+id+',')
        element.parent().parent().remove();
    }

    function addRowCertificate()
    {
        var rownum= $('#tbodyAuditee tr').length+1;
        var s_url= "app/loadUrl/app/personal_kualifikasi_add_row.php?reqId=<?=$id?>&reqIndex="+rownum;
        $.ajax({'url': s_url,'success': function(data){
            $("#tbodyAuditee").append(data);
        }});

    }

        function selisih_tahuns(){
             var tgl2 =datenow();
                   var tgl1 =   $('#reqBirthDate').datebox('getValue');

                   var tgl3 = tgl1.split('-');
                   var tgl4 = tgl3[1]+'/'+tgl3[0]+"/"+tgl3[2];
                   var selisih =hitungSelisihHari(tgl4,tgl2);
                   var selisih2 = selisih/365;
                   // console.log(tgl4);
                   //   console.log(tgl2)
                  $("#reqYear").val(Math.round(selisih2));
                   // console.log(selisih);
                   // var tgl2 =   $('#reqToDate').datebox('getValue');
        }
    </script>
</div>
</div>