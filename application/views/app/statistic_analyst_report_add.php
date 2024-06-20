<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("Statistic");
$this->load->model("StatisticOfferTahun");
$this->load->model("StatisticDetil");
$this->load->model(ProjectHpp);
$this->load->model('MasterLokasi');
$this->load->model("DokumenReport");

$statistic_offer_tahun = new StatisticOfferTahun();



$reqId = $this->input->get("reqId");
$reqTahun = $this->input->get("reqTahun");
$statement =  " AND A.ID ='".$reqId."'";
$arrLabel = array('SURYEVOR_SATISFACTION_SHEET','CLIENT_SATISFACTION_SHEET');
$arrKondisi = array('Excellent ( 81-100 )','Good ( 61- 80 )','Adequate ( 41- 60 )','Poor ( 21- 41 )','Very Poor ( 0- 20 )');
$arrValue =array("Preparation before working","Conduct of the team during","Knowledge of working","Concerns for safety","Performance of equipement","Performance of personnel","Overall Performance of team","etc");

if ($reqId == "") {
    $reqMode = "insert";
} else {
    $reqMode = "ubah";
    
    
        $reqIds = explode('-', $reqId);
        $report = new DokumenReport();
        $statement = "  AND TO_CHAR(A.START_DATE,'YYYY')='".$reqIds[1]."'";

        $report->selectByParams(array(),-1,-1,$statement);
        // echo $report->query;exit;
        $arrDataValue = array();
         $arrDataValue2 = array();
           $arrDataValueKategori = array();
        while ( $report->nextRow()) {
           
              $reqSurveyors = $report->getField("SURYEVOR");
              $reqSurveyorsx = json_decode($reqSurveyors,true);
                
                $reqClients = $report->getField("CLIENT");
                $reqClientsx = json_decode($reqClients,true); 
          


                for($i=0;$i<count($arrValue);$i++){

                     for($j=0;$j<5;$j++){
                         if(!empty($reqSurveyorsx['reqSurveyor'.$i.$j])){
                           
                             $arrDataValue[$arrValue[$i]] += (5-$j);
                              $arrDataValue[$j][$arrValue[$i]] += 1;
                         }

                         if(!empty($reqClientsx['reqClient'.$i.$j])){
                           $arrDataValue2[$arrValue[$i]] += (5-$j);
                             $arrDataValue2[$j][$arrValue[$i]] += 1;
                        }
                        
                     }

                }



        }

        // print_r( $arrDataValue);
        // exit;
    
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
        <?
        if(!empty($reqId)){
        ?>
  <a id="btnPdf" class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;margin-right: 10px" ><i class="fa fa fa-file-pdf-o  "> </i><span> Print PDF</span> </a>
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
                        <h3><i class="fa fa-file-text fa-lg"></i> Entry Statistic <?=$reqDescription?>
                            <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button>

                        </h3>
                        <br>
                    </div>



                    <script src="libraries/highcharts/highcharts.js"></script>
                    <script src="libraries/highcharts/exporting.js"></script>
                    <script src="libraries/highcharts/export-data.js"></script>
                    <script src="libraries/highcharts/accessibility.js"></script>

                   <!--  <figure class="highcharts-figure">
                      <div id="container"></div>
                      <p class="highcharts-description">
                       
                      </p>
                    </figure> -->

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
                   for($kk=0;$kk<count($arrLabel);$kk++){
                   ?>
                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> Statistic Detail <?=str_replace('_', ' ', $arrLabel[$kk])?> </h3>
                    </div>


                    <table class="table table-bordered" style="width: 100%">
                        <thead>
                            <th> Description </th>
                            <!-- <th> Value </th> -->

                                <th valign="center" style="text-align: center;">Excellent ( 81-100 ) <br> (Point 5)</th>
                                <th valign="center" style="text-align: center;">Good ( 61- 80 ) <br> (Point 4) </th>
                                <th valign="center" style="text-align: center;">Adequate ( 41- 60 ) <br> (Point 3) </th>
                                <th valign="center" style="text-align: center;">Poor ( 21- 41 ) <br> (Point 2)</th>
                                <th valign="center" style="text-align: center;">Very Poor ( 0- 20 ) <br> (Point 1)</th>
                            
                            <!-- <th> Color </th> -->
                        </thead>
                        <tbody id="tambahAksi">
                               <?
                                $total =0;
                                $arrTotal= array();
                                 // while ( $master_lokasi->nextRow()) {
                                    for($i=0;$i<count($arrValue);$i++){
                                    // $project_hpp =  new ProjectHpp();
                                    if($kk==0){    
                                    $stotal = ifZero($arrDataValue[$arrValue[$i]]);
                                    $total = $total+$stotal;
                                    }else{
                                         $stotal = ifZero($arrDataValue2[$arrValue[$i]]);
                                         $total = $total+$stotal;
                                    }
                                   ?>
                                   <tr>
                                    <td><input type="text" class="form-control" value="<?=$arrValue[$i]?>"> </td>
                                    <!-- <td><input type="text" class="form-control" value="<?=$stotal?>">  </td> -->
                                    <?

                                    for($mm=0;$mm<5;$mm++){
                                        $valss =0;
                                            if($kk==0){    
                                               $valss = $arrDataValue[$mm][$arrValue[$i]];
                                            }else{
                                               $valss = $arrDataValue2[$mm][$arrValue[$i]];
                                           }
                                           $arrTotal[$mm] +=$valss;
                                         $arrDataValueKategori[$arrLabel[$kk]][$mm][$i]=ifZero($valss);
                                    ?>
                                    <td valign="center" align="center"><?=$valss?> 
                                    <!-- <input type="hidden" id="<?=$arrLabel[$kk].'-'.$mm?>" value="<?=ifZero($valss)?>"> -->
                                  </td>
                                    <?
                                    }
                                    ?>
                                   </tr>
                                <?
                                }
                                ?> 
                            <tr>
                                <td align="left" style="padding-left: 20px">

                                    <input type="hidden" value="<?=$arrLabel[$kk]?>" name="ids[]" class="grap">
                                 <h3> Grand Total </h3> </td>
                                  <!-- <td  ><input type="text" class="form-control" value="<?=$total?>" > </td> -->
                                   <?
                                    for($mm=0;$mm<5;$mm++){
                                   ?> 
                                  <td valign="center" align="center" ><?=$arrTotal[$mm]?> 
                                  

                                </td>
                                  <?
                                  }
                                  ?>

                            </tr>
                            <?
                                
                            ?>
                        </tbody>
                    </table>
                    <div class="page-header">
                      <h3><i class="fa fa-file-text fa-lg"></i> Pie Chart <?=str_replace('_', ' ', $arrLabel[$kk])?></h3>
                    </div>
                   <!--  <table class="table table-bordered">
                      <thead>
                        <tr> 
                          <th> Excellent ( 81-100 )</th>
                          <th>Good ( 61- 80 )</th>
                          <th>Adequate ( 41- 60 )</th>
                          <th>Poor ( 21- 41 )</th>
                          <th>Very Poor ( 0- 20 )</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                    <tr> -->
                      <div class="row">

                    <?


                    for($kl=0;$kl<5;$kl++){
                      ?>
                      <!-- <td> -->
                        <div class="col-md-6">
                          <div class="page-header">
                            <h3><i class="fa fa-file-text fa-lg"></i>  <?=str_replace('_', ' ', $arrKondisi[$kl])?> <span class="pull-right" style="color: white;margin-right: 20px"> <p><b>TOTAL (<?=$arrTotal[$kl]?>) </b> </p> </span></h3>
                          </div>
                        <input type="hidden" id="<?=$arrLabel[$kk].'-'.$kl?>" value="<?=implode(",",$arrDataValueKategori[$arrLabel[$kk]][$kl])?>">
                        <input type="hidden" name="pier" value="<?=$arrLabel[$kk]?>-<?=$kl?>" class="groping">
<div id="piechart<?=$arrLabel[$kk]?>-<?=$kl?>"></div>
                      

                       <!-- </td> -->
                      
                     </div>

<?
                    }
                    // print_r($arrDataValueKategori);
                    ?>
                 <!--  </tr>
                      </tbody>
                    </table> -->
                  </div>
                  <div style="display: none;">
                    <div class="page-header">
                      <h3><i class="fa fa-file-text fa-lg"></i> Colomn Chart <?=str_replace('_', ' ', $arrLabel[$kk])?></h3>
                    </div>

                     <!-- <div id="piechart<?=$arrLabel[$kk]?>"></div>     -->
                    <div id="<?=$arrLabel[$kk]?>"></div>
                     <!-- <div id="container"></div> -->
                   </div>
                    <?
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
             var inputs = $(".groping");
             $('.groping').each(function(){
                var grafikId = $(inputs[ind]).val();
                var uruts = grafikId.split('-');
                var urut = uruts[1];
                // console.log(grafikId);
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);
                function drawChart() {
                    $.getJSON("web/statistic_analyst_json/getGoogleChartReport?reqId="+grafikId+"&reqUrut="+urut+"&reqTahun=<?=$reqTahun?>", function(dataJSON) {
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


 
</div>
</div>
<script type="text/javascript">
 $(document).ready(function() {
   setTimeout(function () {
    var ind=0;
     var inputs = $(".grap");
    $('.grap').each(function(){
         var grafikId = $(inputs[ind]).val();
        
         callBacks(grafikId);
      ind++;
    });

  }, 2000);
 });
</script>

<script type="text/javascript">
  function callBacks(ids){
     // console.log(ids);
     // var val1=val2,val3,val4,val5,val6,val7;
     var arras = ['Excellent ( 81-100 )','Good ( 61- 80 )','Adequate ( 41- 60 )','Poor ( 21- 41 )','Very Poor ( 0- 20 )'];
     var arrrData =new Array();
     for(var kk=0;kk<5;kk++){
      var let =$('#'+ids+'-'+kk).val();
     
      arrrData[kk] = let.split(',').map(function(item) {
    return parseInt(item, 10);
});
     }
        // console.log(arrrData[0]);

     try {
Highcharts.chart(ids, {

  chart: {
    type: 'column',
    events: {
      render() {
        let chart = this,
          stackMale = chart.yAxis[0].stacks['column,male,,'],
          stackFemale = chart.yAxis[0].stacks['column,female,,'];

        // Male label
        for (let i in stackMale) {
          let x = stackMale[i].label.x + chart.plotLeft,
            y = chart.plotHeight + chart.plotTop,
            labelBBox;
            
            console.log(stackMale[i])
          if (stackMale[i].customLabel) {
            stackMale[i].customLabel.destroy();
          }
          stackMale[i].customLabel = chart.renderer.text('Male', x, y)
            .add();
          labelBBox = stackMale[i].customLabel.getBBox();
          stackMale[i].customLabel.translate(-labelBBox.width / 2, labelBBox.height)
        }
        
        // Female label
        for (let i in stackFemale) {
          let x = stackFemale[i].label.x + chart.plotLeft,
            y = chart.plotHeight + chart.plotTop,
            labelBBox;
          if (stackFemale[i].customLabel) {
            stackFemale[i].customLabel.destroy();
          }
          stackFemale[i].customLabel = chart.renderer.text('Female', x, y)
            .add();
          labelBBox = stackFemale[i].customLabel.getBBox();
          stackFemale[i].customLabel.translate(-labelBBox.width / 2, labelBBox.height)
        }

      }
    }
  },

  title: {
    text: ''+ids.replaceAll('_'," ")
  },

  xAxis: {
    categories: ["Preparation before working","Conduct of the team during","Knowledge of working","Concerns for safety","Performance of equipement","Performance of personnel","Overall Performance of team","etc"],
    offset: 30
  },

  yAxis: {
    allowDecimals: false,
    min: 0,
    title: {
      text: ''+ids.replaceAll('_'," ")
    },
    stackLabels: {
      enabled: true,
      style: {
        fontWeight: 'bold'
      },
      formatter: function() {
        var val = this.total;
        if (val > 0) {
          return val;
        }
        return '';
      },

    }
  },

  tooltip: {
    formatter: function() {
      return '<b>' + this.x + '</b><br/>' +
        this.series.name + ': ' + this.y + '<br/>' +
        'Total: ' + this.point.stackTotal;
    }
  },

  plotOptions: {
    column: {
      stacking: 'normal'
    }
  },

  series: [{
    name: 'Excellent ( 81-100 )',
    data:  arrrData[0],
    stack: 'male'
  }, {
    name: 'Good ( 61- 80 )',
    data: arrrData[1],
    stack: 'male'
  }, {
    name: 'Adequate ( 41- 60 )',
    data: arrrData[2],
    stack: 'male'
  }, {
    name: 'Poor ( 21- 41 )',
    data: arrrData[3],
    stack: 'male'
  }, {
    name: 'Very Poor ( 0- 20 )',
    data: arrrData[4],
    stack: 'male'
  }]
});
}
catch(err) {
}
}
</script>