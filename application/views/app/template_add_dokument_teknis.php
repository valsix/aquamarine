<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("MasterLokasi");
$so_team = new MasterLokasi();

$aColumns = array("MASTER_LOKASI_ID", "NAMA", "KETERANGAN","AKSI" );
$reqId = $this->input->get("reqId");
$reqModes = $this->input->get("reqModes");
$reqSoTeamId = $this->input->get("reqSoTeamId");

if($reqSoTeamId == ""){}
else {
    $so_team->selectByParamsMonitoringTeam(array("MASTER_LOKASI_ID" => $reqId));
    $so_team->firstRow();
    $reqId    =  $so_team->getField("MASTER_LOKASI_ID");
    $reqCode  = $so_team->getField("NAMA");
    $reqKeterangan   = $so_team->getField("KETERANGAN");
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
    <script type="text/javascript" src="libraries/functions/string.func.js"></script>

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





<!-- FIXED AKSI AREA WHEN SCROLLING -->
<link rel="stylesheet" href="css/gaya-stick-when-scroll.css" type="text/css">
<script src="js/stick.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        var s = $("#bluemenu");

        var pos = s.position();
        $(window).scroll(function() {
            var windowpos = $(window).scrollTop();
            //s.html("Distance from top:" + pos.top + "<br />Scroll position: " + windowpos);
            if (windowpos >= pos.top) {
                s.addClass("stick");
                $('#example thead').addClass('stick-datatable');
            } else {
                s.removeClass("stick");
                $('#example thead').removeClass('stick-datatable');
            }
        });
    });
</script>

<style>
    /** THEAD **/
    thead.stick-datatable th:nth-child(1) {
        width: 440px !important;
        *border: 1px solid cyan;
    }

    /** TBODY **/
    thead.stick-datatable~tbody td:nth-child(1) {
        width: 440px !important;
        *border: 1px solid yellow;
    }
</style>




<script type="text/javascript" language="javascript" class="init">
    var oTable;
    var total;  
    var reqIds;
    $(document).ready(function() {
            reqIds =$("#reqId").val();
        if(reqIds==''){
            reqIds=-1;
        }
        oTable = $('#example').dataTable({
            bJQueryUI: true,
            "iDisplayLength": 10,
            /* UNTUK MENGHIDE KOLOM ID */
            "aoColumns": [{
                    bVisible: false
                },
                <?
                for ($i = 1; $i < count($aColumns)-1; $i++) {
                    ?>
                   null,
                    <?
                 }
                ?>
                 null
            ],
            "bSort": true,
            "bProcessing": true,
            "bServerSide": false,
            "bScrollInfinite": true,
            "sAjaxSource": "web/master_lokasi_json/json?reqModes=<?=$reqModes?>&reqId="+reqIds,
            "fnServerParams": function ( aoData ) {
                    // aoData.push( { "name": "input1", "value": $("#input1").val() } );
                    // aoData.push( { "name": "input2", "value": $("#input2").val() } );
                    // aoData.push( { "name": "input3", "value": $("#input3").val() } );
                },
            columnDefs: [{
                className: 'never',
                targets: [0]
            }],
             "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
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
            elements  = oTable.fnGetData(anSelected[0]);
            anSelectedId = element[0];
        });

        $('#btnAdd').on('click', function() {
            // document.location.href = "app/index/cash_report_add";

            $('#btnProses').show();
            $('#btnProses').html('Add');
            clearForm();

        });

        $('.editing').on('click', function() {
            if (anSelectedData == "")
                return false;
            // document.location.href = "app/index/cash_report_add?reqId=" + anSelectedId;
            // alert(anSelectedData);
            
          
            


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

<style type="text/css">
    #tablei tr td{
        padding: 5px;
        font-weight: bold;
        color: white;
    }
</style>
<div class="col-md-12">

    
    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        
        <div class="konten-area">
            <div class="konten-inner">
                <div>
                     <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
                       <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Add Master Lokasi</h3>
                     </div>
                     <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Nama</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-validatebox form-control" style="width:100%" id="reqCode" name="reqCode" value="<?=$reqCode?>" />

                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Keterangan</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <textarea class="form-control" style="width:100%" id="reqKeterangan" name="reqKeterangan" ><?=$reqKeterangan?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-2"></div>
                        <div class="col-md-4">
                            <button type="button" onclick="submitForm()" class="btn btn-primary">Simpan</button>        
                        </div>
                    </div>
                    <div style="text-align:center;padding:5px">

                        <input type="hidden" name="reqId" id="reqId" value="">
                        <!-- 
                         <button type="Submit" id="submitss" class="btn btn-primary"><i class="fa fa-fw fa-send"></i> Submit  </button> -->
                        <!-- <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Close</a> -->
                        <!-- <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> 
                        Submit</a> -->
                        
                    </div>
                     <div class="form-group">

                         <div >
                             <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <?php
                                        for ($i = 1; $i < count($aColumns); $i++) {
                                        
                                            ?>
                                            <th><?= str_replace('_', ' ', $aColumns[$i])  ?></th>
                                            <?php
                                        }
                                
                                    ?>
                                    </tr>
                                </thead>
                            </table>

                        </div>
                    </form>

                   
                </div>
            </div>
        </div>

       
    </div>
    <script type="text/javascript">
        
      function selecteds(id){
        // console.log('Arik data nama');
            var elements  = oTable.fnGetData(id);
            parent.selected_one(elements);
            parent.closePopup();
      }

       function deleting(id){
           var elements  = oTable.fnGetData(id);
                   var kata =  '<b>Detail </b><br>'+elements[2]+"  ";
                    var delele_link='web/master_lokasi_json/delete';
                  $.get(delele_link+"?reqId="+elements[0], function (data) {
                   oTable.api().ajax.reload(null,false);
                   show_toast('warning','Success delete row',kata +data);      
               });

               }
      
         function submits(){
            $("#submitss").click();
        }

        function editing(id) {
            var elements  = oTable.fnGetData(id);
            $("#reqId").val(elements[0]);
            $("#reqCode").val(elements[1]);
             $("#reqKeterangan").val(elements[2]);

            // window.location.href = "app/loadUrl/app/template_add_team?reqId=<?= $reqId ?>&reqSoTeamId="+elements[0];
        }
    

    </script>
    <script type="text/javascript">
        function  company_pilihans(id,name){
        $("#reqCompanyName").val(name);
        $("#reqCompanyId").val(id);
        }

       
    </script>

    <script type="text/javascript">
        function deleteRowss(no){
            $('#A'+no).remove();
            var delele_link='web/so_team_json/delete';
            var jqxhr = $.get( delele_link+'?reqId='+no, function() {
                   
                })
        }
    </script>

</div>
 <!-- EMODAL -->
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
    <script type="text/javascript">
        function submitForm() {
        $('#ff').form('submit', {
            url: 'web/master_lokasi_json/add',
            onSubmit: function() {
                return $(this).form('enableValidation').form('validate');
            },
            success: function(data) {

                show_toast('info','Success  row',data);      
                window.location.reload();
            }
        });
    }

    function clearForm() {
        $('#ff').form('clear');
    }
    </script>
 <script>
        window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {

        $('#reqDocumentId').combobox({
            onSelect: function(param) {
                $.get("web/personal_kualifikasi_json/getPosition/"+param.id, function (data) {
                    $("#reqPosition").val(data)    
                });
           }
           });

    });
    
</script>

