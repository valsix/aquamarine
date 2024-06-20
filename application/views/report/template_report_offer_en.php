 <base href="<?= base_url(); ?>" />

 <?
    include_once("functions/string.func.php");
    include_once("functions/date.func.php");

    $reqId = $this->input->get('reqId');

    $this->load->model('Offer');
    $offer = new Offer();
    // $reqId =$reqIds;

    $offer->selectByParamsMonitoring(array("OFFER_ID" => $reqId));
    $offer->firstRow();

    $reqId               = $offer->getField("OFFER_ID");
    $reqDocumentId       = $offer->getField("DOCUMENT_ID");
    $reqDocumentPerson   = $offer->getField("DOCUMENT_PERSON");
    $reqDestination      = $offer->getField("DESTINATION");
    $reqDateOfService    = $offer->getField("DATE_OF_SERVICE");
    $reqTypeOfService    = $offer->getField("TYPE_OF_SERVICE");
    $reqScopeOfWork      = $offer->getField("SCOPE_OF_WORK");
    $reqTermAndCondition = $offer->getField("TERM_AND_CONDITION");
    $reqPaymentMethod    = $offer->getField("PAYMENT_METHOD");
    $reqTotalPrice       = $offer->getField("TOTAL_PRICE");
    $reqTotalPriceWord   = $offer->getField("TOTAL_PRICE_WORD");
    $reqStatus           = $offer->getField("STATUS");
    $reqReason           = $offer->getField("REASON");
    $reqNoOrder          = $offer->getField("NO_ORDER");
    $reqDateOfOrder      = $offer->getField("DATE_OF_ORDER");
    $reqCompanyName      = $offer->getField("COMPANY_NAME");
    $reqAddress          = $offer->getField("ADDRESS");
    $reqFaximile         = $offer->getField("FAXIMILE");
    $reqEmail            = $offer->getField("EMAIL");
    $reqTelephone        = $offer->getField("TELEPHONE");
    $reqHp               = $offer->getField("HP");
    $reqVesselName       = $offer->getField("VESSEL_NAME");
    $reqTypeOfVessel     = $offer->getField("TYPE_OF_VESSEL");
    $reqClassOfVessel    = $offer->getField("CLASS_OF_VESSEL");
    $reqMaker            = $offer->getField("MAKER");

    ?>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 100px;">Ref No Order</td>
                     <td style="width: 25px;">:</td>
                     <td style="width: 100px;"><?= $reqNoOrder ?></td>
                     <td style="width: 175px;"></td>
                     <td style="width: 143px;"></td>
                     <td style="width: 145px;"></td>
                     <td style="width: 100px;">Sidoarjo,</td>
                     <td style="width: 25px;"></td>
                     <td style="width: 100px;"><?= $reqDateOfOrder ?></td>
                 </tr>
             </table>
         </div>
     </div>
 </center>

 <br>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 100px;"><small><strong> Dear. </strong></small></td>
                     <td style="width: 25px;"></td>
                     <td style="width: 100px;"></td>
                     <td style="width: 175px;"></td>
                     <td style="width: 25px;"></td>
                     <td style="width: 175px;"></td>
                     <td style="width: 100px;"></td>
                     <td style="width: 25px;"></td>
                     <td style="width: 100px;"></td>
                 </tr>
                 <tr>
                     <td style="width: 100px;"></td>
                     <td colspan="7"><small><strong> <?= $reqDocumentPerson ?> </strong></small></td>
                 </tr>

                 <tr>
                     <td style="width: 100px;"></td>
                     <td colspan="7"><small><strong> <?= $reqCompanyName ?> </strong></small></td>
                 </tr>

                 <tr>
                     <td style="width: 100px;"></td>
                     <td colspan="7"><small><strong> <?= $reqHp ?> </strong></small> </td>
                 </tr>

             </table>
         </div>
     </div>
 </center>

 <br>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 111px;">Sincerely,</td>
                     <td style="width: 25px;"></td>
                     <td style="width: 100px;"></td>
                     <td style="width: 175px;"></td>
                     <td style="width: 133px;"></td>
                     <td style="width: 175px;"></td>
                     <td style="width: 100px;"></td>
                     <td style="width: 25px;"></td>
                     <td style="width: 100px;"></td>
                 </tr>
             </table>
         </div>
     </div>
 </center>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 988px;" align="justify">Thank you for the trust of <strong> <?= $reqCompanyName ?> </strong> to use <strong> <?= $reqTypeOfService ?> </strong> work in our company, based on information by phone, we hereby offer prices according to the details below:</td>
                 </tr>
             </table>
         </div>
     </div>
 </center>

 <br>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 120px;">Name of Client</td>
                     <td style="width: 25px;">:</td>
                     <td style="width: 350px;"><strong> <?= $reqCompanyName ?> </strong></td>
                     <td style="width: 476px;"></td>
                 </tr>
                 <tr>
                     <td style="width: 120px;">Name Of Vessel</td>
                     <td style="width: 25px;">:</td>
                     <td style="width: 350px;"><strong> <?= $reqVesselName ?> </strong></td>
                     <td style="width: 476px;"></td>
                 </tr>
                 <tr>
                     <td style="width: 120px;">Type Of Vessel</td>
                     <td style="width: 25px;">:</td>
                     <td style="width: 350px;"><?= $reqTypeOfVessel ?></td>
                     <td style="width: 476px;"></td>
                 </tr>
                 <tr>
                     <td style="width: 120px;">Class</td>
                     <td style="width: 25px;">:</td>
                     <td style="width: 350px;"><?= $reqClassOfVessel ?></td>
                     <td style="width: 476px;"></td>
                 </tr>
                 <tr>
                     <td style="width: 120px;">Destination</td>
                     <td style="width: 25px;">:</td>
                     <td style="width: 350px;"><?= $reqDestination ?></td>
                     <td style="width: 476px;"></td>
                 </tr>
                 <tr>
                     <td style="width: 120px;">Date of Service</td>
                     <td style="width: 25px;">:</td>
                     <td style="width: 350px;">Tentative <?= $reqDateOfService ?></td>
                     <td style="width: 476px;"></td>
                 </tr>
                 <tr>
                     <td style="width: 120px;">Type of Service</td>
                     <td style="width: 25px;">:</td>
                     <td style="width: 350px;"><?= $reqTypeOfService ?></td>
                     <td style="width: 476px;"></td>
                 </tr>
                 <tr>
                     <td style="width: 120px;"><strong> Price </strong></td>
                     <td style="width: 25px;">:</td>
                     <?
                        $text = $reqTotalPrice;
                        $text = substr($text, 0, 4) . currencyToPage2(substr($text, 4));
                        ?>
                     <td style="width: 350px;"><?= $text ?> / <strong> Vessel </strong> (<?= $reqTotalPriceWord ?>)</td>
                     <td style="width: 476px;"></td>
                 </tr>
             </table>
         </div>
     </div>
 </center>

 <br>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 140px;"><strong> Scope of Work </strong></td>
                     <td style="width: 25px;">:</td>
                     <td style="width: 812px;"></td>
                 </tr>
             </table>
         </div>
     </div>
 </center>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 50px;"></td>
                     <td colspan="2"><small> <?= $reqScopeOfWork ?> </small></td>

                 </tr>
             </table>
         </div>
     </div>
 </center>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 140px;"><strong> Term & Conditions </strong></td>
                     <td style="width: 25px;">:</td>
                     <td style="width: 812px;"></td>
                 </tr>
             </table>
         </div>
     </div>
 </center>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 50px;"></td>
                     <td colspan="2"><small> <?= $reqTermAndCondition ?> </small></td>

                 </tr>
             </table>
         </div>
     </div>
 </center>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 140px;"><strong> Payment method </strong></td>
                     <td style="width: 25px;">:</td>
                     <td style="width: 812px;"></td>
                 </tr>
             </table>
         </div>
     </div>
 </center>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 50px;"></td>
                     <td colspan="2"><small> <?= $reqPaymentMethod ?> </small></td>
                 </tr>
             </table>
         </div>
     </div>
 </center>

 <br>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 988px;" align="justify">Thank you for your attention, we hope that there will be good cooperation between our company and <strong> <?= $reqCompanyName ?> </strong>, ask for a decision and to immediately send a <strong> Working Order (WO)</strong>.</td>
                 </tr>
             </table>
         </div>
     </div>
 </center>

 <br>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 140px;">Best Regards,</td>
                     <td style="width: 25px;"></td>
                     <td style="width: 812px;"></td>
                 </tr>
             </table>
         </div>
     </div>
 </center>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td></td>
                     <td style="width: 140px;"><img src="uploads/offering/<?= $reqId ?>/offering<?= $reqMaker ?>.png"></td>
                     <td style="width: 812px;"></td>
                 </tr>
             </table>
         </div>
     </div>
 </center>


 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 140px;"><?= $reqMaker ?></td>
                     <td style="width: 25px;"></td>
                     <td style="width: 812px;"></td>
                 </tr>
             </table>
         </div>
     </div>
 </center>

 <center>
     <div class="row">
         <div class="col">
             <table>
                 <tr>
                     <td style="width: 140px;">Marketing</td>
                     <td style="width: 25px;"></td>
                     <td style="width: 812px;"></td>
                 </tr>
             </table>
         </div>
     </div>
 </center>