<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Employment_contracts");
$employmentContracts = new Employment_contracts();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $employmentContracts->selectByParams(array("DOCUMENT_ID" => $reqId));
    $employmentContracts->firstRow();

    $reqType = $employmentContracts->getField("TYPE");
    $reqName = $employmentContracts->getField("NAME");
    $reqDescription = $employmentContracts->getField("DESCRIPTION");
    $reqPath = $employmentContracts->getField("PATH");
    $reqLastRevisi = $employmentContracts->getField("LAST_REVISI");
    $reqTahun = $employmentContracts->getField("TAHUN");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/employment_contracts"> Contracts</a> &rsaquo; Form Contracts
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="master_type_contract()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Master Type Contract</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Data
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>


                  

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2"> Vendor Code</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqName" class="easyui-validatebox textbox form-control" required="true" name="reqName" value="<?= $reqName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                   <table class="table table-striped table-hover dt-responsive">
                    <thead>
                       <tr> 
                            <th style="width: 23%"> Vendor Type <br>
                                 <input type="text" id="reqName" class="easyui-validatebox textbox form-control" placeholder="Enter Code" name="reqName" value="" style=" width:35%" />
                             </th>
                              <th style="width: 23%">Supplier </th>
                                <th style="width: 23%">Service </th>
                               <th style="width: 23%">Both Supplay </th>
                               <th style="width: 8%">Aksi </th>
                       </tr>
                       </thead>
                   </table>
                   <table class="table table-striped table-hover dt-responsive">
                    <thead>
                       <tr> 
                            <th style="width: 100%"> Register
                                 <input type="text" id="reqName" class="easyui-validatebox textbox form-control" placeholder="Enter Code" name="reqName" value="" style=" width:35%" />
                             </th>
                             
                       </tr>
                       </thead>
                   </table>

                    

                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqTipe" value="Contracts" />
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

                var win = $.messager.progress({
            title: 'Office Management  | PT Aquamarine Divindo',
            msg: 'proses data...'
        });

            $('#ff').form('submit', {
                url: 'web/employment_contracts_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    // console.log(data);return false;
                    var datas = data.split('-');
                      if(datas[0]=='xxx'){
                             show_toast('error', 'Have troube in ',  datas[1]);
                            $.messager.alert('Info', datas[1], 'info'); 
                    }else{
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/employment_contracts_add?reqId=" + datas[0]);
                    }
                      $.messager.progress('close');
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>

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
    </script>
</div>
</div>