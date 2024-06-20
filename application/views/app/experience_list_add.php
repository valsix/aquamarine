<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("ExperienceList");
$this->load->model("Company");
$experience_list = new ExperienceList();

$reqId = $this->input->get("reqId");

if($reqId == ""){
    $reqMode = "insert";
    $reqUrut = $this->db->query("SELECT COALESCE(MAX(URUT), 0) + 1 nilai FROM EXPERIENCE_LIST")->row()->nilai;
}else{
    $reqMode = "ubah";
    $statement= " AND A.EXPERIENCE_LIST_ID = ".$reqId;
    $experience_list->selectByParamsMonitoring(array(), -1,-1, $statement);

    $experience_list->firstRow();
    $reqId= $experience_list->getField("EXPERIENCE_LIST_ID");
    $reqUrut= $experience_list->getField("URUT");
    $reqProjectName= $experience_list->getField("PROJECT_NAME");
    $reqProjectLocation= $experience_list->getField("PROJECT_LOCATION");
    $reqCostumerId= $experience_list->getField("COSTUMER_ID");
    $reqContactNo= $experience_list->getField("CONTACT_NO");
    $reqFromDate= dateToPageCheck3($experience_list->getField("FROM_DATE"));
    $reqToDate= dateToPageCheck3($experience_list->getField("TO_DATE"));
    $reqDuration= $experience_list->getField("DURATION");
    $reqPath= $experience_list->getField("PATH");

    if(!empty($reqCostumerId)){
        $company = new Company();
        $company->selectByParamsMonitoring(array("A.COMPANY_ID"=>$reqCostumerId));
        $company->firstRow();
        $reqCompanyName = $company->getField("NAME");
        $reqDocumentPerson  = $company->getField("CP1_NAME");
        $reqAddress = $company->getField("ADDRESS");
        $reqEmail = $company->getField("EMAIL");
        $reqTelephone = $company->getField("PHONE");
        $reqFaximile = $company->getField("FAX");
        $reqHp = $company->getField("CP1_TELP");
    }

}

    // echo $reqToDate;
    // $tgl_skrng = date('m/d/Y');
    // $startDate = time();
    // $startDate1Day =date('m/d/Y', strtotime('+1 day', $startDate));
    // if(empty($reqFromDate)){$reqFromDate=$tgl_skrng;} 
    // if(empty($reqToDate)){$reqToDate=$startDate1Day;}
    // if(empty($reqDuration)){$reqDuration='1';} 

// echo $startDate1Day;


?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/experience_list"> Experience List</a> &rsaquo; Form Experience List
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry Experience List
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqCompanyName" class="control-label col-md-2">Nomor Urut</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" style="padding: 6px 12px;" class="easyui-numberbox form-control" name="reqUrut" id="reqUrut" value="<?=($reqUrut)?>" data-options="min:0,precision:0, height: 30, width: 200, required: true" >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCompanyName" class="control-label col-md-2">Project Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea cols="3" rows="2" id="reqProjectName" name="reqProjectName" class="form-control"><?=$reqProjectName?></textarea>
                                </div>
                            </div>
                        </div>
                        <label for="reqCompanyName" class="control-label col-md-2">Project Location</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <textarea cols="3" rows="2" id="reqProjectLocation" name="reqProjectLocation" class="form-control"><?=$reqProjectLocation?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                   <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Client Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <input type="text" class="easyui-validatebox textbox form-control" name="reqCompanyName" id="reqCompanyName" value="<?= $reqCompanyName ?>" style=" width:100%" onclick="openCompany()" />
                                        <input type="hidden" id="reqCompanyId" name="reqCompanyId" value="<?=$reqCostumerId?>" required>
                                        <span class="input-group-addon" onclick="openCompany()"><i>...</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Contact</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" id="reqDocumentPerson" name="reqDocumentPerson" value="<?= $reqDocumentPerson ?>" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="page-header">

                    </div>

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Adress</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control tinyMCES" name="reqAddress" id="reqAddress" style="width:100%;" ><?=$reqAddress?></textarea>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                      <div class="form-group">
                    <label for="reqPhone" class="control-label col-md-2">Email</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqEmail" class="easyui-validatebox textbox form-control" name="reqEmail" value="<?= $reqEmail ?>" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Telephone</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqTelephone" onkeypress='validate(event)' class="easyui-validatebox textbox form-control"  name="reqTelephone" value="<?= $reqTelephone ?>" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Fax.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" onkeypress='validate(event)' class="easyui-validatebox textbox form-control" name="reqFaximile" id="reqFaximile" value="<?= $reqFaximile ?>" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                        <label for="reqPhone" class="control-label col-md-2">Handphone</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqHp" onkeypress='validate(event)' class="easyui-validatebox textbox form-control" name="reqHp" value="<?= $reqHp ?>" style=" width:100%"  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">

                    </div>
                   

                    <div class="form-group">
                        <label for="reqPath" class="control-label col-md-2">Date Project</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">


                                    <input type="text" class="easyui-datebox textbox form-control" name="reqFromDate" id="reqFromDate" value="<?=$reqFromDate?>" style=" width:100%"  /> To  <input type="text" class="easyui-datebox textbox form-control" name="reqToDate" id="reqToDate"   value="<?=$reqToDate?>" style=" width:100%" />
                                    <input type="text" id="reqDuration" style="width: 10%"class="easyui-validatebox textbox form-control" name="reqDuration" value="<?= $reqDuration ?>" readonly   /> / Day
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Contract No.</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqContactNo" style="width: 100%"class="easyui-validatebox textbox form-control" name="reqContactNo" value="<?= $reqContactNo ?>"  />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">

                    </div>
                    <div style="padding: 10px">
                        <table style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="90%"> File Name :  <a onclick="tambahPenyebab()" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>

                                    <th width="10%"> Action </th>
                                </tr>
                            </thead>
                            <tbody id="tambahAttacment">
                                <?
                                $files_data = explode(';',  $reqPath);
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
                                                  <a href="uploads/experience_list/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                  <a onclick="openAdd(`uploads/experience_list/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                <?
                                                }
                                                ?>
                                            </td>

                                            <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                        </tr>
                                <?
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
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
                url: 'web/experience_list_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                      show_toast('info', 'Information', 'Header success added <br>' + datas[1]);
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/experience_list_add?reqId=" + datas[0]);
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>

    <script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript">
        function tambahPenyebab(filename='') {
            var id = $('#tambahAttacment tr').length+1;
            $.get("app/loadUrl/app/tempalate_row_attacment?id="+id+"&filename="+filename, function(data) {
                $("#tambahAttacment").append(data);
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
            $(tinymce.get('reqAddress').getBody()).html(reqFaximile);
            // $("#reqAddress").val(reqAddress);
            $("#reqEmail").val(reqAddress);
            $("#reqTelephone").val(reqHp);
            $("#reqFaximile").val(reqTelephone);
            $("#reqHp").val(reqEmail);

        }

    </script>
</div>
</div>