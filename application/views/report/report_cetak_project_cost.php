<?
$this->load->model("Project_cost");
$reqId           = $this->input->get("reqId");
$projectCost         = new Project_cost();
$reqIds          = explode(',', $reqId);

$statement = '';

$projectCost->selectByParamsCetakPdf(array(), -1, -1, $statement);
?>
<H1 style="text-align: center;"> Project Cost Report </H1>
<table style="width: 100%;border-collapse: 1px solid black" border="1">
    <tr>
        <th>No </th>
        <th>No Project </th>
        <th>Vessel Name </th>
        <th>Type Of Vessel </th>
        <th>Type Of Service </th>
        <th>Date Service1 </th>
        <th>Date Service2 </th>
        <th>Destination </th>
        <th>Company Name </th>
        <th>Contact Person </th>
        <th>Kasbon </th>
        <th>Offer Price </th>
        <th>Real Price </th>
        <th>Surveyor </th>
    </tr>
    <?
    $no = 1;
    while ($projectCost->nextRow()) {
    ?>
        <tr>
            <td><?= $no ?> </td>
            <td><?= $projectCost->getField('NO_PROJECT') ?> </td>
            <td><?= $projectCost->getField('VESSEL_NAME') ?> </td>
            <td><?= $projectCost->getField('TYPE_OF_VESSEL') ?> </td>
            <td><?= $projectCost->getField('TYPE_OF_SERVICE') ?> </td>
            <td><?= $projectCost->getField('DATESERVICE1') ?> </td>
            <td><?= $projectCost->getField('DATESERVICE2') ?> </td>
            <td><?= $projectCost->getField('DESTINATION') ?> </td>
            <td><?= $projectCost->getField('COMPANY_NAME') ?> </td>
            <td><?= $projectCost->getField('CONTACT_PERSON') ?> </td>
            <td><?= $projectCost->getField('KASBON') ?> </td>
            <td><?= $projectCost->getField('OFFER_PRICE') ?> </td>
            <td><?= $projectCost->getField('REAL_PRICE') ?> </td>
            <td><?= $projectCost->getField('SURVEYOR') ?> </td>

        </tr>
    <?
        $no++;
    }
    ?>

</table>