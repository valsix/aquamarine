<?
$id = rand();
$reqUrutNo = $this->input->get("id");
$reqParent = $this->input->get("reqParent");

?>
<tr>
  <td> 
     <script type="text/javascript">
     $("#reqStatusProgres<?=$id?>").combobox();
       $("#reqDueDate<?=$id?>").datebox();
    

    </script>
    <div style="background:green;height: 8px;margin: 10px" class="solusi<?=$reqParent?>" > </div>
    <div class="row" style="padding: 20px">
                    <div class="col-md-4">
                       <div class="form-group">
                        <label for="reqCertificate" class="col-md-4">Progres</label>
                        <div class="col-md-">
                            <div class="form-group">
                                <div class="col-md-11">
                                  
                                      <input type="text" id="reqProses" class="easyui-validatebox textbox form-control" name="reqProses<?=$reqParent?>[]" value="" style=" width:90%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCertificate" class="col-md-4">Due Date</label>
                        <div class="col-md-">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <input type="text" id="reqDueDate<?=$id?>" class="easyui-datebox textbox form-control datest" name="reqDueDate<?=$reqParent?>[]" value="<?= $reqName ?>" style="width: 180px" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCertificate" class="col-md-4">PIC Person</label>
                        <div class="col-md-">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <input type="text" id="reqPicPerson<?=$id?>" class="easyui-validatebox textbox form-control datest" name="reqPicPerson<?=$reqParent?>[]" value="" style="width: 180px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                     <div class="col-md-4">
                       <div class="form-group">
                        <label for="reqCertificate" class="col-md-4">No</label>
                        <div class="col-md-">
                            <div class="form-group">
                                <div class="col-md-11">
                                  <input type="hidden" class="easyui-validatebox textbox form-control" id="reqParamInline<?=$id?>" name="reqParamInline<?=$reqParent?>[]" value='<?=$id?>'>
                                      <input type="hidden"  class="easyui-validatebox textbox form-control" id="reqWeeklyProgresInlineId<?=$id?>" name="reqWeeklyProgresInlineId<?=$reqParent?>[]" value=''>
                                      <input type="text" onkeypress='validate(event)' id="reqUrutInline<?=$id?>" class="easyui-validatebox textbox form-control" name="reqUrutInline<?=$reqParent?>[]" value="<?=$reqUrutNo?>" style=" width:20%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCertificate" class="col-md-4">Status</label>
                        <div class="col-md-">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <input id="reqStatusProgres<?=$id?>" class="easyui-combobox form-control comboboxs"  style="width:100%" name="reqStatusProgres<?=$reqParent?>[]" data-options="width:'230',editable:false, valueField:'id',textField:'text',url:'combo_json/comboStatusWeekly'" value="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                     <div class="col-md-4">
                        <div class="pull-right">
                            <a onclick="hapus_folder2(this,<?=$id?>)"  class="btn btn-danger pull-right" style="margin-right: 20px"><i class="fa fa-fw fa-remove"></i> Hapus Progres</a>
                        </div>
                         <div style="background: white;height: auto;color: black;height: 180px;width: 180px;border: 1px solid black;padding: 20px">
                                    <img id="imgLogo<?=$id?>" src="images/icon_arsip.jpg" style="height: 100%;width: 100%">

                                </div>
                                <div class="input-group">
                                        <span class="input-group-addon" onclick="openFile(<?=$id?>)"><i class="fa fa-upload fa-lg"></i> </span>
                                       
                                        <input type="text" class="form-control"  id="reqNamaFile<?=$id?>"  value="" 
                                        style=" width:80%"
                                        >
                                      </div>
                                <input type="file" style="display: none;" id="reqFilesName<?=$id?>" name="reqFilesName<?=$reqParent?>[]" class="form-control" style="width: 60%" >
                                <input type="hidden" class="easyui-validatebox textbox form-control" name="reqFilesNames<?=$reqParent?>[]" value="">
                                <input type="hidden" class="easyui-validatebox textbox form-control" id="reqValueFolder2<?=$id?>" value="0" />


                    </div>
                    </div>

                    
                    <div class="col-md-8"> <b>Rincian Progress </b>   <a onclick="tambah_rincian(<?=$id?>)" id="btnPenyebab" class="btn btn-info " style="margin-right: 20px"><i class="fa fa-fw fa-plus-square"></i> Tambah  Rincian</a>  <a onclick="hide_folder3(<?=$id?>)"  class="btn btn-warning" style="margin-right: 20px"><i id="nameFolder3<?=$id?>" class="fa fa-fw fa-folder-o"></i></a></div>
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