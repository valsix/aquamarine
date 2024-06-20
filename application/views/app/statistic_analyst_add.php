<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Statistic");
$this->load->model("StatisticDetil");

$equipment = new Statistic();
$statistic_detil = new StatisticDetil();

$reqId = $this->input->get("reqId");
 $statement =  " AND CAST(A.STATISTIC_ID AS VARCHAR) ='".$reqId."'";

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
   
    $equipment->selectByParamsMonitoring(array(),-1,-1,$statement);
    $equipment->firstRow();

    $reqDescription = $equipment->getField("DESCRIPTION");
     $reqTahun = $equipment->getField("TAHUN");
    // $reqValue = $equipment->getField("VALUE");
    // $reqColor = $equipment->getField("COLOR");
}
?>

<!--// plugin-specific resources //-->
<script src='libraries/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='libraries/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="css/gaya-multifile.css" type="text/css" />

<div class="col-md-12">

    <div class="judul-halaman"> <a href="app/index/statistic_analyst">Monitoring Document of Statistic Analyst</a> &rsaquo; Form Monitoring Document of Statistic Analyst
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>
                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry Statistic
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Header Text</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">


                                    <input type="text" name="reqHeader" class="easyui-validatebox textbox form-control" id="reqValue" style="width: 100%;" value="<?= $reqDescription; ?>">


                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Tahun</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">
                                          <input class="easyui-combobox form-control" style="width:100%" name="reqTahun" id="reqTahun" data-options="width:'250',editable:false, valueField:'id',textField:'text',url:'combo_json/ambil_all_tahun'" value="<?= $reqTahun ?>" />



                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Statistic Detail </h3>
                    </div>


                    <table class="table table-bordered" style="width: 100%">
                        <thead>
                            <th> Description <a onclick="tambahPenyebab()" id="btnPenyebab" class="btn btn-info"><i class="fa fa-plus-square"></i></a> </th>
                            <th> Value </th>
                            <th> Color </th>
                            <th> Aksi </th>
                        </thead>
                        <tbody id="tambahAksi">
                            <?
                            $statistic_detil->selectByParamsMonitoring(array(),-1,-1,$statement);
                            // echo $statistic_detil->query;
                             $total=0;
                            while ($statistic_detil->nextRow()) {
                                  $total += $statistic_detil->getField('VALUE');


                            ?>
                                <tr>
                                    <td> <input type="text" name="reqDescription[]" class="easyui-validatebox textbox form-control" style="width: 100%;" value="<?= $statistic_detil->getField('DESCRIPTION') ?>">

                                    </td>
                                    <td> <input type="text" onkeypress='validate(event)' name="reqValue[]" class="easyui-validatebox textbox form-control" style="width: 100%;" value="<?= $statistic_detil->getField('VALUE') ?>"></td>
                                    <td> <input type="color" name="reqColor[]" class=" form-control" style="width: 50%;" value="<?= $statistic_detil->getField('COLOR') ?>"></td>
                                    <td><a onclick="$(this).parent().parent().remove();"><i class="fa fa-trash fa-lg"></i></a> </td>
                                </tr>
                            <?
                            }
                            ?>
                              <tr>
                                <td align="left" style="padding-left: 20px"> <h3> Grand Total </h3> </td>
                                  <td ><input type="text" class="form-control" value="<?=$total?>" > </td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="piechart"></div>    
                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
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
        function submitForm() {
            $('#ff').form('submit', {
                url: 'web/statistic_analyst_json/add_new',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    //alert(data);
                    var datas = data.split('-');
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/statistic_analyst_add?reqId=" + datas[0]);
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }
    </script>
    <script type="text/javascript">
        function tambahPenyebab() {
            $.get("app/loadUrl/app/tempalate_row_statistic?", function(data) {
                $("#tambahAksi").append(data);
            });
        }
    </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script type="text/javascript">
        // Load google charts
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
            $.getJSON("web/statistic_analyst_json/singleChar?reqId=<?=$reqId?>", function(dataJSON) {
                var data = google.visualization.arrayToDataTable(dataJSON.data);

                // Optional; add a title and set the width and height of the chart
                var options = {'title':'', 'width':'100%', 'height':600, 'legend': {position: 'labeled'}};

                // Display the chart inside the <div> element with id="piechart"
                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
            });

            
        }
    </script> -->
</div>
</div>