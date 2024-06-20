    <?
$fileName = 'print_cash_report.xls';
$aColumns = array( "TANGGAL", "KETERANGAN",  "DEBET ( Rp. )", "KREDIT ( Rp. )", "BALENCE ( Rp. )", "STATUS", "ID_DETAIL");
$aColumns_alias = array("TANGGAL","KETERANGAN","DEBET","KREDIT","SALDO","CASH_REPORT_ID","CASH_REPORT_DETIL_ID");

header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Content-Type: application/vnd.ms-excel;");

//  $company->selectByParamsMonitoring(array(), -1, -1, $statement);
$this->load->model("CashReportDetil");
$cash_report_detil = new CashReportDetil();
$reqId = $this->input->get("reqId");
$cash_report_detil->selectByParamsMonitoring(array("A.CASH_REPORT_ID"=>$reqId));
?>
<H1 style="text-align: center;">  REPORT CASH REPORT </H1>
<table style="width: 100%;border-collapse: 1px solid black" border="1">
  <thead>
    <tr>
        <th>NO</th>
        <?php
        for ($i = 0; $i < count($aColumns); $i++) {
            ?>
            <th><?= str_replace('_', ' ', $aColumns[$i])  ?></th>
            <?php

        };
        ?>
    </tr>

</thead>
<tbody>
    <?
    $nomer=1;
    while ($cash_report_detil->nextRow()) {
        echo '<tr>';
          echo '<td>'.$nomer.'</td>';

         for ($i = 0; $i < count($aColumns_alias); $i++) {
            echo '<td>';
                if ($aColumns_alias[$i] == "DESKRIPSI"){
                    echo   truncate($cash_report_detil->getField($aColumns_alias[$i]), 2);
                }else if ($aColumns_alias[$i] == "DEBET"){
                    echo currencyToPage2($cash_report_detil->getField($aColumns_alias[$i]));
                }else if ($aColumns_alias[$i] == "KREDIT"){
                    echo currencyToPage2($cash_report_detil->getField($aColumns_alias[$i]));
                }else if ($aColumns_alias[$i] == "SALDO"){
                    echo currencyToPage2($cash_report_detil->getField($aColumns_alias[$i]));
                }else{
                        echo $cash_report_detil->getField($aColumns_alias[$i]);
                }
                echo '</td>';
            }
              echo  '</tr>';     
        $nomer++;
    }
    ?>
</tbody>
</table>