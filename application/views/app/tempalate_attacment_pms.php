<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$reqOfferId = $this->input->get("reqOfferId");


$this->load->model('DocumentPms');
$reqId = $this->input->get('reqId');
if(empty($reqId)){
    $reqId=-1;
}
// echo $reqId;
$document_attacment = new DocumentPms();
$document_attacment->selectByParamsMonitoring(array("CAST(A.DOCUMENT_PMS_ID AS VARCHAR)" => $reqId));


$document_attacment->firstRow();
$reqDocumentAttacmentId= $document_attacment->getField("DOCUMENT_PMS_ID");
$reqName= $document_attacment->getField("NAME");
$reqDesciption= $document_attacment->getField("DESCIPTION");
$reqPath= $document_attacment->getField("PATH");
$reqExtension= $document_attacment->getField("EXTENSION");
$reqSize= $document_attacment->getField("SIZE");


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
            selector: ".tinyMCE",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            menubar: true,

        });
    </script>
    <?
    $aColumns = array("DOCUMENT_PMS_ID","NAME","DESCIPTION","PATH","EXTENSION","SIZE","PRIVIEW","AKSI");
    ?>
<script type="text/javascript" language="javascript" class="init">
    var oTable;
    var reqIds;
    $(document).ready(function() {
        reqIds = $("#reqId").val();
        if (reqIds == '') {
            reqIds = '-1';
        }
        oTable = $('#example').dataTable({
            bJQueryUI: true,
            "iDisplayLength": 10,
            /* UNTUK MENGHIDE KOLOM ID */
            "aoColumns": [{
                    bVisible: false
                },
                <?
                for ($i = 1; $i < count($aColumns) - 1; $i++) {
                ?>
                    null,
                <?
                }
                ?>
                null
            ],
            "bSort": false,
            "bProcessing": true,
            "bServerSide": false,
            "bScrollInfinite": true,
            "sAjaxSource": "web/document_pms_json/json?reqId=<?= $reqId ?>",
            "fnServerParams": function(aoData) {
                // aoData.push( { "name": "input1", "value": $("#input1").val() } );
                // aoData.push( { "name": "input2", "value": $("#input2").val() } );
                // aoData.push( { "name": "input3", "value": $("#input3").val() } );
            },
            columnDefs: [{
                className: 'never',
                targets: [0,4,5]
            }],
            "bStateSave": true,
            "fnStateSave": function(oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function(oSettings) {
                var data = localStorage.getItem('DataTables_' + window.location.pathname);
                return JSON.parse(data);
            },
            "sPaginationType": "full_numbers"
           
        });
        /* Click event handler */

        /* RIGHT CLICK EVENT */
        var anSelectedData = '';
        var anSelectedId = '';
        var elements = '';
        var anSelectedDownload = '';
        var anSelectedPosition = '';

        function fnGetSelected(oTableLocal) {
            var aReturn = new Array();
            var aTrs = oTableLocal.fnGetNodes();
            for (var i = 0; i < aTrs.length; i++) {
                if ($(aTrs[i]).hasClass('row_selected')) {
                    aReturn.push(aTrs[i]);
                    anSelectedPosition = i;
                }
            }
            return aReturn;
        }

        $("#example tbody").click(function(event) {
            $(oTable.fnSettings().aoData).each(function() {
                $(this.nTr).removeClass('row_selected');
            });
            $(event.target.parentNode).addClass('row_selected');

            var anSelected = fnGetSelected(oTable);

            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            // console.log(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(',');
            elements = oTable.fnGetData(anSelected[0]);
            anSelectedId = element[0];
        });

        $('#btnAdd').on('click', function() {
            // document.location.href = "app/index/cash_report_add";

            $('#btnProses').show();
            $('#btnProses').html('Add');
            clearForm();

        });

        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            // document.location.href = "app/index/cash_report_add?reqId=" + anSelectedId;
            // alert(anSelectedData);

            $("#reqCashReportDetilId").val(elements[0]);
            $('#reqDetailTanggal').datebox('setValue', elements[1]);
            $("#reqKeterangan").val(elements[2]);
            $("#reqPelunasan").val(elements[3]);
            $("#reqNoRek").val(elements[4]);
            $("#reqDebet").val(elements[5]);
            $("#reqKredit").val(elements[6]);
            // $("#reqSaldo").val(elements[7]);
            // console.log(elements[7]);

            $('#btnProses').show();
            $('#btnProses').html('Update');

        });

        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;
            // deleteData("web/cash_report_json/delete", anSelectedId);
            del(anSelectedId);
        });

        $('#btnRefresh').on('click', function() {
            Refresh();
        });

        $('#btnProses').on('click', function() {
            submitForm();
        });

    });
</script>



<div class="col-md-12">

    <div class="judul-halaman">Entry Document of Pms</div>

    
    <div class="konten-area">
        <div class="konten-inner">
            <div>
                 <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                 

                     <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>Pms File</h3>
                    </div>
                     <div class="form-group">
                        <label for="reqName" class="control-label col-md-2"> Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqName" id="reqName" value="<?= $reqName ?>" style=" width:100%" />
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="form-group">
                        <label for="reqPhone" class="control-label col-md-2">Description</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control" name="reqDescription"  cols="4" rows="3" style="width:100%;"><?= $reqDesciption; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqPhone" class="control-label col-md-2">File path</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="file" name="document[]" class="form-control" style="width: 90%">
                                            <input type="hidden" name="reqLinkFileTemp[]" value="<?= $reqPath ?>">
                                            <?
                                            if(!empty($reqPath)){
                                            ?>
                                            <a onclick="openAdd('uploads/eccommerce/<?= $reqId ?>/<?= $reqPath ?>');" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file"> <?= $reqPath ?> </span>
                                            <?
                                            }
                                            ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                   
                      <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                        <input type="hidden" name="reqOfferId" value="<?= $reqOfferId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />
                     <input type="hidden" name="reqTipe" value="dok_pms" />

                    <div style="text-align:center;padding:5px">
                        <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                    </div>
                </form>

                  <div>
                    <div style="display: none;" >
                       <button class="btn btn-default" type="button" onclick="check()"> Check All </button>
                                    <button class="btn btn-default" type="button" onclick="uncheck()"> Uncheck All </button>
                                      <button class="btn btn-default" type="button" onclick="pilih()"> Pilih </button>
                                  </div>
                            <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <?php
                                        for ($i = 1; $i < count($aColumns); $i++) {
                                        ?>
                                            <th><?= str_replace('_', ' ', $aColumns[$i])  ?></th>
                                        <?php

                                        };
                                        ?>
                                    </tr>
                                </thead>
                            </table>

                        </div>

            </div>
        </div>
    </div>

      

    </div>
    
</div>
 <script src="libraries/emodal/eModal.js"></script>
    <script src="libraries/emodal/eModal-cabang.js"></script>

      <!-- TOAST -->
        <link rel="stylesheet" type="text/css" href="libraries/toast/toast.css" />
        <script type="text/javascript" language="javascript" src="libraries/toast/toast.js?n=1"></script>
        <script type="text/javascript" language="javascript" src="libraries/toast/costum.js"></script>

    <script>
        function openAdd(pageUrl) {
            eModal.iframe(pageUrl, 'Office Management | PT Aquamarine Divindo Inspection')
        }

        function openCabang(pageUrl) {
            eModalCabang.iframe(pageUrl, 'Office Management | PT Aquamarine Divindo Inspection')
        }

        function closePopup() {
            eModal.close();
        }

        function windowOpener(windowHeight, windowWidth, windowName, windowUri) {
            var centerWidth = (window.screen.width - windowWidth) / 2;
            var centerHeight = (window.screen.height - windowHeight) / 2;

            newWindow = window.open(windowUri, windowName, 'resizable=0,width=' + windowWidth +
                ',height=' + windowHeight +
                ',left=' + centerWidth +
                ',top=' + centerHeight);

            newWindow.focus();
            return newWindow.name;
        }

        function windowOpenerPopup(windowHeight, windowWidth, windowName, windowUri) {
            var centerWidth = (window.screen.width - windowWidth) / 2;
            var centerHeight = (window.screen.height - windowHeight) / 2;

            newWindow = window.open(windowUri, windowName, 'resizable=1,scrollbars=yes,width=' + windowWidth +
                ',height=' + windowHeight +
                ',left=' + centerWidth +
                ',top=' + centerHeight);

            newWindow.focus();
            return newWindow.name;
        }
    </script>

 <script>
        window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>

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
                    $("#namaFile"+id).html(input.files[0].name);
                else
                    tambahPenyebab(encodeURIComponent(input.files[i].name))
            }
            
        }
        function editing(id) {

            var elements = oTable.fnGetData(id);
           // console.log(elements[0]);
            window.location.href="app/loadUrl/app/tempalate_attacment_pms?reqId="+elements[0];

        }
         function deleting(id) {
            var elements = oTable.fnGetData(id);
            var kata = '<b>Detail </b><br>' + elements[1] ;
            // console.log(elements[0]);

            $.get("web/document_pms_json/delete?reqId=" + elements[0], function(data) {
                oTable.api().ajax.reload(null,false);
                show_toast('warning', 'Success delete row', kata);
            });
        }
    </script>

    <script type="text/javascript">
        function submitForm() {

            $('#ff').form('submit', {
                url: 'web/document_pms_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');
                     oTable.api().ajax.reload(null,false);
                        show_toast('info', 'Information', data);
                        clearForm();
                    //alert(data);
                    // $.messager.alertLink('Info', datas[1], 'info', "app/loadUrl/app/tempalate_attacment_pms?reqId=" + datas[0]);
                }
            });
        }
        function clearForm(){
                $('#ff').form('clear');
            }
    </script>
<script type="text/javascript">
    $('#select_all').click(function(event) {
        if (this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $(':checkbox').each(function() {
                this.checked = false;
            });
        }
    });
     function check(){
             $(':checkbox').each(function() {
                  this.checked = true;
              });
    }

    function uncheck(){
             $(':checkbox').each(function() {
                  this.checked = false;
              });
    }
</script>

<script type="text/javascript">
    function pilih(){
         var ttds='';
        var kode='';                            
        var params = oTable.$('input').serializeArray();
        $.each(params, function(i, field){
           kode = kode+field.value+',';
       });

        if(kode != ''){
            top.addLampiran(kode);
            top.closePopup();
        }
       
    }
</script>