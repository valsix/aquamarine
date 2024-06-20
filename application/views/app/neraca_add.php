<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Neraca");
$neraca = new Neraca();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $neraca->selectByParams(array("A.NERACA_ID" => $reqId));

    $neraca->firstRow();
    $reqId = $neraca->getField("NERACA_ID");
    $reqBulan = $neraca->getField("BULAN");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css">

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/neraca">Neraca</a> &rsaquo; Kelola Neraca</div>

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
                        <label for="reqBulan" class="control-label col-md-2">Bulan</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="date" id="reqBulan" class="easyui-validatebox textbox form-control" required name="reqBulan" value="<?= $reqBulan ?>" data-options="required:true" style="width:100%" />
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
                url: 'web/neraca_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    $.messager.alertLink('Info', data, 'info', "app/index/neraca");
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>

</div>

</div>