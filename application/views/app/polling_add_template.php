<?                                            
$id = rand();
?>
<tr>
    <td>
    <input class="easyui-validatebox textbox form-control" type="text" name="reqJawaban[]"  id="reqJawaban<?=$id?>" value="<?=$reqJawaban?>" data-options="required:true" style="width:100%">
    </td>
    
    <td style="text-align:center">
    <input type="hidden" name="reqPollingDetilId[]" value="">
   	<a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a>
    </td>             
</tr>