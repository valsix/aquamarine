<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("Customer");
$customer = new Customer();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $customer->selectByParams(array("COMPANY_ID" => $reqId));
    $customer->firstRow();

    $reqName = $customer->getField("NAME");
    $reqAddress = $customer->getField("ADDRESS");
    $reqPhone = $customer->getField("PHONE");
    $reqFax = $customer->getField("FAX");
    $reqEmail = $customer->getField("EMAIL");
    $reqCp1Name = $customer->getField("CP1_NAME");
    $reqCp1Telp = $customer->getField("CP1_TELP");
    $reqCp2Name = $customer->getField("CP2_NAME");
    $reqCp2Telp = $customer->getField("CP2_TELP");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/customer">Customer</a> &rsaquo; Form Customer</div>

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
                        <label for="reqName" class="control-label col-md-2">Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqName" class="easyui-validatebox textbox form-control" required name="reqName" value="<?= $reqName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqAddress" class="control-label col-md-2">Address</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqAddress" id="reqAddress" style="width:100%;" required><?= $reqAddress ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqPhone" class="control-label col-md-2">Phone</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="number" id="reqPhone" class="easyui-validatebox textbox form-control" required name="reqPhone" value="<?= $reqPhone ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqFax" class="control-label col-md-2">FAX</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="number" id="reqFax" class="easyui-validatebox textbox form-control" required name="reqFax" value="<?= $reqFax ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqEmail" class="control-label col-md-2">Email</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="email" id="reqEmail" class="easyui-validatebox textbox form-control" required name="reqEmail" value="<?= $reqEmail ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCp1Name" class="control-label col-md-2">CP1 Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCp1Name" class="easyui-validatebox textbox form-control" required name="reqCp1Name" value="<?= $reqCp1Name ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCp1Telp" class="control-label col-md-2">CP1 Telp</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="number" id="reqCp1Telp" class="easyui-validatebox textbox form-control" required name="reqCp1Telp" value="<?= $reqCp1Telp ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCp2Name" class="control-label col-md-2">CP2 Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqCp2Name" class="easyui-validatebox textbox form-control" required name="reqCp2Name" value="<?= $reqCp2Name ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqCp2Telp" class="control-label col-md-2">CP2 Telp</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="number" id="reqCp2Telp" class="easyui-validatebox textbox form-control" required name="reqCp2Telp" value="<?= $reqCp2Telp ?>" style=" width:100%" />
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
                url: 'web/customer_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    $.messager.alertLink('Info', data, 'info', "app/index/customer");
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
</div>
</div>