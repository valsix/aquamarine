<?
$this->load->model("Offer");
$reqId           = $this->input->get("reqId");
$offer         = new Offer();
$reqIds          = explode(',', $reqId);

$statement = '';

$offer->selectByParamsPrint(array(), -1, -1, $statement);
?>
<H1 style="text-align: center;"> OFFER REPORT </H1>
<table style="width: 100%;border-collapse: 1px solid black" border="1">
    <tr>
        <th>No </th>
        <th>No Order </th>
        <th>Perusahaan </th>
        <th>Kontak Person </th>
        <th>Vessel </th>
        <th>Tgl Servis </th>
        <th>Tipe Servis </th>
        <th>Tujuan </th>
        <th>Total Harga </th>
        <th>Status </th>
    </tr>
    <?
    $no = 1;
    while ($offer->nextRow()) {
    ?>
        <tr>
            <td><?= $no ?> </td>
            <td><?= $offer->getField('NO_ORDER') ?> </td>
            <td><?= $offer->getField('COMPANY_NAME') ?> </td>
            <td><?= $offer->getField('DOCUMENT_PERSON') ?> </td>
            <td><?= $offer->getField('VESSEL_NAME') ?> </td>
            <td><?= $offer->getField('DATESERVICE') ?> </td>
            <td><?= $offer->getField('TYPE_OF_SERVICE') ?> </td>
            <td><?= $offer->getField('DESTINATION') ?> </td>
            <td><?= $offer->getField('TOTAL_PRICE') ?> </td>
            <td><?= $offer->getField('CASE') ?> </td>

        </tr>
    <?
        $no++;
    }
    ?>

</table>