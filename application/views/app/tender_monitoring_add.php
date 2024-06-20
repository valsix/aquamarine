<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Tender");
$tender = new Tender();

$reqId = $this->input->get("reqId");

if($reqId == ""){
    $reqMode = "insert";
}else{
    $reqMode = "ubah";
    $statement= " AND A.TENDER_ID = ".$reqId;
    $tender->selectByParams(array(), -1,-1, $statement);
    $tender->firstRow();

    $reqCompanyId= $tender->getField("COMPANY_ID");
    $reqCompanyName= $tender->getField("COMPANY_NAME");
    $reqProjectName= $tender->getField("PROJECT_NAME");
    $reqProjectNo= $tender->getField("PROJECT_NO");
    $reqIssuedDate= $tender->getField("ISSUED_DATE");
    $reqRegisterDate= $tender->getField("REGISTER_DATE");
    $reqPQDate= $tender->getField("PQ_DATE");
    $reqPrebidDate= $tender->getField("PREBID_DATE");
    $reqPrebidPath= $tender->getField("PREBID_PATH");
    $reqSubmissionDate= $tender->getField("SUBMISSION_DATE");
    $reqOpening1Date= $tender->getField("OPENING1_DATE");
    $reqOpening2Date= $tender->getField("OPENING2_DATE");
    $reqAnnouncement= $tender->getField("ANNOUNCEMENT");
    $reqLoa= $tender->getField("LOA");
    $reqRemark= $tender->getField("REMARK");
    $reqPersiapanPath= $tender->getField("PERSIAPAN_PATH");
    $reqPelaksanaanPath= $tender->getField("PELAKSANAAN_PATH");
    $reqBaPenyPath= $tender->getField("BA_PENY_PATH");

   
}

$reqTipe ='tender_monitoring_baru';

?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/tender_monitoring"> Tender Monitoring</a> &rsaquo; Form Tender Monitoring
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry Tender Monitoring
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>
                     <div class="form-group">
                        <label for="reqCompanyName" class="control-label col-md-2">Project No</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <!--  <input type="text" id="reqProjectNo" class="easyui-validatebox textbox form-control"  name="reqProjectNo" value="<?= $reqProjectNo ?>" style=" width:100%"  /> -->
                                     <input class="easyui-combobox form-control" style="width:100%" name="reqProjectNo" id="reqProjectNo" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'web/master_tender_json/combo_master',
                       onSelect: function(param){
              rubah_keterangan(param.id);
        }," value="<?= $reqProjectNo ?>"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqCompanyName" class="control-label col-md-2">Project Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea cols="3" rows="2" id="reqProjectName" name="reqProjectName" class="form-control" disabled readonly><?=$reqProjectName?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Company Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <span class="input-group-addon" onclick=""><i class="fa fa-address-book-o fa-lg"></i> </span>
                                        <span class="input-group-addon" onclick="clearCompany()" ><i class="fa fa-times fa-lg"></i> </span>
                                        <input type="text" class="form-control"  id="reqCompanyName" name="reqCompanyName" value="<?= $reqCompanyName ?>" 
                                        style=" width:150%"
                                        disabled readonly >

                                    </div>

                                  
                                    <input type="hidden" class="easyui-validatebox textbox form-control" name="reqCompanyId" id="reqCompanyId" value="<?= $reqCompanyId ?>" style=" width:100%" />

                                </div>
                            </div>
                        </div>
                    </div>

                   
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Issued Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqIssuedDate" id="reqIssuedDate" value="<?=$reqIssuedDate?>" data-options="width:'150'" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="page-header"></div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Register Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqRegisterDate" id="reqRegisterDate" value="<?=$reqRegisterDate?>" data-options="width:'150'" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">PQ Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqPQDate" id="reqPQDate" value="<?=$reqPQDate?>" data-options="width:'150'" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Pre Bid Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqPrebidDate" id="reqPrebidDate" value="<?=$reqPrebidDate?>" data-options="width:'150'" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Pre Bid Document</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <table style="width: 100%" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="80%"> File Name <a onclick="tambahPenyebab()" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>
                                                <th width="10%"> Type </th>
                                                <th width="10%"> Action </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tambahAttacment">
                                            <?
                                            $files_data = explode(';',  $reqPrebidPath);
                                            for ($i = 0; $i < count($files_data); $i++) {
                                                if (!empty($files_data[$i])) {
                                                    $texts = explode('-', $files_data[$i]);
                                                    $ext = substr($files_data[$i], -3);
                                            ?>
                                                    <tr>

                                                        <td>
                                                            <input type="file" onchange="getFileName(this, '<?=($i+1)?>')" name="document[]" multiple class="form-control" style="width: 90%">
                                                            <input type="hidden" name="reqLinkFileTemp[]" value="<?= $files_data[$i] ?>">
                                                            <?if ($ext !=='pdf')
                                                            {
                                                            ?>
                                                              <a href="uploads/prebid/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                            <?
                                                            }
                                                            else
                                                            {
                                                            ?>
                                                              <a onclick="openAdd(`uploads/prebid/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                            <?
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?=strtoupper($ext)?></td>
                                                        <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                                    </tr>
                                            <?
                                                }
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Submision Date</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqSubmisionDate" id="reqSubmisionDate" value="<?=$reqSubmisionDate?>" data-options="width:'150'" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Bid Closing</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqOpening1Date" id="reqOpening1Date" value="<?=$reqOpening1Date?>" data-options="width:'150'" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Opening 2nd Amplop</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-datebox textbox form-control" name="reqOpening2Date" id="reqOpening2Date" value="<?=$reqOpening2Date?>" data-options="width:'150'" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="page-header"></div>

                    <div class="form-group">
                        <label for="reqPhone" class="control-label col-md-2">LOA / Award</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqLoa" class="easyui-validatebox textbox form-control" name="reqLoa" value="<?= $reqLoa ?>" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqPhone" class="control-label col-md-2">Status</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqAnnouncement"  id="reqAnnouncement" data-options="width:'150',editable:false, valueField:'id',textField:'text',url:'combo_json/comboAnnouncement'" value="<?= $reqAnnouncement ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqPhone" class="control-label col-md-2">Remark</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea cols="3" rows="2" id="reqRemark" name="reqRemark" class="form-control"><?=$reqRemark?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                 
                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>

        </div>

    </div>

    <script>
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/tender_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                      show_toast('info', 'Information', 'Header success added <br>' + datas[1]);
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/tender_monitoring_add?reqId=" + datas[0]);
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>

    <script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript">
        function tambahPenyebab(filename='',idField='') {
            var id = $('#tambahAttacment'+idField+' tr').length+1;
            $.get("app/loadUrl/app/tempalate_row_attacment_with_field_nama?id="+id+"&filename="+idField, function(data) {
                $("#tambahAttacment"+idField).append(data);
            });
        }

        function getFileName(input, id) {
            for (var i = 0; i < input.files.length; i++) {
                if(i == 0)
                {
                    $("#namaFile"+id).html(input.files[0].name);
                    var ext = input.files[0].name.split('.').pop();
                    ext = ext.toUpperCase();
                    if(ext.length > 3) ext = '';
                    if(ext == 'PNG' || ext == 'JPG' || ext == 'JPEG' || ext == 'BMP') ext = 'IMAGE'
                    $("#namaFile"+id).parent().next().html(ext);
                }
                else
                    tambahPenyebab(encodeURIComponent(input.files[i].name))
            }
            
        }
        function openCompany() {
            openAdd('app/loadUrl/app/template_load_company_id');
        }
        function clearCompany(){

            $('#reqCompanyName').val('');
            $('#reqCompanyId').val('');
            $('#reqDocumentPerson').val('');
            $('#reqAddress').val('');
            $('#reqEmail').val('');
            $('#reqTelephone').val('');
            $('#reqFaximile').val('');
            $('#reqHp').val('');
        }
        // $('#reqFromDate').datebox('setValue', '6/1/2012');
        $('#reqFromDate').datebox({
            onSelect: function(date){
                // alert(date.getFullYear()+":"+(date.getMonth()+1)+":"+date.getDate());
                ambil_interval();
            }
        });
          $('#reqToDate').datebox({
            onSelect: function(date){
                 ambil_interval();
                // alert(date.getFullYear()+":"+(date.getMonth()+1)+":"+date.getDate());
            }
        });

           function rubah_keterangan(id){
                      $.get("web/tender_json/pilih_detail?reqId=" + id, function(data) {
                        var obj = JSON.parse(data);
                            $("#reqProjectName").val(obj.KETERANGAN);
                            $("#reqCompanyId").val(obj.COMPANY_ID);
                            $("#reqCompanyName").val(obj.COMPANY_NAME);
                    });             
        }

        function ambil_interval(){
         var tgl1 =   $('#reqFromDate').datebox('getValue');
         var tgl2 =   $('#reqToDate').datebox('getValue');

         var selisih =hitungSelisihHari(tgl1,tgl2);
         // console.log(selisih+" Day ");
         $("#reqDuration").val(selisih);

        }
         function company_pilihans(id, name, contact, reqAddress, reqEmail, reqTelephone, reqFaximile, reqHp) {
            $("#reqCompanyName").val(name);
            $("#reqCompanyId").val(id);
            $("#reqDocumentPerson").val(contact);
            $("#reqAddress").val(reqAddress);
            $("#reqEmail").val(reqEmail);
            $("#reqTelephone").val(reqTelephone);
            $("#reqFaximile").val(reqFaximile);
            $("#reqHp").val(reqHp);

        }

    </script>
</div>
</div>