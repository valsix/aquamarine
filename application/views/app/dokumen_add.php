<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Dokumen");
$dokumen = new Dokumen();

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
	$dokumen->selectByParams(array("DOKUMEN_ID" => $reqId));
	$dokumen->firstRow();
	$reqNama             = $dokumen->getField("NAMA");
  $reqKeterangan       = $dokumen->getField("KETERANGAN");
  $reqLinkFile        = $dokumen->getField("LINK_FILE");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css">

<div class="col-md-12">

  <div class="judul-halaman"> <a href="app/index/dokumen">Dokumen</a> &rsaquo; Kelola Dokumen</div>

    <div class="konten-area">
    	<div class="konten-inner">
            <div>

                <!--<div class='panel-body'>-->
		        <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Data</h3>
                    </div>

                    <!-- nama -->
                    <div class="form-group">
                        <label for="reqUrut" class="control-label col-md-2">Nama</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNama" class="easyui-textbox textbox form-control"
                                    required name="reqNama" maxlength="300"  value="<?=$reqNama ?>"
                                    data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- keterangan -->
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


                    <!-- linkfile -->
                    <div class="form-group">
                        <label for="reqKeterangan" class="control-label col-md-2">File Dokumen</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <label for="file-upload" class="custom-file-upload">
                                    	<i class="fa fa-cloud-upload"></i> Pilih File
                                    </label>
                                    <input name="reqLinkFile[]" type="file" maxlength="1"  class="multi with-preview maxsize-25480" accept="jpg|jpeg|png|doc|docx|pdf" value="<?=$reqLinkFile ?>"/>
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
					url:'web/dokumen_json/add',
                    onSubmit:function(){
                        return $(this).form('enableValidation').form('validate');
                    },
					success:function(data){
						//alert(data);
						$.messager.alertLink('Info', data, 'info', "app/index/dokumen");
					}
                });
            }
            function clearForm(){
                $('#ff').form('clear');
            }

        </script>
    </div>
</div>
