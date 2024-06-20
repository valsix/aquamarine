<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("Aplikasi");
$aplikasi = new Aplikasi();

$reqId = $this->input->get("reqId");

if($reqId == ""){
$reqMode = "insert";
$reqTanggal = date("d-m-Y");
$reqJam = date("H:i");
$reqJenis  = "URL";
}
else
{
	$reqMode = "ubah";
	$aplikasi->selectByParams(array("A.APLIKASI_ID" => $reqId));
	$aplikasi->firstRow();
	$reqUrut                    = $aplikasi->getField("URUT");
	$reqId                      = $aplikasi->getField("APLIKASI_ID");
	$reqJenis       		    = $aplikasi->getField("JENIS");
	$reqLinkAplikasi            = $aplikasi->getField("LINK_URL");
	$reqNama                    = $aplikasi->getField("NAMA");
	$reqKeterangan              = $aplikasi->getField("KETERANGAN");
	$reqLinkFile				= $aplikasi->getField("LINK_FILE");
}
?>

<!--// plugin-specific resources //--> 
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script> 
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script> 
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script> 
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css">

<div class="col-md-12">
    
  <div class="judul-halaman"> <a href="app/index/aplikasi">Aplikasi</a> &rsaquo; Kelola Aplikasi</div>   

    <div class="konten-area">
    	<div class="konten-inner">
            <div>
            	
                <!--<div class='panel-body'>-->
		        <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Data</h3>                      
                    </div>
                                      

                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">Nama</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqUrut" class="easyui-numberbox textbox form-control" required name="reqUrut" maxlength="3"  value="<?=(int)$reqUrut ?>" data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">Nama</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama"  value="<?=$reqNama ?>" data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqKeterangan" class="control-label col-md-2">Keterangan</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqKeterangan" style="width:100%; height:200px"><?=$reqKeterangan ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                              
                    
                    <div class="form-group">
                        <label for="reqTipe" class="control-label col-md-2">Jenis</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" name="reqJenis" class="easyui-combobox"  id="reqJenis"
                                                    data-options="width:'300',editable:false, valueField:'id',textField:'text',url:'combo_json/comboJenisAplikasi'"  value="<?=$reqJenis?>"  required />
                                </div>
                            </div>
                        </div>
                    </div>
                                    

                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">Link Aplikasi</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLinkAplikasi" class="easyui-validatebox textbox form-control" required name="reqLinkAplikasi"  value="<?=$reqLinkAplikasi ?>" data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="reqKeterangan" class="control-label col-md-2">File Gambar</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <label for="file-upload" class="custom-file-upload">
                                    	<i class="fa fa-cloud-upload"></i> Pilih File
                                    </label>
                                    <input name="reqLinkFile[]" type="file" maxlength="1"  class="multi with-preview maxsize-1024" accept="jpg|jpeg|png" value=""/>               
                                </div>
                            </div>
                        </div>
                    </div>
					<?
                    if ($reqLinkFile == "")
                    {}
                    else
                    {
                    ?>
                    <div class="form-group" >
                    <label for="reqKeterangan" class="col-md-2"></label>
                    <div class="col-md-9">
                    <div class="form-group"style="margin-top:-25px !important">
                        <div class="col-md-11">
                            <?
                            $arrDokumen = explode(",", $reqLinkFile);
                            for($i=0;$i<count($arrDokumen);$i++)
                            {
                            ?>
                            <div class="MultiFile-label">
                                <a class="MultiFile-remove" onclick="$(this).parent().remove();">
                                    <i class="fa fa-trash fa-lg"></i>
                                </a>
                                <span>
                                    <span class="MultiFile-label" title="File selected: <?=$arrDokumen[$i]?>">
                                        <span class="img">
                                            <img src="uploads/<?=$arrDokumen[$i]?>" class="MultiFile-preview" style="max-height:100px; max-width:100px;">
                                        </span>
                                    <span class="MultiFile-title"><?=$arrDokumen[$i]?></span>
                                    </span>
                                </span>
                                <input type="hidden" name="reqLinkFileTemp[]" value="<?=$arrDokumen[$i]?>" />
                            </div>
                            <?
                            }
                            ?>
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
					url:'web/aplikasi_json/add',
                    onSubmit:function(){
                        return $(this).form('enableValidation').form('validate');
                    },
					success:function(data){
						//alert(data);
						$.messager.alertLink('Info', data, 'info', "app/index/aplikasi");	
					}
                });
            }
            function clearForm(){
                $('#ff').form('clear');
            }
						
        </script>
    
    </div>        
    
</div>



