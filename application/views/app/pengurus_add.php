<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("Pengurus");
$pengurus = new Pengurus();

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
	$pengurus->selectByParams(array("PENGURUS_ID" => $reqId));
	$pengurus->firstRow();
	$reqUrut 						= $pengurus->getField("URUT");
	$reqNip 						= $pengurus->getField("NIP");
	$reqNama 						= $pengurus->getField("NAMA");
	$reqJabatan 						= $pengurus->getField("JABATAN");
	$reqJabatanPengurus 					= $pengurus->getField("JABATAN_PENGURUS");
	$reqTanggalMulai 						= $pengurus->getField("TANGGAL_MULAI");
	$reqTanggalAkhir 						= $pengurus->getField("TANGGAL_AKHIR");
	$reqCabangId = $pengurus->getField("CABANG_ID");

	
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css"/>

<div class="col-md-12">

  <div class="judul-halaman"> <a href="app/index/pengurus">Pengurus</a> &rsaquo; Kelola Pengurus</div>

    <div class="konten-area">
    	<div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
		        <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Data</h3>
                    </div>

                    <!-- nip -->
                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">Urut</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqUrut" class="easyui-textbox textbox form-control"
                                    required name="reqUrut"   value="<?=(	int)$reqUrut ?>"
                                    data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- nip -->
                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">Unit Kerja</label>
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <div class="col-md-11">
                                   <input name="reqCabangId" class="easyui-combobox form-control" id="reqCabangId"
                                            data-options="width:'160',editable:false,valueField:'id',textField:'text',url:'combo_json/cabang',
                                                onSelect: function(rec){
                                                }" style="color:#FFFFFF !important" value="<?=$reqCabangId?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- nip -->
                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">NIP</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                
                                    <table>
                                    <tr>
                                    <td>
                                    <input type="text" id="reqNip" class="easyui-textbox textbox form-control"
                                    required name="reqNip"   value="<?=$reqNip ?>"
                                    data-options="required:true" style="width:100%" readonly  />
                                    </td>
                                    <td>
                                    &nbsp;&nbsp;
                                    </td>
                                    <td>
                                    <a id="btnAdd" onClick="openAdd('app/loadUrl/app/pegawai_lookup')"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i> </a>
                                    </td>
                                    </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- nama -->
                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">Nama</label>
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <div class="col-md-11">
                                    <input type="text" id="reqNama" class="easyui-textbox textbox form-control"
                                    required name="reqNama" maxlength="100"  value="<?=$reqNama ?>"
                                    data-options="required:true" style="width:100%" readonly />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <!-- nama -->
                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">Jabatan Pengurus</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqJabatanPengurus" class="easyui-textbox textbox form-control"
                                    required name="reqJabatanPengurus" maxlength="100"  value="<?=$reqJabatanPengurus ?>"
                                    data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <!-- nama -->
                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">Tanggal Mulai</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqTanggalMulai" class="easyui-datebox textbox form-control"
                                    required name="reqTanggalMulai" maxlength="100"  value="<?=$reqTanggalMulai ?>"
                                    data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <!-- nama -->
                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">Tanggal Selesai</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqTanggalAkhir" class="easyui-datebox textbox form-control"
                                    required name="reqTanggalAkhir" maxlength="100"  value="<?=$reqTanggalAkhir ?>"
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
                    <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()">Clear</a>
                </div>

            </div>

        </div>

		<script>
            function submitForm(){

                $('#ff').form('submit',{
					url:'web/pengurus_json/add',
                    onSubmit:function(){
                        return $(this).form('enableValidation').form('validate');
                    },
					success:function(data){
						//alert(data);
						$.messager.alertLink('Info', data, 'info', "app/index/pengurus");
					}
                });
            }
            function clearForm(){
                $('#ff').form('clear');
            }

			function tambahPegawai(id, nama)
			{
				$("#reqNip").val(id);	
				$("#reqNama").val(nama);	
			}
        </script>
    </div>
</div>
