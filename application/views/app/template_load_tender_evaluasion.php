<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("TenderEvaluation");
$this->load->model('MasterTenerMenus');
$this->load->model("TenderEvaluationDetail");
$this->load->model("MasterTenderPeriode");
$reqPeriode   =  $this->input->get("reqPeriode");
$reqId   =  $this->input->get("reqDetailId");

$master_tender_periode = new MasterTenderPeriode();
$master_tender_periode->selectByParamsMonitoring(array("A.TAHUN"=>$reqPeriode));
$master_tender_periode->firstRow();
$reqPeriodeId =$master_tender_periode->getField('MASTER_TENDER_PERIODE_ID');
if(empty($reqPeriodeId)){
$master_tender_periode = new MasterTenderPeriode();
$master_tender_periode->setField("TAHUN",$reqTahun);
$master_tender_periode->insert();
$reqPeriodeId = $master_tender_periode->id;
} 


$master_tener_menus = new MasterTenerMenus();
$master_tener_menus->selectByParamsMonitoring(array());
$attData= array();
$attDataId= array();
while ( $master_tener_menus->nextRow()) {
    array_push($attData , strtoupper($master_tener_menus->getField('NAMA')));

    $attDataId[strtoupper($master_tener_menus->getField('NAMA'))]= $master_tener_menus->getField("MASTER_TENDER_MENUS_ID");
}

$tender_evaluation = new TenderEvaluation();
$tender_evaluation->selectByParamsMonitoring(array("CAST(A.TENDER_EVALUATION_ID AS VARCHAR)"=>$reqId,"CAST(A.MASTER_TENDER_PERIODE_ID AS VARCHAR)"=>$reqPeriodeId));
$tender_evaluation->firstRow();
$reqId                      = $tender_evaluation->getField("TENDER_EVALUATION_ID");
$reqMasterTenderPeriodeId   = $tender_evaluation->getField("MASTER_TENDER_PERIODE_ID");
$reqIndex                   = $tender_evaluation->getField("INDEX");
$reqMasterPscId             = $tender_evaluation->getField("MASTER_PSC_ID");
$reqTitle                   = $tender_evaluation->getField("TITLE");
$reqTenderNo                = $tender_evaluation->getField("TENDER_NO");
$reqClosing                 = $tender_evaluation->getField("CLOSING");
$reqOpening                 = $tender_evaluation->getField("OPENING");
$reqStatus                  = $tender_evaluation->getField("STATUS");
$reqOwner                   = $tender_evaluation->getField("OWNER");
$reqBidValue                = $tender_evaluation->getField("BID_VALUE");
$reqTkdn                    = $tender_evaluation->getField("TKDN");
$reqBidBouds                = $tender_evaluation->getField("BID_BOUDS");
$reqBidValidaty             = $tender_evaluation->getField("BID_VALIDATY");
$reqNotes                   = $tender_evaluation->getField("NOTES");
$reqBidCur                  = $tender_evaluation->getField("CUR_BID");
$reqOwnerCur                = $tender_evaluation->getField("CUR_OWNER");

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


<?php
// Header Nama TABEL TH
$aColumns = array(
    "EMAIL_ID", "SUBJECT"
);

?>



<div class="col-md-12">

    

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
         <div id="bluemenu" class="aksi-area">
            <!-- <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Pilih</a></span> -->
          
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
        </div>
       <div class="konten-area">
        <div class="konten-inner">
            <div>
        <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">
            <div class="page-header">
                <h3><i class="fa fa-file-text fa-lg"></i> Detail Tender Evalution</h3>
            </div>
            <div class="form-group">
                <label for="reqName" class="control-label col-md-2">Index</label>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-11">
                            <input type="text" class="easyui-validatebox textbox form-control" name="reqIndex" id="reqIndex" value="<?= $reqIndex ?>" style=" width:100%" />
                        </div>
                    </div>
                </div>
                <label for="reqName" class="control-label col-md-2">PSC Name</label>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-11">
                            
                              <input class="easyui-combobox form-control" style="width:100%" id="reqMasterPscId" name="reqMasterPscId" data-options="width:'300',editable:false, valueField:'id',textField:'text',url:'combo_json/combo_pcs'" value="<?= $reqMasterPscId ?>" required />

                        </div>
                    </div>
                </div>
            </div>
              <div class="form-group">
                <label for="reqName" class="control-label col-md-2">Tender Title</label>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-11">
                            <textarea type="text" class="easyui-validatebox textbox form-control" name="reqTitle" id="reqTitle" value="<?= $reqName ?>" style=" width:100%" cols="2" rows="2"><?=$reqTitle?></textarea>
                        </div>
                    </div>
                </div>
                <label for="reqName" class="control-label col-md-2">Tender No</label>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-11">
                            <input type="text" class="easyui-validatebox textbox form-control" name="reqTenderNo" id="reqTenderNo" value="<?= $reqTenderNo ?>" style=" width:100%" />
                        </div>
                    </div>
                </div>
            </div>
             
                <div class="form-group">
                <label for="reqName" class="control-label col-md-2">Closing Date/1st Opening</label>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-11">
                            <input type="text" class="easyui-datebox textbox form-control" name="reqClosing" id="reqClosing" value="<?= $reqClosing ?>" style=" width:300px" />
                        </div>
                    </div>
                </div>
                <label for="reqName" class="control-label col-md-2">2nd Opening Date</label>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-11">
                            <input type="text" class="easyui-datebox textbox form-control" name="reqOpening" id="reqOpening" value="<?= $reqOpening ?>" style=" width:300px" />
                        </div>
                    </div>
                </div>
            </div>
          <div class="page-header">
                <h3><i class="fa fa-file-text fa-lg"></i>  Tender Evalution Presentase</h3>
         </div>
         <table class="table table-striped table-hover dt-responsive">
            <thead>
                <tr>
             <?
             for($i=0;$i<count($attData);$i++){
            ?>
            <th> <?=$attData[$i]?></th>
            <?
             }   
             ?>
            </tr>
             </thead>
             <tbody>
                 <tr>
                     <?
             for($i=0;$i<count($attData);$i++){

                $tender_evaluation_detail = new TenderEvaluationDetail();
                $tender_evaluation_detail->selectByParamsMonitoring(array("CAST(A.TENDER_EVALUTATION_ID AS VARCHAR)"=>$reqId,"A.MASTER_TENDER_MENUS_ID"=>$attDataId[$attData[$i]]));
                $tender_evaluation_detail->firstRow();
                $nilai = $tender_evaluation_detail->getField('NILAI');
            ?>
            <td> <input type="hidden" name="reqMenuId[]" value="<?=$attDataId[$attData[$i]]?>"><input type="text" class="form-control" name="reqValueMenu[]" onkeypress='validate(event)' value="<?=$nilai?>" size="4" maxlength="3" onchange="changeHandler(this)"></td>
            <?
             }   
             ?>
                   
                 </tr>
             </tbody>
         </table>
         <div class="page-header">
            <h3><i class="fa fa-file-text fa-lg"></i>  Tender Evalution Description</h3>
        </div>
         <div class="form-group">
                <label for="reqName" class="control-label col-md-2">Failed/Decline</label>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-11">
                           

                             <input class="easyui-combobox form-control" style="width:100%" id="reqStatus" name="reqStatus" data-options="width:'300',editable:false, valueField:'id',textField:'text',url:'combo_json/combo_fail'" value="<?= $reqStatus ?>"  />

                        </div>
                    </div>
                </div>
                <label for="reqName" class="control-label col-md-2">Owners Estimate</label>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-11">
                             <input class="easyui-combobox form-control" style="width:90px" id="reqOwnerCur" name="reqOwnerCur" data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="<?= $reqOwnerCur ?>" />
                            <input type="text" class="easyui-validatebox textbox form-control" name="reqOwner" id="reqOwner" value="<?= currencyToPage2($reqOwner) ?>" style=" width:50%"  onchange="numberWithCommas('reqOwner')" onkeyup="numberWithCommas('reqOwner')" />
                        </div>
                    </div>
                </div>
            </div>
             <div class="form-group">
                <label for="reqName" class="control-label col-md-2">Bid Value</label>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-11">
                             <input class="easyui-combobox form-control" style="width:90px" id="reqBidCur" name="reqBidCur" data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueDollar'" value="<?= $reqBidCur ?>" />
                            <input type="text" class="easyui-validatebox textbox form-control" name="reqBidValue" id="reqBidValue" value="<?= currencyToPage2($reqBidValue) ?>" style=" width:50%" 
                            onchange="numberWithCommas('reqBidValue')" onkeyup="numberWithCommas('reqBidValue')"

                            />
                        </div>
                    </div>
                </div>
                <label for="reqName" class="control-label col-md-2">% TKDN</label>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-11">
                            <input type="text" class="easyui-validatebox textbox form-control" name="reqTkdn" id="reqTkdn" value="<?= $reqTkdn ?>" style=" width:100%" />
                        </div>
                    </div>
                </div>
            </div>
             <div class="form-group">
                <label for="reqName" class="control-label col-md-2">Bid Bond Value</label>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-11">
                            
                            <input type="text" class="easyui-validatebox textbox form-control" name="reqBidBouds" id="reqBidBouds" value="<?= currencyToPage2($reqBidBouds) ?>"  onchange="numberWithCommas('reqBidBouds')" onkeyup="numberWithCommas('reqBidBouds')" style=" width:100%" />
                        </div>
                    </div>
                </div>
                <label for="reqName" class="control-label col-md-2">Bid Validity (days)</label>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-11">
                            <input type="text" class="easyui-validatebox textbox form-control" name="reqBidValidaty" id="reqBidValidaty" value="<?= $reqBidValidaty ?>" style=" width:100%" />
                        </div>
                    </div>
                </div>
            </div>
              <div class="form-group">
                <label for="reqName" class="control-label col-md-2">Notes</label>
                <div class="col-md-10">
                    <div class="form-group">
                        <div class="col-md-11">
                            <textarea type="text" class="easyui-validatebox textbox form-control tinyMCES" name="reqNotes" id="reqNotes" value="<?= $reqNotes ?>" style=" width:100%" > <?=$reqNotes?></textarea>
                        </div>
                    </div>
                </div>
              
            </div>
        <div style="text-align:center;padding:5px">
             <input type="hidden" name="reqId" id="reqId" value="<?=$reqId ?>" />
             <input type="hidden" name="reqPeriode" id="reqPeriode" value="<?=$reqPeriodeId ?>" />
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Close</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>

        </form>
</div>
</div>
</div>
            

    </div>
   

</div>
<script type="text/javascript">
    
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/tender_evaluation_json/addDetail',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                  

        // $.messager.alertLink('Info', datas[2], 'info', "app/loadUrl/app/template_load_vessel?reqId="+datas[0]+"&reqCompanyId="+datas[1]);
        clearForm() ;
                   
                }
            });
        }

        function clearForm() {
            top.reload_table();
            top.closePopup();
        }
    
</script>
<script type="text/javascript">
     function changeHandler(val)
  {
    if (Number(val.value) > 100)
    {
      val.value = 100
    }
  }
</script>
 <script>
        window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>

