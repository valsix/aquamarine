<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <meta name="description" content="">
    <meta name="author" content="">
    <!--<link rel="icon" href="libraries/bootstrap-3.3.7/docs/favicon.ico">-->

    <title>Office Management | PT Aquamarine Divindo Inspection</title>
    <base href="<?= base_url(); ?>" />

    <!-- Bootstrap core CSS -->
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

    <link href='css/pagination.css' rel='stylesheet' type='text/css'>

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
    
     <!-- TAG INPUT -->
     <link rel="stylesheet" type="text/css" href="libraries/taginput/bootstrap-tagsinput.css">
     <script type="text/javascript" language="javascript" src="libraries/taginput/bootstrap-tagsinput.js"></script>






    <!-- PAGINATION -->
    <link rel="stylesheet" type="text/css" href="libraries/drupal-pagination/pagination.css" />

    <!-- tiny MCE -->
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
<style type="text/css">
	h1.head{
  font-size: 250px;
  font-weight: 900;
  letter-spacing: 25px;
  margin: 10px 0 0 0;
}
h1.head span {
  position: relative;
  display: inline-block;
}
h1.head span:before, h1.head span:after{
  position: absolute;
  top:50%;
  width: 50%;
  height: 1px;
  background: #fff;
  content: '';
}
h1.head span:before{
  left: -55%;
}
h1.head span:after{
  right: -55%;
}
.btn-outline{
  border: 2px solid #fff;
  color: #fff;
  padding: 12px 40px;
  border-radius: 25px;
  margin-top: 25px;
  display: inline-block;
  font-weight: 600;
}
.btn-outline:hover{
  color: #aa076b;
  background: #fff;
}



@media (max-width: 1024px) {
  
h1.head {
  font-size: 200px;
  letter-spacing: 25px;
}

}

@media (max-width: 768px) {
  
h1.head {
  font-size: 150px;
  letter-spacing: 25px;
}
}
</style>
</head>

<body>
	<div class="container-fluid ">
	<div class="container text-center">
		<div class="brand">
			<img src="images/logo.png" style="text-align: center;" class="img-fluid">
			<!-- <span class="glyphicon glyphicon-king" aria-hidden="true"></span> -->
			<!-- <h3 class="text-uppercase">Office Management | PT Aquamarine Divindo Inspection</h3> -->
		</div>
		<h1 class="head"><span>404</span></h1>
		<p>Oops! The Page you requested was not found!</p>
		<a href="app" class="btn-outline"> Back to Home</a>
	</div>
</div>

   <footer class="text-center footer">
        <span>Copyright © 2020 PT Aquamarine Divindo Inspection. All Rights Reserved.</span>
    </footer>
</body>
