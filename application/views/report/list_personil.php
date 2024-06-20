<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqCariCompanyName = $this->input->get('reqCariCompanyName');
$reqCariTypeofQualification = $this->input->get('reqCariTypeofQualification');
$reqTypeOfService = $this->input->get('reqTypeOfService');


if (!empty($reqCariCompanyName)) {
  $statement_privacy .= " AND A.NAME LIKE '%" . $reqCariCompanyName . "%'";
}
if (!empty($reqCariTypeofQualification)) {
  $statement_privacy .= " AND A.JENIS_ID='" . $reqCariTypeofQualification . "'";
}
if (!empty($reqTypeOfService)) {
  $reqTypeOfService =str_replace('-', ',', $reqTypeOfService);
  $statement_privacy .= "   AND A.DOCUMENT_ID IN (SELECT C.DOCUMENT_ID FROM DETIL_PERSONAL_CERTIFICATE C WHERE C.CERTIFICATE_ID IN (" . $reqTypeOfService . "))";
}


$this->load->model("DokumenKualifikasi");
$this->load->model("DokumenCertificate");
$this->load->model("DokumenSertifikat");

$this->load->model("Cabang");
$dokumen_kualifikasi = new DokumenKualifikasi();
$dokumen_kualifikasi->selectByParamsMonitoringPersonil(array(),-1,-1,$statement_privacy, " ORDER BY A.DOCUMENT_ID DESC");
// echo $dokumen_kualifikasi->query;exit;

$this->load->model('PersonalCertificate');
$certificate = new PersonalCertificate();
$certificate->selectByParamsMonitoring(array());
$arrDatas = array();
$no = 0;
while ($certificate->nextRow()) {
    $arrDatas[$no]['ID']     = $certificate->getField("CERTIFICATE_ID");
    $arrDatas[$no]['NAME']   = $certificate->getField("CERTIFICATE");
    $no++;
}
?>

<h1 style="font-family: Arial;font-size: 19px;text-align: center;"> LIST PERSONNEL </h1> 
<br>

<table style="width: 100%;font-size: 18px;font-family: Arial;border-collapse: 1px solid black;text-align: center;" border="1">
    <tr>
        <td rowspan="2" style="background-color: #BFBFBF;width: 3%"> NO </td>
        <td rowspan="2" style="background-color: #BFBFBF;width: 7%"> ID NUMBER </td>
        <td rowspan="2" style="background-color: #BFBFBF;width: 7%"> NAME</td>
        <td rowspan="2" style="background-color: #BFBFBF;width: 7%"> ID CARD </td>
        <td rowspan="2" style="background-color: #BFBFBF;width: 7%">POSITION </td>
        <td rowspan="2" style="background-color: #BFBFBF;width: 7%"> LOCATION</td>
        <td rowspan="2" style="background-color: #BFBFBF;width: 7%"> AGE <br> ( th ) </td>
        <td rowspan="2" style="background-color: #BFBFBF;width: 7%"> CONTACT</td>
        <td  colspan="<?=$no?>" style="background-color: #BFBFBF;width: 35%"> WORKING CERTIFICATE</td>
        <td rowspan="2" style="background-color: #BFBFBF;width: 7%"> WORK <BR>ON<BR> HEIGHT </td>
        <td rowspan="2" style="background-color: #BFBFBF;width: 7%">REMARK </td>
    </tr>
     
    <tr>
        <?
        for($jj=0;$jj<count($arrDatas);$jj++){
        ?>
      <td style="background-color: #BFBFBF;width: 7%"> <?=$arrDatas[$jj]['NAME']?></td>
      
      <?
        }
      ?>
    </tr>
    <?  
        

    $nomer=1;
        while ($dokumen_kualifikasi->nextRow()) {
           $reqId          = $dokumen_kualifikasi->getField("DOCUMENT_ID");
           $reqName        = $dokumen_kualifikasi->getField("NAME");
           $reqDescription = $dokumen_kualifikasi->getField("DESCRIPTION");
           $reqPath        = $dokumen_kualifikasi->getField("PATH");
           $reqAddress     = $dokumen_kualifikasi->getField("ADDRESS");
           $reqBirthDate   = $dokumen_kualifikasi->getField("BIRTH_DATE");
           $reqPhone       = $dokumen_kualifikasi->getField("PHONE");
           $reqPhone2      = $dokumen_kualifikasi->getField("PHONE2");
           $reqPosition    = $dokumen_kualifikasi->getField("POSITION");
           $reqPosition_nama    = $dokumen_kualifikasi->getField("POSITION_NAMA");
           $reqListCertificates    = $dokumen_kualifikasi->getField("LIST_CERTIFICATE");
           $reqIdNumber    = $dokumen_kualifikasi->getField("ID_NUMBER");
           $reqIdCard      = $dokumen_kualifikasi->getField("ID_CARD");
           $reqCabangId    = $dokumen_kualifikasi->getField("CABANG_ID");
           $reqCabangNama    = $dokumen_kualifikasi->getField("CABANG_NAMA");
           $reqRemarks    = $dokumen_kualifikasi->getField("REMARKS");


           $reqListCertificate = explode(',', $reqListCertificates);

           $reqDescription  = explode(',', $reqDescription);

          
           $cabang = new Cabang();
           $statement  = " AND CAST(A.CABANG_ID AS VARCHAR ) = '".$reqCabangId."'";
           $cabang->selectByParamsMonitoring(array(),-1,-1, $statement);
           $cabang->firstRow();
           $reqCabangNama    = $cabang->getField("NAMA");
           
     ?>
     <tr>
        <td><?=$nomer?> </td>
        <td> <?=$reqIdNumber?> </td>
        <td><?=$reqName?> </td>
        <td><?=$reqIdCard?> </td>
        <td><?=$reqPosition_nama?> </td>
        <td><?=$reqCabangNama?> </td>
        <td><?=hitung_umur_tahun($reqBirthDate)?> </td>
        <td><?=$reqPhone?> </td>
        <?
        $arrHigh = array();
        $jumlah_total =0;
        for($jj=0;$jj<count($arrDatas);$jj++){
          /*
                $ids='';
                for($kk=0;$kk<count($reqDescription);$kk++){
                    if($arrDatas[$jj]['ID']==$reqDescription[$kk]){
                        $ids =$reqListCertificate[$kk];
                        $jumlah_total++;
                    }
                }

                $dokumen_certificate = new DokumenCertificate();
                $statements = " AND CAST(A.DOCUMENT_ID AS VARCHAR) = '".$ids."'";
                $dokumen_certificate->selectByParams(array(),-1,-1,$statements);
                $dokumen_certificate->firstRow();
                $reqNames            = $dokumen_certificate->getField("NAME");
                $reqIssuedDates      = $dokumen_certificate->getField("ISSUED_DATE");
                $reqExpiredDates     = $dokumen_certificate->getField("EXPIRED_DATE");

               
                $exp_date = $dokumen_certificate->getField("DATES");
                array_push($arrHigh, $exp_date);

                $tgl_skrng = Date('d-m-Y');
              
                // echo $tgl_skrng.'-'.$exp_date;
                $datetime1 = date_create($tgl_skrng);
                $datetime2 = date_create($exp_date);
                $interval = date_diff($datetime1, $datetime2);
                $interval = $interval->format("%R%a");
                $point = substr($interval, 0,1);
                $style='';
                // if( $point!="+" || empty($ids)){
                //     $style='style="background-color:red"';
                // }
            */
            $dokumen_certificate = new DokumenSertifikat();
            $dokumen_certificate->selectByParams(array("A.DOKUMEN_ID" => $dokumen_kualifikasi->getField("DOCUMENT_ID"), "C.CERTIFICATE" => $arrDatas[$jj]["NAME"]));
            if($dokumen_certificate->rowCount > 0)
            {
              $jumlah_total++;
            }
            $dokumen_certificate->firstRow();
            $reqNames = $dokumen_certificate->getField("EXPIRED_DATE2");
            ?>
            <td <?=$style?>><?=$reqNames?> </td>

            <?

        }
        // rsort($arrHigh);
        $ket ='Data lengkap';
        if($jumlah_total!=count($arrDatas)){
             $ket ='Data tidak lengkap';
        }
        ?>
        <td><?=$arrHigh[0]?> </td>
        <td> <?=$reqRemarks ?></td>
     </tr> 


     <?    
     $nomer++; 
        }

    ?>
    
</table>

