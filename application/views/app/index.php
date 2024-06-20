<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$stylemenumarketing = "";
$stylemenufinance = "";
$stylemenuproduction = "";
$stylemenudocument = "";
$stylemenuresearch = "";
$stylemenuothers = "";
$stylemenuepl = "";
$stylemenuuwild = "";
$stylemenuwp = "";
$stylemenupl = "";
$stylemenuel = "";
$stylemenupms = "";
$stylemenurs = "";
$stylemenustd = "";
$stylemenusten = "";
$stylemenuswd = "";
$stylemenuswarehouse = "";


if($this->MENUMARKETING == "0")
    $stylemenumarketing = "style='display:none;'";

if($this->MENUFINANCE == "0")
    $stylemenufinance = "style='display:none;'";

if($this->MENUPRODUCTION == "0")
    $stylemenuproduction = "style='display:none;'";

if($this->MENUDOCUMENT == "0")
    $stylemenudocument = "style='display:none;'";

if($this->MENUSEARCH == "0")
    $stylemenuresearch = "style='display:none;'";

if($this->MENUOTHERS == "0")
    $stylemenuothers = "style='display:none;'";

if($this->MENUEPL == "0")
    $stylemenuepl = "style='display:none;'";

if($this->MENUUWILD == "0")
    $stylemenuuwild = "style='display:none;'";

if($this->MENUWP == "0")
    $stylemenuwp = "style='display:none;'";

if($this->MENUPL == "0")
    $stylemenupl = "style='display:none;'";

if($this->MENUEL == "0")
    $stylemenuel = "style='display:none;'";

if($this->MENUPMS == "0")
    $stylemenupms = "style='display:none;'";

if($this->MENURS == "0")
    $stylemenurs = "style='display:none;'";

if($this->MENUSTD == "0")
    $stylemenustd = "style='display:none;'";

if($this->MENUSTEN == "0")
    $stylemenusten = "style='display:none;'";

if($this->MENUSWD == "0")
    $stylemenuswd = "style='display:none;'";

if($this->MENUWAREHOUSE == "0")
    $stylemenuswarehouse = "style='display:none;'";


$stylemenuAnother='';
if($this->MENUINVPROJECT == "0"){
    $stylemenuinvproject = "style='display:none;'";
}else if($this->MENUINVPROJECT==1 && $this->MENUFINANCE==0 ){
     $stylemenufinance='';
     $stylemenuAnother= "style='display:none;'";
}



?>
<!DOCTYPE html>
<html lang="en">

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
            remove_linebreaks : false,
             force_br_newlines : true,
        force_p_newlines : false,
        forced_root_block : '',
         style_formats : [
            {title : 'Line height 20px', selector : 'p,div,h1,h2,h3,h4,h5,h6', styles: {lineHeight: '20px'}},
            {title : 'Line height 30px', selector : 'p,div,h1,h2,h3,h4,h5,h6', styles: {lineHeight: '30px'}}
    ]
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

        .bootstrap-tagsinput span {

            color: red;
            /*font-size: 16px;*/
        }
        tbody > tr, input[type=checkbox]{
            cursor: pointer;
        }
        .alat_list {
    width: 12em;
    height: 10em;
    line-height: 2em;
    border: 1px solid #ccc;
    padding: 0;
    margin: 0;
    overflow: scroll;
    overflow-x: hidden;
}

    </style>
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="app/" class="logo navbar-brand">
                <img src="images/logo.png" class="img-responsive">
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <!--<ul class="nav navbar-nav">
            <li><a href="#">Left</a></li>
        </ul>-->
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="app"><i style="margin-top: -5px" class="fa fa-home fa-2x"></i></a>
                </li>
				 <li class="dropdown" <?=$stylemenuswarehouse?>>
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">Warehouse
                        <span class="caret"></span></a>
                         <ul class="dropdown-menu">
                             <li <?=$stylemenuAnother?>><a href="app/index/material_invoice">Pembelian Material dan Pembayaran </a></li>
                             <li><a href="app/index/pembelian">Pembelian Barang dan Jasa </a></li>
                             <!-- <li><a href="app/index/penyimpanan">Pengolahan Penyimpanan </a></li> -->
                              <li <?=$stylemenupms?>><a href="app/index/pms">PMS </a></li>
                             <li><a href="app/index/pengelolaan_alat_kerja">Pengolahan Alat</a></li>
                              <li><a href="app/index/pengelolaan_spare_part">Pengolahan Spare Part</a></li>
                                <li><a href="app/index/issue_po">Issue PO Project</a></li>
                            <li><a href="app/index/supplier_list">Vendor List</a></li>
                         </ul>
                   </li>
                <li class="nav-item dropdown" <?=$stylemenumarketing?>>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Marketing
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <!-- <li><a href="app/index/aplikasi">Aplikasi </a></li> -->
                        <!-- <li><a href="app/index/customer">Customer</a></li> -->
                        <li><a href="app/index/customer_list">Customer List </a></li>
                        <li><a href="app/index/e_commerce">E-Commerce</a></li>
                       <li ><a class="dropdown-item dropdown-toggle" href="#">Offering <span class="fa fa-caret-right pull-right"></span></a>
                            <ul class="submenu dropdown-menu">
                                <li><a class="dropdown-item" href="app/index/offer">Offering Survey</a></li>
                                <li><a class="dropdown-item" href="app/index/offer_realisasi">Monitoring Realisasi</a></li>
                            </ul>
                        </li>
                        <!-- <li><a href="app/index/service_order">Service Order </a></li> -->
                        <li ><a class="dropdown-item dropdown-toggle" href="javascript:void(0)">Operation Work Request (OWR) <span class="fa fa-caret-right pull-right"></span></a>
                            <ul class="submenu dropdown-menu">
                                <li><a class="dropdown-item" href="app/index/service_order_new">OWR Project</a></li>
                                <li><a class="dropdown-item" href="app/index/service_order">OWR Survey</a></li>
                            </ul>
                        </li>
                        <!-- <li><a href="app/index/service_order">Operation Work Request </a></li> -->
                        <!-- <li><a href="app/index/document">Experience List</a></li> -->
                        <li><a href="app/index/experience_list">Experience List</a></li>
                        <li><a href="app/index/company_profile">Company Profile</a></li>
                        <!-- <li><a href="app/index/document">Dcocuments</a></li> -->
                        <li><a href="app/index/website">Website</a></li>
                         <!--<li><a href="app/index/issue_po">Issue PO Project</a></li> -->
                        <li><a href="app/index/issue_po_non_project">Issue PO non Project</a></li>
                        <li><a href="app/index/reminder_client">Reminder Client</a></li>
                         <li ><a class="dropdown-item dropdown-toggle" href="#">Hpp <span class="fa fa-caret-right pull-right"></span></a>
                            <ul class="submenu dropdown-menu">
                                <li><a class="dropdown-item" href="app/index/project_hpp_new">Hpp Project</a></li>
                                <li><a class="dropdown-item" href="app/index/project_hpp">Hpp Survey</a></li>
                            </ul>
                        </li>

                    </ul>
                </li>

                <li class="nav-item dropdown" <?=$stylemenufinance?>>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Finance
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <!-- <li><a href="app/index/aplikasi">Aplikasi </a></li> -->
                        <li <?=$stylemenuAnother?>><a href="app/index/project_cost">Survey Cost</a></li>
                           <li ><a class="dropdown-item dropdown-toggle" href="#">Invoice  <span class="fa fa-caret-right pull-right"></span></a>
                            <ul class="submenu dropdown-menu">
                                <li><a class="dropdown-item" href="app/index/invoice_news">Invoice Project</a></li>
                                <li  <?=$stylemenuinvproject?>><a class="dropdown-item" href="app/index/invoice">Invoice Survey</a></li>
                            </ul>
                        </li>
                        <!-- <li><a href="app/index/invoice">Invoice</a></li> -->
                 
                        <li <?=$stylemenuAnother?>><a href="app/index/material_invoice">Pembelian Material dan Pembayaran </a></li>
                        <li <?=$stylemenuAnother?>><a href="app/index/cost_request">Cost Request</a></li>
                        <!-- <li><a href="app/index/cash_report">Cash Flow Report</a></li> -->
                        <li <?=$stylemenuAnother?>><a href="app/index/cash_report">Cash Flow Report</a></li>
                        <li <?=$stylemenuAnother?>><a href="app/index/cash_saldo">Saldo Cash</a></li>
                        <li <?=$stylemenuAnother?>><a href="app/index/kas_besar">Kas Besar</a></li>
                        <li <?=$stylemenuAnother?>><a href="app/index/kas_kecil">Kas Kecil</a></li>
                        <li <?=$stylemenuAnother?>><a href="app/index/laporan_keuangan">Laporan Keuangan</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown" <?=$stylemenuproduction?>>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Production
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <!-- <li><a href="app/index/aplikasi">Aplikasi </a></li> -->
                        <!-- <li><a href="app/index/equipment_delivery">Equipment Delivery</a></li> -->
                        <li <?=$stylemenuepl?>><a href="app/index/equipment_delivery">Equipment Project List</a></li>
                         <li <?=$stylemenuepl?>><a href="app/index/equipment_pengembalian">Pengembalian  Project</a></li>
                        <!-- <li><a href="app/index/pre_report">Pre Report</a></li> -->
                        <li <?=$stylemenuuwild?>><a href="app/index/pre_report">Form Underwater / UWILD</a></li>
                        <!-- <li><a href="app/index/work_procedures">Work Procedures</a></li> -->
                        <li <?=$stylemenuwp?>><a href="app/index/work_procedures">Working Procedures</a></li>
                        <!-- <li><a href="app/index/personal_kualifikasi">Personal Qualifications </a></li> -->
                        <li <?=$stylemenupl?>><a href="app/index/personal_kualifikasi">Personal List </a></li>
                        <!-- <li><a href="app/index/equipment">Equipment </a></li> -->
                        <li <?=$stylemenuel?>><a href="app/index/equipment">Equipment List</a></li>
                       <!--  <li <?=$stylemenupms?>><a href="app/index/pms">PMS </a></li>-->
                        <!-- <li><a href="app/index/report">Report </a></li> -->
                        <li <?=$stylemenurs?>><a href="app/index/report">Report Survey</a></li>
                        <li <?=$stylemenustd?>><a href="app/index/standarisasi">Standarisasi </a></li>
                        <li <?= $stylemenusten?>><a class="dropdown-item dropdown-toggle" href="#">Tender <span class="fa fa-caret-right pull-right"></span></a>
                            <ul class="submenu dropdown-menu">
                                <li><a class="dropdown-item" href="app/index/tender_monitoring">Tender Monitoring</a></li>
                                <li><a class="dropdown-item" href="app/index/tender_project">File Project</a></li>
                            </ul>
                        </li>
                         <li <?=$stylemenuswd?>><a href="app/index/weekly_meeting">Weekly Meeting </a></li>
                    </ul>
                </li>

                <li class="dropdown" <?=$stylemenudocument?>>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Documents
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <!-- <li><a href="app/index/aplikasi">Aplikasi </a></li> -->
                        <li><a href="app/index/certificate">Certificates</a></li>
                        <li><a href="app/index/qms">QMS</a></li>
                        <li><a href="app/index/qps">QPS</a></li>
                        <li><a href="app/index/form">Form</a></li>
                        <li><a href="app/index/hse">HSE & ISO </a></li>
                        <li><a href="app/index/legality_letters">Legality Letters</a></li>
                        <li><a href="app/index/employment_contracts">Contracts </a></li>
                        <li><a href="app/index/rules">Rules & Regulation </a></li>
                        <li><a href="app/index/drawing">Drawing </a></li>
                        <li><a href="app/index/others">Others & MOU </a></li>
                        <!-- <li><a href="app/index/company_experience">Company Experience </a></li> -->
                        <!-- <li><a href="app/index/document">Company Experience </a></li> -->

                        <li><a href="app/index/qhse">QHSE </a></li>
                          <li><a href="app/index/wi">WI </a></li>
                        <li><a href="app/index/work_instructions">Work Instructions </a></li>
                        <li><a href="app/index/report_department">Report Department </a></li>
                        <li><a href="app/index/tender_dok">Dokument Tender </a></li>
                    </ul>
                </li>

                <li class="dropdown" <?=$stylemenuresearch?>>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Research & Develop
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <!-- <li><a href="app/index/aplikasi">Aplikasi </a></li> -->
                        <li><a href="app/index/bussines_plan">Bussines Plan</a></li>
                        <li><a href="app/index/customer_complain">Customer Complain</a></li>
                        <li><a href="app/index/statistic_analyst">Statistic and Analyst</a></li>
                        <li><a href="app/index/tender_evaluation">Tender Evaluation</a></li>

                    </ul>
                </li>

                <li class="dropdown" <?=$stylemenuothers?>>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Others
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <!-- <li><a href="app/index/aplikasi">Aplikasi </a></li> -->
                        <li><a href="app/index/users_management">Users Management</a></li>
                        <li><a href="javascript:void(0)" onclick="openAdd('app/loadUrl/app/tempalate_master_email');">Email Setting</a></li>
                        <!-- <li><a href="app/index/application_settings">Application Settings</a></li> -->
                        <li><a href="app/index/searching_document">Searching Document</a></li>
                        <li><a href="app/index/log_history">Log History </a></li>
                    </ul>
                </li>

                <!--</ul>
        <ul class="nav navbar-nav navbar-right">-->
                <li class="dropdown dropdown-info-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user-circle-o"></i>
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="info-user">
                                <div class="nama"><?= $this->FULLNAME ?></div>
                                <div class="jabatan"><?= $this->JABATAN ?></div>
                            </div>
                        </li>
                        <li><a href="app/index/ganti_password"><i class="fa fa-key fa-xs"></i> Ganti Password</a></li>
                        <li><a href="login/logout"><i class="fa fa-sign-out fa-xs"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Begin page content -->
    <!--<div class="container-fluid" style="height: calc(100vh - 90px - 45px); padding-bottom: 0px;">-->
    <!--<div class="row" style="position: relative; min-height: 100%; height: 100%;">-->
    <div class="container-fluid <? if ($pg == "" || $pg == "home") { ?> container-home<? } ?>">
        <!--<div class="container-fluid">-->
        <div class="row" style="position: relative;">
            <?= ($content ? $content : '') ?>

        </div>
    </div>

    <footer class="text-center footer">
        <span>Copyright © 2020 PT Aquamarine Divindo Inspection. All Rights Reserved.</span>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
    <script>
        window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>

    <!-- SCROLLBAR -->
    <link rel="stylesheet" href="css/scrollbar.css" type="text/css">
    <script type='text/javascript' src="js/enscroll-0.6.0.min.js"></script>
    <script type='text/javascript'>
        //<![CDATA[
        $(function() {
            $('.operator-inner').enscroll({
                showOnHover: false,
                verticalTrackClass: 'track3',
                verticalHandleClass: 'handle3'
            });
        }); //]]>
    </script>
    <!--<script src="libraries/bootstrap/dist/js/bootstrap.min.js"></script>-->

    <!-- EASYUI 1.4.5 -->
    <link rel="stylesheet" type="text/css" href="libraries/easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="libraries/easyui/themes/icon.css">
    <script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="libraries/easyui/globalfunction.js"></script>
    <script type="text/javascript" src="libraries/easyui/kalender-easyui.js"></script>
    <script type="text/javascript" src="libraries/functions/string.func.js?n=1"></script>
    <script type="text/javascript" src="libraries/functions/command.js"></script>

    <!-- EMODAL -->
    <script src="libraries/emodal/eModal.js"></script>
    <script src="libraries/emodal/eModal-cabang.js"></script>

    <!-- TOAST -->
    <link rel="stylesheet" type="text/css" href="libraries/toast/toast.css" />
    <script type="text/javascript" language="javascript" src="libraries/toast/toast.js?n=1"></script>
    <script type="text/javascript" language="javascript" src="libraries/toast/costum.js"></script>

    <!-- YEAR PICKER -->
    <link rel="stylesheet" type="text/css" href="libraries/bootstrap-datepicker/bootstrap-datepicker.css" />
    <script type="text/javascript" language="javascript" src="libraries/bootstrap-datepicker/bootstrap-datepicker.js?n=1"></script>
    <style type="text/css">
        .redClass {
            background-color: red !important;
        }

        .yellowClass {
            background-color: yellow !important;
        }
        .blueClass {
            background-color: #7B83EB !important;
        }

        .greenClass {
            background-color: green !important;
        }

        .grayClass {
            background-color: #BDBDBD !important;
        }

        .x.close span {
            font-size: 36px;
        }
        table.table tbody tr.even.row_selected td {
            background-color: #1f295a !important;
            color: #FFF;
        }
        table.table tbody tr.odd.row_selected td {
            background-color: #1f295a !important;
            color: #FFF;
        }
    </style>

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

    <!-- ACCORDION -->
    <link href="libraries/jquery-accordion-menu/style/format.css" rel="stylesheet" type="text/css" />
    <link href="libraries/jquery-accordion-menu/style/text.css" rel="stylesheet" type="text/css" />
    <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"> </script>-->
    <script type="text/javascript">
        $(document).ready(function() {

            $('div.accordionButton').click(function() {
                $('div.accordionContent').slideUp('normal');
                $(this).next().slideDown('normal');
            });

            $("div.accordionContent").show();
            $('div.accordionContent:first').show();

        });
    </script>


    <!-- SELECTED ROW ON TABLE SCROLLING -->
    <style>
        *table#Demo tbody tr:nth-child(odd) {
            background-color: #ddf7ef;
        }

        table#Demo tbody tr:hover {
            background-color: #333;
            color: #FFFFFF;
        }

        table#Demo tbody tr.selectedRow {
            background-color: #0072bc;
            color: #FFFFFF;
        }
    </style>
    <script>
        $("table#Demo tbody tr").click(function() {
            //alert("haii");
            $("table tr").removeClass('selectedRow');
            $(this).addClass('selectedRow');
        });
    </script>
    <script type="text/javascript">
        function Refresh() {
            document.location.reload();
        }
    </script>
    <!-- CHANGE BGCOLOR WHEN SCROLL -->
    <script>
        $(function() {
            $(document).scroll(function() {
                var $nav = $(".navbar-fixed-top");
                $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
            });
        });
    </script>
    <script type="text/javascript">
          
    </script>
    <script type="text/javascript">
        function round(value, decimals) {
            return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
        }
    </script>
    <style>
        .navbar-fixed-top.scrolled {
            transition: background-color 1000ms linear;
            *background-color: #86a4d5 !important;
            background-color: #000000 !important;
        }
    </style>
    <script type="text/javascript">
        function deleteData_for_table(delele_link, id, idx, position) {
            var elements = oTable.fnGetData(idx);
            //  console.log('Arik');
            // console.log(elements);
            // return;
            var kata = '<b>Detail </b><br>' + elements[position+1] + '<br>';
            $.messager.confirm('Konfirmasi', 'Yakin menghapus data terpilih ?<br>' + kata, function(r) {
                if (r) {
                    var jqxhr = $.get(delele_link + '?reqId=' + id, function(data) {
                            oTable.api().ajax.reload(null,false);
                            show_toast('warning', 'Delete row', kata + ' - ' + data);
                            oTable.api().ajax.reload(null,false);
                            // document.location.reload();
                        })
                        .done(function() {
                            show_toast('warning', 'Delete row', kata + ' - ' + data);
                            oTable.api().ajax.reload(null,false);
                            // document.location.reload();
                        })
                        .fail(function() {
                            // oTable.api().ajax.reload(null,false);
                            alert("error");
                        });
                }
            });
        }
    </script>

    <script type="text/javascript">
        function hidding() {
            $('#ff').find('input, textarea, select').attr('disabled', 'disabled');
            $('#ff .easyui-combobox').combobox({
                disabled: true
            });
            $('#ff .easyui-datebox').datebox({
                disabled: true
            });
            $('.btn-primary').attr('disabled', '').css('pointer-events', 'none');
            $('.btn-warning').attr('disabled', '').css('pointer-events', 'none');
            //  $('#element_id').css('pointer-events','none');
            // style.pointerEvents = 'none';
            $('.btn-danger').attr('disabled', '').css('pointer-events', 'none');
            $('.btn-info').attr('disabled', '').css('pointer-events', 'none');
            $('.btn-success').attr('disabled', '').css('pointer-events', 'none');
            // $(".btn-info").off('click');
            $('a .fa-trash-o').parent().hide();
            $('a .fa-trash').parent().hide();
            $('a .fa-pencil').parent().hide();


        }

        function unHidding() {
            $('#ff').find('input, textarea, select').removeAttr("disabled");
            $('#ff .easyui-combobox').combobox({
                disabled: false
            });
            $('#ff .easyui-datebox').datebox({
                disabled: false
            });

            $('.btn-primary').removeAttr("disabled").css('pointer-events', '');
            $('.btn-warning').removeAttr("disabled").css('pointer-events', '');
            $('.btn-danger').removeAttr("disabled").css('pointer-events', '');
            $('.btn-info').removeAttr("disabled").css('pointer-events', '');
            $('.btn-success').removeAttr("disabled").css('pointer-events', '');

            $('.fa-trash-o').parent().show();
            $('.fa-trash').parent().show();
            $('.fa-pencil').parent().show();

        }
        var myFunc = function(event) {
            event.stopPropagation();
            // execute a bunch of action to preform
        }
    </script>

    <script type="text/javascript">
        setTimeout(function() {
            $(document).ready(function() {
                <?
                if (!empty($reqId)) {
                ?>
                    hidding();
                <?
                } else {
                ?>
                    editing_form();
                <?
                }
                ?>
            });
        }, 1000);


        var i = 0;

        function editing_form() {

            if (i == 0) {
                $('#opens').removeClass('fa fa fa-folder fa-lg').addClass('fa fa fa-folder-open-o fa-lg');
                $('#htmlopen').html("Close");
                unHidding();
                i = 1;
            } else {
                $('#opens').removeClass('fa fa fa-folder-open-o fa-lg').addClass('fa fa fa-folder fa-lg');
                $('#htmlopen').html("Open");
                i = 0;
                hidding();

            }

        }
    </script>
    <script type="text/javascript">
        function types() {
            openAdd("app/loadUrl/app/tempalate_master_type_of_vessel");
        }

        function classes() {
            openAdd("app/loadUrl/app/tempalate_master_class_of_service");
        }

        function master_format() {
            openAdd("app/loadUrl/app/tempalate_master_format");
        }

        function master_ttd() {
            openAdd("app/loadUrl/app/tempalate_master_ttd");
        }

        function master_category_project() {
            openAdd("app/loadUrl/app/tempalate_master_category_project");
        }

        function master_type_contract() {
            openAdd("app/loadUrl/app/tempalate_master_type_contract");
        }

        function master_type_tender(type, id) {
            openAdd("app/loadUrl/app/tempalate_master_type_tender?reqType="+type+"&reqTenderId="+id);
        }

        function master_type_po(type) {
            openAdd("app/loadUrl/app/tempalate_master_type_po");
        }

    </script>

    <script type="text/javascript">
        var back = 1;

        function goBack() {


            window.history.back();

        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.tagsinput').tagsinput({
                tagClass: 'big'
            });
        });
    </script>
    <script type="text/javascript">
        function searching_post() {

            var win = $.messager.progress({
                title: 'Office Management  | PT Aquamarine Divindo',
                msg: 'proses data...'
            });
            setTimeout(function() {
                oTable.fnPageChange(0);
                oTable.api().ajax.reload(null,false);
                $.messager.progress('close');
            }, 700);


        }
    </script>
    <script type="text/javascript">
        $(".datepicker").datepicker({
            format: " yyyy", // Notice the Extra space at the beginning
            viewMode: "years",
            minViewMode: "years"
        });
    </script>

    <script type="text/javascript">
        function tambahDirektorat(id, CERTIFICATE_ID, NAME, DESCRIPTION, ISSUED_DATE, EXPIRED_DATE, SURVEYOR) {
            var rv = true;
            $('[name^=reqAuditDirektoratKdDitId]').each(function() {
                if ($(this).val() == id) {
                    rv = false;
                    return false;
                }
            });

            if (rv == true) {
                $.post("app/loadUrl/app/template_certificate", {
                        reqId: id,
                        reqCertificateId: CERTIFICATE_ID,
                        reqName: NAME,
                        reqDescription: DESCRIPTION,
                        reqIssuedDate: ISSUED_DATE,
                        reqExpiredDate: EXPIRED_DATE,
                        reqSurveyor: SURVEYOR
                    })
                    .done(function(data) {
                        $("#tbodyAuditee").append(data);
                    });
            }
        }
    </script>
	 <script type="text/javascript">
        function closeComplit(){
              $(".complit").hide();
        }
    </script>
    <script type="text/javascript">
    /// some script

    // jquery ready start
    $(document).ready(function() {
        // jQuery code

        //////////////////////// Prevent closing from click inside dropdown
        $(document).on('click', '.dropdown-menu', function (e) {
          e.stopPropagation();
        });

        // make it as accordion for smaller screens
        if ($(window).width() < 992) {
            $('.dropdown-menu a').click(function(e){
                e.preventDefault();
                if($(this).next('.submenu').length){
                    $(this).next('.submenu').toggle();
                }
                $('.dropdown').on('hide.bs.dropdown', function () {
                   $(this).find('.submenu').hide();
                })
            });
        }
        
    }); // jquery end
    </script>

    <style type="text/css">
        @media (min-width: 992px){
            .dropdown-menu .dropdown-toggle:after{
                border-top: .3em solid transparent;
                border-right: 0;
                border-bottom: .3em solid transparent;
                border-left: .3em solid;
            }

            .dropdown-menu .dropdown-menu{
                margin-left:0; margin-right: 0;
            }

            .dropdown-menu li{
                position: relative;
            }
            .nav-item .submenu{ 
                display: none;
                position: absolute;
                left:100%; top:-3px;
                background: #03428b;

                box-shadow: none;
                border: none;

                -webkit-border-radius: 4px;
                -moz-border-radius: 4px;
                border-radius: 4px;

                *padding: 0 0;
            }
            .nav-item .submenu-left{ 
                right:100%; left:auto;
            }
            .dropdown-menu > li:hover > .submenu{
                display: block;
            }
            .dropdown-item {
                background-color: rgb(3, 66, 139);
            }
            .dropdown-item > li:hover{ background-color: rgba(0,0,0,0.1) }
        }
    </style>
	
	<script type="text/javascript">
      var oTable2;
    $("#example2 tbody").click(function(event) {
        $(oTable2.fnSettings().aoData).each(function (){
          $(this.nTr).removeClass('row_selected');
        });
        $(event.target.parentNode).addClass('row_selected');
      });
       function fnGetSelected( oTableLocal )
{
    var aReturn = new Array();
    var aTrs = oTableLocal.fnGetNodes();
    
    for ( var i=0 ; i<aTrs.length ; i++ )
    {
        if ( $(aTrs[i]).hasClass('row_selected') )
        {
            aReturn.push( aTrs[i] );
        }
    }
    return aReturn;
}

</script>
</body>



</html>