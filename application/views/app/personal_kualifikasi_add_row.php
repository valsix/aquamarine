<?
$reqIndex = $this->input->get("reqIndex");
$reqId = $this->input->get("reqId");
$id=rand();
$i= $reqIndex;

?>

<tr>
    <td>
          <input class="easyui-combobox form-control" style="width:100%" name="reqTypeOfService[]"  id="reqTypeOfService<?=$id?>" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/personalCertificate'" value="<?=$reqDescription?>" />
    </td>
    <td>
        <input readonly class="easyui-validatebox textbox form-control" type="hidden" name="reqIdCertifcate[]"  id="reqIdCertifcate<?=$id?>" value="<?=$reqListCertificate[$i]?>" data-options="required:true" style="width:100%">
        <input  class="easyui-validatebox textbox form-control" type="text" name="reqCertificateName[]"  id="reqCertificateName<?=$id?>" value="<?=$reqNames?>" data-options="required:true" style="width:100%">
    </td>
    <td>
        <input  class="easyui-datebox  form-control dates" type="text" data-options="formatter:myformatter,parser:myparser" name="reqIssueDate[]"  id="reqIssueDate<?=$id?>" value="<?=$reqIssuedDates?>" data-options="required:true" style="width:100%">
    </td>
    <td>
       <input  class="easyui-datebox  form-control dates" data-options="formatter:myformatter,parser:myparser" type="text" name="reqExpiredDate[]"  id="reqExpiredDate<?=$id?>" value="<?=$reqExpiredDates?>"  style="width:100%">
       <input  class="easyui-validatebox  form-control" type="hidden" name="reqIdSertifikat[]"  id="reqIdSertifikat<?=$id?>" value="<?=$reqIdSertifikat?>"  style="width:100%">

    </td>
    <td style="text-align:center">
        <a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a>

    </td>             
</tr>
<script type="text/javascript">
    $("#reqTypeOfService<?=$id?>").combobox({
        width:'250',
        editable:false, 
        valueField:'id',
        textField:'text',
        url:'combo_json/personalCertificate'
    })
    $("#reqExpiredDate<?=$id?>").datebox()
    $("#reqIssueDate<?=$id?>").datebox()
    function myformatter(date){
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
    }
    function myparser(s){
        if (!s) return new Date();
        var ss = (s.split('-'));
        var y = parseInt(ss[0],10);
        var m = parseInt(ss[1],10);
        var d = parseInt(ss[2],10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
            return new Date(d,m-1,y);
        } else {
            return new Date();
        }
    }
</script>



