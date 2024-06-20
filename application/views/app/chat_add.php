<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");


$reqId = $this->input->get("reqId");

$this->load->model("Pegawai");
$pegawai = new Pegawai();
$pegawai->selectByParams(array("A.PEGAWAI_ID" => $reqId));
$pegawai->firstRow();

?>

<!--// plugin-specific resources //--> 

  <style type="text/css">
    #daddy-shoutbox {
      padding: 5px;
      background: #3E5468;
      color: white;
      width: 100%;
      font-family: Arial,Helvetica,sans-serif;
      font-size: 11px;
    }
    .shoutbox-list {
      border-bottom: 1px solid #627C98;
      
      padding: 5px;
      display: none;
    }
    #daddy-shoutbox-list {
      text-align: left;
      margin: 0px auto;
    }
    #daddy-shoutbox-form {
      text-align: left;
      
    }
    .shoutbox-list-time {
      color: #8DA2B4;
    }
    .shoutbox-list-nick {
      margin-left: 5px;
      font-weight: bold;
    }
    .shoutbox-list-message {
      margin-left: 5px;
    }
    
  </style>
<script type="text/javascript" src="libraries/shoutbox2/javascript/jquery.js"></script>
<script type="text/javascript" src="libraries/shoutbox2/javascript/jquery.form.js"></script>
<div class="col-md-12">
    
  <div class="judul-halaman"> <a href="app/index/chat">Chat</a> &rsaquo; Balas Chat</div>   

    <div class="konten-area">
    	<div class="konten-inner">
            <div>
            	
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                <!--<div class='panel-body'>-->
		        <!--<form class='form-horizontal' role='form'>-->
	                
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Data</h3>                      
                    </div>
                                      

                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">NRP</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <label class="control-label control-label-text"><?=$pegawai->getField("PEGAWAI_ID")?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">Pegawai</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <label class="control-label control-label-text"><?=$pegawai->getField("NAMA")?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">Jabatan</label>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <label class="control-label control-label-text"><?=$pegawai->getField("JABATAN")?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqNama" class="control-label col-md-2">Unit Kerja</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <label class="control-label control-label-text"><?=$pegawai->getField("CABANG")?></label>
                                </div>
                            </div>
                        </div>
                    </div>
            		</form>
                    
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>PERCAKAPAN
                        </h3>                      
                    </div>       
                    <div class="form-group">
                        <div class='col-md-12'>
                            <div class='form-group'>
                                <div class='col-md-12'>


                                     <div id="daddy-shoutbox">
                                        <div id="daddy-shoutbox-list"></div>
                                        <br />
            							<form id="daddy-shoutbox-form" action="web/chat_json/php_shoutbox/?action=add" method="post"> 
                                        <input type="hidden" name="nickname" value="Operator" /> 
                                        <input type="hidden" name="reqPegawaiId" value="<?=$reqId?>" readonly /> 
                                        <input type="hidden" name="reqHalaman" value="0" readonly />
                                        <input type="hidden" name="reqKode" value="0" readonly />
                                        Operator: <input id="btn-input" class="form-control" type="text" name="message" style="width:400px !important" />
                                        <input type="submit" value="Submit" class="btn btn-primary" style="color:#000" />
                                        <span id="daddy-shoutbox-response"></span>
                                        </form>
                                      </div>
                            
                                      <script type="text/javascript">
                                            var count = 0;
                                            var files = 'libraries/shoutbox2/';
                                            var lastTime = 0;
                                            
                                            function prepare(response) {
                                              var d = new Date();
                                              count++;
                                              d.setTime(response.time*1000);
                                              var mytime = d.getHours()+':'+d.getMinutes()+':'+d.getSeconds();
                                              var string = '<div class="shoutbox-list" id="list-'+count+'">'
                                                  + '<span class="shoutbox-list-time">'+response.waktu+'</span>'
                                                  + '<span class="shoutbox-list-nick">'+response.nickname+':</span>'
                                                  + '<span class="shoutbox-list-message">'+response.message+'</span>'
                                                  +'</div>';	
                                              return string;
                                            }
                            
                            
                                            function success(response, status)  { 
                                              if(status == 'success') {
                                                lastTime = response.time;
                                                $('#daddy-shoutbox-response').html('<img src="'+files+'images/accept.png" />');
                                                $('#daddy-shoutbox-list').append(prepare(response));
                                                $('#btn-input').val('');
                                                $('#btn-input').focus();										
                                                $('#list-'+count).fadeIn('slow');
                                                timeoutID = setTimeout(refresh, 3000);
                                              }
                                            }
                                            
                                            function validate(formData, jqForm, options) {
                                              for (var i=0; i < formData.length; i++) { 
                                                  if (!formData[i].value) {
                                                      alert('Please fill in all the fields'); 
                                                      $('input[@name='+formData[i].name+']').css('background', 'red');
                                                      return false; 
                                                  } 
                                              } 
                                              $('#daddy-shoutbox-response').html('<img src="'+files+'images/loader.gif" />');
                                              clearTimeout(timeoutID);
                                            }
                                    
                                            function refresh() {
                                              $.getJSON("web/chat_json/php_shoutbox/?reqId=<?=$reqId?>&action=view&time="+lastTime, function(json) {
                                                if(json.length) {
                                                  for(i=0; i < json.length; i++) {
                                                    $('#daddy-shoutbox-list').append(prepare(json[i]));
                                                    $('#list-' + count).fadeIn('slow');
                                                  }
                                                  var j = i-1;
                                                  lastTime = json[j].time;
                                                }
                                                //alert(lastTime);
                                              });
                                              timeoutID = setTimeout(refresh, 3000);
                                            }
                                            
                                            // wait for the DOM to be loaded 
                                            $(document).ready(function() { 
                                                var options = { 
                                                  dataType:       'json',
                                                  beforeSubmit:   validate,
                                                  success:        success
                                                }; 
                                                $('#daddy-shoutbox-form').ajaxForm(options);
                                                timeoutID = setTimeout(refresh, 100);
                                            });
                                      </script> 


                                </div>
                            </div>
                        </div>
                    </div>    

                    
            </div>
        </div>
        
    </div>        
    
</div>



