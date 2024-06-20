<?
$id = rand();

$reqId              = $this->input->post("reqId");
$reqCertificateId   = $this->input->post("reqCertificateId");
$reqName            = $this->input->post("reqName");
$reqDescription     = $this->input->post("reqDescription");
$reqIssuedDate      = $this->input->post("reqIssuedDate");
$reqExpiredDate     = $this->input->post("reqExpiredDate");
$reqSurveyor        = $this->input->post("reqSurveyor");


?>
<tr>
    <td>
          <input class="easyui-combobox form-control" style="width:100%" name="reqTypeOfService[]"  id="reqTypeOfService<?=$id?>" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/personalCertificate',required:true" value="" />
    </td>
    <td>
        <input readonly class="easyui-validatebox textbox form-control" type="hidden" name="reqIdCertifcate[]"  id="reqIdCertifcate<?=$id?>" value="<?=$reqId?>" data-options="required:true" style="width:100%">
         <input readonly class="easyui-validatebox textbox form-control" type="text" name="reqCertificateName[]"  id="reqCertificateName<?=$id?>" value="<?=$reqName?>" data-options="required:true" style="width:100%">
    </td>
    <td>
         
        <input readonly class="easyui-validatebox  form-control dates" type="text" name="reqIssueDate[]"  id="reqIssueDate<?=$id?>" value="<?=$reqIssuedDate?>" data-options="required:true" style="width:100%">
    </td>
    
    <td>
        <script>
            $("#reqTypeOfService<?=$id?>").combobox();
            $(".dates").datebox();
        </script>
        <input readonly class="easyui-datebox  form-control dates" type="text" name="reqExpiredDate[]"  id="reqExpiredDate<?=$id?>" value="<?=$reqExpiredDate?>" data-options="required:true" style="width:100%">
        
    </td>
    <td style="text-align:center">
        <a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a>
            
    </td>             
</tr>