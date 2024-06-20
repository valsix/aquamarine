<?

$this->load->model("VendorCode");
$this->load->model("Company");
$company = new Company();
$reqId = $this->input->get('id');
$ID = $ID?$ID:$reqId;
$company->selectByParamsMonitoring(array("A.COMPANY_ID::VARCHAR" => $ID));
$company->firstRow();

$reqCompanyId          = $company->getField("COMPANY_ID");
$reqName               = $company->getField("NAME");
$reqAddress            = $company->getField("ADDRESS");
$reqPhone              = $company->getField("PHONE");
$reqFax                = $company->getField("FAX");
$reqEmail              = $company->getField("EMAIL");
$reqCp1Name            = $company->getField("CP1_NAME");
$reqCp1Telp            = $company->getField("CP1_TELP");
$reqCp2Name            = $company->getField("CP2_NAME");
$reqCp2Telp            = $company->getField("CP2_TELP");
$reqLa1Name            = $company->getField("LA1_NAME");
$reqLa1Address         = $company->getField("LA1_ADDRESS");
$reqLa1Phone           = $company->getField("LA1_PHONE");
$reqLa1Fax             = $company->getField("LA1_FAX");
$reqLa1Email           = $company->getField("LA1_EMAIL");
$reqLa1Cp1             = $company->getField("LA1_CP1");
$reqLa1Cp2             = $company->getField("LA1_CP2");
$reqLa2Name            = $company->getField("LA2_NAME");
$reqLa2Address         = $company->getField("LA2_ADDRESS");
$reqLa2Telp            = $company->getField("LA2_TELP");
$reqLa2Fax             = $company->getField("LA2_FAX");
$reqLa2Email           = $company->getField("LA2_EMAIL");
$reqLa2Cp1             = $company->getField("LA2_CP1");
$reqLa2Cp2             = $company->getField("LA2_CP2");
$reqLa1Cp1Phone        = $company->getField("LA1_CP1_PHONE");
$reqLa1Cp2Phone        = $company->getField("LA1_CP2_PHONE");
$reqLa2Cp1Phone        = $company->getField("LA2_CP1_PHONE");
$reqLa2Cp2Phone        = $company->getField("LA2_CP2_PHONE");
$reqTipe               = $company->getField("TIPE");
$reqProvinsi               = $company->getField("PROPINSI_ID");
$combo_kabupaten               = $company->getField("KABUPATEN_ID");

   $vendorcode = new VendorCode();
     $vendorcode->selectByParamsMonitoring(array('A.SUPPLIER_ID::VARCHAR'=>$ID,'A.STATUS_AKTIF'=>'1'));
     $arrDataVendor = $vendorcode->rowResult;
     $arrDataVendor =   $arrDataVendor[0];
     $reqKodeVendor = $arrDataVendor['kode'];
     $reqType = $arrDataVendor['type'];
      $reqLoc = $arrDataVendor['area'];
?>
<div id='companyForm'>
 <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Company of Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <?
                                        if(empty($DARI)){
                                        ?>
                                    <div class="input-group">
                                       
                                        <span class="input-group-addon" onclick="openCompany()"><i class="fa fa-address-book-o fa-lg"></i> </span>
                                        <span class="input-group-addon" onclick="clearCompany()" ><i class="fa fa-times fa-lg"></i> </span>
                                       

                                        <input type="text" class="form-control"  id="reqCompanyName" name="reqCompanyName" value="<?= $reqName ?>" 
                                        style=" width:150%"  
                                        >

                                    </div>

                                    <?
                                }else{
                                ?>
                                  <input type="text" class="form-control"  id="reqCompanyName" name="reqCompanyName" value="<?= $reqName ?>" 
                                        style=" width:150%"  
                                        >
                                <?    
                                }
                                ?>
                                    <input type="hidden" class="easyui-validatebox textbox form-control" name="reqCompanyId" id="reqCompanyId" value="<?= $reqCompanyId ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                         <label for="reqPhone" class="control-label col-md-2">Telp</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPhone" class="easyui-validatebox textbox form-control" name="reqPhone" value="<?= $reqPhone ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                       
                    </div>
                   

                    <div class="form-group">
                        <label for="reqAddress" class="control-label col-md-2">Address</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqAddress" id="reqAddress" class="form-control " style="width:100%;"><?= $reqAddress ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                      <div class="form-group">
                    <label for="reqFax" class="control-label col-md-2">Fax</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqFax" class="easyui-validatebox textbox form-control" name="reqFax" value="<?= $reqFax ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>

                        <label for="reqEmail" class="control-label col-md-2">Email</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqEmail" class="easyui-validatebox textbox form-control" name="reqEmail" value="<?= $reqEmail ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCp1Name" class="control-label col-md-2">Contact Person I</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <input type="text" class="easyui-validatebox textbox form-control" id="reqDocumentPerson" name="reqDocumentPerson" value="<?= $reqCp1Name ?>" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp1Telp" class="control-label col-md-2">Mobile Phone 1</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCp1Telp" class="easyui-validatebox textbox form-control" name="reqCp1Telp" value="<?= $reqCp1Telp ?>" onkeypress='validate(event)' style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCp2Name" class="control-label col-md-2">Contact Person II</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCp2Name" class="easyui-validatebox textbox form-control" name="reqCp2Name" value="<?= $reqCp2Name ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp2Telp" class="control-label col-md-2">Mobile Phone 2</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" onkeypress='validate(event)' id="reqCp2Telp" class="easyui-validatebox textbox form-control" name="reqCp2Telp" value="<?= $reqCp2Telp ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCp2Name" class="control-label col-md-2">Provinsi</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqProvinsi" id="reqProvinsi" data-options="width:'350',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_provinsi',onSelect: function(rec){

                                                  gantiKota(rec.id);
                                           }" value="<?= $reqProvinsi ?>" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp2Telp" class="control-label col-md-2">Kota</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input class="easyui-combobox form-control" style="width:100%" name="combo_kabupaten" id="combo_kabupaten" data-options="width:'350',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_kabupaten?reqId=<?= $reqProvinsi ?>'" value="<?= $combo_kabupaten ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        
                        <label for="reqCp2Telp" class="control-label col-md-2">Type</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:40%" id='reqType'   name="reqType" data-options="width:'190',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_type_sub'" value="<?=$reqType?>" />
                                </div>
                            </div>
                        </div>
                        <label for="reqCp2Telp" class="control-label col-md-2">Location Code</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                        <input class="easyui-combobox form-control" style="width:40%" id='reqLoc'  name="reqLoc" data-options="width:'290',editable:false, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_type_location'" value="<?=$reqLoc?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCp2Telp" class="control-label col-md-2">Vendor Code</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                 <input type="text"  disabled readonly class="easyui-validatebox textbox form-control" id='vendorcode' value="<?=$reqKodeVendor?>"  style=" width:60%" disabled readonly />
                                
                             </div>
                         </div>
                     </div>
                 </div>

                 <script type="text/javascript">
                  function openCompany() {
                    openAdd('app/loadUrl/app/template_load_company_id_new');

                }
                function clearCompany(){

                    $('#reqCompanyName').val('');
                    $('#reqCompanyId').val('');

                }
                function company_pilihans(id, name, contact, reqAddress, reqEmail, reqTelephone, reqFaximile, reqHp) {
                    $('#reqCompanyName').val(name);
                    $('#reqCompanyId').val(id);
                    $.get("app/loadUrl/app/company_form?id="+id, function(data) {
                         $('#companyForm').empty();
                        $('#companyForm').append(data);
                        $('#reqLoc').combobox();
                    });
                }
              </script>
              </div>