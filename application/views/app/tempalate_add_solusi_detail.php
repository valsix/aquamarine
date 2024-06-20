<?
$id = rand();
$reqUrutNo = $this->input->get("id");
$reqParent = $this->input->get("reqParent");

?>
<tr class="trremove<?=$id?>">
  <td colspan="7">
  <div style="background:green;height: 8px;margin: 10px" class="solusi<?=$reqParent?>" > </div>
</td>
  </tr>
<tr class="trremove<?=$id?>">
  <td> <script type="text/javascript">
   $("#reqStatusProgres<?=$id?>").combobox();
   $("#reqDueDate<?=$id?>").datebox();
 </script>
 <input type="hidden" class="easyui-validatebox textbox form-control" id="reqParamInline<?=$id?>" name="reqParamInline<?=$reqParent?>[]" value='<?=$id?>'>
                                      <input type="hidden"  class="easyui-validatebox textbox form-control" id="reqWeeklyProgresInlineId<?=$id?>" name="reqWeeklyProgresInlineId<?=$reqParent?>[]" value=''>
                                      <input type="text" onkeypress='validate(event)' id="reqUrutInline<?=$id?>" class="easyui-validatebox textbox form-control" name="reqUrutInline<?=$reqParent?>[]" value="<?=$reqUrutNo?>" style=" width:100%" />
  </td>
   <td>  <textarea rows="5" type="text" id="reqProses" class="easyui-validatebox textbox form-control" name="reqProses<?=$reqParent?>[]" value="" style=" width:90%" /> </textarea></td>
    <td>  <input type="text" id="reqDueDate<?=$id?>" class="easyui-datebox textbox form-control datest" name="reqDueDate<?=$reqParent?>[]" value="<?= $reqName ?>" style="width: 180px" /></td>
     <td>   <input type="text" id="reqPicPerson<?=$id?>" class="easyui-validatebox textbox form-control datest" name="reqPicPerson<?=$reqParent?>[]" value="" style="width: 180px" /></td>
      <td>  <img id="imgLogo<?=$id?>" src="images/icon_arsip.jpg" style="height: 150px !important;width: 250px !important"><br>
        <div class="input-group">
                                        <span class="input-group-addon" onclick="openFile(<?=$id?>)"><i class="fa fa-upload fa-lg"></i> </span>
                                       
                                        <input type="text" class="form-control"  id="reqNamaFile<?=$id?>"  value="" 
                                        style=" width:80%"
                                        >
                                      </div>
                                <input type="file" style="display: none;" id="reqFilesName<?=$id?>" name="reqFilesName<?=$reqParent?>[]" class="form-control" style="width: 60%" >
                                <input type="hidden" class="easyui-validatebox textbox form-control" name="reqFilesNames<?=$reqParent?>[]" value="">
                                <input type="hidden" class="easyui-validatebox textbox form-control" id="reqValueFolder2<?=$id?>" value="0" />
      </td>
       <td>       <input id="reqStatusProgres<?=$id?>" class="easyui-combobox form-control comboboxs"  style="width:100%" name="reqStatusProgres<?=$reqParent?>[]" data-options="width:'230',editable:false, valueField:'id',textField:'text',url:'combo_json/comboStatusWeekly'" value="" /></td>
        <td>  <a onclick="hapus_folder2(this,<?=$id?>)"  class="btn btn-danger pull-right" style="margin-right: 20px"><i class="fa fa-fw fa-remove"></i> </a> </td>
</tr>
<tr class="trremove<?=$id?>">
  <td align="center"> <b>Rincian Detail : </b>  </td>
    <td colspan="6"><b>Rincian Progress </b>   <a onclick="tambah_rincian(<?=$id?>)" id="btnPenyebab" class="btn btn-info " style="margin-right: 20px"><i class="fa fa-fw fa-plus-square"></i> Tambah  Rincian</a>  <a onclick="hide_folder3(<?=$id?>)"  class="btn btn-warning" style="margin-right: 20px"><i id="nameFolder3<?=$id?>" class="fa fa-fw fa-folder-o"></i></a></div>
                    <br>
                     <br>
                   <div style="background-color: black;height: 2px;margin-left: 10px;margin-right: 10px"></div>
                    <br>
                    <table style="width: 100%;padding: 10px" class="table table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10%"> No </th>
                          <th> Keterangan </th>
                            <th style="width: 10%"> Aksi </th></tr>
                          </thead>
                          <tbody id="detailRincian<?=$id?>"></tbody>
                    </table>
 </td>
  </tr>