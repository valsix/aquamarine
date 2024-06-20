<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId   =  $this->input->get("reqId");
if(!empty($reqId )){
$this->load->model("Email");
$email = new Email();
$email->selectByParams(array("A.EMAIL_ID"=>$reqId));
$email->firstRow();
$reqSubject         = $email->getField("SUBJECT");
$reqDescription     = $email->getField("BODY");
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
            font_formats: "Arial=arial;Times New Roman=Times New Roman;Tahoma=Tahoma;Sans=sans-serif;Monospace= monospace;Verdana=Verdana",
            fontsize_formats: "8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 24pt 36pt",

        });
    </script>


<div class="col-md-12">

    <div class="konten-area">
        <div class="konten-inner">
            <div id="bluemenu" class="aksi-area" >
            <span><a id="btnAdd" onclick="news_template()"><i class="fa fa-fw fa-newspaper-o" aria-hidden="true" ></i> Add Email Template</a></span>
            <span><a id="btnEdit" onclick="delete_field()"><i class="fa fa-fw fa-trash-o" aria-hidden="true"></i> Delete Template</a></span>
            <span><a id="btnDelete" onclick="submitForm()"><i class="fa fa-fw fa-save" aria-hidden="true"></i> Save Template</a></span>
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
   

           </div>
           </div>

<div class="col-md-12" >
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
                  

                     

                    

                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Template name</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" name="reqSubject" class="form-control" style="width:100%" value="<?=$reqSubject?>"
                                             
                                         >
                                </div>
                            </div>
                        </div>
                    </div>

                   
                    <div class="form-group">

                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="form-control tinyMCES" name="reqDescription" id="reqDescription" style="width: 100%;height: 400px" ><?= $reqDescription; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                   
                    
                    

                   

                    <input type="hidden" name="reqId" id="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning" onclick="closesss()"><i class="fa fa-fw fa-times"></i> Close</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>

        </div>

    </div>

    <script>
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/template_email_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                      // $.messager.alert('Info', data, 'info');
                      var datas = data.split('-');

                     $.messager.alertLink('Info', datas[1], 'info', "app/loadUrl/app/template_email_template?reqId="+datas[0]);
                
                }
                });
            }

        function clearForm() {
            $('#ff').form('clear');
        }

        function closesss(){
            top.closePopup();
        }

        function delete_field(){
            var anSelectedId = $('#reqId').val();
            $.messager.confirm('Confirm','Apakah anda yakin ingin menghapus data terpilih ? Perlu anda ingat bahwa data yang anda hapus akan hilang secara permanen.',function(r){
                if (r){
                   $.get('web/template_email_json/delete_email' + '?reqId=' + anSelectedId, function(data) {
                      setTimeout(function(){
                        top.closePopup();
                    }, 1000);

                  });
               }
           });
             
               
           
            
        }

        function delete_template(){
            var anSelectedId = $('#reqId').val();
             deleteData("web/template_email_json/delete_email", anSelectedId);
        }
        
        function news_template(){
                $('#ff').form('clear');
                tinyMCE.activeEditor.setContent('');
        }

    </script>
</div>
</div>

 <script>
        window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>