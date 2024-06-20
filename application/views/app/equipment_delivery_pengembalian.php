<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$aColumns = array("SO_EQUIP_ID", "CATEGORY","EQUIPMENT_NAME","SN","QTY","ITEM","OUT_CONDITION","IN_CONDITION","REMARK", "EDIT" );
 $this->load->model("SoEquip");
$this->load->model("Service_order");
$this->load->model("SoEquipPengembalian");
$service_order = new Service_order();

$reqId = $this->input->get("reqId");

$this->load->model("SoEquip");
$so_equip = new SoEquip();
// $so_equip->delete_from_flag();

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $service_order->selectByParamsMonitoring(array("A.SO_ID" => $reqId));
    // echo $service_order->query;exit;
    $service_order->firstRow();
   
    $reqSoId            = $service_order->getField("SO_ID");
    $reqProjectName     = $service_order->getField("PROJECT_NAME");
    $reqNoDelivery      = $service_order->getField("NO_ORDER");
    $reqCompanyName     = $service_order->getField("COMPANY_NAME");
    $reqVesselName      = $service_order->getField("VESSEL_NAME");
    $reqVesselType      = $service_order->getField("VESSEL_TYPE");
    $reqSurveyor        = $service_order->getField("SURVEYOR");
    $reqDestination     = $service_order->getField("DESTINATION");
    $reqService         = $service_order->getField("SERVICE");
    $reqDateOfStart     = $service_order->getField("DATE_OF_START");
    $reqDateOfFinish    = $service_order->getField("DATE_OF_FINISH");
    $reqTransport       = $service_order->getField("TRANSPORT");
    $reqEquipment       = $service_order->getField("EQUIPMENT");
    $reqObligation      = $service_order->getField("OBLIGATION");
    $reqDateOfService   = $service_order->getField("DATE_OF_SERVICE");
    $reqPicEquip        = $service_order->getField("PIC_EQUIP");
    $reqContactPerson   = $service_order->getField("CONTACT_PERSON");
    $reqNoDelivery      = $service_order->getField("NO_DELIVERY");
    $reqPath      = $service_order->getField("PATH");

    
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />



<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/equipment_pengembalian"> Equipment Project List  </a> &rsaquo; Delivery Slip Pengembalian


         <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

         <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right:  10px" onclick="cetakPdf()"><i class="fa fa-file-pdf-o "> </i><span> Print</span> </a>
       
        
    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Data
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>
                        </h3>
                        <br>
                    </div>


                   
                    <div class="form-group">
                        <label for="reqTanggal" class="control-label col-md-2">Delivery No.</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <div class="input-group">
                                      <?=$reqNoDelivery?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                      <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />
                        <input type="hidden" value="<?=$reqId?>" name="reqIds" id="reqIds">

                
                  

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Detail Equipment Delivery</h3>
                    </div>
                    <br>


                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Project of Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <?= $reqProjectName ?>
                                  
                                </div>
                            </div>
                        </div>
                        <label for="reqTelephone" class="control-label col-md-2">Company of Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <?= $reqCompanyName ?>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                   
                   <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Type Of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <?= $reqVesselType ?>
                                   
                                </div>
                            </div>
                        </div>
                   
                        <label for="reqTelephone" class="control-label col-md-2">Name of Vessel</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <?= $reqVesselName ?>
                                   
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqInvoiceNumber" class="control-label col-md-2">Location</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <?= $reqDestination ?>
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>
                     <br>

                     <?
                     if(!empty($reqId)){
                     ?>
                    <table style="width: 100%" class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="80%"> File Name </th>
                                <th width="10%"> Type </th>
                                <th width="10%"> Action </th>
                            </tr>
                        </thead>
                        <tbody id="tambahAttacment">
                            <?
                            $files_data = explode(';',  $reqPath);

                            for ($i = 0; $i < count($files_data); $i++) {
                                if (!empty($files_data[$i])) {
                                    $texts = explode('-', $files_data[$i]);
                                    $ext = substr($files_data[$i], -3);
                            ?>
                                    <tr>

                                        <td>
                                            <input type="file" onchange="getFileName(this, '<?=($i+1)?>')" name="document[]" multiple class="form-control" style="width: 90%">
                                            <input type="hidden" name="reqLinkFileTemp[]" value="<?= $files_data[$i] ?>">
                                            <?if ($ext !=='pdf')
                                            {
                                            ?>
                                              <a href="uploads/equipment_delivery_slip/<?= $reqId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                              <a onclick="openAdd(`uploads/equipment_delivery_slip/<?= $reqId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFile<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                            <?
                                            }
                                            ?>   
                                        </td>
                                        <td><?=strtoupper($ext)?></td>
                                        <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                    </tr>
                            <?
                                }
                            }
                            ?>

                        </tbody>
                    </table>

                    <br>
                     <br>

                     <?
                                $soequippengembalian = new SoEquipPengembalian();
                                $total_kembali = $soequippengembalian->getCountByParamsMonitoring(array(
                                "A.SO_ID"=>$reqId,"A.FLAG"=>'1'));
                                   $so_equip = new SoEquip();
                                    $total = $so_equip->getCountByParamsMonitoring(array(
                                "A.SO_ID"=>$reqId));
                               
                     ?>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Equipment List

                            <div class="pull-right" style="margin-right:20px">
                                <b> TOTAL : (  <?=$total?>  ) </b> || 
                                 <b> RETURN :(  <?=$total_kembali?>  )</b>
                            </div>
                        </h3>
                    </div>
                    <div style="padding: 10px">
                        <div class="form-group ">
                            <div class="col-md-12">
                             
                          
                          
                                 &nbsp;&nbsp;
                            <input type="text"  class="easyui-validatebox textbox form-control" name="reqKodeBarcode" id="reqKodeBarcode" value="" style=" width:30%" placeholder="Scan Barcode di sini" />
                             
                            
                               <br>
                            <br>
                            <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th> ID </th>
                                         <th> CATEGORY </th>
                                         <th> EQUIPMENT NAME </th>
                                          <th> SN </th>
                                          <th> QTY </th>
                                           <th> ITEM </th>
                                             <th> OUT CONDITION </th>
                                             <th>  IN CONDITION</th>
                                               <th> REMARK</th>
                                                 <th> CHECK</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?
                                   
                                      $statement = " AND A.SO_ID ='".$reqId."'";
                                     $so_equip = new SoEquip();
                                    $so_equip->selectByParamsMonitoringEquips(array(),-1,-1,$statement);
                                    // echo $so_equip->query;exit;
                                    $no=0;
                                    $i=0;
                                    while($so_equip->nextRow()){
										
										
                                    
                                $soequippengembalian = new SoEquipPengembalian();
								$total = $soequippengembalian->getCountByParamsMonitoring(array("A.SO_EQUIP_ID"=>$so_equip->getField("SO_EQUIP_ID"),
								"A.EQUIP_ID"=>$so_equip->getField("EQUIP_ID"),
								"A.SO_ID"=>$reqId));
                                $soequippengembalian->selectByParamsMonitoring(array("A.SO_EQUIP_ID"=>$so_equip->getField("SO_EQUIP_ID"),"A.SO_ID"=>$reqId));
								//echo $soequippengembalian->query;exit;

							   $soequippengembalian->firstRow();
                                $reqRemark          = $soequippengembalian->getField("REMARK");
								  $reqSoEquipPengembalianId        = $soequippengembalian->getField("SO_EQUIP_PENGEMBALIAN_ID");
                                $reqInCondition     = $soequippengembalian->getField("IN_CONDITION");
                                $regFlag = $soequippengembalian->getField("FLAG");
								$regFlag = $reqSoEquipPengembalianId?$regFlag:'';
                                $checked = '';
                                $style ="style='display:none'";
                                if($regFlag=='1' && !empty($reqSoEquipPengembalianId) ){
                                    $checked = "checked";
                                     $style='';
									  $no++;   
                                }else{
									  $checked = '';
									   $style ="style='display:none'";
								}
                                $reqInCondition  = $reqInCondition ?$reqInCondition :$so_equip->getField("OUT_CONDITION");
                                    ?>
                                        <tr id="trId<?=$so_equip->getField("SO_EQUIP_ID")?>" class="trTable" <?=$style?>>
                                            <td> <?=$no?>   </td>
                                            <td><?=$so_equip->getField("EQUIP_ID")?> </td>
                                          
                                            <td> <?=$so_equip->getField("EC_NAME")?> </td>
                                            <td> <?=$so_equip->getField("EQUIP_NAME")?>  </td>
                                              <td><?=$so_equip->getField("SERIAL_NUMBER")?>  </td>
                                            <td> <?=$so_equip->getField("QTY")?> </td>
                                            <td><?=$so_equip->getField("ITEM")?>  </td>
                                            <td>
                                                <?=$so_equip->getField("OUT_CONDITION")?>
                                              </td>
                                            <td>  
                                               <input class="easyui-combobox form-control" style="width:100%" id="reqInCondition<?=$so_equip->getField("SO_EQUIP_ID")?>"  data-options="width:'90',editable:false, valueField:'id',textField:'text',url:'combo_json/equipmentStatus',
                                               onSelect: function(rec){
                                                  gantiFungsi(<?=$so_equip->getField("SO_EQUIP_ID")?>);
                                           }
                                               " value="<?=$reqInCondition?>" />
                                                </td>
                                            <td>   <input type="text"  class="easyui-validatebox textbox form-control" id="reqRemark<?=$so_equip->getField("SO_EQUIP_ID")?>"  name="reqItem" value="<?=$reqRemark?>" style=" width:100%" 
                                                onchange="gantiFungsi(<?=$so_equip->getField("SO_EQUIP_ID")?>)"

                                                /></td>
                                            <td>  <input type="checkbox" id="reqId<?=$so_equip->getField("SO_EQUIP_ID")?>"  value="<?=$so_equip->getField("SO_EQUIP_ID")?> " onchange="gantiFungsi(<?=$so_equip->getField("SO_EQUIP_ID")?>)" <?=$checked?>></td>
                                        </tr>
                                    <? 
                                   
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?
                    }
                    ?>
            </form>
        </div>


    </div>

</div>

<script>
    function submitForm() {
        $('#ff').form('submit', {
            url: 'web/equipment_delivery_slip_json/add',
            onSubmit: function() {
                return $(this).form('enableValidation').form('validate');
            },
            success: function(data) {
                // console.log(data);return false;
                var datas = data.split('-');
                // if (datas[1] != '') {
                //     $('#reqId').val(datas[1]);
                //     reqIds = datas[1];

              //   show_toast('info', 'Information',data);
                // <?
                // if(empty($reqId)){
                // ?>
                //     $.messager.alertLink('Info', datas[0], 'info', "app/index/equipment_delivery_slip/?reqId="+datas[0]);
                //  <?
                //  }   
                //  ?>   
                // } else {
                //     oTable.api().ajax.reload(null,false);
                //     show_toast('info', 'Information', datas[0]);

                // }
                // reload_detail();
                // reseti();
                 show_toast('info', 'Information', data);
            }
        });
    }

    // function clearForm() {
    //     $('#ff').form('clear');
    //     $('#reqTanggal').datebox('setValue', '<?= $reqTanggal ?>');  
    //     $('#reqId').val('<?= $reqId ?>');  

    // }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#btnProses').hide();
        // reload_detail();
    });
</script>

<script type="text/javascript">
    function cetakPdf(){
        openAdd("app/loadUrl/report/equpment_delevery_pdf?reqId=<?=$reqId?>");

    }

    function addEquipment() {
        if('<?=$reqId?>' == ''){
            $.messager.confirm('Confirm','Equipment Project List will be saved before adding equipment?',function(r){
                if (r){
                    submitForm();
                }
            });
        } else {
            openAdd('app/loadUrl/app/template_add_equipment?reqId=<?=$reqId?>');
        }
    }
    function loadEquipment() {
        openAdd('app/loadUrl/app/template_load_template_equipment?reqId=<?=$reqId?>');
    }
    function open_service_order() {
        openAdd('app/loadUrl/app/template_load_service_order');
    }
    function template_equipment_project(){
         openAdd('app/loadUrl/app/tempalate_master_so_template');
    }
    function addOWR(id, kode,name,type,surveyor,project,company,location) {
        $("#reqNoDelivery").val(kode);
        $("#reqVesselName").val(name);
        $("#reqVesselType").val(type);
        $("#reqProjectName").val(project);
        $("#reqCompanyName").val(company);
        $("#reqDestination").val(location);
        
        // $("#reqSurveyor").val(surveyor);
            // $("#reqServiceOrderId").val(id);
    }
    function pilih_project() {
        openAdd('app/loadUrl/app/template_load_offering');
    }
    function addOffering(id, kode,date,location,detail,cp,clvessel,nmvessel,tpvessel,company) {
        $("#reqNoDelivery").val(kode);
        $("#reqVesselName").val(nmvessel);
        $("#reqVesselType").val(tpvessel);
        $("#reqProjectName").val(detail);
        $("#reqCompanyName").val(company);
        $("#reqDestination").val(location);
    }
</script>

<script type="text/javascript">
    function deleting(id){
        deleteData_for_table('web/so_equip_json/delete_equipment', id, anIndex, 3);
    }
    function editing(id) {
        var elements = oTable.fnGetData(id);

        // $("#reqCostRequestDetailId").val(elements[0]);
        // $('#reqKeterangan2').val(elements[2]);
        // $("#reqCostCode").combobox('setValue', elements[3]);
        // $("#reqCostCodeCategori").val(elements[4]);
        // $("#reqEvidance").combobox('setValue', elements[5]);
        // $("#reqAmount").val(elements[6]);
        // $("#reqProject").val(elements[7]);
        // $("#reqPaidTo").val(elements[8]);

        openAdd("app/loadUrl/app/template_add_equipment?reqId=<?=$reqId?>&reqSoEquipId="+id);

    }

    function reseti() {
        oTable.api().ajax.reload(null,false);
        $("#reqCostRequestDetailId").val();
        $('#reqKeterangan2').val('');
        $("#reqCostCode").combobox('setValue', '');
        $("#reqCostCodeCategori").val('');
        $("#reqEvidance").combobox('setValue', '');
        $("#reqAmount").val('');
        $("#reqProject").val('');
        $("#reqPaidTo").val('');
      
    }

    function reload(id) {
        oTable.api().ajax.reload(null,false);
    }
</script>


<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript">
    $('#reqCostCode').combobox({
        onSelect: function(param) {
            var text = param.text;
            var datas = text.split('-');
            $("#reqCostCodeCategori").val(datas[1]);
        }
    });
     function tambahPenyebab(filename='') {
            var id = $('#tambahAttacment tr').length+1;
            $.get("app/loadUrl/app/tempalate_row_attacment?id="+id+"&filename="+filename, function(data) {
                $("#tambahAttacment").append(data);
            });
        }
</script>

<script type="text/javascript">
       $("#reqKodeBarcode").on("change", function(e) {
            var value = $("#reqKodeBarcode").val();
            $.post( "web/so_equip_pengembalian_json/ambilData", { reqParam: value, reqId: "<?=$reqId?>" })
            .done(function( data ) {
                  var datas = JSON.parse(data);
                  var equipId = datas.SO_EQUIP_ID;
                  if(equipId == '' || equipId == null){
                    show_toast('error', 'Information', 'Data gagal di simpan Barcode tidak di temukan');
                  }else{
                        $(".trTable").css("background-color","white");
                        $("#trId"+equipId).css("background-color","orange");
                          $("#trId"+equipId).css("background-color","orange");
                           $("#trId"+equipId).show();
                        var remember = document.getElementById("reqId"+equipId);

                        if (remember.checked) {
                        //   $('#reqId'+equipId).prop('checked', false); // Checks it
                        }else{
                           $('#reqId'+equipId).prop('checked', true); // Checks it
                        }
						show_toast('info', 'Information', 'Data berhasil di simpan');
                        gantiFungsi(equipId);
                        $("#reqKodeBarcode").val('');
                  }  


            });
        });

    function gantiFungsi(id){
        var in_conditon = $("#reqInCondition"+id).combobox('getValue');
        var remark = $("#reqRemark"+id).val();
        var remember = document.getElementById("reqId"+id);
        var checkbox =0;
        if (remember.checked) {
            checkbox =1;
        }else{
             checkbox =0;
        }
          $.post( "web/so_equip_pengembalian_json/add", { reqEquipId: id, reqId: '<?=$reqId?>',reqCheck:checkbox,reqInconditon:in_conditon,reqRemark:remark })
            .done(function( data ) {

            });

        
    }
      function cetakPdf(){
        openAdd("app/loadUrl/report/equpment_delevery_pengembalian_pdf?reqId=<?=$reqId?>");

    }
</script>
</div>
</div>