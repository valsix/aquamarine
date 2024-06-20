<?
$reqParent = $this->input->get("reqParent");
$reqId     = $this->input->get("id");
$id =rand();
?>
<tr>

<td><input type="text" id="reqUrutRincian<?=$id?>" onkeypress='validate(event)' class="easyui-validatebox textbox form-control" name="reqUrutRincian<?=$reqParent?>[]" value="<?=$reqId?>" style=" width:100%" /></td> 
<td class="rincian<?=$reqParent?>">
	<input type="hidden" id="reqWeeklyProgresRincianId<?=$id?>" name="reqWeeklyProgresRincianId<?=$reqParent?>[]" value=""> 
	<input type="text" id="reqRincian" class="easyui-validatebox textbox form-control" name="reqRincian<?=$reqParent?>[]" value="" style=" width:100%" /></td>
<td>  <a onclick="hapus_folder3(this,<?=$id?>)"  class="btn btn-danger " ><i class="fa fa-fw fa-remove"></i></a> </td>
</tr>