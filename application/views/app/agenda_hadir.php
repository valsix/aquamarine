<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$this->load->model("Slider");
$slider = new Slider();

$reqId = $this->input->get("reqId");

if($reqId == ""){
$reqMode = "insert";
$reqTanggal = date("d-m-Y");
$reqJam = date("H:i");
}
else
{
	$reqMode = "ubah";
	$slider->selectByParams(array("A.SLIDER_ID" => $reqId, "JENIS" => "AGENDA"));
	$slider->firstRow();
	$reqId             = $slider->getField("SLIDER_ID");
	$reqTipe       		= $slider->getField("TIPE");
	$reqTanggal                  = $slider->getField("HARI");
	$reqJam                  = $slider->getField("JAM");
	$reqNama                    = $slider->getField("NAMA");
	$reqKeterangan              = $slider->getField("KETERANGAN");
	$reqLinkFile				= $slider->getField("LINK_FILE");
}
?>

<!--// plugin-specific resources //--> 
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script> 
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script> 
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script> 
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css">

<div class="col-md-12">
    
  <div class="judul-halaman"> <a href="app/index/agenda">Agenda</a> &rsaquo; Daftar Hadir</div>   

    <div class="konten-area">
    	<div class="konten-inner">
            <div>
            	
                <!--<div class='panel-body'>-->
		        <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Informasi</h3>                      
                    </div>
                                      

                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">Tanggal Kegiatan</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                	<table style="color:#fff">
                                    <tr>
                                    <td>
                                   	<?=$reqTanggal?>
                                    </td>
                                    <td>&nbsp;&nbsp;</td>
                                    <td>
                                    <?=$reqJam?>
                                	</td>
                                    </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">Kegiatan</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <span style="color:#fff"><?=$reqNama ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqKeterangan" class="control-label col-md-2">Keterangan</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                   <span style="color:#fff"><?=$reqKeterangan ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                      
              </form>
			</div>
                
                <?
                if($reqId == "")
				{}
				else
				{s
				?>
                <div class="page-header">
                    <h3><i class="fa fa-file-text fa-lg"></i>DAFTAR HADIR
                    </h3>                      
                </div> 
                
                <div style="text-align:center;padding:5px">
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="cetak()">Cetak Daftar Hadir</a>
                </div>
                
                <div class="form-group" id="divHeader">
                    <div class='col-md-12'>
                        <div class='form-group'>
                            <div class='col-md-12'>
                                                                               
                                <table class="table">
                                <thead>
                                  <tr>
                                    <th style="width:10%">NRP</th>
                                    <th style="width:10%">NO SEKAR</th>
                                    <th style="width:25%">NAMA </th>
                                    <th style="width:25%">UNIT KERJA </th>
                                    <th style="width:25%">JAM</th>
                                  </tr>
                                </thead>
                                <tbody id="tbodyKomentar" style="color:#FFFFFF"> 
                                <?
                                $slider_komentar = new Slider();
								$slider_komentar->selectByParamsHadir(array("A.SLIDER_ID" => $reqId));
								while($slider_komentar->nextRow())
								{
								?>
                                     <tr>
                                        <td><?=$slider_komentar->getField("PEGAWAI_ID")?></td>
                                        <td><?=$slider_komentar->getField("NO_SEKAR")?></td>
                                        <td><?=$slider_komentar->getField("NAMA")?></td>
                                        <td><?=$slider_komentar->getField("CABANG")?></td>
                                        <td><?=$slider_komentar->getField("JAM_HADIR")?></td>
                                     </tr>
                                <?
								}
								?>
                                 </tbody>  
                              </table>
                            </div>
                        </div>
                    </div>
                </div>                                        
                
                <?
				}
				?>
            
            </div>
            
        </div>
        
		<script>
            function cetak(){
				document.location.href = "login/loadUrl/report/daftar_hadir/?reqId=<?=$reqId?>";
            }
						
        </script>
    
    </div>        
    
</div>



