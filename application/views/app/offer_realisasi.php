<?php

$arrPost = $this->input->post();
foreach ($arrPost as $key => $value) {
   $_SESSION[$pg.$key] =$value;
}


$reqCariNoOrder = $_SESSION[$pg."reqCariNoOrder"];
$reqCariDateofServiceFrom = $_SESSION[$pg."reqCariDateofServiceFrom"];
$reqCariDateofServiceTo = $_SESSION[$pg."reqCariDateofServiceTo"];
$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqBulan = $_SESSION[$pg."reqBulan"];
$reqCariPeriodeYear = $_SESSION[$pg."reqCariPeriodeYear"];
$reqCariVasselName = $_SESSION[$pg."reqCariVasselName"];
$reqCariProject = $_SESSION[$pg."reqCariProject"];
$reqClass = $_SESSION[$pg."reqClass"];
$reqClassType = $_SESSION[$pg."reqClassType"];
$reqDestination = $_SESSION[$pg."reqDestination"];
$reqProjectName = $_SESSION[$pg."reqProjectName"];
$reqCariGlobalSearch = $_SESSION[$pg."reqCariGlobalSearch"];
$reqCariStatus = $_SESSION[$pg."reqCariStatus"];


$statament = '';
if(!empty($reqCariNoOrder)){
        $statament  .="  AND A.URUT = '".$reqCariNoOrder."'";
}
if(!empty($reqCodeProject)){
     $statament  .=" AND EXISTS( SELECT 1 FROM MASTER_PROJECT CC WHERE CC.MASTER_PROJECT_ID::VARCHAR = A.CODE AND UPPER(CC.CODE) LIKE '%".strtoupper($reqCodeProject)."%')";
}
if(!empty($reqContactPerson)){
      $statament  .=" AND EXISTS( SELECT 1 FROM COMPANY CC WHERE CC.COMPANY_ID::VARCHAR  = A.COMPANY_ID AND (
      UPPER(CC.CP1_NAME) LIKE '%".strtoupper($reqCompanyName)."%'
      OR  UPPER(CC.CP2_NAME) LIKE '%".strtoupper($reqContactPerson)."%'
    ))";
}
if(!empty($reqLokasi)){
      $statament  .="  AND UPPER(A.LOCATION) LIKE '%".strtoupper($reqLokasi)."%' ";
}
 if (!empty($reqCariDateofServiceFrom) && !empty($reqCariDateofServiceTo)) {

            $statament .= " AND B.DATE_OF_SERVICE BETWEEN to_date('" . $reqCariDateofServiceFrom . "', 'DD-MM-YYYY')  AND  to_date('" . $reqCariDateofServiceTo . "', 'DD-MM-YYYY') ";
        }
        if (!empty($reqProjectName)) {
          // $statement_privacy .= "  AND EXISTS( SELECT 1 FROM OFFER_PROJECT CC WHERE CC.OFFER_ID = A.OFFER_ID AND UPPER(A.DESCRIPTION) LIKE '%" . strtoupper($reqProjectName) . "%' ) ";
            $statament .=" AND B.MASTER_REASON_ID='".$reqProjectName."'";

        }
if(!empty($reqCariCompanyName)){
      $statament  .=" AND UPPER(A.NAME) LIKE '%".strtoupper($reqCariCompanyName)."%'";
}

if(!empty($reqCariVasselName)){
      $statament  .=" AND UPPER(A.NAME_OF_VESSEL) LIKE '%".strtoupper($reqCariVasselName)."%'";
}
if(!empty($reqCariProject)){
      $statament  .=" AND FD.SERVICES_ID = '".strtoupper($reqCariProject)."'";
}
if(!empty($reqClass)){
      $statament  .=" AND UPPER(A.CLASS_SOCIETY) LIKE '%".strtoupper($reqClass)."%'";
}

if(!empty($reqClassType)){
      $statament  .=" AND UPPER(A.TYPE_OF_VESSEL) LIKE '%".strtoupper($reqClassType)."%'";
}


if(!empty($reqDestination)){
      $statament  .="  AND UPPER(A.LOCATION) LIKE '%".strtoupper($reqDestination)."%' ";
}
if(!empty($reqBulan) && $reqCariPeriodeYear != 'All Year'){
      $statament  .="  AND TO_CHAR(B.DATE_OF_SERVICE,'MMYYYY')='".$reqBulan.$reqTahun."'";
}

if(!empty($reqDestination)){
      $statament  .="  AND UPPER(A.LOCATION) LIKE '%".strtoupper($reqDestination)."%' ";
}
if (is_numeric($reqCariStatus)) {
  if($reqCariStatus=='3'){
    $statament .= "  AND B.STATUS  IS NULL";
  }else{
    $statament .= "  AND B.STATUS  =".$reqCariStatus ;
  }
}
if(!empty($reqGlobal)){
     $statament  .="  AND ( 
     UPPER(A.NAME_OF_VESSEL) LIKE '%".strtoupper($reqGlobal)."%' 
     OR UPPER(A.LOCATION  ) LIKE '%".strtoupper($reqGlobal)."%' 
     OR UPPER(A.TYPE_OF_VESSEL  ) LIKE '%".strtoupper($reqGlobal)."%' 
     OR UPPER(A.CLASS_SOCIETY  ) LIKE '%".strtoupper($reqGlobal)."%' 
     OR UPPER(A.SCOPE_OF_WORK  ) LIKE '%".strtoupper($reqGlobal)."%' 
     OR UPPER(GG.KETERANGAN  ) LIKE '%".strtoupper($reqGlobal)."%' 
     OR UPPER(FD.NAMA  ) LIKE '%".strtoupper($reqGlobal)."%' 
    

     )";
}

   $this->load->model("Report");

$projecthppnew = new Report();
$projecthppnew->selectByParamsRealisasi(array(),-1,-1,$statament,' ORDER BY A.URUT DESC');

$arrData = $projecthppnew->rowResult;
$total = $projecthppnew->rowCount;

 $_SESSION["reqStatementOfferRealisasi"]=$statament;


?>


<!-- FIXED AKSI AREA WHEN SCROLLING -->
<link rel="stylesheet" href="css/gaya-stick-when-scroll.css" type="text/css">
<script src="js/stick.js" type="text/javascript"></script>


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
<style type="text/css">
    #tablei tr td {
        padding: 5px;
        font-weight: bold;
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

    <div class="judul-halaman"> Monitoring Realisasi</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <!-- <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah </a></span> -->
           
            <span><a id="btnRefresh" onclick="window.location.reload()"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
            <span><a id="btnPrint" onclick="openAdd('app/loadUrl/report/offer_realisasi_pdf')"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print </a></span>
           
         
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td>No Urut. </td>
                                <td><input type="text" name="reqCariNoOrder" class="form-control" id="reqCariNoOrder" value="<?= $reqCariNoOrder ?>"></td>
                                <td>Date of Service</td>
                                <td colspan="2"><input type="text" name="reqCariDateofServiceFrom" class="easyui-datebox " id="reqCariDateofServiceFrom" value="<?= $reqCariDateofServiceFrom ?>"> To <input type="text" name="reqCariDateofServiceTo" class="easyui-datebox " id="reqCariDateofServiceTo" value="<?= $reqCariDateofServiceTo ?>">


                                </td>

                            </tr>
                            <tr>
                                <td>Company of Name </td>
                                <td><input type="text" name="reqCariCompanyName" class="form-control" id="reqCariCompanyName" value="<?= $reqCariCompanyName ?>"></td>
                                <td>Periode Year</td>
                                <td>
                                     <input class="easyui-combobox form-control" placeholder="Date" style="width:190px" id="reqBulan" name="reqBulan" data-options="editable:false,height: 34, valueField:'id',textField:'text',url:'combo_json/ComboBulanId'" value="<?= $reqBulan ?>" /> /
                                      <input class="easyui-combobox form-control" style="width:100%" id="reqCariPeriodeYear" name="reqCariPeriodeYear" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/ambil_tahun'" value="<?= $reqCariPeriodeYear ?>" />
                                
                                </td>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td>Name of Vessel </td>
                                <td><input type="text" name="reqCariVasselName" class="form-control" id="reqCariVasselName" value="<?= $reqCariVasselName ?>"></td>
                                <td>Scope of Work ( General Services )</td>
                                <td>
                                    <!-- <input type="text" name="reqCariProject" class=" form-control" id="reqCariProject" value="<?= $reqCariProject ?>"> -->

                                      <input class="easyui-combobox form-control" style="width:100%" name="reqCariProject" id="reqCariProject" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'web/services_json/comboAll'" value="<?= $reqCariProject ?>" />

                                </td>
                                <td> </td>

                            </tr>
                            <tr>
                                <td>Class of Vessel</td>
                                <td >


                                    <input class="easyui-combobox form-control" style="width:100%" name="reqClass" id="reqClass" data-options="width:'200',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueClassOfVessel?reqMode=ALL'" value="<?= $reqClass ?>" />
                                </td>
                                <td>Class Type</td>
                                <td>
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqClassType" id="reqClassType" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/comboValueTypeOfVessel?reqMode=ALL'" value="<?= $reqClassType ?>" />
                                </td>
                                <td>&nbsp;</td>
                                
                            </tr>
                             <tr>
                                <td>Location </td>
                                <td>

                                 <!--   <input class="easyui-combobox form-control" style="width:100%" name="reqDestination" id="reqDestination" data-options="width:'250',editable:true, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_lokasi2?reqMode=ALL'" value="<?= $reqDestination ?>" / -->

                                 <input type="text" id="reqDestination" placeholder=" Lokasi"  class="easyui-combobox textbox form-control" name="reqDestination" value="<?= $reqDestination ?>" style=" width:250px"
                                     data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/combo_lokasiNama?reqMode=All'"

                                      />   

                                     

                                   </td>
                                <td>Reason </td>
                                <td>
                                 <!--    <input class="easyui-combobox form-control" style="width:100%" name="reqProjectName" id="reqProjectName" data-options="width:'250',editable:true, valueField:'id',textField:'text',url:'web/combo_baru_json/combo_offer_project'" value="<?= $reqProjectName ?>" /> -->

                                      <input class="easyui-combobox form-control" style="width:350px" id='reqProjectName' name="reqProjectName" data-options="editable:false, valueField:'id',textField:'text',url:'combo_json/combo_reason?reqMode=All'" value="<?= $reqProjectName ?>" />

                                </td>
                                <td>&nbsp;</td>
                                
                            </tr>

                            <tr>
                                <td>Global Search</td>
                                <td><input type="text" class="form-control" name="reqCariGlobalSearch" id="reqCariGlobalSearch" value="<?= $reqCariGlobalSearch ?>"></td>
                                <td>Status </td>
                                <td><select class="form-control" name="reqCariStatus" id="reqCariStatus" value="">
                                        <!-- <option value="">All </option>
                                        <option value="Pending">Pending </option>
                                        <option value="Real">Real </option>
                                        <option value="Cancel">Cancel </option> -->
                                        <option value="">All </option>
                                        <option value="0" <?if($reqCariStatus=='0'){echo 'selected';}?>>Pending </option>
                                        <option value="1" <?if($reqCariStatus=='1'){echo 'selected';}?>>Realisasi </option>
                                        <option value="2" <?if($reqCariStatus=='2'){echo 'selected';}?>>Cancel </option>
                                        <option value="3" <?if($reqCariStatus=='3'){echo 'selected';}?>>Not Respond </option>


                                    </select> </td>

                                <td>
                                    <button type="submit"  id="btn_cari_filters" class="btn btn-default"> Searching </button></td>
                            </tr>

                        </table>
                    </form>

                </div>
            </div>
        </div>
        <div class="table-responsive" style="width: 2000px">
        <table id="example2" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th width="2%">NO</th>
                <th>No.<br>Report</th>
                <th>Nama Kapal</th>

                <th>Type Service</th>
                <th>Lokasi Survey</th>
                <th>Class </th>
                <th>OWNER / AGENT</th>
                <th>Work Date</th>
                <th>Finish Date</th>
                <th>Harga Jual</th>
               
                <th>Operasional Cost</th>
                <th>Profit</th>
                <th>Status Pembayaran</th>
                <th>%</th>
                <th>Ket</th>              

                </tr>
            </thead>
            <tbody>

              <?
              $no=1;
              foreach ($arrData as $value) {
                $reqStatus= $value['status_realisasi'];
                $class='';
                if( $reqStatus=='Belum Lunas'){
                   $class ="class='redClass'";
                }else if( $reqStatus=='Lunas'){
                     $class ="class='greenClass'";
                }else if( $reqStatus=='Pending'){
                     $class ="class='yellowClass'";
                }
              ?>
              <tr>
                <td <?=$class?> > <?=$no;$no++;?> </td>
                <td <?=$class?>> <?=$value['urut']?></td>
                 <td <?=$class?>>
                   <?=$value['name_of_vessel']?><br> 
<b><?=$value['type_of_vessel']?></b> 
                   </td>
                  <td <?=$class?>> <?=$value['general_service_detail']?>  </td>
                   <td <?=$class?>> <?=$value['location']?> </td>
                   <td <?=$class?>> <?=$value['class_society']?> </td>
                   <td <?=$class?>>  <?=$value['name']?> </td>
                   <td <?=$class?>> <?=$value['start_date']?> </td>
                   <td <?=$class?>> <?=$value['finish_date']?> </td>
                   <td <?=$class?>> <?=currencyToPage2($value['total'])?> </td>
                   
                    <td <?=$class?>> <?=currencyToPage2($value['total_realisasi'])?> </td>
                     <td <?=$class?>> <?=currencyToPage2($value['profit'])?> </td>
                     <td <?=$class?>> <?=$value['status_realisasi']?> </td>
                      <td  <?=$class?>> <?=$value['prescentage']?> </td>
                       <td <?=$class?>> <?=$value['keterangan']?> </td>
              </tr>
              <?
              }
              ?>
             
               
            </tbody>
        </table>
      </div>
    </div>


</div>

<!------>

<script type="text/javascript">
     $(document).ready(function() {
    oTable2 =    $('#example2').dataTable({
           "bPaginate": true,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": true,
        "pageLength": 25,
        "bScrollCollapse": true,
        "bInfo": true,
        order: [[0, 'asc']],
        "bAutoWidth": false,
        // "bStateSave": true,
        //     "fnStateSave": function(oSettings, oData) {
        //         localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
        //     },
        //     "fnStateLoad": function(oSettings) {
        //         var data = localStorage.getItem('DataTables_' + window.location.pathname);
        //         return JSON.parse(data);
        //     },
        });
         window.history.replaceState('','',window.location.href);
         $('#btnAdd').on('click', function() {
            window.location.href='app/index/project_hpp_new_add';
        });
       });

     function klikRubah(id){
        window.location.href='app/index/project_hpp_new_add?reqId='+id;
     }
     function delData(id){
        deleteData('web/project_hpp_new_json/delete',id);
     }
</script>

