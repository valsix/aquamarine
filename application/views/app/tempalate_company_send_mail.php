<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$reqId = $this->input->get("reqId");


// $this->load->model('Offer');
// $this->load->model('Document');
// $offer = new Offer();
// $offer->selectByParamsMonitoring(array("A.OFFER_ID"=>$reqOfferId));
// $offer->firstRow();
// $reqId = $offer->getField('DOCUMENT_ID');
// $reqOfferId  = $offer->getField('OFFER_ID');
// $reqCompanyName  = $offer->getField('COMPANY_NAME');
// $reqText = "Attachment of ".$reqCompanyName;
// if(!empty($reqId)){

// $document = new Document();
// $document->selectByParams(array("A.DOCUMENT_ID" => $reqId));
// $document->firstRow();
// $reqId = $document->getField("DOCUMENT_ID");
// $reqName = $document->getField("NAME");
// $reqDescription = $document->getField("DESCRIPTION");
// $reqPath = $document->getField("PATH");
// }
// if(empty($reqName)){
//     $reqName = $reqText;
// }
// if(empty($reqDescription)){
//     $reqDescription = $reqText;
// }
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
            mode: "specific_textareas",
            selector: ".tinyMCES",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste",
                "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking"
            ],

            toolbar: "insertfile undo redo | styleselect | sizeselect | bold italic | fontsize | fontselect | fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ",
            toolbar2: " responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
            image_advtab: true,
            menubar: true,
            image_advtab: true,
            external_filemanager_path: "<?= base_url() ?>filemanager/",
            filemanager_title: "File manager",
            external_plugins: {
                "filemanager": "<?= base_url() ?>filemanager/plugin.min.js"
            },
            font_formats: 'Arial=arial;Times New Roman=Times New Roman;Tahoma=Tahoma;Sans=sans-serif;Monospace= monospace;Verdana=Verdana',
            fontsize_formats: "8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 24pt 36pt",
        });
    </script>

       <!-- TAG INPUT -->
     <link rel="stylesheet" type="text/css" href="libraries/taginput/bootstrap-tagsinput.css">
     <script type="text/javascript" language="javascript" src="libraries/taginput/bootstrap-tagsinput.js"></script>

<style type="text/css">
    .bootstrap-tagsinput {
      width: 100% !important;
  }
    .bootstrap-tagsinput .tag {
        margin-right: 2px;
        color: black;
        font-size: 16px;
    }
    .bootstrap-tagsinput span   {
        
        color: red;
        /*font-size: 16px;*/
    }
</style>
<script type="text/javascript">
        $( document ).ready(function() {
            $('.tagsinput').tagsinput({
  tagClass: 'big'
});
        });
    </script>

<?php
// Header Nama TABEL TH


?>



<div class="col-md-12">

    <div class="judul-halaman">Entry Document of Attachment</div>

    
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd" onclick="add_email_template()"><i class="fa fa-fw fa-envelope-o" aria-hidden="true"></i> Add Email Template</a></span>
            <span><a id="btnEdit" onclick="edit_email_template()"><i class="fa fa-fw fa-file-text" aria-hidden="true"></i> Edit Email Template</a></span>
            <span><a id="btnDelete" onclick="load_email_template()"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Load Template</a></span>
            
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
           
            <br>
            <br>
        </div>
        <div class="konten-inner">
            <div>
                 <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                 

                     <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>Attacment File</h3>
                    </div>
                     <div class="form-group">
                        <label for="reqName" class="control-label col-md-2"> Name</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="form-control tagsinput" name="reqName3" id="reqName3"  placeholder="ex: Bpk Aqumarine [aqumarine@gmail.com],Ibu Aqumarine [ aqumarine@yahoo.com ]  ( , ) to space" />
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="form-group">
                        <!-- <label for="reqPhone" class="control-label col-md-2">Description</label> -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea class="form-control tinyMCES" name="reqDescription"  cols="4" rows="3" style="width:100%;"><?= $reqDescription; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                   

                      <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                        <input type="hidden" name="reqOfferId" value="<?= $reqOfferId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />
                     <input type="hidden" name="reqEmailId" id="reqEmailId"  />
                     <input type="hidden" name="reqTipe" value="Attachment" />

                    <div style="text-align:center;padding:5px">
                        <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Send</a>
                    </div>
                </form>

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
        function tambahPenyebab() {
            $.get("app/loadUrl/app/tempalate_row_attacment?", function(data) {
                $("#tambahAttacment").append(data);
            });
        }

        function edit_email_template() {
        var reqId = $("#reqEmailId").val();

        if (reqId == '') {
            return;
        }
        openAdd('app/loadUrl/app/template_email_template?reqId=' + reqId);
    }
    function terpilih_template(id) {

        $("#reqEmailId").val(id);
        $.get("web/template_email_json/load_template_body?reqId=" + id, function(data) {

            $(tinymce.get('reqDescription').getBody()).html(data);


        });

    }

    function load_company(id) {
        openAdd('app/loadUrl/app/template_load_company?reqId=' + id);
    }

    function load_email_template() {
        openAdd('app/loadUrl/app/template_load_email_parent');
    }

    </script>


    <script type="text/javascript">
        function submitForm() {

            var win = $.messager.progress({
                title: 'Office Management  | PT Aquamarine Divindo',
                msg: 'proses data...'
            });

            $('#ff').form('submit', {
                url: 'web/pre_report_json/send_as_email',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    // var datas = data.split('-');
                    $.messager.alertLink('Info', data, 'info');
                    $.messager.progress('close');
                  
                }
            });
        }
    </script>
