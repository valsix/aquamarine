<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("DokumenKualifikasi");
$dokumen_kualifikasi = new DokumenKualifikasi();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $dokumen_kualifikasi->selectByParamsMonitoringPersonil(array("DOCUMENT_ID" => $reqId));
    // echo $dokumen_kualifikasi->query;
    $dokumen_kualifikasi->firstRow();

    $reqId          = $dokumen_kualifikasi->getField("DOCUMENT_ID");
    $reqName        = $dokumen_kualifikasi->getField("NAME");
    $reqDescription = $dokumen_kualifikasi->getField("DESCRIPTION");
    $reqPath        = $dokumen_kualifikasi->getField("PATH");
    $reqAddress     = $dokumen_kualifikasi->getField("ADDRESS");
    $reqBirthDate   = $dokumen_kualifikasi->getField("BIRTH_DATE");
    $reqPhone       = $dokumen_kualifikasi->getField("PHONE");
    $reqPhone2      = $dokumen_kualifikasi->getField("PHONE2");
    $reqPosition    = $dokumen_kualifikasi->getField("POSITION");
    $reqListCertificate    = $dokumen_kualifikasi->getField("LIST_CERTIFICATE");
    $reqIdNumber    = $dokumen_kualifikasi->getField("ID_NUMBER");
    $reqIdCard      = $dokumen_kualifikasi->getField("ID_CARD");
    $reqCabangId    = $dokumen_kualifikasi->getField("CABANG_ID");
    $reqBirthDates =str_replace('-', '/', $reqBirthDate);
    
}
// echo $reqBirthDate;
$this->load->model('PersonalCertificate');
$certificate = new PersonalCertificate();
$certificate->selectByParamsMonitoring(array());
$arrDatas = array();
$no = 0;
while ($certificate->nextRow()) {
    $arrDatas[$no]['ID']     = $certificate->getField("CERTIFICATE_ID");
    $arrDatas[$no]['NAME']   = $certificate->getField("CERTIFICATE");
    $no++;
}
// echo $reqPosition;
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/personal_kualifikasi"> Personal List</a> &rsaquo; Form Personal List
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entri Document of Personal List
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqJenis" class="control-label col-md-2">Personnel Name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqName" class="easyui-validatebox textbox form-control" name="reqName" value="<?= $reqName ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="form-group">
                        <label for="reqJenis" class="control-label col-md-2">ID Number</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqIdNumber" class="easyui-validatebox textbox form-control" name="reqIdNumber" value="<?= $reqIdNumber ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                         <label for="reqJenis" class="control-label col-md-2">ID Card</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqIdCard" class="easyui-validatebox textbox form-control" name="reqIdCard" value="<?= $reqIdCard ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqAddress" class="control-label col-md-2">Address</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqAddress" cols="4" rows="3" style="width:100%;"><?= $reqAddress; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <label for="reqAddress" class="control-label col-md-2">City</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input class="easyui-combobox form-control" style="width:100%" name="reqCabangId"  id="reqCabangId" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'web/cabang_json/combo'" value="<?= $reqCabangId ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqBirthDate" class="control-label col-md-2">Birth Date</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    
                                    <input type="text" id="reqBirthDate" class="easyui-datebox textbox form-control" name="reqBirthDate" value="<?= $reqBirthDate ?>" data-options="onSelect:selisih_tahuns"style=" width:200px" />
                              
                                     <input type="text" id="reqYear" style="width: 10%"class="easyui-validatebox textbox form-control" name="reqYear" value="<?= $reqDuration ?>" readonly   /> / Year Old
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Phone 1</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPhone" class="easyui-validatebox textbox form-control" name="reqPhone" value="<?= $reqPhone ?>" style=" width:60%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Phone 2</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPhone2" class="easyui-validatebox textbox form-control" name="reqPhone2" value="<?= $reqPhone2 ?>" style=" width:60%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2"> Type Of Qualification </label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqPosition" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/combo_jenis_kwalifikasi'" value="<?=$reqPosition?>" />
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2"> Type of Certificate </label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div style="background-color: white;
                                    height: 150px;
                                    width: 100%;
                                    overflow-y: scroll;">
                                        <table style="width: 100%;color: black;font-weight: bold;" valign="top">
                                            <?
                                            $nomer = 1;
                                            for ($i = 0; $i < count($arrDatas); $i++) {
                                                $checked = '';
                                                $reqTypeOfServices = explode(",", $reqDescription);
                                                for ($j = 0; $j < count($reqTypeOfServices); $j++) {
                                                    if ($arrDatas[$i]['NAME'] == $reqTypeOfServices[$j]) {
                                                        $checked = "checked";
                                                    }
                                                }


                                            ?>

                                                <tr>
                                                    <td style="padding: 2px"><input type="checkbox" class="form-control" name="reqTypeOfService[]" value="<?= $arrDatas[$i]['NAME'] ?>" <?= $checked ?>> </td>
                                                    <td style="padding: 2px"> <?= $nomer . '. ' . $arrDatas[$i]['NAME'] ?> </td>
                                                </tr>
                                            <?
                                                $nomer++;
                                            }
                                            ?>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                     <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2"> List Certificate</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <table class="table" id="tablePegawai" >
                                        <thead>
                                            <tr>
                                                <th style="width:15%">Type of Certificate</th>
                                                <th style="width:45%">Name <a onClick="openAdd('app/loadUrl/app/certificate_lookup')"><i class="fa fa-plus-circle fa-lg"></i></a></th>
                                                <th style="width:15%">Issue Date </th>
                                                <th style="width:15%">Expired Date</th>
                                                <th>AKSI</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyAuditee">
                                           
                                            <?
                                            $this->load->model("DokumenCertificate");
                                            $dokumen_certificate = new DokumenCertificate();
                                            $reqListCertificate = explode(',', $reqListCertificate);

                                            // print_r($reqListCertificate);exit;
                                            for($i=0;$i<count($reqListCertificate);$i++){
                                                if(!empty($reqListCertificate[$i])){
                                                    $reqTypeOfServices = explode(",", $reqDescription);
                                            $id = rand();
                                            $dokumen_certificate->selectByParamsMonitoring(array("A.DOCUMENT_ID" => $reqListCertificate[$i]));
                                            $dokumen_certificate->firstRow();
                                            $reqNames            = $dokumen_certificate->getField("NAME");
                                            $reqIssuedDates      = $dokumen_certificate->getField("ISSUED_DATE");
                                            $reqExpiredDates     = $dokumen_certificate->getField("EXPIRED_DATE");
                                            ?>
                                             

                                            <tr>
                                                 <td>
          <input class="easyui-combobox form-control" style="width:100%" name="reqTypeOfService[]"  id="reqTypeOfService<?=$id?>" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/personalCertificate'" value="<?=$reqTypeOfServices[$i]?>" />
    </td>
                                                <td>
                                                    <input readonly class="easyui-validatebox textbox form-control" type="hidden" name="reqIdCertifcate[]"  id="reqIdCertifcate<?=$id?>" value="<?=$reqListCertificate[$i]?>" data-options="required:true" style="width:100%">
                                                    <input readonly class="easyui-validatebox textbox form-control" type="text" name="reqCertificateName[]"  id="reqCertificateName<?=$id?>" value="<?=$reqNames?>" data-options="required:true" style="width:100%">
                                                </td>
                                                <td>

                                                    <input readonly class="easyui-validatebox  form-control dates" type="text" name="reqIssueDate[]"  id="reqIssueDate<?=$id?>" value="<?=$reqIssuedDates?>" data-options="required:true" style="width:100%">
                                                </td>

                                                <td>

                                                    <input readonly class="easyui-datebox  form-control dates" type="text" name="reqExpiredDate[]"  id="reqExpiredDate<?=$id?>" value="<?=$reqExpiredDates?>" data-options="required:true" style="width:100%">

                                                </td>
                                                <td style="text-align:center">
                                                    <a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a>

                                                </td>             
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



                    <br>
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
                            $files_data = explode(',',  $reqPath);
                            for ($i = 0; $i < count($files_data); $i++) {
                                if (!empty($files_data[$i])) {
                                    $texts = explode('-', $files_data[$i]);
                            ?>
                                    <tr>

                                        <td>
                                            <input type="file" name="document[]" class="form-control" style="width: 90%">
                                            <input type="hidden" name="reqLinkFileTemp[]" value="<?= $files_data[$i] ?>">
                                            <a onclick="openAdd('uploads/personal_qualification/<?= $reqId ?>/<?= $files_data[$i] ?>');" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file"> <?= $files_data[$i] ?> </span>
                                        </td>

                                        <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                    </tr>
                            <?
                                }
                            }
                            ?>

                        </tbody>
                    </table>
                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqTipe" value="Personal Qualification" />

                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>

        </div>

    </div>
<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
    <script>
        function submitForm() {
             var win = $.messager.progress({
            title: 'Office Management  | PT Aquamarine Divindo',
            msg: 'proses data...'
        });
            $('#ff').form('submit', {
                url: 'web/personal_kualifikasi_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                      if(datas[0]=='xxx'){
                             show_toast('error', 'Have troube in ',  datas[1]);
                            $.messager.alert('Info', datas[1], 'info'); 
                    }else{
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/personal_kualifikasi_add?reqId=" + datas[0]);
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
        function tambahPenyebab() {
            $.get("app/loadUrl/app/tempalate_row_attacment?", function(data) {
                $("#tambahAttacment").append(data);
            });
        }
    </script>
      <script type="text/javascript">
        $( document ).ready(function() {
        setTimeout(function(){
            selisih_tahuns();
        }, 1000);
    });
    </script>

    <script type="text/javascript">


        // $('#reqInvoiceDate').datebox({
        //     onSelect: function(date){
        //            //

        //            // var selisih =hitungSelisihHari(tgl1,tgl2);
        //           // selisih_tahuns();
        //         // alert(date.getFullYear()+":"+(date.getMonth()+1)+":"+date.getDate());
        //     }
        // });
        function selisih_tahuns(){
             var tgl2 =datenow();
                   var tgl1 =   $('#reqBirthDate').datebox('getValue');

                   var tgl3 = tgl1.split('-');
                   var tgl4 = tgl3[1]+'/'+tgl3[0]+"/"+tgl3[2];
                   var selisih =hitungSelisihHari(tgl4,tgl2);
                   var selisih2 = selisih/365;
                   console.log(tgl4);
                     console.log(tgl2)
                  $("#reqYear").val(Math.round(selisih2));
                   // console.log(selisih);
                   // var tgl2 =   $('#reqToDate').datebox('getValue');
        }
    </script>
</div>
</div>