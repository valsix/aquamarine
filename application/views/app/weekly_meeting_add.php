<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("WeeklyProses");
$this->load->model("WeeklyProsesDetail");
$this->load->model("WeeklyProgresInline");
$this->load->model('WeeklyProgresRincian');


$dokumen_certificate = new WeeklyProses();

$reqId = $this->input->get("reqId");
$reqTipe = 'Certificate';
if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    $dokumen_certificate->selectByParamsMonitoring(array("CAST(A.WEEKLY_PROSES_ID AS VARCHAR)" => $reqId));
    $dokumen_certificate->firstRow();
    $reqId                  = $dokumen_certificate->getField("WEEKLY_PROSES_ID");
    $reqDepartementId       = $dokumen_certificate->getField("DEPARTEMENT_ID");
    $reqMasalah             = $dokumen_certificate->getField("MASALAH");
    $reqTanggalMasalah      = $dokumen_certificate->getField("TANGGAL_MASALAH");
   
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/weekly_meeting"> Weekly </a> &rsaquo; Meeting
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <?
        if(!empty($reqId)){
          ?>
          <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="rev_history()"><i class="fa fa-fw fa-gavel fa lg"> </i><span>  Meeting History</span> </a>
          <?
        }
        ?>
<a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="cetak_pdf()"><i class="fa fa-fw fa-file-pdf-o fa lg"> </i><span>  PDF</span> </a>
<!-- <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" onclick="rev_history()"><i class="fa fa-fw fa-spinner  fa lg"> </i><span>  Progres</span> </a> -->
    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry Weekly Meeting
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqCertificate" class="control-label col-md-2">Departement</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-combobox form-control" style="width:100%" name="reqDepartementId" data-options="width:'230',editable:false, valueField:'id',textField:'text',url:'combo_json/comboDepartments'" value="<?= $reqDepartementId ?>" />
                                </div>
                            </div>
                        </div>
                         <label for="reqDescription" class="control-label col-md-2">Tanggal</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <input type="text" id="reqTanggalMasalah" class="easyui-datebox textbox form-control" name="reqTanggalMasalah" value="<?= $reqTanggalMasalah ?>" style=" width:170px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Masalah</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                  

                                    <textarea type="text" rows="3" cols="2" id="reqMasalah" class=" textbox form-control" name="reqMasalah" style=" width:100%"><?= $reqMasalah ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?
                    if(!empty($reqId)){
                    ?>
                    <div class="page-header">
                        <h3 style="height: 45px"><i class="fa fa-file-text fa-lg"></i> Solusi Detail
  <a onclick="tambahPenyebab()" id="btnPenyebab" class="btn btn-info pull-right" style="margin-right: 20px"><i class="fa fa-fw fa-plus-square"></i></a>
  <a onclick="hide_folder1()"   class="btn btn-warning pull-right" style="margin-right: 20px"><i id="nameFolder1" class="fa fa-fw fa-folder-o"></i></a>
                          
                        </h3>
                    </div>
                
                   <table style="width: 100%">
                       <tbody id="tambahAttacment" >
                        <?
                        $weeklyprosesdetail = new WeeklyProsesDetail();
                         $weeklyprosesdetail->selectByParamsMonitoring(array("A.WEEKLY_PROSES_ID"=>$reqId),-1,-1," "," ORDER BY A.URUT ASC");
                         $reqUrutNo =1;
                         while ( $weeklyprosesdetail->nextRow()) {
                            $reqWeeklyProsesDetailId = $weeklyprosesdetail->getField("WEEKLY_PROSES_DETAIL_ID");
                            $reqMasterSolusiId = $weeklyprosesdetail->getField("MASTER_SOLUSI_ID");
                         

                        ?>


                                    <tr>
                                        <td>
                                           <div style="background:blue;height: 5px;margin: 10px" class="parentSolusi"></div>
                                           <div class="form-group">
                                            <label for="reqCertificate" class="control-label col-md-1">Urut</label>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <div class="col-md-11">
                                                      <input type="hidden" class="easyui-validatebox textbox form-control"  name="reqParams[]" value="<?=$reqWeeklyProsesDetailId?>">
                                                      <input type="hidden"  class="easyui-validatebox textbox form-control" id="reqWeeklyProsesDetailId<?=$reqWeeklyProsesDetailId?>" name="reqWeeklyProsesDetailId[]" value="<?=$reqWeeklyProsesDetailId?>">
                                                      <input type="text" onkeypress='validate(event)' id="reqUrutSolusi" class="easyui-validatebox textbox form-control" name="reqUrutSolusi[]" value="<?=$reqUrutNo?>" style=" width:100%" />
                                                  </div>
                                              </div>
                                          </div>
                                          <label for="reqDescription" class="control-label col-md-1">Solusi</label>
                                          <div class="col-md-9">
                                            <div class="form-group">
                                                <div class="col-md-9">
                                                   <input type="text"   class="easyui-validatebox textbox form-control" name="reqMasterSolusiId[]" value="<?=$reqMasterSolusiId?>" style=" width:80%" />
                                                  
                                               </div>
                                               <div class="col-md-1">
                                                   <a onclick="tambahDetail(<?=$reqWeeklyProsesDetailId?>)" id="btnPenyebab" class="btn btn-info pull-right" style="margin-right: 20px"><i class="fa fa-fw fa-plus-square"></i> Detail Solusi</a>


                                               </div>
                                               <div class="col-md-1">
                                                 <input type="hidden" id="reqValueFolder<?=$reqWeeklyProsesDetailId?>" value="0" />
                                                 <a onclick="hide_folder2(<?=$reqWeeklyProsesDetailId?>)" id="btnPenyebab" class="btn btn-warning pull-right" style="margin-right: 20px"><i id="nameFolder2<?=$reqWeeklyProsesDetailId?>" class="fa fa-fw fa-folder-o"></i></a>

                                             </div>
                                             <div class="col-md-1">
                                                <a onclick="hapus_folder1(this,<?=$reqWeeklyProsesDetailId?>)"  class="btn btn-danger pull-right" style="margin-right: 20px"><i  class="fa fa-fw fa-remove"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <div style="background-color: black;height: 2px;margin-left: 10px;margin-right: 10px"></div>
                                <table style="width: 100%" id='tables_hil<?=$reqWeeklyProsesDetailId?>' class="table table-bordered hilangkan">
                                  <head>
                                    <tr>
                                      <th style="width:10%"> No </th>
                                      <th style="width: 34%"> Progress </th>
                                       <th style="width: 10%"> Due Date </th>
                                        <th style="width: 15%"> PIC Person </th>
                                        <th style="width: 15%"> Due Pic </th>
                                        <th style="width: 10%"> Status </th>
                                         <th style="width: 3%"> Aksi </th>
                                    </tr>
                                  </head>
                                  <tbody id="detail<?=$reqWeeklyProsesDetailId?>" class="hilangkan">



                                    <?
                                    $WeeklyProgresInline = new WeeklyProgresInline();
                                    $WeeklyProgresInline->selectByParamsMonitoring(array("A.WEEKLY_PROSES_ID"=>$reqId,"A.WEEKLY_PROSES_DETAIL_ID"=>$reqWeeklyProsesDetailId),-1,-1," "," ORDER BY A.URUT ASC");
                                    $reqUrutNoInline =1;
                                    while ($WeeklyProgresInline->nextRow()) {
                                      $reqWeeklyProgresInlineId = $WeeklyProgresInline->getField("WEEKLY_PROGRES_INLINE_ID");
                                      $reqProsesInline = $WeeklyProgresInline->getField("PROSES");
                                      $reqStatusInline = $WeeklyProgresInline->getField("STATUS");
                                      $reqDueDateInline = $WeeklyProgresInline->getField("DUE_DATE");
                                      $reqPicPath = $WeeklyProgresInline->getField("DUE_PIC");
                                       $reqPicPaths = $WeeklyProgresInline->getField("DUE_PIC");
                                       $reqPicPerson  = $WeeklyProgresInline->getField("PIC_PERSON");
                                       $format_file = explode('.', $reqPicPaths);
                                       $indexArr = count($format_file);
                                       $format_filesi =  $format_file[$indexArr-1];
                                      $reqPicPath2 ="uploads/weekly_meeting/".$reqWeeklyProgresInlineId."/".$reqPicPath;
                                       // echo $format_filesi;
                                       
                                        $displayDownload='';
                                      if(empty( $reqPicPath)){
                                         $reqPicPath ="images/icon_arsip.jpg";
                                         $displayDownload=" style='display:none'";
                                      } else{
                                         // $reqPicPath ="images/icon_arsip.jpg";
                                            // $reqPicPath ="uploads/weekly_meeting/".$reqWeeklyProgresInlineId."/".$reqPicPath;

                                            
                                          
                                            if(strtoupper($format_filesi) == 'PNG' || strtoupper($format_filesi) == 'JPG' || strtoupper($format_filesi) == 'JPEG' ){
                                               $reqPicPath = $reqPicPath2;
                                               
                                            }else{
                                                 $reqPicPath ="images/icon_arsip.jpg"; 
                                            }
                                      }                                                           

                                                                          
                                    ?>
                                    <tr class="trremove<?=$reqWeeklyProgresInlineId?>">
                                      <td colspan="7">
                                        <div style="background:green;height: 8px;margin: 10px" class="solusi<?=$reqWeeklyProsesDetailId?>" > </div>
                                      </td>
                                    </tr>
                                    <tr class="trremove<?=$reqWeeklyProgresInlineId?>"> 
                                      <td> <input type="hidden" class="easyui-validatebox textbox form-control" id="reqParamInline<?=$reqWeeklyProgresInlineId?>" name="reqParamInline<?=$reqWeeklyProsesDetailId?>[]" value='<?=$reqWeeklyProgresInlineId?>'>
                                                                  <input type="hidden"  class="easyui-validatebox textbox form-control" id="reqWeeklyProgresInlineId<?=$reqWeeklyProgresInlineId?>" name="reqWeeklyProgresInlineId<?=$reqWeeklyProsesDetailId?>[]" value='<?=$reqWeeklyProgresInlineId?>'>
                                                                  <input type="text" onkeypress='validate(event)' class="easyui-validatebox textbox form-control" name="reqUrutInline<?=$reqWeeklyProsesDetailId?>[]" value="<?=$reqUrutNoInline?>" style=" width:100%" /> </td>
                                      <td><textarea rows="5"  type="text" id="reqProses" class="easyui-validatebox textbox form-control" name="reqProses<?=$reqWeeklyProsesDetailId?>[]"  style=" width:90%" /><?=$reqProsesInline?></textarea> </td>
                                      <td>   <input type="text" id="reqDueDate<?=$reqWeeklyProgresInlineId?>" class="easyui-datebox textbox form-control datest" name="reqDueDate<?=$reqWeeklyProsesDetailId?>[]" value="<?= $reqDueDateInline ?>" style="width: 180px" /></td>
                                      <td><input type="text" id="reqPicPerson<?=$reqWeeklyProgresInlineId?>" class="easyui-validatebox  form-control datest" name="reqPicPerson<?=$reqWeeklyProsesDetailId?>[]" value="<?= $reqPicPerson ?>" style="width: 180px" /> </td>
                                      <td><img id="imgLogo<?=$reqWeeklyProgresInlineId?>" src="<?=$reqPicPath?>" style="height: 150px !important;width: 250px !important"> 
                                        <br>
                                        <div class="input-group">
                                        <span class="input-group-addon" onclick="openFile(<?=$reqWeeklyProgresInlineId?>)"><i class="fa fa-upload fa-lg"></i> </span>
                                        <span  <?=$displayDownload?> class="input-group-addon" onclick="download2(<?=$reqWeeklyProgresInlineId?>)" ><i class="fa fa-download fa-lg"></i> </span>
                                        <input type="text" class="form-control"  id="reqNamaFile<?=$reqWeeklyProgresInlineId?>"  value="<?= $reqPicPaths ?>" 
                                        style=" width:80%"
                                        >
                                      </div>
                                      <input style="display: none;" type="file" id="reqFilesName<?=$reqWeeklyProgresInlineId?>" name="reqFilesName<?=$reqWeeklyProsesDetailId?>[]" class="form-control" style="width: 60%" >
                                      <input type="hidden" class="easyui-validatebox textbox form-control" id="reqFilesNama2<?=$reqWeeklyProgresInlineId?>" name="reqFilesNames<?=$reqWeeklyProsesDetailId?>[]" value="<?=$reqPicPaths?>">
                                      <input type="hidden" class="easyui-validatebox textbox form-control" id="reqFilesNama22<?=$reqWeeklyProgresInlineId?>"  value="<?=$reqPicPath?>">
                                      <input type="hidden" class="easyui-validatebox textbox form-control" id="reqValueFolder2<?=$reqWeeklyProgresInlineId?>" value="0" />
                                       </td>
                                      <td> <input id="reqStatusProgres<?=$reqWeeklyProgresInlineId?>" class="easyui-combobox form-control comboboxs"  style="width:100%" name="reqStatusProgres<?=$reqWeeklyProsesDetailId?>[]" data-options="width:'230',editable:false, valueField:'id',textField:'text',url:'combo_json/comboStatusWeekly'" value="<?=$reqStatusInline?>" /> </td>
                                      <td>   <a onclick="hapus_folder2(this,<?=$reqWeeklyProgresInlineId?>)"  class="btn btn-danger pull-right" style="margin-right: 20px"><i class="fa fa-fw fa-remove"></i></a> </td>
                                    </tr>
                                                
                                    <tr class="trremove<?=$reqWeeklyProgresInlineId?>">
                                      <td align="center"> <b>Rincian Detail : </b>  </td>
                                      <td colspan="6">
                                    <div class="col-md-8"> <b>Rincian Progress </b>   <a onclick="tambah_rincian(<?=$reqWeeklyProgresInlineId?>)" id="btnPenyebab" class="btn btn-info " style="margin-right: 20px"><i class="fa fa-fw fa-plus-square"></i> Tambah  Rincian</a>  <a onclick="hide_folder3(<?=$reqWeeklyProgresInlineId?>)"  class="btn btn-warning" style="margin-right: 20px"><i id="nameFolder3<?=$reqWeeklyProgresInlineId?>" class="fa fa-fw fa-folder-o"></i></a></div>
                                    <br>
                                    <br>
                                    <div style="background-color: black;height: 2px;margin-left: 10px;margin-right: 10px"></div>
                                    <br>
                                    <table style="width: 100%;padding: 10px" class="table table-bordered">
                                      <thead>
                                        <tr>
                                          <th style="width: 10%"> No </th>
                                          <th> Keterangan </th>
                                          <th style="width: 10%"> Aksi </th></tr>
                                      </thead>
                                      <tbody id="detailRincian<?=$reqWeeklyProgresInlineId?>" >

                                        <?
                                        $weekly_progres_rincian = new WeeklyProgresRincian();
                                        $weekly_progres_rincian->selectByParamsMonitoring(array("A.WEEKLY_PROSES_ID"=>$reqId,"A.WEEKLY_PROSES_DETAIL_ID"=>$reqWeeklyProsesDetailId,"A.WEEKLY_PROGRES_INLINE_ID"=>$reqWeeklyProgresInlineId),-1,-1," "," ORDER BY A.URUT ASC");
                                        $reqUrutNoRincian =1;
                                        while ( $weekly_progres_rincian->nextRow()) {
                                             $reqWeeklyProgresRincianId = $weekly_progres_rincian->getField('WEEKLY_PROGRES_RINCIAN_ID');
                                             $reqWeeklyRincian = $weekly_progres_rincian->getField('RINCIAN');
                                              
                                        ?>
                                        <tr>
                                            <td><input type="text" id="reqUrutRincian<?=$reqWeeklyProgresRincianId?>" onkeypress='validate(event)' class="easyui-validatebox textbox form-control" name="reqUrutRincian<?=$reqWeeklyProgresInlineId?>[]" value="<?=$reqUrutNoRincian?>" style=" width:100%" /></td> 
                                            <td class="rincian<?=$reqWeeklyProgresInlineId?>">
                                                <input type="hidden" id="reqWeeklyProgresRincianId<?=$reqWeeklyProgresRincianId?>" name="reqWeeklyProgresRincianId<?=$reqWeeklyProgresInlineId?>[]" value="<?=$reqWeeklyProgresRincianId?>"> 
                                                <input type="text" id="reqRincian" class="easyui-validatebox textbox form-control" name="reqRincian<?=$reqWeeklyProgresInlineId?>[]" value="<?=$reqWeeklyRincian?>" style=" width:100%" /></td>
                                                <td>  <a onclick="hapus_folder3(this,<?=$reqWeeklyProgresRincianId?>)"  class="btn btn-danger " ><i class="fa fa-fw fa-remove"></i></a> </td>
                                            </tr>
                                            <?
                                            $reqUrutNoRincian++;
                                        }
                                            ?>
                                      </tbody>
                                  </table>
                              </td>
                          </tr> 
                          <?
                          $reqUrutNoInline++;
                            } 
                          ?>


                                  </tbody>
                              </table>
                          </td>
                      </tr>
                      <?
                        $reqUrutNo++;
                        }
                      ?>

                           
                       </tbody>
                   </table>
                   <br>
                   <br>
                   <?
                    }
                   ?>
                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqTipe" value="<?= $reqTipe ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Reset</a>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> Submit</a>
            </div>

        </div>

    </div>

    <script>
        $( document ).ready(function() {
            $(".hilangkan").hide();
        });
        function submitForm() {
              var win = $.messager.progress({
            title: 'Office Management  | PT Aquamarine Divindo',
            msg: 'proses data...'
        });
            $('#ff').form('submit', {
                url: 'web/weekly_proses_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                     if(datas[0]=='xxx'){
                             show_toast('error', 'Have troube in ',  datas[1]);
                            $.messager.alert('Info', datas[1], 'info'); 
                    }else{
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/weekly_meeting_add?reqId=" + datas[0]);
                    }
                     $.messager.progress('close'); 
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }

        function rev_history(){
          openAdd("app/loadUrl/app/template_rev_history_weekly?reqId=<?=$reqId?>");
        }
    </script>
    <script type="text/javascript">
        function tambahPenyebab(filename='') {
            
             var id= $('.parentSolusi').length+1;
            // var id = $('#tambahAttacment tr').length+1;
            $.get("app/loadUrl/app/tempalate_add_solusi?id="+id+"&filename="+filename, function(data) {
                $("#tambahAttacment").append(data);
            });
        }

        function tambahDetail(ids) {
            // console.log(id);
            // var idk = $('#detail'+ids+' tr .solusi'+ids).length;
             var idk= $('.solusi'+ids).length+1;
           
            $.get("app/loadUrl/app/tempalate_add_solusi_detail?id="+idk+"&reqParent="+ids, function(data) {
                $("#detail"+ids).append(data);

            });
        }
        function tambah_rincian(ids) {
           var id= $('.rincian'+ids).length+1;
            $.get("app/loadUrl/app/tempalate_add_solusi_rincian?id="+id+"&reqParent="+ids, function(data) {
                $("#detailRincian"+ids).append(data);
            });
        }

        function getFileName(input, id) {
            for (var i = 0; i < input.files.length; i++) {
                if(i == 0)
                {
                    $("#namaFile"+id).html(input.files[0].name);
                    var ext = input.files[0].name.split('.').pop();
                    ext = ext.toUpperCase();
                    if(ext.length > 3) ext = '';
                    if(ext == 'PNG' || ext == 'JPG' || ext == 'JPEG' || ext == 'BMP') ext = 'IMAGE'
                    $("#namaFile"+id).parent().next().html(ext);
                }
                else
                    tambahPenyebab(encodeURIComponent(input.files[i].name))
            }
            
        }
    </script>
    <script type="text/javascript">
        var i=0;
        function hide_folder1(id){
          var id ='';
          if(i==0){
             $("#tambahAttacment"+id).show();
             $('#nameFolder1'+id).removeClass('fa fa fa-folder fa-lg').addClass('fa fa fa-folder-open-o fa-lg');         
             i=1;         
         }else{
             $("#tambahAttacment"+id).hide();
             $('#nameFolder1'+id).removeClass('fa fa fa-folder-open-o fa-lg').addClass('fa fa fa-folder fa-lg');
             i=0;
         }
     }
     function hide_folder2(id){
          var i = $("#reqValueFolder"+id).val();
          if(i==0){
             $("#detail"+id).show();
              $("#tables_hil"+id).show();
             $('#nameFolder2'+id).removeClass('fa fa fa-folder fa-lg').addClass('fa fa fa-folder-open-o fa-lg');         
             $("#reqValueFolder"+id).val(1);      
         }else{
             $("#detail"+id).hide();
              $("#tables_hil"+id).hide();
             $('#nameFolder2'+id).removeClass('fa fa fa-folder-open-o fa-lg').addClass('fa fa fa-folder fa-lg');
            $("#reqValueFolder"+id).val(0);
         }
     }
      
     function hide_folder3(id){
          var i = $("#reqValueFolder2"+id).val();
          if(i==0){
             $("#detailRincian"+id).show();
             $('#nameFolder3'+id).removeClass('fa fa fa-folder fa-lg').addClass('fa fa fa-folder-open-o fa-lg');         
             $("#reqValueFolder2"+id).val(1);      
         }else{
             $("#detailRincian"+id).hide();
             $('#nameFolder3'+id).removeClass('fa fa fa-folder-open-o fa-lg').addClass('fa fa fa-folder fa-lg');
            $("#reqValueFolder2"+id).val(0);
         }
     }

     function openFile(id){
      $(document).ready(function() {
        $("#reqFilesName"+id).change(function() {
           var i = $(this).prev('label').clone();
           var file = $('#reqFilesName'+id)[0].files[0].name;
           var format = $('#reqFilesName'+id)[0].files[0].type;
           //application/pdf
           //application/vnd.ms-excel
          // application/vnd.openxmlformats-officedocument.wordprocessingml.document
          //image/png
         var datas = format.split('/');
           console.log(datas);
           $("#reqNamaFile"+id).val(file);
           if(datas[0]=='image'){
              readURL(this,id);
           }


        });
      });
      $("#reqFilesName"+id).click();
      

     }
    
     function download2(id){
        var location = $("#reqFilesNama22"+id).val();
        // console.log(location);
        openAdd(""+location);
     }
     function readUrl2(input,id){
       $('#file-upload').change(function() {
        var i = $(this).prev('label').clone();
        var file = $('#file-upload')[0].files[0].name;
        $(this).prev('label').text(file);
      });

     }

     function readURL(input,id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#imgLogo'+id).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
       
        function hapus_folder1(input,id){
            var ids = $("#reqWeeklyProsesDetailId"+id).val();
            if(ids!=''){
                deleteDataFolder('web/weekly_proses_json/delete2',ids,input,5);
            }else{
                $(input).parent().parent().parent().parent().parent().remove();
            }
           

        }

        function cetak_pdf(){
           openAdd('app/loadUrl/report/cetak_weekly_pdf?reqId=<?=$reqId?>');
        }
        function hapus_folder2(input,id){
            var ids = $("#reqWeeklyProgresInlineId"+id).val();
             if(ids!=''){
            deleteDataFolder('web/weekly_proses_json/delete3',ids,input,3);
            }else{
            // $(input).parent().parent().remove();
              $(".trremove"+id).remove();
            
            }
            
           
        }

        function hapus_folder3(input,id){
            var ids = $("#reqWeeklyProgresRincianId"+id).val();
            if(ids!=''){
            deleteDataFolder('web/weekly_proses_json/delete4',ids,input,2);
            }else{

              $(input).parent().parent().remove();
            }
             
            
        }

        function deleteDataFolder(delele_link, id,input,jml)
        {
            $.messager.confirm('Konfirmasi','Yakin menghapus data terpilih ?',function(r){
                if (r){
                    var jqxhr = $.get( delele_link+'?reqId='+id, function() {
                        // document.location.reload();
                    })
                    .done(function() {
                        if(jml==2){
                            $(input).parent().remove();
                        }
                        if(jml==5){
                            $(input).parent().parent().parent().parent().parent().remove();
                        }
                          if(jml==3){
                             $(".trremove"+id).remove();
                            // $(input).parent().parent().parent().parent().parent().remove();
                        }
                    })
                    .fail(function() {
                        alert( "error" );
                    });                             
                }
            });             
        }

    </script>
</div>
</div>