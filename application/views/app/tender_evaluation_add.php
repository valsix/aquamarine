<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("MasterTenderPeriode");
$rules = new MasterTenderPeriode();
$this->load->model("MasterTenerMenus");

$reqId = $this->input->get("reqId");


    $rules->selectByParamsMonitoring(array("CAST(A.MASTER_TENDER_PERIODE_ID AS VARCHAR)" => $reqId));
    $rules->firstRow();
    $reqId = $rules->getField("MASTER_TENDER_PERIODE_ID");
    $reqTahun = $rules->getField("TAHUN");
  
    $master_tener_menus = new MasterTenerMenus();
    $master_tener_menus->selectByParamsMonitoring(array());
    $attData = array();
    while ( $master_tener_menus->nextRow()) {
        array_push($attData , strtoupper($master_tener_menus->getField('NAMA')));
    }

    $aColumns = array(
            "TENDER_EVALUATION_ID","MASTER_TENDER_PERIODE_ID","INDEX","PSC_NAME","TITLE","TENDER_NO","CLOSING","OPENING"
        );

    $aColumns= array_merge($aColumns,$attData);
        $arDataOther = array("STATUS","OWNER","BID_VALUE","TKDN","BID_BOUDS","BID_VALIDATY","NOTES","AKSI");
     $aColumns=    array_merge($aColumns,$arDataOther);


        // print_r($aColumns);exit;
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />


    <script type="text/javascript" language="javascript" class="init">
    var oTable;
    var total_usd = 0;
    var total_idr = 0;
    var reqIds;
    
    $(document).ready(function() {
        reqIds = $("#reqId").val();
        if (reqIds == '') {
            reqIds = '-0';
        }

        
        oTable = $('#example').dataTable({
            bJQueryUI: true,
            "iDisplayLength": 10,
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
            "bSort": true,
            "bProcessing": true,
            "bServerSide": false,
            "bScrollInfinite": true,
            "sAjaxSource": "web/tender_evaluation_json/json?reqId=" + reqIds,
            "fnServerParams": function(aoData) {
                // aoData.push( { "name": "input1", "value": $("#input1").val() } );
                // aoData.push( { "name": "input2", "value": $("#input2").val() } );
                // aoData.push( { "name": "input3", "value": $("#input3").val() } );
            },
            columnDefs: [{
                className: 'never',
                targets: [0,1],
                "visible": false
            }],
            // "bStateSave": true,
            // "fnStateSave": function(oSettings, oData) {
            //     localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            // },
            // "fnStateLoad": function(oSettings) {
            //     var data = localStorage.getItem('DataTables_' + window.location.pathname);
            //     return JSON.parse(data);
            // },
            "sPaginationType": "full_numbers"
            

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
        });

        $('#btnAdd').on('click', function() {
            // document.location.href = "app/index/cash_report_add";

            $('#btnProses').show();
            $('#btnProses').html('Add');
            clearForm();

        });

        $('.editing').on('click', function() {
            if (anSelectedData == "")
                return false;
            // document.location.href = "app/index/cash_report_add?reqId=" + anSelectedId;
            // alert(anSelectedData);





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

    });

    function tambahPenyebabEvaluation() {
        openAdd("app/loadUrl/app/template_load_tender_evaluasion?reqPeriode=<?=$reqId?>");
    }
    function editing(id) {
           
            var elements = oTable.fnGetData(id);
          
            openAdd("app/loadUrl/app/template_load_tender_evaluasion?reqPeriode=<?=$reqId?>&reqDetailId="+elements[0]);
       

    }

    function deleting(id){
         var elements = oTable.fnGetData(id);
          deleteData('web/tender_evaluation_json/delete',elements[0]);
    }
</script>
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

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/tender_evaluation"> Tender </a> &rsaquo; Form Tender Evaluation
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-left: 10px" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>
        <?
        if(!empty($reqId)){
        ?>
         <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-left: 10px" onclick="downloadExcel()"><i class="fa fa-file-excel-o"> </i><span> Download</span> </a>
         <?
        }
         ?>
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
                        <label for="reqClassRules" class="control-label col-md-2">Tahun</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                      <div class="col-md-11">
                                          <input class="easyui-combobox form-control" style="width:100%" name="reqTahun" id="reqTahun" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/ambil_all_tahun'" value="<?= $reqTahun ?>" />



                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <?
                    if(!empty($reqId)){
                    ?>
<div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Detail <a onclick="tambahPenyebabEvaluation()" class="btn btn-info"><i class="fa fa-fw fa-plus-square"></i></a>
                           

                        </h3>
                        
                    </div>
                    <div class="form-group">

                        <div>
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
                    <?
                    }
                    ?>
                   

                  
                    <input type="hidden" name="reqId" id="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqTipe" value="Rules" />
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

        function reload_table() {
            oTable.api().ajax.reload(null,false);
        }
        function submitForm() {
            var win = $.messager.progress({
            title: 'Office Management  | PT Aquamarine Divindo',
            msg: 'proses data...'
        });
            $('#ff').form('submit', {
                url: 'web/master_tender_periode_json/add',
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
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/tender_evaluation_add?reqId=" + datas[0]);
                    }
                     $.messager.progress('close');
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
    <script type="text/javascript">
        function tambahPenyebab(filename='') {
            var id = $('#tambahAttacment tr').length+1;
            $.get("app/loadUrl/app/tempalate_row_attacment?id="+id+"&filename="+filename, function(data) {
                $("#tambahAttacment").append(data);
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

        function downloadExcel(){
            window.location.href='app/loadUrl/app/excel_tender_evaluasi?reqId=<?=$reqId?>';
        }
    </script>


</div>
</div>