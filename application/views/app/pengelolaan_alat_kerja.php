<?php
$this->load->model('EquipmentList');
$equipmentlist = new EquipmentList();

// Header Nama TABEL TH
$aColumns = array(
            "EQUIP_ID","TGL","NAMA ALAT", "SPEC", "JUMLAH", "BAIK","RUSAK","LOKASI","KALIBRASI","STOCK"
        );

$arrPost = $this->input->post();
foreach ($arrPost as $key => $value) {
   $_SESSION[$pg.$key] =$value;
}

$reqCariIdNumber = $_SESSION[$pg."reqCariIdNumber"];
$reqCariCondition = $_SESSION[$pg."reqCariCondition"];
$reqCariCategori = $_SESSION[$pg."reqCariCategori"];
$reqCariStorage = $_SESSION[$pg."reqCariStorage"];
$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqCariIncomingDateFrom = $_SESSION[$pg."reqCariIncomingDateFrom"];
$reqCariIncomingDateTo = $_SESSION[$pg."reqCariIncomingDateTo"];
$reqCariItemFrom = $_SESSION[$pg."reqCariItemFrom"];
$reqCariItemTo = $_SESSION[$pg."reqCariItemTo"];
$reqCariLastCalibrationFrom = $_SESSION[$pg."reqCariLastCalibrationFrom"];
$reqCariLastCalibrationTo = $_SESSION[$pg."reqCariLastCalibrationTo"];
$reqCariQuantity = $_SESSION[$pg."reqCariQuantity"];
$reqCariNextCalibrationFrom = $_SESSION[$pg."reqCariNextCalibrationFrom"];
$reqCariNextCalibrationTo = $_SESSION[$pg."reqCariNextCalibrationTo"];
$reqCariSpesification = $_SESSION[$pg."reqCariSpesification"];

$statement_privacy = '';
        if (!empty($reqCariIdNumber)) {

            $statement_privacy  .= " AND A.EQUIP_ID = '" . strtoupper($reqCariIdNumber) . "' ";
        }
        if (!empty($reqCariCondition) && $reqCariCondition != "ALL") {

            $statement_privacy  .= " AND UPPER(A.EQUIP_CONDITION) LIKE '" .  strtoupper($reqCariCondition) . "%' ";
        }
        if (!empty($reqCariCategori) && $reqCariCategori != "ALL") {

            $statement_privacy  .= " AND A.EC_ID ='" .  strtoupper($reqCariCategori) . "' ";
        }
        if (!empty($reqCariStorage)) {

            $statement_privacy  .= " AND UPPER(A.EQUIP_STORAGE) LIKE '%" .  strtoupper($reqCariStorage) . "%' ";
        }
        if (!empty($reqCariCompanyName)) {

            $statement_privacy  .= " AND UPPER(A.EQUIP_NAME) LIKE '%" .  strtoupper($reqCariCompanyName) . "%' ";
        }
        if (!empty($reqCariIncomingDateFrom) && !empty($reqCariIncomingDateTo)) {

            $statement_privacy  .= " AND A.EQUIP_DATEIN BETWEEN  TO_DATE('" . $reqCariIncomingDateFrom . "','dd-mm-yyyy')  AND TO_DATE('" . $reqCariIncomingDateFrom . "','dd-mm-yyyy') ";
        }

        if (!empty($reqCariItemFrom) && !empty($reqCariItemTo)) {


            $statement_privacy  .= " AND A.EQUIP_QTY BETWEEN " . $reqCariItemFrom . " AND " . $reqCariItemTo;
        }

        if (!empty($reqCariLastCalibrationFrom) && !empty($reqCariLastCalibrationTo)) {

            $statement_privacy  .= " AND A.EQUIP_LASTCAL BETWEEN TO_DATE('" . $reqCariLastCalibrationFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariLastCalibrationTo . "','dd-mm-yyyy') ";
        }

        if (!empty($reqCariQuantity)) {

            $statement_privacy  .= " AND A.EQUIP_ITEM = '" . $reqCariQuantity . "' ";
        }
        if (!empty($reqCariNextCalibrationFrom) && !empty($reqCariNextCalibrationTo)) {
            $statement_privacy  .= " AND A.EQUIP_NEXTCAL BETWEEN TO_DATE('" . $reqCariNextCalibrationFrom . "','dd-mm-yyyy') AND TO_DATE('" . $reqCariNextCalibrationTo . "','dd-mm-yyyy') ";
        }

        if (!empty($reqCariSpesification)) {

            $statement_privacy  .= " AND UPPER(A.SERIAL_NUMBER) LIKE '%" .  strtoupper($reqCariSpesification) . "%' ";
        }

        if (!empty($reqExpired)) {

            $statement_privacy  .= " AND CERTIFICATE_EXPIRED_DATE < CURRENT_DATE ";
        }

        $reqKategoriId                       = $this->input->get("reqKategoriId");
        if (!empty($reqKategoriId)) {

            $statement_privacy  .= " AND A.EC_ID ='".$reqKategoriId."' ";
        }


?>
<script type="text/javascript" src="libraries/easyui/jquery.easyui.min.js"></script>

<script type="text/javascript">
     $(document).ready(function() {
       $('#btnMaster').on('click', function() {
            openAdd('app/loadUrl/app/tempalate_master_project');

        });
       $('#btnAdd').on('click', function() {
        window.location.href='app/index/pembelian_add'

    });
       });
</script>
<style type="text/css">
   .text-wrap{
    white-space:normal;
}
.biruMuda{
 /*background-color: #42f5dd !important; */
}
.biruMuda2{
 background-color: #42f5dd !important; 
}
</style>

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

<!-- <style>
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
</style> -->
<style type="text/css">
    #tablei tr td {
        padding: 5px;
        font-weight: bold;
    }
    .textWrap{

    width: 200px;
    word-wrap: break-word;
    white-space: normal;
    }
</style>

<!-- AREA FILTER TAMBAHAN -->
<script>
    $(document).ready(function() {
        $("button.pencarian-detil").click(function() {
            $(".area-filter-tambahan").toggle();
            $("i", this).toggleClass("fa-caret-up fa-caret-down");
        });
    });
</script>
<style>
    .area-filter-tambahan {
        display: none;
    }
     
   
</style>

<div class="col-md-12">

    <div class="judul-halaman"> Pengelolaan Alat Kerja</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
           
           
            
            <span><a id="btnRefresh2"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            
            <!-- <span><a id="btnPrint"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span> -->
            
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

       <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td>ID Number </td>
                                <td><input type="text" name="reqCariIdNumber" class="easyui-validatebox textbox form-control" id="reqCariIdNumber" value="<?= $reqCariIdNumber ?>" onkeypress='validate(event)' ></td>
                                <td>Condition </td>
                                <td><input class="easyui-combobox form-control" style="width:100%" name="reqCariCondition" id="reqCariCondition" data-options="width:'290', height: '36',editable:false, valueField:'id',textField:'text',url:'combo_json/equipmentStatus?reqMode=ALL'" id="reqCariCondition" value="<?= $reqCariCondition ?>" /></td>

                                <td>&nbsp;


                                </td>

                            </tr>
                            <tr>
                                <td>Categori </td>
                                <td><input class="easyui-combobox form-control" style="width:100%" name="reqCariCategori" data-options="width:'290', height: '36',editable:false, valueField:'id',textField:'text',url:'combo_json/comboEquipCategori?reqMode=ALL'" id="reqCariCategori" value="<?= $reqCariCategori ?>" /></td>
                                <td>Storage </td>
                                <td><input type="text" name="reqCariStorage" class="easyui-validatebox textbox form-control" id="reqCariStorage" value="<?=$reqCariStorage?>"></td>

                                <td>&nbsp;


                                </td>

                            </tr>
                            <tr>
                                <td>Equipment Name </td>
                                <td><input type="text" name="reqCariCompanyName" class="easyui-validatebox textbox form-control" id="reqCariCompanyName" value="<?= $reqCariCompanyName ?>"></td>
                                <td>Incoming Date </td>
                                <td><input type="text" name="reqCariIncomingDateFrom" id="reqCariIncomingDateFrom" class="easyui-datebox " value="<?=$reqCariIncomingDateFrom?>" data-options="width:'150', height: '36'"> To <input type="text" name="reqCariIncomingDateTo" id="reqCariIncomingDateTo" class="easyui-datebox " value="<?=$reqCariIncomingDateTo?>" data-options="width:'150', height: '36'"></td>

                                <td>&nbsp;


                                </td>

                            </tr>
                            <tr>
                                <td>Item </td>
                                <td><input style="width: 40%" type="text" name="reqCariItemFrom" class="easyui-validatebox textbox form-control" id="reqCariItemFrom" value=""> to <input type="text" style="width: 40%" name="reqCariItemTo" class="form-control" id="reqCariItemTo" value="<?= $reqCariItemTo ?>"></td>
                                <td>Last Calibration </td>
                                <td><input type="text" name="reqCariLastCalibrationFrom" id="reqCariLastCalibrationFrom" class="easyui-datebox " value="<?=$reqCariLastCalibrationFrom?>" data-options="width:'150', height: '36'"> To <input type="text" name="reqCariLastCalibrationTo" id="reqCariLastCalibrationTo" class="easyui-datebox " value="<?=$reqCariLastCalibrationTo?>" data-options="width:'150', height: '36'"></td>

                                <td>&nbsp;


                                </td>

                            </tr>
                            <tr>
                                <td>Quantity </td>
                                <td><input type="text" name="reqCariQuantity" class="easyui-validatebox textbox form-control" id="reqCariQuantity" value="<?= $reqCariQuantity ?>"></td>
                                <td>Next Calibration </td>
                                <td><input type="text" name="reqCariNextCalibrationFrom" id="reqCariNextCalibrationFrom" class="easyui-datebox " value="<?=$reqCariNextCalibrationFrom?>" data-options="width:'150', height: '36'"> To <input type="text" name="reqCariNextCalibrationTo" id="reqCariNextCalibrationTo" class="easyui-datebox " value="<?=$reqCariNextCalibrationTo?>" data-options="width:'150', height: '36'"></td>

                                <td>&nbsp;


                                </td>

                            </tr>
                            <tr>
                                <td>Serial No. </td>
                                <td colspan="2"><input type="text" name="reqCariSpesification" class="easyui-validatebox textbox form-control" id="reqCariSpesification" value="<?= $reqCariSpesification ?>"></td>
                                <td colspan="2"><button type="submit"   class="btn btn-default"> Searching </button>
 <button type="submit" style="display: none" id="btn_cari_filters" class="btn btn-default"> Searching </button></td>
                            </tr>
                        </table>
                    </form>

                </div>
            </div>
        </div>



        <table id="example2" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th rowspan="2" style="display: none;"># </th>
                    <th rowspan="2">No </th>
                            <th rowspan="2">Tanggal <br> Masuk </th>
                            <th rowspan="2" style="display: none;">Nama Alat </th>
                            <th rowspan="2">Nama Alat / Barcode </th>
                            <th rowspan="2">Serial Number </th>
                              <th rowspan="2"  width="200">Merk Speck </th>
                            <th rowspan="2">Jumlah </th>
                            <th colspan="3"> Status </th>
                            <th rowspan="2"> Lokasi </th>
                            <th  colspan="2">Status Kalibarasi </th>
                            <th colspan="2">Stock</th>
                           
                   
                </tr>
                <tr>
                    <th> Baik </th>
                     <th> Repair </th>
                    <th> Rusak </th>
                    <th> Last Calibration  </th>
                    <th> Next Calibration  </th>
                    <th> Baik   </th>
                    <th> Keluar   </th>
                </tr>
            </thead>
            <tbody>
                <?
             $equipmentlist = new EquipmentList();
             $equipmentlist->selectByParamsMonitoringStock(array(),-1,-1,$statement_privacy," ORDER BY equip_name ASC");
             $arrDataEquip = $equipmentlist->rowResult;
             $arrDataNamaEquip = array_column($arrDataEquip, 'equip_name');
             $arrDataNamaEquip = array_unique($arrDataNamaEquip);
             $no=1;
             foreach ($arrDataNamaEquip as  $value) {
                 $arrDataDetail = multi_array_search($arrDataEquip,array('equip_name'=>$value));
                 $arrDataQty = array_column($arrDataDetail, 'equip_qty');
                 $arrDataStok = array_column($arrDataDetail, 'equip_stok');
                   $arrDataStokKeluar = array_column($arrDataDetail, 'equip_keluar');
                 $arrDataCondition = array_column($arrDataDetail, 'equip_condition');
                 $arrDataLokasi = array_column($arrDataDetail, 'equip_storage');
                 $arrDataLokasi = array_unique($arrDataLokasi);
                 $arrDataLokasi = implode('<br>', $arrDataLokasi);
                 
                 $total = array_sum($arrDataQty);
                 $totalStok = array_sum($arrDataStok);
                 $totalStokKeluar = array_sum($arrDataStokKeluar);

                 $total_G=$total_R=$total_B=0;
                 foreach ($arrDataCondition as $val) {

                    if(strtoupper($val) == 'GOOD'){
                             $total_G +=1;
                    }else if(strtoupper($val)=='BROKEN'){
                            $total_B +=1;
                    }else if(strtoupper($val)=='REPAIR'){
                            $total_R +=1;
                    }
                 }

                 $rand = rand();
             ?> 
             <tr>
                <td style="display: none;" class="biruMuda">head</td>
                <td class="biruMuda"><?=$no?> </td>
                <td class="biruMuda"> <button type="button" class="btn btn-primary" onclick="buka_tutup(<?=$rand?>)" ><i id="iFa<?=$rand?>" class="fa fa-arrow-down fa-lg"></i></button>
                    <input type="hidden" id="rField<?=$rand?>"  value="0" ></td>
                <td style="display: none;"><?=$value?> </td>
                <td class="biruMuda"> <?=$value?> </td>
                   <td class="biruMuda"> - </td>
                <td class="biruMuda"> - </td>
                <td class="biruMuda"> <?=$total?></td>
                <td class="biruMuda"> <?=$total_G?></td>
                <td class="biruMuda"> <?=$total_R?> </td>
                <td class="biruMuda"> <?=$total_B?> </td>
                <td class="biruMuda"> <?=$arrDataLokasi?>  </td>
                <td class="biruMuda"> - </td>
                <td class="biruMuda"> - </td>
                <td class="biruMuda"> <?=$totalStok?> </td>
                <td class="biruMuda">   <?=$totalStokKeluar?>  </td>
             </tr>  
             <?
             $KK=1;
             foreach($arrDataDetail as $tVal){
              $itotal_G=$itotal_R=$itotal_B=0;
                       if(strtoupper($val) == 'GOOD'){
                           $itotal_G +=1;
                       }else if(strtoupper($val)=='BROKEN'){
                        $itotal_R +=1;
                    }else if(strtoupper($val)=='REPAIR'){
                        $itotal_B +=1;
                    }
             ?>
             <tr class="Itr<?=$rand?> lrhide"  >
                <td style="display: none;">child<?=$rand?></td>
                <td align="right" class="biruMuda2"> <?=getColoms($KK)?> </td>
                <td class="biruMuda2">  <?=$tVal['equip_datein']?> </td>
                <td style="display: none;"><?=$tVal['equip_name']?> </td>
                <td class="biruMuda2"> <?=$tVal['barcode']?> </td>
                  <td class="biruMuda2"> <?=$tVal['serial_number']?> </td>
                <td class="textWrap biruMuda2" style="white-space: normal;"> <?=$tVal['equip_spec']?></td>
                <td class="biruMuda2"> <?=$tVal['equip_qty']?></td>
                <td class="biruMuda2"> <?=$itotal_G?></td>
                <td class="biruMuda2">  <?=$itotal_R?></td>
                <td class="biruMuda2">  <?=$itotal_B?></td>
                <td class="biruMuda2"> <?=$tVal['equip_storage']?> </td>
                <td class="biruMuda2"><?=$tVal['equip_lastcal']?> </td>
                <td class="biruMuda2"> <?=$tVal['equip_nextcal']?> </td>
                <td class="biruMuda2"> <?=$tVal['equip_stok']?> </td>
                <td class="biruMuda2">  <?=$tVal['equip_keluar']?> </td>
             </tr>
             <?   
             $KK++;
             }
             ?>
                
                <?
            $no++;
             }
                ?>
            </tbody>
        </table>

    </div>


</div>

<!------>

<script type="text/javascript">
     
  
         var     otable;
        $(document).ready(function() { 
        otable= $('#example2').DataTable( {
            columnDefs: [{
                className: 'never',
                targets: [0]
            },{
            "bSort": true,"targets": 0,
               data: "status",
               "type": "num",
               "bScrollInfinite": true,
               "sPaginationType": "full_numbers",
               "bStateSave": true,
           },{
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap width-200'>" + data + "</div>";
                    },
                    targets: 5
                }
           ],
           "iDisplayLength": 25,
        },
        );
        


        drawHead();
        $('#btnRefresh2').on('click', function() {
            windows.location.reload();

        });
        });
        function drawHead(){
           $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {

                return otable.row(dataIndex).data().status == 'head' 
            }
            );
           otable.draw();
        }

        function draw_by_id(param){
             resetDraw();
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    
               return loopinLook(dataIndex,param)
               
              }
            );
           otable.draw(false);
        }

        function loopinLook(dataIndex,param){
            var  bollean = false;
            var status = otable.row(dataIndex).data().status;
            if(param.includes(status)){
                bollean=true;
            }

            return  bollean;

        }
        function resetDraw(){
              $.fn.dataTable.ext.search.pop();
             otable.draw(false);
        }
         function buka_tutup(id) {
           
           
            var val = $("#rField"+id).val();
        
            if (val == 0) {
                $('#iFa'+id).removeClass('fa fa-arrow-down fa-lg').addClass('fa fa fa-arrow-up fa-lg'); 

               $("#rField"+id).val('1');
              
                 
            } else {
                $('#iFa'+id).removeClass('fa fa fa-arrow-up fa-lg').addClass('fa fa-arrow-down fa-lg');
                $("#rField"+id).val('0');
            }
            let ob=['head'];
            $('.fa-arrow-up').each(function(i, obj) {
                var id = $(this).attr('id');
                var  newStr = id.replace("iFa", "child");
                ob.push(newStr);
            });
            draw_by_id(ob);
         
             
        }
</script>