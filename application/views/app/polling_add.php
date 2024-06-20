<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("Polling");
$polling = new Polling();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $polling->selectByParamsEntri(array("A.POLLING_ID" => $reqId));

    $polling->firstRow();
    $reqId                        = $polling->getField("POLLING_ID");
    $reqNama                    = $polling->getField("NAMA");
    $reqKeterangan              = $polling->getField("KETERANGAN");
    $reqTanggalAwal              = $polling->getField("TANGGAL_AWAL");
    $reqTanggalAkhir              = $polling->getField("TANGGAL_AKHIR");
}
?>

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/polling">Polling</a> &rsaquo; Kelola Polling</div>

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
                        <label for="reqNama" class="control-label col-md-2">Pertanyaan</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqNama" class="easyui-validatebox textbox form-control" required name="reqNama" value="<?= $reqNama ?>" data-options="required:true" style="width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">Tanggal Awal</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqTanggalAwal" class="easyui-datetimebox textbox form-control" required name="reqTanggalAwal" value="<?= $reqTanggalAwal ?>" data-options="required:true" style="width:180px" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">Tanggal Berakhir</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqTanggalAkhir" class="easyui-datetimebox textbox form-control" required name="reqTanggalAkhir" value="<?= $reqTanggalAkhir ?>" data-options="required:true" style="width:180px" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="reqKeterangan" class="control-label col-md-2">Keterangan</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqKeterangan" style="width:100%; height:200px"><?= $reqKeterangan ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>JAWABAN
                        </h3>
                    </div>

                    <div class="form-group">
                        <div class='col-md-12'>
                            <div class='form-group'>
                                <div class='col-md-12'>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="width:95%">KETERANGAN<a onClick="tambahJawaban()"><i class="fa fa-plus-circle fa-lg"></i></a></th>
                                                <th>AKSI</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyJawaban">
                                            <?
                                            $this->load->model("PollingDetil");
                                            $polling_detil = new PollingDetil();

                                            $polling_detil->selectByParams(array("A.POLLING_ID" => (int) $reqId));
                                            $id = rand();

                                            while ($polling_detil->nextRow()) {
                                                $id = rand();
                                                $reqJawaban = $polling_detil->getField("NAMA");
                                            ?>
                                                <tr>
                                                    <td>
                                                        <input class="easyui-validatebox textbox form-control" type="text" name="reqJawaban[]" id="reqJawaban<?= $id ?>" value="<?= $reqJawaban ?>" data-options="required:true" style="width:100%">
                                                    </td>

                                                    <td style="text-align:center">
                                                        <input type="hidden" name="reqPollingDetilId[]" value="<?= $polling_detil->getField("POLLING_DETIL_ID") ?>">
                                                        <a onclick="deleteData('web/polling_json/delete_jawaban/', '<?= $polling_detil->getField("POLLING_DETIL_ID") ?>')"><i class="fa fa-trash fa-lg"></i></a>
                                                    </td>
                                                </tr>
                                            <? } ?>
                                        </tbody>
                                    </table>
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
                url: 'web/polling_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    $.messager.alertLink('Info', data, 'info', "app/index/polling");
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }


        function tambahJawaban() {
            $.get("app/loadUrl/app/polling_add_template", function(data) {
                $("#tbodyJawaban").append(data);
            });
        }
    </script>

</div>

</div>