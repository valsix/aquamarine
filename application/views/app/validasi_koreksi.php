<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("Pegawai");
$pegawai = new Pegawai();

$reqId = $this->input->get("reqId");

if($reqId == ""){
$reqMode 		= "insert";
$reqTanggal = date("d-m-Y");
$reqJam 		= date("H:i");
$reqJenis 	= "URL";
}
else
{
	$reqMode = "ubah";
	$pegawai->selectByParams(array("PEGAWAI_ID" => $reqId));
	$pegawai->firstRow();
	
		$reqNrp				= $pegawai->getField("NRP");
		$reqNip				= $pegawai->getField("NIP");
		$reqNama			= $pegawai->getField("NAMA");
		$reqNamaPanggilan	= $pegawai->getField("NAMA_PANGGILAN");
		$reqJenisKelamin	= $pegawai->getField("JENIS_KELAMIN");
		$reqTempatLahir		= $pegawai->getField("TEMPAT_LAHIR");
		$reqTanggalLahir	= $pegawai->getField("TANGGAL_LAHIR");
		$reqUnitKerja		= $pegawai->getField("UNIT_KERJA");
		$reqAlamat			= $pegawai->getField("ALAMAT");
		$reqNomorHp			= $pegawai->getField("NOMOR_HP");
		$reqEmailPribadi	= $pegawai->getField("EMAIL_PRIBADI");
		$reqEmailBulog		= $pegawai->getField("EMAIL_BULOG");
		$reqNomorWa			= $pegawai->getField("NOMOR_WA");
		$reqGolonganDarah	= $pegawai->getField("GOLONGAN_DARAH");

	
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css"/>

<div class="col-md-12">

  <div class="judul-halaman"> <a href="app/index/validasi">Validasi</a> &rsaquo; Koreksi Profil Pemohon</div>

    <div class="konten-area">
    	<div class="konten-inner">
            <div> 
                <!--<div class='panel-body'>-->
		        <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Data</h3>
                    </div>

                   

                   <?
					if($pegawai->getField("FOTO") == "")
					{}
					else
					{
					?>
                   <div style="position:absolute; right:0">
                   	<img src="uploads/<?=$pegawai->getField("FOTO")?>" style="margin-right:100px;" height="200">
                   </div>
                    <?	
					}
					?>
                    <!-- NAMA -->
                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">Nama</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input readonly type="text" id="reqNama" class="easyui-textbox textbox form-control"
                                    required name="reqNama" maxlength="100"  value="<?=$reqNama ?>"
                                    data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- NAMA_PANGGILAN -->
                    <div class="form-group">
                        <label for="reqNamaPanggilan" class="control-label col-md-2">Nama Panggilan</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input readonly type="text" id="reqNamaPanggilan" class="easyui-textbox textbox form-control"
                                    required name="reqNamaPanggilan" maxlength="100"  value="<?=$reqNamaPanggilan ?>"
                                    data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqNrp" class="control-label col-md-2">NRP</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input  type="text" id="reqNrp" class="easyui-textbox textbox form-control"
                                    required name="reqNrp" maxlength="100"  value="<?=$reqNrp ?>"
                                    data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- NIP -->
                    <div class="form-group">
                        <label for="reqNip" class="control-label col-md-2">NIP</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input  type="text" id="reqNip" class="easyui-textbox textbox form-control"
                                    required name="reqNip" maxlength="100"  value="<?=$reqNip ?>"
                                    data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />

              </form>
			</div>
                <div style="text-align:center;padding:5px">
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                </div>

            </div>

        </div>

		<script>
            function submitForm(){

                $('#ff').form('submit',{ 
					url:'web/pegawai_json/koreksi',
                    onSubmit:function(){
                        return $(this).form('enableValidation').form('validate');
                    },
					success:function(data){
						//alert(data);
						$.messager.alertLink('Info', data, 'info', "app/index/validasi");
					}
                });
            }
            function clearForm(){
                $('#ff').form('clear');
            }

        </script>
    </div>
</div>
