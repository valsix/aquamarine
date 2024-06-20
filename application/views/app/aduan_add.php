<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("Aduan");
$aduan = new Aduan();

$reqId = $this->input->get("reqId");

if($reqId == ""){
exit;
}
else
{
	$reqMode = "ubah";
	$aduan->selectByParamsMonitoring(array("ADUAN_ID" => $reqId));
	$aduan->firstRow();
	$reqId                    = $aduan->getField("ADUAN_ID");
	$reqNip                   = $aduan->getField("NIP");
	$reqNama                   = $aduan->getField("NAMA");
	$reqAduan                  = $aduan->getField("ADUAN");
	$reqLinkFile               = $aduan->getField("LINK_FILE");
	$reqBalasan                = $aduan->getField("BALASAN");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css"/>

<div class="col-md-12">

  <div class="judul-halaman"> <a href="app/index/aduan">Aduan</a> &rsaquo; Kelola Aduan</div>

    <div class="konten-area">
    	<div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
		        <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Data</h3>
                    </div>

                <div style="position:absolute; right:0">
                    <a href="javascript:void(0)" class="btn btn-warning" onclick="forwardPesan()" style="margin-right:200px; margin-top:4px"><i class="fa fa-mail-forward fa-lg" aria-hidden="true"></i> Forward</a>
                </div>
                    <!-- nip -->
                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">Anggota</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <span style="color:#FFFFFF"><?=$aduan->getField("NIP_NAMA")?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- nama -->
                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">Judul</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <span style="color:#FFFFFF"><?=$reqNama ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">Tanggal Aduan</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <span style="color:#FFFFFF"><?=$aduan->getField("CREATED_DATE") ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?
                    if($reqLinkFile == "")
					{}
					else
					{
					?>
					
                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">Attachment</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <a href="uploads/<?=$reqLinkFile?>" style="color:#FFFFFF">download</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?
					}
					?>
                    <!-- aduan -->

					<div class="form-group">
                        <label for="reqAduan" class="control-label col-md-2">Isi Aduan</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <span style="color:#FFFFFF"><?=$reqAduan ?> </span>
                                </div>
                            </div>
                        </div>
                    </div>


										<!-- linkfile -->
                    <?
                    if($reqBalasan == "")
					{
					?>                    
                    <div class="form-group">
                        <label for="reqKeterangan" class="control-label col-md-2">Balas Aduan</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <textarea name="reqBalasan" style="width:100%; height:200px"><?=$reqBalasan?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
					<?
					}
					else
					{
					?>
                    
                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">Balasan</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <span style="color:#FFFFFF"><?=$reqBalasan ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">Tanggal Balas</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <span style="color:#FFFFFF"><?=$aduan->getField("BALASAN_DATE") ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?
					}
					?>
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />

              </form>
			</div><?
                    if($reqBalasan == "")
					{
					?>
                <div style="text-align:center;padding:5px">
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                </div>
                	<?
					}
					?>

            </div>

        </div>

		<script>
            function submitForm(){

                $('#ff').form('submit',{
					url:'web/aduan_json/add',
                    onSubmit:function(){
                        return $(this).form('enableValidation').form('validate');
                    },
					success:function(data){
						$.messager.alertLink('Info', data, 'info', "app/index/aduan");
					}
                });
            }
			
			
			function forwardPesan(){
				
				$.messager.prompt("Forward aduan", 'Masukkan email : ', function(r){

						if (r){
							
							
							var win = $.messager.progress({
								title:'Office Management | PT Aquamarine Divindo Inspection',
								msg:'kirim email...'
							});						
							
							$.post( "web/aduan_json/forward/", { reqId: "<?=$reqId?>", reqMail: r })
							  .done(function( data ) {
								  	$.messager.progress('close');
									$.messager.alertLink('Info', data, 'info', "app/index/aduan_add/?reqId=<?=$reqId?>");
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
