<?php
$id = $this->input->get("id");
$filename = $this->input->get("filename");
$ext = substr($filename, -3);
$ext = strtoupper($ext);
if(count($ext) > 3) $ext = '';
if($ext == 'PNG' || $ext == 'JPG' || $ext == 'JPEG' || $ext == 'BMP') $ext = 'IMAGE';
?>
<tr>
    <td><input type="file" onchange="getFileName(this, '<?=$id?>')" name="document<?=$filename?>[]" multiple id="document<?=$id?>" class="form-control">
      <input type="hidden" name="reqLinkFileTemp<?=$filename?>[]" id="reqLinkFileTemp<?=$id?>" value="">
    
    </td>
    <td>ALL</td>
    <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
</tr>