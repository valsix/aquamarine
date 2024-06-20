<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$reqId = $this->input->get("reqId");


$this->load->model("SoEquip");

$reqIds        = $this->input->post("reqIds");
$reqName        = $this->input->post("reqName");
$reqDescription = $this->input->post("reqDescription");
$reqItem        = $this->input->post("reqItem");
$reqQty         = $this->input->post("reqQty");
$reqCondition   = $this->input->post("reqCondition");
$reqRemark      = $this->input->post("reqRemark");
$reqEquipId      = $this->input->post("reqEquipId");

if(!empty($reqIds)){
$so_equip = new SoEquip();
$so_equip->setField("SO_ID",$reqIds);
$so_equip->setField("EQUIP_ID",$reqEquipId);
$so_equip->setField("EQUIP_QTY",$reqQty);
$so_equip->setField("OUT_CONDITION",$reqCondition );
$so_equip->setField("IS_BACK",0);
$so_equip->setField("REMARK",$reqRemark);
$so_equip->insert();
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
<?
$aColumns = array('ID', 'KATEGORI',  'NAME', 'ITEM', 'SPEC', 'CONDITION', 'STOCK', 'PIC_PATH');
?>
<script type="text/javascript" language="javascript" class="init">
    var oTable;
    $(document).ready(function() {

        oTable = $('#example').dataTable({
            bJQueryUI: true,
            "iDisplayLength": 10,
            /* UNTUK MENGHIDE KOLOM ID */
            "aoColumns": [{
                    bVisible: false
                },
                null,
                null,
                null,
                null,
                null,
                null,
               
                null
            ],
            "bSort": true,
            "bProcessing": true,
            "bServerSide": true,
             "bAutoWidth": false,
             "aoColumns" : [
            { sWidth: '50px' },
            { sWidth: '50px' },
            { sWidth: '120px' },
            { sWidth: '30px' }
        ]  ,
            "sAjaxSource": "web/equipment_list_json/json?<?=$add_str?>",
            columnDefs: [{
                className: 'never',
                targets: [0,1,3,4,5,7]
            }],
            "sPaginationType": "full_numbers"

        });
        /* Click event handler */

        /* RIGHT CLICK EVENT */
        var anSelectedData = '';
        var anSelectedId = '';
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
            var element = anSelectedData.split(',');
            anSelectedId = element[0];
        });

        $('#btnAdd').on('click', function() {
            document.location.href = "app/index/offer_add";

        });

         $('#btnExcel').on('click', function() {
             openAdd('app/loadUrl/app/excel_offer');
        });
         $('#btnPrint').on('click', function() {
             openAdd('report/index/report_cetak_offer_pdf?reqId=' + anSelectedId);
        });

        $('#btnEdit').on('click', function() {
            if (anSelectedData == "")
                return false;
            document.location.href = "app/index/offer_add?reqId=" + anSelectedId;

        });

        $('#btnDelete').on('click', function() {
            if (anSelectedData == "")
                return false;

            deleteData("web/offer_json/delete", anSelectedId);

        });
        $('#btnRefresh').on('click', function() {
           

            Refresh();

        });

        

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

<?
        $this->load->model("SoTeam");
        $reqDocumentId = $this->input->post("reqDocumentId");
        $reqIds = $this->input->post("reqIds");

        if(!empty($reqIds)){    
        $so_team = new SoTeam();
        $so_team->setField("SO_ID",$reqIds);
        $so_team->setField("DOCUMENT_ID",$reqDocumentId);
        $so_team->insert();
        }





       
?>
<style type="text/css">
    #tablei tr td{
        padding: 5px;
        font-weight: bold;
        color: white;
    }
      #tableis tr td{
        padding: 5px;
        font-weight: bold;
        color: white;
    }
    #tableiss tr td{
        padding: 5px;
        font-weight: bold;
        color: white;
    }
</style>
</style>

<div class="col-md-12">

    <div class="judul-halaman">Team Service Order </div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        
        <div class="konten-area">
            <div class="konten-inner">
                <div>
                    <form class="form-horizontal" method="post" novalidate enctype="multipart/form-data">
                       <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Add Equipement</h3>
                     </div>
                     <br>

                        <table style="width: 100%" id="tableis">
                            <tr>
                                <td style="width: 10%"> Equipment Name </td>
                                 <td style="width: 30%"> 
                                  <input type="text" id="reqName" class="easyui-validatebox textbox form-control" name="reqName" value="<?= $reqEmail ?>" style=" width:90%" />
                                  <input type="hidden" value="" name="reqSoEquipId" id="reqSoEquipId">
                                  <input type="hidden" value="" name="reqEquipId" id="reqEquipId">
                                   <input type="hidden" value="<?=$reqId?>" name="reqIds" id="reqIds">
                                   </td>
                                  
                                   <td style="width: 20%" rowspan="6" valign="top">
                                    <div style="background: white;height: auto;color: black;height: 360px;width: 300px;border: 1px solid black;padding: 20px" >
                                    <img src="images/icon-user-login.png" style="height: 100%;width: 100%">
                                    </div>
                                    </td>
                                    <td style="width: 40%" rowspan="6" valign="top">
                                       <div id="bluemenu">
                                        <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" style="width: 300px">
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

                                     </td>

                            </tr>
                            <tr>
                                <td> Description </td>
                                <td><input type="text" id="reqDescription" class="easyui-validatebox textbox form-control" name="reqDescription" value="<?= $reqDescription ?>" style=" width:90%" /> </td>
                            </tr>
                            <tr>
                                <td> Item </td>
                                <td><input type="text" id="reqItem" class="easyui-validatebox textbox form-control" name="reqItem" value="<?= $reqItem ?>" style=" width:50%" /> </td>
                            </tr>
                            <tr>
                                <td> Qty </td>
                                <td><input type="number" id="reqQty" class="easyui-validatebox textbox form-control" name="reqQty" value="<?= $reqQty ?>" style=" width:30%" /> </td>
                            </tr>
                            <tr>
                                <td> Condition </td>
                                <td><input type="text" id="reqCondition" class="easyui-validatebox textbox form-control" name="reqCondition" value="<?= $reqCondition ?>" style=" width:90%" /> </td>
                            </tr>
                            <tr>
                                <td> Remark </td>
                                <td><input type="text" id="reqRemark" class="easyui-validatebox textbox form-control" name="reqRemark" value="<?= $reqRemark ?>" style=" width:90%" /> </td>
                            </tr>
                            
                        </table>
                    
                    <div style="text-align:center;padding:5px">
                          <button type="button" onclick="submits()" class="btn btn-primary">Simpan </button>
                         

                        <input type="hidden" name="reqIds" value="<?=$reqId?>">   
                         <button type="Submit" id="submitss" class="btn btn-primary"><i class="fa fa-fw fa-send"></i> Submit  </button>
                        <!-- <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Close</a> -->
                        <!-- <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> 
                        Submit</a> -->
                        
                    </div>
                    </form>

                    <table class="table  w-auto" id="tablei">
                        <thead>
                            <tr>
                                <th style="width: 15"> Name </th>
                                <th style="width: 10"> Item </th>
                                <th style="width: 25"> Conditon </th>
                                <th style="width: 10"> Qty </th>
                                <th style="width: 30"> Remark </th>
                                <th style="width: 10;text-align: center;">Aksi </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                                
                                $so_equip = new SoEquip();
                                $so_equip->selectByParamsMonitoringEquips(array("A.SO_ID"=>$reqId));
                                // $no=1;
                                while ( $so_equip->nextRow()) {
                                       $no = $so_equip->getField("SO_EQUIP_ID");
                                
                            ?>
                            <tr id="A<?=$no?>">
                                <td><?=$so_equip->getField("EQUIP_NAME")?></td>
                                 <td><?=$so_equip->getField("EQUIP_ITEM")?></td>
                                 <td><?=$so_equip->getField("EQUIP_CONDITION")?> </td>
                                  <td><?=$so_equip->getField("QTY")?> </td>
                                   <td><?=$so_equip->getField("REMARK")?> </td>
                                 <td align="center"> <a onclick="deleteRowss(<?=$no?>)" ><i class="fa fa-trash fa-lg"></i></a>  </td>
                            </tr>
                            <?
                                // $no++;
                                }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

       
    </div>
    <script type="text/javascript">
        
       function openPersonil(){
             openAdd('app/loadUrl/app/template_load_personil');
       }
         function submits(){
            $("#submitss").click();
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

        function clickDetail(id){
            var  urls ='web/equipment_list_json/detail_rows';
             var jqxhr = $.get( urls+'?reqId='+id, function(data) {
                var obj = JSON.parse(data);
                $('#reqEquipId').val(obj.ID);
                $('#reqName').val(obj.NAME);
                // $('#reqDescription').val(obj.);
                $('#reqItem').val(obj.ITEM);
                $('#reqQty').val(obj.STOCK);
                 $('#reqCondition').val(obj.CONDITION);
                
           
                    
                });

        }
    </script>

</div>
 <!-- EMODAL -->
    <script src="libraries/emodal/eModal.js"></script>
    <script src="libraries/emodal/eModal-cabang.js"></script>

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

