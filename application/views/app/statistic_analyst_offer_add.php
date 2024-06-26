<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Statistic");
$this->load->model("StatisticOfferTahun");
$this->load->model("StatisticDetil");

$equipment = new Statistic();
$statistic_detil = new StatisticDetil();

$reqId = $this->input->get("reqId");
$reqTahun = $this->input->get("reqTahun");
$statement =  " AND CAST(A.STATISTIC_ID AS VARCHAR) ='".$reqId."' AND A.TAHUN = '".$reqTahun."' ";

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
   
    $equipment->selectByParamsMonitoringOffer(array(),-1,-1,$statement);
    $equipment->firstRow();

    $reqDescription = $equipment->getField("DESCRIPTION");
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
  <a id="btnPdf" class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" ><i class="fa fa fa-file-pdf-o  "> </i><span> Print PDF</span> </a>
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

                   <!--  <div class="form-group">
                        <label for="reqDescription" class="control-label col-md-2">Header Text</label>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-11">


                              

                                </div>
                            </div>
                        </div>
                    </div> -->
                    <?
                    $this->load->model('StatisticOfferTahun');
                    $statistic_offer_tahun = new StatisticOfferTahun();
                    $statistic_offer_tahun->selectByParamsOfferTahun(array("A.TAHUN"=>$reqTahun)); 
                    // echo $statistic_offer_tahun->query;
                    $i=1;
                    while ( $statistic_offer_tahun->nextRow()) {
                        $statements =  " AND CAST(A.STATISTIC_ID AS VARCHAR) ='".$i."' AND A.TAHUN = '".$reqTahun."' ";

                       
                    ?>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Statistic Detail <?=$statistic_offer_tahun->getField("DESCRIPTION")?> </h3>
                    </div>


                    <table class="table table-bordered" style="width: 100%">
                        <thead>
                            <th> Description </th>
                            <th> Value </th>
                            <!-- <th> Color </th> -->
                        </thead>
                        <tbody id="tambahAksi">
                            <?
                            $statistic_detil->selectByParamsMonitoringOffer(array(),-1,-1,$statements);
                            // echo $statistic_detil->query;
                            $total=0;
                            while ($statistic_detil->nextRow()) {
                                   $total += $statistic_detil->getField('VALUE');

                            ?>
                                <tr>
                                    <td> <input type="text" name="reqDescription[]" class="easyui-validatebox textbox form-control" style="width: 100%;" value="<?= $statistic_detil->getField('DESCRIPTION') ?>">
                                        <input type="hidden" value="<?=$i?>" name="ids[]" class="grap">
                                    </td>
                                    <td> <input type="text" name="reqValue[]" class="easyui-validatebox textbox form-control" style="width: 100%;" value="<?= $statistic_detil->getField('VALUE') ?>"></td>
                                    <!-- <td> <input type="color" name="reqColor[]" class=" form-control" style="width: 50%;" value="<?= $statistic_detil->getField('COLOR') ?>" readonly></td> -->
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
                     <div id="piechart<?=$i?>"></div>    
                    <?
                    $i++;
                    }
                    ?>
                 
                    

                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                </form>
            </div>
            <div style="display: none; text-align:center;padding:5px">
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
    <script type="text/javascript">
        $('#btnPdf').on('click', function() {
           
             openAdd('app/loadUrl/report/report_statistic_pdf?reqId=<?=$reqTahun?>');
        });
    </script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript">
       $(document).ready(function() {
           setTimeout(function () {
             var ind=0;
             var inputs = $(".grap");
             $('.grap').each(function(){
                var grafikId = $(inputs[ind]).val();
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);
                function drawChart() {
                    $.getJSON("web/statistic_analyst_json/getGoogleChart?reqId="+grafikId+"&reqTahun=<?=$reqTahun?>", function(dataJSON) {
                        var data = google.visualization.arrayToDataTable(dataJSON.data);

                // Optional; add a title and set the width and height of the chart
                var options = {'title':'', 'width':'100%', 'height':600, 'legend': {position: 'labeled'}};

                // Display the chart inside the <div> element with id="piechart"
                var chart = new google.visualization.PieChart(document.getElementById('piechart'+grafikId));
                chart.draw(data, options);
                 });
                }

                ind++;
            });
         }, 1000);
       });
   </script>


 <!--    <script type="text/javascript">
        // Load google charts
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
            $.getJSON("web/statistic_analyst_json/getGoogleChart?reqId=<?=$reqId?>&reqTahun=<?=$reqTahun?>", function(dataJSON) {
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