<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("TipeAudit");
$this->load->model("TipeAuditTahapan");
$tipe_audit = new TipeAudit();
$tipe_audit_tahapan = new TipeAuditTahapan();

$reqId = $this->input->get("reqId");

if($reqId == ""){
$reqMode = "insert";
}
else
{
	$reqMode = "ubah";
	$tipe_audit->selectByParams(array("A.TIPE_AUDIT_ID" => $reqId));
	
	$tipe_audit->firstRow();
	$reqTipeAuditId             = $tipe_audit->getField("TIPE_AUDIT_ID");
	$reqTipe       = $tipe_audit->getField("TIPE");
	$reqNama                    = $tipe_audit->getField("NAMA");
	$reqKeterangan              = $tipe_audit->getField("KETERANGAN");
}
?>

<div class="col-md-12 col-with-sidebar">
    
	<div class="judul-halaman"> <a href="app/index/tipe_audit">TIPE AUDIT</a> &rsaquo; TIPE AUDIT DATA</div>   

    <div class="konten-area">
    	<div class="konten-inner">
            <div>
            	
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Informasi</h3>                      
                    </div>
                                      
                    <div class="form-group">
                        <label for="reqTipe" class="control-label col-md-2">Jenis</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" name="reqTipe" class="easyui-combobox"  id="reqTipe"
                                                    data-options="width:'300',editable:false, valueField:'id',textField:'text',url:'combo_json/comboJenisAudit'"  value="<?=$reqTipe?>"  required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">Nama</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama"  value="<?=$reqNama ?>" data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqKeterangan" class="control-label col-md-2">Keterangan</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqKeterangan" class="easyui-validatebox textbox form-control" required name="reqKeterangan"  value="<?=$reqKeterangan ?>" data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>TAHAPAN
                        </h3>                      
                    </div>       
                    <div class="form-group" id="divHeader">
                        <div class='col-md-12'>
                            <div class='form-group'>
                                <div class='col-md-12'>
                                                                                   
                                    <table class="table">
                                    <thead>
                                      <tr>
                                        <th style="width:5%">Urut</th>
                                        <th style="width:15%">Kode</th>
                                        <th style="width:60%">Nama</th>
                                        <th style="width:12%">Urut Tahapan</th>
                                        <th>Aksi <input type="checkbox" onClick="$('input[type=checkbox]').prop('checked', $(this).prop('checked'));"></th>
                                      </tr>
                                    </thead>
                                    <tbody id="tbodyInterval"> 
                                    <?
                                    $i = 0;
									$tipe_audit_tahapan->selectByParamsEntri((int)$reqId);
                                    while($tipe_audit_tahapan->nextRow())
                                    {
                                    ?>          
                                        <tr>
                                            <td>
                                                <input readonly class="easyui-validatebox textbox form-control" type="text" value="<?=$tipe_audit_tahapan->getField("URUT")?>" data-options="required:true" style="width:100%">
                                            </td>
                                            <td>
                                                <input readonly class="easyui-validatebox textbox form-control" type="text" name="reqTahapanKode[<?=$i?>]"  value="<?=$tipe_audit_tahapan->getField("KODE")?>" data-options="required:true" style="width:100%">
                                            </td>                                            
                                            <td>
                                                <input readonly class="easyui-validatebox textbox form-control" type="text" name="reqTahapanNama[<?=$i?>]" value="<?=$tipe_audit_tahapan->getField("NAMA")?>" style="width:100%">
                                            </td>
                                            <td>
                                                <input class="easyui-validatebox textbox form-control" type="text" name="reqTahapanUrut[<?=$i?>]"  id="reqTahapanUrut" value="<?=coalesce($tipe_audit_tahapan->getField("URUT_TAHAPAN"), $tipe_audit_tahapan->getField("URUT"))?>" data-options="required:true" style="width:100%">
                                            </td>
                                            <td style="text-align:center">
                                            	<input type="checkbox" name="reqTahapanId[<?=$i?>]" value="<?=$tipe_audit_tahapan->getField("TAHAPAN_ID")?>" <? if($tipe_audit_tahapan->getField("TIPE_AUDIT_TAHAPAN_ID") == "") {} else { ?>  checked <? } ?>>
                                            </td>     
                                        </tr>                   
                                    <?
                                    	$i++;
                                    }
                                    ?>
                                    </tbody>  
                                  </table>
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
            
		</div> <!-- END konten-inner-->
	</div> <!-- END konten-area-->
        
	<script>
        function submitForm(){
            
            $('#ff').form('submit',{
                url:'tipe_audit_json/add',
                onSubmit:function(){
                    return $(this).form('enableValidation').form('validate');
                },
                success:function(data){
                    $.messager.alertLink('Info', data, 'info', "app/index/tipe_audit");	
                }
            });
        }
        function clearForm(){
            $('#ff').form('clear');
        }
                    
    </script>
        
</div>



