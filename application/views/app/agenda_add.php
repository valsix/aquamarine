<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("Slider");
$slider = new Slider();

$reqId = $this->input->get("reqId");

if($reqId == ""){
$reqMode = "insert";
$reqTanggal = date("d-m-Y");
$reqJam = date("H:i");
}
else
{
	$reqMode = "ubah";
	$slider->selectByParams(array("A.SLIDER_ID" => $reqId));
	$slider->firstRow();
	$reqId             = $slider->getField("SLIDER_ID");
	$reqTipe       		= $slider->getField("TIPE");
	$reqTanggal                  = $slider->getField("HARI");
	$reqJam                  = $slider->getField("JAM");
	$reqNama                    = $slider->getField("NAMA");
	$reqKeterangan              = $slider->getField("KETERANGAN");
	$reqLinkFile				= $slider->getField("LINK_FILE");
}
?>

<!--// plugin-specific resources //--> 
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script> 
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script> 
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script> 
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css">

<div class="col-md-12">
    
  <div class="judul-halaman"> <a href="app/index/agenda">Agenda</a> &rsaquo; Kelola Agenda</div>   

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
                        <label for="reqNama" class="control-label col-md-2">Tanggal (d-m-y h24:mi)</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                	<table>
                                    <tr>
                                    <td>
                                    <input type="text" id="reqTanggal" class="easyui-datebox textbox form-control" required name="reqTanggal"  value="<?=$reqTanggal?>" data-options="required:true" style="width:100%" />
                                    </td>
                                    <td>&nbsp;&nbsp;</td>
                                    <td>
                                    <input class="easyui-validatebox textbox form-control" type="text" name="reqJam"  id="reqJam" value="<?=$reqJam?>" onkeydown="return format_menit(event, 'reqJam');" data-options="required:true" style="width:60%" maxlength="5">
                                	</td>
                                    </tr>
                                    </table>
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
                      
                                                    
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqJenis" value="AGENDA" />
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
					url:'web/slider_json/add',
                    onSubmit:function(){
                        return $(this).form('enableValidation').form('validate');
                    },
					success:function(data){
						//alert(data);
						$.messager.alertLink('Info', data, 'info', "app/index/agenda");	
					}
                });
            }
            function clearForm(){
                $('#ff').form('clear');
            }
						
        </script>
    
    </div>        
    
</div>



