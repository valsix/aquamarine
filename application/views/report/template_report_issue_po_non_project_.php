<?
$this->load->model("IssuePoNonProject");
$issue_po = new IssuePoNonProject();
$this->load->model("IssuePoNonProjectDetail");
$issue_po_detail = new IssuePoNonProjectDetail();

$reqId = $this->input->get("reqId");
$statement = " AND A.ISSUE_PO_ID = " . $reqId;
$issue_po->selectByParamsMonitoring(array(), -1, -1, $statement);
// echo $issue_po->query;exit;
$issue_po->firstRow();

$reqId   = $issue_po->getField("ISSUE_PO_ID");
$reqNomerPo     = $issue_po->getField("NOMER_PO");
$reqPoDate      = $issue_po->getField("PO_DATE1");
$reqDocLampiran = $issue_po->getField("DOC_LAMPIRAN");
$reqReferensi   = $issue_po->getField("FINANCE");
$reqPathLampiran = $issue_po->getField("PATH_LAMPIRAN");
$reqFinance     = $issue_po->getField("REFERENSI");
$reqCompanyId   = $issue_po->getField("COMPANY_ID");
$reqCompanyName = $issue_po->getField("COMPANY_NAME");
$reqContact     = $issue_po->getField("CONTACT");
$reqAddress     = $issue_po->getField("ADDRESS");
$reqEmail       = $issue_po->getField("EMAIL");
$reqTelp        = $issue_po->getField("TELP");
$reqFax         = $issue_po->getField("FAX");
$reqHp          = $issue_po->getField("HP");
$reqBuyerId     = $issue_po->getField("BUYER_ID");
$reqOther       = $issue_po->getField("OTHER");
$reqPpn         = $issue_po->getField("PPN");
// echo $reqPpn;exit;
$reqPpnPercent  = $issue_po->getField("PPN_PERCENT");

$reqPic         = $issue_po->getField("PIC");
$reqDepartement = $issue_po->getField("DEPARTEMENT");
$reqNote        = $issue_po->getField("NOTE");

$reqTypePo = $issue_po->getField("TYPE");
$reqAcknowledgedBy = $issue_po->getField("ACKNOWLEDGED_BY");
$reqAcknowledgedDept = $issue_po->getField("ACKNOWLEDGED_DEPT");
$reqApproved1By = $issue_po->getField("APPROVED1_BY");
$reqApproved1Dept = $issue_po->getField("APPROVED1_DEPT");
$reqApproved2By = $issue_po->getField("APPROVED2_BY");
$reqApproved2Dept = $issue_po->getField("APPROVED2_DEPT");

$reqTypeCur     = $issue_po->getField("TYPE_CUR");
$reqInisial     = $issue_po->getField("INISIAL");
$reqCur     = $issue_po->getField("CUR");
$reqFormat     = $issue_po->getField("FORMAT");

?>
<h1 style="font-size: 18px;text-align: center;font-family: Calibri"><u> <b> <?=$reqTypePo?> </b> </u><br>
</h1>

<div style="padding-left: 20px; padding-right: 20px">

<div class="row">
    <div class="col">
        <table border="1" style="font-size: 14px; width: 100%; border-collapse: 1px solid black;font-family: Calibri">
            <thead>
                <tr>
                    <th>Doc Name</th>
                    <th>Doc #</th>
                    <th>Reference</th>
                    <th>PO Date</th>
                    <th>PO#</th>
                    <th>Finance Ref</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="text-align: center; width: 16%" valign="top">Purchase Order </td>
                    <td style="text-align: center; width: 10%" valign="top"><?=$reqDocLampiran?> </td>
                    <td style="text-align: center; width: 20%" valign="top"><?=$reqReferensi ?></td>
                    <td style="text-align: center; width: 10%" valign="top"><?=$reqPoDate?></td>
                    <td style="text-align: center; width: 30%" valign="top">No: <?=$reqNomerPo?></td>
                    <td style="text-align: center; width: 14%" valign="top"><?=$reqFinance?></td>
                </tr>
               
            </tbody>
        </table>
    </div>
</div>

<br>

<div class="row">
    <div class="col">
        <table border="1" style="font-size: 14px; width: 100%; border-collapse: 1px solid black;font-family: Calibri">
            <thead>
                <tr>
                    <th>To</th>
                    <th>Originator/Buyers</th>
                    <th>Delivery to</th>
                </tr>
            <tbody>
                <tr>
                    <td style="padding-left: 5px; width: 35%" valign="top">
                        
                       
                        <ul>
                            <span><b> <?=$reqCompanyName?> </b></span>
                        </ul>
                        <ul>
                            <span> <?=$reqAddress?> </span>
                        </ul>
                        
                        <ul>
                            <span><b> PIC : <b><?=$reqContact ?> </span>
                        </ul>
                        <ul>
                            <span>M. Phone : <?=$reqTelp?></span>
                        </ul>
                    </td>
                    <td style="padding-left: 5px; width: 23%" valign="top" >

                     <?     $arrDatas  = array(
                                                "General Manager",
                                                "Finance Manager",
                                                "Marketing Manager",
                                                "Operation Manager",
                                                "Finance Support",
                                             "Others"
                             );
                     $other=false;

                     for ($i = 0; $i < count($arrDatas); $i++) {
                                                            $checked = '';
                                                            $reqBuyerId = explode(",", $reqBuyerId);
                                                            for ($j = 0; $j < count($reqBuyerId); $j++) {
                                                                if ($arrDatas[$i] == $reqBuyerId[$j]) {
                                                                    $checked = 'checked="checked"';
                                                                    if($reqBuyerId[$j]=='Others'){
                                                                            $other=true;
                                                                    }
                                                                }
                                                            }
                        ?>
                        <ul><input type="checkbox" <?=$checked?> /> <?=$arrDatas[$i]?> <br> </ul>
                    <?                                  
                    }                                      
                    ?>
                     <br>
                     <?
                     $text='___________';
                     if($other){
                        $text =$reqOther;
                     }
                     ?>
                    <div style="margin-left: 300px">
                        &nbsp;&nbsp;&nbsp;&nbsp;<u> <?=$text?> </u>
                     <div>
                     
                    </td>
                    <td style="padding-left: 5px; width: 49%; vertical-align: top;">
                        <ul>
                            <span> PT. Aquamarine Divindo Inspection </span>
                        </ul>
                        <ul>
                            <span><b> KOMPLEK PERGUDANGAN 88 BLOK C5-C7 </b></span>
                        </ul>
                        <ul>
                            <span> Jl. Raya Sedati Gede No.88 Sedati Juanda, Sidoarjo, East Java – Indonesia, 61253 </span>
                        </ul>
                        <ul>
                            <span ><b> PIC : <?=$reqPic?> </b></span>
                        </ul>
                        <ul>
                            <span > Dept : <?=$reqDepartement?> </span>
                        </ul>
                        <br>
                        <br>
                    </td>
                </tr>
            </tbody>
            </thead>
        </table>
    </div>
</div>

<br>

<div class="row">
    <div class="col">
        <table border="1" style="font-size: 14px; width: 100%; border-collapse: 1px solid black;font-family: Calibri">
            <thead>
                <tr>
                    <th>No</th>
                    <th>QTY</th>
                    <th>DESCRIPTION</th>
                    <th>UNIT PRICE (<?=$reqCur?>)</th>
                    <th>TOTAL (<?=$reqCur?>)</th>
                    
                </tr>
            </thead>
            <tbody>
                <?
                $no=1;
               
                $total_keseluruhan_amount =0;
                $total_keseluruhan_total =0;
                $issue_po_detail = new IssuePoNonProjectDetail();
                $total_row = $issue_po_detail->getCountByParamsMonitoring(array("A.ISSUE_PO_ID"=>$reqId));
                $total_rows= 10-$total_row;

                $issue_po_detail = new IssuePoNonProjectDetail();

                 $issue_po_detail->selectByParamsMonitoring(array("A.ISSUE_PO_ID"=>$reqId));
                 // echo $issue_po_detail->query;exit;
                while ( $issue_po_detail->nextRow()) {
                    $total_keseluruhan_amount +=$issue_po_detail->getField("AMOUNT");
                     $total_keseluruhan_total +=$issue_po_detail->getField("TOTAL");
                    # code...
                
                ?>
                <tr>
                    <td style="vertical-align: top;text-align:center;width: 5%">
                       <?=$no?>
                    </td>

                    <td style="vertical-align: top; text-align:center;width: 10%">
                       <?=$issue_po_detail->getField("QTY")?><?=$issue_po_detail->getField("SATUAN")?>
                    </td>

                    <td style="padding-left: 5px;">
                       <?=$issue_po_detail->getField("KETERANGAN")?>
                        <br>
                         <br>
                          <?=$issue_po_detail->getField("TERM")?>

                    </td>

                    <td style="vertical-align: top; text-align:right;width: 20%">
                      <?=currencyToPage2($issue_po_detail->getField("AMOUNT"))?>
                    </td>

                    <td style="vertical-align: top; text-align:right;width: 20%">
                        <?=currencyToPage2($issue_po_detail->getField("TOTAL"))?>
                    </td>

                   
                </tr>
                <?
                $no++;
                }
                ?>
               <!--  <?
                for($i=0;$i<$total_rows;$i++){
                    ?>
                    <tr>
                        <td> &nbsp;</td>
                         <td>&nbsp; </td>
                          <td> &nbsp;</td>
                           <td>&nbsp; </td>
                            <td> &nbsp;</td>
                    </tr>
                    <?
                }
                ?> -->
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: center;">SubTotal </td>
                    <!-- <td style="text-align: center;"><?=currencyToPage2($total_keseluruhan_amount)?> </td> -->
                    <td style="text-align: center;"></td>
                    <td style=" text-align:right;"><?=currencyToPage2($total_keseluruhan_total)?> </td>
                    
                </tr>
                <tr>
                    <?
                     $percent_amount=0;
                     $percent_total=0;
                    //if($reqPpn==1){
                    //      $percent_amount =($total_keseluruhan_amount*$reqPpnPercent)/100;
                    //      $percent_total =($total_keseluruhan_total*$reqPpnPercent)/100;
                    //}
                  
                    ?>
                   <!--  <td colspan="3" style="text-align: center;">Vat <?=$reqPpnPercent?>% (include) </td> -->
                    <!-- <td style="text-align: center;"> <?=currencyToPage2($percent_amount)?></td> -->
                    <!-- <td style="text-align: center;"> </td> -->
                    <!-- <td style="text-align:center;"><?=currencyToPage2($percent_total)?></td> -->
                     
                </tr>
                <?
                     $percent_amount =($total_keseluruhan_amount*$reqPpnPercent)/100;
                    $percent_total =($total_keseluruhan_total*$reqPpnPercent)/100;
                ?>
                <tr>
                    <td colspan="3" style="text-align: center;">Vat <?=$reqPpnPercent?>% (include) </td>
                    <!-- <td style="text-align: center;"> <?=currencyToPage2($percent_amount)?></td> -->
                    <td style="text-align: center;"> </td>
                    <td style="text-align:right;"><?=currencyToPage2($percent_total)?></td>

                </tr>
                <tr>
                    <?
                    $total_amount=$total_keseluruhan_amount+$percent_amount;
                    $total_total=$total_keseluruhan_total+$percent_total;
                    ?>
                    <td colspan="3" style="text-align: center;"><b> Total Payable </b></td>
                    <!-- <td style="text-align: center;"> <?=currencyToPage2($total_amount)?></td> -->
                    <td style="text-align: center;"></td>
                    <td style=" text-align:right;"><b> <?=currencyToPage2($total_total)?> </b></td>
                   
                </tr>
                <tr>
                    <td colspan="5" style="text-align: center;"><b><em> ## 
                        <?
                        if($reqTypeCur=='1'){
                            echo kekata($total_total);
                        }else{
                            echo kekata_eng($total_total);
                        }
                        ?>
                     <?=$reqFormat?> ## </em></b></td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: left; padding: 5px">
                        <b><u>Note: </u></b>
                        <br>
                        <?=$reqNote?>
                    </td>
                </tr>
            </tfoot>
            </tbody>
        </table>
    </div>
</div>

<br>

<div class="row">
    <div class="col">
        <table border="1" style="font-size: 14px; width: 100%; border-collapse: 1px solid black;font-family: Calibri">
            <tr>
                <td style="text-align: center;width: 25%">
                    <ul>
                        <span> Requested by,</span>
                    </ul>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <ul>
                        <span ><u> <?=$reqPic?> </u></span>
                    </ul>
                    <ul>
                       <?=$reqDepartement?>
                    </ul>

                </td>
                <td style="text-align: center;width: 25%">
                    <ul>
                        <span>Acknowledged by,</span>
                    </ul>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <ul>
                        <span><u> <?=$reqAcknowledgedBy?> </u></span>
                    </ul>
                    <ul>
                        <span><?=$reqAcknowledgedDept?></span>
                    </ul>

                </td>
                <td style="text-align: center;width: 25%">
                    <ul>
                        <span>Approved by,</span>
                    </ul>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <ul>
                        <span><u><?=$reqApproved1By?></u></span>
                    </ul>
                    <ul>
                        <span><?=$reqApproved1Dept?></span>
                    </ul>

                </td>
                <td style="text-align: center;width: 25%">
                    <ul>
                        <span> Approved by,</span>
                    </ul>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <ul>
                        <span><u><?=$reqApproved2By?></u></span>
                    </ul>
                    <ul>
                        <span><?=$reqApproved2Dept?></span>
                    </ul>

                </td>
            </tr>
        </table>
    </div>
</div>

<br>
<br>

<div class="row">
    <div class="col">
        <table border="1" style="font-size: 14px; width: 100%;  border-collapse: 1px solid black;font-family: Calibri">
            <tr>
                <td>Note:
                    <ol>
                        <li>The vendor/supplier must confirm receipt of this Purchase Order to the buyer.</li>
                        <li>All payments will be made based on the Purchase Order's purchase approval.</li>
			<li>Please stipulate each ID No. of the spares, within the invoice packing list.</li>
                    </ol>
                </td>
            </tr>
        </table>
    </div>
</div>
</div>