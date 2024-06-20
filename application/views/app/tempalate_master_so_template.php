<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("SoTemplate");
$so_template = new SoTemplate();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $statement = " AND A.SO_TEMPLATE_ID = " . $reqId;
    $so_template->selectByParamsMonitoring(array(), -1, -1, $statement);

    $so_template->firstRow();
    $reqId = $so_template->getField("SO_TEMPLATE_ID");
    $reqName = $so_template->getField("NAMA");
    $reqDescription = $so_template->getField("KETERANGAN");
}


?>
<base href="<?= base_url(); ?>" />
<link href="libraries/bootstrap-3.3.7/docs/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="libraries/bootstrap-3.3.7/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="libraries/bootstrap-3.3.7/docs/examples/sticky-footer-navbar/sticky-footer-navbar.css" rel="stylesheet">

<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="libraries/bootstrap-3.3.7/docs/assets/js/ie-emulation-modes-warning.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<link rel="stylesheet" href="css/halaman.css" type="text/css">
<link rel="stylesheet" href="css/gaya-egateway.css" type="text/css">
<link rel="stylesheet" href="css/gaya-affix.css" type="text/css">
<link rel="stylesheet" href="css/gaya-pagination.css" type="text/css">
<link rel="stylesheet" href="css/gaya-datatable-egateway.css" type="text/css">
<link rel="stylesheet" href="libraries/font-awesome-4.7.0/css/font-awesome.css">

<script type='text/javascript' src="libraries/bootstrap/js/jquery-1.12.4.min.js"></script>


<!-- DATATABLE -->
<link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/extensions/Responsive/css/dataTables.responsive.css">
<link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/examples/resources/syntax/shCore.css">
<link rel="stylesheet" type="text/css" href="libraries/DataTables-1.10.7/examples/resources/demo.css">

<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/media/js/fnReloadAjax.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
<script type="text/javascript" language="javascript" src="libraries/DataTables-1.10.7/examples/resources/demo.js"></script>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />
<!-- EASYUI 1.4.5 -->
<link rel="stylesheet" type="text/css" href="libraries/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="libraries/easyui/themes/icon.css">
<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="libraries/easyui/globalfunction.js"></script>
<script type="text/javascript" src="libraries/easyui/kalender-easyui.js"></script>

<script src="libraries/tinyMCE/tinymce.min.js"></script>

<script type="text/javascript">
    tinymce.init({
        selector: ".tinyMCES",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        menubar: true,

    });
</script>


<?php
// Header Nama TABEL TH
?>

<style type="text/css">
    #tablei {
        background-color: white;
    }

    #tablei tr td {
        color: black;
        font-weight: bold;
        padding: 5px;
        border-bottom: 1px solid black;
    }
</style>
<body>
<div class="col-md-12">

    <div class="judul-halaman">Master Template Equipment Project </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">



                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Master Template Equipment List </h3>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2"> Name Tamplate</label>
                        <div class="col-md-5">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqName" id="reqName" value="<?= $reqName ?>" style=" width:100%" required />

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqPhone" class="control-label col-md-2">Description</label>
                        <div class="col-md-5">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control" name="reqDescription" cols="4" rows="3" style="width:100%;"><?= $reqDescription; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2"> EQUIPMENT LIST</label>
                        <div class="col-md-11">
                            <div class="form-group">
                                <div class="col-md-15">
                                    <table class="table table-bordered" id="tablePegawai" >
					
                                        <thead>
					    </tr>
                                                <th style="width:40%">Equipment <a style="padding: 6px" onClick="addRow()"><i class="fa fa-plus-circle fa-lg"></th>
                                                <th style="width:10%">Kategori </th>
                                                <th style="width:13%">SN </th>
                                                <th style="width:10%">Condition </th>
                                                <th style="width:4%">Out </th>
                                                <th style="width:4%">In </th>
                                                <th style="width:10%">Remarks </th>
                                                <th style="width:10%">Aksi</th>
                                            </tr>
					</thead>
					
                                        <tbody id="tbodyAuditee">
                                           
                                            <?
                                            $this->load->model("SoTemplateEquip");
                                            $so_template_equip = new SoTemplateEquip();
                                            
                                            if ($reqId)
                                            {
                                                $statement = ' AND A.SO_TEMPLATE_ID='.$reqId;
                                            }
                                            else
                                            {
                                                $statement = ' AND 1=2';
                                            }
                                        
                                            $so_template_equip->selectByParamsMonitoringEquips(array(),-1,-1,$statement);
                                            // echo $so_template_equip->query;exit;
                                            $id=0;
                                            while ($so_template_equip->nextRow()) 
                                            {
                                                $reqSoTemplateEquipId = $so_template_equip->getField("SO_TEMPLATE_EQUIP_ID");
                                                $reqEquipId           = $so_template_equip->getField("EQUIP_ID");
                                                $reqEquipName         = $so_template_equip->getField("EQUIP_NAME");
                                                $reqKategori          = $so_template_equip->getField("EC_NAME");
                                                $reqNoSerial          = $so_template_equip->getField("SERIAL_NUMBER");
                                                $reqCondition         = $so_template_equip->getField("EQUIP_CONDITION");
                                                $reqOutCondition      = $so_template_equip->getField("OUT_CONDITION");
                                                $reqInCondition       = $so_template_equip->getField("IN_CONDITION");
                                                $reqRemark            = $so_template_equip->getField("REMARK");
                                                $btn_edit = '<button type="button"  class="btn btn-info " onclick=editing('.$reqSoEquipId.')  ><i class="fa fa-pencil-square-o fa-lg"> </i> </button>';
                                                $btn_delete = '<button type="button"  class="btn btn-danger hapusi"  onclick="deleting('.$reqSoEquipId.')"><i class="fa fa-trash-o fa-lg"> </i> </button>';
                                            ?>
                                            <tr>
                                            <td>
                                                <input readonly type="hidden" name="reqSoTemplateEquipId[]"  id="reqSoTemplateEquipId<?=$id?>" value="<?=$reqSoTemplateEquipId?>">
                                                <input type="hidden" value="<?=$reqEquipId?>" name="reqEquipId[]" id="reqEquipId<?=$id?>">
                                                <div class="input-group">
                                                    <input type="text"  class="easyui-validatebox textbox form-control" id="reqEquipName<?=$id?>" name="reqEquipName[]" value="<?= $reqEquipName ?>" style=" width:105%" onclick="pilih_company('<?=$id?>')"/> 
                                                    <span onclick="pilih_company('<?=$id?>')"class="input-group-addon" > ... </span>
                                                </div>
                                            </td>
                                            <td>
                                                <input class="easyui-validatebox form-control" id="reqKategori<?=$id?>" value="<?=$reqKategori?>" readonly />
                                            </td>
                                            <td>
                                                <input class="easyui-validatebox form-control" id="reqNoSerial<?=$id?>" value="<?=$reqNoSerial?>" readonly />
                                            </td>
                                            <td>
                                                <input class="easyui-validatebox form-control" id="reqCondition<?=$id?>" value="<?=$reqCondition?>" readonly />
                                            </td>
                                            <td>
                                                <input class="easyui-combobox form-control" style="width:100%" id="reqOutCondition<?=$id?>" name="reqOutCondition[]" data-options="width:'95',editable:false, valueField:'id',textField:'text',url:'combo_json/equipmentStatus'" value="<?=$reqOutCondition?>" />
                                            </td>
                                            <td>
                                                <input class="easyui-combobox form-control" style="width:100%" id="reqInCondition<?=$id?>" name="reqInCondition[]" data-options="width:'95',editable:false, valueField:'id',textField:'text',url:'combo_json/equipmentStatus'" value="<?=$reqInCondition?>" />
                                            </td>
                                            <td>
                                                <input  class="easyui-validatebox  form-control" name="reqRemark[]" id="reqRemark<?=$id?>" value="<?=$reqRemark?>"  style="width:120%">

                                            </td>
                                            <td style="text-align:center">
                                                <button type="button"  class="btn btn-danger hapusi" onclick="deleteEquip('<?=$reqSoTemplateEquipId?>');$(this).parent().parent().remove();"><i class="fa fa-trash-o fa-lg"> </i> </button>
                                            </td>             
                                        </tr>

                                            <?
                                            $id++;
                                        }
                                        ?>
                                           
                                        </tbody>
                                    </table>
                                

                                </div>
                            </div>
                        </div>
                    </div>
                    <br>


                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />
                    <input type="hidden" id="reqSoTemplateEquipIdDelete" name="reqSoTemplateEquipIdDelete" value="" />

                    <div style="text-align:center;padding:5px">
                        <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                    </div>
                </form>

                <?
                ?>
                <table style="width: 100%" id="tablei" class="table table-striped table-hover dt-responsive" style="background: white">
                    <thead>
                        <tr>
                            <th width="10%">Aksi </th>
                            <th width="30%">Nama </th>
                            <th width="40%"> Description </th>
                            <th width="100%"> Jumlah Equipment </th>
                        </tr>
                    </thead>
                    <tbody id="tambahAttacment">
                        <?
                       
                        $so_template = new SoTemplate();
                        $so_template->selectByParamsMonitoring(array());
                        while ($so_template->nextRow()) {

                            $reqIds = $so_template->getField("SO_TEMPLATE_ID");
                            $reqJenis           = $so_template->getField("NAMA");
                            $reqDescription     = $so_template->getField("KETERANGAN");
                            $reqJumlah          = $so_template->getField("JUMLAH");
                        ?>
                            <tr>
                                <td> <a onclick="tambahPenyebab(<?= $reqIds ?>)" id="btnPenyebab" class="btn btn-info"><i class="fa fa-pencil"></i></a>
                                    <a onclick="hapus(<?= $reqIds ?>)"  class="btn btn-danger"><i class="fa fa-trash"></i></a>

                                </td>
                                <td><?= $reqJenis ?> </td>
                                <td> <?= $reqDescription ?> </td>
                                <td> <?= $reqJumlah ?> </td>
                            </tr>
                        <?
                        }
                        ?>

                    </tbody>
                </table>
</div>
            </div>
        </div>
    </div>



</body>
<script type="text/javascript">
    function submitForm() {
        $('#ff').form('submit', {
            url: 'web/so_template_json/add',
            onSubmit: function() {
                return $(this).form('enableValidation').form('validate');
            },
            success: function(data) {

                //alert(data);
                $.messager.alertLink('Info', data, 'info', "app/loadUrl/app/tempalate_master_so_template");
                //parent.location.reload(true);
            }
        });
    }

    function clearForm() {
        $('#ff').form('clear');
    }

    function hapus(id){
        deleteData('web/so_template_json/delete',id);
    }

    function tambahPenyebab(id) {
        window.location.href = "app/loadUrl/app/tempalate_master_so_template?reqId=" + id;
    }
    function addRow()
    {
        var rownum= $('#tbodyAuditee tr').length+1;
        var s_url= "app/loadUrl/app/so_template_add_row.php?reqIndex="+rownum;
        $.ajax({'url': s_url,'success': function(data){
            $("#tbodyAuditee").append(data);
        }});

    }
    function deleteEquip(id = '') {
        var deleteId = $("#reqSoTemplateEquipIdDelete").val();
        if(deleteId == "") {
            deleteId = [];
        } else {
            deleteId = JSON.parse(deleteId);
        }
        if(id != ''){
            deleteId.push(id);
            $("#reqSoTemplateEquipIdDelete").val(JSON.stringify(deleteId));    
        }
    }
</script>

<script>
    window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
</script>
<script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
<!-- EMODAL -->
<script src="libraries/emodal/eModal.js"></script>
<script src="libraries/emodal/eModal-cabang.js"></script>
<script type="text/javascript">
    var rowId;
    function openAdd(pageUrl) {
        eModal.iframe(pageUrl, 'Office Management | PT Aquamarine Divindo Inspection')
    }
    function closePopup() {
        eModal.close();
    }
    function pilih_company(id){
        rowId = id;
        openAdd('app/loadUrl/app/template_stock');
    }
    function ambilStock(elements){
        // console.log(elements);
        // console.log('Arik');
        $('#reqEquipName'+rowId).val(elements[4]);
        $("#reqEquipId"+rowId).val(elements[0]);
        $("#reqKategori"+rowId).val(elements[3]);
        $("#reqNoSerial"+rowId).val(elements[6]);
        $("#reqCondition"+rowId).val(elements[10]);
        $("#reqOutCondition"+rowId).combobox('setValue', elements[10]);
        
        $("#reqRemark"+rowId).val(elements[6]);

    }
</script>