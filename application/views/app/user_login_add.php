<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("UserLogin");
$user_login = new UserLogin();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $user_login->selectByParams(array("A.USER_LOGIN_ID" => $reqId));

    $user_login->firstRow();
    $reqId = $user_login->getField("USER_LOGIN_ID");
    $reqNama = $user_login->getField("NAMA");
    $reqPegawaiId = $user_login->getField("PEGAWAI_ID");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css">

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/user_login">User Login</a> &rsaquo; Kelola User Login</div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>

                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Data</h3>
                    </div>


                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">NRP</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <table>
                                        <tr>
                                            <td>
                                                <input type="text" id="reqPegawaiId" class="easyui-validatebox textbox form-control" required name="reqPegawaiId" value="<?= $reqPegawaiId ?>" readonly data-options="required:true" style="width:100%" />
                                            </td>
                                            <td>
                                                &nbsp;&nbsp;
                                            </td>
                                            <td>
                                                <a id="btnAdd" onClick="openAdd('app/loadUrl/app/pegawai_lookup')"><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i> </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqKeterangan" class="control-label col-md-2">Nama</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama" value="<?= $reqNama ?>" readonly data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()">Clear</a>
            </div>

        </div>
    </div>

    <script>
        function submitForm() {

            $('#ff').form('submit', {
                url: 'web/user_login_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    $.messager.alertLink('Info', data, 'info', "app/index/user_login");
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }

        function tambahPegawai(id, nama) {
            $("#reqPegawaiId").val(id);
            $("#reqNama").val(nama);
        }
    </script>

</div>

</div>