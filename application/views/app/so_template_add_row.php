<?
$id=$this->input->get("reqIndex");
$i= $this->input->get("reqIndex");

?>

<tr>
    <td>
        <input readonly type="hidden" name="reqSoTemplateEquipId[]"  id="reqSoTemplateEquipId<?=$id?>" value="<?=$reqSoTemplateEquipId?>">
        <input type="hidden" value="<?=$reqEquipId?>" name="reqEquipId[]" id="reqEquipId<?=$id?>">
        <div class="input-group">
            <input type="text"  class="easyui-validatebox textbox form-control" id="reqEquipName<?=$id?>" name="reqEquipName[]" value="<?= $reqEquipName ?>" style=" width:100%" onclick="pilih_company('<?=$id?>')"/> 
            <span onclick="pilih_company('<?=$id?>')"class="input-group-addon" > ... </span>
        </div>
    </td>
    <td>
        <input class="easyui-validatebox form-control" id="reqKategori<?=$id?>" value="<?=$reqKategori?>" readonly />
    </td>
    <td>
        <input class="easyui-validatebox form-control" id="reqNoSerial<?=$id?>" value="<?=$reqNoSerial?>" readonly />
    </td>
    <td>
        <input class="easyui-validatebox form-control" id="reqCondition<?=$id?>" value="<?=$reqCondition?>" readonly />
    </td>
    <td>
        <input class="easyui-combobox form-control" style="width:100%" id="reqOutCondition<?=$id?>" name="reqOutCondition[]" data-options="width:'120',editable:false, valueField:'id',textField:'text',url:'combo_json/equipmentStatus'" value="<?=$reqOutCondition?>" />
    </td>
    <td>
        <input class="easyui-combobox form-control" style="width:100%" id="reqInCondition<?=$id?>" name="reqInCondition[]" data-options="width:'120',editable:false, valueField:'id',textField:'text',url:'combo_json/equipmentStatus'" value="<?=$reqInCondition?>" />
    </td>
    <td>
        <input  class="easyui-validatebox  form-control" name="reqRemark[]" id="reqRemark<?=$id?>" value="<?=$reqRemark?>"  style="width:100%">
    </td>
    <td style="text-align:center">
        <button type="button"  class="btn btn-danger hapusi" onclick="$(this).parent().parent().remove();"><i class="fa fa-trash-o fa-lg"> </i> </button>
    </td>             
</tr>
<script type="text/javascript">
    $("#reqOutCondition<?=$id?>").combobox({
        width:'120',editable:false, valueField:'id',textField:'text',url:'combo_json/equipmentStatus'
    })
    $("#reqInCondition<?=$id?>").combobox({
        width:'120',editable:false, valueField:'id',textField:'text',url:'combo_json/equipmentStatus'
    })
</script>



