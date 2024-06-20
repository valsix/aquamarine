<?php

$arrPost = $this->input->post();
foreach ($arrPost as $key => $value) {
   $_SESSION[$pg.$key] =$value;
}

$reqCompanyName = $_SESSION[$pg."reqCompanyName"];
$reqCodeProject = $_SESSION[$pg."reqCodeProject"];
$reqContactPerson = $_SESSION[$pg."reqContactPerson"];
$reqNoPoContract = $_SESSION[$pg."reqNoPoContract"];
$reqNamaProject = $_SESSION[$pg."reqNamaProject"];
$reqLokasi = $_SESSION[$pg."reqLokasi"];
$reqBulan = $_SESSION[$pg."reqBulan"];
$reqTahun = $_SESSION[$pg."reqTahun"];
$reqGlobal = $_SESSION[$pg."reqGlobal"];

$statament = '';
if(!empty($reqCompanyName)){
        $statament  .=" AND EXISTS( SELECT 1 FROM COMPANY CC WHERE CC.COMPANY_ID::VARCHAR  = A.COMPANY_ID AND UPPER(CC.NAME) LIKE '%".strtoupper($reqCompanyName)."%')";
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

if(!empty($reqNoPoContract)){
      $statament  .=" AND EXISTS( SELECT 1 FROM MASTER_PROJECT CC WHERE CC.MASTER_PROJECT_ID::VARCHAR = A.CODE AND UPPER(CC.KETERANGAN) LIKE '%".strtoupper($reqNoPoContract)."%')";
}

if(!empty($reqNamaProject)){
      $statament  .=" AND EXISTS( SELECT 1 FROM MASTER_PROJECT CC WHERE CC.MASTER_PROJECT_ID::VARCHAR = A.CODE AND UPPER(CC.NAMA) LIKE '%".strtoupper($reqNamaProject)."%')";
}

if(!empty($reqLokasi)){
      $statament  .="  AND UPPER(A.LOKASI) LIKE '%".strtoupper($reqLokasi)."%' ";
}
if(!empty($reqBulan) && !empty($reqTahun)){
      $statament  .="  AND TO_CHAR(A.TANGGAL,'MMYYYY')='".$reqBulan.$reqTahun."'";
}

if(!empty($reqGlobal)){
     $statament  .="  AND ( 
     UPPER(A.LOKASI) LIKE '%".strtoupper($reqGlobal)."%' 
     OR UPPER(A.nomer  ) LIKE '%".strtoupper($reqGlobal)."%' 
     OR UPPER(A.nama  ) LIKE '%".strtoupper($reqGlobal)."%' 
     OR UPPER(A.code  ) LIKE '%".strtoupper($reqGlobal)."%' 
     OR UPPER(A.lokasi  ) LIKE '%".strtoupper($reqGlobal)."%' 
     OR UPPER(A.nama_project  ) LIKE '%".strtoupper($reqGlobal)."%' 
     OR UPPER(A.hpp_date  ) LIKE '%".strtoupper($reqGlobal)."%' 
     OR UPPER(A.estimasi  ) LIKE '%".strtoupper($reqGlobal)."%' 
      OR UPPER(A.approved  ) LIKE '%".strtoupper($reqGlobal)."%' 

     )";
}


$this->load->model('ProjectHppNew');
$projecthppnew = new ProjectHppNew();
$projecthppnew->selectByParamsMonitoring(array(),-1,-1,$statament);
$arrData = $projecthppnew->rowResult;
$total = $projecthppnew->rowCount;




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

    <div class="judul-halaman"> HPP PROJECT LIST</div>

    <!--<div class="judul-halaman-bawah">&nbsp;</div>-->
    <div class="konten-area">
        <div id="bluemenu" class="aksi-area">
            <span><a id="btnAdd"><i class="fa fa-fw fa-plus" aria-hidden="true"></i> Tambah </a></span>
           
            <span><a id="btnRefresh"><i class="fa fa-fw fa-refresh" aria-hidden="true"></i> Refresh </a></span>
           
         
            <!-- <span><a id="btnPosting"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> Posting</a></span> -->
            <button class="pull-right pencarian-detil">Pencarian Detil <i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>

        <div class="col-md-12 area-filter-tambahan" style="padding-bottom: 10px">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 10px">
                    <form id="ffs" class="easyui-form form-horizontal" method="post" data-options="novalidate:true">
                        <table id="tablei" style="width: 100%;padding: 10px;margin:10px">
                            <tr>
                                <td>Campany Nama </td>
                                <td><input type="text" name="reqCompanyName" class="form-control" id="reqCompanyName" value="<?= $reqCompanyName ?>" Placeholder='Masukkan Campany Nama'></td>
                                <td>Contact Person</td>
                                <td colspan="2"><input type="text" name="reqContactPerson" class="form-control" id="reqContactPerson" value="<?= $reqContactPerson ?>" Placeholder='Masukkan Contact Person'>

                                </td>
                                 <td>&nbsp;</td>

                            </tr>
                            <tr>
                                <td> Code project</td>
                                <td > <input type="text" name="reqCodeProject" class="easyui-textbox form-control" id="reqCodeProject" value="<?= $reqCodeProject ?>" style="width: 30%" data-options="height: 36" Placeholder='Masukkan Code project'></td>
                                 <td>No. PO/Contract</td>
                                <td>
                                  <input type="text" name="reqNoPoContract" class="easyui-textbox form-control" id="reqNoPoContract" value="<?= $reqNoPoContract ?>" style="width: 100%" data-options="height: 36" Placeholder='Masukkan No. PO/Contract'>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                           
                            <tr>
                                <td> Nama Project </td>
                                <td > <input type="text" name="reqNamaProject" class="easyui-textbox form-control" id="reqNamaProject" value="<?= $reqNamaProject ?>" style="width: 100%" data-options="height: 36" Placeholder='Masukkan Nama Project' ></td>
                                <td>Lokasi</td>
                                <td > <input type="text" name="reqLokasi" class="easyui-textbox form-control" id="reqLokasi" value="<?= $reqLokasi ?>" style="width: 100%" data-options="height: 36" Placeholder='Masukkan Lokasi'> </td>
                                 <td>&nbsp;</td>
                            </tr>
                               <tr>
                                <td> Bulan / Tahun </td>
                                <td >   <input class="easyui-combobox form-control" placeholder="Date" style="width:190px" id="reqBulan" name="reqBulan" data-options="editable:false,height: 34, valueField:'id',textField:'text',url:'combo_json/ComboBulan'" value="<?= $reqBulan ?>" /> / <input type="text" name="reqTahun" class="easyui-textbox form-control" id="reqTahun" value="<?= $reqTahun ?>" style="width: 10%" maxlength="4" onkeypress='validate(event)'  Placeholder='Tahun'> </td>
                                <td>  </td>
                            </tr>

                           <!--  <tr>
                                <td> Class </td>
                                <td > <input type="text" name="reqCariClass" class="easyui-textbox form-control" id="reqCariClass" value="<?= $reqCariClass ?>" style="width: 100%" data-options="height: 36"></td>
                                <td>Type Vessel</td>
                                <td colspan="2"><input type="text" name="reqCariTypeVessel" class="easyui-textbox form-control" id="reqCariTypeVessel" value="<?= $reqCariTypeVessel ?>" style="width: 40%" data-options="height: 36"> </td>
                            </tr> -->

                            
                              <tr>
                               
                                <td>Global</td>
                                <td colspan="2"><input type="text" name="reqGlobal" class="easyui-textbox form-control" id="reqGlobal" value="<?= $reqGlobal ?>" style="width: 40%" data-options="height: 36" Placeholder='Masukkan Global'> </td>
                                <td colspan="2"> </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="3"></td>

                                <td><button type="submit"   class="btn btn-default"> Searching </button>
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
                      <th width="2%">NO</th>
                         <th>No Hpp</th>
                    <th>Date</th>

                      <th>Code Project</th>
                        <th>Company Nama</th>
                          <th>Project </th>
                           <th>Location</th>
                            <th>Profit</th>
                          <th width="5%">AKSI</th>
                    
                </tr>
            </thead>
            <tbody>
                <?
                foreach ($arrData as  $value) {
                      $reqCode= $value["code_project"];
                ?>
                <tr>
                    <td> <?=$total?> </td>
                     <td> <?=$value['nomer']?> </td>
                      <td> <?=$value['tanggal']?> </td>
                     
                   
                    <td> <?= $reqCode ?> </td>
                     <td> <?=$value['nama_company']?> </td>
                    <td> <?=$value['nama_project2']?> </td>
                   
                   
                  
                     <td> <?=$value['lokasi']?> </td>
                    <td> <?=currencyToPage2($value['profit'])?> </td>
                    <td>
                       <a href="javascript:void(0)" class="btn btn-warning" onclick="klikRubah(<?=$value['project_hpp_new_id']?>)"><i class="fa fa-fw fa-pencil"></i></a>
                       <a href="javascript:void(0)" class="btn btn-danger" onclick="delData(<?=$value['project_hpp_new_id']?>)"><i class="fa fa-fw fa-trash"></i></a> </td>
                       
                   </tr>
                <?    
                $total--;    
                }
                ?>
               
            </tbody>
        </table>

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
        "bScrollCollapse": true,
        "bInfo": true,
        "bAutoWidth": false,"bStateSave": true,
            "fnStateSave": function(oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function(oSettings) {
                var data = localStorage.getItem('DataTables_' + window.location.pathname);
                return JSON.parse(data);
            },
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

