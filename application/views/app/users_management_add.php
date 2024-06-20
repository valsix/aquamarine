<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Users_management");
$usersManagement = new Users_management();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
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
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/users_management"> Users Management</a> &rsaquo; Form Users Management
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

                    <div class="form-group">
                        <label for="reqLevel" class="control-label col-md-2"> Level</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <select name="reqLevel" id="reqLevel" class="easyui-validatebox form-control" style="width: 100%;" required="true">
                                        <option value="">-- Silahkan Pilih --</option>
                                        <option value="0" <?= $reqLevel = '0' ? 'selected' : ''; ?>>Administrator</option>
                                        <option value="1" <?= $reqLevel = '1' ? 'selected' : ''; ?>>Operator</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry User </h3>
                    </div>
                    <div class="form-group">
                         <div class="col-md-12" style="padding: 10px">
                            <table id="tt" class="easyui-treegrid" style="width:100% !important;height:400px"
                                data-options="url:'web/tree_json/hak_akses?reqId=<?=$reqId?>',width:'100%',
                                idField:'ID',treeField:'NAME',
                                rownumbers: true,
                                lines: true,
                                pagination:false,
                                remoteFilter:false,
                                animate: true,        
                                collapsible: false,
                                iconCls: 'icon-ok',
                                fitColumns: true,
                                onLoadSuccess: function(row,param){
                                    bindCheck()
                                },
                                onBeforeLoad: function(row,param){

                                    if (!row) { 
                                    // load top level rows
                                    param.id = 0; // set id=0, indicate to load new page rows                            
                                }
                            }
                            ">
                                <thead>
                                    <tr>
                                        <th data-options="field:'NAME',width:280">Menu</th>
                                        <th data-options="field:'CHECK',width:100">Akses</th>

                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <?php 
                    /*
                    <div class="col-md-12" style="padding: 20px;text-align: left;font-weight: bold;">
                        
                        <table style="width: 100%;color: white" class="table">


                            <tr>
                                <td style="width: 10px"><input type="checkbox" name="reqMenuMarketing" <?= $reqMenuMarketing == '1' ? 'checked' : ''; ?>> </td>
                                <td> Marketing </td>
                                <td style="width: 10px"><input type="checkbox" name="reqMenuProduction" <?= $reqMenuProduction == '1' ? 'checked' : ''; ?>> </td>
                                <td> Production </td>
                                <td style="width: 10px"><input type="checkbox" name="reqMenuSearch" <?= $reqMenuSearch == '1' ? 'checked' : ''; ?>> </td>
                                <td> Research & Develop </td>
                            </tr>
                            <tr>
                                <td style="width: 10px"><input type="checkbox" name="reqMenuDocument" <?= $reqMenuDocument == '1' ? 'checked' : ''; ?>> </td>
                                <td> Document </td>
                                <td style="width: 10px"><input type="checkbox" name="reqMenuOthers" <?= $reqMenuOthers == '1' ? 'checked' : ''; ?>> </td>
                                <td> Other </td>
                                <td style="width: 10px"><input type="checkbox" name="reqMenuFinance" <?= $reqMenuFinance == '1' ? 'checked' : ''; ?>> </td>
                                <td> Finance </td>
                            </tr>
                        </table>
                    </div>
                    */
                    ?>


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
                url: 'web/users_management_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/users_management_add?reqId="+datas[0]);
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