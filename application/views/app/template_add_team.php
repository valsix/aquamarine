<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model("SoTeam");

$so_team = new SoTeam();

$aColumns = array("SO_TEAM_ID", "SO_ID", "DOCUMENT_ID", "NAME","POSITION","AKSI" );
$reqId = $this->input->get("reqId");
$reqSoTeamId = $this->input->get("reqSoTeamId");

if($reqSoTeamId == ""){}
else {
    $so_team->selectByParamsMonitoring(array("SO_TEAM_ID" => $reqSoTeamId));
    $so_team->firstRow();

    $reqDocumentId = $so_team->getField("DOCUMENT_ID");
    $reqPosition = $so_team->getField("POSITION");

    $reqNoContact  = $so_team->getField("NO_CONTACT");
    $reqTanggalContact  = $so_team->getField("TANGGAL_CONTACT");
    $reqTanggalMulai  = $so_team->getField("TANGGAL_MULAI");
    $reqWorkHitung  =$so_team->getField("TOTAL_HARI_KERJA");
    $reqStandByMulai  = $so_team->getField("STAND_BY_MULAI");
    $reqStandByTotal  = $so_team->getField("TOTAL_STANDBY");
    $reqRateWork  =$so_team->getField("RATE_WORK");
    $reqRateStandNy  = $so_team->getField("RATE_STAND_BY");
    $reqTotalWork  = $so_team->getField("TOTAL_RATE_WORK");
    $reqTotalStand  =$so_team->getField("TOTAL_RATE_STAND_BY");
    $reqCertificatePath= $so_team->getField("LAMPIRAN");
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
       <script type="text/javascript" src="libraries/functions/string.func.js?n=1"></script>
    <script type="text/javascript" src="libraries/functions/command.js?ver=1.0.0"></script>
    <script src="libraries/tinyMCE/tinymce.min.js"></script>
<script src="js/moment.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
 <link rel="stylesheet" href="css/datepicter.css">
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

<?
        
        $reqIds = $this->input->post("reqIds");

        if(!empty($reqIds))
        {    
            $this->load->model("SoTeam");
            $reqDocumentId = $this->input->post("reqDocumentId");
            $reqSoTeamId = $this->input->post("reqSoTeamId");
            $reqPosition = $this->input->post("reqPosition");


            $reqNoContact  = $this->input->post("reqNoContact");
            $reqTanggalContact  = $this->input->post("reqTanggalContact");
            $reqTanggalMulai  = $this->input->post("reqTanggalMulai");
            $reqWorkHitung  = $this->input->post("reqWorkHitung");
            $reqStandByMulai  = $this->input->post("reqStandByMulai");
            $reqStandByTotal  = $this->input->post("reqStandByTotal");
            $reqRateWork  = $this->input->post("reqRateWork");
            $reqRateStandNy  = $this->input->post("reqRateStandNy");
            $reqTotalWork  = $this->input->post("reqTotalWork");
            $reqTotalStand  = $this->input->post("reqTotalStand");

            $so_team = new SoTeam();
            $so_team->setField("SO_TEAM_ID",$reqSoTeamId);
            $so_team->setField("SO_ID",$reqIds);
            $so_team->setField("DOCUMENT_ID",ValToNullDB($reqDocumentId));
            $so_team->setField("POSITION",$reqPosition);
            $so_team->setField("NO_CONTACT",$reqNoContact);
            $so_team->setField("TANGGAL_CONTACT",dateToDBCheck($reqTanggalContact));
            $so_team->setField("TANGGAL_MULAI",$reqTanggalMulai);
            $so_team->setField("TOTAL_HARI_KERJA",$reqWorkHitung);
            $so_team->setField("STAND_BY_MULAI",$reqStandByMulai);
            $so_team->setField("TOTAL_STANDBY",$reqStandByTotal);
            $so_team->setField("RATE_WORK",ifZero2(dotToNo($reqRateWork)));
            $so_team->setField("RATE_STAND_BY",ifZero2(dotToNo($reqRateStandNy)));
            $so_team->setField("TOTAL_RATE_WORK",ifZero2(dotToNo($reqTotalWork)));
            $so_team->setField("TOTAL_RATE_STAND_BY",ifZero2(dotToNo($reqTotalStand)));
            
            if($reqSoTeamId == ""){
                $so_team->insertBaru();
                $reqSoTeamId = $so_team->id;
            }else {
                $so_team->updateBaru();
            }

            $this->load->library("FileHandler");
            $file = new FileHandler();
             $FILE_DIR = "uploads/so_team/" . $reqSoTeamId . "/";
                makedirs($FILE_DIR);

                $filesData = $_FILES["reqLinkFileCertificate"];
                $reqLinkFileCertificateTemp      = $this->input->post("reqLinkFileCertificateTemp");

                $arrData = array();
                for ($i = 0; $i < count($filesData['name']); $i++) {
                    $renameFile = $reqId . '-' . $i . "-" . getExtension($filesData['name'][$i]);
                    if ($file->uploadToDirArray('reqLinkFileCertificate', $FILE_DIR, $renameFile, $i)) {
                        array_push($arrData, $renameFile);
                    } else {
                        array_push($arrData, $reqLinkFileCertificateTemp[$i]);
                    }
                }
                $str_name_path = '';
                for ($i = 0; $i < count($arrData); $i++) {
                    if (!empty($arrData[$i])) {
                        if ($i == 0) {
                            $str_name_path .= $arrData[$i];
                        } else {
                            $str_name_path .= ';' . $arrData[$i];
                        }
                    }
                }

        $so_team->setField("SO_TEAM_ID", $reqSoTeamId);
        $so_team->setField("LAMPIRAN", setQuote($str_name_path));
        $so_team->updateLampiran();
            // echo $this->db->last_query();
            ?>
            <script type="text/javascript">
             window.location.href = "app/loadUrl/app/template_add_team?reqId=<?= $reqId ?>&reqSoTeamId=";   
            </script>
            <?
                
        }
       
?>



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
                    <form class="form-horizontal" method="post" novalidate enctype="multipart/form-data">
                       <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Add Team</h3>
                     </div>
                     <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Name</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                   

                                    <input class="easyui-combobox form-control" style="width:100%" id="reqDocumentId" name="reqDocumentId"  data-options="width:'250',editable:true, valueField:'id',textField:'text',url:'combo_json/personil_combo', filter: function(q, row){
                                        var opts = $(this).combobox('options');
                                        return row[opts.textField].toUpperCase().includes(q.toUpperCase());
                                    }"  value="<?=$reqDocumentId?>" />
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Position</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-validatebox form-control" style="width:100%" id="reqPosition" name="reqPosition" value="<?=$reqPosition?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">No Contact</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-validatebox form-control" style="width:100%" id="reqNoContact" name="reqNoContact" value="<?=$reqNoContact?>" />
                                </div>
                            </div>
                        </div>
                         <label for="reqName" class="control-label col-md-2">Tanggal Contact</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-datebox form-control" style="width:150px" id="reqTanggalContact" name="reqTanggalContact" value="<?=$reqTanggalContact?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Tanggal Work</label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class=" form-control dateMulti" style="width:150px" id="reqTanggalMulai" name="reqTanggalMulai" value="<?=$reqTanggalMulai?>" />
                                </div>
                            </div>
                        </div>
                        
                         <label for="reqName" class="control-label col-md-2">Rate ( Day )</label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-validatebox form-control" style="width:150px" id="reqWorkHitung" name="reqWorkHitung" value="<?=$reqWorkHitung?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Stand by </label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class=" form-control dateMulti" style="width:150px" id="reqStandByMulai" name="reqStandByMulai" value="<?=$reqStandByMulai?>" />
                                </div>
                            </div>
                        </div>
                        
                           <label for="reqName" class="control-label col-md-2">Rate ( Day )</label>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-validatebox form-control" style="width:150px" id="reqStandByTotal" name="reqStandByTotal" value="<?=$reqStandByTotal?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqName" class="control-label col-md-2">Rate Work ( Rp )</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-validatebox form-control numberWithCommas" style="width:100%" id="reqRateWork" name="reqRateWork" value="<?=currencyToPage2($reqRateWork)?>" />
                                </div>
                            </div>
                        </div>
                         <label for="reqName" class="control-label col-md-2">Stand By ( Rp )</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-validatebox form-control numberWithCommas" style="width:100%" id="reqRateStandNy" name="reqRateStandNy" value="<?=currencyToPage2($reqRateStandNy)?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="form-group">
                        <label for="reqName" class="control-label col-md-2"> Total Rate Work ( Rp )</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-validatebox form-control" style="width:100%" id="reqTotalWork" name="reqTotalWork" value="<?=currencyToPage2($reqTotalWork)?>" />
                                </div>
                            </div>
                        </div>
                         <label for="reqName" class="control-label col-md-2">Total Stand By ( Rp )</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input class="easyui-validatebox form-control" style="width:100%" id="reqTotalStand" name="reqTotalStand" value="<?=currencyToPage2($reqTotalStand)?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                      <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> LAMPIRAN
                        </h3>
                    </div>
                    <div style="padding: 10px">
                        
                        <table style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="90%"> File Name <a onclick="addCerificate()" id="addCerificate" class="btn btn-info"><i class="fa fa-plus-square"></i></a></th>

                                    <th width="10%"> Action </th>
                                </tr>
                            </thead>
                            <tbody id="tambahCerificate">
                                <?
                                $files_data = explode(';',  $reqCertificatePath);
                                $ll=0;
                                for ($i = 0; $i < count($files_data); $i++) {
                                    if (!empty($files_data[$i])) {
                                        $texts = explode('-', $files_data[$i]);
                                        $ext = substr($files_data[$i], -3);
                                        $ll++;
                                ?>
                                        <tr>

                                            <td>
                                                <input type="file" onchange="getFileNameCertificate(this, '<?=($i+1)?>')" name="reqLinkFileCertificate[]" multiple class="form-control" style="width: 90%">
                                                <input type="hidden" name="reqLinkFileCertificateTemp[]" value="<?= $files_data[$i] ?>">
                                                <?if ($ext !=='pdf')
                                                {
                                                ?>
                                                  <a href="uploads/so_team/<?= $reqSoTeamId ?>/<?= $files_data[$i] ?>" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileCertificate<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                <?
                                                }
                                                else
                                                {
                                                ?>
                                                  <a onclick="openAdd(`uploads/so_team/<?= $reqSoTeamId ?>/<?= $files_data[$i] ?>`);" style="margin-left: 20px"><i class="fa fa-download fa-lg"></i></a><span class="nama-temp-file" id="namaFileCertificate<?=($i+1)?>"> <?= $files_data[$i] ?> </span>
                                                <?
                                                }
                                                ?>
                                            </td>

                                            <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                        </tr>
                                <?
                                    }
                                }
                                if($ll==0){
                                ?>
                                <tr>
                                  <td colspan="2" align="center" id='reqJumlahLampir'> No Display Record   </td>
                                </tr>
                                <?  
                                }
                                ?>

                            </tbody>
                        </table>

                    </div>
                    <div class="form-group">
                        <div class="col-md-2"></div>
                        <div class="col-md-4">
                            <button type="button" onclick="submits()" class="btn btn-primary"><i class="fa fa-fw fa-send"></i> <?=($reqSoTeamId == "" ? "Add" : "Update")?></button>        
                        </div>
                    </div>
                    <div style="text-align:center;padding:5px">

                        <input type="hidden" name="reqIds" id="reqId" value="<?=$reqId?>">
                        <input type="hidden" name="reqSoTeamId" id="reqSoTeamId" value="<?=$reqSoTeamId?>">   
                         <button type="Submit" id="submitss" class="btn btn-primary"><i class="fa fa-fw fa-send"></i> Submit  </button>
                        <!-- <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()"><i class="fa fa-fw fa-refresh"></i> Close</a> -->
                        <!-- <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()"><i class="fa fa-fw fa-send"></i> 
                        Submit</a> -->
                        
                    </div>
                     <div class="form-group">

                         <div >
                             <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                         <th>Aksi</th>
                                       <th>Nama</th>
                                         <th>Posisi</th>
                                         <th>Contact</th>
                                         <th>Tanggal Contact</th>
                                          <th>Total Rate Work</th>
                                          <th>Total Stand By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?
                                     $so_team->selectByParamsMonitoring(array("A.SO_ID::varchar" => $reqId));
                                     $arrData = $so_team->rowResult;
                                     $no=1;
                                     foreach ($arrData as $value) {
                                    ?>
                                    <tr>
                                        <td> <?=$no?> </td>
                                        <td>    <a href="javascript:void(0)" class="btn btn-warning" onclick="klikRubah(<?=$value['so_team_id']?>)"><i class="fa fa-fw fa-pencil"></i></a>
                    <a href="javascript:void(0)" class="btn btn-danger" onclick="delData(<?=$value['so_team_id']?>)"><i class="fa fa-fw fa-trash"></i></a>  </td>
                                         <td> <?=$value['nama_personil'];?>  </td>
                                          <td> <?=$value['position'];?>  </td>
                                          <td> <?=$value['no_contact'];?>  </td>
                                            <td> <?=$value['tanggal_contact'];?>  </td>
                                           <td> <?=currencyToPage2($value['total_rate_work']);?>  </td>
                                            <td> <?=currencyToPage2($value['total_rate_stand_by']);?>  </td>
                                         
                                    </tr>
                                    <?   
                                    $no++;
                                     }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </form>

                   
                </div>
            </div>
        </div>

       
    </div>
    <script type="text/javascript">
        
       function openPersonil(){
             openAdd('app/loadUrl/app/template_load_personil');
       }

        function klikRubah(id){
        window.location.href='app/loadUrl/app/template_add_team?reqId=<?=$reqId?>&reqSoTeamId='+id;
     }
     function delData(id){
        deleteData('web/so_team_new_json/delete',id);
     }

       function deleting(id){
           var elements  = oTable.fnGetData(id);
                   var kata =  '<b>Detail </b><br>'+elements[3]+"  ";
                    var delele_link='web/so_team_json/delete';
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
            window.location.href = "app/loadUrl/app/template_add_team?reqId=<?= $reqId ?>&reqSoTeamId="+elements[0];
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

             $(".numberWithCommas").on("keyup change", function(e) {
          var id = $(this).attr('id');

          numberWithCommas(id);
          });
                $('#example').DataTable( {
    order: [[0, 'desc']]
});

                //  $('#reqTanggalMulai,#reqTanggalSelesai').datebox({
                //     onSelect: function(date){
                //         getAnnualExtend();
                //     }
                // });
                //  $('#reqStandByMulai,#reqStandBySelesai').datebox({
                //     onSelect: function(date){
                //         getAnnualExtendStandBy();
                //     }
                // });
                 $("#reqRateWork").on("keyup change", function(e) {
                    var  hargaSatuan = $(this).val();
                    var total =   $('#reqWorkHitung').val();
                    hargaSatuan = hargaSatuan.replaceAll('.', '');
                    hargaSatuan = hargaSatuan.replaceAll(',', '.');
                    var total_keseluruhan = total * hargaSatuan;
                    $('#reqTotalWork').val(FormatCurrencyWithDecimal(total_keseluruhan));
                     });
                 $("#reqRateStandNy").on("keyup change", function(e) {
                     var  hargaSatuan = $(this).val();
                    var total =   $('#reqStandByTotal').val();
                    hargaSatuan = hargaSatuan.replaceAll('.', '');
                    hargaSatuan = hargaSatuan.replaceAll(',', '.');
                    var total_keseluruhan = total * hargaSatuan;
                    $('#reqTotalStand').val(FormatCurrencyWithDecimal(total_keseluruhan));
                     });
                 $('.dateMulti').datepicker({
                  multidate: true,
                  format: 'dd-mm-yyyy'
              }).change(function () {
                var id = $(this).attr('id');
                var isi = $(this).val();
                var datas = isi.split(',');
                var total = datas.length;
                if(id=='reqTanggalMulai'){
                      $('#reqWorkHitung').val(total);
                }else if(id=='reqStandByMulai'){
                      $('#reqStandByTotal').val(total);
                }
});

    });
    
</script>

<script type="text/javascript">
      function getAnnualExtend() {
                var reqDueDate = moment($('#reqTanggalSelesai').datebox('getValue'), 'DD-MM-YYYY');
                var reqDate = moment($('#reqTanggalMulai').datebox('getValue'), 'DD-MM-YYYY');
                var diff = reqDueDate.diff(reqDate, 'days', true)
                if(isNaN(diff)) diff = 0;
                $('#reqWorkHitung').val(Math.ceil(diff))
            }
             function getAnnualExtendStandBy() {
                var reqDueDate = moment($('#reqStandBySelesai').datebox('getValue'), 'DD-MM-YYYY');
                var reqDate = moment($('#reqStandByMulai').datebox('getValue'), 'DD-MM-YYYY');
                var diff = reqDueDate.diff(reqDate, 'days', true)
                if(isNaN(diff)) diff = 0;
                $('#reqStandByTotal').val(Math.ceil(diff))
            }

</script>

  <script type="text/javascript">
         function FormatCurrencyWithDecimal(num) 
{
    num = Math.round(num * 100)/100;
    num = num.toString().replace(/\$|\,/g,'');
    if(isNaN(num))
        num = "0";
        
    sign = (num == (num = Math.abs(num)));
    
    num_str = num.toString();
    cents = 0;
    
    if(num_str.indexOf(".")>=0)
    {
        num_str = num.toString();
        angka = num_str.split(".");
        cents = angka[1];
    }
    
    num = Math.floor(num).toString();
    
        
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    {
        num = num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
    }
    
    if(cents != "00"){
        var legCent = cents.length;
        if(legCent==1){ cents = cents+'0';}
       // if(legCent > 2 ){ cents = Math.round(cents * 100)/100 ;}
        return (((sign)?'':'-') +  num + ',' + cents);
    }
    else{
        return (((sign)?'':'-') +  num);
    }
}
    </script>

 <script type="text/javascript">
      function addCerificate(filename='') {
        $('#reqJumlahLampir').remove();
            var id = $('#tambahCerificate tr').length+1;
            var data = `<tr>
                <td><input type="file" onchange="getFileNameCertificate(this, '${id}')" name="reqLinkFileCertificate[]" multiple id="reqLinkFileCertificate${id}" class="form-control">
                  <input type="hidden" name="reqLinkFileCertificateTemp[]" id="reqLinkFileCertificateTemp${id}" value="">
                  <span style="margin-left: 50px" class="nama-temp-file" id="namaFileCertificate${id}">${filename}</span>
                </td>

                <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
            </tr>
            `;
            $("#tambahCerificate").append(data);
        }

        function getFileNameCertificate(input, id) {
            for (var i = 0; i < input.files.length; i++) {
                if(i == 0)
                    $("#namaFileCertificate"+id).html(input.files[0].name);
                else
                    addCerificate((input.files[i].name))
            }
            
        }

    </script>