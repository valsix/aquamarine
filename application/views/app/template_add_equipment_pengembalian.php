<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$aColumns = array("SO_EQUIP_PENGEMBALIAN_ID","SO_EQUIP_ID", "ID","CATEGORY","EQUIPMENT_NAME","SN","QTY","ITEM","OUT_CONDITION","IN_CONDITION","REMARK", "EDIT" );
$reqId = $this->input->get("reqId");
$reqSoEquipId = $this->input->get("reqSoEquipId");



$reqPengembalianId  = $this->input->get('reqPengembalianId');


$this->load->model("SoEquip");
$this->load->model("SoEquipPengembalian");






$so_equip = new SoEquipPengembalian();

$reqIdx = 61;
if($reqPengembalianId != "")
{
    $so_equip->selectByParamsMonitoringEquips(array("CAST(A.SO_EQUIP_PENGEMBALIAN_ID AS VARCHAR)"=>$reqPengembalianId));
    $so_equip->firstRow();

    $reqName                    = $so_equip->getField("EQUIP_NAME");
    $reqKategori                = $so_equip->getField("EC_NAME");
    $reqSerialNumber            = $so_equip->getField("SERIAL_NUMBER");
    $reqItem                    = $so_equip->getField("EQUIP_ITEM");
    $reqQty                     = $so_equip->getField("QTY");
    $reqOutCondition            = $so_equip->getField("OUT_CONDITION");
    $reqInCondition             = $so_equip->getField("IN_CONDITION");
    $reqRemark                  = $so_equip->getField("REMARK");
    $reqEquipId                 = $so_equip->getField("EQUIP_ID");
    $reqImagePath               = $so_equip->getField("PIC_PATH");
    $reqIdx                     = $so_equip->getField("SO_ID");
    $reqSoEquipPengembalianId  = $so_equip->getField("SO_EQUIP_PENGEMBALIAN_ID");
    $reqKodeBarcode            =$so_equip->getField("BARCODE");
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

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />


<script type="text/javascript" language="javascript" class="init">
    var oTable;
    var reqIds;
    var anSelectedData = '';
    var anSelectedId = '';
    var anSelectedDownload = '';
    var anSelectedPosition = '';
    var anIndex = '';
    $(document).ready(function() {
        reqIds = $("#reqId").val();
        if (reqIds == '') {
            reqIds = '-1';
        }
        oTable = $('#example').dataTable({
            bJQueryUI: true,
            "iDisplayLength": 0,
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
            "bPaginate": false,
            "bProcessing": true,
            "bServerSide": false,
            "bScrollInfinite": true,
            "sAjaxSource": "web/so_equip_pengembalian_json/json?reqId="+reqIds,
            "fnServerParams": function(aoData) {
                // aoData.push( { "name": "input1", "value": $("#input1").val() } );
                // aoData.push( { "name": "input2", "value": $("#input2").val() } );
                // aoData.push( { "name": "input3", "value": $("#input3").val() } );
            },
            columnDefs: [{
                className: 'never',
                targets: [0,1]
            }],
            "bStateSave": true,
            "fnStateSave": function(oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function(oSettings) {
                var data = localStorage.getItem('DataTables_' + window.location.pathname);
                return JSON.parse(data);
            },
            "sPaginationType": "full_numbers",
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;
                var intVal = function(i) {
                    // console.log(i);
                    return typeof i === 'string' ?
                        i.replaceAll('.', '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };
                var total = api
                    .column(6)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $("#reqTotal").val(FormatCurrencyBaru(total));

            }

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
            anIndex = anSelected[0];
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

        $("#reqKodeBarcode").on("keyup change", function(e) {
          ambilData();
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
        // $this->load->model("SoTeam");
        // $reqDocumentId = $this->input->post("reqDocumentId");
        // $reqId = $this->input->post("reqId");

        // if(!empty($reqId)){    
        // $so_team = new SoTeam();
        // $so_team->setField("SO_ID",$reqId);
        // $so_team->setField("DOCUMENT_ID",$reqDocumentId);
        // $so_team->insert();
        // }





       
?>


<script type="text/javascript">
    function clearForm(){
        top.closePopup();
    }
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
                        <h3><i class="fa fa-file-text fa-lg"></i> Add Equipement</h3>
                     </div>
                     <div class="row">
                        <div class="col-md-8">
                           <div class="form-group">
                            <label for="reqName" class="control-label col-md-2">Name</label>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="col-md-11">
                                        <table style="width: 100%">
                                        <tr>
                                            <td style="width: 90%"> 
                                            <input type="hidden" value="<?=$reqSoEquipId?>" name="reqSoEquipId" id="reqSoEquipId">
                                            <input type="hidden" value="<?=$reqEquipId?>" name="reqEquipId" id="reqEquipId" class="easyui-validatebox textbox form-control" required>
                                            <input type="hidden" value="<?=$reqIdx?>" name="reqId" id="reqId">
                                             <input type="hidden" value="<?=$reqSoEquipPengembalianId?>" name="reqSoEquipPengembalianId" id="reqSoEquipPengembalianId">
                                            <input type="text"  class="easyui-validatebox textbox form-control" id="reqName" name="reqName" value="<?= $reqName ?>" style=" width:100%" onclick="pilih_company()"/> 

                                            </td>
                                            <td style="width: 10%"> <button type="button" onclick="pilih_company()"class="btn btn-default" > ... </button> </td>
                                        </tr>
                                    </table>
                                     
                                 </div>
                             </div>
                         </div>
                     </div>
                      <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Barcode</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <input type="text" value="<?= $reqKodeBarcode ?>" name="reqKodeBarcode" id="reqKodeBarcode" class="easyui-validatebox textbox form-control" placeholder="Scan Barcode disini ">
                               </div>
                           </div>
                       </div>
                   </div>
                     <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Categori</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <input type="text" value="<?= $reqKategori ?>" name="reqKategori" id="reqKategori" class="easyui-validatebox textbox form-control" readonly="">
                               </div>
                           </div>
                       </div>
                   </div>

                   <div class="form-group">
                      <label for="reqName" class="control-label col-md-2">Serial Number</label>
                      <div class="col-md-8">
                          <div class="form-group">
                              <div class="col-md-11">
                                    <input type="text" value="<?= $reqSerialNumber ?>" name="reqSerialNumber" id="reqSerialNumber" class="easyui-validatebox textbox form-control" readonly="">
                             </div>
                         </div>
                     </div>
                   </div>
                   <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Qty</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                   
                                   <input type="text" onkeypress='validate(event)'  class="easyui-validatebox textbox form-control" id="reqQty" name="reqQty" value="<?= $reqQty ?>" style=" width:30%" />
                               </div>
                           </div>
                       </div>
                   </div>
                   <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Item</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                   
                                   <input type="text"  class="easyui-validatebox textbox form-control" id="reqItem" name="reqItem" value="<?= $reqItem ?>" style=" width:100%" />
                               </div>
                           </div>
                       </div>
                   </div>
                    
                    <div class="form-group">
                        <label for="reqOutCondition" class="control-label col-md-2">Out Condition</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                     <input class="easyui-combobox form-control" style="width:100%" id="reqOutCondition" name="reqOutCondition" data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/equipmentStatus'" value="<?=$reqOutCondition?>" />

                                   
                               </div>
                           </div>
                       </div>
                   </div>
                   <div class="form-group">
                        <label for="reqInCondition" class="control-label col-md-2">In Condition</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <input class="easyui-combobox form-control" style="width:100%" id="reqInCondition" name="reqInCondition" data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/equipmentStatus'" value="<?=$reqInCondition?>" />

                                   
                               </div>
                           </div>
                       </div>
                   </div>
                   <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Remark</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-11">
                                   
                                   <input type="text"  class="easyui-validatebox textbox form-control" id="reqRemark" name="reqRemark" value="<?= $reqRemark ?>" style=" width:100%" />
                               </div>
                           </div>
                       </div>
                   </div>
                        </div>
                         <div class="col-md-4" style="text-align: center;padding-left: 0;margin: 0">
                            <div class="form-group">
                                  <div class="col-md-12">
                            <div class="panel panel-default" style="padding: 50px;border: 2px solid black;
  border-radius: 25px;">
                            <img id="equipment-image" src="uploads/equipment/<?=$reqImagePath?>" style="height: 50%;width:100%;"></div>
                        </div>
                            </div>
                          </div>
                      
                  </div>


                    <div style="text-align:center;padding:5px">
                       

                        <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Close</a>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
                    </div>
                  
                </form>
                 <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Equipment List</h3>
                    </div>
                <br>
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
 <script>
        function submitForm() {
            var vals =$("#reqEquipId").val();
            if(vals==''){
              show_toast('warning','Information','Belum ada item terpilih'); 
              // return ;
             }
            $('#ff').form('submit', {
                url: 'web/so_equip_pengembalian_json/add',
                onSubmit: function(data) {
                    // console.log(data);return false;
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    // console.log(data);return false;
                  reseti();
                    show_toast('info','Information',data);   
                  
                }
            });
        }

        
    </script>

   
<script type="text/javascript">
     function editing(id){
              
              window.location.href='app/loadUrl/app/template_add_equipment_pengembalian?reqPengembalianId='+id;
            // var elements  = oTable.fnGetData(id);
            
            
            //  $("#reqSoEquipPengembalianId").val(elements[0]);
            // $("#reqSoEquipId").val(elements[1]);
            // $("#reqName").val(elements[7]);
            // $("#reqItem").val(elements[8]);
            // $("#reqQty").val(elements[11]);
            // $("#reqOutCondition").combobox('setValue',elements[5]);
            // $("#reqCondition").combobox('setValue',elements[6]);
            // $("#reqRemark").val(elements[10]);
            // $("#reqEquipId").val(elements[3]);
            // $("#reqKategori").val(elements[2]);
            // $("#equipment-image").attr("src","uploads/equipment/"+elements[4]);
          
                
        }
     function deleting(id){
                

        $.messager.confirm('Konfirmasi','Yakin menghapus data terpilih ?',function(r){
            if (r){
                var jqxhr = $.get( "web/so_equip_pengembalian_json/delete?reqId="+id, function(data) {
                   oTable.api().ajax.reload(null,false);
                      show_toast('warning','Success delete row',kata);     
                })
                .done(function() {
                    //document.location.reload();
                })
                .fail(function() {
                    alert( "error" );
                });                             
            }
        });             

                  
               
              }

    function reseti(){
         oTable.api().ajax.reload(null,false);

         $("#reqSoEquipId").val('');
         $("#reqName").val('');
         $("#reqItem").val('');
         $("#reqQty").val('');
         $("#reqId").val('');
         $('#reqSoEquipPengembalianId').val(''); 
         $("#reqOutCondition").combobox('setValue','');
         $("#reqInCondition").combobox('setValue','');
         $("#reqRemark").val('');
         $("#reqEquipId").val('');
         $("#reqKategori").val('');
         $("#reqSerialNumber").val('');
         $("#reqKodeBarcode").val('');
          
    }
    function pilih_company(){
        
          openAdd('app/loadUrl/app/template_stock?reqModes=SOEQUIPS-<?=$reqIdx?>');
    }
    function ambilStock(elements){
        console.log(elements)
        // $('#reqName').val(elements[4]);
        // $('#reqItem').val(elements[16]);
        // $('#reqQty').val(elements[15]);
        $("#reqEquipId").val(elements[0]);
        // $("#reqKategori").val(elements[3]);
        // $("#reqSerialNumber").val(elements[6]);
        //  $("#reqOutCondition").combobox('setValue',elements[10]);
        // $("#reqRemark").val(elements[6]);
        // $("#equipment-image").attr("src","uploads/equipment/"+elements[17]);
        ambilData(); 
    }

    function ambilData(){
        var reqEquipId = $("#reqEquipId").val();
         var param = $("#reqKodeBarcode").val();
        $.post( "web/so_equip_pengembalian_json/ambilData", { reqParam: param,reqEquipId: reqEquipId, reqId: '<?=$reqIdx?>' })
            .done(function( data ) {
               var datas = JSON.parse(data);
                  $('#reqName').val(datas.EQUIP_NAME);
               $('#reqItem').val(datas.ITEM);
                 $('#reqKodeBarcode').val(datas.BARCODE);
                 $('#reqSoEquipId').val(datas.SO_EQUIP_ID);
                 $('#reqSoEquipPengembalianId').val(''); 
              $('#reqQty').val(datas.QTY);
               $("#reqEquipId").val(datas.EQUIP_ID);
               $("#reqKategori").val(datas.EC_NAME);
               $("#reqSerialNumber").val(datas.SERIAL_NUMBER);
                $("#reqOutCondition").combobox('setValue',datas.OUT_CONDITION);
                $("#reqRemark").val(datas.REMARK);
                $("#equipment-image").attr("src","uploads/equipment/"+datas.PIC_PATH);
            });
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
 <script>
        window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="libraries/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="libraries/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>

