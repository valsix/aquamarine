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

	
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css"/>

<div class="col-md-12">

  <div class="judul-halaman"> <a href="app/index/validasi">Validasi Pendaftaran</a> &rsaquo; Validasi</div>

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
<div class="form-group">
    <label for="reqNrp" class="control-label col-md-2">NRP</label>
    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-11">
                <input readonly type="text" id="reqNrp" class="easyui-textbox textbox form-control"
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
                <input readonly type="text" id="reqNip" class="easyui-textbox textbox form-control"
                required name="reqNip" maxlength="100"  value="<?=$reqNip ?>"
                data-options="required:true" style="width:100%" />
            </div>
        </div>
    </div>
</div>
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
<!-- JENIS_KELAMIN -->
<div class="form-group">
    <label for="reqJenisKelamin" class="control-label col-md-2">Jenis Kelamin</label>
    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-11">
                <input type="JenisKelamin" id="reqNama" class="easyui-textbox textbox form-control"
                required name="reqJenisKelamin" maxlength="100"  value="<?=$reqJenisKelamin ?>"
                data-options="required:true" style="width:100%" />
            </div>
        </div>
    </div>
</div>
<!-- TEMPAT_LAHIR -->
<div class="form-group">
    <label for="reqTempatLahir" class="control-label col-md-2">Tempat Lahir</label>
    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-11">
                <input readonly type="text" id="reqTempatLahir" class="easyui-textbox textbox form-control"
                required name="reqTempatLahir" maxlength="100"  value="<?=$reqTempatLahir ?>"
                data-options="required:true" style="width:100%" />
            </div>
        </div>
    </div>
</div>
<!-- TANGGAL_LAHIR -->
<div class="form-group">
    <label for="reqTanggalLahir" class="control-label col-md-2">Tanggal Lahir</label>
    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-11">
                <input readonly type="text" id="reqTanggalLahir" class="easyui-datebox textbox form-control"
                required name="reqTanggalLahir" maxlength="100"  value="<?=$reqTanggalLahir ?>"
                data-options="required:true" style="width:100%" />
            </div>
        </div>
    </div>
</div>
<!-- UNIT_KERJA -->
<div class="form-group">
    <label for="reqUnitKerja" class="control-label col-md-2">Unit Kerja</label>
    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-11">
                <input readonly type="text" id="reqUnitKerja" class="easyui-textbox textbox form-control"
                required name="reqUnitKerja" maxlength="100"  value="<?=$reqUnitKerja ?>"
                data-options="required:true" style="width:100%" />
            </div>
        </div>
    </div>
</div>
<!-- ALAMAT -->
<div class="form-group">
    <label for="reqAlamat" class="control-label col-md-2">Alamat</label>
    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-11">
                <input readonly type="text" id="reqAlamat" class="easyui-textbox textbox form-control"
                required name="reqAlamat" maxlength="100"  value="<?=$reqAlamat ?>"
                data-options="required:true" style="width:100%" />
            </div>
        </div>
    </div>
</div>
<!-- NOMOR_HP -->
<div class="form-group">
    <label for="reqNomerHp" class="control-label col-md-2">Nomer Hp</label>
    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-11">
                <input type="number" id="reqNomorHp" class="easyui-textbox textbox form-control"
                required name="reqNomorHp" maxlength="100"  value="<?=$reqNomorHp ?>"
                data-options="required:true" style="width:100%" />
            </div>
        </div>
    </div>
</div>
<!-- EMAIL_PRIBADI -->
<div class="form-group">
    <label for="reqEmailPribadi" class="control-label col-md-2">Email Pribadi</label>
    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-11">
                <input readonly type="text" id="reqEmailPribadi" class="easyui-textbox textbox form-control"
                required name="reqEmailPribadi" maxlength="100"  value="<?=$reqEmailPribadi ?>"
                data-options="required:true" style="width:100%" />
            </div>
        </div>
    </div>
</div>
<!-- EMAIL_BULOG -->
<div class="form-group">
    <label for="reqEmailBulog" class="control-label col-md-2">Email Bulog</label>
    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-11">
                <input readonly type="text" id="reqEmailBulog" class="easyui-textbox textbox form-control"
                required name="reqEmailBulog" maxlength="100"  value="<?=$reqEmailBulog ?>"
                data-options="required:true" style="width:100%" />
            </div>
        </div>
    </div>
</div>
<!-- NOMOR_WA -->
<div class="form-group">
    <label for="reqNomorWa" class="control-label col-md-2">Nomor Wa</label>
    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-11">
                <input readonly type="text" id="reqNomorWa" class="easyui-textbox textbox form-control"
                required name="reqNomorWa" maxlength="100"  value="<?=$reqNomorWa ?>"
                data-options="required:true" style="width:100%" />
            </div>
        </div>
    </div>
</div>


                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="hidden" name="reqValidasi" id="reqValidasi"/>

              </form>
			</div>
                <div style="text-align:center;padding:5px">
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm('SETUJUI')">Submit</a>
                    <a href="javascript:void(0)" class="btn btn-danger" onclick="submitForm('TOLAK')">Tolak</a>
                </div>

            </div>

        </div>

		<script>
            function submitForm(validasi){
				
				$("#reqValidasi").val(validasi);
				
				
					
				$.messager.confirm('Konfirmasi', validasi + ' PENDAFTARAN ANGGOTA?',function(r){
				if (r){
							$('#ff').form('submit',{
								url:'web/pegawai_json/validasi',
								onSubmit:function(){
									return $(this).form('enableValidation').form('validate');
								},
								success:function(data){
									//alert(data);
									$.messager.alertLink('Info', data, 'info', "app/index/validasi");
								}
							});
							
					}
				});
            }
            function clearForm(){
                $('#ff').form('clear');
            }

        </script>
    </div>
</div>
