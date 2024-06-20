<?
     $this->load->model("Company");
    $reqId           = $this->input->get("reqId");
    $company         = new Company();
    $reqIds          = explode(',', $reqId);

    $statement='';
    for($i=0;$i<count($reqIds);$i++){
        if(!empty($reqIds[$i])){
            if($i==0){
                 $statement .= " AND A.COMPANY_ID=".$reqIds[$i];
            }else{
                 $statement .= " OR A.COMPANY_ID=".$reqIds[$i];   
            }   

        }

    }
    $company->selectByParamsMonitoring(array(),-1,-1,$statement);
    ?>
    <H1 style="text-align: center;"> COSTUMER REPORT </H1>
    <table style="width: 100%;border-collapse: 1px solid black" border="1">
        <tr>
            <th width="30">NO </th>
            <th width="70">NAME </th>
            <th width="130">ADDRESS </th>
            <th>PHONE </th>
            <th>FAX </th>
            <th>EMAIL </th>
            <th>CP 1 </th>
            <th>CP 2 </th>
            <th>LA 1 </th>
            <th>LA 2 </th>
        </tr>
        <?
        $no=1;
        while ($company->nextRow()) {
        ?>
        <tr>
            <td><?=$no?> </td>
            <td><?=$company->getField('NAME')?> </td>
            <td><?=$company->getField('ADDRESS')?> </td>
            <td><?=$company->getField('PHONE')?> </td>
            <td><?=$company->getField('FAX')?> </td>
            <td><?=$company->getField('EMAIL')?> </td>
            <td><?=$company->getField('CP1_NAME')?> </td>
            <td><?=$company->getField('CP2_NAME')?> </td>
            <td><?=$company->getField('CP1_TELP')?> </td>
            <td><?=$company->getField('CP2_TELP')?> </td>

        </tr>   
        <?  
         $no++;
        }
        ?>

    </table>