<?
$this->load->model("IssuePo");
$issue_po = new IssuePo();
$this->load->model("IssuePoDetail");
$issue_po_detail = new IssuePoDetail();

$reqId = $this->input->get("reqId");
$statement = " AND A.ISSUE_PO_ID = " . $reqId;
$issue_po->selectByParamsMonitoring(array(), -1, -1, $statement);
$issue_po->firstRow();

$reqId   = $issue_po->getField("ISSUE_PO_ID");
$reqNomerPo     = $issue_po->getField("NOMER_PO");
$reqPoDate      = $issue_po->getField("PO_DATE");
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
$reqPpnPercent  = $issue_po->getField("PPN_PERCENT");

$reqPic         = $issue_po->getField("PIC");
$reqDepartement = $issue_po->getField("DEPARTEMENT");
$reqTermsAndCondition = $issue_po->getField("TERMS_AND_CONDITION");

$reqTermsAndCondition = str_replace('<table>', '<table style="font-size: 14px;font-family: Calibri">', $reqTermsAndCondition);

?>
<h1 style="font-size: 18px;text-align: center;font-family: Calibri"><u> <b> PURCHASE ORDER (PO) </b> </u><br>
</h1>

<div style="padding-left: 20px; padding-right: 20px">
<br>


<div class="row">
    <div class="col">
        <table border="1" style="font-size: 14px; width: 100%; border-collapse: 1px solid black;font-family: Calibri">
            <thead>
                <tr>
                    <th>Doc Name</th>
                    <th>Doc #</th>
                    <th>Reference</th>
                    <th>SO Date</th>
                    <th>SO#</th>
                    <th>Finance Ref</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="text-align: center; width: 16%" valign="top">Service Order </td>
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
        <table style="font-size: 14px; width: 100%; text-align: center;font-family: Calibri">
            <tr>
                <td>
                    Terms and Conditions
                </td>
            </tr>
        </table>
    </div>
</div>

<br>

<div class="row">
    <div class="col">
        <?=$reqTermsAndCondition?>
        <!-- <table style="font-size: 14px;font-family: Calibri">
            <tr>
                <td style="width: 3%;">1.</td>
                <td style="width: 20%;">Price in </td>
                <td>:</td>
                <td style="width: 75%; color:red;">IDR</td>
            </tr>
            <tr>
                <td>2.</td>
                <td>Lingkup kerja </td>
                <td>:</td>
                <td style="color:red;">HSD Solar B20</td>
            </tr>
            <tr>
                <td>3.</td>
                <td>Payment Terms</td>
                <td>:</td>
                <td style="color:red;">Pembayaran 3 hari setelah supply solar & Invoice diterima</td>
            </tr>
            <tr>
                <td>4.</td>
                <td>Delivery time </td>
                <td>:</td>
                <td style="color:red;">Maksimal 3 hari setelah PO diterbitkan</td>
            </tr>
            <tr>
                <td>5.</td>
                <td>Applicable Taxes </td>
                <td>:</td>
                <td style="color:red;"> PPn 10% & Perijinan (include)</td>
            </tr>
            <tr>
                <td>6.</td>
                <td>Exclude </td>
                <td>:</td>
                <td style="color:red;">-</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">7.</td>
                <td style="vertical-align: top;">Others </td>
                <td style="vertical-align: top;">:</td>
                <td style="color:red; vertical-align: top;">
                    <ul>
                        <span>PO Number must be referenced on all documents </span>
                    </ul>
                    <ul>
                        <span>(delivery order, invoice, etc) </span>
                    </ul>
                    <ul>
                        <span> Any questions pertaining to this PO payment status to be addressed </span>
                    </ul>
                    <ul>
                        <span> to our Finance/Accounting : isnaini.aquamarine@gmail.com with cc </span>
                    </ul>
                    <ul>
                        <span> to operation@aquamarine.id, inspection@aquamarine.id </span>
                    </ul>
                </td>
            </tr>
        </table> -->
    </div>
</div>

<br>
<br>

<div class="row">
    <div class="col">
        <table border="1" style="font-size: 14px; border-collapse: 1px solid black;font-family: Calibri">
            <tr>
                <td style="width: 200px; padding-left: 5px;">
                    <ul>
                        <span>Accepted by</span>
                    </ul>
                    <ul>
                        <span> Vendor/Supplier</span>
                    </ul>
                </td>
                <td style="width: 200px;"></td>
            </tr>

            <tr>
                <td style="height: 100px;"></td>
                <td style="height: 100px;"></td>
            </tr>
        </table>
    </div>
</div>
</div>