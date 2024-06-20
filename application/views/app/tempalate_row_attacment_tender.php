<?php
$id = $this->input->get("id");
$filename = $this->input->get("filename");
$type = $this->input->get("type");
$typeId = $this->input->get("typeId");
$jenis = $this->input->get("jenis");

// echo $jenis;exit; 
?>
<tr>
	
    <td>


    	<input type="hidden" style="width:100%" name="reqType<?=$type?>[]" value="<?=$jenis?>" />
    	<input type="file" onchange="getFileName(this, '<?=$id?>', '<?=$type?>', '<?=$typeId?>', '<?=$jenis?>')" name="reqLinkFile<?=$type?>[]" multiple class="form-control">
		<input type="hidden" name="reqLinkFile<?=$type?>Temp[]" value="">
		<span style="margin-left: 50px" class="nama-temp-file" id="namaFile<?=$type?><?=$typeId?><?=$id?>"><?=$filename?></span>
    </td>
    <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
</tr>