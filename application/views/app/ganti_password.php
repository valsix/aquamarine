<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Users_management");
$usersManagement = new Users_management();

$reqId = $this->USERID;

$usersManagement->selectByParams(array("USERID" => $reqId));
$usersManagement->firstRow();

$reqUsername = $usersManagement->getField("USERNAME");
$reqFullName = $usersManagement->getField("FULLNAME");
$reqUserPass = $usersManagement->getField("USERPASS");
$reqLevel = $usersManagement->getField("LEVEL");
$reqMenuMarketing = $usersManagement->getField("MENUMARKETING");
$reqMenuFinance = $usersManagement->getField("MENUFINANCE");
$reqMenuProduction = $usersManagement->getField("MENUPRODUCTION");
$reqMenuDocument = $usersManagement->getField("MENUDOCUMENT");
$reqMenuSearch = $usersManagement->getField("MENUSEARCH");
$reqMenuOthers = $usersManagement->getField("MENUOTHERS");
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> Ganti Password
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
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
                        <label for="reqUsername" class="control-label col-md-2"> Username</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqUsername" class="easyui-validatebox textbox form-control" required="true" name="reqUsername" value="<?= $reqUsername ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqFullName" class="control-label col-md-2"> Fullname</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqFullName" class="easyui-validatebox textbox form-control" required="true" name="reqFullName" value="<?= $reqFullName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqUserPass" class="control-label col-md-2"> Password</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="password" id="reqUserPass" class="easyui-validatebox textbox form-control" <?=($reqId == "" ? 'required="true"' : '')?> name="reqUserPass" value="" style=" width:100%" <?=($reqId == "" ? "" : 'placeholder="Kosongkan jika tidak ingin mengubah password"')?>/>
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
                url: 'web/users_management_json/ganti_password',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/ganti_password");
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }

        function bindCheck() {
            $('#MENUPRODUCTION').change(function() {
                // if(!this.checked){
                //     $('#MENUEPL').prop("checked", this.checked);
                //     $('#MENUUWILD').prop("checked", this.checked);
                //     $('#MENUWP').prop("checked", this.checked);
                //     $('#MENUPL').prop("checked", this.checked);
                //     $('#MENUEL').prop("checked", this.checked);
                //     $('#MENUPMS').prop("checked", this.checked);
                //     $('#MENURS').prop("checked", this.checked);
                //     $('#MENUSTD').prop("checked", this.checked);
                // }
            });
        }

        
    </script>
</div>
</div>