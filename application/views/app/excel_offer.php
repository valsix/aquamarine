<?
$fileName = 'print_report.xls';
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Content-Type: application/vnd.ms-excel;");
$reqIds          = $this->input->get("reqIds");
$reqId           = explode(',', $reqIds);
$this->load->model("Offer");
$reqId           = $this->input->get("reqIds");
$offer         = new Offer();
$reqIds          = explode(',', $reqId);

// $statement='';
// for($i=0;$i<count($reqIds);$i++){
//     if(!empty($reqIds[$i])){
//         if($i==0){
//              $statement .= " AND A.COMPANY_ID=".$reqIds[$i];
//         }else{
//              $statement .= " OR A.COMPANY_ID=".$reqIds[$i];   
//         }   

//     }

// }
$reqCariNoOrder = $_SESSION[$pg."reqCariNoOrder"];
$reqCariDateofServiceFrom = $_SESSION[$pg."reqCariDateofServiceFrom"];
$reqCariDateofServiceTo = $_SESSION[$pg."reqCariDateofServiceTo"];
$reqCariCompanyName = $_SESSION[$pg."reqCariCompanyName"];
$reqCariPeriodeYear = $_SESSION[$pg."reqCariPeriodeYear"];
$reqCariVasselName = $_SESSION[$pg."reqCariVasselName"];
$reqCariProject = $_SESSION[$pg."reqCariProject"];
$reqCariGlobalSearch = $_SESSION[$pg."reqCariGlobalSearch"];
$reqCariStatus = $_SESSION[$pg."reqCariStatus"];
$reqDestination = $_SESSION[$pg."reqDestination"];

if (!empty($reqCariNoOrder)) {
            $statement_privacy .= " AND UPPER(A.NO_ORDER) LIKE '%" . strtoupper($reqCariNoOrder) . "' ";
        }
        // echo $reqCariDateofServiceFrom;exit;
        if (!empty($reqCariDateofServiceFrom) && !empty($reqCariDateofServiceTo)) {

            $statement_privacy .= " AND A.DATE_OF_SERVICE BETWEEN to_date('" . $reqCariDateofServiceTo . "', 'yyyy-MM-dd')  AND  to_date('" . $reqCariDateofServiceFrom . "', 'yyyy-MM-dd') ";
        }

        if (!empty($reqCariCompanyName)) {
            $statement_privacy .= " AND UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($reqCariCompanyName) . "%' ";
        }

        if (!empty($reqCariPeriodeYear) && $reqCariPeriodeYear !='' && $reqCariPeriodeYear !='ALL') {
            $statement_privacy .= " AND   TO_CHAR(A.DATE_OF_ORDER, 'yyyy') ='".$reqCariPeriodeYear."'";
        }

        if (!empty($reqCariVasselName)) {
            $statement_privacy .= " AND UPPER(A.VESSEL_NAME) LIKE '%" . strtoupper($reqCariVasselName) . "%' ";
        }

        if (!empty($reqCariProject)) {
            $statement_privacy .= "AND UPPER(A.SCOPE_OF_WORK) LIKE '%" . strtoupper($reqCariProject) . "%'  ";
        }
         if (!empty($reqDestination)) {
            $statement_privacy .= "AND UPPER(A.DESTINATION) LIKE '%" . strtoupper($reqDestination) . "%'  ";
        }

        if (!empty($reqCariGlobalSearch)) {
            $statement_privacy .= " AND (  UPPER(A.VESSEL_NAME) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' 
                                    OR UPPER(A.COMPANY_NAME) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' 
                                    OR UPPER(A.SCOPE_OF_WORK) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' 
                                
                                    OR UPPER(A.NO_ORDER) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' ";
            $statement_privacy .= " OR A.TOTAL_PRICE LIKE '%" . $reqCariGlobalSearch . "%' OR UPPER(A.CONTACT_PERSON) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%' OR UPPER(A.DESTINATION) LIKE '%" . strtoupper($reqCariGlobalSearch) . "%')  ";
        }

        // if (!empty($reqCariStatus) && $reqCariStatus !='ALL') {
        //     $statement_privacy .= "  AND UPPER(A.STATUS)  LIKE '%" . strtoupper($reqCariStatus) . "%'";
        // }


        if (is_numeric($reqCariStatus)) {
            if($reqCariStatus=='3'){
                    $statement_privacy .= "  AND A.STATUS  IS NULL";
                }else{
                    $statement_privacy .= "  AND A.STATUS  =".$reqCariStatus ;
                }
        }


$offer->selectByParamsPrint(array(), -1, -1, $statement_privacy);

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