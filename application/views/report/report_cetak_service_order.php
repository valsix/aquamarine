<?
$this->load->model("Service_order");
$reqId           = $this->input->get("reqId");
$offer         = new Service_order();
$reqIds          = explode(',', $reqId);

        $reqCariNoOrder          = $this->input->get('reqCariNoOrder');
        $reqCariPeriodeYearFrom  = $this->input->get('reqCariPeriodeYearFrom');
        $reqCariPeriodeYearTo    = $this->input->get('reqCariPeriodeYearTo');
        $reqCariCompanyName      = $this->input->get('reqCariCompanyName');
        $reqCariPeriodeYear      = $this->input->get('reqCariPeriodeYear');
        $reqCariVasselName       = $this->input->get('reqCariVasselName');
        $reqCariProject          = $this->input->get('reqCariProject');
        $reqCariGlobal           = $this->input->get('reqCariGlobal');    


         if(!empty($reqCariNoOrder)){
                $statement_privacy .= " AND  A.NO_ORDER LIKE '%".$reqCariNoOrder."%' ";
         } 
         if(!empty($reqCariPeriodeYearFrom) ||!empty($reqCariPeriodeYearTo) ){
                $statement_privacy .= " AND A.DATE_OF_SERVICE BETWEEN TO_CHAR('".$reqCariPeriodeYearFrom."','dd-mm-yyyy') AND  TO_CHAR('".$reqCariPeriodeYearTo."','dd-mm-yyyy')";      
         }   
         
         if(!empty($reqCariCompanyName)){
                $statement_privacy .= " AND  A.COMPANY_NAME LIKE '%".$reqCariCompanyName."%' ";
         }  
         if(!empty($reqCariPeriodeYear)){
                          $mtgl_awal = '01-01-'.$reqCariPeriodeYear ; 
                           $mtgl_akhir = '31-12-'.$reqCariPeriodeYear ; 
                           if($reqCariPeriodeYear != 'All Year'){
                                 $statement_privacy .= " AND A.DATE_OF_SERVICE BETWEEN TO_CHAR('".$mtgl_awal."','dd-mm-yyyy') AND  TO_CHAR('".$mtgl_akhir."','dd-mm-yyyy')";  
                           }
              
         }
         if(!empty($reqCariVasselName)){
                $statement_privacy .= " AND A.VESSEL_NAME LIKE '%".$reqCariVasselName."%' ";
         } 
          if(!empty($reqCariGlobal)){
                $statement_privacy .= " AND  A.SERVICE   '%".$reqCariGlobal."%' ";
         }   


$aColumns = array(
    "SO_ID", "NO_ORDER", "PROJECT_NAME", "COMPANY_NAME", "VESSEL_NAME", "VESSEL_TYPE", "SURVEYOR", "DESTINATION", "SERVICE", "DATE_OF_START", "DATE_OF_FINISH",
    "EQUIPMENT", "DATE_OF_SERVICE"
);

$offer->selectByParams(array(), -1, -1, $statement_privacy);
?>
<H1 style="text-align: center;"> OFFER REPORT </H1>
<table style="width: 100%;border-collapse: 1px solid black" border="1">
    <tr>
                    <th>No </th>
                    <?php
                    for ($i = 1; $i < count($aColumns); $i++) {
                    ?>
                        <th><?= str_replace('_', ' ', $aColumns[$i])  ?></th>
                    <?php

                    };
                    ?>
                </tr>
    <?
    $no = 1;
    while ($offer->nextRow()) {
    ?>
        <tr>
            <td><?= $no ?> </td>
           
            <?php
            for ($i = 1; $i < count($aColumns); $i++) {
                ?>
                <td><?= $offer->getField($aColumns[$i]) ?> </td>
                <?php

            };
            ?>

        </tr>
    <?
        $no++;
    }
    ?>

</table>