<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("TypeOfService");
$type_of_service = new TypeOfService();

$reqId = $this->input->get("reqId");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $statement = " AND A.TYPE_OF_SERVICE_ID = " . $reqId;
    $type_of_service->selectByParamsMonitoring(array(), -1, -1, $statement);

    $type_of_service->firstRow();
    $reqId = $type_of_service->getField("TYPE_OF_SERVICE_ID");
    $reqName = $type_of_service->getField("NAME");
    $reqDescription = $type_of_service->getField("DESCRIPTION");
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

    <div class="judul-halaman">Master Of  Code </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <table id="tt" title="Organisasi Struktur" class="easyui-treegrid display mdl-data-table dt-responsive" style="width:1400px; height: 900px; "
                                data-options="
                                url: 'web/cost_code_json/tree_json?',
                                rownumbers: true,
                                 lines: true,
                               pagination:true,
                                  remoteFilter:false,
                                idField: 'ID',
                                 animate: true,
                                treeField: 'NAMA',
                                 collapsible: false,
                                   iconCls: 'icon-ok',
                fitColumns: true,
                                onBeforeLoad: function(row,param){
                                   <!-- $('#tt').datagrid('enableFilter'); -->
                               
                                  if (!row) { // load top level rows
                                  param.id = 0; // set id=0, indicate to load new page rows
                                
                                  }
                                },onClickRow: function(rowIndex, rowData){
                                    // reloadMe(rowIndex.ID); 
                                    // console.log('cek'+  rowIndex.ID);
                                },onLoadSuccess: function(row,param){
                                $('#tt').treegrid('expandAll'); 
                              
                                  <!-- $(this).treegrid('enableDnd', row?row.ID:null); -->
                                
                                
                                 
                              },onDrop: function(target,source,point){
                              <!-- move_tree(source.ID,target.ID); -->
            <!-- alert(target.ID+':'+source.ID+':'+point); -->
          }

                                
                                " >
                                <thead>
                                    <tr >
                                        <th field="CODE" align="left"  width="5%" style="height: 5%;text-align:left">
                                        KODE  <a title=" Tambah Baru " onclick="databaru()"><img src="images/icon-tambah.png" heigth="15px" width="15px" style="margin-right:5px"></a>
                                        </th>
                                        <th field="NAMA" width="15%" >KETERANGAN</th>
                                        <th field="AKSI" width="10%" >AKSI</th>
                                      
                                       
                                       
                                    </tr>
                                </thead>
                                </table>
            </div>
        </div>
    </div>
</div>



</body>
<script type="text/javascript">
    function Delete(id){
        deleteData("web/cost_code_json/delete",id);
    }
    function databaru(){
         openAdd("app/loadUrl/app/tempalate_master_code_add?&reqMode=baru");
    }
    function popTambah(id){
        openAdd("app/loadUrl/app/tempalate_master_code_add?reqId="+id+"&reqMode=insert");
    }
    function popEdit(id){
        openAdd("app/loadUrl/app/tempalate_master_code_add?reqId="+id+"&reqMode=edit");
    }
    
</script>

  <!-- EMODAL -->
    <script src="libraries/emodal/eModal.js"></script>
    <script src="libraries/emodal/eModal-cabang.js"></script>

    <script type="text/javascript">
        function reloads(){
            window.location.reload();
        }
    </script>

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
<script type="text/javascript">
    function tambahPenyebab(id) {
        window.location.href = "app/loadUrl/app/tempalate_master_type_of_service?reqId=" + id;

    }
</script>

<script>
    window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
</script>
<script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>