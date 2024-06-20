<?
$reqUrutNo = $this->input->get("id");
$id =rand();
?>
 <tr>
  <td>
   
    <div style="background:blue;height: 5px;margin: 10px" class="parentSolusi"></div>
 <div class="form-group">
                        <label for="reqCertificate" class="control-label col-md-1">Urut</label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-11">
                                  <input type="hidden" class="easyui-validatebox textbox form-control"  name="reqParams[]" value="<?=$id?>">
                                  <input type="hidden"  class="easyui-validatebox textbox form-control" id="reqWeeklyProsesDetailId<?=$id?>" name="reqWeeklyProsesDetailId[]" value="">
                                     <input type="text" onkeypress='validate(event)' id="reqUrutSolusi" class="easyui-validatebox textbox form-control" name="reqUrutSolusi[]" value="<?=$reqUrutNo?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                         <label for="reqDescription" class="control-label col-md-1">Solusi</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-9">
                                  <input type="text"  class="easyui-validatebox textbox form-control" name="reqMasterSolusiId[]" value="" style=" width:80%" />


                                     
                                </div>
                                <div class="col-md-1">
                                     <a onclick="tambahDetail(<?=$id?>)" id="btnPenyebab" class="btn btn-info pull-right" style="margin-right: 20px"><i class="fa fa-fw fa-plus-square"></i> Detail Solusi</a>
                                    
                                    
                                </div>
                                <div class="col-md-1">
                                       <input type="hidden" id="reqValueFolder<?=$id?>" value="0" />
                                      <a onclick="hide_folder2(<?=$id?>)" id="btnPenyebab" class="btn btn-warning pull-right" style="margin-right: 20px"><i id="nameFolder2<?=$id?>" class="fa fa-fw fa-folder-o"></i></a>
                                      
                                </div>
                                  <div class="col-md-1">
                                    <a onclick="hapus_folder1(this,<?=$id?>)"  class="btn btn-danger pull-right" style="margin-right: 20px"><i  class="fa fa-fw fa-remove"></i></a>
                                  </div>
                            </div>
                        </div>
                    </div> 
                    <div style="background-color: black;height: 2px;margin-left: 10px;margin-right: 10px"></div>
                    <table style="width: 100%" >
                      <tbody id="detail<?=$id?>"></tbody>
                    </table>
                  </td>
                </tr>